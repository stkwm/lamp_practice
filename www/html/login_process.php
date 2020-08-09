<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

session_start();
// ログインしていれば、ホーム画面へ移動
if(is_logined() === true){
  redirect_to(HOME_URL);
}
// ログイン画面で入力したデータを取得する
$name = get_post('name');
$password = get_post('password');
// DBに接続
$db = get_db_connect();

// ログインに成功の際、adminの場合は管理ページへ、その他はホームページへ移動する
$user = login_as($db, $name, $password);
if( $user === false){
  set_error('ログインに失敗しました。');
  redirect_to(LOGIN_URL);
}

set_message('ログインしました。');

if ($user['type'] === USER_TYPE_ADMIN){
  redirect_to(ADMIN_URL);
}

redirect_to(HOME_URL);