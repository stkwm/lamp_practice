<?php
require_once MODEL_PATH . 'functions.php';


function get_db_connect(){
  // MySQL用のDSN文字列
  $dsn = 'mysql:dbname='. DB_NAME .';host='. DB_HOST .';charset='.DB_CHARSET;
 
  try {
    // データベースに接続
    $dbh = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    exit('接続できませんでした。理由：'.$e->getMessage() );
  }
  return $dbh;
}
// データベースの読み込みの実行 fetch
function fetch_query($db, $sql, $params = array()){
  try{
    $statement = $db->prepare($sql);
    $statement->execute($params);
    return $statement->fetch();
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。');
  }
  return false;
}
// データベースの読み込みの実行 fetchAll
function fetch_all_query($db, $sql, $params = array()){
  try{
    $statement = $db->prepare($sql);
    $statement->execute($params);
    return entity_assoc_array($statement->fetchAll());
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。');
  }
  return false;
}
// データベースの実行(DBへの書き込み用)
// 「execute」メソッドを実行するSQL文に引数がある場合(後で値を指定するために「?」や名前付きパラメータを指定した場合)、「execute」メソッドの引数に、値を配列の形で指定します。
// $params = array()　は初期値を与えている
// execute($params)はbindvalueの部分と同じことをしている
function execute_query($db, $sql, $params = array()){
  try{
    $statement = $db->prepare($sql);
    // foreach ($params as $param) {
    //   $param_id = array_search($param, $params) + 1;
    //     if(is_numeric($param)) {
    //       $statement->bindvalue($param_id, $param, PDO::PARAM_INT);
    //     } else {
    //       $statement->bindvalue($param_id, $param, PDO::PARAM_STR);
    //     }
    //   }
    return $statement->execute($params);
  }catch(PDOException $e){
    set_error('更新に失敗しました。'.$sql);
  }
  return false;
}
// この時点でエラーがなければ、コミット。あれば、ロールバック
function commit_transaction($db) {
  if(has_error() === true) {
    $db->rollback();
  } else {
    $db->commit();
  }
}
  