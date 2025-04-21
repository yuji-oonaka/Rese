<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        $rating = $this->faker->numberBetween(1, 5);

        $comments = [
            1 => [
                '期待外れでした。もう利用しないと思います。',
                'サービスの質が低く、残念でした。',
                '料理が冷めていて美味しくなかったです。',
                '店員の対応が悪かったです。',
                '待ち時間が長すぎました。',
            ],
            2 => [
                '普通以下でした。改善を期待します。',
                '味はまあまあですが、コスパが悪いです。',
                'スタッフの対応が少し気になりました。',
                '店内が少し汚れていました。',
                'もう少しサービス向上を期待します。',
            ],
            3 => [
                '可もなく不可もなく、普通でした。',
                '値段相応の内容でした。',
                '特別良い点も悪い点もありません。',
                '無難なお店だと思います。',
                'また機会があれば利用します。',
            ],
            4 => [
                '美味しかったです。また利用したいです。',
                'サービスが良く、満足できました。',
                '雰囲気も良く、友人にも勧めたいです。',
                'スタッフの対応が丁寧でした。',
                'コストパフォーマンスが良いと感じました。',
            ],
            5 => [
                '最高でした！また必ず来ます！',
                '料理もサービスも大満足です。',
                '今までで一番のお店です。',
                '全てが素晴らしかったです。',
                '家族や友人にも自信を持っておすすめできます。',
            ],
        ];

        return [
            'comment' => $this->faker->randomElement($comments[$rating]),
            'rating' => $rating,
            'image_path' => null,
        ];
    }
}