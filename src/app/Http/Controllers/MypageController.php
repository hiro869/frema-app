<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MypageController extends Controller
{
    /** マイページ（閲覧） /mypage */
    public function index(Request $request)
    {
        $user = auth()->user();
        $tab  = $request->query('page'); // 'buy' or 'sell' など拡張用

        // 必要に応じて購入/出品一覧を取得する処理を追記
        // $bought = $user->purchases()->with('product')->latest()->get();
        // $selling = $user->products()->latest()->get();

        return view('profile.index', compact('user', 'tab' /*, 'bought', 'selling'*/));
    }

    /** プロフィール編集表示 /mypage/profile (GET) */
    public function edit()
    {
        $user = auth()->user();

        // 初回設定かどうか（全て空なら true）
        $isFirst = empty($user->zip)
            && empty($user->address1)
            && empty($user->address2)
            && empty($user->avatar_path);

        return view('profile.edit', compact('user', 'isFirst'));
    }

    /** プロフィール更新 /mypage/profile (PATCH) */
    public function update(ProfileRequest $request)
    {
        $user = auth()->user();
        $data = $request->validated();

        // 画像が来ていたら差し替え
        if ($request->hasFile('avatar')) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $data['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }

        // フィールドを更新（未入力は既存値を保持）
        $user->fill([
            'name'        => $data['name']      ?? $user->name,
            'zip'         => $data['zip']       ?? null,
            'address1'    => $data['address1']  ?? null,
            'address2'    => $data['address2']  ?? null,
            'avatar_path' => $data['avatar_path'] ?? $user->avatar_path,
        ])->save();

        // 更新後は商品一覧トップへ
        return redirect()->route('items.index')->with('status', 'プロフィールを更新しました。');
    }
}
