@extends('layouts.app')
@section('title','プロフィール設定')

@push('page_css')
<link rel="stylesheet" href="{{ asset('css/profile/edit.css') }}?v=1">
@endpush

@section('content')
<div class="profile-wrap">
  <h1 class="title">プロフィール設定</h1>

  {{-- 成功メッセージ --}}
  @if(session('status'))
    <div class="alert-success">{{ session('status') }}</div>
  @endif

  {{-- エラー --}}
  @if ($errors->any())
    <ul class="form-errors">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  @endif

  <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form" novalidate>
    @csrf

    {{-- アバター --}}
    <div class="avatar-block">
      <div class="avatar-circle">
        @if($user->avatar_path)
          <img src="{{ asset('storage/'.$user->avatar_path) }}" alt="avatar">
        @else
          <span class="avatar-placeholder"></span>
        @endif
      </div>
      <label class="btn-outline">
        画像を選択する
        <input type="file" name="avatar" accept="image/jpeg,image/png" hidden>
      </label>
      @error('avatar')
        <p class="field-error">{{ $message }}</p>
      @enderror
    </div>

    {{-- ユーザー名 --}}
    <label class="form-label">ユーザー名
      <input type="text" name="name" value="{{ old('name', $user->name) }}">
      @error('name') <p class="field-error">{{ $message }}</p> @enderror
    </label>

    {{-- 郵便番号 --}}
    <label class="form-label">郵便番号
      <input type="text" name="zip" placeholder="123-4567" value="{{ old('zip', $user->zip) }}">
      @error('zip') <p class="field-error">{{ $message }}</p> @enderror
    </label>

    {{-- 住所 --}}
    <label class="form-label">住所
      <input type="text" name="address1" value="{{ old('address1', $user->address1) }}">
      @error('address1') <p class="field-error">{{ $message }}</p> @enderror
    </label>

    {{-- 建物名 --}}
    <label class="form-label">建物名
      <input type="text" name="address2" value="{{ old('address2', $user->address2) }}">
      @error('address2') <p class="field-error">{{ $message }}</p> @enderror
    </label>

    <button type="submit" class="btn-primary">更新する</button>
  </form>
</div>
@endsection
