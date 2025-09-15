<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'coachtecフリマ')</title>

  {{-- 共通CSS --}}
  <link rel="stylesheet" href="{{ asset('css/common.css') }}?v=3">
  @stack('page_css')
</head>
<body>

  {{-- ▼ ロゴだけのヘッダー --}}
  @if (!View::hasSection('with_nav'))
  <header class="site-header header-simple">
    <div class="header-inner">
      <a class="logo" href="/">
        <img src="{{ asset('images/coachtech-logo.svg') }}" alt="COACHTECH">
      </a>
    </div>
  </header>
  @endif

  {{-- ▼ ナビ付きヘッダー --}}
  @yield('with_nav')

  <main class="site-main">
    @yield('content')
  </main>

</body>
</html>
