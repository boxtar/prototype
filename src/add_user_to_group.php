<?php 
require 'includes/src/core/config.inc.php';
// ensure current user is logged in:
include UTILITIES.'authenticate_user.inc.php';
// default page title:
$page_title = 'Add User To Group';

if(isset($_POST['submitted'])){
	if (Token::check($_POST['token'])){
		// trim user input:
		$trimmed=array_map('trim', $_POST);
		// get instance of Group Manager:
		$group_manager = Group_Manager::getInstance();
		// currently logged in user:
		$curr_user = new User();
		
		// Validate provided access permissions:
		if(isset($trimmed['access']) && is_numeric($trimmed['access']) && ($trimmed['access']>0 && $trimmed['access']<3)){
			$access_rights=$trimmed['access'];
		}
		else{
			$access_rights=false;
			echo '<center><p class="red">Invalid access rights provided</p></center>';
		}
		
		// user to add to group:
		$user_to_add = new User(strtolower($trimmed['name']));

		// group being added to:
		$group = new Group(strtolower($trimmed['group']));
		
		// Set page title:
		$page_title = 'Add User To ';
		$page_title .= $group->exists() ? $group->name() : 'Group';
		include HEADER;
		include UTILITIES.'brand_img.inc.php';

		$status = $group_manager->add_user_to_group($user_to_add, $group, ADMIN);
		if($status['status']===true)
			echo '<br/><center><p><span class="green">'.escape($status['msg']).'</span><br/>Return to <a href="manage_groups.php">Group Management</a></p></center>';
		else
			echo '<br/><center><p><span class="red">'.escape($status['msg']).'</span><br/>Return to <a href="manage_groups.php">Group Management</a></p></center>';
	}
	else{
		include HEADER;
		include UTILITIES.'brand_img.inc.php';
		echo '<br/><center><p><span class="red">Invalid Form Submission. Please Try Again</p></center>';
	}
}
else{
	redirect('manage_groups.php');
}
include FORMS.'add_user_to_group_form.inc.php';
include FOOTER;
?>
