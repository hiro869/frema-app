@extends('layouts.auth')   {{-- ★ロゴだけのレイアウトを使う --}}
@section('title','ログイン')

@push('page_css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endpush

@section('content')
<div class="auth-wrap">
  <h1 class="auth-title">ログイン</h1>


  <form method="POST" action="{{ route('login') }}" class="auth-form" novalidate>
    @csrf
    <label class="form-label">メールアドレス
      <input type="email" name="email" value="{{ old('email') }}">
      @error('email') <p class="field-error">{{ $message }}</p> @enderror
    </label>

    <label class="form-label">パスワード
      <input type="password" name="password">
      @error('password') <p class="field-error">{{ $message }}</p> @enderror
    </label>

    <button type="submit" class="btn-primary">ログインする</button>
  </form>

  <div class="auth-link">
    <a href="{{ route('register') }}">会員登録はこちら</a>
  </div>
</div>
@endsection
