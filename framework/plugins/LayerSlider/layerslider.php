<?php

/**
 * @package LayerSlider WP
 * @version 4.5.5
 */
/*

Plugin Name: LayerSlider WP
Plugin URI: http://codecanyon.net/user/kreatura/
Description: The Wordress Parallax Slider
Version: 4.5.5
Author: Kreatura Media
Author URI: http://kreaturamedia.com/
*/


/********************************************************/
/*                        Actions                       */
/********************************************************/

	$GLOBALS['lsPluginVersion'] = '4.5.5';
	$GLOBALS['lsPluginPath'] = get_template_directory_uri() . '/framework/plugins/LayerSlider/';
	$GLOBALS['lsAutoUpdateBox'] = false;
	$GLOBALS['lsRepoAPI'] = 'http://repo.kreatura.hu/';
	$GLOBALS['lsPluginSlug'] = basename(dirname(__FILE__));

	// Activation hook for creating the initial DB table
	register_activation_hook(__FILE__, 'layerslider_activation_scripts');

	// Run activation scripts when adding new sites to a multisite installation
	add_action('wpmu_new_blog', 'layerslider_new_site');

	// Register custom settings menu
	add_action('admin_menu', 'layerslider_settings_menu');

	// Auto update
	if(get_option('layerslider-validated', '0')) {
		add_filter('pre_set_site_transient_update_plugins', 'layerslider_check_for_plugin_update');
		add_filter('plugins_api', 'layerslider_plugin_api_call', 10, 3);
	}

	// Link content resources
	add_action('wp_enqueue_scripts', 'layerslider_enqueue_content_res');

	// Link admin resources
	add_action('admin_enqueue_scripts', 'layerslider_enqueue_admin_res');

	// AJAXs
	add_action('wp_ajax_layerslider_verify_purchase_code', 'layerslider_verify_purchase_code');

	// Add shortcode
	add_shortcode("layerslider","layerslider_init");

	// Widget action
	add_action( 'widgets_init', create_function( '', 'register_widget("LayerSlider_Widget");' ) );

	// Load plugin locale
	add_action('plugins_loaded', 'layerslider_load_lang');

	// Remove slider
	if(isset($_GET['page']) && $_GET['page'] == 'layerslider' && isset($_GET['action']) && $_GET['action'] == 'remove') {
		add_action('admin_init', 'layerslider_removeslider');
	}

	// Duplicate slider
	if(isset($_GET['page']) && $_GET['page'] == 'layerslider' && isset($_GET['action']) && $_GET['action'] == 'duplicate') {
		add_action('admin_init', 'layerslider_duplicateslider');
	}

	// Import sample sliders
	if(isset($_GET['page']) && $_GET['page'] == 'layerslider' && isset($_GET['action']) && $_GET['action'] == 'import_sample') {
		add_action('admin_init', 'layerslider_import_sample_slider');
	}

	// Convert data storage
	if(isset($_GET['page']) && $_GET['page'] == 'layerslider' && isset($_GET['action']) && $_GET['action'] == 'convert') {
		add_action('admin_init', 'layerslider_convert');
	}

	// Help menu
	add_filter('contextual_help', 'layerslider_help', 10, 3);

	// Storage notice
	if(get_option('layerslider-slides') != false) {

        // Get current page
        global $pagenow;

        // Plugins page
        if($pagenow == 'plugins.php' || $pagenow == 'index.php' || strstr($_SERVER['REQUEST_URI'], 'layerslider')) {

			add_action('admin_notices', 'layerslider_admin_notice');
		}
	}

/********************************************************/
/*                  LayerSlider locale                  */
/********************************************************/
function layerslider_load_lang() {
	load_plugin_textdomain('LayerSlider', false, basename(dirname(__FILE__)) . '/languages/' );
}

/********************************************************/
/*             CuteSlider activation scripts            */
/********************************************************/

function layerslider_activation_scripts() {

	// Multi-site
	if(is_multisite()) {

		// Get WPDB Object
		global $wpdb;

		// Get current site
		$old_site = $wpdb->blogid;

		// Get all sites
		$sites = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

		// Iterate over the sites
		foreach($sites as $site) {
			switch_to_blog($site);
			layerslider_create_db_table();
		}

		// Switch back the old site
		switch_to_blog($old_site);

	// Single-site
	} else {
		layerslider_create_db_table();
	}
}


/********************************************************/
/*            LayerSlider new site activation           */
/********************************************************/

function layerslider_new_site($blog_id) {

    // Get WPDB Object
    global $wpdb;

    // Get current site
	$old_site = $wpdb->blogid;

	// Switch to new site
	switch_to_blog($blog_id);

	// Run activation scripts
	layerslider_create_db_table();

	// Switch back the old site
	switch_to_blog($old_site);

}

/********************************************************/
/*           LayerSlider database table create          */
/********************************************************/

function layerslider_create_db_table() {

	// Get WPDB Object
	global $wpdb;

	// Table name
	$table_name = $wpdb->prefix . "layerslider";

	// Building the query
	$sql = "CREATE TABLE $table_name (
			  id int(10) NOT NULL AUTO_INCREMENT,
			  name varchar(100) NOT NULL,
			  data mediumtext NOT NULL,
			  date_c int(10) NOT NULL,
			  date_m int(11) NOT NULL,
			  flag_hidden tinyint(1) NOT NULL DEFAULT 0,
			  flag_deleted tinyint(1) NOT NULL DEFAULT 0,
			  PRIMARY KEY  (id)
			);";

	// Executing the query
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	// Execute the query
	dbDelta($sql);
}

/********************************************************/
/*                 Check purchase code                  */
/********************************************************/
function layerslider_verify_purchase_code() {

	// Build URL
	$url = 'http://activate.kreatura.hu?';
	$url.= 'plugin='.urlencode('LayerSlider WP').'&';
	$url.= 'code='.urlencode($_POST['purchase_code']);

	// Store purchase code
	update_option('layerslider-purchase-code', $_POST['purchase_code']);

	// Make the call
	$response = wp_remote_post($url);
	$response = $response['body'];

	// Check validation
	if($response == 'valid') {

		// Store validity
		update_option('layerslider-validated', '1');

		// Show message
		die(json_encode(array('success' => true, 'message' => __('Thank you for purchasing LayerSlider WP. You successfully validated your purchase code for auto-updates.', 'LayerSlider'))));

	} else {

		// Store validity
		update_option('layerslider-validated', '0');

		// Show message
		die(json_encode(array('success' => false, 'message' => __("Your purchase code doesn't appear to be valid. Please make sure that you entered your purchase code correctly.", "LayerSlider"))));
	}
}

/********************************************************/
/*               LayerSlider Auto-update                */
/********************************************************/

function layerslider_check_for_plugin_update($checked_data) {

	// Get WP version
	global  $wp_version;

	// Get purchase code
	$code = get_option('layerslider-purchase-code', '');

	//Comment out these two lines during testing.
	if (empty($checked_data->checked))
		return $checked_data;

	$args = array(
		'slug' => $GLOBALS['lsPluginSlug'],
		'version' => $checked_data->checked[$GLOBALS['lsPluginSlug'] .'/'. strtolower($GLOBALS['lsPluginSlug']) .'.php'],
	);

	$request_string = array(
			'body' => array(
				'action' => 'basic_check',
				'code' => $code,
				'request' => serialize($args),
				'api-key' => md5(get_bloginfo('url'))
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
		);

	// Start checking for an update
	$raw_response = wp_remote_post($GLOBALS['lsRepoAPI'], $request_string);

	if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200))
		$response = unserialize($raw_response['body']);

	if (is_object($response) && !empty($response)) // Feed the update data into WP updater
		$checked_data->response[$GLOBALS['lsPluginSlug'] .'/'. strtolower($GLOBALS['lsPluginSlug']) .'.php'] = $response;

	return $checked_data;
}

function layerslider_plugin_api_call($def, $action, $args) {

	// Get WP version
	global $wp_version;

	// Get purchase code
	$code = get_option('layerslider-purchase-code', '');

	if (!isset($args->slug) || ($args->slug != $GLOBALS['lsPluginSlug']))
		return false;

	// Get the current version
	$plugin_info = get_site_transient('update_plugins');
	$current_version = $plugin_info->checked[$GLOBALS['lsPluginSlug'] .'/'. strtolower($GLOBALS['lsPluginSlug']) .'.php'];
	$args->version = $current_version;

	$request_string = array(
			'body' => array(
				'action' => $action,
				'code' => $code,
				'request' => serialize($args),
				'api-key' => md5(get_bloginfo('url'))
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
		);

	$request = wp_remote_post($GLOBALS['lsRepoAPI'], $request_string);

	if (is_wp_error($request)) {
		$res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>', 'LayerSlider'), $request->get_error_message());
	} else {
		$res = unserialize($request['body']);

		if ($res === false)
			$res = new WP_Error('plugins_api_failed', __('An unknown error occurred', 'LayerSlider'), $request['body']);
	}

	return $res;
}


/********************************************************/
/*               Enqueue Content Scripts                */
/********************************************************/

	function layerslider_enqueue_content_res() {

		wp_enqueue_script('layerslider_js', $GLOBALS['lsPluginPath'].'js/layerslider.kreaturamedia.jquery.js', array('jquery'), $GLOBALS['lsPluginVersion'] );
		wp_enqueue_script('jquery_easing', $GLOBALS['lsPluginPath'].'js/jquery-easing-1.3.js', array('jquery'), '1.3.0' );
		wp_enqueue_script('transit', $GLOBALS['lsPluginPath'].'js/jquerytransit.js', array('jquery'), '0.9.9' );
		wp_enqueue_script('layerslider_transitions', $GLOBALS['lsPluginPath'].'js/layerslider.transitions.js', array(), $GLOBALS['lsPluginVersion'] );
		wp_enqueue_style('layerslider_css', $GLOBALS['lsPluginPath'].'css/layerslider.css', array(), $GLOBALS['lsPluginVersion'] );

		// Custom transitions
		$upload_dir = wp_upload_dir();
		$custom_trs = $upload_dir['basedir'] . '/layerslider.custom.transitions.js';
		$custom_trs_url = $upload_dir['baseurl'] . '/layerslider.custom.transitions.js';

		if(file_exists($custom_trs)) {
			wp_enqueue_script('layerslider_custom_transitions', $custom_trs_url, array(), $GLOBALS['lsPluginVersion'] );
		}

		// Custom CSS
		$custom_css = $upload_dir['basedir'] . '/layerslider.custom.css';
		$custom_css_url = $upload_dir['baseurl'] . '/layerslider.custom.css';

		if(file_exists($custom_css)) {
			wp_enqueue_style('layerslider_custom_css', $custom_css_url, array(), $GLOBALS['lsPluginVersion'] );
		}
	}


/********************************************************/
/*                Enqueue Admin Scripts                 */
/********************************************************/

	function layerslider_enqueue_admin_res() {

		// Get WP version
		global $wp_version;

		// Global
		wp_enqueue_style('layerslider_global_css', $GLOBALS['lsPluginPath'].'css/global.css', array(), $GLOBALS['lsPluginVersion'] );

		// LayerSlider
		if(isset($_GET['page']) && strstr($_GET['page'], 'layerslider') != false) {

			// New Media Library
			if(function_exists( 'wp_enqueue_media' )){
    			wp_enqueue_media();
    		}

			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');

			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('jquery-ui-draggable');

			// New color picker
			if(version_compare($wp_version, '3.5', '>=')) {
				wp_enqueue_script('wp-color-picker');
				wp_enqueue_style('wp-color-picker');
			}

			wp_enqueue_script('wp-pointer');
			wp_enqueue_style('wp-pointer');

			wp_enqueue_script('json2');

			wp_enqueue_script('layerslider_admin_js', $GLOBALS['lsPluginPath'].'js/admin.js', array('jquery'), $GLOBALS['lsPluginVersion'] );
			wp_enqueue_style('layerslider_admin_css', $GLOBALS['lsPluginPath'].'css/admin.css', array(), $GLOBALS['lsPluginVersion'] );

			wp_enqueue_script('layerslider_js', $GLOBALS['lsPluginPath'].'js/layerslider.kreaturamedia.jquery.js', array('jquery'), $GLOBALS['lsPluginVersion'] );
			wp_enqueue_script('layerslider_transitions', $GLOBALS['lsPluginPath'].'js/layerslider.transitions.js', array(), $GLOBALS['lsPluginVersion'] );
			wp_enqueue_script('jquery_easing', $GLOBALS['lsPluginPath'].'js/jquery-easing-1.3.js', array('jquery'), '1.3.0' );
			wp_enqueue_script('transit', $GLOBALS['lsPluginPath'].'js/jquerytransit.js', array('jquery'), '0.9.9' );
			wp_enqueue_script('layerslider_tr_gallery', $GLOBALS['lsPluginPath'].'js/layerslider.transitiongallery.js', array('jquery'), $GLOBALS['lsPluginVersion'] );
			wp_enqueue_style('layerslider_css', $GLOBALS['lsPluginPath'].'css/layerslider.css', array(), $GLOBALS['lsPluginVersion'] );
			wp_enqueue_style('layerslider_tr_gallery', $GLOBALS['lsPluginPath'].'css/layerslider.transitiongallery.css', array(), $GLOBALS['lsPluginVersion'] );

			// Custom transitions
			$upload_dir = wp_upload_dir();
			$custom_trs = $upload_dir['basedir'] . '/layerslider.custom.transitions.js';
			$custom_trs_url = $upload_dir['baseurl'] . '/layerslider.custom.transitions.js';

			if(file_exists($custom_trs)) {
				wp_enqueue_script('layerslider_custom_transitions', $custom_trs_url, array(), $GLOBALS['lsPluginVersion'] );
			}

			// Custom CSS
			$custom_css = $upload_dir['basedir'] . '/layerslider.custom.css';
			$custom_css_url = $upload_dir['baseurl'] . '/layerslider.custom.css';

			if(file_exists($custom_css)) {
				wp_enqueue_style('layerslider_custom_css', $custom_css_url, array(), $GLOBALS['lsPluginVersion'] );
			}
		}

		// Transition builder
		if(isset($_GET['page']) && strstr($_GET['page'], 'layerslider_transition_builder') != false) {
			wp_enqueue_script('layerslider_tr_builder', $GLOBALS['lsPluginPath'].'js/builder.js', array('jquery'), $GLOBALS['lsPluginVersion'] );
		}
	}

function layerslider_help($contextual_help, $screen_id, $screen) {

	if(strstr($_SERVER['REQUEST_URI'], 'layerslider')) {

		if(function_exists('file_get_contents')) {

			// List view
			if(isset($_GET['page']) && $_GET['page'] == 'layerslider' && !isset($_GET['action'])) {

				// Overview
				$screen->add_help_tab(array(
				   'id' => 'home_overview',
				   'title' => 'Overview',
				   'content' => file_get_contents(dirname(__FILE__).'/docs/home_overview.html')
				));

				// Managing sliders
				$screen->add_help_tab(array(
				   'id' => 'home_screen',
				   'title' => 'Managing sliders',
				   'content' => file_get_contents(dirname(__FILE__).'/docs/managing_sliders.html')
				));

				// Insert LayerSlider to your page
				$screen->add_help_tab(array(
				   'id' => 'inserting_slider',
				   'title' => 'Insert LayerSlider to your page',
				   'content' => file_get_contents(dirname(__FILE__).'/docs/inserting_slider.html')
				));

				// Export / Import
				$screen->add_help_tab(array(
				   'id' => 'exportimport',
				   'title' => 'Export / Import',
				   'content' => file_get_contents(dirname(__FILE__).'/docs/exportimport.html')
				));

				// Sample slider
				$screen->add_help_tab(array(
				   'id' => 'sample_slider',
				   'title' => 'Sample sliders',
				   'content' => file_get_contents(dirname(__FILE__).'/docs/sample_slider.html')
				));

			// Skin editor
			} elseif(strstr($_SERVER['REQUEST_URI'], 'layerslider_skin_editor')) {

				// Overview
				$screen->add_help_tab(array(
				   'id' => 'skin_overview',
				   'title' => 'Overview',
				   'content' => file_get_contents(dirname(__FILE__).'/docs/skin_overview.html')
				));

			// Styles editor
			} elseif(strstr($_SERVER['REQUEST_URI'], 'layerslider_style_editor')) {

				// Overview
				$screen->add_help_tab(array(
				   'id' => 'styles_overview',
				   'title' => 'Overview',
				   'content' => file_get_contents(dirname(__FILE__).'/docs/styles_overview.html')
				));

			// Transition builder
			} elseif(strstr($_SERVER['REQUEST_URI'], 'layerslider_transition_builder')) {

				// Overview
				$screen->add_help_tab(array(
				   'id' => 'transition_overview',
				   'title' => 'Overview',
				   'content' => file_get_contents(dirname(__FILE__).'/docs/transition_overview.html')
				));

				// Getting started
				$screen->add_help_tab(array(
				   'id' => 'transition_start',
				   'title' => 'Getting started',
				   'content' => file_get_contents(dirname(__FILE__).'/docs/transition_start.html')
				));

				// 3D transitions
				$screen->add_help_tab(array(
				   'id' => 'transition_3d',
				   'title' => '3D transitions',
				   'content' => file_get_contents(dirname(__FILE__).'/docs/transition_3d.html')
				));


				// Easings
				$screen->add_help_tab(array(
				   'id' => 'transition_easings',
				   'title' => 'Easings',
				   'content' => file_get_contents(dirname(__FILE__).'/docs/transition_easings.html')
				));

			// Editor view
			} else {

				// Overview
				$screen->add_help_tab(array(
				   'id' => 'overview',
				   'title' => 'Overview',
				   'content' => file_get_contents(dirname(__FILE__).'/docs/edit_overview.html')
				));

				// Getting started video
				$screen->add_help_tab(array(
				   'id' => 'gettingstarted',
				   'title' => 'Getting started',
				   'content' => file_get_contents(dirname(__FILE__).'/docs/gettingstarted.html')
				));


				// Insert LayerSlider to your page
				$screen->add_help_tab(array(
				   'id' => 'inserting_slider',
				   'title' => 'Insert LayerSlider to your page',
				   'content' => file_get_contents(dirname(__FILE__).'/docs/inserting_slider.html')
				));

				// Slider types
				$screen->add_help_tab(array(
				   'id' => 'slider_types',
				   'title' => 'Slider types',
				   'content' => file_get_contents(dirname(__FILE__).'/docs/slider_types.html')
				));

				// Slide options
				$screen->add_help_tab(array(
				   'id' => 'layer_options',
				   'title' => 'Slide options',
				   'content' => file_get_contents(dirname(__FILE__).'/docs/layer_options.html')
				));

				// Layer options
				$screen->add_help_tab(array(
				   'id' => 'sublayer_options',
				   'title' => 'Layer options',
				   'content' => file_get_contents(dirname(__FILE__).'/docs/sublayer_options.html')
				));

				// WYSIWYG Editor
				$screen->add_help_tab(array(
				   'id' => 'wysiwyg_editor',
				   'title' => 'WYSIWYG Editor',
				   'content' => file_get_contents(dirname(__FILE__).'/docs/wysiwyg_editor.html')
				));

				// Embedding videos
				$screen->add_help_tab(array(
				   'id' => 'embedding_videos',
				   'title' => 'Embedding videos',
				   'content' => file_get_contents(dirname(__FILE__).'/docs/embedding_videos.html')
				));

				// Other features
				$screen->add_help_tab(array(
				   'id' => 'other_features',
				   'title' => 'Other features',
				   'content' => file_get_contents(dirname(__FILE__).'/docs/other_features.html')
				));

				// Event callbacks
				$screen->add_help_tab(array(
				   'id' => 'event_callbacks',
				   'title' => 'Event callbacks',
				   'content' => file_get_contents(dirname(__FILE__).'/docs/event_callbacks.html')
				));

				// LayerSlider API
				$screen->add_help_tab(array(
				   'id' => 'layerslider_api',
				   'title' => 'LayerSlider API',
				   'content' => file_get_contents(dirname(__FILE__).'/docs/api.html')
				));
			}
		} else {

			// Error
			$screen->add_help_tab(array(
				'id' => 'error',
				'title' => 'Error',
				'content' => 'This help section couldn\'t show you the documentation because your server don\'t support the "file_get_contents" function'
			));
		}
	}
}

/********************************************************/
/*                 Loads settings menu                  */
/********************************************************/
function layerslider_settings_menu() {

	// Menu hook
	global $layerslider_hook;

	// Get custom capability
	$capability = get_option('layerslider_custom_capability', 'manage_options');

	// Create new top-level menu
	$layerslider_hook = add_menu_page('LayerSlider WP', 'LayerSlider WP', $capability, 'layerslider', 'layerslider_router', $GLOBALS['lsPluginPath'].'img/icon_16x16.png');

	// Add sub-menus
	add_submenu_page('layerslider', 'LayerSlider WP', __('All Sliders', 'LayerSlider'), $capability, 'layerslider', 'layerslider_router');
	add_submenu_page('layerslider', 'Add New LayerSlider', __('Add New', 'LayerSlider'), $capability, 'layerslider_add_new', 'layerslider_add_new');
	add_submenu_page('layerslider', 'LayerSlider WP Skin Editor', __('Skin Editor', 'LayerSlider'), $capability, 'layerslider_skin_editor', 'layerslider_skin_editor');
	add_submenu_page('layerslider', 'LayerSlider WP Transition Builder', __('Transition Builder', 'LayerSlider'), $capability, 'layerslider_transition_builder', 'layerslider_transition_builder');
	add_submenu_page('layerslider', 'LayerSlider WP Custom Styles Editor', __('Custom Styles Editor', 'LayerSlider'), $capability, 'layerslider_style_editor', 'layerslider_style_editor');

	// Call register settings function
	add_action( 'admin_init', 'layerslider_register_settings' );
}

/********************************************************/
/*                    Settings page                     */
/********************************************************/
function layerslider_router() {

	// Plugin activatation
	if(isset($_GET['action']) && $_GET['action'] == 'add') {
		include(dirname(__FILE__).'/add.php');

	// Edit
	} elseif(isset($_GET['action']) && $_GET['action'] == 'edit') {
		include(dirname(__FILE__).'/edit.php');

	// List
	} else {
		include(dirname(__FILE__).'/list.php');
	}
}

function layerslider_add_new() {
	include(dirname(__FILE__).'/add.php');
}

function layerslider_skin_editor() {
	include(dirname(__FILE__).'/editor.php');
}

function layerslider_style_editor() {
	include(dirname(__FILE__).'/style_editor.php');
}

function layerslider_transition_builder() {
	include(dirname(__FILE__).'/builder.php');
}

/********************************************************/
/*                  Register settings                   */
/********************************************************/
function layerslider_register_settings() {

	// Global settings
	if(isset($_POST['posted_ls_global']) && strstr($_SERVER['REQUEST_URI'], 'layerslider')) {
		update_option('layerslider_custom_capability', $_POST['custom_capability']);
		header('Location: admin.php?page=layerslider');
		die();
	}

	// Add slider
	if(isset($_POST['posted_add']) && strstr($_SERVER['REQUEST_URI'], 'layerslider')) {

		if(!isset($_POST['layerslider-slides'])) {
			return;
		}

		// Get WPDB Object
		global $wpdb;

		// Table name
		$table_name = $wpdb->prefix . "layerslider";

		// Create new record
		if($_POST['layerkey'] == 0) {

			// Execute query
			$wpdb->query(
				$wpdb->prepare("INSERT INTO $table_name
									(name, data, date_c, date_m)
								VALUES (%s, %s, %d, %d)",
								'',
								'',
								time(),
								time()
								)
			);

			// Empty slider
			$slider = array();

			// ID
			$id = mysql_insert_id();
		} else {

			// Get slider
			$slider = $wpdb->get_row("SELECT * FROM $table_name ORDER BY id DESC LIMIT 1" , ARRAY_A);

			// ID
			$id = $slider['id'];

			$slider = json_decode($slider['data'], true);
		}

		// Add modifications
		if(isset($_POST['layerslider-slides']['properties']['relativeurls'])) {
			$slider['properties'] = $_POST['layerslider-slides']['properties'];
			$slider['layers'][ $_POST['layerkey'] ] = layerslider_convert_urls($_POST['layerslider-slides']['layers'][$_POST['layerkey']]);
		} else {
			$slider['properties'] = $_POST['layerslider-slides']['properties'];
			$slider['layers'][ $_POST['layerkey'] ] = $_POST['layerslider-slides']['layers'][$_POST['layerkey']];
		}

		// DB data
		$name = $wpdb->escape($slider['properties']['title']);
		$data = $wpdb->escape(json_encode($slider));

		// Update
		$wpdb->query("UPDATE $table_name SET
					name = '$name',
					data = '$data',
					date_m = '".time()."'
				  ORDER BY id DESC LIMIT 1");

		// Echo last ID for redirect
		echo $id;

		// Redirect back
		//header('Location: '.$_SERVER['REQUEST_URI'].'');
		die();
	}

	// Edit slider
	if(isset($_POST['posted_edit']) && strstr($_SERVER['REQUEST_URI'], 'layerslider')) {

		if(!isset($_POST['layerslider-slides'])) {
			return;
		}

		// Get WPDB Object
		global $wpdb;

		// Table name
		$table_name = $wpdb->prefix . "layerslider";

		// Get the IF of the slider
		$id = (int) $_GET['id'];

		// Get slider
		$slider = $wpdb->get_row("SELECT * FROM $table_name WHERE id = ".(int)$id." ORDER BY date_c DESC LIMIT 1" , ARRAY_A);
		$data = json_decode($slider['data'], true);

		// Empty the slider
		if($_POST['layerkey'] == 0) {
			$data = array();
		}

		// Add modifications
		if(isset($_POST['layerslider-slides']['properties']['relativeurls'])) {
			$data['properties'] = $_POST['layerslider-slides']['properties'];
			$data['layers'][ $_POST['layerkey'] ] = layerslider_convert_urls($_POST['layerslider-slides']['layers'][$_POST['layerkey']]);
		} else {
			$data['properties'] = $_POST['layerslider-slides']['properties'];
			$data['layers'][ $_POST['layerkey'] ] = $_POST['layerslider-slides']['layers'][$_POST['layerkey']];
		}

		// DB data
		$name = $wpdb->escape($data['properties']['title']);
		$data = $wpdb->escape(json_encode($data));

		// Update
		$wpdb->query("UPDATE $table_name SET
					name = '$name',
					data = '$data',
					date_m = '".time()."'
				  WHERE id = '$id' LIMIT 1");

		// Redirect back
		//header('Location: '.$_SERVER['REQUEST_URI'].'');
		die();
	}

	// Import settings
	if(isset($_POST['import']) && strstr($_SERVER['REQUEST_URI'], 'layerslider')) {

		// Try to get slider data with JSON
		$import = json_decode(base64_decode($_POST['import']), true);

		// Invalid export code
		if(!is_array($import)) {

			// Try to get slider data with PHP unserialize
			$import = unserialize(base64_decode($_POST['import']));

			// Failed to extract the slider data, exit
			if(!is_array($import)) {
				header('Location: '.$_SERVER['REQUEST_URI'].'');
				die();
			}
		}

		// Get WPDB Object
		global $wpdb;

		// Table name
		$table_name = $wpdb->prefix . "layerslider";

		// Iterate over imported sliders
		foreach($import as $item) {

			// Execute query
			$wpdb->query(
				$wpdb->prepare("INSERT INTO $table_name
									(name, data, date_c, date_m)
								VALUES (%s, %s, %d, %d)",
								$item['properties']['title'],
								json_encode($item),
								time(),
								time()
								)
			);
		}

		// Redirect back
		header('Location: '.$_SERVER['REQUEST_URI'].'');
		die();
	}

	// Skin Editor
	if(isset($_POST['posted_ls_skin_editor']) && strstr($_SERVER['REQUEST_URI'], 'layerslider')) {

		// GET SKIN
		if(isset($_GET['skin']) && !empty($_GET['skin'])) {
			$skin = $_GET['skin'];
		} else {

			// Open folder
			$handle = opendir(dirname(__FILE__) . '/skins');

			// Iterate over the contents
			while (false !== ($entry = readdir($handle))) {
				if($entry == '.' || $entry == '..' || $entry == 'preview') {
					continue;
				} else {
					$skin = $entry;
					break;
				}
			}
		}

		// Get file path
		$file = dirname(__FILE__) . '/skins/' . $skin . '/skin.css';

		// Get content
		$content = $_POST['contents'];

		// Write to file
		$status = @file_put_contents($file, $content);

		if(!$status) {
			wp_die(__("It looks like your files isn't writable, so PHP couldn't make any changes (CHMOD).", "LayerSlider"), __('Cannot write to file', 'LayerSlider'), array('back_link' => true) );
		} else {
			header('Location: admin.php?page=layerslider_skin_editor&skin='.$skin.'&edited=1');
		}
	}


	// Transition builder
	if(isset($_POST['posted_ls_transition_builder']) && strstr($_SERVER['REQUEST_URI'], 'layerslider')) {

		// Array to hold transitions
		$transitions = array();

		// Get transitions
		$transitions['t2d'] = isset($_POST['t2d']) ? $_POST['t2d'] : array();
		$transitions['t3d'] = isset($_POST['t3d']) ? $_POST['t3d'] : array();

		array_walk_recursive($transitions['t2d'], 'layerslider_builder_convert_numbers');
		array_walk_recursive($transitions['t3d'], 'layerslider_builder_convert_numbers');

		// Iterate over the sections
		foreach($transitions['t3d'] as $key => $val) {

			// Rows
			if(strstr($val['rows'], ',')) { $tmp = explode(',', $val['rows']); $tmp[0] = (int) trim($tmp[0]); $tmp[1] = (int) trim($tmp[1]); $transitions['t3d'][$key]['rows'] = $tmp; }
				else { $transitions['t3d'][$key]['rows'] = (int) $val['rows']; }

			// Cols
			if(strstr($val['cols'], ',')) { $tmp = explode(',', $val['cols']); $tmp[0] = (int) trim($tmp[0]); $tmp[1] = (int) trim($tmp[1]); $transitions['t3d'][$key]['cols'] = $tmp; }
				else { $transitions['t3d'][$key]['cols'] = (int) $val['cols']; }

			// Depth
			if(isset($val['tile']['depth'])) {
				$transitions['t3d'][$key]['tile']['depth'] = 'large';
			}

			// Before
			if(!isset($val['before']['enabled'])) {
				unset($transitions['t3d'][$key]['before']['transition']);
			}

			// After
			if(!isset($val['after']['enabled'])) {
				unset($transitions['t3d'][$key]['after']['transition']);
			}
		}

		// Iterate over the sections
		foreach($transitions['t2d'] as $key => $val) {

			if(strstr($val['rows'], ',')) { $tmp = explode(',', $val['rows']); $tmp[0] = (int) trim($tmp[0]); $tmp[1] = (int) trim($tmp[1]); $transitions['t2d'][$key]['rows'] = $tmp; }
				else { $transitions['t2d'][$key]['rows'] = (int) $val['rows']; }

			if(strstr($val['cols'], ',')) { $tmp = explode(',', $val['cols']); $tmp[0] = (int) trim($tmp[0]); $tmp[1] = (int) trim($tmp[1]); $transitions['t2d'][$key]['cols'] = $tmp; }
				else { $transitions['t2d'][$key]['cols'] = (int) $val['cols']; }

			if(empty($val['transition']['rotateX']))
				unset($transitions['t2d'][$key]['transition']['rotateX']);

			if(empty($val['transition']['rotateY']))
				unset($transitions['t2d'][$key]['transition']['rotateY']);

			if(empty($val['transition']['rotate']))
				unset($transitions['t2d'][$key]['transition']['rotate']);

			if(empty($val['transition']['scale']) || $val['transition']['scale'] == '1.0' || $val['transition']['scale'] == '1')
				unset($transitions['t2d'][$key]['transition']['scale']);

		}

		// Custom transitions file
		$upload_dir = wp_upload_dir();
		$custom_trs = $upload_dir['basedir'] . '/layerslider.custom.transitions.js';

		/*
			echo '<pre>';
			print_r($transitions);
			echo '</pre>';
			die();
		*/

		// Build transition file
		$data = 'var layerSliderCustomTransitions = ';
		$data.= json_encode($transitions);
		$data.= ';';

		// Write to file
		file_put_contents($custom_trs, $data);

		die('SUCCESS');
	}


	// Styles Editor
	if(isset($_POST['posted_ls_styles_editor']) && strstr($_SERVER['REQUEST_URI'], 'layerslider')) {

		// Get uploads dir
		$upload_dir = wp_upload_dir();
		$upload_dir = $upload_dir['basedir'];

		// Get css file
		$file = $upload_dir . '/layerslider.custom.css';

		// Get content
		$content = stripslashes($_POST['contents']);

		// Write to file
		$status = @file_put_contents($file, $content);

		if(!$status) {
			wp_die(__("It looks like your files isn't writable, so PHP couldn't make any changes (CHMOD).", "LayerSlider"), __('Cannot write to file', 'LayerSlider'), array('back_link' => true) );
		} else {
			header('Location: admin.php?page=layerslider_style_editor&edited=1');
		}
	}
}

function layerslider_builder_convert_numbers(&$item, $key) {
	if(is_numeric($item)) {
		$item = (float) $item;
	}
}

/********************************************************/
/*       public PHP function to show LayerSlider        */
/********************************************************/
function layerslider($id = 0, $page = '') {

	// Check id
	if(!isset($id) || empty($id)) {
		echo '[LayerSlider WP] You need to specify the "id" parameter for the layerslider() function call';
		return;
	}

	// Page filter
	if(isset($page) && !empty($page)) {

		// Get page name
		global $pagename;

		// Get page ID
		$pageid = (string) get_the_ID();

		// Get pages
		$pages = explode(',', $page);

		// Iterate over the pages
		foreach($pages as $page) {

			if($page == 'homepage' && is_front_page()) {
				echo layerslider_init(array('id' => $id));

			} else if($pageid == $page) {
				echo layerslider_init(array('id' => $id));
			} else if($pagename == $page) {
				echo layerslider_init(array('id' => $id));
			}
		}


	// All pages
	} else {
		echo layerslider_init(array('id' => $id));
	}
}

/********************************************************/
/*                 LayerSlider init                     */
/********************************************************/

function layerslider_init($atts) {

	// ID check
	if(!isset($atts['id']) || empty($atts['id'])) {
		return '[LayerSliderWP] '.__('Invalid shortcode', 'LayerSlider').'';
	}

	// Get slider ID
	$id = $atts['id'];

	// Get WPDB Object
	global $wpdb;

	// Table name
	$table_name = $wpdb->prefix . "layerslider";

	// Get slider
	$slider = $wpdb->get_row("SELECT * FROM $table_name
								WHERE id = ".(int)$id." AND flag_hidden = '0'
								AND flag_deleted = '0'
								ORDER BY date_c DESC LIMIT 1" , ARRAY_A);

	// Result check
	if($slider == null) {
		return '[LayerSliderWP] '.__('Slider not found', 'LayerSlider').'';
	}

	// Decode data
	$slides = json_decode($slider['data'], true);

	// Returned data
	$data = '';
	if(!defined('NL')) {
		define("NL", "\r\n");
	}

	if(!defined('TAB')) {
		define("TAB", "\t");
	}

	// Include slider file
	include(dirname(__FILE__).'/init.php');
	include(dirname(__FILE__).'/slider.php');

	// Return data
	return $data;
}

/********************************************************/
/*              LayerSlider storage notice              */
/********************************************************/

function layerslider_admin_notice() {
	?>
    <div id="layerslider_notice" class="updated">
        <img src="<?php echo $GLOBALS['lsPluginPath'].'img/ls_80x80.png' ?>" alt="WeatherSlider icon">
        <h1><?php _e('The new version of LayerSlider WP is almost ready!', 'LayerSlider') ?></h1>
        <p>
            <?php _e('For a faster and more reliable solution, LayerSlider WP needs to convert your data associated with the plugin. Your sliders and settings will remain still, and it only takes a click on this button.', 'LayerSlider') ?>

            <a href="admin.php?page=layerslider&action=convert"><?php _e('Convert Data', 'LayerSlider') ?></a>
        </p>
        <div class="clear"></div>
    </div>
    <?php
}

/********************************************************/
/*              LayerSlider storage convert             */
/********************************************************/

function layerslider_convert() {

	// Get WPDB Object
	global $wpdb;

	// Table name
	$table_name = $wpdb->prefix . "layerslider";

	// Building the query
	$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `name` varchar(100) NOT NULL,
			  `data` text NOT NULL,
			  `date_c` int(10) NOT NULL,
			  `date_m` int(11) NOT NULL,
			  `flag_hidden` tinyint(1) NOT NULL DEFAULT '0',
			  `flag_deleted` tinyint(1) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

	// Executing the query
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	// Execute the query
	dbDelta($sql);

	// Get sliders if any
	$sliders = get_option('layerslider-slides');

	// There is no sliders, exit
	if($sliders == false) {
		header('Location: admin.php?page=layerslider');
		die();
	}

	// Unserialize
	$sliders = is_array($sliders) ? $sliders : unserialize($sliders);

	// Iterate over them
	foreach($sliders as $key => $slider) {

		$wpdb->query(
			$wpdb->prepare("INSERT INTO $table_name
								( name, data, date_c, date_m )
							VALUES
								(
									'%s', '%s', '%d', '%d'
								)",
								$slider['properties']['title'],
	        					json_encode($slider),
								time(),
								time()
			)
		);
	}

	// Remove old data
	delete_option('layerslider-slides');

	// Done, exit
	header('Location: admin.php?page=layerslider');
	die();
}

/********************************************************/
/*               Action to duplicate slider             */
/********************************************************/
function layerslider_duplicateslider() {

	// Check ID
	if(!isset($_GET['id'])) {
		return;
	}

	// Get the ID of the slider
	$id = (int) $_GET['id'];

	// Get WPDB Object
	global $wpdb;

	// Table name
	$table_name = $wpdb->prefix . "layerslider";

	// Get slider
	$slider = $wpdb->get_row("SELECT * FROM $table_name WHERE id = ".(int)$id." ORDER BY date_c DESC LIMIT 1" , ARRAY_A);
	$slider = json_decode($slider['data'], true);

	// Name check
	if(empty($slider['properties']['title'])) {
		$slider['properties']['title'] = 'Unnamed';
	}

	// Rename
	$slider['properties']['title'] .= ' copy';

	// Insert the duplicate
	$wpdb->query(
		$wpdb->prepare("INSERT INTO $table_name
							(name, data, date_c, date_m)
						VALUES (%s, %s, %d, %d)",
						$slider['properties']['title'],
						json_encode($slider),
						time(),
						time()
		)
	);

	// Success
	header('Location: admin.php?page=layerslider');
	die();
}


/********************************************************/
/*                Action to remove slider               */
/********************************************************/
function layerslider_removeslider() {

	// Check ID
	if(!isset($_GET['id'])) {
		return;
	}

	// Get the ID of the slider
	$id = (int) $_GET['id'];

	// Get WPDB Object
	global $wpdb;

	// Table name
	$table_name = $wpdb->prefix . "layerslider";

	// Remove the slider
	$wpdb->query("UPDATE $table_name SET flag_deleted = '1' WHERE id = '$id' LIMIT 1");

	// Success
	header('Location: admin.php?page=layerslider');
	die();
}

/********************************************************/
/*            Action to import sample slider            */
/********************************************************/
function layerslider_import_sample_slider() {

	// Base64 encoded, serialized slider export code
	$sample_slider = json_decode(base64_decode(file_get_contents(dirname(__FILE__).'/sampleslider/sample_sliders.txt')), true);

	// Iterate over the sliders
	foreach($sample_slider as $sliderkey => $slider) {

		// Iterate over the layers
		foreach($sample_slider[$sliderkey]['layers'] as $layerkey => $layer) {

			// Change background images if any
			if(!empty($sample_slider[$sliderkey]['layers'][$layerkey]['properties']['background'])) {
				$sample_slider[$sliderkey]['layers'][$layerkey]['properties']['background'] = $GLOBALS['lsPluginPath'].'sampleslider/'.basename($layer['properties']['background']);
			}

			// Change thumbnail images if any
			if(!empty($sample_slider[$sliderkey]['layers'][$layerkey]['properties']['thumbnail'])) {
				$sample_slider[$sliderkey]['layers'][$layerkey]['properties']['thumbnail'] = $GLOBALS['lsPluginPath'].'sampleslider/'.basename($layer['properties']['thumbnail']);
			}

			// Iterate over the sublayers
			if(isset($layer['sublayers']) && !empty($layer['sublayers'])) {
				foreach($layer['sublayers'] as $sublayerkey => $sublayer) {

					// Only IMG sublayers
					if($sublayer['type'] == 'img') {
						$sample_slider[$sliderkey]['layers'][$layerkey]['sublayers'][$sublayerkey]['image'] = $GLOBALS['lsPluginPath'].'sampleslider/'.basename($sublayer['image']);
					}
				}
			}
		}
	}

	// Get WPDB Object
	global $wpdb;

	// Table name
	$table_name = $wpdb->prefix . "layerslider";

	// Append duplicate
	foreach($sample_slider as $key => $val) {

		// Insert the duplicate
		$wpdb->query(
			$wpdb->prepare("INSERT INTO $table_name
								(name, data, date_c, date_m)
							VALUES (%s, %s, %d, %d)",
							$val['properties']['title'],
							json_encode($val),
							time(),
							time()
			)
		);
	}

	// Success
	header('Location: admin.php?page=layerslider');
	die();
}

/********************************************************/
/*                        MISC                          */
/********************************************************/

function layerslider_check_unit($str) {

	if(strstr($str, 'px') == false && strstr($str, '%') == false) {
		return $str.'px';
	} else {
		return $str;
	}
}

function layerslider_convert_urls($arr) {

	// Layer BG
	if(strpos($arr['properties']['background'], 'http://') !== false) {
		$arr['properties']['background'] = parse_url($arr['properties']['background'], PHP_URL_PATH);
	}

	// Layer Thumb
	if(strpos($arr['properties']['thumbnail'], 'http://') !== false) {
		$arr['properties']['thumbnail'] = parse_url($arr['properties']['thumbnail'], PHP_URL_PATH);
	}

	// Image sublayers
	foreach($arr['sublayers'] as $sublayerkey => $sublayer) {

	    if($sublayer['type'] == 'img') {
	    	if(strpos($sublayer['image'], 'http://') !== false) {
	    		$arr['sublayers'][$sublayerkey]['image'] = parse_url($sublayer['image'], PHP_URL_PATH);
	    	}
	    }
	}

	return $arr;
}

/********************************************************/
/*                   Widget settings                    */
/********************************************************/

class LayerSlider_Widget extends WP_Widget {

	function LayerSlider_Widget() {

		$widget_ops = array( 'classname' => 'layerslider_widget', 'description' => __('Insert a slider with LayerSlider WP Widget', 'LayerSlider') );
		$control_ops = array( 'id_base' => 'layerslider_widget' );
		$this->WP_Widget( 'layerslider_widget', __('LayerSlider WP Widget', 'LayerSlider'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', $instance['title'] );


		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;

		// Call layerslider_init to show the slider
		echo do_shortcode('[layerslider id="'.$instance['id'].'"]');

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['id'] = strip_tags( $new_instance['id'] );
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}

	function form( $instance ) {

		// Defaults
		$defaults = array( 'title' => __('LayerSlider', 'LayerSlider'));
		$instance = wp_parse_args( (array) $instance, $defaults );

		// Get WPDB Object
		global $wpdb;

		// Table name
		$table_name = $wpdb->prefix . "layerslider";

		// Get sliders
		$sliders = $wpdb->get_results( "SELECT * FROM $table_name
											WHERE flag_hidden = '0' AND flag_deleted = '0'
											ORDER BY date_c ASC LIMIT 100" );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'id' ); ?>"><?php _e('Choose a slider:', 'LayerSlider') ?></label><br>
			<?php if($sliders != null && !empty($sliders)) { ?>
			<select id="<?php echo $this->get_field_id( 'id' ); ?>" name="<?php echo $this->get_field_name( 'id' ); ?>">
				<?php foreach($sliders as $item) : ?>
				<?php $name = empty($item->name) ? 'Unnamed' : $item->name; ?>
				<?php if(($item->id) == $instance['id']) { ?>
				<option value="<?php echo $item->id?>" selected="selected"><?php echo $name ?> | #<?php echo $item->id?></option>
				<?php } else { ?>
				<option value="<?php echo $item->id?>"><?php echo $name ?> | #<?php echo $item->id?></option>
				<?php } ?>
				<?php endforeach; ?>
			</select>
			<?php } else { ?>
			<?php _e("You didn't create any slider yet.", "LayerSlider", "LayerSlider") ?>
			<?php } ?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'LayerSlider'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>
	<?php
	}
}