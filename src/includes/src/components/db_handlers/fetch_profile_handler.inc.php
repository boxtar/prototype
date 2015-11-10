<?php 	# Included in profile.php, edit_profile.php, edit_group.php

		# This script makes sure only one row is returned/affected otherwise it triggers an error and kills execution of the script
		# If zero rows returned then it prints out a friendly not found message
		
		# The script must be killed if there is an event that stops $query_result being populated as this variable is accessed
		# despite the outcome of the DB query
		
		# The query is not done here as it will change depending on several conditions (see profile.php)

// If INCLUDED isn't set then this hasn't been accessed properly.
// Redirect user away to home page. Have to redirect manually as no config included
if(!defined('INCLUDED')){
	echo 'Access Denied. <a href="http://dev.boxtar.uk">Return to Home Page</a>';
	exit();
}
	
// If no errors produced while querying...
if(empty($db->errors())){
	// Make sure exactly one row returned
	if($db->num_rows()==1){
		$query_result = $db->first_result();
	}
	elseif($db->num_rows()>1){
		include HEADER;
		include UTILITIES.'brand_img.inc.php';
		trigger_error('URGENT: More than one row returned from Database Query in'. trim($_SERVER['SCRIPT_NAME'], '/') .'<i> (from within fetch_profile_hander.inc.php)</i><br/>This should only ever return 1 row.');
		include FOOTER;
		exit();
	}
	else{ // Zero rows returned - Exit script
		include HEADER;
		include UTILITIES.'brand_img.inc.php';
		echo '<br/><center><p>Oops! Profile not found.<br/>Return to the <a href="http://dev.boxtar.uk">Home page</a></p></center>';
		include FOOTER;
		exit();
	}
}
else{ // Deal with error state
	include HEADER;
	include UTILITIES.'brand_img.inc.php';
	trigger_error('<center><p>Error occurred in '. trim($_SERVER['SCRIPT_NAME'], '/') .'<i> (from within fetch_profile_hander.inc.php)</i><br/>DB Errors:</p> '.implode('<br/>', $db->errors()));
	include FOOTER;
	exit();
}
?>