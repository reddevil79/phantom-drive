<?php
session_start();
include("connection/connect.php");

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $action = $_POST['action'];
    
    $response = ['success' => false];
    
    if($action == 'update') {
        if(isset($_SESSION["c_item"][$product_id])) {
            if($quantity <= 0) {
                unset($_SESSION["c_item"][$product_id]);
            } else {
                $_SESSION["c_item"][$product_id]["quantity"] = $quantity;
            }
            
            // Calculate updated totals
            $cart_count = 0;
            $cart_total = 0;
            $item_price = 0;
            
            foreach($_SESSION["c_item"] as $item) {
                $cart_count += $item["quantity"];
                $cart_total += ($item["price"] * $item["quantity"]);
                
                if($item['product_id'] == $product_id) {
                    $item_price = $item["price"];
                }
            }
            
            $response = [
                'success' => true,
                'cart_count' => $cart_count,
                'cart_total' => $cart_total,
                'item_price' => $item_price
            ];
        } else {
            $response['message'] = 'Item not found in cart';
        }
    }
    
    echo json_encode($response);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);