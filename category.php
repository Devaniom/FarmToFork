<?php @include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
   exit();
}

if(isset($_POST['add_to_wishlist'])){

   $pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);
   $p_name = filter_var($_POST['p_name'], FILTER_SANITIZE_STRING);
   $p_price = filter_var($_POST['p_price'], FILTER_SANITIZE_STRING);
   $p_image = filter_var($_POST['p_image'], FILTER_SANITIZE_STRING);

   $check_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
   $check_wishlist->execute([$p_name, $user_id]);

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart->execute([$p_name, $user_id]);

   if($check_wishlist->rowCount() > 0){
      $message[] = 'Already added to wishlist!';
   } elseif($check_cart->rowCount() > 0){
      $message[] = 'Already added to cart!';
   } else {
      $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
      $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
      $message[] = 'Added to wishlist!';
   }
}

if(isset($_POST['add_to_cart'])){

   $pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);
   $p_name = filter_var($_POST['p_name'], FILTER_SANITIZE_STRING);
   $p_price = filter_var($_POST['p_price'], FILTER_SANITIZE_STRING);
   $p_image = filter_var($_POST['p_image'], FILTER_SANITIZE_STRING);
   $p_qty = filter_var($_POST['p_qty'], FILTER_SANITIZE_STRING);

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart->execute([$p_name, $user_id]);

   if($check_cart->rowCount() > 0){
      $message[] = 'Already added to cart!';
   } else {

      $check_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist->execute([$p_name, $user_id]);

      if($check_wishlist->rowCount() > 0){
         $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
         $delete_wishlist->execute([$p_name, $user_id]);
      }

      $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
      $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
      $message[] = 'Added to cart!';
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

   <title>FramToFork | Category</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'shop_header.php'; ?>

<section class="products">

   <h1 class="title">Product Categories</h1>

   <div class="box-container">

   <?php
      $category_name = isset($_GET['category']) ? filter_var($_GET['category'], FILTER_SANITIZE_STRING) : '';

      if(!empty($category_name)) {
         $select_products = $conn->prepare("SELECT * FROM `products` WHERE category = ?");
         $select_products->execute([$category_name]);
         $products = $select_products->fetchAll(PDO::FETCH_ASSOC);

         if(count($products) > 0){
            foreach($products as $fetch_products){ 
   ?>
   <form action="" class="box" method="POST">
      <div class="price">₹<span><?= htmlspecialchars($fetch_products['price']); ?></span>/-</div>
      <a href="view_page.php?pid=<?= htmlspecialchars($fetch_products['id']); ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="">
      <div class="name"><?= htmlspecialchars($fetch_products['name']); ?></div>
      <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_products['id']); ?>">
      <input type="hidden" name="p_name" value="<?= htmlspecialchars($fetch_products['name']); ?>">
      <input type="hidden" name="p_price" value="<?= htmlspecialchars($fetch_products['price']); ?>">
      <input type="hidden" name="p_image" value="<?= htmlspecialchars($fetch_products['image']); ?>">
      <input type="number" min="1" value="1" name="p_qty" class="qty">
      <input type="submit" value="Add to Wishlist" class="option-btn" name="add_to_wishlist">
      <input type="submit" value="Add to Cart" class="btn" name="add_to_cart">
   </form>
   <?php
            }
         } else {
            echo '<p class="empty">No products available!</p>';
         }
      } else {
         echo '<p class="empty">No category selected!</p>';
      }
   ?>

   </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
