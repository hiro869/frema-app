<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Storage;


// =================== 商品関連 ===================
Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/item/{item}', [ItemController::class, 'show'])->name('items.show');

Route::middleware('auth')->group(function () {
    Route::post('/items/{product}/comments', [CommentController::class, 'store'])->name('comments.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/sell',  [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');
});

// =================== プロフィール（要件：/mypage, /mypage/profile） ===================
Route::middleware('auth')->group(function () {
    // プロフィール「画面」＝ /mypage（名前は profile.index に統一）
    Route::get('/mypage', [MypageController::class, 'index'])->name('profile.index');

    // プロフィール「編集画面／保存」＝ /mypage/profile
    Route::get('/mypage/profile',   [MypageController::class, 'edit'])->name('profile.edit');
    Route::patch('/mypage/profile', [MypageController::class, 'update'])->name('profile.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/items/{product}/like', [LikeController::class, 'store'])->name('items.like');
    Route::delete('/items/{product}/like', [LikeController::class, 'destroy'])->name('items.unlike');
});

// =================== 購入関連 ===================
Route::get('/purchase/success', [PurchaseController::class, 'success'])->name('purchase.success');

Route::get('/purchase/address/{item}', [PurchaseController::class, 'address'])->name('purchase.address')->middleware('auth');
Route::post('/purchase/address/{item}', [PurchaseController::class, 'updateAddress'])->name('purchase.address.update')->middleware('auth');

Route::get('/purchase/{item}',    [PurchaseController::class, 'index'])->name('purchase.index')->middleware('auth');
Route::post('/purchase/{item}',   [PurchaseController::class, 'store'])->name('purchase.store')->middleware('auth');


Route::post('/stripe/webhook', [PurchaseController::class, 'webhook'])->name('stripe.webhook');

// 誘導画面
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// 認証完了メール内リンクが来る先ー>プロフィール設定画面へ
Route::get('/email/verify/{id}/{hash}',function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('profile.edit')->with('status','メール認証が完了しました。');
})->middleware(['auth','signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', '認証メールを再送しました。');
})->middleware(['auth','throttle:6,1'])->name('verification.send');


