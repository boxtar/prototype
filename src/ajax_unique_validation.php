<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
	die('<center>
			<h1>Access Denied</h1>
			<p>Return to <a href="http://dev.boxtar.uk/">Boxtar UK</a></p>
			</center>');
}
else if((strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'))
	die('<center>
			<h1>Access Denied</h1>
			<p>Return to <a href="http://dev.boxtar.uk/">Boxtar UK</a></p>
			</center>');
			
require 'includes/src/core/config.inc.php';
require UTILITIES.'validate_input.php';

if(isset($_POST['input'])){
	$input = $_POST['input'];
	$validation_type = isset($_POST['validation_type']) ? $_POST['validation_type'] : '';
	
	if(!validate_input($input, $validation_type)){
		die('<div class="status" data-status="failed"></div>
			<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="14" height="14" viewBox="0 0 16 16">
				<g>
				</g>
				<path d="M8 0c-4.418 0-8 3.582-8 8s3.582 8 8 8 8-3.582 8-8-3.582-8-8-8zM12 5.414l-2.586 2.586 2.586 2.586v1.414h-1.414l-2.586-2.586-2.586 2.586h-1.414v-1.414l2.586-2.586-2.586-2.586v-1.414h1.414l2.586 2.586 2.586-2.586h1.414v1.414z" fill="#A00"></path>
			</svg>'
		);
	}
	// Validation passed if we reach this point
	// Check input is unique:
	$user = new User();
	if($user->find($input, 'users', ['email'])){
		die('<span class="red">In use</span>');
	}
	// Validation passed and input unique if this point is reached
	// Display success SVG:
	die('<div class="status" data-status="success"></div><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="14" height="14" viewBox="0 0 16 16">
		<g></g>
		<path d="M8 0c-4.418 0-8 3.582-8 8s3.582 8 8 8 8-3.582 8-8-3.582-8-8-8zM6.5 13l-3.313-4.313 1.469-1.531 1.844 2.344 5.781-4.719 0.719 0.719-6.5 7.5z" fill="#0A0"></path>
		</svg>');
	
}
?>