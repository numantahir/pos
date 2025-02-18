<?php
	include('system_load.php');
	//This loads system.
	//user Authentication.
	authenticate_user('subscriber');
	//creating company object.
	
	if(partial_access('admin') || $store_access->have_module_access('expenses')) {} else { 
		HEADER('LOCATION: store.php?message=products');
	}
	
	if(!isset($_SESSION['store_id']) || $_SESSION['store_id'] == '') { 
		HEADER('LOCATION: stores.php?message=1');
	} //select company redirect ends here.
	
	//Delete user level.
	if(isset($_GET['expense_id']) && $_GET['expense_id'] != '') { 
		if(partial_access('admin')) :
		$message = $expenses->delete_expense($_GET['expense_id']);
		else:
		$message = "You are trying to do something you are not allowed for.";
		endif;
	}//delete level ends here.
	
	$new_store->set_store($_SESSION['store_id']); //setting store.
	 
	$page_title = 'Expense Types'; //You can edit this to change your page title.
	require_once("includes/header.php"); //including header file.

    //display message if exist.
        if((isset($message) && $message != '') || (isset($_GET['message']) && $_GET['message'] != '')) { 
            echo '<div class="alert alert-success">';
            if(isset($message)): echo $message; else: echo $_GET['message']; endif;
			echo '</div>';
        }
    ?>
	<!--content here-->
    <p>
	    <a href="manage_expenses.php" class="btn btn-primary btn-default">Add New</a>
    </p>
    <table cellpadding="0" cellspacing="0" border="0" class="table-responsive table-hover table display table-bordered" id="wc_table" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Title</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Added by</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
			<?=$expenses->list_expenses(); ?>
        </tbody>
    </table>
    <!--content Ends here.-->
	<script type="text/javascript">
    	$('.delete').click(function(){ 
			return confirm_delete();
		});
    </script>
<?php
	require_once("includes/footer.php");
?>