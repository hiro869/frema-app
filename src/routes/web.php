<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/mypage/profile', function () {
    return 'プロフィール設定画面（仮）';
})->middleware('auth');


