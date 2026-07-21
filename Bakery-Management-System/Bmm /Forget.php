<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>Forget Password</title>
  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">

  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>
<link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>

      <link rel="stylesheet" href="css/login.css">

	  <style type="text/css">
	  #buttn{
		  color:#fff;
		  background-color: #ff3300;
	  }
	  </style>
  
</head>

<body>

<?php
include("connection/connect.php");  //include connection file
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\autoload;
// // Include PHPMailer library
// set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\BMS\admin\PHP');

require 'admin/phpmailer/src/Exception.php';
require 'admin/phpmailer/src/PHPMailer.php';
require 'admin/phpmailer/src/SMTP.php';

if(isset($_POST['pwdrst'])) {
    // Retrieve email address from form
    $email = $_POST['email'];

    // Check if email exists in the database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($db, $query);

    if(mysqli_num_rows($result) > 0) {
        // Generate random password
        $password = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);

        // Update user's password in database
        $sql = "UPDATE users SET password = '$password' WHERE email = '$email'";
        $result = mysqli_query($db, $sql);

        if($result) {
            // Create new PHPMailer object
            $mail = new PHPMailer;

            // Configure SMTP settings
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "karkisujan590@gmail.com";
            $mail->Password = "axiy ktkl nvde voaj";
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;
            
            // Set email content
            $mail->setFrom("your_email@gmail.com", "Bakery Management System");
            $mail->addAddress($email);
            $mail->Subject = "Password Reset";
            $mail->Body = "Your new password is: $password";

            if (!$mail->send()) {
                $msg = "Failed to send email: " . $mail->ErrorInfo;
            } else {
                $msg = "Password reset email sent successfully";
            }
        }
    } else {
        $msg = "Email does not exist";
    }
}

// Close database connection
mysqli_close($db);
?>

<div class="pen-title">
  <h1>Forget Password</h1>
</div>
<!-- Form Module-->
<div class="module form-module">
  <div class="toggle">
   
  </div>
  <div class="form">
  
    <form action="" method="post">
    <input type="text" name="email" id="email" placeholder="Enter your Email" required 
                data-parsley-type="email" data-parsley-trigger="keyup" class="form-control" autocomplete="off"/>
                   
                        <input type="submit" id="buttn" name="pwdrst" value="Reset Password" />

                        <p class="error"><?php if(!empty($msg)){ echo $msg; } ?></p>
    </form>
  </div>
  <div class="cta">Have you reset your password? <a href="login.php" style="color:#f30;"> Login</a></div>
</div>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

  

   



</body>

</html>
