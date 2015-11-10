<?php 	# Deals with the insertion of a new music group and overseer user relation
		# Used in create_music_group.php

		
// If INCLUDED isn't set then this hasn't been accessed properly.
// Redirect user away to home page. Have to redirect manually as no config included
if(!defined('INCLUDED')){
	echo 'Access Denied. <a href="http://dev.boxtar.uk">Return to Home Page</a>';
	exit();
}	

$sql="INSERT INTO artist_music (name, genre, prof_link, date_reg) VALUES (?, ?, ?, NOW())";
$values=["$n", "$g", preg_replace('/[ \')(&-.]/', '', trim(strtolower($n), " ")).'.'.uniqid(rand())];
// Insert record to artist_music
if(empty($db->query($sql, $values)->errors()) && $db->num_rows() == 1){
	// newly created artist_music ID to populate intermediary table:
	$new_id = (int)$db->last_insert_id();
	
	$sql="INSERT INTO users_artist_music (am_id, u_id, status_flag, date_reg) VALUES (?, ?, ?, NOW())";
	$values=[$new_id, (int)$_SESSION['user_id'], 3];
	// Insert record to user_artist_music - status_flag 3 = overseer
	if(empty($db->query($sql, $values)->errors()) && $db->num_rows() == 1){
		echo '<br/><center><h5 class="uppercase">New Music Group Successfully Created!</h5></center><br/>';
		echo '<center><p class="">Head over to <a href="edit_group.php?_i='.$new_id.'" class="dark-green"><b> "' . escape($n) . '" </b></a> edit page to customise your page and start sharing what you love with others</p><center>';
		include FOOTER;
		exit();
	}
	else{
		// Delete entry from artist_music since insertion into intermediary failed:
		$db->query("DELETE FROM artist_music WHERE id=?", [$new_id]);
		trigger_error('<br/><b>Error while inserting into USER_ARTIST_MUSIC (intermediary) DB in create_page.php</b><br/>');
		echo '<center><h4 class="red uppercase">System Error</h4><center><br/>';
		echo '<center><p class="red">Oops! We\'ve tripped up - Webmaster has been informed of this error<br/>We\'re really sorry for the inconvenience</p><center>';
	}
}
else{
	// Deal with error state
	trigger_error('<br/><b>Error while inserting into ARTIST_MUSIC DB in create_page.php</b><br/>(If no DB errors then DB::num_rows did not = 1)<br/>DB Errors: ' . implode('<br/>', $db->errors()) . '<br/>');
	echo '<center><h4 class="red uppercase">System Error</h4><center><br/>';
	echo '<center><p class="red">Oops! We\'ve tripped up - Webmaster has been informed of this error<br/>We\'re really sorry for the inconvenience</p><center>';
}

?>