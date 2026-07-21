<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");  //include connection file
error_reporting(0);  // using to hide undefine undex errors
session_start(); //start temp session until logout/browser closed
include_once 'product-action.php';
$cart_count = 0;
if(isset($_SESSION["c_item"])) {
    foreach($_SESSION["c_item"] as $item) {
        $cart_count += $item["quantity"];
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
    <link rel="icon" href="homepage.png">
    <title>Home</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet"> 
</head>
<style>
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
                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            }
            
    </style>
<body class="home">
    
        <!--header starts-->
        <header id="header" class="header-scroll top-header headrom">
            <!-- .navbar -->
            <nav class="navbar navbar-dark">
                <div class="container">
                    <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                    <a class="navbar-brand" href="index.php"> <img class="img-rounded" src="images/img/logo.png" alt="" style="height: 50px; width: 150px;"> </a>
                    <div class="collapse navbar-toggleable-md  float-lg-right" id="mainNavbarCollapse">
                        <ul class="nav navbar-nav">
                            <li class="nav-item"> <a class="nav-link active" href="index.php">Home <span class="sr-only">(current)</span></a> </li>
                            <li class="nav-item"> <a class="nav-link active" href="dishes.php">Products <span class="sr-only"></span></a> </li>
                             <li class="nav-item cart-badge"> 
                                <a class="nav-link active" href="cart.php">My Cart 
                                    <?php if($cart_count > 0): ?>
                                    <span class="notification-badge"><?php echo $cart_count; ?></span>
                                    <?php endif; ?>
                                    <span class="sr-only"></span>
                                </a> 
                            </li>
                           
							<?php
						if(empty($_SESSION["user_id"])) // if user is not login
							{
								echo '<li class="nav-item"><a href="login.php" class="nav-link active">Login</a> </li>
							  <li class="nav-item"><a href="registration.php" class="nav-link active">Sign Up</a> </li>';
							}
						else
							{
									//if user is login
									
									echo  '<li class="nav-item"><a href="your_orders.php" class="nav-link active">Your Orders</a> </li>';
									echo  '<li class="nav-item"><a href="logout.php" class="nav-link active">Logout</a> </li>';
							}

						?>
							 
                        </ul>
						 
                    </div>
                </div>
            </nav>
            <!-- /.navbar -->
        </header>
        <!-- banner part starts -->
        <section class="hero bg-image" data-image-src="images/img/Bms1.jpg">
            <div class="hero-inner">
                <div class="container text-center font-white">
                    <h1>Make Your Day Sweet !</h1>
                    <div class="banner-form">
                    <form class="form-inline" method="POST" action="search.php">
    <div class="form-group">
        <input type="text" class="form-control form-control-lg"  id="navbar-search-input" name="keyword" autocomplete="on" placeholder="I would like to eat...." required>
        <span class="input-group-btn" id="searchBtn" style="display:none;">
    </div>
    <button type="submit" name="submit" class="btn theme-btn btn-lg">Search it</button>
</form>
            <!--end:Hero inner -->
        </section>
        <!-- banner part ends -->

            
        <!-- Popular block starts -->
        <section class="popular">
            <div class="container">
                <div class="title text-xs-center m-b-30">
                    <h2>Popular Bakery Products</h2>
                </div>
                <div class="row">
                                  
						
                <?php 
						// fetch records from database to display popular first 3 dishes from table
						$query_res = mysqli_query($db, "SELECT * FROM product_list WHERE status = 1 LIMIT 3");

									      while($r=mysqli_fetch_array($query_res))
										  {													
                                            echo '<div class="col-xs-12 col-sm-6 col-md-4 food-item">
                                            <div class="food-item-wrap">
                                              <div class="figure-wrap bg-image" data-image-src="http://localhost/bakery/images/products/'.$r['image'].'">
                                                <div class="rating pull-left">';
                                    // Display stars based on the average rating
                                    $avg_rating = round($r['avg_rating']); // Round the average rating to the nearest integer
                                    for ($i = 1; $i <= 5; $i++) {
                                      if ($i <= $avg_rating){
                                        echo '<i class="fa fa-star"></i> ';
                                      } else {
                                        echo '<i class="fa fa-star-o"></i> ';
                                      }
                                    }
                                
                                    echo '</div></div>
                                          <div class="content">
                                            <h5><a href="dishes.php?res_id='.$r['product_id'].'">'.$r['name'].'</a></h5>
                                            <div class="product-name">'.$r['description'].'</div>
                                            <div class="price-btn-block"> <span class="price">Rs '.$r['price'].'</span> 
                                            <a href="orders.php?id='.$r['product_id'].'" class="btn theme-btn-dash pull-right">Order Now</a> </div>
                                          </div></div></div>';
										  }
						
						
						?>  
                 
                </div>
            </div>
        </section>
        <!-- Popular block ends -->
    
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

    
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <script>
         function updateCart(productId, newQuantity) {
                    // Show loading indicator or animation if desired
                    
                    // Send AJAX request to update cart
                    $.ajax({
                        url: 'update-cart.php',
                        type: 'POST',
                        data: {
                            product_id: productId,
                            quantity: newQuantity,
                            action: 'update'
                        },
                        success: function(response) {
                            // Reload the page to reflect changes
                            // You could also update the UI dynamically here
                            location.reload();
                        },
                        error: function() {
                            alert('Failed to update cart. Please try again.');
                        }
                    });
                }
                
                // Add animation to the cart badge
                $('.notification-badge').addClass('animated bounce');
        </script>
    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>
     <script src="js/search.js"></script>

</body>

</html>