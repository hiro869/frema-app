<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /** このカテゴリに属する商品（n:n） */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
