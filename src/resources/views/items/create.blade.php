@extends('layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
@endpush

@section('content')
<div class="sell-container">
  <h1 class="sell-title">商品の出品</h1>

  <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="sell-form" novalidate>
    @csrf

    {{-- 画像 --}}
    <div class="form-group">
      <label class="form-label">商品画像</label>
      <div class="image-drop" id="imageDrop">
        <input id="image" type="file" name="image" accept="image/*">

        {{-- 最初はボタンが中央に表示される --}}
        <div class="preview-wrap" id="previewWrap">
          <span id="pickTrigger" class="btn-like">画像を選択する</span>
        </div>
      </div>
      @error('image') <p class="text-error">{{ $message }}</p> @enderror
    </div>

    <h2 class="section-title">商品の詳細</h2>

    {{-- カテゴリ --}}
    <div class="form-group">
      <label class="form-label">カテゴリ</label>
      <div class="category-list">
        @foreach($categories as $cat)
          <label class="category-label">
            <input
  type="checkbox"
  name="category_ids[]"
  value="{{ $cat->id }}"
  class="category-input"
  {{ collect(old('category_ids', isset($product) ? $product->categories->pluck('id')->toArray() : []))->contains($cat->id) ? 'checked' : ''}}>
  <span class="category-item">{{ $cat->name }}</span>
  </label>
        @endforeach
      </div>
      @error('category_ids')   <p class="text-error">{{ $message }}</p> @enderror
      @error('category_ids.*') <p class="text-error">{{ $message }}</p> @enderror
    </div>

    {{-- コンディション --}}
    <div class="form-group">
      <label class="form-label">商品の状態</label>
      <select name="condition" class="select">
        <option value="">選択してください</option>
        <option value="good" {{ old('condition')==='good' ? 'selected' : '' }}>良好</option>
        <option value="no_obvious_damage" {{ old('condition')==='no_obvious_damage' ? 'selected' : '' }}>目立った傷や汚れなし</option>
        <option value="some_damage" {{ old('condition')==='some_damage' ? 'selected' : '' }}>やや傷や汚れあり</option>
        <option value="bad" {{ old('condition')==='bad' ? 'selected' : '' }}>状態が悪い</option>
      </select>
      @error('condition') <p class="text-error">{{ $message }}</p> @enderror
    </div>

    <h2 class="section-title">商品名と説明</h2>

    <div class="form-group">
      <label for="name" class="form-label">商品名</label>
      <input id="name" name="name" type="text" value="{{ old('name') }}" class="input">
      @error('name') <p class="text-error">{{ $message }}</p> @enderror
    </div>

    <div class="form-group">
      <label for="brand" class="form-label">ブランド名</label>
      <input id="brand" name="brand" type="text" value="{{ old('brand') }}" class="input">
      @error('brand') <p class="text-error">{{ $message }}</p> @enderror
    </div>

    <div class="form-group">
      <label for="description" class="form-label">商品の説明</label>
      <textarea id="description" name="description" rows="4" class="textarea">{{ old('description') }}</textarea>
      @error('description') <p class="text-error">{{ $message }}</p> @enderror
    </div>

    <div class="form-group">
      <label for="price" class="form-label">販売価格</label>
      <div class="price-wrapper">
        <input id="price" name="price" type="number" inputmode="numeric" pattern="[0-9]*" value="{{ old('price') }}" class="input price-input">
      </div>
      @error('price') <p class="text-error">{{ $message }}</p> @enderror
    </div>

    <div class="form-actions">
      <button type="submit" class="sell-btn">出品する</button>
    </div>
  </form>
</div>

{{-- 画像プレビューJS --}}
<script>
  // ✅ 修正版
const input       = document.getElementById('image');
const previewWrap = document.getElementById('previewWrap');
const pickTrigger = document.getElementById('pickTrigger');

// ← imageDrop.addEventListener は削除！

pickTrigger.addEventListener('click', () => input.click());

input.addEventListener('change', (e) => {
  const file = e.target.files && e.target.files[0];
  previewWrap.innerHTML = '';

  if (!file) {
    const btn = document.createElement('span');
    btn.id = 'pickTrigger';
    btn.className = 'btn-like';
    btn.textContent = '画像を選択する';
    btn.addEventListener('click', () => input.click());
    previewWrap.appendChild(btn);
    return;
  }

  if (!file.type.startsWith('image/')) {
    alert('画像ファイルを選択してください');
    input.value = '';
    return;
  }

  const img = document.createElement('img');
  img.src = URL.createObjectURL(file);
  img.onload = () => URL.revokeObjectURL(img.src);
  previewWrap.appendChild(img);
});
</script>
@endsection
