{{-- PG05 商品詳細 --}}
@extends('layouts.app')

@section('content')
<h1>商品詳細画面</h1>
<p>ここに商品ID {{ $id ?? '' }} の詳細を表示します。</p>
@endsection
