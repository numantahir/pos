<?php

//Update script to 2.1 Version.

if(get_option('version') == '' || get_option('version') < '2.1') :
	global $db; //creating database object.
	
	//Updating Vendors Table.
	$update_vendor = "ALTER TABLE `vendors` ADD `zipcode` varchar(100) NOT NULL AFTER `state`";
	$result = $db->query($update_vendor) or die($db->error);
	echo "Zip code for vendor was added!";
	//Update Vendor Table ends here.
	
	//Update clients Table.
	$update_client = "ALTER TABLE `clients` ADD `zipcode` varchar(100) NOT NULL AFTER `state`";
	$result = $db->query($update_client) or die($db->error);
	echo "Zip code for client was added!";
	
	//Update clients Table.
	$update_client = "ALTER TABLE `store_access` ADD `expenses` varchar(100) NOT NULL AFTER `reports`";
	$result = $db->query($update_client) or die($db->error);
	
	set_option('version', '2.2');
	$message = "System Updated successfully!";
endif; 