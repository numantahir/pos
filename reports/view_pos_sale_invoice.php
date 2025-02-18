<?php
	include('../system_load.php');
	//This loads system.
	//user Authentication.
	authenticate_user('subscriber');
	//creating company object.
		
	if(partial_access('admin') || $store_access->have_module_access('sales')) {} else { 
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
    	<style type="text/css">
		@media print { div.page-break { display: block; page-break-before: always; } }
        	.pos_report_container { 
				width:300px;
				float:left;
				height:auto;
			}
			h3 { 
				text-align:center;
				font-size:15px;	
			}
			p.phone, .invoicetable { 
				  font-family:Verdana, Geneva, sans-serif;
  				  font-size: 12px;	
				  text-align:center;
			}
			.invoicetable {margin-top:15px;}
			.lefttd {text-align:left;width:140px;} 
			.righttd {text-align:right;width:140px;}
			table { font-size:12px;}
			table .header th {border-bottom:1px solid #000;}
			table .header th, table td {padding:3.5px;}
			.item_detail .total, .item_detail .tax, .item_detail .qty, .item_detail .price {text-align:center;}
        </style>
        
		<script type="text/javascript">
			window.print();
			//window.close();
		</script>
    </head>
    
    <body>
    
    	<div class="pos_report_container">

				<h3>BS RECEIPT</h3>
				<p class="phone"><?php echo $new_store->get_store_info($_SESSION['store_id'], 'address1'); ?> <?php echo $new_store->get_store_info($_SESSION['store_id'], 'address2'); ?> <?php echo $new_store->get_store_info($_SESSION['store_id'], 'city'); ?> <?php echo $new_store->get_store_info($_SESSION['store_id'], 'state'); ?> <?php echo $new_store->get_store_info($_SESSION['store_id'], 'country'); ?> <br></p>
        <?php $mysqldate = strtotime($sale->get_sale_info($_GET['sale_id'], 'datetime')); ?>
        <?php //$agent_id = $sale->get_sale_info($_GET['sale_id'], 'agent_id'); ?>
        <?php $client_id = $sale->get_sale_info($_GET['sale_id'], 'client_id'); ?>
        	  <table class="invoicetable">
              		<tr>
                    	<td class="lefttd">Date: <?php echo date('d-M-Y', $mysqldate); ?></td>
                        <!-- <td class="righttd">S.INV # : <?php //echo $_GET['sale_id']; ?></td> -->
			<td class="righttd">POS # : <?php echo str_pad($sale->get_sale_info($_GET['sale_id'], 'invoice_number'), 6, '0', STR_PAD_LEFT);?></td>
                    </tr>
                    
                    <tr>
                    	<td class="lefttd">NTN: 8898112</td>
                        <td class="righttd"><strong>
                        <?php
						$SaleTableID = $sale->get_sale_info($_GET['sale_id'], 'table_id'); 
						if($SaleTableID != ''){
						echo $client->GetTableInfo($SaleTableID);
						}
						?></strong>
                        <br>
                        Payment: <?php 
						$PaymentStatusGet = $sale->get_sale_info($_GET['sale_id'], 'payment_status'); 
						if($PaymentStatusGet == 'credit_card'){
						echo 'Credit Card';
						} else {
						echo $PaymentStatusGet;
						}
						?></td>
                    </tr>
              </table>
              <?php $invoice_detail = $sale->view_sale_invoice($_GET['sale_id'], 'pos_invoice'); ?>
              <!--work here.-->
              	<table class="item_detail" width="100%" align="center" cellpadding="0" cellspacing="0" style="border-bottom:solid 1px #000000;">
                	<tr class="header">
                    	<th style="width:10px !important;">#</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <!--<th>Tax</th>-->
                        <th>Total</th>
                    </tr>
                    <?php echo $invoice_detail['rows']; ?>
                </table>
              <!--work here.-->
    
<table width="95%" align="right" cellspacing="0" style="margin-top:5px; text-align:right;margin-bottom:5px;" cellpadding="5" border="0px">
        		<tr>
                	<td width="73%"><strong>Total:</strong></td>
                	<td width="27%" align="left">Rs.<?php echo currency_format($invoice_detail['grand_total']); ?></td>
        		</tr>
                    <!-- <tr>
                	<td><strong>GST:</strong></td>
                	<td align="left">Rs.<?php echo currency_format($invoice_detail['tax_total']); ?></td>
                    </tr> -->
                    <tr>
                    <td><strong>Grand Total:</strong></td>
                    <td align="left" style="font-size:14px; font-weight:bold">Rs.<?php //echo currency_format(round($invoice_detail['received_amount'])); ?><?php echo currency_format($invoice_detail['grand_total']); ?></td>
                    </tr>
                <tr>
                  <td colspan="2" align="center"></tr>
                <tr>
                  <td colspan="2" align="center" style="font-size:10px;">Thank you for visit us. <br> See you again.                                    
    </tr>
                <tr>
                  <td colspan="2" align="center">                  
    </tr>
          </table>





















<div class="page-break"></div>



<h3>BS RECEIPT</h3>
				<p class="phone"><?php echo $new_store->get_store_info($_SESSION['store_id'], 'address1'); ?> <?php echo $new_store->get_store_info($_SESSION['store_id'], 'address2'); ?> <?php echo $new_store->get_store_info($_SESSION['store_id'], 'city'); ?> <?php echo $new_store->get_store_info($_SESSION['store_id'], 'state'); ?> <?php echo $new_store->get_store_info($_SESSION['store_id'], 'country'); ?> <br></p>






<table class="invoicetable">
              		<tr>
                    	<td class="lefttd">Date: <?php echo date('d-M-Y', $mysqldate); ?></td>
                       <!-- <td class="righttd">POS # : <?php echo $_GET['sale_id']; ?></td> -->
<td class="righttd">S.INV # : <?php echo str_pad($sale->get_sale_info($_GET['sale_id'], 'invoice_number'), 6, '0', STR_PAD_LEFT);?></td>
                    </tr>
                    
                    <tr>
                    	<td class="lefttd">NTN: 8898112</td>
                        <td class="righttd"><strong>
                        <?php
						$SaleTableID = $sale->get_sale_info($_GET['sale_id'], 'table_id'); 
						if($SaleTableID != ''){
						echo $client->GetTableInfo($SaleTableID);
						}
						?></strong>
                        <br>Payment: <?php echo $sale->get_sale_info($_GET['sale_id'], 'payment_status'); ?></td>
                    </tr>
              </table>
              <?php $invoice_detail = $sale->view_sale_invoice($_GET['sale_id'], 'pos_invoice'); ?>
              <!--work here.-->
              	<table class="item_detail" width="100%" align="center" cellpadding="0" cellspacing="0" style="border-bottom:solid 1px #000000;">
                	<tr class="header">
                    	<th style="width:10px !important;">#</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <!--<th>Tax</th>-->
                        <th>Total</th>
                    </tr>
                    <?php echo $invoice_detail['rows']; ?>
                </table>
              <!--work here.-->
    
<table width="95%" align="right" cellspacing="0" style="margin-top:5px; text-align:right;margin-bottom:5px;" cellpadding="5" border="0px">
        		<tr>
                	<td width="73%"><strong>Total:</strong></td>
                	<td width="27%" align="left">Rs.<?php echo currency_format($invoice_detail['grand_total']); ?></td>
        		</tr>
                   <!-- <tr>
                	<td><strong>GST:</strong></td>
                	<td align="left">Rs.<?php echo currency_format($invoice_detail['tax_total']); ?></td>
                    </tr> -->
                    <tr>
                    <td><strong>Grand Total:</strong></td>
                    <td align="left" style="font-size:14px; font-weight:bold">Rs.<?php //echo currency_format(round($invoice_detail['received_amount'])); ?><?php echo currency_format($invoice_detail['grand_total']); ?></td>
                    </tr>
                <tr>
                  <td colspan="2" align="center"></tr>
                <tr>
                  <td colspan="2" align="center" style="font-size:10px;">Thank you for visit us. <br> See you again.                                    
    </tr>
                <tr>
                  <td colspan="2" align="center">                  
    </tr>
          </table>












        </div><!--reportContainer Ends here.-->














    </body>
</html><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>