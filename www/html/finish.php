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

// CSRF対策　トークンの照合
$token = get_post('token');
// var_dump($token);
if (is_valid_csrf_token($token) === FALSE) {
  redirect_to(LOGIN_URL);
}

// 購入履歴テーブルにデータを挿入する
if(history_insert($db, $carts) === false) {
  set_error('商品が購入できませんでした。。');
  redirect_to(CART_URL);
}
// 購入履歴詳細テーブルにデータを挿入する
if(history_details($db, $user_id['user_id']) === false){
  set_error('商品が購入できませんでした。。。');
  redirect_to(CART_URL);
}

// 在庫数から購入個数を引いてカートテーブルから指定のユーザーIDのデータを削除する。エラーがある場合には、エラーメッセージをSETしてショッピングカートページへ移動する
if(purchase_carts($db, $carts) === false){
  set_error('商品が購入できませんでした。');
  redirect_to(CART_URL);
} 

// ショッピングカートにある商品の合計金額を取得する
$total_price = sum_carts($carts);

include_once '../view/finish_view.php';