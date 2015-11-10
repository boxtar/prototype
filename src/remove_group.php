<?php 
	// Grab configuration
	require_once 'includes/src/core/config.inc.php';
	// Ensure user is logged in or redirect
	require_once UTILITIES.'authenticate_user.inc.php';
	
	$page_title="Leave Group";
	include HEADER;
	include UTILITIES.'brand_img.inc.php';
	echo <<<EOT
<center><p>Still to be implemented</p></center>
EOT;
	include FOOTER;
	exit();
	
	// REDO THIS SCRIPT USING OOP:
		
	// member id to remove user from
	$group_id=false;
	
	// PAGE ACCESSED WITH GET VARIABLE - VALIDATE VAR AND DISPLAY CONFIRMATION FORM
	if(isset($_GET['_i'])){
		$trimmed=trim($_GET['_i']);
		$group_id=(isset($trimmed) && is_numeric($trimmed)) ? $trimmed : false;
		
		// IF GET is set but is of an invalid type - redirect
		if(!$group_id){
			echo '<div class="boxtar-content"><br/><center><h4>Group not found</h4><br/><p>You are being redirected to Group Management</p></center></div>';
			redirect('group_mgmt.php?', 3);
		}
	}
	// CONFIRMATION FORM SUBMITTED
	elseif(isset($_POST['submitted'])){
		$trimmed=(trim($_POST['group_id']));
		$group_id=(isset($trimmed) && is_numeric($trimmed)) ? $trimmed : false;
		// IF POST is set but id is invalid - redirect : This should be impossible unless user tinkers with posted variables?
		if(!$group_id){
			echo '<div class="boxtar-content"><br/><h4>Invalid Input</h4><br/><p>You are being redirected to Group Management</p></div>';
			redirect('group_mgmt.php?', 3);
		}
	}
	// PAGE ACCESSED WITH NO REQUEST VARIABLES - ERROR
	else{
		// Display message and redirect
		echo '<div class="boxtar-content">
		<br/><br/><center><h4 class="">Page Accessed In Error</h4>
		<br/><p class="error">You are being redirected to your Group Management page</p></center></div>';
		redirect('group_mgmt.php?', 3);
	}
	
	// POST REQUEST (confirmation form submitted)
	if(isset($_POST['submitted'])){
		if($_POST['delete'] == "yes"){
			$db=DB::getInstance();
			
			// no_of_mems is defined during GET part of this IF statement (below) ONLY if it's an overseer account accessing this script
			// if no_of_mems is not defined (i.e the user accessing this script is not an overseer) then 0 is echoed to the hidden input
			// indicating to just remove the user from the member/group/act.
			
			// ONLY ONE MEMBER (AND IT MUST BE THE OVERSEER)
			if($_POST['no_of_mems'] == 1){
				$sql="UPDATE artist_music SET active = 0, date_closed = NOW() WHERE id=?";
				if(empty($db->query($sql, [$group_id])->errors())){
					$sql="UPDATE users_artist_music SET active = 0, date_closed = NOW() WHERE id=?";
					if(empty($db->query($sql, [(int)$_POST['um_id']])->errors())){
						//note: we do not change status_flag to allow overseer to reactivate the account
						include UTILITIES.'brand_img.inc.php';
						echo '<br/><br/><center><h4>You have removed "' . escape($_POST['group_name']) . '"</h4><br/><p><i>(remember you can reactivate again within 6 months)</i></p><br/><p>Return to <a href="group_mgmt.php">Group Management</a></p></center>';
					}
					else{
						// update of users_artist_music failed so undo update to artist_music
						$db->update("artist_music", ['id'=>$group_id], ['active'=>1, 'date_closed'=>'NULL']);
						trigger_error('Error updating DB in script '.trim($_SERVER['PHP_SELF'], '/').'<br/>DB Errors: '.implode('<br/>',$db->errors()));
					}
				}
				else{
					trigger_error('Error updating DB in script '.trim($_SERVER['PHP_SELF'], '/').'<br/>DB Errors: '.implode('<br/>',$db->errors()));
				}
			}
			// SHOULD NEVER GET HERE AS NO FORM DISPLAYED WHEN OVERSEER TRYING TO REMOVE MEMBER/GROUP/ACT WITH ACTIVE MEMBERS
			elseif($_POST['no_of_mems'] > 1){
				echo 'How did you get here? You weren\'t given the option to delete this group';
			}
			// NOT OVERSEER - PROCEED WITH REMOVING USER FROM MEMBER/GROUP/ACT
			else{
				//"users_artist_music", ['id'=>$_POST['um_id']], ['active'=>0]
				$sql="UPDATE users_artist_music SET active=0, date_closed=NOW() WHERE id=?";
				if(empty($db->query($sql, [(int)$_POST['um_id']])->errors())){
					echo '<br/><br/><center><h4>You have left ' . $_POST['group_name'] . '</h4><br/><p>Return to <a href="group_mgmt.php">Group Management</a></p></center>';
					//redirect('group_mgmt.php');
				}
				else{ // Deal with Error state
					trigger_error("Error occured while querying DB in script: " . trim($_SERVER['SCRIPT_NAME'], '/') . '<br/>DB Errors: ' . implode('<br/>', $db->errors()) . '<br/>');
				}
			}
		}
		else{ // User chose not to remove membership - display msg and redirect
			include UTILITIES.'brand_img.inc.php';
			echo '<div class="boxtar-content">
			<br/><br/><center><h4>You\'ll be glad to know that you\'re still a member of "' . $_POST['group_name'] . '"</h4><br/>
			<p>Return to <a href="group_mgmt.php">Group Management</a></p></center></div>';
		}
	}
	// GET REQUEST
	else{ // N.B: No need to check for valid input as this is reassured at top of script
		// Need to grab info from DB
		$db=DB::getInstance();
		// SQL
		$sql="SELECT m.name, um.id, um.status_flag FROM users_artist_music AS um INNER JOIN artist_music AS m ON(um.am_id=m.id) WHERE (um.u_id=? AND (um.am_id=? AND (um.active!=0 AND (m.active!=0))))";
		// DB QUERY AND ERROR CHECK
		if(empty($db->query($sql, [ $_SESSION['user_id'], $group_id ])->errors())){
			// MEMBER/GROUP/ACT FOUND
			if($db->num_rows()==1){
				// Save returned information to variables as will be used in confirmation form and neatens up code (a bit)
				$group_name = $db->first_result()->name;
				$um_id = $db->first_result()->id;
				$status_flag = $db->first_result()->status_flag;
				
				// USER IS OVERSEER - NEED EXTRA WORK
				if($status_flag==3){
					// Check to see how many users are currently a member of the act the overseer is trying to delete
					// If more than 1 then overseer will have to remove the members first.
					$sql = "SELECT COUNT(id) AS no_of_mems FROM users_artist_music WHERE am_id=? AND active!=0";
					if(empty($db->query($sql, [$group_id])->errors())){
						$no_of_mems = $db->first_result()->no_of_mems;
						if($no_of_mems > 1){
							include UTILITIES.'brand_img.inc.php';
							echo '<br/><center><h5>'.$group_name.' still has active members</h5>';
							echo '<br/><p>Please visit <a href="edit_group.php?_i='.$group_id.'">'.$group_name.'</a> management page to Promote a member to overseer or remove all members and try again</p>';
						}
						else{
							include UTILITIES.'brand_img.inc.php';
							echo '<br/><center><h5>You are about to remove "'.$group_name.'"</h5><br/>
							<div class="md-container"><p><i>(You have the ability to reactivate "'.$group_name.'" again but you must do so within 6 months or it will be completely removed from our system)</i></p></div></center>';
							$remove_conf_msg='Are you <b>really</b> sure you want to remove "'.$group_name.'"?';
							include FORMS.'remove_group_form.inc.php';
						}
					}
					else{
						trigger_error("Error occured while querying DB in script: " . trim($_SERVER['SCRIPT_NAME'], '/') . '<br/>DB Errors: ' . implode('<br/>', $db->errors()) . '<br/>');
					}
				}
				else{ // User belongs to act and is not an overseer - Allow user to remove them-self
					$remove_conf_msg='Are you sure you want to leave "'.$group_name.'"?';
					include FORMS.'remove_group_form.inc.php';
				}
			}
			// USER IS MAPPED TO MEMBER/GROUP/ACT MORE THAN ONCE - SHOULD BE IMPOSSIBLE
			elseif($db->num_rows()>1){
				trigger_error('More than one row returned from query in script: ' . trim($_SERVER['SCRIPT_NAME'], '/') . '<br/>');
			}
			// MEMBER/GROUP/ACT DOESN'T EXIST OR USER DOESN'T BELONG TO IT
			else{
				// CHECK IF MEMBER/GROUP/ACT EXISTS
				$sql="SELECT name FROM artist_music WHERE id=? AND active!=0";
				if(empty($db->query($sql, [$group_id])->errors())){
					// THE MEMBER/GROUP/ACT PROVIDED DOES EXIST SO USER IS NOT A MEMBER
					if($db->num_rows()==1){
						echo '<br/><br/><center><h4>Sorry - You\'re not a member of "' . $db->first_result()->name . '"</h4></center>';
						echo '<br/><center><p>Return to <a href="group_mgmt.php">Group Management</a></p></center>';
					}
					// MEMBER/GROUP/ACT DOES NOT EXIST
					else{
						echo '<center><br/><p>Sorry - Invalid group specified<br/><br/>Redirecting to Group Management...</p></center>';
						redirect('group_mgmt.php', 3);
					}
				}
				// DB QUERY ERROR
				else{
					trigger_error("Error occured while querying DB in script: " . trim($_SERVER['SCRIPT_NAME'], '/') . '<br/>DB Errors: ' . implode('<br/>', $db->errors()) . '<br/>');
				}
			}
		}
		else{
			trigger_error("Error occured while querying DB in script: " . trim($_SERVER['SCRIPT_NAME'], '/') . '<br/>DB Errors: ' . implode('<br/>', $db->errors()) . '<br/>');
		}
	}
	
	include FOOTER;
?>
