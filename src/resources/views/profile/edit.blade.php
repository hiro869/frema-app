@extends('layouts.app')

@section('title','プロフィール設定')

@push('page_css')
<link rel="stylesheet" href="{{ asset('css/profile/edit.css') }}">
@endpush

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    $avatarUrl = $user->avatar_path
      ? asset('storage/'.$user->avatar_path)
      : asset('img/avatar-placeholder.png');
@endphp
<section class="profile-page">
  <div class="profile-wrap">
    <h1 class="profile-title">プロフィール設定</h1>

    {{-- エラー表示 --}}
    @if ($errors->any())
      <ul class="profile-errors">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
      @csrf
      @method('PATCH')

     <div class="avatar-field">
      <img id="avatarPreview" class="avatar" src="{{ $avatarUrl }}" alt="">
      <input id="avatarInput" type="file" name="avatar"
      accept="image/png,image/jpeg" class="file-input" />

      <label for="avatarInput" class="file-label">画像を選択する</label>
     </div>

<script>
  // 選んだ画像を即プレビュー
  document.getElementById('avatar').addEventListener('change', e => {
    const file = e.target.files?.[0];
    if (file) {
      document.getElementById('avatarPreview').src = URL.createObjectURL(file);
    }
  });
</script>

      <label class="form-row">
        <span class="form-label">ユーザー名</span>
        <input type="text" name="name"
               value="{{ old('name', $isFirst ? '' : $user->name) }}">
      </label>

      <label class="form-row">
        <span class="form-label">郵便番号</span>
        <input type="text" name="zip"
               placeholder="123-4567"
               value="{{ old('zip', $isFirst ? '' : $user->zip) }}">
      </label>

      <label class="form-row">
        <span class="form-label">住所</span>
        <input type="text" name="address1"
               value="{{ old('address1', $isFirst ? '' : $user->address1) }}">
      </label>

      <label class="form-row">
        <span class="form-label">建物名</span>
        <input type="text" name="address2"
               value="{{ old('address2', $isFirst ? '' : $user->address2) }}">
      </label>

      <button class="profile-submit" type="submit">更新する</button>
    </form>
  </div>
</section>
@endsection
