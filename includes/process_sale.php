<?php
	//messages processings
	require_once('../system_load.php');
	//loading system.
	
	authenticate_user('subscriber');
	
	extract($_POST);
	
	//form validation first important part.
	
	if($client_id == '') { 
		HEADER('LOCATION: ../manage_sale.php?message=Please select vendor.');
		exit();
	}
	
	if(!isset($product_id)) { 
		HEADER('LOCATION: ../manage_sale.php?message=Please enter at least 1 product to process invoice.');
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
			HEADER('LOCATION: ../manage_sale.php?message=Quantity and price should not less than or equal to zero and should be a number.');
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
			HEADER('LOCATION: ../manage_sale.php?message=Quantity and price should not less than or equal to zero and should be a number.');
			exit();
		}
	}
	
	if($payment_method == '' || $payment_method == '0') { 
		HEADER('LOCATION: ../manage_sale.php?message=Please select a payment method.');
		exit();
	}
	//forms validation ends here.
	
	//form processing starts here.
	
	$sale_id = $sale->add_sale($date, $custom_inv_no, $memo, $client_id, $payment_method);
	//add purchase ends here.
	if($payment_method == 'cash') { 
		$transaction_type = 'Cash Sale';
	} else if($payment_method == 'credit_card') {
		$transaction_type = 'Credit Card Sale';	
	} else { 
		$transaction_type = 'Sale Invoice';
	}
	$client_log_id = $client->add_log($date, $client_id, $transaction_type, $sale_id);
	
	$grand_total = 0;
	foreach($qty as $index => $qt) { 
		$total = $qt*$selling_price[$index];
		$tax = $qt*$tax_rate[$index];
		$grand_total = $total+$grand_total+$tax;
	}
	if($payment_method == 'cash') { 
		$receiving_id = $client->add_receiving($date, 'Cash Sale', $sale_id, $memo, $grand_total, $client_id, $cash_received, $table_id, $tax);
		$client_log_id = $client->add_log($date, $client_id, 'Sale Receiving', $receiving_id);
	}
	if($payment_method == 'credit_card') { 
		$receiving_id = $client->add_receiving($date, 'Credit Card Sale', $sale_id, $memo, $grand_total, $client_id, $cash_received, $table_id);
		$client_log_id = $client->add_log($date, $client_id, 'Sale Receiving', $receiving_id);
	}
	//start here adding products ----------------------- <<<<
	foreach($qty as $index => $qt) {
		$product_id_in = $product_id[$index];
		$quantity = $qt;
		$price_in = $selling_price[$index];
		$tax_in = $tax_rate[$index];
		$warehouse_id_in = $warehouse_id[$index];
		
		if($payment_method == 'cash' || $payment_method == 'credit_card') { 
			$received = $price_in*$quantity+$quantity*$tax_in;
		} else { 
			$received = 0;
		}
		$total_credit = $price_in*$quantity+$tax_in*$quantity;
		$creditor_id = $sale->add_creditor($total_credit, $received, $client_id);		
		//debt add ends here.
		$inventory_id = $sale->add_inventory('0', $quantity, $product_id_in, $warehouse_id_in);
		//add inventory ends here.
		$price_id = $sale->add_price('0', $price_in, $tax_in, $product_id_in);
		//add price ends here.
		$purchase_detail_id = $sale->add_sale_detail($sale_id, $price_id, $inventory_id, $creditor_id);
	} //processing details
	
	if(isset($sale_type) && $sale_type == "pos_sale") { 
		if($save == 'Save') { 
			HEADER('LOCATION: ../point_of_sale.php?message=Sale was saved successfuly.');
		} else if($print == 'Print'){ 
			HEADER('LOCATION: ../point_of_sale.php?message=Sale was saved successfuly.&sale_id='.$sale_id);	
		}	
	} else { 
		if($save == 'Save') { 
			HEADER('LOCATION: ../manage_sale.php?message=Sale was saved successfuly.');
		} else if($print == 'Print'){ 
			HEADER('LOCATION: ../manage_sale.php?message=Sale was saved successfuly.&sale_id='.$sale_id);	
		}
	}