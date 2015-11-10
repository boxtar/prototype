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
	

session_start();
//require '../../core/config.inc.php';
/**
 * Defines that are required for included class scripts
 *
 * These defines are defined in config.inc.php but that script does
 * not work when included in AJAX script handlers
 */
// Location of DB connection info:
define ('DB_INFO', '../../../../../../db_info.inc.php');
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

require '../../classes/DB.php';
require '../../classes/Base_Account.php';
require '../../classes/User.php';
require '../../classes/Post_Manager.php';
require '../../classes/Session.php';
require '../../classes/Token.php';

if(isset($_POST['task']) && $_POST['task']=='post-submit'){
	if(Token::check($_POST['token'])){
		// data to be encoded as JSON and passed back to client:
		$data=[];
		// need to generate new session token as we've just consumed the previous one:
		$new_token = Token::generate();
		// get instance of the post_manager:
		$pm = Post_Manager::getInstance();
		// user submitting the post:
		$user = new User($_POST['user']);
		// target of the post:
		// @TODO: switch target_type to instantiate the target as the appropriate type
		$target = new User($_POST['target']);
		// The target type (user, music, dance, comedy):
		// @TODO: this will be used to properly instantiate the target object
		$target_type = $_POST['target_type'];
		// The actual post with line breaks preserved:
		$post = str_replace("\n", "<br/>", $_POST['post']);
		
		if($user->exists() && $target->exists()){
			$post_info = $pm->create_post($user, $target, $target_type, $post);
			if($post_info){
				$data['post_id'] = $post_info['id'];
				$data['post'] = $post;
				$data['user'] = $user->name();
				$data['user_avatar'] = $user->avatar();
				$data['status'] = 1;
			}
			else{
				$data['status'] = 0;
				$data['msg'] = 'Error submitting post';
			}
		}	
		else{
			$data['status'] = 0;
			$data['msg'] = 'Cannot find user';
		}
		$data['token'] = $new_token;
		echo json_encode($data);
	}
	else{
		echo 'Invalid Token';
	}
}
else{
	echo 'Script access error';
}
?>
