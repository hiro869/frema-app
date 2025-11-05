<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 名前が未入力だと_お名前を入力してください_が表示される()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'name' => 'お名前を入力してください',
        ]);
    }
    /** @test */
public function メールが未入力だと_メールアドレスを入力してください_が表示される()
{
    $response = $this->post('/register', [
        'name' => 'テスト太郎',
        'email' => '',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertSessionHasErrors([
        'email' => 'メールアドレスを入力してください',
    ]);
}

/** @test */
public function パスワードが未入力だと_パスワードを入力してください_が表示される()
{
    $response = $this->post('/register', [
        'name' => 'テスト太郎',
        'email' => 'test@example.com',
        'password' => '',
        'password_confirmation' => '',
    ]);

    $response->assertSessionHasErrors([
        'password' => 'パスワードを入力してください',
    ]);
}

/** @test */
public function パスワードが7文字以下だと_パスワードは8文字以上で入力してください_が表示される()
{
    $response = $this->post('/register', [
        'name' => 'テスト太郎',
        'email' => 'test@example.com',
        'password' => 'short7',
        'password_confirmation' => 'short7',
    ]);

    $response->assertSessionHasErrors([
        'password' => 'パスワードは8文字以上で入力してください',
    ]);
}

/** @test */
public function 確認用パスワードと一致しないと_パスワードと一致しません_が表示される()
{
    $response = $this->post('/register', [
        'name' => 'テスト太郎',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'different',
    ]);

    $response->assertSessionHasErrors([
        'password' => 'パスワードと一致しません',
    ]);
}

/** @test */
public function 正しい情報ならメール認証画面に遷移する()
{
    $response = $this->post('/register', [
        'name' => 'テスト太郎',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect('/email/verify');
}

}
