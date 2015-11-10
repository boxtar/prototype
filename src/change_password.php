<?php # script to change password for a user who is logged in

// Grab configuration
require_once 'includes/src/core/config.inc.php';

// If not logged in then just redirect to login page
require_once UTILITIES.'authenticate_user.inc.php';

$page_title = "Change Password";
include HEADER;

$user = new User();
$update_status = $user->update([]);
echo $update_status['msg'];

?>

<div class="boxtar-content">
	<br/>
	<center><h2>This feature will be implemented soon</h2></center>
</div>

<?php
include FOOTER;
 ?>