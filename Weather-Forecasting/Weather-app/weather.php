<?php 
   date_default_timezone_set('Asia/Kathmandu');
   $mysqli=new mysqli('localhost:3308','root','');

   $createDbQuery = "CREATE DATABASE Weather";
   $successfullycreated=$mysqli->query($createDbQuery);

   $mysqli->select_db('Weather');

   $createTableQuery="
     CREATE TABLE WEATHER_table (
          Cityname VARCHAR(100) NOT NULL,
          description VARCHAR(100) NOT NULL,
          Temperature FLOAT NOT NULL,
          Pressure INT NOT NULL,
          Humidity INT NOT NULL,
          WindSpeed FLOAT NOT NULL,
          WindDegree INT NOT NULL,
          APIDATE DATETIME NOT NULL,
          Icon VARCHAR(100) NOT NULL
     )
   "; 

   $mysqli->query($createTableQuery);

   $check = " SELECT * FROM  WEATHER_table
        WHERE APIDATE >= DATE_SUB(NOW(),INTERVAL 10 SECOND)
";

$mysqli_result = $mysqli->query($check);
if ($mysqli_result->num_rows == 0) {

   $jsonFile = file_get_contents('https://api.openweathermap.org/data/2.5/weather?q=Auburn&appid=caeac020fc9b4ff79d4ae671a39031a6&units=metric');
   $php_objects = json_decode($jsonFile);

   $city = $php_objects->name; 
   $desc = $php_objects->weather[0]->description;
   $temp = $php_objects->main->temp;
   $pres = $php_objects->main->pressure;
   $humi = $php_objects->main->humidity;
   $wind = $php_objects->wind->speed;
   $degr = $php_objects->wind->deg;
   $date = date("Y-m-d H:i:s");
   $ic = $php_objects->weather[0]->icon;

   $insertDbQuery ="
       INSERT INTO WEATHER_table
       (Cityname,Description,Temperature,Pressure,Humidity,WindSpeed,WindDegree,APIDATE,Icon)
       VALUES
       ('$city','$desc',$temp,$pres,$humi,$wind,$degr,'$date','$ic')";

       $mysqli->query($insertDbQuery);
}