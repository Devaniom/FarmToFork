<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
}
;
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
   <title>FarmToFork | Products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php include 'admin_header.php'; ?>


   <section class="show-products">

      <h1 class="title">Products</h1>

      <div class="box-container">

         <?php
         $show_products = $conn->prepare("SELECT * FROM `products`");
         $show_products->execute();
         if ($show_products->rowCount() > 0) {
            while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {
               ?>
               <div class="box">
                  <div class="price">₹<?= $fetch_products['price']; ?>/-</div>
                  <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
                  <div class="name"><?= $fetch_products['name']; ?></div>
                  <div class="cat"><?= $fetch_products['category']; ?></div>
                  <div class="details"><?= $fetch_products['details']; ?></div>
               </div>
               <?php
            }
         } else {
            echo '<p class="empty">now products added yet!</p>';
         }
         ?>

      </div>

   </section>

   <script src="js/script.js"></script>

</body>

</html>