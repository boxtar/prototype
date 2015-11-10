<?php 	# Upload class to securely and flexibly manage user uploads
		# ONLY JPEG FILES AT THE MOMENT!
		# @TODO: Extend to support Several Image types and Audio and Videos too (if possible - will be with inheritance!)

// Used for throwing exception on failure to create a dir to stop the process for removing a dir being invoked
class CustomException extends Exception{}

class Upload_Image{
	
	/*   FIELDS   */
	
	private $_root_dir;		// uploads folder
	
	private $_img_name;		// randomly generated image name
	private $_img_dest;		// full image path on server
	
	private $_folder_name;	// randomly generated folder name
	private $_folder_path;	// root dir and folder name
	
	/* All extracted from $_FILES */
	private $_file_name;	
	private $_file_tmp;
	private $_file_size;
	private $_file_type;
	private $_file_ext;
	
	private $_thumb_requested;
	private $_thumb_width;
	private $_thumb_height;
	private $_thumb_prefix;
	private $_thumb_dest;	// full thumb image path on server
	
	private $_permitted_types;
	private $_form_input_name;
	private $_errors;
	private $_token;
	 
	/*   METHODS   */
	 
	public function __construct($var=[]){
		$this->_errors 				= 	[];
		$this->_permitted_types 	= 	['jpg', 'jpeg'];
		$this->_root_dir 			= 	(empty($var['root_dir']) ? UPLOADS_DIR : $var['root_dir']);
		$this->_form_input_name		=	(empty($var['form_input_name']) ? 'upload' : $var['form_input_name']);
		$this->_token = isset($_POST['token']) ? $_POST['token'] : false;
		
		// Only create a directory for the upload if constructor called with key 'new_dir' set to true:
		if(!empty($var['new_dir']) && $var['new_dir']===true){
			$this->_folder_name	=	(empty($var['dir_name']) ? $this->rand_string(20).'/' : $var['dir_name']);
			$this->_folder_path	=	$this->_root_dir . $this->_folder_name;
		}
		
		// Only create a thumbnail if requested:
		$this->_thumb_requested = !empty($var['thumb_required']) ? ($var['thumb_required']===true ? true: false) : false;
		
		if(isset($_FILES[$this->_form_input_name])){
			$this->_file_name	=	$_FILES[$this->_form_input_name]['name'];
			$this->_file_tmp	=	$_FILES[$this->_form_input_name]['tmp_name'];
			$this->_file_size	=	$_FILES[$this->_form_input_name]['size'] / 1024;
			$this->_file_type	=	$_FILES[$this->_form_input_name]['type'];
			$this->_file_ext	=	strtolower(pathinfo($this->_file_name, PATHINFO_EXTENSION));
			
			$this->_img_name	=	$this->rand_string(20).'.'.$this->_file_ext;
			$this->_img_dest	=	(empty($this->_folder_path) ? $this->_root_dir.$this->_img_name : $this->_folder_path.$this->_img_name);
			
			if($this->_thumb_requested){
				$this->_thumb_width		=	(empty($var['thumb_width']) ? 250 : $var['thumb_width']);
				$this->_thumb_height	=	(empty($var['thumb_height']) ? 250 : $var['thumb_height']);
				$this->_thumb_prefix	=	(empty($var['thumb_prefix']) ? 'thumb_' : $var['thumb_prefix']);
				$this->_thumb_dest		=	(empty($this->_folder_path) ? $this->_root_dir.$this->_thumb_prefix.$this->_img_name : $this->_folder_path.$this->_thumb_prefix.$this->_img_name);
			}
		}
		else{
			$this->_errors[]	=	'No Uploaded Files Found';
			$this->_errors[]	=	'Check name of the file input in your form and make sure it is passed to Upload constructor';
		}
	}
	
	public function process(){
		try{
			if(!$this->is_image_valid())
				throw new Exception('Invalid Type/Size - JPEG only and Max size of 3MB');
			
			if(!$this->save_image())
				throw new Exception('File could not be processed');
			
			return true;
		}
		catch(Exception $e){
			$this->delete_tmp_file();
			$this->_errors[] = $e->getMessage();
		}
		return false;
	}
	
	public function save_image(){
		try{
			if(!empty($this->_folder_path)){
				if(!$this->create_image_dir())
					throw new Exception('Failed to create image directory');
			}
			
			if(!$this->move_uploaded_file())
				throw new CustomException('Failed to move file');
			
			if($this->_thumb_requested){
				if(!$this->create_thumbnail())
					throw new CustomException('Failed to create thumbnail');
			}
			
			return true;
		}
		catch(Exception $e){
			$this->_errors[] = $e->getMessage();
		}
		catch(CustomException $e){
			$this->remove_image_dir();
			$this->_errors[] = $e->getMessage();
		}
		return false;
	}
	
	public function create_image_dir(){
		// If root directory doesn't exist then create it
		if(!is_dir($this->_root_dir))
			mkdir($this->_root_dir, 0777, true);
		
		// If new_dir is true then create new folder and return true
		// or return false if the folder already exists
		if(!is_dir($this->_folder_path)){
			mkdir($this->_folder_path, 0777);
			return true;
		}
		else{
			return false;
		}
	}
	 
	public function remove_image_dir(){
		// remove new directory
		rmdir($this->_folder_path);
		// return true if not a directory anymore or false it if still exists
		return !is_dir($this->_folder_path);
	}
	
	public function is_image_valid(){
		return ((in_array($this->_file_ext, $this->_permitted_types)) && ($this->_file_size <= 3072));
	}
	
	public function move_uploaded_file(){
		if(@move_uploaded_file($this->_file_tmp, $this->_img_dest))
			@chmod($this->_img_dest, 0755);
		else
			return false;
		// We reach here if move_uploaded_file succeeded
		return true;
	}
	
	private function delete_tmp_file(){
		if(file_exists($this->_file_tmp) && is_file($this->_file_tmp)){
			if(unlink($this->_file_tmp))
				return true;
			else
				trigger_error('Error unlinking temporary upload file');
		}
		return false;
	}
	
	public function create_thumbnail(){
		// create a new image Resource from file into memory:
		$img = imagecreatefromjpeg($this->_img_dest);
		
		// get the image resource dimensions:
		$width 	= imagesx($img);
		$height	= imagesy($img);
		
		// calculate thumbnail dimensions (maintaining aspect ratio):
		$new_width = $this->_thumb_width;
		// Ok so, $new_width/$width gives the percentage change in width
		// By multiplying that percentage with the original height we get the new
		// height with aspect ratio maintained.
		$new_height = floor($height*($new_width/$width));
		
		// create a new temp image in memory:
		$tmp_img = imagecreatetruecolor($new_width, $new_height);
		
		// resize the original image resource and copy it to our new temp image in memory:
		imagecopyresampled($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		
		// move the newly created image from memory to server disk:
		imagejpeg($tmp_img, $this->_thumb_dest);
		
		return file_exists($this->_thumb_dest);
	}
	
	public function get_file_name(){
		return $this->_img_name;
	}
	
	public function rand_string($length=10){
		$chars = "0123456789abcdefghijklmnopqrstuvwxyz";
		$string = '';
		for($x=0; $x<$length; $x++){
			$string.=$chars[mt_rand(0,strlen($chars)-1)];
		}
		return $string;
	}
	
	public function errors(){
		echo '<br/><center><p class="error">Upload Errors:</p></center><br/>';
		foreach($this->_errors as $err)
			echo '<center><p>- '.$err.'</p></center>';
	}
}

?>
