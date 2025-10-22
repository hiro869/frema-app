<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExhibitionRequest;

class ItemController extends Controller
{
    /** 一覧 */
    public function index(Request $request)
    {
        $tab     = $request->query('tab', 'all');
        $keyword = $request->query('keyword');

        $query = Product::query()
            ->withCount(['likers', 'comments'])
            ->latest();

        if ($tab === 'mylist') {
            if (Auth::check()) {
                $query->whereHas('likers', fn($q) => $q->whereKey(Auth::id()));
            } else {
                $products = collect();
                return view('items.index', compact('products','tab','keyword'));
            }
        } else {
            if (Auth::check()) {
                $query->where('user_id', '!=', Auth::id());
            }
        }

        if (filled($keyword)) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        $products = $query->paginate(12)->withQueryString();
        return view('items.index', compact('products','tab','keyword'));
    }

    /** 詳細（カテゴリは多対多） */
    public function show($id)
    {
        $product = Product::with([
            'categories',       // ← 多対多
            'likers',
            'purchase',
            'comments' => function ($commentQuery) {
                $commentQuery->latest();
            },
            'comments.user',
        ])->withCount(['likers','comments'])->findOrFail($id);

        $likedByMe = auth()->check()
            ? $product->likers()->whereKey(auth()->id())->exists()
            : false;

        return view('items.show', compact('product','likedByMe'));
    }

    /** 出品フォーム */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('items.create', compact('categories'));
    }

    /** 出品保存（多対多・ファイル保存） */
    public function store(\App\Http\Requests\ExhibitionRequest $request)
    {
        // ① ExhibitionRequest で検証済みのデータだけを取得
        $data = $request->validated();

        // ② 画像を保存（storage/app/public/product_images）
        $path = $request->file('image')->store('product_images', 'public');

        // ③ 商品を作成
        $product = \App\Models\Product::create([
            'user_id'     => \Illuminate\Support\Facades\Auth::id(),
            'name'        => $data['name'],
            'brand'       => $data['brand'] ?? null,
            'price'       => $data['price'],
            'description' => $data['description'] ?? null,
            'condition'   => $data['condition'],
            'image_path'  => $path,
        ]);

        // ④ カテゴリ紐付け（中間テーブル）
        $product->categories()->sync($data['category_ids']);

        // ⑤ 成功時は詳細へ
        return redirect()->route('items.show', $product)
                         ->with('status', '出品しました');
    }
}
