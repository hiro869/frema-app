@extends('layouts.app')
@section('title','マイページ')

@push('page_css')
<link rel="stylesheet" href="{{ asset('css/profile/index.css') }}">
@endpush

@section('content')
@php
    use Illuminate\Support\Facades\Storage;

    $raw = (string) ($user->avatar_path ?? '');
    $path = ltrim(str_replace(['storage/', 'public/'], '', $raw), '/');

    if ($path !== '' && Storage::disk('public')->exists($path)) {
        $avatarUrl = asset('storage/' . $path);
    } else {
        $avatarUrl = asset('images/notimage-gray.png'); // ← 同じ画像を使用！
    }
@endphp

<div class="profile-container">

  {{-- 上部：左に丸画像、中央にユーザー名、右に赤枠ボタン（1枚目の見本） --}}
  <div class="profile-head">
    <div class="head-left">
      <img class="avatar" src="{{ $avatarUrl }}" alt="">
      <h2 class="user-name">{{ $user->name }}</h2>
    </div>
    <a href="{{ route('profile.edit') }}" class="btn-outline">プロフィールを編集</a>
  </div>

  <div class="tabs">
    <a href="{{ route('profile.index',['page'=>'sell']) }}" class="tab {{ $page==='sell'?'is-active':'' }}">出品した商品</a>
    <a href="{{ route('profile.index',['page'=>'buy']) }}" class="tab {{ $page==='buy'?'is-active':'' }}">購入した商品</a>
  </div>

  @php $list = $page==='sell' ? $soldProducts : $boughtProducts; @endphp
  <div class="grid">
    @forelse($list as $product)
      <a href="{{ route('items.show',$product) }}" class="card">
        <div class="thumb"><img src="{{ $product->image_url }}" alt="{{ $product->name }}"></div>
        <div class="body">
          <div class="title">{{ $product->name }}</div>
        </div>
      </a>
    @empty
      <p class="empty">{{ $page==='sold'?'出品した商品がありません':'購入した商品がありません' }}。</p>
    @endforelse
  </div>

  <div class="pager">{{ $list->appends(['page'=>$page])->links() }}</div>
</div>
@endsection
