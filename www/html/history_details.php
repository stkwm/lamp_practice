<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';
require_once MODEL_PATH . 'history.php';
require_once MODEL_PATH . 'user.php';

// セッションスタート
session_start();

// ログインしているかどうか確認
if(is_logined() === false){
    redirect_to(LOGIN_URL);
  }

$db = get_db_connect();

$user = get_login_user($db);
  
$history_id = get_post('history_id');
// CSRF対策　トークンの照合
$token = get_post('token');
// var_dump($token);
if (is_valid_csrf_token($token) === FALSE) {
  redirect_to(LOGIN_URL);
}

$history_details = get_history_details($db, $history_id);

include_once VIEW_PATH . '/history_details_view.php';
?>