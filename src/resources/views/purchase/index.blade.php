@extends('layouts.app')

@push('page_css')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/purchase/index.css') }}">
@endpush

@section('content')
<div class="purchase-container">
  <div class="purchase-grid">
    {{-- 左カラム --}}
    <main class="purchase-main">
      <div class="product-row">
        <img class="thumb" src="{{ $product->image_url }}" alt="{{ $product->name }}">
        <div class="meta">
          <h1 class="name">商品名</h1>
          <div class="price">¥ {{ number_format($product->price) }}</div>
        </div>
      </div>

      <hr class="divider">

      <form action="{{ route('purchase.store', $product) }}" method="POST" class="purchase-form">
        @csrf

        <section class="field">
          <label class="label">支払い方法</label>
          <select name="pay_method" id="payMethod" class="select" required>
            <option value="">選択してください</option>
            <option value="card"        {{ old('pay_method')==='card' ? 'selected' : '' }}>クレジットカード</option>
            <option value="convenience" {{ old('pay_method')==='convenience' ? 'selected' : '' }}>コンビニ払い</option>
          </select>
          @error('pay_method') <p class="error">{{ $message }}</p> @enderror
        </section>

        <hr class="divider">

        <section class="ship">
          <div class="ship-head">
            <span class="label">配送先</span>
            <a class="link-change" href="{{ route('purchase.address', $product) }}">変更する</a>
          </div>
          <address class="addr">
            〒 {{ $ship['zip'] }}<br>
            {{ $ship['address1'] }}<br>
            {{ $ship['address2'] }}
          </address>
        </section>

        @error('ship') <p class="error">{{ $message }}</p>@enderror

        <hr class="divider">
      </form>
    </main>

    {{-- 右カラム --}}
    <aside class="purchase-aside">
      <div class="summary-card">
        <div class="row">
          <span class="row-label">商品代金</span>
          <strong class="row-value">¥ {{ number_format($product->price) }}</strong>
        </div>
        <div class="row">
          <span class="row-label">支払い方法</span>
          <span class="row-value" id="summaryMethod">選択してください</span>
        </div>
      </div>

      <form action="{{ route('purchase.store', $product) }}" method="POST">
        @csrf
        <input type="hidden" name="pay_method" id="hiddenPayMethod">
        <button type="submit" class="btn-buy">購入する</button>
      </form>
    </aside>
  </div>
</div>

<script>
  const sel = document.getElementById('payMethod');
  const out = document.getElementById('summaryMethod');
  const hid = document.getElementById('hiddenPayMethod');
  const label = {card:'クレジットカード', convenience:'コンビニ払い'};
  function sync(){ const v=sel.value||''; out.textContent=label[v] ?? '選択してください'; hid.value=v; }
  sel.addEventListener('change', sync); sync();
</script>
@endsection
