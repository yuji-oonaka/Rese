<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'shop_id'];

    // お気に入りは1つの店舗に属する（多対1）
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    // お気に入りは1人のユーザーに属する（多対1）
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}