<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    /**
     * ユーザーがこのリクエストを許可されているかどうかを判断する
     */
    public function authorize()
    {
        return true; // 認証済みのユーザーなら誰でも予約できるように
    }

    /**
     * バリデーションルールを定義する
     */
    public function rules()
    {
        $today = now()->toDateString(); // 今日の日付

        return [
            'date' => ['required', 'date', 'after_or_equal:' . $today], // 今日以降の日付のみ許可
            'time' => ['required', 'date_format:H:i'], // 時間はHH:MM形式で必須
            'number_of_people' => ['required', 'integer', 'min:1', 'max:10'], // 1人以上10人以下
        ];
    }

    /**
     * カスタムエラーメッセージの定義（オプション）
     */
    public function messages()
    {
        return [
            'date.required' => '予約日を選択してください。',
            'date.after_or_equal' => '予約日は今日以降の日付を選択してください。',
            'time.required' => '予約時間を選択してください。',
            'time.date_format' => '時間はHH:MM形式で入力してください。',
            'number_of_people.required' => '人数を選択してください。',
            'number_of_people.integer' => '人数は整数で入力してください。',
            'number_of_people.min' => '予約人数は1名様以上でお願いいたします。',
            'number_of_people.max' => '人数は10人以下でなければなりません。',
        ];
    }
}