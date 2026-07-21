<?php
include('Weather/weather.php');
$mysqli_objects = $mysqli->query("SELECT*FROM WEATHER_table
ORDER BY APIDATE DESC LIMIT 1");

echo json_encode($mysqli_objects-> fetch_assoc());
?>
