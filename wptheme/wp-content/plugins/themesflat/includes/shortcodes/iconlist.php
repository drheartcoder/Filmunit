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
    class WPBakeryShortCode_IconList extends WPBakeryShortCodesContainer {
    }
}

/**
 * Register filter for append custom class name
 * that generated from visual-composer
 */
add_filter( 'themekit/shortcode/iconlist_class', 'themesflat_custom_shortcodes_class', 10, 3 );
add_filter( 'themekit/shortcode/iconlist_item_class', 'themesflat_custom_shortcodes_class', 10, 3 );

/**
 * Register shortcode into Visual Composer
 */
add_action( 'vc_before_init', 'themesflat_iconlist_shortcode_params' );

function themesflat_iconlist_shortcode_params() {
	/**
	 * Map the iconlist slider shortcode
	 */
	vc_map( array(
		'name'                    =>esc_html__( 'themesflat: Icon List', 'finance' ),
		'base'                    => 'iconlist',
		'as_parent'               => array( 'only' => 'iconlist_item' ), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
		'content_element'         => true,
		'show_settings_on_create' => false,
		'category'    =>esc_html__( 'Themesflat', 'finance' ),
		'params'                  => array(
			array(
				'type' => 'textfield',
				'heading' =>esc_html__( 'List Icon', 'finance' ),
				'param_name' => 'icon',
				'description' =>esc_html__( 'Default icon for all items in the list', 'finance' )
			),
			array(
				'type' => 'attach_image',
				'heading' =>esc_html__( 'List Image', 'finance' ),
				'param_name' => 'image',
				'description' =>esc_html__( 'Default image for all items in the list', 'finance' )
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
	 * Map the single iconlist_item item
	 */
	vc_map( array(
		'base'        => 'iconlist_item',
		'name'        =>esc_html__( 'themesflat: Icon List Item', 'finance' ),
		'icon'        => 'themesflat-shortcode',
		'category'    =>esc_html__( 'Themesflat', 'finance' ),
		'as_child'    => array( 'only' => 'iconlist' ),
		'params'      => array(
			array(
				'type' => 'textfield',
				'heading' =>esc_html__( 'List Icon', 'finance' ),
				'param_name' => 'icon',
				'description' =>esc_html__( 'Default icon for all items in the list', 'finance' )
			),
			array(
				'type' => 'attach_image',
				'heading' =>esc_html__( 'List Image', 'finance' ),
				'param_name' => 'image',
				'description' =>esc_html__( 'Default image for all items in the list', 'finance' )
			),
			array(
				'type' => 'textarea',
				'heading' =>esc_html__( 'Content', 'finance' ),
				'param_name' => 'content'
			),
			array(
				'type' => 'checkbox',
				'heading' =>esc_html__( 'Enable Icon Circle Style', 'finance' ),
				'param_name' => 'circle_style',
				'value' => array(
					__( 'Yes, please', 'finance' ) => 'yes'
				)
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
		)
	) );
}



add_filter( 'themekit/shortcode/iconlist_item_atts', 'themesflat_iconlist_shortcode_atts' );

function themesflat_iconlist_shortcode_atts( $atts ) {
	$atts['circle_style'] = '';

	return $atts;
}



add_filter( 'themekit/shortcode/iconlist_item_class', 'themesflat_iconlist_shortcode_class', 10, 2 );

function themesflat_iconlist_shortcode_class( $classes, $atts ) {
	if ( $atts['circle_style'] == 'yes' )
		$classes[] = 'circle';

	return $classes;
}

add_shortcode( 'iconlist', 'themekit_shortcode_iconlist' );
add_shortcode( 'iconlist_item', 'themekit_shortcode_iconlist_item' );

/**
 * Iconlist shortcode handle
 * 
 * @param   array  $atts  Shortcode attributes
 * @return  void
 */
function themekit_shortcode_iconlist( $atts, $content = null ) {
	$atts = shortcode_atts( apply_filters( 'themekit/shortcode/iconlist_atts', array(
		'class' => '',
		'css'   => '',
		
		// Icon style
		'icon'  => 'cog',
		'image' => ''
	) ), $atts );

	$class = apply_filters( 'themekit/shortcode/iconlist_class', array( 'iconlist', $atts['class'] ), $atts );
	$children = array();

	if ( preg_match_all( '/\[iconlist_item([^\]]+)\](.*?)\[\/iconlist_item\]/is', $content, $matches ) ) {
		foreach ( $matches[1] as $index => $attributes ) {
			$_attributes = shortcode_parse_atts( trim( $attributes ) );
			$_content = trim( $matches[2][$index] );

			if ( ! isset( $_attributes['icon'] ) && ! empty( $atts['icon'] ) )
				$_attributes['icon'] = $atts['icon'];

			if ( ! isset( $_attributes['image'] ) && ! empty( $atts['image'] ) )
				$_attributes['image'] = $atts['image'];

			$children[] = themekit_shortcode_iconlist_item( $_attributes, $_content );
		}
	}

	// Enqueue shortcode assets
	wp_enqueue_script( 'themekit-shortcodes' );

	return sprintf( '<ul class="iconlist %s">%s</ul>', esc_attr( implode( ' ', $class ) ), implode( '', $children ) );
}

function themekit_shortcode_iconlist_item( $atts, $content = null ) {
	$atts = shortcode_atts( apply_filters( 'themekit/shortcode/iconlist_item_atts', array(
		'class' => '',
		'css'   => '',
		
		// Icon style
		'icon'  => 'cog',
		'image' => ''
	) ), $atts );

	$class = apply_filters( 'themekit/shortcode/iconlist_item_class', array( $atts['class'] ), $atts );
	$icon = '';

	if ( ! empty( $atts['image'] ) ) {
		if ( is_numeric( $atts['image'] ) ) {
			$image_src = wp_get_attachment_image_src( $atts['image'], 'full' );
			$atts['image'] = array_shift( $image_src );
		}

		$alt  = ! empty($atts['title'])
			? $atts['title']
			: pathinfo( $atts['image'], PATHINFO_FILENAME );

		$icon = sprintf( '<img src="%s" alt="%s" />', esc_url( $atts['image'] ), esc_attr( $alt ) );
	}
	elseif ( ! empty( $atts['icon'] ) ) {
		$icon = sprintf( '<i class="%s"></i>', esc_attr( $atts['icon'] ) );
		
	}

	$class = esc_attr( trim( implode( ' ', $class ) ) );
	if ( ! empty( $class ) )
		$class = "class=\"{$class}\"";

	// Enqueue shortcode assets
	wp_enqueue_script( 'themekit-shortcodes' );

	return sprintf( '<li %s>%s %s</li>',
		$class,
		$icon,
		$content
	);
}

