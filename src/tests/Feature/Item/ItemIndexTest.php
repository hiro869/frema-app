<?php

namespace Tests\Feature\Item;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Carbon;

class ItemIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 売却済み商品には_SOLD_ラベルが表示される()
    {
        // 1️⃣ 出品者ユーザーを作成
        $user = User::factory()->create();

        // 2️⃣ 売却済み商品と未販売商品を作成
        $sold = Product::factory()->create([
            'user_id' => $user->id,
            'name' => '売却済みのカメラ',
            'sold_at' => Carbon::now(), // 売却済み
        ]);

        $normal = Product::factory()->create([
            'user_id' => $user->id,
            'name' => '未販売の本',
            'sold_at' => null, // 未販売
        ]);

        // 3️⃣ 商品一覧ページへアクセス
        $response = $this->get('/');

        // 4️⃣ 売却済みの商品には「SOLD」が表示されていることを確認
        $response->assertOk();
        $response->assertSee('Sold'); // Bladeで「SOLD」と出しているなら大文字に合わせて

        // （任意）未販売の商品名も確認しておく
        $response->assertSee($normal->name);
    }
}
