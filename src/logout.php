<?php 	# Logout handler script
	require_once('includes/src/core/config.inc.php');
	
	// If user not logged in - redirect to login page
	require_once UTILITIES.'authenticate_user.inc.php';
	
	$page_title='Goodbye';
	include HEADER;
	
	$user = new User();
	$user->logout();
?>

<div class="boxtar-content">
	<?php include UTILITIES.'brand_img.inc.php'; ?>
	<br/>
	<br/>
	<center>
		<h3 class="uppercase">You are now logged out</h3>
		<br/>
		<br/>
		<p><a href="index.php" class="">Return to home page</a></p>
	</center>
</div>
<?php 			
	include FOOTER;
?>