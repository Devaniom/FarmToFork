<?php @include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
  header('location:login.php');
  exit();
}

if (isset($_POST['add_to_wishlist'])) {

  $pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);
  $p_name = filter_var($_POST['p_name'], FILTER_SANITIZE_STRING);
  $p_price = filter_var($_POST['p_price'], FILTER_SANITIZE_STRING);
  $p_image = filter_var($_POST['p_image'], FILTER_SANITIZE_STRING);

  $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
  $check_wishlist_numbers->execute([$p_name, $user_id]);

  $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
  $check_cart_numbers->execute([$p_name, $user_id]);

  if ($check_wishlist_numbers->rowCount() > 0) {
    $message[] = 'already added to wishlist!';
  } elseif ($check_cart_numbers->rowCount() > 0) {
    $message[] = 'already added to cart!';
  } else {
    $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
    $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
    $message[] = 'added to wishlist!';
  }
}

if (isset($_POST['add_to_cart'])) {

  $pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);
  $p_name = filter_var($_POST['p_name'], FILTER_SANITIZE_STRING);
  $p_price = filter_var($_POST['p_price'], FILTER_SANITIZE_STRING);
  $p_image = filter_var($_POST['p_image'], FILTER_SANITIZE_STRING);
  $p_qty = filter_var($_POST['p_qty'], FILTER_SANITIZE_STRING);

  $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
  $check_cart_numbers->execute([$p_name, $user_id]);

  if ($check_cart_numbers->rowCount() > 0) {
    $message[] = 'already added to cart!';
  } else {

    $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
    $check_wishlist_numbers->execute([$p_name, $user_id]);

    if ($check_wishlist_numbers->rowCount() > 0) {
      $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
      $delete_wishlist->execute([$p_name, $user_id]);
    }

    $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
    $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
    $message[] = 'added to cart!';
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">

  <title>FramToFork | Shop</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

  <!-- custom css file link  -->
  <link rel="stylesheet" href="css/style.css">

</head>

<body>
  <?php include 'shop_header.php'; ?>

  <section class="p-category">
    <a href="category.php?category=fruits">Fruits</a>
    <a href="category.php?category=vegitables">Vegitables</a>
  </section>

  <section class="products">
    <h1 class="title">Latest Products</h1>
    <div class="box-container">
      <?php
      $select_products = $conn->prepare("SELECT * FROM `products`");
      $select_products->execute();
      $result = $select_products->fetchAll(PDO::FETCH_ASSOC);

      if (count($result) > 0) {
        foreach ($result as $fetch_products) {
          ?>
          <form action="" class="box" method="POST">
            <div class="price">₹<span><?= $fetch_products['price']; ?></span>/-</div>
            <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
            <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
            <div class="name"><?= $fetch_products['name']; ?></div>
            <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
            <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
            <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
            <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
            <input type="number" min="1" value="1" name="p_qty" class="qty">
            <input type="submit" value="Add to Wishlist" class="option-btn" name="add_to_wishlist">
            <input type="submit" value="Add to Cart" class="btn" name="add_to_cart">
          </form>
          <?php
        }
      } else {
        echo '<p class="empty">No products added yet!</p>';
      }
      ?>
    </div>
  </section>

  <script src="js/script.js"></script>

</body>

</html>