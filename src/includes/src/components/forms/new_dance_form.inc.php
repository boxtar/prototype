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
<!-- Form for creating a new music group -->

<center>
	<br/>
	<br/>
	<br/>
	<h4 class="light-blue uppercase" style="letter-spacing: 2px">create a new dance group</h4>
	</br>
</center>

<!-- Borrow login-form-wrap for it's max width -->
<div class="login-form-wrap">
	<form action="create_group.php" method="post">
		<div class="grid-container">
			<div class="grid-row">
				<div class="col-10"><label for="name"><strong>Dance Group Name: </strong></label></div>
			</div>
			<div class="grid-row">
				<div class="col-10"><input type="text" name="name" id="name" maxlength="100" value="<?php if(isset($_POST['name'])) echo escape(trim($_POST['name'])); ?>" required/></div>
			</div>
			<div class="grid-row" id="music" style="display:">
				<div class="col-10"><label for="style"><strong>Style: </strong></label></div>
				<div class="col-10">
					<select name="style" id="style" required style="width:100%;height:30px;">
						<option value=1>Freestyle</option>
						<option value=2>Ballroom</option>
						<option value=3>Breakdancing</option>
						<option value=4>Swing</option>
					</select>
				</div>
			</div>
		</div>
		<br/>
		<center><input type="submit" name="submit" value="Create" /></center>
		<input type="hidden" name="submitted" value="TRUE"/>
		<input type="hidden" name="group_type" value=2 />
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
	</form>
</div>
