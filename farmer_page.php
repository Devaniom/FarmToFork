<?php
session_start();
@include 'config.php';

// Check if seller is logged in
if (!isset($_SESSION['seller_id'])) {
   header('location: login.php');
   exit();
}

$seller_id = $_SESSION['seller_id']; // Get seller ID from session


?>


<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
   <title>FarmToFork | farmer page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php include 'farmer_header.php'; ?>

   <section class="dashboard">

      <h1 class="title">Dashboard</h1>

      <div class="box-container">

         <div class="box">
            <?php
            $select_pendings = $conn->prepare("SELECT SUM(total_price) AS total_pendings FROM `orders` WHERE seller_id = ? AND payment_status = ?");
            $select_pendings->execute([$seller_id, 'pending']);
            $result_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC);
            $total_pendings = $result_pendings['total_pendings'] ?? 0;
            ?>
            <h3>₹<?= $total_pendings; ?>/-</h3>
            <p>Pending Payments</p>
            <!-- <a href="farmer_orders.php" class="btn">See Orders</a> -->
            <a href="farmer_orders.php?status=pending" class="btn"> Pending Orders</a>
         </div>

         <div class="box">
            <?php
            $select_completed = $conn->prepare("SELECT SUM(total_price) AS total_completed FROM `orders` WHERE seller_id = ? AND payment_status = ?");
            $select_completed->execute([$seller_id, 'completed']);
            $result_completed = $select_completed->fetch(PDO::FETCH_ASSOC);
            $total_completed = $result_completed['total_completed'] ?? 0;
            ?>
            <h3>₹<?= $total_completed; ?>/-</h3>
            <p>Completed Payments</p>
            <!-- <a href="farmer_orders.php" class="btn">See Orders</a> -->
            <a href="farmer_orders.php?status=completed" class="btn"> Completed Orders</a>
         </div>

         <div class="box">
            <?php
            $select_orders = $conn->prepare("SELECT COUNT(*) AS total_orders FROM `orders` WHERE seller_id = ?");
            $select_orders->execute([$seller_id]);
            $result_orders = $select_orders->fetch(PDO::FETCH_ASSOC);
            $number_of_orders = $result_orders['total_orders'] ?? 0;
            ?>
            <h3><?= $number_of_orders; ?></h3>
            <p>Orders Placed</p>
            <!-- <a href="farmer_orders.php" class="btn">See Orders</a> -->
            <a href="farmer_orders.php?status=all" class="btn">See Orders</a>
         </div>

         <div class="box">
            <?php
            $select_products = $conn->prepare("SELECT COUNT(*) AS total_products FROM `products` WHERE seller_id = ?");
            $select_products->execute([$seller_id]);
            $result_products = $select_products->fetch(PDO::FETCH_ASSOC);
            $number_of_products = $result_products['total_products'] ?? 0;
            ?>
            <h3><?= $number_of_products; ?></h3>
            <p>Products Added</p>
            <a href="farmer_products.php" class="btn">See Products</a>
         </div>
      </div>
   </section>
   <script src="js/script.js"></script>
</body>

</html>