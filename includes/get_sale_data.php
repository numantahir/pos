<?php
	//messages processings
	require_once('../system_load.php');
	//loading system.
	
	extract($_POST);
	if(isset($data_type) && isset($client_id)) { 
		$product_name = $products->get_product_info($product_id, 'product_name');
		$product_manual_id = $products->get_product_info($product_id, 'product_manual_id');	
		//setting price level.
		$price_level = $client->get_client_info($client_id, 'price_level');
		
		$product_price = $products->get_product_rate($product_id, $price_level);
		//get tax info
		$tax_id = $products->get_product_info($product_id, 'tax_id');
		$tax_type = $ProductTax->get_tax_info($tax_id, 'tax_type');
		$tax_rate = $ProductTax->get_tax_info($tax_id, 'tax_rate');
		if($client_id == 1){
		if($tax_rate == 0) { 
			$tax = 0;
		} else if($tax_type == 'fixed') { 
			$tax = $tax_rate;
		} else if($tax_type == 'percentage') { 
			$tax = $product_price/100*$tax_rate;
		}
		} else {
			$tax = 0;
		}
		
		$sale_data = array(
						"product_name" => $product_name,
						"product_price" => $product_price,
						"tax" => $tax
					);
		echo json_encode($sale_data);
		exit();
	}
	
	if(isset($product_id) && !isset($client_id)) { 
		$warehouse_options = $warehouses->warehouse_options_by_inv($product_id);
		//sending data back.
		$sale_data = array(
			"warehouse_options" => $warehouse_options,
			"default_warehouse" => get_option($_SESSION['store_id'].'_default_warehouse')
		);
		echo json_encode($sale_data);			
	}//When product id is set only
	//sending data only for warehouse information about inventory./
	if(isset($client_id)) {
		$product_name = $products->get_product_info($product_id, 'product_name');
		$product_manual_id = $products->get_product_info($product_id, 'product_manual_id');	
		$warehouse_name = $warehouses->get_warehouse_info($warehouse_id, 'name');
		//setting price level.
		$price_level = $client->get_client_info($client_id, 'price_level');
		
		$product_price = $products->get_product_rate($product_id, $price_level);
		//get tax info
		$tax_id = $products->get_product_info($product_id, 'tax_id');
		$tax_type = $ProductTax->get_tax_info($tax_id, 'tax_type');
		$tax_rate = $ProductTax->get_tax_info($tax_id, 'tax_rate');
		
		if($tax_rate == 0) { 
			$tax = 0;
		} else if($tax_type == 'fixed') { 
			$tax = $tax_rate;
		} else if($tax_type == 'percentage') { 
			$tax = $product_price/100*$tax_rate;
		}

		$sale_data = array(
						"product_name" => $product_name,
						"product_manual_id" => $product_manual_id,
						"warehouse_name" => $warehouse_name,
						"product_price" => $product_price,
						"tax" => $tax
					);
				echo json_encode($sale_data);	
	}