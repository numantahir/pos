<?php
//user levels Class

class Userlevel {
	public $level_name;
	public $level_description;
	public $level_page;
	
	function set_level($level_id) { 
		global $db;
		$query = 'SELECT * from user_level WHERE level_id="'.$level_id.'"';
		$result = $db->query($query) or die($db->error);
		$row = $result->fetch_array();
		$this->level_name = $row['level_name'];
		$this->level_description = $row['level_description'];
		$this->level_page = $row['level_page'];
	}//level set ends here.
	
	function update_user_level($level_id, $level_name, $level_description, $level_page) { 
		global $db;
		$query = 'UPDATE user_level SET
				  level_name = "'.$level_name.'",
				  level_description = "'.$level_description.'",
				  level_page = "'.$level_page.'"
				   WHERE level_id="'.$level_id.'"';
		$result = $db->query($query) or die($db->error);
		return 'User level updated Successfuly!';
	}//update user level ends here.
	
	function add_user_level($level_name, $level_description, $level_page) { 
		global $db;
		//checking if level already exist.
		$query = "SELECT * from user_level WHERE level_name='".$level_name."'";
		$result = $db->query($query) or die($db->error);
		$num_rows = $result->num_rows;
		
		if($num_rows > 0) { 
			$message = 'You cannot add duplicate user levels.';
		} else { 
			$level_page = stripslashes($level_page);
			$query = "INSERT into user_level VALUES(NULL, '".$level_name."', '".$level_description."', '".$level_page."')";
			$result = $db->query($query) or die($db->error);
			$message = 'User level added successfuly!';
		}
		return $message;
	}//add_user_level ends here.
	
	function list_levels($user_type) {
		global $db;
		if($user_type == 'admin') { 
			$query = 'SELECT * from user_level ORDER by level_name ASC';
			$result = $db->query($query) or die($db->error);
			$content = '';
			$count = 0;
			while($row = $result->fetch_array()) { 
				extract($row);
				$count++;
				if($count % 2 == 0) { 
					$class = 'even';
				} else { 
					$class = 'odd';
				}
				$content .= '<tr class="'.$class.'">';
				$content .= '<td>';
				$content .= $level_id;
				$content .= '</td><td>';
				$content .= $level_name;
				$content .= '</td><td>';
				$content .= $level_description;
				$content .= '</td><td>';
				$content .= $level_page;
				$content .= '</td><td>';
				$content .= '<button class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal_'.$level_id.'">
  							Message
							</button>';
				$content .= '<!-- Modal -->
<script type="text/javascript">
$(function(){
$("#message_form_'.$level_id.'").on("submit", function(e){
  e.preventDefault();
  $.post("includes/messageprocess.php", 
	 $("#message_form_'.$level_id.'").serialize(), 
	 function(data, status, xhr){
	   $("#success_message_'.$level_id.'").html("<div class=\'alert alert-success\'>"+data+"</div>");
	 });
});
});
</script>				
<div class="modal fade" id="modal_'.$level_id.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="message_form_'.$level_id.'" method="post" name="send_message">
	<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Send Message</h4>
      </div>
	  
      <div class="modal-body">
      		<div id="success_message_'.$level_id.'"></div>
	   		<div class="form-group">
				<label class="control-label">Message To</label>
				<input type="text" class="form-control" name="message_to" value="All '.$level_name.'" readonly="readonly" />
			</div>
			
			<div class="form-group">
				<label class="control-label">Subject</label>
				<input type="text" class="form-control" name="subject" value="" />
			</div>
			
			<div class="form-group">
				<label class="control-label">Message</label>
				<textarea class="form-control" name="message"></textarea>
			</div>
      </div>
	  <input type="hidden" name="level_name" value="'.$level_name.'" />
	  <input type="hidden" name="level_form" value="1" />
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<input type="submit" value="Send Message" class="btn btn-primary" />
      </div>
    </div><!-- /.modal-content -->
   </form>
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->';			
				$content .= '</td><td>';
				$content .= '<form method="post" name="edit" action="manage_user_level.php">';
				$content .= '<input type="hidden" name="edit_level" value="'.$level_id.'">';
				$content .= '<input type="submit" class="btn btn-default btn-sm" value="Edit">';
				$content .= '</form>';
				$content .= '</td><td>';
				$content .= '<form method="post" name="delete" onsubmit="return confirm_delete();" action="">';
				$content .= '<input type="hidden" name="delete_level" value="'.$level_id.'">';
				$content .= '<input type="submit" class="btn btn-default btn-sm" value="Delete">';
				$content .= '</form>';
				$content .= '</td>';
				$content .= '</tr>';
				unset($class);
			}//loop ends here.
			
		} else { 
			$content = 'You cannot view list of levels.';
		}	
		echo $content;
	}//list_levels ends here.
	
	function delete_level($user_type, $level_id) {
		global $db;
		if($user_type == 'admin') {
			$query = 'DELETE from user_level WHERE level_id="'.$level_id.'"';
			$result = $db->query($query) or die($db->error);
			$message = 'Level was deleted successfuly!';	
		} else { 
			$message = 'You dont have access to delete this user level.';
		}	
		return $message;
	}//delete level ends here.
	
	function userlevel_options($user_type) {
		global $db;
		$query = 'SELECT * from user_level ORDER by level_name ASC';
		$result = $db->query($query) or die($db->error);
		$options = '';
		if($user_type != '') { 
			while($row = $result->fetch_array()) { 
				if($user_type == $row['level_name']) {
				$options .= '<option selected="selected" value="'.$row['level_name'].'">'.ucfirst($row['level_name']).'</option>';
				} else { 
				$options .= '<option value="'.$row['level_name'].'">'.ucfirst($row['level_name']).'</option>';
				}
			}
		} else { 
			while($row = $result->fetch_array()) { 
				$options .= '<option value="'.$row['level_name'].'">'.ucfirst($row['level_name']).'</option>';
			}
		}
		echo $options;	
	}//return user level options for select
	
	function get_level_info() { 
		global $db;
		if($_SESSION['user_type'] == 'admin') { 
				$table = '';
				$table .= '<tr>';
				$table .= '<td>Admin</td>';
				$table .= '<td>dashboard.php</td>';
				$query = "SELECT * from users WHERE user_type='admin'";
				$result = $db->query($query) or die($db->error);
				$num_rows = $result->num_rows;
				$table .= '<td>'.$num_rows.'</td>';
				$table .= '</tr>';
			
			$query = "SELECT * from user_level ORDER by level_name ASC";
			$result = $db->query($query) or die($db->error);
			
			while($row = $result->fetch_array()) { 
				$table .= '<tr>';
				$table .= '<td>'.ucfirst($row['level_name']).'</td>';
				$table .= '<td>'.$row['level_page'].'</td>';
				$query_users = "SELECT * from users WHERE user_type='".$row['level_name']."'";
				$result_users = $db->query($query_users) or die($db->error);
				$num_rows = $result_users->num_rows;
				$table .= '<td>'.$num_rows.'</td>';
				$table .= '</tr>';
			}
			echo $table;
		} else { 
			echo 'You cannot view this list.';
		}
	}//get user level info ends here.
}//class ends here.