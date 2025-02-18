<?php
	include('system_load.php');
	//This loads system.
	//user Authentication.
	authenticate_user('subscriber');
	//creating company object.
	$new_store = new Store;
	$store_access = new StoreAccess;
	$sale = new Sale;
		
	if(partial_access('admin') || $store_access->have_module_access('sales')) {} else { 
		HEADER('LOCATION: store.php?message=warehouse');
	}
	
	if(!isset($_SESSION['store_id']) || $_SESSION['store_id'] == '') { 
		HEADER('LOCATION: stores.php?message=1');
	} //select company redirect ends here.
	
	//delete store if exist.
	if(isset($_POST['delete_sale']) && $_POST['delete_sale'] != '') { 
		$message = $sale->delete_sale($_POST['delete_sale']);
	}//delete account.
	
	$new_store->set_store($_SESSION['store_id']); //setting store.
	 
	$page_title = 'Sales'; //You can edit this to change your page title.
	require_once("includes/header.php"); //including header file.
	?>

	<?php
    //display message if exist.
        if(isset($message) && $message != '') { 
            echo '<div class="alert alert-success">';
            echo $message;
            echo '</div>';
        }
    ?>
	<!--content here-->
    <!--<p>
	    <a href="manage_sale.php" class="btn btn-primary btn-default">Add New</a>
    </p>-->
    
    <table cellpadding="0" cellspacing="0" border="0" class="table-responsive table-hover table display table-bordered" id="wc_table" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Agent</th>
                <th>Client</th>
                <th>Take / Table</th>
                <!--<th>Memo</th>-->
                <th>Type</th>
                <th>Items</th>
                <th>Receiveable</th>
                <th>Received</th>
                <th>View</th>
            </tr>
        </thead>
        <tbody>
			<?php $sale->list_all_sales_credit(); ?>
        </tbody>
    </table> 
    <!--content Ends here.-->

<?php
	require_once("includes/footer.php");
?>