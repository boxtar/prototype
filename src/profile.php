<?php 
	// Grab configuration
	require_once('includes/src/core/config.inc.php');
	
	// Input validator
	require_once(UTILITIES.'validate_input.php');
	
	// Will exists if current user is a logged in user:
	$current_user = new User();
	
	// GET variable for profile to retrieve:
	$param_user = false;
	// GET variable for type of profile being retrieved - defaults to a user:
	$param_type = 'user';
	
	// IF provided user is validated then assign:
	// PERL Compatible Regular Expressions used in .htaccess so input to this should only be invalid if access directly (eg profile.php?user=<invalid&value<?)
	if(isset($_GET['user'])){
		$tmp_user = strtolower(trim($_GET['user']));
		$param_user = validate_input($tmp_user, 'profile_link') ? $tmp_user : false;
	}
	
	// IF provided type matches an entry in $client_type (defined in config) then assign:
	if(isset($_GET['type'])){
		$tmp_type = strtolower(trim($_GET['type']));
		if(in_array($tmp_type, $client_type))
			$param_type = $tmp_type;
	}
	
	// Create object of targeted type:
	switch($param_type){
		case 'music':
			$target = new Music_Group($param_user);
			break;
		case 'dance':
			$target = new Dance_Group($param_user);
			break;
		case 'comedy':
			$target = new Comedy_Group($param_user);
			break;
		default: // Assume user:
			$target = new User($param_user);
			break;
	}
	
	// If no target then inform user and exit script:
	if(!$target->exists()){
		include HEADER;
		include UTILITIES.'brand_img.inc.php';
		echo '<br/><center><p class="">Could not find the profile link <span class="dark-grey">'.escape($param_user).'</span>
		<br/><br/>Return to <a href="'.BASE_URL.'">Boxtar UK</a></p></center>';
		include FOOTER;
		exit();
	}
	// if current user is viewing their own profile then set this flag to true; else false.
	// could be used for some conditional outputting like update prof pic link or something..
	if($current_user->exists()){
		$current_users_profile = ($param_type === 'user' ? ($target->data()->id === Session::get('id') ? true : false) : false);
	}
	else{
		$current_users_profile = false;
	}
	
	$page_title = $target->name();
	// This can't be included at top of script if I want the page title to be dynamically created
	include HEADER;
?>
	<br/>
	<br/>
	<div class="boxtar-content">
	<?php 
	// This might seem a bit backwards but it's the way I've setup the database
	// An account is active if column 'active' is NULL.
	// An account is pending activation if 'active' column contains a string (activation code)
	//($param_type === 'music' || $param_type === 'dance' || $param_type === 'comedy')
	if(($param_type === 'user' && !$target->data()->active) || ($param_type != 'user' && in_array($param_type, $client_type) && $target->data()->active)){
	?>
		<!-- Content grid container -->
		<div class="grid-container">
			<!-- will split up test_profile template into separate files -->
			<?php include TEMPLATES.'profile_page.inc.php'; ?>
		</div> <!-- End Grid Container -->
	<?php 
	}
	else{ // Profile hasn't been activated yet
		include UTILITIES.'brand_img.inc.php';
	?>
		<center><h5><?php echo $target->name() . ($param_type=='user'?' hasn\'t activated their account yet':' is no longer active'); ?><br/><br/></h5><p>Return to the <a href=<?php echo BASE_URL;?>>Home Page</a></p></center>
	<?php
	}
	?>
	
	</div> <!-- boxtar-content -->

<?php 
	include FOOTER;
?>
