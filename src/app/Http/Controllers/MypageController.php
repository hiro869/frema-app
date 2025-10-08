<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class MypageController extends Controller
{
    // /mypage （プロフィール＋タブ一覧）
    public function index(Request $request)
    {
        $user = Auth::user();
        $tab  = $request->query('tab', 'sold'); // sold | bought

        // 出品した商品
        $soldProducts = Product::withCount(['likers','comments'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(12, ['*'], 'sold_page');

        // 購入した商品（purchases.user_id が自分）
        $boughtProducts = Product::with('purchase')
            ->whereHas('purchase', fn($q) => $q->where('user_id', $user->id))
            ->latest()
            ->paginate(12, ['*'], 'bought_page');

        return view('profile.index', compact('user','tab','soldProducts','boughtProducts'));
    }

    // /mypage/profile （編集フォーム）
    public function edit()
    {
        $user = auth()->user();
        $isFirst = is_null($user->name) && is_null($user->zip) && is_null($user->address1);
        return view('profile.edit', compact('user', 'isFirst'));
    }

    // PATCH /mypage/profile （更新）
    public function update(Request $request)
    {
        $data = $request->validate([
            'name'      => ['required','string','max:255'],
            'zip'       => ['nullable','string','max:255'],
            'address1'  => ['nullable','string','max:255'],
            'address2'  => ['nullable','string','max:255'],
            'avatar' => ['nullable','image','max:5120'], // 5MB
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_path = $path;
        }
        $user->name     = $data['name'];
        $user->zip      = $data['zip']     ?? null;
        $user->address1 = $data['address1'] ?? null;
        $user->address2 = $data['address2'] ?? null;
        $user->save();

        return redirect()->route('profile.index')->with('status', 'プロフィールを更新しました。');
    }
}
