<?php
	//messages processings
	require_once('../system_load.php');
	//loading system.
	$sale_return_obj = new SaleReturn;
	$client_obj = new Client;
	$sale_obj = new Sale;
	
	extract($_POST);
	//form validation first important part.
	
	if($client_id == '') { 
		HEADER('LOCATION: ../manage_sale_returns.php?message=Please select client.');
		exit();
	}
	
	if($reason_id == '') { 
		HEADER('LOCATION: ../manage_sale_returns.php?message=Please select return reason.');
		exit();
	}
	
	if(!isset($product_id)) { 
		HEADER('LOCATION: ../manage_sale_returns.php?message=Please enter at least 1 product to process invoice.');
		exit();
	}
	
	if(isset($qty)) {
		$error = 0; 
		foreach($qty as $qt) { 
			if($qt == '' || $qt == '0' || !is_numeric($qt)) { 
				$error = 1;
			} 
		}//foreach 
		if($error == 1) { 
			HEADER('LOCATION: ../manage_sale_returns.php?message=Quantity and price should not less than or equal to zero and should be a number.');
			exit();
		}
	}
	
	if(isset($price)) {
		$error = 0; 
		foreach($price as $qt) { 
			if($qt == '' || $qt == '0' || !is_numeric($qt)) { 
				$error = 1;
			} 
		}//foreach 
		if($error == 1) { 
			HEADER('LOCATION: ../manage_sale_returns.php?message=Quantity and price should not less than or equal to zero and should be a number.');
			exit();
		}
	}
	
	if($payment_method == '' || $payment_method == '0') { 
		HEADER('LOCATION: ../manage_sale_returns.php?message=Please select a payment method.');
		exit();
	}
	//forms validation ends here.
	
	//form processing starts here.
	
	$sale_rt_id = $sale_return_obj->add_sale_return($date, $sale_inv_no, $memo, $client_id, $payment_method);
	//add purchase ends here.
	if($payment_method == 'cash') { 
		$transaction_type = 'Sale Return';
	} else { 
		$transaction_type = 'Invoice Return';
	}
	$client_log_id = $client_obj->add_log($date, $client_id, $transaction_type, $sale_rt_id);
	
	$grand_total = 0;
	foreach($qty as $index => $qt) { 
		$total = $qt*$price[$index];
		$tax_in = $qt*$tax[$index];
		$grand_total = $total+$grand_total+$tax_in;
	}
	if($payment_method == 'cash') { 
		$return_payment_id = $client_obj->add_return_payment($date, 'Sale Return', $sale_rt_id, $memo, $grand_total, $client_id);
		$client_log_id = $client_obj->add_log($date, $client_id, 'Sale Return Refund', $return_payment_id);
	}
	//start here adding products ----------------------- <<<<
	foreach($qty as $index => $qt) {
		$product_id_in = $product_id[$index];
		$quantity = $qt;
		$price_in = $price[$index];
		$tax_in = $tax[$index];
		$warehouse_id_in = $warehouse_id[$index];
		
		if($payment_method == 'cash') { 
		$received = $price_in*$quantity+$quantity*$tax_in;
		} else { 
		$received = 0;
		}
		$total_credit = $price_in*$quantity+$tax_in*$quantity;
		$creditor_id = $sale_obj->add_creditor($received, $total_credit, $client_id);		
		//debt add ends here.
		$inventory_id = $sale_obj->add_inventory($quantity, '0', $product_id_in, $warehouse_id_in);
		//add inventory ends here.
		$price_id = $sale_obj->add_price('0', $price_in, $tax_in, $product_id_in);
		//add price ends here.add_return_detail($sale_rt_id)
		$purchase_detail_id = $sale_return_obj->add_return_detail($sale_rt_id, $price_id, $inventory_id, $creditor_id, $reason_id);
	} //processing details
	
	if($save == 'Save') { 
		HEADER('LOCATION: ../manage_sale_returns.php?message=Sale was saved successfuly.');
	} else if($print == 'Print'){ 
		HEADER('LOCATION: ../manage_sale_returns.php?message=Sale was saved successfuly.&sale_rt_id='.$sale_rt_id);	
	}