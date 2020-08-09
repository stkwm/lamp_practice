<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);
// ショッピングカートの購入数の変更ボタンを押したときのPOSTの値を取得する
$cart_id = get_post('cart_id');
$amount = get_post('amount');

// CSRF対策　トークンの照合
$token = get_post('token');
// var_dump($token);
if (is_valid_csrf_token($token) === FALSE) {
  redirect_to(LOGIN_URL);
}

// DBに指定の商品の購入数を変更する

if(update_cart_amount($db, $cart_id, $amount)){
  set_message('購入数を更新しました。');
} else {
  set_error('購入数の更新に失敗しました。');
}

redirect_to(CART_URL);