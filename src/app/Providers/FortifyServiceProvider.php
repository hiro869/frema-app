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
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\RegisterResponse;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse {
            public function toResponse($request)
            {
                return redirect()->to('/mypage/profile');
            }
        });
    }

    public function boot(): void
    {
        /** ===== Fortify の画面割り当て ===== */
        Fortify::loginView(fn () => view('auth.login'));
        Fortify::registerView(fn () => view('auth.register'));

        /** ===== 新規登録の作成クラスを指定 ===== */
        Fortify::createUsersUsing(CreateNewUser::class);

        /** ===== ログイン認証（FormRequest で検証） ===== */
        Fortify::authenticateUsing(function (Request $request) {
            // LoginRequest のルール／メッセージをそのまま使う
            $form = app(LoginRequest::class);

            Validator::make(
                $request->all(),
                $form->rules(),
                $form->messages()
            )->validate(); // デフォルトの $errors バッグに入る

            // 認証
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user; // 認証成功
            }

            // 認証失敗（評価要件の文言）
            throw ValidationException::withMessages([
                'email' => 'ログイン情報が登録されていません',
            ]);
        });
    }
}
