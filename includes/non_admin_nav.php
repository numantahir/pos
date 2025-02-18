    <div class="sidebar-menu">
    	<nav class="navbar navbar-default" role="navigation">
  		<!-- Brand and toggle get grouped for better mobile display -->
  		<div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
    			<a class="navbar-brand" href="dashboard.php"><?php echo get_option('site_name'); ?></a>
                        <!--collapse user info box opner-->
                        <div class="settings-icon">
						<a href="#collapseExample" data-toggle="collapse" title="View user detail" data-animate="true">
							<i class="glyphicon glyphicon-triangle-bottom"></i>
						</a>
						</div>
		
  	</div>

  <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav">
            <li><a href="dashboard.php">Dashboard</a></li>
            <!--<li>
            	<a data-toggle="dropdown" class="dropdown-toggle" href="stores.php">Stores <b class="caret"></b></a>
                <ul>
                	<li><a href="store.php">Store</a></li>
                	<li><a href="stores.php">Select Store</a></li>
                </ul>
            </li>-->
            
            <li>
            	<a href="store.php">Stores</a>
                
            </li>
            
            <?php include('store_nav.php'); ?>
          </ul>
  </div><!-- /.navbar-collapse -->
</nav>
</div>
<!--==================Sidebar Ends Here===========================-->