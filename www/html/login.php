<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';

session_start();
// ログインしていれば、ホーム画面へ移動する
if(is_logined() === true){
  redirect_to(HOME_URL);
}

include_once VIEW_PATH . 'login_view.php';