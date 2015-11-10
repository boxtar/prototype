<?php # list memberships - only available for logged in users atm
      # will add ability to make this public via get

	// Grab configuration
	require_once 'includes/src/core/config.inc.php';
	// Ensure user is logged in or redirect
	require_once UTILITIES.'authenticate_user.inc.php';
	
	// Function to return the html for each group:
	function group_output($group, $group_type, $users_access_level){
		$output = '<div class="section group border-bottom">
						<div class="col">
							<p><img style="width:50px;height:auto;vertical-align:middle" src="get_img.php?img='.$group_type.'/'.$group->profile_link().'/img/prof/'.$group->data()->avatar_link.'" alt="img" />&nbsp;'.
							($users_access_level > 1 ? '<a href="edit_group.php?grp='.$group->profile_link().'" class="uppercase">' . $group->name() . '</a>&nbsp;&nbsp;':'<span class="uppercase">'.$group->name().'</span>&nbsp;&nbsp;').
							'<a href="'.BASE_URL.$group_type.'/'.$group->profile_link().'" class="">View Profile</a>&nbsp;&nbsp;'
							.'<a href="'. BASE_URL . 'remove_group.php?_i=' . $group->data()->id .'" class="">&nbsp;Leave Group</a></p>
						</div>
					</div>';
		return $output;
	}
	
	$user = new User();
	
	$page_title = $user->name() ? $user->name() . ' | Group Management' : 'Group Management';
	include HEADER;
	
	$structured_output='';
	$groups=[];
	if($user->exists()){
		foreach($user->get_groups() as $group){				
			switch($group->group_type()){
				case Group_Manager::MUSIC:
					$groups['music'][]=$group;
					break;
				case Group_Manager::DANCE:
					$groups['dance'][]=$group;
					break;
				case Group_Manager::COMEDY:
					$groups['comedy'][]=$group;
					break;
				default:
					$groups['music'][]=$group;
					break;
			}
		}
	}
?>
	
	<div class="boxtar-content">
		<br/><br/>
		<center><h4 class="uppercase">group management</h4></center>
		<br/><br/>
			<!-- MUSIC SECTION -->
			<div class="section group border-bottom">
				<div class="col uppercase"><h5><b>music groups</b><h5></div>
			</div>
			<?php
				if(!empty($groups['music'])){
					foreach($groups['music'] as $group)
						echo group_output($group, 'music', $user->group_access_level($group));
				}
				else{
					echo '<div class="section group border-bottom"><div class="col"><p>You are not a member of any music groups</p></div></div>';
				}
			?>
			<div class="grid-container">
				<div class="grid-row">
					<div class="col-10 align-right"><br/><p><a href=# class="new-music-modal"><i class="dark-green fa fa-plus-square-o"></i>&nbsp;Add New Music Page</a></p></div>
				</div>
			</div>
			
			<br/><br/>
			
			<!-- DANCE SECTION -->
			<div class="section group border-bottom">
				<div class="col uppercase"><h5><b>dance groups</b><h5></div>
			</div>
			<?php
				if(!empty($groups['dance'])){
					foreach($groups['dance'] as $group)
						echo group_output($group, 'dance', $user->group_access_level($group));
				}
				else{
					echo '<div class="section group border-bottom"><div class="col"><p>You are not a member of any dance groups</p></div></div>';
				}
			?>
			<div class="grid-container">
				<div class="grid-row">
					<div class="col-10 align-right"><br/><p><a href=# class="new-dance-modal"><i class="dark-green fa fa-plus-square-o"></i>&nbsp;Add New Dance Page</a></p></div>
				</div>
			</div>
			
			<br/><br/>
			
			<!-- COMEDY SECTION -->
			<div class="section group border-bottom">
				<div class="col uppercase"><h5><b>comedy groups</b><h5></div>
			</div>
			<?php
				if(!empty($groups['comedy'])){
					foreach($groups['comedy'] as $group)
						echo group_output($group, 'comedy', $user->group_access_level($group));
				}
				else{
					echo '<div class="section group border-bottom"><div class="col"><p>You are not a member of any comedy groups</p></div></div>';
				}
			?>
			<div class="grid-container">
				<div class="grid-row">
					<div class="col-10 align-right"><br/><p><a href=# class="new-comedy-modal"><i class="dark-green fa fa-plus-square-o"></i>&nbsp;Add New Comedy Page</a></p></div>
				</div>
			</div>
	</div>
	
<?php
	include FOOTER;
?>
