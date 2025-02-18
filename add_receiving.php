<?php
	include('system_load.php');
	//This loads system.
	//user Authentication.
	authenticate_user('subscriber');
	//creating company object.
	$new_store = new Store;
	$store_access = new StoreAccess;
	$client = new Client;
	
	if(partial_access('admin') || $store_access->have_module_access('clients')) {} else { 
		HEADER('LOCATION: store.php?message=warehouse');
	}
	
	if(!isset($_SESSION['store_id']) || $_SESSION['store_id'] == '') { 
		HEADER('LOCATION: stores.php?message=1');
	} //select company redirect ends here.
	$new_store->set_store($_SESSION['store_id']); //setting store.
	 
	if(isset($_POST['add_receiving']) && $_POST['add_receiving'] == 1) { 
		extract($_POST);
		if($method == '') { 
			$message = 'Set receiving method';
		} else if($amount == '' || !is_numeric($amount)) { 
			$message = "Amount is empty or not numeric.";
		} else { 
			//Process Payment here.
			$receiving_id = $client->add_receiving($date, $method, $ref_no, $memo, $amount, $client_id);
			$client->add_log($date, $client_id, 'Receiving', $receiving_id);
			$clear_creditors = $client->clear_creditors($amount, $client_id);
			HEADER('LOCATION: add_receiving.php?message=Receiving added successfuly!&client_id='.$_GET['client_id']);
		}			
	}
	$page_title = 'Add Receiving'; //You can edit this to change your page title.
	require_once("includes/header.php"); //including header file.
	
	//display message if exist.
        if(isset($message) && $message != '') { 
            echo '<div class="alert alert-success">';
            echo $message;
            echo '</div>';
        }
		if(isset($_GET['message']) && $_GET['message'] != '') { 
            echo '<div class="alert alert-success">';
            echo $_GET['message'];
            echo '</div>';
        }
    ?>
	<!--content here-->

<?php if(!isset($_GET['client_id']) || $_GET['client_id'] == '0'): ?>
	<!--when vendor is not set-->
	<h3>Please select Client</h3>
	<form action="" method="get">
    	<div class="form-group">
        	<label class="label-control">Select Client:</label><br />
            <select class="autofill" style="width:250px;" name="client_id">
            	<option value="0">Select Client</option>
                <?=$client->client_options(); ?>
            </select>
        </div>
        <input type="submit" class="btn btn-primary" value="Select" />
    </form>
	<!--when vendor is not set-->
<?php else: ?>
<!--when vendor is set.-->
    <div class="row">
    	<div class="col-md-4">
        	<!--vendor info-->
            <table width="100%" border="1px" cellspacing="0" cellpadding="5px">
				<tr>
    				<td bgcolor="#666666"><strong style="color:#FFF;">Client Info</strong></td>
    			</tr>
    			<tr>
    				<td><?php $client_id = $_GET['client_id']; ?>
    					<p><strong><?php echo $client->get_client_info($client_id, 'full_name'); ?></strong><br />
        				Phone # : <?php echo $client->get_client_info($client_id, 'phone'); ?> Mob # : <?php echo $client->get_client_info($client_id, 'mobile'); ?><br />
						Address: <?php echo $client->get_client_info($client_id, 'address'); ?> <?php echo $client->get_client_info($client_id, 'city'); ?> <?php echo $client->get_client_info($client_id, 'state'); ?> <?php echo $client->get_client_info($client_id, 'country'); ?><br>
        				<span style="text-align:right; background-color:#CCC; font-weight:bold; padding:2px; width:80%; float:right;">Total Payable: <?php echo currency_format($client->get_client_balance($client_id)); ?></span></p></td>
			    </tr>
			</table>
            <!--vendor info-->
        </div>
        <div class="col-md-4">
        	<table border="1px" width="100%" cellspacing="0" cellpadding="5px">
				<tr>
    				<td bgcolor="#666666"><strong style="color:#FFF;">Add Receiving</strong></td>
    			</tr>
    			<tr>
                	<td>
                    	<form action="" method="post">
                    	<table border="0" cellpadding="5">
						<tr>
                            <th>Date:</th>
                            <td width="270"><input type="text" class="form-control datepick" readonly="readonly" value="<?php echo date('Y-m-d'); ?>" required="required" name="date" /></td>				
                         </tr>
                		<tr>
                            <th>Method</th>
                            <td><input type="text" class="form-control" placeholder="Receiving Method" required="required" name="method" /></td>				
                        </tr>
                		<tr>
                            <th>Ref#:</th>
                            <td><input type="text" class="form-control" placeholder="Reference Number" name="ref_no" /></td>										
                       </tr>
                		<tr>
                            <th>Memo:</th>
                            <td><textarea class="form-control" name="memo" placeholder="Memo"></textarea></td>				
                        </tr>
                		
                        <tr>
                            <th>Amount:</th>
                            <td><input type="text" class="form-control" placeholder="Amount Received" required="required" name="amount" /></td>				
                        </tr>
                            
                        <tr>
                            <th>&nbsp;</th>
                            <td>
                            <input type="hidden" name="add_receiving" value="1" />
                            <input type="hidden" name="client_id" value="<?php echo $_GET['client_id']; ?>" />
                            <input type="submit" class="btn btn-primary" value="Add Receiving" /></td>
                        </tr>    
                        </table>
                        </form>
                	</td>
				</tr>
			</table>
        </div>
        <div class="clearfix"></div>
    </div>
<!--when vendor is set.-->
<?php endif; ?>

<?php
	require_once("includes/footer.php");
?>