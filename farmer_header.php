<?php

if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}

?>

<header class="header">

   <div class="flex">

      <a href="farmer_page.php" class="logo">Farmer<span>Panel</span></a>

      <nav class="navbar">
         <a href="farmer_page.php">Dashboard</a>
         <a href="farmer.php">Add Product</a>
         <a href="farmer_products.php">Products</a>
         <a href="farmer_orders.php">Order's</a>
         <!-- <a href="admin_orders.php">Orders</a>
         <a href="admin_user.php">Users</a> -->
         <!-- <a href="admin_contacts.php">messages</a> -->
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
         $select_profile->execute([$seller_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
         <p><?= $fetch_profile['name']; ?></p>
         <a href="farmer_update_profile.php" class="btn">Update profile</a>
         <a href="logout.php" class="delete-btn">Logout</a>
         <div class="flex-btn">
            <a href="login.php" class="option-btn">Login</a>
            <a href="register.php" class="option-btn">Register</a>
         </div>
      </div>

   </div>

</header>