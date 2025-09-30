<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\ExhibitionRequest;   // ← 追加
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /** 一覧 */
    public function index(\Illuminate\Http\Request $request)
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

    /** 詳細（カテゴリは belongsTo 前提） */
    public function show($id)
    {
        $product = Product::with([
            'category',        // ← 多対多の 'categories' ではなく単数
            'likers',
            'purchase',
            'comments.user',
        ])->withCount(['likers','comments'])->findOrFail($id);

        return view('items.show', compact('product'));
    }

    /** 出品フォーム */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('items.create', compact('categories'));
    }

    /** 出品保存（ExhibitionRequest で検証） */
    public function store(ExhibitionRequest $request)
    {
        $validated = $request->validated();

        // 画像保存
        $path = $request->file('image')->store('products', 'public');

        // 商品作成（カテゴリは外部キー）
        $product = Product::create([
            'user_id'     => Auth::id(),
            'category_id' => $validated['category_id'],   // ← 単一カテゴリ
            'name'        => $validated['name'],
            'brand'       => $validated['brand'] ?? null,
            'description' => $validated['description'],
            'price'       => $validated['price'],
            'condition'   => $validated['condition'],
            'image_path'  => $path,
        ]);

        return redirect()->route('items.create')->with('status', '出品しました。');
    }
}
