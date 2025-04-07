<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Notifications\ReservationReminderNotification;
use Carbon\Carbon;

class SendReservationReminders extends Command
{
    protected $signature = 'reminders:send';
    protected $description = '予約当日の朝にリマインダーを送信する';

    public function handle()
    {
        // 現在の日付（今日）
        $today = Carbon::today();

        // 当日の予約を取得
        $reservations = Reservation::with(['user', 'shop'])
            ->whereDate('date', $today)
            ->get();

        foreach ($reservations as $reservation) {
            // ユーザーにリマインダー通知を送信
            $reservation->user->notify(new ReservationReminderNotification($reservation));
        }

        $this->info('リマインダー通知が送信されました。');
    }
}

