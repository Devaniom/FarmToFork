<?php 
@include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit();
}

// Approve seller
if (isset($_GET['approve']) && is_numeric($_GET['approve'])) {
    $approve_id = $_GET['approve'];
    $approve_stmt = $conn->prepare("UPDATE `users` SET is_approved = 1 WHERE id = ? AND user_type = 'seller'");
    $approve_stmt->execute([$approve_id]);
    header('location:total_farmers.php');
    exit();
}

// Delete seller
if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_users = $conn->prepare("DELETE FROM `users` WHERE id = ?");
   $delete_users->execute([$delete_id]);
   header('location:total_farmers.php');

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
   <title>FarmToFork | Farmers</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css -->
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="user-accounts">
   <h1 class="title">Farmer's accounts</h1>

   <div class="box-container">
      <?php
         $select_users = $conn->prepare("SELECT * FROM `users` WHERE user_type = 'seller'");
         $select_users->execute();
         while ($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)) {
      ?>
      <div class="box" style="<?php if ($fetch_users['id'] == $admin_id) echo 'display:none'; ?>">
         <img src="uploaded_img/<?= $fetch_users['image']; ?>" alt="">
         <p> User id : <span><?= $fetch_users['id']; ?></span></p>
         <p> Username : <span><?= $fetch_users['name']; ?></span></p>
         <p> Email : <span><?= $fetch_users['email']; ?></span></p>
         <p> User type : 
            <span style="color:<?php if ($fetch_users['user_type'] == 'buyer') echo 'orange'; ?>">
               <?= $fetch_users['user_type']; ?>
            </span>
         </p>
         <p> Approved : 
            <span style="color:<?= $fetch_users['is_approved'] ? 'green' : 'red'; ?>">
               <?= $fetch_users['is_approved'] ? 'Yes' : 'No'; ?>
            </span>
         </p>

         <?php if (!$fetch_users['is_approved']): ?>
            <a href="total_farmers.php?approve=<?= $fetch_users['id']; ?>" class="option-btn" onclick="return confirm('Approve this user?');">Approve</a>
         <?php endif; ?>
         
         <a href="total_farmers.php?delete=<?= $fetch_users['id']; ?>" onclick="return confirm('Delete this user?');" class="delete-btn">Delete</a>
      </div>
      <?php } ?>
   </div>
</section>

<script src="js/script.js"></script>

</body>
</html>
