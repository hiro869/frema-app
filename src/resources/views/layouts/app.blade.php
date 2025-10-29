<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'COACHTECH')</title>
  <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
  @stack('page_css')
</head>
<body>
  <header class="site-header">
    <div class="header-inner">
      {{-- ロゴ --}}
      <a href="{{ url('/') }}" class="logo">
        <img src="{{ asset('images/coachtech-logo.svg') }}" alt="COACHTECH">
      </a>

      {{-- 検索フォーム --}}
      {{-- 元の正しい検索フォーム --}}
<form action="{{ url('/') }}" method="GET" class="search-form">
  <input
    type="text"
    name="keyword"
    value="{{ request('keyword') }}"
    placeholder="なにをお探しですか？"
  >
</form>

      {{-- ナビゲーション --}}
      <nav class="global-nav">
        @auth
          <form method="POST" action="{{ route('logout') }}" class="nav-inline-form">
            @csrf
            <button type="submit" class="nav-link">ログアウト</button>
          </form>
          <a class="nav-link" href="{{ route('profile.index') }}">マイページ</a>
          <a class="btn-primary" href="{{ route('items.create') }}">出品</a>
        @else
          <a class="nav-link" href="{{ route('login') }}">ログイン</a>
          <a class="nav-link" href="{{ route('register') }}">マイページ</a>
          <a class="btn-primary" href="{{ route('items.create') }}">出品</a>
        @endauth
      </nav>
    </div>
  </header>

  <main class="site-main">
    @yield('content')
  </main>

  @stack('page_js')
</body>
</html>
