<?php
	include('system_load.php');
	//This loads system.
	
	//user Authentication.
	authenticate_user('subscriber');
	//creating store object.
	
	if(isset($_GET['message']) && $_GET['message'] != '') { 
		$message = 'Please select your store.';
	}//Message ends here select store
	
	//delete store if exist.
	if(isset($_POST['delete_store']) && $_POST['delete_store'] != '') { 
		$message = $new_store->delete_store($_POST['delete_store']);
	}//delete account.
		
	$page_title = "Stores"; //You can edit this to change your page title.
	require_once("includes/header.php"); //including header file.

    //display message if exist.
        if(isset($message) && $message != '') { 
            echo '<div class="alert alert-success">';
            echo $message;
            echo '</div>';
        }
    ?>
    <?php /*if(partial_access('admin')) { ?><p>
	    <a href="manage_store.php" class="btn btn-primary btn-default">Add New</a>
    </p><?php } */ ?>

    <table cellpadding="0" cellspacing="0" border="0" class="table-responsive table-hover table display table-bordered" id="wc_table" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>City</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Currency</th>
                <th>Logo</th>
                <th>Select</th>
                <?php if(partial_access('admin')) { ?><th>Edit</th>
                <th>Delete</th><?php } ?>
            </tr>
        </thead>
        <tbody>
           <?php echo $new_store->list_stores(); ?>
        </tbody>
    </table>
                        
<?php
	require_once("includes/footer.php");
?>