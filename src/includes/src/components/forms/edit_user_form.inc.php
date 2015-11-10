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


<div class="md-container">
	<br/><br/>
	<div class="grid-container">
		<form action="" method="post" class="" autocomplete="off">
			<!-- NAME & EMAIL -->
			<div class="grid-row">
				<div class="col-10 collapsable-panel-toggler border">
					<p class="uppercase">name &amp; email&nbsp;&nbsp;<i class="toggle-indicator"></i></p>
				</div>
				<div class="col-10 collapsable-panel border-lr border-bottom">
					<div class="grid-container">
						<!-- First Name -->
						<div class="grid-row">
							<div class="col-5"><label for="fname"><strong>First Name</strong></label></div>
							<div class="col-5">
								<div class="grid-container">
									<div class="grid-row">
										<div class="col-9">
											<input type="text" name="fname" id="fname" class="ajax-validate-input" value="<?php if(isset($user->data()->first_name)) echo escape($user->data()->first_name); ?>" data-validate="first_name"/>
										</div>
										<div class="col-1 validation-status">
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- Last Name -->
						<div class="grid-row">
							<div class="col-5"><label for="lname"><div class="validation-status" style=""></div>&nbsp;<strong>Last Name</strong></div>
							<div class="col-5">
								<div class="grid-container">
									<div class="grid-row">
										<div class="col-9">
											<input type="text" name="lname" id="lname" class="ajax-validate-input" value="<?php if(isset($user->data()->last_name)) echo escape($user->data()->last_name); ?>" data-validate="last_name"/>
										</div>
										<div class="col-1 validation-status">
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- Email (currently disabled) -->
						<div class="grid-row">
							<div class="col-5"><label for="email"><div class="validation-status" style=""></div>&nbsp;<strong>Email Address</strong></label></div>
							<div class="col-5">
								<div class="grid-container">
									<div class="grid-row">
										<div class="col-9">
											<!-- @TODO: Disabled changing email as i'll need to implement functionality for user to confirm the new email -->
											<input type="text" name="email" id="email" class="ajax-validate-input not-allowed" value="<?php if(isset($user->data()->email)) echo $user->data()->email; ?>" readonly data-validate="email"/>
										</div>
										<div class="col-1 validation-status">
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- Submit -->
						<div class="grid-row">
							<br/>
							<div class="col-10">
								<center><input type="submit" name="submit" value="Confirm Changes"/>&nbsp;&nbsp;&nbsp;&nbsp;
								<!--<a href="<?php //echo BASE_URL.trim($_SERVER['SCRIPT_NAME'], '/'); ?>">[Reset]</a></center>-->
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<!-- PASSWORD -->
			<div class="grid-row" style="display:none">
				<div class="col-10 collapsable-panel-toggler border-lr border-bottom">
					<p class="uppercase">password<i class="lowercase">&nbsp;(not yet implemented)</i>&nbsp;&nbsp;<i class="toggle-indicator"></i></p>
				</div>
				<div class="col-10 collapsable-panel border-lr border-bottom">
					<div class="grid-container">
						<!-- Current Password -->
						<div class="grid-row">
							<div class="col-5"><label for="curr_pass"><strong>Current Password</strong></div>
							<div class="col-5"><input type="password" name="curr_pass" id="curr_pass" /></div>
						</div>
						<!-- New Password -->
						<div class="grid-row">
							<div class="col-5"><label for="new_pass"><strong>New Password</strong></div>
							<div class="col-5"><input type="password" name="new_pass" id="new_pass" /></div>
						</div>
						<!-- Confirm New Password -->
						<div class="grid-row">
							<div class="col-5"><label for="conf_new_pass"><strong>Confirm New Password</strong></div>
							<div class="col-5"><input type="password" name="conf_new_pass" id="conf_new_pass" /></div>
						</div>
						<!-- Submit -->
						<div class="grid-row">
							<br/>
							<div class="col-10">
								<center><input type="submit" name="submit" value="Confirm Changes"/>&nbsp;&nbsp;&nbsp;&nbsp;
								<!--<a href="<?php //echo BASE_URL.trim($_SERVER['SCRIPT_NAME'], '/'); ?>">[Reset]</a></center>-->
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<!-- LINK, LOCATION & BIO -->
			<div class="grid-row">
				<div class="col-10 collapsable-panel-toggler border-lr border-bottom">
					<p class="uppercase">link, location &amp; bio&nbsp;&nbsp;<i class="toggle-indicator"></i></p>
				</div>
				<div class="col-10 collapsable-panel border-lr border-bottom">
					<div class="grid-container">
						<!-- Profile Link -->
						<div class="grid-row">
							<div class="col-5"><label for="prof_link"><strong>Link to your profile:&nbsp;</strong></div>
							<div class="col-5">
								<div class="grid-container">
									<div class="grid-row">
										<div class="col-9">
											<input type="text" name="prof_link" id="prof_link" class="ajax-validate-input" value="<?php if(isset($user->data()->prof_link)) echo $user->data()->prof_link; ?>" data-validate="profile_link"/>
										</div>
										<div class="col-1 validation-status">
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- Location -->
						<div class="grid-row">
							<div class="col-5"><label for="loc"><strong>Location</strong></div>
							<div class="col-5">
								<div class="grid-container">
									<div class="grid-row">
										<div class="col-9">
											<input type="text" name="loc" id="loc" class="ajax-validate-input" value="<?php if(isset($user->data()->loc)) echo $user->data()->loc; ?>" data-validate="location"/>
										</div>
										<div class="col-1 validation-status">
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- Bio -->
						<div class="grid-row">
							<div class="col-5"><label for="bio"><strong>Quick &amp; Quirky Message</strong></div>
							<div class="col-5">
								<div class="grid-container">
									<div class="grid-row">
										<div class="col-9">
											<textarea name="bio" id="bio" class="ajax-validate-input" maxlength="150" data-validate=""><?php if(isset($user->data()->bio)) echo $user->data()->bio; ?></textarea>
										</div>
										<div class="col-1 validation-status">
										</div>
									</div>
								</div>
							
							</div>
						</div>
						<!-- Submit -->
						<div class="grid-row">
							<div class="col-10">
								<center><input type="submit" name="submit" value="Confirm Changes"/>&nbsp;&nbsp;&nbsp;&nbsp;
								<!--<a href="<?php //echo BASE_URL.trim($_SERVER['SCRIPT_NAME'], '/'); ?>">[Reset]</a></center>-->
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<!-- PROFILE PICTURE -->
			<div class="grid-row">
				<div class="col-10 collapsable-panel-toggler border-lr border-bottom">
					<p class="uppercase">profile picture&nbsp;&nbsp;<i class="toggle-indicator"></i></p>
				</div>
				<div class="col-10 collapsable-panel border-lr border-bottom">
					<p><img src="get_img.php?img=user/<?php echo $user->profile_link().'/img/prof/'.$user->data()->avatar_link; ?>" alt="avatar" style="width:75px;height:auto">&nbsp;&nbsp;&nbsp;Still to be implemented</p>
					<!-- <h3><br/>Select a JPEG or PNG file to upload (2MB size limit)<br/><br/></h3>
					<form action="" method="post" enctype="multipart/form-data">

						<input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
						<p><label for="upload">File: </label>
							<input type="file" name="upload" />
						</p>
						<p><br/>
							<input type="submit" name="submit" value="Upload" />
						</p>
						<input type="hidden" name="submitted" value="TRUE" />
					</form> -->
				</div>
			</div>
			<input type="hidden" name="submitted" value="TRUE"/>
			<input type="hidden" name="<?php echo Config::get('session/token_name') ?>" value="<?php echo Token::generate() ?>" />
		</form>
	</div>
</div>