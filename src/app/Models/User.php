<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
        'avatar_path', 'zip', 'address1', 'address2',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        // Laravel10+: 'password' => 'hashed', // 使っていれば有効化
    ];

    /** 出品（1:n） */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /** いいね（1:n） */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /** コメント（1:n） */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /** 購入（1:n） */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
    public function favoriteItems()
    {
        return $this->belongsToMany(\App\Models\Product::class, 'likes')->withTimestamps();
    }
    public function getAvatarUrlAttribute(): string
    {
        if (!$this->avatar_path) {
            return asset('images/avatar-placeholder.png');
        }

        return \Illuminate\Support\Str::startsWith($this->avatar_path, ['http://','http://'])
            ? $this->avatar_path
            : asset('storage/'.$this->avatar_path);
    }
}
