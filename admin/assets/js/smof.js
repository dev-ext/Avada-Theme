/**
 * SMOF js
 *
 * contains the core functionalities to be used
 * inside SMOF
 */

jQuery.noConflict();

/** Fire up jQuery - let's dance! 
 */
jQuery(document).ready(function($){
	
	//(un)fold options in a checkbox-group
  	jQuery('.fld').click(function() {
    	var $fold='.f_'+this.id;
    	$($fold).slideToggle('normal', "swing");
  	});
	
	//delays until AjaxUpload is finished loading
	//fixes bug in Safari and Mac Chrome
	if (typeof AjaxUpload != 'function') { 
			return ++counter < 6 && window.setTimeout(init, counter * 500);
	}
	
	//hides warning if js is enabled			
	$('#js-warning').hide();
	
	//Tabify Options			
	$('.group').hide();
	
	// Display last current tab	
	if ($.cookie("of_current_opt") === null) {
		$('.group:first').fadeIn('fast');	
		$('#of-nav li:first').addClass('current');
	} else {
	
		var hooks = $('#hooks').html();
		hooks = jQuery.parseJSON(hooks);
		
		$.each(hooks, function(key, value) { 
		
			if ($.cookie("of_current_opt") == '#of-option-'+ value) {
				$('.group#of-option-' + value).fadeIn();
				$('#of-nav li.' + value).addClass('current');
			}
			
		});
	
	}
				
	//Current Menu Class
	$('#of-nav li a').click(function(evt){
	// event.preventDefault();
				
		$('#of-nav li').removeClass('current');
		$(this).parent().addClass('current');
							
		var clicked_group = $(this).attr('href');
		
		$.cookie('of_current_opt', clicked_group, { expires: 7, path: '/' });
			
		$('.group').hide();
							
		$(clicked_group).fadeIn('fast');
		return false;
						
	});

	//Expand Options 
	var flip = 0;
				
	$('#expand_options').click(function(){
		if(flip == 0){
			flip = 1;
			$('#of_container #of-nav').hide();
			$('#of_container #content').width(755);
			$('#of_container .group').add('#of_container .group h2').show();
	
			$(this).removeClass('expand');
			$(this).addClass('close');
			$(this).text('Close');
					
		} else {
			flip = 0;
			$('#of_container #of-nav').show();
			$('#of_container #content').width(595);
			$('#of_container .group').add('#of_container .group h2').hide();
			$('#of_container .group:first').show();
			$('#of_container #of-nav li').removeClass('current');
			$('#of_container #of-nav li:first').addClass('current');
					
			$(this).removeClass('close');
			$(this).addClass('expand');
			$(this).text('Expand');
				
		}
			
	});
	
	//Update Message popup
	$.fn.center = function () {
		this.animate({"top":( $(window).height() - this.height() - 200 ) / 2+$(window).scrollTop() + "px"},100);
		this.css("left", 250 );
		return this;
	}
		
			
	$('#of-popup-save').center();
	$('#of-popup-reset').center();
	$('#of-popup-fail').center();
			
	$(window).scroll(function() { 
		$('#of-popup-save').center();
		$('#of-popup-reset').center();
		$('#of-popup-fail').center();
	});
			

	//Masked Inputs (images as radio buttons)
	$('.of-radio-img-img').click(function(){
		$(this).parent().parent().find('.of-radio-img-img').removeClass('of-radio-img-selected');
		$(this).addClass('of-radio-img-selected');
	});
	$('.of-radio-img-label').hide();
	$('.of-radio-img-img').show();
	$('.of-radio-img-radio').hide();
	
	//Masked Inputs (background images as radio buttons)
	$('.of-radio-tile-img').click(function(){
		$(this).parent().parent().find('.of-radio-tile-img').removeClass('of-radio-tile-selected');
		$(this).addClass('of-radio-tile-selected');
	});
	$('.of-radio-tile-label').hide();
	$('.of-radio-tile-img').show();
	$('.of-radio-tile-radio').hide();

	//AJAX Upload
	function of_image_upload() {
	$('.image_upload_button').each(function(){
			
	var clickedObject = $(this);
	var clickedID = $(this).attr('id');	
			
	var nonce = $('#security').val();
			
	new AjaxUpload(clickedID, {
		action: ajaxurl,
		name: clickedID, // File upload name
		data: { // Additional data to send
			action: 'of_ajax_post_action',
			type: 'upload',
			security: nonce,
			data: clickedID },
		autoSubmit: true, // Submit file after selection
		responseType: false,
		onChange: function(file, extension){},
		onSubmit: function(file, extension){
			clickedObject.text('Uploading'); // change button text, when user selects file	
			this.disable(); // If you want to allow uploading only 1 file at time, you can disable upload button
			interval = window.setInterval(function(){
				var text = clickedObject.text();
				if (text.length < 13){	clickedObject.text(text + '.'); }
				else { clickedObject.text('Uploading'); } 
				}, 200);
		},
		onComplete: function(file, response) {
			window.clearInterval(interval);
			clickedObject.text('Upload Image');	
			this.enable(); // enable upload button
				
	
			// If nonce fails
			if(response==-1){
				var fail_popup = $('#of-popup-fail');
				fail_popup.fadeIn();
				window.setTimeout(function(){
				fail_popup.fadeOut();                        
				}, 2000);
			}				
					
			// If there was an error
			else if(response.search('Upload Error') > -1){
				var buildReturn = '<span class="upload-error">' + response + '</span>';
				$(".upload-error").remove();
				clickedObject.parent().after(buildReturn);
				
				}
			else{
				var buildReturn = '<img class="hide of-option-image" id="image_'+clickedID+'" src="'+response+'" alt="" />';

				$(".upload-error").remove();
				$("#image_" + clickedID).remove();	
				clickedObject.parent().after(buildReturn);
				$('img#image_'+clickedID).fadeIn();
				clickedObject.next('span').fadeIn();
				clickedObject.parent().prev('input').val(response);
			}
		}
	});
			
	});
	
	}
	
	of_image_upload();
			
	//AJAX Remove Image (clear option value)
	$('.image_reset_button').live('click', function(){
	
		var clickedObject = $(this);
		var clickedID = $(this).attr('id');
		var theID = $(this).attr('title');	
				
		var nonce = $('#security').val();
	
		var data = {
			action: 'of_ajax_post_action',
			type: 'image_reset',
			security: nonce,
			data: theID
		};
					
		$.post(ajaxurl, data, function(response) {
						
			//check nonce
			if(response==-1){ //failed
							
				var fail_popup = $('#of-popup-fail');
				fail_popup.fadeIn();
				window.setTimeout(function(){
					fail_popup.fadeOut();                        
				}, 2000);
			}
						
			else {
						
				var image_to_remove = $('#image_' + theID);
				var button_to_hide = $('#reset_' + theID);
				image_to_remove.fadeOut(500,function(){ $(this).remove(); });
				button_to_hide.fadeOut();
				clickedObject.parent().prev('input').val('');
			}
						
						
		});
					
	}); 

	// Style Select
	(function ($) {
	styleSelect = {
		init: function () {
		$('.select_wrapper').each(function () {
			$(this).prepend('<span>' + $(this).find('.select option:selected').text() + '</span>');
		});
		$('.select').live('change', function () {
			$(this).prev('span').replaceWith('<span>' + $(this).find('option:selected').text() + '</span>');
		});
		$('.select').bind($.browser.msie ? 'click' : 'change', function(event) {
			$(this).prev('span').replaceWith('<span>' + $(this).find('option:selected').text() + '</span>');
		}); 
		}
	};
	$(document).ready(function () {
		styleSelect.init()
	})
	})(jQuery);
	
	
	/** Aquagraphite Slider MOD */
	
	//Hide (Collapse) the toggle containers on load
	$(".slide_body").hide(); 

	//Switch the "Open" and "Close" state per click then slide up/down (depending on open/close state)
	$(".slide_edit_button").live( 'click', function(){
		$(this).parent().toggleClass("active").next().slideToggle("fast");
		return false; //Prevent the browser jump to the link anchor
	});	
	
	// Update slide title upon typing		
	function update_slider_title(e) {
		var element = e;
		if ( this.timer ) {
			clearTimeout( element.timer );
		}
		this.timer = setTimeout( function() {
			$(element).parent().prev().find('strong').text( element.value );
		}, 100);
		return true;
	}
	
	$('.of-slider-title').live('keyup', function(){
		update_slider_title(this);
	});
		
	
	//Remove individual slide
	$('.slide_delete_button').live('click', function(){
	// event.preventDefault();
	var agree = confirm("Are you sure you wish to delete this slide?");
		if (agree) {
			var $trash = $(this).parents('li');
			//$trash.slideUp('slow', function(){ $trash.remove(); }); //chrome + confirm bug made slideUp not working...
			$trash.animate({
					opacity: 0.25,
					height: 0,
				}, 500, function() {
					$(this).remove();
			});
			return false; //Prevent the browser jump to the link anchor
		} else {
		return false;
		}	
	});
	
	//Add new slide
	$(".slide_add_button").live('click', function(){		
		var slidesContainer = $(this).prev();
		var sliderId = slidesContainer.attr('id');
		var sliderInt = $('#'+sliderId).attr('rel');
		
		var numArr = $('#'+sliderId +' li').find('.order').map(function() { 
			var str = this.id;
			str = str.replace(/\D/g,'');
			str = str.substring(1);
			str = parseFloat(str);
			return str;			
		}).get();
		
		var maxNum = Math.max.apply(Math, numArr);
		if (maxNum < 1 ) { maxNum = 0};
		var newNum = parseFloat(maxNum) + 1;

		var newSlide = '<li class="temphide"><div class="slide_header"><strong>Slide ' + newNum + '</strong><input type="hidden" class="slide of-input order" name="' + sliderId + '[' + newNum + '][order]" id="' + sliderId + '_slide_order-' + newNum + '" value="' + newNum + '"><a class="slide_edit_button" href="#">Edit</a></div><div class="slide_body" style="display: none; "><label>Title</label><input class="slide of-input of-slider-title" name="' + sliderId + '[' + newNum + '][title]" id="' + sliderId + '_' + newNum + '_slide_title" value=""><label>Image URL</label><input class="slide of-input" name="' + sliderId + '[' + newNum + '][url]" id="' + sliderId + '_' + newNum + '_slide_url" value=""><div class="upload_button_div"><span class="button media_upload_button" id="' + sliderId + '_' + newNum + '" rel="'+sliderInt+'">Upload</span><span class="button mlu_remove_button hide" id="reset_' + sliderId + '_' + newNum + '" title="' + sliderId + '_' + newNum + '">Remove</span></div><div class="screenshot"></div><label>Link URL (optional)</label><input class="slide of-input" name="' + sliderId + '[' + newNum + '][link]" id="' + sliderId + '_' + newNum + '_slide_link" value=""><label>Video Embed Code (optional)</label><textarea class="slide of-input" name="' + sliderId + '[' + newNum + '][description]" id="' + sliderId + '_' + newNum + '_slide_description" cols="8" rows="8"></textarea><a class="slide_delete_button" href="#">Delete</a><div class="clear"></div></div></li>';
		
		slidesContainer.append(newSlide);
		$('.temphide').fadeIn('fast', function() {
			$(this).removeClass('temphide');
		});
				
		of_image_upload(); // re-initialise upload image..
		
		return false; //prevent jumps, as always..
	});	
	
	//Sort slides
	jQuery('.slider').find('ul').each( function() {
		var id = jQuery(this).attr('id');
		$('#'+ id).sortable({
			placeholder: "placeholder",
			opacity: 0.6
		});	
	});
	
	
	/**	Sorter (Layout Manager) */
	jQuery('.sorter').each( function() {
		var id = jQuery(this).attr('id');
		$('#'+ id).find('ul').sortable({
			items: 'li',
			placeholder: "placeholder",
			connectWith: '.sortlist_' + id,
			opacity: 0.6,
			update: function() {
				$(this).find('.position').each( function() {
				
					var listID = $(this).parent().attr('id');
					var parentID = $(this).parent().parent().attr('id');
					parentID = parentID.replace(id + '_', '')
					var optionID = $(this).parent().parent().parent().attr('id');
					$(this).prop("name", optionID + '[' + parentID + '][' + listID + ']');
					
				});
			}
		});	
	});
	
	
	/**	Ajax Backup & Restore MOD */
	//backup button
	$('#of_backup_button').live('click', function(){
	
		var answer = confirm("Click OK to backup your current saved options.")
		
		if (answer){
	
			var clickedObject = $(this);
			var clickedID = $(this).attr('id');
					
			var nonce = $('#security').val();
		
			var data = {
				action: 'of_ajax_post_action',
				type: 'backup_options',
				security: nonce
			};
						
			$.post(ajaxurl, data, function(response) {
							
				//check nonce
				if(response==-1){ //failed
								
					var fail_popup = $('#of-popup-fail');
					fail_popup.fadeIn();
					window.setTimeout(function(){
						fail_popup.fadeOut();                        
					}, 2000);
				}
							
				else {
							
					var success_popup = $('#of-popup-save');
					success_popup.fadeIn();
					window.setTimeout(function(){
						location.reload();                        
					}, 1000);
				}
							
			});
			
		}
		
	return false;
					
	}); 
	
	//restore button
	$('#of_restore_button').live('click', function(){
	
		var answer = confirm("'Warning: All of your current options will be replaced with the data from your last backup! Proceed?")
		
		if (answer){
	
			var clickedObject = $(this);
			var clickedID = $(this).attr('id');
					
			var nonce = $('#security').val();
		
			var data = {
				action: 'of_ajax_post_action',
				type: 'restore_options',
				security: nonce
			};
						
			$.post(ajaxurl, data, function(response) {
			
				//check nonce
				if(response==-1){ //failed
								
					var fail_popup = $('#of-popup-fail');
					fail_popup.fadeIn();
					window.setTimeout(function(){
						fail_popup.fadeOut();                        
					}, 2000);
				}
							
				else {
							
					var success_popup = $('#of-popup-save');
					success_popup.fadeIn();
					window.setTimeout(function(){
						location.reload();                        
					}, 1000);
				}	
						
			});
	
		}
	
	return false;
					
	});
	
	/**	Ajax Transfer (Import/Export) Option */
	$('#of_import_button').live('click', function(){
	
		var answer = confirm("Click OK to import options.")
		
		if (answer){
	
			var clickedObject = $(this);
			var clickedID = $(this).attr('id');
					
			var nonce = $('#security').val();
			
			var import_data = $('#export_data').val();
		
			var data = {
				action: 'of_ajax_post_action',
				type: 'import_options',
				security: nonce,
				data: import_data
			};
						
			$.post(ajaxurl, data, function(response) {
				var fail_popup = $('#of-popup-fail');
				var success_popup = $('#of-popup-save');
				
				//check nonce
				if(response==-1){ //failed
					fail_popup.fadeIn();
					window.setTimeout(function(){
						fail_popup.fadeOut();                        
					}, 2000);
				}		
				else 
				{
					success_popup.fadeIn();
					window.setTimeout(function(){
						location.reload();                        
					}, 1000);
				}
							
			});
			
		}
		
	return false;
					
	});
	
	/** AJAX Save Options */
	$('#of_save').live('click',function() {
			
		var nonce = $('#security').val();
					
		$('.ajax-loading-img').fadeIn();
		
		//get serialized data from all our option fields			
		var serializedReturn = $('#of_form :input[name][name!="security"][name!="of_reset"]').serialize();
						
		var data = {
			type: 'save',
			action: 'of_ajax_post_action',
			security: nonce,
			data: serializedReturn
		};
					
		$.post(ajaxurl, data, function(response) {
			var success = $('#of-popup-save');
			var fail = $('#of-popup-fail');
			var loading = $('.ajax-loading-img');
			loading.fadeOut();  
						
			if (response==1) {
				success.fadeIn();
			} else { 
				fail.fadeIn();
			}
						
			window.setTimeout(function(){
				success.fadeOut(); 
				fail.fadeOut();				
			}, 2000);
		});
			
	return false; 
					
	});   
	
	
	/* AJAX Options Reset */	
	$('#of_reset').click(function() {
		
		//confirm reset
		var answer = confirm("Click OK to reset. All settings will be lost and replaced with default settings!");
		
		//ajax reset
		if (answer){
			
			var nonce = $('#security').val();
						
			$('.ajax-reset-loading-img').fadeIn();
							
			var data = {
			
				type: 'reset',
				action: 'of_ajax_post_action',
				security: nonce,
			};
						
			$.post(ajaxurl, data, function(response) {
				var success = $('#of-popup-reset');
				var fail = $('#of-popup-fail');
				var loading = $('.ajax-reset-loading-img');
				loading.fadeOut();  
							
				if (response==1)
				{
					success.fadeIn();
					window.setTimeout(function(){
						location.reload();                        
					}, 1000);
				} 
				else 
				{ 
					fail.fadeIn();
					window.setTimeout(function(){
						fail.fadeOut();				
					}, 2000);
				}
							

			});
			
		}
			
	return false;
		
	});


	/**	Tipsy @since v1.3 */
	if (jQuery().tipsy) {
		$('.typography-size, .typography-height, .typography-face, .typography-style, .of-typography-color').tipsy({
			fade: true,
			gravity: 's',
			opacity: 0.7,
		});
	}
	
}); //end doc ready


jQuery(document).ready(function($) {    
    var green = new Array();
    green['primary_color']='#a0ce4e';
    green['pricing_box_color']='#92C563';
    green['image_gradient_top_color']='#D1E990';
    green['image_gradient_bottom_color']='#AAD75B';
    green['button_gradient_top_color']='#D1E990';
    green['button_gradient_bottom_color']='#AAD75B';
    green['button_gradient_text_color']='#54770f';

    var darkgreen = new Array();
    darkgreen['primary_color']='#9db668';
    darkgreen['pricing_box_color']='#a5c462';
    darkgreen['image_gradient_top_color']='#cce890';
    darkgreen['image_gradient_bottom_color']='#afd65a';
    darkgreen['button_gradient_top_color']='#cce890';
    darkgreen['button_gradient_bottom_color']='#AAD75B';
    darkgreen['button_gradient_text_color']='#577810';

    var orange = new Array();
    orange['primary_color']='#e9a825';
    orange['pricing_box_color']='#c4a362';
    orange['image_gradient_top_color']='#e8cb90';
    orange['image_gradient_bottom_color']='#d6ad5a';
    orange['button_gradient_top_color']='#e8cb90';
    orange['button_gradient_bottom_color']='#d6ad5a';
    orange['button_gradient_text_color']='#785510';

    var lightblue = new Array();
    lightblue['primary_color']='#67b7e1';
    lightblue['pricing_box_color']='#62a2c4';
    lightblue['image_gradient_top_color']='#90c9e8';
    lightblue['image_gradient_bottom_color']='#5aabd6';
    lightblue['button_gradient_top_color']='#90c9e8';
    lightblue['button_gradient_bottom_color']='#5aabd6';
    lightblue['button_gradient_text_color']='#105378';

    var lightred = new Array();
    lightred['primary_color']='#f05858';
    lightred['pricing_box_color']='#c46262';
    lightred['image_gradient_top_color']='#e89090';
    lightred['image_gradient_bottom_color']='#d65a5a';
    lightred['button_gradient_top_color']='#e89090';
    lightred['button_gradient_bottom_color']='#d65a5a';
    lightred['button_gradient_text_color']='#781010';

    var pink = new Array();
    pink['primary_color']='#e67fb9';
    pink['pricing_box_color']='#c46299';
    pink['image_gradient_top_color']='#e890c2';
    pink['image_gradient_bottom_color']='#d65aa0';
    pink['button_gradient_top_color']='#e890c2';
    pink['button_gradient_bottom_color']='#d65aa0';
    pink['button_gradient_text_color']='#78104b';

    var lightgrey = new Array();
    lightgrey['primary_color']='#9e9e9e';
    lightgrey['pricing_box_color']='#c4c4c4';
    lightgrey['image_gradient_top_color']='#e8e8e8';
    lightgrey['image_gradient_bottom_color']='#d6d6d6';
    lightgrey['button_gradient_top_color']='#e8e8e8';
    lightgrey['button_gradient_bottom_color']='#d6d6d6';
    lightgrey['button_gradient_text_color']='#787878';

    var brown = new Array();
    brown['primary_color']='#ab8b65';
    brown['pricing_box_color']='#c49862';
    brown['image_gradient_top_color']='#e8c090';
    brown['image_gradient_bottom_color']='#d69e5a';
    brown['button_gradient_top_color']='#e8c090';
    brown['button_gradient_bottom_color']='#d69e5a';
    brown['button_gradient_text_color']='#784910';

    var red = new Array();
    red['primary_color']='#e10707';
    red['pricing_box_color']='#c40606';
    red['image_gradient_top_color']='#e80707';
    red['image_gradient_bottom_color']='#d60707';
    red['button_gradient_top_color']='#e80707';
    red['button_gradient_bottom_color']='#d60707';
    red['button_gradient_text_color']='#780404';

    var blue = new Array();
    blue['primary_color']='#1a80b6';
    blue['pricing_box_color']='#62a2c4';
    blue['image_gradient_top_color']='#90c9e8';
    blue['image_gradient_bottom_color']='#5aabd6';
    blue['button_gradient_top_color']='#90c9e8';
    blue['button_gradient_bottom_color']='#5aabd6';
    blue['button_gradient_text_color']='#105378';

    var light = new Array();
    light['header_bg_color'] = '#ffffff';
    light['header_border_color'] = '#efefef';
    light['content_bg_color'] = '#ffffff';
    light['footer_bg_color'] = '#363839';
    light['footer_border_color'] = '#e9eaee';
    light['copyright_border_color'] = '#4B4C4D';
    light['copyright_bg_color'] = '#282a2b';
    light['title_border_color'] = '#e7e6e6';
    light['testimonial_bg_color'] = '#f6f3f3';
    light['testimonial_text_color'] = '#747474';
    light['sep_color'] = '#e0dede';
    light['form_bg_color'] = '#ffffff';
    light['form_text_color'] = '#aaa9a9';
    light['form_border_color'] = '#d2d2d2';
    light['tagline_font_color'] = '#747474';
    light['page_title_color'] = '#333333';
    light['h1_color'] = '#333333';
    light['h2_color'] = '#333333';
    light['h3_color'] = '#333333';
    light['h4_color'] = '#333333';
    light['h5_color'] = '#333333';
    light['h6_color'] = '#333333';
    light['body_text_color'] = '#747474';
    light['link_color'] = '#333333';
    light['menu_first_color'] = '#333333';
    light['menu_sub_bg_color'] = '#edebeb';
    light['menu_sub_color'] = '#333333';
    light['menu_bg_hover_color'] = '#f5f4f4';
    light['menu_sub_sep_color'] = '#dcdadb';
    light['snav_color'] = '#ffffff';
    light['header_top_first_border_color'] = '#efefef';
    light['header_top_sub_bg_color'] = '#ffffff';
    light['header_top_menu_sub_color'] = '#333333';
    light['header_top_menu_bg_hover_color'] = '#fafafa';
    light['header_top_menu_sub_hover_color'] = '#333333';
    light['header_top_menu_sub_sep_color'] = '#e0dfdf';
    light['sidebar_bg_color'] = '#ffffff';
    light['page_title_bg_color'] = '#F6F6F6';
    light['page_title_border_color'] = '#d2d3d4';
    light['accordian_inactive_color'] = '#333333';
    light['counter_filled_color'] = '#a0ce4e';
    light['counter_unfilled_color'] = '#f6f6f6';
    light['arrow_color'] = '#333333';
    light['dates_box_color'] = '#eef0f2';
    light['carousel_nav_color'] = '#999999';
    light['carousel_hover_color'] = '#808080';
    light['content_box_bg_color'] = 'transparent';
    light['title_border_color'] = '#e0dede';
    light['icon_circle_color'] = '#333333';
    light['icon_border_color'] = '#333333';
    light['icon_color'] = '#ffffff';
    light['imgframe_border_color'] = '#f6f6f6';
    light['imgframe_style_color'] = '#000000';
    light['pricing_bg_color'] = '#ffffff';
    light['pricing_border_color'] = '#f8f8f8';
    light['pricing_divider_color'] = '#ededed';
    light['social_bg_color'] = '#f6f6f6';
    light['tabs_bg_color'] = '#ffffff';
    light['tabs_inactive_color'] = '#ebeaea';
    light['tagline_bg'] = '#f6f6f6';
    light['tagline_border_color'] = '#f6f6f6';
    light['timeline_color'] = '#ebeaea';

    var dark = new Array()
    dark['header_bg_color'] = '#29292a';
    dark['header_border_color'] = '#3e3e3e';
    dark['header_top_bg_color'] = '#3e3e3e';
    dark['content_bg_color'] = '#29292a';
    dark['footer_bg_color'] = '#363839';
    dark['footer_border_color'] = '#484747';
    dark['copyright_border_color'] = '#4B4C4D';
    dark['copyright_bg_color'] = '#282a2b';
    dark['title_border_color'] = '#3e3e3e';
    dark['testimonial_bg_color'] = '#3e3e3e';
    dark['testimonial_text_color'] = '#aaa9a9';
    dark['sep_color'] = '#3e3e3e';
    dark['form_bg_color'] = '#3e3e3e';
    dark['form_text_color'] = '#cccccc';
    dark['form_border_color'] = '#212122';
    dark['tagline_font_color'] = '#ffffff';
    dark['page_title_color'] = '#3e3e3e';
    dark['h1_color'] = '#ffffff';
    dark['h2_color'] = '#ffffff';
    dark['h3_color'] = '#ffffff';
    dark['h4_color'] = '#ffffff';
    dark['h5_color'] = '#ffffff';
    dark['h6_color'] = '#ffffff';
    dark['body_text_color'] = '#aaa9a9';
    dark['link_color'] = '#ffffff';
    dark['menu_first_color'] = '#ffffff';
    dark['menu_sub_bg_color'] = '#3e3e3e';
    dark['menu_sub_color'] = '#d6d6d6';
    dark['menu_bg_hover_color'] = '#383838';
    dark['menu_sub_sep_color'] = '#313030';
    dark['snav_color'] = '#747474';
    dark['header_top_first_border_color'] = '#555555';
    dark['header_top_sub_bg_color'] = '#ffffff';
    dark['header_top_menu_sub_color'] = '#333333';
    dark['header_top_menu_bg_hover_color'] = '#fafafa';
    dark['header_top_menu_sub_hover_color'] = '#555555';
    dark['header_top_menu_sub_sep_color'] = '#e0dfdf';
    dark['sidebar_bg_color'] = '#29292a';
    dark['page_title_bg_color'] = '#353535';
    dark['page_title_border_color'] = '#464646';
    dark['accordian_inactive_color'] = '#3e3e3e';
    dark['counter_filled_color'] = '#a0ce4e';
    dark['counter_unfilled_color'] = '#3e3e3e';
    dark['arrow_color'] = '#ffffff';
    dark['dates_box_color'] = '#3e3e3e';
    dark['carousel_nav_color'] = '#3a3a3a';
    dark['carousel_hover_color'] = '#333333';
    dark['content_box_bg_color'] = 'transparent';
    dark['title_border_color'] = '#3e3e3e';
    dark['icon_circle_color'] = '#3e3e3e';
    dark['icon_border_color'] = '#3e3e3e';
    dark['icon_color'] = '#ffffff';
    dark['imgframe_border_color'] = '#494848';
    dark['imgframe_style_color'] = '#000000';
    dark['pricing_bg_color'] = '#3e3e3e';
    dark['pricing_border_color'] = '#353535';
    dark['pricing_divider_color'] = '#29292a';
    dark['social_bg_color'] = '#3e3e3e';
    dark['tabs_bg_color'] = '#3e3e3e';
    dark['tabs_inactive_color'] = '#313132';
    dark['tagline_bg'] = '#3e3e3e';
    dark['tagline_border_color'] = '#3e3e3e';
    dark['timeline_color'] = '#3e3e3e';

    $('#scheme_type').change(function() {
        colorscheme = $(this).val();

        if (colorscheme == 'Dark') { colorscheme = dark; }
        if (colorscheme == 'Light') { colorscheme = light; }

        for (id in colorscheme) {
            of_update_color(id,colorscheme[id]);
        }

        var name = $('#section-header_layout input:checked').val();
        if($(this).val() == 'Light') {
    		jQuery('#checklist_icons_color option:selected,#social_links_color option:selected').removeAttr('selected');
    		jQuery('#checklist_icons_color,#social_links_color').val('Dark');
    		jQuery('#section-checklist_icons_color .select_wrapper span,#section-social_links_color .select_wrapper span').html('Dark');
	    	if(name == 'v2') {
	    		of_update_color('header_top_bg_color', '#ffffff');
	    		of_update_color('header_top_first_border_color', '#efefef');
	    		of_update_color('snav_color', '#747474');
	    		jQuery('#header_icons_color option:selected').removeAttr('selected');
	    		jQuery('#header_icons_color').val('Dark');
	    		jQuery('#section-header_icons_color .select_wrapper span').html('Dark');
	    	} else if(name == 'v3' || name == 'v4' || name == 'v5') {
	    		of_update_color('header_top_bg_color', $('#primary_color').val());
	    		of_update_color('header_top_first_border_color', '#ffffff');
	    		of_update_color('snav_color', '#ffffff');
	    		jQuery('#header_icons_color option:selected').removeAttr('selected');
	    		jQuery('#header_icons_color').val('Light');
	    		jQuery('#section-header_icons_color .select_wrapper span').html('Light');
	    	}
        } else if($(this).val() == 'Dark') {
    		jQuery('#checklist_icons_color option:selected,#social_links_color option:selected').removeAttr('selected');
    		jQuery('#checklist_icons_color,#social_links_color').val('Light');
    		jQuery('#section-checklist_icons_color .select_wrapper span,#section-social_links_color .select_wrapper span').html('Light');
	    	if(name == 'v2') {
	    		of_update_color('header_top_bg_color', '#29292a');
	    		of_update_color('header_top_first_border_color', '#3e3e3e');
	    		of_update_color('snav_color', '#ffffff');
	    		jQuery('#header_icons_color option:selected').removeAttr('selected');
	    		jQuery('#header_icons_color').val('Light');
	    		jQuery('#section-header_icons_color .select_wrapper span').html('Light');
	    	} else if(name == 'v3' || name == 'v4' || name == 'v5') {
	    		of_update_color('header_top_bg_color', $('#primary_color').val());
	    		of_update_color('header_top_first_border_color', '#ffffff');
	    		of_update_color('snav_color', '#ffffff');
	    		jQuery('#header_icons_color option:selected').removeAttr('selected');
	    		jQuery('#header_icons_color').val('Light');
	    		jQuery('#section-header_icons_color .select_wrapper span').html('Light');
	    	}
        }
    });

    $('#color_scheme').change(function() {
        colorscheme = $(this).val();
        if (colorscheme == 'Green') { colorscheme = green; }
        if (colorscheme == 'Dark Green') { colorscheme = darkgreen; }
        if (colorscheme == 'Orange') { colorscheme = orange; }
        if (colorscheme == 'Light Blue') { colorscheme = lightblue; }
        if (colorscheme == 'Light Red') { colorscheme = lightred; }
        if (colorscheme == 'Pink') { colorscheme = pink; }
        if (colorscheme == 'Light Grey') { colorscheme = lightgrey; }
        if (colorscheme == 'Brown') { colorscheme = brown; }
        if (colorscheme == 'Red') { colorscheme = red; }
        if (colorscheme == 'Blue') { colorscheme = blue; }

        for (id in colorscheme) {
            of_update_color(id,colorscheme[id]);
        }
		
		var name = $('#section-header_layout input:checked').val();
        if($('#scheme_type').val() == 'Light') {
	    	if(name == 'v2') {
	    		of_update_color('header_top_bg_color', '#ffffff');
	    		of_update_color('header_top_first_border_color', '#efefef');
	    		of_update_color('snav_color', '#747474');
	    		jQuery('#header_icons_color option:selected').removeAttr('selected');
	    		jQuery('#header_icons_color').val('Dark');
	    		jQuery('#section-header_icons_color .select_wrapper span').html('Dark');
	    	} else if(name == 'v3' || name == 'v4' || name == 'v5') {
	    		of_update_color('header_top_bg_color', $('#primary_color').val());
	    		of_update_color('header_top_first_border_color', '#ffffff');
	    		of_update_color('snav_color', '#ffffff');
	    		jQuery('#header_icons_color option:selected').removeAttr('selected');
	    		jQuery('#header_icons_color').val('Light');
	    		jQuery('#section-header_icons_color .select_wrapper span').html('Light');
	    	}
        } else if($('#scheme_type').val() == 'Dark') {
	    	if(name == 'v2') {
	    		of_update_color('header_top_bg_color', '#29292a');
	    		of_update_color('header_top_first_border_color', '#3e3e3e');
	    		of_update_color('snav_color', '#ffffff');
	    		jQuery('#header_icons_color option:selected').removeAttr('selected');
	    		jQuery('#header_icons_color').val('Light');
	    		jQuery('#section-header_icons_color .select_wrapper span').html('Light');
	    	} else if(name == 'v3' || name == 'v4' || name == 'v5') {
	    		of_update_color('header_top_bg_color', $('#primary_color').val());
	    		of_update_color('header_top_first_border_color', '#ffffff');
	    		of_update_color('snav_color', '#ffffff');
	    		jQuery('#header_icons_color option:selected').removeAttr('selected');
	    		jQuery('#header_icons_color').val('Light');
	    		jQuery('#section-header_icons_color .select_wrapper span').html('Light');
	    	}
        }

        of_update_color('counter_filled_color', $('#primary_color').val());
    });

    // This does the heavy lifting of updating all the colorpickers and text
    function of_update_color(id,hex) {
        //$('#section-' + id + ' .of-color').css({backgroundColor:hex});
        $('#section-' + id + ' .colorSelector').ColorPickerSetColor(hex);
        $('#section-' + id + ' .colorSelector').children('div').css('backgroundColor', hex);
        $('#section-' + id + ' .of-color').val(hex);
        //$('#section-' + id + ' .of-color').animate({backgroundColor:'#ffffff'}, 600);
    }

    $('#section-header_layout img').click(function(e) {
    	e.preventDefault();

    	var name = $(this).parent().find('input[type=radio]').attr('value');

        if($('#scheme_type').val() == 'Light') {
	    	if(name == 'v2') {
	    		of_update_color('header_top_bg_color', '#ffffff');
	    		of_update_color('header_top_first_border_color', '#efefef');
	    		of_update_color('snav_color', '#747474');
	    		jQuery('#header_icons_color option:selected').removeAttr('selected');
	    		jQuery('#header_icons_color').val('Dark');
	    		jQuery('#section-header_icons_color .select_wrapper span').html('Dark');
	    	} else if(name == 'v3' || name == 'v4' || name == 'v5') {
	    		of_update_color('header_top_bg_color', $('#primary_color').val());
	    		of_update_color('header_top_first_border_color', '#ffffff');
	    		of_update_color('snav_color', '#ffffff');
	    		jQuery('#header_icons_color option:selected').removeAttr('selected');
	    		jQuery('#header_icons_color').val('Light');
	    		jQuery('#section-header_icons_color .select_wrapper span').html('Light');
	    	}
        } else if($('#scheme_type').val() == 'Dark') {
	    	if(name == 'v2') {
	    		of_update_color('header_top_bg_color', '#29292a');
	    		of_update_color('header_top_first_border_color', '#3e3e3e');
	    		of_update_color('snav_color', '#ffffff');
	    		jQuery('#header_icons_color option:selected').removeAttr('selected');
	    		jQuery('#header_icons_color').val('Light');
	    		jQuery('#section-header_icons_color .select_wrapper span').html('Light');
	    	} else if(name == 'v3' || name == 'v4' || name == 'v5') {
	    		of_update_color('header_top_bg_color', $('#primary_color').val());
	    		of_update_color('header_top_first_border_color', '#ffffff');
	    		of_update_color('snav_color', '#ffffff');
	    		jQuery('#header_icons_color option:selected').removeAttr('selected');
	    		jQuery('#header_icons_color').val('Light');
	    		jQuery('#section-header_icons_color .select_wrapper span').html('Light');
	    	}
        }

    	if(name == 'v4' || name == 'v5') {
    		$('#nav_height').attr('value', '40');
    	} else {
    		$('#nav_height').attr('value', '83');
    	}
    });
});
