<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // 登録完了後のリダイレクト
        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse {
            public function toResponse($request)
            {
                return redirect()->to('/mypage/profile');
            }
        });
    }

    public function boot(): void
    {
        // ビュー割り当て
        Fortify::loginView(fn () => view('auth.login'));
        Fortify::registerView(fn () => view('auth.register'));

        // 新規登録処理クラス
        Fortify::createUsersUsing(CreateNewUser::class);

        // ログイン認証
        Fortify::authenticateUsing(function (Request $request) {
            // ① LoginRequestでバリデーション
            $formRequest = app(LoginRequest::class);

            Validator::make(
                $request->all(),
                $formRequest->rules(),
                $formRequest->messages()
            )->validateWithBag('login');   // loginバッグに保存

            // ② 認証処理
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }

            // ③ 認証失敗時
            throw ValidationException::withMessages([
                'email' => 'ログイン情報が登録されていません',
            ])->errorBag('login');
        });
    }
}
