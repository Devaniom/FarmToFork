<?php

@include 'config.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if (!isset($seller_id)) {
   header('location:login.php');
   exit();
}

if (isset($_POST['update_profile'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   $update_profile = $conn->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
   $update_profile->execute([$name, $email, $seller_id]);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/' . $image;
   $old_image = $_POST['old_image'];

   if (!empty($image)) {
      if ($image_size > 2000000) {
         $message[] = 'Image size is too large!';
      } else {
         $update_image = $conn->prepare("UPDATE `users` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $seller_id]);
         if ($update_image) {
            move_uploaded_file($image_tmp_name, $image_folder);
            //unlink('uploaded_img/' . $old_image);
            $message[] = 'Image updated successfully!';
         }
      }
   }

    // Fetch the old password from the database
    $check_pass_query = $conn->prepare("SELECT password FROM `users` WHERE id = ?");
    $check_pass_query->execute([$seller_id]);
    $fetch_pass = $check_pass_query->fetch(PDO::FETCH_ASSOC);
 
    if ($fetch_pass && password_verify($_POST['update_pass'], $fetch_pass['password'])) {
       if ($_POST['new_pass'] == $_POST['confirm_pass']) {
          $new_hashed_pass = password_hash($_POST['new_pass'], PASSWORD_DEFAULT);
          $update_pass_query = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
          $update_pass_query->execute([$new_hashed_pass, $seller_id]);
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

   <title>FarmToFork | Update Profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/components.css">

</head>

<body>

   <?php include 'farmer_header.php'; ?>

   <section class="update-profile">

      <h1 class="title">Update Profile</h1>

      <form action="" method="POST" enctype="multipart/form-data">
         <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
         <div class="flex">
            <div class="inputBox">
               <span>Username :</span>
               <input type="text" name="name" value="<?= $fetch_profile['name']; ?>" placeholder="Update username"
                  required class="box">
               <span>Email :</span>
               <input type="email" name="email" value="<?= $fetch_profile['email']; ?>" placeholder="Update email"
                  required class="box">
               <span>Update pic :</span>
               <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box">
               <input type="hidden" name="old_image" value="<?= $fetch_profile['image']; ?>">
            </div>
            <div class="inputBox">
               <input type="hidden" name="old_pass" value="<?= $fetch_profile['password']; ?>">
               <span>Old password :</span>
               <input type="password" name="update_pass" placeholder="Enter previous password" class="box">
               <span>New password :</span>
               <input type="password" name="new_pass" placeholder="Enter new password" class="box">
               <span>Confirm password :</span>
               <input type="password" name="confirm_pass" placeholder="Confirm new password" class="box">
            </div>
         </div>
         <div class="flex-btn">
            <input type="submit" class="btn" value="Update profile" name="update_profile">
            <a href="farmer.php" class="option-btn">Go back</a>
         </div>
      </form>

   </section>

   <script src="js/script.js"></script>

</body>

</html>
