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

<center> 
	<h4 class="" style="letter-spacing: 2px">Please Log in or <a href="register.php" class="green">Sign Up</a></h4>
</center>
<br/><br/>
	
<div class="login-form-wrap">
	<form action="login.php" method="post">
		<div class="grid-container">
			<div class="grid-row">
				<div class="col-10"><label for="email"><strong>Email or Profile Link</strong></label></div>
			</div>
			<div class="grid-row">
				<div class="col-10"><input type="text" name="email" id="email" maxlength="80" value="<?php if (isset($trimmed['email'])) echo $trimmed['email']; ?>" autofocus /></div>
			</div>
			<div class="grid-row">
				<div class="col-10"><label for="pass"><strong>Password</strong></label></div>
			</div>
			<div class="grid-row">
				<div class="col-10"><input type="password" name="pass" id="pass" maxlength="20"/></div>
			</div>
		</div>
		<br/>
		<center><input type="submit" name="submit" value="Login" /></center>
		<input type="hidden" name="submitted" value="TRUE"/>
		<!-- Can't include CSRF prevention token as this form in grabbed by AJAX -->
	</form>
</div>
