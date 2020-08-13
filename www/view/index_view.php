<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  
  <title>商品一覧</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  

  <div class="container">
    <h1>商品一覧</h1>
    <form class="sorting" method="get" action="index.php">
      <select name="items_order">
        <option value="new" selected>新着順</option>
        <option value="low_price">価格の安い順</option>
        <option value="high_price">価格の高い順</option>
      </select>
      <input type="submit" value="並び替え">
    </form>
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <div class="card-deck">
      <div class="row">
      <?php foreach($items as $item){ ?>
        <div class="col-6 item">
          <div class="card h-100 text-center">
            <div class="card-header">
              <?php print($item['name']); ?>
            </div>
            <figure class="card-body">
              <img class="card-img" src="<?php print(IMAGE_PATH . $item['image']); ?>">
              <figcaption>
                <?php print(number_format($item['price'])); ?>円
                <?php if($item['stock'] > 0){ ?>
                  <form action="index_add_cart.php" method="post">
                    <input type="submit" value="カートに追加" class="btn btn-primary btn-block">
                    <input type="hidden" name="item_id" value="<?php print($item['item_id']); ?>">
                    <input type="hidden" name="token" value=<?php print $token; ?>>
                  </form>
                <?php } else { ?>
                  <p class="text-danger">現在売り切れです。</p>
                <?php } ?>
              </figcaption>
            </figure>
          </div>
        </div>
      <?php } ?>
      </div>
    </div>

    <h2 class="ranking_title">人気ランキング</h2>
    <div class="ranking-items">
      <?php foreach($ranking_items as $ranking_item) { ?>
      <figure class="ranking-body">
        <figcaption><?php print $ranking_num?>位</figcaption>
        <img class="ranking-img" src="<?php print(IMAGE_PATH . $ranking_item['image']); ?>">
        <figcaption>
          <?php print($ranking_item['name']); ?>
          <?php print(number_format($ranking_item['price'])); ?>円
        </figcaption>
      </figure>
      <?php $ranking_num++; ?>
      <?php } ?>
    </div>
  </div>
  
</body>
</html>