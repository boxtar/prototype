<?php 	# Grabs the list of genres from the DB and populates them into a select list
		# NOT USED - create_music_group_form.inc.php is grabbed by AJAX so this won't work

// If INCLUDED isn't set then this hasn't been accessed properly.
// Redirect user away to home page. Have to redirect manually as no config included
if(!defined('INCLUDED')){
	echo 'Access Denied. <a href="http://dev.boxtar.uk">Return to Home Page</a>';
	exit();
}

$db=DB::getInstance();
if(empty($db->get('genres')->errors())){
	echo '<select name="genre" id="genre" required autofocus style="width:100%;height:30px;">';
	if($db->num_rows()>0){
		foreach($db->results() as $key=>$val){
			echo "<option value=$key>$val</option>";
		}
	}
	else{
		echo '<option value=0>No Genres Available</option>';
	}
	echo '</select>';
}
else{
	trigger_error('Failed to retrieve data from genres table<br/>DB Error(s):<br/>'.implode('<br/>', $db->errors()));
	include FOOTER;
	exit();
}
?>