<?php
/**
 * WARNING: This file is part of the themesflat theme. DO NOT edit
 * this file under any circumstances.
 */

if ( ! function_exists( 'themesflat_custom_shortcodes_class' ) ) {
	/**
	 * Helper function to append custom css class that
	 * generated from VisualComposer for shortcode
	 *
	 * @param   array  $classes  Shortcode classes
	 * @param   array  $atts     Shortcode attributes
	 * @param   string $tag      Shortcode tag name
	 *
	 * @return  array
	 */
	function themesflat_custom_shortcodes_class( $classes, $atts = array(), $tag = '' ) {
		if ( function_exists( 'vc_shortcode_custom_css_class' ) && ! empty( $atts['css'] ) ) {
			$classes[] = vc_shortcode_custom_css_class( $atts['css'], ' ' );
		}

		return $classes;
	}
}



