<?php 
	// If INCLUDED isn't set then this hasn't been accessed properly.
	// Redirect user away to home page. Have to redirect manually as no config included
	if(!defined('INCLUDED')){
		echo 'Access Denied. <a href="http://dev.boxtar.uk">Return to Home Page</a>';
		exit();
	}
?>

	<!-- End of boxtar-container -->
	</div>
	<!-- My Scripts -->
	<script src="<?php echo INCLUDES; ?>js/modal.js" type="text/javascript"></script>
	<script src="<?php echo INCLUDES; ?>js/ajax.js" type="text/javascript"></script>
	<script src="<?php echo INCLUDES; ?>js/script.js" type="text/javascript"></script>
</body>
</html>
<?php
	// Flush the output buffer
	ob_end_flush();
?>