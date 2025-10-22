<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MypageController extends Controller
{
    /**
     * マイページ（タブ切替）
     * /mypage?page=sell  … 出品した商品
     * /mypage?page=buy   … 購入した商品
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $page = $request->query('page', 'sell');

        // 出品した商品（自分が出品者）
        $soldProducts = Product::withCount(['likers','comments'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(12, ['*'], 'sold_page');

        // 購入した商品（purchases.user_id が自分）
        $boughtProducts = Product::with('purchase')
            ->whereHas('purchase', fn ($q) => $q->where('user_id', $user->id))
            ->latest()
            ->paginate(12, ['*'], 'bought_page');

        // アバターURL（無ければデフォルト画像）
        $avatarUrl = $user->avatar_path
            ? Storage::url($user->avatar_path)
            : asset('images/avatar-default.png');

        return view('profile.index', compact('user', 'page', 'soldProducts', 'boughtProducts', 'avatarUrl'));
    }

    /**
     * プロフィール編集画面
     */
    public function edit()
    {
        $user = Auth::user();
        $isFirst = is_null($user->name) && is_null($user->zip) && is_null($user->address1);

        $avatarUrl = $user->avatar_path
            ? Storage::url($user->avatar_path)
            : asset('images/avatar-default.png');

        return view('profile.edit', compact('user', 'isFirst', 'avatarUrl'));
    }

    /**
     * プロフィール更新
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'zip'      => ['nullable','string','max:255'],
            'address1' => ['nullable','string','max:255'],
            'address2' => ['nullable','string','max:255'],
            'avatar'   => ['nullable','image','max:5120'], // 5MB
        ], [
            'name.required' => 'ユーザー名を入力してください',
            'avatar.image'  => '画像ファイルを選択してください',
            'avatar.max'    => '画像サイズは5MB以下にしてください',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // アバターを更新（新規アップロード時のみ）
        if ($request->hasFile('avatar')) {
            // 既存ファイルがあれば削除（任意）
            if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_path = $path;
        }

        // そのほかの項目
        $user->name     = $data['name'];
        $user->zip      = $data['zip']      ?? null;
        $user->address1 = $data['address1'] ?? null;
        $user->address2 = $data['address2'] ?? null;
        $user->save();

        return redirect()->route('profile.index')->with('status', 'プロフィールを更新しました。');
    }
}
