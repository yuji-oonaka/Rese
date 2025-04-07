# Rese 
### 飲食店予約システム
ユーザー登録しログインすることでグループ会社の飲食店閲覧、お気に入り登録、予約、評価等を行うことができる
![スクリーンショット 2024-11-30 094006](https://github.com/user-attachments/assets/5ae84db8-de2e-4fad-a8fb-b28a1937b20f)

## アプリケーションURL
- 開発環境:http://localhost  
- phpMyAdmin:http://localhost:8080

## 機能一覧
- ログイン、ログアウト機能
- 飲食店閲覧機能
- 飲食店検索機能
- 飲食店予約機能
- お気に入り登録機能
- 飲食店評価機能
- QRコード予約確認表示機能

## 使用技術
- PHP:8.3.7
- Laravel:11.33.2
- MySQL:8.0.26

## テーブル設計  
![image](https://github.com/user-attachments/assets/bb3dd0b6-9ae9-4b4f-ad17-e52443753b90)



## ER図
![shop drawio (5)](https://github.com/user-attachments/assets/c0642232-fc4b-46a6-ba9b-7de655ebaaae)

## 環境構築  
### Dockerビルド
1. `git clone git@github.com:yuji-oonaka/Rese.git`
2. DockerDesktopアプリを立ち上げる
3. `docker-compose up -d --build`

### Laravel環境構築
1. `docker-compose exec php bash`
2. `composer install`
3. `cp .env.example .env`

4. アプリケーションキーの作成
```
php artisan key:generate
```
5. マイグレーションの実行
```
php artisan migrate
```
6. シーディングの実行
php artisan db:seed

7. シンボリックリンクの作成
php artisan storage:link

## テストアカウント
name: 111
email: 111@sample.com
password: 111sample
- または会員登録にて任意のユーザーを登録

## 管理者アカウント
name: admin
email: admin@example.com
password: password

## 店舗代表者アカウント
name: 代表者1",
email: rep1@example.com
password: password

## 口コミ機能(未実装)
店舗来店予定日時終了後から口コミ機能を利用することが可能です

## コメント
今回、Pro入会テストを受けさせていただきましてありがとうございます
模擬案件時からの再設計、追加実装、機能要件の見落としに加え設計やシートの更新ができておらず、タスク管理も不十分でした
ファイルなどの乱立により修正ができなくなり機能要件を満たせませんでした
かなり力不足を実感しました
ただやはり作り上げていく過程は毎回勉強になるし、感動もあるので精進していき、出直したいと思います。
