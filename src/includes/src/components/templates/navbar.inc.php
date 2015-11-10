<!-- Top Nav Bar -->

<?php 
	// If INCLUDED isn't set then this hasn't been accessed properly.
	// Redirect user away to home page. Have to redirect manually as no config included
	if(!defined('INCLUDED')){
		echo 'Access Denied. <a href="http://dev.boxtar.uk">Return to Home Page</a>';
		exit();
	}
	$user=new User();
?>

<nav class="navbar cf">
	<div class="nav-content cf">
		<div class="nav-left cf">
			<ul class="nav-list">
				<li class="dropdown-toggler"><a  href="<?php echo BASE_URL; ?>"><b>BOXTAR UK</b></a>
					<ul class="nav-list-dropdown">
						<li><a href="<?php echo BASE_URL; ?>"><i class="fa fa-home fa-fw"></i>&nbsp; Home</a></li>
						<li><a href=#>Music</a></li>
						<li><a href=#>Dance</a></li>
						<li><a href=#>Comedy</a></li>
						<li><a href=#>About</a></li>
						<li><a href=#>Contact</a></li>
					</ul>
				</li>
			</ul>
		</div>
		<div class="nav-right">
			<ul class="nav-list cf">
				<?php 
					if($user->logged_in() && !stripos($_SERVER['REQUEST_URI'], 'logout.php')){
						// IF logged in user is a site admin: Display appropriate links for access level
						if($user->data()->access_level > 0){ ?>
							<li class="dropdown-toggler"><a href="<?php echo BASE_URL . 'admin_panel.php'; ?>"><i class="fa fa-wrench fa-lg"></i>&nbsp; Site Admin</a>
								<ul class="nav-list-dropdown">
									<li><a href=#>Users</a></li>
									<li><a href=#>Comments</a></li>
									<?php 
									if($user->data()->access_level > 1)
										echo '<li><a href="'.BASE_URL.'test.php">Test Page</a></li>';
									?>
								</ul>
							</li>
				  <?php } // End access_level if ?> 
						<li class="dropdown-toggler"><a href="profile.php?type=user"><i class="fa fa-cog fa-lg"></i>&nbsp; Manage Account</a>
							<ul class="nav-list-dropdown">
								<li><a href="<?php echo BASE_URL . 'profile.php'; ?>">View Profile</a></li>
								<li><a href="<?php echo BASE_URL . 'edit_user.php'; ?>">Edit Profile</a></li>
								<li><a href="<?php echo BASE_URL . 'manage_groups.php'; ?>">Manage Groups</a></li>
								<li><a href="<?php echo BASE_URL . 'change_password.php'; ?>">Change Password</a></li>
							</ul>
						</li>
						<li><a href="<?php echo BASE_URL . 'logout.php'; ?>"><i class="fa fa-user-times fa-lg"></i>&nbsp; Logout</a></li>
			  <?php }
					// IF not logged in
					else{ ?>
						<li><a href="<?php echo BASE_URL . 'register.php'; ?>"><i class="fa fa-user-plus fa-lg"></i>&nbsp; Sign Up</a></li>
						
						<li><a href=<?php echo BASE_URL.'login.php'; ?> class="login-modal"><i class="fa fa-user-plus fa-lg"></i>&nbsp; Login</a></li>
			  <?php } ?>
			</ul>
		</div>
	</div>
</nav>