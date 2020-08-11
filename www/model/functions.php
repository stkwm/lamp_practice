<?php
// 引数$varの内容を出力する
function dd($var){
  var_dump($var);
  exit();
}
// 引数$urlへリダイレクトする
function redirect_to($url){
  header('Location: ' . $url);
  exit;
}
// GETのデータを取得する
function get_get($name){
  if(isset($_GET[$name]) === true){
    return $_GET[$name];
  };
  return '';
}
// POSTのデータを取得する
function get_post($name){
  if(isset($_POST[$name]) === true){
    return $_POST[$name];
  };
  return '';
}
// ファイルのデータを取得する
function get_file($name){
  if(isset($_FILES[$name]) === true){
    return $_FILES[$name];
  };
  return array();
}
// セッションに保存されている名前を取得する
function get_session($name){
  if(isset($_SESSION[$name]) === true){
    return $_SESSION[$name];
  };
  return '';
}
// セッションに名前を保存
function set_session($name, $value){
  $_SESSION[$name] = $value;
}
// セッションにエラーメッセージを保存
function set_error($error){
  $_SESSION['__errors'][] = $error;
}
// セッションに保存したエラーメッセージを取得し、エラーに関するセッションのデータをリセットする
function get_errors(){
  $errors = get_session('__errors');
  if($errors === ''){
    return array();
  }
  set_session('__errors',  array());
  return $errors;
}
// 何らかのエラーがあるかどうか、論理値を返す
function has_error(){
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}
// セッションに引数$messageを保存する
function set_message($message){
  $_SESSION['__messages'][] = $message;
}
// セッションに保存されているメッセージを取得する
function get_messages(){
  $messages = get_session('__messages');
  if($messages === ''){
    return array();
  }
  set_session('__messages',  array());
  return $messages;
}
// セッションにユーザーIDが保存されていれば、TRUEを返す
function is_logined(){
  return get_session('user_id') !== '';
}
// アップロードするファイルのnameを取得する
function get_upload_filename($file){
  if(is_valid_upload_image($file) === false){
    return '';
  }
  $mimetype = exif_imagetype($file['tmp_name']);
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
  return get_random_string() . '.' . $ext;
}
// ランダムな定数を取得する
function get_random_string($length = 20){
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}

function save_image($image, $filename){
  return move_uploaded_file($image['tmp_name'], IMAGE_DIR . $filename);
}

function delete_image($filename){
  if(file_exists(IMAGE_DIR . $filename) === true){
    unlink(IMAGE_DIR . $filename);
    return true;
  }
  return false;
  
}


// ある文字の文字数が最大数と最小数の条件を満たしているか、論理値を返す
function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  $length = mb_strlen($string);
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}
// 引数$stringが半角英数字であれば、TRUEを返す
function is_alphanumeric($string){
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}
// ある数値が正の整数であれば、TRUEを返す
function is_positive_integer($string){
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}
// ある文字（数値）がフォーマットの条件を満たしていれば、TRUEを返す
function is_valid_format($string, $format){
  return preg_match($format, $string) === 1;
}

// 画像ファイル形式を調べる関数
function is_valid_upload_image($image){
  if(is_uploaded_file($image['tmp_name']) === false){
    set_error('ファイル形式が不正です。');
    return false;
  }
  $mimetype = exif_imagetype($image['tmp_name']);
  if( isset(PERMITTED_IMAGE_TYPES[$mimetype]) === false ){
    set_error('ファイル形式は' . implode('、', PERMITTED_IMAGE_TYPES) . 'のみ利用可能です。');
    return false;
  }
  return true;
}
// 文字列を引数として渡すと、特殊文字をHTMLエスケープを施した値を返す
function h($str){
  return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}

function entity_assoc_array($assoc_array) {
  foreach ($assoc_array as $key => $value) {
    foreach ($value as $keys => $values) {
      if (is_numeric($values) !== TRUE) {
        $assoc_array[$key][$keys] = h($values);
      }
    }
  }
  return $assoc_array;
}


// トークンの生成
function get_csrf_token(){
  // get_random_string()はユーザー定義関数。ランダムな定数を取得する
  $token = get_random_string(30);
  // set_session()はユーザー定義関数。セッションに指定のデータを保存。function set_session($name, $value){ $_SESSION[$name] = $value;}
  set_session('csrf_token', $token);
  return $token;
}

// トークンのチェック
// function get_session($name) セッションにデータが保存されていれば、そのデータを返す。
// {if(isset($_SESSION[$name]) === true) {return $_SESSION[$name];} ;return '';}
function is_valid_csrf_token($token){
  if($token === '') {
    return false;
  }
  // get_session()はユーザー定義関数
  return $token === get_session('csrf_token');
}


