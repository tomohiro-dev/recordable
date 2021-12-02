![recordable_logo](https://user-images.githubusercontent.com/51843368/144446810-168f713c-f568-4dbc-9216-f171a444f516.png)


# Recordable
日本語学習の仕組みを創るためのサービス提供プロジェクト。

![Laravel 6.20.32](https://img.shields.io/badge/Laravel-6.20.32-red) 
![Composer 2.0.14](https://img.shields.io/badge/Composer-2.0.14-red) 
![php 7.4.23](https://img.shields.io/badge/php-7.4.23-purple)
![Node 14.17.5](https://img.shields.io/badge/node-14.17.5-green)
![npm 6.14.14](https://img.shields.io/badge/npm-6.14.14-lightgreen) 
![Vue 2.6.14](https://img.shields.io/badge/Vue-2.6.14-green) 
![mysql 8.0.26](https://img.shields.io/badge/MySQL-8.0.26-blue) 
![nginx 1.20.1](https://img.shields.io/badge/nginx-1.20.1-darkgreen) 
<img src="https://img.shields.io/badge/-Docker-EEE.svg?logo=docker&style=flat"> 

（下記は次回以降のアップデートで導入予定） \
※初期リリースでの使用を中止しました。 \
https://bit.ly/3Djm7ZW \
<img src="https://img.shields.io/badge/-Terraform-EEE.svg?logo=terraform&logoColor=7B42BC">
<img src="https://img.shields.io/badge/-GithubActions-EEE.svg?logo=githubactions&logoColor=black">

## Set up
main branchで`git clone`を行ってください。`cd`commandで当Directoryへ移動し、開発を進めてください。 \
Build errorが出た場合は、エラーの内容に従って都度修正を行ってください。

**【重要】`.php`ファイルを`git commit`する時にコード整形を行う場合は、「コード整形のみ」の`feature branch`として`feature/000_fixPhpCode`を切ってください。** \
`000`は前回のチケット番号から連番でbranchを切ってください。 \
Asana内で管理している、他のチケット番号と被らないよう注意してください。

## Project構成
本Projectは、laravel-vue-docker-template-v2の`main` branchをベースに作成しています。 \
環境構築手順は下記の**Docker container build**以降を参考に進めてください。 

（下記は次回以降のアップデートで導入予定） \
※初期リリースでの使用を中止しました。 \
また、容量の肥大化を予防するために、Infrastructure（以下Infra）は別Repositoryで作成を検討中。 \
Infraの設定は**recordable-infra** Repository下で`main` branchから独自でBranchを切って開発を進めてください。 \
https://github.com/soregashi-27/recordable-infra

## Directory構成
「編集中」
infraにterraformを追加したら書きたい \
【TODO:ディレクトリツリーを追加する】

## 環境構築手順

### Docker container build
移動したタイミングでDockerを一旦立ち上げる
```
docker compose up -d --build
```

## Laravelをインストール
### appコンテナに入る
```
docker compose exec app bash
```

Laravelに必要なパッケージをインストール
```
composer install
```

バージョンを確認
```
php artisan -V
```

### php-cs-fixerのライブラリ情報をインストール
```
composer require --dev friendsofphp/php-cs-fixer:v2.19.x
```

バージョンの確認
```
./vendor/bin/php-cs-fixer --version
```

ローカルホストへ接続
```
http://127.0.0.1/
```
500 | servert errorが出ることを確認する（正しい） `composer install`時は `.env`環境変数ファイルは作成されないので、 
`.env.example`を元にコピーして作成する。

### `.env`ファイルの設定
```
cd backend
```

```
cp .env.example .env
```


### アプリケーションキーをつくる
appコンテナから出ている場合は再度入る
```
docker compose exec app bash
```

アプリケーションキーを作成する（※`.env`にkeyがないことを確認する）
```
php artisan key:generate
```
暗号化の際に使われる。SessionやAuth機能など。App Keyが確実に指定されていれば暗号化された値は安全。

### シンボリックリンクをつくる
appコンテナ内でやる
```
php artisan storage:link
```
public/storage から storage/app/public へのシンボリックリンクをつくる システムで生成したファイル等をブラウザからアクセスできるよう公開するため。strorage dirにアクセスするためのもの。

Laravelで作ったプリケーションが公開されるとき、公開されるのは一番上の階層にある public ディレクトリのみ。
public ディレクトリにファイルや処理のすべてが集約される。保存した画像も public ディレクトリ内に存在しないとアクセスすることがで着なくなってしまうので、その予防策。


### 書き込み権限を追加する
```
chmod -R 777 storage bootstrap/cache
```
-R : recursive \
`storage,bootstrap/cache`はフレームワークからファイル書き込みが発生するので、書き込み権限を与える。

## Dockerで立ち上げる
一旦最初に作成したImageとVolumesやnetworkは消す
```
docker compose down --rmi all --volumes --remove-orphans
```
※migrationしたら基本的には使わないこと。（tableが消える）

### Docker composeでBuildする
```
docker compose up -d --build
```

立ち上がっているか確認
```
docker ps -a
```

### バージョンの確認
・Laravel
Docker立ち上げ後確認
```
docker compose exec app bash
```

```
php artisan -V
```

・php-cs-fixer
appコンテナ内で確認
```
./vendor/bin/php-cs-fixer --version
```

・MySQL

```
docker compose exec db bash
```

```
mysql --version
```

・nginx
local環境で確認
```
docker compose exec web nginx -v
```

### Web Browser上で画面を確認する
・Laravel
```
http://127.0.0.1:80/
```
80番は省略される

・PhpMyAdmin
```
http://127.0.0.1:8080/
```

## データベースの設定
### `.env`ファイルの設定を確認する
`.env.example`ファイルを書き換えてコピーする \
（事前に終わっていたら飛ばす）

### Migrateする
```
php artisan migrate
```

### PhpMyAdminでMigrateしたデータベースとテーブルが作成されているかを確認
```
http://127.0.0.1:8080/
```

## ログの設定
### Laravelのログをコンテナログに表示させる
`backend/.env` を修正する
```
LOG_CHANNEL=stderr
```

### 表示されるかを確認

`backend/routes/web.php`に追記
```
Route::get('/', function () {
    logger('welcome route.');
    return view('welcome');
});
```

**logをみる方法3パターン**

```
$ docker compose logs

# -f でログウォッチ
$ docker compose logs -f

# サービス名を指定してログを表示
$ docker compose logs -f app
```

`docker compose logs -f app`のあとに、`http://127.0.0.1:8080/`に接続するlog上にこんな感じで表示される
```
app_1  | [2021-07-25 05:48:53] local.DEBUG: welcome route.  
app_1  | 172.20.0.3 -  25/Jul/2021:05:48:51 +0000 "GET /index.php" 200
```
こんな感じで表示されてたらOK。

・laravel/uiを追加する

```
composer require laravel/ui:1.3.0 --dev
```

## Front-Endの設定

vue.jsを使った認証機能の追加
```
php artisan ui vue --auth
```

webコンテナへ移動
```
docker compose exec web ash
```
※軽量化（起動を早くする&CPU、メモリ節約）のためにAlpine Linux shellを使用
そこそこ時間がかかる

```
npm install && npm run dev
```
追加しているライブラリについてはWikiを参照してください。 \
【TODO:Wikiのリンクを編集後に追加】

・npm
```
npm -v
```

・Node.js
```
node -v
```

## Buildコマンド
dockerfileのビルドコマンド \
ECRへpushする前は使う \
※github actionsで自動化したいところ

appコンテナをビルド
```
docker build -t app -f infra/docker/php/Dockerfile .
```

webコンテナをビルド
```
docker build -t web -f infra/docker/nginx/Dockerfile .
```


-----------

## カラーコード
編集中


-----------

## よく使うコマンドリスト

### php-cs-fixerでコード整形する
```
./vendor/bin/php-cs-fixer fix -v --diff --diff-format udiff
```
差分を表示して整形できる

### phpunitでtestする
```
./vendor/bin/phpunit --testdox
```

### Docker Image, Volumes, networkを一括消去
```
docker compose down --rmi all --volumes --remove-orphans
```


### Laravelのキャッシュクリアコマンド
```
// 基本これ1つ
php artisan cache:clear

//Routingの部分を実装しているとき
php artisan route:clear

//Viewの部分を実装しているとき
php artisan view:clear

//基本つかわない（本番環境を使う時に使う場合がある）
php artisan config:clear
```

### `"ERR! code ELIFECYCLE"`が発生した時
Package-lock.jsonとpackage.jsonのバージョンが違っていたり、キャッシュが溜まり過ぎてたり・・・？ 的な問題の可能性を疑う。 \
npmファイルは結構壊れやすかったりもする

インストールされたjsモジュールを全部消す
```
rm -rf node_modules
```

インストールされたjsモジュールのバージョン情報を消す
```
rm package-lock.json
```

npmのキャッシュをクリアする
```
npm cache clear --force
```
 
再度jsモジュールを全部入れ直して、実行する
```
npm install
npm run dev
```

## Credit
Big thanks to Jonathan(Non-developer), who is a my language exchange mate living in Austria. \
We are learning English and Japanese. We make this app together. \
And, thanks to multinational friends giving some information.
