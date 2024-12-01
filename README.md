# Rese 
### 飲食店予約システム
ユーザー登録しログインすることでグループ会社の飲食店閲覧、お気に入り登録、予約、評価等を行うことができる
![スクリーンショット 2024-11-30 094006](https://github.com/user-attachments/assets/5ae84db8-de2e-4fad-a8fb-b28a1937b20f)

## 作成した目的
自社予約サービスを持つため

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
1. `git@github.com:yuji-oonaka/Rese.git`
2. DockerDesktopアプリを立ち上げる
3. `docker-compose up -d --build`
>*MacのM1・M2チップのPCの場合、`no matching manifest for linux/arm64/v8 in the manifest list entries`のメッセージが表示されビルドができないことがあります。 エラーが発生する場合は、docker-compose.ymlファイルの「mysql」内に「platform」の項目を追加で記載してください*
```
mysql:
    platform: linux/x86_64(この文追加)
    image: mysql:8.0.26
    environment:
```

### Laravel環境構築
1. `docker-compose exec php bash`
2. `composer install`
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.envファイルを作成
4. .envに以下の環境変数を追加
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```
5. アプリケーションキーの作成
```
php artisan key:generate
```
6. マイグレーションの実行
```
php artisan migrate
```
###追記
- お店の評価は予約日時が過ぎた後に予約履歴内の対象のお店欄の評価するにて行う
- QRコードはmypageと予約履歴にて表示可能
