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
	<center><h4><?php echo $remove_conf_msg; ?></h4></center>
	<br/>
	
	<form action="" method="post">
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
		<input type="hidden" name="group_id" value="<?php echo $group_id; //No need to escape as already confirmed as existing in DB ?>"/>
		<input type="hidden" name="um_id" value="<?php echo $um_id; ?>"/>
		<input type="hidden" name="group_name" value="<?php echo $group_name; ?>"/>
		<input type="hidden" name="no_of_mems" value="<?php if(isset($no_of_mems)) echo $no_of_mems; else echo 0; ?>"/>
	</form>
</div>