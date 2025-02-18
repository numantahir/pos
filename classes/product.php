<?php
//product Class

class Product {
	public $product_id;
	public $product_manual_id;
	public $product_name;
	public $product_description;
	public $product_unit;
	public $category_id;
	public $tax_id;
	public $product_image;
	public $alert_units;
	public $product_cost;
	public $product_selling_price;
	
	function set_product($product_id) { 
		global $db;
		$query = "SELECT * from products WHERE product_id='".$product_id."' AND store_id='".$_SESSION['store_id']."'";
		$result = $db->query($query) or die($db->error);
		$row = $result->fetch_array();
		extract($row);
		
		$this->product_manual_id 	= $product_manual_id;
		$this->product_name 		= $product_name;
		$this->product_description 	= $product_description;
		$this->product_unit 		= $product_unit;
		$this->category_id 			= $category_id;
		$this->tax_id 				= $tax_id;
		$this->product_image 		= $product_image;
		$this->alert_units 			= $alert_units;
		//query cost and selling price.
		$query_cost = "SELECT * from price WHERE product_id='".$product_id."' AND store_id='".$_SESSION['store_id']."' ORDER by price_id ASC LIMIT 1";
		$result_cost = $db->query($query_cost) or die($db->error);
		$row_cost = $result_cost->fetch_array();
		
		$this->product_cost = $row_cost['cost'];
		$this->product_selling_price = $row_cost['selling_price'];
		
	}
	
	function add_product($product_manual_id, $product_name, $product_unit, $category_id, $tax_id, $product_cost, $product_selling_price, $alert_units, $product_image, $product_description) { 
		global $db;
		$query = "SELECT * from products WHERE product_manual_id='".$product_manual_id."' AND store_id='".$_SESSION['store_id']."'";
		$result = $db->query($query) or die($db->error);
		$num_rows = $result->num_rows;
		if($num_rows > 0) { 
			return 'A product already exist with this id.';
		} else { 
			$query = "INSERT into products (product_id, product_manual_id, product_name, product_description, product_unit, category_id, tax_id, product_image, alert_units, store_id) VALUES(NULL, '".$product_manual_id."', '".$product_name."', '".$product_description."', '".$product_unit."', '".$category_id."', '".$tax_id."', '".$product_image."', '".$alert_units."', '".$_SESSION['store_id']."')";
			$result = $db->query($query) or die($db->error);
			$product_id = $db->insert_id;
			
			//inserting values into price table.
			$query_price = "INSERT into price(price_id, cost, selling_price, store_id, product_id) VALUES(NULL, '".$product_cost."', '".$product_selling_price."', '".$_SESSION['store_id']."', '".$product_id."')";
			$result_price = $db->query($query_price) or die($db->error);
			
			//inserting product rates table.
			$query_rate = "INSERT into product_rates (rate_id, default_rate, level_1, level_2, level_3, level_4, level_5, store_id, product_id) VALUES(NULL, '".$product_selling_price."', '".$product_selling_price."', '".$product_selling_price."', '".$product_selling_price."', '".$product_selling_price."', '".$product_selling_price."', '".$_SESSION['store_id']."', '".$product_id."')";
			$result_rate = $db->query($query_rate) or die($db->error);
			return 'Product was added successfuly!';
		}
	}//add product ends here.
	
	function update_product($edit_product,$product_manual_id, $product_name, $product_unit, $category_id, $tax_id, $product_cost, $product_selling_price, $alert_units, $product_image, $product_description) {
		global $db;
		$query = "UPDATE products SET
			product_manual_id='".$product_manual_id."',
			product_name='".$product_name."',
			product_unit='".$product_unit."',
			category_id='".$category_id."',
			tax_id='".$tax_id."',
			alert_units='".$alert_units."',
			product_image='".$product_image."',
			product_description='".$product_description."'
			WHERE product_id='".$edit_product."'
		";
		$result = $db->query($query) or die($db->error);
		
		//update price.
		$update_price = "UPDATE price SET
		cost='".$product_cost."',
		selling_price='".$product_selling_price."'
		WHERE product_id='".$edit_product." ORDER by price_id ASC LIMIT 1'
		";
		$result_price = $db->query($update_price) or die($db->error);
		//Updating price ends here.
		$update_rate = "UPDATE product_rates SET
		default_rate='".$product_selling_price."'
		WHERE product_id='".$edit_product."' AND store_id='".$_SESSION['store_id']."'
		";
		$rate_query = $db->query($update_rate) or die($db->error);
		return 'Product was updated successfuly.';	
	}//update product ends here.
	
	function list_products() { 
		global $db;
		$query = "SELECT * from products WHERE store_id='".$_SESSION['store_id']."' ORDER by product_name ASC";
		$result = $db->query($query) or die($db->error);
			$content = '';
			$count = 0;
			while($row = $result->fetch_array()) { 
				extract($row);
				$count++;
				if($count % 2 == 0) { 
					$class = 'even';
				} else { 
					$class = 'odd';
				}
				$content .= '<tr class="'.$class.'">';
				$content .= '<td>';
				$content .= $product_manual_id;
				$content .= '</td><td>';
				$content .= $product_name;
				$content .= '</td><td>';
				$content .= substr($product_description, 0,18);
				$content .= '</td><td>';
				$content .= $product_unit;
				$content .= '</td><td>';
				//category and tax objects to get related information.
				$product_category = new ProductCategory;
				$product_tax = new ProductTax;
				
				$content .= $product_category->get_category_info($category_id, 'category_name');
				$content .= '</td><td>';
				$content .= $product_tax->get_tax_info($tax_id, 'tax_name');
				$content .= '</td><td>';
				if($product_image != '') { 
					$product_image = '<img class="img-thumbnail" src="'.$product_image.'" width="50" height="50" />';
				}
				$inventory = "SELECT SUM(inn), SUM(out_inv) FROM inventory WHERE product_id='".$product_id."'";
				$inventory_result = $db->query($inventory) or die($db->error);
				$inventory_row = $inventory_result->fetch_array();
				
				$inventory = $inventory_row['SUM(inn)']-$inventory_row['SUM(out_inv)'];
				
				$content .= $product_image;
				$content .= '</td><td>';
				$content .= $alert_units;
				$content .= '</td><td>';
				$content .= $inventory;
				$content .= '</td><td>';
				//query cost and selling price.
				$query_cost = "SELECT * from price WHERE product_id='".$product_id."' AND store_id='".$_SESSION['store_id']."' ORDER by price_id ASC LIMIT 1";
				$result_cost = $db->query($query_cost) or die($db->error);
				$row_cost = $result_cost->fetch_array();
				if(partial_access('admin')) {
				$content .= $row_cost['cost'];
				$content .= '</td><td>';
				}
				$content .= $row_cost['selling_price'];
				$content .= '</td>';
				if(partial_access('admin')) {
				$content .= '<td class="no-print">';
				$content .= '<form method="post" name="edit" action="manage_products.php">';
				$content .= '<input type="hidden" name="edit_product" value="'.$product_id.'">';
				$content .= '<input type="submit" class="btn btn-default btn-sm" value="Edit">';
				$content .= '</form>';
				$content .= '</td><td class="no-print">';
				$content .= '<form method="post" name="delete" onsubmit="return confirm_delete();" action="">';
				$content .= '<input type="hidden" name="delete_product" value="'.$product_id.'">';
				$content .= '<input type="submit" class="btn btn-default btn-sm" value="Delete">';
				$content .= '</form>';
				$content .= '</td>';
				}
				$content .= '</tr>';
				unset($class);
			}//loop ends here.
		echo $content;
	}
	
	function stock_detail() { 
		global $db;
		$query = "SELECT * from products WHERE store_id='".$_SESSION['store_id']."' ORDER by product_manual_id ASC";
		$result = $db->query($query) or die($db->error);
			$content = '';
			$count = 0;
			while($row = $result->fetch_array()) { 
				extract($row);
				$count++;
				if($count % 2 == 0) { 
					$class = 'even';
				} else { 
					$class = 'odd';
				}
				$content .= '<tr class="'.$class.'">';
				$content .= '<td>';
				$content .= $product_manual_id;
				$content .= '</td><td>';
				$content .= $product_name;
				$content .= '</td><td>';
				if($product_image != '') { 
					$product_image = '<img class="img-thumbnail" src="../'.$product_image.'" width="75" height="75" />';
				}
				$inventory = "SELECT SUM(inn), SUM(out_inv) FROM inventory WHERE product_id='".$product_id."'";
				$inventory_result = $db->query($inventory) or die($db->error);
				$inventory_row = $inventory_result->fetch_array();
				
				$inventory = $inventory_row['SUM(inn)']-$inventory_row['SUM(out_inv)'];
				
				$content .= $product_image;
				$content .= '</td><td>';
				$content .= $inventory;
				$content .= '</td><td>';
				//query cost and selling price.
				$query_cost = "SELECT * from price WHERE product_id='".$product_id."' AND store_id='".$_SESSION['store_id']."' ORDER by price_id DESC LIMIT 1";
				$result_cost = $db->query($query_cost) or die($db->error);
				$row_cost = $result_cost->fetch_array();
				
				$content .= $row_cost['selling_price'];
				$content .= '</td>';
				$content .= '</tr>';
				unset($class);
			}//loop ends here.
		echo $content;
		echo 'Printed Rows: '.$count;
	}
	
	function products_alert() { 
		global $db;
		$query = "SELECT * from products WHERE store_id='".$_SESSION['store_id']."' ORDER by product_name ASC";
		$result = $db->query($query) or die($db->error);
			$content = '';

			while($row = $result->fetch_array()) { 
				extract($row);
				
				$inventory = "SELECT SUM(inn), SUM(out_inv) FROM inventory WHERE product_id='".$product_id."'";
				$inventory_result = $db->query($inventory) or die($db->error);
				$inventory_row = $inventory_result->fetch_array();
				
				$inventory = $inventory_row['SUM(inn)']-$inventory_row['SUM(out_inv)'];
				
				if($inventory <= $alert_units) {
				$content .= '<tr><td>';
				$content .= $product_manual_id;
				$content .= '</td><td>';
				$content .= $product_name;
				$content .= '</td><td>';
				$content .= substr($product_description, 0,18);
				$content .= '</td><td>';
				$content .= $alert_units;
				$content .= '</td><td>';
				$content .= $inventory;
				$content .= '</td></tr>';
				}
			}//loop ends here.
		echo $content;
	}
	
	function products_alert_count() { 
		global $db;
		$query = "SELECT * from products WHERE store_id='".$_SESSION['store_id']."' ORDER by product_name ASC";
		$result = $db->query($query) or die($db->error);
			$count = 0;

			while($row = $result->fetch_array()) { 
				extract($row);
				
				$inventory = "SELECT SUM(inn), SUM(out_inv) FROM inventory WHERE product_id='".$product_id."'";
				$inventory_result = $db->query($inventory) or die($db->error);
				$inventory_row = $inventory_result->fetch_array();
				
				$inventory = $inventory_row['SUM(inn)']-$inventory_row['SUM(out_inv)'];
				
				if($inventory <= $alert_units) {
					$count++;
				}
			}//loop ends here.
		return $count;
	}
	
	
	function list_product_rates() { 
		global $db;
		$query = "SELECT * from products WHERE store_id='".$_SESSION['store_id']."' ORDER by product_name ASC";
		$result = $db->query($query) or die($db->error);
		
		$content = '';
		while($row = $result->fetch_array()) {
			extract($row);
			
			$query_rate = "SELECT * from product_rates WHERE product_id='".$product_id."' AND store_id='".$_SESSION['store_id']."'";
			$result_rate = $db->query($query_rate) or die($db->error);
			$row_rate = $result_rate->fetch_array();
			
			$content .= '<tr>';
			$content .= '<form method="post" action="">';
			$content .= '<td>';
			$content .= $product_manual_id;
			$content .= '</td><td>';
			$content .= $product_name;
			$content .= '</td><td>';
			$content .= '<input type="text" class="rate" name="default_rate" value="'.$row_rate['default_rate'].'">';
			$content .= '</td><td>';
			$content .= '<input type="text" class="rate" name="level_1" value="'.$row_rate['level_1'].'">';
			$content .= '</td><td>';
			$content .= '<input type="text" class="rate" name="level_2" value="'.$row_rate['level_2'].'">';
			$content .= '</td><td>';
			$content .= '<input type="text" class="rate" name="level_3" value="'.$row_rate['level_3'].'">';
			$content .= '</td><td>';
			$content .= '<input type="text" class="rate" name="level_4" value="'.$row_rate['level_4'].'">';
			$content .= '</td><td>';
			$content .= '<input type="text" class="rate" name="level_5" value="'.$row_rate['level_5'].'">';
			$content .= '</td><td>';
			$content .= '<input type="hidden" name="update_rate" value="'.$row_rate['rate_id'].'">';
			$content .= '<input type="hidden" name="product_id" value="'.$product_id.'">';
			$content .= '<input type="submit" class="btn btn-default btn-sm" value="Update">';
			$content .= '</td></form></tr>';	
		} //while loop products
		echo $content;	
	} //list product rates to manage rates of different price levels.
	
	function delete_product($product_id) {
		global $db;
		$query = "SELECT * FROM inventory WHERE product_id='".$product_id."'";
		$result = $db->query($query) or die($db->error);
		$num_rows = $result->num_rows;
		
		if($num_rows > 0) {
			return 'You cannot delete product please delete purchase invoices, sale invoices, sale returns, purchase returns related to this product first.'; 
		} else { 
		$query = "DELETE FROM products WHERE product_id='".$product_id."'";
		$result = $db->query($query) or die($db->error);
		return 'Product was deleted successfuly.';
		}
	}//product delete
	
	function update_client_level($client_id, $price_level) {
		global $db;
		
		$query = 'UPDATE clients SET
			price_level="'.$price_level.'"
			WHERE client_id="'.$client_id.'" AND store_id="'.$_SESSION['store_id'].'"
		';	
		$result = $db->query($query) or die($db->error);
		return 'Price level was updated successfuly!';
	}//update_client level
	
	function update_product_rates($product_id, $rate_id, $default_rate, $level_1, $level_2, $level_3, $level_4, $level_5) { 
		global $db;
		
		$update_rate = "UPDATE product_rates SET
		default_rate='".$default_rate."',
		level_1='".$level_1."',
		level_2='".$level_2."',
		level_3='".$level_3."',
		level_4='".$level_4."',
		level_5='".$level_5."'
		WHERE rate_id='".$rate_id."'
		";
		$result_rate = $db->query($update_rate) or die($db->error);
		
		//update price.
		$update_price = "UPDATE price SET
		selling_price='".$default_rate."'
		WHERE product_id='".$product_id."' ORDER by price_id ASC LIMIT 1";
		$result_price = $db->query($update_price) or die($db->error);
		
		return 'Rate was updated successfuly!';
	}
	
	function list_client_levels() { 
		global $db;
		$query = "SELECT * from clients WHERE store_id='".$_SESSION['store_id']."' ORDER by full_name ASC";
		$result = $db->query($query) or die($db->error);
		
		$content = '';
		while($row = $result->fetch_array()) {
			extract($row);
			$content .= '<tr>';
			$content .= '<form method="post" action="">';
			$content .= '<td>';
			$content .= $client_id;
			$content .= '</td><td>';
			$content .= $full_name;
			$content .= '</td><td>';
			$content .= $business_title;
			$content .= '</td><td>';
			$content .= $mobile;
			$content .= '</td><td>';
			$content .= $phone;
			$content .= '</td><td>';
			$content .= $email;
			$content .= '</td><td>';
			$content .= '<select name="price_level" class="form-control" style="height:28px;padding-top:3px;padding-bottom:3px;">';
			if($price_level == 'default_level'):
			$content .= '<option selected="selected" value="default_level">Default</option>';
            else:
			$content .= '<option value="default_level">Default</option>';
			endif;
			if($price_level == 'level_1'):
			$content .= '<option selected="selected" value="level_1">Level 1</option>';
			else:
			$content .= '<option value="level_1">Level 1</option>';
			endif;
			if($price_level == 'level_2'):
            $content .= '<option selected="selected" value="level_2">Level 2</option>';
			else:
			$content .= '<option value="level_2">Level 2</option>';
			endif;
			if($price_level == 'level_3'):
            $content .= '<option selected="selected" value="level_3">Level 3</option>';
			else:
			$content .= '<option value="level_3">Level 3</option>';
			endif;
			if($price_level == 'level_4'):
            $content .= '<option selected="selected" value="level_4">Level 4</option>';
			else:
			$content .= '<option value="level_4">Level 4</option>';
			endif;
			if($price_level == 'level_5'):
            $content .= '<option selected="selected" value="level_5">Level 5</option>';
			else:
			$content .= '<option value="level_5">Level 5</option>';
            endif;
			$content .= '</select>';
			$content .= '</td><td>';
			$content .= '<input type="hidden" name="update_client" value="'.$client_id.'">';
			$content .= '<input type="submit" class="btn btn-default btn-sm" value="Update">';
			$content .= '</td></form></tr>';	
		} //while loop products
		echo $content;	
	} //list product rates to manage rates of different price levels.
	
	function product_options($product_id) {
		global $db;
		$query = 'SELECT * from products WHERE store_id="'.$_SESSION['store_id'].'" ORDER by product_name ASC';
		$result = $db->query($query) or die($db->error);
		$options = '';
		if($product_id != '') { 
			while($row = $result->fetch_array()) { 
				if($product_id == $row['product_id']) {
				$options .= '<option selected="selected" value="'.$row['product_id'].'">'.$row['product_name'].' ('.$row['product_manual_id'].')</option>';
				} else { 
				$options .= '<option value="'.$row['product_id'].'">'.$row['product_name'].' ('.$row['product_manual_id'].')</option>';
				}
			}
		} else { 
			while($row = $result->fetch_array()) { 
				$options .= '<option value="'.$row['product_id'].'">'.$row['product_name'].' ('.$row['product_manual_id'].')</option>';
			}
		}
		echo $options;	
	}//product_options ends here.
	
	function get_product_info($product_id, $term) { 
		global $db;
		$query = "SELECT * from products WHERE product_id='".$product_id."'";
		$result = $db->query($query) or die($db->error);
		$row = $result->fetch_array();
		return $row[$term];
	}//get user email ends here.
	
	function get_product_rate($product_id, $term) { 
		global $db;
		$query = "SELECT * from product_rates WHERE product_id='".$product_id."'";
		$result = $db->query($query) or die($db->error);
		$row = $result->fetch_array();
		return $row[$term];
	}//get user email ends here.
	
	function list_pos_products($category) { 
		global $db;
		
		if(get_option($_SESSION['store_id'].'_pos_items') == '' || !is_numeric(get_option($_SESSION['store_id'].'_pos_items'))) { 
			$items_to_show = 18;
		} else { 
			$items_to_show = get_option($_SESSION['store_id'].'_pos_items');
		}
		
				
		if(isset($_SESSION['category_id']) && $_SESSION['category_id'] == 'all') {
			$num_rows = "SELECT COUNT(*) FROM products WHERE store_id='".$_SESSION['store_id']."'";
		} else { 
			$num_rows = "SELECT COUNT(*) FROM products WHERE store_id='".$_SESSION['store_id']."' AND category_id='".$_SESSION['category_id']."'";
		}
		
		$num_rows_result = $db->query($num_rows) or die($db->error());
		$num_rows_rows = $num_rows_result->fetch_row();
		
		$last = ceil($num_rows_rows[0]/$items_to_show);
		
		if($last < 1){
			$last = 1;
		}

		$pagenum = 1;
		// Get pagenum from URL vars if it is present, else it is = 1
		if(isset($_SESSION['pn'])){
			$pagenum = preg_replace('#[^0-9]#', '', $_SESSION['pn']);
		}
		// This makes sure the page number isn't below 1, or more than our $last page
		if ($pagenum < 1) { 
			$pagenum = 1; 
		} else if ($pagenum > $last) { 
			$pagenum = $last; 
		}
		// This sets the range of rows to query for the chosen $pagenum
		$limit = 'LIMIT ' .($pagenum - 1) * $items_to_show .',' .$items_to_show;
		
		if(isset($_SESSION['category_id']) && $_SESSION['category_id'] == 'all')  { 
			$query = "SELECT product_id, product_name, product_image FROM products WHERE store_id='".$_SESSION['store_id']."' ORDER BY product_manual_id ASC $limit";
		} else { 
			$query = "SELECT product_id, product_name, product_image FROM products WHERE category_id='".$_SESSION['category_id']."' AND store_id='".$_SESSION['store_id']."' ORDER BY product_manual_id ASC $limit";
		}
		
		// Establish the $paginationCtrls variable
		$paginationCtrls = '<ul class="pager">';
		// If there is more than 1 page worth of results
		if($last != 1){
			if ($pagenum > 1) {
   			     $previous = $pagenum - 1;
				  $paginationCtrls .= '<li><a href="#" id="prevpage" data-page='.$previous.'">Previous</a></li>';
    		}
    		if ($pagenum != $last) {
    		    $next = $pagenum + 1;
        		$paginationCtrls .= '<li><a href="#" id="nextpage" data-page='.$next.'>Next</a></li>';
    		}
		}
		$paginationCtrls .= '</ul>';
		
		$result = $db->query($query) or die($db->error);
		$products = '';
		while($row = $result->fetch_array()) { 
			$products .= '<div class="product">';
			if($row['product_image'] == '') { 
				$product_image = 'images/product_icon.png';
			} else { 
				$product_image = $row['product_image'];
			}
			$products .= '<input type="image" src="'.$product_image.'" class="pos_product_id" value="'.$row['product_id'].'"  />';
			$products .= '<label title="Click on image to add product">'.$row['product_name'].'</label>';	
            $products .= '</div>';
		}
		return $paginationCtrls.$products;
	} //list POs products
	
}//class ends here.