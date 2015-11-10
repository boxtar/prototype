<?php 
require 'includes/src/core/config.inc.php';
require UTILITIES.'authenticate_user.inc.php';
$page_title="Upload a File";
include HEADER;
include UTILITIES.'brand_img.inc.php';

if(isset($_POST['submitted'])){
	// Create new User Object:
	$user = new User($_SESSION['user_id']);
	if(isset($user->data()->prof_link)){
		// Create new Uploader Object:
		$uploader = new Upload_Image([
			'root_dir' => UPLOADS_DIR.'usr/'.$user->data()->prof_link.'/img/prof/',
			'new_dir' => false,
			'thumb_required'=>true
		]);
		
		// Initiate uploading process:
		if($uploader->process()){
			// update users avatar_link
			$file_name = $uploader->get_file_name();
			$user->update_info(['avatar_link' => $file_name]);
			echo '<center><h5 class="green">File successfully uploaded</h5></center><br/>';
		}
		else
			$uploader->errors();
	}
	else{
		echo 'Error uploading - User profile link not found';
	}
}
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
		</div>
	</div>
	<input type="hidden" name="submitted" value="TRUE" />
	<input type="hidden" name="token" value="" />
</form>
<?php include FOOTER; ?>