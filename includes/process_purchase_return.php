<?php
	//messages processings
	require_once('../system_load.php');
	//loading system.
	$purchase_return_obj = new PurchaseReturn;
	$vendor_obj = new Vendor;
	$purchase_obj = new Purchase;
	
	extract($_POST);
	//form validation first important part.
	
	if($vendor_id == '') { 
		HEADER('LOCATION: ../manage_purchase_returns.php?message=Please select vendor.');
		exit();
	}
	
	if($reason_id == '') { 
		HEADER('LOCATION: ../manage_purchase_returns.php?message=Please select return reason.');
		exit();
	}
	
	if(!isset($product_id)) { 
		HEADER('LOCATION: ../manage_purchase_returns.php?message=Please enter at least 1 product to process invoice.');
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
			HEADER('LOCATION: ../manage_purchase_returns.php?message=Quantity and price should not less than or equal to zero and should be a number.');
			exit();
		}
	}
	
	if(isset($cost_p)) {
		$error = 0; 
		foreach($cost_p as $qt) { 
			if($qt == '' || $qt == '0' || !is_numeric($qt)) { 
				$error = 1;
			} 
		}//foreach 
		if($error == 1) { 
			HEADER('LOCATION: ../manage_purchase_returns.php?message=Quantity and price should not less than or equal to zero and should be a number.');
			exit();
		}
	}
	
	if($payment_method == '' || $payment_method == '0') { 
		HEADER('LOCATION: ../manage_purchase_returns.php?message=Please select a payment method.');
		exit();
	}
	//forms validation ends here.
	
	//form processing starts here.
	
	$purchase_rt_id = $purchase_return_obj->add_purchase_return($date, $purchase_inv_no, $memo, $vendor_id, $payment_method);
	//add purchase ends here.
	if($payment_method == 'cash') { 
		$transaction_type = 'Purchase Return';
	} else { 
		$transaction_type = 'Invoice Return';
	}
	$client_log_id = $vendor_obj->add_log($date, $vendor_id, $transaction_type, $purchase_rt_id);
	
	$grand_total = 0;
	foreach($qty as $index => $qt) { 
		$total = $qt*$cost_p[$index];
		$grand_total = $total+$grand_total;
	}
	if($payment_method == 'cash') { 
		$return_payment_id = $vendor_obj->add_return_payment($date, 'Purchase Return', $purchase_rt_id, $memo, $grand_total, $vendor_id);
		$client_log_id = $vendor_obj->add_log($date, $vendor_id, 'Purchase Return Refund', $return_payment_id);
	}
	//start here adding products ----------------------- <<<<
	foreach($qty as $index => $qt) {
		$product_id_in = $product_id[$index];
		$quantity = $qt;
		$price_in = $cost_p[$index];
		$warehouse_id_in = $warehouse_id[$index];
		
		if($payment_method == 'cash') { 
		$received = $price_in*$quantity;
		} else { 
		$received = 0;
		}
		$total_credit = $price_in*$quantity;
		$creditor_id = $purchase_obj->add_debt($received, $total_credit, $vendor_id);		
		//debt add ends here.
		$inventory_id = $purchase_obj->add_inventory('0', $quantity, $product_id_in, $warehouse_id_in);
		//add inventory ends here.
		$price_id = $purchase_obj->add_price($price_in, '0', '0', $product_id_in);
		//add price ends here.add_return_detail($purchase_rt_id)
		$purchase_detail_id = $purchase_return_obj->add_return_detail($purchase_rt_id, $price_id, $inventory_id, $creditor_id, $reason_id);
	} //processing details
	
	if($save == 'Save') { 
		HEADER('LOCATION: ../manage_purchase_returns.php?message=Sale was saved successfuly.');
	} else if($print == 'Print'){ 
		HEADER('LOCATION: ../manage_purchase_returns.php?message=Sale was saved successfuly.&purchase_rt_id='.$purchase_rt_id);	
	}