<?php

include 'config.php';

if(isset($_POST['submit'])){

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   $pass = $_POST['pass'];
   $cpass = $_POST['cpass'];
   $user_type = filter_var($_POST['user_type'], FILTER_SANITIZE_STRING);
   
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $message = []; // Initialize message array

   // Check if email already exists
   $select = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select->execute([$email]);

   if($select->rowCount() > 0){
      $message[] = 'User email already exists!';
   } else {
      if($pass !== $cpass){
         $message[] = 'Confirm password not matched!';
      } else {
         $hashed_pass = password_hash($pass, PASSWORD_DEFAULT); // Secure password hashing

         // Insert into database with is_approved = 0
         $insert = $conn->prepare("INSERT INTO `users`(name, email, password, user_type, image, is_approved) VALUES(?,?,?,?,?,0)");
         $insert->execute([$name, $email, $hashed_pass, $user_type, $image]);

         if($insert){
            if($image_size > 2000000){
               $message[] = 'Image size is too large!';
            } else {
               if(move_uploaded_file($image_tmp_name, $image_folder)){
                  $message[] = 'Registered successfully! Awaiting admin approval.';
               } else {
                  $message[] = 'Failed to upload image!';
               }
            }
         }
      }
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

   <title>FramToFork | Register</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/components.css">
</head>
<body>

<?php
if(!empty($message)){
   foreach($message as $msg){
      echo '
      <div class="message">
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>
   
<section class="form-container">
   <form action="" enctype="multipart/form-data" method="POST">
      <h3>Register Now</h3>
      <input type="text" name="name" class="box" placeholder="Enter your name" required>
      <input type="email" name="email" class="box" placeholder="Enter your email" required>
      <input type="password" name="pass" class="box" placeholder="Enter your password" required>
      <input type="password" name="cpass" class="box" placeholder="Confirm your password" required>
      <select name="user_type" class="box">
         <option value="buyer">Buyer</option>
         <option value="seller">Seller</option>
         <!-- <option value="admin">Admin</option> -->
      </select>
      <input type="file" name="image" class="box" required accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="Register Now" class="btn" name="submit">
      <p>Already have an account? <a href="login.php">Login now</a></p>
   </form>
</section>

</body>
</html>
