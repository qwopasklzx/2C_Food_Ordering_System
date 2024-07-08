<?php
session_start();

// Check if session variable is set
if (!isset($_SESSION['UserID'])) {
    // Redirect to login page if not logged in
    echo "<script>alert('Please log in first.'); window.location.href = 'loginsignup.html';</script>";
    exit();
}

// Get User ID from session
$UserID = $_SESSION['UserID'];

// Check if cart data is set
if (!isset($_POST['cartData']) || empty($_POST['cartData'])) {
    echo "<script>alert('Cart is empty.'); window.location.href = 'receipt.php';</script>";
    exit();
}

// Decode cart data from JSON
$cartData = json_decode($_POST['cartData'], true);

// Establish database connection
$conn = mysqli_connect("localhost", "root", "");

// display error message "Could not connect to the database" if the connection fails. 
if (!$conn) {
    die("Could not connect to MySQL Server: " . mysqli_connect_error());
}

// access to the company database
mysqli_select_db($conn, 'twt2');

// Get User Details
$query_user = "SELECT Username FROM users WHERE UserID='$UserID'";
$result_user = mysqli_query($conn, $query_user);
$user = mysqli_fetch_assoc($result_user);
$username = $user['Username'];

// Insert into orders table
$orderDate = date('Y-m-d H:i:s');
$totalAmount = array_reduce($cartData, function($sum, $item) {
    return $sum + ($item['price'] * $item['quantity']);
}, 0);

$query_order = "INSERT INTO orders (UserID, OrderDate, TotalAmount) VALUES ('$UserID', '$orderDate', '$totalAmount')";
mysqli_query($conn, $query_order);
$orderID = mysqli_insert_id($conn);

// Insert into orderdetails table
foreach ($cartData as $item) {
    $itemID = $item['id'];
    $quantity = $item['quantity'];
    $subtotal = $item['price'] * $quantity;

    $query_orderdetails = "INSERT INTO orderdetails (OrderID, ItemID, Quantity, Subtotal) VALUES ('$orderID', '$itemID', '$quantity', '$subtotal')";
    mysqli_query($conn, $query_orderdetails);
}

// Close database connection
mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<html>
<head>
    <title>Receipt Page</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="receipt.css">
    <link rel="shortcut icon" href="images/0.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <!--<h1>Receipt</h1>
    <h1>Order Placed Successfully!</h1>
    <h1>Name</h1>
    <h1>Food Order + Quantity</h1>
    <h1>Total Price</h1>
    <h1>Please pay when food is arrived.</h1>-->


    <h2>GUER Foodie: Receipt</h2>
    <div class="container" id="container">
    
   

    <div class="form-container receipt-container">
      <form>
        <h1>Receipt</h1>
        <div class="social-container">
          <a href="#" class="social"><i class="fas fa-heart"></i></a>
          <a href="#" class="social"><i class="fas fa-smile"></i></a>
          <a href="#" class="social"><i class="fas fa-thumbs-up"></i></a>
        </div>
        <span>Order Placed Successfully!</span>
        <span>Name: <?php echo htmlspecialchars($username); ?></span>
        <span style="text-align: justify; display: block; margin-left:-15px;">
            <ol>
            <?php foreach ($cartData as $item): ?>
                <li><?php echo htmlspecialchars($item['name']) . ' - ' . htmlspecialchars($item['quantity']) . '<br>'; ?></li>
            <?php endforeach; ?>
            </ol>

        </span>
        <span>Total Price: RM <?php echo number_format($totalAmount, 2); ?></span>
        <span>Please pay when food arrives.</span>
      </form>
    </div>
    
    <div class="overlay-container">
      <div class="overlay">
        
        <div class="overlay-panel overlay-right">
          <h1>Thank you</h1>
          <p>Hope you enjoy your journey with us. Please visit us again.</p>
          
          <button class="ghost" id="signUp" onclick="redirectToOrder()">Order More</button>
          
        </div>
      </div>
    </div>
  </div>
  <script>
    function redirectToOrder() {
        window.location.href = 'order.php';
    }
  </script>
  

</body>
</html>