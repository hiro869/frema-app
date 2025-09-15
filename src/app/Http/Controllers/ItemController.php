<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    // PG01, PG02: 商品一覧
    public function index(Request $request)
    {
        // 一覧 or マイリストを出し分け（あとで実装）
        return view('items.index');
    }

    // PG05: 商品詳細
    public function show($id)
    {
        return view('items.show', compact('id'));
    }

    // PG08: 商品出品（表示）
    public function create()
    {
        return view('items.create');
    }

    // 出品処理（DB保存などは後で実装）
    public function store(Request $request)
    {
        return redirect()->route('items.index');
    }
}

