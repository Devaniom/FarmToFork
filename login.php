<?php 
@include 'config.php';

session_start();

// Ensure a default admin exists
$default_admin_email = 'admin@example.com';
$default_admin_pass = 'admin123'; // Change this after first login
$hashed_pass = password_hash($default_admin_pass, PASSWORD_DEFAULT);

$sql_check_admin = "SELECT * FROM `users` WHERE email = ? AND user_type = 'admin'";
$stmt = $conn->prepare($sql_check_admin);
$stmt->execute([$default_admin_email]);

if ($stmt->rowCount() == 0) {
    $sql_insert_admin = "INSERT INTO `users` (name, email, password, user_type, is_approved) VALUES (?, ?, ?, 'admin', 1)";
    $stmt_insert = $conn->prepare($sql_insert_admin);
    $stmt_insert->execute(['Admin', $default_admin_email, $hashed_pass]);
}

// Handle login form submission
if(isset($_POST['submit'])){
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $pass = $_POST['pass'];
 
    $sql = "SELECT * FROM `users` WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    $message = []; // Initialize message array
 
    if($row){
       if(password_verify($pass, $row['password'])){ // Verify hashed password
          
          if($row['user_type'] == 'admin'){
             $_SESSION['admin_id'] = $row['id'];
             $_SESSION['profile_pic'] = ''; // Admin has no profile picture
             header('location:admin_page.php');
             exit();
          }

          elseif($row['user_type'] == 'seller'){
             if($row['is_approved'] == 1){
                $_SESSION['seller_id'] = $row['id'];
                $_SESSION['profile_pic'] = !empty($row['profile_pic']) ? $row['profile_pic'] : 'assets/images/favicon.png';
                header('location:farmer_page.php');
                exit();
             } else {
                $message[] = 'Your seller account is pending admin approval.';
             }
          }

          elseif($row['user_type'] == 'buyer'){
             $_SESSION['user_id'] = $row['id'];
             $_SESSION['profile_pic'] = !empty($row['profile_pic']) ? $row['profile_pic'] : 'assets/images/favicon.png';
             header('location:shop.php');
             exit();
          }

       } else {
          $message[] = 'Incorrect email or password!';
       }
    } else {
       $message[] = 'No user found!';
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

   <title>FarmToFork | Login</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/components.css">
</head>
<body>

<?php
if(!empty($message)){
   foreach($message as $msg){
      echo '<div class="message"><span>'.$msg.'</span><i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>';
   }
}
?>
   
<section class="form-container">
   <form action="" method="POST">
      <h3>Login Now</h3>
      <input type="email" name="email" class="box" placeholder="Enter your email" required>
      <input type="password" name="pass" class="box" placeholder="Enter your password" required>
      <input type="submit" value="Login Now" class="btn" name="submit">
      <p>Don't have an account? <a href="register.php">Register now</a></p>
   </form>
</section>

</body>
</html>
