<?php
	// Can't include config here as it messes up header at bottom of script that sends img back to caller
	// uploads directory
	$dir = '../../uploads_bxtar/';
	// image name
	$img_name = $_GET['img'];
	// Full image pathinfo
	$img_path = $dir . $img_name;
	// Make sure the given file exists. If not; bail
	if( (!file_exists($img_path)) && (!is_file($img_path)) ){
		die('The file does not exist');
	}
	// Make sure file given is an image
	$img_data = getimagesize($img_path);
	if(!$img_data){ // If false, not an image
		die('File requested is not an image');
	}
	// Create headers describing what is to come
	header("Content-Type:{$img_data['mime']}\n");
	header('Content-Length:' . filesize($img_path));
	// Start to read the above described file
	readfile($img_path);
?>
