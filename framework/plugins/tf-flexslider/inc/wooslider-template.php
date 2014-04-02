<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Enable the usage of do_action( 'wooslider' ) to display a slideshow within a theme/plugin.
 *
 * @since  1.0.6
 */
add_action( 'wooslider', 'wooslider', 10, 2 );

if ( ! function_exists( 'wooslider' ) ) {
/**
 * WooSlider template tag.
 * @since  1.0.0
 * @param  array   $args 	Optional array of arguments to customise this instance of the slider.
 * @param  array   $extra_args 	Optional array of extra arguments to customise this instance of the slider.
 * @param  boolean $echo 	Whether or not to echo the slider output (default: true)
 * @return string/void      Returns a string of $echo is false. Otherwise, returns void.
 */
function wooslider ( $args = array(), $extra_args = array(), $echo = true ) {
	global $wooslider;

	$defaults = $wooslider->settings->get_settings();
	$defaults['slider_type'] = 'attachments';

	$settings = wp_parse_args( $args, $defaults );

	// Generate an ID for this slider.
	if ( isset( $extra_args['id'] ) ) {
		$settings['id'] = $extra_args['id'];
	} else {
		$settings['id'] = 'wooslider-id-' . $wooslider->slider_count++;
	}

	$slides = $wooslider->frontend->sliders->get_slides( $settings['slider_type'], $extra_args, $settings );

	$wooslider->frontend->sliders->add( $slides, $settings, $extra_args );

	$theme = 'default';
	if ( $wooslider->frontend->is_valid_theme( $extra_args ) ) {
		$theme = $wooslider->frontend->get_sanitized_theme_key( $extra_args );
	}

	$slides_html = $wooslider->frontend->sliders->render( $slides, $extra_args );

	$html = '';
	if ( '' != $slides_html ) {
		$html .= '<div id="' . esc_attr( $settings['id'] ) . '" class="wooslider ' . esc_attr( $settings['id'] ) . ' wooslider-type-' . esc_attr( $settings['slider_type'] ) . ' wooslider-theme-' . esc_attr( $theme ) . '"><ul class="slides">' . "\n";
		$html .= $slides_html;
		$html .= '</ul></div>' . "\n";
	}

	if ( $echo == true ) { echo $html; }

	return $html;
} // End wooslider()
}

if ( ! function_exists( 'wooslider_shortcode' ) ) {
/**
 * WooSlider shortcode wrapper.
 * @since  1.0.0
 * @param  array $atts    	Optional shortcode attributes, used to customise slider settings.
 * @param  string $content 	Content, if the shortcode supports wrapping of content.
 * @return string          	Rendered WooSlider.
 */
function wooslider_shortcode ( $atts, $content = null ) {
	global $wooslider;
	$args = $wooslider->settings->get_settings();
	$args['slider_type'] = 'attachments';
	$settings = shortcode_atts( $args, $atts );
	$extra_args = array();

	foreach ( (array)$atts as $k => $v ) {
		if ( ! in_array( $k, array_keys( $args ) ) ) {
			$extra_args[$k] = $v;
		}
	}

	return wooslider( $settings, $extra_args, false );
} // End wooslider_shortcode()
}

add_shortcode( 'wooslider', 'wooslider_shortcode' );
?>