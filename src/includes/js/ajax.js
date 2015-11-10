// Author: Johnpaul McMahon
// Description: Script for all AJAX related functionality:
// Project: BOXTAR UK

$(document).ready(function(){

	//************ AJAX INPUT VALIDATION ************
	// Hide the submit button initially:
	$('.ajax-validate-input').closest('form').find('input[type="submit"]').attr('disabled','disabled').hide();
	// Show submit button when a change has been made:
	$('.ajax-validate-input').closest('form').find('input[type="text"], input[type="password"], textarea').on('keyup.showsubmit', function(){
		$(this).closest('form').find('input:submit').slideDown();
		$(this).unbind('keyup.showsubmit');
	})
	// Input box keyup event:
	// @TODO: INSTEAD OF AN ANONYMOUS FUNCTION, MAKE THIS A NAMED FUNCTION SO IT CAN BE CALLED ON PAGE LOAD TOO FOR SOME INITIAL FORM CHECKING!
	$('.ajax-validate-input').on('keyup', function(){
		// Change has been made so disable submit button:
		$('.ajax-validate-input').closest('form').find('input[type="submit"]').attr('disabled','disabled');
		// The value of the input fields to be validated:
		var string_to_validate = $(this).val();
		// Type of validation to be applied as defined by data attribute on input tag:
		var validation_type = $(this).data('validate');
		// Construct the data to post to the target script:
		var data_string = 'input=' + string_to_validate + '&validation_type=' + validation_type;
		// Target handler script:
		if($(this).data('target'))
			var target = $(this).data('target')+'.php';
		else
			var target = "includes/src/components/ajax/validate_input.php";
		// Bake validation status into label for the input (will re-design this as it's not at all flexible):
		$(this).parent().siblings(".validation-status").html('<img src="includes/img/loading.gif" style="width:25px;height:auto;"/>').slideDown();
		$.ajax({
			type: "POST",
			url: target,
			data: data_string,
			cache: false,
			context: this,
			success: function(html){
				// Update status element of target input box (baked into grid system - will redesign):
				$(this).parent().siblings(".validation-status").html(html).show();
				// This makes sure the submit button is enabled only if ALL input fields are valid:
				var valid = true;
				$('.validation-status > .status').each(function(){
					if($(this).data('status') == 'failed')
						valid = false;
				});
				if(valid)
					$(this).closest('form').find('input[type="submit"]').removeAttr('disabled');
			}
		});
	})
	//***********************************************

	//*********** AJAX FORM SUBMISISON *************
	// THIS BREAKS CSRF TOKEN AS IT DELETES IT ON CHECK BUT DOESNT GENERATE A NEW ONE
	// NOT IN USE:
	$('.ajax-form').on('submit', function(e){
		var form = $(this);
		var target = $(this).data('target')+'.php';
		var data_string = form.serialize();
		$.ajax({
			type: "POST",
			url: target,
			data: data_string,
			cache: false,
			success: function(html){
				$('#update-status').html(html).show();
			}
		});
		e.preventDefault();
	});
	//*********************************************

	//************ AJAX SEARCH ************
	$('.ajax-search').on('keyup', function(){
		var search_string = $(this).val();
		var data_string = 'search_string='+ search_string;
		if(search_string==''){
		}
		else{
			$.ajax({
				type: "POST",
				url: "includes/src/components/ajax/search.php",
				data: data_string,
				cache: false,
				success: function(html){
					//$("#display").html(html).show();
					// Deal with search results
				}
			});
		}return false; 
	});
	//*************************************

	/********** AJAX POST SUBMISSION **********/
	$('#post-button').click(function(){ post_submit(); });
	
	/**
	 * Submit user post to server via AJAX
	 */
	function post_submit(){
		// Task to be performed by AJAX script:
		var _task = "post-submit";
		// Unique Token for CSRF protection:
		var _token = $('#token').val();
		// The users post:
		var _post = $('#post-content').val();
		_post = _post.trim();
		// The user submitting the post:
		var _user = $('#user').val();
		// The target of the post:
		var _target = $('#target').val();
		// The type of the target:
		var _target_type = $('#target_type').val();
	
		if(_post.length>0 && _user!=null){
			// Set border back to default:
			$('.post-submit-wrapper').css('border', '1px solid #E1E1E1');
		
			$.post(
				"includes/src/components/ajax/post_submit.php",
				{
					task: _task,
					token: _token,
					user: _user,
					target: _target,
					target_type: _target_type,
					post: _post
				}
			).error(
				function(){
					console.log("Error");
				}
			).success(
				function(data){
					var the_data = jQuery.parseJSON(data);
					prepend_new_post(the_data);
					$('#token').val(the_data.token);
					console.log(data);
				}
			);
		}
		else{
			// Set border to red to indicate error:
			$('.post-submit-wrapper').css('border', '1px solid red');
		
			// DEBUG
			console.log("Textarea is empty");
		}
		// Clear textarea for a new comment:
		$('#post-content').val("");
	}
	
	/**
	 * Add the new post to the top of the posts list on the page
	 */
	function prepend_new_post(data){
		var t='';
		t += '<li class="post" id="_'+data.post_id+'">';
		t += '<div class="post-img-wrapper">';
		t += '<img src="get_img.php?img='+data.user_avatar+'" alt="" class="post-img"/>';
		t += '</div>';
		t += '<div class="post-content-wrapper">';
		t += '<h3 class="post-username">'+data.user+'</h3>';
		t += '<div class="post-content"><p>'+data.post+'</p></div>';
		t += '</div>';
		t += '<div class="post-buttons-wrapper">';
		t += '<ul class="post-buttons">';
		t += '<li class="post-button"><center><i class="fa fa-times"></i></center></li>';
		t += '<li class="post-button"><center><i class="fa fa-pencil-square-o"></i></center></li>';
		t += '</ul>';
		t += '</div>';
		t += '</li>';
		$('.posts-list').prepend(t);
	}
	/******************************************/
	
 
});//document.ready

