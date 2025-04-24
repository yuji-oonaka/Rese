<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:400',
            'image' => 'nullable|image|mimes:jpeg,png|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => '評価の選択は必須です',
            'rating.min' => '評価は1以上で選択してください',
            'rating.max' => '評価は5以下で選択してください',
            'comment.max' => 'コメントは400文字以内で入力してください',
            'image.image' => '画像ファイルを選択してください',
            'image.mimes' => '画像はJPEGまたはPNG形式のみ対応しています',
            'image.max' => '画像サイズは2MB以下にしてください',
        ];
    }
}
