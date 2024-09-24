<?php
/**
 * WARNING: This file is part of the themesflat. DO NOT edit
 * this file under any circumstances.
 */

/**
 * Register filter for append custom class name
 * that generated from visual-composer
 */
add_filter( 'themesflat/shortcode/counter_class', 'themesflat_custom_shortcodes_class', 10, 3 );

/**
 * Register shortcode into Visual Composer
 */
add_action( 'vc_before_init', 'themesflat_counter_shortcode_params' );

/**
 * Register parameters for counter shortcode
 * 
 * @return  void
 */
function themesflat_counter_shortcode_params() {
	vc_map( array(
		'base'        => 'counter',
		'name'        => esc_html__( 'Themesflat: Counter', 'finance' ),
		'icon'        => 'themesflat-shortcode',
		'category'    => esc_html__( 'Themesflat', 'finance' ),
		'params'      => array(
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Icon', 'finance' ),
				'param_name'  => 'icon',
				'description' => sprintf( esc_html__( 'FontAwesome Icon name. <a href="%s" target="blank">Full list of icons</a>', 'finance' ), 'http://fontawesome.io/icons/' )
			),

			array(
				'type'       => 'attach_image',
				'heading'    => esc_html__( 'Image', 'finance' ),
				'param_name' => 'image',
				'description' => esc_html__( 'Select image to replace the icon', 'finance' )
			),

			array(
				'type'             => 'textfield',
				'heading'          => esc_html__( 'Title', 'finance' ),
				'param_name'       => 'title'
			),

			array(
				'type'             => 'textfield',
				'heading'          => esc_html__( 'Value', 'finance' ),
				'param_name'       => 'value',
				'value'            => 0
			),

			array(
				'type'             => 'textfield',
				'heading'          => esc_html__( 'Prefix', 'finance' ),
				'param_name'       => 'prefix'
			),

			array(
				'type'             => 'textfield',
				'heading'          => esc_html__( 'Suffix', 'finance' ),
				'param_name'       => 'suffix'
			),

			array(
				'type'             => 'textfield',
				'heading'          => esc_html__( 'Duration', 'finance' ),
				'param_name'       => 'duration',
				'value'            => 1000
			),

			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Extra Class', 'finance' ),
				'param_name' => 'class'
			),

			array(
				'type' => 'css_editor',
				'param_name' => 'css',
				'group' => esc_html__( 'Design Options', 'finance' )
			)
		)
	) );
}

add_shortcode( 'counter', 'themesflat_shortcode_counter' );

/**
 * Counter shortcode handle
 * 
 * @param   array  $atts  Shortcode attributes
 * @return  void
 */
function themesflat_shortcode_counter( $atts, $content = null ) {
	$atts = shortcode_atts( apply_filters( 'themesflat/shortcode/counter_atts', array(
		'class'         => '',
		'css'           => '',		
		
		'icon'          => '',
		'image'         => '',
		'value'         => 100,
		'title'         => '',
		
		'duration'      => 1000,
		'prefix'        => '',
		'suffix'        => ''
	) ), $atts );

	$class = apply_filters( 'themesflat/shortcode/counter_class', array( 'counter', $atts['class'] ), $atts );
	$markup_image = '';

	if ( ! empty( $atts['image'] ) ) {
		if ( is_numeric( $atts['image'] ) ) {
			$image_src = wp_get_attachment_image_src( $atts['image'], 'full' );
			$atts['image'] = array_shift( $image_src );
		}

		$markup_image = sprintf( '<img src="%s" alt="%s" />', $atts['image'], $atts['title'] );
	}
	elseif ( ! empty( $atts['icon'] ) ) {
		$markup_image = sprintf( '<i class="fa %s"></i>', $atts['icon'] );
	}

	$markup = sprintf( '<div class="%1$s">', implode( ' ', $class ) );

	if ( ! empty( $markup_image ) )
		$markup.= sprintf( '<div class="counter-image">%s</div>', $markup_image );

	$markup.= sprintf( '
		<div class="counter-content">
			<span class="counter-prefix">%3$s</span>
			<div class="numb-counter">
				<p class="numb-count" data-from="0" data-to="%1$d" data-speed="%2$s" data-waypoint-active="yes">%1$d</p>
				<p class="separator"></p>
			</div>
		</div>
	', $atts['value'], $atts['duration'], $atts['prefix'], $atts['suffix'] );

	if ( ! empty( $atts['title'] ) )
		$markup.= sprintf( '<p class="name">%s</p>', $atts['title'] );

	$markup.= '</div>';

	// Enqueue shortcode assets		
	wp_enqueue_script( 'themesflat-counter' );	

	return $markup;
}