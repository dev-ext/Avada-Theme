(function($) {
	$.fn.equalHeights = function(minHeight, maxHeight) {
		tallest = (minHeight) ? minHeight : 0;
		this.each(function() {
			if($(this).height() > tallest) {
				tallest = $(this).height();
			}
		});
		if((maxHeight) && tallest > maxHeight) tallest = maxHeight;
		return this.each(function() {
			$(this).height(tallest).css("overflow","auto");
		});
	}
})(jQuery);

/* jQuery CounTo */

(function(a){a.fn.countTo=function(g){g=g||{};return a(this).each(function(){function e(a){a=b.formatter.call(h,a,b);f.html(a)}var b=a.extend({},a.fn.countTo.defaults,{from:a(this).data("from"),to:a(this).data("to"),speed:a(this).data("speed"),refreshInterval:a(this).data("refresh-interval"),decimals:a(this).data("decimals")},g),j=Math.ceil(b.speed/b.refreshInterval),l=(b.to-b.from)/j,h=this,f=a(this),k=0,c=b.from,d=f.data("countTo")||{};f.data("countTo",d);d.interval&&clearInterval(d.interval);d.interval=
setInterval(function(){c+=l;k++;e(c);"function"==typeof b.onUpdate&&b.onUpdate.call(h,c);k>=j&&(f.removeData("countTo"),clearInterval(d.interval),c=b.to,"function"==typeof b.onComplete&&b.onComplete.call(h,c))},b.refreshInterval);e(c)})};a.fn.countTo.defaults={from:0,to:0,speed:1E3,refreshInterval:100,decimals:0,formatter:function(a,e){return a.toFixed(e.decimals)},onUpdate:null,onComplete:null}})(jQuery);

jQuery(window).load(function() {
	jQuery('.progress-bar').each(function() {
		var percentage = jQuery(this).find('.progress-bar-content').data('percentage');
		jQuery(this).find('.progress-bar-content').css('width', '0%');
		jQuery(this).find('.progress-bar-content').animate({
			width: percentage+'%'
		}, 'slow');
	});

	if(jQuery().waypoint) {
		jQuery('#progress-bars').waypoint(function() {
			jQuery('.progress-bar').each(function() {
				var percentage = jQuery(this).find('.progress-bar-content').data('percentage');
				jQuery(this).find('.progress-bar-content').css('width', '0%');
				jQuery(this).find('.progress-bar-content').animate({
					width: percentage+'%'
				}, 'slow');
			});
		}, {
			triggerOnce: true,
			offset: '100%'
		});
	}

	jQuery('.display-percentage').each(function() {
		var percentage = jQuery(this).data('percentage');
		jQuery(this).countTo({from: 0, to: percentage, speed: 900});
	});

	if(jQuery().waypoint) {
		jQuery('.counters-box').waypoint(function() {
			jQuery(this).find('.display-percentage').each(function() {
				var percentage = jQuery(this).data('percentage');
				jQuery(this).countTo({from: 0, to: percentage, speed: 900});
			});
		}, {
			triggerOnce: true,
			offset: '100%'
		});
	}
});
jQuery(document).ready(function($) {
	// Tabs
	//When page loads...
	jQuery('.tabs-wrapper').each(function() {
		jQuery(this).find(".tab_content").hide(); //Hide all content
		jQuery(this).find("ul.tabs li:first").addClass("active").show(); //Activate first tab
		jQuery(this).find(".tab_content:first").show(); //Show first tab content
	});
	
	//On Click Event
	jQuery("ul.tabs li").click(function(e) {
		jQuery(this).parents('.tabs-wrapper').find("ul.tabs li").removeClass("active"); //Remove any "active" class
		jQuery(this).addClass("active"); //Add "active" class to selected tab
		jQuery(this).parents('.tabs-wrapper').find(".tab_content").hide(); //Hide all tab content

		var activeTab = jQuery(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		jQuery(this).parents('.tabs-wrapper').find(activeTab).fadeIn(); //Fade in the active ID content
		
		e.preventDefault();
	});
	
	jQuery("ul.tabs li a").click(function(e) {
		e.preventDefault();
	})

	jQuery('.project-content .tabs-horizontal .tabset,.post-content .tabs-horizontal .tabset').each(function() {
		var menuWidth = jQuery(this).width();
	    var menuItems = jQuery(this).find('li').size();
	    var menuItemsExcludingLast = jQuery(this).find('li:not(:last)');
	    var menuItemsExcludingLastSize = jQuery(this).find('li:not(:last)').size();

		if(menuItems%2 == 0) {
			var itemWidth = Math.ceil(menuWidth/menuItems)-2;
		} else {
			var itemWidth = Math.ceil(menuWidth/menuItems)-1;
		}

	    jQuery(this).css({'width': menuWidth +'px'});
	    jQuery(this).find('li').css({'width': itemWidth +'px'});

	    if(jQuery('body').hasClass('dark')) {
	    	var menuItemsExcludingLastWidth = ((menuItemsExcludingLast.width() + 1) * menuItemsExcludingLastSize);
	    } else {
	    	var menuItemsExcludingLastWidth = ((menuItemsExcludingLast.width()) * menuItemsExcludingLastSize);
		}
	    var lastItemSize = menuWidth - menuItemsExcludingLastWidth;

	    jQuery(this).find('li:last').css({'width': lastItemSize +'px'});
	});

	jQuery('#sidebar .tabset').each(function() {
		var menuWidth = jQuery(this).width();
	    var menuItems = jQuery(this).find('li').size();
	    var itemWidth = (menuWidth/menuItems)-1;
	   	var menuItemsExcludingLast = jQuery(this).find('li:not(:last)');
	    var menuItemsExcludingLastSize = jQuery(this).find('li:not(:last)').size();

	    jQuery(this).css({'width': menuWidth +'px'});
	    jQuery(this).find('li').css({'width': itemWidth +'px'});

	    var menuItemsExcludingLastWidth = ((menuItemsExcludingLast.width() + 1) * menuItemsExcludingLastSize);
	    var lastItemSize = menuWidth - menuItemsExcludingLastWidth;

	    //jQuery(this).find('li:last').css({'width': lastItemSize +'px'});
	});
	
	jQuery('.tooltip-shortcode, #footer .social-networks li, .footer-area .social-networks li, #sidebar .social-networks li, .social_links_shortcode li, .share-box li, .social-icon, .social li').mouseenter(function(){
		jQuery(this).find('.popup').fadeIn();
	});

	jQuery('.tooltip-shortcode, #footer .social-networks li, .footer-area .social-networks li, #sidebar .social-networks li, .social_links_shortcode li, .share-box li, .social-icon, .social li').mouseleave(function(){
		jQuery(this).find('.popup').fadeOut();
	});

	if(jQuery().carouFredSel) {
		jQuery('.clients-carousel').each(function() {
			jQuery(this).find('ul').carouFredSel({
				auto: false,
				prev: jQuery(this).find('.es-nav-prev'),
				next: jQuery(this).find('.es-nav-next'),
				width: '100%',
			});
		});

		jQuery('.es-carousel-wrapper').each(function() {
			jQuery(this).find('ul').carouFredSel({
				auto: false,
				prev: jQuery(this).find('.es-nav-prev'),
				next: jQuery(this).find('.es-nav-next'),
				width: '100%',
			});
		});
	}

	jQuery('.portfolio-tabs a').click(function(e){
		e.preventDefault();

		var selector = jQuery(this).attr('data-filter');
		jQuery(this).parents('.portfolio').find('.portfolio-wrapper').isotope({ filter: selector });

		jQuery(this).parents('ul').find('li').removeClass('active');
		jQuery(this).parent().addClass('active');
	});

	jQuery('.faq-tabs a').click(function(e){
		e.preventDefault();

		var selector = jQuery(this).attr('data-filter');

		jQuery('.faqs .portfolio-wrapper .faq-item').fadeOut();
		jQuery('.faqs .portfolio-wrapper .faq-item'+selector).fadeIn();

		jQuery(this).parents('ul').find('li').removeClass('active');
		jQuery(this).parent().addClass('active');
	});

	jQuery('.toggle-content').each(function() {
		if(!jQuery(this).hasClass('default-open')){
			jQuery(this).hide();
		}
	});

	jQuery("h5.toggle").click(function(){
		if(jQuery(this).parents('.accordian').length >=1){
			var accordian = jQuery(this).parents('.accordian');

			if(jQuery(this).hasClass('active')){
				jQuery(accordian).find('h5.toggle').removeClass('active');
				jQuery(accordian).find(".toggle-content").slideUp();
			} else {
				jQuery(accordian).find('h5.toggle').removeClass('active');
				jQuery(accordian).find(".toggle-content").slideUp();

				jQuery(this).addClass('active');
				jQuery(this).next(".toggle-content").slideToggle();
			}
		} else {
			if(jQuery(this).hasClass('active')){
				jQuery(this).removeClass("active");
			}else{
				jQuery(this).addClass("active");
			}
		}

		return false;
	});

	jQuery("h5.toggle").click(function(){
		if(!jQuery(this).parents('.accordian').length >=1){
			jQuery(this).next(".toggle-content").slideToggle();
		}
	});

	function isScrolledIntoView(elem)
	{
	    var docViewTop = jQuery(window).scrollTop();
	    var docViewBottom = docViewTop + jQuery(window).height();

	    var elemTop = jQuery(elem).offset().top;
	    var elemBottom = elemTop + jQuery(elem).height();

	    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
	}

	jQuery('.toggle-alert').click(function(e) {
		e.preventDefault();

		jQuery(this).parent().slideUp();
	});

	// Create the dropdown base
	jQuery('<select />').appendTo('.nav-holder');

	// Create default option 'Go to...'
	jQuery('<option />', {
		'selected': 'selected',
		'value'   : '',
		'text'    : 'Go to...'
	}).appendTo('.nav-holder select');

	// Populate dropdown with menu items
	jQuery('.nav-holder a').each(function() {
		var el = jQuery(this);

		if(jQuery(el).parents('.sub-menu .sub-menu').length >= 1) {
			jQuery('<option />', {
			 'value'   : el.attr('href'),
			 'text'    : '-- ' + el.text()
			}).appendTo('.nav-holder select');
		}
		else if(jQuery(el).parents('.sub-menu').length >= 1) {
			jQuery('<option />', {
			 'value'   : el.attr('href'),
			 'text'    : '- ' + el.text()
			}).appendTo('.nav-holder select');
		}
		else {
			jQuery('<option />', {
			 'value'   : el.attr('href'),
			 'text'    : el.text()
			}).appendTo('.nav-holder select');
		}
	});

	jQuery('.nav-holder select').ddslick({
		width: '100%',
	    onSelected: function(selectedData){
	    	if(selectedData.selectedData.value != '') {
	        	window.location = selectedData.selectedData.value;
	    	}
	    }   
	});

	// Create the dropdown base
	jQuery('<select />').appendTo('.top-menu');

	// Create default option 'Go to...'
	jQuery('<option />', {
		'selected': 'selected',
		'value'   : '',
		'text'    : 'Go to...'
	}).appendTo('.top-menu select');

	// Populate dropdown with menu items
	jQuery('.top-menu a').each(function() {
		var el = jQuery(this);
		
		if(jQuery(el).parents('.sub-menu .sub-menu').length >= 1) {
			jQuery('<option />', {
			 'value'   : el.attr('href'),
			 'text'    : '-- ' + el.text()
			}).appendTo('.top-menu select');
		}
		else if(jQuery(el).parents('.sub-menu').length >= 1) {
			jQuery('<option />', {
			 'value'   : el.attr('href'),
			 'text'    : '- ' + el.text()
			}).appendTo('top-menu select');
		}
		else {
			jQuery('<option />', {
			 'value'   : el.attr('href'),
			 'text'    : el.text()
			}).appendTo('.top-menu select');
		}
	});

	jQuery('.top-menu select').ddslick({
		width: '100%',
	    onSelected: function(selectedData){
	    	if(selectedData.selectedData.value != '') {
	        	window.location = selectedData.selectedData.value;
	    	}
	    }   
	});

	jQuery('.side-nav li').each(function() {
		if(jQuery(this).find('> .children').length >=1) {
			jQuery(this).find('> a').append('<span class="arrow"></span>');
		}
	});

	jQuery('.side-nav .current_page_item').each(function() {
		if(jQuery(this).find('.children').length >= 1){
			jQuery(this).find('.children').show('slow');
		}
	});

	jQuery('.side-nav .current_page_item').each(function() {
		if(jQuery(this).parent().hasClass('side-nav')) {
			jQuery(this).find('ul').show('slow');
		}
		
		if(jQuery(this).parent().hasClass('children')){
			jQuery(this).parents('ul').show('slow');
		}
	});

	jQuery('.content-boxes').each(function() {
		var cols = jQuery(this).find('.col').length;
		jQuery(this).addClass('columns-'+cols);
	});

	jQuery('.columns-3 .col:nth-child(3n), .columns-4 .col:nth-child(4n)').css('margin-right', '0px');

	jQuery('input, textarea').placeholder();

	jQuery('.content-boxes-icon-boxed').each(function() {
		jQuery(this).find('.col').equalHeights();
	});

	function checkForImage(url) {
	    return(url.match(/\.(jpeg|jpg|gif|png)$/) != null);
	}

	if(Modernizr.mq('only screen and (max-width: 479px)')) {
		jQuery('.overlay-full.layout-text-left .slide-excerpt p').each(function () {
			var excerpt = jQuery(this).html();
		    var wordArray = excerpt.split(/[\s\.\?]+/); //Split based on regular expression for spaces
		    var maxWords = 10; //max number of words
		    var total_words = wordArray.length; //current total of words
		    var newString = "";
		    //Roll back the textarea value with the words that it had previously before the maximum was reached
		    if (total_words > maxWords+1) {
		         for (var i = 0; i < maxWords; i++) {
		            newString += wordArray[i] + " ";
		        }
		        jQuery(this).html(newString);
		    }
		});

		jQuery('.portfolio .gallery-icon').each(function () {
			var img = jQuery(this).attr('href');

			if(checkForImage(img) == true) {
				jQuery(this).parents('.image').find('> img').attr('src', img).attr('width', '').attr('height', '');
			}
			jQuery(this).parents('.portfolio-item').css('width', 'auto');
			jQuery(this).parents('.portfolio-item').css('height', 'auto');
			jQuery(this).parents('.portfolio-one:not(.portfolio-one-text)').find('.portfolio-item').css('margin', '0');
		});

		if(jQuery('.portfolio').length >= 1) {
			jQuery('.portfolio-wrapper').isotope('reLayout');
		}

		jQuery('.title').each(function() {
			var title = jQuery(this).find('h1,h2,h3,h4,h5,h6').html();

			if(title && title.length >= 35) {
				jQuery(this).find('h1,h2,h3,h4,h5,h6').css('white-space', 'normal');
				jQuery(this).find('.title-sep-container').css('width', '20%');
			}
		});
	}

	if(Modernizr.mq('only screen and (max-width: 800px)')) {
		jQuery('.tabs-vertical').addClass('tabs-horizontal').removeClass('tabs-vertical');
	}

	jQuery(window).resize(function() {
		if(Modernizr.mq('only screen and (max-width: 800px)')) {
			jQuery('.tabs-vertical').addClass('tabs-original-vertical');
			jQuery('.tabs-vertical').addClass('tabs-horizontal').removeClass('tabs-vertical');
		} else {
			jQuery('.tabs-original-vertical').removeClass('tabs-horizontal').addClass('tabs-vertical');
		}
	});
});