<?php 	# script to handle and (re)display registration form
require_once('includes/src/core/config.inc.php');

// IF USER IS LOGGED IN - REDIRECT
if(Session::get('user_id')){
	//Redirect:
	redirect("profile.php");
} // End redirection conditional

$page_title = 'Create Your Box';
include HEADER;
include UTILITIES.'brand_img.inc.php';

// IF REGISTRATION FORM HAS BEEN SUBMITTED:
if(isset($_POST['submitted'])){
	// Check token validity:
	if (Token::check($_POST['token'])){
		// Grab function for validating inputs:
		require(UTILITIES.'validate_input.php');	
		// Trim all of the posted inputs:
		$trimmed = array_map('trim', $_POST);
		// Convert prof_link to lower
		$trimmed['email'] = strtolower($trimmed['email']);
		// Assume all inputs are invalid as default:
		$fn = $ln = $em = $pw = FALSE;
		// Used to print any error messages:
		function print_err($msg){
			echo $msg . '<br/>';
		}
		
		/********** INPUT VALIDATION **********/
		// validate first name
		validate_input($trimmed['fname'], 'first_name') ? $fn=$trimmed['fname'] : print_err('<center>- First Name is not valid: <em>(Must be between 2 & 20 characters and can only contain letters, apostrophes and hyphens)</em></center>');
		// validate last name
		validate_input($trimmed['lname'], 'last_name') ? $ln=$trimmed['lname'] : print_err('<center>- Last Name is not valid: <i>(Must be between 2 & 40 characters and can only contain letters, apostrophes and hyphens)</i></center>');
		// validate email
		validate_input($trimmed['email'], 'email') ? $em=$trimmed['email'] : print_err('<center>- You did not provide a valid email address</center>');
		// validate password	
		if(validate_input($trimmed['pass'], 'password')){
			$trimmed['pass'] == $trimmed['pass2'] ? $pw=$trimmed['pass'] : print_err('<center>- Your passwords did not match</center>');
		}
		else{
			print_err('<center>- Please enter a valid password: <small>(Must be between 4 & 20 characters. Can only contain letters, numbers and underscores)</small></center>');
		}
		/**************************************/
		
		// INPUT VALIDATION SUCCEEDED:
		if($fn && $ln && $em && $pw){
			// Create activation code:
			$a = md5(uniqid(rand(), true));
			
			$user =	new User();
			$user->register([
				'first_name'=>$fn,
				'last_name'=>$ln,
				'email'=>$em,
				'password'=>$pw,
				'prof_link'=>strtolower($fn.'.'.$ln.uniqid(rand()))
			]);
		}
		// INPUT VALIDATION FAILED:
		else{
			print_err('<br/><center><h5 class="red">Please amend your information as detailed and try again</h5></center><br/><br/>');
		}
	}
}

// Include registration form markup
include(FORMS.'registration_form.inc.php');

include FOOTER;
?>
