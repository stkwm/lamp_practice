<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
// セッションにトークンを作成、変数tokenに値を代入する
$token = get_csrf_token();
// var_dump($token);

$db = get_db_connect();
$user = get_login_user($db);
// ステータス公開の商品データを取得する

$items_order = get_get('items_order');

$items = get_open_items($db, $items_order);
// 商品名をHTMLエンティティに変換
// $items = entity_assoc_array($items);

// 商品の人気ランキングデータを取得
$ranking_items = get_ranking_items($db);

$ranking_num = 1;

include_once VIEW_PATH . 'index_view.php';