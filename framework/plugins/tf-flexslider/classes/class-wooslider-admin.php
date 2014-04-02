<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WooSlider Administration Class
 *
 * All functionality pertaining to the administration sections of WooSlider.
 *
 * @package WordPress
 * @subpackage WooSlider
 * @category Administration
 * @author WooThemes
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * - __construct()
 * - admin_styles_global()
 * - add_media_tab()
 * - media_tab_handle()
 * - media_tab_process()
 * - media_tab_js()
 * - popup_fields()
 * - display_special_settings()
 * - add_default_conditional_fields()
 * - conditional_fields_attachments()
 * - conditional_fields_posts()
 * - conditional_fields_slides()
 * - generate_field_by_type()
 * - generate_default_conditional_fields()
 * - generate_conditional_fields_slides()
 * - generate_conditional_fields_posts()
 */
class WooSlider_Admin {
	/**
	 * Constructor.
	 * @since  1.0.0
	 * @return  void
	 */
	public function __construct () {
		add_action( 'admin_enqueue_scripts', array( &$this, 'admin_styles_global' ) );
		add_filter( 'media_upload_tabs', array( &$this, 'add_media_tab' ) );
		add_action( 'media_upload_wooslider', array( &$this, 'media_tab_handle' ) );

		add_action( 'admin_print_scripts', array( &$this, 'media_tab_js' ) );
		add_action( 'wooslider_popup_conditional_fields', array( &$this, 'add_default_conditional_fields' ) );
	} // End __construct()

	/**
	 * Load the global admin styles for the menu icon and the relevant page icon.
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function admin_styles_global () {
		global $wooslider;
		wp_register_style( $wooslider->token . '-global', $wooslider->plugin_url . 'assets/css/global.css', '', '1.0.6', 'screen' );
		wp_enqueue_style( $wooslider->token . '-global' );
	} // End admin_styles_global()

	/**
	 * Filter the "Add Media" popup's tabs, to add our own.
	 * @since  1.0.0
	 * @param array $tabs The existing array of tabs.
	 */
	public function add_media_tab ( $tabs ) {
		$tabs['wooslider'] = __( 'FlexSlider', 'wooslider' );
		return $tabs;
	} // End add_media_tab()

	/**
	 * Display the tab content in a WordPress iframe.
	 * @since  1.0.0
	 * @return void
	 */
	public function media_tab_handle () {
		wp_iframe( array( &$this, 'media_tab_process' ) );
	} // End media_tab_handle()

	/**
	 * Create the tab content to be displayed.
	 * @since  1.0.0
	 * @uses  global $wooslider Global $wooslider object
	 * @return void
	 */
	public function media_tab_process () {
		global $wooslider;
		media_upload_header();
		$wooslider->post_types->setup_slide_pages_taxonomy();
?>
<form action="media-new.php" method="post" id="wooslider-insert">
	<?php submit_button( __( 'Insert Slideshow', 'wooslider' ) ); ?>
	<?php $this->popup_fields(); ?>
	<p class="hide-if-no-js"><a href="#advanced-settings" class="advanced-settings button"><?php _e( 'Advanced Settings', 'wooslider' ); ?></a></p>
	<div id="wooslider-advanced-settings">
		<div class="updated fade"><p><?php _e( 'Optionally override the default slideshow settings using the fields below.', 'wooslider' ); ?></p></div>
		<?php
			$this->display_special_settings();
			settings_fields( $wooslider->settings->token );
			do_settings_sections( $wooslider->settings->token );
		?>
	</div><!--/#wooslider-advanced-settings-->
	<?php submit_button( __( 'Insert Slideshow', 'wooslider' ) ); ?>
</form>
<?php
	} // End media_tab_process()

	/**
	 * Load the JavaScript to handle the media tab in the "Add Media" popup.
	 * @since  1.0.0
	 * @return void
	 */
	public function media_tab_js () {
		global $wooslider, $pagenow;
		if ( 'media-upload.php' != $pagenow ) return; // Execute only in the Media Upload popup.

		$wooslider->settings->enqueue_field_styles();

		$wooslider->settings->enqueue_scripts();

		wp_enqueue_script( 'wooslider-settings-ranges' );
		wp_enqueue_script( 'wooslider-settings-imageselectors' );

		wp_enqueue_style( 'wooslider-settings-ranges' );
		wp_enqueue_style( 'wooslider-settings-imageselectors' );

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_register_script( $wooslider->token . '-media-tab', esc_url( $wooslider->plugin_url . 'assets/js/shortcode-creator' . $suffix . '.js' ), array( 'jquery' ), '1.0.7', false );
		wp_enqueue_script( $wooslider->token . '-media-tab' );

		$settings = $wooslider->settings->get_settings();

		// Allow themes/plugins to filter here.
		$settings['category'] = '';
		$settings['tag'] = '';
		$settings['slide_page'] = '';
		$settings['slider_type'] = '';
		$settings['theme'] = 'default';
		$settings['layout'] = '';
		$settings['overlay'] = '';
		$settings['limit'] = '5';
		$settings['thumbnails'] = '';
		$settings['link_title'] = '';
		$settings['display_excerpt'] = '1';
		$settings['id'] = '';
		$settings['sync'] = '';
		// $settings['as_nav_for'] = '';
		$settings = (array)apply_filters( 'wooslider_popup_settings', $settings );

		wp_localize_script( $wooslider->token . '-media-tab', $wooslider->token . '_settings', $settings );
	} // End media_tab_js()

	/**
	 * Fields specific to the "Add Media" popup.
	 * @since  1.0.0
	 * @return void
	 */
	public function popup_fields () {
		$types = WooSlider_Utils::get_slider_types();

	    $slider_types = array();
	    foreach ( (array)$types as $k => $v ) {
	    	$slider_types[$k] = $v['name'];
	    }
?>
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><?php _e( 'Slideshow Type', 'wooslider' ); ?></th>
				<td><select id="slider_type" name="wooslider-settings[slider_type]">
					<?php
						foreach ( (array)$slider_types as $k => $v ) {
							echo '<option value="' . esc_attr( $k ) . '">' . $v . '</option>' . "\n";
						}
					?>
					</select>
					<p><span class="description"><?php _e( 'The type of slideshow to insert', 'wooslider' ); ?></span></p>
				</td>
			</tr>
			<?php
				// Theming engine integration.
				$themes = WooSlider_Utils::get_slider_themes();

				if ( is_array( $themes ) && ( 1 < count( $themes ) ) ) {
			?>
			<tr valign="top">
				<th scope="row"><?php _e( 'Slideshow Theme', 'wooslider' ); ?></th>
				<td><select id="theme" name="wooslider-settings[theme]">
					<?php
						foreach ( (array)$themes as $k => $v ) {
							echo '<option value="' . esc_attr( $k ) . '">' . $v['name'] . '</option>' . "\n";
						}
					?>
					</select>
					<p><span class="description"><?php _e( 'The desired slideshow theme', 'wooslider' ); ?></span></p>
				</td>
			</tr>
			<?php
				}
			?>
			<tr valign="top">
				<th scope="row"><?php _e( 'Slideshow ID', 'wooslider' ); ?></th>
				<td><input type="text" name="wooslider-settings[id]" id="id" value="" />
					<p><span class="description"><?php _e( 'Give this slideshow a specific ID (optional)', 'wooslider' ); ?></span></p>
				</td>
			</tr>
		</tbody>
	</table>
<?php
		// Allow themes/plugins to act here.
		do_action( 'wooslider_popup_conditional_fields', $types );
	} // End popup_fields()

	/**
	 * Display special settings that can apply to all slideshow types.
	 * @since  1.0.7
	 * @return void
	 */
	private function display_special_settings () {
?>
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><?php _e( 'Sync', 'wooslider' ); ?></th>
				<td><input type="text" name="wooslider-settings[sync]" id="sync" value="" />
					<p><span class="description"><?php _e( 'Slideshow ID: Mirror the actions performed on this slideshow with another slideshow. Use with care.', 'wooslider' ); ?></span></p>
				</td>
			</tr>
<?php /*
			<tr valign="top">
				<th scope="row"><?php _e( 'As Navigation For', 'wooslider' ); ?></th>
				<td><input type="text" name="wooslider-settings[as_nav_for]" id="as_nav_for" value="" />
					<p><span class="description"><?php _e( 'Slideshow ID: Use this slideshow as navigation for another slideshow. Make sure the number of slides matches.', 'wooslider' ); ?></span></p>
				</td>
			</tr>
*/ ?>
		</tbody>
	</table>
<?php
		// Allow themes/plugins to act here.
		do_action( 'wooslider_popup_special_settings_fields' );
	} // End display_special_settings()

	/**
	 * Setup the conditional fields for the default slider types.
	 * @since  1.0.0
	 * @param  array $types The supported slideshow types.
	 * @return void
	 */
	public function add_default_conditional_fields ( $types ) {
		global $pagenow;
		if ( 'media-upload.php' != $pagenow ) return; // Execute only in the Media Upload popup.
		
		foreach ( (array)$types as $k => $v ) {
			if ( method_exists( $this, 'conditional_fields_' . $k ) ) {
				echo '<div class="conditional conditional-' . esc_attr( $k ) . '">' . "\n";
				$this->{'conditional_fields_' . $k}();
				echo '</div>' . "\n";
			}
		}
	} // End add_default_conditional_fields()

	/**
	 * Conditional fields, displayed only for the "attachments" slideshow type.
	 * @since  1.0.0
	 * @return void
	 */
	private function conditional_fields_attachments () {
		global $wooslider;

		$fields = $this->generate_conditional_fields_attachments();
?>
	<table class="form-table">
		<tbody>
<?php foreach ( $fields as $k => $v ) { ?>
			<tr valign="top">
				<th scope="row"><?php echo $v['name']; ?></th>
				<td>
					<?php $this->generate_field_by_type( $v['type'], $v['args'] ); ?>
					<?php if ( $v['description'] != '' ) { ?><p><span class="description"><?php echo $v['description']; ?></span></p><?php } ?>
				</td>
			</tr>
<?php } ?>
		</tbody>
	</table>
<?php
	} // End conditional_fields_attachments()

	/**
	 * Conditional fields, displayed only for the "posts" slideshow type.
	 * @since  1.0.0
	 * @return void
	 */
	private function conditional_fields_posts () {
		$fields = $this->generate_conditional_fields_posts();
?>
	<table class="form-table">
		<tbody>
<?php foreach ( $fields as $k => $v ) { ?>
			<tr valign="top">
				<th scope="row"><?php echo $v['name']; ?></th>
				<td>
					<?php $this->generate_field_by_type( $v['type'], $v['args'] ); ?>
					<?php if ( $v['description'] != '' ) { ?><p><span class="description"><?php echo $v['description']; ?></span></p><?php } ?>
				</td>
			</tr>
<?php } ?>
		</tbody>
	</table>
<?php
	} // End conditional_fields_posts()

	/**
	 * Conditional fields, displayed only for the "slides" slideshow type.
	 * @since  1.0.0
	 * @return void
	 */
	private function conditional_fields_slides () {
		global $wooslider;

		$fields = $this->generate_conditional_fields_slides();
?>
	<table class="form-table">
		<tbody>
<?php foreach ( $fields as $k => $v ) { ?>
			<tr valign="top">
				<th scope="row"><?php echo $v['name']; ?></th>
				<td>
					<?php $this->generate_field_by_type( $v['type'], $v['args'] ); ?>
					<?php if ( $v['description'] != '' ) { ?><p><span class="description"><?php echo $v['description']; ?></span></p><?php } ?>
				</td>
			</tr>
<?php } ?>
		</tbody>
	</table>
<?php
	} // End conditional_fields_slides()

	/**
	 * Generate a field from the settings API based on a provided field type.
	 * @since  1.0.0
	 * @param  string $type The type of field to generate.
	 * @param  array $args Arguments to be passed to the field.
	 * @return void
	 */
	public function generate_field_by_type ( $type, $args ) {
		if ( is_array( $args ) && isset( $args['key'] ) && isset( $args['data'] ) ) {
			global $wooslider;
			$default = '';
			if ( isset( $args['data']['default'] ) ) { $default = $args['data']['default']; }

			switch ( $type ) {
				// Text fields.
				case 'text':
					$html = '<input type="text" name="' . esc_attr( $args['key'] ) . '" id="' . esc_attr( $args['key'] ) . '" value="' . esc_attr( $default ) . '" />' . "\n";

					echo $html;
				break;

				// Select fields.
				case 'select':
					$html = '<select name="' . esc_attr( $args['key'] ) . '" id="' . esc_attr( $args['key'] ) . '">' . "\n";
					foreach ( $args['data']['options'] as $k => $v ) {
						$html .= '<option value="' . esc_attr( $k ) . '"' . selected( $k, $default, false ) . '>' . $v . '</option>' . "\n";
					}
					$html .= '</select>' . "\n";

					echo $html;
				break;

				// Single checkbox.
				case 'checkbox':
					$default = '';
					if ( isset( $args['data']['default'] ) ) { $default = $args['data']['default']; }
					$checked = checked( $default, 'true', false) ;
					$html = '<input type="checkbox" id="' . $args['key'] . '" name="' . $args['key'] . '" class="checkbox checkbox-' . esc_attr( $args['key'] ) . '" value="true"' . $checked . ' /> ' . "\n";
					echo $html;

				break;

				// Multiple checkboxes.
				case 'multicheck':
				if ( isset( $args['data']['options'] ) && ( count( (array)$args['data']['options'] ) > 0 ) ) {
					$html = '<div class="multicheck-container" style="height: 100px; overflow-y: auto;">' . "\n";
					foreach ( $args['data']['options'] as $k => $v ) {
						$checked = '';
						$html .= '<input type="checkbox" name="' . $args['key'] . '[]" class="multicheck multicheck-' . esc_attr( $args['key'] ) . '" value="' . esc_attr( $k ) . '"' . $checked . ' /> ' . $v . '<br />' . "\n";
					}
					$html .= '</div>' . "\n";
					echo $html;
				}

				break;

				// Image selectors.
				case 'images':
				if ( isset( $args['data']['options'] ) && ( count( (array)$args['data']['options'] ) > 0 ) ) {
					$html = '';
					foreach ( $args['data']['options'] as $k => $v ) {
						$image_url = $wooslider->plugin_url . '/assets/images/default.png';
						if ( isset( $args['data']['images'][$k] ) ) {
							$image_url = $args['data']['images'][$k];
						}
						$image = '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $v ) . '" title="' . esc_attr( $v ) . '" class="radio-image-thumb" />';
						$html .= '<input type="radio" name="' . $args['key'] . '" value="' . esc_attr( $k ) . '" class="radio-images" /> ' . $image . "\n";
					}
					echo $html;
				}
				break;
			}
		}
	} // End generate_field_by_type()

	/**
	 * Generate an array of the conditional fields for the default slider types.
	 * @since  1.0.0
	 * @param  array $types The supported slideshow types.
	 * @return array $fields.
	 */
	public function generate_default_conditional_fields ( $types ) {
		$fields = array();
		foreach ( (array)$types as $k => $v ) {
			if ( method_exists( $this, 'generate_conditional_fields_' . $k ) ) {
				$fields[$k] = (array)$this->{'generate_conditional_fields_' . $k}();
			}
		}

		return $fields;
	} // End generate_default_conditional_fields()

	/**
	 * Generate conditional fields for the "attachments" slideshow type.
	 * @since  1.0.0
	 * @return array $fields An array of fields.
	 */
	private function generate_conditional_fields_attachments () {
		$fields = array();

		$limit_options = array();
		for ( $i = 1; $i <= 20; $i++ ) {
			$limit_options[$i] = $i;
		}
		$limit_args = array( 'key' => 'limit', 'data' => array( 'options' => $limit_options, 'default' => 5 ) );
		$thumbnails_args = array( 'key' => 'thumbnails', 'data' => array() );

		// Create final array.
		$fields['limit'] = array( 'name' => __( 'Number of Images', 'wooslider' ), 'type' => 'select', 'args' => $limit_args, 'description' => __( 'The maximum number of images to display', 'wooslider' ) );
		$fields['thumbnails'] = array( 'name' => __( 'Use thumbnails for Pagination', 'wooslider' ), 'type' => 'checkbox', 'args' => $thumbnails_args, 'description' => __( 'Use thumbnails for pagination, instead of "dot" indicators', 'wooslider' ) );

		return $fields;
	} // End generate_conditional_fields_attachments()

	/**
	 * Generate conditional fields for the "slides" slideshow type.
	 * @since  1.0.0
	 * @return array $fields An array of fields.
	 */
	private function generate_conditional_fields_slides () {
		$fields = array();

		// Categories.
		$terms = get_terms( 'slide-page' );
		$terms_options = array();
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $k => $v ) {
				$terms_options[$v->slug] = $v->name;
			}
		}

		$categories_args = array( 'key' => 'slide_page', 'data' => array( 'options' => $terms_options ) );

		$limit_options = array();
		for ( $i = 1; $i <= 20; $i++ ) {
			$limit_options[$i] = $i;
		}
		$limit_args = array( 'key' => 'limit', 'data' => array( 'options' => $limit_options, 'default' => 5 ) );
		$thumbnails_args = array( 'key' => 'thumbnails', 'data' => array() );
		$display_featured_image_args = array( 'key' => 'display_featured_image', 'data' => array() );

		// Create final array.
		$fields['limit'] = array( 'name' => __( 'Number of Slides', 'wooslider' ), 'type' => 'select', 'args' => $limit_args, 'description' => __( 'The maximum number of slides to display', 'wooslider' ) );
		$fields['slide_page'] = array( 'name' => __( 'Slide Groups', 'wooslider' ), 'type' => 'multicheck', 'args' => $categories_args, 'description' => __( 'The slide groups from which to display slides', 'wooslider' ) );
		$fields['thumbnails'] = array( 'name' => __( 'Use thumbnails for Pagination', 'wooslider' ), 'type' => 'checkbox', 'args' => $thumbnails_args, 'description' => __( 'Use thumbnails for pagination, instead of "dot" indicators (uses featured image)', 'wooslider' ) );

		return $fields;
	} // End generate_conditional_fields_slides()

	/**
	 * Generate conditional fields for the "posts" slideshow type.
	 * @since  1.0.0
	 * @return array $fields An array of fields.
	 */
	private function generate_conditional_fields_posts () {
		global $wooslider;

		$images_url = $wooslider->plugin_url . '/assets/images/';
		$fields = array();

		// Categories.
		$terms = get_categories();
		$terms_options = array();
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $k => $v ) {
				$terms_options[$v->slug] = $v->name;
			}
		}

		$categories_args = array( 'key' => 'category', 'data' => array( 'options' => $terms_options ) );

		// Tags.
		$terms = get_tags();
		$terms_options = array();
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $k => $v ) {
				$terms_options[$v->slug] = $v->name;
			}
		}

		$tags_args = array( 'key' => 'tag', 'data' => array( 'options' => $terms_options ) );

		$layout_types = WooSlider_Utils::get_posts_layout_types();
		$layout_options = array();

		foreach ( (array)$layout_types as $k => $v ) {
			$layout_options[$k] = $v['name'];
		}

		$layout_images = array(
								'text-left' => esc_url( $images_url . 'text-left.png' ), 
								'text-right' => esc_url( $images_url . 'text-right.png' ), 
								'text-top' => esc_url( $images_url . 'text-top.png' ), 
								'text-bottom' => esc_url( $images_url . 'text-bottom.png' )
							);
		$layouts_args = array( 'key' => 'layout', 'data' => array( 'options' => $layout_options, 'images' => $layout_images ) );

		$overlay_images = array(
								'none' => esc_url( $images_url . 'default.png' ), 
								'full' => esc_url( $images_url . 'text-bottom.png' ), 
								'natural' => esc_url( $images_url . 'overlay-natural.png' )
							);

		$overlay_options = array( 'none' => __( 'None', 'wooslider' ), 'full' => __( 'Full', 'wooslider' ), 'natural' => __( 'Natural', 'wooslider' ) );

		$overlay_args = array( 'key' => 'overlay', 'data' => array( 'options' => $overlay_options, 'images' => $overlay_images ) );

		$limit_options = array();
		for ( $i = 1; $i <= 20; $i++ ) {
			$limit_options[$i] = $i;
		}
		$limit_args = array( 'key' => 'limit', 'data' => array( 'options' => $limit_options, 'default' => 5 ) );
		$thumbnails_args = array( 'key' => 'thumbnails', 'data' => array() );
		$link_title_args = array( 'key' => 'link_title', 'data' => array() );
		$display_excerpt_args = array( 'key' => 'display_excerpt', 'data' => array('default' => '1') );

		// Create final array.
		$fields['limit'] = array( 'name' => __( 'Number of Posts', 'wooslider' ), 'type' => 'select', 'args' => $limit_args, 'description' => __( 'The maximum number of posts to display', 'wooslider' ) );
		$fields['thumbnails'] = array( 'name' => __( 'Use thumbnails for Pagination', 'wooslider' ), 'type' => 'checkbox', 'args' => $thumbnails_args, 'description' => __( 'Use thumbnails for pagination, instead of "dot" indicators (uses featured image)', 'wooslider' ) );
		$fields['link_title'] = array( 'name' => __( 'Link the post title to it\'s post', 'wooslider' ), 'type' => 'checkbox', 'args' => $link_title_args, 'description' => __( 'Link the post title to it\'s single post screen', 'wooslider' ) );
		$fields['display_excerpt'] = array( 'name' => __( 'Display the post\'s excerpt', 'wooslider' ), 'type' => 'checkbox', 'args' => $display_excerpt_args, 'description' => __( 'Display the post\'s excerpt on each slide', 'wooslider' ) );
		$fields['layout'] = array( 'name' => __( 'Layout', 'wooslider' ), 'type' => 'images', 'args' => $layouts_args, 'description' => __( 'The layout to use when displaying posts', 'wooslider' ) );
		$fields['overlay'] = array( 'name' => __( 'Overlay', 'wooslider' ), 'type' => 'images', 'args' => $overlay_args, 'description' => __( 'The type of overlay to use when displaying the post text', 'wooslider' ) );
		$fields['category'] = array( 'name' => __( 'Categories', 'wooslider' ), 'type' => 'multicheck', 'args' => $categories_args, 'description' => __( 'The categories from which to display posts', 'wooslider' ) );
		$fields['tag'] = array( 'name' => __( 'Tags', 'wooslider' ), 'type' => 'multicheck', 'args' => $tags_args, 'description' => __( 'The tags from which to display posts', 'wooslider' ) );

		return $fields;
	} // End generate_conditional_fields_posts()
} // End Class
?>