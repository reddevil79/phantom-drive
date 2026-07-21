<?php
require_once('DBConnection.php'); 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\autoload;
// // Include PHPMailer library
// set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\BMS\admin\PHP');

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if(isset($_POST['pwdrst'])) {
    // Retrieve email address from form
    $email = $_POST['email'];

    // Check if email exists in the database
    $query = "SELECT * FROM user_list WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {
        // Generate random password
        $password = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);

        // Update user's password in database
        $sql = "UPDATE user_list SET password = '$password' WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

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
            $mail->setFrom("your_email@gmail.com", "Bakery Management System Admin");
            $mail->addAddress($email);
            $mail->Subject = "Password Reset";
            $mail->Body = "Your new password is: $password";

            if (!$mail->send()) {
                $msg = "Failed to send email: " . $mail->ErrorInfo;
                $alert_type = "danger";
            } else {
                $msg = "Password reset email sent successfully";
                $alert_type = "success";
            }
        }
    } else {
        $msg = "Email does not exist";
        $alert_type = "danger";
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin|Forget Password|</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/script.js"></script>
    <style>
    
    /* Default styles */

    html, body{
        height:100%;
        font-size: 16px;
    }
    body{
       background-image:url('images/login.jpg') !important;
        background-size:cover;
        background-repeat:no-repeat;
        background-position:center center;
    }
    h1#sys_title {
        font-size: 6em;
        text-shadow: 3px 3px 10px #000000;
    }
    .card.my-3.col-md-4.offset-md-4 {
        opacity: 1;
    }
    .cta {
        background: #f2f2f2;
        width: 100%;
        padding: 15px 40px;
        box-sizing: border-box;
        color: #666666;
        font-size: 12px;
        text-align: center;
    }
    .movable {
        width: 200px;
        position: absolute;
        top: 150px;
        left: 650px;
    }
    .custom-input-btn {
      
  width: 50%;
}

    /* Responsive styles */

    @media screen and (max-width: 768px) {
        /* Adjust font size of system title */
        h1#sys_title {
            font-size: 4em;
        }
        
        /* Move image to center */
        .movable {
            left: 50%;
            transform: translateX(-50%);
        }
        
        /* Adjust card width */
        .card.my-3.col-md-4.offset-md-4 {
            width: 80%;
            margin: 0 auto;
        }

        /* Adjust padding of title */
        h1#sys_title {
            padding: 2rem 0;
        }

        /* Adjust font size of title */
        h1#sys_title {
            font-size: 3rem;
        }

        /* Adjust margin of form */
        .card-body form {
            margin-top: 2rem;
        }

        /* Adjust font size of labels */
        .card-body form label {
            font-size: 1rem;
        }

        /* Adjust font size of inputs */
        .card-body form input {
            font-size: 1rem;
            padding: .5rem;
        }

        /* Adjust font size of login button */
        .card-body form button {
            font-size: 1rem;
            padding: .5rem 1rem;
        }

        /* Adjust font size of cta links */
        .cta a {
            font-size: 1rem;
        }
    }

        
    </style>
</head>
<?php if(!empty($msg))?></p>
            


<body class="">
   <div class="h-100 d-flex jsutify-content-center align-items-center">
       <div class='w-100'>
        <h1 class="py-5 text-center text-dark px-4" id="sys_title">Forgot Password</h1>
        <div class="card my-3 col-md-4 offset-md-4">
            <div class="card-body">
            
                <form id="validate_form" method="post">  
                    <div class="form-group">
                    <?php if(isset($msg)): ?>
    <div class="alert alert-<?php echo $alert_type; ?> alert-dismissible fade show" role="alert">
        <?php echo $msg; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

                        <label for="email">Email Address</label>
                        <input type="text" name="email" id="email" placeholder="Enter Email" required 
                        data-parsley-type="email" data-parsley-trigger="keyup" class="form-control" />
                    </div>
                    <div class="form-group">

                    <div class="d-flex justify-content-center">
  <input 
    type="submit" 
    id="login" 
    name="pwdrst" 
    value="Reset Password" 
    class="btn btn-sm btn-primary rounded-0 my-1 custom-input-btn" 
  />
</div>
                    </div>
                    <div class="cta">Have you reset your password? <a href="login.php" style="color:#f30;"> Log In</a></div>
                </form>
            
              
            </div>
        </div>
       </div>
   </div>
</body>
</html>