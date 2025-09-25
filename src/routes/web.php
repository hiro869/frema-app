<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MypageController;



// -----------------------------------
// 商品関連
// -----------------------------------
// PG01, PG02: 商品一覧（トップ & マイリスト）
Route::get('/', [ItemController::class, 'index'])->name('items.index');

// PG05: 商品詳細
Route::get('/item/{item}', [ItemController::class, 'show'])->name('items.show');

// PG08: 商品出品
Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

// -----------------------------------
// 会員登録 / ログイン（Fortify側で対応）
// PG03: /register → Fortify
// PG04: /login → Fortify

// -----------------------------------
// 購入関連
// -----------------------------------
// PG06: 商品購入
Route::get('/purchase/{item_id}', [PurchaseController::class, 'index'])->name('purchase.index');
Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');

// PG07: 送付先住所変更
Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'address'])->name('purchase.address');
Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->name('purchase.updateAddress');

// -----------------------------------
// プロフィール関連
// -----------------------------------


Route::middleware('auth')->group(function () {
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');
    Route::get('/mypage/profile', [MypageController::class, 'edit'])->name('mypage.edit');
    Route::patch('/mypage/profile', [MypageController::class, 'update'])->name('mypage.update');
});

// PG11, PG12: 購入一覧・出品一覧（クエリパラメータで切替）
// /mypage?page=buy
// /mypage?page=sell
// → ProfileController@index 内で分岐処理
