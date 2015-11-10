<?php
require 'includes/src/core/config.inc.php';
// ensure current user is logged in:
include UTILITIES.'authenticate_user.inc.php';
$page_title='Remove A User from A Group';
include HEADER;
include UTILITIES.'brand_img.inc.php';

// Create new User Object:
$user = new User();

if(isset($_POST['submitted'])){
	if(Token::check($_POST['token'])){
		if($user->exists()){
			// Create new Uploader Object:
			$uploader = new Upload_Image([
				'root_dir' => USER_UPLOADS.$user->data()->prof_link.'/img/prof/',
				'new_dir' => false,
				'thumb_required'=>true
			]);
		
			// Initiate uploading process:
			if($uploader->process()){
				// update users avatar_link
				$file_name = $uploader->get_file_name();
				$user->update(['avatar_link' => $file_name]);
				echo '<center><h5 class="green">File successfully uploaded</h5></center><br/>';
			}
			else
				$uploader->errors();
		}
		else{
			echo '<center><h5 class="red">Error uploading - User not found</h5></center><br/>';
		}
	}
	else{
		echo '<center><h5 class="red">Invalid Form Submission - Please try again</h5></center><br/>';
	}
}


$imgs = $user->exists() ? glob(USER_UPLOADS.$user->profile_link().'/img/prof/*.jpg') : false;

?>
<br/>
<br/>
<form action="" method="post" enctype="multipart/form-data">
	<div class="md-container">
		<div class="grid-container">
			<div class="row">
				<div class="col-10"><p class="uppercase">select a file to upload:</p></div>
			</div>
			<div class="row">
				<div class="col-10"><input type="file" name="upload" id="upload" /></div>
			</div>
			<div class="row">
				<div class="col-10"><input type="submit" name="submit" value="Upload File" /></div>
			</div>
			<div class="row">
				<div class="col-4">
					<?php
						if($imgs){
							foreach($imgs as $img){
								//$img_name = basename($img);
								$img_path = 'user/'.$user->profile_link().'/img/prof/'.basename($img);
								echo '<p><img src="get_img.php?img='.$img_path.'" alt="FAIL" /></p>';
							}
						}
					?>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" name="submitted" value="TRUE" />
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
</form>

<?php
include FOOTER;
?>
