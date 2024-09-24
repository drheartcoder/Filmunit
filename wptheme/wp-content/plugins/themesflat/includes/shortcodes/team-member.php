<?php
/**
 * WARNING: This file is part of the themesflat theme. DO NOT edit
 * this file under any circumstances.
 */

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	/**
	 * Extended class to integrate testimonial slider with
	 * visual composer
	 */
    class WPBakeryShortCode_teammember_slider extends WPBakeryShortCodesContainer {
    }
}

/**
 * Register filter for append custom class name
 * that generated from visual-composer
 */
add_filter( 'themesflat/shortcode/member_class', 'themesflat_custom_shortcodes_class', 10, 3 );
add_action( 'vc_before_init', 'themesflat_team_member_shortcode_params' );

function themesflat_team_member_shortcode_params() {
	/**
	 * Map the testimonial slider shortcode
	 */
	vc_map( array(
		'name'                    =>esc_html__( 'Themesflat: Team Member Slider', 'finance' ),
		'base'                    => 'teammember_slider',
		'as_parent'               => array( 'only' => 'member' ), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
		'content_element'         => true,
		'show_settings_on_create' => false,
		'category'                =>esc_html__( 'Themesflat', 'finance' ),
		'params'                  => array(			
			array(
				'type' => 'textfield',
				'heading' =>esc_html__( 'Margin', 'finance' ),
				'param_name' => 'margin',
				'value' => '30',
				'description' =>esc_html__( 'Margin item for slide', 'finance' )
			),
			array(
				'type' => 'textfield',
				'heading' =>esc_html__( 'Slides per view', 'finance' ),
				'param_name' => 'slides_per_view',
				'value' => '1',
				'description' =>esc_html__( 'Set numbers of slides you want to display at the same time on slider\'s container for carousel mode. Supports also "auto" value, in this case it will fit slides depending on container\'s width. "auto" mode isn\'t compatible with loop mode.', 'finance' )
			),
			array(
				'type' => 'checkbox',
				'heading' =>esc_html__( 'Slider autoplay', 'finance' ),
				'param_name' => 'autoplay',
				'description' =>esc_html__( 'Disable autoplay mode.', 'finance' ),
				'value' => array(esc_html__( 'Yes, please', 'finance' ) => 'yes' )
			),
			array(
				'type' => 'checkbox',
				'heading' =>esc_html__( 'Hide pagination control', 'finance' ),
				'param_name' => 'hide_control',
				'description' =>esc_html__( 'If YES pagination control will be removed.', 'finance' ),
				'value' => array(esc_html__( 'Yes, please', 'finance' ) => 'yes' )
			),
			array(
				'type' => 'checkbox',
				'heading' =>esc_html__( 'Hide prev/next buttons', 'finance' ),
				'param_name' => 'hide_buttons',
				'description' =>esc_html__( 'If "YES" prev/next control will be removed.', 'finance' ),
				'value' => array(esc_html__( 'Yes, please', 'finance' ) => 'yes' )
			),			
			array(
				'type' => 'textfield',
				'heading' =>esc_html__( 'Extra class name', 'finance' ),
				'param_name' => 'class',
				'description' =>esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'finance' )
			),

			array(
				'type' => 'css_editor',
				'param_name' => 'css',
				'group' =>esc_html__( 'Design Options', 'finance' )
			)
		),
		'js_view' => 'VcColumnView'
	) );

	/**
	 * Map the member item
	 */
	vc_map( array(
		'base'        => 'member',
		'name'        =>esc_html__( 'Themesflat: Team Member', 'finance' ),
		'icon'        => 'themesflat-shortcode',
		'category'    =>esc_html__( 'Themesflat', 'finance' ),
		'params'      => array(
			array(
				'type'        => 'textfield',
				'heading'     =>esc_html__( 'Name', 'finance' ),
				'param_name'  => 'name'
			),

			array(
				'type'       => 'attach_image',
				'heading'    =>esc_html__( 'Image', 'finance' ),
				'param_name' => 'image'
			),

			array(
				'type'             => 'textfield',
				'heading'          =>esc_html__( 'Subtitle', 'finance' ),
				'param_name'       => 'subtitle',
			),

			array(
				'type'       => 'textarea',
				'heading'    =>esc_html__( 'Content', 'finance' ),
				'param_name' => 'content'
			),

			array(
				'type'       => 'textfield',
				'heading'    =>esc_html__( 'Facebook URL', 'finance' ),
				'param_name' => 'facebook'
			),

			array(
				'type'       => 'textfield',
				'heading'    =>esc_html__( 'Twitter URL', 'finance' ),
				'param_name' => 'twitter'
			),

			array(
				'type'       => 'textfield',
				'heading'    =>esc_html__( 'LinkedIn URL', 'finance' ),
				'param_name' => 'linkedin'
			),

			array(
				'type'       => 'textfield',
				'heading'    =>esc_html__( 'Google+ URL', 'finance' ),
				'param_name' => 'google'
			),

			array(
				'type' => 'textfield',
				'heading' =>esc_html__( 'Read More Link', 'finance' ),
				'param_name' => 'link',
				'description' =>esc_html__( 'Enter custom url for read more button', 'finance' )
			),

			array(
				'type' => 'textfield',
				'heading' =>esc_html__( 'Read More Text', 'finance' ),
				'param_name' => 'text',
				'description' =>esc_html__( 'Enter custom text for read more button', 'finance' ),
				'value' =>esc_html__( 'View Profile', 'finance' )
			),

			array(
				'type'       => 'textfield',
				'heading'    =>esc_html__( 'Extra Class', 'finance' ),
				'param_name' => 'class'
			),

			array(
				'type'       => 'checkbox',
				'heading'    =>esc_html__( 'Show Social Icons On Hover', 'finance' ),
				'param_name' => 'hover_show_icons',
				'value'      => array(
					__( 'Yes, please', 'finance' ) => 'yes'
				)
			),

			array(
				'type' => 'css_editor',
				'param_name' => 'css',
				'group' =>esc_html__( 'Design Options', 'finance' )
			)
		)
	) );
}

add_shortcode( 'member', 'themesflat_shortcode_member' );
add_shortcode( 'teammember_slider', 'themesflat_shortcode_teammember_slider' );

/**
 * Testimonial shortcode handle
 * 
 * @param   string  $atts  Shortcode attributes
 * @return  void
 */
function themesflat_shortcode_member( $atts, $content = null ) {
	$atts = shortcode_atts( apply_filters( 'finance/shortcode/member_atts', array(
		'class'    => '',
		'css'      => '',
		
		'name'     => 'John Doe',
		'subtitle' => '',
		'image'    => '',

		'facebook' => '',
		'twitter'  => '',
		'linkedin' => '',
		'google'   => '',
		'link'  => '',
		'text'  => 'View Profile',
	) ), $atts );

	$member_image = '';
	$member_info = sprintf( '<h3 class="team-name">%s</h3>', wp_kses_post( $atts['name'] ) );
	$class = apply_filters( 'themesflat/shortcode/member_class', array( 'flat-team', $atts['class'] ), $atts );

	if ( ! empty( $atts['image'] ) ) {
		if ( is_numeric( $atts['image'] ) && $images = wp_get_attachment_image_src( $atts['image'], 'full' ) )
			$atts['image'] = array_shift( $images );

		$class[] = 'has-image';
		$member_image = sprintf( '
			<div class="team-image">
				<img src="%s" alt="%s" />
			</div>
		', esc_attr( $atts['image'] ), esc_attr( $atts['name'] ) );
	}

	if ( ! empty( $atts['subtitle'] ) )
		$member_info.= sprintf( '<div class="team-subtitle">%s</div>', wp_kses_post( $atts['subtitle'] ) );

	$social_links = '';

	if ( ! empty( $atts['facebook'] ) )
		$social_links.= sprintf( ' <a href="%s" data-title="Facebook" class="facebook"><i class="fa fa-facebook"></i></a>', esc_url( $atts['facebook'] ) );

	if ( ! empty( $atts['twitter'] ) )
		$social_links.= sprintf( ' <a href="%s" data-title="Twitter" class="twitter"><i class="fa fa-twitter"></i></a>', esc_url( $atts['twitter'] ) );

	if ( ! empty( $atts['linkedin'] ) )
		$social_links.= sprintf( ' <a href="%s" data-title="LinkedIn" class="linkedin"><i class="fa fa-linkedin"></i></a>', esc_url( $atts['linkedin'] ) );

	if ( ! empty( $atts['google'] ) )
		$social_links.= sprintf( ' <a href="%s" data-title="Google Plus" class="google-plus"><i class="fa fa-google-plus"></i></a>', esc_url( $atts['google'] ) );

	if ( ! empty( $social_links ) )
		$social_links = sprintf( '<div class="social-links">%s</div>', $social_links );

	$box_readmore = '';

	if ( ! empty( $atts['link'] ) && ! empty( $atts['text'] ) ) {
		$box_readmore = sprintf( '
			<p class="box-readmore">
				<a href="%s">%s</a>
			</p>', esc_url( $atts['link'] ), esc_html( $atts['text'] ) );
	}

	return sprintf( '
		<div class="%s">
			%s			
			<div class="team-info">	
				%s			
				<div class="team-desc">%s</div>
				%s
				%s
			</div>
		</div>
	', esc_attr( implode( ' ', $class ) ), $member_image, $member_info, $content, $social_links
	 ,$box_readmore );
}

/**
 * This function will be use to handle testimonial slider
 * shortcode
 * 
 * @param   string  $atts     Shortcode attributes
 * @param   string  $content  Shortcode content
 * @return  string
 */
function themesflat_shortcode_teammember_slider( $atts, $content = null ) {
	$atts = shortcode_atts( array(		
		'margin'           => '30',
		'slides_per_view' => '1',
		'autoplay'        => '',
		'hide_control'    => '',
		'hide_buttons'    => '',		
		'class'           => '',
		'css'             => ''
	), $atts );
	
	$config = $atts;

	unset( $config['class'] );
	unset( $config['css'] );

	$class = apply_filters( 'themesflat/shortcode/testimonial_slider_class', array( 'testimonial-slider', $atts['class'] ), $atts );

	// Enqueue shortcode assets
	wp_enqueue_script( 'finance-carousel' );
	
	return sprintf( '
		<div class="%s" data-margin="%s" data-slides_per_view="%s" data-autoplay="%s" data-hide_control="%s" data-hide_buttons="%s">			
			%s				
		</div>
	', implode( ' ', $class ), esc_attr( $atts['margin'] ) , esc_attr( $atts['slides_per_view'] ), esc_attr( $atts['autoplay'] ), esc_attr( $atts['hide_control'] ), esc_attr( $atts['hide_buttons'] ),  do_shortcode( $content ) );
}

