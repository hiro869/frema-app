<h1>ログイン</h1>

@if ($errors->any())
  <ul style="color:red;">
    @foreach ($errors->all() as $e)
      <li>{{ $e }}</li>
    @endforeach
  </ul>
@endif

@if (session('status'))
  <div>{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('login') }}" novalidate>
  @csrf

  <div>
    <label>メールアドレス</label>
    <input type="email" name="email" value="{{ old('email') }}">
    @error('email') <div style="color:red;">{{ $message }}</div> @enderror
  </div>

  <div>
    <label>パスワード</label>
    <input type="password" name="password">
    @error('password') <div style="color:red;">{{ $message }}</div> @enderror
  </div>

  <button type="submit">ログイン</button>
</form>

<p><a href="{{ route('register') }}">会員登録へ</a></p>
