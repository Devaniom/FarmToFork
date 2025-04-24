<?php @include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}
;

if (isset($_POST['order'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = 'flat no. ' . $_POST['flat'] . ' ' . $_POST['street'] . ' ' . $_POST['city'] . ' ' . $_POST['state'] . ' ' . $_POST['country'] . ' - ' . $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $placed_on = date('d-M-Y');

   $cart_total = 0;
$cart_products = [];
$seller_id = null;  // Variable to store seller_id

// Query to fetch cart items along with seller_id from the products table
$cart_query = $conn->prepare("
   SELECT c.*, p.seller_id 
   FROM `cart` c 
   JOIN `products` p ON c.pid = p.id  -- Using pid as product ID
   WHERE c.user_id = ?
");
$cart_query->execute([$user_id]);

if ($cart_query->rowCount() > 0) {
    while ($cart_item = $cart_query->fetch(PDO::FETCH_ASSOC)) {
        $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ')';
        $sub_total = ($cart_item['price'] * $cart_item['quantity']);
        $cart_total += $sub_total;
        $seller_id = $cart_item['seller_id'];  // Fetch seller_id
    }
}

// Convert product list to string
$total_products = implode(', ', $cart_products);

// Check if the cart is empty before placing an order
if ($cart_total == 0) {
    $message[] = 'Your cart is empty!';
} else {
    // Insert order into the database with seller_id
    $insert_order = $conn->prepare("
        INSERT INTO `orders` (seller_id, user_id, name, number, email, method, address, total_products, total_price, placed_on)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $insert_order->execute([$seller_id, $user_id, $name, $number, $email, $method, $address, $total_products, $cart_total, $placed_on]);

    // Clear the cart after placing the order
    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart->execute([$user_id]);

    $_SESSION['message'] = 'Order placed successfully!';
    header('location: checkout.php');
    exit();
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

   <title>FramToFork | Checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'shop_header.php'; ?>

   <section class="display-orders">

      <?php
      $cart_grand_total = 0;
      $select_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart_items->execute([$user_id]);
      if ($select_cart_items->rowCount() > 0) {
         while ($fetch_cart_items = $select_cart_items->fetch(PDO::FETCH_ASSOC)) {
            $cart_total_price = ($fetch_cart_items['price'] * $fetch_cart_items['quantity']);
            $cart_grand_total += $cart_total_price;
            ?>
            <p> <?= $fetch_cart_items['name']; ?>
               <span>(<?= '₹' . $fetch_cart_items['price'] . '/- x ' . $fetch_cart_items['quantity']; ?>)</span> </p>
            <?php
         }
      } else {
         echo '<p class="empty">your cart is empty!</p>';
      }
      ?>
      <div class="grand-total">Grand Total : <span>₹<?= $cart_grand_total; ?>/-</span></div>
   </section>

   <section class="checkout-orders">

      <form action="" method="POST">

         <h3>Place Your Order</h3>

         <div class="flex">
            <div class="inputBox">
               <span>Your Name :</span>
               <input type="text" name="name" placeholder="enter your name" class="box" required>
            </div>
            <div class="inputBox">
               <span>Your Number :</span>
               <input type="number" name="number" placeholder="enter your number" class="box" required>
            </div>
            <div class="inputBox">
               <span>Your Email :</span>
               <input type="email" name="email" placeholder="enter your email" class="box" required>
            </div>
            <div class="inputBox">
               <span>Payment Method :</span>
               <select name="method" class="box" required>
                  <option value="cash on delivery">Cash On Delivery</option>
               </select>
            </div>
            <div class="inputBox">
               <span>Address :</span>
               <input type="text" name="flat" placeholder="e.g. flat number" class="box" required>
            </div>
            <div class="inputBox">
               <span>Near By :</span>
               <input type="text" name="street" placeholder="e.g. street name" class="box" required>
            </div>
            <div class="inputBox">
               <span>City :</span>
               <input type="text" name="city" placeholder="e.g. mumbai" class="box" required>
            </div>
            <div class="inputBox">
               <span>State :</span>
               <input type="text" name="state" placeholder="e.g. maharashtra" class="box" required>
            </div>
            <div class="inputBox">
               <span>Country :</span>
               <input type="text" name="country" placeholder="e.g. India" class="box" required>
            </div>
            <div class="inputBox">
               <span>Pin Code :</span>
               <input type="number" min="0" name="pin_code" placeholder="e.g. 123456" class="box" required>
            </div>
         </div>

         <input type="submit" name="order" class="btn <?= ($cart_grand_total > 1) ? '' : 'disabled'; ?>"
            value="place order">

      </form>

   </section> 

   <script src="js/script.js"></script>
   <?php if(isset($_SESSION['message'])): ?>
   <script>
      alert("<?php echo $_SESSION['message']; ?>");
      window.location.href = "orders.php";
   </script>
   <?php unset($_SESSION['message']); ?>
<?php endif; ?>


</body>

</html>