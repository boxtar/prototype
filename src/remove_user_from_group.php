<?php
require 'includes/src/core/config.inc.php';
// ensure current user is logged in:
include UTILITIES.'authenticate_user.inc.php';
$page_title='Remove A User from A Group';
include HEADER;
include UTILITIES.'brand_img.inc.php';

// Logged in user:
$user = new User();
// Group Manager:
$group_manager = Group_Manager::getInstance();

if(isset($_GET['user']) && !empty($_GET['user']) && isset($_GET['group']) && !empty($_GET['group'])){
	// Trim user input:
	$trimmed_input=array_map('trim', $_GET);
	
	// User being removed:
	$user_to_remove = new User($trimmed_input['user']);
	// Check user being removed exists:
	if(!$user_to_remove->exists()){
		echo '<center><p>User doesn\'t exist</p></center>';
		include FOOTER;
		exit();
	}
	
	// Group to remove user from:
	$group = new Group($trimmed_input['group']);
	// Check group exists:
	if(!$group->exists()){
		echo '<center><p>Group doesn\'t exist</p></center>';
		include FOOTER;
		exit();
	}
	
	// If user is attempting to remove themself then redirect to Leave Group script:
	if($user->profile_link()===$user_to_remove->profile_link()){
		echo '<center><p>Redirect to Leave Group</p></center>';
		include FOOTER;
		exit();
	}
	
	// Ensure logged in user is owner before proceeding:
	if($group_manager->user_access_level($user, $group) < OWNER){
		echo '<center><p>Insufficient privileges</p></center>';
		include FOOTER;
		exit();
	}
	
	// Ensure user being removed belongs to group:
	if(!$group_manager->is_user_active_member($user_to_remove, $group)){
		echo '<center><p>'.$user_to_remove->name().' is not a member of '.$group->name().'</p></center>';
		include FOOTER;
		exit();
	}
	
	// Display the confirmation form:
	$remove_conf_msg="Are you sure you want to remove <span class=\"dark-grey\">{$user_to_remove->name()}</span> from <span class=\"dark-grey\">{$group->name()}</span>?";
	include FORMS.'remove_user_from_group_form.inc.php';
	
}
elseif(isset($_POST['submitted'])){
	// Check valid token exists:
	if(isset($_POST['token']) && Token::check($_POST['token'])){
		// Create user to be removed:
		$user_to_remove = new User($_POST['user']);
		// Create group to be modified:
		$group = new Group($_POST['group']);
		// If user confirmed deletion of member:
		if($_POST['delete']==='yes'){
			$status = $group_manager->remove_user_from_group($user_to_remove, $group);
			if($status['status']===true){
				echo '<center><p>'.$status['msg'].'</p></center>';
			}
			else{
				echo '<center><p>'.$status['msg'].'</p></center>';
			}
		}
		else{
			$url = $group->exists() ? 'edit_group.php?grp='.$group->profile_link() : 'manage_groups.php';
			echo $url;
			redirect($url);
		}
	}
	else{
		echo '<center><p>Invalid form submission<br/>Please return to <a href="'.BASE_URL.'">Boxtar UK</a></p></center>'; 
	}
}
else{
	echo '<center><p>Page accessed with errors<br/>Return to <a href="'.BASE_URL.'">Boxtar UK</a></p></center>';
}

?>

<?php
include FOOTER;
?>
