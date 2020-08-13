<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// DB利用
// DBの商品の詳細を読み込み、指定のitem=idの商品のデータを入手する
function get_item($db, $item_id){
  $sql = "
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
    WHERE
      item_id = ?
  ";

  $params = array($item_id);
  return fetch_query($db, $sql, $params);
}
// DBの商品の詳細を読み込み、ステータス別の商品のデータをすべて入手する
function get_items($db, $is_open = false, $items_order = ''){
  $sql = '
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
  ';
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  }
  if($items_order === 'new'){
    $sql .= '
      ORDER BY
        created
      DESC
    ';
  } else if($items_order === 'high_price'){
    $sql .= '
      ORDER BY
        price
      DESC
    ';
  } else if($items_order === 'low_price'){
    $sql .= '
      ORDER BY
        price
      ASC
    ';
  }

  return fetch_all_query($db, $sql);
}
// DBの商品の詳細を読み込み、全商品のデータを取得する
function get_all_items($db){
  return get_items($db);
}
// DBの商品を読み込み、ステータスが公開のデータをすべて取得する
function get_open_items($db, $items_order){
  return get_items($db, true, $items_order);
}
// 管理ページでの商品の登録
function regist_item($db, $name, $price, $stock, $status, $image){
  $filename = get_upload_filename($image);
  if(validate_item($name, $price, $stock, $filename, $status) === false){
    return false;
  }
  return regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename);
}

function regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename){
  $db->beginTransaction();
  if(insert_item($db, $name, $price, $stock, $filename, $status) 
    && save_image($image, $filename)){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
  
}
// DBに商品のデータを書き込む
function insert_item($db, $name, $price, $stock, $filename, $status){
  $status_value = PERMITTED_ITEM_STATUSES[$status];
  $sql = "
    INSERT INTO
      items(
        name,
        price,
        stock,
        image,
        status
      )
    VALUES(?, ?, ?, ?, ?);
  ";

  $params = array($name, $price, $stock, $filename, $status_value);

  return execute_query($db, $sql, $params);
}
// DBにある指定のitem=idの商品のステータスを変更する
function update_item_status($db, $item_id, $status){
  $sql = "
    UPDATE
      items
    SET
      status = ?
    WHERE
      item_id = ?
    LIMIT 1
  ";
  
  $params = array($status, $item_id);

  return execute_query($db, $sql, $params);
}
// DBにある指定のitem=idの商品の在庫数を変更する
function update_item_stock($db, $item_id, $stock){
  $sql = "
    UPDATE
      items
    SET
      stock = ?
    WHERE
      item_id = ?
    LIMIT 1
  ";

  $params = array($stock, $item_id);
  
  return execute_query($db, $sql, $params);
}

function destroy_item($db, $item_id){
  $item = get_item($db, $item_id);
  if($item === false){
    return false;
  }
  $db->beginTransaction();
  if(delete_item($db, $item['item_id'])
    && delete_image($item['image'])){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
}

function delete_item($db, $item_id){
  $sql = "
    DELETE FROM
      items
    WHERE
      item_id = ?
    LIMIT 1
  ";

  $params = array($item_id);
  
  return execute_query($db, $sql, $params);
}


// 非DB

// 商品のステータスが公開であれば、TRUEを返す
function is_open($item){
  return $item['status'] === 1;
}
// 商品のエラーチェック
function validate_item($name, $price, $stock, $filename, $status){
  $is_valid_item_name = is_valid_item_name($name);
  $is_valid_item_price = is_valid_item_price($price);
  $is_valid_item_stock = is_valid_item_stock($stock);
  $is_valid_item_filename = is_valid_item_filename($filename);
  $is_valid_item_status = is_valid_item_status($status);

  return $is_valid_item_name
    && $is_valid_item_price
    && $is_valid_item_stock
    && $is_valid_item_filename
    && $is_valid_item_status;
}
// 引数$nameのエラーチェック
function is_valid_item_name($name){
  $is_valid = true;
  if(is_valid_length($name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === false){
    set_error('商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  return $is_valid;
}
// 引数$priceのエラーチェック
function is_valid_item_price($price){
  $is_valid = true;
  if(is_positive_integer($price) === false){
    set_error('価格は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}
// 引数$stockのエラーチェック
function is_valid_item_stock($stock){
  $is_valid = true;
  if(is_positive_integer($stock) === false){
    set_error('在庫数は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}
// 引数$filenameのエラーチェック
function is_valid_item_filename($filename){
  $is_valid = true;
  if($filename === ''){
    $is_valid = false;
  }
  return $is_valid;
}
// 引数$statusのエラーチェック
function is_valid_item_status($status){
  $is_valid = true;
  if(isset(PERMITTED_ITEM_STATUSES[$status]) === false){
    $is_valid = false;
  }
  return $is_valid;
}


// 人気商品順に商品データを入手する（購入数が多い順）
function get_ranking_items($db){
  $sql = "
    SELECT
      name,
      image,
      items.price,
      SUM(amount) AS total_amount
    FROM
      items 
    INNER JOIN 
      history_details
    ON
      items.item_id = history_details.item_id
    GROUP BY
      name, image, items.price
    ORDER BY
      total_amount DESC
    LIMIT
      3
    ";
  return fetch_all_query($db, $sql);
}
