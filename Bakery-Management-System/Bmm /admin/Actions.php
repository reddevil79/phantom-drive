<?php
ob_start();
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'php-errors.log');
error_reporting(E_ALL);



session_start();
require_once('DBConnection.php');

class Actions extends DBConnection
{
    function __construct()
    {
        parent::__construct();
    }
    function __destruct()
    {
        parent::__destruct();
    }
    function login()
    {
        extract($_POST);
        $sql = "SELECT * FROM user_list where username = '{$username}' && `password` = '{$password}' ";
        @$qry = $this->db->query($sql)->fetch_array();
        if (!$qry) {
            $resp['status'] = "failed";
            $resp['msg'] = "Invalid username or password.";
        } else {
            $resp['status'] = "success";
            $resp['msg'] = "Login successfully.";
            foreach ($qry as $k => $v) {
                if (!is_numeric($k))
                    $_SESSION[$k] = $v;
            }
        }
        return json_encode($resp);
    }

    function signup()
    {
        extract($_POST);
        if (
            empty($_POST['username']) ||  //fetching and find if its empty 
            empty($_POST['email']) ||
            empty($_POST['password']) ||
            empty($_POST['cpassword'])
        ) {
            $message = "All fields must be Required!";
        } else {
            //cheching username & email if already present
            $check_username = mysqli_query($conn, "SELECT username FROM user_list where username = '" . $_POST['username'] . "' ");
            $check_email = mysqli_query($conn, "SELECT email FROM user_list where email = '" . $_POST['email'] . "' ");



            if ($_POST['password'] != $_POST['cpassword']) {  //matching passwords
                $message = "Password not match";
            } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) // Validate email address
            {
                $message = "Invalid email address please type a valid email!";
            } elseif (mysqli_num_rows($check_username) > 0)  //check username
            {
                $message = 'username Already exists!';
            } elseif (mysqli_num_rows($check_email) > 0) //check email
            {
                $message = 'Email Already exists!';
            } else {

                //inserting values into db
                $mql = "INSERT INTO user_list(email,username,password) VALUES('" . $_POST['email'] . "','" . $_POST['username'] . "','" . $_POST['password'] . "')";
                mysqli_query($db, $mql);
                $success = "Account Created successfully! <p>You will be redirected in <span id='counter'>5</span> second(s).</p>
                                                     <script type='text/javascript'>
                                                     function countdown() {
                                                         var i = document.getElementById('counter');
                                                         if (parseInt(i.innerHTML)<=0) {
                                                             location.href = 'login.php';
                                                         }
                                                         i.innerHTML = parseInt(i.innerHTML)-1;
                                                     }
                                                     setInterval(function(){ countdown(); },1000);
                                                     </script>'";




                header("refresh:5;url=login.php"); // redireted once inserted success
            }
        }
    }
    function logout()
    {
        session_destroy();
        header("location:./");
    }

    function save_user()
    {
        extract($_POST);

        // Check if all required POST fields are present
        $required_fields = array('email', 'username', 'password');
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                $resp['status'] = 'failed';
                $resp['msg'] = 'Missing required field: ' . $field;
                return json_encode($resp);
            }
        }

        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id'))) {
                if (!empty($id)) {
                    if (!empty($data)) $data .= ",";
                    $data .= " `{$k}` = '{$v}' ";
                } else {
                    if ($k == 'username') {
                        $cols[] = $k;
                        $values[] = "'" . mysqli_real_escape_string($this->db, $v) . "'";
                    } else {
                        $cols[] = $k;
                        $values[] = "'{$v}'";
                    }
                }
            }
        }
        if (empty($id)) {
            $cols[] = 'password';
            $values[] = "'" . md5($password) . "'";
        }

        if (isset($cols) && isset($values)) {
            $data = "(" . implode(',', $cols) . ") VALUES (" . implode(',', $values) . ")";
        }
        // checks email exists in database or not 
        @$check_email = $this->db->query("SELECT count(user_id) as `count` FROM user_list where `email` = '{$email}' " . ($id > 0 ? " and user_id != '{$id}' " : ""))->fetch_array()['count'];
        if (@$check_email > 0) {
            $resp['status'] = 'failed';
            $resp['msg'] = "Email already exists.";
        } else {
            // checks username exists in database or not 
            @$check_username = $this->db->query("SELECT count(user_id) as `count` FROM user_list where `username` = '{$username}' " . ($id > 0 ? " and user_id != '{$id}' " : ""))->fetch_array()['count'];
            if (@$check_username > 0) {
                $resp['status'] = 'failed';
                $resp['msg'] = "Username already exists.";
            }
            // checks password match or not 
            elseif ($_POST['password'] != $_POST['cpassword']) {  //matching passwords
                $resp['status'] = 'failed';
                $resp['msg'] = "Password do not match";
            } else {
                //insert new user in database
                if (empty($id)) {
                    $sql = "INSERT INTO user_list (email, username, password) VALUES ('$email', '$username', '$password')";
                } else {
                    //updates user account in database 
                    $sql = "UPDATE `user_list` set {$data} where user_id = '{$id}'";
                }
                @$save = $this->db->query($sql);
                if ($save) {
                    $resp['status'] = 'success';
                    if (empty($id))
                        $resp['msg'] = 'New User successfully saved.';
                    else
                        $resp['msg'] = 'User Details successfully updated.';
                } else {
                    $resp['status'] = 'failed';
                    $resp['msg'] = 'Saving User Details Failed. Error: ' . $this->db->error;
                    $resp['sql'] = $sql;
                }
            }
        }
        return json_encode($resp);
    }

    function edit_user()
    {
        extract($_POST);

        // Check if all required POST fields are present
        $required_fields = array('email', 'username', 'password');
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                $resp['status'] = 'failed';
                $resp['msg'] = 'Missing required field: ' . $field;
                return json_encode($resp);
            }
        }

        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id'))) {
                if (!empty($id)) {
                    if (!empty($data)) $data .= ",";
                    $data .= " `{$k}` = '{$v}' ";
                } else {
                    if ($k == 'username') {
                        $cols[] = $k;
                        $values[] = "'" . mysqli_real_escape_string($this->db, $v) . "'";
                    } else {
                        $cols[] = $k;
                        $values[] = "'{$v}'";
                    }
                }
            }
        }
        if (empty($id)) {
            // $cols[] = 'username';
            $cols[] = 'password';
            $values[] = "'" . md5($password) . "'";
        }

        if (isset($cols) && isset($values)) {
            $data = "(" . implode(',', $cols) . ") VALUES (" . implode(',', $values) . ")";
        }

        @$check = $this->db->query("SELECT count(user_id) as `count` FROM user_list where `username` = '{$username}' " . ($id > 0 ? " and user_id != '{$id}' " : ""))->fetch_array()['count'];
        if (@$check > 0) {
            $resp['status'] = 'failed';
            $resp['msg'] = "Username already exists.";
        } else {
            if (empty($id)) {

                $email = $_POST['email'];
                $username = $_POST['username'];
                $password = $_POST['password'];


                $sql = "INSERT INTO user_list (email, username, password) VALUES ('$email', '$username', '$password')";
            } else {
                $sql = "UPDATE `user_list` set {$data} where user_id = '{$id}'";
            }
            @$save = $this->db->query($sql);
            if ($save) {
                $resp['status'] = 'success';
                if (empty($id))
                    $resp['msg'] = 'New User successfully saved.';
                else
                    $resp['msg'] = 'User Details successfully updated.';
            } else {
                $resp['status'] = 'failed';
                $resp['msg'] = 'Saving User Details Failed. Error: ' . $this->db->error;
                $resp['sql'] = $sql;
            }
        }
        return json_encode($resp);
    }

    function update_user()
    {
        extract($_POST);

        // Check if all required POST fields are present
        $required_fields = array('email', 'username', 'password');
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                $resp['status'] = 'failed';
                $resp['msg'] = 'Missing required field: ' . $field;
                return json_encode($resp);
            }
        }

        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id'))) {
                if (!empty($id)) {
                    if (!empty($data)) $data .= ",";
                    $data .= " `{$k}` = '{$v}' ";
                } else {
                    if ($k == 'username') {
                        $cols[] = $k;
                        $values[] = "'" . mysqli_real_escape_string($this->db, $v) . "'";
                    } else {
                        $cols[] = $k;
                        $values[] = "'{$v}'";
                    }
                }
            }
        }
        if (empty($id)) {
            // $cols[] = 'username';
            $cols[] = 'password';
            $values[] = "'" . md5($password) . "'";
        }

        if (isset($cols) && isset($values)) {
            $data = "(" . implode(',', $cols) . ") VALUES (" . implode(',', $values) . ")";
        }

        if (empty($id)) {

            $email = $_POST['email'];
            $username = $_POST['username'];
            $password = $_POST['password'];

            $sql = "UPDATE `user_list` set {$data} where user_id = '{$id}'";
            // $sql = "UPDATE `user_list` set {$password} where user_id = '{$id}'";
        }
        @$save = $this->db->query($sql);
        if ($save) {
            $resp['status'] = 'success';
            if (empty($id))
                $resp['msg'] = 'User Details successfully updated.';
        } else {
            $resp['status'] = 'failed';
            $resp['msg'] = 'Saving User Details Failed. Error: ' . $this->db->error;
            $resp['sql'] = $sql;
        }

        return json_encode($resp);
    }



    function delete_u()
    {
        extract($_POST);

        @$delete = $this->db->query("DELETE FROM `users` WHERE u_id = '{$id}'");
        if ($delete) {
            $resp['status'] = 'success';
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = 'User successfully deleted.';
        } else {
            $resp['status'] = 'failed';
            $resp['error'] = $this->db->error;
        }
        return json_encode($resp);
    }
    function delete_user()
    {
        extract($_POST);

        @$delete = $this->db->query("DELETE FROM `user_list` where user_id = '{$id}'");
        if ($delete) {
            $resp['status'] = 'success';
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = 'User successfully deleted.';
        } else {
            $resp['status'] = 'failed';
            $resp['error'] = $this->db->error;
        }
        return json_encode($resp);
    }
    function update_credentials()
    {
        extract($_POST);
        $data = "";
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        if (!empty($cpassword) != $_SESSION['password']) {
            $resp['status'] = 'failed';
            $resp['msg'] = "Old password is incorrect.";
        } else {
            foreach ($_POST as $k => $v) {
                if (!in_array($k, array('id', 'cpassword')) && !empty($v)) {
                    if (!empty($data)) $data .= ",";
                    if ($k == 'password') $v = ($v);
                    $data .= " `{$k}` = '{$v}' ";
                }
            }
            $sql = "UPDATE `user_list` SET {$data} WHERE user_id = '{$_SESSION['user_id']}'";
            @$save = $this->db->query($sql);
            if ($save) {
                $resp['status'] = 'success';
                $_SESSION['flashdata']['type'] = 'success';
                $_SESSION['flashdata']['msg'] = 'Credential successfully updated.';
                foreach ($_POST as $k => $v) {
                    if (!in_array($k, array('id', 'cpassword')) && !empty($v)) {
                        if (!empty($data)) $data .= ",";
                        if ($k == 'password') $v = ($v);
                        $_SESSION[$k] = $v;
                    }
                }
            } else {
                $resp['status'] = 'failed';
                $resp['msg'] = 'Updating Credentials Failed. Error: ' . $this->db->error;
                $resp['sql'] = $sql;
            }
        }
        echo json_encode($resp);
    }

    function save_category()
    {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id'))) {
                $v = addslashes(trim($v));
                if (empty($id)) {
                    $cols[] = "`{$k}`";
                    $vals[] = "'{$v}'";
                } else {
                    if (!empty($data)) $data .= ", ";
                    $data .= " `{$k}` = '{$v}' ";
                }
            }
        }
        if (isset($cols) && isset($vals)) {
            $cols_join = implode(",", $cols);
            $vals_join = implode(",", $vals);
        }
        if (empty($id)) {
            $sql = "INSERT INTO `category_list` ({$cols_join}) VALUES ($vals_join)";
        } else {
            $sql = "UPDATE `category_list` set {$data} where category_id = '{$id}'";
        }
        @$check = $this->db->query("SELECT COUNT(category_id) as count from `category_list` where `name` = '{$name}' " . ($id > 0 ? " and category_id != '{$id}'" : ""))->fetch_array()['count'];
        if (@$check > 0) {
            $resp['status'] = 'failed';
            $resp['msg'] = 'Category already exists.';
        } else {
            @$save = $this->db->query($sql);
            if ($save) {
                $resp['status'] = "success";
                if (empty($id))
                    $resp['msg'] = "Category successfully saved.";
                else
                    $resp['msg'] = "Category successfully updated.";
            } else {
                $resp['status'] = "failed";
                if (empty($id))
                    $resp['msg'] = "Saving New Category Failed.";
                else
                    $resp['msg'] = "Updating Category Failed.";
                $resp['error'] = $this->db->error;
            }
        }
        return json_encode($resp);
    }
    function save_product()
{
    // Initialize response array
    $resp = ['status' => '', 'msg' => ''];
    
    // Ensure no output before this point
    ob_start();
    
    try {
        // Log debug information
        file_put_contents('debug_log.txt', "POST: " . print_r($_POST, true) . "\nFILES: " . print_r($_FILES, true) . "\n\n", FILE_APPEND);

        $id = isset($_POST['id']) ? $this->db->real_escape_string($_POST['id']) : '';
        $product_code = $this->db->real_escape_string($_POST['product_code']);
        $category_id = $this->db->real_escape_string($_POST['category_id']);
        $name = $this->db->real_escape_string($_POST['name']);
        $description = $this->db->real_escape_string($_POST['description']);
        $price = $this->db->real_escape_string($_POST['price']);
        $alert_restock = $this->db->real_escape_string($_POST['alert_restock']);
        $status = $this->db->real_escape_string($_POST['status']);

        // Handle image upload
        $upload_path = $_SERVER['DOCUMENT_ROOT'] . '/bakery/images/products/';

        // Check if directory exists or try to create it
        if (!file_exists($upload_path)) {
            if (!mkdir($upload_path, 0755, true)) {
                $resp['status'] = 'failed';
                $resp['msg'] = 'Directory creation failed. Manually create `/bakery/images/products/` and set permissions to 755.';
                return $this->return_json_response($resp);
            }
        }

        // Verify the directory is writable
        if (!is_writable($upload_path)) {
            $resp['status'] = 'failed';
            $resp['msg'] = 'Directory is not writable. Run: `chmod 755 /bakery/images/products/`';
            return $this->return_json_response($resp);
        }

        // Handle image file
        if (!empty($_FILES['image']['name'])) {
            $filename = $_FILES['image']['name'];
            $temp_file = $_FILES['image']['tmp_name'];

            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $image = time() . '_' . rand(1000, 9999) . '.' . $ext;

            if (!move_uploaded_file($temp_file, $upload_path . $image)) {
                $resp['status'] = 'failed';
                $resp['msg'] = 'Failed to upload image. Error: ' . (error_get_last() ? error_get_last()['message'] : 'Unknown error');
                return $this->return_json_response($resp);
            }
        } else if (isset($_POST['current_image']) && !empty($_POST['current_image'])) {
            $image = $this->db->real_escape_string($_POST['current_image']);
        } else if (empty($id)) {
            $resp['status'] = 'failed';
            $resp['msg'] = 'Product image is required.';
            return $this->return_json_response($resp);
        }

        // SQL handling
        if (empty($id)) {
            // New product
            $sql = "INSERT INTO product_list (product_code, category_id, name, description, price, image, alert_restock, status, avg_rating) 
                    VALUES ('$product_code', '$category_id', '$name', '$description', '$price', '$image', '$alert_restock', '$status', 0)";
        } else {
            // Update existing product
            $sql = "UPDATE product_list SET 
                    product_code = '$product_code', 
                    category_id = '$category_id', 
                    name = '$name', 
                    description = '$description', 
                    price = '$price', 
                    image = '$image', 
                    alert_restock = '$alert_restock', 
                    status = '$status',
                    date_updated = NOW()
                    WHERE product_id = '$id'";
        }

        $save = $this->db->query($sql);
        
        if ($save) {
            $resp['status'] = 'success'; // Changed 'Success' to lowercase 'success' for consistency
            $resp['msg'] = 'Product saved successfully';
        } else {
            $resp['status'] = 'failed';
            $resp['msg'] = 'Database error: ' . $this->db->error;
            error_log("SQL Error in save_product(): " . $this->db->error);
        }
        
        return $this->return_json_response($resp);
    } catch (Exception $e) {
        $resp['status'] = 'failed';
        $resp['msg'] = 'Server error: ' . $e->getMessage();
        error_log("Exception in save_product(): " . $e->getMessage());
        return $this->return_json_response($resp);
    }
}

// Helper function to ensure clean JSON response
private function return_json_response($data) {
    // Clear any output buffers
    ob_clean();
    
    // Set proper JSON header
    header('Content-Type: application/json');
    
    // Return JSON encoded string
    return json_encode($data);
}


 function delete_product()
{
    extract($_POST);
    
    // Use the class's database connection instead of global $conn
    $sql = "UPDATE product_list SET delete_flag = 1 WHERE product_id = '{$id}'";
    $delete = $this->db->query($sql);

    if ($delete) {
        $resp['status'] = 'success';
        $resp['msg'] = 'Product has been deleted successfully.';
    } else {
        $resp['status'] = 'failed';
        $resp['msg'] = 'Error: ' . $this->db->error;
    }

    return json_encode($resp);
}

    function delete_category()
    {
        global $conn;
        $id = $_POST['id'];

        $sql = "UPDATE category_list SET delete_flag = 1 WHERE category_id = '$id'";
        $delete = $conn->query($sql);

        if ($delete) {
            $resp['status'] = 'success';
            $resp['msg'] = 'Category has been deleted successfully.';
        } else {
            $resp['status'] = 'failed';
            $resp['msg'] = 'Error: ' . $conn->error;
        }

        return json_encode($resp);
    }




    function delete_orders()
    {
        extract($_POST);

        @$update = $this->db->query("DELETE FROM `users_orders` WHERE o_id = '{$id}'");
        if ($update) {
            $resp['status'] = 'success';
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = 'Order successfully deleted.';
        } else {
            $resp['status'] = 'failed';
            $resp['error'] = $this->db->error;
        }
        return json_encode($resp);
    }
    function save_stock()
    {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id'))) {
                $v = addslashes(trim($v));
                if (empty($id)) {
                    $cols[] = "`{$k}`";
                    $vals[] = "'{$v}'";
                } else {
                    if (!empty($data)) $data .= ", ";
                    $data .= " `{$k}` = '{$v}' ";
                }
            }
        }
        if (isset($cols) && isset($vals)) {
            $cols_join = implode(",", $cols);
            $vals_join = implode(",", $vals);
        }
        if (empty($id)) {
            $sql = "INSERT INTO `stock_list` ({$cols_join}) VALUES ($vals_join)";
        } else {
            $sql = "UPDATE `stock_list` set {$data} where stock_id = '{$id}'";
        }

        @$save = $this->db->query($sql);
        if ($save) {
            $resp['status'] = "success";
            if (empty($id))
                $resp['msg'] = "Stock successfully saved.";
            else
                $resp['msg'] = "Stock successfully updated.";
        } else {
            $resp['status'] = "failed";
            if (empty($id))
                $resp['msg'] = "Saving New Stock Failed.";
            else
                $resp['msg'] = "Updating Stock Failed.";
            $resp['error'] = $this->db->error;
        }
        return json_encode($resp);
    }
    function delete_stock()
    {
        extract($_POST);

        @$delete = $this->db->query("DELETE FROM `stock_list` where stock_id = '{$id}'");
        if ($delete) {
            $resp['status'] = 'success';
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = 'Stock successfully deleted.';
        } else {
            $resp['status'] = 'failed';
            $resp['error'] = $this->db->error;
        }
        return json_encode($resp);
    }
    function save_transaction()
    {
        extract($_POST);
        $data = "";
        $receipt_no = time();
        $i = 0;
        while (true) {
            $i++;
            $chk = $this->db->query("SELECT count(transaction_id) `count` FROM `transaction_list` where receipt_no = '{$receipt_no}' ")->fetch_array()['count'];
            if ($chk > 0) {
                $receipt_no = time() . $i;
            } else {
                break;
            }
        }
        $_POST['receipt_no'] = $receipt_no;
        $_POST['user_id'] = $_SESSION['user_id'];
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id')) && !is_array($_POST[$k])) {
                $v = addslashes(trim($v));
                if (empty($id)) {
                    $cols[] = "`{$k}`";
                    $vals[] = "'{$v}'";
                } else {
                    if (!empty($data)) $data .= ", ";
                    $data .= " `{$k}` = '{$v}' ";
                }
            }
        }
        if (isset($cols) && isset($vals)) {
            $cols_join = implode(",", $cols);
            $vals_join = implode(",", $vals);
        }
        if (empty($id)) {
            $sql = "INSERT INTO `transaction_list` ({$cols_join}) VALUES ($vals_join)";
        } else {
            $sql = "UPDATE `transaction_list` set {$data} where stock_id = '{$id}'";
        }

        @$save = $this->db->query($sql);
        if ($save) {
            $resp['status'] = "success";
            $_SESSION['flashdata']['type'] = "success";
            if (empty($id))
                $_SESSION['flashdata']['msg'] = "Transaction successfully saved.";
            else
                $_SESSION['flashdata']['msg'] = "Transaction successfully updated.";
            if (empty($id))
                $last_id = $this->db->insert_id;
            $tid = empty($id) ? $last_id : $id;
            $data = "";
            foreach ($product_id as $k => $v) {
                if (!empty($data)) $data .= ",";
                $data .= "('{$tid}','{$v}','{$quantity[$k]}','{$price[$k]}')";
            }
            if (!empty($data))
                $this->db->query("DELETE FROM transaction_items where transaction_id = '{$tid}'");
            $sql = "INSERT INTO transaction_items (`transaction_id`,`product_id`,`quantity`,`price`) VALUES {$data}";
            $save = $this->db->query($sql);
            $resp['transaction_id'] = $tid;
        } else {
            $resp['status'] = "failed";
            if (empty($id))
                $resp['msg'] = "Saving New Transaction Failed.";
            else
                $resp['msg'] = "Updating Transaction Failed.";
            $resp['error'] = $this->db->error;
        }
        return json_encode($resp);
    }
    function delete_transaction()
    {
        extract($_POST);

        @$delete = $this->db->query("DELETE FROM `transaction_list` where transaction_id = '{$id}'");
        if ($delete) {
            $resp['status'] = 'success';
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = 'Transaction successfully deleted.';
        } else {
            $resp['status'] = 'failed';
            $resp['error'] = $this->db->error;
        }
        return json_encode($resp);
    }
    function update_orders()
    {
        extract($_POST);

        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id'))) {
                $v = addslashes(trim($v));
                if (!empty($data)) $data .= ", ";
                $data .= " `{$k}` = '{$v}' ";
            }
        }

        $sql = "UPDATE `users_orders` SET {$data} WHERE o_id = '{$id}'";

        @$update = $this->db->query($sql);
        if ($update) {
            $resp['status'] = 'success';
            $resp['msg'] = "Order successfully updated";
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = 'Order successfully updated.';
        } else {
            $resp['status'] = 'failed';
            $resp['error'] = $this->db->error;
        }
        return json_encode($resp);
    }
}

$a = isset($_GET['a']) ? $_GET['a'] : '';
$action = new Actions();
switch ($a) {
    case 'login':
        echo $action->login();
        break;

    case 'signup':
        echo $action->signup();
        break;
    case 'logout':
        echo $action->logout();
        break;

    case 'save_user':
        echo $action->save_user();
        break;
    case 'edit_user':
        echo $action->edit_user();
        break;
    case 'update_user':
        echo $action->update_user();
        break;
    case 'delete_u':
        echo $action->delete_u();
        break;
    case 'delete_user':
        echo $action->delete_user();
        break;
    case 'update_credentials':
        echo $action->update_credentials();
        break;
    case 'save_category':
        echo $action->save_category();
        break;
    case 'delete_category':
        echo $action->delete_category();
        break;
    case 'save_product':
        echo $action->save_product();
        break;
    // Rest of your switch cases...
    default:
        echo json_encode(['status' => 'failed', 'msg' => 'Invalid action']);
        break;
    case 'delete_product':
        echo $action->delete_product();
        break;
    case 'delete_orders':
        echo $action->delete_orders();
        break;
    case 'save_stock':
        echo $action->save_stock();
        break;
    case 'delete_stock':
        echo $action->delete_stock();
        break;
    case 'save_transaction':
        echo $action->save_transaction();
        break;
    case 'delete_transaction':
        echo $action->delete_transaction();
        break;
    case 'update_orders':
        echo $action->update_orders();
        break;
        // default action here
        break;
}
