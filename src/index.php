<?php 	
	# BOXTAR Home Page
	// Config file
	require_once('includes/src/core/config.inc.php');
	
	// Header - starts off HTML and sets title etc
	include HEADER;
?>
<!--<div class="boxtar-content">-->
<section class="boxtar-section">
	<div class="homepage-banner">
		<a href="index.php"><img class="brand-img" src="includes/img/boxtar.png" alt="brand"></a>
		<h1 class="main-title"><?php echo $page_title; ?></h1>
		<h4 class="lowercase" id=""><i>discover and share what you love</i></h4>
		<!-- <div id="boxtar-about" class="align-left">
			<p>Boxtar&copy; is an online entertainment streaming platform that supports fresh new talent across three entertainment zones:</p>
			<br/>
			<center><a href="#music">New Music</a> | <a href="#dance">New Dance</a> | <a href="#comedy">New Comedy</a></center>
		<div> -->
	</div>
</section>
<?php
	// If user is not logged in display register form
	if(!Session::get('id')){
		echo '<section class="boxtar-section">';
		// Include registration form markup
		include(FORMS . 'registration_form.inc.php');
		echo '</section>';
	}
	else{ // User is logged in
		// Display stuff for a logged in user or re-direct / whatever
	}
?>

<br/>
<section class="boxtar-section transp-background">
	<div class="boxtar-content">
	
		<div class="grid-container">
			<div class="grid-row">
				<div class="col-10"><h1 class="uppercase main-title">new music</h1></div>
			</div>
			<br/><br/>
			<div class="grid-row">
				<div class="col-5">
					<p class="uppercase">most viewed groups this month</p>
					<br/><br/>
					<div class="grid-container">
						<div class="grid-row">
							<div class="col-10">
								<p><img class="small-img tile-image" src="get_img.php?img=default.jpg" alt="img" />&nbsp;Group Name - Location</p>
							</div>
						</div>
						<div class="grid-row">
							<div class="col-10">
								<p><img class="small-img tile-image" src="get_img.php?img=default.jpg" alt="img" />&nbsp;Group Name - Location</p>
							</div>
						</div>
						<div class="grid-row">
							<div class="col-10">
								<p><img class="small-img tile-image" src="get_img.php?img=default.jpg" alt="img" />&nbsp;Group Name - Location</p>
							</div>
						</div>
						<div class="grid-row">
							<div class="col-10">
								<p><img class="small-img tile-image" src="get_img.php?img=default.jpg" alt="img" />&nbsp;Group Name - Location</p>
							</div>
						</div>
						<div class="grid-row">
							<div class="col-10">
								<p><img class="small-img tile-image" src="get_img.php?img=default.jpg" alt="img" />&nbsp;Group Name - Location</p>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-5">
					<p class="uppercase">most listened to tracks this month</p>
					<br/><br/>
					<div class="grid-container">
						<div class="grid-row">
							<div class="col-10">
								<p><img class="small-img tile-image" src="get_img.php?img=default.jpg" alt="img" />&nbsp;Song Name - Group Name</p>
							</div>
						</div>
						<div class="grid-row">
							<div class="col-10">
								<p><img class="small-img tile-image" src="get_img.php?img=default.jpg" alt="img" />&nbsp;Song Name - Group Name</p>
							</div>
						</div>
						<div class="grid-row">
							<div class="col-10">
								<p><img class="small-img tile-image" src="get_img.php?img=default.jpg" alt="img" />&nbsp;Song Name - Group Name</p>
							</div>
						</div>
						<div class="grid-row">
							<div class="col-10">
								<p><img class="small-img tile-image" src="get_img.php?img=default.jpg" alt="img" />&nbsp;Song Name - Group Name</p>
							</div>
						</div>
						<div class="grid-row">
							<div class="col-10">
								<p><img class="small-img tile-image" src="get_img.php?img=default.jpg" alt="img" />&nbsp;Song Name - Group Name</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<br/><br/>
			<div class="grid-row">
				<div class="tile-col"><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></div>
				<div class="tile-col"><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></div>
				<div class="tile-col"><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></div>
				<div class="tile-col"><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></div>
				<div class="tile-col"><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></div>
				<div class="tile-col"><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></div>
			</div>
			<div class="grid-row hideable">
				<div class="tile-col"><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></div>
				<div class="tile-col"><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></div>
				<div class="tile-col"><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></div>
				<div class="tile-col"><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></div>
				<div class="tile-col"><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></div>
				<div class="tile-col"><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></div>
			</div>
		</div>
	</div>
</section>

<!-- <section class="boxtar-section">
	<div class="boxtar-content">
		<div class="section group">
			<h1 class="uppercase ">new dance</h1>
			<br/><br/>
		</div>
		<div class="section group">
			<div class="col span_1_of_6">
				<a href=#><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></a>		
			</div>
			<div class="col span_1_of_6">
				<a href=#><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></a>		
			</div>
			<div class="col span_1_of_6">
				<a href=#><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></a>		
			</div>
			<div class="col span_1_of_6">
				<a href=#><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></a>		
			</div>
			<div class="col span_1_of_6">
				<a href=#><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></a>		
			</div>
			<div class="col span_1_of_6">
				<a href=#><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></a>		
			</div>
		</div>
	</div>
</section>

<section class="boxtar-section transp-background">
	<div class="boxtar-content">
		<div class="section group">
			<h1 class="uppercase ">new comedy</h1>
			<br/><br/>
		</div>
		<div class="section group">
			<div class="col span_1_of_6">
				<a href=#><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></a>		
			</div>
			<div class="col span_1_of_6">
				<a href=#><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></a>		
			</div>
			<div class="col span_1_of_6">
				<a href=#><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></a>		
			</div>
			<div class="col span_1_of_6">
				<a href=#><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></a>		
			</div>
			<div class="col span_1_of_6">
				<a href=#><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></a>		
			</div>
			<div class="col span_1_of_6">
				<a href=#><img class="tile-image" src="get_img.php?img=default.jpg" alt="img" /></a>		
			</div>
		</div>
	</div>
</section> -->

<br/><br/>

<!-- </div> --><!-- boxtar-content -->	
<?php 
	include FOOTER;
?>
