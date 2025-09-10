{{-- resources/views/auth/register.blade.php --}}
<h1>会員登録</h1>

<form method="POST" action="{{ route('register') }}" novalidate>
    @csrf

    <div>
        <label>お名前</label>
        <input type="text" name="name" value="{{ old('name') }}">
        @error('name')
            <div style="color:red;">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label>メールアドレス</label>
        <input type="email" name="email" value="{{ old('email') }}">
        @error('email')
            <div style="color:red;">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label>パスワード</label>
        <input type="password" name="password">
        @error('password')
            <div style="color:red;">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label>確認用パスワード</label>
        <input type="password" name="password_confirmation">
    </div>

    <button type="submit">登録</button>
</form>

<p><a href="{{ route('login') }}">ログイン画面へ</a></p>
