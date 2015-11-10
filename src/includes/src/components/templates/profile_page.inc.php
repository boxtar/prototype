<?php	# Template for view profile pages
		# Will pull together all necessary templates to create the full profile layout (TODO)
		
// If INCLUDED isn't set then this hasn't been accessed properly.
// Redirect user away to home page. Have to redirect manually as no config included
if(!defined('INCLUDED')){
	echo 'Access Denied. <a href="http://dev.boxtar.uk">Return to Home Page</a>';
	exit();
}	
?>
<div id="profile-content-wrap">
	<div class="grid-container">
		<div class="grid-row">
			<?php
				$data = $target->data();
				
				
				$avatar = "get_img.php?img=";
				//$avatar .= $param_type.'/'.$data->prof_link.'/img/prof/'.$data->avatar_link;
				$avatar .= $target->avatar();
			?>
			<div class="col-3" id="avatar" style="background-image:url('<?php //echo $avatar; ?>')">
				<center>
					<p><img src="<?php echo $avatar; ?>" alt="avatar" class="user-profile-img"></p>
					<?php if($current_users_profile) echo '<br/><p><a href="edit_user.php">Edit Profile</a></p>'; ?>
				</center>
			</div><!--avatar-->
			<div class="col-4" id="basic-info">
				<p>
					<h3 class="uppercase"><?php echo $target->name(); ?></h3>
					<br/>
					<?php if($param_type!='user') echo '<p><b>Genre:</b> '.$target->get_genre().'</p>'; ?>
					<br/>
					<p><b>Location:</b> <?php echo escape($data->loc); ?></p>
					<br/>
					<p><b>Profile Link:</b> <a href="<?php echo BASE_URL.$param_type.'/'.escape($data->prof_link); ?> "> <?php echo BASE_URL.$param_type.'/'.escape($data->prof_link); ?></a></p>
					<br/>
					<p><b>Short Bio:</b><br/><?php echo nl2br(escape($data->bio)); ?></p>
					<br/>
				</p>
			</div><!--basic-info-->
			<div class="col-3" id="mibox">
				<p>
					&quot;MiBox&quot;
					<br/><br/>
					Enter some starred groups in here
				</p>
			</div><!--Mibox-->
		</div><!--grid-row-->
		
		<br/><br/>
		
		<div class="grid-row">
			<div id="prof-main-content-wrap">
				<div class="grid-container">
					<div class="grid-row">
						<div class="col-10" id="music-section"><p>Music Player</p></div>
						<div class="col-10" id="video-section"><p>Video Player</p></div>
						<div class="col-10" id="image-gallery-section"><p>Image Gallery</p></div>
					</div>
				</div>
			</div><!--prof-main-content-wrap-->

			<div id="prof-blog-content">
				<div class="grid-container">
					<div class="grid-row">
						<?php 
						//only need the post submission elements if user is viewing their own profile
						if($current_users_profile){
						?>
						<div class="col-10">
							<div class="post-submit-wrapper">
								<!--<div class="post-submit-img-wrapper">
									<img src="images/avatar" alt="" class="post-submit-img"/>
								</div>-->
								<div class="post-submit-header-wrapper">
									<div class="post-submit-header-caption">
										<p><?php echo $target->name() ?>: </p>
									</div>
									<div class="post-submit-header-button-wrapper">
										<div class="post-submit-header-button" id="post-button"><center>Share</center></div>
									</div>
								</div>
								<div class="post-submit-content-wrapper">
									<textarea id="post-content" placeholder="Type Away..."></textarea>
								</div>
							</div><!--post-submit-wrapper-->
						</div>
						<?php
						} // if($current_users_profile)
						?>
						<div class="col-10">
							<ul class="posts-list">
								<?php
								// Post manager instance for retrieving posts:
								$post_manager = Post_Manager::getInstance();
								// Retrieve all posts related to the targetted profile:
								$posts_query = $post_manager->get_posts($target);
								
								if($posts_query){
								foreach($posts_query as $post_data):
								?>
								<li class="post" id="<?php echo $post_data['id']; ?>">
									<div class="post-img-wrapper">
										<img src="<?php echo $avatar; ?>" alt="" class="post-img"/>
									</div>
									<div class="post-content-wrapper">
										<h3 class="post-username"><?php echo $post_data['user']; ?></h3>
										<div class="post-content">
											<p><?php echo $post_data['content']; ?></p>
										</div>
									</div>
									<?php
									if($current_users_profile){
									?>
									<div class="post-buttons-wrapper">
										<ul class="post-buttons">
											<li class="post-button"><center><i class="fa fa-times"></i></center></li>
											<li class="post-button"><center><i class="fa fa-pencil-square-o"></i></center></li>
										</ul>
									</div>
									<?php
									}
									?>
								</li><!-- post -->
								<?php endforeach;
								}else{?>
								<li class="post">
									<div class="post-content-wrapper">
										<h3 class="post-username">Boxtar UK</h3>
										<div class="post-content">
											<p><?php echo $target->name(); ?> has no posts to display yet</p>
										</div>
									</div>
								</li>
								<?php
								}
								?>
							</ul><!-- posts-list -->
							<?php 
							//only need the post submission elements if user is viewing their own profile
							if($current_users_profile){
							?>
							<input type="hidden" name="user" id="user" value="<?php echo $current_user->profile_link(); ?>" />
							<input type="hidden" name="target" id="target" value="<?php echo $target->profile_link(); ?>" />
							<input type="hidden" name="target_type" id="target_type" value="<?php echo $param_type; ?>" />
							<input type="hidden" name="token" id="token" value="<?php echo Token::generate(); ?>" />
							<?php
							}
							?>
						</div><!--posts-lists col-10 container-->
					</div>
				</div>
			</div><!--prof-blog-content-->
		</div><!--grid-row-->
	</div><!--grid-container-->
</div>
<br/>
<br/>
<br/>
<br/>
<br/>
