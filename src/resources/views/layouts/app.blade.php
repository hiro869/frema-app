<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>@yield('title', 'COACHTECH')</title>

  {{-- 共通レイアウトCSS --}}
  <link rel="stylesheet" href="{{ asset('css/layout.css') }}?v=1" />

  {{-- ページ専用CSS --}}
  @stack('page_css')
</head>
<body class="@yield('body_class')">

@php
  // 子ビューで  @section('simple_header', true) と書けばロゴだけのヘッダーになる
  $simpleHeader = trim((string) View::getSection('simple_header')) === 'true';
@endphp

<header class="site-header {{ $simpleHeader ? 'site-header--simple' : '' }}">
  <div class="header-inner">

    {{-- 左：ロゴ --}}
    <a href="{{ url('/') }}" class="logo">
      <img src="{{ asset('images/coachtech-logo.svg') }}" alt="COACHTECH">
    </a>

    @unless($simpleHeader)
      {{-- 中央：検索フォーム --}}
      <form action="{{ route('items.index') }}" method="GET" class="search">
        <input
          type="text"
          name="keyword"
          value="{{ request('keyword') }}"
          placeholder="なにをお探しですか？"
        >
        <button type="submit" class="search-btn">検索</button>
      </form>

      {{-- 右：ナビ（固定3つだけ） --}}
      <nav class="global-nav">
        <form method="POST" action="{{ route('logout') }}" class="nav-inline-form">
          @csrf
          <button type="submit" class="nav-link">ログアウト</button>
        </form>
        <a class="nav-link" href="{{ route('mypage.index') }}">マイページ</a>
        <a class="btn-primary" href="{{ route('items.create') }}">出品</a>
      </nav>
    @endunless

  </div>
</header>

<main class="site-main">
  @yield('content')
</main>

@stack('page_js')
</body>
</html>
