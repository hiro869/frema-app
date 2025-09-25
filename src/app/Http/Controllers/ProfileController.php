<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('mypage.profile',[
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        // 更新処理（仮）
        $user = Auth::user();

        $request->validate([
            'name' =>'required|string|max:255',
            'zip' => 'nullable|string|max:10',
            'address1' => 'nullable|string|max::255',
            'address2' => 'nullable|string|max:255',
        ]);
        $user->update($request->only('name', 'zip', 'address1', 'address2'));

        return redirect()->route('profile.edit')->with('status','プロフィールを更新しました');
    }
}
