<?php
    require_once('../system_load.php');
	
    //user Authentication.
	authenticate_user('subscriber');
		   
    global $db;
	
	if(isset($_POST['category_id'])) {
		if(isset($_POST['pn'])) { 
			$_SESSION['pn'] = $_POST['pn'];
		}
		$_SESSION['category_id'] = $_POST['category_id'];
		$products = $product->list_pos_products($_SESSION['category_id']); 
		$products_data = array(
					 		"dataproduct" => $products
						);   
		echo json_encode($products_data);
		unset($_SESSION['pn']);
		exit();
	}
	
    if(!isset($_GET["term"])) { 
		exit();
	}
    $param = $_GET["term"];
     
    //query the database
    $query = "SELECT product_id, product_manual_id, product_name FROM products WHERE product_name LIKE '%$param%' AND store_id='".$_SESSION['store_id']."' ORDER BY product_manual_id asc";
     
	 $result = $db->query($query);
	 
	 if($result->num_rows == '0') { 
	 	$query = "SELECT product_id, product_manual_id, product_name FROM products WHERE product_manual_id LIKE '%$param%' AND store_id='".$_SESSION['store_id']."' ORDER BY product_manual_id  asc";
     
	 $result = $db->query($query);
	 }
	 
    //build array of results
    for ($x = 0, $numrows = $result->num_rows; $x < $numrows; $x++) {
        $row = $result->fetch_assoc();
     
        $friends[$x] = array(
					"id" => $row["product_id"],
					"value" => $row["product_name"].' | '.$row["product_manual_id"]
					);        
    }
	
    //echo JSON to page
    echo json_encode($friends);