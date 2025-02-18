<?php
	include('system_load.php');
	//This loads system.

	//user Authentication.
	authenticate_user('admin');
	//creating store object.

	if(isset($_POST['user_id']) && isset($_POST['store_id'])) { 
		if($_POST['user_id'] == '' && $_POST['store_id'] == '') { 
			$message = 'Store id and user id required. Please select.';
		} else { 
			$message =  $store_access->add_store_access($_POST['user_id'], $_POST['store_id'], $_POST['access_to']);
		}
	}//add store access ends here.
	//delete access
	if(isset($_POST['delete_access']) && $_POST['delete_access'] != '') { 
		$message = $store_access->delete_access($_POST['delete_access']);
	}
	//delete access ends here.	
	$page_title = "Manage Stores Access"; //You can edit this to change your page title.
	require_once("includes/header.php"); //including header file.
    	
	//display message if exist.
	if(isset($message) && $message != '') { 
		echo '<div class="alert alert-success">';
		echo $message;
		echo '</div>';
	}
     ?>

                    <h3>Grant Access</h3>
                    <form name="grand_access" id="grand_access" action="" method="post">
                    <table cellpadding="10" style="padding:10px;" border="0">
                    	<tr>
                        	<th style="padding:10px;">Select User</th>
                            <th style="padding:10px;">Select Store</th>
                        </tr>
                        <tr>
                        	<td style="padding:10px;">
                            	<select class="form-control" name="user_id" required="required">
                                	<option value="">Select User</option>
                                    <?php $new_user->subscriber_options(); ?>
                                </select>
                            </td>
                            <td style="padding:10px;">
                            	<select class="form-control" name="store_id" required="required">
                                	<option value="">Select Store</option>
                                    <?php $new_store->store_options(); ?>
                                </select>
                            </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding:10px;">
                                <strong>Access To: </strong>
                                    <input type="checkbox" name="access_to[]" value="sales" /> Sales &nbsp;
                                    <input type="checkbox" name="access_to[]" value="purchase" /> Purchase &nbsp;
                                    <input type="checkbox" name="access_to[]" value="vendors" /> Vendors &nbsp;
                                    <input type="checkbox" name="access_to[]" value="clients" /> Clients &nbsp;
                                    <input type="checkbox" name="access_to[]" value="products" /> Products &nbsp;
                                    <input type="checkbox" name="access_to[]" value="warehouse" /> Warehouse &nbsp;
                                    <input type="checkbox" name="access_to[]" value="returns" /> Returns &nbsp;
                                    <input type="checkbox" name="access_to[]" value="price_level" /> Price Level &nbsp;
                                    <input type="checkbox" name="access_to[]" value="reports" /> Reports
                                    <input type="checkbox" name="access_to[]" value="expenses" /> Expenses
                                </td>
                            </tr>
                            <tr>
                            	<td style="padding:10px;"><input type="submit" class="btn btn-primary btn-sm" value="Grant Access" /></td>
                                <td>&nbsp;</td>
                            </tr>
                        </tr>
                    </table>
                    </form>
                    <br />
					<br />
					<table cellpadding="0" cellspacing="0" border="0" class="table-responsive table-hover table display table-bordered" id="wc_table" width="100%">	
                        <thead>
                            <tr>
                                <th>User&nbsp;Id</th>
                                <th>User&nbsp;Name</th>
                                <th>Email</th>
                                <th>Store&nbsp;Access</th>
                                <th>Modules</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
							<?php $store_access->list_store_access(); ?>
                        </tbody>
                    </table>
                  <script type="text/javascript">
						$(document).ready(function() {
						// validate the register form
					$("#grand_access").validate();
						});
                    </script>
<?php
	require_once("includes/footer.php");
?>