<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
include_once 'product-action.php';
include_once 'config.php';
error_reporting(0);
session_start();
if (empty($_SESSION["user_id"])) {
    header('location:login.php');
} else {

    foreach ($_SESSION["c_item"] as $item) {
        $item_total += ($item["price"] * $item["quantity"]);

        if ($_POST['submit']) {

            $SQL = "insert into users_orders(u_id,product_id,name,quantity,price,customization) values('" . $_SESSION["user_id"] . "','" . $item["product_id"] . "','" . $item["name"] . "','" . $item["quantity"] . "','" . $item["price"] . "','" . $_POST['message'] . "')";

            if (mysqli_query($db, $SQL)) {
                $success = "Thankyou! Your Order Placed Successfully!";
            } else {
                $error = "Please rewrite and don't mention(. , / : ;)";
            }
        }
    }
?>


    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="checkout.png">
        <title>Order Checkout</title>
        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/font-awesome.min.css" rel="stylesheet">
        <link href="css/animsition.min.css" rel="stylesheet">
        <link href="css/animate.css" rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="css/style.css" rel="stylesheet">
        <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
        <style>
            #order-forms {
                opacity: 0;
            }
        </style>
    </head>

    <body>

        <div class="site-wrapper">
            <!--header starts-->
            <header id="header" class="header-scroll top-header headrom">
                <!-- .navbar -->
                <nav class="navbar navbar-dark">
                    <div class="container">
                        <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                        <a class="navbar-brand" href="index.php"> <img class="img-rounded" src="images/food-picky-logo.png" alt=""> </a>
                        <div class="collapse navbar-toggleable-md  float-lg-right" id="mainNavbarCollapse">
                            <ul class="nav navbar-nav">
                                <li class="nav-item"> <a class="nav-link active" href="index.php">Home <span class="sr-only">(current)</span></a> </li>
                                <li class="nav-item"> <a class="nav-link active" href="dishes.php">Products <span class="sr-only"></span></a> </li>
                                <li class="nav-item"> <a class="nav-link active" href="cart.php">My Cart <span class="sr-only"></span></a> </li>

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
                <div class="top-links">
                    <div class="container">

                    </div>
                </div>

                <div class="container">

                    <span style="color:green;">
                        <?php
                        if (isset($success)) {
                            echo $success;
                        }
                        ?>
                    </span>

                    <span style="color:red;">
                        <?php
                        if (isset($error)) {
                            echo $error;
                        }
                        ?>
                    </span>


                </div>




                <div class="container m-t-30">

                    <div class="widget clearfix">

                        <div class="widget-body">
                            <form method="post">
                                <div class="row">

                                    <div class="col-sm-12">
                                        <div class="cart-totals margin-b-20">
                                            <div class="cart-totals-title">
                                                <h4>Cart Summary</h4>
                                            </div>
                                            <div class="cart-totals-fields">

                                                <table class="table">
                                                    <tbody>

                                                        <?php
                                                        if (isset($_SESSION["c_item"])) {
                                                            $item_number = 1;
                                                            foreach ($_SESSION["c_item"] as $item) {
                                                        ?>
                                                                <tr>
                                                                    <td><?php echo "Item " . $item_number . ":"; ?></td>
                                                                    <td><?php echo $item["name"] . " (" . $item["quantity"] . ")"; ?></td>
                                                                </tr>
                                                        <?php
                                                                $item_number++;
                                                            }
                                                        }
                                                        ?>


                                                        <tr>
                                                            <td>Cart Subtotal</td>
                                                            <td>Rs <?php echo $item_total; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Shipping &amp; Handling</td>
                                                            <td>FREE*</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-color"><strong>Total</strong></td>
                                                            <td class="text-color"><strong>Rs <?php echo $item_total; ?></strong></td>
                                                        </tr>
                                                    </tbody>




                                                </table>
                                            </div>
                                        </div>
                                        <!--cart summary-->
                                        <div class="payment-option">
                                            <ul class=" list-unstyled">
                                                <li>
                                                    <label class="custom-control custom-radio  m-b-20">
                                                        <input name="mod" id="radioStacked1" checked value="COD" type="radio" class="custom-control-input"> <span class="custom-control-indicator"></span> <span class="custom-control-description">Cash on delivery</span>
                                                </li>
                                                <li>
                                                    <label class="custom-control custom-radio  m-b-20">
                                                        <!-- <button id="payment-button">Pay with Khalti</button> -->
                                                        <input name="mod" id="payment-button" checked value="CODE" type="radio" class="custom-control-input"> <span class="custom-control-indicator"></span> <span class="custom-control-description">Pay with Khalti</span>
                                                </li>
                                            </ul>
                                            <!-- cutomization -->
                                            <label for="message" style="vertical-align: top;">Order Notes: </label>
                                            <textarea id="message" name="message" placeholder="Any suggestion?" rows="5" cols="40"></textarea>
                                            <input type="submit" onclick="return confirm('Do you want to confirm the order?');" name="submit" id="order-forms">


                                            <p class="text-xs-center">
                                                <input type="submit" name="submit" class="btn btn-outline-success btn-block" id="order-form" value="Order now"
                                                    onclick="
      var paymentMethod = document.querySelector('input[name=\'mod\']:checked').value;
      if (paymentMethod === 'COD') {
        // perform the action for Cash on Delivery
        return document.getElementById('order-forms').click();
      } else if (paymentMethod === 'CODE') {
        // perform the action for Pay with Khalti
        var config = {
          'publicKey': 'test_public_key_b70439cd6a9140a6a736f2337eac388d',
          'productIdentity': '1234567890',
          'productName': 'Dragon',
          'productUrl': 'http://gameofthrones.wikia.com/wiki/Dragons',
          'paymentPreference': [
            'KHALTI',
            'EBANKING',
            'MOBILE_BANKING',
            'CONNECT_IPS',
            'SCT',
          ],
          'eventHandler': {
            onSuccess: function(payload) {
              // Send the transaction details to the server using AJAX
              var xhr = new XMLHttpRequest();
              var url = 'process_payment.php';
              xhr.open('POST', url, true);
              xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
              xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                  console.log(xhr.responseText);
                }
              };
              xhr.send('transaction_id=' + payload.token + '&amount=' + payload.amount);

              // Show a success message to the user
              alert('Payment successful! Thank you for your purchase.');
              document.getElementById('order-forms').click();
            },
            onError: function(error) {
              console.log(error);
              alert('Payment failed. Please try again later.');
            },
            onClose: function() {
              console.log('Widget is closing');
            }
          }
        };
        var checkout = new KhaltiCheckout(config);
        checkout.show({ amount: 1000 });
        return false; // prevent the form from submitting
      }
    ">

                                        </div>

                                        <?php
                                        if (isset($_POST['submit'])) {
                                            // code to insert the order into the database

                                            // Clear the shopping cart
                                            unset($_SESSION["c_item"]);
                                        }
                                        ?>
                            </form>

                        </div>
                    </div>

                </div>
            </div>

        </div>


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

        </footer>
        <!-- end:Footer -->
        </div>
        <!-- end:page wrapper -->
        </div>

        <!-- Bootstrap core JavaScript
    ================================================== -->
        <script src="js/jquery.min.js"></script>
        <script src="js/tether.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/animsition.min.js"></script>
        <script src="js/bootstrap-slider.min.js"></script>
        <script src="js/jquery.isotope.min.js"></script>
        <script src="js/headroom.js"></script>
        <script src="js/foodpicky.min.js"></script>
        <script>
            var config = {
                "publicKey": "test_public_key_b70439cd6a9140a6a736f2337eac388d",
                "productIdentity": "1234567890",
                "productName": "Dragon",
                "productUrl": "http://gameofthrones.wikia.com/wiki/Dragons",
                "paymentPreference": [
                    "KHALTI",
                    "EBANKING",
                    "MOBILE_BANKING",
                    "CONNECT_IPS",
                    "SCT",
                ],
                "eventHandler": {
                    onSuccess(payload) {
                        // Send the transaction details to the server using AJAX
                        var xhr = new XMLHttpRequest();
                        var url = "process_payment.php";
                        xhr.open("POST", url, true);
                        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState == 4 && xhr.status == 200) {
                                console.log(xhr.responseText);
                            }
                        };
                        xhr.send("transaction_id=" + payload.transaction_id + "&amount=" + payload.amount);

                        // Show a success message to the user
                        alert("Payment successful! Thank you for your purchase.");
                        document.getElementById("order-forms").click();

                    },
                                                onError(error) {
                        console.log(error);
                        alert("Payment failed. Please try again later.");
                    },
                    onClose() {
                        console.log("Widget is closing");
                    }
                }
            };

            var checkout = new KhaltiCheckout(config);
            var btn = document.getElementById("payment-button");
            btn.onclick = function() {
                // Minimum transaction amount must be 10, i.e 1000 in paisa.
                checkout.show({
                    amount: 1000
                });
                return false; // prevent the form from submitting
            };
        </script>

    </body>

</html>

<?php
}
?>