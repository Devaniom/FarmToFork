<?php

@include 'config.php';

session_start();

if (!isset($_SESSION['seller_id'])) {
   header('location: login.php');
   exit();
}

$seller_id = $_SESSION['seller_id'];

if (isset($_POST['update_order'])) {
   $order_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   $update_payment = filter_var($update_payment, FILTER_SANITIZE_STRING);

   $update_orders = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_orders->execute([$update_payment, $order_id]);

   $message[] = 'Payment has been updated!';
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_orders->execute([$delete_id]);
   header('location:farmer_orders.php');
   exit();
}

// Handle filters
$status = $_GET['status'] ?? 'all';
$search_product = $_GET['search_product'] ?? '';
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Build WHERE clause
$where = "WHERE seller_id = ?";
$params = [$seller_id];

if ($status === 'pending' || $status === 'completed') {
   $where .= " AND payment_status = ?";
   $params[] = $status;
}

if (!empty($search_product)) {
   $where .= " AND total_products LIKE ?";
   $params[] = "%$search_product%";
}

// Get total count for pagination
$total_stmt = $conn->prepare("SELECT COUNT(*) FROM `orders` $where");
$total_stmt->execute($params);
$total_rows = $total_stmt->fetchColumn();
$total_pages = ceil($total_rows / $limit);

// Fetch paginated results
$order_query = "SELECT * FROM `orders` $where LIMIT $limit OFFSET $offset";
$select_orders = $conn->prepare($order_query);
$select_orders->execute($params);

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
   <link rel="stylesheet" href="css/admin_style.css">

   <style>
      .tabs {
         display: flex;
         justify-content: center;
         gap: 20px;
         margin-bottom: 30px;
      }

      .tab {
         font-size: 1.5rem;
         padding: 10px 20px;
         cursor: pointer;
         background-color: #f0f0f0;
         border-radius: 5px;
         text-decoration: none;
      }

      .tab.active {
         background-color: #333;
         color: #fff;
      }

      .filters {
         text-align: center;
         margin-bottom: 20px;
      }

      .filters input,
      .filters select {
         padding: 8px;
         margin: 5px;
      }

      .pagination {
         display: flex;
         justify-content: center;
         margin-top: 20px;
         gap: 10px;
      }

      .pagination a {
         padding: 8px 12px;
         background: #eee;
         border-radius: 4px;
         text-decoration: none;
         color: #333;
      }

      .pagination a.active {
         background: #333;
         color: #fff;
      }
   </style>
</head>

<body>

   <?php include 'farmer_header.php'; ?>

   <section class="placed-orders">
      <h1 class="title">Placed Orders</h1>

      <div class="tabs">
         <a href="?status=all" class="tab <?= ($status == 'all') ? 'active' : '' ?>">All</a>
         <a href="?status=pending" class="tab <?= ($status == 'pending') ? 'active' : '' ?>">Pending</a>
         <a href="?status=completed" class="tab <?= ($status == 'completed') ? 'active' : '' ?>">Completed</a>
      </div>

      <form class="filters" method="GET">
         <input type="hidden" name="status" value="<?= htmlspecialchars($status) ?>">
         <input type="text" name="search_product" placeholder="Filter by product" value="<?= htmlspecialchars($search_product) ?>">
         <button type="submit">Search</button>
         <a href="?status=<?= $status ?>" style="margin-left: 10px;">Reset</a>
      </form>

      <div class="box-container">
         <?php
         if ($select_orders->rowCount() > 0) {
            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
         ?>
               <div class="box">
                  <p> Order ID: <span><?= htmlspecialchars($fetch_orders['id']); ?></span> </p>
                  <p> User ID: <span><?= htmlspecialchars($fetch_orders['user_id']); ?></span> </p>
                  <p> Placed on: <span><?= htmlspecialchars($fetch_orders['placed_on']); ?></span> </p>
                  <p> Name: <span><?= htmlspecialchars($fetch_orders['name']); ?></span> </p>
                  <p> Email: <span><?= htmlspecialchars($fetch_orders['email']); ?></span> </p>
                  <p> Number: <span><?= htmlspecialchars($fetch_orders['number']); ?></span> </p>
                  <p> Address: <span><?= htmlspecialchars($fetch_orders['address']); ?></span> </p>
                  <p> Total Products: <span><?= htmlspecialchars($fetch_orders['total_products']); ?></span> </p>
                  <p> Total Price: <span>₹<?= htmlspecialchars($fetch_orders['total_price']); ?>/-</span> </p>
                  <p> Payment Method: <span><?= htmlspecialchars($fetch_orders['method']); ?></span> </p>
                  <form action="" method="POST">
                     <input type="hidden" name="order_id" value="<?= htmlspecialchars($fetch_orders['id']); ?>">
                     <select name="update_payment" class="drop-down">
                        <option value="" selected disabled><?= htmlspecialchars($fetch_orders['payment_status']); ?></option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                     </select>
                     <div class="flex-btn">
                        <input type="submit" name="update_order" class="option-btn" value="Update">
                        <a href="farmer_orders.php?delete=<?= htmlspecialchars($fetch_orders['id']); ?>" class="delete-btn" onclick="return confirm('Delete this order?');">Delete</a>
                     </div>
                  </form>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">No ' . htmlspecialchars($status) . ' orders found!</p>';
         }
         ?>
      </div>

      <div class="pagination">
         <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
            <a href="?status=<?= htmlspecialchars($status) ?>&search_product=<?= urlencode($search_product) ?>&page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"> <?= $i ?> </a>
         <?php } ?>
      </div>
   </section>

   <script src="js/script.js"></script>

</body>

</html>
