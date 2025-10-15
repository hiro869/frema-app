<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;
use Stripe\Webhook;

class PurchaseController extends Controller
{
    public function index(Product $item)
{
    $user = auth()->user();

    // 配送先住所はプロフィール情報を初期値として表示
    $ship = [
        'zip' => $user->zip,
        'address1' => $user->address1,
        'address2' => $user->address2,
    ];

    return view('purchase.index', [
        'product' => $item,
        'ship' => $ship,
    ]);
}

    // 「購入する」→ Stripe Checkout へ
    public function store(Request $request, Product $item)
    {
        // 既に売却済みはNG
        abort_if($item->is_sold, 403);

        $validated = $request->validate([
            'pay_method' => ['required', 'in:card,convenience'],
        ],[
            'pay_method.required' => '支払い方法を選択してください。',
        ]);

        // Stripe 決済方法
        $methods = $validated['pay_method'] === 'card' ? ['card'] : ['konbini'];

        $stripe = new StripeClient(config('services.stripe.secret'));

        $session = $stripe->checkout->sessions->create([
            'mode' => 'payment',
            'payment_method_types' => $methods, // card or konbini
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $item->name],
                    'unit_amount' => (int) $item->price, // JPY は整数
                ],
                'quantity' => 1,
            ]],
            // コンビニ支払いは email 必須
            'customer_email' => $request->user()->email,
            'success_url'    => route('purchase.success') .'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'     => route('purchase.index',   $item),

            // Webhook で復元するためのメタ情報（DBのカラム名に合わせておく）
            'metadata' => [
                'product_id'     => $item->id,
                'buyer_id'       => $request->user()->id,
                'payment_method' => $validated['pay_method'], // convenience|card
            ],
        ]);

        return redirect()->away($session->url);
    }

    // 成功戻り（表示だけ、確定はWebhookでやる）
    public function success(\Illuminate\Http\Request $request)
    {
        // ここではまだ確定保存しない（Webhookで確定）
        return redirect()
            ->route('items.index')
            ->with('status', '決済処理を受け付けました。完了反映まで少しお待ちください。');
    }

    // Stripe Webhook（支払い完了の確定を受ける）
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig     = $request->header('Stripe-Signature');
        $secret  = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sig, $secret);
        } catch (\Throwable $e) {
            return response('Invalid', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $productId     = $session->metadata->product_id ?? null;
            $buyerId       = $session->metadata->buyer_id ?? null;
            $paymentMethod = $session->metadata->payment_method ?? null;

            if ($productId && $buyerId) {
                DB::transaction(function () use ($productId, $buyerId, $paymentMethod) {
                    // 商品ロック（同時購入防止）
                    /** @var Product $product */
                    $product = Product::lockForUpdate()->find($productId);
                    if (!$product) return;

                    // 既にSOLDなら二重作成しない（Webhookは再送の可能性あり）
                    if ($product->is_sold) return;

                    // 購入者の住所をスナップショット
                    $user = \App\Models\User::find($buyerId);
                    $snapshot = [
                        'zip'      => $user->zip,
                        'address1' => $user->address1,
                        'address2' => $user->address2,
                    ];

                    // purchases へ作成（テーブル定義に合わせる）
                    Purchase::create([
                        'user_id'           => $buyerId,
                        'product_id'        => $product->id,
                        'price_at_purchase' => $product->price,
                        'address_snapshot'  => $snapshot,                // ← JSON
                        'payment_method'    => $paymentMethod,           // 'convenience' or 'card'
                    ]);

                    // 商品側を SOLD に
                    $product->update([
                        'is_sold' => true,
                        'sold_at' => now(),
                    ]);
                });
            }
        }

        return response()->json(['ok' => true]);
    }
}
