<?php
//storeAccess Class

class StoreAccess {
	
	function add_store_access($user_id, $store_id, $access_to) { 
		global $db;
		if($_SESSION['user_type'] == 'admin') {
			$query = "SELECT * from store_access WHERE user_id='".$user_id."' AND store_id='".$store_id."'";
			$result = $db->query($query) or die($db->error);
			$rows = $result->num_rows;
			if($rows > 0) { 
				return 'User already have access to this store.';
			} else { 
				$sales = 0;
				$purchase = 0;
				$vendors = 0;
				$clients = 0;
				$products = 0;
				$warehouse = 0;
				$returns = 0;
				$price_level = 0;
				$reports = 0;
				$expenses = 0;
				
				foreach($access_to as $access) { 
					if($access == 'sales') { 
						$sales = 1;
					} else if($access == 'purchase') { 
						$purchase = 1;
					} else if($access == 'vendors') { 
						$vendors = 1;
					} else if($access == 'clients') { 
						$clients = 1;
					} else if($access == 'products') { 
						$products = 1;
					} else if($access == 'warehouse') { 
						$warehouse = 1;
					} else if($access == 'returns') { 
						$returns = 1;
					} else if($access == 'price_level') { 
						$price_level = 1;
					} else if($access == 'reports') { 
						$reports = 1;
					} else if($access == 'expenses') { 
						$expenses = 1;
					}
				}
				
				$query = "INSERT into store_access(user_id, store_id, sales, purchase, vendors, clients, products, warehouse, returns, price_level, reports, expenses) VALUES('".$user_id."', '".$store_id."', '".$sales."', '".$purchase."', '".$vendors."', '".$clients."', '".$products."', '".$warehouse."', '".$returns."', '".$price_level."', '".$reports."', '".$expenses."')";
				$result = $db->query($query) or die($db->error);
				return 'Access granted successfuly.';
			}
		} else { 
			return 'You cannot access this feature.';
		}
	}//add store acces ends here,.
	
	function list_store_access() { 
		global $db;
		if($_SESSION['user_type'] != 'admin') {
			echo 'You cannot view this list.';	
		} else {
			$query = "SELECT * from store_access";
			$result = $db->query($query) or die($db->error);
			$options = '';
			while($row = $result->fetch_array()) {
				$query_user = "SELECT * from users WHERE user_id='".$row['user_id']."'";
				$result_user = $db->query($query_user) or die($db->error);
				$row_user = $result_user->fetch_array();
				//user info query ends here.
				$query_store = "SELECT * from stores WHERE store_id='".$row['store_id']."'";
				$result_store = $db->query($query_store) or die($db->error);
				$row_store = $result_store->fetch_array();	
				//store info ends here.
				
				$options .= '<tr>';
				$options .= '<td>'.$row['user_id'].'</td>';
				$options .= '<td>'.$row_user['first_name'].' '.$row_user['last_name'].'</td>';
				$options .= '<td>'.$row_user['email'].'</td>';
				$options .= '<td>'.$row_store['store_name'].'</td>';
				$options .= '<td>';
				if($row['sales'] == '1') { 
					$options .= 'Sales, ';
				} 
				if($row['purchase'] == '1'){ 
					$options .= 'Purchase, ';
				}
				if($row['vendors'] == '1'){ 
					$options .= 'Vendors, ';
				}
				if($row['clients'] == '1'){ 
					$options .= 'Clients, ';
				}
				if($row['products'] == '1'){ 
					$options .= 'Products, ';
				}
				if($row['warehouse'] == '1'){ 
					$options .= 'Warehouse, ';
				}
				if($row['returns'] == '1'){ 
					$options .= 'Returns, ';
				}
				if($row['price_level'] == '1'){ 
					$options .= 'Price Level, ';
				}
				if($row['reports'] == '1'){ 
					$options .= 'Reports, ';
				}
				if($row['expenses'] == '1'){ 
					$options .= 'Expenses';
				}
				$options .= '</td>';
				$options .= '<td><form method="post" name="delete" onsubmit="return confirm_delete();" action="">';
				$options .= '<input type="hidden" name="delete_access" value="'.$row['access_id'].'">';
				$options .= '<input type="submit" class="btn btn-default btn-sm" value="Delete Access">';
				$options .= '</form></td>';
				$options .= '</tr>';
			}//while loop ends here.
			echo $options;	
		}
	}//list_store_access function ends here.
	
	function delete_access($access_id) {
			global $db; 
		if($_SESSION['user_type'] == 'admin' && $access_id != '') { 
			$query = "DELETE from store_access WHERE access_id='".$access_id."'";
			$result = $db->query($query) or die($db->error);
			return 'store access deleted successfuly!';
		}//if admin
	}//delete acces function ends here.
	
	function have_store_access() {
		global $db;
		$query = "SELECT * from store_access WHERE user_id='".$_SESSION['user_id']."' AND store_id='".$_SESSION['store_id']."'"; 
		$result = $db->query($query) or die($db->error);
		$num_rows = $result->num_rows;
		if($num_rows > 0) { 
			return TRUE;
		} else { 
			return FALSE;
		}
	}//have_store_access.
	
	function have_module_access($module) { 
		global $db;
		$query = "SELECT * from store_access WHERE user_id='".$_SESSION['user_id']."' AND store_id='".$_SESSION['store_id']."'";
		$result = $db->query($query) or die($db->error);
		$row = $result->fetch_array();
		
		if($row[$module] == '1') { 
			return TRUE;
		} else { 
			return FALSE;
		}
	}//end of have module access.
	
}//store access class ends here.