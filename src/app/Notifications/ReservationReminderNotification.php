<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationReminderNotification extends Notification
{
    use Queueable;

    protected $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('【リマインダー】予約情報のご案内')
            ->greeting("こんにちは、{$notifiable->name}さん")
            ->line('以下の予約が本日予定されています。')
            ->line("店舗名: {$this->reservation->shop->name}")
            ->line("日時: {$this->reservation->date} {$this->reservation->time}")
            ->action('詳細を見る', url(route('reservation.verify', $this->reservation->id)))
            ->line('ご来店お待ちしております！');
    }
}
