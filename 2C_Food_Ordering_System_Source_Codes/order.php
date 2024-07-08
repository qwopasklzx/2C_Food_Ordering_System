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

// Establish database connection
$conn = mysqli_connect("localhost", "root", "");

// display error message "Could not connect to the database" if the connection fails. 
if (!$conn) {
  die("Could not connect to MySQL Server: " . mysqli_connect_error());
} 

// access to the company database
mysqli_select_db($conn, 'twt2');

// Prepare and execute SQL query to fetch user data
$query_user = "SELECT * FROM Users WHERE UserID = '$UserID'";
$result_user = mysqli_query($conn, $query_user);


// Check if user data exists
if (mysqli_num_rows($result_user) > 0) {
    $user_data = mysqli_fetch_assoc($result_user);
    // use $user_data['ColumnName'] to access user details    

} else {
    // Redirect to login page if no user data found
    echo "<script>alert('No user data found.'); window.location.href = 'loginsignup.html';</script>";
    exit();
}

// Prepare and execute SQL query to fetch menu items
$query_menu = "SELECT ItemID, ItemName, Description, Price FROM menuitems";
$result_menu = mysqli_query($conn, $query_menu);

// Check if menu items data exists
$main_courses = [];
$drinks = [];
$desserts = [];

// Check if menu items data exists
if (mysqli_num_rows($result_menu) > 0) {
    while ($row = mysqli_fetch_assoc($result_menu)) {
        // Categorize menu items into respective arrays
        if (count($main_courses) < 4) {
            $main_courses[] = $row;
        } elseif (count($drinks) < 4) {
            $drinks[] = $row;
        } else {
            $desserts[] = $row;
        }
    }
} else {
    echo "No menu items found.";
}

// Close database connection
mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ordering Page</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="order.css">
    <link rel="shortcut icon" href="images/0.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
      .cart-item {
          display: flex;
          align-items: center;
          padding: 10px;
          border-bottom: 1px solid #ccc; /* Optional: Add a border bottom for separation */
          width:395px;
      }

      .cart-item-img {
          width: 80px; /* Adjust image width as needed */
          height: 80px; /* Adjust image height as needed */
          object-fit: cover; /* Maintain aspect ratio and cover space */
          margin-right: 15px; /* Adjust margin for spacing */
      }

      .item-info {
          flex: 1;
      }

      /*.cart-item-name {
          font-weight: bold;
          color: black;
          margin-bottom: 5px;
          white-space: nowrap; /* Prevent item name from wrapping 
          overflow: hidden; /* Hide overflow text if needed 
          text-overflow: ellipsis; /* Display ellipsis (...) if text overflows 
      }*/

      .cart-item-price {
          color: #555;
          margin-left: 35px;
          white-space: nowrap;
      }

      /* Quality Selector */
      .quantity-selector {
          display: flex;
          align-items: center;
          margin-left: 35px;

      }

      .quantity-selector button {
          background-color: #ddd;
          border: none;
          padding: 5px;
          cursor: pointer;
          font-size: 16px;
      }

      .quantity-selector input {
          width: 40px;
          text-align: center;
          border: 1px solid #ddd;
          margin: 0 5px;
      }

      .remove-item {
          margin-left: 30px;
          cursor: pointer;
          color: red;
      }

      .navigation button {
        background-color:white; 
        border:none;
        font-size:23.5px;
        font-weight: bold;
        margin-left: -7px;
        color:#333;
      }

      .navigation button:hover {
        color: rgb(121, 219, 252);
      }
    </style>
</head>

  <body>

    <div class="shoppingcart1">
      <div class="order-welcome">
        <img src="images/restaurant.gif" alt="logo">
        <h1>Hello, <?php echo htmlspecialchars($user_data['Username']); ?><h1>
        <div class="navigation">
              <ul>
                  <li><a href="#mainsection" id="mainbutton">Main Courses</a></li>
                  <li><a href="#drinksection" id="drinkbutton">Drinks</a></li>
                  <li><a href="#dessertsection" id="dessertbutton">Desserts</a></li>
                  <li><button onclick="logout()">Log Out</li>
              </ul>
        </div>
      </div>
      <header>
        <h1>Food Ordering Menu for Delivery</h1>
          <div class="shopping">
            <i class="fas fa-shopping-cart"></i>
            <span class="quantity">0</span>
          </div>
      </header>
    </div>

    <div class="sidecart">
      <div class="cart-header">
        <h1>Your Gourmet Cart</h1>
        <div class="closeshopping">
          <i class="fas fa-times"></i>
        </div>
      </div>
      <ul class="listcart"></ul>
      <div class="checkout">
         <div>
          <div class="total">Total:</div>
          <div class="totalprice">0</div>
         </div>
            <!-- Hidden form for submitting cart data -->
            
            <form id="orderForm" action="receipt.php" method="post" style="display:none;">
                <input type="hidden" name="cartData" id="cartData">
            </form>

            <button id="placeOrderButton" class="place-order-button">Place Order</button>
          
      </div>
    </div>
      
    <main class="container">
      
      <section id="mainsection">
        <div class="menu">
          <h2 class="menu-group-heading">Main Courses</h2>
          <div class="menu-group">

            <?php foreach ($main_courses as $item): ?>
            <div class="menu-item">
              <img src="<?php echo $item['ItemName']; ?>.jpg" alt="<?php echo $item['ItemName']; ?>" class="menu-item-img">
              <div class="menu-item-text">
                <h3 class="menu-item-heading">

                  <span class="menu-item-name"><?php echo htmlspecialchars($item['ItemName']); ?></span>
                  <span class="menu-item-price">RM <?php echo number_format($item['Price'], 2); ?></span>
                </h3>
                <p class="menu-item-desc">
                  <?php echo htmlspecialchars($item['Description']); ?>
                </p>
              <!--Add to Cart Button-->
              <button class="addtocart" data-item-id="<?php echo $item['ItemID']; ?>" data-item-name="<?php echo htmlspecialchars($item['ItemName']); ?>" data-item-price="<?php echo number_format($item['Price'], 2); ?>">
              <div class="pretext">
                  <i class="fas fa-cart-plus icon-spacing"></i> ADD
                </div>
                <div class="pretext done">
                  <div class="posttext"><i class="fas fa-check icon-spacing"></i> ADDED</div>
                </div>
                
              </button>
            </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
      <section id="drinksection">
        <div class="menu">
          <h2 class="menu-group-heading">Drinks</h2>
          <div class="menu-group">

            <?php foreach ($drinks as $item): ?>
              <div class="menu-item">
                <img src="<?php echo $item['ItemName']; ?>.jpg" alt="<?php echo $item['ItemName']; ?>" class="menu-item-img">
                <div class="menu-item-text">
                  <h3 class="menu-item-heading">

                    <span class="menu-item-name"><?php echo htmlspecialchars($item['ItemName']); ?></span>
                    <span class="menu-item-price">RM <?php echo number_format($item['Price'], 2); ?></span>
                  </h3>
                  <p class="menu-item-desc">
                    <?php echo htmlspecialchars($item['Description']); ?>
                  </p>
                  <!--Add to Cart Button-->
                  <button class="addtocart" data-item-id="<?php echo $item['ItemID']; ?>" data-item-name="<?php echo htmlspecialchars($item['ItemName']); ?>" data-item-price="<?php echo number_format($item['Price'], 2); ?>">
                  <div class="pretext">
                      <i class="fas fa-cart-plus icon-spacing"></i> ADD
                    </div>
                    <div class="pretext done">
                      <div class="posttext"><i class="fas fa-check icon-spacing"></i> ADDED</div>
                    </div>
                    
                  </button>
                </div>
              </div>
              <?php endforeach; ?>
          </div>
        </div>
      </section>
      <section id="dessertsection">
        <div class="menu">
          <h2 class="menu-group-heading">Desserts</h2>
          <div class="menu-group">
            
            <?php foreach ($desserts as $item): ?>
                <div class="menu-item">
                  <img src="<?php echo $item['ItemName']; ?>.jpg" alt="<?php echo $item['ItemName']; ?>" class="menu-item-img">
                  <div class="menu-item-text">
                    <h3 class="menu-item-heading">

                      <span class="menu-item-name"><?php echo htmlspecialchars($item['ItemName']); ?></span>
                      <span class="menu-item-price">RM <?php echo number_format($item['Price'], 2); ?></span>
                    </h3>
                    <p class="menu-item-desc">
                      <?php echo htmlspecialchars($item['Description']); ?>
                    </p>
                    <!--Add to Cart Button-->
                    <button class="addtocart" data-item-id="<?php echo $item['ItemID']; ?>" data-item-name="<?php echo htmlspecialchars($item['ItemName']); ?>" data-item-price="<?php echo number_format($item['Price'], 2); ?>">
                    <div class="pretext">
                        <i class="fas fa-cart-plus icon-spacing"></i> ADD
                      </div>
                      <div class="pretext done">
                        <div class="posttext"><i class="fas fa-check icon-spacing"></i> ADDED</div>
                      </div>
                      
                    </button>
                  </div>
                </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    </main>

    <!--Handle adding items to cart-->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
    const cart = []; // Array to store cart items
    const sideCart = document.querySelector('.sidecart');
    const cartQuantity = document.querySelector('.shopping .quantity');
    const cartTotalPrice = document.querySelector('.totalprice');
    const listCart = document.querySelector('.listcart');
    const orderForm = document.getElementById('orderForm');
    const cartDataInput = document.getElementById('cartData');
    const placeOrderButton = document.getElementById('placeOrderButton');
    const buttons = document.querySelectorAll('.addtocart');

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const done = button.querySelector('.done');
            const pretext = button.querySelector('.pretext');

            const itemID = button.getAttribute('data-item-id');
            const itemName = button.getAttribute('data-item-name');
            const itemPrice = parseFloat(button.getAttribute('data-item-price'));
            const itemImg = button.closest('.menu-item').querySelector('.menu-item-img').src;

            let existingCartItem = cart.find(item => item.id === itemID);

            if (!existingCartItem) {
                existingCartItem = {
                    id: itemID,
                    name: itemName,
                    price: itemPrice,
                    quantity: 0,
                    img: itemImg,
                    button: button
                };
                cart.push(existingCartItem);
            }

            existingCartItem.quantity++;

            done.style.transform = "translate(0)";
            pretext.style.transform = "translate(-110%)";
            button.disabled = true;

            updateCartDisplay();
        });
    });

    function updateCartDisplay() {
        listCart.innerHTML = '';

        let totalQuantity = 0;
        let totalPrice = 0;

        cart.forEach(item => {
            const listItem = document.createElement('li');
            listItem.classList.add('cart-item');
            listItem.innerHTML = `
                <div class="cart-item-details">
                    <img src="${item.img}" alt="${item.name}" class="cart-item-img">
                </div>
                <div class="quantity-selector">
                    <button class="decrease-quantity" data-item-id="${item.id}">-</button>
                    <input type="text" class="item-quantity" value="${item.quantity}" readonly>
                    <button class="increase-quantity" data-item-id="${item.id}">+</button>
                </div>
                <span class="cart-item-price">RM ${(item.price * item.quantity).toFixed(2)}</span>
                <i class="fas fa-trash remove-item" data-item-id="${item.id}"></i>
            `;
            listCart.appendChild(listItem);

            totalQuantity += item.quantity;
            totalPrice += item.price * item.quantity;

            listItem.querySelector('.decrease-quantity').addEventListener('click', () => {
                if (item.quantity > 1) {
                    item.quantity--;
                } else {
                    cart.splice(cart.indexOf(item), 1);
                    resetButtonState(item.button);
                }
                updateCartDisplay();
            });

            listItem.querySelector('.increase-quantity').addEventListener('click', () => {
                item.quantity++;
                updateCartDisplay();
            });

            listItem.querySelector('.remove-item').addEventListener('click', () => {
                cart.splice(cart.indexOf(item), 1);
                resetButtonState(item.button);
                updateCartDisplay();
            });
        });

        cartQuantity.textContent = totalQuantity;
        cartTotalPrice.textContent = `${totalPrice.toFixed(2)}`;
    }

    function resetButtonState(button) {
        const done = button.querySelector('.done');
        const pretext = button.querySelector('.pretext');
        done.style.transform = "translate(110%)";
        pretext.style.transform = "translate(0)";
        button.disabled = false;
    }

    placeOrderButton.addEventListener('click', () => {
        cartDataInput.value = JSON.stringify(cart);
        orderForm.submit();
    });
});

    function logout() {
      window.location.href = 'logout.php';
    }

    </script>
    <script src="order.js"></script>

    <footer>
    <p>
      Created with <i class="fa fa-utensils"></i> by
      <a>Guer</a>
      - Web Techniques and Application Project
      <a target="_blank" href="https://www.mmu.edu.my/programmes-by-faculty-all/programmes-by-faculty-fist/">FIST</a>
    </p>
  </footer>

  </body>
</html>
