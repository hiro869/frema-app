<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MypageController extends Controller
{
    public function index()
    {
        return view('profile.index'); // resources/views/profile/index.blade.php を表示
    }

    public function edit()
    {
        return view('profile.edit'); // プロフィール編集
    }

    public function update(Request $request)
    {
        // 更新処理は後で実装
        return back()->with('status', 'プロフィールを更新しました');
    }
}
