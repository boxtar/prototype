<?php	#Script to activate new user account
	
	require_once('includes/src/core/config.inc.php');
	$page_title = 'Activate Account';
	include HEADER;
	
	// If user is already logged in then redirect to home page
	if(Session::get('id')) redirect('profile.php');
	
	// Validate GET variables
	$em = $a = FALSE;
	
	// Email validation
	if(isset($_GET['_x004a']) && preg_match('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,8}$/', $_GET['_x004a'])){
		$em = $_GET['_x004a'];
	}
	if(isset($_GET['_y0030']) && (strlen($_GET['_y0030']) == 32)){
		$ac = $_GET['_y0030'];
	}
	
	if($em && $ac){ // activate account
		$db=DB::getInstance();
		$sql="UPDATE users SET active=NULL WHERE (email=? AND active=?) LIMIT 1";
		$binds=[$em, $ac];
		
		if(empty($db->query($sql, $binds)->errors()) && $db->num_rows()==1){
			echo '<div class="boxtar-content">';
			include UTILITIES.'brand_img.inc.php';
			echo'<br/><br/>
			<center><h4 class="">Activation Successful</h3>
			<br/>
			<p>Welcome to the community. <a href="login.php?e=' . urlencode($em) . '" class="underline green">Log In</a> to get started!</p>
			</center></div>';
		}
		else{
			echo '<div class="boxtar-content">';
			include UTILITIES.'brand_img.inc.php';
			echo '<br/><br/>
			<center><h4 class="red">Activation Unsuccessful</h4>
			<br/>
			<p class="red">Your account could not be activated at this time.<br/>
			Please contact the support team providing them with your email address and full activation link you received.</p>
			</center></div>';
		}
	}
	else{ // Incorrect GET parameters
		include UTILITIES.'brand_img.inc.php';
		echo '<br/><center><p>Please <a href="'. BASE_URL .'register.php" class="underline">create an account</a> to receive an activation code</p></center>';
	}
	
	include FOOTER;
?>