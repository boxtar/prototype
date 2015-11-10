<?php 	# Use this script when it is a requirement that only 1 row is returned
		# If 0 or more than 1 row is returned then an error is triggered and script terminated
		#
		# Currently used in add_user_to_group.php

		
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
		trigger_error('URGENT: More than one row returned from Database Query in'. trim($_SERVER['SCRIPT_NAME'], '/') .'<i> (from within fetch_one_row_strict.inc.php)</i><br/>This should only ever return 1 row.');
		include FOOTER;
		exit();
	}
	else{ // Zero rows returned - Exit script
		echo '<br/><center><p>Oops! Profile not found.<br/>Return to <a href="profile.php">Your Profile</a></p></center>';
		include FOOTER;
		exit();
	}
}
else{ // Deal with error state
	trigger_error('<center><p>Error occurred in '. trim($_SERVER['SCRIPT_NAME'], '/') .'<i> (from within fetch_one_row_strict.inc.php)</i><br/>DB Errors:</p> '.implode('<br/>', $db->errors()));
	include FOOTER;
	exit();
}	
?>