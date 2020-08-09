<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();

$user = get_login_user($db);

// CSRF対策　トークンの照合
$token = get_post('token');
// var_dump($token);
if (is_valid_csrf_token($token) === FALSE) {
  redirect_to(LOGIN_URL);
}

if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
// 削除ボタンを押したときのPOSTの値を取得する
$item_id = get_post('item_id');

// DBから指定のitem_idの商品を削除する
if(destroy_item($db, $item_id) === true){
  set_message('商品を削除しました。');
} else {
  set_error('商品削除に失敗しました。');
}


redirect_to(ADMIN_URL);