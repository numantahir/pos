<?php
	include('../system_load.php');
	//This loads system.
	//user Authentication.
	authenticate_user('subscriber');
	//creating company object.
	$store_access = new StoreAccess;
	$new_store = new Store;
	$client = new Client;
		
	if(partial_access('admin') || $store_access->have_module_access('reports')) {} else { 
		HEADER('LOCATION: ../store.php?message=products');
	}
	if(!isset($_SESSION['store_id']) || $_SESSION['store_id'] == '') { 
		HEADER('LOCATION: ../stores.php?message=1');
	} //select company redirect ends here.
?>	
<html>
	<head>
    	<title>Sale invoice</title>
        <link rel="stylesheet" type="text/css" media="all" href="reports.css" />
    </head>
    
    <body>
    	<div id="reportContainer" style="width:600px;">
			<!--store Head Start here.-->
            <h2 align="center"><?php echo $new_store->get_store_info($_SESSION['store_id'], 'store_name'); ?></h2>
            <h3 align="center">Customers Balance Summary</h3>
            <h4 align="center">All Transactions</h4>
            <!--stor_head Ends here.-->
            <h4 align="right">Date: <?php echo date('d-M-Y'); ?></h4>
            
            <table width="100%" align="center" cellpadding="5" cellspacing="0">
            	<tr style="background-color:#CCC;">
                	<th>Full Name</th>
                    <th>Business Title</th>
                    <th>Balance</th>
                </tr>
                <?php $client->customers_balance_summary(); ?>
            </table>
            
		</div><!--reportContainer Ends here.-->
    </body>
</html>