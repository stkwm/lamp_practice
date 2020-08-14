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

// セッションにトークンを作成、変数tokenに値を代入する
$token = get_csrf_token();
// var_dump($token);

$db = get_db_connect();
// $userは連想配列
$user = get_login_user($db);
// var_dump($user['user_id']);
// 履歴テーブルからデータを取得する（管理者用とユーザー用）
if(is_admin($user) === true){
    $history_orders = get_all_history($db);
} else {
    $history_orders = get_history($db, $user['user_id']);
}
// var_dump($history_orders);
include_once VIEW_PATH . '/history_view.php';
?>