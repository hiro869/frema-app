{{-- PG09 / PG11 / PG12 プロフィール（通常 / 購入一覧 / 出品一覧） --}}
@extends('layouts.app')

@section('content')
<h1>プロフィール画面</h1>
@if($page === 'buy')
    <p>購入一覧タブを表示中</p>
@elseif($page === 'sell')
    <p>出品一覧タブを表示中</p>
@else
    <p>通常プロフィールを表示中</p>
@endif
@endsection
