<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\NoticeMail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;

class NoticeMailController extends Controller
{
    public function showForm()
    {
        return view('admin.notice_mail');
    }

    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:100',
            'message' => 'required|string|max:1000',
        ]);

        // 一般利用者のみを取得（管理者と代表者を除外）
        $users = User::with('roles')
            ->whereDoesntHave('roles', function($query) {
                $query->whereIn('name', ['admin', 'representative']);
            })
            ->get();

        // メール送信処理
        foreach ($users as $user) {
            RateLimiter::attempt(
                'mailing:'.$user->id,
                3,
                function() use ($user, $request) {
                    $user->notify(
                        (new NoticeMail($request->subject, $request->message))
                            ->onQueue('emails')
                    );
                },
                60 // 60秒間隔
            );
        }

        return back()->with('success', '一般利用者にメールを送信しました');
    }
}
