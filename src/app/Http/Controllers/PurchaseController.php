<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    // PG06: 購入画面
    public function index($id)
    {
        return view('purchase.index', compact('id'));
    }

    public function store(Request $request, $id)
    {
        // 購入処理（仮でリダイレクト）
        return redirect()->route('profile.index');
    }

    // PG07: 送付先住所変更
    public function address($id)
    {
        return view('purchase.address', compact('id'));
    }

    public function updateAddress(Request $request, $id)
    {
        return redirect()->route('purchase.index', $id);
    }
}
