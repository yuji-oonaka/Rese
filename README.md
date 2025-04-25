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
![スクリーンショット 2025-04-07 233451](https://github.com/user-attachments/assets/4c4b8d65-48ba-45ab-877f-e135209d5fea)

![スクリーンショット 2025-04-07 233331](https://github.com/user-attachments/assets/869ced62-89d1-4b62-9bee-a06a6c6eb9e0)



## ER図
![Rese](https://github.com/user-attachments/assets/19ff060f-9709-4288-8ce4-8783349d5e03)


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
```
php artisan db:seed
```
7. シンボリックリンクの作成
```
php artisan storage:link
```
## テストアカウント
- name: 111
- email: 111@sample.com
- password: 111sample
- または会員登録にて任意のユーザーを登録

## 管理者アカウント
- name: admin
- email: admin@example.com
- password: password

## 店舗代表者アカウント
- name: 代表者1",
- email: rep1@example.com
- password: password

## メール認証について
mailtrapというツールを使用しています。  
Mailtrapアカウント作成
Mailtrap公式サイト (https://mailtrap.io/) 
でサインアップし、ログインします。

SMTP情報の取得
ログイン後、Inboxesからテスト用のInboxを作成または選択し、「Integrations」から「Laravel」を選択します。

.envファイルのMAIL_MAILERからMAIL_ENCRYPTIONまでの項目をコピー＆ペーストしてください。　
MAIL_FROM_ADDRESSは任意のメールアドレスを入力してください。

## 口コミ機能
口コミは「店舗来店予定日時終了後」から投稿可能です。

1. 一般ユーザー
1店舗あたり1件のみ口コミを投稿できます
自身が投稿した口コミのみ編集可能です
自身が投稿した口コミのみ削除できます。

2. 管理者ユーザー
口コミの追加、編集はできません。
管理者権限での全ての口コミを削除できます。

3. 店舗ユーザー
口コミの追加・編集・削除はできません。

## CSVインポート機能
管理者ユーザーは、CSVファイルをインポートすることで新規店舗情報を一括追加できます
店舗作成ページにてファイルを選択、確認後インポートできます
テスト用のCSVファイルをプロジェクトディレクトリ直下に作成しています。

　
