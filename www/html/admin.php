<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();
// ログインしていなければ、ログインページへ（セッションにuser_idがセットされていなければログインページへ）
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
// DBに接続
$db = get_db_connect();
// ログインしたユーザーのデータを取得する
$user = get_login_user($db);
//ログインしたユーザーが管理者のものでなければ、ログインページへ
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
// 全商品のデータを取得する
$items = get_all_items($db);
include_once VIEW_PATH . '/admin_view.php';
