<?php
session_start();
if(isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0){
    header("Location:./");
    exit;
}
require_once('DBConnection.php');
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin|LOGIN|</title>
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
<body class="">
   <div class="h-100 d-flex jsutify-content-center align-items-center">
       <div class='w-100'>
       <h1 class="py-5 text-center text-dark px-4" id="sys_title">LOG IN</h1>
    
        <div class="card my-3 col-md-4 offset-md-4">
            <div class="card-body">
                <form action="" id="login-form">
                   
                    <div class="form-group" >
                        <label for="username" class="control-label">Username</label>
                        <input type="text" id="username" autofocus name="username" class="form-control form-control-sm rounded-0" style="margin-top: 8px; margin-bottom: 8px;"
 required >
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control form-control-sm rounded-0" style="margin-top: 8px; margin-bottom: 8px;" required>
                    </div>
                    <div class="text-center" >
                        <button class="btn btn-primary" style="height: 42px; width: 430px; margin-bottom:8px;">Log In</button>
                    </div>
                    <div class="cta"><a href="Forget.php" style="color:#f30;"> Forget Password</a></div>

                </form>
            </div>
        </div>
       </div>
   </div>
</body>
<script>
    $(function(){
        $('#login-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            _this.find('button').attr('disabled',true)
            _this.find('button[type="submit"]').text('Loging in...')
            $.ajax({
                url:'./Actions.php?a=login',
                method:'POST',
                data:$(this).serialize(),
                dataType:'JSON',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                    _this.find('button').attr('disabled',false)
                    _this.find('button[type="submit"]').text('Save')
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        _el.addClass('alert alert-success')
                        setTimeout(() => {
                            location.replace('./');
                        }, 2000);
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                    _this.find('button').attr('disabled',false)
                    _this.find('button[type="submit"]').text('Save')
                }
            })
        })
    })
</script>
</html>