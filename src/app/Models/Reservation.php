<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'shop_id', 'date', 'time', 'number_of_people'];

    // 予約は1人のユーザーに属する
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 予約は1つの店舗に属する
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}