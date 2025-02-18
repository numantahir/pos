	<div class="top_section">
    	<nav class="navbar user-info-navbar"  role="navigation"><!-- User Info, Notifications and Menu Bar -->
				<!-- Left links for user info navbar -->
				<ul class="user-info-menu left-links list-inline list-unstyled">
                	<li><a class="navbar-brand" href="<?php echo get_option('site_url'); ?>"><?php echo get_option('site_name'); ?></a></li>
				</ul>
			
			
				<!-- Right links for user info navbar -->
				<ul class="user-info-menu right-links list-inline list-unstyled">
					<li class="dropdown user-profile">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<span>
								Sign In <!--<b class="caret"></b>-->
							</span>
						</a>
							<ul class="dropdown-menu" style="display:none;">
                	<li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
			        <li><a href="register.php"><span class="glyphicon glyphicon-file"></span> Register</a></li>
                </ul> 
					</li>
				</ul>
			</nav>
    </div><!--topbar ends here-->