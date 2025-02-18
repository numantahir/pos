<?php
//Purchase Class

class SaleReturn { 

	function get_sale_return_info($sale_rt_id, $term) { 
		global $db;
		$query = "SELECT * from sale_returns WHERE sale_rt_id='".$sale_rt_id."'";
		$result = $db->query($query) or die($db->error);
		$row = $result->fetch_array();
		return $row[$term];
	}//get user email ends here.
	
	//add purchase functions starts here.
	function add_sale_return($datetime, $sale_inv_no, $memo, $client_id, $payment_method) { 
		global $db;
		
		$query = "INSERT into sale_returns(sale_rt_id, datetime, invoice_no, memo, client_id, payment_status, store_id, agent_id) VALUES(NULL, '".$datetime."', '".$sale_inv_no."', '".$memo."', '".$client_id."', '".$payment_method."', '".$_SESSION['store_id']."', '".$_SESSION['user_id']."')";
		$result = $db->query($query) or die($db->error);
		return $db->insert_id;
	}//add_purchase ends here. returns purchase id.

	//add purchase functions ends here.
	function add_debt($payable, $paid, $vendor_id) { 
		global $db;
		
		$query = "INSERT into debts(debt_id, payable, paid, vendor_id, store_id) VALUES(NULL, '".$payable."', '".$paid."', '".$vendor_id."', '".$_SESSION['store_id']."')";
		$result = $db->query($query) or die($db->error);
		return $db->insert_id;
	}//add_debt ends here.
	
	function add_inventory($inn, $out_inv, $product_id, $warehouse_id) {
		global $db;
		$query = "INSERT into inventory(inventory_id, inn, out_inv, store_id, product_id, warehouse_id) VALUES(NULL, '".$inn."', '".$out_inv."', '".$_SESSION['store_id']."', '".$product_id."', '".$warehouse_id."')";
		$result = $db->query($query) or die($db->error);
		return $db->insert_id;	
	}//add inventory function ends here.
	
	function add_price($cost, $selling_price, $product_id) {
		global $db;	
		$query = "INSERT into price(price_id, cost, selling_price, store_id, product_id) VALUES(NULL, '".$cost."', '".$selling_price."', '".$_SESSION['store_id']."', '".$product_id."')";
		$result = $db->query($query) or die($db->error);
		return $db->insert_id;
	}//add price table ends here.
	
	function list_all_sale_returns() { 
		global $db;
		
		$query = "SELECT * from sale_returns WHERE store_id='".$_SESSION['store_id']."' ORDER by sale_rt_id DESC";
		$result = $db->query($query) or die($db->error);
		
		$content = '';
		while($row = $result->fetch_array()) {
			extract($row);
			
			$users = new Users;
			$agent_name = $users->get_user_info($agent_id, 'first_name').' '.$users->get_user_info($agent_id, 'last_name');
			
			$client = new Client;
			$client_name = $client->get_client_info($client_id, 'full_name');
			
			$purchase_detail = "SELECT * from sale_return_detail WHERE sale_rt_id='".$sale_rt_id."'";
			$purchase_detail_result = $db->query($purchase_detail) or die($db->error);
			
			$receiveable = 0;
			$received = 0;
			$items = 0;
			
			while($purchase_detail_row = $purchase_detail_result->fetch_array()) {
				$inventory_id = $purchase_detail_row['inventory_id'];
				$debt_id = $purchase_detail_row['credit_id'];
				
				//Inventory q?uery.
				$inventory_query = "SELECT * from inventory WHERE inventory_id='".$inventory_id."'";
				$inventory_result = $db->query($inventory_query) or die($db->error);
				$inventory_row = $inventory_result->fetch_array();
				
				$items += $inventory_row['inn'];
				
				//Inventory q?uery.
				$debt_query = "SELECT * from creditors WHERE credit_id='".$debt_id."'";
				$debt_result = $db->query($debt_query) or die($db->error);
				$debt_row = $debt_result->fetch_array();
				
				$receiveable += $debt_row['receiveable'];
				$received += $debt_row['received'];
					
			}//purchase detail loop.
			
			$content .= '<tr><td>';
			$content .= $sale_rt_id;
			$content .= '</td><td>';
			$datetime = strtotime($datetime);
			$content .= date('d-m-Y', $datetime);
			$content .= '</td><td>';
			$content .= $agent_name;
			$content .= '</td><td>';
			$content .= $client_name;
			$content .= '</td><td>';
			$content .= $invoice_no;
			$content .= '</td><td>';
			$content .= $memo;
			$content .= '</td><td>';
			$content .= $payment_status;
			$content .= '</td><td>';
			$content .= $items;
			$content .= '</td><td>';
			$content .= number_format($received);
			$content .= '</td><td>';
			$content .= number_format($receiveable);
			$content .= '</td><td>';
			$content .= '<a href="reports/view_sale_return_invoice.php?sale_rt_id='.$sale_rt_id.'" target="_blank">View</a>';
			$content .= '</td>';
				if(partial_access('admin')) { 
				$content .= '<td>';
				$content .= '<form method="post" name="delete" onsubmit="return confirm_delete();" action="">';
				$content .= '<input type="hidden" name="delete_sale_return" value="'.$sale_rt_id.'">';
				$content .= '<input type="submit" class="btn btn-default btn-sm" value="Delete">';
				$content .= '</form>';
				$content .= '</td>'; }
				$content .= '</tr>';	
		}//main_while loop
		echo $content;
	}//list_all purchases function ends here.
		
	function add_return_detail($sale_rt_id, $price_id, $inventory_id, $creditor_id, $reason_id) {
		global $db;	
		$query = "INSERT into sale_return_detail(return_detail_id, sale_rt_id, store_id, price_id, inventory_id, credit_id, reason_id) VALUES(NULL, '".$sale_rt_id."', '".$_SESSION['store_id']."', '".$price_id."', '".$inventory_id."', '".$creditor_id."', '".$reason_id."')";
		$result = $db->query($query) or die($db->error);
		return $db->insert_id;
	}//add purchase detail function ends here.	
	
	function view_sale_return_invoice($sale_rt_id) {
		global $db;
		
		/*Products Detail.*/
		$sale_detail_query = "SELECT * from sale_return_detail WHERE sale_rt_id='".$sale_rt_id."' AND store_id='".$_SESSION['store_id']."'";
		$sale_detail_result = $db->query($sale_detail_query) or die($db->error);
	
		$grandTotal = 0;
		$received = 0;
		$rows = '';
	
		while($sale_detail_row = $sale_detail_result->fetch_array()) { 
			$price_id = $sale_detail_row['price_id'];
			$inventory_id = $sale_detail_row['inventory_id'];
			$credit_id = $sale_detail_row['credit_id'];
			$reason_id = $sale_detail_row['reason_id'];
			
			$inventoryQuery = "SELECT * from inventory WHERE inventory_id='".$inventory_id."'";
			$inventoryResult = $db->query($inventoryQuery) or die($db->error);
			$inventoryRow = $inventoryResult->fetch_array();
			$qty = $inventoryRow['inn'];
			$product_id = $inventoryRow['product_id'];
			
			$pductQuery = "SELECT * from products WHERE product_id='".$product_id."'";
			$productResult = $db->query($pductQuery) or die($db->error);
			$productRow = $productResult->fetch_array();
			
			$pId = $productRow['product_manual_id'];
			$pName = $productRow['product_name'];
		
			$priceQuery = "SELECT * from price WHERE price_id='".$price_id."'";
			$priceResult = $db->query($priceQuery) or die($db->error);
			$priceRow = $priceResult->fetch_array();
		
			$creditQuery = "SELECT * from creditors WHERE credit_id='".$credit_id."'";
			$creditResult = $db->query($creditQuery) or die($db->error);
			$creditRow = $creditResult->fetch_array();
		
			$price = $priceRow['selling_price'];
			$tax = $priceRow['tax'];
			$grandTotal += ($price*$qty)+($tax*$qty);
			$received += $creditRow['receiveable'];
				
			$rows .= "<tr><td>";
			$rows .= $pId;
			$rows .= "</td><td>";
			$rows .= $pName;
			$rows .= "</td><td>";
			$rows .= $price;
			$rows .= "</td><td>";
			$rows .= $qty;
			$rows .= "</td><td>";
			$rows .= $tax;
			$rows .= "</td><td>";
			$rows .= (($qty*$price)+($tax*$qty));
			$rows .= "</td></tr>";
		}
		$reason_obj = new Returnreason;
		$reason_title = $reason_obj->get_reason_info($reason_id, 'title');
		
		$rows .= "<tr><td colspan='6'><strong>Reason: </strong>".$reason_title."</td></tr>";
		$return_message = array(
			"rows" => $rows,
			"grand_total" => $grandTotal,
			"received_amount" => $received
		);
		return $return_message;
	}//view purchase invoice ends here.

	function delete_sale_return($sale_rt_id) {
		global $db;
		
		$query = "DELETE FROM sale_returns WHERE sale_rt_id='".$sale_rt_id."'";
		$result = $db->query($query) or die($db->error);
		
		$query = "SELECT * from sale_return_detail WHERE sale_rt_id='".$sale_rt_id."'";
		$result_detail = $db->query($query) or die($db->error);	
		
		while($row = $result_detail->fetch_array()) { 
			extract($row);
			
			$delete[] = "DELETE FROM price WHERE price_id='".$price_id."'";
			$delete[] = "DELETE FROM inventory WHERE inventory_id='".$inventory_id."'";
			$delete[] = "DELETE FROM creditors WHERE credit_id='".$credit_id."'";
			
			foreach($delete as $query) { 
				$result = $db->query($query) or die($db->error);
			}
		}//main loop ends here.
		$delete = "DELETE FROM sale_return_detail WHERE sale_rt_id='".$sale_rt_id."'";
		$result = $db->query($delete) or die($db->error);
		
		$delete = "DELETE FROM customer_log WHERE transaction_type='Sale Return' AND type_table_id='".$sale_rt_id."'";
		$result = $db->query($delete) or die($db->error);
		
		$delete = "DELETE FROM customer_log WHERE transaction_type='Invoice Return' AND type_table_id='".$sale_rt_id."'";
		$result = $db->query($delete) or die($db->error);
		
		return "Sale return was deleted successfuly.";
	}//delete_sale ends here.
	
}//Purchase Class Ends here.