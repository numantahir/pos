<?php
	include('system_load.php');
	//Including this file we load system.
	/*
	Logout function if called.
	*/
	if(isset($_GET['logout']) && $_GET['logout'] == 1) { 
		session_destroy();
		HEADER('LOCATION: index.php');
	} //Logout done.
		HEADER('LOCATION: store.php');
	exit();
	//user Authentication.
	authenticate_user('admin');
	
	$new_user = new Users;//New user object.
	$new_level = new Userlevel;
	$notes_obj = new Notes;
	$message_obj = new Messages;
	
	$page_title = "Dashboard"; //You can edit this to change your page title.
	require_once("includes/header.php"); //including header file.
?>
<div class="well">
	<p><strong>Welcome Back! <?php echo $_SESSION['first_name'].' '.$_SESSION['last_name']; ?></a></strong> Here you can manage users, User levels, your messages and notes.</p>
</div>
	<div class="page-header">
        <h2>System Information</h2>
     </div>                   
     <div class="row">
         <div class="col-sm-6">
         <!--userinfo starts here.-->
         <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Users info</h3>
            </div>
            <div class="panel-body">
                <strong>Total Users:</strong> <?php $new_user->get_total_users('all');?> <br />
                <strong>Active Users:</strong> <?php $new_user->get_total_users('activate');?> <br />
                <strong>Deactive Users:</strong> <?php $new_user->get_total_users('deactivate');?> <br />
                <strong>Ban Users:</strong> <?php $new_user->get_total_users('ban');?> <br />
                <strong>Suspend Users:</strong> <?php $new_user->get_total_users('suspend');?> <br />
                <p>You can manage users by going to users management <a href="users.php">Manage Users</a></p>
            </div>
          </div>
         <!--user info ends here.-->
         </div><!--2 columns ends here.-->
         
         <div class="col-sm-6">
         <!--level starts here.-->
         <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Users Levels</h3>
            </div>
            <div class="panel-body">
                <table width="100%" cellpadding="2" cellspacing="0" border="0">
                    <tr>
                        <th>Name</th>
                        <th>Default Page</th>
                        <th>Users</th>
                    </tr>
                    <?php $new_level->get_level_info(); ?>
                </table>
            </div>
          </div>
         <!--level info ends here.-->
         
         </div><!--2 columns ends here.-->
      <div class="col-sm-6">
         <!--level starts here.-->
         <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">My Notes</h3>
            </div>
            <div class="list-group">
			 	<?php $notes_obj->notes_widget(); ?>
          </div>
       </div> <!--mynotes ends here.-->
       </div>
       <!--level starts here.-->
      <div class="col-sm-6">
         <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Messages</h3>
            </div>
            <div class="list-group">
			 	<?php $message_obj->message_widget(); ?>
          </div>
       </div> <!--mynotes ends here.-->    
      </div><!--row ends here.-->                    
<?php
	require_once("includes/footer.php");
?>