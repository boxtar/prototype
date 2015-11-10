<?php 
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
	if(!defined('INCLUDED')){
		die('<center>
			<h1>Access Denied</h1>
			<p>Return to <a href="http://dev.boxtar.uk/">Boxtar UK</a></p>
			</center>');
	}
}
else if((strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'))
	die('<center>
		<h1>Access Denied</h1>
		<p>Return to <a href="http://dev.boxtar.uk/">Boxtar UK</a></p>
		</center>');

if(session_status()===PHP_SESSION_NONE){
	// If no active session then config hasn't been included so start session and include required class files:
	session_start();
	require('../../classes/Session.php');
	require('../../classes/Token.php');
}
?>

<!-- Form for adding a user to a group -->
<center>
	<br/>
	<br/>
	<br/>
	<h4 class="light-blue uppercase" style="letter-spacing: 2px">Add A User To <?php echo ((isset($group) && $group->exists())?$group->name():'A Group'); ?></h4>
	</br>
</center>
<!-- Borrow login-form-wrap for it's max width -->
<div class="login-form-wrap">
	<form action="test.php" method="post">
		
		<div class="grid-container">
			<div class="grid-row">
				<div class="col-10"><label for="name"><strong>Email or Profile Link of User to Add<?php if(isset($group) && $group->exists()) echo ' to '.$group->name(); ?>: </strong></label></div>
			</div>
			<div class="grid-row">
				<div class="col-10"><input type="text" name="name" id="name" maxlength="100" value="<?php if(isset($_POST['name'])) echo escape(trim($_POST['name'])); ?>" required/></div>
			</div>
			<div class="grid-row">
				<div class="col-10"><label for="access"><strong>Give Edit Permissions?:</strong></label></div>
			</div>
			<div class="grid-row">
				<div class="col-10">
					<select name="access" id="access" required style="width:100%;height:30px;">
						<option value=1>No</option>
						<option value=2>Yes</option>
					</select>
				</div>
			</div>
			<br/>
			<div class="grid-row">
				<div class="col-10"><center><input type="submit" name="submit" value="Add User" /></center></div>
			</div>
		</div>
		<input type="hidden" name="group" value="<?php if(isset($_GET['grp']) && !empty(trim($_GET['grp']))) echo htmlentities(trim($_GET['grp'])); elseif(isset($group) && $group->exists()) echo htmlentities($group->profile_link()); ?>">
		<input type="hidden" name="submitted" value="TRUE"/>
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
	</form>
</div>