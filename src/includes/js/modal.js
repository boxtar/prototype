// Author: <Get Author>
// Description: Setup script for Modal functionality
// Project: BOXTAR UK

//****************** MODAL *******************
var modal = (function(){
	var 
	method = {},
	$overlay = $('<div id="overlay"></div>'),
	$modal = $('<div id="modal"></div>'),
	$content = $('<div id="content"></div>'),
	$close = $('<a id="close" href="#"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x black"></i><i class="fa fa-times fa-stack-1x"></i></span></a>');
	
	// If no width passed when opening modal then make width 80% of window width
	/* var full_width = ($(window).width())*0.8; */

	// Append the HTML
	$modal.hide();
	$overlay.hide();
	$modal.append($content, $close);
	// Append Modal to body
	$(document).ready(function(){
		$('body').append($overlay, $modal);
	});
	
	// Center the modal in the viewport
	method.center = function () {
		var top, left;
		top = Math.max($(window).height() - $modal.outerHeight(), 0) /2;
		left = Math.max($(window).width() - $modal.outerWidth(), 0) /2;
		$modal.css({
			top: top + $(window).scrollTop(),
			left: left + $(window).scrollLeft()
		});
	};

	// Open the modal
	method.open = function (settings) {
		// settings will contain the content, width and height
		// clear anything left in content element and append new content
		$content.empty().append(settings.content);
		
		// Set width and height of modal if provided
		$modal.css({
			"width": settings.width || 'auto', 
			"height": settings.height || 'auto',
			"max-width": settings.max_width || 'none',
			"max-height": settings.max_height || 'none'
		});
		// Dimensions set so can now center
		method.center();
		
		// 'resize.modal' - this is event name-spacing so that removing the trigger doesn't affect other resize triggers
		// Invoke the center method when window is resized
		$(window).on('resize.modal', method.center);
		//$(window).on('click.overlay', method.close);
		
		// Show modal and overlay
		$modal.show();
		$overlay.show();
	};

	// Close the modal
	method.close = function () {
		$modal.hide();
		$overlay.hide();
		$content.empty();
		// Unbind the re-centering of the modal when window resizes as it's unnecessary when modal is closed
		$(window).unbind('resize.modal');
	};
	
	// Add close modal event
	$close.click(function(e){
		e.preventDefault();
		method.close();
	});
	// Clicking the overlay will close the modal (maybe undesirable)
	$overlay.click(function(e){
		method.close();
	});
	// return array of functions that can be called to utilise modals
	return method;
}());
//************ END MODAL SETUP ***************