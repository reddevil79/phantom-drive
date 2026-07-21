<?php
if(!empty($_GET["action"])) 
{
$productId = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';
$quantity = isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : '';

switch($_GET["action"])
 {
	case "add":
		if(!empty($quantity)) {
			$stmt = $db->prepare("SELECT * FROM product_list where product_id= ?");
			$stmt->bind_param('i',$productId);
			$stmt->execute();
			$productDetails = $stmt->get_result()->fetch_object();
			$itemArray = array(
				$productDetails->product_id => array(
					'name' => $productDetails->name,
					'product_id' => $productDetails->product_id,
					'quantity' => $quantity,
					'price' => $productDetails->price,
					'order_notes' => $orderNotes // add the order notes to the item array
				)
			);
			if(!empty($_SESSION["c_item"])) {
				if(in_array($productDetails->product_id,array_keys($_SESSION["c_item"]))) {
					foreach($_SESSION["c_item"] as $k => $v) {
						if($productDetails->product_id == $k) {
							if(empty($_SESSION["c_item"][$k]["quantity"])) {
								$_SESSION["c_item"][$k]["quantity"] = 0;
							}
							$_SESSION["c_item"][$k]["quantity"] += $quantity;
						}
					}
				} else {
					$_SESSION["c_item"] = $_SESSION["c_item"] + $itemArray;
				}
			} else {
				$_SESSION["c_item"] = $itemArray;
			}
		}
		break;
	
			
	case "remove":
		if(!empty($_SESSION["c_item"]))
			{
				foreach($_SESSION["c_item"] as $k => $v) 
				{
					if($productId == $v['product_id'])
						unset($_SESSION["c_item"][$k]);
				}
			}
			break;
			
	case "empty":
			unset($_SESSION["c_item"]);
			break;
			
	case "check":
			header("location:checkout.php");
			break;
	}
}