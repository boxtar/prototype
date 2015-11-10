
//******* RANDOM BACKGROUND COLOR *******
// 				GREY,     ORANGE,     GREEN,     BLUE,     PURPLE,      RED,
var colors = ['#CFCFC4', '#FDC68A', '#77DD77', '#779ECB', '#B19CD9', '#FF6961'];
var totalCount = colors.length;

function ChangeBackgroundColor() {
	var num =  Math.ceil( Math.random() * totalCount );
	document.body.style.background = colors[num-1];
}
//***************************************

//********** LOADING OVERLAY **********
// Only use loading overlay if javascript is enabled otherwise we won't be able to hide it
$loading_overlay = $('<div id="loading-overlay" style="position:absolute;height:50px;"><img src="includes/img/loading.gif" alt="Loading...Thank you for your patience." style="width:50px;height:50px;"></div>');
// Center loading overlay
var top_pos, left_pos;
top_pos = Math.max($(window).height() - $loading_overlay.height(), 0) /2;
left_pos = Math.max($(window).width() - $loading_overlay.outerWidth(), 0) /2;
// Set top and left
$loading_overlay.css({
	top: top_pos + $(window).scrollTop(),
	left: left_pos + $(window).scrollLeft()
});
// Append loading overlay
$(document).ready(function(){
	$('body').append($loading_overlay);
});
//*************************************

//************ DOCUMENT READY ***************
$(document).ready(function(){
	// Change background color when DOM is ready
	ChangeBackgroundColor();
	
	/***** DROP DOWN NAV *****/
	$('.dropdown-toggler>a').click(function(event){
		$(this).parent().find('.nav-list-dropdown').slideToggle(200);
		$(this).find(".fa-cog").toggleClass("fa-spin");
		event.preventDefault();
	});
	
	$('.dropdown-toggler').mouseleave(function(){
		$(this).find('.nav-list-dropdown').slideUp(200);
		$(this).find(".fa-cog").removeClass("fa-spin");
	}); // drop-down navigation
	/*************************/
	
	/******* COLLAPSABLE PANEL SETUP *******/
	// Hide all collapsable panels on load
	$('.collapsable-panel').hide();
	// Add font awesome glyphs (the order is important)
	$('.collapsable-panel-toggler .toggle-indicator').addClass('fa fa-chevron-up fa-chevron-down');
	// onclick handler to show collapsable info panel and toggle font awesome glyph
	$('.collapsable-panel-toggler').click(function(){
		$(this).next().stop().slideToggle('fast', 'swing');
		$(this).find('.toggle-indicator').toggleClass('fa-chevron-down');
	});
	/***************************************/
	
	/***** MODAL CLICK EVENTS *****/
	// Attach click event to new music group link:
	$('.new-music-modal').click(function(e){
		e.preventDefault();
		// Ajax
		$.get('includes/src/components/forms/new_music_form.inc.php', function(data){
			modal.open({content: data, max_width: "350px"});
		});
	});	
	
	// Attach click event to new dance group link:
	$('.new-dance-modal').click(function(e){
		e.preventDefault();
		// Ajax
		$.get('includes/src/components/forms/new_dance_form.inc.php', function(data){
			modal.open({content: data, max_width: "350px"});
		});
	});	
	
	// Attach click event to new comedy group link:
	$('.new-comedy-modal').click(function(e){
		e.preventDefault();
		// Ajax
		$.get('includes/src/components/forms/new_comedy_form.inc.php', function(data){
			modal.open({content: data, max_width: "350px"});
		});
	});	
	
	// Attach click event to add_user_to_group link
	$('.add-user-modal').click(function(e){
		e.preventDefault();
		// Ajax
		$.get('includes/src/components/forms/add_user_to_group_form.inc.php?grp='+$(this).data('group'), function(data){
			modal.open({content: data, max_width: "350px"});
		});
	});
	
	// Attach click event to login navbar link
	$('.login-modal').click(function(e){
		e.preventDefault();
		// Ajax
		$.get('includes/src/components/forms/login_form.inc.php', function(data){
			modal.open({content: data, max_width: "350px"});
		});
	});
	/****************************/
	
	/***** Managing which select tag to display on new_group_form ****/
	$('#group-type').on('change', function(){
		$('#music, #dance, #comedy').hide();
		var group_type = $(this).find('option:selected').data('type');
		$('#'+group_type).show();
	});
	/*****************************************************************/
	
}); // document.ready

//************ WINDOW READY ***************
window.onload=function(){
	// Everything is ready so hide the loading overlay
	$('#loading-overlay').css("display", "none");
	// And fade in the content.
	$("#fouc").delay(200).fadeIn(1350);
} // window ready


