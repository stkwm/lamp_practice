<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'history.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入履歴</h1>
  <div>
    <?php include VIEW_PATH . 'templates/messages.php'; ?>
    <?php if (count($history_orders) > 0) { ?>
      <table>
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>合計金額</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($history_orders as $history_order) { ?>
          <tr>
            <td><?php print $history_order['history_id'];?></td>
            <td><?php print $history_order['created'];?></td>
            <td><?php print(number_format($history_order['total_price']));?>円</td>
            <td>
              <form method="post" action="history_details.php">
                <input type="submit" value="購入明細" class="btn btn-secondary">
                <input type="hidden" name="history_id" value="<?php print $history_order['history-id'];?>">
                <input type="hidden" name="token" value="<?php print $token;?>">
              </form>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <p>購入した商品はありません。</p>
    <?php } ?>
  </div>
</body>
</html>