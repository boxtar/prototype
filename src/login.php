
<?php 	# Login script

	require_once('includes/src/core/config.inc.php');
	$page_title='Login';
	include HEADER;
	include UTILITIES.'brand_img.inc.php';
	
	// If user is already logged in then redirect
	if(Session::exists('id')){
		//Redirect:
		redirect();
	}
	
	if(isset($_POST['submitted'])){
		// Used to print any user errors during execution
		function print_err($msg){
			echo '<center><p class="red">' . $msg . '<br/></p></center>';
		}
		// Trim inputs
		$trimmed = array_map('trim', $_POST);
		
		// Validate inputs
		$em = $pw = FALSE;
		
		// We could use regular expressions to validate the email address but it's not
		// required as this is done at registration so no invalid emails could be input.
		// Login will simply fail if an invalid email address is provided as the DB Query
		// won't return anything - so no need for unnecessary overhead
		!empty($trimmed['email']) ? $em=$trimmed['email'] : print_err('You did not enter an email address');
		!empty($trimmed['pass']) ? $pw=$trimmed['pass'] : print_err('You did not enter a password');
		
		if($em && $pw){ // email and password provided - start querying DB
			$user = new User();
			$login_status = $user->login($em, $pw);
			if($login_status['status'] === true)
				redirect();
			else
				print_err($login_status['msg'].'<br/><br/>Please try again');
		}
		else{ // Email and/or password did NOT pass validation
			print_err('<br/>Please try again');
		}
	}
	else if(isset($_GET['e'])){ // Used for redirecting from activate_acc.php
		$trimmed = ['email' => trim($_GET['e'])];
	}
?>
<br/>
<br/>

<?php include(FORMS.'login_form.inc.php'); ?>		
	
</div>
<?php 
	include FOOTER;
?>
