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
    	<title>Customer Ledger Summary	</title>
        
<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css" media="all" />
<link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.min.css" media="all" />
<link rel="stylesheet" type="text/css" href="../css/style.css" media="all" />
<link rel="stylesheet" type="text/css" href="../css/select2.css" media="all" />
<link rel="stylesheet" type="text/css" href="../css/ui-lightness/jquery-ui-1.10.3.custom.min.css" media="all" />
<link rel="stylesheet" type="text/css" media="all" href="reports.css" />

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="../js/bootstrap.min.js"></script>
<script type="text/javascript" language="javascript" src="../js/select2.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>

 	<script type="text/javascript">
	$(document).ready(function() {$(".autofill").select2(); });
	
	$(function() {
		$(".datepick").datepicker({
			inline: true,
			dateFormat: 'yy-mm-dd',
		});
	});	
	</script>
    </head>
    
    <body>
    	<div id="reportContainer" style="width:800px;">
        	<?php if(!isset($_GET['client_id']) || $_GET['client_id'] == ''): ?>
				<!--when vendor is not set-->
                <h1>Customer Ledger Summary</h1>
                <h3>Please select Client</h3>
                <form action="" method="get">
                    <div class="form-group">
                        <label class="label-control">Select Client:</label><br />
                        <select class="autofill" style="width:250px;" name="client_id">
                            <option value="0">Select Client</option>
                            <?php echo $client->client_options(); ?>
                        </select>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Select" />
                </form>
                <!--when vendor is not set-->
			<?php else: ?>
			<!--store Head Start here.-->
            <?php $client_id = $_GET['client_id']; ?>
            <h2 align="center"><?php echo $new_store->get_store_info($_SESSION['store_id'], 'store_name'); ?></h2>
            <h3 align="center"><?php echo $client->get_client_info($client_id, 'full_name'); ?> Customer Ledger Summary</h3>
            <p align="center">Phone # : <?php echo $client->get_client_info($client_id, 'phone'); ?> Mob # : <?php echo $client->get_client_info($client_id, 'mobile'); ?><br />
		Address: <?php echo $client->get_client_info($client_id, 'address'); ?> <?php echo $client->get_client_info($client_id, 'city'); ?> <?php echo $client->get_client_info($client_id, 'state'); ?> <?php echo $client->get_client_info($client_id, 'country'); ?></p>
            <!--stor_head Ends here.-->
            <h4 align="right">Date: <?php echo date('d-M-Y'); ?></h4>
            
            <table width="100%" align="center" cellpadding="5" cellspacing="0">
            	<tr style="background-color:#CCC;">
                	<th>Type</th>
                    <th>Date</th>
                    <th>Num</th>
                    <th>Memo</th>
                    <th>Amount</th>
                    <th>Balance</th>
                </tr>
                <?php $client->customer_ledger_summary($client_id); ?>
            </table>
            <?php endif; ?>
		</div><!--reportContainer Ends here.-->
    </body>
</html>