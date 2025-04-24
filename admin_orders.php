<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
   <title>FarmToFork | Orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

   <style>
      .tab-button {
         font-size: 1.3rem;
         font-weight: bold;
         margin: 0 20px;
         padding: 8px 20px;
         cursor: pointer;
         border: none;
         background: none;
         border-bottom: 4px solid transparent;
         color: #333;
      }

      .tab-button.active {
         border-color: #28a745;
         color: #28a745;
      }

      .tab-content {
         display: none;
      }

      .tab-content.active {
         display: block;
      }

      .placed-orders .title::after {
         display: none;
      }
   </style>
</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="placed-orders">

      <h1 class="title">PLACED ORDERS</h1>

      <div style="text-align: center; margin-bottom: 30px;">
         <button class="tab-button" onclick="showTab('all')">All</button>
         <button class="tab-button" onclick="showTab('pending')">Pending</button>
         <button class="tab-button" onclick="showTab('completed')">Completed</button>
      </div>

      <!-- All Orders -->
      <div class="tab-content" id="all">
         <div class="box-container">
            <?php
            $select_all = $conn->prepare("SELECT * FROM `orders` ORDER BY id DESC");
            $select_all->execute();
            if ($select_all->rowCount() > 0) {
               while ($fetch_orders = $select_all->fetch(PDO::FETCH_ASSOC)) {
            ?>
                  <div class="box">
                     <p> User id : <span><?= $fetch_orders['user_id']; ?></span> </p>
                     <p> Placed on : <span><?= $fetch_orders['placed_on']; ?></span> </p>
                     <p> Name : <span><?= $fetch_orders['name']; ?></span> </p>
                     <p> Email : <span><?= $fetch_orders['email']; ?></span> </p>
                     <p> Number : <span><?= $fetch_orders['number']; ?></span> </p>
                     <p> Address : <span><?= $fetch_orders['address']; ?></span> </p>
                     <p> Total products : <span><?= $fetch_orders['total_products']; ?></span> </p>
                     <p> Total price : <span>₹<?= $fetch_orders['total_price']; ?>/-</span> </p>
                     <p> Payment method : <span><?= $fetch_orders['method']; ?></span> </p>
                     <p> Payment status : <span><?= $fetch_orders['payment_status']; ?></span> </p>
                  </div>
            <?php
               }
            } else {
               echo '<p class="empty">No Orders Found!</p>';
            }
            ?>
         </div>
      </div>

      <!-- Pending Orders -->
      <div class="tab-content" id="pending">
         <div class="box-container">
            <?php
            $select_pending = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = 'pending'");
            $select_pending->execute();
            if ($select_pending->rowCount() > 0) {
               while ($fetch_orders = $select_pending->fetch(PDO::FETCH_ASSOC)) {
            ?>
                  <div class="box">
                     <p> User id : <span><?= $fetch_orders['user_id']; ?></span> </p>
                     <p> Placed on : <span><?= $fetch_orders['placed_on']; ?></span> </p>
                     <p> Name : <span><?= $fetch_orders['name']; ?></span> </p>
                     <p> Email : <span><?= $fetch_orders['email']; ?></span> </p>
                     <p> Number : <span><?= $fetch_orders['number']; ?></span> </p>
                     <p> Address : <span><?= $fetch_orders['address']; ?></span> </p>
                     <p> Total products : <span><?= $fetch_orders['total_products']; ?></span> </p>
                     <p> Total price : <span>₹<?= $fetch_orders['total_price']; ?>/-</span> </p>
                     <p> Payment method : <span><?= $fetch_orders['method']; ?></span> </p>
                  </div>
            <?php
               }
            } else {
               echo '<p class="empty">No Pending Orders Found!</p>';
            }
            ?>
         </div>
      </div>

      <!-- Completed Orders -->
      <div class="tab-content" id="completed">
         <div class="box-container">
            <?php
            $select_completed = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = 'completed'");
            $select_completed->execute();
            if ($select_completed->rowCount() > 0) {
               while ($fetch_orders = $select_completed->fetch(PDO::FETCH_ASSOC)) {
            ?>
                  <div class="box">
                     <p> User id : <span><?= $fetch_orders['user_id']; ?></span> </p>
                     <p> Placed on : <span><?= $fetch_orders['placed_on']; ?></span> </p>
                     <p> Name : <span><?= $fetch_orders['name']; ?></span> </p>
                     <p> Email : <span><?= $fetch_orders['email']; ?></span> </p>
                     <p> Number : <span><?= $fetch_orders['number']; ?></span> </p>
                     <p> Address : <span><?= $fetch_orders['address']; ?></span> </p>
                     <p> Total products : <span><?= $fetch_orders['total_products']; ?></span> </p>
                     <p> Total price : <span>₹<?= $fetch_orders['total_price']; ?>/-</span> </p>
                     <p> Payment method : <span><?= $fetch_orders['method']; ?></span> </p>
                  </div>
            <?php
               }
            } else {
               echo '<p class="empty">No Completed Orders Found!</p>';
            }
            ?>
         </div>
      </div>

   </section>

   <script src="js/script.js"></script>
   <script>
      function showTab(tabId) {
         const buttons = document.querySelectorAll('.tab-button');
         const tabs = document.querySelectorAll('.tab-content');

         buttons.forEach(btn => btn.classList.remove('active'));
         tabs.forEach(tab => tab.classList.remove('active'));

         document.getElementById(tabId).classList.add('active');

         const activeBtn = document.querySelector(`.tab-button[onclick*="${tabId}"]`);
         if (activeBtn) activeBtn.classList.add('active');
      }

      window.addEventListener('DOMContentLoaded', () => {
         const urlParams = new URLSearchParams(window.location.search);
         const defaultTab = urlParams.get('tab') || 'all';
         showTab(defaultTab);
      });
   </script>

</body>

</html>
