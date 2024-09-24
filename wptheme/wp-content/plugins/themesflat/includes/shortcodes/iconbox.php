<?php
/**
 * WARNING: This file is part of the Themesflat theme. DO NOT edit
 * this file under any circumstances.
 */

/**
 * Register filter for append custom class name
 * that generated from visual-composer
 */

/**
 * Register shortcode into Visual Composer
 */
add_action( 'vc_before_init', 'themesflat_iconbox_shortcode_params' );

/**
 * Register parameters for iconbox shortcode
 * 
 * @return  void
 */
function themesflat_iconbox_shortcode_params() {
	vc_map( array(
		'base'        => 'iconbox',
		'name'        =>esc_html__( 'Themesflat: Icon Box', 'finance' ),
		'icon'        => 'finance-shortcode',
		'category'    =>esc_html__( 'Themesflat', 'finance' ),
		'params'      => array(
			array(
				'type'        => 'textfield',
				'heading'     =>esc_html__( 'Icon', 'finance' ),
				'param_name'  => 'icon',
				'description' => sprintf(esc_html__( 'Ionicons. EX: ion-clock <a href="%s" target="blank">Full list of icons</a>', 'finance' ), 'http://ionicons.com/' )
			),

			// Title
			array(
				'type'             => 'textfield',
				'heading'          =>esc_html__( 'Title', 'finance' ),
				'param_name'       => 'title',
				'edit_field_class' => 'vc_col-md-6 vc_column'
			),

			// Sub Title
			array(
				'type'             => 'textfield',
				'heading'          =>esc_html__( 'Sub Title', 'finance' ),
				'param_name'       => 'sub_title',
				'edit_field_class' => 'vc_col-md-6 vc_column'
			),

			array(
				'type'       => 'dropdown',
				'heading'    =>esc_html__( 'Title Element Tag', 'finance' ),
				'param_name' => 'tag',
				'value'      => array(
					'h3' => 'h3',
					'h2' => 'h2',					
					'h4' => 'h4',
					'h5' => 'h5',
					'h6' => 'h6'
				)
			),

			array(
				'type'       => 'textarea_html',
				'heading'    =>esc_html__( 'Content', 'finance' ),
				'param_name' => 'content'
			),

			array(
				'type'       => 'attach_image',
				'heading'    =>esc_html__( 'Image', 'finance' ),
				'param_name' => 'image',
				'description' =>esc_html__( 'Select image to replace the icon', 'finance' )
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
				'value' =>esc_html__( 'more...', 'finance' )
			),		

			array(
				'type'       => 'dropdown',
				'heading'    =>esc_html__( 'Icon Position', 'finance' ),
				'param_name' => 'icon_position',
				'value' => array(
					__( 'Top', 'finance' ) => 'top',
					__( 'Left', 'finance' ) => 'left',
					__( 'Left Inline', 'finance' ) => 'inline-left',
					__( 'Right', 'finance' ) => 'right'
				)
			),

			array(
				'type'       => 'dropdown',
				'heading'    =>esc_html__( 'Icon Style', 'finance' ),
				'param_name' => 'icon_style',
				'value' => array(
					__( 'Default', 'finance' )         => 'default',
					__( 'Circle', 'finance' )          => 'circle',
					__( 'Circle Outline', 'finance' )  => 'circle-outlined',
					__( 'Rounded', 'finance' )         => 'rounded',
					__( 'Rounded Outline', 'finance' ) => 'outlined',
					__( 'Square', 'finance' )          => 'square',
					__( 'Square Outline', 'finance' )  => 'square-outlined'
				)
			),

			array(
				'type'       => 'textfield',
				'heading'    =>esc_html__( 'Extra Class', 'finance' ),
				'param_name' => 'class'
			),

			array(
				'type' => 'css_editor',
				'param_name' => 'css',
				'group' =>esc_html__( 'Design Options', 'finance' )
			)
		)
	) );
}

add_filter( 'themesflat/shortcode/iconbox_atts', 'themesflat_iconbox_shortcode_atts' );

function themesflat_iconbox_shortcode_atts( $atts ) {
	$atts['icon_position'] = '';
	$atts['icon_style']    = '';	

	return $atts;
}

add_filter( 'themesflat/shortcode/iconbox_class', 'themesflat_iconbox_shortcode_class', 10, 3 );

function themesflat_iconbox_shortcode_class( $classes, $atts, $tag = '' ) {
	$classes[] = $atts['icon_position'];
	$classes[] = $atts['icon_style'];	

	return $classes;
}

add_shortcode( 'iconbox', 'themesflat_shortcode_iconbox' );

/**
 * Iconbox shortcode handle
 * 
 * @param   array  $atts  Shortcode attributes
 * @return  void
 */
function themesflat_shortcode_iconbox( $atts, $content = null ) {
	$atts = shortcode_atts( apply_filters( 'themesflat/shortcode/iconbox_atts', array(
		'class' => '',
		
		// Icon style
		'icon'  => 'cog',
		'image' => '',
		
		// Box style
		'title' =>esc_html__( 'Untitled', 'finance' ),
		'sub_title' => '',
		'tag'   => 'h3',
		
		'link'  => '',
		'text'  => '',
		
		'css'   => ''
	) ), $atts );

	$class = apply_filters( 'themesflat/shortcode/iconbox_class', array( 'iconbox', $atts['class'] ), $atts );

	if ( ! empty( $atts['image'] ) ) {
		$icon = false;

		if ( is_numeric( $atts['image'] ) && $image_src = wp_get_attachment_image_src( $atts['image'], 'full' ) ) {
			$atts['image'] = array_shift( $image_src );

			$alt  = ! empty($atts['title'])
				? $atts['title']
				: pathinfo( $atts['image'], PATHINFO_FILENAME );

			$icon = sprintf( '<img src="%s" alt="%s" />', esc_url( $atts['image'] ), esc_attr( $alt ) );
		}
	}
	elseif ( ! empty( $atts['icon'] ) ) {
		$icon = sprintf( '<span class="%s"></span>', esc_attr( $atts['icon'] ) );
		
	}
	else {
		$icon = false;
	}

	$box_icon = $icon ? sprintf( '<div class="box-icon">%s</div>', $icon ) : '';
	$box_readmore = '';

	if ( ! empty( $atts['link'] ) && ! empty( $atts['text'] ) ) {
		$box_readmore = sprintf( '
			<p class="box-readmore">
				<a href="%s">%s</a>
			</p>', esc_url( $atts['link'] ), esc_html( $atts['text'] ) );
	}

	$sub_title = '';

	if ( ! empty( $atts['sub_title'] ) ) {
		$sub_title = sprintf( '
			<div class="sub-title">
				%s
			</div>', esc_html( $atts['sub_title'] ) );
	}	

	return sprintf( '<div class="%2$s">
		<div class="box-header">
			%1$s
			<%4$s class="box-title">%3$s</%4$s>
			%7$s
		</div>
		<div class="box-content">
			%5$s
			%6$s
		</div>
	</div>', $box_icon, esc_attr( implode( ' ', $class ) ), esc_html( $atts['title'] ), $atts['tag'], wp_kses_post ($content), $box_readmore, $sub_title );
}


