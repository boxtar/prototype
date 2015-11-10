<?php
// If INCLUDED isn't set then this hasn't been accessed properly.
// Redirect user away to home page. Have to redirect manually as no config included
if(!defined('INCLUDED')){
	echo 'Access Denied. <a href="http://dev.boxtar.uk">Return to Home Page</a>';
	exit();
}
?>

<center>
	<a href="index.php"><img class="brand-img" src="includes/img/boxtar.png" alt="brand"></a>
</center>
<br/>