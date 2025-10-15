@extends('layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('css/purchase/index.css') }}">
@endpush

@section('content')
<div class="purchase-container">

  <div class="grid">
    {{-- 左カラム：商品概要 / 支払い / 住所リンク --}}
    <div class="left">
      <div class="product-row">
        <img class="thumb" src="{{ $product->image_url }}" alt="{{ $product->name }}">
        <div class="meta">
          <div class="name">{{ $product->name }}</div>
          <div class="price">¥{{ number_format($product->price) }}</div>
        </div>
      </div>

      <hr>

      {{-- 支払い方法 --}}
      <div class="block">
        <div class="block-title">支払い方法</div>

        <form id="purchase-form" method="POST" action="{{ route('purchase.store', $product) }}">
          @csrf
          <select name="pay_method" class="select">
            <option value="" hidden>選択してください</option>
            <option value="convenience" {{ old('pay_method')==='convenience'?'selected':'' }}>コンビニ支払い</option>
            <option value="card"        {{ old('pay_method')==='card'?'selected':'' }}>カード支払い</option>
          </select>
          @error('pay_method')
            <p class="err">{{ $message }}</p>
          @enderror
        </form>
      </div>

      <hr>

      {{-- 配送先（プロフィールの住所が初期値） --}}
      <div class="block">
        <div class="block-title between">
          <span>配送先</span>
          <a class="link" href="{{ route('purchase.address', $product) }}">変更する</a>
        </div>
        <div class="addr">
          <div>〒 {{ $ship['zip'] }}</div>
          <div>{{ $ship['address1'] }}</div>
          @if($ship['address2']) <div>{{ $ship['address2'] }}</div> @endif
        </div>
      </div>
    </div>

    <aside class="right">
        <div class="card">
            <div class="sum-row">
                <span>商品代金</span>
                <span>¥{{ number_format($product->price) }}</span>
            </div>
            <div class="sum-row">
                <span>支払い方法</span>
                <span id="pay_label">
                    @if(old('pay_method')==='convenience')コンビニ支払い
                    @elseif(old('pay_method')==='card') カード支払い
                    @else 選択してください
                    @endif
                </span>
            </div>
        </div>

        <button form="purchase-form" class="btn-buy" {{ $product->is_sold ? 'disabled' : ''}}>
            購入する
        </button>
    </aside>
</div>

</div>
@push('page_js')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const select = document.querySelector('select[name="pay_method"]');
        const label = document.getElementById('pay_label');
        if (!select || !label) return;

        const mapping = {
            convenience: 'コンビニ支払い',
            card: 'カード支払い'
        };
        select.addEventListener('change', () => {
            const value = select.value;
            label.textContent = mapping[value] || '選択してください';
        });
    });
</script>
@endpush
@endsection
