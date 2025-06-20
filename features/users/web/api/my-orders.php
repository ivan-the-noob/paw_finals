<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORDERS | DIGITAL PAWS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/my-order.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDmgygVeipMUsrtGeZPZ9UzXRmcVdheIqw&libraries=places">
        
    </script>
      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
        <link rel="icon" href="../../../../assets/img/logo.png" type="image/x-icon">

</head>


<?php
session_start();
$email = isset($_SESSION['email']) ? $_SESSION['email'] : null;
include '../../../../db.php';

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];  // Get the email from session
    
    // Prepare and execute the query to fetch orders where from_cart = 1 and match the email
    $sql = "SELECT * FROM checkout WHERE from_cart = 1 AND email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);  // Bind the email parameter
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Fetch all orders as an associative array
    $orders = $result->fetch_all(MYSQLI_ASSOC);
} else {
    // No email in session, so set orders as empty
    $orders = [];
}


?>

<nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
            <a class="navbar-brand d-none d-lg-block" href="../../../../index.php">
                    <img src="../../../../assets/img/logo.png" alt="Logo" width="30" height="30">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        style="stroke: black; fill: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>

                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="../../../../index.php">Home</a>
                        </li>
                       
                    </ul>
                    <div class="d-flex ml-auto">
                        <?php if ($email): ?>
                            <!-- Profile Dropdown -->
                            <div class="dropdown second-dropdown">
                                <button class="btn" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="../../../../assets/img/<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="Profile Image" class="profile">
                                </button>
                                <ul class="dropdown-menu custom-center-dropdown" aria-labelledby="dropdownMenuButton2">
                                    <li><a class="dropdown-item" href="dashboard.php">Profile</a></li>
                                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                </ul>
                            </div>
                          <?php
                            include '../../function/php/count_cart.php';
                          ?>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                        <a href="../../function/php/update_cart_status.php" class="header-cart">
                            <span class="material-symbols-outlined">
                                shopping_cart
                            </span>

                            <?php if ($newCartData > 0): ?>
                                <span class="badge"><?= $newCartData ?></span>
                            <?php endif; ?>
                        </a>
                                <a href="my-orders.php" class="header-cart">
                                    <span class="material-symbols-outlined">
                                        local_shipping
                                    </span>
                                </a>
                                <div class="dropdown">
                                    <a href="#" class="header-cart " data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="material-symbols-outlined">
                                        notifications
                                        </span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" style="width: 300px; height: 400px; overflow-y: auto;">
                                    <?php
                                        include '../../../../db.php';
                                       

                                        $email = $_SESSION['email'] ?? '';

                                        if ($email) {
                                            $query = "SELECT message, created_at FROM notification WHERE email = ? ORDER BY id DESC";
                                            $stmt = $conn->prepare($query);
                                            $stmt->bind_param("s", $email);
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            if ($result && $result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $message = $row['message'];
                                                    $created_at = $row['created_at'];

                                                    // Format the created_at date as "April 4, 5:00 PM"
                                                    $formatted_date = date('F j, g:i a', strtotime($created_at));

                                                    // Apply styles for the message
                                                    $classes = 'dropdown-item bg-white shadow-sm px-3 py-2 rounded';
                                                    $style = 'box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);';

                                                    if (trim($message) == "Your appointment has been approved!") {
                                                        $classes .= ' text-success';
                                                    } else if (trim($message) == "Your checkout has been approved") {
                                                        $classes .= ' text-success';
                                                    } else if (trim($message) == "Your item has been picked up by courier. Please ready payment for COD.") {
                                                        $classes .= ' text-info';
                                                    } else if (trim($message) == "Your profile info has been updated.") {
                                                        $classes .= ' text-info';
                                                    } else if (trim($message) == "New services offered! Check it now!") {
                                                        $classes .= ' text-success';
                                                    } else if (trim($message) == "New product has been arrived! Check it now!") {
                                                        $classes .= ' text-success';
                                                    }

                                                    // Display the message with the date below
                                                    echo "<li><a class=\"$classes d-flex flex-column mx-auto\" href=\"#\" style=\"$style\">";
                                                    echo "<span>$message</span>";
                                                    echo "<div style=\"font-size: 0.9em; color: black; margin-top: 5px;\">$formatted_date</div></a></li>";
                                                    echo "<li><hr class=\"dropdown-divider\"></li>";
                                                }
                                            } else {
                                                echo "<li><a class=\"dropdown-item bg-white shadow-sm\" href=\"#\">No notifications</a></li>";
                                            }

                                            $stmt->close();
                                        } else {
                                            echo "<li><a class=\"dropdown-item bg-white shadow-sm\" href=\"#\">Please log in to see notifications</a></li>";
                                        }

                                 
                                        ?>
                                    </ul>

                                </div>
                            </div>
                            </div>


                        <?php else: ?>
                            <a href="features/users/web/api/login.php" class="btn-theme" type="button">Login</a>
                        <?php endif; ?>
                    </div>

        </nav>


<body>
<style>
    #successMessage {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #28a745;  /* Green background for success */
        color: white;
        padding: 10px;
        border-radius: 5px;
        z-index: 1000;
        display: none; /* Initially hidden */
    }
    .alert {
        font-size: 16px;
        font-weight: bold;
    }
</style>

<?php
if (isset($_GET['status']) && $_GET['status'] == 'order-success') {
    // Output the success message
    echo "<div id='successMessage' class='alert alert-success'>Order Successfully!</div>";
}
?>

<script>
    window.onload = function() {
        const successMessage = document.getElementById('successMessage');
        if (successMessage) {
            // Show the success message
            successMessage.style.display = 'block';
            
            // Set a timer to remove the message after 3 seconds
            setTimeout(function() {
                successMessage.style.display = 'none';
            }, 3000); // 3000 milliseconds = 3 seconds
        }
    };
</script>
  <div class="container mt-4">
    <div class="row">
      <h5>My Orders</h5>

      <div class="order-button d-flex gap-1 mt-4">
        <button class="button-highlight" onclick="showSection('orders')">Orders</button>
        <button onclick="showSection('to-ship')">To Ship</button>
        <button onclick="showSection('to-receive')">To Receive</button>
        <button onclick="showSection('received-orders')">Received Orders</button>
        <button onclick="showSection('cancelled-orders')">Cancelled Orders</button>
      </div>
    </div>
   <?php
// Get the user's email from the session
$email = $_SESSION['email'];

// Orders query
$sql = "SELECT * FROM checkout WHERE status = 'orders' AND email = '$email' ORDER BY created_at ASC";
$result = $conn->query($sql);

$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

// Group orders by created_at
$groupedOrders = [];
foreach ($orders as $order) {
    $createdAt = $order['created_at']; // Assuming created_at is in 'Y-m-d H:i:s' format
    $groupedOrders[$createdAt][] = $order;
}

// To Ship query
$sql_to_ship = "SELECT * FROM checkout WHERE status = 'to-ship' AND email = '$email' ORDER BY created_at ASC";
$result_to_ship = $conn->query($sql_to_ship);

$to_ship_orders = [];
if ($result_to_ship->num_rows > 0) {
    while ($row = $result_to_ship->fetch_assoc()) {
        $to_ship_orders[] = $row;
    }
}

// Group to-ship orders by created_at
$groupedToShipOrders = [];
foreach ($to_ship_orders as $order) {
    $createdAt = $order['created_at'];
    $groupedToShipOrders[$createdAt][] = $order;
}

// To Receive query
$sql_to_receive = "SELECT * FROM checkout WHERE status = 'to-receive' AND email = '$email' ORDER BY created_at ASC";
$result_to_receive = $conn->query($sql_to_receive);

$to_receive_orders = [];
if ($result_to_receive->num_rows > 0) {
    while ($row = $result_to_receive->fetch_assoc()) {
        $to_receive_orders[] = $row;
    }
}

// Group to-receive orders by created_at
$groupedToReceiveOrders = [];
foreach ($to_receive_orders as $order) {
    $createdAt = $order['created_at'];
    $groupedToReceiveOrders[$createdAt][] = $order;
    $is_rated = $order['is_rated'];
}

// Completed query
$sql_completed = "SELECT * FROM checkout WHERE status = 'received-order' AND email = '$email' ORDER BY created_at ASC";
$result_completed = $conn->query($sql_completed);

$completed_orders = [];
if ($result_completed->num_rows > 0) {
    while ($row = $result_completed->fetch_assoc()) {
        $completed_orders[] = $row;
        $is_rated = $row['is_rated'];
    }
}

// Group completed orders by created_at
$groupedCompletedOrders = [];
foreach ($completed_orders as $order) {
    $createdAt = $order['created_at'];
    $groupedCompletedOrders[$createdAt][] = $order;
}

// Cancelled query
$sql_cancelled = "SELECT * FROM checkout WHERE status = 'cancel' AND email = '$email' ORDER BY created_at ASC";
$result_cancelled = $conn->query($sql_cancelled);

$cancelled_orders = [];
if ($result_cancelled->num_rows > 0) {
    while ($row = $result_cancelled->fetch_assoc()) {
        $cancelled_orders[] = $row;
    }
}

// Group cancelled orders by created_at
$groupedCancelledOrders = [];
foreach ($cancelled_orders as $order) {
    $createdAt = $order['created_at'];
    $groupedCancelledOrders[$createdAt][] = $order;
}
?>

<div class="orders">
<?php foreach ($groupedOrders as $createdAt => $ordersGroup): ?>
            <div class="card p-3 mt-4">
                <div class="d-flex gap-1 mb-3 justify-content-end">
                    <p class="p-2 pending">Pending</p>
                    <button type="button" class="success-btn" data-bs-toggle="modal" data-bs-target="#buyAgainModal">
                        Buy Again
                    </button>
                    <button class="cancel btn btn-danger" data-id="<?php echo htmlspecialchars($ordersGroup[0]['id']); ?>">Cancel</button>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <?php
                        $totalSubTotal = 0;
                        $totalShippingFee = 0;
                        foreach ($ordersGroup as $order):
                            $totalSubTotal += $order['cost'] * $order['quantity'];
                            $totalShippingFee = $order['shipping_fee'];
                        ?>
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <img src="../../../../assets/img/product/<?php echo htmlspecialchars($order['product_img']); ?>" alt="Product Image" class="img-fluid" style="border-radius: 10px;" />
                                </div>
                                <div class="col-md-7">
                                    <h5 class="card-title mb-1"><?php echo htmlspecialchars($order['product_name']); ?></h5>
                                    <p class="mb-0">Quantity: <?php echo htmlspecialchars($order['quantity']); ?></p>
                                </div>
                                <div class="col-md-3 text-end">
                                    <p class="mb-1">Subtotal: <span class="price">₱<?php echo number_format($order['cost'] * $order['quantity'], 2); ?></span></p>
                                </div>
                            </div>
                            <hr>
                        <?php endforeach; ?>
                        <p class="total-row mb-1 d-flex justify-content-end">Shipping Fee: Via lalamove</p>
                        <p class="total-row d-flex justify-content-end">Total: <span class="price">₱<?php echo number_format($totalSubTotal + $totalShippingFee, 2); ?></span></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Buy Again Modal -->
        <div class="modal fade" id="buyAgainModal" tabindex="-1" aria-labelledby="buyAgainModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="buyAgainModalLabel">Buy Again</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <?php foreach ($orders as $order): ?>
                                <div class="col-md-4">
                                    <div class="card">
                                        <img src="../../../../assets/img/product/<?php echo htmlspecialchars($order['product_img']); ?>" class="card-img-top" alt="Product Image">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($order['product_name']); ?></h5>
                                            <form action="../../function/php/buyagain.php" method="post">
                                                <input type="hidden" name="id" value="<?= $order['id'] ?>">
                                                <div class="form-group mb-2">
                                                    <label for="quantity_<?= $order['id'] ?>">Quantity</label>
                                                    <input type="number" class="form-control" id="quantity_<?= $order['id'] ?>" name="quantity" value="1" min="1" required>
                                                </div>
                                                <button type="submit" class="btn btn-success w-100">Buy Again</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

   
</div>
<script>
document.querySelectorAll('.cancel').forEach(button => {
    button.addEventListener('click', function() {
        const orderId = this.getAttribute('data-id'); 
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../../function/php/update_order_status.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status == 200) {
                const response = xhr.responseText.trim();
                if (response === 'success') {
                    alert('Order cancelled successfully!');

                    location.reload();
                } else {
                    alert('Failed to cancel: ' + response);
                }
            } else {
                console.log('Error:', xhr.statusText);
            }
        };
        xhr.send('id=' + orderId + '&status=cancel'); 
    });
});
</script>





<script>
 document.querySelectorAll('.cancel').forEach(button => {
    button.addEventListener('click', function() {
        const orderId = this.getAttribute('data-id'); 

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../../function/php/update_order_status.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        xhr.onload = function() {
    if (xhr.status == 200) {
        console.log(xhr.responseText); 
        showSection('cancelled-orders'); 
    } else {
        console.log('Error:', xhr.statusText);
    }
};

        xhr.send('id=' + orderId + '&status=Cancel'); 
    });
});

</script>


<div class="to-ship">
    <?php if (!empty($to_ship_orders)): ?>
        <?php
        // Group to-ship orders by created_at
        $groupedToShipOrders = [];
        foreach ($to_ship_orders as $order) {
            $createdAt = $order['created_at']; // Assuming created_at is in 'Y-m-d H:i:s' format
            $groupedToShipOrders[$createdAt][] = $order;
        }
        ?>

        <!-- Display To-Ship Orders Grouped by created_at -->
        <?php foreach ($groupedToShipOrders as $createdAt => $ordersGroup): ?>
            <div class="card p-3 mt-4">
                <div class="d-flex gap-1 mb-3 justify-content-end">
                    <p class="p-2 to-ship-w">To Ship</p>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <?php
                        $totalToShipSubTotal = 0;
                        $totalToShipShippingFee = 0;
                        foreach ($ordersGroup as $order):
                            $totalToShipSubTotal += $order['cost'] * $order['quantity']; 
                            $totalToShipShippingFee = $order['shipping_fee']; 
                        ?>
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <img src="../../../../assets/img/product/<?php echo htmlspecialchars($order['product_img']); ?>" alt="Product Image" class="img-fluid" style="border-radius: 10px;" />
                                </div>
                                <div class="col-md-7">
                                    <h5 class="card-title mb-1"><?php echo htmlspecialchars($order['product_name']); ?></h5>
                                    <p class="mb-0">Quantity: <?php echo htmlspecialchars($order['quantity']); ?></p>
                                </div>
                                <div class="col-md-3 text-end">
                                    <p class="mb-1">Subtotal: <span class="price">₱<?php echo number_format($order['cost'] * $order['quantity'], 2); ?></span></p>
                                </div>
                            </div>
                            <hr>
                        <?php endforeach; ?>

                        <p class="total-row mb-1 d-flex justify-content-end">Shipping Fee: Via lalamove</p>
                        <p class="total-row d-flex justify-content-end">Total: <span class="price">₱<?php echo number_format($totalToShipSubTotal + $totalToShipShippingFee, 2); ?></span></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    <?php else: ?>
        <p>No to-ship orders available.</p>
    <?php endif; ?>
</div>


<div class="to-receive">
    <?php if (!empty($to_receive_orders)): ?>
        <?php
        // Group to-receive orders by created_at
        $groupedToReceiveOrders = [];
        foreach ($to_receive_orders as $order) {
            $createdAt = $order['created_at']; // Assuming created_at is in 'Y-m-d H:i:s' format
            $groupedToReceiveOrders[$createdAt][] = $order;
        }
        ?>

        <!-- Display To-Receive Orders Grouped by created_at -->
        <?php foreach ($groupedToReceiveOrders as $createdAt => $ordersGroup): ?>
            <div class="card p-3 mt-4">
                <div class="d-flex gap-1 mb-3 justify-content-end">
                        <p class="p-2 to-receive-w">To Receive</p>
                        <form action="../../function/php/update-receive.php" method="POST" onsubmit="return confirm('Mark all items as received?');">
                            <input type="hidden" name="created_at" value="<?php echo htmlspecialchars($createdAt); ?>">
                            <button type="submit" class="btn btn-success btn-sm">Mark as Received</button>
                        </form>
                    
                </div>
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <?php
                        $totalToReceiveSubTotal = 0;
                        $totalToReceiveShippingFee = 0;
                        foreach ($ordersGroup as $order):
                            $totalToReceiveSubTotal += $order['cost'] * $order['quantity']; 
                            $totalToReceiveShippingFee = $order['shipping_fee']; 
                        ?>
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <img src="../../../../assets/img/product/<?php echo htmlspecialchars($order['product_img']); ?>" alt="Product Image" class="img-fluid" style="border-radius: 10px;" />
                                </div>
                                <div class="col-md-7">
                                    <h5 class="card-title mb-1"><?php echo htmlspecialchars($order['product_name']); ?></h5>
                                    <p class="mb-0">Quantity: <?php echo htmlspecialchars($order['quantity']); ?></p>
                                </div>
                                <div class="col-md-3 text-end">
                                    <p class="mb-1">Subtotal: <span class="price">₱<?php echo number_format($order['cost'] * $order['quantity'], 2); ?></span></p>
                                </div>
                            </div>
                            <hr>
                        <?php endforeach; ?>

                        <p class="total-row mb-1 d-flex justify-content-end">Shipping Fee: Via lalamove</p>
                        <p class="total-row d-flex justify-content-end">Total: <span class="price">₱<?php echo number_format($totalToReceiveSubTotal + $totalToReceiveShippingFee, 2); ?></span></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    <?php else: ?>
        <p>No to-receive orders available.</p>
    <?php endif; ?>
</div>

<div class="received-orders">
    <?php if (!empty($completed_orders)): ?>
        <?php
        // Group completed orders by created_at
        $groupedCompletedOrders = [];
        foreach ($completed_orders as $order) {
            $groupedCompletedOrders[$order['created_at']][] = $order;
        }
        ?>

        <?php foreach ($groupedCompletedOrders as $createdAt => $ordersGroup): ?>
            <div class="card p-3 mt-4">
                <div class="d-flex gap-1 mb-3 justify-content-between align-items-center">
                    <p class="p-2 completed-orders mb-0">Completed Orders</p>

                    <!-- Rate Button (only show if not rated) -->
                   <?php if ($is_rated == 0): ?>
                        <button class="btn btn-warning btn-sm text-white fw-bold" data-bs-toggle="modal" data-bs-target="#ratingModal">
                            Rate our service
                        </button>
                    <?php endif; ?>

                </div>

                <div class="row align-items-center">
                    <div class="col-md-12">
                        <?php
                        $totalCompletedSubTotal = 0;
                        $totalCompletedShippingFee = 0;
                        foreach ($ordersGroup as $order):
                            $totalCompletedSubTotal += $order['cost'] * $order['quantity']; 
                            $totalCompletedShippingFee = $order['shipping_fee']; 
                        ?>
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <img src="../../../../assets/img/product/<?php echo htmlspecialchars($order['product_img']); ?>" alt="Product Image" class="img-fluid" style="border-radius: 10px;" />
                                </div>
                                <div class="col-md-7">
                                    <h5 class="card-title mb-1"><?php echo htmlspecialchars($order['product_name']); ?></h5>
                                    <p class="mb-0">Quantity: <?php echo htmlspecialchars($order['quantity']); ?></p>
               
                                </div>
                                <div class="col-md-3 text-end">
                                    <p class="mb-1">Subtotal: <span class="price">₱<?php echo number_format($order['cost'] * $order['quantity'], 2); ?></span></p>
                                </div>
                            </div>
                            <hr>
                        <?php endforeach; ?>

                        <p class="total-row mb-1 d-flex justify-content-end">Shipping Fee: Via lalamove</p>
                        <p class="total-row d-flex justify-content-end">Total: <span class="price">₱<?php echo number_format($totalCompletedSubTotal + $totalCompletedShippingFee, 2); ?></span></p>
                    </div>
                </div>
            </div>

            <!-- Rating Modal -->
            <div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <form id="ratingForm" action="../../function/php/submit_rating.php" method="POST">
                        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                          <input type="hidden" name="id" value="<?php echo htmlspecialchars($order['id']); ?>">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ratingModalLabel">Rate Our Service</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                               <div class="mb-3 text-center">
                                    <input type="hidden" name="rating" id="ratingValue">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fa-regular fa-star fa-2x star" data-value="<?php echo $i; ?>" style="cursor:pointer; color: #ccc;"></i>
                                    <?php endfor; ?>
                                    <div id="ratingError" class="text-danger mt-2" style="display:none;">Star rating is required.</div>
                                </div>


                                <div class="mb-3">
                                    <label for="comment" class="form-label fw-bold">Comments (optional)</label>
                                    <textarea name="comment" class="form-control" rows="3" placeholder="Tell us what you think..."></textarea>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-warning text-white fw-bold">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>

    <?php else: ?>
        <p>No completed orders available.</p>
    <?php endif; ?>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const allStars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('ratingValue');
    const form = document.getElementById('ratingForm');
    const ratingError = document.getElementById('ratingError');

    // Star click logic
    allStars.forEach((star, index) => {
        star.addEventListener('click', function () {
            const rating = this.getAttribute('data-value');
            ratingInput.value = rating;
            ratingError.style.display = 'none';

            // Highlight selected stars
            allStars.forEach((s, i) => {
                s.classList.remove('fa-solid');
                s.classList.add('fa-regular');
                s.style.color = '#ccc';

                if (i < rating) {
                    s.classList.remove('fa-regular');
                    s.classList.add('fa-solid');
                    s.style.color = '#ffc107';
                }
            });
        });
    });

    // Validate form before submitting
    form.addEventListener('submit', function (e) {
        if (!ratingInput.value) {
            e.preventDefault();
            ratingError.style.display = 'block';
        }
    });
});
</script>







<div class="cancelled-orders">
    <?php if (!empty($cancelled_orders)): ?>
        <?php
        // Group cancelled orders by created_at
        $groupedCancelledOrders = [];
        foreach ($cancelled_orders as $order) {
            $createdAt = $order['created_at']; // Assuming created_at is in 'Y-m-d H:i:s' format
            $groupedCancelledOrders[$createdAt][] = $order;
        }
        ?>

        <!-- Display Cancelled Orders Grouped by created_at -->
        <?php foreach ($groupedCancelledOrders as $createdAt => $ordersGroup): ?>
            <div class="card p-3 mt-4">
                <div class="d-flex gap-1 mb-3 justify-content-end">
                    <p class="p-2 cancelled-order">Cancelled Orders</p>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <?php
                        $totalCancelledSubTotal = 0;
                        $totalCancelledShippingFee = 0;
                        foreach ($ordersGroup as $order):
                            $totalCancelledSubTotal += $order['cost'] * $order['quantity']; 
                            $totalCancelledShippingFee = $order['shipping_fee']; 
                        ?>
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <img src="../../../../assets/img/product/<?php echo htmlspecialchars($order['product_img']); ?>" alt="Product Image" class="img-fluid" style="border-radius: 10px;" />
                                </div>
                                <div class="col-md-7">
                                    <h5 class="card-title mb-1"><?php echo htmlspecialchars($order['product_name']); ?></h5>
                                    <p class="mb-0">Quantity: <?php echo htmlspecialchars($order['quantity']); ?></p>
                                </div>
                                <div class="col-md-3 text-end">
                                    <p class="mb-1">Subtotal: <span class="price">₱<?php echo number_format($order['cost'] * $order['quantity'], 2); ?></span></p>
                                </div>
                            </div>
                            <hr>
                        <?php endforeach; ?>

                        <p class="total-row mb-1 d-flex justify-content-end">Shipping Fee: Via lalamove</p>
                        <p class="total-row d-flex justify-content-end">Total: <span class="price">₱<?php echo number_format($totalCancelledSubTotal + $totalCancelledShippingFee, 2); ?></span></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    <?php else: ?>
        <p>No cancelled orders available.</p>
    <?php endif; ?>
</div>


  </div>

  <script>
 
 document.addEventListener("DOMContentLoaded", function () {
        showSection('orders');

        document.querySelectorAll('.order-button button').forEach(button => {
            button.addEventListener('click', function (event) {
                const section = this.textContent.trim().toLowerCase().replace(' ', '-');
                showSection(section, event);
            });
        });
    });

    function showSection(section, event = null) {
    document.querySelectorAll('.order-button button').forEach(button => {
        button.classList.remove('button-highlight');
        if (button.textContent.trim().toLowerCase().replace(' ', '-') === section) {
            button.classList.add('button-highlight');
        }
    });

    document.querySelectorAll('.orders, .to-ship, .to-receive, .received-orders, .cancelled-orders').forEach(div => {
        div.style.display = 'none';
    });

    document.querySelector(`.${section}`).style.display = 'block';
}
  </script>
</body>
<!--Header End-->


<script src=" https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js">
</script>
<script src="../../function/script/chatbot-toggle.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
</script>

</html>