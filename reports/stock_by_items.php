<?php
	include('../system_load.php');
	//This loads system.
	//user Authentication.
	authenticate_user('subscriber');
	//creating company object.
	$store_access 	= new StoreAccess;
	$new_store 		= new Store;
	$client 		= new Client;
		
	if(partial_access('admin') || $store_access->have_module_access('reports')) {} else { 
		HEADER('LOCATION: ../store.php?message=products');
	}
	if(!isset($_SESSION['store_id']) || $_SESSION['store_id'] == '') { 
		HEADER('LOCATION: ../stores.php?message=1');
	} //select company redirect ends here.
?>	
<html>
	<head>
    	<title>Sales Report By Products</title>
        <link rel="stylesheet" type="text/css" media="all" href="../css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" media="all" href="reports.css" />
    </head>
    
    <body>
    	<?php
			if(isset($_GET['start_date']) && !empty($_GET['start_date']) && isset($_GET['end_date']) && !empty($_GET["end_date"])):
				$start_date = $_GET["start_date"];
				$end_date 	= $_GET["end_date"];
				$view_type 	= $_GET["view_type"];
				$sort_by 	= $_GET["sort_by"];
			?>	
			<div id="reportContainer" style="width:950px;">
                <!--store Head Start here.-->
                <h2 align="center"><?php echo $new_store->get_store_info($_SESSION['store_id'], 'store_name'); ?></h2>
                <h3 align="center">Sale Report By Items</h3>
                <h4 align="center">From: <?php echo $start_date; ?> To: <?php echo $end_date; ?> </h4>
                <!--stor_head Ends here.-->
                <h4 align="right">Created On: <?php echo date('d-M-Y'); ?></h4>
                
                <?php 
					$output = $sale->list_periodical_sales_by_items($start_date, $end_date, $view_type, $sort_by); 
				?>
                
                <table class="table table-striped" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Unique ID</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Unit</th>
                            <th>Sold QTY</th>
                            <th>Net Sales ($)</th>
                            <th>Net Sales With Tax ($)</th>
                        </tr>
                    </thead>
                    <?php 
						if(!empty($output)):
					
						echo $output["content"]; ?>
                </table>
            	<div class="clearfix"></div>
                <div style="width:350px;float:right;">
                	<table class="table">
                    	<tr>
                        	<th>Items Sold</th>
                            <td class="text-right"><?php echo $output["total_units"]; ?></td>
                        </tr>
                        <tr>
                        	<th>Sale Amount</th>
                            <td class="text-right"><?php echo $output["total_amount"]; ?></td>
                        </tr>
                    </table>
                </div>
                
                <?php 
					else: echo "</table>Please select date range which have sales!";
					endif;
				?>
			</div><!--reportContainer Ends here.-->
        
			<?php
			else:
			?>
			<div class="select_date_range" style="width:600px; margin:auto;margin-top:150px;">
            	<h2>Select Date Range</h2>
                
                <?php
					if(isset($_GET['data']) && !empty($_GET['data'])) { 
						$data = $_GET['data'];
					} else { 
						$data = "today";
					}
					
					if($data == "today") { 
						$start_date = date("Y-m-d");
						$end_date 	= date("Y-m-d");
					} elseif($data == "seven_days") { 
						$start_date = date('Y-m-d', strtotime('-7 days'));
						$end_date 	= date("Y-m-d");
					} elseif($data == "thirty_days") { 
						$start_date = $start_date = date('Y-m-d', strtotime('-30 days'));;
						$end_date 	= date("Y-m-d");
					}
				?>
                
                <form action="stock_by_items.php" method="get">
                <table class="table">
                	<tr>
                        <td><h4>Start Date</h4><input type="date" class="form-control" name="start_date" value="<?php echo $start_date; ?>" /></td>
                        <td><h4>End Date</h4><input type="date" class="form-control" name="end_date" value="<?php echo $end_date; ?>" /></td>	
                    </tr>
                    <tr>
                        <td>
							<h4>View Type</h4>
							<select name="view_type" class="form-control">
								<option value="list_sales_total">List Sales By All selected time</option>
							</select>
                        </td>
                        <td>
							<h4>Sort By</h4>
							<select name="sort_by" class="form-control">
								<option value="sold_units">Sold Quantity</option>
								<option value="sale_amount">Sale Amount</option>
							</select>
                        </td>	
                    </tr>
                    <tr>
                    	<td colspan="2"><input type="submit" class="btn btn-primary" value="Generate Report" /></td>
                    </tr>
                </table>
                </form>
            </div>
			<?php
            endif;
		?>
    </body>
</html>