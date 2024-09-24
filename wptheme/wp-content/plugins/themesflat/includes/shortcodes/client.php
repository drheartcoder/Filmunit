<?php
/**
 * WARNING: This file is part of the Themesflat theme. DO NOT edit
 * this file under any circumstances.
 */

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	/**
	 * Extended class to integrate testimonial slider with
	 * visual composer
	 */
    class WPBakeryShortCode_client_slider extends WPBakeryShortCodesContainer {
    }
}

/**
 * Register filter for append custom class name
 * that generated from visual-composer
 */
add_filter( 'themesflat/shortcode/member_class', 'themesflat_custom_shortcodes_class', 10, 3 );
add_action( 'vc_before_init', 'themesflat_client_shortcode_params' );

function themesflat_client_shortcode_params() {
	/**
	 * Map the client slider shortcode
	 */
	vc_map( array(
		'name'                    => esc_html__( 'Themesflat: Client Slider', 'finance' ),
		'base'                    => 'client_slider',
		'as_parent'               => array( 'only' => 'client' ), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
		'content_element'         => true,
		'show_settings_on_create' => false,
		'category'                => esc_html__( 'Themesflat', 'finance' ),
		'params'                  => array(			
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Margin', 'finance' ),
				'param_name' => 'margin',
				'value' => '30',
				'description' => esc_html__( 'Margin item for slide', 'finance' )
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Slides per view', 'finance' ),
				'param_name' => 'slides_per_view',
				'value' => '6',
				'description' => esc_html__( 'Set numbers of slides you want to display at the same time on slider\'s container for carousel mode. Supports also "auto" value, in this case it will fit slides depending on container\'s width. "auto" mode isn\'t compatible with loop mode.', 'finance' )
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Slider autoplay', 'finance' ),
				'param_name' => 'autoplay',
				'description' => esc_html__( 'Disable autoplay mode.', 'finance' ),
				'value' => array( esc_html__( 'Yes, please', 'finance' ) => 'yes' )
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Hide pagination control', 'finance' ),
				'param_name' => 'hide_control',
				'description' => esc_html__( 'If YES pagination control will be removed.', 'finance' ),
				'value' => array( esc_html__( 'Yes, please', 'finance' ) => 'yes' )
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Hide prev/next buttons', 'finance' ),
				'param_name' => 'hide_buttons',
				'description' => esc_html__( 'If "YES" prev/next control will be removed.', 'finance' ),
				'value' => array( esc_html__( 'Yes, please', 'finance' ) => 'yes' )
			),			
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Extra class name', 'finance' ),
				'param_name' => 'class',
				'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'finance' )
			),

			array(
				'type' => 'css_editor',
				'param_name' => 'css',
				'group' => esc_html__( 'Design Options', 'finance' )
			)
		),
		'js_view' => 'VcColumnView'
	) );

	/**
	 * Map the client item
	 */
	vc_map( array(
		'base'        => 'client',
		'name'        => esc_html__( 'Themesflat: Client', 'finance' ),
		'icon'        => 'themesflat-shortcode',
		'category'    => esc_html__( 'Themesflat', 'finance' ),
		'params'      => array(	
			array(
				'type'       => 'attach_image',
				'heading'    => esc_html__( 'Image', 'finance' ),
				'param_name' => 'image'
			),
		
			array(
				'type' => 'css_editor',
				'param_name' => 'css',
				'group' => esc_html__( 'Design Options', 'finance' )
			)
		)
	) );
}

add_shortcode( 'client', 'themesflat_shortcode_client' );
add_shortcode( 'client_slider', 'themesflat_shortcode_client_slider' );

/**
 * Testimonial shortcode handle
 * 
 * @param   string  $atts  Shortcode attributes
 * @return  void
 */
function themesflat_shortcode_client( $atts, $content = null ) {
	$atts = shortcode_atts( apply_filters( 'themesflat/shortcode/client_atts', array(
		'class'    => '',
		'css'      => '',	
		
		'image'    => '',
	) ), $atts );

	if ( ! empty( $atts['image'] ) ) {
		if ( is_numeric( $atts['image'] ) && $images = wp_get_attachment_image_src( $atts['image'], 'full' ) )
			$atts['image'] = array_shift( $images );
	}
	
	return sprintf( '
		<div class="client-image">
			<img src="%s" alt="images" />
		</div>'
	,esc_attr( $atts['image'] ) );
}

/**
 * This function will be use to handle client slider
 * shortcode
 * 
 * @param   string  $atts     Shortcode attributes
 * @param   string  $content  Shortcode content
 * @return  string
 */
function themesflat_shortcode_client_slider( $atts, $content = null ) {
	$atts = shortcode_atts( array(		
		'margin'           => '30',
		'slides_per_view' => '6',
		'autoplay'        => '',
		'hide_control'    => '',
		'hide_buttons'    => '',		
		'class'           => '',
		'css'             => ''
	), $atts );
	
	$config = $atts;

	unset( $config['class'] );
	unset( $config['css'] );

	$class = apply_filters( 'themesflat/shortcode/client_slider_class', array( 'client-slide', $atts['class'] ), $atts );

	// Enqueue shortcode assets
	wp_enqueue_script( 'themesflat-carousel' );
	
	return sprintf( '
		<div class="wrap-client-slide">
			<div class="%s" data-margin="%s" data-slides_per_view="%s" data-autoplay="%s" data-hide_control="%s" data-hide_buttons="%s">			
				%s				
			</div>
		</div>
	', implode( ' ', $class ), esc_attr( $atts['margin'] ) , esc_attr( $atts['slides_per_view'] ), esc_attr( $atts['autoplay'] ), esc_attr( $atts['hide_control'] ), esc_attr( $atts['hide_buttons'] ),  do_shortcode( $content ) );
}

