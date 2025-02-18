<?php
	include('../system_load.php');
	//This loads system.
	//user Authentication.
	authenticate_user('subscriber');
	//creating company object.
	
	if(partial_access('admin') || $store_access->have_module_access('products')) {} else { 
		HEADER('LOCATION: store.php?message=products');
	}
	
	if(!isset($_SESSION['store_id']) || $_SESSION['store_id'] == '') { 
		HEADER('LOCATION: stores.php?message=1');
	} //select company redirect ends here.
	
	$new_store->set_store($_SESSION['store_id']); //setting store.
	
    //display message if exist.
        if(isset($message) && $message != '') { 
            echo '<div class="alert alert-success">';
            echo $message;
            echo '</div>';
        }
?>
<html>
	<head>
    	<title>Stock List</title>
        <link rel="stylesheet" type="text/css" media="all" href="reports.css" />
    </head>
    
    <body>
    	<div id="reportContainer">
        	<table width="100%" cellpadding="10px" border="0px">
            	<tr>
                	<td style="text-align:left;">
                    	<h2><?php echo $new_store->get_store_info($_SESSION['store_id'], 'store_name'); ?></h2>
                        <p class="phone">Phone: <?php echo $new_store->get_store_info($_SESSION['store_id'], 'phone'); ?><br />
                        Address: <?php echo $new_store->get_store_info($_SESSION['store_id'], 'address1'); ?> <?php echo $new_store->get_store_info($_SESSION['store_id'], 'address2'); ?> <?php echo $new_store->get_store_info($_SESSION['store_id'], 'city'); ?> <?php echo $new_store->get_store_info($_SESSION['store_id'], 'state'); ?> <?php echo $new_store->get_store_info($_SESSION['store_id'], 'country'); ?><br>
						Email: <?php echo $new_store->get_store_info($_SESSION['store_id'], 'email'); ?>
                        </p>
                    </td>
                </tr>
            </table>
            <br />
<br />

<table width="100%" cellpadding="2" cellspacing="0" border="1">
	<thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Image</th>
                <th>Available<br />Units</th>
                <th>Selling<br />Price</th>
            </tr>
        </thead>
        <tbody>
			<?php $product->stock_detail();  ?>
        </tbody>
</table>
<div style="clear:both;"></div>
        </div><!--reportContainer Ends here.-->
    </body>
</html>