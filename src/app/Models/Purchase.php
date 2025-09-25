<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'product_id',
        'price_at_purchase', 'address_snapshot', 'payment_method',
    ];

    protected $casts = [
        'address_snapshot' => 'array', // JSONを配列で扱える
    ];

    /** 購入者（n:1） */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** 購入した商品（n:1） */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
