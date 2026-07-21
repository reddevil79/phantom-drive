<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();

include_once 'product-action.php';

// Calculate total items in cart for notification badge
$cart_count = 0;
$item_total = 0;

if (isset($_SESSION["c_item"])) {
    foreach ($_SESSION["c_item"] as $item) {
        $cart_count += $item["quantity"];
        $item_total += ($item["price"] * $item["quantity"]);
    }
}

if (empty($_SESSION['user_id'])) {
    header('location:login.php');
} else {
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="restaurant.png">
    <title>My Cart</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <style type="text/css" rel="stylesheet">
            /* Custom Cart Styling */
            .cart-widget {
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                margin-top: 20px;
                margin-bottom: 20px;
                

            }

            .cart-header {
                background: #ff3300;
                color: white;
                padding: 15px;
                font-weight: 600;
                border-radius: 8px 8px 0 0;
                position: relative;
            }

            .cart-header h3 {
                margin: 0;
                font-size: 18px;
                color: white;
                text-align: center;
            }

            .cart-item {
                padding: 15px;
                border-bottom: 1px solid #f0f0f0;
                position: relative;
                transition: all 0.3s ease;
            }

            .cart-item:hover {
                background-color: #f9f9f9;
            }

            .cart-item-title {
                font-weight: 600;
                margin-bottom: 10px;
                padding-right: 25px;
            }

            .cart-item-remove {
                position: absolute;
                right: 15px;
                top: 15px;
                color: #ff3300;
                background: none;
                border: none;
                font-size: 16px;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .cart-item-remove:hover {
                transform: scale(1.2);
                color: #c92800;
            }

            .cart-item-details {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .cart-item-price {
                font-weight: 500;
                color: #444;
                width: 25%;
            }

            .quantity-control {
                display: flex;
                align-items: center;
                border: 1px #ddd;
                border-radius: 4px;
                overflow: hidden;
            }

            .quantity-btn {
                background:#ccc;
                border: none;
                color: #555;
                width: 30px;
                height: 30px;
                font-size: 14px;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .quantity-btn:hover {
                background: #e5e5e5;
            }

            .quantity-input {
                width: 40px;
                text-align: center;
                border: none;
                border-left: 1px solid #dddd;
                border-right: 1px solid #dddd;
                height: 30px;
            }

            .cart-footer {
                background: #f9f9f9;
                padding: 20px;
                text-align: center;
            }

            .cart-total {
                font-size: 18px;
                font-weight: 600;
                margin-bottom: 15px;
            }

            .cart-total span {
                color: #ff3300;
            }

            .free-delivery {
                background: #e8f5e9;
                color: #388e3c;
                padding: 5px 10px;
                border-radius: 4px;
                display: inline-block;
                margin-bottom: 15px;
                font-weight: 500;
            }

            .checkout-btn {
                width: 100%;
                padding: 12px;
                font-size: 16px;
                font-weight: 600;
                border-radius: 4px;
                transition: all 0.3s ease;
            }

            .checkout-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .empty-cart {
                padding: 30px;
                text-align: center;
            }

            .empty-cart i {
                font-size: 50px;
                color: #ddd;
                margin-bottom: 15px;
            }

            .empty-cart p {
                font-size: 16px;
                color: #777;
            }

            .cart-table {
                width: 100%;
                margin: 0;
                border-collapse: separate;
                border-spacing: 0 15px;
            }

            .cart-table thead th {
                border-bottom: 2px solid #eee;
                padding: 15px;
                font-weight: 600;
                background: #f8f9fa;
            }

            .cart-table tbody td {
                padding: 15px;
                vertical-align: middle;
                border-bottom: 1px solid #f0f0f0;
            }

            .cart-table tr:last-child td {
                border-bottom: none;
            }

            .cart-table tr:hover td {
                background-color: #f9f9f9;
            }

            /* Cart Notification Badge */
            .cart-badge {
                position: relative;
                display: inline-block;
            }

            .notification-badge {
                position: absolute;
                top: -8px;
                right: -8px;
                background: #ff3300;
                color: white;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                font-weight: bold;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            }

            @media (max-width: 768px) {
                .cart-table thead {
                    display: none;
                }

                .cart-table tbody td {
                    display: block;
                    text-align: right;
                    padding: 10px;
                }

                .cart-table tbody td::before {
                    content: attr(data-label);
                    float: left;
                    font-weight: bold;
                    margin-right: 15px;
                }

                .cart-table tbody td:first-child {
                    background: #f8f9fa;
                    border-bottom: none;
                }

                .cart-table tbody tr {
                    margin-bottom: 15px;
                    display: block;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                    border-radius: 8px;
                }
            }

            /* Responsive Styles */
            @media only screen and (max-width: 768px) {
                .cart-item-details {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .cart-item-price {
                    margin: 10px 0;
                    width: 100%;
                }

                .quantity-control {
                    width: 100%;
                }
            }
    
        
       .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .btn-updating {
            position: relative;
            color: transparent !important;
        }
        
        .btn-updating:after {
            content: "";
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }


        </style>
    </head>

    <body>

        <!--header starts-->
        <header id="header" class="header-scroll top-header headrom">
            <!-- .navbar -->
            <nav class="navbar navbar-dark">
                <div class="container">
                    <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                    <a class="navbar-brand" href="index.php"> <img class="img-rounded" src="images/img/logo.png" alt="" style="height: 50px; width: 150px;"> </a>
                    <div class="collapse navbar-toggleable-md float-lg-right" id="mainNavbarCollapse">
                        <ul class="nav navbar-nav">
                            <li class="nav-item"> <a class="nav-link active" href="index.php">Home <span class="sr-only"></span></a> </li>
                            <li class="nav-item"> <a class="nav-link active" href="dishes.php">Products <span class="sr-only"></span></a> </li>
                            <li class="nav-item cart-badge">
                                <a class="nav-link active" href="cart.php">My Cart
                                    <?php if ($cart_count > 0): ?>
                                        <span class="notification-badge"><?php echo $cart_count; ?></span>
                                    <?php endif; ?>
                                    <span class="sr-only">(current)</span>
                                </a>
                            </li>

                            <?php
                            if (empty($_SESSION["user_id"])) {
                                echo '<li class="nav-item"><a href="login.php" class="nav-link active">Login</a> </li>
                              <li class="nav-item"><a href="registration.php" class="nav-link active">Sign Up</a> </li>';
                            } else {
                                echo  '<li class="nav-item"><a href="your_orders.php" class="nav-link active">Your Orders</a> </li>';
                                echo  '<li class="nav-item"><a href="logout.php" class="nav-link active">LogOut</a> </li>';
                            }
                            ?>

                        </ul>
                    </div>
                </div>
            </nav>
            <!-- /.navbar -->
        </header>
        <div class="page-wrapper">

              <div class="page-wrapper">
        <div class="inner-page-hero bg-image" data-image-src="images/img/product.jpg"></div>

        <section class="cart-page">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-sm-7 col-md-7">
                        <div class="cart-widget">
                            <div class="cart-header">
                                <h3><i class="fa fa-shopping-cart"></i> Your Cart</h3>
                            </div>

                            <?php if (empty($_SESSION["c_item"]) || count($_SESSION["c_item"]) == 0): ?>
                                <div class="empty-cart">
                                    <i class="fa fa-shopping-cart"></i>
                                    <p>Your cart is empty. Browse our <a href="dishes.php">products</a> to add items.</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table cart-table">
                                        <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cart-items">
                                            <?php foreach ($_SESSION["c_item"] as $item): 
                                                $item_price = $item["price"] * $item["quantity"];
                                            ?>
                                                <tr class="cart-item" id="item-<?php echo $item['product_id']; ?>">
                                                    <td>
                                                        <div class="cart-item-title">
                                                            <?php echo $item["name"]; ?>
                                                            <a href="dishes.php?res_id=<?php echo $_GET['res_id']; ?>&action=remove&id=<?php echo $item["product_id"]; ?>" class="cart-item-remove">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td class="cart-item-price">
                                                        Rs. <?php echo number_format($item["price"], 2); ?>
                                                    </td>
                                                    <td>
                                                        <div class="quantity-control">
                                                            <button type="button" class="quantity-btn minus-btn" data-product-id="<?php echo $item["product_id"]; ?>">
                                                                <i class="fa fa-minus"></i>
                                                            </button>
                                                            <input type="text" id="quantity-<?php echo $item["product_id"]; ?>"
                                                                class="quantity-input" value="<?php echo $item["quantity"]; ?>"
                                                                min="1" max="10" readonly>
                                                            <button type="button" class="quantity-btn plus-btn" data-product-id="<?php echo $item["product_id"]; ?>">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td class="item-total" id="item-total-<?php echo $item['product_id']; ?>">
                                                        <strong>Rs. <?php echo number_format($item_price, 2); ?></strong>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="cart-footer">
                                    <div class="cart-total text-right">
                                        Total: <span id="cart-total">Rs. <?php echo number_format($item_total, 2); ?></span>
                                    </div>
                                    <div class="free-delivery text-center">
                                        <i class="fa fa-truck"></i> Free Delivery!
                                    </div>
                                    <a href="checkout.php?&action=check" class="btn <?php echo ($item_total == 0) ? 'btn-danger disabled' : 'btn-success'; ?> checkout-btn">
                                        <i class="fa fa-check"></i> Proceed to Checkout
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>



            <!-- start: FOOTER -->
            <footer class="footer">
                <div class="container">
                    <!-- top footer statrs -->
                    <div class="row top-footer">
                        <div class="col-xs-12 col-sm-2 about color-gray">
                            <h5>About Us</h5>
                            <ul>
                                <li><a href="#">facebook</a></li>
                            </ul>
                        </div>
                        <div class="col-xs-12 col-sm-2 how-it-works-links color-gray">
                            <h5>Best Selling Product</h5>
                            <ul>
                                <li><a href="#">Donut</a></li>
                            </ul>
                        </div>
                        <div class="col-xs-12 col-sm-2 pages color-gray">
                            <h5>Legal</h5>
                        </div>
                        <div class="col-xs-12 col-sm-3 popular-locations color-gray">
                            <h5>Our Donut Pasal</h5>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end:Footer -->
        </div>

       <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>
    
    <script>
    $(document).ready(function() {
        // Function to update cart quantities via AJAX
        function updateCart(productId, newQuantity, button) {
            // Show loading state on the button
            $(button).addClass('btn-updating');
            
            $.ajax({
                url: 'update-cart.php',
                type: 'POST',
                data: {
                    product_id: productId,
                    quantity: newQuantity,
                    action: 'update'
                },
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        // Update the item total
                        var itemTotal = (response.item_price * newQuantity).toFixed(2);
                        $('#item-total-' + productId).html('<strong>Rs. ' + itemTotal + '</strong>');
                        
                        // Update the cart total
                        $('#cart-total').text('Rs. ' + response.cart_total.toFixed(2));
                        
                        // Update the cart badge
                        $('.notification-badge').text(response.cart_count);
                        
                        // If quantity is 0, remove the item from display
                        if(newQuantity == 0) {
                            $('#item-' + productId).remove();
                            
                            // Check if cart is now empty
                            if($('#cart-items tr').length == 0) {
                                location.reload(); // Reload to show empty cart message
                            }
                        }
                    } else {
                        alert(response.message || 'Failed to update cart');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error updating cart: ' + error);
                },
                complete: function() {
                    // Remove loading state
                    $(button).removeClass('btn-updating');
                }
            });
        }

        // Plus button click handler
        $('.plus-btn').click(function() {
            var productId = $(this).data('product-id');
            var inputField = $('#quantity-' + productId);
            var currentVal = parseInt(inputField.val());

            if (!isNaN(currentVal) && currentVal < 10) {
                inputField.val(currentVal + 1);
                updateCart(productId, currentVal + 1, this);
            }
        });

        // Minus button click handler
        $('.minus-btn').click(function() {
            var productId = $(this).data('product-id');
            var inputField = $('#quantity-' + productId);
            var currentVal = parseInt(inputField.val());

            if (!isNaN(currentVal) && currentVal > 1) {
                inputField.val(currentVal - 1);
                updateCart(productId, currentVal - 1, this);
            }
        });

        // Add animation to the cart badge
        $('.notification-badge').addClass('animated bounce');
    });
    </script>
</body>
</html>
<?php } ?>