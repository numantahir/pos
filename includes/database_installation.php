<?php

	global $db; //creating database object.
	
	if($db->query('SELECT 1 from user_meta') == FALSE) { 
		$query = 'CREATE TABLE user_meta (
			`user_meta_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`user_id` bigint(20) NULL,
			`message_email` varchar(50) NULL,
			`last_login_time` datetime NULL,
			`last_login_ip` varchar(120) NULL,
  			`login_attempt` bigint(20) NULL,
			`login_lock` varchar(50) NULL,
			PRIMARY KEY (`user_meta_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'User Meta Table created.<br>';
	}  //Creating user notes table ends here.
	
	if($db->query('SELECT 1 from purchase_return_receiving') == FALSE) {
		$query = 'CREATE TABLE purchase_return_receiving (
			`return_receiving_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`datetime` datetime NULL,
			`method` varchar(200) NULL,
			`ref_no` varchar(200) NULL,
			`memo` varchar(200) NULL,
			`amount` double NULL,
			`vendor_id` bigint(20) NULL,
			`agent_id` bigint(20) NULL,
			`store_id` bigint(20) NULL,
			PRIMARY KEY (`return_receiving_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Payments Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from purchase_return_detail') == FALSE) {
		$query = 'CREATE TABLE purchase_return_detail (
			`purchase_detail_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`purchase_rt_id` bigint(20) NULL,
			`store_id` bigint(20) NULL,
			`price_id` bigint(20) NULL,
			`inventory_id` bigint(20) NULL,
			`debt_id` bigint(20) NULL,
			`reason_id` bigint(20) NULL,
			PRIMARY KEY (`purchase_detail_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Purchase Detail Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from purchase_returns') == FALSE) {
		$query = 'CREATE TABLE purchase_returns (
			`purchase_rt_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`datetime` datetime NULL,
			`invoice_no` varchar(200) NULL,
			`memo` varchar(200) NULL,
			`vendor_id` bigint(20) NULL,
			`payment_status` varchar(200) NULL,
			`store_id` bigint(20) NULL,
			`agent_id` bigint(20) NULL,
			PRIMARY KEY (`purchase_rt_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Purchase return Table created.<br>';
	} //creating user level table ends.
	
	//purchase return tables ends here.
	
	if($db->query('SELECT 1 from receivings') == FALSE) {
		$query = 'CREATE TABLE receivings (
			`receiving_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`datetime` datetime NULL,
			`method` varchar(200) NULL,
			`ref_no` varchar(200) NULL,
			`memo` varchar(200) NULL,
			`amount` double NULL,
			`client_id` bigint(20) NULL,
			`agent_id` bigint(20) NULL,
			`store_id` bigint(20) NULL,
			PRIMARY KEY (`receiving_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Receving Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from sale_return_payment') == FALSE) {
		$query = 'CREATE TABLE sale_return_payment (
			`return_payment_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`datetime` datetime NULL,
			`method` varchar(200) NULL,
			`ref_no` varchar(200) NULL,
			`memo` varchar(200) NULL,
			`amount` double NULL,
			`client_id` bigint(20) NULL,
			`agent_id` bigint(20) NULL,
			`store_id` bigint(20) NULL,
			PRIMARY KEY (`return_payment_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Payments Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from payments') == FALSE) {
		$query = 'CREATE TABLE payments (
			`payment_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`datetime` datetime NULL,
			`method` varchar(200) NULL,
			`ref_no` varchar(200) NULL,
			`memo` varchar(200) NULL,
			`amount` double NULL,
			`vendor_id` bigint(20) NULL,
			`agent_id` bigint(20) NULL,
			`store_id` bigint(20) NULL,
			PRIMARY KEY (`payment_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Payments Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from customer_log') == FALSE) {
		$query = 'CREATE TABLE customer_log (
			`customer_log_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`datetime` datetime NULL,
			`client_id` bigint(20) NULL,
			`transaction_type` varchar(200) NULL,
			`type_table_id` bigint(20) NULL,
			`store_id` bigint(20) NULL,
			PRIMARY KEY (`customer_log_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Client Log Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from vendor_log') == FALSE) {
		$query = 'CREATE TABLE vendor_log (
			`vendor_log_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`datetime` datetime NULL,
			`vendor_id` bigint(20) NULL,
			`transaction_type` varchar(200) NULL,
			`type_table_id` bigint(20) NULL,
			`store_id` bigint(20) NULL,
			PRIMARY KEY (`vendor_log_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Vendor Log Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from sale_detail') == FALSE) {
		$query = 'CREATE TABLE sale_detail (
			`sale_detail_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`sale_id` bigint(20) NULL,
			`store_id` bigint(20) NULL,
			`price_id` bigint(20) NULL,
			`inventory_id` bigint(20) NULL,
			`credit_id` bigint(20) NULL,
			PRIMARY KEY (`sale_detail_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Purchase Detail Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from sale_return_detail') == FALSE) {
		$query = 'CREATE TABLE sale_return_detail (
			`return_detail_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`sale_rt_id` bigint(20) NULL,
			`store_id` bigint(20) NULL,
			`price_id` bigint(20) NULL,
			`inventory_id` bigint(20) NULL,
			`credit_id` bigint(20) NULL,
			`reason_id` bigint(20) NULL,
			PRIMARY KEY (`return_detail_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Purchase Detail Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from purchase_detail') == FALSE) {
		$query = 'CREATE TABLE purchase_detail (
			`purchase_detail_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`purchase_id` bigint(20) NULL,
			`store_id` bigint(20) NULL,
			`price_id` bigint(20) NULL,
			`inventory_id` bigint(20) NULL,
			`debt_id` bigint(20) NULL,
			PRIMARY KEY (`purchase_detail_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Purchase Detail Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from creditors') == FALSE) {
		$query = 'CREATE TABLE creditors (
			`credit_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`receiveable` double NULL,
			`received` double NULL,
			`client_id` bigint(20) NULL,
			`store_id` bigint(20) NULL,
			PRIMARY KEY (`credit_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Creditors Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from debts') == FALSE) {
		$query = 'CREATE TABLE debts (
			`debt_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`payable` double NULL,
			`paid` double NULL,
			`vendor_id` bigint(20) NULL,
			`store_id` bigint(20) NULL,
			PRIMARY KEY (`debt_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Debts Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from inventory') == FALSE) {
		$query = 'CREATE TABLE inventory (
			`inventory_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`inn` bigint(20) NULL,
			`out_inv` bigint(20) NULL,
			`store_id` bigint(20) NULL,
			`product_id` bigint(20) NULL,
			`warehouse_id` bigint(20) NULL,
			PRIMARY KEY (`inventory_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Inventory Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from purchases') == FALSE) {
		$query = 'CREATE TABLE purchases (
			`purchase_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`datetime` datetime NULL,
			`supp_inv_no` varchar(200) NULL,
			`memo` varchar(200) NULL,
			`vendor_id` bigint(20) NULL,
			`payment_status` varchar(200) NULL,
			`store_id` bigint(20) NULL,
			`agent_id` bigint(20) NULL,
			PRIMARY KEY (`purchase_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Purchases Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from sale_returns') == FALSE) {
		$query = 'CREATE TABLE sale_returns (
			`sale_rt_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`datetime` datetime NULL,
			`invoice_no` varchar(200) NULL,
			`memo` varchar(200) NULL,
			`client_id` bigint(20) NULL,
			`payment_status` varchar(200) NULL,
			`store_id` bigint(20) NULL,
			`agent_id` bigint(20) NULL,
			PRIMARY KEY (`sale_rt_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Sales Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from sales') == FALSE) {
		$query = 'CREATE TABLE sales (
			`sale_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`datetime` datetime NULL,
			`manual_inv_no` varchar(200) NULL,
			`memo` varchar(200) NULL,
			`client_id` bigint(20) NULL,
			`payment_status` varchar(200) NULL,
			`store_id` bigint(20) NULL,
			`agent_id` bigint(20) NULL,
			PRIMARY KEY (`sale_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Sales Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from return_reasons') == FALSE) {
		$query = 'CREATE TABLE return_reasons (
			`reason_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`title` varchar(200) NULL,
			`description` varchar(200) NULL,
			`store_id` bigint(20) NULL,
			PRIMARY KEY (`reason_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Return Reasons Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from clients') == FALSE) {
		$query = 'CREATE TABLE clients (
			`client_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`full_name` varchar(200) NULL,
			`business_title` varchar(200) NULL,
			`mobile` varchar(200) NULL,
			`phone` varchar(200) NULL,
			`address` varchar(200) NULL,
			`city` varchar(200) NULL,
			`state` varchar(200) NULL,
			`zipcode` varchar(200) NULL,
			`country` varchar(200) NULL,
			`email` varchar(200) NULL,
			`price_level` varchar(200) NULL,
			`notes` varchar(400) NULL,
			`store_id` bigint(20) NULL,
			PRIMARY KEY (`client_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Client Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from vendors') == FALSE) {
		$query = 'CREATE TABLE vendors (
			`vendor_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`full_name` varchar(200) NULL,
			`contact_person` varchar(200) NULL,
			`mobile` varchar(200) NULL,
			`phone` varchar(200) NULL,
			`address` varchar(200) NULL,
			`city` varchar(200) NULL,
			`state` varchar(200) NULL,
			`zipcode` varchar(100) NULL,
			`country` varchar(200) NULL,
			`provider_of` varchar(200) NULL,
			`store_id` bigint(20) NULL,
			PRIMARY KEY (`vendor_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Vendors Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from warehouse_log') == FALSE) {
		$query = 'CREATE TABLE warehouse_log (
			`wh_log_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`date` datetime NULL,
			`product_id` bigint(20) NULL,
			`units` bigint(20) NULL,
			`current_wh_id` bigint(20) NULL,
			`new_wh_id` bigint(20) NULL,
			`store_id` bigint(20) NULL,
			`agent_id` bigint(20) NULL,
			PRIMARY KEY (`wh_log_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Warehouse log Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from warehouses') == FALSE) {
		$query = 'CREATE TABLE warehouses (
			`warehouse_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`name` varchar(200) NULL,
			`address` varchar(200) NULL,
			`city` varchar(200) NULL,
			`state` varchar(200) NULL,
			`country` varchar(200) NULL,
			`manager` varchar(200) NULL,
			`contact` varchar(200) NULL,
			`store_id` bigint(20) NULL,
			PRIMARY KEY (`warehouse_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Warehouse Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from price') == FALSE) {
		$query = 'CREATE TABLE price (
			`price_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`cost` float NULL,
			`selling_price` float NULL,
			`tax` double NULL,
			`store_id` bigint(20) NULL,
			`product_id` bigint(20) NULL,
			PRIMARY KEY (`price_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Product Price Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from product_rates') == FALSE) {
		$query = 'CREATE TABLE product_rates (
			`rate_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`default_rate` float NULL,
			`level_1` float NULL,
			`level_2` float NULL,
			`level_3` float NULL,
			`level_4` float NULL,
			`level_5` float NULL,
			`store_id` bigint(20) NULL,
			`product_id` bigint(20) NULL,
			PRIMARY KEY (`rate_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Product Rate Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from products') == FALSE) {
		$query = 'CREATE TABLE products (
			`product_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`product_manual_id` varchar(200) NULL,
			`product_name` varchar(200) NULL,
			`product_description` varchar(600) NULL,
			`product_unit` varchar(200) NULL,
			`category_id` bigint(20) NULL,
			`tax_id` bigint(20) NULL,
			`product_image` varchar(400) NULL,
			`alert_units` varchar(100) NULL,
			`store_id` bigint(20) NULL,
			PRIMARY KEY (`product_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Product Taxes Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from product_taxes') == FALSE) {
		$query = 'CREATE TABLE product_taxes (
			`tax_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`tax_name` varchar(200) NULL,
			`tax_rate` varchar(200) NULL,
			`tax_type` varchar(200) NULL,
			`tax_description` varchar(600) NULL,
			`store_id` bigint(20) NULL,
			PRIMARY KEY (`tax_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Product Taxes Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from product_categories') == FALSE) {
		$query = 'CREATE TABLE product_categories (
			`category_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`category_name` varchar(200) NULL,
			`category_description` varchar(600) NULL,
			`store_id` bigint(20) NULL,
			PRIMARY KEY (`category_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Product Categories Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from expense_types') == FALSE) {
		$query = 'CREATE TABLE expense_types (
			`type_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`type_name` varchar(200) NULL,
			`type_description` varchar(600) NULL,
			`store_id` bigint(20) NULL,
			PRIMARY KEY (`type_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Product Categories Table created.<br>';
	} //creating user level table ends.
	
	//if database tables does not exist already create them.
	if($db->query('SELECT 1 from expenses') == FALSE) {
		$query = 'CREATE TABLE expenses (
			`expense_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`type_id` bigint(20) NULL,
			`datetime` datetime NULL,
			`title` varchar(400) NULL,
			`description` varchar(1000) NULL,
			`amount` decimal(10,2),
			`agent_id` bigint(20) NULL,
			`store_id` bigint(20) NULL,
  			PRIMARY KEY (`expense_id`)
		)';
		$result = $db->query($query) or die($db->error);	
		echo "Expenses database created successfuly!";
	} //creating Journal Voucher table ends.
	
	if($db->query('SELECT 1 from store_logs') == FALSE) { 
		$query = 'CREATE TABLE store_logs (
			`log_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`log_datetime` datetime NULL,
			`description` varchar(600) NULL,
			`store_id` bigint(20) NULL,
			`user_id` bigint(20) NULL,
		PRIMARY KEY (`log_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Project Logs Table created.<br>';
	}  //Creating Projects logs table ends here.
	
	if($db->query('SELECT 1 from stores') == FALSE) { 
		$query = 'CREATE TABLE stores (
			`store_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`store_manual_id` varchar(100) NULL,
			`store_name` varchar(100) NULL,
			`business_type` varchar(100) NULL,
			`address1` varchar(200) NULL,
			`address2` varchar(200) NULL,
			`city` varchar(100) NULL,
			`state` varchar(100) NULL,
			`country` varchar(100) NULL,
			`zip_code` varchar(100) NULL,
			`phone` varchar(200) NULL,
			`email` varchar(200) NULL,
			`currency` varchar(50) NULL,
			`store_logo` varchar(500) NULL,
			`description` varchar(600) NULL,
			`user_id` varchar(100) NULL,
		PRIMARY KEY (`store_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Stores Table created.<br>';
	}  //Creating users table ends here.
	
	//if database tables does not exist already create them.
	if($db->query('SELECT 1 from store_access') == FALSE) {
		$query = 'CREATE TABLE store_access (
			`access_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`user_id` bigint(20) NULL,
			`store_id` bigint(20) NULL,
			`sales` bigint(20) NULL,
			`purchase` bigint(20) NULL,
			`vendors` bigint(20) NULL,
			`clients` bigint(20) NULL,
			`products` bigint(20) NULL,
			`warehouse` bigint(20) NULL,
			`returns` bigint(20) NULL,
			`price_level` bigint(20) NULL,
			`reports` bigint(20) NULL,
			`expenses` bigint(20) NULL,
  			PRIMARY KEY (`access_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Store Access Table created.<br>';
	} //creating user level table ends.
	
	if($db->query('SELECT 1 from message_meta') == FALSE) { 
		$query = 'CREATE TABLE message_meta (
			`msg_meta_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`message_id` bigint(20) NULL,
			`status` varchar(100) NULL,
			`from_id` bigint(20) NULL,
			`to_id` bigint(20) NULL,
  			`subject_id` bigint(20) NULL,
			PRIMARY KEY (`msg_meta_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Message Meta Table created.<br>';
	}  //Creating user notes table ends here.
	
	if($db->query('SELECT 1 from messages') == FALSE) { 
		$query = 'CREATE TABLE messages (
			`message_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`message_datetime` datetime NULL,
			`message_detail` varchar(1000) NULL,
			PRIMARY KEY (`message_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Messages Table created.<br>';
	}  //Creating user notes table ends here.
	
	if($db->query('SELECT 1 from subjects') == FALSE) { 
		$query = 'CREATE TABLE subjects (
			`subject_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`subject_title` varchar(600) NULL,
  			PRIMARY KEY (`subject_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Subjects Table created.<br>';
	}  //Creating user notes table ends here.
	
	if($db->query('SELECT 1 from notes') == FALSE) { 
		$query = 'CREATE TABLE notes (
			`note_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`note_date` date NULL,
			`note_title` varchar(200) NULL,
			`note_detail` varchar(600) NULL,
			`user_id` bigint(20) NULL,
  			PRIMARY KEY (`note_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Notes Table created.<br>';
	}  //Creating user notes table ends here.
	
	if($db->query('SELECT 1 from announcements') == FALSE) { 
		$query = 'CREATE TABLE announcements (
			`announcement_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`announcement_date` date NULL,
			`announcement_title` varchar(200) NULL,
			`announcement_detail` varchar(1000) NULL,
			`user_type` varchar(100) NULL,
			`announcement_status` varchar(50) NULL,
  			PRIMARY KEY (`announcement_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Notes Table created.<br>';
	}  //Creating user notes table ends here.
	
	//if database tables does not exist already create them.
	if($db->query('SELECT 1 from options') == FALSE) {
		$query = 'CREATE TABLE options (
			`option_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`option_name` varchar(500) NULL,
			`option_value` varchar(500) NULL,
  			PRIMARY KEY (`option_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Options Table created.<br>';
	} //creating options table.
	
	if($db->query('SELECT 1 from users') == FALSE) { 
		$query = 'CREATE TABLE users (
			`user_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`first_name` varchar(100) NULL,
			`last_name` varchar(100) NULL,
			`gender` varchar(50) NULL,
			`date_of_birth` date NULL,
			`address1` varchar(200) NULL,
			`address2` varchar(200) NULL,
			`city` varchar(100) NULL,
			`state` varchar(100) NULL,
			`country` varchar(100) NULL,
			`zip_code` varchar(100) NULL,
			`mobile` varchar(200) NULL,
			`phone` varchar(200) NULL,
			`username` varchar(100) NULL,
			`email` varchar(200) NULL,
			`password` varchar(200) NULL,
			`profile_image` varchar(500) NULL,
			`description` varchar(600) NULL,
			`status` varchar(100) NULL,
			`activation_key` varchar(100) NULL,
			`date_register` date NULL,
			`user_type` varchar(100) NULL,
  			PRIMARY KEY (`user_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Users Table created.<br>';
	}  //Creating users table ends here.
	
	//if database tables does not exist already create them.
	if($db->query('SELECT 1 from user_level') == FALSE) {
		$query = 'CREATE TABLE user_level (
			`level_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`level_name` varchar(200) NULL,
			`level_description` varchar(600) NULL,
			`level_page` varchar(100) NULL,
  			PRIMARY KEY (`level_id`)
		)';	
		$result = $db->query($query) or die($db->error);
		echo 'Options Table created.<br>';
	} //creating user level table ends.