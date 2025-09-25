<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'brand',
        'price',
        'description',
        'condition',
        'image_path',
        'sold_at',
    ];

    /** いいねしたユーザー */
    public function likers()
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

    /** 購入情報（unique product_id なので1対1想定） */
    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

    /** コメント */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /** カテゴリ（多対多） */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    /** 名前部分一致検索 */
    public function scopeSearchName($query, ?string $keyword)
    {
        if (filled($keyword)) {
            $query->where('name', 'like', "%{$keyword}%");
        }
        return $query;
    }

    /** Sold判定（アクセサ） */
    public function getIsSoldAttribute(): bool
    {
        return !is_null($this->sold_at);
    }
}
