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
	<h4 class="light-blue uppercase" style="letter-spacing: 2px">create a new group</h4>
	</br>
</center>

<!-- Borrow login-form-wrap for it's max width -->
<div class="login-form-wrap">
	<form action="create_group.php" method="post">
		<div class="grid-container">
			<div class="grid-row">
				<div class="col-10"><label for="name"><strong>Group Name: </strong></label></div>
			</div>
			<div class="grid-row">
				<div class="col-9"><input type="text" name="name" id="name" class="ajax-validate-input" maxlength="100" value="<?php if(isset($_POST['name'])) echo escape(trim($_POST['name'])); ?>" data-validate="group_name" required/></div>
				<div class="col-1 validation-status"><?php if(!isset($trimmed['name'])) echo '<div class="status" data-status="failed"></div>'; elseif(!validate_input($trimmed['name'], 'group_name')) echo '<div class="status" data-status="failed"></div>'; ?></div>
			</div>
			<div class="grid-row">
				<div class="col-10"><label for="genre"><strong>Group Type: </strong></label></div>
			</div>
			<!-- GROUP TYPE -->
			<div class="grid-row">
				<div class="col-10">
					<select name="group_type" id="group-type" required autofocus style="width:100%;height:30px;">
						<option value=1 data-type="music">Music</option>
						<option value=2 data-type="dance">Dance</option>
						<option value=3 data-type="comedy">Comedy</option>
					</select>
				</div>
			</div>
			<!-- MUSIC GENRES -->
			<div class="grid-row" id="music" style="display:">
				<div class="col-10"><label for="genre"><strong>Genre: </strong></label></div>
				<div class="col-10">
					<select name="genre" id="genre" required style="width:100%;height:30px;">
						<option value=1>Alternative</option>
						<option value=2>Blues</option>
						<option value=3>Country</option>
						<option value=4>Dance</option>
						<option value=5>Electronic</option>
						<option value=6>Jazz</option>
						<option value=7>Pop</option>
						<option value=8>Rock</option>
						<option value=9>R&amp;B/Soul</option>
						<option value=10>Reggae</option>
					</select>
				</div>
			</div>
			<!-- DANCE STYLES -->
			<div class="grid-row" id="dance" style="display:none">
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
			<!-- COMEDY CATEGORIES -->
			<div class="grid-row" id="comedy" style="display:none">
				<div class="col-10"><label for="category"><strong>Category: </strong></label></div>
				<div class="col-10">
					<select name="category" id="category" required style="width:100%;height:30px;">
						<option value=1>Under 18 safe</option>
						<option value=2>Over 18 only</option>
						<option value=3>Political</option>
						<option value=4>Crude</option>
					</select>
				</div>
			</div>
		</div>
		<br/>
		<center><input type="submit" name="submit" value="Create" /></center>
		<input type="hidden" name="submitted" value="TRUE"/>
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
	</form>
</div>
