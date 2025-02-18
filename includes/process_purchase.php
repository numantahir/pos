<?php
	//messages processings
	require_once('../system_load.php');
	//loading system.
	
	$purchase_obj = new Purchase;
	$vendor_obj = new Vendor;
	
	extract($_POST);
	
	//form validation first important part.
	
	if($vendor_id == '') { 
		HEADER('LOCATION: ../manage_purchase.php?message=Please select vendor.');
		exit();
	}
	
	if(!isset($product_id)) { 
		HEADER('LOCATION: ../manage_purchase.php?message=Please enter at least 1 product to process invoice.');
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
			HEADER('LOCATION: ../manage_purchase.php?message=Quantity and cost should not less than or equal to zero and should be a number.');
			exit();
		}
	}
	
	if(isset($cost)) {
		$error = 0; 
		foreach($cost as $qt) { 
			if($qt == '' || $qt == '0' || !is_numeric($qt)) { 
				$error = 1;
			} 
		}//foreach 
		if($error == 1) { 
			HEADER('LOCATION: ../manage_purchase.php?message=Quantity and cost should not less than or equal to zero and should be a number.');
			exit();
		}
	}
	
	if($payment_method == '' || $payment_method == '0') { 
		HEADER('LOCATION: ../manage_purchase.php?message=Please select a payment method.');
		exit();
	}
	//forms validation ends here.
	
	//form processing starts here.
	
	$purchase_id = $purchase_obj->add_purchase($date, $supp_inv_no, $memo, $vendor_id, $payment_method);
	//add purchase ends here.
	if($payment_method == 'cash') { 
		$transaction_type = 'Cash Purchase';
	} else { 
		$transaction_type = 'Purchase Invoice';
	}
	$vendor_log_id = $vendor_obj->add_log($date, $vendor_id, $transaction_type, $purchase_id);
	
	$grand_total = 0;
	foreach($qty as $index => $qt) { 
		$total = $qt*$cost[$index];
		$grand_total = $total+$grand_total;
	}
	if($payment_method == 'cash') { 
		$payment_id = $vendor_obj->add_payment($date, 'Cash Purchase', $purchase_id, $memo, $grand_total, $vendor_id);
		$vendor_log_id = $vendor_obj->add_log($date, $vendor_id, 'Purchase Payment', $payment_id);
	}
	
	foreach($qty as $index => $qt) {
		$product_id_in = $product_id[$index];
		$quantity = $qt;
		$cost_in = $cost[$index];
		$warehouse_id_in = $warehouse_id[$index];
		
		if($payment_method == 'cash') { 
		$paid = $cost_in*$quantity;
		} else { 
		$paid = 0;
		}
		$debt_id = $purchase_obj->add_debt($cost_in*$quantity, $paid, $vendor_id);		
		//debt add ends here.
		$inventory_id = $purchase_obj->add_inventory($quantity, '0', $product_id_in, $warehouse_id_in);
		//add inventory ends here.
		$price_id = $purchase_obj->add_price($cost_in, '0', $product_id_in);
		//add price ends here.
		$purchase_detail_id = $purchase_obj->add_purchase_detail($purchase_id, $price_id, $inventory_id, $debt_id);
	} //processing details
	
	if($save == 'Save') { 
		HEADER('LOCATION: ../manage_purchase.php?message=Purchase was saved successfuly.');
	} else if($print == 'Print'){ 
		HEADER('LOCATION: ../manage_purchase.php?message=Purchase was saved successfuly.&purchase_id='.$purchase_id);	
	}