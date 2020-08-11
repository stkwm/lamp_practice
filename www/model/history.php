<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// ユーザーごとの履歴テーブルの読み込み（ユーザー用）
function get_history($db, $user_id) {
  $sql = "
    SELECT 
      history.history_id,
      created,
      SUM(price * amount) AS total_price
    FROM
      history
    INNER JOIN
      history_details
    ON
      history.history_id = history_details.history_id
    WHERE
      user_id = ?
    GROUP BY
      history.history_id
    ORDER BY
      created
    DESC
    ";
    $params = array($user_id);
    return fetch_all_query($db, $sql, $params);
}

// すべての履歴テーブルの読み込み（ユーザー用）
function get_all_history($db, $user_id) {
    $sql = "
      SELECT 
        history.history_id,
        created,
        SUM(price * amount) AS total_price
      FROM
        history
      INNER JOIN
        history_details
      ON
        history.history_id = history_details.history_id
      GROUP BY
        history.history_id
      ORDER BY
        created
      DESC
      ";
      return fetch_all_query($db, $sql);
  }


// 履歴詳細テーブルの読み込み
function get_history_details($db, $history_id) {
  $sql = "
    SELECT
      history_details.history_id
      history_details.price
      history_details.amount
      items.name
    FROM
      history_details
    INNER JOIN
      items
    ON
      history_details.item_id = items.item_id
    WHERE
      history_id = ?
    ";
  $params = array($history_id);
  return fetch_all_query($db, $sql, $params);
}

// 履歴テーブルにデータを挿入する関数
// phpmyadminで日時のデフォルト値をCURRENT_STAMPに設定してある→$sqlに現在日時の設定必要ない
function insert_history($db, $user_id) {
  $sql = "
    INSERT INTO
      history(
        user_id
      )
      VALUES(?)
      ";
  $params = array($user_id);
  return execute_query($db, $sql, $params);
}

// 履歴詳細テーブルにデータを挿入する関数
function insert_history_details($db, $carts) {
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
    execute_query($db, $sql, $params);
    // returnで返してたら、その時点で処理を終える
  }
}
?>