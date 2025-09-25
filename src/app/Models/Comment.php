<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'product_id', 'body',
    ];

    /** 投稿者（n:1） */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** 対象商品（n:1） */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
