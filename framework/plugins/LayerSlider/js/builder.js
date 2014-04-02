(function( $ ) {
	var radioCheck = /radio|checkbox/i,
		keyBreaker = /[^\[\]]+/g,
		numberMatcher = /^[\-+]?[0-9]*\.?[0-9]+([eE][\-+]?[0-9]+)?$/;

	var isNumber = function( value ) {
		if ( typeof value == 'number' ) {
			return true;
		}

		if ( typeof value != 'string' ) {
			return false;
		}

		return value.match(numberMatcher);
	};

	$.fn.extend({
		/**
		 * @parent dom
		 * @download http://jmvcsite.heroku.com/pluginify?plugins[]=jquery/dom/form_params/form_params.js
		 * @plugin jquery/dom/form_params
		 * @test jquery/dom/form_params/qunit.html
		 * <p>Returns an object of name-value pairs that represents values in a form.
		 * It is able to nest values whose element's name has square brackets. </p>
		 * Example html:
		 * @codestart html
		 * &lt;form>
		 *   &lt;input name="foo[bar]" value='2'/>
		 *   &lt;input name="foo[ced]" value='4'/>
		 * &lt;form/>
		 * @codeend
		 * Example code:
		 * @codestart
		 * $('form').formParams() //-> { foo:{bar:2, ced: 4} }
		 * @codeend
		 *
		 * @demo jquery/dom/form_params/form_params.html
		 *
		 * @param {Boolean} [convert] True if strings that look like numbers and booleans should be converted.  Defaults to true.
		 * @return {Object} An object of name-value pairs.
		 */
		formParams: function( convert ) {
			if ( this[0].nodeName.toLowerCase() == 'form' && this[0].elements ) {

				return jQuery(jQuery.makeArray(this[0].elements)).getParams(convert);
			}
			return jQuery("input[name], textarea[name], select[name]", this[0]).getParams(convert);
		},
		getParams: function( convert ) {
			var data = {},
				current;

			convert = convert === undefined ? true : convert;

			this.each(function() {
				var el = this,
					type = el.type && el.type.toLowerCase();
				//if we are submit, ignore
				if ((type == 'submit') || !el.name ) {
					return;
				}

				var key = el.name,
					value = $.data(el, "value") || $.fn.val.call([el]),
					isRadioCheck = radioCheck.test(el.type),
					parts = key.match(keyBreaker),
					write = !isRadioCheck || !! el.checked,
					//make an array of values
					lastPart;

				if ( convert ) {
					if ( isNumber(value) ) {
						value = parseFloat(value);
					} else if ( value === 'true' || value === 'false' ) {
						value = Boolean(value);
					}

				}

				// go through and create nested objects
				current = data;
				for ( var i = 0; i < parts.length - 1; i++ ) {
					if (!current[parts[i]] ) {
						current[parts[i]] = {};
					}
					current = current[parts[i]];
				}
				lastPart = parts[parts.length - 1];

				//now we are on the last part, set the value
				if ( lastPart in current && type === "checkbox" ) {
					if (!$.isArray(current[lastPart]) ) {
						current[lastPart] = current[lastPart] === undefined ? [] : [current[lastPart]];
					}
					if ( write ) {
						current[lastPart].push(value);
					}
				} else if ( write || !current[lastPart] ) {
					current[lastPart] = write ? value : undefined;
				}

			});
			return data;
		}
	});

})(jQuery)

var lsTrBuilder = {

	selectTransition : function(el) {

		// Get parent
		var parent = jQuery(el).hasClass('3d') ? jQuery('.ls-tr-list-3d') : jQuery('.ls-tr-list-2d');

		// Get index
		var index = jQuery(el).find('option:selected').index();

		// Stop preview
		lsTrBuilder.stopPreview( parent.children('.active') );

		// Hide the previous transition box
		jQuery(parent).children().removeClass('active')

		// Show the new one
		jQuery(parent).children().eq(index).addClass('active')
	},

	addTransition : function(el) {

		// Get select
		var select = jQuery(el).prev();

		// Get parent
		var parent = jQuery(el).hasClass('3d') ? jQuery('.ls-tr-list-3d') : jQuery('.ls-tr-list-2d');


		// Remove notification if any
		if(parent.find('.ls-no-transitions-notification').length) {

			// Remove notification
			parent.children('.ls-no-transitions-notification').remove();

			// Remove option from select
			select.children('.notification').remove();
		}

		// Get clone
		var clone = jQuery(el).hasClass('3d') ? jQuery('#ls-tr-sample-3d') : jQuery('#ls-tr-sample-2d');

		// Append clone
		var tr = clone.children().clone().appendTo(parent);

		// Find tr name
		var name = tr.find('input[name="name"]').val();

		// Stop current preview
		lsTrBuilder.stopPreview(parent);

		// Append tr to list and select it
		select.children().prop('selected', false);
		jQuery( jQuery('<option>', { text : name })).appendTo(select).prop('selected', true);

		// Show new transition
		parent.children().removeClass('active')
		tr.addClass('active')
	},

	removeTransition : function(el) {

		if(!confirm('Are you sure you want to remove this transition?')) {
			return;
		}

		// Get transition
		var tr = jQuery(el).closest('.ls-transition-item');

		// Get index
		var index = tr.index();

		// 3D
		if(jQuery(el).closest('.ls-tr-list-3d').length > 0) {

			// Get select
			var select = jQuery('.ls-tr-builder-tr-select.3d');

			// Select notification
			var selectNotification = 'No 3D transition yet';

			// Text notification
			var textNotification = 'You didn\'t create any 3D transitions yet';

		// 2D
		} else {

			// Get select
			var select = jQuery('.ls-tr-builder-tr-select.2d');

			// Select notification
			var selectNotification = 'No 2D transition yet';

			// Text notification
			var textNotification = 'You didn\'t create any 2D transitions yet';
		}

		// Get parent
		var parent = tr.parent();

		if(select.children().length > 1) {

			// Get next items
			var newIndex = (tr.prev().length > 0) ? (index -1) : (index+1);

			// Select new item in list
			select.children().prop('selected', false);
			select.children().eq(newIndex).prop('selected', true);

			// Show new transition
			tr.parent().children().removeClass('active');
			tr.parent().children().eq(newIndex).addClass('active');

			// Stop preview
			lsTrBuilder.stopPreview( tr );
		}


		// Remove transition
		tr.remove();

		// Remove from select
		select.children().eq(index).remove();

		// Add notification if needed
		if(select.children().length == 0) {

			// Add select placeholder
			select.append( jQuery('<option>', { 'class' : 'notification', 'text' : selectNotification }));

			// Add notification
			parent.append( jQuery('<div>', { 'class' : 'ls-no-transitions-notification' })
				.append( jQuery('<h1>', { 'text' : textNotification }))
				.append( jQuery('<p>', { 'text' : 'To create a new transition, click to the "Add new" button above this text.' }))

			);

		}

	},

	toggleTableGroup : function(el) {

		// Get tbody
		var tbody = jQuery(el).closest('thead').next();

		if(tbody.hasClass('ls-builder-collapsed')) {
			tbody.removeClass('ls-builder-collapsed');
		} else {
			tbody.addClass('ls-builder-collapsed');
		}
	},

	addProperty : function(el) {

		// Get list
		var list = jQuery(el).parent().prev();

		// Get select
		var select = jQuery(el).next();

		// Get title
		var title = select.children(':selected').text();

		// Get name
		var name = select.children(':selected').val().split(',')[0];

		// Get value
		var value = select.children(':selected').val().split(',')[1];


		// Build tag
		list.append( jQuery('<li>')
			.append( jQuery('<p>')
				.append( jQuery('<span>', { 'text' : title })
				.after( jQuery('<input>', { 'value' : value, 'name' : name }))))

			.append( jQuery('<a>', { 'href' : '#', 'text' : 'x' }) )
		);

	},

	removeProperty : function(el) {

		jQuery(el).closest('li').remove();
	},

	storeNameAttrs : function() {

		jQuery('.wrap form').find('input,select').each(function() {
			jQuery(this).data('originalName', jQuery(this).attr('name'));
		});
	},

	restoreNameAttrs : function() {

		jQuery('.wrap form').find('input,select').each(function() {
			jQuery(this).attr('name', jQuery(this).data('originalName'));
		});
	},


	serializeTransitions : function(el) {

		// Iterate over 2D transitions
		jQuery('.ls-tr-list-2d table').each(function(index) {

			// Basic
			jQuery(this).find('tbody.basic input').each(function() {
				jQuery(this).attr('name', 't2d['+index+']['+jQuery(this).attr('name')+']');
			});

			// Iterate over other sections
			jQuery(this).find('tbody:gt(1)').each(function() {

				// Get section name
				if(typeof jQuery(this).attr('class') != "undefined") {

					var section = jQuery(this).attr('class');
					// Iterate over the fields
					jQuery(this).find('input,select').each(function() {
						jQuery(this).attr('name', 't2d['+index+']['+section+']['+jQuery(this).attr('name')+']');
					});
				}
			});
		});

		// Iterate over 3D transitions
		jQuery('.ls-tr-list-3d table').each(function(index) {

			// Basic
			jQuery(this).find('tbody.basic input').each(function() {
				jQuery(this).attr('name', 't3d['+index+']['+jQuery(this).attr('name')+']');
			});

			// Iterate over other sections
			jQuery(this).find('tbody:gt(1)').each(function() {

				if(typeof jQuery(this).attr('class') != "undefined") {

					// Get section name
					var section = jQuery(this).attr('class').split(' ')[0];

					// Iterate over the fields
					jQuery(this).find('input,select').each(function() {

						// Basic field
						if(jQuery(this).closest('tr.transition').length == 0) {
							jQuery(this).attr('name', 't3d['+index+']['+section+']['+jQuery(this).attr('name')+']');

						// Transition field
						} else if(jQuery(this).is('input')) {
							jQuery(this).attr('name', 't3d['+index+']['+section+'][transition]['+jQuery(this).attr('name')+']');
						}
					});

					// Thead options
					jQuery(this).prev().find('input').each(function() {
						jQuery(this).attr('name', 't3d['+index+']['+section+'][enabled]');
					});
				}
			});
		});
	},

	stopPreview : function(el) {

		// Get transition element
		if(jQuery(el).is('button')) {
			el = jQuery(el).closest('.ls-transition-item');
		}

		// Get button
		var button = jQuery(el).find('.ls-builder-preview-button button');

		if(button.hasClass('playing')) {

			// Set stopped class
			button.text('Enter Preview').removeClass('playing');

			// Remove the preview
			jQuery(el).find('.ls-builder-preview').transitionGallery('destroy');

			// Place preview image
			jQuery(el).find('.ls-builder-preview').append( jQuery('<img>', { 'src' : lsTrImgPath + 'sample_slide_1.png' }));
		}
	},

	startPreview : function(el) {

		// Check playing status
		if(jQuery(el).hasClass('playing')) {
			lsTrBuilder.stopPreview(el);
			return;
		}

		// Set playing class
		jQuery(el).text('Exit Preview').addClass('playing');

		// Get transitions item
		var item = jQuery(el).closest('.ls-transition-item');

		// Get tr index
		var index = jQuery(item).index();

		// Get type
		var trtype = jQuery(item).closest('.ls-tr-list-3d').length ? '3d' : '2d';

		// Store name attrs
		lsTrBuilder.storeNameAttrs();

		// Serialize
		lsTrBuilder.serializeTransitions();

		// Get transition
		var trObj = jQuery(item).formParams();
			trObj = trObj['t'+trtype+''][index];

		if(trtype == '3d') {
			if(typeof trObj['before']['enabled'] == "undefined") {
				trObj['before'] = {};
			}

			if(typeof trObj['after']['enabled'] == "undefined") {
				trObj['after'] = {};
			}
		}
		//console.log(JSON.stringify(trObj));
		// Restore name attrs
		lsTrBuilder.restoreNameAttrs();

		// Try preview
		try {
			jQuery(item).find('.ls-builder-preview').empty();
			jQuery(item).find('.ls-builder-preview').transitionGallery({
				type : trtype,
				transition : trObj,
				delay : 1500,
				path : lsTrImgPath
			});
		} catch(err) {

			// Stop preview
			lsTrBuilder.stopPreview(item);

			// Show error message
			alert('Oops, something went wrong, please check your transitions settings and enter valid values. Error: '+err);
		}
	},

	save : function(el) {

		// Store name attrs
		lsTrBuilder.storeNameAttrs();

		// Temporary disable submit button
		jQuery('.ls-publish button').text('Saving ...').addClass('saving').attr('disabled', true);
		jQuery('.ls-saving-warning').text('Please do not navigate away from this page while LayerSlider WP saving your transitions!');

		// Serialize
		lsTrBuilder.serializeTransitions();

		// Post
		jQuery.post( jQuery(el).attr('action'), jQuery(el).serialize(), function() {

			// Give feedback
			jQuery('.ls-publish button').text('Saved').removeClass('saving').addClass('saved');
			jQuery('.ls-saving-warning').text('');

			// Re-enable the button
			setTimeout(function() {
				jQuery('.ls-publish button').text('Save changes').attr('disabled', false).removeClass('saved');
			}, 2000);

			// Restore name attrs
			lsTrBuilder.restoreNameAttrs();
		});
	}
};

jQuery(document).ready(function() {

	// Transition select
	jQuery('.ls-tr-builder-tr-select').change(function() {
		lsTrBuilder.selectTransition(this);
	});

	// Add transition
	jQuery('.ls-tr-builder-add-tr').click(function(e) {
		e.preventDefault();
		lsTrBuilder.addTransition(this);
	});

	// Remove transition
	jQuery('.ls-tr-builder').on('click', '.ls-tr-remove', function(e) {
		e.preventDefault();
		lsTrBuilder.removeTransition(this);
	});

	// Collapsable toggles
	jQuery('.ls-tr-builder').on('click', '.ls-builder-collapse-toggle', function() {
		lsTrBuilder.toggleTableGroup(this);
	});

	// Add property
	jQuery('.ls-tr-builder').on('click', '.ls-tr-add-property a', function(e) {
		e.preventDefault();
		lsTrBuilder.addProperty(this);
	});

	// Remove property
	jQuery('.ls-tr-builder').on('click', '.ls-tr-tags a', function(e) {
		e.preventDefault();
		lsTrBuilder.removeProperty(this);
	});

	// Start preview
	jQuery('.ls-tr-builder').on('click', '.ls-builder-preview-button button', function(e) {
		e.preventDefault();
		lsTrBuilder.startPreview(this);
	});

	// Form submit
	jQuery('#ls-tr-builder-form').submit(function(e) {
		e.preventDefault();
		lsTrBuilder.save(this);
	})
});