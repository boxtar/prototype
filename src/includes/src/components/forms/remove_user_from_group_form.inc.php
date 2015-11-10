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
?>

<div class="boxtar-content">
	<br/><br/>
	<?php if(!isset($remove_conf_msg)) $remove_conf_msg = "Are you sure you want to proceed?"; ?>
	<center><h5><?php echo $remove_conf_msg; ?></h5></center>
	<br/>
	
	<form action="remove_user_from_group.php" method="post">
		<div class="section group align-center">
			<select name="delete" required autofocus style="width:65px;height:30px;">
				<option value="no">No</option>
				<option value="yes">Yes</option>
			</select>
		</div>
		<br/>
		<br/>
		<div class="section group align-center">
			<input type="submit" name="submit" value="Confirm"/>
		</div>
		<input type="hidden" name="submitted" value="TRUE"/>
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>"/>
		<input type="hidden" name="group" value="<?php echo $group->profile_link(); ?>"/>
		<input type="hidden" name="user" value="<?php echo $user_to_remove->profile_link(); ?>"/>
	</form>
</div>
