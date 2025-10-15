@extends('layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('css/purchase/address.css') }}">
@endpush

@section('content')
<div class="address-page">
    <h1 class="address-title">住所の変更</h1>

    <form method="POST" action="{{ route('purchase.address.update', $product) }}" class="address-form">
        @csrf

        <div class="form-group">
            <label for="zip" class="form-label">郵便番号</label>
            <input id="zip" type="text" name="zip" class="form-input" value="{{ old('zip', $user->zip) }}">
            @error('zip') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="address1" class="form-label">住所</label>
            <input id="address1" type="text" name="address1" class="form-input" value="{{ old('address1', $user->address1) }}">
            @error('address1') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="address2" class="form-label">建物名</label>
            <input id="address2" type="text" name="address2" class="form-input" value="{{ old('address2', $user->address2) }}">
            @error('address2') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">更新する</button>
        </div>
    </form>
</div>
@endsection
