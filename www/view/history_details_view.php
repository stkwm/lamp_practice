<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入明細</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'history_details.css'); ?>">
</head>
<body>
  <!-- <?php var_dump($history_specific_order);?> -->
  <!-- <?php var_dump($history_details);?> -->
  <!-- <?php var_dump($history_id);?> -->
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入明細</h1>
  <div>
    <?php include VIEW_PATH . 'templates/messages.php'; ?>
    <table class="table table-bordered text-center">
      <tr>
        <th>注文番号</th>
        <th>購入日時</th>
        <th>合計金額</th>
      </tr>
      <tr>
        <td><?php print $history_specific_order[0]['history_id'];?></td>
        <td><?php print $history_specific_order[0]['created'];?></td>
        <td><?php print $history_specific_order[0]['total_price'];?></td>
      </tr>
    </table>
    </div>
    <?php if (count($history_details) > 0) { ?>
      <table class="table table-bordered text-center">
        <thead class="thead-light">
          <tr>
            <th>商品名</th>
            <th>購入時の商品価格</th>
            <th>購入数</th>
            <th>小計</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($history_details as $history_detail){ ?>
          <tr>
            <td><?php print $history_detail['name'];?></td>
            <td><?php print $history_detail['price'];?></td>
            <td><?php print $history_detail['amount'];?></td>
            <td><?php print $history_detail['price'] * $history_detail['amount'];?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <p>エラーが発生しました。</p>
    <?php } ?>
  </div>
</body>
</html>