<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreShopRequest extends FormRequest
{
    public function authorize()
    {
        return true; // 認可ロジックは必要に応じて
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:50',
            'area_id' => 'required|exists:areas,id',
            'genre_id' => 'required|exists:genres,id',
            'description' => 'required|string|max:400',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'representative_id' => 'nullable|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '店舗名は必須です。',
            'name.max' => '店舗名は50文字以内で入力してください。',
            'area_id.required' => 'エリアを選択してください。',
            'area_id.exists' => '選択されたエリアが存在しません。',
            'genre_id.required' => 'ジャンルを選択してください。',
            'genre_id.exists' => '選択されたジャンルが存在しません。',
            'description.required' => '説明は必須です。',
            'description.max' => '説明は400文字以内で入力してください。',
            'image.required' => '画像を選択してください。',
            'image.image' => '画像ファイルを選択してください。',
            'image.mimes' => '画像はjpg、jpeg、png形式でアップロードしてください。',
            'image.max' => '画像サイズは2MB以内でアップロードしてください。',
            'representative_id.exists' => '選択された代表者が存在しません。',
        ];
    }
}