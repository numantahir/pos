<?php
	include('system_load.php');
	//This loads system.
	//user Authentication.
	authenticate_user('subscriber');
	//creating company object.
	$new_store = new Store;
	$store_access = new StoreAccess;
	$vendor = new Vendor;
	
	if(partial_access('admin') || $store_access->have_module_access('vendors')) {} else { 
		HEADER('LOCATION: store.php?message=warehouse');
	}
	
	if(!isset($_SESSION['store_id']) || $_SESSION['store_id'] == '') { 
		HEADER('LOCATION: stores.php?message=1');
	} //select company redirect ends here.
	$new_store->set_store($_SESSION['store_id']); //setting store.
	 
	if(isset($_POST['add_payment']) && $_POST['add_payment'] == 1) { 
		extract($_POST);
		if($method == '') { 
			$message = 'Set payment method';
		} else if($amount == '' || !is_numeric($amount)) { 
			$message = "Amount is empty or not numeric.";
		} else { 
			//Process Payment here.
			$payment_id = $vendor->add_payment($date, $method, $ref_no, $memo, $amount, $vendor_id);
			$vendor->add_log($date, $vendor_id, 'Payment', $payment_id);
			$clear_debts = $vendor->clear_debts($amount, $vendor_id);
			HEADER('LOCATION: add_payment.php?message=Payment added successfuly!&vendor_id='.$_GET['vendor_id']);
		}			
	}
	$page_title = 'Add Payment'; //You can edit this to change your page title.
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

<?php if(!isset($_GET['vendor_id']) || $_GET['vendor_id'] == '0'): ?>
	<!--when vendor is not set-->
	<h3>Please select Vendor</h3>
	<form action="" method="get">
    	<div class="form-group">
        	<label class="label-control">Select Vendor:</label><br />
            <select class="autofill" style="width:250px;" name="vendor_id">
            	<option value="0">Select Vendor</option>
                <?php echo $vendor->vendor_options(); ?>
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
    				<td bgcolor="#666666"><strong style="color:#FFF;">Vendor Info</strong></td>
    			</tr>
    			<tr>
    				<td><?php $vendor_id = $_GET['vendor_id']; ?>
    					<p><strong><?php echo $vendor->get_vendor_info($vendor_id, 'full_name'); ?></strong><br />
        				Phone # : <?php echo $vendor->get_vendor_info($vendor_id, 'phone'); ?> Mob # : <?php echo $vendor->get_vendor_info($vendor_id, 'mobile'); ?><br />
						Address: <?php echo $vendor->get_vendor_info($vendor_id, 'address'); ?> <?php echo $vendor->get_vendor_info($vendor_id, 'city'); ?> <?php echo $vendor->get_vendor_info($vendor_id, 'state'); ?> <?php echo $vendor->get_vendor_info($vendor_id, 'country'); ?><br>
        				<span style="text-align:right; background-color:#CCC; font-weight:bold; padding:2px; width:80%; float:right;">Total Payable: <?php echo currency_format($vendor->get_vendor_balance($vendor_id)); ?></span></p></td>
			    </tr>
			</table>
            <!--vendor info-->
        </div>
        <div class="col-md-4">
        	<table border="1px" width="100%" cellspacing="0" cellpadding="5px">
				<tr>
    				<td bgcolor="#666666"><strong style="color:#FFF;">Add Payment</strong></td>
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
                            <td><input type="text" class="form-control" placeholder="Payment Method" required="required" name="method" /></td>				
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
                            <td><input type="text" class="form-control" placeholder="Amount Paid" required="required" name="amount" /></td>				
                        </tr>
                            
                        <tr>
                            <th>&nbsp;</th>
                            <td>
                            <input type="hidden" name="add_payment" value="1" />
                            <input type="hidden" name="vendor_id" value="<?php echo $_GET['vendor_id']; ?>" />
                            <input type="submit" class="btn btn-primary" value="Add Payment" /></td>
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