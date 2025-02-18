<?php
//Purchase Class

class Sale { 

	function get_sale_info($sale_id, $term) { 
		global $db;
		$query = "SELECT * from sales WHERE sale_id='".$sale_id."'";
		$result = $db->query($query) or die($db->error);
		$row = $result->fetch_array();
		return $row[$term];
	}//get user email ends here.
	
	//add purchase functions starts here.
	function add_sale($datetime, $custom_inv_no, $memo, $client_id, $payment_method, $table_id) { 
		global $db;
		
		$GetLastSaleID = "SELECT invoice_number from sales ORDER BY sale_id DESC LIMIT 0,1";
		$GetInvoiceNumberLast = $db->query($GetLastSaleID) or die($db->error);
		$LastInvoiceNumber = $GetInvoiceNumberLast->fetch_array();
		$NewSaleInvoiceNumber = (int)trim($LastInvoiceNumber["invoice_number"]) + 1;



		/* $query = "INSERT into sales(sale_id, datetime, manual_inv_no, memo, client_id, payment_status, store_id, agent_id) VALUES(NULL, '".$datetime."', '".$custom_inv_no."', '".$memo."', '".$client_id."', '".$payment_method."', '".$_SESSION['store_id']."', '".$_SESSION['user_id']."')"; */
		$query = "INSERT into sales(sale_id, datetime, manual_inv_no, memo, client_id, payment_status, store_id, agent_id, invoice_number, table_id) VALUES(NULL, '".$datetime."', '".$custom_inv_no."', '".$memo."', '".$client_id."', '".$payment_method."', '".$_SESSION['store_id']."', '".$_SESSION['user_id']."', '".$NewSaleInvoiceNumber."', '".$table_id."')";

		$result = $db->query($query) or die($db->error);
		return $db->insert_id;
	}//add_purchase ends here. returns purchase id.
	//add purchase functions ends here.
	
	function add_creditor($receiveable, $received, $client_id) { 
		global $db;
		
		$query = "INSERT into creditors(credit_id, receiveable, received, client_id, store_id) VALUES(NULL, '".$receiveable."', '".$received."', '".$client_id."', '".$_SESSION['store_id']."')";
		$result = $db->query($query) or die($db->error);
		return $db->insert_id;
	}//add_credit ends here.
	
	function add_inventory($inn, $out_inv, $product_id, $warehouse_id) {
		global $db;
		$query = "INSERT into inventory(inventory_id, inn, out_inv, store_id, product_id, warehouse_id) VALUES(NULL, '".$inn."', '".$out_inv."', '".$_SESSION['store_id']."', '".$product_id."', '".$warehouse_id."')";
		$result = $db->query($query) or die($db->error);
		return $db->insert_id;	
	}//add inventory function ends here.
	
	function add_price($cost, $selling_price, $tax, $product_id) {
		global $db;	
		$query = "INSERT into price(price_id, cost, selling_price, tax, store_id, product_id) VALUES(NULL, '".$cost."', '".$selling_price."', '".$tax."', '".$_SESSION['store_id']."', '".$product_id."')";
		$result = $db->query($query) or die($db->error);
		return $db->insert_id;
	}//add price table ends here.
	
	function add_sale_detail($sale_id, $price_id, $inventory_id, $creditor_id) {
		global $db;	
		$query = "INSERT into sale_detail(sale_detail_id, sale_id, store_id, price_id, inventory_id, credit_id) VALUES(NULL, '".$sale_id."', '".$_SESSION['store_id']."', '".$price_id."', '".$inventory_id."', '".$creditor_id."')";
		$result = $db->query($query) or die($db->error);
		return $db->insert_id;
	}//add purchase detail function ends here.	
	
	function view_sale_invoice($sale_id, $type) {
		global $db;
		
		/*Products Detail.*/
		$sale_detail_query = "SELECT * from sale_detail WHERE sale_id='".$sale_id."' AND store_id='".$_SESSION['store_id']."'";
		$sale_detail_result = $db->query($sale_detail_query) or die($db->error);
	
		$grandTotal = 0;
		$received = 0;
		$rows = '';
		$counter = 1;
		$tax = 0;
		while($sale_detail_row = $sale_detail_result->fetch_array()) { 
			$price_id = $sale_detail_row['price_id'];
			$inventory_id = $sale_detail_row['inventory_id'];
			$credit_id = $sale_detail_row['credit_id'];
		
			$inventoryQuery = "SELECT * from inventory WHERE inventory_id='".$inventory_id."'";
			$inventoryResult = $db->query($inventoryQuery) or die($db->error);
			$inventoryRow = $inventoryResult->fetch_array();
			$qty = $inventoryRow['out_inv'];
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
			$GrandTotalTax += $tax*$qty;
			//$grandTotal += ($price*$qty)+($tax*$qty);
			$grandTotal += ($price*$qty);
			$received += $creditRow['received'];
				
			$rows .= "<tr><td>";
			if($type == 'pos_invoice') { 
			$rows .= $counter;
			} else { 
			$rows .= $pId;
			}
			$rows .= "</td><td>";
			$rows .= $pName;
			$rows .= "</td><td class='price'>";
			$rows .= $qty;
			//$rows .= "</td><td class='tax'>";
			$rows .= "</td>";
			//$rows .= $price;
			$rows .= "</td><td class='qty'>";
			$rows .= $price;
			$rows .= "</td><td class='total'>";
			$rows .= currency_format((($qty*$price)));
			$rows .= "</td></tr>";
			$counter++;
		}
		$return_message = array(
			"rows" => $rows,
			"grand_total" => $grandTotal,
			"tax_total" => $GrandTotalTax,
			"received_amount" => $received
		);
		return $return_message;
	}//view purchase invoice ends here.
	
	function list_periodical_sales($start_date, $end_date) { 
		global $db;
		
		$from = $start_date;
		$to = $end_date;
		
		$query = "SELECT * from sales WHERE store_id='".$_SESSION['store_id']."' AND datetime between '".$from."' AND '".$to."' ORDER by sale_id DESC";
		$result = $db->query($query) or die($db->error);
		
		$items_sold	 	 = 0;
		$sales_amount 	 = 0;
		$received_amount = 0;
		$TotalTax = 0;
		
		$content = '';
		while($row = $result->fetch_array()) {
			extract($row);
			
			$users = new Users;
			$agent_name = $users->get_user_info($agent_id, 'first_name').' '.$users->get_user_info($agent_id, 'last_name');
			
			$clients = new Client;
			$client_name = $clients->get_client_info($client_id, 'full_name');
			
			$sale_detail = "SELECT * from sale_detail WHERE sale_id='".$sale_id."'";
			$sale_detail_result = $db->query($sale_detail) or die($db->error);
			
			$receiveable = 0;
			$receiveabletax = 0;
			$received = 0;
			$items = 0;
			
			
			while($sale_detail_row = $sale_detail_result->fetch_array()) {
				$inventory_id = $sale_detail_row['inventory_id'];
				$creditor_id = $sale_detail_row['credit_id'];
				$print_id = $sale_detail_row['price_id'];
				
				//Inventory q?uery.
				$inventory_query = "SELECT * from inventory WHERE inventory_id='".$inventory_id."'";
				$inventory_result = $db->query($inventory_query) or die($db->error);
				$inventory_row = $inventory_result->fetch_array();
				
				$items += $inventory_row['out_inv'];
				$NumberofQty = $inventory_row['out_inv'];
				
				$priceQuery = "SELECT * from price WHERE price_id='".$print_id."'";
				$priceResult = $db->query($priceQuery) or die($db->error);
				$priceRow = $priceResult->fetch_array();
				//$GrandTotal = $NumberofQty*$priceRow['tax'];
				//Inventory q?uery.
				$credit_query = "SELECT * from creditors WHERE credit_id='".$creditor_id."'";
				$credit_result = $db->query($credit_query) or die($db->error);
				$credit_row = $credit_result->fetch_array();
				
				$receivings_query = "SELECT * from receivings WHERE ref_no='".$sale_detail_row['sale_id']."'";
				$receivings_result = $db->query($receivings_query) or die($db->error);
				$receivings_row = $receivings_result->fetch_array();
				
				//$SalePrice += $credit_row['selling_price'];
				$receiveable += $NumberofQty*$priceRow['selling_price'];
				$receiveabletax += $NumberofQty*$priceRow['tax'];
				$received += $credit_row['received'];
				$TotalTax += $NumberofQty*$priceRow['tax'];
				$sales_amount += $NumberofQty*$priceRow['selling_price'];
					
			}//purchase detail loop.
			
			$items_sold 		= $items_sold+$items;
			//$sales_amount 		= $sales_amount+$receiveable;
			$received_amount 	= $received_amount+$received;
			
			$content .= '<tr><td style="border:1px solid #ddd">';
			$content .= $sale_id;
			$content .= '</td><td style="border:1px solid #ddd">';
			$datetime = strtotime($datetime);
			$content .= date('d-m-Y', $datetime);
			$content .= '</td><td style="border:1px solid #ddd">';
			$content .= $client_name;
			$content .= '</td><td style="border:1px solid #ddd">';
			$content .= $payment_status;
			$content .= '</td><td style="border:1px solid #ddd">';
			$content .= $items;
			$content .= '</td><td class="text-left" style="border:1px solid #ddd">';
			$content .= 'Rs. '.number_format($receiveable, 2);
			$content .= '</td><td class="text-left" style="border:1px solid #ddd">';
			$content .= 'Rs. '.number_format($receiveabletax, 2);
			$content .= '</td><td class="text-left" style="border:1px solid #ddd">';
			$content .= 'Rs. '.number_format($received, 2);
			$content .= '</td>';
			$content .= '</tr>';	
		}//main_while loop
		
		$output = array( 
			"content" 			=> $content,
			"items_qty" 		=> $items_sold,
			"sales_amount" 		=> $sales_amount,
			"received_amount"	=> $received_amount,
			"grand_total"		=> $TotalTax
		);
		
		return $output;
	}//list_all purchases function ends here.
	
	function list_periodical_sales_new($start_date, $end_date) { 
		global $db;
		
		$from = $start_date;
		$to = $end_date;
		
		$query = "SELECT * from sales WHERE store_id='".$_SESSION['store_id']."' AND datetime between '".$from."' AND '".$to."' ORDER by sale_id DESC";
		$result = $db->query($query) or die($db->error);
		
		$items_sold	 	 = 0;
		$sales_amount 	 = 0;
		$received_amount = 0;
		$TotalTax = 0;
		
		$content = '';
		while($row = $result->fetch_array()) {
			extract($row);
			
			//$users = new Users;
			//$agent_name = $users->get_user_info($agent_id, 'first_name').' '.$users->get_user_info($agent_id, 'last_name');
			
			//$clients = new Client;
			//$client_name = $clients->get_client_info($client_id, 'full_name');
			
			$sale_detail = "SELECT * from sale_detail WHERE sale_id='".$sale_id."'";
			$sale_detail_result = $db->query($sale_detail) or die($db->error);
			
			$receiveable = 0;
			$receiveabletax = 0;
			$received = 0;
			$items = 0;
			
			
			while($sale_detail_row = $sale_detail_result->fetch_array()) {
				$inventory_id = $sale_detail_row['inventory_id'];
				$creditor_id = $sale_detail_row['credit_id'];
				$print_id = $sale_detail_row['price_id'];
				
				//Inventory q?uery.
				$inventory_query = "SELECT * from inventory WHERE inventory_id='".$inventory_id."'";
				$inventory_result = $db->query($inventory_query) or die($db->error);
				$inventory_row = $inventory_result->fetch_array();
				
				$items += $inventory_row['out_inv'];
				$NumberofQty = $inventory_row['out_inv'];
				
				//$priceQuery = "SELECT * from price WHERE price_id='".$print_id."'";
				//$priceResult = $db->query($priceQuery) or die($db->error);
				//$priceRow = $priceResult->fetch_array();
				//$GrandTotal = $NumberofQty*$priceRow['tax'];
				//Inventory q?uery.
				$credit_query = "SELECT * from creditors WHERE credit_id='".$creditor_id."'";
				$credit_result = $db->query($credit_query) or die($db->error);
				$credit_row = $credit_result->fetch_array();
				
				//$receivings_query = "SELECT * from receivings WHERE ref_no='".$sale_detail_row['sale_id']."'";
				//$receivings_result = $db->query($receivings_query) or die($db->error);
				//$receivings_row = $receivings_result->fetch_array();
				
				//$SalePrice += $credit_row['selling_price'];
				//$receiveable += $NumberofQty*$priceRow['selling_price'];
				//$receiveabletax += $NumberofQty*$priceRow['tax'];
				$received += $credit_row['received'];
				$TotalTax += $NumberofQty*$priceRow['tax'];
				$sales_amount += $NumberofQty*$priceRow['selling_price'];
					
			}//purchase detail loop.
			
			$items_sold 		= $items_sold+$items;
			//$sales_amount 		= $sales_amount+$receiveable;
			$received_amount 	= $received_amount+$received;
			
			$content .= '<tr><td style="border:1px solid #ddd">';
			$content .= $invoice_number;
			$content .= '</td><td style="border:1px solid #ddd">';
			$datetime = strtotime($datetime);
			$content .= date('d-m-Y', $datetime);
			$content .= '</td><td style="border:1px solid #ddd">';
			//$content .= $client_name;
			//$content .= '</td><td style="border:1px solid #ddd">';
			$content .= $payment_status;
			$content .= '</td><td style="border:1px solid #ddd">';
			$content .= $items;
			$content .= '</td><td class="text-left" style="border:1px solid #ddd">';
			//$content .= 'Rs. '.number_format($receiveable, 2);
			//$content .= '</td><td class="text-left" style="border:1px solid #ddd">';
			//$content .= 'Rs. '.number_format($receiveabletax, 2);
			//$content .= '</td><td class="text-left" style="border:1px solid #ddd">';
			$content .= 'Rs. '.number_format($received, 2);
			$content .= '</td>';
			$content .= '</tr>';	
		}//main_while loop
		
		$output = array( 
			"content" 			=> $content,
			"items_qty" 		=> $items_sold,
			"sales_amount" 		=> $sales_amount,
			"received_amount"	=> $received_amount,
			"grand_total"		=> $TotalTax
		);
		
		return $output;
	}//list_all purchases function ends here.
	
	
	function list_periodical_sales_by_items($start_date, $end_date, $view_type, $sort_by) { 
		global $db;
		
		$from = $start_date;
		$to = $end_date;
		
		$query = "SELECT * from sales WHERE store_id='".$_SESSION['store_id']."' AND datetime between '".$from."' AND '".$to."' ORDER by sale_id DESC";
		$result = $db->query($query) or die($db->error);
		
		$items_sale_detail = array();
		
		$content = '';
		while($row = $result->fetch_array()) {
			extract($row);
			
			$sale_detail 			= "SELECT * from sale_detail WHERE sale_id='".$sale_id."'";
			$sale_detail_result 	= $db->query($sale_detail) or die($db->error);
			
			$inventory_arr			= array();
			
			while($sale_detail_row = $sale_detail_result->fetch_array()) {
				$inventory_id 		= $sale_detail_row["inventory_id"];
				$price_id 			= $sale_detail_row["price_id"];
				
				//Inventory q?uery.
				$inventory_query 	= "SELECT * from inventory WHERE inventory_id='".$inventory_id."'";
				$inventory_result 	= $db->query($inventory_query) or die($db->error);
				$inventory_row 		= $inventory_result->fetch_array();
				
				$product_id 		= $inventory_row["product_id"];
				$sold_qty			= $inventory_row["out_inv"];
				
				
				//Getting Product Info
				$product_obj		= new Product;
				$product_unique_id	= $product_obj->get_product_info($product_id, "product_manual_id");
				$product_name		= $product_obj->get_product_info($product_id, "product_name");
				$product_unit		= $product_obj->get_product_info($product_id, "product_unit");
				$product_category	= $product_obj->get_product_info($product_id, "category_id");
				
				
				//GEtting Product Category name
				$product_cate_obj	= new ProductCategory;
				$product_cat_name	= $product_cate_obj->get_category_info($product_category, "category_name");

				
				//Selling Price Query.
				$price_query 		= "SELECT * from price WHERE price_id='".$price_id."'";
				$price_result 		= $db->query($price_query) or die($db->error);
				$price_row 			= $price_result->fetch_array();
				
				$product_price		= $price_row["selling_price"];
				$product_tax		= $price_row["tax"];
				
				$inventory_arr[]	= array(
										"product_id"			=> $product_id,
										"sold_qty"				=> $sold_qty,
										"product_price"			=> $product_price,
										"product_tax"			=> $product_tax
									);
			}//purchase detail loop.
			
			
			$items_sale_detail[] = array ( 
						  			"sale_id" 			=> $sale_id,
							    	"sale_date"			=> $datetime,
									"inventory_detail"	=> $inventory_arr
						  		);
			
		}//main_while loop
		
		//Empty PRoduct Array
		$product_array = array();
		
		foreach($items_sale_detail as $item_sale) {
			
			foreach($item_sale["inventory_detail"] as $inventory_detail) {
				
				$sale_info_arr = array (
										"sale_id"			=> $item_sale["sale_id"],
										"sale_date"			=> $item_sale["sale_date"],
										"sold_qty"			=> $inventory_detail["sold_qty"],
										"product_price"		=> $inventory_detail["product_price"],
										"product_tax"		=> $inventory_detail["product_tax"],
									);
				
				$sold_unit 			= $inventory_detail["sold_qty"];
				$sold_net_amount 	= $inventory_detail["product_price"];
				$sold_amount 		= $inventory_detail["product_price"]+$inventory_detail["product_tax"]; 
				
				if(isset($product_info[$inventory_detail["product_id"]])):
					$product_info[$inventory_detail["product_id"]]["sale_info"][]		= $sale_info_arr;
					$product_info[$inventory_detail["product_id"]]["sold_units"] 		+= $sold_unit;
					$product_info[$inventory_detail["product_id"]]["sale_net_amount"] 	+= $sold_net_amount;
					$product_info[$inventory_detail["product_id"]]["sale_amount"] 		+= $sold_amount;
				else:
					$product_info[$inventory_detail["product_id"]] = array (
										"product_id"		=> $inventory_detail["product_id"],
										"sold_units"		=> $sold_unit,
										"sale_net_amount"	=> $sold_net_amount,
										"sale_amount"		=> $sold_amount,
										"sale_info"			=> array($sale_info_arr),
					);
				endif;	
			}
			
		}
		
		if(isset($product_info)):
			$product_info_new = self::array_sort($product_info, $sort_by, SORT_DESC);
		
			$output 	 = "";
			$total_units  = 0;
			$total_amount = 0;
			foreach($product_info_new as $product_detail) {
				$product_id = $product_detail["product_id"];
				
				//Getting Product Info
				$product_obj		= new Product;
				$product_unique_id	= $product_obj->get_product_info($product_id, "product_manual_id");
				$product_name		= $product_obj->get_product_info($product_id, "product_name");
				$product_unit		= $product_obj->get_product_info($product_id, "product_unit");
				$product_category	= $product_obj->get_product_info($product_id, "category_id");
				
				
				//Getting Product Category name
				$product_cate_obj	= new ProductCategory;
				$product_cat_name	= $product_cate_obj->get_category_info($product_category, "category_name");
				
				$output .= "<tr>";
				$output .= "<td>".$product_id."</td>";
				$output .= "<td>".$product_unique_id."</td>";
				$output .= "<td>".$product_name."</td>";
				$output .= "<td>".$product_cat_name."</td>";
				$output .= "<td>".$product_unit."</td>";
				
				$output .= "<td class='text-right'>".$product_detail["sold_units"]."</td>";
				$output .= "<td class='text-right'>".number_format($product_detail["sale_net_amount"], 2)."</td>";
				$output .= "<td class='text-right'>".number_format($product_detail["sale_amount"], 2)."</td>";
				$output .= "</tr>";
				
				$total_units 	= $total_units+$product_detail["sold_units"];
				$total_amount 	= $total_amount+$product_detail["sale_amount"];
			}
		
		endif;
		
		if(isset($output)):
		$total_amount = number_format($total_amount, 2);
		
		$output_arr = array(
						"content"		=> $output,
						"total_units"	=> $total_units,
						"total_amount"	=> $total_amount
						);
		endif;
		
		if(isset($output_arr)):
			return $output_arr;
		else:
			return '';
		endif;
	}//list_all purchases function ends here.
	
	function array_sort($array, $on, $order=SORT_ASC){
		$new_array = array();
		$sortable_array = array();

		if (count($array) > 0) {
			foreach ($array as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $k2 => $v2) {
						if ($k2 == $on) {
							$sortable_array[$k] = $v2;
						}
					}
				} else {
					$sortable_array[$k] = $v;
				}
			}

			switch ($order) {
				case SORT_ASC:
					asort($sortable_array);
				break;
				case SORT_DESC:
					arsort($sortable_array);
				break;
			}

			foreach ($sortable_array as $k => $v) {
				$new_array[$k] = $array[$k];
			}
		}

		return $new_array;
	}
	
	function list_all_sales() { 
		global $db;
		
		$query = "SELECT * from sales WHERE store_id='".$_SESSION['store_id']."' ORDER by sale_id DESC LIMIT 0,100";
		$result = $db->query($query) or die($db->error);
		
		$content = '';
		while($row = $result->fetch_array()) {
			extract($row);
			
			$users = new Users;
			$agent_name = $users->get_user_info($agent_id, 'first_name').' '.$users->get_user_info($agent_id, 'last_name');
			
			$clients = new Client;
			$client_name = $clients->get_client_info($client_id, 'full_name');
			
			$sale_detail = "SELECT * from sale_detail WHERE sale_id='".$sale_id."'";
			$sale_detail_result = $db->query($sale_detail) or die($db->error);
			
			$receiveable = 0;
			$received = 0;
			$items = 0;
			
			while($sale_detail_row = $sale_detail_result->fetch_array()) {
				$inventory_id = $sale_detail_row['inventory_id'];
				$creditor_id = $sale_detail_row['credit_id'];
				
				//Inventory q?uery.
				$inventory_query = "SELECT * from inventory WHERE inventory_id='".$inventory_id."'";
				$inventory_result = $db->query($inventory_query) or die($db->error);
				$inventory_row = $inventory_result->fetch_array();
				
				$items += $inventory_row['out_inv'];
				
				//Inventory q?uery.
				$credit_query = "SELECT * from creditors WHERE credit_id='".$creditor_id."'";
				$credit_result = $db->query($credit_query) or die($db->error);
				$credit_row = $credit_result->fetch_array();
				
				$receiveable += $credit_row['receiveable'];
				$received += $credit_row['received'];
					
			}//purchase detail loop.
			
			$content .= '<tr><td>';
			$content .= $sale_id;
			$content .= '</td><td>';
			$datetime = strtotime($datetime);
			$content .= date('d-m-Y', $datetime);
			$content .= '</td><td>';
			$content .= $agent_name;
			$content .= '</td><td>';
			$content .= $client_name;
			$content .= '</td><td>';
			$content .= $manual_inv_no;
			$content .= '</td><td>';
			$content .= $memo;
			$content .= '</td><td>';
			$content .= $payment_status;
			$content .= '</td><td>';
			$content .= $items;
			$content .= '</td><td>';
			$content .= ($receiveable);
			$content .= '</td><td>';
			$content .= ($received);
			$content .= '</td><td>';
			$content .= '<a href="reports/view_sale_invoice.php?sale_id='.$sale_id.'" target="_blank">View</a><br><a href="reports/view_pos_sale_invoice.php?sale_id='.$sale_id.'" target="_blank" style="color:orange;">POS View</a>';
			$content .= '</td>';
				if(partial_access('admin')) { 
				$content .= '<td><form method="post" name="delete" onsubmit="return confirm_delete();" action="">';
				$content .= '<input type="hidden" name="delete_sale" value="'.$sale_id.'">';
				$content .= '<input type="submit" class="btn btn-default btn-sm" value="Delete">';
				$content .= '</form>';
				$content .= '</td>'; }
				$content .= '</tr>';	
		}//main_while loop
		echo $content;
	}//list_all purchases function ends here.
	
	function list_all_sales_clear() { 
		global $db;
		
		//$query = "SELECT * from sales WHERE store_id='".$_SESSION['store_id']."' AND payment_status!='credit' AND (datetime BETWEEN '".date("Y-m-" . 1)."' AND '".date("Y-m-" . 31)."') ORDER by sale_id DESC";
		$query = "SELECT * from sales WHERE store_id='".$_SESSION['store_id']."' AND payment_status!='credit' AND datetime='".date("Y-m-d")."' ORDER by sale_id DESC";
		$result = $db->query($query) or die($db->error);
		
		$content = '';
		while($row = $result->fetch_array()) {
			extract($row);
			
			$users = new Users;
			$agent_name = $users->get_user_info($agent_id, 'first_name').' '.$users->get_user_info($agent_id, 'last_name');
			
			$clients = new Client;
			$client_name = $clients->get_client_info($client_id, 'full_name');
			
			$sale_detail = "SELECT * from sale_detail WHERE sale_id='".$sale_id."'";
			$sale_detail_result = $db->query($sale_detail) or die($db->error);
			
			$receiveable = 0;
			$received = 0;
			$items = 0;
			
			while($sale_detail_row = $sale_detail_result->fetch_array()) {
				$inventory_id = $sale_detail_row['inventory_id'];
				$creditor_id = $sale_detail_row['credit_id'];
				
				//Inventory q?uery.
				$inventory_query = "SELECT * from inventory WHERE inventory_id='".$inventory_id."'";
				$inventory_result = $db->query($inventory_query) or die($db->error);
				$inventory_row = $inventory_result->fetch_array();
				
				$items += $inventory_row['out_inv'];
				
				//Inventory q?uery.
				$credit_query = "SELECT * from creditors WHERE credit_id='".$creditor_id."'";
				$credit_result = $db->query($credit_query) or die($db->error);
				$credit_row = $credit_result->fetch_array();
				
				$receiveable += $credit_row['receiveable'];
				$received += $credit_row['received'];
					
			}//purchase detail loop.
			
			if($payment_status == 'credit_card'){
				$RetrunPaymentStatus = 'Credit Card';
			} else {
				$RetrunPaymentStatus = $payment_status;
			}
			$content .= '<tr><td>';
			$content .= $invoice_number;
			$content .= '</td><td>';
			$datetime = strtotime($datetime);
			$content .= date('d-m-Y', $datetime);
			$content .= '</td><td>';
			$content .= $agent_name;
			$content .= '</td><td>';
			$content .= $client_name;
			$content .= '</td><td>';
			$content .= $manual_inv_no;
			$content .= '</td><td>';
			$content .= $memo;
			$content .= '</td><td>';
			$content .= $RetrunPaymentStatus;
			$content .= '</td><td>';
			$content .= $items;
			$content .= '</td><td>';
			$content .= ($receiveable);
			$content .= '</td><td>';
			$content .= ($received);
			$content .= '</td><td>';
			$content .= '<a href="reports/view_pos_sale_invoice.php?sale_id='.$sale_id.'" target="_blank" style="color:orange;">POS View</a>
			<br>
			<a href="#" onclick="openpospage_sale('.$sale_id.');" class="btn btn-default btn-primary" style="color:#FFF;">Edit Bill</a>';
			$content .= '</td>';
				if(partial_access('admin')) { 
				$content .= '<td><form method="post" name="delete" onsubmit="return confirm_delete();" action="">';
				$content .= '<input type="hidden" name="delete_sale" value="'.$sale_id.'">';
				$content .= '<input type="submit" class="btn btn-default btn-sm" value="Delete">';
				$content .= '</form>';
				$content .= '</td>'; }
				$content .= '</tr>';	
		}//main_while loop
		echo $content;
	}//list_all purchases function ends here.
	
	function list_all_sales_credit() { 
		global $db;
		
		$query = "SELECT * from sales WHERE store_id='".$_SESSION['store_id']."' AND payment_status='credit' AND (datetime BETWEEN '".date("Y-m-" . 1)."' AND '".date("Y-m-" . 31)."') ORDER by sale_id DESC";
		$result = $db->query($query) or die($db->error);
		
		$content = '';
		while($row = $result->fetch_array()) {
			extract($row);
			
			$users = new Users;
			$agent_name = $users->get_user_info($agent_id, 'first_name').' '.$users->get_user_info($agent_id, 'last_name');
			
			$clients = new Client;
			$client_name = $clients->get_client_info($client_id, 'full_name');
			
			$sale_detail = "SELECT * from sale_detail WHERE sale_id='".$sale_id."'";
			$sale_detail_result = $db->query($sale_detail) or die($db->error);
			
			//GetTableInfo
			if($table_id != ''){
			$table_name = $clients->GetTableInfo($table_id);
			} else {
			$table_name = '';	
			}
			$receiveable = 0;
			$received = 0;
			$items = 0;
			
			while($sale_detail_row = $sale_detail_result->fetch_array()) {
				$inventory_id = $sale_detail_row['inventory_id'];
				$creditor_id = $sale_detail_row['credit_id'];
				
				//Inventory q?uery.
				$inventory_query = "SELECT * from inventory WHERE inventory_id='".$inventory_id."'";
				$inventory_result = $db->query($inventory_query) or die($db->error);
				$inventory_row = $inventory_result->fetch_array();
				
				$items += $inventory_row['out_inv'];
				
				//Inventory q?uery.
				$credit_query = "SELECT * from creditors WHERE credit_id='".$creditor_id."'";
				$credit_result = $db->query($credit_query) or die($db->error);
				$credit_row = $credit_result->fetch_array();
				
				$receiveable += $credit_row['receiveable'];
				$received += $credit_row['received'];
					
			}//purchase detail loop.
			
			$content .= '<tr><td>';
			$content .= $invoice_number;
			$content .= '</td><td>';
			$datetime = strtotime($datetime);
			$content .= date('d-m-Y', $datetime);
			$content .= '</td><td>';
			$content .= $agent_name;
			$content .= '</td><td>';
			$content .= $client_name;
			$content .= '</td><td>';
			$content .= $table_name;
			$content .= '</td><td>';
			//$content .= $memo;
			//$content .= '</td><td>';
			$content .= $payment_status;
			$content .= '</td><td>';
			$content .= $items;
			$content .= '</td><td>';
			$content .= ($receiveable);
			$content .= '</td><td>';
			$content .= ($received);
			$content .= '</td><td>';
			$content .= '<a href="#" onclick="openpospage_sale('.$sale_id.');" class="btn btn-default btn-primary" style="color:#FFF;">Bill New</a>';
			$content .= '</td>';
				$content .= '</tr>';	
		}//main_while loop
		echo $content;
	}//list_all purchases function ends here.
	
	function list_of_POS_Sale_Edit($sale_id) { 
		global $db;
		
		$query = "SELECT * from sales WHERE sale_id='".$sale_id."'";
		$result = $db->query($query) or die($db->error);
		
		$content = '';
		$row = $result->fetch_array();
		//while($row = $result->fetch_array()) {
			extract($row);

			$sale_detail = "SELECT * from sale_detail WHERE sale_id='".$sale_id."'";
			$sale_detail_result = $db->query($sale_detail) or die($db->error);
			
			$receiveable = 0;
			$received = 0;
			$items = 0;
			
			while($sale_detail_row = $sale_detail_result->fetch_array()) {
				
				//Inventory q?uery.
				$inventory_query = "SELECT * from inventory WHERE inventory_id='".$sale_detail_row['inventory_id']."'";
				$inventory_result = $db->query($inventory_query) or die($db->error);
				$inventory_row = $inventory_result->fetch_array();
				
				//$items += $inventory_row['out_inv'];
				//$receiveable += $credit_row['receiveable'];
				//$received += $credit_row['received'];
				
				//Product Query.
				$product_query = "SELECT * from products WHERE product_id='".$inventory_row["product_id"]."'";
				$product_result = $db->query($product_query) or die($db->error);
				$product_row = $product_result->fetch_array();
				
				//Product Price Query.
				$product_price_query = "SELECT * from price WHERE price_id='".$sale_detail_row["price_id"]."'";
				$product_price_result = $db->query($product_price_query) or die($db->error);
				$product_price_row = $product_price_result->fetch_array();
				$TotalAmountThisItem = $product_price_row["selling_price"] * $inventory_row["out_inv"];
				$content .= '<tr class="item-row"><td><div class="delete-wpr"><input type="hidden" name="product_id[]" value="'.$product_row["product_id"].'"><a class="delme" href="javascript:;" title="Remove row">X</a></div></td><td>'.$product_row["product_name"].'</td><td><input type="text" class="qty" name="qty[]" value="'.$inventory_row["out_inv"].'"></td><td><input type="text" readonly="readonly" class="tax_rate" name="tax_rate[]" value="'.$product_price_row["tax"].'"></td><td><input type="text" onchange="update_total();" class="selling_price" name="selling_price[]" value="'.$product_price_row["selling_price"].'"></td><td><input type="text" class="itemtotal" readonly="readonly" value="'.$TotalAmountThisItem.'"><input type="hidden" name="warehouse_id[]" value="1"></td></tr>';
				
				
					
			}//purchase detail loop.
			
		//}//main_while loop
		echo $content;
	}//list_all purchases function ends here.
	
	function update_payment_methord($payment_methord, $sale_id) {
		global $db;	
		$query = "UPDATE sales SET payment_status='".$payment_methord."' WHERE sale_id='".$sale_id."'";
		$result = $db->query($query) or die($db->error);
		return 'Done';
	}//add price table ends here
	
	function sale_graph_data() { 
		global $db;
		$query = "SELECT * FROM sales WHERE datetime > DATE_SUB(NOW(), INTERVAL 7 DAY) AND store_id='".$_SESSION['store_id']."'";
		$result = $db->query($query) or die($db->error);

		$date_set = '';
		$daily_sale = 0;
		$today = '';
		while($row = $result->fetch_array()) {
			extract($row);
			$sale_detail = "SELECT * from sale_detail WHERE sale_id='".$sale_id."'";
			$sale_detail_result = $db->query($sale_detail) or die($db->error);
			$receiveable = 0;
			while($sale_detail_row = $sale_detail_result->fetch_array()) {
				$creditor_id = $sale_detail_row['credit_id'];
				//Inventory q?uery.
				$credit_query = "SELECT * from creditors WHERE credit_id='".$creditor_id."'";
				$credit_result = $db->query($credit_query) or die($db->error);
				$credit_row = $credit_result->fetch_array();
				
				$receiveable += $credit_row['receiveable'];
			}//purchase detail loop.
			
			$datetime = strtotime($datetime);
			$date_pr = date('Y-m-d', $datetime);
			$daily_sale = $receiveable; 
			$today = $date_pr;
			$content[] = Array(
				"date" => $today,
				"total" => $daily_sale
			);
		}//main_while loop
		
		$new_arr = array();
		array_walk($content,function ($v,$k) use(&$new_arr) {
    		array_key_exists($v['date'],$new_arr) ? $new_arr[$v['date']] = $new_arr[$v['date']]+$v['total'] : $new_arr[$v['date']]=$v['total'];
});
	$js_arr = '';
	foreach($new_arr as $key => $value) { 
		if($js_arr != '') { 
			$js_arr .= ', ';
		}
		$js_arr .= '["'.$key.'", '.$value.']';
	}
	echo $js_arr;
}//list_all purchases function ends here.
	
	function delete_this_sale($sale_id) {
		global $db;
		
		$query = "SELECT * from sale_detail WHERE sale_id='".$sale_id."'";
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
		$delete = "DELETE FROM sale_detail WHERE sale_id='".$sale_id."'";
		$result = $db->query($delete) or die($db->error);
		
		$delete = "DELETE FROM customer_log WHERE transaction_type='Sale Invoice' AND type_table_id='".$sale_id."'";
		$result = $db->query($delete) or die($db->error);
		
		$delete = "DELETE FROM customer_log WHERE transaction_type='Cash Sale' AND type_table_id='".$sale_id."'";
		$result = $db->query($delete) or die($db->error);
		
		return "Done";
	}//delete_sale ends here.	
	
	function delete_sale($sale_id) {
		global $db;
		
		$query = "DELETE FROM sales WHERE sale_id='".$sale_id."'";
		$result = $db->query($query) or die($db->error);
		
		$query = "SELECT * from sale_detail WHERE sale_id='".$sale_id."'";
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
		$delete = "DELETE FROM sale_detail WHERE sale_id='".$sale_id."'";
		$result = $db->query($delete) or die($db->error);
		
		$delete = "DELETE FROM customer_log WHERE transaction_type='Sale Invoice' AND type_table_id='".$sale_id."'";
		$result = $db->query($delete) or die($db->error);
		
		$delete = "DELETE FROM customer_log WHERE transaction_type='Cash Sale' AND type_table_id='".$sale_id."'";
		$result = $db->query($delete) or die($db->error);
		
		return "Sale was deleted successfuly.";
	}//delete_sale ends here.
	


function filter_sales() { 
		global $db;
		$TotalSumAmount = 0;
		$query = "SELECT * from sales WHERE store_id='".$_SESSION['store_id']."' AND (datetime BETWEEN '2019-02-01' AND '2019-02-31') ORDER by sale_id DESC";
		//$query = "SELECT * from sales WHERE store_id='".$_SESSION['store_id']."' AND payment_status!='credit' AND datetime='".date("Y-m-d")."' ORDER by sale_id DESC";
		$result = $db->query($query) or die($db->error);
		
		$content = '';
		while($row = $result->fetch_array()) {
			extract($row);
			
			$client_name = 'Default';
			
			$sale_detail = "SELECT * from sale_detail WHERE sale_id='".$sale_id."'";
			$sale_detail_result = $db->query($sale_detail) or die($db->error);
			
			$receiveable = 0;
			$received = 0;
			$items = 0;
			
			while($sale_detail_row = $sale_detail_result->fetch_array()) {
				$inventory_id = $sale_detail_row['inventory_id'];
				$creditor_id = $sale_detail_row['credit_id'];
				
				//Inventory q?uery.
				$inventory_query = "SELECT * from inventory WHERE inventory_id='".$inventory_id."'";
				$inventory_result = $db->query($inventory_query) or die($db->error);
				$inventory_row = $inventory_result->fetch_array();
				
				$items += $inventory_row['out_inv'];
				
				//Inventory q?uery.
				$credit_query = "SELECT * from creditors WHERE credit_id='".$creditor_id."'";
				$credit_result = $db->query($credit_query) or die($db->error);
				$credit_row = $credit_result->fetch_array();
				
				$receiveable += $credit_row['receiveable'];
				$received += $credit_row['received'];
					
			}//purchase detail loop.
			$GrandTotalTax = 1;
			
			if($row["sale_id"] == 11000){
			$TotalSumAmount += $receiveable;			
			$content .= '<tr><td>';
			$content .= $invoice_number;
			$content .= '</td><td>';
			$datetime = strtotime($datetime);
			$content .= date('d-m-Y', $datetime);
			$content .= '</td><td>';
			$content .= $agent_name;
			$content .= '</td><td>';
			$content .= $client_name;
			$content .= '</td><td>';
			$content .= $manual_inv_no;
			$content .= '</td><td>';
			$content .= $memo;
			$content .= '</td><td>';
			$content .= $TotalSumAmount;
			$content .= '</td><td>';
			$content .= $items;
			$content .= '</td><td>';
			$content .= ($receiveable);
			$content .= '</td><td>';
			$content .= ($received);
			$content .= '</td><td>';
			$content .= '<a href="reports/view_sale_invoice.php?sale_id='.$sale_id.'" target="_blank">View</a><br><a href="reports/view_pos_sale_invoice.php?sale_id='.$sale_id.'" target="_blank" style="color:orange;">POS View</a>';
			$content .= '</td>';
				if(partial_access('admin')) { 
				$content .= '<td><a href="updatequery.php?si='.$sale_id.'" target="_blank">Update</a>';
				$content .= '</td>'; }
				$content .= '</tr>';	
			}
			if($GrandTotalTax > 0 && $received > 750 && $received < 900){
			$TotalSumAmount += $receiveable;			
			$content .= '<tr><td>';
			$content .= $invoice_number;
			$content .= '</td><td>';
			$datetime = strtotime($datetime);
			$content .= date('d-m-Y', $datetime);
			$content .= '</td><td>';
			$content .= $agent_name;
			$content .= '</td><td>';
			$content .= $client_name;
			$content .= '</td><td>';
			$content .= $manual_inv_no;
			$content .= '</td><td>';
			$content .= $memo;
			$content .= '</td><td>';
			$content .= $TotalSumAmount;
			$content .= '</td><td>';
			$content .= $items;
			$content .= '</td><td>';
			$content .= ($receiveable);
			$content .= '</td><td>';
			$content .= ($received);
			$content .= '</td><td>';
			$content .= '<a href="reports/view_sale_invoice.php?sale_id='.$sale_id.'" target="_blank">View</a><br><a href="reports/view_pos_sale_invoice.php?sale_id='.$sale_id.'" target="_blank" style="color:orange;">POS View</a>';
			$content .= '</td>';
				if(partial_access('admin')) { 
				$content .= '<td><a href="updatequery.php?si='.$sale_id.'" target="_blank">Update</a>';
				$content .= '</td>'; }
				$content .= '</tr>';	
			}
			if($GrandTotalTax > 0 && $received > 900 && $received < 1000){
			$TotalSumAmount += $receiveable;			
			$content .= '<tr><td>';
			$content .= $invoice_number;
			$content .= '</td><td>';
			$datetime = strtotime($datetime);
			$content .= date('d-m-Y', $datetime);
			$content .= '</td><td>';
			$content .= $agent_name;
			$content .= '</td><td>';
			$content .= $client_name;
			$content .= '</td><td>';
			$content .= $manual_inv_no;
			$content .= '</td><td>';
			$content .= $memo;
			$content .= '</td><td>';
			$content .= $TotalSumAmount;
			$content .= '</td><td>';
			$content .= $items;
			$content .= '</td><td>';
			$content .= ($receiveable);
			$content .= '</td><td>';
			$content .= ($received);
			$content .= '</td><td>';
			$content .= '<a href="reports/view_sale_invoice.php?sale_id='.$sale_id.'" target="_blank">View</a><br><a href="reports/view_pos_sale_invoice.php?sale_id='.$sale_id.'" target="_blank" style="color:orange;">POS View</a>';
			$content .= '</td>';
				if(partial_access('admin')) { 
				$content .= '<td><a href="updatequery.php?si='.$sale_id.'" target="_blank">Update</a>';
				$content .= '</td>'; }
				$content .= '</tr>';	
			}
			if($GrandTotalTax > 0 && $received > 1400 && $received < 1500){
			$TotalSumAmount += $receiveable;			
			$content .= '<tr><td>';
			$content .= $invoice_number;
			$content .= '</td><td>';
			$datetime = strtotime($datetime);
			$content .= date('d-m-Y', $datetime);
			$content .= '</td><td>';
			$content .= $agent_name;
			$content .= '</td><td>';
			$content .= $client_name;
			$content .= '</td><td>';
			$content .= $manual_inv_no;
			$content .= '</td><td>';
			$content .= $memo;
			$content .= '</td><td>';
			$content .= $TotalSumAmount;
			$content .= '</td><td>';
			$content .= $items;
			$content .= '</td><td>';
			$content .= ($receiveable);
			$content .= '</td><td>';
			$content .= ($received);
			$content .= '</td><td>';
			$content .= '<a href="reports/view_sale_invoice.php?sale_id='.$sale_id.'" target="_blank">View</a><br><a href="reports/view_pos_sale_invoice.php?sale_id='.$sale_id.'" target="_blank" style="color:orange;">POS View</a>';
			$content .= '</td>';
				if(partial_access('admin')) { 
				$content .= '<td><a href="updatequery.php?si='.$sale_id.'" target="_blank">Update</a>';
				$content .= '</td>'; }
				$content .= '</tr>';	
			}
			if($GrandTotalTax > 0 && $received > 1500 && $received < 2000){ //Check
			$TotalSumAmount += $receiveable;			
			$content .= '<tr><td>';
			$content .= $invoice_number;
			$content .= '</td><td>';
			$datetime = strtotime($datetime);
			$content .= date('d-m-Y', $datetime);
			$content .= '</td><td>';
			$content .= $agent_name;
			$content .= '</td><td>';
			$content .= $client_name;
			$content .= '</td><td>';
			$content .= $manual_inv_no;
			$content .= '</td><td>';
			$content .= $memo;
			$content .= '</td><td>';
			$content .= $TotalSumAmount;
			$content .= '</td><td>';
			$content .= $items;
			$content .= '</td><td>';
			$content .= ($receiveable);
			$content .= '</td><td>';
			$content .= ($received);
			$content .= '</td><td>';
			$content .= '<a href="reports/view_sale_invoice.php?sale_id='.$sale_id.'" target="_blank">View</a><br><a href="reports/view_pos_sale_invoice.php?sale_id='.$sale_id.'" target="_blank" style="color:orange;">POS View</a>';
			$content .= '</td>';
				if(partial_access('admin')) { 
				$content .= '<td><a href="updatequery.php?si='.$sale_id.'" target="_blank">Update</a>';
				$content .= '</td>'; }
				$content .= '</tr>';	
			}
			if($GrandTotalTax > 0 && $received > 3950 && $received < 4000){
			$TotalSumAmount += $receiveable;			
			$content .= '<tr><td>';
			$content .= $invoice_number;
			$content .= '</td><td>';
			$datetime = strtotime($datetime);
			$content .= date('d-m-Y', $datetime);
			$content .= '</td><td>';
			$content .= $agent_name;
			$content .= '</td><td>';
			$content .= $client_name;
			$content .= '</td><td>';
			$content .= $manual_inv_no;
			$content .= '</td><td>';
			$content .= $memo;
			$content .= '</td><td>';
			$content .= $TotalSumAmount;
			$content .= '</td><td>';
			$content .= $items;
			$content .= '</td><td>';
			$content .= ($receiveable);
			$content .= '</td><td>';
			$content .= ($received);
			$content .= '</td><td>';
			$content .= '<a href="reports/view_sale_invoice.php?sale_id='.$sale_id.'" target="_blank">View</a><br><a href="reports/view_pos_sale_invoice.php?sale_id='.$sale_id.'" target="_blank" style="color:orange;">POS View</a>';
			$content .= '</td>';
				if(partial_access('admin')) { 
				$content .= '<td><a href="updatequery.php?si='.$sale_id.'" target="_blank">Update</a>';
				$content .= '</td>'; }
				$content .= '</tr>';	
			}
			if($GrandTotalTax > 0 && $received > 4100 && $received < 4200){
			$TotalSumAmount += $receiveable;			
			$content .= '<tr><td>';
			$content .= $invoice_number;
			$content .= '</td><td>';
			$datetime = strtotime($datetime);
			$content .= date('d-m-Y', $datetime);
			$content .= '</td><td>';
			$content .= $agent_name;
			$content .= '</td><td>';
			$content .= $client_name;
			$content .= '</td><td>';
			$content .= $manual_inv_no;
			$content .= '</td><td>';
			$content .= $memo;
			$content .= '</td><td>';
			$content .= $TotalSumAmount;
			$content .= '</td><td>';
			$content .= $items;
			$content .= '</td><td>';
			$content .= ($receiveable);
			$content .= '</td><td>';
			$content .= ($received);
			$content .= '</td><td>';
			$content .= '<a href="reports/view_sale_invoice.php?sale_id='.$sale_id.'" target="_blank">View</a><br><a href="reports/view_pos_sale_invoice.php?sale_id='.$sale_id.'" target="_blank" style="color:orange;">POS View</a>';
			$content .= '</td>';
				if(partial_access('admin')) { 
				$content .= '<td><a href="updatequery.php?si='.$sale_id.'" target="_blank">Update</a>';
				$content .= '</td>'; }
				$content .= '</tr>';	
			}
			if($GrandTotalTax > 0 && $received > 4800 && $received < 5000){
			$TotalSumAmount += $receiveable;			
			$content .= '<tr><td>';
			$content .= $invoice_number;
			$content .= '</td><td>';
			$datetime = strtotime($datetime);
			$content .= date('d-m-Y', $datetime);
			$content .= '</td><td>';
			$content .= $agent_name;
			$content .= '</td><td>';
			$content .= $client_name;
			$content .= '</td><td>';
			$content .= $manual_inv_no;
			$content .= '</td><td>';
			$content .= $memo;
			$content .= '</td><td>';
			$content .= $TotalSumAmount;
			$content .= '</td><td>';
			$content .= $items;
			$content .= '</td><td>';
			$content .= ($receiveable);
			$content .= '</td><td>';
			$content .= ($received);
			$content .= '</td><td>';
			$content .= '<a href="reports/view_sale_invoice.php?sale_id='.$sale_id.'" target="_blank">View</a><br><a href="reports/view_pos_sale_invoice.php?sale_id='.$sale_id.'" target="_blank" style="color:orange;">POS View</a>';
			$content .= '</td>';
				if(partial_access('admin')) { 
				$content .= '<td><a href="updatequery.php?si='.$sale_id.'" target="_blank">Update</a>';
				$content .= '</td>'; }
				$content .= '</tr>';	
			}
			if($GrandTotalTax > 0 && $received > 5500 && $received < 6700){ // 6000
			$TotalSumAmount += $receiveable;			
			$content .= '<tr><td>';
			$content .= $invoice_number;
			$content .= '</td><td>';
			$datetime = strtotime($datetime);
			$content .= date('d-m-Y', $datetime);
			$content .= '</td><td>';
			$content .= $agent_name;
			$content .= '</td><td>';
			$content .= $client_name;
			$content .= '</td><td>';
			$content .= $manual_inv_no;
			$content .= '</td><td>';
			$content .= $memo;
			$content .= '</td><td>';
			$content .= $TotalSumAmount;
			$content .= '</td><td>';
			$content .= $items;
			$content .= '</td><td>';
			$content .= ($receiveable);
			$content .= '</td><td>';
			$content .= ($received);
			$content .= '</td><td>';
			$content .= '<a href="reports/view_sale_invoice.php?sale_id='.$sale_id.'" target="_blank">View</a><br><a href="reports/view_pos_sale_invoice.php?sale_id='.$sale_id.'" target="_blank" style="color:orange;">POS View</a>';
			$content .= '</td>';
				if(partial_access('admin')) { 
				$content .= '<td><a href="updatequery.php?si='.$sale_id.'" target="_blank">Update</a>';
				$content .= '</td>'; }
				$content .= '</tr>';	
			}
			if($GrandTotalTax > 0 && $received > 7500 && $received < 7800){
			$TotalSumAmount += $receiveable;			
			$content .= '<tr><td>';
			$content .= $invoice_number;
			$content .= '</td><td>';
			$datetime = strtotime($datetime);
			$content .= date('d-m-Y', $datetime);
			$content .= '</td><td>';
			$content .= $agent_name;
			$content .= '</td><td>';
			$content .= $client_name;
			$content .= '</td><td>';
			$content .= $manual_inv_no;
			$content .= '</td><td>';
			$content .= $memo;
			$content .= '</td><td>';
			$content .= $TotalSumAmount;
			$content .= '</td><td>';
			$content .= $items;
			$content .= '</td><td>';
			$content .= ($receiveable);
			$content .= '</td><td>';
			$content .= ($received);
			$content .= '</td><td>';
			$content .= '<a href="reports/view_sale_invoice.php?sale_id='.$sale_id.'" target="_blank">View</a><br><a href="reports/view_pos_sale_invoice.php?sale_id='.$sale_id.'" target="_blank" style="color:orange;">POS View</a>';
			$content .= '</td>';
				if(partial_access('admin')) { 
				$content .= '<td><a href="updatequery.php?si='.$sale_id.'" target="_blank">Update</a>';
				$content .= '</td>'; }
				$content .= '</tr>';	
			}
			if($GrandTotalTax > 0 && $received > 8000 && $received < 10550){	 // 86 -> 95
			$TotalSumAmount += $receiveable;			
			$content .= '<tr><td>';
			$content .= $invoice_number;
			$content .= '</td><td>';
			$datetime = strtotime($datetime);
			$content .= date('d-m-Y', $datetime);
			$content .= '</td><td>';
			$content .= $agent_name;
			$content .= '</td><td>';
			$content .= $client_name;
			$content .= '</td><td>';
			$content .= $manual_inv_no;
			$content .= '</td><td>';
			$content .= $memo;
			$content .= '</td><td>';
			$content .= $TotalSumAmount;
			$content .= '</td><td>';
			$content .= $items;
			$content .= '</td><td>';
			$content .= ($receiveable);
			$content .= '</td><td>';
			$content .= ($received);
			$content .= '</td><td>';
			$content .= '<a href="reports/view_sale_invoice.php?sale_id='.$sale_id.'" target="_blank">View</a><br><a href="reports/view_pos_sale_invoice.php?sale_id='.$sale_id.'" target="_blank" style="color:orange;">POS View</a>';
			$content .= '</td>';
				if(partial_access('admin')) { 
				$content .= '<td><a href="updatequery.php?si='.$sale_id.'" target="_blank">Update</a>';
				$content .= '</td>'; }
				$content .= '</tr>';	
			}
			if($GrandTotalTax > 0 && $received > 3590 && $received < 3610){	
			$TotalSumAmount += $receiveable;			
			$content .= '<tr><td>';
			$content .= $invoice_number;
			$content .= '</td><td>';
			$datetime = strtotime($datetime);
			$content .= date('d-m-Y', $datetime);
			$content .= '</td><td>';
			$content .= $agent_name;
			$content .= '</td><td>';
			$content .= $client_name;
			$content .= '</td><td>';
			$content .= $manual_inv_no;
			$content .= '</td><td>';
			$content .= $memo;
			$content .= '</td><td>';
			$content .= $TotalSumAmount;
			$content .= '</td><td>';
			$content .= $items;
			$content .= '</td><td>';
			$content .= ($receiveable);
			$content .= '</td><td>';
			$content .= ($received);
			$content .= '</td><td>';
			$content .= '<a href="reports/view_sale_invoice.php?sale_id='.$sale_id.'" target="_blank">View</a><br><a href="reports/view_pos_sale_invoice.php?sale_id='.$sale_id.'" target="_blank" style="color:orange;">POS View</a>';
			$content .= '</td>';
				if(partial_access('admin')) { 
				$content .= '<td><a href="updatequery.php?si='.$sale_id.'" target="_blank">Update</a>';
				$content .= '</td>'; }
				$content .= '</tr>';	
			}
				
		}//main_while loop
		echo $content;
	}//list_all purchases function ends here.



}//Purchase Class Ends here.