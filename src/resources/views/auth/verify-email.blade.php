@extends('layouts.app')
@section('title','認証メールを送信しました')

@push('page_css')
<link rel="stylesheet" href="{{ asset('css/auth/verify.css') }}">
@endpush

@section('content')
<section class="verify-wrap">

  <p class="verify-text">
    登録していただいたメールアドレスに認証メールを送付しました。<br>
    メール認証を完了してください。
  </p>

  <div class="verify-actions">
    {{-- MailHog を新規タブで開く --}}
    <a href="http://localhost:8025/" target="_blank" rel="noopener" class="verify-btn">
      認証はこちらから
    </a>

    {{-- 再送 --}}
    <form method="POST" action="{{ route('verification.send') }}">
      @csrf
      <button type="submit" class="verify-resend">認証メールを再送する</button>
    </form>
  </div>

  @if (session('status') === 'verification-link-sent')
    <p class="verify-notice">再送しました。受信トレイを確認してください。</p>
  @endif
</section>
@endsection
