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
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Laravel\Fortify\contracts\LoginResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * サービス登録
     */
    public function register(): void
    {
        $this->app->instance(
            \Laravel\Fortify\Contracts\RegisterResponse::class,
            new class implements \Laravel\Fortify\Contracts\RegisterResponse {
                public function toResponse($request)
                {
                    return redirect()->route('verification.notice');
                }
            }
        );
        $this->app->instance(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            new class implements \Laravel\Fortify\Contracts\LoginResponse {
                public function toResponse($request)
                {
                    $user = $request->user();
                    if ($user && method_exists($user, 'hasVerifiedEmail') && !$user->hasVerifiedEmail()) {
                        return redirect()->route('verification.notice');
                    }
                    return redirect()->route('items.index');
                }
            }
        );
    }
    /**
     * 起動処理
     */
    public function boot(): void
    {
        /** ===== Fortify の画面割り当て ===== */
        Fortify::loginView(fn () => view('auth.login'));
        Fortify::registerView(fn () => view('auth.register'));

        /** ===== 新規登録の作成クラスを指定 ===== */
        Fortify::createUsersUsing(CreateNewUser::class);

        /** ===== ログイン認証（FormRequest で検証） ===== */
        Fortify::authenticateUsing(function (Request $request) {
            // LoginRequest のルール／メッセージを使用して検証
            $form = app(LoginRequest::class);

            Validator::make(
                $request->all(),
                $form->rules(),
                $form->messages()
            )->validate();

            // 認証処理
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }

            // 失敗時（要件の文言）
            throw ValidationException::withMessages([
                'email' => 'ログイン情報が登録されていません',
            ]);
        });
    }
}
