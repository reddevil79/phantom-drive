<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();

include_once 'product-action.php';
$cart_count = 0;
if(isset($_SESSION["c_item"])) {
    foreach($_SESSION["c_item"] as $item) {
        $cart_count += $item["quantity"];
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
    <title>Search Results</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <style type="text/css">
        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
            margin-top: 20px;
        }

        .product-card {
            width: calc(33.33% - 20px);
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-details {
            padding: 15px;
        }

        .product-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .product-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 12px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-size: 18px;
            font-weight: bold;
            color: #ff3300;
            margin-bottom: 15px;
        }

        .product-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .quantity-btn {
            background: #ff3300;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .quantity-btn:hover {
            background: #e62e00;
        }

        .quantity-input {
            width: 50px;
            text-align: center;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .add-to-cart-btn {
            background: #ff3300;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .add-to-cart-btn:hover {
            background: #e62e00;
        }

        .rating {
            margin-bottom: 10px;
        }

        .rating i {
            color: #ffc107;
        }

        .tags {
            list-style: none;
            padding: 0;
        }

        .tags li {
            display: inline-block;
            margin: 0 5px 5px 0;
        }

        .tags a {
            display: inline-block;
            padding: 5px 10px;
            background: #f1f1f1;
            border-radius: 3px;
            color: #333;
            text-decoration: none;
        }

        .tags a:hover {
            background: #ddd;
        }

        .search-header {
            margin-bottom: 30px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .no-results {
            text-align: center;
            padding: 50px;
            font-size: 18px;
            color: #666;
        }

        @media (max-width: 992px) {
            .product-card {
                width: calc(50% - 15px);
            }
        }

        @media (max-width: 768px) {
            .product-card {
                width: 100%;
            }
        }
  
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
</head>

<body>
    <!--header starts-->
    <header id="header" class="header-scroll top-header headrom">
        <nav class="navbar navbar-dark">
            <div class="container">
                <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                <a class="navbar-brand" href="index.php"> <img class="img-rounded" src="images/img/logo.png" alt="" style="height: 50px; width: 150px;"> </a>
                <div class="collapse navbar-toggleable-md float-lg-right" id="mainNavbarCollapse">
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
                        if (empty($_SESSION["user_id"])) {
                            echo '<li class="nav-item"><a href="login.php" class="nav-link active">Login</a> </li>
                              <li class="nav-item"><a href="registration.php" class="nav-link active">Sign Up</a> </li>';
                        } else {
                            echo '<li class="nav-item"><a href="your_orders.php" class="nav-link active">Your Orders</a> </li>';
                            echo '<li class="nav-item"><a href="logout.php" class="nav-link active">LogOut</a> </li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="page-wrapper">
        <div class="inner-page-hero bg-image" data-image-src="images/img/product.jpg"></div>

        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-5 col-md-4 col-lg-3">
                    <div class="widget">
                        <div class="widget-heading">
                            <h3 class="widget-title text-dark">Categories</h3>
                            <div class="clearfix"></div>
                        </div>
                        <div class="widget-body">
                            <ul class="tags">
                                <?php
                                $category_query = "SELECT * FROM category_list";
                                $category_result = mysqli_query($db, $category_query);

                                while ($category_row = mysqli_fetch_assoc($category_result)) {
                                    $product_query = "SELECT * FROM product_list WHERE category_id = " . $category_row['category_id'];
                                    $product_result = mysqli_query($db, $product_query);
                                    $product_count = mysqli_num_rows($product_result);

                                    echo '<li><a href="category.php?category_id=' . $category_row['category_id'] . '" class="tag">' . $category_row['name'] . ' (' . $product_count . ')</a></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-7 col-md-8 col-lg-9">
                    <div class="search-header">
                        <h3>Search Results</h3>
                        <?php
                        if (isset($_POST['keyword'])) {
                            echo '<p>Showing results for: <strong>"' . htmlspecialchars($_POST['keyword']) . '"</strong></p>';
                        }
                        ?>
                    </div>

                    <div class="product-container">
                        <?php
                        if (isset($_POST['keyword'])) {
                            $keyword = '%' . $_POST['keyword'] . '%';
                            $stmt = $db->prepare("SELECT * FROM product_list WHERE name LIKE ? OR description LIKE ?");
                            $stmt->bind_param('ss', $keyword, $keyword);
                            $stmt->execute();
                            $products = $stmt->get_result();

                            if ($products->num_rows > 0) {
                                $_SESSION['search_query'] = $keyword;
                                foreach ($products as $product) {
                        ?>
                                    <div class="product-card">
                                        <img src="http://localhost/bakery/images/products/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-image">
                                        <div class="product-details">
                                            <h3 class="product-title"><?php echo $product['name']; ?></h3>
                                            <p class="product-description"><?php echo $product['description']; ?></p>

                                            <div class="rating">
                                                <?php
                                                $avg_rating = round($product['avg_rating']);
                                                for ($i = 1; $i <= 5; $i++) {
                                                    echo ($i <= $avg_rating) ? '<i class="fa fa-star"></i>' : '<i class="fa fa-star-o"></i>';
                                                }
                                                ?>
                                            </div>

                                            <div class="product-price">Rs <?php echo $product['price']; ?></div>

                                            <form method="post" action='search.php?action=add&id=<?php echo $product['product_id']; ?>' class="product-actions">
                                                <div class="quantity-control">
                                                    <button type="button" class="quantity-btn minus">-</button>
                                                    <input type="text" name="quantity" class="quantity-input" value="1" min="1" readonly>
                                                    <button type="button" class="quantity-btn plus">+</button>
                                                </div>
                                                <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                                            </form>
                                        </div>
                                    </div>
                                <?php
                                }
                            } else {
                                ?>
                                <div class="no-results">
                                    <p>No products found matching your search.</p>
                                    <a href="dishes.php" class="btn btn-primary">Browse All Products</a>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
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
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Quantity control functionality
            document.querySelectorAll('.quantity-control').forEach(control => {
                const minusBtn = control.querySelector('.minus');
                const plusBtn = control.querySelector('.plus');
                const input = control.querySelector('.quantity-input');

                // Prevent any keyboard input
                input.addEventListener('keydown', (e) => {
                    e.preventDefault();
                    return false;
                });

                // Prevent paste
                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    return false;
                });

                minusBtn.addEventListener('click', () => {
                    let value = parseInt(input.value);
                    if (value > input.min) {
                        input.value = value - 1;
                    }
                });

                plusBtn.addEventListener('click', () => {
                    let value = parseInt(input.value);
                    input.value = value + 1;
                });
            });
        });
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
</body>

</html>
<?php
}
?>