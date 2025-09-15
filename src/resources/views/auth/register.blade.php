@extends('layouts.app')

@section('title', '会員登録')

@push('page_css')
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endpush


@section('content')
  <section class="auth-section">
    <h1 class="auth-title">会員登録</h1>

    <form method="POST" action="{{ route('register') }}" class="auth-form">
      @csrf

      {{-- ユーザー名 --}}
      <div class="form-group">
        <label for="name">ユーザー名</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}">
        @error('name')
          <p class="error">{{ $message }}</p>
        @enderror
      </div>

      {{-- メールアドレス --}}
      <div class="form-group">
        <label for="email">メールアドレス</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}">
        @error('email')
          <p class="error">{{ $message }}</p>
        @enderror
      </div>

      {{-- パスワード --}}
      <div class="form-group">
        <label for="password">パスワード</label>
        <input id="password" type="password" name="password">
        @error('password')
          <p class="error">{{ $message }}</p>
        @enderror
      </div>

      {{-- 確認用パスワード --}}
      <div class="form-group">
        <label for="password_confirmation">確認用パスワード</label>
        <input id="password_confirmation" type="password" name="password_confirmation">
        @error('password_confirmation')
          <p class="error">{{ $message }}</p>
        @enderror
      </div>

      <button type="submit" class="btn-submit">登録する</button>

      <p class="auth-switch">
        <a class="auth-login" href="{{ route('login') }}">ログインはこちら</a>
      </p>
    </form>
  </section>
@endsection
