<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfProfileIncomplete
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $u = Auth::user();
            // ここで「プロフィールが完成済みか」を判定
            $completed = filled($u->name) && filled($u->zip) && filled($u->address1);

            // 未完成ならプロフィール編集へ（編集ページ自身は除外）
            if (! $completed && ! $request->is('mypage/profile*')) {
                return redirect()->route('profile.edit')->with('first_login', true);
            }
        }
        return $next($request);
    }
}
