<?php
//Notes Class

class Vendor {
	public $vendor_id;
	public $full_name;
	public $contact_person;
	public $mobile;
	public $phone;
	public $address;
	public $city;
	public $state;
	public $zipcode;
	public $country;
	public $provider_of;
	
	function get_vendor_info($vendor_id, $term) { 
		global $db;
		$query = "SELECT * from vendors WHERE vendor_id='".$vendor_id."'";
		$result = $db->query($query) or die($db->error);
		$row = $result->fetch_array();
		return $row[$term];
	}//get user email ends here.
	
	function add_vendor($full_name, $contact_person, $mobile, $phone, $address, $city, $state, $zipcode, $country, $provider_of) {
		global $db;
		$query = "SELECT * from vendors WHERE full_name='".$full_name."' AND store_id='".$_SESSION['store_id']."'";
		$result = $db->query($query) or die($db->error);
		$num_rows = $result->num_rows;
		
		if($num_rows > 0) { 
			return 'A vendor with same name already exists.';
		} else { 
			$query = "INSERT into vendors(vendor_id, full_name, contact_person, mobile, phone, address, city, state, zipcode, country, provider_of, store_id)
				VALUES(NULL, '".$full_name."', '".$contact_person."', '".$mobile."', '".$phone."', '".$address."', '".$city."', '".$state."', '".$zipcode."', '".$country."', '".$provider_of."', '".$_SESSION['store_id']."')
			";
			$result = $db->query($query) or die($db->error);
			$_SESSION['vn_id'] = $db->insert_id;
			return 'Vendor added successfuly.';
		}
	}//add warehouse ends here.
	
	function set_vendor($vendor_id) { 
		global $db;
		$query = 'SELECT * from vendors WHERE vendor_id="'.$vendor_id.'" AND store_id="'.$_SESSION['store_id'].'"';
		$result = $db->query($query) or die($db->error);
		$row = $result->fetch_array();
		extract($row);
		$this->vendor_id = $vendor_id;
		$this->full_name = $full_name;
		$this->contact_person = $contact_person;
		$this->mobile = $mobile;
		$this->phone = $phone;
		$this->address = $address;
		$this->city = $city;
		$this->state = $state;
		$this->zipcode = $zipcode;
		$this->country = $country;
		$this->provider_of = $provider_of;
	}//Set Warehouse ends here..
	
	function update_vendor($vendor_id, $full_name, $contact_person, $mobile, $phone, $address, $city, $state, $zipcode, $country, $provider_of) { 
		global $db;
		$query = 'UPDATE vendors SET
				  full_name = "'.$full_name.'",
				  contact_person = "'.$contact_person.'",
				  mobile = "'.$mobile.'",
				  phone = "'.$phone.'",
				  address = "'.$address.'",
				  city = "'.$city.'",
				  state = "'.$state.'",
				  zipcode = "'.$zipcode.'",
				  country = "'.$country.'",
				  provider_of = "'.$provider_of.'"
				   WHERE vendor_id="'.$vendor_id.'" AND store_id="'.$_SESSION['store_id'].'"';
		$result = $db->query($query) or die($db->error);
		return 'Vendor updated Successfuly!';
	}//update user level ends here.	
	
	function list_vendors() {
		global $db;
		$query = 'SELECT * from vendors WHERE store_id="'.$_SESSION['store_id'].'" ORDER by full_name ASC';
		$result = $db->query($query) or die($db->error);
		$content = '';
		$count = 0;
		while($row = $result->fetch_array()) { 
			extract($row);
			$count++;
			if($count%2 == 0) { 
				$class = 'even';
			} else { 
				$class = 'odd';
			}
			$content .= '<tr class="'.$class.'">';
			$content .= '<td>';
			$content .= $vendor_id;
			$content .= '</td><td>';
			$content .= $full_name;
			$content .= '</td><td>';
			$content .= $contact_person;
			$content .= '</td><td>';
			$content .= $mobile;
			$content .= '</td><td>';
			$content .= $phone;
			$content .= '</td><td>';
			$content .= $address.' '.$city.' '.$state.' '.$zipcode.' '.$country;
			$content .= '</td><td>';
			$content .= $provider_of;
			$content .= '</td><td>';
			$content .= number_format($this->get_vendor_balance($vendor_id), 2);
			$content .= '</td>';
			if(partial_access('admin')) {
			$content .= '<td><form method="post" name="edit" action="manage_vendor.php">';
			$content .= '<input type="hidden" name="edit_vendor" value="'.$vendor_id.'">';
			$content .= '<input type="submit" class="btn btn-default btn-sm" value="Edit">';
			$content .= '</form>';
			$content .= '</td><td>';
			$content .= '<form method="post" name="delete" onsubmit="return confirm_delete();" action="">';
			$content .= '<input type="hidden" name="delete_vendor" value="'.$vendor_id.'">';
			$content .= '<input type="submit" class="btn btn-default btn-sm" value="Delete">';
			$content .= '</form>';
			$content .= '</td>';
			}
			$content .= '</tr>';
			unset($class);
		}//loop ends here.	
	echo $content;
	}//list_notes ends here.
	
	function delete_vendor($vendor_id) { 
		global $db;
		$query = "SELECT * FROM vendor_log WHERE vendor_id='".$vendor_id."'";
		$result = $db->query($query) or die($db->error);
		$num_rows = $result->num_rows;
		
		if($num_rows > 0) { 
			return 'Please delete purchase invoices, payments, return invoices, return receivings for related vendor first.';
		} else { 
			$query = "DELETE FROM vendors WHERE vendor_id='".$vendor_id."'";
			$result = $db->query($query) or die($db->error);
			return 'Vendor deleted successfuly!';
		}
	}//delete client ends here.
	
	function vendor_options($vendor_id) {
		global $db;
		$query = 'SELECT * from vendors WHERE store_id="'.$_SESSION['store_id'].'" ORDER by full_name ASC';
		$result = $db->query($query) or die($db->error);
		$options = '';
		if($vendor_id != '') { 
			while($row = $result->fetch_array()) { 
				if($vendor_id == $row['vendor_id']) {
				$options .= "<option selected='selected' value='".$row['vendor_id']."'>".$row['full_name']." (".$row['mobile'].")</option>";
				} else { 
				$options .= "<option value='".$row['vendor_id']."'>".$row['full_name']." (".$row['mobile'].")</option>";
				}
			}
		} else { 
			while($row = $result->fetch_array()) { 
				$options .= "<option value='".$row['vendor_id']."'>".$row['full_name']." (".$row['mobile'].")</option>";
			}
		}
		return $options;
	}//vendor options ends here.
	
	function add_return_payment($date, $method, $ref_no, $memo, $amount, $vendor_id) { 
		global $db;
		$query = "INSERT into purchase_return_receiving(return_receiving_id, datetime, method, ref_no, memo, amount, vendor_id, agent_id, store_id) VALUES(NULL, '".$date."', '".$method."', '".$ref_no."', '".$memo."', '".$amount."', '".$vendor_id."', '".$_SESSION['user_id']."', '".$_SESSION['store_id']."')";
		$result = $db->query($query) or die($db->error);
		return $db->insert_id;
	}//add_payment ends here.
	
	function add_log($datetime, $vendor_id, $transaction_type, $type_table_id) {
		global $db;
		$query = "INSERT into vendor_log(vendor_log_id, datetime, vendor_id, transaction_type, type_table_id, store_id) VALUES(NULL, '".$datetime."', '".$vendor_id."', '".$transaction_type."', '".$type_table_id."', '".$_SESSION['store_id']."')";	
		$result = $db->query($query) or die($db->error);
		return $db->insert_id;
	}//add log ends here.
	
	function add_payment($date, $method, $ref_no, $memo, $amount, $vendor_id) { 
		global $db;
		$query = "INSERT into payments (payment_id, datetime, method, ref_no, memo, amount, vendor_id, agent_id, store_id) VALUES(NULL, '".$date."', '".$method."', '".$ref_no."', '".$memo."', '".$amount."', '".$vendor_id."', '".$_SESSION['user_id']."', '".$_SESSION['store_id']."')";
		$result = $db->query($query) or die($db->error);
		return $db->insert_id;
	}//add_payment ends here.
	
	function clear_debts($amount, $vendor_id){
		global $db;
		
		$query = "SELECT * FROM debts WHERE vendor_id='".$vendor_id."' ORDER by debt_id ASC";
		$result = $db->query($query) or die($db->error);
		
		while($row = $result->fetch_array()) {
			extract($row);
			if($payable == 0 || $payable == $paid || $amount == 0) { 
				//do nothing.
			} else { 
				if($paid == 0) {
					if($amount < $payable) { 
						$pay = $amount;
					} else { 
						$pay = $payable;
					}
					$query_up = "UPDATE debts SET
						paid = '".$pay."'
						WHERE debt_id='".$debt_id."'
						";
					$amount -= $pay;	
				} else if($paid != 0) { 
					$difference = $payable-$paid;
					if($amount < $difference) { 
						$pay = $amount+$paid;
					} else { 
						$pay = $difference+$paid;
					}
					$query_up = "UPDATE debts SET
						paid = '".$pay."'
						WHERE debt_id='".$debt_id."'
						";
					$amount -= $difference;	
				}
				$result_up = $db->query($query_up) or die($db->error);
			}//main if ends here.
		}//main loop ends.
		
	}//debts clear ends here.--
	
	function get_vendor_balance($vendor_id) { 
		global $db;
		
		$debtQuery = "SELECt * from debts WHERE vendor_id='".$vendor_id."' AND store_id='".$_SESSION['store_id']."'";
		$debtResult = $db->query($debtQuery) or die($db->error);
		$payable = 0;
	
		while($debtRow = $debtResult->fetch_array()) {
			$payable += $debtRow['payable'];
			if($debtRow['payable'] == 0) { 
				$payable -= $debtRow['paid'];
			}
		}

		$paymentQuery = "SELECt * from payments WHERE vendor_id='".$vendor_id."' AND store_id='".$_SESSION['store_id']."'";
		$paymentResult = $db->query($paymentQuery) or die($db->error);
	
		while($paymentRow = $paymentResult->fetch_array()) {
			$payable -= $paymentRow['amount'];
		}
		
		$purchase_return_payment = "SELECt * from purchase_return_receiving WHERE vendor_id='".$vendor_id."' AND store_id='".$_SESSION['store_id']."'";
		$purchase_payment_result = $db->query($purchase_return_payment) or die($db->error);
		
		while($purchase_return_row = $purchase_payment_result->fetch_array()) { 
			$payable -= $purchase_return_row['amount'];
		}
		
		return $payable;
	}//get vendor balance ends here.
	
	function list_payments() {
		global $db;
		$query = 'SELECT * from payments WHERE store_id="'.$_SESSION['store_id'].'" ORDER by payment_id DESC';
		$result = $db->query($query) or die($db->error);
		$content = '';

		while($row = $result->fetch_array()) { 
			extract($row);
			
			$datetime = strtotime($datetime);
			$date = date('d-M-Y', $datetime);
			
			$client = $this->get_vendor_info($vendor_id, 'full_name');
			
			$user = new Users;
			$agent = $user->get_user_info($agent_id, 'first_name').' '.$user->get_user_info($agent_id, 'last_name');
			
			$content .= '<tr><td>';
			$content .= $payment_id;
			$content .= '</td><td>';
			$content .= $date;
			$content .= '</td><td>';
			$content .= $method;
			$content .= '</td><td>';
			$content .= $ref_no;
			$content .= '</td><td>';
			$content .= $agent;
			$content .= '</td><td>';
			$content .= $client;
			$content .= '</td><td>';
			$content .= $memo;
			$content .= '</td><td>';
			$content .= $amount;
			$content .= '</td>';
			if(partial_access('admin')) { 
				$content .= '<td><form method="post" name="delete" onsubmit="return confirm_delete();" action="">';
				$content .= '<input type="hidden" name="delete_payment" value="'.$payment_id.'">';
				$content .= '<input type="submit" class="btn btn-default btn-sm" value="Delete">';
				$content .= '</form>';
				$content .= '</td>'; }
				$content .= '</tr>';
			
			unset($class);
		}//loop ends here.	
	echo $content;
	}//list_notes ends here.
	
	function delete_payment($payment_id) {
		global $db;
		
		$query = "DELETE from payments WHERE payment_id='".$payment_id."'";
		$result = $db->query($query) or die($db->error);
		
		$query = "DELETE from vendor_log WHERE transaction_type='Purchase Payment' AND type_table_id='".$payment_id."'";
		$result = $db->query($query) or die($db->error);
		
		$query = "DELETE from vendor_log WHERE transaction_type='Payment' AND type_table_id='".$payment_id."'";
		$result = $db->query($query) or die($db->error);
		
		return 'Payment deleted Successfuly.';	
	}//delete_purchase return receiving.
	
	function list_return_receivings() {
		global $db;
		$query = 'SELECT * from purchase_return_receiving WHERE store_id="'.$_SESSION['store_id'].'" ORDER by return_receiving_id DESC';
		$result = $db->query($query) or die($db->error);
		$content = '';

		while($row = $result->fetch_array()) { 
			extract($row);
			
			$datetime = strtotime($datetime);
			$date = date('d-M-Y', $datetime);
			
			$client = $this->get_vendor_info($vendor_id, 'full_name');
			
			$user = new Users;
			$agent = $user->get_user_info($agent_id, 'first_name').' '.$user->get_user_info($agent_id, 'last_name');
			
			$content .= '<tr><td>';
			$content .= $return_receiving_id;
			$content .= '</td><td>';
			$content .= $date;
			$content .= '</td><td>';
			$content .= $method;
			$content .= '</td><td>';
			$content .= $ref_no;
			$content .= '</td><td>';
			$content .= $agent;
			$content .= '</td><td>';
			$content .= $client;
			$content .= '</td><td>';
			$content .= $memo;
			$content .= '</td><td>';
			$content .= $amount;
			$content .= '</td>';
			if(partial_access('admin')) { 
				$content .= '<td><form method="post" name="delete" onsubmit="return confirm_delete();" action="">';
				$content .= '<input type="hidden" name="delete_purchase_return_receiving" value="'.$return_receiving_id.'">';
				$content .= '<input type="submit" class="btn btn-default btn-sm" value="Delete">';
				$content .= '</form>';
				$content .= '</td>'; }
				$content .= '</tr>';
			unset($class);
		}//loop ends here.	
	echo $content;
	}//list_notes ends here.
	
	function delete_purchase_return_receiving($return_receiving_id) {
		global $db;
		
		$query = "DELETE from purchase_return_receiving WHERE return_receiving_id='".$return_receiving_id."'";
		$result = $db->query($query) or die($db->error);
		
		$query = "DELETE from vendor_log WHERE transaction_type='Purchase Return Refund' AND type_table_id='".$return_receiving_id."'";
		$result = $db->query($query) or die($db->error);
		return 'Return Receiving deleted Successfuly.';	
	}//delete_purchase return receiving.
	
	function vendors_balance_summary() { 
		global $db;
		
		$query = "SELECT * FROM vendors WHERE store_id='".$_SESSION['store_id']."' ORDER by full_name ASC";
		$result = $db->query($query) or die($db->error);
		$content = '';
		$grand_total = 0;
		
		while($row = $result->fetch_array()) { 
			extract($row);
			$total = 0;
			//getting balance.
			$balance = $this->get_vendor_balance($vendor_id);
			$grand_total += $balance;
			$total += $balance;
			$content .= '<tr><td>';
			$content .= $full_name;
			$content .= '</td><td>';
			$content .= $contact_person;
			$content .= '</td><td align="right">';
			$content .= currency_format($total);
			$content .= '</td></tr>';
		}	
			$new_store = new Store;
			$currency = $new_store->get_store_info($_SESSION['store_id'], 'currency');
			$content .= '<tr><th colspan="2" align="right">Grand Total</th><th align="right">'.$currency.' '.currency_format($grand_total).'</tH></tr>';
		echo $content;
	}//customers balance summary ends here.	
	
	function vendor_ledger_summary($vendor) {
		global $db;
		
		$query = "SELECT * from vendor_log WHERE vendor_id='".$vendor."' ORDER by vendor_log_id ASC";
		$result = $db->query($query) or die($db->error);
		$balance = 0;
		$content = '';
		$balance = 0;
		while($row = $result->fetch_array()) {
			extract($row);
			
			$datetime = strtotime($datetime);
			$date = date('d-M-Y', $datetime);
			
			$content .= '<tr><td>';
			$content .= $transaction_type;
			$content .= '</td><td>';
			$content .= $date;
			$content .= '</td><td>';
			$content .= $type_table_id;
			$content .= '</td><td>';
			
			if($transaction_type == 'Purchase Invoice' || $transaction_type == 'Cash Purchase') { 
				//Invoice Details.
				$sale_query = "SELECT * from purchases WHERE purchase_id='".$type_table_id."'";
				$sale_result = $db->query($sale_query) or die($db->error);
				
				while($sale_row = $sale_result->fetch_array()) {
					$content .= $sale_row['memo'];
					$content .= '</td><td>';
				}
				
				$sale_detail_query = "SELECT * from purchase_detail WHERE purchase_id='".$type_table_id."'";
				$sale_detail_result = $db->query($sale_detail_query) or die($db->error);
				$invoice_total = 0;
				while($sale_detail_row = $sale_detail_result->fetch_array()) { 
					$credit_query = "SELECT * from debts WHERE debt_id='".$sale_detail_row['debt_id']."'";
					$credit_result = $db->query($credit_query) or die($db->error);
					
					while($credit_row = $credit_result->fetch_array()) { 
						$invoice_total += $credit_row['payable'];
					}
				}
				$balance = $invoice_total+$balance;
				
				$content .= currency_format($invoice_total);
				$content .= '</td><td>';
				$content .= currency_format($balance);
				$content .= '</td></tr>';
				
			} else if($transaction_type == 'Purchase Payment' || $transaction_type == 'Payment') { 
				//Cash receivign.
				$receiving_query = "SELECT * from payments WHERE payment_id='".$type_table_id."'";
				$receiving_result = $db->query($receiving_query) or die($db->error);
				
				while($receiving_row = $receiving_result->fetch_array()) { 
					$content .= $receiving_row['memo'];
					$content .= '</td><td>';
					
					$balance = $balance-$receiving_row['amount'];
					$content .= '('.currency_format($receiving_row['amount']).')';
					$content .= '</td><td>';
					$content .= currency_format($balance);
					$content .= '</td></tr>';
				}
			} else if($transaction_type == 'Invoice Return' || $transaction_type == 'Purchase Return') { 
				//sale return invoice.
				$sale_query = "SELECT * from purchase_returns WHERE purchase_rt_id='".$type_table_id."'";
				$sale_result = $db->query($sale_query) or die($db->error);
				
				while($sale_row = $sale_result->fetch_array()) {
					$content .= $sale_row['memo'];
					$content .= '</td><td>';
				}
				
				$sale_detail_query = "SELECT * from purchase_return_detail WHERE purchase_rt_id='".$type_table_id."'";
				$sale_detail_result = $db->query($sale_detail_query) or die($db->error);
				$invoice_total = 0;
				while($sale_detail_row = $sale_detail_result->fetch_array()) { 
					$credit_query = "SELECT * from debts WHERE debt_id='".$sale_detail_row['debt_id']."'";
					$credit_result = $db->query($credit_query) or die($db->error);
					
					while($credit_row = $credit_result->fetch_array()) { 
						$invoice_total += $credit_row['paid'];
					}
				}
				$balance = $balance-$invoice_total;
				
				$content .= '('.currency_format($invoice_total).')';
				$content .= '</td><td>';
				$content .= currency_format($balance);
				$content .= '</td></tr>';
				
				
			} else if($transaction_type == 'Purchase Return Refund') { 
				//sale Return Payment.
				$receiving_query = "SELECT * from purchase_return_receiving WHERE return_receiving_id='".$type_table_id."'";
				$receiving_result = $db->query($receiving_query) or die($db->error);
				while($receiving_row = $receiving_result->fetch_array()) { 
					$content .= $receiving_row['memo'];
					$content .= '</td><td>';
					
					$balance = $balance+$receiving_row['amount'];
					$content .= currency_format($receiving_row['amount']);
					$content .= '</td><td>';
					$content .= currency_format($balance);
					$content .= '</td></tr>';
				}
			}
			
		}//main loop ends here.
		echo $content;
	}//customer ledger summary ends here.
	
}//class ends here.