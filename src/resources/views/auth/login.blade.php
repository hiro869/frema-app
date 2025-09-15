@extends('layouts.app')
@section('title','ログイン')

@push('page_css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}?v=15">
@endpush

@section('content')
<div class="auth-wrap">
  <h1 class="auth-title">ログイン</h1>

  {{-- 全体エラー（login バッグ） --}}
  @if ($errors->login->any())
    <ul class="auth-errors">
      @foreach ($errors->login->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  @endif

  <form method="POST" action={{ route('login')}} class="auth-form" novalidate>
    @csrf

    {{-- メールアドレス --}}
    <label class="form-label">メールアドレス
      <input type="email" name="email" value="{{ old('email') }}">
      @error('email','login')
        <p class="field-error">{{ $message }}</p>
      @enderror
    </label>

    {{-- パスワード --}}
    <label class="form-label">パスワード
      <input type="password" name="password">
      @error('password','login')
        <p class="field-error">{{ $message }}</p>
      @enderror
    </label>

    <button type="submit" class="btn-primary">ログインする</button>
  </form>

  <div class="auth-link">
    <a href="{{ route('register') }}">会員登録はこちら</a>
  </div>
</div>
@endsection
