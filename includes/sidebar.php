<div class="col-sm-4">
    <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo $language['your_profile']; ?></h3>
        </div>
            <ul class="list-group">
            	<li class="list-group-item"><center><img src="<?php echo $profile_img; ?>" class="img-thumbnail" style="width:100px" /></center></li>
                <li class="list-group-item"><strong><?php echo $language['user_id']; ?>:</strong> <?php echo $_SESSION['user_id']; ?></li>
                <li class="list-group-item"><strong><?php echo $language['first_name']; ?>:</strong> <?php echo $new_user->get_user_info($_SESSION['user_id'], 'first_name'); ?></li>
                <li class="list-group-item"><strong><?php echo $language['last_name']; ?>:</strong> <?php echo $new_user->get_user_info($_SESSION['user_id'], 'last_name');; ?></li>
                <li class="list-group-item"><strong><?php echo $language['username']; ?>:</strong> <?php echo $new_user->get_user_info($_SESSION['user_id'], 'username');; ?></li>
                <li class="list-group-item"><strong><?php echo $language['email']; ?>:</strong> <?php echo $new_user->get_user_info($_SESSION['user_id'], 'email');; ?></li>
                <li class="list-group-item"><strong><?php echo $language['user_status']; ?>:</strong> <?php echo $new_user->get_user_info($_SESSION['user_id'], 'status');; ?></li>
                <li class="list-group-item"><strong><?php echo $language['user_type']; ?>:</strong> <?php echo $new_user->get_user_info($_SESSION['user_id'], 'user_type');; ?></li>
                <li class="list-group-item"><p><a href="edit_profile.php?user_id=<?php echo $_SESSION['user_id']; ?>"><?php echo $language['edit_profile']; ?></a></p></li>
            </ul>
    </div>
</div><!--righ sidebar ends here.-->