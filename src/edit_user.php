<?php 
require 'includes/src/core/config.inc.php';

// If not logged in then just redirect to login page
require_once UTILITIES.'authenticate_user.inc.php';

$user = new User();
$page_title = $user->name();
include HEADER;

if(isset($_POST['submitted'])){
	if(Token::check($_POST[Config::get('session/token_name')])){
		// Grab function for validating inputs
		require(UTILITIES . 'validate_input.php');
		// Message String:
		$msg = '';
		// Trim all of the posted inputs
		$trimmed = array_map('trim', $_POST);
		// Convert email to lower:
		$trimmed['email'] = strtolower($trimmed['email']);
		// Convert prof_link to lower:
		$trimmed['prof_link'] = strtolower($trimmed['prof_link']);
	
		// Assume all inputs are invalid as default
		$fn = $ln = $em = $loc = $lnk = $bio = false;
		
		/******************* INPUT VALIDATION *******************/
		// validate first name
		validate_input($trimmed['fname'], 'first_name') ? $fn=$trimmed['fname'] : $msg .= '<p class="dark-grey">- Invalid first name (must be at least 2 characters)</p>';
		// validate last name
		validate_input($trimmed['lname'], 'last_name') ? $ln=$trimmed['lname'] : $msg .= '<p class="dark-grey">- Invalid last name (must be at least 2 characters)</p>';
		// validate location
		validate_input($trimmed['loc'], 'location') ? $loc=$trimmed['loc'] : $msg .= '<p class="dark-grey">- Invalid location provided (must be at least 2 characters)</p>';
		// validate profile link
		validate_input($trimmed['prof_link'], 'profile_link') ? $lnk=$trimmed['prof_link'] : $msg .= '<p class="dark-grey">- Invalid profile link provided (can only contain characters, periods and dashes)</p>';
		// validate email
		validate_input($trimmed['email'], 'email') ? $em=$trimmed['email'] : $msg .= '<p class="dark-grey">- Invalid email address provided</p>';
		// validate bio
		validate_input($trimmed['bio'], '') ? $bio=$trimmed['bio'] : $msg .= '<p class="dark-grey">- Invalid quick message provided</p>';
		/********************************************************/
		
		// If all validated - continue with update process
		if( $fn && $ln && $em && $loc && $lnk && $bio){
			$update_status = $user->update([
				'first_name' => $fn,
				'last_name' => $ln,
				'email' => $em,
				'prof_link' => $lnk,
				'loc' => $loc,
				'bio' => $bio,
			]);
			
			if($update_status['status']){
				// update user objects data from DB:
				$user->find($lnk, 'users', ['prof_link']);
				$msg = '<h5 class="green">Profile Updated</h5>';
				echo '<br/><br/><center><div id="update-status">'.$msg.'</div></center>';
			}
			else{
				$msg .= '<p style="color:#A00;">'.$update_status['msg'].'</p>';
				$msg .= '<br/><h5 class="" style="color:#A00;">Profile not updated</h5><br/>';
				echo '<br/><br/><center><div id="update-status">'.$msg.'</div></center>';
			}
		}
		else{ // Input validation failed
			$msg .= '<br/><h5 class="" style="color:#A00;">Profile not updated</h5><br/>';
			echo '<br/><br/><center><div id="update-status">'.$msg.'</div></center>';
		}
	}
	else{
		echo '<br/><br/><center><div id="update-status"><p class="error">Invalid form submission</p></div></center>';
	}
	// re-fetch logged in users details as they have either been updated or invalidated:
	$user->find(Session::get('user_id'));
}

?>

<?php include FORMS.'edit_user_form.inc.php'; ?>

<?php include FOOTER ?>