@extends('layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('css/items/show.css') }}?v={{ filemtime(public_path('css/items/show.css')) }}">
@endpush

@section('content')
<div class="detail-container">

  {{-- 2カラム：左=画像 / 右=詳細 --}}
  <div class="detail-grid">
    {{-- 左：サムネイル --}}
    <div class="thumb">
      <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
      @if($product->is_sold)
        <span class="badge-sold">SOLD</span>
      @endif
    </div>

    {{-- 右：テキストパネル --}}
    <div class="panel">
      <h1 class="title">{{ $product->name }}</h1>

      @if($product->brand)
        <div class="brand">{{ $product->brand }}</div>
      @endif

      <div class="price-line">
        <span class="price">¥{{ number_format($product->price) }}</span>
        <span class="tax">(税込)</span>
      </div>

      {{-- いいね／コメント数（> は出さない） --}}
      <div class="socials">
        {{-- いいねボタン --}}
        @auth
          @if($likedByMe)
            {{-- 解除（赤い星） --}}
            <form method="POST" action="{{ route('items.unlike', $product) }}" class="inline">
              @csrf
              @method('DELETE')
              <button class="icon-btn liked" title="いいね解除">
                <svg viewBox="0 0 24 24" class="ico"><path d="M12 17.3l-6.2 3.7 1.6-7L2 9.2l7.2-.6L12 2l2.8 6.6 7.2.6-5.5 4.7 1.6 7z"/></svg>
                <span>{{ $product->likers_count }}</span>
              </button>
            </form>
          @else
            {{-- 付与（白い星） --}}
            <form method="POST" action="{{ route('items.like', $product) }}" class="inline">
              @csrf
              <button class="icon-btn" title="いいね">
                <svg viewBox="0 0 24 24" class="ico"><path d="M12 17.3l-6.2 3.7 1.6-7L2 9.2l7.2-.6L12 2l2.8 6.6 7.2.6-5.5 4.7 1.6 7z"/></svg>
                <span>{{ $product->likers_count }}</span>
              </button>
            </form>
          @endif
        @else
          {{-- 未ログイン時は表示のみ --}}
          <div class="icon-btn" title="ログインでいいね可能">
            <svg viewBox="0 0 24 24" class="ico"><path d="M12 17.3l-6.2 3.7 1.6-7L2 9.2l7.2-.6L12 2l2.8 6.6 7.2.6-5.5 4.7 1.6 7z"/></svg>
            <span>{{ $product->likers_count }}</span>
          </div>
        @endauth

        {{-- コメント数（表示のみ） --}}
        <div class="icon-btn" title="コメント">
          <svg viewBox="0 0 24 24" class="ico"><path d="M20 2H4a2 2 0 0 0-2 2v14l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z"/></svg>
          <span>{{ $product->comments_count }}</span>
        </div>
      </div>

      {{-- 購入ボタン：購入確認画面へ遷移 --}}
      <form method="GET" action="{{ route('purchase.index', $product) }}">
        <button class="btn-buy" {{ $product->is_sold ? 'disabled' : '' }}>
          {{ $product->is_sold ? '売り切れ' : '購入手続きへ' }}
        </button>
      </form>

      {{-- 商品説明 --}}
      <h2 class="sec">商品説明</h2>
      <div class="desc">{{ $product->description ?: '—' }}</div>

      {{-- 商品の情報 --}}
      <h2 class="sec">商品の情報</h2>
      <div class="info-rows">
        <div class="row">
          <span class="label">カテゴリー</span>
          <span class="vals">
            @forelse($product->categories as $c)
              <span class="pill">{{ $c->name }}</span>
            @empty
              —
            @endforelse
          </span>
        </div>
        <div class="row">
          <span class="label">商品の状態</span>
          <span class="vals">
            @switch($product->condition)
              @case('good') 良好 @break
              @case('no_obvious_damage') 目立った傷や汚れなし @break
              @case('some_damage') やや傷や汚れあり @break
              @case('bad') 状態が悪い @break
              @default —
            @endswitch
          </span>
        </div>
      </div>

      {{-- コメント一覧＋投稿 --}}
      <div class="comments">
        <h2 class="sec">コメント（{{ $product->comments_count }}）</h2>

        <ul class="comment-list">
          @forelse($product->comments as $comment)
            <li class="comment">
              <img class="avatar" src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}">
              <div class="comment-body">
                <div class="head">
                  <span class="name">{{ $comment->user->name }}</span>
                </div>
                <p>{{ $comment->body }}</p>
              </div>
            </li>
          @empty
            <li class="comment empty">こちらにコメントが入ります。</li>
          @endforelse
        </ul>
        @auth
          <form method="POST" action="{{ route('comments.store', $product) }}" class="comment-form" novalidate>
            @csrf
            <label class="sec sub">商品へのコメント</label>
            <textarea name="body" rows="9" >{{ old('body') }}</textarea>
            @error('body') <p class="err">{{ $message }}</p> @enderror
            <button class="btn-comment">コメントを送信する</button>
          </form>
        @else
        <div class="comment-form disabled">
        <textarea rows="9" disabled></textarea>
        <button class="btn-comment" disabled>コメントを送信する</button>
        </div>
        @endauth
      </div>
    </div>
  </div>
</div>
@endsection
