<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CsvImportFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'csv_file.required' => 'CSVファイルを選択してください。',
            'csv_file.file' => '有効なファイルを選択してください。',
            'csv_file.mimes' => 'CSVファイル（.csv, .txt）形式でアップロードしてください。',
            'csv_file.max' => 'ファイルサイズは2MB以内にしてください。',
        ];
    }
}
