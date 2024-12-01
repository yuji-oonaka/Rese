<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $today = now()->toDateString();

        return [
            'date' => ['required', 'date', 'after_or_equal:' . $today],
            'time' => ['required', 'date_format:H:i'],
            'number_of_people' => ['required', 'integer', 'min:1', ],
        ];
    }

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
        ];
    }
}