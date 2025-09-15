<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // PG09, PG11, PG12: マイページ（通常/購入一覧/出品一覧）
    public function index(Request $request)
    {
        $page = $request->query('page'); // null, buy, sell
        return view('profile.index', compact('page'));
    }

    // PG10: プロフィール編集
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        // 更新処理（仮）
        return redirect()->route('profile.index');
    }
}
