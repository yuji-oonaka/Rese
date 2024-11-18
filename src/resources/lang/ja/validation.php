<?php

return [
    'required' => ':attributeは必須項目です。',
    'string' => ':attributeは文字列で入力してください。',
    'email' => ':attributeは有効なメールアドレス形式で入力してください。',
    'max' => [
        'string' => ':attributeは:max文字以内で入力してください。',
    ],
    'min' => [
        'string' => ':attributeは:min文字以上で入力してください。',
    ],
    'unique' => ':attributeは既に使用されています。',
    'confirmed' => ':attributeが確認用と一致しません。',

    'attributes' => [
        'name' => '名前',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
    ],
    'custom' => [
        'name' => [
            'required' => '名前を入力してください'
        ],
        'email' => [
            'required' => 'メールアドレスを入力してください。',
            'email' => '有効なメールアドレスを入力してください。',
        ],
        'password' => [
            'required' => 'パスワードを入力してください。',
        ],
    ],
];