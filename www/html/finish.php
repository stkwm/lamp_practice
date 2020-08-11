<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'history.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);
// ユーザーのショッピングカートのデータを取得する
$carts = get_user_carts($db, $user['user_id']);
// var_dump($user['user_id']);

// CSRF対策　トークンの照合
$token = get_post('token');
// var_dump($token);
if (is_valid_csrf_token($token) === FALSE) {
  redirect_to(LOGIN_URL);
}

$db->beginTransaction();
// 購入履歴テーブルにデータを挿入する
insert_history($db, $user['user_id']);

// 購入履歴詳細テーブルにデータを挿入する
insert_history_details($db, $carts);

// 在庫数の変更とカートテーブルから指定のユーザーIDのデータを削除する。エラーがある場合には、エラーメッセージをSETしてショッピングカートページへ移動する
if(purchase_carts($db, $carts) === false){
  set_error('商品が購入できませんでした。');
  redirect_to(CART_URL);
} 


commit_transaction($db);

// ショッピングカートにある商品の合計金額を取得する
$total_price = sum_carts($carts);

include_once '../view/finish_view.php';