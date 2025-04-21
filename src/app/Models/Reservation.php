<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Carbon\Carbon;

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

    public function generateQrCode()
    {
        try {
            if (!\Storage::disk('public')->exists('qrcodes')) {
                \Storage::disk('public')->makeDirectory('qrcodes');
            }

            $url = route('reservation.verify', ['id' => $this->id]);
            $renderer = new ImageRenderer(
                new RendererStyle(400),
                new ImagickImageBackEnd()
            );

            $writer = new Writer($renderer);
            $qrCode = $writer->writeString($url);

            $path = 'qrcodes/' . $this->id . '.png';
            $result = \Storage::disk('public')->put($path, $qrCode);

            if ($result) {
                $this->qr_code_path = $path;
                $this->save();
                return true;
            }

            return false;
        } catch (\Exception $e) {
            \Log::error('QR Code generation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function isValidForQrCode()
    {
        $reservationDate = Carbon::parse($this->date);
        return $reservationDate->isToday();
    }

    public function getQrCodeUrlAttribute()
    {
        if ($this->qr_code_path) {
            return asset('storage/' . $this->qr_code_path);
        }
        return null;
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'reservation_id', 'id')
        ->where('user_id', auth()->id());
    }

    public function getFormattedDateAttribute()
    {
        return \Carbon\Carbon::parse($this->date)->format('Y-m-d');
    }
    public function getFormattedTimeAttribute()
    {
        return \Carbon\Carbon::parse($this->time)->format('H:i');
    }

    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
            'time' => 'datetime:H:i:s',
            'number_of_people',
            // その他のキャスト定義...
        ];
    }
}