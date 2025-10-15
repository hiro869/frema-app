{{-- PG07 送付先住所変更 --}}
@extends('layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('css/purchase/address.css')}}">
@endpush

@section('content')
<div class="address-container">
    <h1 class="title">配送先の変更</h1>

    <form method="POST" action="{{ route('purchase.address.update', $product) }}" class="form">
        @csrf

        <label class="lbl">郵便番号</label>
        <input type="text" name="address1" value="{{ old('address1', $user->address1) }}">
        @error('address1') <p class="err">{{ $message }}</p> @enderror

        <label class="lbl">建物名 （任意）</label>
            <input type="text" name="address2" value="{{ old('address2', $user->address2) }}">
            @error('address2') <p class="err">{{ $message }}</p> @enderror

            <div class="actions">
                <a href="{{ route('purchase.index', $product) }}" class="btn ghost">戻る</a>
                <button class="btn primary">保存して戻る</button>
            </div>
    </form>
</div>
@endsection
