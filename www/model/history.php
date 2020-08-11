<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// 履歴テーブルにデータを挿入する関数
function insert_history($db, $user_id) {
  $sql = "
    INSERT INTO
    history(
        user_id
    )
    VALUES(?) 
  ";
$params = array($user_id)
return execute_query($db, $sql, $params);
}
}

// 履歴詳細テーブルにデータを挿入する関数
function insert_history_detail($db, $carts) {
  $history_id = $db->lastInsertId('history_id');
  foreach($carts as $cart) {
    $sql = "
      INSERT INTO
        history_details(
          history_id,
          item_id,
          price,
          amount
        )
      VALUES(?, ?, ?, ?)
      ";
    $params = array($history_id, $cart['item_id'], $cart['price'], $cart['amount']);
    return execute_query($db, $sql, $params);
  }
}


?>