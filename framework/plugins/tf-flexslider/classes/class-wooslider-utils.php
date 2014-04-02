<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WooSlider Utilities Class
 *
 * Common utility functions for WooSlider.
 *
 * @package WordPress
 * @subpackage WooSlider
 * @category Utilities
 * @author WooThemes
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * - get_slider_types()
 * - get_posts_layout_types()
 * - get_supported_effects()
 * - get_slider_themes()
 */
class WooSlider_Utils {
	/**
	 * Get an array of the supported slider types.
	 * @since  1.0.0
	 * @return array The slider types supported by WooSlider.
	 */
	public static function get_slider_types () {
		return (array)apply_filters( 'wooslider_slider_types', array(
																	'attachments' => array( 'name' => __( 'Attached Images', 'wooslider' ), 'callback' => 'method' ), 
																	'slides' => array( 'name' => __( 'Slides', 'wooslider' ), 'callback' => 'method' ), 
																	'posts' => array( 'name' => __( 'Posts', 'wooslider' ), 'callback' => 'method' )
																	)
									);
	} // End get_slider_types()

	/**
	 * Get an array of the supported posts layout types.
	 * @since  1.0.0
	 * @return array The posts layout types supported by WooSlider.
	 */
	public static function get_posts_layout_types () {
		return (array)apply_filters( 'wooslider_posts_layout_types', array(
																	'text-left' => array( 'name' => __( 'Text Left', 'wooslider' ), 'callback' => 'method' ), 
																	'text-right' => array( 'name' => __( 'Text Right', 'wooslider' ), 'callback' => 'method' ), 
																	'text-top' => array( 'name' => __( 'Text Top', 'wooslider' ), 'callback' => 'method' ), 
																	'text-bottom' => array( 'name' => __( 'Text Bottom', 'wooslider' ), 'callback' => 'method' )
																	)
									);
	} // End get_posts_layout_types()

	/**
	 * Return an array of supported slider effects.
	 * @since  1.0.0
	 * @uses  filter: 'wooslider_supported_effects'
	 * @return array Supported effects.
	 */
	public static function get_supported_effects () {
		return (array)apply_filters( 'wooslider_supported_effects', array( 'fade', 'slide' ) );
	} // End get_supported_effects()

	/**
	 * Get the placeholder thumbnail image.
	 * @since  1.0.0
	 * @return string The URL to the placeholder thumbnail image.
	 */
	public static function get_placeholder_image () {
		global $wooslider;
		return esc_url( apply_filters( 'wooslider_placeholder_thumbnail', $wooslider->plugin_url . 'assets/images/placeholder.png' ) );
	} // End get_placeholder_image()

	/**
	 * Get an array of the supported slider themes.
	 * @since  1.0.4
	 * @return array The slider themes supported by WooSlider.
	 */
	public static function get_slider_themes () {
		return (array)apply_filters( 'wooslider_slider_themes', array(
																	'default' => array( 'name' => __( 'Default', 'wooslider' ), 'stylesheet' => '' )
																	)
									);
	} // End get_slider_themes()
} // End Class
?>