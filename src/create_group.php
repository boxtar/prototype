<?php 
	// Grab configuration
	require_once 'includes/src/core/config.inc.php';
	// Ensure user is logged in or redirect
	require_once UTILITIES.'authenticate_user.inc.php';
	$page_title="Create A Group";
	include HEADER;
	include UTILITIES.'brand_img.inc.php';
	
	$user = new User();
	
	if(isset($_POST['submitted'])){
		if (Token::check($_POST['token'])){
			// Trim all of the posted inputs:
			$trimmed=array_map('trim', $_POST);
			// Assume inputs are invalid:
			$name = $type = $genre = false;
			// Validate inputs:
			require_once(UTILITIES.'validate_input.php');
			/* Nested Ternary */
			(isset($trimmed['name']) && !empty($trimmed['name'])) ? ((validate_input($trimmed['name'], 'group_name') ) ? $name=$trimmed['name'] : print '<center><p class="red">- Group name is not valid</p></center>') : print '<center><p class="red">- A Name is Required</p></center>';
			(isset($trimmed['group_type']) && !empty($trimmed['group_type'])) ? ((is_numeric($trimmed['group_type']) && $trimmed['group_type']>=1 && $trimmed['group_type']<=3) ? $type=$trimmed['group_type']  :  print '<center><p class="red">- Invalid Group Type provided<br/>'.$trimmed['group_type'].'</p><center>') : print '<center><p class="red">- A Group Type is required<br/></p><center>';
			
			if($type){
				switch($type){
					case Group_Manager::MUSIC:
						(isset($trimmed['genre']) && !empty($trimmed['genre'])) ? ((is_numeric($trimmed['genre']) && $trimmed['genre']>=1 && $trimmed['genre']<=10) ? $genre=$trimmed['genre'] : print '<center><p class="red">- Genre should be a numeric value</p></center>') : print '<center><p class="red">- A Genre is required<br/></p><center>';
						break;
					case Group_Manager::DANCE:
						(isset($trimmed['style']) && !empty($trimmed['style'])) ? ((is_numeric($trimmed['style']) && $trimmed['style']>=1 && $trimmed['style']<=4) ? $genre=$trimmed['style'] : print '<center><p class="red">- Style should be a numeric value</p></center>') : print '<center><p class="red">- A Style is required<br/></p><center>';
						break;
					case Group_Manager::COMEDY:
						(isset($trimmed['category']) && !empty($trimmed['category'])) ? ((is_numeric($trimmed['category']) && $trimmed['category']>=1 && $trimmed['category']<=4) ? $genre=$trimmed['category'] : print '<center><p class="red">- Category should be a numeric value</p></center>') : print '<center><p class="red">- A Category is required<br/></p><center>';
						break;
				}
			}
			
			if($name && $type && $genre){
				$group_mngr = Group_Manager::getInstance();
				$new_group = $group_mngr->create_new_group($user, [
					'name'=>$name,
					'genre'=>$genre,
					'type'=>$type
				]);
				
				if($new_group->exists()){
					echo '<br/><center><h5 class="uppercase">New Music Group Successfully Created!</h5></center><br/>';
					echo '<center><p class="">Head over to <a href="edit_group.php?grp='.$new_group->data()->prof_link.'" class="dark-green"><b> "' . escape($name) . '" </b></a> edit page to customise your page and start sharing what you love with others</p><center>';
					include FOOTER;
					exit();
				}
				else{
					trigger_error('<br/><b>Error creating a new music group</b><br/>');
					include FOOTER;
					exit();
				}
			}
			else{
				echo '<br/><center><h5 class="red">Please review above errors and try again</h5></center><br/>';
			}
		}
		else{
			echo '<br/><center><p><span class="red">Invalid Form Submission. Please Try Again</p></center>';
		}
	}
?>

<div class="boxtar-content">
	<br/>
	<?php include FORMS . 'new_group_form.inc.php'; ?>
</div>

<?php 
	include FOOTER;
?>