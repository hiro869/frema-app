@extends('layouts.app')

@section('title','商品一覧')

@push('page_css')
  <link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endpush

@section('content')
  <section class="items-page">

    {{-- タブ（おすすめ/マイリスト） --}}
    <div class="tabs">
      <a
        href="{{ url('/').($keyword ? '?keyword='.urlencode($keyword) : '') }}"
        class="tab {{ $tab === 'all' ? 'is-active' : '' }}"
      >おすすめ</a>

      <a
        href="{{ url('/').'?tab=mylist'.($keyword ? '&keyword='.urlencode($keyword) : '') }}"
        class="tab {{ $tab === 'mylist' ? 'is-active' : '' }}"
      >マイリスト</a>
    </div>

    {{-- グリッド --}}
    @if($products->isEmpty())
      <p class="empty">表示できる商品がありません。</p>
    @else
      <ul class="grid">
        @foreach($products as $product)
          <li class="card">
            <a href="{{ url('/item/'.$product->id) }}" class="card-link">
              <div class="thumb">
                <img src="{{ $product->image_path }}" alt="{{ $product->name }}">
                @if($product->sold_at)
                  <span class="badge-sold">Sold</span>
                @endif
              </div>
              <div class="meta">
                <p class="name">{{ $product->name }}</p>
                <p class="price">¥{{ number_format($product->price) }}</p>
              </div>
            </a>
          </li>
        @endforeach
      </ul>

      <div class="pager">
        {{ $products->links() }}
      </div>
    @endif
  </section>
@endsection
