@extends('layouts.app')

@section('title','プロフィール設定')

@push('page_css')
<link rel="stylesheet" href="{{ asset('css/profile/edit.css') }}">
@endpush

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    $avatarUrl = $user->avatar_path
      ? asset('storage/' . $user->avatar_path)
      : asset('images/notimage-gray.png');
@endphp
<section class="profile-page">
  <div class="profile-wrap">
    <h1 class="profile-title">プロフィール設定</h1>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
      @csrf
      @method('PATCH')

     <div class="avatar-field">
      <img id="avatarPreview" class="avatar" src="{{ $avatarUrl }}?v=1" >
      <input id="avatarInput" type="file" name="avatar"
      accept="image/png,image/jpeg" class="file-input" />
      <label for="avatarInput" class="file-label">画像を選択する</label>
      @error('avatar')
        <p class="error-message">{{ $message }}</p>
      @enderror
    </div>


      <label class="form-row">
        <span class="form-label">ユーザー名</span>
        <input type="text" name="name" value="{{ old('name', $isFirst ? '' : $user->name) }}">
        @error('name')
          <p class="error-message">{{ $message }}</p>
        @enderror
      </label>

      <label class="form-row">
        <span class="form-label">郵便番号</span>
        <input type="text" name="zip" placeholder="123-4567" value="{{ old('zip', $isFirst ? '' : $user->zip) }}">
        @error('zip')
          <p class="error-message">{{ $message }}</p>
        @enderror
      </label>

      <label class="form-row">
        <span class="form-label">住所</span>
        <input type="text" name="address1" value="{{ old('address1', $isFirst ? '' : $user->address1) }}">
        @error('address1')
          <p class="error-message">{{ $message }}</p>
        @enderror
      </label>

      <label class="form-row">
        <span class="form-label">建物名</span>
        <input type="text" name="address2" value="{{ old('address2', $isFirst ? '' : $user->address2) }}">
      </label>

      <button class="profile-submit" type="submit">更新する</button>
    </form>
  </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const input  = document.getElementById('avatarInput');   // <input type="file">
  const imgEl  = document.getElementById('avatarPreview'); // <img>（現在のアバター）

  if (!input || !imgEl) return;

  input.addEventListener('change', (e) => {
    const file = e.target.files && e.target.files[0];
    if (!file) return;

    // 画像以外は弾く
    if (!file.type.startsWith('image/')) {
      alert('画像ファイルを選択してください');
      input.value = '';
      return;
    }

    // いま選んだ画像を即プレビューに反映
    const url = URL.createObjectURL(file);
    imgEl.src = url;
    // メモリ解放（読み込み完了後でOK）
    imgEl.onload = () => URL.revokeObjectURL(url);
  });
});
</script>
@endsection
