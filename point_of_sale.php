<?php
	include('system_load.php');
	//This loads system.	
	$GetSaleIDForBill = trim($_GET["si"]);
	if($_POST["post_from"]=="pos" && $_POST["authby"]=="num" && trim($_POST["old_sale_id"]) == ""){
	//	print_r($_POST);
	//	die();
	$client_id = $_POST["client_id"];
	$product_id = $_POST["product_id"];
	$qty = $_POST["qty"];
	$cost = $_POST["cost"];
	$payment_method = $_POST["payment_method"];
	$date = $_POST["date"];
	$custom_inv_no = $_POST["custom_inv_no"];
	$memo = $_POST["memo"];
	$selling_price = $_POST["selling_price"];
	$tax_rate = $_POST["tax_rate"];
	$warehouse_id = $_POST["warehouse_id"];
	$cash_received = $_POST["cash_received"];
	$table_id = $_POST["table_id"];
	$to_auto = $_POST["to_auto"];
	$sale_type = $_POST["sale_type"];
	$save	= $_POST["save"];
	$print	= $_POST["print"];
		
	if($client_id == '') { 
		header('location:manage_sale.php?message=Please select vendor.');
		exit();
	}
	
	if(!isset($product_id)) { 
		header('location:manage_sale.php?message=Please enter at least 1 product to process invoice.');
		exit();
	}
	
	if(isset($qty)) {
		$error = 0; 
		foreach($qty as $qt) { 
			if($qt == '' || $qt == '0' || !is_numeric($qt)) { 
				$error = 1;
			} 
		}//foreach 
		if($error == 1) { 
			header('location:manage_sale.php?message=Quantity and price should not less than or equal to zero and should be a number.');
			exit();
		}
	}
	
	if(isset($cost)) {
		$error = 0; 
		foreach($cost as $qt) { 
			if($qt == '' || $qt == '0' || !is_numeric($qt)) { 
				$error = 1;
			} 
		}//foreach 
		if($error == 1) { 
			header('location:manage_sale.php?message=Quantity and price should not less than or equal to zero and should be a number.');
			exit();
		}
	}
	
	if($payment_method == '' || $payment_method == '0') { 
		header('location:manage_sale.php?message=Please select a payment method.');
		exit();
	}
	//forms validation ends here.
	
	//form processing starts here.
	//echo '-1-';
	$sale_id = $sale->add_sale($date, $custom_inv_no, $memo, $client_id, $payment_method,$table_id);
	//add purchase ends here.
	if($payment_method == 'cash') { 
		$transaction_type = 'Cash Sale';
	} else if($payment_method == 'credit_card') {
		$transaction_type = 'Credit Card Sale';	
	} else { 
		$transaction_type = 'Sale Invoice';
	}
	$client_log_id = $client->add_log($date, $client_id, $transaction_type, $sale_id);
	
	$grand_total = 0;
	foreach($qty as $index => $qt) { 
		$total = $qt*$selling_price[$index];
		$tax = $qt*$tax_rate[$index];
		$grand_total = $total+$grand_total+$tax;
	}
	//echo '-2-';
	if($payment_method == 'cash') { 
		$receiving_id = $client->add_receiving($date, 'Cash Sale', $sale_id, $memo, $grand_total, $client_id, $cash_received, $table_id, $tax);
		$client_log_id = $client->add_log($date, $client_id, 'Sale Receiving', $receiving_id);
	}
	if($payment_method == 'credit_card') { 
		$receiving_id = $client->add_receiving($date, 'Credit Card Sale', $sale_id, $memo, $grand_total, $client_id, $cash_received, $table_id, $tax);
		$client_log_id = $client->add_log($date, $client_id, 'Sale Receiving', $receiving_id);
	}
	//start here adding products ----------------------- <<<<
	foreach($qty as $index => $qt) {
		$product_id_in = $product_id[$index];
		$quantity = $qt;
		$price_in = $selling_price[$index];
		$tax_in = $tax_rate[$index];
		$warehouse_id_in = $warehouse_id[$index];
		
		if($payment_method == 'cash' || $payment_method == 'credit_card') { 
			$received = $price_in*$quantity+$quantity*$tax_in;
		} else { 
			$received = 0;
		}
		$total_credit = $price_in*$quantity+$tax_in*$quantity;
		$creditor_id = $sale->add_creditor($total_credit, $received, $client_id);		
		//debt add ends here.
		$inventory_id = $sale->add_inventory('0', $quantity, $product_id_in, $warehouse_id_in);
		//add inventory ends here.
		$price_id = $sale->add_price('0', $price_in, $tax_in, $product_id_in);
		//add price ends here.
		$purchase_detail_id = $sale->add_sale_detail($sale_id, $price_id, $inventory_id, $creditor_id);
	} //processing details
	//echo '-3-';
	if(isset($sale_type) && $sale_type == "pos_sale") { 
		//echo '-4-';
		if($payment_method == 'credit') { 
		//echo '-5-';
			//header('location:temp.php?message=Sale was saved successfuly.');
echo "<script type='text/javascript'>window.open('reports/view_pos_sale_invoice.php?sale_id=".$sale_id."', '_blank');</script>";
			
		} else if($payment_method != 'credit'){ 
		//echo '-6-';
			//header('location:temp.php?message=Sale was saved successfuly.&sale_id='.$sale_id);	
			echo "<script type='text/javascript'>window.open('reports/view_pos_sale_invoice.php?sale_id=".$sale_id."', '_blank');</script>";
		}	
	} else { 
		if($payment_method == 'credit') { 
			header('location:manage_sale.php?message=Sale was saved successfuly.');
		} else if($payment_method != 'credit'){ 
			//header('location:manage_sale.php?message=Sale was saved successfuly.&sale_id='.$sale_id);	
			echo "<script type='text/javascript'>window.open('reports/view_pos_sale_invoice.php?sale_id=".$sale_id."', '_blank');</script>";
		}
	}
		
		
		
		
		
		
		
		
		
		
	}
	
	if($_POST["post_from"]=="pos" && $_POST["authby"]=="num" && trim($_POST["old_sale_id"]) != ""){
	//	print_r($_POST);
	//	die();
	$client_id = $_POST["client_id"];
	$product_id = $_POST["product_id"];
	$qty = $_POST["qty"];
	$cost = $_POST["cost"];
	$payment_method = $_POST["payment_method"];
	$date = $_POST["date"];
	$custom_inv_no = $_POST["custom_inv_no"];
	$memo = $_POST["memo"];
	$selling_price = $_POST["selling_price"];
	$tax_rate = $_POST["tax_rate"];
	$warehouse_id = $_POST["warehouse_id"];
	$cash_received = $_POST["cash_received"];
	$table_id = $_POST["table_id"];
	$to_auto = $_POST["to_auto"];
	$sale_type = $_POST["sale_type"];
	$save	= $_POST["save"];
	$print	= $_POST["print"];
	$sale_id = trim($_POST["old_sale_id"]);
		
	if($client_id == '') { 
		header('location:manage_sale.php?message=Please select vendor.');
		exit();
	}
	
	if(!isset($product_id)) { 
		header('location:manage_sale.php?message=Please enter at least 1 product to process invoice.');
		exit();
	}
	
	if(isset($qty)) {
		$error = 0; 
		foreach($qty as $qt) { 
			if($qt == '' || $qt == '0' || !is_numeric($qt)) { 
				$error = 1;
			} 
		}//foreach 
		if($error == 1) { 
			header('location:manage_sale.php?message=Quantity and price should not less than or equal to zero and should be a number.');
			exit();
		}
	}
	
	if(isset($cost)) {
		$error = 0; 
		foreach($cost as $qt) { 
			if($qt == '' || $qt == '0' || !is_numeric($qt)) { 
				$error = 1;
			} 
		}//foreach 
		if($error == 1) { 
			header('location:manage_sale.php?message=Quantity and price should not less than or equal to zero and should be a number.');
			exit();
		}
	}
	
	if($payment_method == '' || $payment_method == '0') { 
		header('location:manage_sale.php?message=Please select a payment method.');
		exit();
	}
	
	$sale->update_payment_methord($payment_method, $sale_id);
	//Delete All OLD Record
	$GetDeleteSaleStatus = $sale->delete_this_sale($sale_id);
	if($GetDeleteSaleStatus == 'Done'){
	
	
	//add purchase ends here.
	if($payment_method == 'cash') { 
		$transaction_type = 'Cash Sale';
	} else if($payment_method == 'credit_card') {
		$transaction_type = 'Credit Card Sale';	
	} else { 
		$transaction_type = 'Sale Invoice';
	}
	$client_log_id = $client->add_log($date, $client_id, $transaction_type, $sale_id);
	
	$grand_total = 0;
	foreach($qty as $index => $qt) { 
		$total = $qt*$selling_price[$index];
		$tax = $qt*$tax_rate[$index];
		$grand_total = $total+$grand_total+$tax;
	}
	//echo '-2-';
	if($payment_method == 'cash') { 
		$receiving_id = $client->add_receiving($date, 'Cash Sale', $sale_id, $memo, $grand_total, $client_id, $cash_received, $table_id, $tax);
		$client_log_id = $client->add_log($date, $client_id, 'Sale Receiving', $receiving_id);
	}
	if($payment_method == 'credit_card') { 
		$receiving_id = $client->add_receiving($date, 'Credit Card Sale', $sale_id, $memo, $grand_total, $client_id, $cash_received, $table_id, $tax);
		$client_log_id = $client->add_log($date, $client_id, 'Sale Receiving', $receiving_id);
	}
	//start here adding products ----------------------- <<<<
	foreach($qty as $index => $qt) {
		$product_id_in = $product_id[$index];
		$quantity = $qt;
		$price_in = $selling_price[$index];
		$tax_in = $tax_rate[$index];
		$warehouse_id_in = $warehouse_id[$index];
		
		if($payment_method == 'cash' || $payment_method == 'credit_card') { 
			$received = $price_in*$quantity+$quantity*$tax_in;
		} else { 
			$received = 0;
		}
		$total_credit = $price_in*$quantity+$tax_in*$quantity;
		$creditor_id = $sale->add_creditor($total_credit, $received, $client_id);		
		//debt add ends here.
		$inventory_id = $sale->add_inventory('0', $quantity, $product_id_in, $warehouse_id_in);
		//add inventory ends here.
		$price_id = $sale->add_price('0', $price_in, $tax_in, $product_id_in);
		//add price ends here.
		$purchase_detail_id = $sale->add_sale_detail($sale_id, $price_id, $inventory_id, $creditor_id);
	} //processing details
	//echo '-3-';
	if(isset($sale_type) && $sale_type == "pos_sale") { 
		//echo '-4-';
		if($payment_method == 'credit') { 
		//echo '-5-';
			header('location:temp.php?message=Sale was saved successfuly.');
			
		} else if($payment_method != 'credit'){ 
		//echo '-6-';
			//header('location:temp.php?message=Sale was saved successfuly.&sale_id='.$sale_id);	
			echo "<script type='text/javascript'>window.open('reports/view_pos_sale_invoice.php?sale_id=".$sale_id."', '_blank'); window.location = 'temp.php?clear=yes';</script>";
		}	
	} else { 
		if($payment_method == 'credit') { 
			header('location:manage_sale.php?message=Sale was saved successfuly.');
		} else if($payment_method != 'credit'){ 
			//header('location:manage_sale.php?message=Sale was saved successfuly.&sale_id='.$sale_id);	
			echo "<script type='text/javascript'>window.open('reports/view_pos_sale_invoice.php?sale_id=".$sale_id."', '_blank'); window.location = 'temp.php?clear=yes';</script>";
		}
	}
	
	}
		
		
	}
	//user Authentication.
	authenticate_user('subscriber');
	//creating company object.
		
	if(partial_access('admin') || $store_access->have_module_access('sales')) {} else { 
		HEADER('LOCATION: store.php?message=products');
		exit();
	}

	$page_title = "Point Of Sale"; //You can edit this to change your page title.
	$page = "pos";
	
	if(get_option($_SESSION['store_id'].'_default_warehouse') == '') { 
		$message = "Please select default warehouse to access POS going to Dashboard >> Store >> <a href='pos_settings.php'>Store Settings</a> so POS can process invoices from that warehouse because there is no option to select different warehouses, if you want to use different warehouse for each product please go to Sales >> <a href='manage_sale.php'>Add new</a>.";
		HEADER("LOCATION: pos_settings.php?message=".$message);
	}
	
	require_once('includes/header.php');
	
	/*if(isset($message) && $message != '') { 
		echo "<script type='text/javaScript'>
				alert('".$_GET['message']."');
			  </script>";
	}
	if(isset($_GET['message']) && $_GET['message'] != '') { 
		echo "<script type='text/javaScript'>
				alert('".$_GET['message']."');
			  </script>";
	}*/
	
	if(isset($_GET['sale_id'])) { ?>
	<script type="text/javascript">
		//window.open('reports/view_pos_sale_invoice.php?sale_id=<?php echo $_GET['sale_id']; ?>', '_blank'); 
		//window.location = "temp.php?clear=yes";
	</script>
<?php } ?>

<link rel="stylesheet" type="text/css" href="css/pos.css" media="all" />
<script type="text/javascript">
	jQuery(function($) {
		$('form[data-async]').on('submit', function(event) {
			
			var $form = $(this);
			var $target = $($form.attr('data-target'));

			$.ajax({
				type: $form.attr('method'),
				url: 'includes/otherprocesses.php',
				data: $form.serialize(),
				dataType: 'json',
 
			success: function(response) {
				var message = response.message;
				var client_options = response.client_options;
				var client_id = response.client_id;
			
				$('#client_id').html(client_options);
				$("#client_id").select2().select2('val', client_id);
				$('#success_message').html('<div class="alert alert-success">'+message+'</div>');
			}
		});
		event.preventDefault();
	});
});
</script>

<!-- Add new vendor modal starts here. -->
<div class="modal fade" id="addnewclient" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add new client</h4>
      </div>
     	
         <div class="modal-body">
         <form data-async data-target="#addnewclient" method="POST" enctype="multipart/form-data" role="form">
         <div id="success_message"></div>
         		<table style="width:100%;">
                	<tr>
                    	<td>
                    <div class="form-group">
                        	<label class="control-label">Full Name*</label>
                            <input type="text" class="form-control" name="full_name" placeholder="Client full name" value="" required="required" />
                      </div>
                      		</td>
                            <td>
                      <div class="form-group">
                        	<label class="control-label">Business Title</label>
                            <input type="text" class="form-control" name="business_title" placeholder="Business Title" value="" />
                      </div>
                      	</td>
                        </tr>
                        <tr>
                        <td>
                      <div class="form-group">
                        	<label class="control-label">Mobile</label>
                            <input type="text" class="form-control" name="mobile" placeholder="Mobile number" value="" />
                      </div>
                      </td>
                      <td>
                      <div class="form-group">
                        	<label class="control-label">Phone</label>
                            <input type="text" class="form-control" name="phone" placeholder="Phone number" value="" />
                      </div>
                      </td>
                      </tr>
                      <tr>
                      	<td>
                      		<div class="form-group">
                        		<label class="control-label">Address</label>
                        	    <input type="text" class="form-control" name="address" placeholder="Address" value="" />
                      		</div>
                      	</td>
                      <td>
                      <div class="form-group">
                        	<label class="control-label">City</label>
                            <input type="text" class="form-control" name="city" placeholder="City" value="" />
                      </div>
                      </td>
                      </tr>
                      <tr>
                      <td>
                      <div class="form-group">
                        	<label class="control-label">State</label>
                            <input type="text" class="form-control" name="state" placeholder="State" value="" />
                      </div>
                      </td>
                      <td>
                      <div class="form-group">
                        	<label class="control-label">Zipcode</label>
                            <input type="text" class="form-control" name="zipcode" placeholder="Zip Code" value="" />
                      </div>
                      </td>
                      </tr>
                      <tr>
                      <td>
                      <div class="form-group">
                        	<label class="control-label">Country</label>
                            <input type="text" class="form-control" name="country" placeholder="Country" value="" />
                      </div>
                      </td>
                      <td>
				     <div class="form-group">
                        	<label class="control-label">Email</label>
                            <input type="text" class="form-control" name="email" placeholder="Email" value="" />
                      </div>
                      </td>
                      </tr>
                      <tr>
                      	<td>
                      <div class="form-group">
                        	<label class="control-label">Price Level</label>
                            <select name="price_level" class="form-control">
                            	<option value="default_rate">Default</option>
                                <option value="level_1">Level 1</option>
                                <option value="level_2">Level 2</option>
                                <option value="level_3">Level 3</option>
                                <option value="level_4">Level 4</option>
                                <option value="level_5">Level 5</option>
                            </select>
                      </div>
                      	</td>
                        <td>
                      <div class="form-group">
                        	<label class="control-label">Notes</label>
                            <textarea class="form-control" name="notes"></textarea>
                      </div>
                      	</td>
                        	</tr>
                      </table>	
                        <input type="hidden" name="add_client" value="1" />
                         <input type="submit" id="submit" class="btn btn-primary" value="Add client">
                      </form>   
                              </div>
      <div class="modal-footer">
      	  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--add new vendor modal ends here.-->

<script type="text/javascript">
	$('body').ready(function(){ 
		$("input[name='to_auto']").focus();
	});
	
	$("#btnSubmit").on("click", function(e){
          e.PreventDefault();
          $(this).closest("form")[0].submit();
          $(this).prop('disabled',true)
     });
</script>
    <?php
	if($_GET["si"] != ''){
	$SaleTableID = $sale->get_sale_info($_GET["si"], 'table_id'); 
	//					echo $client->GetTableInfo($SaleTableID);	
	}
	?>
  	<div class="point_of_sale">
    	<!--Left sidebar-->
        <div class="pos_left">
        <!--<form action="includes/process_sale.php" method="post">-->
        <form action="" name="posform" method="post">
        <input type="hidden" name="old_sale_id" value="<?php echo $GetSaleIDForBill; ?>">
        <input type="hidden" name="post_from" value="pos" />
        <input type="hidden" name="authby" value="num" />
      	  <input type="hidden" name="date" value="<?php echo date('Y-m-d'); ?>" />
      	  <input type="hidden" name="custom_inv_no" value="POS Sale" />
     	   <input type="hidden" name="memo" value="Sale processed from POS Module" >
        
        	<table>
            	<tr>
                	<td width="370px">
                       
                       
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td> <div class="form-group">
                            <select name="client_id" id="client_id" class="autofill" style="width:100%">
                                <?php echo $client->client_options(get_option($_SESSION['store_id'].'_default_customer')); ?>	
                        	</select>
                    	</div></td>
                            <td> <div class="form-group">
                            <select name="table_id" id="table_id" class="autofill" style="width:100%">
                                <?php echo $client->table_options($SaleTableID); ?>	
                        	</select>
                    	</div></td>
                          </tr>
                        </table>

                       
                        
                        
                        
                    </td>
                    
                    
                    <td><!--<div class="form-group"><a data-toggle="modal" href="#addnewclient" style="font-size:22px;" title="Add new client"><i class="glyphicon glyphicon-plus-sign"></i></a></div>--></td>
                </tr>

                <tr>
                	<td colspan="2">
                    	<div class="form-group">
            			    <input type="text" class="form-control" name="to_auto" id="to" placeholder="Product Name or barcode" value="" />
          				</div>
                    </td>
                </tr>
            </table>
            
            <div class="items_container">
            <table class="table" id="items">
            	<tr>
                	<th><i class="glyphicon glyphicon-trash" title="Delete item"></i></th>
                    <th>Product</th>
                    <th>QTY</th>
                    <th>Tax</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
                <?php if($_GET["si"]!=""){ $sale->list_of_POS_Sale_Edit($_GET["si"]); } ?>
                
                <tr class="item-row">
                </tr>
                
                
                
            </table>
            </div>
            
            <div class="calculations">
            	<div class="styletotal">Total: <span class="totalamount">0.00</span> &nbsp;&nbsp;&nbsp;Tax: <span class="taxamount">0.00</span> &nbsp;&nbsp;&nbsp;Items: <span class="numberofitems">0</span></div>
                
                <table>
               		<tr>
                	  <td>
                      
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td><div class="paymentamount">Receiveable: <span id="grand_total" class="receiveable">0.00</span></div></td>
                              </tr>
                              <tr>
                                <td><div class="paymentamount" style="color:#F90;">Received: <span id="print_cash_received" class="receiveable">0.00</span></div></td>
                              </tr>
                              <tr>
                                <td><div class="paymentamount" style="color:#930">Balance: <span id="print_remainginBalance" class="receiveable">0.00</span></div></td>
                              </tr>
                            </table>

                      
                      
                      </td>
                	  <td>
                      <input type="hidden" class="grandtotle" />
                      <input type="text" class="form-control" id="cash_received" name="cash_received" onkeyup="Cashreceived()" placeholder="Cash Received" style="height:100px;font-size: 45px;width: 160px;" /></td>
              	  </tr>
                 
                	<tr>
                    	<td>
                        <select name="payment_method" class="form-control">
                			<option value="0">Select payment method</option>
                            <?php //if($GetSaleIDForBill == ''){?>
                            <option value="credit" selected="selected">Credit</option>
                            <?php //} ?>
                    		<option value="cash">Cash</option>
                			<option value="credit_card">Credit Card</option>
                		</select>
                        </td>
                        <td>
                        <input type="hidden" name="sale_type" value="pos_sale" />
                        <!--<input type="submit" class="btn btn-primary" name="save" value="Save" /> &nbsp;--><input type="submit" class="btn btn-primary" name="print" onclick="this.style.visibility='hidden';" value="Print" />
                        </td>
                    </tr>
                	
                </table>
            </div>
		</form>
        </div>
        <!--leftSide bar Ends here-->
		

        <div class="rightsidepos">
        

        	<select name="product_cat_id" id="product_cat_id" class="autofill" style="width:100%">
            	<option value="all">From all categories</option>
                <?php $product_category->category_options('all'); ?>
            </select>
            <div id="productscontainer">
             	<?php $_SESSION['category_id'] = 'all'; echo $product->list_pos_products($_SESSION['category_id']); ?>
            </div><!--productsContainer Ends here-->
        	
        </div>
  </div>
<?php
	require_once("includes/footer.php");
?>