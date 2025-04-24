<?php
session_start();
@include 'config.php';

// Check if seller is logged in
if (!isset($_SESSION['seller_id'])) {
   header('location: login.php');
   exit();
}

$seller_id = $_SESSION['seller_id']; // Get seller ID from session

try {
   // Fetch only the logged-in seller's products
   $show_products = $conn->prepare("SELECT * FROM `products` WHERE seller_id = ?");
   $show_products->execute([$seller_id]);
} catch (PDOException $e) {
   die("Query Error: " . $e->getMessage());
}
if (isset($_POST['add_product'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/' . $image;

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if ($select_products->rowCount() > 0) {
      $message[] = 'product name already exist!';
   } else {

      $insert_products = $conn->prepare("INSERT INTO `products`(seller_id, name, category, details, price, image) VALUES(?,?,?,?,?,?)");
      $insert_products->execute([$seller_id, $name, $category, $details, $price, $image]);


      if ($insert_products) {
         if ($image_size > 2000000) {
            $message[] = 'image size is too large!';
         } else {
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'new product added!';
         }

      }

   }

}
;

if (isset($_GET['delete'])) {

   $delete_id = $_GET['delete'];
   $select_delete_image = $conn->prepare("SELECT image FROM `products` WHERE id = ?");
   $select_delete_image->execute([$delete_id]);
   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/' . $fetch_delete_image['image']);
   $delete_products = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_products->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:farmer_products.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
   <title>FarmToFork | Your Products</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">
</head>

<body>

   <?php include 'farmer_header.php'; ?>

   <section class="show-products"><br>
      <h1 class="title"> Your Products</h1>
      <div class="box-container">
         <?php
         if ($show_products->rowCount() > 0) {
            while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {
               ?>
               <div class="box">
                  <div class="price">₹<?= htmlspecialchars($fetch_products['price']); ?>/-</div>
                  <img src="uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="">
                  <div class="name"><?= htmlspecialchars($fetch_products['name']); ?></div>
                  <div class="cat"><?= htmlspecialchars($fetch_products['category']); ?></div>
                  <div class="details"><?= htmlspecialchars($fetch_products['details']); ?></div>
                  <div class="flex-btn">
                     <a href="farmer_update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Update</a>
                     <a href="farmer_products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn"
                        onclick="return confirm('Delete This Product?');">Delete</a>
                  </div>
               </div>
               <?php
            }
         } else {
            echo "<p class='empty'>No products found for you.</p>";
         }
         ?>
      </div>
   </section>

   <script src="js/script.js"></script>
</body>

</html>