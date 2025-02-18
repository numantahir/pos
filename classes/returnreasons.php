<?php
//Return REasons class

class Returnreason {
	public $title;
	public $description;
	
	function get_reason_info($reason_id, $term) { 
		global $db;
		$query = "SELECT * from return_reasons WHERE reason_id='".$reason_id."'";
		$result = $db->query($query) or die($db->error);
		$row = $result->fetch_array();
		return $row[$term];
	}//get user email ends here.
	
	function set_reason($reason_id) { 
		global $db;
		$query = 'SELECT * from return_reasons WHERE reason_id="'.$reason_id.'" AND store_id="'.$_SESSION['store_id'].'"';
		$result = $db->query($query) or die($db->error);
		$row = $result->fetch_array();
		extract($row);
		$this->title = $title;
		$this->description = $description;
	}//Set return reason ends here.
	
	function update_reason($reason_id, $title, $description) { 
		global $db;
		$query = 'UPDATE return_reasons SET
				  title = "'.$title.'",
				  description = "'.$description.'"
				   WHERE reason_id="'.$reason_id.'" AND store_id="'.$_SESSION['store_id'].'"';
		$result = $db->query($query) or die($db->error);
		return 'Return Reason updated Successfuly!';
	}//update user level ends here.	
	
	function add_reason($title, $description) {
		global $db;
		$query = "SELECT * from return_reasons WHERE title='".$title."' AND store_id='".$_SESSION['store_id']."'";
		$result = $db->query($query) or die($db->error);
		$num_rows = $result->num_rows;
		
		if($num_rows > 0) { 
			return 'A return reason with same name already exists.';
		} else { 
			$query = "INSERT into return_reasons(reason_id, title, description, store_id)
				VALUES(NULL, '".$title."', '".$description."', '".$_SESSION['store_id']."')
			";
			$result = $db->query($query) or die($db->error);
			return 'Return reason added successfuly.';
		}
	}//add warehouse ends here.
	
	function list_reasons() {
		global $db;
		$query = 'SELECT * from return_reasons WHERE store_id="'.$_SESSION['store_id'].'" ORDER by title ASC';
		$result = $db->query($query) or die($db->error);
		$content = '';
		$count = 0;
		while($row = $result->fetch_array()) { 
			extract($row);
			$count++;
			if($count%2 == 0) { 
				$class = 'even';
			} else { 
				$class = 'odd';
			}
			$content .= '<tr class="'.$class.'">';
			$content .= '<td>';
			$content .= $reason_id;
			$content .= '</td><td>';
			$content .= $title;
			$content .= '</td><td>';
			$content .= $description;
			$content .= '</td>';
			if(partial_access('admin')) {
			$content .= '<td><form method="post" name="edit" action="manage_reasons.php">';
			$content .= '<input type="hidden" name="edit_reason" value="'.$reason_id.'">';
			$content .= '<input type="submit" class="btn btn-default btn-sm" value="Edit">';
			$content .= '</form>';
			$content .= '</td><td>';
			$content .= '<form method="post" name="delete" onsubmit="return confirm_delete();" action="">';
			$content .= '<input type="hidden" name="delete_reason" value="'.$reason_id.'">';
			$content .= '<input type="submit" class="btn btn-default btn-sm" value="Delete">';
			$content .= '</form>';
			$content .= '</td>';
			} 
			$content .= '</tr>';
			unset($class);
		}//loop ends here.	
	echo $content;
	}//list_notes ends here.
	
	function delete_reason($reason_id) {
		global $db;
		$query = "DELETE FROM return_reasons WHERE reason_id='".$reason_id."'";	
		$result = $db->query($query) or die($db->error);
		return 'Delete reason was deleted successfuly!';
	}//reason ends here.

	function reason_options($selected_reason) {
		global $db;
		$query = 'SELECT * from return_reasons WHERE store_id="'.$_SESSION['store_id'].'" ORDER by title ASC';
		$result = $db->query($query) or die($db->error);
		$options = '';
		if($selected_reason != '') { 
			while($row = $result->fetch_array()) { 
				if($selected_reason == $row['reason_id']) {
				$options .= '<option selected="selected" value="'.$row['reason_id'].'">'.ucfirst($row['title']).'</option>';
				} else { 
				$options .= '<option value="'.$row['reason_id'].'">'.ucfirst($row['title']).'</option>';
				}
			}
		} else { 
			while($row = $result->fetch_array()) { 
				$options .= '<option value="'.$row['reason_id'].'">'.ucfirst($row['title']).'</option>';
			}
		}
		echo $options;	
	}//return user level options for select
	
}//class ends here.