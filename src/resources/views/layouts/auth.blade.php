<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>@yield('title', 'COACHTECH')</title>
  <link rel="stylesheet" href="{{ asset('css/layout.css') }}?v=1">
  @stack('page_css')
</head>
<body>
<header class="site-header">
  <div class="header-inner">
    <a href="{{ url('/') }}" class="logo">
      <img src="{{ asset('images/coachtech-logo.svg') }}" alt="COACHTECH">
    </a>
    {{-- 検索/ナビは出さない --}}
  </div>
</header>

<main class="site-main">@yield('content')</main>
@stack('page_js')
</body>
</html>
