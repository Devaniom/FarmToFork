<?php
@include 'config.php';
session_start();

if (!isset($_SESSION['seller_id'])) {
    header('location: login.php');
    exit();
}

$seller_id = $_SESSION['seller_id']; // Get seller ID from session

if(isset($_POST['add_product'])){
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
   $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
   $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $insert_products = $conn->prepare("INSERT INTO `products`(name, category, details, price, image, seller_id) VALUES(?,?,?,?,?,?)");
   $insert_products->execute([$name, $category, $details, $price, $image, $_SESSION['seller_id']]);

   if ($insert_products) {
      if ($image_size > 2000000) {
         echo '<script>alert("Image size is too large!");</script>';
      } else {
         move_uploaded_file($image_tmp_name, $image_folder);
         echo '<script>alert("New product added!");</script>';
      }
   }
}


if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];

   // Check if the product belongs to this seller
   $check_product = $conn->prepare("SELECT * FROM `products` WHERE id = ? AND seller_id = ?");
   $check_product->execute([$delete_id, $seller_id]);

   if ($check_product->rowCount() > 0) {
       $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
       $delete_product->execute([$delete_id]);

       echo "<script>alert('Product deleted successfully!');</script>";
       header('location:farmer_products.php');
       exit();
   } else {
       echo "<script>alert('Error: You can only delete your own products!');</script>";
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

   <title>FramToFork | Farmer</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'farmer_header.php'; ?>

<section class="add-products">

   <h1 class="title">Add New Product</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
         <input type="text" name="name" class="box" required placeholder="enter product name">
         <select name="category" class="box" required>
            <option value="" selected disabled>Select Category</option>
               <option value="Vegitables">Vegitables</option>
               <option value="Fruits">Fruits</option>
         </select>
         </div>
         <div class="inputBox">
         <input type="number" min="0" name="price" class="box" required placeholder="enter product price">
         <input type="file" name="image" required class="box" accept="image/jpg, image/jpeg, image/png">
         </div>
      </div>
      <textarea name="details" class="box" required placeholder="enter product details" cols="30" rows="10"></textarea>
      <input type="submit" class="btn" value="add product" name="add_product">
   </form>

</section>

<script src="js/script.js"></script>

</body>
</html>