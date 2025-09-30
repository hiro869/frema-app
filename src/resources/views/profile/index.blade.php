{{-- PG09 / PG11 / PG12 プロフィール（通常 / 購入一覧 / 出品一覧） --}}
@extends('layouts.app')
@section('title','マイページ')

@section('content')
  <h1>マイページ</h1>

  <nav style="margin: 12px 0;">
    <a href="{{ url('/mypage?page=buy') }}">購入一覧</a> |
    <a href="{{ url('/mypage?page=sell') }}">出品一覧</a> |
    <a href="{{ route('profile.edit') }}">プロフィール編集</a>
  </nav>

  <p>ようこそ、{{ auth()->user()->name }} さん</p>
  {{-- ここに購入/出品リストを後で追加 --}}
@endsection
