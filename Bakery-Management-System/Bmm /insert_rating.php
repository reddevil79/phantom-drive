<?php
include("connection/connect.php");
session_start();

if(empty($_SESSION['user_id']))  //if user is not logged in redirect back to login page
{
	header('location:login.php');
}
else
{
	
	// Get the rating from the POST parameter
	$rating = $_POST["rating"];
	
	// Get the user ID from the session
	$user_id = $_SESSION["user_id"];

	// Get the product ID from the URL parameter
	$product_id = $_GET["product_id"];

	// Get the average rating from the POST parameter
	$avg_rating = $_POST["avg_rating"];


	// Check if the user has already rated the product
	$sql = "SELECT * FROM rating WHERE u_id='$user_id' AND product_id='$product_id'";
	$result = $db->query($sql);
	if ($result->num_rows > 0) {
		// If the user has already rated the product, update the rating
		$sql = "UPDATE rating SET rating='$rating' WHERE u_id='$user_id' AND product_id='$product_id'";
		if ($db->query($sql) === TRUE) {
			echo "Rating updated successfully";
		} else {
			echo "Error updating rating: " . $db->error;
		}
	} else {
		// If the user has not yet rated the product, insert a new row
		$sql = "INSERT INTO rating (u_id, product_id, rating) VALUES ('$user_id', '$product_id', '$rating')";
		if ($db->query($sql) === TRUE) {
			echo "Rating inserted successfully";
		} else {
			echo "Error inserting rating: " . $db->error;
		}
	}

	// Get the average rating for the product from the database
	$sql = "SELECT AVG(rating) as avg_rating FROM rating WHERE product_id='$product_id'";
	$result = $db->query($sql);
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$avg_rating = $row["avg_rating"];
	} else {
		$avg_rating = "N/A";
	}

	// Update the product's average rating in the database
	$sql = "UPDATE product_list SET avg_rating='$avg_rating' WHERE product_id='$product_id'";
	if ($db->query($sql) === TRUE) {
		echo "Average rating updated successfully";
	} else {
		echo "Error updating average rating: " . $db->error;
	}


	$db->close();
}
?>
