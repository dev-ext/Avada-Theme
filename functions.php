<?php
// Translation
load_theme_textdomain('Avada', TEMPLATEPATH.'/languages');

// Default RSS feed links
add_theme_support('automatic-feed-links');

// Allow shortcodes in widget text
add_filter('widget_text', 'do_shortcode');

// Register Navigation
register_nav_menu('main_navigation', 'Main Navigation');
register_nav_menu('top_navigation', 'Top Navigation');
register_nav_menu('404_pages', '404 Useful Pages');

// Content Width
if (!isset( $content_width )) $content_width = 1000;

/* Options Framework */
require_once(get_template_directory().'/admin/index.php');

// Post Formats
if($data['blog_layout'] == 'Large Alternate' || $data['blog_layout'] == 'Medium Alternate') {
	add_theme_support('post-formats', array('gallery', 'link', 'image', 'quote', 'video', 'audio', 'chat'));
}
// Auto plugin activation
if(get_option('avada_int_plugins', '0') == '0') {
	global $wpdb;
	$wpdb->query("UPDATE ". $wpdb->options ." SET option_value = 'a:0:{}' WHERE option_name = 'active_plugins';");
	$wpdb->query("UPDATE ". $wpdb->sitemeta ." SET meta_value = 'a:0:{}' WHERE meta_key = 'active_plugins';");
	update_option('avada_int_plugins', '1');
}

if(get_option('avada_int_plugins', '0') == '1') {
	/**************************/
	/* Include LayerSlider WP */
	/**************************/

	$layerslider = get_template_directory() . '/framework/plugins/LayerSlider/layerslider.php';

	if(!$data['status_layerslider']) {
		include $layerslider;
		
		$layerslider_last_version = get_option('avada_layerslider_last_version', '1.0');

		// Activate the plugin if necessary
		if(get_option('avada_layerslider_activated', '0') == '0') {
			// Run activation script
			layerslider_activation_scripts();

			// Save a flag that it is activated, so this won't run again
			update_option('avada_layerslider_activated', '1');

			// Save the current version number of LayerSlider
			update_option('avada_layerslider_last_version', $GLOBALS['lsPluginVersion']);

		// Do version check
		} else if(version_compare($GLOBALS['lsPluginVersion'], $layerslider_last_version, '>')) {
			// Run again activation scripts for possible adjustments
			layerslider_activation_scripts();

			// Update the version number
			update_option('avada_layerslider_last_version', $GLOBALS['lsPluginVersion']);
		}
	}

	/**************************/
	/* Include RevSlider WP */
	/**************************/

	$revslider = get_template_directory() . '/framework/plugins/revslider/revslider.php';
	if(!$data['status_revslider']) {
		include $revslider;

		// Activate the plugin if necessary
		if(get_option('avada_revslider_activated', '0') == '0') {
			if(!class_exists('RevSliderAdmin')) {
				$revslider_admin_script = get_template_directory() . '/framework/plugins/revslider/revslider_admin.php';
				include $revslider_admin_script;
			}

		    // Run activation script
		    $revslider_admin = new RevSliderAdmin($revslider);
		    $revslider_admin->onActivate();

		    // Save a flag that it is activated, so this won't run again
		    update_option('avada_revslider_activated', '1');
		}
	}

	/**************************/
	/* Include Flexslider WP */
	/**************************/

	$flexslider = get_template_directory() . '/framework/plugins/tf-flexslider/wooslider.php';
	if(!$data['status_flexslider']) {
		include $flexslider;
	}

	/**************************/
	/* Include Posts Type Order */
	/**************************/

	$pto = get_template_directory() . '/framework/plugins/post-types-order/post-types-order.php';
	if($data['post_type_order']) {
		include $pto;
	}

	/************************************************/
	/* Include Previous / Next Post Pagination Plus */
	/************************************************/
	$pnp = 	get_template_directory() . '/framework/plugins/ambrosite-post-link-plus.php';
	include $pnp;

	/***********************/
	/* Include WPML Fixes  */
	/***********************/
	if(defined('ICL_SITEPRESS_VERSION')) {
		$wpml_include = get_template_directory() . '/framework/plugins/wpml.php';
		include $wpml_include;
	}
}

// Double check if rev slider table exists
/*if(get_option('avada_revslider_activated', '0') == '1') {
	global $wpdb;
	$revslider_db_exists = $wpdb->get_results("SHOW TABLES LIKE '".$wpdb->prefix."revslider_slides'");
	if(!$revslider_db_exists) {
		if(!class_exists('RevSliderAdmin')) {
			$revslider_admin_script = get_template_directory() . '/framework/plugins/revslider/revslider_admin.php';
			include $revslider_admin_script;
		}

    	// Run activation script
    	$revslider_admin = new RevSliderAdmin($revslider);
    	$revslider_admin->onActivate();
	}

	$revslider_siteid_exists = $wpdb->get_results("SHOW COLUMNS FROM ".$wpdb->prefix."revslider_sliders LIKE 'siteid'");
	if(!$revslider_siteid_exists) {
		if(!class_exists('RevSliderAdmin')) {
			$revslider_admin_script = get_template_directory() . '/framework/plugins/revslider/revslider_admin.php';
			include $revslider_admin_script;
		}

    	// Run activation script
    	$revslider_admin = new RevSliderAdmin($revslider);
    	$revslider_admin->onActivate();

    	$wpdb->query("ALTER TABLE ".$wpdb->prefix."revslider_sliders ADD COLUMN siteid int");
	}
}*/


// Check for theme updates
/*if($data['tf_username'] && $data['tf_api']) {
	$envato = get_template_directory() . '/framework/plugins/envato-wordpress-toolkit-library/class-envato-wordpress-theme-upgrader.php';
	include $envato;
	$upgrader = new Envato_WordPress_Theme_Upgrader($data['tf_username'], $data['tf_api']);
	$check_upgrade = $upgrader->check_for_theme_update('Avada');
	var_dump($check_upgrade);
	if($check_upgrade->updated_themes_count && $data['tf_update']) {
		$upgrader->upgrade_theme();
	}
}*/

// Metaboxes
include_once(get_template_directory().'/framework/metaboxes.php');

// Extend Visual Composer
get_template_part('shortcodes');

// Custom Functions
get_template_part('framework/custom_functions');

// Plugins
include_once(get_template_directory().'/framework/plugins/multiple_sidebars.php');

// Widgets
get_template_part('widgets/widgets');

// Add post thumbnail functionality
add_theme_support('post-thumbnails');
add_image_size('blog-large', 669, 272, true);
add_image_size('blog-medium', 320, 202, true);
add_image_size('tabs-img', 52, 50, true);
add_image_size('related-img', 180, 138, true);
add_image_size('portfolio-one', 540, 272, true);
add_image_size('portfolio-two', 460, 295, true);
add_image_size('portfolio-three', 300, 214, true);
add_image_size('portfolio-four', 220, 161, true);
add_image_size('portfolio-full', 940, 400, true);
add_image_size('recent-posts', 660, 405, true);
add_image_size('recent-works-thumbnail', 66, 66, true);

// Register widgetized locations
if(function_exists('register_sidebar')) {
	register_sidebar(array(
		'name' => 'Blog Sidebar',
		'id' => 'avada-blog-sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<div class="heading"><h3>',
		'after_title' => '</h3></div>',
	));

	register_sidebar(array(
		'name' => 'Footer Widget 1',
		'id' => 'avada-footer-widget-1',
		'before_widget' => '<div id="%1$s" class="footer-widget-col %2$s">',
		'after_widget' => '<div style="clear:both;"></div></div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

	register_sidebar(array(
		'name' => 'Footer Widget 2',
		'id' => 'avada-footer-widget-2',
		'before_widget' => '<div id="%1$s" class="footer-widget-col %2$s">',
		'after_widget' => '<div style="clear:both;"></div></div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

	register_sidebar(array(
		'name' => 'Footer Widget 3',
		'id' => 'avada-footer-widget-3',
		'before_widget' => '<div id="%1$s" class="footer-widget-col %2$s">',
		'after_widget' => '<div style="clear:both;"></div></div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

	register_sidebar(array(
		'name' => 'Footer Widget 4',
		'id' => 'avada-footer-widget-4',
		'before_widget' => '<div id="%1$s" class="footer-widget-col %2$s">',
		'after_widget' => '<div style="clear:both;"></div></div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
}

// Register custom post types
add_action('init', 'pyre_init');
function pyre_init() {
	global $data;
	register_post_type(
		'avada_portfolio',
		array(
			'labels' => array(
				'name' => 'Portfolio',
				'singular_name' => 'Portfolio'
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => $data['portfolio_slug']),
			'supports' => array('title', 'editor', 'thumbnail','comments'),
			'can_export' => true,
		)
	);

	register_taxonomy('portfolio_category', 'avada_portfolio', array('hierarchical' => true, 'label' => 'Categories', 'query_var' => true, 'rewrite' => true));
	register_taxonomy('portfolio_skills', 'avada_portfolio', array('hierarchical' => true, 'label' => 'Skills', 'query_var' => true, 'rewrite' => true));

	register_post_type(
		'avada_faq',
		array(
			'labels' => array(
				'name' => 'FAQs',
				'singular_name' => 'FAQ'
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'faq-items'),
			'supports' => array('title', 'editor', 'thumbnail','comments'),
			'can_export' => true,
		)
	);

	register_taxonomy('faq_category', 'avada_faq', array('hierarchical' => true, 'label' => 'Categories', 'query_var' => true, 'rewrite' => true));

	register_post_type(
		'themefusion_elastic',
		array(
			'labels' => array(
				'name' => 'Elastic Slider',
				'singular_name' => 'Elastic Slide'
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'elastic-slide'),
			'supports' => array('title', 'thumbnail'),
			'can_export' => true,
			'menu_position' => 100,
		)
	);

	register_taxonomy('themefusion_es_groups', 'themefusion_elastic', array('hierarchical' => false, 'label' => 'Groups', 'query_var' => true, 'rewrite' => true));
}

// How comments are displayed
function avada_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<?php $add_below = ''; ?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
	
		<div class="the-comment">
			<div class="avatar">
				<?php echo get_avatar($comment, 54); ?>
			</div>
			
			<div class="comment-box">
			
				<div class="comment-author meta">
					<strong><?php echo get_comment_author_link() ?></strong>
					<?php printf(__('%1$s at %2$s', 'Avada'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__(' - Edit', 'Avada'),'  ','') ?><?php comment_reply_link(array_merge( $args, array('reply_text' => __(' - Reply', 'Avada'), 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
				</div>
			
				<div class="comment-text">
					<?php if ($comment->comment_approved == '0') : ?>
					<em><?php echo __('Your comment is awaiting moderation.', 'Avada') ?></em>
					<br />
					<?php endif; ?>
					<?php comment_text() ?>
				</div>
			
			</div>
			
		</div>

<?php }

/*function pyre_SearchFilter($query) {
	if ($query->is_search) {
		$query->set('post_type', 'post');
	}
	return $query;
}
add_filter('pre_get_posts','pyre_SearchFilter');*/

add_filter('wp_get_attachment_link', 'avada_pretty');
function avada_pretty($content) {
	$content = preg_replace("/<a/","<a rel=\"prettyPhoto[postimages]\"",$content,1);
	return $content;
}

require_once(get_template_directory().'/framework/plugins/multiple-featured-images/multiple-featured-images.php');

if( class_exists( 'kdMultipleFeaturedImages' )  && !$data['legacy_posts_slideshow']) {
		$i = 2;

		while($i <= $data['posts_slideshow_number']) {
	        $args = array(
	                'id' => 'featured-image-'.$i,
	                'post_type' => 'post',      // Set this to post or page
	                'labels' => array(
	                    'name'      => 'Featured image '.$i,
	                    'set'       => 'Set featured image '.$i,
	                    'remove'    => 'Remove featured image '.$i,
	                    'use'       => 'Use as featured image '.$i,
	                )
	        );

	        new kdMultipleFeaturedImages( $args );

	        $args = array(
	                'id' => 'featured-image-'.$i,
	                'post_type' => 'page',      // Set this to post or page
	                'labels' => array(
	                    'name'      => 'Featured image '.$i,
	                    'set'       => 'Set featured image '.$i,
	                    'remove'    => 'Remove featured image '.$i,
	                    'use'       => 'Use as featured image '.$i,
	                )
	        );

	        new kdMultipleFeaturedImages( $args );

	        $args = array(
	                'id' => 'featured-image-'.$i,
	                'post_type' => 'avada_portfolio',      // Set this to post or page
	                'labels' => array(
	                    'name'      => 'Featured image '.$i,
	                    'set'       => 'Set featured image '.$i,
	                    'remove'    => 'Remove featured image '.$i,
	                    'use'       => 'Use as featured image '.$i,
	                )
	        );

	        new kdMultipleFeaturedImages( $args );

	        $i++;
    	}

}

function avada_excerpt_length( $length ) {
	global $data;
	
	if(isset($data['excerpt_length_blog'])) {
		return $data['excerpt_length_blog'];
	}
}
add_filter('excerpt_length', 'avada_excerpt_length', 999);

function avada_admin_bar_render() {
	global $wp_admin_bar;
	$wp_admin_bar->add_menu( array(
		'parent' => 'site-name', // use 'false' for a root menu, or pass the ID of the parent menu
		'id' => 'smof_options', // link ID, defaults to a sanitized title value
		'title' => __('Theme Options', 'Avada'), // link title
		'href' => admin_url( 'themes.php?page=optionsframework'), // name of file
		'meta' => false // array of any of the following options: array( 'html' => '', 'class' => '', 'onclick' => '', target => '', title => '' );
	));
}
add_action( 'wp_before_admin_bar_render', 'avada_admin_bar_render' );

add_filter('upload_mimes', 'avada_filter_mime_types');
function avada_filter_mime_types($mimes)
{
	$mimes['ttf'] = 'font/ttf';
	$mimes['woff'] = 'font/woff';
	$mimes['svg'] = 'font/svg';
	$mimes['eot'] = 'font/eot';

	return $mimes;
}


function tf_content($limit, $strip_html) {
	global $data;

	$test_strip_html = $strip_html;

	if($strip_html == "true" || $strip_html == true) {
		$test_strip_html = true;
	}

	if($strip_html == "false" || $strip_html == false) {
		$test_strip_html = false;
	}

	if($test_strip_html) {
		$raw_content = strip_shortcodes( strip_tags( get_the_content() ) );
	} else {
		$raw_content = strip_shortcodes( get_the_content() );
	}

	if($raw_content) {
		$content = explode(' ', $raw_content, $limit);
		if (count($content)>=$limit) {
		array_pop($content);
		$content = implode(" ",$content).' &#91;...&#93;';
		} else {
		$content = implode(" ",$content);
		}	
		$content = preg_replace('/\[.+\]/','', $content);
		$content = apply_filters('the_content', $content); 
		$content = str_replace(']]>', ']]&gt;', $content);
		return $content;
	}
}



function avada_scripts() {
	if (!is_admin() && !in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) )) {
	wp_reset_query();

	global $data,$post;

	$slider_page_id = $post->ID;
	if(is_home() && !is_front_page()){
		$slider_page_id = get_option('page_for_posts');
	}

    wp_enqueue_script( 'jquery', false, array(), false, true);

    wp_deregister_script('ccgallery_modernizr');

    wp_deregister_script( 'modernizr' );
    wp_register_script( 'modernizr', get_bloginfo('template_directory').'/js/modernizr.js', array(), false, true);
	wp_enqueue_script( 'modernizr' );

    wp_deregister_script( 'jquery.carouFredSel' );
    wp_register_script( 'jquery.carouFredSel', get_bloginfo('template_directory').'/js/jquery.carouFredSel-6.2.1-packed.js', array(), false, true);
    //if(is_single()) {
		wp_enqueue_script( 'jquery.carouFredSel' );
    //}
    
    wp_deregister_script( 'jquery.prettyPhoto' );
    wp_register_script( 'jquery.prettyPhoto', get_bloginfo('template_directory').'/js/jquery.prettyPhoto.js', array(), false, true);
	wp_enqueue_script( 'jquery.prettyPhoto' );

    wp_deregister_script( 'jquery.isotope' );
    wp_register_script( 'jquery.isotope', get_bloginfo('template_directory').'/js/jquery.isotope.min.js', array(), false, true);
	/*if(
		is_page_template('portfolio-one-column.php') || is_page_template('portfolio-one-column-text.php') ||
		is_page_template('portfolio-two-column.php') || is_page_template('portfolio-two-column-text.php') ||
		is_page_template('portfolio-three-column.php') || is_page_template('portfolio-three-column-text.php') ||
		is_page_template('portfolio-four-column.php') || is_page_template('portfolio-four-column-text.php') ||
		(is_home() && $data['blog_layout'] == 'Grid') || is_page_template('demo-gridblog.php') ||
		is_page_template('demo-timelineblog.php')
	) {*/
		wp_enqueue_script( 'jquery.isotope' );
	//}

    wp_deregister_script( 'jquery.flexslider' );
    wp_register_script( 'jquery.flexslider', get_bloginfo('template_directory').'/js/jquery.flexslider-min.js', array(), false, true);
    //if(is_home() || is_single() || is_search() || is_archive() || get_post_meta($slider_page_id, 'pyre_slider_type', true) == 'flex2') {
		wp_enqueue_script( 'jquery.flexslider' );
	//}

    wp_deregister_script( 'jquery.cycle' );
    wp_register_script( 'jquery.cycle', get_bloginfo('template_directory').'/js/jquery.cycle.lite.js', array(), false, true);
	//wp_enqueue_script( 'jquery.cycle' );

    wp_deregister_script( 'jquery.fitvids' );
    wp_register_script( 'jquery.fitvids', get_bloginfo('template_directory').'/js/jquery.fitvids.js', array(), false, true);
	wp_enqueue_script( 'jquery.fitvids' );

    wp_deregister_script( 'jquery.hoverIntent' );
    wp_register_script( 'jquery.hoverIntent', get_bloginfo('template_directory').'/js/jquery.hoverIntent.minified.js', array(), false, true);
	wp_enqueue_script( 'jquery.hoverIntent' );

    wp_deregister_script( 'jquery.easing' );
    wp_register_script( 'jquery.easing', get_bloginfo('template_directory').'/js/jquery.easing.js', array(), false, false);
	//wp_enqueue_script( 'jquery.easing' );

    wp_deregister_script( 'jquery.eislideshow' );
    wp_register_script( 'jquery.eislideshow', get_bloginfo('template_directory').'/js/jquery.eislideshow.js', array(), false, true);
    //if(get_post_meta($slider_page_id, 'pyre_slider_type', true) == 'elastic') {
		wp_enqueue_script( 'jquery.eislideshow' );
	//}

    wp_deregister_script( 'froogaloop' );
    wp_register_script( 'froogaloop', get_bloginfo('template_directory').'/js/froogaloop.js', array(), false, true);
	wp_enqueue_script( 'froogaloop' );

    wp_deregister_script( 'jquery.placeholder' );
    wp_register_script( 'jquery.placeholder', get_bloginfo('template_directory').'/js/jquery.placeholder.js', array(), false, true);
	wp_enqueue_script( 'jquery.placeholder' );

    wp_deregister_script( 'jquery.waypoint' );
    wp_register_script( 'jquery.waypoint', get_bloginfo('template_directory').'/js/jquery.waypoint.js', array(), false, true);
	wp_enqueue_script( 'jquery.waypoint' );

	//wp_deregister_script('gmaps.api');
	//wp_register_script('gmaps.api', 'https://maps.google.com/maps/api/js?v=3.exp&amp;sensor=false&amp;language='.substr(get_locale(), 0, 2), array(), false, true);
	//if(is_page_template('contact.php') || is_page_template('contact-2.php')) {
		//wp_enqueue_script( 'gmaps.api' );
	//}

	wp_deregister_script( 'jquery.ui.map' );
	wp_register_script( 'jquery.ui.map', get_bloginfo('template_directory').'/js/gmap.js', array(), false, true);
	//if(is_page_template('contact.php') || is_page_template('contact-2.php')) {
		wp_enqueue_script( 'jquery.ui.map' );
	//}

	wp_deregister_script( 'jquery.gauge' );
	wp_register_script( 'jquery.gauge', get_bloginfo('template_directory').'/js/gauge.js', array(), false, true);
	wp_enqueue_script( 'jquery.gauge' );

	wp_deregister_script( 'jquery.ddslick.' );
	wp_register_script( 'jquery.ddslick', get_bloginfo('template_directory').'/js/jquery.ddslick.min.js', array(), false, true);
	wp_enqueue_script( 'jquery.ddslick' );

	//if($data['blog_pagination_type'] == 'Infinite Scroll' || is_page_template('demo-gridblog.php') || is_page_template('demo-timelineblog.php')) {
	    wp_deregister_script( 'jquery.infinitescroll' );
	    wp_register_script( 'jquery.infinitescroll', get_bloginfo('template_directory').'/js/jquery.infinitescroll.min.js', array(), false, true);
		wp_enqueue_script( 'jquery.infinitescroll' );
	//}
	
    wp_deregister_script( 'avada' );
    wp_register_script( 'avada', get_bloginfo('template_directory').'/js/main.js', array(), false, true);
	wp_enqueue_script( 'avada' );
	}
}
add_action('wp_enqueue_scripts', 'avada_scripts');

add_filter('jpeg_quality', 'avada_image_full_quality');
add_filter('wp_editor_set_quality', 'avada_image_full_quality');
function avada_image_full_quality($quality) {
    return 100;
}

add_filter('get_archives_link', 'avada_cat_count_span');
add_filter('wp_list_categories', 'avada_cat_count_span');
function avada_cat_count_span($links) {
	$get_count = preg_match_all('#\((.*?)\)#', $links, $matches);

	if($matches) {
		$i = 0;
		foreach($matches[0] as $val) {
			$links = str_replace('</a> '.$val, ' '.$val.'</a>', $links);
			$links = str_replace('</a>&nbsp;'.$val, ' '.$val.'</a>', $links);
			$i++;
		}
	}

	return $links;
}

remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');

add_filter('pre_get_posts','avada_SearchFilter');
function avada_SearchFilter($query) {
	global $data;
	if($query->is_search) {
		if($data['search_content'] == 'Only Posts') {
			$query->set('post_type', 'post');
		}

		if($data['search_content'] == 'Only Pages') {
			$query->set('post_type', 'page');
		}
	}
	return $query;
}

add_action('admin_head', 'avada_admin_css');
function avada_admin_css() {
	echo '<link rel="stylesheet" type="text/css" href="'.get_template_directory_uri().'/css/admin_shortcodes.css">';
}