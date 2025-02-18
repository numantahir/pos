<?php 
if(isset($_SESSION['store_id'])):
$st_access = new StoreAccess; ?>
<?php if(partial_access('admin') || $st_access->have_module_access('sales')): ?>
	<!--<li>
        <a data-toggle="dropdown" class="dropdown-toggle" href="#">Sale <b class="caret"></b></a>
        <ul>
            <li style="display:none;"><a href="manage_sale.php">Add new</a></li>
            <li><a href="sale.php">Sales</a></li>
        </ul>
    </li>-->
     <li>
        <a href="credit.php">Pay Bill</a>
    </li>
    <li>
        <a href="sale.php">Sales </a>
    </li>
<?php endif; ?>
<?php if(partial_access('admin') || $st_access->have_module_access('purchase')): ?>
	<li>
        <a data-toggle="dropdown" class="dropdown-toggle" href="#">Purchase <b class="caret"></b></a>
        <ul>
            <li><a href="manage_purchase.php">Add new</a></li>
            <li><a href="purchase.php">Purchases</a></li>
        </ul>
    </li>
<?php endif; ?>
<?php if(partial_access('admin') || $st_access->have_module_access('vendors')): ?>
	<li>
        <a data-toggle="dropdown" class="dropdown-toggle" href="vendors.php">Vendors <b class="caret"></b></a>
        <ul>
            <li><a href="vendors.php">Vendors</a></li>
            <li><a href="payments.php">Payments</a></li>
        </ul>
    </li>
<?php endif; ?>
<?php if(partial_access('admin') || $st_access->have_module_access('clients')): ?>
	<li>
        <a data-toggle="dropdown" class="dropdown-toggle" href="clients.php">Clients <b class="caret"></b></a>
        <ul>
            <li><a href="clients.php">Clients</a></li>
            <li><a href="receivings.php">Receivings</a></li>
        </ul>
    </li>
<?php endif; ?>
<?php if(partial_access('admin') || $st_access->have_module_access('products')): ?>
	<li>
        <a data-toggle="dropdown" class="dropdown-toggle" href="products.php">Products <b class="caret"></b></a>
        <ul>
            <li><a href="products.php">Products</a></li>
            <li><a href="product_categories.php">Product Categories</a></li>
            <li><a href="product_taxes.php">Product Taxes</a></li>
        </ul>
    </li>
<?php endif; ?>
<?php if(partial_access('admin') || $st_access->have_module_access('warehouse')): ?>
    <li style="display:none;">
        <a data-toggle="dropdown" class="dropdown-toggle" href="warehouse.php">Warehouses <b class="caret"></b></a>
        <ul>
            <li><a href="warehouse.php">Warehouses</a></li>
            <li><a href="manage_warehouse_inventory.php">Manage Warehouse inventory</a></li>
            <li><a href="warehouse_transfer_logs.php">Transfer Logs</a></li>
        </ul>
    </li>
<?php endif; ?>
<?php if(partial_access('admin') || $st_access->have_module_access('returns')): ?>
	<li>
        <a data-toggle="dropdown" class="dropdown-toggle" href="#">Returns <b class="caret"></b></a>
        <ul>
            <li><a href="sale_returns.php">Sale Returns</a></li>
            <li><a href="purchase_returns.php">Purchase Returns</a></li>
            <li><a href="return_reasons.php">Return Reasons</a></li>
        </ul>
    </li>
<?php endif; ?>
<?php if(partial_access('admin') || $st_access->have_module_access('price_level')): ?>
	<li>
        <a data-toggle="dropdown" class="dropdown-toggle" href="set_product_rates.php">Price Levels <b class="caret"></b></a>
        <ul>
            <li><a href="set_product_rates.php">Set Product Rates</a></li>
            <li><a href="set_client_level.php">Client Price Level</a></li>
        </ul>
    </li>
<?php endif; ?>
<?php if(partial_access('admin') || $st_access->have_module_access('expenses')): ?>
	<li>
        <a data-toggle="dropdown" class="dropdown-toggle" href="expenses.php">Expenses <b class="caret"></b></a>
        <ul>
            <li><a href="expense_types.php">Expense Types</a></li>
            <li><a href="expenses.php">Expenses</a></li>
        </ul>
    </li>
<?php endif; ?>
<?php if(partial_access('admin') || $st_access->have_module_access('reports')): ?>
	<li><a href="reports.php">Reports</a></li>
<?php endif; endif; ?>
