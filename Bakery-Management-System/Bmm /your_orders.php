<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
require('config.php');
error_reporting(0);
session_start();

include_once 'product-action.php';
$cart_count = 0;
if(isset($_SESSION["c_item"])) {
    foreach($_SESSION["c_item"] as $item) {
        $cart_count += $item["quantity"];
    }
}

if (empty($_SESSION['user_id']))  //if usser is not login redirected back to login page
{
  header('location:login.php');
} else {
?>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="ecommerce.png">
    <title>Your Orders</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <style type="text/css" rel="stylesheet">
      .indent-small {
        margin-left: 5px;
      }

      .form-group.internal {
        margin-bottom: 0;
      }

      .dialog-panel {
        margin: 10px;
      }

      .datepicker-dropdown {
        z-index: 200 !important;
      }

      .panel-body {
        background: #e5e5e5;
        /* Old browsers */
        background: -moz-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
        /* FF3.6+ */
        background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, #e5e5e5), color-stop(100%, #ffffff));
        /* Chrome,Safari4+ */
        background: -webkit-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
        /* Chrome10+,Safari5.1+ */
        background: -o-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
        /* Opera 12+ */
        background: -ms-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
        /* IE10+ */
        background: radial-gradient(ellipse at center, #e5e5e5 0%, #ffffff 100%);
        /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#e5e5e5', endColorstr='#ffffff', GradientType=1);
        /* IE6-9 fallback on horizontal gradient */
        font: 600 15px "Open Sans", Arial, sans-serif;
      }

      label.control-label {
        font-weight: 600;
        color: #777;
      }


      table {
        width: 750px;
        border-collapse: collapse;
        margin: auto;

      }

      /* Zebra striping */
      tr:nth-of-type(odd) {
        background: #eee;
      }

      th {
        background: #ff3300;
        color: white;
        font-weight: bold;

      }

      td,
      th {
        padding: 10px;
        border: 1px solid #ccc;
        text-align: left;
        font-size: 14px;

      }

  
      @media only screen and (max-width: 760px),
      (min-device-width: 768px) and (max-device-width: 1024px) {

        table {
          width: 100%;
        }

        /* Force table to not be like tables anymore */
        table,
        thead,
        tbody,
        th,
        td,
        tr {
          display: block;
        }

        /* Hide table headers (but not display: none;, for accessibility) */
        thead tr {
          position: absolute;
          top: -9999px;
          left: -9999px;
        }

        tr {
          border: 1px solid #ccc;
        }

        td {
          /* Behave  like a "row" */
          border: none;
          border-bottom: 1px solid #eee;
          position: relative;
          padding-left: 50%;
        }

        td:before {
          /* Now like a table header */
          position: absolute;
          /* Top/left values mimic padding */
          top: 6px;
          left: 6px;
          width: 45%;
          padding-right: 10px;
          white-space: nowrap;
          /* Label the data */
          content: attr(data-column);

          color: #000;
          font-weight: bold;
        }

      }

      @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
      }

      .container_popup {
        position: relative;
        width: 400px;
        background: #111;
        padding: 20px 30px;
        border: 1px solid #444;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        visibility: hidden;
        transform: translate(-50%, -50%) scale(0.1);
        transition: transform 0.4s, top 0.4s;
        top: 0;
        left: 50%
      }

      .open-popup {
        visibility: visible;
        top: 50%;
        transform: translate(-50%, -50%) scale(1);

      }

      .container_popup .post {
        display: none;
      }

      .container_popup .text {
        font-size: 25px;
        color: #666;
        font-weight: 500;
      }

      .container_popup .edit {
        position: absolute;
        right: 10px;
        top: 5px;
        font-size: 16px;
        color: #666;
        font-weight: 500;
        cursor: pointer;
      }

      .container_popup .edit:hover {
        text-decoration: underline;
      }

      .container_popup .star-widget input {
        display: none;
      }

      .star-widget label {
        font-size: 40px;
        color: #444;
        padding: 10px;
        float: right;
        transition: all 0.2s ease;
      }

      input:not(:checked)~label:hover,
      input:not(:checked)~label:hover~label {
        color: #fd4;
      }

      input:checked~label {
        color: #fd4;
      }

      input#rate-5:checked~label {
        color: #fe7;
        text-shadow: 0 0 20px #952;
      }

      #rate-1:checked~form header:before {
        content: "I just hate it ";
      }

      #rate-2:checked~form header:before {
        content: "I don't like it ";
      }

      #rate-3:checked~form header:before {
        content: "It is awesome ";
      }

      #rate-4:checked~form header:before {
        content: "I just like it ";
      }

      #rate-5:checked~form header:before {
        content: "I just love it ";
      }

      .container form {
        display: none;
      }

      input:checked~form {
        display: block;
      }

      form header {
        width: 100%;
        font-size: 25px;
        color: #fe7;
        font-weight: 500;
        margin: 5px 0 20px 0;
        text-align: center;
        transition: all 0.2s ease;
      }

      form .textarea {
        height: 100px;
        width: 100%;
        overflow: hidden;
      }

      form .textarea textarea {
        height: 100%;
        width: 100%;
        outline: none;
        color: #eee;
        border: 1px solid #333;
        background: #222;
        padding: 10px;
        font-size: 17px;
        resize: none;
      }

      .textarea textarea:focus {
        border-color: #444;
      }

      form .btn {
        height: 45px;
        width: 100%;
        margin: 15px 0;
      }

      form .btn button {
        height: 100%;
        width: 100%;
        border: 1px solid #444;
        outline: none;
        background: #222;
        color: #999;
        font-size: 17px;
        font-weight: 500;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s ease;
      }

      form .btn button:hover {
        background: #1b1b1b;
      }

      .r1 {
        visibility: hidden;
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
      <!-- top Links -->

      <!-- end:Top links -->
      <!-- start: Inner page hero -->
      <div class="inner-page-hero bg-image" data-image-src="images/img/product.jpg">
        <div class="container"> </div>
        <!-- end:Container -->
      </div>
      <div class="result-show">
        <div class="container">
          <div class="row">


          </div>
        </div>
      </div>

      <!-- //results show -->
      <section class="Products-page">
        <div class="container">
          <div class="row">
            <div class="col-lg-12 col-sm-7 col-md-7 ">
              <div class="bg-gray restaurant-entry">
                <div class="row">

                  <table>
                    <thead>
                      <tr>

                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>

                      </tr>
                    </thead>
                    <tbody>


                      <?php
                      // displaying current session user login orders 
                      $query_res = mysqli_query($db, "select * from users_orders where u_id='" . $_SESSION['user_id'] . "'");
                      if (!mysqli_num_rows($query_res) > 0) {
                        echo '<td colspan="6"><center>You have no orders Placed yet. </center></td>';
                      } else {

                        while ($row = mysqli_fetch_array($query_res)) {

                      ?>
                          <tr>
                            <td data-column="Item"> <?php echo $row['name']; ?></td>
                            <td data-column="Quantity"> <?php echo $row['quantity']; ?></td>
                            <td data-column="Price">Rs <?php echo $row['price']; ?></td>
                            <td data-column="Status">
                              <?php
                              $status = $row['status'];
                              if ($status == "" or $status == "NULL") {
                              ?>
                                <button type="button" class="btn btn-info" style="font-weight:bold;">Dispatch</button>
                              <?php
                              }
                              if ($status == "in process") { ?>
                                <button type="button" class="btn btn-warning"><span class="fa fa-cog fa-spin" aria-hidden="true"></span>On the Way!</button>
                              <?php
                              }
                              if ($status == "closed") {
                              ?>
                                <button type="button" class="btn btn-success"><span class="fa fa-check-circle" aria-hidden="true">Delivered</button>
                              <?php
                              }
                              ?>
                              <?php
                              if ($status == "rejected") {
                              ?>
                                <button type="button" class="btn btn-danger"> <i class="fa fa-close"></i>Cancelled</button>
                              <?php
                              }
                              ?>

                            </td>
                            <td data-column="Date"> <?php echo $row['date']; ?></td>
                            <td data-column="Action">
                              <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Action
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                  <a class="dropdown-item" href="delete_orders.php?order_del=<?php echo $row['o_id']; ?>" onclick="return confirm('Are you sure you want to cancel your order?');"><i class="fa fa-trash" style="font-size:16px"></i> Cancel Order</a>

                                  <?php
                                  if ($status == "closed") {
                                  ?>

                                    <div class="product">

                                      <a class="dropdown-item" onclick="openPopup(<?php echo $row['product_id']; ?>)">
                                        <i class="fa fa-star" style="font-size:16px" id="rating-btn"></i> Ratings
                                      </a>
                                    </div>
                                  <?php
                                  }
                                  ?>

                                </div>
                              </div>
                            </td>

                          </tr>

                      <?php

                        }
                      }
                      ?>




                    </tbody>
                  </table>



                </div>
                <!--end:row -->
              </div>
              <div>

              </div>

              <!-- RATING -->
              <div class="container_popup" id="popup-container">
                <div class="star-widget">
                  <input type="radio" name="rate" id="rate-5" class="r1">
                  <label for="rate-5" class="fas fa-star"></label>
                  <input type="radio" name="rate" id="rate-4" class="r1">
                  <label for="rate-4" class="fas fa-star"></label>
                  <input type="radio" name="rate" id="rate-3" class="r1">
                  <label for="rate-3" class="fas fa-star"></label>
                  <input type="radio" name="rate" id="rate-2" class="r1">
                  <label for="rate-2" class="fas fa-star"></label>
                  <input type="radio" name="rate" id="rate-1" class="r1">
                  <label for="rate-1" class="fas fa-star"></label>
                  <form action="#">
                    <div class="btn">
                      <button type="submit" onclick="closePopup(<?php echo $row['product_id']; ?>)">Post</button>
                    </div>
                  </form>
                </div>
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

      </footer>
      <!-- end:Footer -->
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
      const btn = document.querySelector("button");
      const post = document.querySelector(".post");
      const widget = document.querySelector(".star-widget");
      const editBtn = document.querySelector(".edit");
      btn.onclick = () => {
        widget.style.display = "none";
        post.style.display = "block";

        return false;
      }
    </script>
    <script>
      const popup = document.getElementById("popup-container");
      let product_id; // define the product_id variable outside of the functions
      let ratings = []; // initialize the ratings variable as an empty array

      function openPopup(id) {
        product_id = id;
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "insert_rating.php?product_id=" + encodeURIComponent(product_id));
        xhr.send();
        popup.classList.add("open-popup");
      }

      function closePopup() {
        popup.classList.remove("open-popup");
        const selectedRating = document.querySelector('input[name="rate"]:checked');
        if (!selectedRating) {
          alert("Please select a rating");
          return;
        }
        const ratingValue = parseInt(selectedRating.id.split("-")[1]);
        ratings.push(ratingValue); // add the new rating to the ratings array
        calculateAverageRating(); // calculate the new average rating

        // Send the rating data to the server using AJAX
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "insert_rating.php?product_id=" + encodeURIComponent(product_id));
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText); // log the server response (optional)
          }
        };
        const ratingData = "product_id=" + encodeURIComponent(product_id) + "&rating=" + encodeURIComponent(ratingValue);
        xhr.send(ratingData);
      }

      function calculateAverageRating() {
        if (ratings.length === 0) {
          return; // exit early if there are no ratings
        }
        const sum = ratings.reduce((acc, rating) => acc + rating, 0);
        const avg = sum / ratings.length;
        const avgRatingElement = document.getElementById("avg-rating");
        if (!avgRatingElement) {
          return; // exit early if the element does not exist
        }
        avgRatingElement.textContent = isNaN(avg) ? "N/A" : avg.toFixed(1); // display "N/A" if the average rating is NaN

        // Send the average rating to the server using AJAX
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "insert_rating.php");
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText); // log the server response (optional)
          }
        };
        const ratingData = "product_id=" + encodeURIComponent(product_id) + "&avg_rating=" + encodeURIComponent(avg);
        xhr.send(ratingData);
      }
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

  </body>

</html>
<?php
}
?>