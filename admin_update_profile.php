<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
   exit();
}

if(isset($_POST['update_profile'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   $update_profile = $conn->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
   $update_profile->execute([$name, $email, $admin_id]);


    // Fetch the old password from the database
    $check_pass_query = $conn->prepare("SELECT password FROM `users` WHERE id = ?");
    $check_pass_query->execute([$admin_id]);
    $fetch_pass = $check_pass_query->fetch(PDO::FETCH_ASSOC);
 
    if ($fetch_pass && password_verify($_POST['update_pass'], $fetch_pass['password'])) {
       if ($_POST['new_pass'] == $_POST['confirm_pass']) {
          $new_hashed_pass = password_hash($_POST['new_pass'], PASSWORD_DEFAULT);
          $update_pass_query = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
          $update_pass_query->execute([$new_hashed_pass, $admin_id]);
          $message[] = 'Password updated successfully!';
       } else {
          $message[] = 'Confirm password does not match!';
       }
    } else {
       $message[] = 'Old password is incorrect!';
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
   <title>FarmToFork | Update Admin Profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/components.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="update-profile">
   <h1 class="title">Update Profile</h1>
   <form action="" method="POST" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>Username :</span>
            <input type="text" name="name" value="<?= $fetch_profile['name']; ?>" placeholder="Update Username" required class="box">
            <span>Email :</span>
            <input type="email" name="email" value="<?= $fetch_profile['email']; ?>" placeholder="Update Email" required class="box">
         </div>
         <div class="inputBox">
            <input type="hidden" name="old_pass" value="<?= $fetch_profile['password']; ?>">
            <span>Old Password :</span>
            <input type="password" name="update_pass" placeholder="Enter Previous Password" class="box">
            <span>New Password :</span>
            <input type="password" name="new_pass" placeholder="Enter New Password" class="box">
            <span>Confirm Password :</span>
            <input type="password" name="confirm_pass" placeholder="Confirm New Password" class="box">
         </div>
      </div>
      <div class="flex-btn">
         <input type="submit" class="btn" value="update profile" name="update_profile">
         <a href="admin_page.php" class="option-btn">Go Back</a>
      </div>
   </form>
</section>

<script src="js/script.js"></script>

</body>
</html>
