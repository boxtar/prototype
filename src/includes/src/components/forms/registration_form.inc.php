<!-- Registration Form for including
	 Currently included in register.php and index.php -->
	 
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
	<h4 class="uppercase" style="letter-spacing: 2px">Create an Account - It's Quick & Easy</h4>
	</br>
	<b><p class="" style="letter-spacing: 2px">Already have an Account? <a href="login.php" class="green">Log In Here</a></p></b>
</center>
<br/><br/>	 

<div class="form-wrap">
	<form action="register.php" method="post" autocomplete="off">
		<div class="grid-container">
			<div class="grid-row">
				<div class="col-5 reg-form-label"><label for="fname"><strong>First Name</strong></label></div>
				<div class="col-5">
					<div class="grid-container">
						<div class="grid-row">
							<div class="col-9">
								<input type="text" name="fname" id="fname" class="ajax-validate-input" maxlength="20" value="<?php if (isset($trimmed['fname'])) echo $trimmed['fname'];?>" placeholder="" data-validate="first_name"/>
							</div>
							<div class="col-1 validation-status"><?php if(!isset($trimmed['fname'])) echo '<div class="status" data-status="failed"></div>'; elseif(!validate_input($trimmed['fname'], 'first_name')) echo '<div class="status" data-status="failed"></div>'; ?></div>
						</div>
					</div>
				</div>
			</div>
			<div class="grid-row">
				<div class="col-5 reg-form-label"><label for="lname"><strong>Last Name</strong></label></div>
				<div class="col-5">
					<div class="grid-container">
						<div class="grid-row">
							<div class="col-9">
								<input type="text" name="lname" id="lname" class="ajax-validate-input" maxlength="40" value="<?php if (isset($trimmed['lname'])) echo $trimmed['lname'];?>" placeholder="" data-validate="last_name"/>
							</div>
							<div class="col-1 validation-status"><?php if(!isset($trimmed['lname'])) echo '<div class="status" data-status="failed"></div>'; elseif(!validate_input($trimmed['lname'], 'last_name')) echo '<div class="status" data-status="failed"></div>'; ?></div>
						</div>
					</div>
				</div>
			</div>
			<div class="grid-row">
				<div class="col-5 reg-form-label"><label for="email"><strong>Email Address</strong></label></div>
				<div class="col-5">
					<div class="grid-container">
						<div class="grid-row">
							<div class="col-9">
								<input type="text" name="email" id="email" class="ajax-validate-input" maxlength="80" value="<?php if (isset($trimmed['email'])) echo $trimmed['email'];?>" data-validate="email" data-target="ajax_unique_validation" autocomplete="off"/>
							</div>
							<div class="col-1 validation-status"><?php if(!isset($trimmed['email'])) echo '<div class="status" data-status="failed"></div>'; elseif(!validate_input($trimmed['email'], 'email')) echo '<div class="status" data-status="failed"></div>'; ?></div>
						</div>
					</div>
				</div>
			</div>
			<div class="grid-row">
				<div class="col-5 reg-form-label"><label for="pass"><strong>Password</strong></label></div>
				<div class="col-5">
					<div class="grid-container">
						<div class="grid-row">
							<div class="col-9">
								<input type="password" name="pass" id="pass" class="ajax-validate-input" maxlength="20" placeholder="Not currently encrypted - careful!" data-validate="password"/>
							</div>
							<div class="col-1 validation-status"><div class="status" data-status="failed"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="grid-row">
				<div class="col-5 reg-form-label"><label for="pass2"><strong>Confirm Password</strong></label></div>
				<div class="col-5">
					<div class="grid-container">
						<div class="grid-row">
							<div class="col-9">
								<input type="password" name="pass2" id="pass2" class="ajax-validate-input" maxlength="20" data-validate="password" />
							</div>
							<div class="col-1 validation-status"><div class="status" data-status="failed"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="grid-row">
				<div class="col-5 reg-form-label"></div>
				<div class="col-5"><center><input type="submit" name="submit" value="Sign Up!"/></center></div>
			</div>
		</div>
		<input type="hidden" name="submitted" value="TRUE"/>
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
	</form>
</div>