<?php # config.inc.php 

/*	
This script will define constants
dictate how errors are handled depending on site status
and define any useful functions

BOXTAR UK designed and created by
Johnpaul McMahon & Frankie Sweeney
to help promote undiscovered talent
	
Last update: 24/07/2015
*/

// Start output buffering:
ob_start(); // May be default in this version of PHP

// Start Session:
session_start();

/*******************************************/
/**************** SETTINGS *****************/
/*******************************************/

$host = substr($_SERVER['HTTP_HOST'], 0, 5);
if(in_array($host, ['local', '127.0', '192.1'])){
	// Used for debugging:
	define('LIVE', false);
	// Site URL (redirections):
	define ('BASE_URL', 'http://local.box/');
}
else{
	// Used for debugging:
	define('LIVE', true);
	// Site URL (redirections):
	define ('BASE_URL', 'http://82.18.226.47/boxtar/');
}

// I will use this to check in scripts that are supposed to be included (everything in includes) that
// they are being accessed properly (from within a script) as opposed to user directly navigating to them.
define('INCLUDED', true);
// email address for detailed error messages:
define ('EMAIL', 'jai@boxtar.uk');
// Location of files/folders outside of public view:
define ('OFFSITE', '../../');
// Location of uploads folder:
define ('UPLOADS_DIR', OFFSITE.'uploads_bxtar/');
// Location of user uploads:
define ('USER_UPLOADS', UPLOADS_DIR.'user/');
// Location of music group uploads:
define ('MUSIC_GROUP_UPLOADS', UPLOADS_DIR.'music/');
// Location of dance group uploads:
define ('DANCE_GROUP_UPLOADS', UPLOADS_DIR.'dance/');
// Location of comedy group uploads:
define ('COMEDY_GROUP_UPLOADS', UPLOADS_DIR.'comedy/');
// Location of includes folder:
define('INCLUDES', 'includes/');
// location of src folder:
define('SRC', INCLUDES.'src/');
// Location of components:
define('COMPONENTS', SRC.'components/');
// Location of utility functions:
define('UTILITIES', SRC.'utilities/');
// Location of form components:
define('FORMS', COMPONENTS . 'forms/');
// Location of database handling components:
define('DB_HANDLERS', COMPONENTS . 'db_handlers/');
// Location of database handling components:
define('TEMPLATES', COMPONENTS . 'templates/');
// Location of commonly included header file:
define('HEADER', TEMPLATES.'header.inc.php');
// Location of commonly included header file:
define('FOOTER', TEMPLATES.'footer.inc.php');

/**** DATABASE CONSTANTS ****/
// Location of DB connection info:
define ('DB_INFO', OFFSITE.'db_info.inc.php');
// Name of DB table holding all users:
define('USERS_TABLE', 'users');
// Name of DB table holding all groups:
define('GROUPS_TABLE', 'groups');
// Name of DB table holding all user to group relations:
define('USERS_TO_GROUPS_INTERMEDIARY', 'users_artist_music');

// Constants for users group status
define('NO_PERMISSIONS', 1);
define('ADMIN', 2);
define('OWNER', 3);


$GLOBALS['config'] = [
	'remember_user' => [
		'cookie_name' => '_rucn',
		'cookie_expiry' => '604800'
	],
	'session' => [
		'session_name' => 'user',
		'token_name' => 'token'
	],
	'live' => false
];


// Set the timezone:
date_default_timezone_set('Europe/London');

// Array of client types for profile fetching:
$client_type = ['user', 'music', 'dance', 'comedy'];


// Auto require a class when it is accessed:
spl_autoload_register(function($class){
	require_once(SRC.'classes/' . $class . '.php');
});


/*******************************************/
/************ ERROR MANAGEMENT *************/
/*******************************************/
// Error handler:
function err_handler($e_err_level, $e_err_string, $e_err_file, $e_err_line, $e_err_vars){
	// Build error message:
	$msg = "<br/><br/><p style=\"color:red\">An error occurred in script '$e_err_file' on line $e_err_line:<br/> $e_err_string\n<br/>";
	// Add the date and time:
	$msg .= "Date & Time: " . date('j-n-Y H:i:s') . "\n<br/>";
	// Add the variables
	$msg .= print_r($e_err_vars, 1) . "\n</p>";
	
	if(!LIVE){ // In development mode: print to browser
		echo '<div class="error"><pre>' . $msg . '</pre></div><br/>';
		debug_print_backtrace();
	}
	else{ // Site is live: Email to admin
		$msg = strip_tags($msg); // Strip tags to make email easier to read
		mail(EMAIL, 'URGENT: Boxtar Script Error!', $msg, 'From: error-reporter@boxtar.uk');
		
		// Indicate to user that there was an error
		// Only if it isn't an E_NOTICE
		if($e_err_level != E_NOTICE){
			echo 	'<br/><br/><center><h1>BOXTAR UK</h1><br/><div><p class="red">A system error has occurred and the Webmaster has been notified<br/>
					Sorry for any inconvenience caused</p><br/><p><a href="'. BASE_URL . 'contact_form.php">Get in Touch to have this issue ironed out</a></p><br/>
					<p>OR</p><br/><p><a href="'.BASE_URL.'">Return to Boxtar UK</a></p></center>';
		}
	}
}

// Set err_handler as default
set_error_handler('err_handler');

/*******************************************/
/*********** UTILITY FUNCTIONS *************/
/*******************************************/

function escape($string){
	return htmlentities($string, ENT_QUOTES, 'UTF-8');
}

function redirect($target='', $delay=0){
		// Setup redirect URL
		$url=BASE_URL.$target;
		// Setup redirect
		header( "refresh:$delay;url=$url" );
		// Include footer AFTER header as footer flushes OB
		include FOOTER;
		// Kill script
		exit();
}
	
?>
	
