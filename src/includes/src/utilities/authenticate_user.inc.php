<?php 
	// If INCLUDED isn't set then this hasn't been accessed properly.
	// Redirect user away to home page. Have to redirect manually as no config included
	if(!defined('INCLUDED')){
		echo 'Access Denied. <a href="http://dev.boxtar.uk">Return to Home Page</a>';
		exit();
	}
	
	// If not logged in then just redirect to login page
	if(!Session::exists('id')){
		$url=BASE_URL.'login.php';
		ob_end_clean();
		header("Location: $url");
		exit();
	}
?>