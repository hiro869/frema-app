<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

        // ログイン認証（FormRequest + カスタムメッセージ）
        Fortify::authenticateUsing(function (Request $request) {
            // --- バリデーション ---
            $formRequest = app(LoginRequest::class);
            $validator = validator(
                $request->all(),
                $formRequest->rules(),
                $formRequest->messages()
            );
            $validator->validate();

            // --- 認証処理 ---
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }

            // --- 失敗メッセージ（要件通り） ---
            throw ValidationException::withMessages([
                'email' => 'ログイン情報が登録されていません',
            ]);
        });
    }
}
