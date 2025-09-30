@extends('layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
@endpush

@section('content')
<div class="sell-container">
  <h1 class="sell-title">商品の出品</h1>

  <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="sell-form">
    @csrf

    {{-- 画像 --}}
    <div class="form-group">
      <label class="form-label">商品画像</label>
      <div class="image-drop">
        <input type="file" name="image" accept="image/*">
        <span class="btn-like">画像を選択する</span>
      </div>
      @error('image') <p class="text-error">{{ $message }}</p> @enderror
    </div>

    <h2 class="section-title">商品の詳細</h2>

    {{-- カテゴリ（1つ選択：ラジオ / 送信名は配列） --}}
    <div class="form-group">
      <label class="form-label">カテゴリ</label>
      <div class="category-list">
        @foreach($categories as $cat)
          <label class="category-label">
            <input
              type="radio"
              name="category_ids[]"
              value="{{ $cat->id }}"
              class="category-input"
              {{ collect(old('category_ids', []))->contains($cat->id) ? 'checked' : '' }}>
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
        <input id="price" name="price" type="number" min="1" value="{{ old('price') }}" class="input price-input">
      </div>
      @error('price') <p class="text-error">{{ $message }}</p> @enderror
    </div>

    <div class="form-actions">
      <button type="submit" class="sell-btn">出品する</button>
    </div>
  </form>
</div>
@endsection
