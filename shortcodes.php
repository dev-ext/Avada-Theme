<?php
//////////////////////////////////////////////////////////////////
// Remove extra P tags
//////////////////////////////////////////////////////////////////
function avada_shortcodes_formatter($content) {
	$block = join("|",array("youtube", "vimeo", "soundcloud", "button", "dropcap", "highlight", "checklist", "tabs", "tab", "accordian", "toggle", "one_half", "one_third", "one_fourth", "two_third", "three_fourth", "tagline_box", "pricing_table", "pricing_column", "pricing_price", "pricing_row", "pricing_footer", "content_boxes", "content_box", "slider", "slide", "testimonials", "testimonial", "progress", "person", "recent_posts", "recent_works", "alert", "fontawesome", "social_links", "clients", "client", "title", "separator", "tooltip", "fullwidth", "map", "counters_circle", "counter_circle", "counters_box", "counter_box", "flexslider", "blog", "imageframe", "images", "image", "sharing"));

	// opening tag
	$rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]",$content);

	// closing tag
	$rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)/","[/$2]",$rep);

	return $rep;
}

add_filter('the_content', 'avada_shortcodes_formatter');
add_filter('widget_text', 'avada_shortcodes_formatter');

//////////////////////////////////////////////////////////////////
// Youtube shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('youtube', 'shortcode_youtube');
	function shortcode_youtube($atts) {
		$atts = shortcode_atts(
			array(
				'id' => '',
				'width' => 600,
				'height' => 360
			), $atts);

			return '<div style="max-width:'.$atts['width'].'px;max-height:'.$atts['height'].'px;"><div class="video-shortcode"><iframe title="YouTube video player" width="' . $atts['width'] . '" height="' . $atts['height'] . '" src="http://www.youtube.com/embed/' . $atts['id'] . '" frameborder="0" allowfullscreen></iframe></div></div>';
	}
	
//////////////////////////////////////////////////////////////////
// Vimeo shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('vimeo', 'shortcode_vimeo');
	function shortcode_vimeo($atts) {
		$atts = shortcode_atts(
			array(
				'id' => '',
				'width' => 600,
				'height' => 360
			), $atts);
		
			return '<div style="max-width:'.$atts['width'].'px;max-height:'.$atts['height'].'px;"><div class="video-shortcode"><iframe src="http://player.vimeo.com/video/' . $atts['id'] . '" width="' . $atts['width'] . '" height="' . $atts['height'] . '" frameborder="0"></iframe></div></div>';
	}
	
//////////////////////////////////////////////////////////////////
// SoundCloud shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('soundcloud', 'shortcode_soundcloud');
	function shortcode_soundcloud($atts) {
		$atts = shortcode_atts(
			array(
				'url' => '',
				'width' => '100%',
				'height' => 81,
				'comments' => 'true',
				'auto_play' => 'true',
				'color' => 'ff7700',
			), $atts);
			
			return '<iframe width="'.$atts['width'].'" height="'.$atts['height'].'" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=' . urlencode($atts['url']) . '&amp;show_comments=' . $atts['comments'] . '&amp;auto_play=' . $atts['auto_play'] . '&amp;color=' . $atts['color'] . '"></iframe>';
			//return '<object height="' . $atts['height'] . '" width="' . $atts['width'] . '"><param name="movie" value="http://player.soundcloud.com/player.swf?url=' . urlencode($atts['url']) . '&amp;show_comments=' . $atts['comments'] . '&amp;auto_play=' . $atts['auto_play'] . '&amp;color=' . $atts['color'] . '"></param><param name="allowscriptaccess" value="always"></param><embed allowscriptaccess="always" height="' . $atts['height'] . '" src="http://player.soundcloud.com/player.swf?url=' . urlencode($atts['url']) . '&amp;show_comments=' . $atts['comments'] . '&amp;auto_play=' . $atts['auto_play'] . '&amp;color=' . $atts['color'] . '" type="application/x-shockwave-flash" width="' . $atts['width'] . '"></embed></object>';
	}
	
//////////////////////////////////////////////////////////////////
// Button shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('button', 'shortcode_button');
	function shortcode_button($atts, $content = null) {
			if(!$atts['color']) {
				$atts['color'] = 'default';
			}
			return '<a class="button ' . $atts['size'] . ' ' . $atts['color'] . '" href="' . $atts['link'] . '" target="' . $atts['target'] . '">' .do_shortcode($content). '</a>';
	}
	
//////////////////////////////////////////////////////////////////
// Dropcap shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('dropcap', 'shortcode_dropcap');
	function shortcode_dropcap( $atts, $content = null ) {  
		
		return '<span class="dropcap">' .do_shortcode($content). '</span>';  
		
}
	
//////////////////////////////////////////////////////////////////
// Highlight shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('highlight', 'shortcode_highlight');
	function shortcode_highlight($atts, $content = null) {
		$atts = shortcode_atts(
			array(
				'color' => 'yellow',
			), $atts);
			
			if($atts['color'] == 'black') {
				return '<span class="highlight2" style="background-color:'.$atts['color'].';">' .do_shortcode($content). '</span>';
			} else {
				return '<span class="highlight1" style="background-color:'.$atts['color'].';">' .do_shortcode($content). '</span>';
			}

	}
	
//////////////////////////////////////////////////////////////////
// Check list shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('checklist', 'shortcode_checklist');
	function shortcode_checklist( $atts, $content = null ) {
		global $data;

		extract(shortcode_atts(array(
			'icon' => 'arrow',
			'iconcolor' => '',
			'circle' => 'yes'
		), $atts));

		if(!$iconcolor) {
			$iconcolor = strtolower($data['checklist_icons_color']);
		}

	$content = str_replace('<ul>', '<ul class="list-icon circle-'.$circle.' list-icon-color-'.$iconcolor.' list-icon-'.$icon.'">', do_shortcode($content));
	$content = str_replace('<li>', '<li>', do_shortcode($content));
	
	return $content;
	
}

//////////////////////////////////////////////////////////////////
// Tabs shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('tabs', 'shortcode_tabs');
	function shortcode_tabs( $atts, $content = null ) {
	global $data;

	extract(shortcode_atts(array(
		'layout' => 'horizontal',
		'backgroundcolor' => '',
		'inactivecolor' => ''
	), $atts));

	if(!$backgroundcolor) {
		$backgroundcolor = $data['tabs_bg_color'];
	}

	if(!$inactivecolor) {
		$inactivecolor = $data['tabs_inactive_color'];
	}

	static $avada_tabs_counter = 1;

	$out = "<style type='text/css'>
	#tabs-{$avada_tabs_counter},#tabs-{$avada_tabs_counter}.tabs-vertical .tabs,#tabs-{$avada_tabs_counter}.tabs-vertical .tab_content{border-color:{$inactivecolor} !important;}
	#main #tabs-{$avada_tabs_counter}.tabs-horizontal,#tabs-{$avada_tabs_counter}.tabs-vertical .tab_content,.pyre_tabs .tabs-container{background-color:{$backgroundcolor} !important;}
	body.dark #tabs-{$avada_tabs_counter}.shortcode-tabs .tab-hold .tabs li,body.dark #sidebar .tab-hold .tabs li{border-right:1px solid {$backgroundcolor} !important;}
	body.dark #tabs-{$avada_tabs_counter}.shortcode-tabs .tab-hold .tabs li:last-child{border-right:0 !important;}
	body.dark #main #tabs-{$avada_tabs_counter} .tab-hold .tabs li a{background:{$inactivecolor} !important;border-bottom:0 !important;color:{$data[body_text_color]} !important;}
	body.dark #main #tabs-{$avada_tabs_counter} .tab-hold .tabs li a:hover{background:{$backgroundcolor} !important;border-bottom:0 !important;}
	body #main #tabs-{$avada_tabs_counter} .tab-hold .tabs li.active a,body #main #tabs-{$avada_tabs_counter} .tab-hold .tabs li.active{background:{$backgroundcolor} !important;border-bottom:0 !important;}
	#sidebar .tab-hold .tabs li.active a{border-top-color:{$data[primary_color]} !important;}
	</style>";

    $out .= '<div id="tabs-'.$avada_tabs_counter.'" class="tab-holder shortcode-tabs clearfix tabs-'.$layout.'">';

	$out .= '<div class="tab-hold tabs-wrapper">';
	
	$out .= '<ul id="tabs" class="tabset tabs">';
	foreach ($atts as $key => $tab) {
		if($key != 'layout' && $key != 'backgroundcolor' && $key != 'inactivecolor') {
			$out .= '<li><a href="#' . $key . '">' . $tab . '</a></li>';
		}
	}
	$out .= '</ul>';
	
	$out .= '<div class="tab-box tabs-container">';

	$out .= do_shortcode($content) .'</div></div></div>';

	$avada_tabs_counter++;
	
	return $out;
}

add_shortcode('tab', 'shortcode_tab');
	function shortcode_tab( $atts, $content = null ) {
	extract(shortcode_atts(array(
    ), $atts));
    
	$out = '';
	$out .= '<div id="tab' . $atts['id'] . '" class="tab tab_content">' . do_shortcode($content) .'</div>';
	
	return $out;
}

//////////////////////////////////////////////////////////////////
// Accordian
//////////////////////////////////////////////////////////////////
add_shortcode('accordian', 'shortcode_accordian');
	function shortcode_accordian( $atts, $content = null ) {
	$out = '';
	$out .= '<div class="accordian">';
	$out .= do_shortcode($content);
	$out .= '</div>';
	
   return $out;
}	

//////////////////////////////////////////////////////////////////
// Toggle shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('toggle', 'shortcode_toggle');
	function shortcode_toggle( $atts, $content = null ) {
	extract(shortcode_atts(array(
        'title'      => '',
        'open' => 'no'
    ), $atts));

    $toggleclass = '';
    $toggleclass2 = '';
    $togglestyle = '';

	if($open == 'yes'){
		$toggleclass = "active";
		$toggleclass2 = "default-open";
		$togglestyle = "display: block;";
	}

	$out = '';
	$out .= '<h5 class="toggle '.$toggleclass.'"><a href="#"><span class="arrow"></span>' .$title. '</a></h5>';
	$out .= '<div class="toggle-content '.$toggleclass2.'" style="'.$togglestyle.'">';
	$out .= do_shortcode($content);
	$out .= '</div>';
	
   return $out;
}
	
//////////////////////////////////////////////////////////////////
// Column one_half shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('one_half', 'shortcode_one_half');
	function shortcode_one_half($atts, $content = null) {
		$atts = shortcode_atts(
			array(
				'last' => 'no',
			), $atts);
			
			if($atts['last'] == 'yes') {
				return '<div class="one_half last">' .do_shortcode($content). '</div><div class="clearboth"></div>';
			} else {
				return '<div class="one_half">' .do_shortcode($content). '</div>';
			}

	}
	
//////////////////////////////////////////////////////////////////
// Column one_third shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('one_third', 'shortcode_one_third');
	function shortcode_one_third($atts, $content = null) {
		$atts = shortcode_atts(
			array(
				'last' => 'no',
			), $atts);
			
			if($atts['last'] == 'yes') {
				return '<div class="one_third last">' .do_shortcode($content). '</div><div class="clearboth"></div>';
			} else {
				return '<div class="one_third">' .do_shortcode($content). '</div>';
			}

	}
	
//////////////////////////////////////////////////////////////////
// Column two_third shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('two_third', 'shortcode_two_third');
	function shortcode_two_third($atts, $content = null) {
		$atts = shortcode_atts(
			array(
				'last' => 'no',
			), $atts);
			
			if($atts['last'] == 'yes') {
				return '<div class="two_third last">' .do_shortcode($content). '</div><div class="clearboth"></div>';
			} else {
				return '<div class="two_third">' .do_shortcode($content). '</div>';
			}

	}
	
//////////////////////////////////////////////////////////////////
// Column one_fourth shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('one_fourth', 'shortcode_one_fourth');
	function shortcode_one_fourth($atts, $content = null) {
		$atts = shortcode_atts(
			array(
				'last' => 'no',
			), $atts);
			
			if($atts['last'] == 'yes') {
				return '<div class="one_fourth last">' .do_shortcode($content). '</div><div class="clearboth"></div>';
			} else {
				return '<div class="one_fourth">' .do_shortcode($content). '</div>';
			}

	}
	
//////////////////////////////////////////////////////////////////
// Column three_fourth shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('three_fourth', 'shortcode_three_fourth');
	function shortcode_three_fourth($atts, $content = null) {
		$atts = shortcode_atts(
			array(
				'last' => 'no',
			), $atts);
			
			if($atts['last'] == 'yes') {
				return '<div class="three_fourth last">' .do_shortcode($content). '</div><div class="clearboth"></div>';
			} else {
				return '<div class="three_fourth">' .do_shortcode($content). '</div>';
			}

	}

//////////////////////////////////////////////////////////////////
// Tagline box shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('tagline_box', 'shortcode_tagline_box');
	function shortcode_tagline_box($atts, $content = null) {
		global $data;

		extract(shortcode_atts(array(
			'backgroundcolor' => '',
			'shadow' => 'no',
			'border' => '1px',
			'bordercolor' => '',
			'highlightposition' => 'left',
			'linktarget' => '_self'
		), $atts));

		if(!$backgroundcolor) {
			$backgroundcolor = $data['tagline_bg'];
		}

		if(!$bordercolor) {
			$bordercolor = $data['tagline_border_color'];
		}

		$str = '';
		$str .= '<section class="reading-box clearfix" style="background-color:'.$backgroundcolor.' !important;border-width:'.$border.';border-color:'.$bordercolor.'!important;border-'.$highlightposition.'-width:3px !important;border-'.$highlightposition.'-color:'.$data['primary_color'].'!important;border-style:solid;">';
			if((isset($atts['link']) && $atts['link']) && (isset($atts['button']) && $atts['button'])):
			$str .= '<a href="'.$atts['link'].'" target="'.$linktarget.'" class="continue button large green">'.$atts['button'].'</a>';
			endif;
			if(isset($atts['title']) && $atts['title']):
			$str .= '<h2>'.$atts['title'].'</h2>';
			endif;
			if(isset($atts['description']) && $atts['description']):
			$str.= '<p>'.$atts['description'].'</p>';
			endif;
			if(isset($atts['link']) && $atts['link'] && isset($atts['button']) && $atts['button']):
			$str .= '<a href="'.$atts['link'].'" target="'.$linktarget.'" class="continue mobile-button button large green">'.$atts['button'].'</a>';
			endif;
			if($shadow == 'yes') {
			$str .= '<div class="tagline-shadow"></div>';
			}
		$str .= '</section>';

		return $str;
	}

//////////////////////////////////////////////////////////////////
// Pricing table
//////////////////////////////////////////////////////////////////
add_shortcode('pricing_table', 'shortcode_pricing_table');
	function shortcode_pricing_table($atts, $content = null) {
		global $data;

		extract(shortcode_atts(array(
			'backgroundcolor' => '',
			'bordercolor' => '',
			'dividercolor' => ''
		), $atts));

		static $avada_pricing_table_counter = 1;

		if(!$backgroundcolor) {
			$backgroundcolor = $data['pricing_bg_color'];
		}

		if(!$bordercolor) {
			$bordercolor = $data['pricing_border_color'];
		}

		if(!$dividercolor) {
			$dividercolor = $data['pricing_divider_color'];
		}

		$str = "<style type='text/css'>
		#pricing-table-{$avada_pricing_table_counter}.full-boxed-pricing{background-color:{$bordercolor} !important;}
		#pricing-table-{$avada_pricing_table_counter} .column{background-color:{$backgroundcolor} !important;border-color:{$dividercolor} !important;}
		#pricing-table-{$avada_pricing_table_counter}.sep-boxed-pricing .column{background-color:{$bordercolor} !important;}
		#pricing-table-{$avada_pricing_table_counter} .column li{border-color:{$dividercolor} !important;}
		#pricing-table-{$avada_pricing_table_counter} li.normal-row{background-color:{$backgroundcolor} !important;}
		#pricing-table-{$avada_pricing_table_counter}.full-boxed-pricing li.title-row{background-color:{$backgroundcolor} !important;}
		#pricing-table-{$avada_pricing_table_counter} li.pricing-row,#pricing-table-{$avada_pricing_table_counter} li.footer-row{background-color:{$bordercolor} !important;}
		</style>";

		if($atts['type'] == '2') {
			$type = 'sep';
		} elseif($atts['type'] == '1') {
			$type = 'full';
		} else {
			$type = 'third';
		}
		$str .= '<div id="pricing-table-'.$avada_pricing_table_counter.'" class="'.$type.'-boxed-pricing">';
		$str .= do_shortcode($content);
		$str .= '</div><div class="clear"></div>';

		$avada_pricing_table_counter++;

		return $str;
	}

//////////////////////////////////////////////////////////////////
// Pricing Column
//////////////////////////////////////////////////////////////////
add_shortcode('pricing_column', 'shortcode_pricing_column');
	function shortcode_pricing_column($atts, $content = null) {
		$str = '<div class="column">';
		$str .= '<ul>';
		if($atts['title']):
		$str .= '<li class="title-row">'.$atts['title'].'</li>';
		endif;
		$str .= do_shortcode($content);
		$str .= '</ul>';
		$str .= '</div>';

		return $str;
	}

//////////////////////////////////////////////////////////////////
// Pricing Row
//////////////////////////////////////////////////////////////////
add_shortcode('pricing_price', 'shortcode_pricing_price');
	function shortcode_pricing_price($atts, $content = null) {
		$str = '';
		$str .= '<li class="pricing-row">';
		if(isset($atts['currency']) && !empty($atts['currency']) && isset($atts['price']) && !empty($atts['price'])) {
			$class = '';
			$price = explode('.', $atts['price']);
			if($price[1]){
				$class .= 'price-with-decimal';
			}
			$str .= '<div class="price '.$class.'">';
				$str .= '<strong>'.$atts['currency'].'</strong>';
				$str .= '<em class="exact_price">'.$price[0].'</em>';
				if($price[1]){
					$str .= '<sup>'.$price[1].'</sup>';
				}
				if($atts['time']) {
					$str .= '<em class="time">'.$atts['time'].'</em>';
				}
			$str .= '</div>';
		} else {
			$str .= do_shortcode($content);
		}
		$str .= '</li>';

		return $str;
	}

//////////////////////////////////////////////////////////////////
// Pricing Row
//////////////////////////////////////////////////////////////////
add_shortcode('pricing_row', 'shortcode_pricing_row');
	function shortcode_pricing_row($atts, $content = null) {
		$str = '';
		$str .= '<li class="normal-row">';
		$str .= do_shortcode($content);
		$str .= '</li>';

		return $str;
	}

//////////////////////////////////////////////////////////////////
// Pricing Footer
//////////////////////////////////////////////////////////////////
add_shortcode('pricing_footer', 'shortcode_pricing_footer');
	function shortcode_pricing_footer($atts, $content = null) {
		$str = '';
		$str .= '<li class="footer-row">';
		$str .= do_shortcode($content);
		$str .= '</li>';

		return $str;
	}

//////////////////////////////////////////////////////////////////
// Content box shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('content_boxes', 'shortcode_content_boxes');
	function shortcode_content_boxes($atts, $content = null) {
		global $data;

		extract(shortcode_atts(array(
			'layout' => 'icon-with-title',
			'iconcolor' => '',
			'circlecolor' => '',
			'circlebordercolor' => '',
			'backgroundcolor' => ''
		), $atts));

		static $avada_content_box_counter = 1;

		if(!$iconcolor) {
			$iconcolor = $data['icon_color'];
		}

		if(!$circlecolor) {
			$circlecolor = $data['icon_circle_color'];
		}

		if(!$circlebordercolor) {
			$circlebordercolor = $data['icon_border_color'];
		}

		if(!$backgroundcolor) {
			$backgroundcolor = $data['content_box_bg_color'];
		}

		$str = "<style type='text/css'>
		#content-boxes-{$avada_content_box_counter} article.col{background-color:{$backgroundcolor} !important;}
		#content-boxes-{$avada_content_box_counter} .fontawesome-icon.circle-yes{color:{$iconcolor} !important;background-color:{$circlecolor} !important;border:1px solid {$circlebordercolor} !important;}
		</style>";
		$str .= '<section class="clearfix columns content-boxes content-boxes-'.$layout.'" id="content-boxes-'.$avada_content_box_counter.'">';
		$str .= do_shortcode($content);
		$str .= '</section>';

		$avada_content_box_counter++;

		return $str;
	}

//////////////////////////////////////////////////////////////////
// Content box shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('content_box', 'shortcode_content_box');
	function shortcode_content_box($atts, $content = null) {
		extract(shortcode_atts(array(
			'linktarget' => '_self',
		), $atts));

		$str = '';
		if(!empty($atts['last']) && $atts['last'] == 'yes'):
		$str .= '<article class="col last">';
		else:
		$str .= '<article class="col">';
		endif;

		if((isset($atts['image']) && $atts['image']) || (isset($atts['title']) && $atts['title'])):
			if(!empty($atts['image']) || !empty($atts['icon'])){
				$str .=	'<div class="heading heading-and-icon">';
			} else {
				$str .=	'<div class="heading">';
			}
		if(isset($atts['image']) && $atts['image']):
		$str .= '<img src="'.$atts['image'].'" width="35" height="35" alt="">';
		endif;
		if(!empty($atts['icon']) && $atts['icon']):
			$str .= '<i class="fontawesome-icon medium circle-yes icon-'.$atts['icon'].'"></i>';
		endif;
		if($atts['title']):
		$str .= '<h2>'.$atts['title'].'</h2>';
		endif;
		$str .= '</div>';
		endif;

		$str .= '<div class="col-content-container">';

		$str .= do_shortcode($content);
		
		if(isset($atts['link']) && $atts['link'] && isset($atts['linktext']) && $atts['linktext']):
		$str .= '<span class="more"><a href="'.$atts['link'].'" target="'.$linktarget.'">'.$atts['linktext'].'</a></span>';
		endif;

		$str .= '</div>';
		
		$str .= '</article>';

		return $str;
	}

//////////////////////////////////////////////////////////////////
// Slider
//////////////////////////////////////////////////////////////////
add_shortcode('slider', 'shortcode_slider');
	function shortcode_slider($atts, $content = null) {
		wp_enqueue_script( 'jquery.flexslider' );

		extract(shortcode_atts(array(
			'width' => '100%',
			'height' => '100%'
		), $atts));
		$str = '';
		$str .= '<div class="flexslider" style="overflow:hidden;max-width:'.$width.';height:'.$height.';">';
		$str .= '<ul class="slides">';
		$str .= do_shortcode($content);
		$str .= '</ul>';
		$str .= '</div>';

		return $str;
	}

//////////////////////////////////////////////////////////////////
// Slide
//////////////////////////////////////////////////////////////////
add_shortcode('slide', 'shortcode_slide');
	function shortcode_slide($atts, $content = null) {
		extract(shortcode_atts(array(
			'linktarget' => '_self',
		), $atts));

		$str = '';
		if(!empty($atts['type']) && $atts['type'] == 'video') {
			$str .= '<li class="full-video">';
		} else { 
			$str .= '<li class="image">';
		}
		if(!empty($atts['link']) && $atts['link']):
		$str .= '<a href="'.$atts['link'].'" target="'.$linktarget.'">';
		endif;
		if(!empty($atts['type']) && $atts['type'] == 'video') {
			$str .= do_shortcode($content);
		} else {
			$str .= '<img src="'.str_replace('&#215;', 'x', $content).'" alt="" />';
		}
		if(!empty($atts['link']) && $atts['link']):
		$str .= '</a>';
		endif;
		$str .= '</li>';

		return $str;
	}

//////////////////////////////////////////////////////////////////
// Testimonials
//////////////////////////////////////////////////////////////////
add_shortcode('testimonials', 'shortcode_testimonials');
	function shortcode_testimonials($atts, $content = null) {
		global $data;

		wp_enqueue_script( 'jquery.cycle' );

		extract(shortcode_atts(array(
			'backgroundcolor' => '',
			'textcolor' => ''
		), $atts));

		static $avada_testimonials_id = 1;

		if(!$backgroundcolor) {
			$backgroundcolor = $data['testimonial_bg_color'];
		}


		if(!$textcolor) {
			$textcolor = $data['testimonial_text_color'];
		}

		$getRgb = avada_hex2rgb($stylecolor);

		$str = "<style type='text/css'>
		#testimonials-{$avada_testimonials_id} q{background-color:{$backgroundcolor} !important;color:{$textcolor} !important;}
		.review blockquote div:after{border-top-color:{$backgroundcolor} !important;}
		</style>
		";

		$str .= '<div id="testimonials-'.$avada_testimonials_id.'" class="reviews clearfix">';
		$str .= do_shortcode($content);
		$str .= '</div>';

		$avada_testimonials_id++;

		return $str;
	}

//////////////////////////////////////////////////////////////////
// Testimonial
//////////////////////////////////////////////////////////////////
add_shortcode('testimonial', 'shortcode_testimonial');
	function shortcode_testimonial($atts, $content = null) {
		if(!isset($atts['gender'])) {
			$atts['gender'] = 'male';
		}
		$str = '';
		$str .= '<div class="review '.$atts['gender'].'">';
		$str .= '<blockquote>';
		$str .= '<q>';
		$str .= do_shortcode($content);
		$str .= '</q>';
		if($atts['name']):
			$str .= '<div class="cleafix"><span class="company-name">';
			$str .= '<strong>'.$atts['name'].'</strong>,';
			if($atts['company']):
				if(!empty($atts['link']) && $atts['link']):
				$str .= '<a href="'.$atts['link'].'" target="'.$atts['target'].'">';
				endif;
				$str .= '<span> '.$atts['company'].'</span>';
				if(!empty($atts['link']) && $atts['link']):
				$str .= '</a>';
				endif;
			endif;
			$str .= '</span></div>';
		endif;
		$str .= '</blockquote>';
		$str .= '</div>';

		return $str;
	}

	
//////////////////////////////////////////////////////////////////
// Progess Bar
//////////////////////////////////////////////////////////////////
add_shortcode('progress', 'shortcode_progress');
function shortcode_progress($atts, $content = null) {
	global $data;

	wp_enqueue_script( 'jquery.waypoint' );

	extract(shortcode_atts(array(
		'filledcolor' => '',
		'unfilledcolor' => '',
		'value' => '70'
	), $atts));

	if(!$filledcolor) {
		$filledcolor = $data['counter_filled_color'];
	}

	if(!$unfilledcolor) {
		$unfilledcolor = $data['counter_unfilled_color'];
	}

	$html = '';
	$html .= '<div class="progress-bar" style="background-color:'.$unfilledcolor.' !important;border-color:'.$unfilledcolor.' !important;">';
	$html .= '<div class="progress-bar-content" data-percentage="'.$atts['percentage'].'" style="width: ' . $atts['percentage'] . '%;background-color:'.$filledcolor.' !important;border-color:'.$filledcolor.' !important;">';
	$html .= '</div>';
	$html .= '<span class="progress-title">' . $content . ' ' . $atts['percentage'] . '%</span>';
	$html .= '</div>';

	return $html;
}

//////////////////////////////////////////////////////////////////
// Person
//////////////////////////////////////////////////////////////////
add_shortcode('person', 'shortcode_person');
function shortcode_person($atts, $content = null) {
	$html = '';
	$html .= '<div class="person">';
	if($atts['picture']):
	$html .= '<img class="person-img" src="' . $atts['picture'] . '" alt="' . $atts['name'] . '" />';
	endif;
	if($atts['name'] || $atts['title'] || $atts['facebooklink'] || $atts['twitterlink'] || $atts['linkedinlink'] || $content) {
		$html .= '<div class="person-desc">';
			$html .= '<div class="person-author clearfix">';
				$html .= '<div class="person-author-wrapper"><span class="person-name">' . $atts['name'] . '</span>';
				$html .= '<span class="person-title">' . $atts['title'] . '</span></div>';
				if($atts['facebook']) {
					$html .= '<span class="social-icon"><a href="' . $atts['facebook'] . '" target="'.$atts['linktarget'].'" class="facebook">Facebook</a><div class="popup">
						<div class="holder">
							<p>Facebook</p>
						</div>
					</div></span>';
				}
				if($atts['twitter']) {
					$html .= '<span class="social-icon"><a href="' . $atts['twitter'] . '" target="'.$atts['linktarget'].'" class="twitter">Twitter</a><div class="popup">
						<div class="holder">
							<p>Twitter</p>
						</div>
					</div></span>';
				}
				if($atts['linkedin']) {
					$html .= '<span class="social-icon"><a href="' . $atts['linkedin'] . '" target="'.$atts['linktarget'].'" class="linkedin">LinkedIn</a><div class="popup">
						<div class="holder">
							<p>LinkedIn</p>
						</div>
					</div></span>';
				}
				if($atts['dribbble']) {
					$html .= '<span class="social-icon"><a href="' . $atts['dribbble'] . '" target="'.$atts['linktarget'].'" class="dribbble">Dribbble</a><div class="popup">
						<div class="holder">
							<p>Dribbble</p>
						</div>
					</div></span>';
				}
			$html .= '<div class="clear"></div></div>';
			$html .= '<div class="person-content">' . $content . '</div>';
		$html .= '</div>';
	}
	$html .= '</div>';

	return $html;
}

//////////////////////////////////////////////////////////////////
// Recent Posts
//////////////////////////////////////////////////////////////////
add_shortcode('recent_posts', 'shortcode_recent_posts');
function shortcode_recent_posts($atts, $content = null) {
	global $data;

	wp_enqueue_script( 'jquery.flexslider' );

	extract(shortcode_atts(array(
		'layout' => 'default',
		'columns' => 1,
		'number_posts' => 4,
	), $atts));

	if(!isset($atts['columns'])) {
		$atts['columns'] = 3;
	}

	if(!isset($atts['excerpt_words'])) {
		$atts['excerpt_words'] = 15;
	}

	if(!isset($atts['number_posts'])) {
		$atts['number_posts'] = 3;
	}

	if(!isset($atts['strip_html'])) {
		$atts['strip_html'] = 'true';
	}

	if($layout == 'default'):
		$attachment = '';
		$html = '<div class="avada-container">';
		$html .= '<section class="columns columns-'.$atts['columns'].'" style="width:100%">';
		$html .= '<div class="holder">';
		if(!empty($atts['cat_id']) && $atts['cat_id']){
			$recent_posts = new WP_Query('showposts='.$atts['number_posts'].'&category_name='.$atts['cat_id']);
		} elseif(!empty($atts['cat_slug']) && $atts['cat_slug']){
			$recent_posts = new WP_Query('showposts='.$atts['number_posts'].'&category_name='.$atts['cat_slug']);
		} else {
			$recent_posts = new WP_Query('showposts='.$atts['number_posts']);
		}
		$count = 1;
		while($recent_posts->have_posts()): $recent_posts->the_post();
		$html .= '<article class="col">';
		if($atts['thumbnail'] == "yes"):
		if($data['legacy_posts_slideshow']):
		$args = array(
		    'post_type' => 'attachment',
		    'numberposts' => $data['posts_slideshow_number']-1,
		    'post_status' => null,
		    'post_mime_type' => 'image',
		    'post_parent' => get_the_ID(),
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'exclude' => get_post_thumbnail_id()
		);
		$attachments = get_posts($args);
		if($attachments || has_post_thumbnail() || get_post_meta(get_the_ID(), 'pyre_video', true)):
		$html .= '<div class="flexslider floated-post-slideshow">';
			$html .= '<ul class="slides">';
				if(get_post_meta(get_the_ID(), 'pyre_video', true)):
				$html .= '<li class="full-video">';
					$html .= get_post_meta(get_the_ID(), 'pyre_video', true);
				$html .= '</li>';
				endif;
				if(has_post_thumbnail()):
				$attachment_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'blog-medium');
				$full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
				$attachment_data = wp_get_attachment_metadata(get_post_thumbnail_id());
				$html .= '<li>
					<a href="'.get_permalink(get_the_ID()).'" rel=""><img src="'.$attachment_image[0].'" alt="'.get_the_title().'" /></a>
				</li>';
				endif;
				if($data['posts_slideshow']):
				foreach($attachments as $attachment):
				$attachment_image = wp_get_attachment_image_src($attachment->ID, 'blog-medium');
				$full_image = wp_get_attachment_image_src($attachment->ID, 'full');
				$attachment_data = wp_get_attachment_metadata($attachment->ID);
				$html .= '<li>
					<a href="'.get_permalink(get_the_ID()).'" rel=""><img src="'. $attachment_image[0].'" alt="'.$attachment->post_title.'" /></a>
				</li>';
				endforeach;
				endif;
			$html .= '</ul>
		</div>';
		endif;
		else:
		if(has_post_thumbnail() || get_post_meta(get_the_ID(), 'pyre_video', true)):
		$html .= '<div class="flexslider floated-post-slideshow">';
			$html .= '<ul class="slides">';
				if(get_post_meta(get_the_ID(), 'pyre_video', true)):
				$html .= '<li class="full-video">';
					$html .= get_post_meta(get_the_ID(), 'pyre_video', true);
				$html .= '</li>';
				endif;
				if(has_post_thumbnail()):
				$attachment_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'blog-medium');
				$full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
				$attachment_data = wp_get_attachment_metadata(get_post_thumbnail_id());
				$html .= '<li>
					<a href="'.get_permalink(get_the_ID()).'" rel=""><img src="'.$attachment_image[0].'" alt="'.get_the_title().'" /></a>
				</li>';
				endif;
				if($data['posts_slideshow']):
				$i = 2;
				while($i <= $data['posts_slideshow_number']):
				$attachment_new_id = kd_mfi_get_featured_image_id('featured-image-'.$i, 'post');
				if($attachment_new_id):
				$attachment_image = wp_get_attachment_image_src($attachment_new_id, 'blog-medium');
				$full_image = wp_get_attachment_image_src($attachment_new_id, 'full');
				$attachment_data = wp_get_attachment_metadata($attachment_new_id);
				$html .= '<li>
					<a href="'.get_permalink(get_the_ID()).'" rel=""><img src="'. $attachment_image[0].'" alt="" /></a>
				</li>';
				endif; $i++; endwhile;
				endif;
			$html .= '</ul>
		</div>';
		endif;
		endif;
		endif;
		if($atts['title'] == "yes"):
		$html .= '<h4><a href="'.get_permalink(get_the_ID()).'">'.get_the_title().'</a></h4>';
		endif;
		if($atts['meta'] == "yes"):
		$html .= '<ul class="meta">';
		$html .= '<li><em class="date">'.get_the_time($data['date_format'], get_the_ID()).'</em></li>';
		if(get_comments_number(get_the_ID()) >= 1):
		$html .= '<li><a href="'.get_permalink(get_the_ID()).'">'.get_comments_number(get_the_ID()).' '.__('Comments', 'Avada').'</a></li>';
		endif;
		$html .= '</ul>';
		endif;
		if($atts['excerpt'] == "yes"):
		$stripped_content = tf_content( $atts['excerpt_words'], $atts['strip_html'] );
		$html .= '<p>'.$stripped_content.'</p>';
		endif;
		$html .= '</article>';
		$count++;
		endwhile;
		$html .= '</div>';
		$html .= '</section>';
		$html .= '</div>';
	elseif($layout == 'thumbnails-on-side'):
		$attachment = '';
		$html = '<div class="avada-container layout-'.$layout.' layout-columns-'.$columns.'">';
		$html .= '<section class="columns columns-'.$atts['columns'].'" style="width:100%">';
		$html .= '<div class="holder">';
		if(!empty($atts['cat_id']) && $atts['cat_id']){
			$recent_posts = new WP_Query('showposts='.$atts['number_posts'].'&category_name='.$atts['cat_id']);
		} elseif(!empty($atts['cat_slug']) && $atts['cat_slug']){
			$recent_posts = new WP_Query('showposts='.$atts['number_posts'].'&category_name='.$atts['cat_slug']);
		} else {
			$recent_posts = new WP_Query('showposts='.$atts['number_posts']);
		}
		$count = 1;
		while($recent_posts->have_posts()): $recent_posts->the_post();
		$html .= '<article class="col clearfix">';
		if($atts['thumbnail'] == "yes"):
		if($data['legacy_posts_slideshow']):
		$args = array(
		    'post_type' => 'attachment',
		    'numberposts' => $data['posts_slideshow_number']-1,
		    'post_status' => null,
		    'post_mime_type' => 'image',
		    'post_parent' => get_the_ID(),
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'exclude' => get_post_thumbnail_id()
		);
		$attachments = get_posts($args);
		if($attachments || has_post_thumbnail() || get_post_meta(get_the_ID(), 'pyre_video', true)):
		$html .= '<div class="flexslider floated-post-slideshow">';
			$html .= '<ul class="slides">';
				if(get_post_meta(get_the_ID(), 'pyre_video', true)):
				$html .= '<li class="full-video">';
					$html .= get_post_meta(get_the_ID(), 'pyre_video', true);
				$html .= '</li>';
				endif;
				if(has_post_thumbnail()):
				$attachment_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'portfolio-two');
				$full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
				$attachment_data = wp_get_attachment_metadata(get_post_thumbnail_id());
				$html .= '<li>
					<a href="'.get_permalink(get_the_ID()).'" rel=""><img src="'.$attachment_image[0].'" alt="'.get_the_title().'" /></a>
				</li>';
				endif;
				if($data['posts_slideshow']):
				foreach($attachments as $attachment):
				$attachment_image = wp_get_attachment_image_src($attachment->ID, 'portfolio-two');
				$full_image = wp_get_attachment_image_src($attachment->ID, 'full');
				$attachment_data = wp_get_attachment_metadata($attachment->ID);
				$html .= '<li>
					<a href="'.get_permalink(get_the_ID()).'" rel=""><img src="'. $attachment_image[0].'" alt="'.$attachment->post_title.'" /></a>
				</li>';
				endforeach;
				endif;
			$html .= '</ul>
		</div>';
		endif;
		else:
		if(has_post_thumbnail() || get_post_meta(get_the_ID(), 'pyre_video', true)):
		$html .= '<div class="flexslider floated-post-slideshow">';
			$html .= '<ul class="slides">';
				if(get_post_meta(get_the_ID(), 'pyre_video', true)):
				$html .= '<li class="full-video">';
					$html .= get_post_meta(get_the_ID(), 'pyre_video', true);
				$html .= '</li>';
				endif;
				if(has_post_thumbnail()):
				$attachment_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'portfolio-two');
				$full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
				$attachment_data = wp_get_attachment_metadata(get_post_thumbnail_id());
				$html .= '<li>
					<a href="'.get_permalink(get_the_ID()).'" rel=""><img src="'.$attachment_image[0].'" alt="'.get_the_title().'" /></a>
				</li>';
				endif;
				if($data['posts_slideshow']):
				$i = 2;
				while($i <= $data['posts_slideshow_number']):
				$attachment_new_id = kd_mfi_get_featured_image_id('featured-image-'.$i, 'post');
				if($attachment_new_id):
				$attachment_image = wp_get_attachment_image_src($attachment_new_id, 'portfolio-two');
				$full_image = wp_get_attachment_image_src($attachment_new_id, 'full');
				$attachment_data = wp_get_attachment_metadata($attachment_new_id);
				$html .= '<li>
					<a href="'.get_permalink(get_the_ID()).'" rel=""><img src="'. $attachment_image[0].'" alt="" /></a>
				</li>';
				endif; $i++; endwhile;
				endif;
			$html .= '</ul>
		</div>';
		endif;
		endif;
		endif;
		$html .= '<div class="recent-posts-content">';
		if($atts['title'] == "yes"):
		$html .= '<h4><a href="'.get_permalink(get_the_ID()).'">'.get_the_title().'</a></h4>';
		endif;
		if($atts['meta'] == "yes"):
		$html .= '<ul class="meta">';
		$html .= '<li>'.get_the_time($data['date_format'], get_the_ID()).'</li>';
		if(get_comments_number(get_the_ID()) >= 1):
		$html .= '<li><a href="'.get_permalink(get_the_ID()).'">'.get_comments_number(get_the_ID()).' '.__('Comments', 'Avada').'</a></li>';
		endif;
		$html .= '</ul>';
		endif;
		if($atts['excerpt'] == "yes"):
		$stripped_content = tf_content( $atts['excerpt_words'], $atts['strip_html'] );
		$html .= $stripped_content;
		endif;
		$html .= '</div>';
		$html .= '</article>';
		$count++;
		endwhile;
		$html .= '</div>';
		$html .= '</section>';
		$html .= '</div>';
	elseif($layout == 'date-on-side'):
		$attachment = '';
		$html = '<div class="avada-container layout-'.$layout.' layout-columns-'.$columns.'">';
		$html .= '<section class="columns columns-'.$atts['columns'].'" style="width:100%">';
		$html .= '<div class="holder">';
		if(!empty($atts['cat_id']) && $atts['cat_id']){
			$recent_posts = new WP_Query('showposts='.$atts['number_posts'].'&category_name='.$atts['cat_id']);
		} elseif(!empty($atts['cat_slug']) && $atts['cat_slug']){
			$recent_posts = new WP_Query('showposts='.$atts['number_posts'].'&category_name='.$atts['cat_slug']);
		} else {
			$recent_posts = new WP_Query('showposts='.$atts['number_posts']);
		}
		$count = 1;
		while($recent_posts->have_posts()): $recent_posts->the_post();
		$html .= '<article class="col clearfix">';
		$html .= '<div class="date-and-formats">
			<div class="date-box">
				<span class="date">'.get_the_time('j').'</span>
				<span class="month-year">'.get_the_time('m, Y').'</span>
			</div>';
			$html .= '<div class="format-box">';
				switch(get_post_format()) {
					case 'gallery':
						$format_class = 'camera-retro';
						break;
					case 'link':
						$format_class = 'link';
						break;
					case 'image':
						$format_class = 'picture';
						break;
					case 'quote':
						$format_class = 'quote-left';
						break;
					case 'video':
						$format_class = 'film';
						break;
					case 'audio':
						$format_class = 'headphones';
						break;
					case 'chat':
						$format_class = 'comments-alt';
						break;
					default:
						$format_class = 'book';
						break;
				}
				$html .= '<i class="icon-'.$format_class.'"></i>
			</div>
		</div>';
		$html .= '<div class="recent-posts-content">';
		if($atts['title'] == "yes"):
		$html .= '<h4><a href="'.get_permalink(get_the_ID()).'">'.get_the_title().'</a></h4>';
		endif;
		if($atts['meta'] == "yes"):
		$html .= '<ul class="meta">';
		$html .= '<li>'.get_the_time($data['date_format'], get_the_ID()).'</li>';
		if(get_comments_number(get_the_ID()) >= 1):
		$html .= '<li><a href="'.get_permalink(get_the_ID()).'">'.get_comments_number(get_the_ID()).' '.__('Comments', 'Avada').'</a></li>';
		endif;
		$html .= '</ul>';
		endif;
		if($atts['excerpt'] == "yes"):
		$stripped_content = tf_content( $atts['excerpt_words'], $atts['strip_html'] );
		$html .= $stripped_content;
		endif;
		$html .= '</div>';
		$html .= '</article>';
		$count++;
		endwhile;
		$html .= '</div>';
		$html .= '</section>';
		$html .= '</div>';
	endif;

	return $html;
}

//////////////////////////////////////////////////////////////////
// Recent Works
//////////////////////////////////////////////////////////////////
add_shortcode('recent_works', 'shortcode_recent_works');
function shortcode_recent_works($atts, $content = null) {
	global $data;

	wp_enqueue_script( 'jquery.isotope' );
	wp_enqueue_script( 'jquery.carouFredSel' );

	extract(shortcode_atts(array(
		'layout' => 'carousel',
		'filters' => 'true',
		'columns' => 4,
		'cat_slug' => '',
		'number_posts' => 10,
		'excerpt_words' => 15,
	), $atts));

	if($columns == 1) {
		$columns_words = 'one';
		$portfolio_image_size = 'full';
	} elseif($columns == 2) {
		$columns_words = 'two';
		$portfolio_image_size = 'portfolio-two';
	} elseif($columns == 3) {
		$columns_words = 'three';
		$portfolio_image_size = 'portfolio-three';
	} elseif($columns == 4) {
		$columns_words = 'four';
		$portfolio_image_size = 'portfolio-four';
	}
	$html = '';

	wp_reset_query();

	if($layout == 'carousel') {
	$html .= '<div class="related-posts related-projects">';
	$html .= '<div id="carousel" class="es-carousel-wrapper">';
	$html .= '<div class="es-carousel">';
	$html .= '<ul class="">';
					if(isset($atts['number_posts']) && !empty($atts['number_posts'])) {
						$number_posts = $atts['number_posts'];
					} else {
						$number_posts = 10;
					}
					$args = array(
						'post_type' => 'avada_portfolio',
						'paged' => 1,
						'posts_per_page' => $number_posts,
					);
					if(isset($atts['cat_id']) && !empty($atts['cat_id'])){
						$cat_id = explode(',', $atts['cat_id']);
						$args['tax_query'] = array(
							array(
								'taxonomy' => 'portfolio_category',
								'field' => 'slug',
								'terms' => $cat_id
							)
						);
					} elseif(isset($atts['cat_slug']) && !empty($atts['cat_slug'])){
						$cat_id = explode(',', $atts['cat_slug']);
						$args['tax_query'] = array(
							array(
								'taxonomy' => 'portfolio_category',
								'field' => 'slug',
								'terms' => $cat_id
							)
						);
					}

					$works = new WP_Query($args);
					while($works->have_posts()): $works->the_post();
					if(has_post_thumbnail()):
					$html .= '<li>';
						$html .= '<div class="image">';
								if($data['image_rollover']):
								$html .= get_the_post_thumbnail(get_the_ID(), 'related-img');
								else:
								$html .= '<a href="'.get_permalink(get_the_ID()).'">'.get_the_post_thumbnail(get_the_ID(), 'related-img').'</a>';
								endif;
								//$html .= '<a href="'.get_permalink(get_the_ID()).'">'.get_the_post_thumbnail(get_the_ID(), 'related-img').'</a>';
								if(get_post_meta(get_the_ID(), 'pyre_image_rollover_icons', true) == 'link') {
									$link_icon_css = '';
									$zoom_icon_css = 'display:none;';
								} elseif(get_post_meta(get_the_ID(), 'pyre_image_rollover_icons', true) == 'zoom') {
									$link_icon_css = 'display:none;';
									$zoom_icon_css = '';
								} elseif(get_post_meta(get_the_ID(), 'pyre_image_rollover_icons', true) == 'no') {
									$link_icon_css = 'display:none;';
									$zoom_icon_css = 'display:none;';
								} else {
									$link_icon_css = '';
									$zoom_icon_css = '';
								}

								$icon_url_check = get_post_meta(get_the_ID(), 'pyre_link_icon_url', true); if(!empty($icon_url_check)) {
									$icon_permalink = get_post_meta(get_the_ID(), 'pyre_link_icon_url', true);
								} else {
									$icon_permalink = get_permalink(get_the_ID());
								}
								$html .= '<div class="image-extras">';
									$html .= '<div class="image-extras-content">';
									$html .= '<a style="'.$link_icon_css.'" class="icon link-icon" style="margin-right:3px;" href="'.$icon_permalink.'">';
										$html .= 'Permalink';
									$html .= '</a>';
									$full_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
									if(get_post_meta(get_the_ID(), 'pyre_video_url', true)) {
										$full_image[0] = get_post_meta(get_the_ID(), 'pyre_video_url', true);
									}
									$html .= '<a style="'.$zoom_icon_css.'" class="icon gallery-icon" href="'.$full_image[0].'" rel="prettyPhoto[gallery<?php echo get_the_ID(); ?>]">Gallery</a>';
									$html .= '<h3>'.get_the_title().'</h3>';
									$html .= '</div>
								</div>
						</div>
					</li>';
					endif; endwhile;
				$html .= '</ul>
			</div>
			<div class="es-nav"><span class="es-nav-prev">Previous</span><span class="es-nav-next">Next</span></div>
		</div>
	</div>';
	wp_reset_query();
	} elseif($layout == 'grid') {
		$html .= '<div class="portfolio portfolio-grid portfolio-'.$columns_words.'" data-columns="'.$columns_words.'">';

		$portfolio_category = get_terms('portfolio_category');
		if($portfolio_category && $filters == 'true'):
		$html .= '<ul class="portfolio-tabs clearfix">
			<li class="active"><a data-filter="*" href="#">'.__('All', 'Avada').'</a></li>';
			foreach($portfolio_category as $portfolio_cat):
			if($atts['cat_slug']):
			$cat_slug = preg_replace('/\s+/', '', $atts['cat_slug']);
			$cat_slug = explode(',', $cat_slug);
			if(in_array($portfolio_cat->slug, $cat_slug)):
			$html .='<li><a data-filter=".'.$portfolio_cat->slug.'" href="#">'.$portfolio_cat->name.'</a></li>';
			endif;
			else:
			$html .= '<li><a data-filter=".'.$portfolio_cat->slug.'" href="#">'.$portfolio_cat->name.'</a></li>';
			endif;
			endforeach;
		$html .= '</ul>';
		endif;

		$html .= '<div class="portfolio-wrapper">';

		$args = array(
			'post_type' => 'avada_portfolio',
			'posts_per_page' => $number_posts
		);
		if(isset($atts['cat_id']) && !empty($atts['cat_id'])){
			$cat_id = explode(',', $atts['cat_id']);
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'portfolio_category',
					'field' => 'slug',
					'terms' => $cat_id
				)
			);
		} elseif(isset($atts['cat_slug']) && !empty($atts['cat_slug'])){
			$cat_id = explode(',', $atts['cat_slug']);
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'portfolio_category',
					'field' => 'slug',
					'terms' => $cat_id
				)
			);
		}
		$gallery = new WP_Query($args);
		while($gallery->have_posts()): $gallery->the_post();
			if(has_post_thumbnail() || get_post_meta(get_the_ID(), 'pyre_video', true)):
				$item_classes = '';
				$item_cats = get_the_terms(get_the_ID(), 'portfolio_category');
				if($item_cats):
				foreach($item_cats as $item_cat) {
					$item_classes .= $item_cat->slug . ' ';
				}
				endif;
				
				$permalink = get_permalink();

				$html .= '<div class="portfolio-item '.$item_classes.'">';
					if(has_post_thumbnail()):
					$html .= '<div class="image">';
						if($data['image_rollover']):
						$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $portfolio_image_size);
						$html .= '<img src="'.$thumbnail[0].'" alt="'.get_post_field('post_excerpt', get_post_thumbnail_id(get_the_ID())).'" />';
						else:
						$html .= '<a href="'.$permalink.'"></a>';
						endif;
						if(get_post_meta(get_the_ID(), 'pyre_image_rollover_icons', true) == 'link') {
							$link_icon_css = '';
							$zoom_icon_css = 'display:none;';
						} elseif(get_post_meta(get_the_ID(), 'pyre_image_rollover_icons', true) == 'zoom') {
							$link_icon_css = 'display:none;';
							$zoom_icon_css = '';
						} elseif(get_post_meta(get_the_ID(), 'pyre_image_rollover_icons', true) == 'no') {
							$link_icon_css = 'display:none;';
							$zoom_icon_css = 'display:none;';
						} else {
							$link_icon_css = '';
							$zoom_icon_css = '';
						}

						$icon_url_check = get_post_meta(get_the_ID(), 'pyre_link_icon_url', true); if(!empty($icon_url_check)) {
							$icon_permalink = get_post_meta(get_the_ID(), 'pyre_link_icon_url', true);
						} else {
							$icon_permalink = get_permalink(get_the_ID());
						}
						$html .= '<div class="image-extras">
							<div class="image-extras-content">';
								$full_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
								$html .= '<a style="'.$link_icon_css.'" class="icon link-icon" href="'.$icon_permalink.'">Permalink</a>';

								if(get_post_meta(get_the_ID(), 'pyre_video_url', true)) {
									$full_image[0] = get_post_meta(get_the_ID(), 'pyre_video_url', true);
								}

								$html .= '<a style="'.$zoom_icon_css.'" class="icon gallery-icon" href="'.$full_image[0].'" rel="prettyPhoto[gallery]" title="'.get_post_field('post_content', get_post_thumbnail_id(get_the_ID())).'"><img style="display:none;" alt="'.get_post_field('post_excerpt', get_post_thumbnail_id(get_the_ID())).'" />Gallery</a>';
								$html .= '<h3>'.get_the_title().'</h3>';
								$html .= '<h4>'.get_the_term_list(get_the_ID(), 'portfolio_category', '', ', ', '').'</h4>';
							$html .= '</div>
						</div>
					</div>';
					endif;
				$html .= '</div>';
			endif;
		endwhile;
		wp_reset_query();

		$html .= '</div>';

		$html .= '</div>';
	} elseif($layout == 'grid-with-excerpts') {
		$html .= '<div class="portfolio portfolio-'.$columns_words.'-text portfolio-'.$columns_words.'" data-columns="'.$columns_words.'">';

		$portfolio_category = get_terms('portfolio_category');
		if($portfolio_category && $filters == 'true'):
		$html .= '<ul class="portfolio-tabs clearfix">
			<li class="active"><a data-filter="*" href="#">'.__('All', 'Avada').'</a></li>';
			foreach($portfolio_category as $portfolio_cat):
			if($atts['cat_slug']):
			$cat_slug = preg_replace('/\s+/', '', $atts['cat_slug']);
			$cat_slug = explode(',', $cat_slug);
			if(in_array($portfolio_cat->slug, $cat_slug)):
			$html .='<li><a data-filter=".'.$portfolio_cat->slug.'" href="#">'.$portfolio_cat->name.'</a></li>';
			endif;
			else:
			$html .= '<li><a data-filter=".'.$portfolio_cat->slug.'" href="#">'.$portfolio_cat->name.'</a></li>';
			endif;
			endforeach;
		$html .= '</ul>';
		endif;

		$html .= '<div class="portfolio-wrapper">';

		$args = array(
			'post_type' => 'avada_portfolio',
			'posts_per_page' => $number_posts
		);
		if(isset($atts['cat_id']) && !empty($atts['cat_id'])){
			$cat_id = explode(',', $atts['cat_id']);
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'portfolio_category',
					'field' => 'slug',
					'terms' => $cat_id
				)
			);
		} elseif(isset($atts['cat_slug']) && !empty($atts['cat_slug'])){
			$cat_id = explode(',', $atts['cat_slug']);
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'portfolio_category',
					'field' => 'slug',
					'terms' => $cat_id
				)
			);
		}
		$gallery = new WP_Query($args);
		while($gallery->have_posts()): $gallery->the_post();
			if(has_post_thumbnail() || get_post_meta(get_the_ID(), 'pyre_video', true)):
				$item_classes = '';
				$item_cats = get_the_terms(get_the_ID(), 'portfolio_category');
				if($item_cats):
				foreach($item_cats as $item_cat) {
					$item_classes .= $item_cat->slug . ' ';
				}
				endif;
				
				$permalink = get_permalink();

				$html .= '<div class="portfolio-item '.$item_classes.'">';
					if(has_post_thumbnail()):
					$html .= '<div class="image">';
						if($data['image_rollover']):
						$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $portfolio_image_size);
						$html .= '<img src="'.$thumbnail[0].'" alt="'.get_post_field('post_excerpt', get_post_thumbnail_id(get_the_ID())).'" />';
						else:
						$html .= '<a href="'.$permalink.'"></a>';
						endif;
						if(get_post_meta(get_the_ID(), 'pyre_image_rollover_icons', true) == 'link') {
							$link_icon_css = '';
							$zoom_icon_css = 'display:none;';
						} elseif(get_post_meta(get_the_ID(), 'pyre_image_rollover_icons', true) == 'zoom') {
							$link_icon_css = 'display:none;';
							$zoom_icon_css = '';
						} elseif(get_post_meta(get_the_ID(), 'pyre_image_rollover_icons', true) == 'no') {
							$link_icon_css = 'display:none;';
							$zoom_icon_css = 'display:none;';
						} else {
							$link_icon_css = '';
							$zoom_icon_css = '';
						}

						$icon_url_check = get_post_meta(get_the_ID(), 'pyre_link_icon_url', true); if(!empty($icon_url_check)) {
							$icon_permalink = get_post_meta(get_the_ID(), 'pyre_link_icon_url', true);
						} else {
							$icon_permalink = get_permalink(get_the_ID());
						}
						$html .= '<div class="image-extras">
							<div class="image-extras-content">';
								$full_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
								$html .= '<a style="'.$link_icon_css.'" class="icon link-icon" href="'.$icon_permalink.'">Permalink</a>';

								if(get_post_meta(get_the_ID(), 'pyre_video_url', true)) {
									$full_image[0] = get_post_meta(get_the_ID(), 'pyre_video_url', true);
								}

								$html .= '<a style="'.$zoom_icon_css.'" class="icon gallery-icon" href="'.$full_image[0].'" rel="prettyPhoto[gallery]" title="'.get_post_field('post_content', get_post_thumbnail_id(get_the_ID())).'"><img style="display:none;" alt="'.get_post_field('post_excerpt', get_post_thumbnail_id(get_the_ID())).'" />Gallery</a>';
								$html .= '<h3>'.get_the_title().'</h3>';
								$html .= '<h4>'.get_the_term_list(get_the_ID(), 'portfolio_category', '', ', ', '').'</h4>';
							$html .= '</div>
						</div>
					</div>';
					$html .= '<div class="portfolio-content">
						<h2><a href="'.$permalink.'">'.get_the_title().'</a></h2>
						<h4>'.get_the_term_list(get_the_ID(), 'portfolio_category', '', ', ', '').'</h4>';

						$excerpt_length = $excerpt_words;
						
						$stripped_content = strip_shortcodes( tf_content( $excerpt_length, $data['strip_html_excerpt'] ) );
						$html .= $stripped_content;

						if($columns == 1):
						$html .= '<div class="buttons">
							<a href="'.$permalink.'" class="green button small">'.__('Learn More', 'Avada').'</a>';
							if(get_post_meta(get_the_ID(), 'pyre_project_url', true)):
							$html .= '<a href="'.get_post_meta(get_the_ID(), 'pyre_project_url', true).'" class="green button small">'.__('View Project', 'Avada').'</a>';
							endif;
						$html .= '</div>';
						endif;
					$html .= '</div>';
					endif;
				$html .= '</div>';
			endif;
		endwhile;
		wp_reset_query();

		$html .= '</div>';

		$html .= '</div>';
	}

	return $html;
}


//////////////////////////////////////////////////////////////////
// Alert Message
//////////////////////////////////////////////////////////////////
add_shortcode('alert', 'shortcode_alert');
function shortcode_alert($atts, $content = null) {
	$html = '';
	$html .= '<div class="alert '.$atts['type'].'">';
		$html .= '<div class="msg">'.do_shortcode($content).'</div>';
		$html .= '<a href="#" class="toggle-alert">Toggle</a>';
	$html .= '</div>';

	return $html;
}

//////////////////////////////////////////////////////////////////
// FontAwesome Icons
//////////////////////////////////////////////////////////////////
add_shortcode('fontawesome', 'shortcode_fontawesome');
function shortcode_fontawesome($atts, $content = null) {
	global $data;

	extract(shortcode_atts(array(
		'iconcolor' => '',
		'circlecolor' => '',
		'circlebordercolor' => '',
	), $atts));

	if(!$iconcolor) {
		$iconcolor = $data['icon_color'];
	}

	if(!$circlecolor) {
		$circlecolor = $data['icon_circle_color'];
	}

	if(!$circlebordercolor) {
		$circlebordercolor = $data['icon_border_color'];
	}

	$style = 'color:'.$iconcolor.' !important;';

	if($atts['circle'] == 'yes') {
		$style .= 'background-color:'.$circlecolor.' !important;border:1px solid '.$circlebordercolor.' !important;';
	}

	$html = '<i style="'.$style.'"class="fontawesome-icon '.$atts['size'].' circle-'.$atts['circle'].' icon-'.$atts['icon'].'"></i>';

	return $html;
}

//////////////////////////////////////////////////////////////////
// Social Links
//////////////////////////////////////////////////////////////////
add_shortcode('social_links', 'shortcode_social_links');
function shortcode_social_links($atts, $content = null) {
	global $data;

	extract(shortcode_atts(array(
		'colorscheme' => '',
		'linktarget' => '_self'
	), $atts));

	if(!$colorscheme) {
		$colorscheme = strtolower($data['social_links_color']);
	}

	$html = '<div class="social_links_shortcode clearfix">';
	$html .= '<ul class="social-networks social-networks-'.$colorscheme.' clearfix">';
	foreach($atts as $key => $link) {
		if($link && $key != 'linktarget' && $key != 'colorscheme') {
			$html .= '<li class="'.$key.'">
			<a class="'.$key.'" href="'.$link.'" target="'.$linktarget.'">'.ucwords($key).'</a>
			<div class="popup">
				<div class="holder">
					<p>'.ucwords($key).'</p>
				</div>
			</div>
			</li>';
		}
	}
	$html .= '</ul>';
	$html .= '</div>';

	return $html;
}

//////////////////////////////////////////////////////////////////
// Clients container
//////////////////////////////////////////////////////////////////
add_shortcode('clients', 'shortcode_clients');
function shortcode_clients($atts, $content = null) {
	wp_enqueue_script( 'jquery.carouFredSel' );

	$html = '<div class="related-posts related-projects"><div id="carousel" class="clients-carousel es-carousel-wrapper"><div class="es-carousel"><ul>';
	$html .= do_shortcode($content);
	$html .= '</ul><div class="es-nav"><span class="es-nav-prev">Previous</span><span class="es-nav-next">Next</span></div></div></div></div>';
	return $html;
}

//////////////////////////////////////////////////////////////////
// Client
//////////////////////////////////////////////////////////////////
add_shortcode('client', 'shortcode_client');
function shortcode_client($atts, $content = null) {
	extract(shortcode_atts(array(
		'linktarget' => '_self',
	), $atts));

	$html = '<li>';
	$html .= '<a href="'.$atts['link'].'" target="'.$linktarget.'"><img src="'.$atts['image'].'" alt="" /></a>';
	$html .= '</li>';
	return $html;
}

//////////////////////////////////////////////////////////////////
// Title
//////////////////////////////////////////////////////////////////
add_shortcode('title', 'shortcode_title');
function shortcode_title($atts, $content = null) {

	$html = '<div class="title"><h'.$atts['size'].'>'.do_shortcode($content).'</h'.$atts['size'].'><div class="title-sep-container"><div class="title-sep"></div></div></div>';
	
	return $html;
}

//////////////////////////////////////////////////////////////////
// Separator
//////////////////////////////////////////////////////////////////
add_shortcode('separator', 'shortcode_separator');
function shortcode_separator($atts, $content = null) {
	extract(shortcode_atts(array(
		'style' => 'none',
	), $atts));
	$html = '';
	$css = '';
	if($style != 'none') {
		$css = 'margin-bottom:'.$atts['top'].'px';
	}
	$html .= '<div class="demo-sep sep-'.$style.'" style="margin-top:'.$atts['top'].'px;'.$css.'"></div>';
	return $html;
}

//////////////////////////////////////////////////////////////////
// Tooltip
//////////////////////////////////////////////////////////////////
add_shortcode('tooltip', 'shortcode_tooltip');
function shortcode_tooltip($atts, $content = null) {
	extract(shortcode_atts(array(
		'title' => 'none',
	), $atts));

	$html = '<span class="tooltip-shortcode">';
	$html .= $content;
	$html .= '<span class="popup">
		<span class="holder">
			<span>'.$title.'</span>
		</span>
	</span>';
	$html .= '</span>';

	return $html;
}

//////////////////////////////////////////////////////////////////
// Full Width
//////////////////////////////////////////////////////////////////
add_shortcode('fullwidth', 'shortcode_fullwidth');
function shortcode_fullwidth($atts, $content = null) {
	extract(shortcode_atts(array(
		'backgroundcolor' => '',
		'backgroundimage' => '',
		'backgroundrepeat' => 'no-repeat',
		'backgroundposition' => 'top left',
		'backgroundattachment' => 'scroll',
		'bordersize' => '1px',
		'bordercolor' => '',
		'paddingtop' => '20px',
		'paddingbottom' => '20px'
	), $atts));

	$css = '';
	if($backgroundrepeat == 'no-repeat') {
		$css .= '-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;';
	}

	$html = '<div class="fullwidth-box" style="background-color:'.$backgroundcolor.';background-image:url('.$backgroundimage.');background-repeat:'.$backgroundrepeat.';background-position:'.$backgroundposition.';background-attachment:'.$backgroundattachment.';border-top:'.$bordersize.' solid '.$bordercolor.';border-bottom:'.$bordersize.' solid '.$bordercolor.';padding-top:'.$paddingtop.';padding-bottom:'.$paddingbottom.';'.$css.'">';
	$html .= '<div class="avada-row">';
	$html .= do_shortcode($content);
	$html .= '</div>';
	$html .= '</div>';

	return $html;
}

//////////////////////////////////////////////////////////////////
// Google Map
//////////////////////////////////////////////////////////////////
add_shortcode('map', 'shortcode_google_map');
function shortcode_google_map($atts, $content = null) {
	wp_enqueue_script( 'gmaps.api' );
	wp_enqueue_script( 'jquery.ui.map' );

	extract(shortcode_atts(array(
		'address' => '',
		'type' => 'satellite',
		'width' => '100%',
		'height' => '300px',
		'zoom' => '14',
		'scrollwheel' => 'true',
		'scale' => 'true',
		'zoom_pancontrol' => 'true',
	), $atts));

	static $avada_map_counter = 1;

	$addresses = explode('|', $address);

	$markers = '';
	foreach($addresses as $address_string) {
		$markers .= "{
			address: '{$address_string}',
			html: {
				content: '{$address_string}',
				popup: true
			} 
		},";	
	}

	$html = "<script type='text/javascript'>
	jQuery(document).ready(function($) {
		jQuery('#gmap-{$avada_map_counter}').goMap({
			address: '{$addresses[0]}',
			zoom: {$zoom},
			scrollwheel: {$scrollwheel},
			scaleControl: {$scale},
			navigationControl: {$zoom_pancontrol},
			maptype: '{$type}',
	        markers: [{$markers}]
		});
	});
	</script>";

	$html .= '<div class="shortcode-map" id="gmap-'.$avada_map_counter.'" style="width:'.$width.';height:'.$height.';">';
	$html .= '</div>';

	$avada_map_counter++;

	return $html;
}

//////////////////////////////////////////////////////////////////
// Counters (Circle)
//////////////////////////////////////////////////////////////////
add_shortcode('counters_circle', 'shortcode_counters_circle');
function shortcode_counters_circle($atts, $content = null) {
	$html = '<div class="counters-circle clearfix">';
	$html .= do_shortcode($content);
	$html .= '</div>';

	return $html;
}

//////////////////////////////////////////////////////////////////
// Counter (Circle)
//////////////////////////////////////////////////////////////////
add_shortcode('counter_circle', 'shortcode_counter_circle');
function shortcode_counter_circle($atts, $content = null) {
	global $data;

	wp_enqueue_script( 'jquery.waypoint' );
	wp_enqueue_script( 'jquery.gauge' );
	
	extract(shortcode_atts(array(
		'filledcolor' => '',
		'unfilledcolor' => '',
		'value' => '70'
	), $atts));

	if(!$filledcolor) {
		$filledcolor = $data['counter_filled_color'];
	}

	if(!$unfilledcolor) {
		$unfilledcolor = $data['counter_unfilled_color'];
	}

	static $avada_counter_circle = 1;

	$html = "<script type='text/javascript'>
	jQuery(document).ready(function() {
		var opts = {
		  lines: 12, // The number of lines to draw
		  angle: 0.5, // The length of each line
		  lineWidth: 0.05, // The line thickness
		  colorStart: '{$filledcolor}',   // Colors
		  colorStop: '{$filledcolor}',    // just experiment with them
		  strokeColor: '{$unfilledcolor}',   // to see which ones work best for you
		  generateGradient: false
		};
		var gauge_{$avada_counter_circle} = new Donut(document.getElementById('counter-circle-{$avada_counter_circle}')).setOptions(opts); // create sexy gauge!
		gauge_{$avada_counter_circle}.maxValue = 100; // set max gauge value
		gauge_{$avada_counter_circle}.animationSpeed = 128; // set animation speed (32 is default value)
		gauge_{$avada_counter_circle}.set({$value}); // set actual value
	});
	</script>";

	$html .= '<div class="counter-circle-wrapper">';
	$html .= '<canvas width="220" height="220" class="counter-circle" id="counter-circle-'.$avada_counter_circle.'">';
	$html .= '</canvas>';
	$html .= '<div class="counter-circle-content">';
	$html .= do_shortcode($content);
	$html .= '</div>';
	$html .= '</div>';

	$avada_counter_circle++;

	return $html;
}

//////////////////////////////////////////////////////////////////
// Counters Box
//////////////////////////////////////////////////////////////////
add_shortcode('counters_box', 'shortcode_counters_box');
function shortcode_counters_box($atts, $content = null) {
	$html = '<div class="counters-box">';
	$html .= do_shortcode($content);
	$html .= '</div>';

	return $html;
}

//////////////////////////////////////////////////////////////////
// Counter Box
//////////////////////////////////////////////////////////////////
add_shortcode('counter_box', 'shortcode_counter_box');
function shortcode_counter_box($atts, $content = null) {
	extract(shortcode_atts(array(
		'value' => '70'
	), $atts));

	$html = '';
	$html .= '<div class="counter-box-wrapper">';
	$html .= '<div class="content-box-percentage">';
	$html .= '<span class="display-percentage" data-percentage="'.$value.'">0</span><span class="percent">%</span>';
	$html .= '</div>';
	$html .= '<div class="counter-box-content">';
	$html .= do_shortcode($content);
	$html .= '</div>';
	$html .= '</div>';

	return $html;
}

//////////////////////////////////////////////////////////////////
// Flexslider
//////////////////////////////////////////////////////////////////
add_shortcode('flexslider', 'shortcode_flexslider');
function shortcode_flexslider($atts, $content = null) {
	extract(shortcode_atts(array(
		'layout' => 'posts',
		'excerpt' => '25',
		'category' => '',
		'limit' => '3',
		'id' => ''
	), $atts));

	$shortcode = '';

	if($layout == 'posts') {
		if($id) {
			$shortcode .= 'id="'.$id.'"';
		}
		if($category) {
			$shortcode .= ' category="'.$category.'"';
		}
		$html = do_shortcode('[wooslider slider_type="posts" layout="text-bottom" overlay="natural" link_title="true" display_excerpt="false" limit="'.$limit.'" excerpt="'.$excerpt.'" '.$shortcode.']');
	} elseif($layout == 'posts-with-excerpt') {
		if($id) {
			$shortcode .= 'id="'.$id.'"';
		}
		if($category) {
			$shortcode .= ' category="'.$category.'"';
		}
		$html = do_shortcode('[wooslider slider_type="posts" layout="text-left" overlay="full" link_title="true" display_excerpt="true" limit="'.$limit.'" excerpt="'.$excerpt.'" '.$shortcode.']');
	} else {
		if($id) {
			$shortcode .= 'id="'.$id.'"';
		}
		$html = do_shortcode('[wooslider slider_type="attachments" thumbnails="true" '.$shortcode.']');
	}

	return $html;
}

//////////////////////////////////////////////////////////////////
// Blog Shortcode. Credits: media-lounge.at
//////////////////////////////////////////////////////////////////
function blog_shortcode($atts) {
	global $data;

	wp_enqueue_script( 'jquery.flexslider' );

	if((is_front_page() || is_home() ) ) {
		$paged = (get_query_var('paged')) ?get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
	} else {
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	}

	// if a number of posts should be displayed using their id; got to build array
	if (isset($atts['post__in'])) {
		$atts['post__in'] = array_map( 'trim', explode( ',', $atts['post__in'] ) );
	}

	// only get the specified number of posts
	if (isset($atts['get_latest'] )) {
		$get_latest = $atts['get_latest'];

		$atts = shortcode_atts( array(
				'author'              => '',
				'author_name'		  => '',
				'category_name'       => '',
				'category'      	  => '',
				'id'                  => false,
				'p'					  => false,
				'post__in'			  => false,
				'order'               => 'DESC',
				'orderby'             => 'date',
				'post_status'         => 'publish',
				'post_type'           => 'post',
				'posts_per_page'	  => $atts['get_latest'],
				'nopaging'			  => "false",
				'paged'				  => $paged,
				'tag'                 => '',
				'tax_operator'        => 'IN',
				'tax_term'            => false,
				'taxonomy'            => 'category',
				'title_meta'		  => '',
				'include_shortcodes'  => false,
				'blog_layout' => 'large'
			), $atts );

	// get specified number of posts per page
	} else 	if (isset($atts['posts_per_page'])) {

		$atts = shortcode_atts( array(
				'author'              => '',
				'author_name'		  => '',
				'category_name'       => '',
				'category'      	  => '',
				'id'                  => false,
				'p'					  => false,
				'post__in'			  => false,
				'order'               => 'DESC',
				'orderby'             => 'date',
				'post_status'         => 'publish',
				'post_type'           => 'post',
				'posts_per_page'	  => $atts['posts_per_page'],
				'nopaging'			  => "true",
				'paged'				  => $paged,
				'tag'                 => '',
				'tax_operator'        => 'IN',
				'tax_term'            => false,
				'taxonomy'            => 'category',
				'title_meta'		  => '',
				'include_shortcodes'  => false,
				'blog_layout' => 'large'
			), $atts );

	} else {
		// get all posts, i.e. default
		$atts = shortcode_atts( array(
				'author'              => '',
				'author_name'		  => '',
				'category_name'       => '',
				'category'      	  => '',
				'id'                  => false,
				'p'					  => false,
				'post__in'			  => false,
				'order'               => 'DESC',
				'orderby'             => 'date',
				'post_status'         => 'publish',
				'post_type'           => 'post',
				'nopaging'			  => "false",
				'paged'				  => $paged,
				'tag'                 => '',
				'tax_operator'        => 'IN',
				'tax_term'            => false,
				'taxonomy'            => 'category',
				'title_meta'		  => false,
				'include_shortcodes'  => false,
				'blog_layout' => 'large'
			), $atts );
	}

	if($atts['nopaging'] == 'true') {
		$atts['nopaging'] = true;
	} elseif($atts['nopaging'] == 'false') {
		$atts['nopaging'] = false;
	}

	$ml_query = new WP_Query($atts);

	$html = '</div></div>';

	 while( $ml_query->have_posts() ) :
	 	$ml_query->the_post();

		// get the css styling classes out of the get_post_class() array
		$post_classes_values = 'class="';
		$post_classes = get_post_class();
		foreach ($post_classes as $key => $value) {
			if (strlen($post_classes_values) == 7) {
				$post_classes_values .= $value;
			} else {
				$post_classes_values .= ' '.$value;
			}
		}
		$post_classes_values .= '"';

		$html .= '<div id="post-' .get_the_ID().'"' .$post_classes_values .'>';
		if (!isset($atts['title_meta']) || $atts['title_meta'] == false) {
			if($data['featured_images']) {
				$html .= '<style type="text/css">';

				if(get_post_meta(get_the_ID(), 'pyre_fimg_width', true)) {
					$html.= '#post-' .get_the_ID() .' .post-slideshow,
							#post-' .get_the_ID() .' .floated-post-slideshow,
							#post-' .get_the_ID() .' .post-slideshow .image > img,
							#post-' .get_the_ID() .' .floated-post-slideshow .image > img
							{width:' .get_post_meta(get_the_ID(), 'pyre_fimg_width', true) .' !important;}
					';
				}

				if(get_post_meta(get_the_ID(), 'pyre_fimg_height', true)) {
					$html.= '#post-' .get_the_ID() .' .post-slideshow,
							#post-' .get_the_ID() .' .floated-post-slideshow,
							#post-' .get_the_ID() .' .post-slideshow .image > img,
							#post-' .get_the_ID() .' .floated-post-slideshow .image > img
							{height:' .get_post_meta(get_the_ID(), 'pyre_fimg_height', true) .' !important;}
					';
				}
				$html.= '</style>';


				if(get_post_meta(get_the_ID(), 'pyre_image_rollover_icons', true) == 'link') {
					$link_icon_css = '';
					$zoom_icon_css = 'display:none;';
				} elseif(get_post_meta(get_the_ID(), 'pyre_image_rollover_icons', true) == 'zoom') {
					$link_icon_css = 'display:none;';
					$zoom_icon_css = '';
				} elseif(get_post_meta(get_the_ID(), 'pyre_image_rollover_icons', true) == 'no') {
					$link_icon_css = 'display:none;';
					$zoom_icon_css = 'display:none;';
				} else {
					$link_icon_css = '';
					$zoom_icon_css = '';
				}

				$icon_url_check = get_post_meta(get_the_ID(), 'pyre_link_icon_url', true); if(!empty($icon_url_check)) {
					$icon_permalink = get_post_meta(get_the_ID(), 'pyre_link_icon_url', true);
				} else {
					$icon_permalink = get_permalink(get_the_ID());
				}

				if($data['blog_full_width']) {
					$size = 'full';
				} else {
					$size = 'blog-large';
				}

				if($atts['blog_layout'] == 'large') {

					if(has_post_thumbnail() || get_post_meta(get_the_ID(), 'pyre_video', true)) {

						$html .= '<div class="flexslider post-slideshow">
							<ul class="slides">';
								if(get_post_meta(get_the_ID(), 'pyre_video', true)) {
									$html .= '<li class="full-video">'
										.get_post_meta(get_the_ID(), 'pyre_video', true)
									.'</li>';
								}

								if(has_post_thumbnail(get_the_ID())) {
									$full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
									$attachment_data = wp_get_attachment_metadata(get_post_thumbnail_id());
									$html .= '<li>
										<div class="image">';
											if($data['image_rollover']) {
												$html .= get_the_post_thumbnail(get_the_ID(), $size);
											} else {
												$html .= '<a href="' . get_permalink() .'">' .get_the_post_thumbnail(get_the_ID(), $size) .'</a>';
											}
												$html .= '<div class="image-extras">
													<div class="image-extras-content">';
														$full_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
														$html .= '<a style="'.$link_icon_css.'" class="icon link-icon" href="' . $icon_permalink .'">Permalink</a>';

														if(get_post_meta(get_the_ID(), 'pyre_video_url', true)) {
															$full_image[0] = get_post_meta(get_the_ID(), 'pyre_video_url', true);
														}
														$html .= '<a style="'.$zoom_icon_css.'" class="icon gallery-icon" href="' .$full_image[0] .'" rel="prettyPhoto[gallery' .get_the_ID() .']" title="'. get_post_field('post_content', get_post_thumbnail_id()). '"><img style="display:none;" alt="' .get_post_field('post_excerpt', get_post_thumbnail_id()) .'" />Gallery</a>
														<h3>' .get_the_title() .'</h3>
													</div>
												</div>
										</div>
									</li>';
								}
								if($data['posts_slideshow']) {
									$i = 2;
									while($i <= $data["posts_slideshow_number"]):
										$attachment_id = kd_mfi_get_featured_image_id('featured-image-'.$i, 'post');
										if($attachment_id) {
											$attachment_image = wp_get_attachment_image_src($attachment_id, $size);
											$full_image = wp_get_attachment_image_src($attachment_id, 'full');
											$attachment_data = wp_get_attachment_metadata($attachment_id);
											$html .= '<li>
												<div class="image">
													<a href="' . get_permalink() .'"><img src="' .$attachment_image[0] .'" alt="' .get_post_field('post_excerpt', $new_attachment_id).'" /></a>
													<a style="display:none;" href="' .$full_image[0] .'" rel="prettyPhoto[gallery' .get_the_ID() .']" alt="' .get_post_field('post_excerpt', $attachment_id) .'" title="' .get_post_field('post_content', $attachment_id) .'"><img style="display:none;" alt="' . get_post_field('post_excerpt', $attachment_id) .'" /></a>
												</div>
											</li>';
										}
										$i++;
									endwhile;
								}
							$html .= '</ul>
						</div>';
					}
				}

				if($atts['blog_layout'] == 'medium') {
					if(has_post_thumbnail() || get_post_meta(get_the_ID(), 'pyre_video', true)) {

						$html .= '<div class="flexslider blog-medium-image floated-post-slideshow">
							<ul class="slides">';
								if(get_post_meta(get_the_ID(), 'pyre_video', true)) {
									$html .= '<li class="full-video">'
										.get_post_meta(get_the_ID(), 'pyre_video', true)
									.'</li>';
								}
								if(has_post_thumbnail(get_the_ID())) {
									$full_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
									$attachment_data = wp_get_attachment_metadata(get_post_thumbnail_id(get_the_ID()));
									$html .= '<li>
										<div class="image">
												<a href="' .get_permalink() .'">' .get_the_post_thumbnail(get_the_ID(), "blog-medium"). '</a>
												<div class="image-extras">
													<div class="image-extras-content">';
														$full_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
														$html .= '<a style="'.$link_icon_css.'" class="icon link-icon" href="' . $icon_permalink .'">Permalink</a>';
														if(get_post_meta(get_the_ID(), 'pyre_video_url', true)) {
															$full_image[0] = get_post_meta(get_the_ID(), 'pyre_video_url', true);
														}
														$html .= '<a style="'.$zoom_icon_css.'" class="icon gallery-icon" href="' .$full_image[0] .'" rel="prettyPhoto[gallery' .get_the_ID() .']">Gallery</a>
														<h3>' .get_the_title() .'</h3>
													</div>
												</div>
										</div>
									</li>';
								}

								if($data['posts_slideshow']) {
									$i = 2;
									while($i <= $data['posts_slideshow_number']):
										$new_attachment_id = kd_mfi_get_featured_image_id('featured-image-'.$i, 'post', get_the_ID());
										if($new_attachment_id) {

											$attachment_image = wp_get_attachment_image_src($new_attachment_id, 'blog-medium');
											$full_image = wp_get_attachment_image_src($new_attachment_id, 'full');
											$attachment_data = wp_get_attachment_metadata($new_attachment_id);

											$html .= '<li>
												<div class="image">
													<a href="'. get_permalink() .'"><img src="' . $attachment_image[0] .'" alt="' .get_post_field('post_excerpt', $new_attachment_id).'" /></a>
													<a style="display:none;" href="' . $full_image[0] .'" rel="prettyPhoto[gallery' .get_the_ID().']" alt="' .get_post_field('post_excerpt', $new_attachment_id) .'" title="' .get_post_field('post_content', $new_attachment_id) .'"><img style="display:none;" alt="' .get_post_field('post_excerpt', $new_attachment_id) .'" /></a>

												</div>
											</li>';
										}
										$i++;
									endwhile;
								}

							$html .= '</ul>
						</div>';
					}
				}
			}


			$html .= '<h2><a href="' .get_permalink(). '">' .get_the_title(). '</a></h2>
			<div class="post-content">
			';

			if($data['content_length'] == 'Excerpt') {
				// content of shortcodes should be displayed
				if (isset($atts['include_shortcodes']) && $atts['include_shortcodes'] == true) {
					$html .= custom_excerpt();
				} else {
					// content of shortcodes will be cut out, i.e. standard
					$stripped_content = tf_content( $data['excerpt_length_blog'], $data['strip_html_excerpt'] );
					$html .= $stripped_content;
				}
			} else {
				$html .= get_the_content('');
			}

			$html .= '</div><div class="clearboth"></div>';
			} else {
				$html .= '<h2><a href="' .get_permalink(). '">' .get_the_title(). '</a></h2>';
			}
			if($data['post_meta']) {
				$html .=
				'<div class="meta-info">
					<div class="alignleft">'
						. __('By', 'Avada') .' ';
						ob_start();
						the_author_posts_link();
						$html .= ob_get_clean()
						.'<span class="sep">|</span>' .get_the_time($data['date_format']) .'<span class="sep">|</span>';
						ob_start();
						the_category(', ');
						$html .= ob_get_clean();
						$html .= '<span class="sep">|</span>';
						ob_start();
						comments_popup_link(__('0 Comments', 'Avada'), __('1 Comment', 'Avada'), '% '.__('Comments', 'Avada'));
						$html .= ob_get_clean()
					.'</div>
					<div class="alignright">
						<a href="' .get_permalink() .'" class="read-more">' . __('Read More', 'Avada'). '</a>
					</div>
				</div>';
			}
		$html .= '</div>';
	endwhile;
	$html .= '<div><div>';

	//no paging if only the latest posts are shown
	if (isset($get_latest) && $get_latest > 0) {
		wp_reset_query();
		return $html;
	} else {
		$html .= ml_blog_pagination($ml_query, $pages = '', $range = 2);
		wp_reset_query();
		return $html;
	}
}
add_shortcode( 'blog', 'blog_shortcode' );

if(!function_exists('ml_blog_pagination')):
function ml_blog_pagination($ml_query, $pages = '', $range = 2)
{
	$html = '';
	
    $showitems = ($range * 2)+1;

	if((is_front_page() || is_home() ) ) {
		$paged = (get_query_var('paged')) ?get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
	} else {
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	}

     if($pages == '') {
         global $wp_query;
         $pages = $ml_query->max_num_pages;
         if(!$pages) {
             $pages = 1;
         }
     }

     if(1 != $pages) {
         $html .= '<div class="pagination clearfix">';
         if($paged > 1) $html .= '<a class="pagination-prev" href="'.get_pagenum_link($paged - 1).'"><span class="page-prev"></span>'.__('Previous', 'Avada').'</a>';

         for ($i=1; $i <= $pages; $i++) {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) {
                if ($paged == $i) {
                	$html .= '<span class="current">'.$i.'</span>';
                } else {
                	$html .= '<a href="'.get_pagenum_link($i).'" class="inactive" >'.$i.'</a>';
                }
             }
         }

         if ($paged < $pages) $html .= '<a class="pagination-next" href="'.get_pagenum_link($paged + 1).'">'.__('Next', 'Avada').'<span class="page-next"></span></a>';
         $html .= '</div>';
     }
	return $html;
}
endif;

//////////////////////////////////////////////////////////////////
// Image Frame
//////////////////////////////////////////////////////////////////
add_shortcode('imageframe', 'shortcode_imageframe');
function shortcode_imageframe($atts, $content = null) {
	global $data;

	extract(shortcode_atts(array(
		'style' => 'border',
		'bordercolor' => '',
		'bordersize' => '4px',
		'stylecolor' => '',
		'align' => ''
	), $atts));

	static $avada_imageframe_id = 1;

	if(!$bordercolor) {
		$bordercolor = $data['imgframe_border_color'];
	}


	if(!$stylecolor) {
		$stylecolor = $data['imgframe_style_color'];
	}

	$getRgb = avada_hex2rgb($stylecolor);

	$html = "<style type='text/css'>
	#imageframe-{$avada_imageframe_id}.imageframe{float:{$align};}
	#imageframe-{$avada_imageframe_id}.imageframe img{border:{$bordersize} solid {$bordercolor};}
	#imageframe-{$avada_imageframe_id}.imageframe-glow img{
		-moz-box-shadow: 0 0 3px rgba({$getRgb[0]},{$getRgb[1]},{$getRgb[2]},.3); /* outer glow */
		-webkit-box-shadow: 0 0 3px rgba({$getRgb[0]},{$getRgb[1]},{$getRgb[2]},.3); /* outer glow */
		box-shadow: 0 0 3px rgba({$getRgb[0]},{$getRgb[1]},{$getRgb[2]},.3); /* outer glow */
	}
	#imageframe-{$avada_imageframe_id}.imageframe-dropshadow img{
		-moz-box-shadow: 2px 3px 7px rgba({$getRgb[0]},{$getRgb[1]},{$getRgb[2]},.3); /* drop shadow */
		-webkit-box-shadow: 2px 3px 7px rgba({$getRgb[0]},{$getRgb[1]},{$getRgb[2]},.3); /* drop shadow */
		box-shadow: 2px 3px 7px rgba({$getRgb[0]},{$getRgb[1]},{$getRgb[2]},.3); /* drop shadow */
	}
	</style>
	";

	$html .= '<span id="imageframe-'.$avada_imageframe_id.'" class="imageframe imageframe-'.$style.'">';
	$html .= do_shortcode($content);
	if($style == 'bottomshadow') {
	$html .= '<span class="imageframe-shadow-left"></span><span class="imageframe-shadow-right"></span>';
	}
	$html .= '</span>';

	$avada_imageframe_id++;

	return $html;
}

//////////////////////////////////////////////////////////////////
// Images
//////////////////////////////////////////////////////////////////
add_shortcode('images', 'shortcode_avada_images');
function shortcode_avada_images($atts, $content = null) {
	wp_enqueue_script( 'jquery.carouFredSel' );

	$html = '<div class="related-posts related-projects"><div id="carousel" class="clients-carousel es-carousel-wrapper"><div class="es-carousel"><ul>';
	$html .= do_shortcode($content);
	$html .= '</ul><div class="es-nav"><span class="es-nav-prev">Previous</span><span class="es-nav-next">Next</span></div></div></div></div>';
	return $html;
}

//////////////////////////////////////////////////////////////////
// Image
//////////////////////////////////////////////////////////////////
add_shortcode('image', 'shortcode_avada_image');
function shortcode_avada_image($atts, $content = null) {
	$html = '<li>';
	$html .= '<a href="'.$atts['link'].'" target="'.$atts['linktarget'].'"><img src="'.$atts['image'].'" alt="" /></a>';
	$html .= '</li>';
	return $html;
}

//////////////////////////////////////////////////////////////////
// Social Sharing Box
//////////////////////////////////////////////////////////////////
add_shortcode('sharing', 'shortcode_sharing');
function shortcode_sharing($atts, $content = null) {
	global $data;

	extract(shortcode_atts(array(
		'tagline' => '',
		'title' => '',
		'link' => '',
		'description' => '',
		'backgroundcolor' => ''
	), $atts));

	if(!$backgroundcolor) {
		$backgroundcolor = $data['social_bg_color'];
	}

	$html = '<div class="share-box" style="background-color:'.$backgroundcolor.';">
		<h4>'.$tagline.'</h4>
		<ul class="social-networks social-networks-'.strtolower($data['socialbox_icons_color']).'">';
			if($data['sharing_facebook']):
			$html .= '<li class="facebook">
				<a href="http://www.facebook.com/sharer.php?u='.$link.'&amp;t='.$title.'">
					Facebook
				</a>
				<div class="popup">
					<div class="holder">
						<p>Facebook</p>
					</div>
				</div>
			</li>';
			endif;
			if($data['sharing_twitter']):
			$html .= '<li class="twitter">
				<a href="http://twitter.com/home?status='.$title.' '.$link.'">
					Twitter
				</a>
				<div class="popup">
					<div class="holder">
						<p>Twitter</p>
					</div>
				</div>
			</li>';
			endif;
			if($data['sharing_linkedin']):
			$html .= '<li class="linkedin">
				<a href="http://linkedin.com/shareArticle?mini=true&amp;url='.$link.'&amp;title='.$title.'">
					LinkedIn
				</a>
				<div class="popup">
					<div class="holder">
						<p>LinkedIn</p>
					</div>
				</div>
			</li>';
			endif;
			if($data['sharing_reddit']):
			$html .= '<li class="reddit">
				<a href="http://reddit.com/submit?url='.$link.'&amp;title='.$title.'">
					Reddit
				</a>
				<div class="popup">
					<div class="holder">
						<p>Reddit</p>
					</div>
				</div>
			</li>';
			endif;
			if($data['sharing_tumblr']):
			$html .= '<li class="tumblr">
				<a href="http://www.tumblr.com/share/link?url='.urlencode($link).'&amp;name='.urlencode($title).'&amp;description='.urlencode($description).'">
					Tumblr
				</a>
				<div class="popup">
					<div class="holder">
						<p>Tumblr</p>
					</div>
				</div>
			</li>';
			endif;
			if($data['sharing_google']):
			$html .= '<li class="google">
				<a href="http://google.com/bookmarks/mark?op=edit&amp;bkmk='.$link.'&amp;title='.$title.'">
					Google +1
				</a>
				<div class="popup">
					<div class="holder">
						<p>Google +1</p>
					</div>
				</div>
			</li>';
			endif;
			if($data['sharing_pinterest']):
			$html .= '<li class="pinterest">
				<a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode('.$link.'); ?>&amp;description=<?php echo urlencode('.$title.'); ?>">
					Pinterest
				</a>
				<div class="popup">
					<div class="holder">
						<p>Pinterest</p>
					</div>
				</div>
			</li>';
			endif;
			if($data['sharing_email']):
			$html .= '<li class="email">
				<a href="mailto:?subject='.$title.'&amp;body='.$link.'">
					Email
				</a>
				<div class="popup">
					<div class="holder">
						<p>Email</p>
					</div>
				</div>
			</li>';
			endif;
		$html .= '</ul>
	</div>';
	return $html;
}

//////////////////////////////////////////////////////////////////
// Add buttons to tinyMCE
//////////////////////////////////////////////////////////////////
add_action('init', 'add_button');

function add_button() {
	if(strstr($_SERVER['REQUEST_URI'], 'wp-admin/post-new.php') || strstr($_SERVER['REQUEST_URI'], 'wp-admin/post.php')) {
		if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') )  
		{  
			add_filter('mce_external_plugins', 'add_plugin');  
			add_filter('mce_buttons_2', 'register_button');  
		}
	}
}    

function register_button($buttons) {
   array_push($buttons, "tfbutton");  
   return $buttons;
}  

function add_plugin($plugin_array) {  
   $plugin_array['tfbutton'] = get_template_directory_uri().'/tinymce/customcodes.js';

   return $plugin_array;  
}