<?php # Starts page markup etc

	// If INCLUDED isn't set then this hasn't been accessed properly.
	// Redirect user away to home page. Have to redirect manually as no config included
	if(!defined('INCLUDED')){
		echo 'Access Denied. <a href="http://dev.boxtar.uk">Return to Home Page</a>';
		exit();
	}

	// Ensure there is a title for the page
	if(!isset($page_title)) $page_title = 'BOXTAR UK';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	
	<meta name="description" content="boxtar talent discovery">
	<meta content="http://dev.boxtar.uk/johnpaul" name="author">
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimal-ui">
	<meta name="apple-mobile-web-app-capable" content="yes">
	
	<title><?php echo $page_title ?></title>
	<link rel="shortcut icon" href="favicon.png">
	
	<!-- SET THE BASE FOR ALL RELATIVE PATHS (Required to rewrite rules) -->
	<base href="<?php echo BASE_URL; ?>">
	
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	
	<!-- FOUC FIX -->
	<style type="text/css">
		.js #fouc{display:none;}
	</style>
	<script type="text/javascript">
		// This adds class JS to html tag
		// This ensures that FOUC is only applied when javascript is enabled since we're manipulating the visibility of elements
		document.documentElement.className='js';
	</script>
	<!-- END FOUC FIX -->
	
	<link href='<?php echo INCLUDES; ?>css/html5reset.css' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
	<link href='<?php echo INCLUDES; ?>css/font-awesome/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
	<?php
		// Dynamically include grid-system as there are a lot of files.
		// @TODO: bring all CSS files into one and minimise size
		$dir=INCLUDES.'css/grid-system/';
		$files=scandir($dir);
		$stylesheets=[];
		foreach($files as $file){
			if(substr($file, 0, 1) != '.'){
				$extension = strtolower(substr($file, -4));
				if($extension == '.css')
					$stylesheets[]=$file;
			}
		}
		foreach($stylesheets as $stylesheet){
			echo '<link href="'.$dir.$stylesheet.'" rel="stylesheet" type="text/css">';
		}
	?>
	<link href='<?php echo INCLUDES; ?>css/main.css' rel='stylesheet' type='text/css'>
	<link href='<?php echo INCLUDES; ?>css/navbar.css' rel='stylesheet' type='text/css'>
	<link href='<?php echo INCLUDES; ?>css/forms.css' rel='stylesheet' type='text/css'>
	<link href='<?php echo INCLUDES; ?>css/mygrid.css' rel='stylesheet' type='text/css'>
	<link href='<?php echo INCLUDES; ?>css/group_homepage.css' rel='stylesheet' type='text/css'>
	<link href='<?php echo INCLUDES; ?>css/modal.css' rel='stylesheet' type='text/css'>
	<link href='<?php echo INCLUDES; ?>css/utils.css' rel='stylesheet' type='text/css'>
	<link href='<?php echo INCLUDES; ?>css/profile_page.css' rel='stylesheet' type='text/css'>
	<link href='<?php echo INCLUDES; ?>css/edit_profile.css' rel='stylesheet' type='text/css'>
</head>
<body>
<div class="boxtar-container" id="fouc">	
<?php include(TEMPLATES.'navbar.inc.php'); ?>

<!-- Start of page specific content -->
