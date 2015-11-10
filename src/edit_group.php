<?php 
include 'includes/src/core/config.inc.php';

include UTILITIES.'authenticate_user.inc.php';
// Set default page title:
$page_title='Manage Group';

if(isset($_GET['grp']) && !empty($_GET['grp'])){
	$trimmed_input = trim($_GET['grp']);
	// Get Group Manager Instance:
	$group_manager = Group_Manager::getInstance();
	// Instantiate User:
	$user = new User();
	// Instantiate Groups associated with User:
	$user->populate_groups();
	// Attempt to get targeted Group:	
	$group = $user->get_group($trimmed_input); 
	if(!empty($group) && $group->exists()){
		// Kill script if user doesn't have sufficient access rights:
		if($group_manager->user_access_level($user, $group) < ADMIN){
			include HEADER;
			include UTILITIES.'brand_img.inc.php';
			echo '<br/><center><p>You do not have management rights for '.escape($group->name()).'<br/>Return to <a href="'.BASE_URL.'group_mgmt.php">Group Management</a></p></center>';
			include FOOTER;
			exit();
		}
	}
	else{
		include HEADER;
		include UTILITIES.'brand_img.inc.php';
		echo '<br/><center><p>Oops! Invalid Group identifier provided<br/>Return to <a href="'.BASE_URL.'group_mgmt.php">Group Management</a></p></center>';
		include FOOTER;
		exit();
	}
}
elseif(isset($_POST['submitted'])){
	// Handle form submission
}
else{
	include HEADER;
	include UTILITIES.'brand_img.inc.php';
	echo '<br/><center><p>Oops! Invalid Group identifier provided<br/>Return to <a href="'.BASE_URL.'group_mgmt.php">Group Management</a></p></center>';
	include FOOTER;
	exit();
}

$page_title=escape($group->name());
include HEADER;

$users_group_access = $user->group_access_level($group);
$group_name = $group->name(); // Get Group Name as its output a lot
$group_prof = $group->data()->prof_link;
$group->populate_member_list(); // Instantiates all Users associated with the targeted Group
$group_members = $group->get_member_list(); // Get list of associated Users
// Impossible to have no group members at this stage in the script so throw error:
if(!$group_members){
	include HEADER;
	include UTILITIES.'brand_img.inc.php';
	trigger_error('<br/><center><p>Error occured in '.$_SERVER['PHP_SELF'].'<br/>No members in targeted Group</p></center>');
	include FOOTER;
	exit();
}

?>

<div class="boxtar-content">
	<div class="grid-container">
		<div class="grid-row">
			<div class="col-10">
			<br/><br/>
				<center><h4 class="uppercase">Manage <?php echo escape($group_name); ?></h4></center>
			</div>
			<div class="col-10" id="member-list">
				<br/><br/>
				<h5 class="uppercase">member list</h5>
				<div class="grid-container">
					<?php
					foreach($group_members as $member){
						//$mem_id=$member->data()->id;
						$name=$member->name();
						$prof=$member->data()->prof_link;
						$ava=$member->data()->avatar_link;
						
						echo	'<div class="grid-row">
									<div class="col-10">
										<p>
										<img src="get_img.php?img='.$ava.'" alt="avatar" style="width:50px;height:auto;vertical-align:middle">&nbsp;&nbsp;
										<a href="'.BASE_URL.'user/'.$prof.'" class="uppercase">'.$name.'</a>&nbsp;&nbsp;'.
										($users_group_access == 3 ? '<a href=#>Change Permissions</a>&nbsp;&nbsp;<a href="remove_user_from_group.php?user='.$prof.'&group='.$group_prof.'">Remove Member</a>':'').'
										</p>
									</div>
								</div>';
						
					}
					?>
				</div>
			</div>
		</div>
	</div>

<?php
if($users_group_access==3){ ?>
<div class="section group">
	<div class="col"><br/><p><a href=# data-group="<?php echo $group_prof; ?>" class="add-user-modal"><i class="dark-green fa fa-plus fa-lg"></i>&nbsp;Add User to Group</a></p></div>
</div>
<?php 
} ?>
</div>
<?php 
	include FOOTER;
?>
