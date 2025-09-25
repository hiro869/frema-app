<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * 商品一覧（トップ & マイリスト）
     * - /                … 全件（※自分の出品は除外）
     * - /?tab=mylist     … いいねした商品のみ（未ログインは空表示）
     * - keyword= 部分一致検索（商品名）
     */
    public function index(Request $request)
    {
        $tab     = $request->query('tab', 'all');
        $keyword = $request->query('keyword');

        $query = Product::query()
            ->withCount(['likers', 'comments'])   // likes_count, comments_count
            ->latest();

        // マイリスト（未ログインは空にする仕様）
        if ($tab === 'mylist') {
            if (auth()->check()) {
                $query->whereHas('likers', function ($likeQuery) {
                    $likeQuery->where('users.id', auth()->id());
                });
            } else {
                $products = collect(); // 空コレクション
                return view('items.index', compact('products', 'tab', 'keyword'));
            }
        } else {
            // 通常一覧は「自分が出品した商品を除外」
            if (auth()->check()) {
                $query->where('user_id', '!=', auth()->id());
            }
        }

        // 部分一致検索（商品名）
        if (filled($keyword)) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        $products = $query->paginate(12)->withQueryString();

        return view('items.index', compact('products', 'tab', 'keyword'));
    }

    /**
     * 商品詳細
     */
    public function show($id)
    {
        $product = Product::with(['likers', 'purchase'])->findOrFail($id);
        return view('items.show', compact('product'));
    }
}
