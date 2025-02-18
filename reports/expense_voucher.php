<?php
	include('../system_load.php');
	//This loads system.
	//user Authentication.
	authenticate_user('subscriber');
	//creating company object.
		
	if(partial_access('admin') || $store_access->have_module_access('expenses')) {} else { 
		HEADER('LOCATION: ../store.php?message=products');
	}
	if(!isset($_SESSION['store_id']) || $_SESSION['store_id'] == '') { 
		HEADER('LOCATION: ../stores.php?message=1');
	} //select company redirect ends here.
	
	if(!isset($_GET['expense_id'])) { 
		HEADER("LOCATION: ../expenses.php?message=Please select an expense to view.");
		exit();
	}
	//setting level data if updating or editing.
	if(isset($_GET['expense_id'])) {
		$expenses->set_expense($_GET['expense_id']);
		
		$date = $expenses->datetime;
		$date = strtotime($date);
		$date = date('d M Y', $date);	
	} //level set ends here
?>	
<html>
	<head>
    	<title>Sale Return invoice</title>
        <link rel="stylesheet" type="text/css" media="all" href="reports.css" />
    	<style type="text/css">
			body, table{ 	
				font-size:13px;
				line-height:17px;
			}
        	#reportContainer .head { 
				text-align:center;	
			}
			h3 { 
				text-align:Center;
				text-decoration:underline;
				margin-top:30px;	
			}
			#reportContainer { 
				width:450px;
				border:1px solid #CCC;
				padding:10px;	
			}
        </style>
    </head>
    
    <body>
    	<div id="reportContainer">
        		<div class="head">
        	     		<h2><?php echo $new_store->get_store_info($_SESSION['store_id'], 'store_name'); ?></h2>
                        <p class="phone"><strong>Phone:</strong> <?php echo $new_store->get_store_info($_SESSION['store_id'], 'phone'); ?> <strong>Email:</strong> <?php echo $new_store->get_store_info($_SESSION['store_id'], 'email'); ?><br />
                        <strong>Address:</strong> <?php echo $new_store->get_store_info($_SESSION['store_id'], 'address1'); ?> <?php echo $new_store->get_store_info($_SESSION['store_id'], 'address2'); ?> <?php echo $new_store->get_store_info($_SESSION['store_id'], 'city'); ?> <?php echo $new_store->get_store_info($_SESSION['store_id'], 'state'); ?> <?php echo $new_store->get_store_info($_SESSION['store_id'], 'country'); ?>
                        </p>
                 </div>
                 
                 <h3>Expense Voucher</h3>
                 <table width="100%" cellpadding="5px" border="0">
                 	<tr>
                    	<td><strong>Date:</strong> <?= $date; ?></td>
                        <td align="right"><strong>Expense Type:</strong> <?= $expenses->type_name; ?></td>
                    </tr>
                 </table>
                 
                 <table width="100%" border="1px" cellpadding="7px" cellspacing="0">
                 	<tr>
                        <th width="25px">Sr#</th>
                        <th>Particulars</th>
                        <th>Amount</th>
                 	</tr>
                    <tr>
                    	<td>1</td>
                        <td><?= $expenses->description; ?></td>
                        <td width="50" align="right"><?= currency_format($expenses->amount); ?></td>
                    </tr>
                 </table>
                 
                 <table width="100%" cellpadding="5px" border="0" style="margin-top:40px;">
                 	<tr>
                    	<td><strong>Added By:</strong> <?= $user->get_user_info($expenses->agent_id, 'first_name').' '.$user->get_user_info($expenses->agent_id, 'last_name'); ?></td>
                        <td align="right"><strong>Authorized By:</strong> _________________</td>
                    </tr>
                 </table>
                        
        </div><!--reportContainer Ends here.-->
    </body>
</html>