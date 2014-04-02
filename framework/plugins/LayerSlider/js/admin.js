if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function (searchElement /*, fromIndex */ ) {
        "use strict";
        if (this == null) {
            throw new TypeError();
        }
        var t = Object(this);
        var len = t.length >>> 0;
        if (len === 0) {
            return -1;
        }
        var n = 0;
        if (arguments.length > 1) {
            n = Number(arguments[1]);
            if (n != n) { // shortcut for verifying if it's NaN
                n = 0;
            } else if (n != 0 && n != Infinity && n != -Infinity) {
                n = (n > 0 || -1) * Math.floor(Math.abs(n));
            }
        }
        if (n >= len) {
            return -1;
        }
        var k = n >= 0 ? n : Math.max(len - Math.abs(n), 0);
        for (; k < len; k++) {
            if (k in t && t[k] === searchElement) {
                return k;
            }
        }
        return -1;
    }
}

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

(function( $ ) {

	$.fn.customCheckbox = function() {

		var selector = this.selector;

		return this.each(function() {

			// Get the original element
			var el = this;

			// Hide the checkbox
			$(this).hide();

			// Create replacement element
			var rep = $('<a href="#"><span></span></a>').addClass('ls-checkbox').insertAfter(this);

			// Check help attr
			if($(this).attr('data-help') != "undefined") {
				$(rep).attr('data-help', $(this).attr('data-help'));
			}

			// Set default state
			if($(this).is(':checked')) {
				$(rep).addClass('on');
			} else {
				$(rep).addClass('off');
			}
		});
	};

})( jQuery );


var lsTooltip = {

	init : function() {

		jQuery(document).on('mouseover', '[data-help]', function() {

			lsTooltip.open(this);
		});

		jQuery(document).on('mouseout', '[data-help]', function() {
			lsTooltip.close();
		});
	},

	open : function(el) {

		// Create tooltip
		jQuery('body').prepend( jQuery('<div>', { 'class' : 'ls-tooltip' })
			.append( jQuery('<div>', { 'class' : 'inner' }))
			.append( jQuery('<span>') )
		);

		// Get tooltip
		var tooltip = jQuery('.ls-tooltip');

		// Set tooltip text
		tooltip.find('.inner').text( jQuery(el).attr('data-help'));

		// Get viewport dimensions
		var v_w = jQuery(window).width();

		// Get element dimensions
		var e_w = jQuery(el).width();

		// Get element position
		var e_l = jQuery(el).offset().left;
		var e_t = jQuery(el).offset().top;

		// Get toolip dimensions
		var t_w = tooltip.outerWidth();
		var t_h = tooltip.outerHeight();

		// Position tooltip
		tooltip.css({ top : e_t - t_h - 10, left : e_l - (t_w - e_w) / 2  });
		// Fix right position
		if(tooltip.offset().left + t_w > v_w) {
			tooltip.css({ 'left' : 'auto', 'right' : 10 });
			tooltip.find('span').css({ left : 'auto', right : v_w - jQuery(el).offset().left - jQuery(el).outerWidth() / 2 - 17, marginLeft : 'auto' });
		}

	},

	close : function() {
		jQuery('.ls-tooltip').remove();
	}
};

var LayerSlider = {

	uploadInput : null,
	dragContainer : null,
	dragIndex : 0,
	newIndex : 0,
	timeout : 0,
	counter : 0,

	selectMainTab : function(el) {

		// Remove highlight from the other tabs
		jQuery('#ls-main-nav-bar a').removeClass('active');

		// Highlight selected tab
		jQuery(el).addClass('active');

		// Hide other pages
		jQuery('#ls-pages .ls-page').removeClass('active');

		// Show selected page
		jQuery('#ls-pages .ls-page').eq( jQuery(el).index() ).addClass('active')
	},

	addLayer : function() {

		// Clone the sample layer page
		var clone = jQuery('#ls-sample > div').clone();

		// Append to place
		clone.appendTo('#ls-layers');

		// Close other layers
		jQuery('#ls-layer-tabs a').removeClass('active');

		// Get layer index
		var index = clone.index();

		// Add layer tab
		var tab = jQuery('<a href="#">Slide #'+(index+1)+'<span class="ls-icon-layer-remove">x</span></a>').insertBefore('#ls-add-layer');

		// Open new layer
		tab.click();

		// Add sortables
		LayerSlider.addSortables();

		// Generate preview
		LayerSlider.generatePreview(index);

		if(typeof jQuery.fn.wpColorPicker != "undefined") {

			clone.find('.ls-colorpicker').wpColorPicker({
				width : 150,
				change : function() {
					LayerSlider.willGeneratePreview( jQuery('.ls-box.active').index() );
				},
				clear : function() {
					LayerSlider.willGeneratePreview( jQuery('.ls-box.active').index() );
				}
			});
		}
	},

	removeLayer : function(el) {

		if(confirm('Are you sure you want to remove this slide?')) {

			// Get menu item
			var item = jQuery(el).parent();

			// Get layer
			var layer = jQuery(el).closest('#ls-layer-tabs').next().children().eq( item.index() );

			// Open next or prev layer
			if(layer.next().length > 0) {
				item.next().click();

			} else if(layer.prev().length > 0) {
				item.prev().click();
			}

			// Remove menu item
			item.remove();

			// Remove the layer
			layer.remove();

			// Reindex layers
			LayerSlider.reindexLayers();
		}
	},

	selectLayer : function(el) {

		// Close other layers
		jQuery('#ls-layer-tabs a').removeClass('active');
		jQuery('#ls-layers .ls-layer-box').removeClass('active');

		// Open new layer
		jQuery(el).addClass('active');
		jQuery('#ls-layers .ls-layer-box').eq( jQuery(el).index() ).addClass('active');

		// Open first sublayer
		jQuery('#ls-layers .ls-layer-box').eq( jQuery(el).index() ).find('.ls-sublayers td:first').click();

		// Update preview
		LayerSlider.generatePreview( jQuery(el).index() );

		// Stop preview
		LayerSlider.stop();
	},

	duplicateLayer : function(el) {

		// Clone fix
		LayerSlider.cloneFix();

		// Get layer index
		var index = jQuery(el).closest('.ls-layer-box').index();

		// Append new tab
		jQuery('<a href="#">Slide #0<span>x</span></a>').insertBefore('#ls-layer-tabs a:last');

		// Rename tab
		LayerSlider.reindexLayers();

		// Clone layer
		var clone = jQuery(el).closest('.ls-layer-box').clone();

		// Append new layer
		clone.appendTo('#ls-layers');

		// Remove active class if any
		clone.removeClass('active');

		// Add sortables
		LayerSlider.addSortables();


		// Color picker
		if(typeof jQuery.fn.wpColorPicker != "undefined") {

			// Re-init color picker
			clone.find('.ls-colorpicker').each(function() {
				jQuery(this).appendTo( jQuery(this).closest('td') );
				jQuery(this).closest('td').find('.wp-picker-container').remove();
			});

			jQuery(clone).find('.ls-colorpicker').wpColorPicker({
				width : 150,
				change : function() {
					LayerSlider.willGeneratePreview( jQuery('.ls-box.active').index() );
				},
				clear : function() {
					LayerSlider.willGeneratePreview( jQuery('.ls-box.active').index() );
				}
			});
		}
	},

	addSublayer : function(el) {

		// Get clone from sample
		var clone = jQuery('#ls-sample .ls-sublayers > tr').clone();

		// Appent to place
		clone.appendTo( jQuery(el).prev().find('.ls-sublayers') );

		// Get sublayer index
		var index = clone.index();

		// Rewrite sublayer number
		clone.find('.ls-sublayer-number').html( index + 1);
		clone.find('.ls-sublayer-title').val('Layer #' + (index + 1) );

		// Open it
		clone.click();

		if(typeof jQuery.fn.wpColorPicker != "undefined") {

			clone.find('.ls-colorpicker').wpColorPicker({
				width : 150,
				change : function() {
					LayerSlider.willGeneratePreview( jQuery('.ls-box.active').index() );
				},
				clear : function() {
					LayerSlider.willGeneratePreview( jQuery('.ls-box.active').index() );
				}
			});
		}
	},

	selectSubLayer : function(el) {

		if(jQuery(el).index() == jQuery(el).parent().children('.active:first').index() ) {
			return;
		}

		// Close other sublayers
		jQuery(el).parent().children().removeClass('active');

		// Open the new one
		jQuery(el).addClass('active');
	},

	selectSublayerPage : function(el) {

		// Close previous page
		jQuery(el).parent().children().removeClass('active');
		jQuery(el).parent().next().find('.ls-sublayer-page').removeClass('active');

		// Open the selected one
		jQuery(el).addClass('active');
		jQuery(el).parent().next().find('.ls-sublayer-page').eq( jQuery(el).index() ).addClass('active');
	},

	removeSublayer : function(el) {

		if(!confirm('Are you sure you want to remove this layer?')) {
			return;
		}

		// Get sublayer
		var sublayer = jQuery(el).closest('tr');

		// Get layer index
		var layer = jQuery(el).closest('.ls-layer-box');

		// Open the next or prev sublayer
		if(sublayer.next().length > 0) {
			sublayer.next().click();

		} else if(sublayer.prev().length > 0) {
			sublayer.prev().click();
		}

		// Remove menu item
		jQuery(el).remove();

		// Remove sublayer
		sublayer.remove();

		// Update preview
		LayerSlider.generatePreview( layer.index() );
	},

	highlightSublayer : function(el) {

		if(jQuery(el).prop('checked') == true) {

			// Deselect other checkboxes
			jQuery('.ls-highlight input').not(el).prop('checked', false);

			// Restore sublayers in the preview
			jQuery(el).closest('.ls-layer-box').find('.draggable').children().css({ opacity : 0.5 });

			// Get element index
			var index = jQuery(el).closest('tr').index();

			// Highlight selected one
			jQuery(el).closest('.ls-layer-box').find('.draggable').children().eq(index).css({ zIndex : 1000, opacity : 1 });

		} else {

			// Restore sublayers in the preview
			jQuery(el).closest('.ls-layer-box').find('.draggable').children().each(function(index) {
				jQuery(this).css({ zIndex : 10 + index });
				jQuery(this).css('opacity', 1);
			});
		}
	},

	eyeSublayer : function(el) {

		if(jQuery(el).hasClass('active')) {

			jQuery(el).removeClass('active');
		} else {
			jQuery(el).addClass('active');
		}

		// Update preview
		LayerSlider.generatePreview( jQuery('.ls-box.active').index() );
	},

	lockSublayer : function(el) {

		if(jQuery(el).hasClass('active')) {
			jQuery(el).removeClass('active');
		} else {
			jQuery(el).addClass('active');
		}

		// Update preview
		LayerSlider.generatePreview( jQuery('.ls-box.active').index() );
	},

	duplicateSublayer : function(el) {

		// Clone fix
		LayerSlider.cloneFix();

		// Clone sublayer
		var clone = jQuery(el).closest('.ls-sublayer-wrapper').closest('tr').clone();

		// Remove active class
		clone.removeClass('active');

		// Append
		clone.appendTo( jQuery(el).closest('.ls-sublayers')  );

		// Rename sublayer
		clone.find('.ls-sublayer-title').val( clone.find('.ls-sublayer-title').val() + ' copy' );
		LayerSlider.reindexSublayers( jQuery(el).closest('.ls-layer-box') );

		// Update preview
		LayerSlider.generatePreview( jQuery(el).closest('.ls-layer-box').index() );

		// Color picker
		if(typeof jQuery.fn.wpColorPicker != "undefined") {

			// Re-init color picker
			clone.find('.ls-colorpicker').each(function() {
				jQuery(this).appendTo( jQuery(this).closest('td') );
				jQuery(this).closest('td').find('.wp-picker-container').remove();
			});

			jQuery(clone).find('.ls-colorpicker').wpColorPicker({
				width : 150,
				change : function() {
					LayerSlider.willGeneratePreview( jQuery('.ls-box.active').index() );
				},
				clear : function() {
					LayerSlider.willGeneratePreview( jQuery('.ls-box.active').index() );
				}
			});
		}
	},

	skipSublayer : function(el) {

		LayerSlider.generatePreview( jQuery(el).closest('.ls-layer-box').index()  );
	},

	selectMediaType : function(el) {

		// Remove highlight from the others
		jQuery(el).parent().children().removeClass('active');

		// Highlight the selected one
		jQuery(el).addClass('active');

		// Deselect old elements
		jQuery(el).closest('.ls-sublayer-basic').find('select option').attr('selected', false);

		// Change the select option
		var option = jQuery(el).closest('.ls-sublayer-basic').find('select option').eq( jQuery(el).index() ).prop('selected', true);

		// Show / hide image upload field
		if(option.val() == 'img') {
			jQuery(el).closest('.ls-sublayer-basic').find('.ls-image-uploader').show();
			jQuery(el).closest('.ls-sublayer-basic').find('.ls-html-code').hide();
		} else {
			jQuery(el).closest('.ls-sublayer-basic').find('.ls-image-uploader').hide();
			jQuery(el).closest('.ls-sublayer-basic').find('.ls-html-code').show();
		}
	},

	didSelectMediaType : function(el) {
		LayerSlider.selectMediaType(el);
		LayerSlider.generatePreview( jQuery(el).closest('.ls-layer-box').index() );
	},

	setCallbackBoxesWidth : function() {

		jQuery('.ls-callback-box').width( (jQuery('.wrap').width() - 26) / 3);
	},

	toggleTransitions : function(el) {

		if(jQuery(el).is(':checked')) {

			// Hide slide rows
			jQuery(el).closest('tbody').find('.ls-old-transitions').addClass('ls-hidden');

			// Hide old transition items
			jQuery(el).closest('tr').find('.old').addClass('ls-hidden');

			// Show new transition items
			jQuery(el).closest('tr').find('.new').removeClass('ls-hidden');

		} else {


			// Show slide rows
			jQuery(el).closest('tbody').find('.ls-old-transitions').removeClass('ls-hidden');

			// Hide old transition items
			jQuery(el).closest('tr').find('.old').removeClass('ls-hidden');

			// Show new transition items
			jQuery(el).closest('tr').find('.new').addClass('ls-hidden');
		}
	},

	willGeneratePreview : function(index) {
		clearTimeout(LayerSlider.timeout);
		LayerSlider.timeout = setTimeout(function() {

			if(index == -1) {
				jQuery('#ls-layers .ls-layer-box').each(function( index ) {
					LayerSlider.generatePreview(index);
				});
			} else {
				LayerSlider.generatePreview(index);
			}
		}, 1000);
	},

	generatePreview : function(index) {

		// Get preview element
		var preview = jQuery('.ls-preview').eq( index + 1 );

		// Get the draggable element
		var draggable = preview.find('.draggable');

		// Get sizes
		var width = jQuery('.ls-settings input[name="width"]').val();
		var height = jQuery('.ls-settings input[name="height"]').val();
		var sub_container = jQuery('.ls-settings input[name="sublayercontainer"]').val();

		// Which width?
		if(sub_container != '' && sub_container != 0) {
			width = sub_container;
		}

		// Set sizes
		preview.add(draggable).css({ width : width, height : height });
		preview.parent().css({ width : width });

		// Get backgrounds
		var bgColor = jQuery('.ls-settings input[name="backgroundcolor"]').val();
		var bgImage = jQuery('.ls-settings input[name="backgroundimage"]').val();

		// Set backgrounds
		if(bgColor != '') {
			preview.css({ backgroundColor : bgColor });
		} else {
			preview.css({ backgroundColor : 'transparent' });
		}

		if(bgImage != '') {
			preview.css({ backgroundImage : 'url('+bgImage+')' });
		} else {
			preview.css({ backgroundImage : 'none' });
		}

		// Get yourLogo
		var yourLogo = jQuery('.ls-settings input[name="yourlogo"]').val();
		var yourLogoStyle = jQuery('.ls-settings input[name="yourlogostyle"]').val();

		// Remove previous yourLogo
		preview.parent().find('.yourlogo').remove();

		// Set yourLogo
		if(yourLogo && yourLogo != '') {
			var logo = jQuery('<img src="'+yourLogo+'" class="yourlogo">').prependTo( jQuery(preview).parent() );
			logo.attr('style', yourLogoStyle);

			var oL = oR = oT = oB = 'auto';

			if( logo.css('left') != 'auto' ){
				var logoLeft = logo[0].style.left;
			}
			if( logo.css('right') != 'auto' ){
				var logoRight = logo[0].style.right;
			}
			if( logo.css('top') != 'auto' ){
				var logoTop = logo[0].style.top;
			}
			if( logo.css('bottom') != 'auto' ){
				var logoBottom = logo[0].style.bottom;
			}

			if( logoLeft && logoLeft.indexOf('%') != -1 ){
				oL = width / 100 * parseInt( logoLeft ) - logo.width() / 2;
			}else{
				oL = parseInt( logoLeft );
			}

			if( logoRight && logoRight.indexOf('%') != -1 ){
				oR = width / 100 * parseInt( logoRight ) - logo.width() / 2;
			}else{
				oR = parseInt( logoRight );
			}

			if( logoTop && logoTop.indexOf('%') != -1 ){
				oT = height / 100 * parseInt( logoTop ) - logo.height() / 2;
			}else{
				oT = parseInt( logoTop );
			}

			if( logoBottom && logoBottom.indexOf('%') != -1 ){
				oB = height / 100 * parseInt( logoBottom ) - logo.height() / 2;
			}else{
				oB = parseInt( logoBottom );
			}

			logo.css({
				left : oL,
				right : oR,
				top : oT,
				bottom : oB
			});
		}

		// Get layer background
		var background = jQuery('#ls-layers .ls-layer-box').eq(index).find('input[name="background"]').val();

		// Set layer background
		if(background != '') {
			draggable.css({
				backgroundImage : 'url('+background+')',
				backgroundPosition : 'center center'
			});
		} else {
			draggable.css({
				backgroundImage : 'none'
			});
		}

		// Empty draggable
		draggable.children().remove();

		// Iterate over the sublayers
		jQuery('#ls-layers .ls-layer-box').eq(index).find('.ls-sublayers > tr').each(function() {

			// Get sublayer type
			var type = jQuery(this).find('select[name="type"] option:selected').val()

			// Get image URL
			var url = jQuery(this).find('input[name="image"]').val();

			// Skip?
			var skip = jQuery(this).find('input[name="skip"]').prop('checked');

			// Visibility
			var visibility = jQuery(this).find('.ls-icon-eye').hasClass('active');

			// Lock
			var lock = jQuery(this).find('.ls-icon-lock').hasClass('active');

			// WordWrap
			var wordWrap = jQuery(this).find('input[name="wordwrap"]').prop('checked');

			// Get attribtes
			var id = jQuery(this).find('input[name="id"]').val();
			var classes = jQuery(this).find('input[name="class"]').val();

			// Append element
			if(skip || visibility) {
				jQuery('<div>').appendTo(draggable);
				return true;

			} else if(type == 'img') {
				if(url != '') {
					var item = jQuery('<img src="'+url+'">').appendTo(draggable);
				} else {
					var item =jQuery('<div>').appendTo(draggable);
				}
			} else {

				// Get HTML content
				var html = jQuery(this).find('textarea[name="html"]').val();

				// Append the element
				var item =jQuery('<'+type+'>').appendTo(draggable);

				// Set HTML
				if(html != '') {
					item.html(html);
				}
			}

			if(lock) {
				item.addClass('disabled');
			}

			// Abs pos
			item.css('position', 'absolute');

			// Get style settings
			var top = jQuery(this).find('input[name="top"]').val();
			var left = jQuery(this).find('input[name="left"]').val();
			var custom = jQuery(this).find('textarea[name="style"]').val();

			// Styles
			var styles = {};
			jQuery(this).find('.ls-sublayer-style input.auto').each(function() {
				if(jQuery(this).val() != '') {
					if(isNumber(jQuery(this).val())) {
						styles[jQuery(this).attr('name')] = ''+jQuery(this).val()+'px';
					} else {
						styles[jQuery(this).attr('name')] = jQuery(this).val();
					}
				}
			});

			item.attr('style', custom);

			// Set predefined styles
			item.css(styles);

			// Apply attributes
			item.attr('id', id);
			item.addClass(classes);

			// Word wrap
			if(wordWrap == false) {
				item.css('white-space', 'nowrap');
			}

			var pt = isNaN( parseInt( item.css('padding-top') ) ) ? 0 : parseInt( item.css('padding-top') );
			var pl = isNaN( parseInt( item.css('padding-left') ) ) ? 0 : parseInt( item.css('padding-left') );
			var bt = isNaN( parseInt( item.css('border-top-width') ) ) ? 0 : parseInt( item.css('border-top-width') );
			var bl = isNaN( parseInt( item.css('border-left-width') ) ) ? 0 : parseInt( item.css('border-left-width') );

			var setPositions = function(){

				// Position the element
				if(top.indexOf('%') != -1) {
					item.css({ top : draggable.height() / 100 * parseInt( top ) - item.height() / 2 - pt - bt });
				} else {
					item.css({ top : parseInt(top) });
				}

				if(left.indexOf('%') != -1) {
					item.css({ left : draggable.width() / 100 * parseInt( left ) - item.width() / 2 - pl - bl });
				} else {
					item.css({ left : parseInt(left) });
				}
			};

			if( item.is('img') ){

				item.load(function(){
					setPositions();
				}).attr('src',item.attr('src') );
			}else{
				setPositions();
			}

			// Z-index
			item.css({ zIndex : 10 + item.index() });
		});


		// Add draggable
		LayerSlider.addDraggable();
	},

	openMediaLibrary : function() {

		// New 3.5 media uploader
		if(newMediaUploader == true) {

			jQuery(document).on('click', '.ls-upload', function() {

				uploadInput = this;

				// Media Library params
				var frame = wp.media({
					title : 'Pick an image to use it in LayerSlider WP',
					multiple : false,
					library : { type : 'image'},
					button : { text : 'Insert' }
				});

				// Runs on select
				frame.on('select',function() {

					// Get the attachment details
					attachment = frame.state().get('selection').first().toJSON();

					// Set image URL
					jQuery(uploadInput).val( attachment['url'] );

					// Generate preview
					jQuery('#ls-layers .ls-layer-box').each(function( index ) {
						LayerSlider.generatePreview(index);
					});

					// Image sublayer
					if(jQuery(uploadInput).is('input[name="image"]')) {
						jQuery(uploadInput).prev().attr('src', attachment['url']);
					}
				});

				// Open ML
				frame.open();
			});

		} else {

			// Bind upload button to show media uploader
			jQuery(document).on('click', '.ls-upload', function() {
				uploadInput = this;
				tb_show('Upload or select a new image to insert into LayerSlider WP', 'media-upload.php?type=image&amp;TB_iframe=true&width=650&height=400');
				return false;
			});
		}
	},

	insertUpload : function() {

		// Bind an event to image url insert
		window.send_to_editor = function(html) {

			// Get the image URL
			var img = jQuery('img',html).attr('src');

			// Set image URL
			jQuery(uploadInput).val( img );

			// Remove thickbox window
			tb_remove();

			// Generate preview
			jQuery('#ls-layers .ls-layer-box').each(function( index ) {
				LayerSlider.generatePreview(index);
			});

			// Image sublayer
			if(jQuery(uploadInput).is('input[name="image"]')) {
				jQuery(uploadInput).prev().attr('src', img);
			}
		};
	},

	massUpload : function() {

		jQuery('#ls-layers').on('click', '.ls-mass-upload', function(e) {

			// Prevent browser default submission
			e.preventDefault();

			// Get input field
			var $this = this;

			// Get layer
			var layer = jQuery(this).closest('.ls-layer-box');
			var sublayer = layer.find('.ls-sublayers > tr:last-child');

			// Media Library params
			var frame = wp.media({
				title : 'Upload and select images to use them as layers for this slide',
				multiple : true,
				library : { type : 'image'},
				button : { text : 'Insert' }
			});

			// Runs on select
			frame.on('select',function() {

				// Get the attachments
				first = frame.state().get('selection').first().toJSON();
				attachments = frame.state().get('selection').toJSON();

				// Get the current sublayer
				jQuery($this).val(first['url']);
				jQuery($this).prev().attr('src', first['url']);

				for(c = 1; c < attachments.length; c++) {

					// Get sublayer count
					var count = layer.find('.ls-sublayers > tr').length + 1;

					// Add new sublayer
					var clone = jQuery('#ls-sample .ls-sublayers > tr').clone();
					clone.appendTo( layer.find('.ls-sublayers') );

					// Set image URL
					clone.find('input[name="image"]').val(attachments[c]['url']);

					// Set image
					clone.find('input[name="image"]').prev().attr('src', attachments[c]['url']);

					// Set sublayer label
					clone.find('input[name="subtitle"]').val('Layer #' + count);

					// Set sublayer number
					clone.find('.ls-sublayer-number').text(count);
				}

				// Generate preview
				jQuery('#ls-layers .ls-layer-box').each(function( index ) {
					LayerSlider.generatePreview(index);
				});

				// Select the last layer
				layer.find('.ls-sublayers > tr:last-child').click();
			});

			// Open ML
			frame.open();
		});
	},

	addSortables : function() {

		// Bind sortable function
        jQuery('.ls-sublayer-sortable').sortable({
        	axis : 'y',

			helper: function(e, tr) {
				var $originals = tr.children();
				var $helper = tr.clone();
				$helper.children().each(function(index) {

					// Set helper cell sizes to match the original sizes
					jQuery(this).width($originals.eq(index).width());
				});
				return $helper;
			},
			sort : function(event, ui){
				LayerSlider.dragContainer = jQuery('.ui-sortable-helper').closest('.ls-layer-box');
			},
			stop : function(event, ui) {
				LayerSlider.generatePreview( LayerSlider.dragContainer.index() );
				LayerSlider.reindexSublayers( LayerSlider.dragContainer );
            },
            containment : 'parent',
			tolerance : 'pointer'
        });
	},

	addLayerSortables : function() {

		// Bind sortable function
		jQuery('#ls-layer-tabs').sortable({
			//axis : 'x',
			start : function() {
				LayerSlider.dragIndex = jQuery('.ui-sortable-placeholder').index() - 1;
			},
			change: function() {
				jQuery('.ui-sortable-helper').addClass('moving');
			},
			stop : function(event, ui) {

				// Get old index
				var oldIndex = LayerSlider.dragIndex;

				// Get new index
				var index = jQuery('.moving').index();

				if( index > -1 ){

					// Rearraange layer pages

					if(index == 0) {
						jQuery('#ls-layers .ls-layer-box').eq(oldIndex).prependTo('#ls-layers');
					}else{
						var layerObj = jQuery('#ls-layers .ls-layer-box').eq(oldIndex);
						jQuery('#ls-layers .ls-layer-box').eq(oldIndex).remove();

						layerObj.insertAfter('#ls-layers .ls-layer-box:eq('+(index-1)+')');
					}
				}

				jQuery('.moving').removeClass('moving');

				// Reindex layers
				LayerSlider.reindexLayers();

				// Sortable
				LayerSlider.addSortables();
            },
            containment : 'parent',
			tolerance : 'pointer',
			items : 'a:not(.unsortable)'
        });
	},

	addDraggable : function() {

		// Add dragable
		jQuery('.draggable').children().draggable({
        	drag : function() {

        		LayerSlider.dragging();
        	},
        	stop : function() {

        		LayerSlider.dragging();
        	}
        });

        jQuery('.draggable .disabled').draggable('disable');
	},

	dragging : function() {

		// Get positions
		var top = parseInt(jQuery('.ui-draggable-dragging').position().top);
		var left = parseInt(jQuery('.ui-draggable-dragging').position().left);

		// Get index
		var wrapper = jQuery('.ui-draggable-dragging').closest('.ls-layer-box');
		var index = jQuery('.ui-draggable-dragging').index();

		// Set positions
		wrapper.find('input[name="top"]').eq(index).val(top + 'px');
		wrapper.find('input[name="left"]').eq(index).val(left + 'px');
	},

	selectDragElement : function(el) {

		jQuery(el).closest('.ls-layer-box').find('.ls-sublayers > tr').eq( jQuery(el).index() ).click();
		jQuery(el).closest('.ls-layer-box').find('.ls-sublayers > tr').eq( jQuery(el).index() ).find('.ls-sublayer-nav a:eq(1)').click();
	},

	reindexSublayers : function(el) {

		jQuery(el).find('.ls-sublayers > tr').each(function(index) {

			// Reindex sublayer number
			jQuery(this).find('.ls-sublayer-number').html( index + 1 );

			// Reindex sublayer title if it is untoched
			if(
				jQuery(this).find('.ls-sublayer-title').val().indexOf('Sublayer') != -1 &&
				jQuery(this).find('.ls-sublayer-title').val().indexOf('Layer') != -1 &&
				jQuery(this).find('.ls-sublayer-title').val().indexOf('copy') == -1
			) {
				jQuery(this).find('.ls-sublayer-title').val('Layer #' + (index + 1) );
			}
		});
	},

	reindexLayers : function() {
		jQuery('#ls-layer-tabs a:not(.unsortable)').each(function(index) {
			jQuery(this).html('Slide #' + (index + 1) + '<span>x</span>');
		});
	},

	play : function( index ) {

		// Get layerslider contaier
		var layerslider = jQuery('#ls-layers .ls-layer-box').eq(index).find('.ls-real-time-preview');

		// Stop
		if(layerslider.children().length > 0) {
			jQuery('#ls-layers .ls-layer-box').eq(index).find('.ls-preview').show();
			layerslider.find('.ls-container').layerSlider('stop');
			layerslider.html('').hide();
			jQuery('#ls-layers .ls-layer-box').eq(index).find('.ls-preview-button').html('Enter Preview').removeClass('playing');
			return;
		}

		// Show the LayerSlider
		layerslider.show();
		layerslider = jQuery('<div class="layerslider">').appendTo(layerslider);

		// Hide the preview
		jQuery('#ls-layers .ls-layer-box').eq(index).find('.ls-preview').hide();

		// Change button status
		jQuery('#ls-layers .ls-layer-box').eq(index).find('.ls-preview-button').html('Exit Preview').addClass('playing');

		// Get global settings
		var width = jQuery('.ls-settings input[name="width"]').val();
		var height = jQuery('.ls-settings input[name="height"]').val();
		var backgroundColor = jQuery('.ls-settings input[name="backgroundcolor"]').val();
		var backgroundImage = jQuery('.ls-settings input[name="backgroundimage"]').val();

		// Apply global settings
		layerslider.css({ width: width, height : height });


		if(backgroundColor != '') {
			layerslider.css({ backgroundColor : backgroundColor });
		}

		if(backgroundImage != '') {
			 layerslider.css({ backgroundImage : 'url('+backgroundImage+')' });
		}

		// Iterate over the layers
		jQuery('#ls-layers .ls-layer-box').each(function() {

			// Gather layer data
			var background = jQuery(this).find('input[name="background"]').val();

			// Layer properties
				var layerprops = '';
				jQuery(this).find('.layerprop').each(function() {
					layerprops += ''+jQuery(this).attr('name')+':'+jQuery(this).val()+';';
				});

			// Build the layer
			var layer = jQuery('<div class="ls-layer">').appendTo(layerslider);
				layer.attr('rel', layerprops);

			// Background
			if(background != '') {
				jQuery('<img src="'+background+'" class="ls-bg">').appendTo(layer);
			}

			// New transitions
			if(jQuery(this).find('input[name="new_transitions"]').prop('checked')) {

				// Get selected transitions
				var tr2d = jQuery(this).find('input[name="2d_transitions"]').val();
				var tr3d = jQuery(this).find('input[name="3d_transitions"]').val();
				var tr2dcustom = jQuery(this).find('input[name="custom_2d_transitions"]').val();
				var tr3dcustom = jQuery(this).find('input[name="custom_3d_transitions"]').val();

                if( tr2d == '' &&tr3d == '' && tr2dcustom == '' && tr3dcustom == '' ) {
                    layer.attr('rel', layer.attr('rel') + ' transition2d: all; ');
                   	layer.attr('rel', layer.attr('rel') + ' transition3d: all; ');
                } else {

                    if(tr2d != '') layer.attr('rel', layer.attr('rel') + ' transition2d: '+tr2d+'; ');
                    if(tr3d != '') layer.attr('rel', layer.attr('rel') + ' transition3d: '+tr3d+'; ');
                    if(tr2dcustom != '') layer.attr('rel', layer.attr('rel') + ' customtransition2d: '+tr2dcustom+'; ');
                    if(tr3dcustom != '') layer.attr('rel', layer.attr('rel') + ' customtransition3d: '+tr3dcustom+'; ');
                }
			}


			// Iterate over the sublayers
			jQuery(this).find('.ls-sublayers > tr').each(function(index) {

				// Gather sublayer data
				var type = jQuery(this).find('select[name="type"] option:selected').val();

				var image = jQuery(this).find('input[name="image"]').val();
				var html = jQuery(this).find('textarea[name="html"]').val();
				var style = jQuery(this).find('textarea[name="style"]').val();
				var top = jQuery(this).find('input[name="top"]').val();
				var left = jQuery(this).find('input[name="left"]').val();
				var level = jQuery(this).find('input[name="level"]').val();
				var skip = jQuery(this).find('input[name="skip"]').prop('checked');
				var url = jQuery(this).find('input[name="url"]').val();
				var id = jQuery(this).find('input[name="id"]').val();
				var classes = jQuery(this).find('input[name="class"]').val();

				// Skip sublayer?
				if(skip) {
					return true;
				}

				// Sublayer properties
				var sublayerprops = '';
				jQuery(this).find('.sublayerprop').each(function() {
					if(jQuery(this).val() != 'auto') {
						sublayerprops += ''+jQuery(this).attr('name')+':'+jQuery(this).val()+';';
					}
				});

				var wordWrap = jQuery(this).find('input[name="wordwrap"]').prop('checked');

				// Styles
				var styles = {};
				jQuery(this).find('.ls-sublayer-style input.auto').each(function() {
					if(jQuery(this).val() != '') {
						if(isNumber(jQuery(this).val())) {
							styles[jQuery(this).attr('name')] = ''+jQuery(this).val()+'px';
						} else {
							styles[jQuery(this).attr('name')] = jQuery(this).val();
						}
					}
				});


				// Build the sublayer
				if(type == 'img') {
					if(image != '') {
						var sublayer = jQuery('<img src="'+image+'">').appendTo(layer).addClass('ls-s'+level);
					} else {
						return true;
					}
				} else {
					var sublayer = jQuery('<'+type+'>').appendTo(layer).html(html).addClass('ls-s'+level);
				}

				sublayer.attr('style', style);

				// Apply attributes
				sublayer.attr('id', id);
				sublayer.addClass(classes);


				// Apply styles
				sublayer.css(styles);


				// WordWrap
				if(wordWrap == false) {
					sublayer.css('white-space', 'nowrap');
				}

				// Position the element
				if(top.indexOf('%') != -1) {
					sublayer.css({ top : top, marginTop : - sublayer.height() / 2 - parseInt( sublayer.css('padding-top') ) - parseInt( sublayer.css('border-top-width') ) });
				} else {
					sublayer.css({ top : parseInt(top) });
				}

				if(left.indexOf('%') != -1) {
					sublayer.css({ left : left, marginLeft : - sublayer.width() / 2 - parseInt( sublayer.css('padding-left') ) - parseInt( sublayer.css('border-left-width') ) });
				} else {
					sublayer.css({ left : parseInt(left) });
				}

				if(url != '' && url.match(/^\#[0-9]/)) {
					sublayer.addClass('ls-linkto-' + url.substr(1));
				}

				sublayer.attr('rel', sublayerprops);
			});
		});

		// LayerSlider init params
		var skinPath = pluginPath + 'skins/';

		// Init layerslider
		jQuery(layerslider).layerSlider({
			width : width,
			height : height,
			skin : 'preview',
			skinsPath : skinPath,
			animateFirstLayer : true,
			firstLayer : (index + 1),
			autoStart : true,
			pauseOnHover : false,
			autoPlayVideos : false
		});

	},


	stop : function() {

		// Get layerslider contaier
		var layersliders = jQuery('#ls-layers .ls-layer-box .ls-real-time-preview');

		// Stop the preview if any
		if(layersliders.children().length > 0) {

			// Show the editor
			jQuery('#ls-layers .ls-layer-box .ls-preview').show();

			// Stop LayerSlider
			layersliders.find('.ls-container').layerSlider('stop');

			// Empty and hide the Preview
			layersliders.html('').hide();

			// Rewrote the Preview button text
			jQuery('#ls-layers .ls-layer-box .ls-preview-button').text('Enter Preview').removeClass('playing');
		}
	},

	openTransitionGallery : function() {

		// Create window
		jQuery('body').prepend( jQuery('<div>', { 'id' : 'ls-transition-window', 'class' : 'ls-box' })
			.append( jQuery('<h1>', { 'class' : 'header', 'text' : 'Select LayerSlider transitions' })
				.append( jQuery('<a>', { 'text' : 'x' }))
			)
			.append( jQuery('<div>')
				.append( jQuery('<table>'))
			)
		);

		// Create overlay
		jQuery('body').prepend( jQuery('<div>', { 'id' : 'ls-transition-overlay'}));


		// Add custom checkboxes
		jQuery('#ls-transition-window :checkbox').customCheckbox();

		// Append transitions
		LayerSlider.appendTransition('Built-in 2D transitions', '2d_transitions', layerSliderTransitions['t2d']);
		LayerSlider.appendTransition('Built-in 3D transitions', '3d_transitions', layerSliderTransitions['t3d']);

		if(typeof layerSliderCustomTransitions != "undefined") {

			// Custom 3D transitions
			if(layerSliderCustomTransitions['t3d'].length) {
				LayerSlider.appendTransition('Custom 3D transitions', 'custom_3d_transitions', layerSliderCustomTransitions['t3d']);
			}

			// Custom 2D transitions
			if(layerSliderCustomTransitions['t2d'].length) {
				LayerSlider.appendTransition('Custom 2D transitions', 'custom_2d_transitions', layerSliderCustomTransitions['t2d']);
			}
		}

		// Add custom checkboxes
		jQuery('#ls-transition-window :checkbox').customCheckbox();
	},

	closeTransitionGallery : function() {

		jQuery('#ls-transition-overlay, #ls-transition-window').remove();
	},

	appendTransition : function(title, tbodyclass, transitions) {

		// Append new tbody
		var tbody = jQuery('<tbody>', { 'class' : tbodyclass }).appendTo('#ls-transition-window table');

		// Append section header
		tbody.append( jQuery('<tr>')
			.append( jQuery('<th>', { 'colspan' : 2 })
				.append( jQuery('<span>', { 'text' : title }))
				.append( jQuery('<input>', { 'type' : 'checkbox' }))
				.append( jQuery('<span>', { 'class' : 'all', 'text' : 'Select all' }))
			)
		);

		// Get checked transitions
		var checked = jQuery('#ls-layers .ls-layer-box.active').find('input[name="'+tbodyclass+'"]').val();
			checked = (checked != '') ? checked.split(',') : [];

		// Check checkbox if all is selected
		if(checked == 'all') {
			tbody.find('.ls-checkbox').removeClass('off').addClass('on');
			tbody.find(':checkbox').prop('checked', true);
		}

		for(c = 0; c < transitions.length; c+=2) {

			// Append new table row
			var tr = jQuery('<tr>').appendTo(tbody).append('<td>').append('<td>');

			// Append transition col 1
			tr.children().eq(0).append( jQuery('<a>', { 'href' : '#', 'html' : ''+(c+1)+'. '+transitions[c]['name']+'', 'rel' : 'tr'+(c+1) } ) )
			if(typeof transitions[c]['premium'] && transitions[c]['premium'] == true) {
				tr.children().eq(0).append( jQuery('<span>', { 'class' : 'ls-icon-star' }));
			}

			// Append transition col 2
			if(transitions.length > (c+1)) {
				tr.children().eq(1).append( jQuery('<a>', { 'href' : '#', 'html' : ''+(c+2)+'. '+transitions[(c+1)]['name']+'', 'rel' : 'tr'+(c+2) } ) );
				if(typeof transitions[(c+1)]['premium'] && transitions[(c+1)]['premium'] == true) {
					tr.children().eq(1).append( jQuery('<span>', { 'class' : 'ls-icon-star' }));
				}
			}

			// Check transitions
			if(checked.indexOf(''+(c+1)+'') != -1 || checked == 'all') tr.children().eq(0).addClass('added');
			if((checked.indexOf(''+(c+2)+'') != -1 || checked == 'all') && transitions.length > (c+1)) tr.children().eq(1).addClass('added');
		}
	},

	selectAllTransition : function(index, check) {

		// Get checkbox
		var checkbox = jQuery('#ls-transition-window tbody').eq(index).find(':checkbox');

		// Get category
		var cat = jQuery('#ls-transition-window tbody').eq(index).attr('class');

		if(checkbox.is(':checked') || (typeof check != undefined && check == true) ) {

			// Check every transition
			jQuery('#ls-transition-window tbody').eq(index).find('td a').each(function() {
				jQuery(this).parent().addClass('added');
			});

			// Check the checkbox
			jQuery('#ls-transition-window tbody').eq(index).find('.ls-checkbox').removeClass('off').addClass('on');
			jQuery('#ls-transition-window tbody').eq(index).find(':checkbox').prop('checked', true);

			// Set the hidden input
			jQuery('#ls-layers .ls-layer-box.active').find('input[name="'+cat+'"]').val('all');

		} else {

			// Check every transition
			jQuery('#ls-transition-window tbody').eq(index).find('td').removeClass('added');

			// Set the hidden input
			jQuery('#ls-layers .ls-layer-box.active').find('input[name="'+cat+'"]').val('');
		}
	},

	toggleTransition : function(el) {

		// Toggle addded class
		if(jQuery(el).parent().hasClass('added')) {
			jQuery(el).parent().removeClass('added');

		} else {
			jQuery(el).parent().addClass('added');
		}

		// Get transitions
		var trs = jQuery(el).closest('tbody').find('td');

		// All selected
		if(trs.filter('.added').length == trs.find('a').length) {

			LayerSlider.selectAllTransition( jQuery(el).closest('tbody').index(), true );
			return;

		// Uncheck sleect al.
		} else {

			// Check the checkbox
			jQuery(el).closest('tbody').find('.ls-checkbox').addClass('off').removeClass('on');
			jQuery(el).closest('tbody').find(':checkbox').prop('checked', false);
		}

		// Get category
		var cat = jQuery(el).closest('tbody').attr('class');

		// Array to hold the checked elements
		var checked = [];

		// Get checked elements
		trs.filter('.added').find('a').each(function() {
			checked.push( jQuery(this).attr('rel').substr(2) );
		});

		// Set hidden input
		jQuery('#ls-layers .ls-layer-box.active').find('input[name="'+cat+'"]').val( checked.join(',') );
	},

	showTransition : function(el) {

		// Get transition index
		var index = jQuery(el).attr('rel').substr(2)-1;

		// Create popup
		jQuery('body').prepend( jQuery('<div>', { 'class' : 'ls-popup' })
			.append( jQuery('<div>', { 'class' : 'inner ls-transition-preview' }))
		);

		// Get popup
		var popup = jQuery('.ls-popup');

		// Get viewport dimensions
		var v_w = jQuery(window).width();

		// Get element dimensions
		var e_w = jQuery(el).width();

		// Get element position
		var e_l = jQuery(el).offset().left;
		var e_t = jQuery(el).offset().top;

		// Get toolip dimensions
		var t_w = popup.outerWidth();
		var t_h = popup.outerHeight();

		// Position tooltip
		popup.css({ top : e_t - t_h - 60, left : e_l - (t_w - e_w) / 2  });

		// Fix top
		if(popup.offset().top < 20) {
			popup.css('top', e_t + 75);
		}

		// Fix left
		if(popup.offset().left < 20) {
			popup.css('left', 20);
		}

		// Get transition class
		var trclass = jQuery(el).closest('tbody').attr('class');

		// Built-in 3D
		if(trclass == '3d_transitions') {
			var trtype = '3d';
			var trObj = layerSliderTransitions['t'+trtype+''][index];

		// Built-in 2D
		} else if(trclass == '2d_transitions') {
			var trtype = '2d';
			var trObj = layerSliderTransitions['t'+trtype+''][index];

		// Custom 3D
		} else if(trclass == 'custom_3d_transitions') {
			var trtype = '3d';
			var trObj = layerSliderCustomTransitions['t'+trtype+''][index];

		// Custom 3D
		} else if(trclass == 'custom_2d_transitions') {
			var trtype = '2d';
			var trObj = layerSliderCustomTransitions['t'+trtype+''][index];
		}

		// Init transition
		popup.find('.inner').transitionGallery({
			type : trtype,
			transition : trObj,
			delay : 1500,
			path : lsTrImgPath
		});
	},

	hideTransition : function(el) {

		// Stop transition
		jQuery('.ls-popup').find('.inner').transitionGallery('destroy');

		// Remove transition
		jQuery('.ls-popup').remove();
	},

	save : function(el) {

		// Temporary disable submit button
		jQuery('.ls-publish button').text('Saving ...').addClass('saving').attr('disabled', true);
		jQuery('.ls-saving-warning').text('Please do not navigate away from this page while LayerSlider WP saving your layers!');

		// Iterate over the settings
		jQuery('.ls-settings input:not(.nochange), .ls-settings select').each(function() {

			// Save original name attr to element's data
			jQuery(this).data('name', jQuery(this).attr('name') );

			// Rewrite the name attr
			jQuery(this).attr('name', 'layerslider-slides[properties]['+jQuery(this).attr('name')+']');
		});

		// Iterate over the layers
		jQuery('#ls-layers .ls-layer-box').each(function(layer) {

			// Iterate over layer settings
			jQuery(this).find('.ls-slide-options input, .ls-slide-options select').each(function() {

				// Save original name attr to element's data
				jQuery(this).data('name', jQuery(this).attr('name') );

				// Rewrite the name attr
				jQuery(this).attr('name', 'layerslider-slides[layers]['+layer+'][properties]['+jQuery(this).attr('name')+']');

			});

			// Iterate over the sublayers
			jQuery(this).find('.ls-sublayers > tr').each(function(sublayer) {

				// JSON object for styles
				var styles = {};

				// Iterate over the sublayer properties
				jQuery(this).find('input.auto').each(function() {

					if(jQuery(this).val() != '') {
						styles[jQuery(this).attr('name')] = jQuery(this).val();
					}

					// Save original name attr to element's data
					jQuery(this).data('name', jQuery(this).attr('name') );

					// Remove name
					jQuery(this).attr('name', '');
				});

				// Generate styles object
				jQuery(this).find('.ls-sublayer-style input[name="styles"]').val( JSON.stringify(styles) );

				// Iterate over the sublayer properties
				jQuery(this).find('input:not(.auto), select, textarea').each(function() {

					// Save original name attr to element's data
					jQuery(this).data('name', jQuery(this).attr('name') );

					// Rewrite the name attr
					jQuery(this).attr('name', 'layerslider-slides[layers]['+layer+'][sublayers]['+sublayer+']['+jQuery(this).attr('name')+']');
				});
			});
		});

		// Iterate over the callback functions
		jQuery('.ls-callback-page textarea').each(function() {

			// Save original name attr to element's data
			jQuery(this).data('name', jQuery(this).attr('name') );

			// Rewrite the name attr
			jQuery(this).attr('name', 'layerslider-slides[properties]['+jQuery(this).attr('name')+']');
		});

		// Reset layer counter
		LayerSlider.counter = 0;

		setTimeout(function() {

			// Iterate over the layers
			jQuery('#ls-layers .ls-layer-box').each(function(layer) {

				// Reindex layerkey
				jQuery(this).find('input[name="layerkey"]').val(layer);

				// Data to send
				$data = jQuery('#ls-layers .ls-layer-box').eq(layer).find('input, textarea, select');
				$data = $data.add( jQuery('#ls-slider-form > input')  );
				$data = $data.add( jQuery('.ls-settings').find('input, textarea, select') );
				$data = $data.add( jQuery('.ls-callback-page textarea') );

				// Post layer
				jQuery.ajax(jQuery(el).attr('action'), {
					type : 'POST',
					data : $data.serialize(),
					async : false,
					success : function(id) {

						LayerSlider.counter += 1;

						if(jQuery('#ls-layers .ls-layer-box').length == LayerSlider.counter) {

							// Give feedback
							jQuery('.ls-publish button').text('Saved').removeClass('saving').addClass('saved');
							jQuery('.ls-saving-warning').text('');

							// Re-enable the button
							setTimeout(function() {
								jQuery('.ls-publish button').text('Save changes').attr('disabled', false).removeClass('saved');
							}, 2000);

							// Rewrote original name attr

								// Global settings
								jQuery('.ls-settings input, .ls-settings select').each(function() {
									jQuery(this).attr('name', jQuery(this).data('name'));
								});

								// Layers
								jQuery('#ls-layers .ls-layer-box').each(function(layer) {

									// Layer settings
									jQuery(this).find('.ls-slide-options input, .ls-slide-options select').each(function() {
										jQuery(this).attr('name', jQuery(this).data('name'));
									});

									// Sublayers
									jQuery(this).find('.ls-sublayers > tr').each(function(sublayer) {
										jQuery(this).find('input, select, textarea').each(function() {
											jQuery(this).attr('name', jQuery(this).data('name'));
										});
									});
								});

								// Iterate over the callback functions
								jQuery('.ls-callback-page textarea').each(function() {
									jQuery(this).attr('name', jQuery(this).data('name'));
								});

							// Redirect the edit page when adding new slider
							if(document.location.href.indexOf('layerslider_add_new') != -1) {

								// Redirect
								document.location.href = 'admin.php?page=layerslider&action=edit&id='+id+'';
							}
						}
					}
				});
			});
		}, 500);
	},

	cloneFix : function() {

		jQuery('textarea').each(function() {
			jQuery(this).text( jQuery(this).val() );
		});

		// Select clone fix
		jQuery('select').each(function() {

			// Get selected index
			var index = jQuery(this).find('option:selected').index();

			// Deselect old options
			jQuery(this).find('option').attr('selected', false);

			// Select the new one
			jQuery(this).find('option').eq( index ).attr('selected', true);
		});
	}
};

jQuery(document).ready(function() {

	// List view
	if(
		document.location.href.indexOf('page=layerslider') != -1 &&
		document.location.href.indexOf('layerslider_add_new') == -1 &&
		document.location.href.indexOf('action=edit') == -1 &&
		document.location.href.indexOf('layerslider_skin_editor') == -1 &&
		document.location.href.indexOf('layerslider_style_editor') == -1 &&
		document.location.href.indexOf('layerslider_transition_builder') == -1
	) {

		// Slider remove
		jQuery('.ls-slider-list a.remove').click(function(e) {
			e.preventDefault();
			if(confirm('Are you sure you want to remove this slider?')){
				document.location.href = jQuery(this).attr('href');
			}
		});

		// Auto-update
		jQuery('.ls-auto-update').submit(function(e) {

			// Prevent browser default submission
			e.preventDefault();

			// Set progress text
			jQuery('.ls-auto-update tfoot span').text('Validating ...').css('color', '#333');

			// Post it
			jQuery.post( ajaxurl, jQuery(this).serialize(), function(data) {

				// Parse data
				data = jQuery.parseJSON(data);

				// Check success
				jQuery('.ls-auto-update tfoot span').text(data['message']);

				// Check success
				if(data['success'] == true) {
					jQuery('.ls-auto-update tfoot span').css('color', '#4b982f');
				} else {
					jQuery('.ls-auto-update tfoot span').css('color', '#c33219');
				}
			});
		});

	// Skin editor
	} else if(
		document.location.href.indexOf('layerslider_skin_editor') != -1 ||
		document.location.href.indexOf('layerslider_style_editor') != -1
	) {

		// Select
		jQuery('select[name="skin"]').change(function() {
			document.location.href = 'admin.php?page=layerslider_skin_editor&skin=' + jQuery(this).children(':selected').val();
		});

		// Editor tab
		jQuery('#editor').keydown(function(e) {

			// Get button keycode
			var keyCode = e.keyCode || e.which;

			// Tab only
			if (keyCode == 9) {

				e.preventDefault();
				var start = jQuery(this).get(0).selectionStart;
				var end = jQuery(this).get(0).selectionEnd;

				// set textarea value to: text before caret + tab + text after caret
				jQuery(this).val(jQuery(this).val().substring(0, start)
				+ "\t"
				+ jQuery(this).val().substring(end));

				// put caret at right position again
				jQuery(this).get(0).selectionStart =
				jQuery(this).get(0).selectionEnd = start + 1;
			}
		});

	// Skin editor
	} else if(document.location.href.indexOf('layerslider_transition_builder') != -1) {

		// Tooltips
		lsTooltip.init();

	// Editor view
	} else {

		// Main tab bar page select
		jQuery('#ls-main-nav-bar a:not(.unselectable)').click(function(e) {
			e.preventDefault();
			LayerSlider.selectMainTab( this );
		});

		// Generate preview if user resizes the browser
		jQuery(window).resize(function(){
			LayerSlider.willGeneratePreview( jQuery('.ls-box.active').index() );
		});

		// Support menu
		jQuery('#ls-main-nav-bar a.support').click(function(e) {
			e.preventDefault();
			jQuery('#contextual-help-link').click();
		});

		// Settings: checkboxes
		jQuery('.ls-settings :checkbox, .ls-layer-box :checkbox:not(.noreplace)').customCheckbox();

		// Checkbox event
		jQuery(document).on('click', '.ls-checkbox', function(e){

			// Prevent browers default submission
			e.preventDefault();

			// Get checkbox
			var el = jQuery(this).prev()[0];

			if( jQuery(el).is(':checked') ) {
				jQuery(el).prop('checked', false);
				jQuery(this).removeClass('on').addClass('off');
			} else {
				jQuery(el).prop('checked', true);
				jQuery(this).removeClass('off').addClass('on');
			}

			// Trigger events
			jQuery('#ls-layers').trigger( jQuery.Event('click', { target : el } ) );
			jQuery(document).trigger( jQuery.Event('click', { target : el } ) );

		});

		lsTooltip.init();

		// Generate preview
		jQuery(window).load(function() {
			LayerSlider.generatePreview( jQuery('.ls-box.active').index() );
		});

		// Uploads
		LayerSlider.openMediaLibrary();
		LayerSlider.massUpload();
		LayerSlider.insertUpload();

		// Settings: width, height
		jQuery('.ls-settings').find('input[name="width"], input[name="height"], input[name="sublayercontainer"]').keyup(function() {
			LayerSlider.willGeneratePreview( jQuery('.ls-box.active').index() );
		});

		// Settings: backgroundColor
		jQuery('.ls-settings input[name="backgroundcolor"]').keyup(function() {
			LayerSlider.willGeneratePreview( jQuery('.ls-box.active').index() );
		});

		// Settings: reset button
		jQuery(document).on('click', '.ls-reset', function() {

			// Empty field
			jQuery(this).prev().val('');

			// Generate preview
			LayerSlider.generatePreview( jQuery('.ls-box.active').index() );
		});

		// Settings: yourLogoStyle
		jQuery('.ls-settings input[name="yourlogostyle"]').keyup(function() {
			LayerSlider.willGeneratePreview( jQuery('.ls-box.active').index() );
		});

		// Add layer
		jQuery('#ls-add-layer').click(function(e) {
			e.preventDefault();
			LayerSlider.addLayer();
		});

		// Select layer
		jQuery('#ls-layer-tabs').on('click', 'a:not(.unsortable)', function(e) {
			e.preventDefault();
			LayerSlider.selectLayer(this);
		});

		// Duplicate layer
		jQuery('#ls-layers').on('click', '.ls-layer-options-thead a.duplicate', function(e){
			e.preventDefault();
			LayerSlider.duplicateLayer(this);
		});

		// Toggle transitions
		jQuery('#ls-layers').on('click', 'input[name="new_transitions"]', function() {
			LayerSlider.toggleTransitions(this);
		});

		// Open Transition gallery
		jQuery('#ls-layers').on('click', '.ls-select-transitions', function(e) {
			e.preventDefault();
			LayerSlider.openTransitionGallery();
		});

		// Close transition gallery
		jQuery(document).on('click', '#ls-transition-overlay, #ls-transition-window h1 a', function(e) {
			e.preventDefault();
			LayerSlider.closeTransitionGallery();
		});

		// Add/Remove layer transitions
		jQuery(document).on('click', '#ls-transition-window tbody a:not(.ls-checkbox)', function(e) {
			e.preventDefault();
			LayerSlider.toggleTransition(this);
		});

		// Add/Remove layer transitions
		jQuery(document).on('click', '#ls-transition-window .ls-checkbox', function(e) {
			e.preventDefault();
			LayerSlider.selectAllTransition( jQuery(this).closest('tbody').index() );
		});

		// Show transition
		jQuery(document).on('mouseenter', '#ls-transition-window table a:not(.ls-checkbox)', function() {
			LayerSlider.showTransition(this);
		});

		// Hide transition
		jQuery(document).on('mouseleave', '#ls-transition-window table a:not(.ls-checkbox)', function() {
			LayerSlider.hideTransition(this);
		});

		// Add sublayer
		jQuery('#ls-layers').on('click', '.ls-add-sublayer', function(e) {
			e.preventDefault();
			LayerSlider.addSublayer(this);
		});

		// Remove layer
		jQuery('#ls-layer-tabs').on('click', 'a span', function(e) {
			e.preventDefault();
			e.stopPropagation();
			LayerSlider.removeLayer(this);
		});


		// Select sublayer
		jQuery('#ls-layers').on('click', '.ls-sublayers tr', function() {
			LayerSlider.selectSubLayer(this);
		});


		// Sublayer pages
		jQuery('#ls-layers').on('click', '.ls-sublayer-nav a:not(:last-child)', function(e) {
			e.preventDefault();
			LayerSlider.selectSublayerPage(this);
		});

		// Remove sublayer
		jQuery('#ls-layers').on('click', '.ls-sublayer-nav a:last-child', function(e) {
			e.preventDefault();
			LayerSlider.removeSublayer(this);
		});

		// Duplicate sublayer
		jQuery('#ls-layers').on('click', '.ls-sublayer-options button.duplicate', function(e) {
			e.preventDefault();
			LayerSlider.duplicateSublayer(this);
		});

		// Highlight sublayer
		jQuery('#ls-layers').on('click', '.ls-highlight input', function(e) {
			e.stopPropagation();
			LayerSlider.highlightSublayer(this);
		});

		// Sublayer media type
		jQuery('#ls-layers').on('click', '.ls-sublayer-types > span', function(e) {
			e.preventDefault();
			LayerSlider.didSelectMediaType(this);
		});

		// Restore sublayer media type
		jQuery('.ls-sublayer-basic select').each(function() {

			// Get selected element
			var index = jQuery(this).find('option:selected').index();

			// Restore
			LayerSlider.selectMediaType(jQuery(this).parent().find('.ls-sublayer-types > span').eq(index));
		});

		// Sublayer: Style
		jQuery('#ls-layers').on('keyup', '.ls-sublayer-style input, .ls-sublayer-style select, .ls-sublayer-style textarea', function() {
			LayerSlider.willGeneratePreview( jQuery(this).closest('.ls-layer-box').index() );
		});

		// Sublayer: WordWrap
		jQuery('#ls-layers').on('click', '.ls-sublayers input[name="wordwrap"]', function() {
			LayerSlider.generatePreview( jQuery(this).closest('.ls-layer-box').index() );
		});

		// Sublayer: HTML
		jQuery('#ls-layers').on('keyup', '.ls-sublayers textarea[name="html"]', function() {
			LayerSlider.willGeneratePreview( jQuery(this).closest('.ls-layer-box').index() );
		});

		// Sublayer: sortables, draggable, etc
		LayerSlider.addSortables();
		LayerSlider.addDraggable();
		LayerSlider.addLayerSortables();

		// Sublayer: skip
		jQuery('#ls-layers').on('click', '.ls-sublayer-options input[name="skip"]', function() {
			LayerSlider.skipSublayer(this);
		});

		// Preview
		jQuery('#ls-layers').on('click', '.ls-preview-button', function(e) {
			e.preventDefault();
			LayerSlider.play( jQuery(this).closest('.ls-layer-box').index() );
		});

		// Preview drag element select
		jQuery('#ls-layers').on('click', '.draggable > *', function() {
			LayerSlider.selectDragElement(this);
		});

		// Save changes
		jQuery('#ls-slider-form').submit(function(e) {
			e.preventDefault();
			LayerSlider.save(this);
		});

		// Callback boxes
		LayerSlider.setCallbackBoxesWidth();
		jQuery(window).resize(function() {
			LayerSlider.setCallbackBoxesWidth();
		});

		// Color picker
		if(typeof jQuery.fn.wpColorPicker != "undefined") {
			jQuery('#ls-slider-form .ls-colorpicker').wpColorPicker({
				width : 150,
				change : function() {
					LayerSlider.willGeneratePreview( jQuery('.ls-box.active').index() );
				},
				clear : function() {
					LayerSlider.willGeneratePreview( jQuery('.ls-box.active').index() );
				}
			});
		}

		// Show color picker on focus
		jQuery('.color').focus(function() {
			jQuery(this).next().slideDown();
		});

		// Show color picker on blur
		jQuery('.color').blur(function() {
			jQuery(this).next().slideUp();
		});

		// Eye icon for layers
		jQuery('#ls-layers').on('click', '.ls-icon-eye', function(e) {
			e.stopPropagation();
			LayerSlider.eyeSublayer(this);
		});

		// Lock icon for layers
		jQuery('#ls-layers').on('click', '.ls-icon-lock', function(e) {
			e.stopPropagation();
			LayerSlider.lockSublayer(this);
		});
	}

});