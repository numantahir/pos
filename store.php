<?php
	include('system_load.php');
	//This loads system.

	//user Authentication.
	authenticate_user('subscriber');
	//creating company object.
	
	if(isset($_POST['store_id'])) {
		$_SESSION['store_id'] = $_POST['store_id'];	
		if(partial_access('admin') || $store_access->have_store_access()){} else { unset($_SESSION['store_id']); }
	} //setting company to use.
	
	if(!isset($_SESSION['store_id']) || $_SESSION['store_id'] == '') { 
		HEADER('LOCATION: stores.php?message=1');
	} //select company redirect ends here.
	
	if(isset($_GET['message']) && $_GET['message'] == 'warehouse') { 
		$message = "You cannot access warehouse for this store.";
	} else if(isset($_GET['message']) && $_GET['message'] == 'products') { 
		$message = "You cannot access this module for this store.";
	}
	
	if(isset($_POST['delete_store']) && $_POST['delete_store'] != '') { 
		$message = $new_store->delete_store($_POST['delete_store']); 
	}//delete ends here.
	
	$new_store->set_store($_SESSION['store_id']); //setting store.
	 
	$page_title = $new_store->store_name; //You can edit this to change your page title.
	require_once("includes/header.php"); //including header file.

    //display message if exist.
        if(isset($message) && $message != '') { 
            echo '<div class="alert alert-success">';
            echo $message;
            echo '</div>';
        }
    ?>
<style type="text/css">
	.pull-left {
		margin-right:15px;
		margin-top:10px;
		margin-bottom:10px;
		text-align:center; 
		height:117px;
	}
</style>
		<?php if(partial_access('admin') || $store_access->have_module_access('sales')): ?>
        <div class="pull-left">
            <div class="TopImg">
                <a href="sale.php"><img src="images/3img.png" width="110" /></a>
            </div><!--TopImg-->
                <div class="bottomName">
                        <a href="sale.php">Sales</a>
                </div><!--bottomName-->
        </div><!--pull-left-->
        <?php endif; ?>
		<?php if(partial_access('admin') || $store_access->have_module_access('purchase')): ?>
        <div class="pull-left">
            <div class="TopImg">
                <a href="purchase.php"><img src="images/4img.png" width="110" /></a>
            </div><!--TopImg-->
                <div class="bottomName">
                        <a href="purchase.php">Purchase</a>
                </div><!--bottomName-->
        </div><!--pull-left-->
		<?php endif; ?>
		<?php if(partial_access('admin') || $store_access->have_module_access('vendors')): ?>
        <div class="pull-left">
            <div class="TopImg">
                <a href="vendors.php"><img src="images/5img.png" width="110" /></a>
            </div><!--TopImg-->
                <div class="bottomName">
                        <a href="vendors.php">Vendors</a>
                </div><!--bottomName-->
        </div><!--pull-left-->
		<?php endif; ?>
		<?php if(partial_access('admin') || $store_access->have_module_access('clients')): ?>
        <div class="pull-left">
            <div class="TopImg">
                <a href="clients.php"><img src="images/6img.png" width="110" /></a>
            </div><!--TopImg-->
                <div class="bottomName">
                        <a href="clients.php">Clients</a>
                </div><!--bottomName-->
        </div><!--pull-left-->
		<?php endif; ?>
		<?php if(partial_access('admin') || $store_access->have_module_access('products')): ?>
        <div class="pull-left">
            <div class="TopImg">
                <a href="products.php"><img src="images/7img.png" width="110" /></a>
            </div><!--TopImg-->
                <div class="bottomName">
                        <a href="products.php">Products</a>
                </div><!--bottomName-->
        </div><!--pull-left-->
        <?php endif; ?>
		<?php /*if(partial_access('admin') || $store_access->have_module_access('warehouse')): ?>
        <div class="pull-left">
            <div class="TopImg">
                <a href="warehouse.php"><img src="images/9img.png" width="110" /></a>
            </div><!--TopImg-->
                <div class="bottomName">
                        <a href="warehouse.php">Warehouse</a>
                </div><!--bottomName-->
        </div><!--pull-left-->
		<?php endif; */ ?>
		<?php if(partial_access('admin') || $store_access->have_module_access('reports')): ?>        
        <div class="pull-left">
            <div class="TopImg">
                <a href="reports.php"><img src="images/11.png" width="110" /></a>
            </div><!--TopImg-->
                <div class="bottomName">
                        <a href="reports.php">Reports</a>
                </div><!--bottomName-->
        </div><!--pull-left-->
        <?php endif; ?>
		<?php /*if(partial_access('admin') || $store_access->have_module_access('returns')): ?>
        <div class="pull-left">
            <div class="TopImg">
                <a href="sale_returns.php"><img src="images/13.png" width="110" /></a>
            </div><!--TopImg-->
                <div class="bottomName">
                        <a href="sale_returns.php">Returns</a>
                </div><!--bottomName-->
        </div><!--pull-left-->
		<?php endif; */?>
		<?php if(partial_access('admin') || $store_access->have_module_access('price_level')): ?>
        <div class="pull-left">
            <div class="TopImg">
                <a href="set_product_rates.php"><img src="images/pricelevel.png" width="110" /></a>
            </div><!--TopImg-->
                <div class="bottomName">
                        <a href="set_product_rates.php">Price Level</a>
                </div><!--bottomName-->
        </div><!--pull-left-->
		<?php endif; ?>
        <?php if(partial_access('admin') || $store_access->have_module_access('sales')): ?>
        <div class="pull-left">
            <div class="TopImg">
                <a href="point_of_sale.php"><img src="images/pos-icon.png" width="110" /></a>
            </div><!--TopImg-->
                <div class="bottomName">
                        <a href="point_of_sale.php">Point Of Sale</a>
                </div><!--bottomName-->
        </div><!--pull-left-->
		<?php endif; ?>
        <div class="clearfix"></div>
        <?php if(partial_access('admin')) { ?>
        <link class="include" rel="stylesheet" type="text/css" href="css/jquery.jqplot.min.css" />
        <link class="include" rel="stylesheet" type="text/css" href="css/jquery-ui.css" />
	    <style type="text/css">
			.jqplot-target {
				margin: 20px;
				height: 260px;
				width: 95%;
				color: #dddddd;
			}
			.ui-widget-content {
				background: rgb(57,57,57);
			}
			table.jqplot-table-legend {
				border: 0px;
				background-color: rgba(100,100,100, 0.0);
			}
			.jqplot-highlighter-tooltip {
				background-color: rgba(57,57,57, 0.9);
				padding: 7px;
				color: #dddddd;
			}
	    </style>
        <hr>
        <div class="row">
        	<div class="col-md-6"><?php //include('reports/sale_graph.php'); ?></div><!--sale Graph Ends here.-->
            <div class="col-md-6"><?php //include('reports/purchase_graph.php'); ?></div><!--purchase Graph ends here.-->
        	<div class="clearfix"></div>
        </div><!--row ends here.-->
    	<script class="include" type="text/javascript" src="js/jquery.jqplot.min.js"></script>
    	<script class="include" type="text/javascript" src="js/plugins/jqplot.dateAxisRenderer.min.js"></script>
    	<script class="include" type="text/javascript" src="js/plugins/jqplot.canvasTextRenderer.min.js"></script>
    	<script class="include" type="text/javascript" src="js/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
    	<script class="include" type="text/javascript" src="js/plugins/jqplot.highlighter.min.js"></script>
		<?php } ?>
<?php
	//require_once("includes/footer.php");
?>