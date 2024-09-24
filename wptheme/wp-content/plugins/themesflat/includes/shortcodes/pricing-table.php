<?php
/**
 * WARNING: This file is part of the themesflat theme. DO NOT edit
 * this file under any circumstances.
 */

/**
 * Register filter for append custom class name
 * that generated from visual-composer
 */

/**
 * Register shortcode into Visual Composer
 */
add_action( 'vc_before_init', 'themesflat_pricingtable_shortcode_params' );

/**
 * Register parameters for iconbox shortcode
 * 
 * @return  void
 */
function themesflat_pricingtable_shortcode_params() {
	vc_map( array(
		'base'        => 'pricingtable',
		'name'        =>esc_html__( 'Themesflat: Pricing Table', 'finance' ),
		'icon'        => 'themesflat-shortcode',
		'category'    =>esc_html__( 'Themesflat', 'finance' ),
		'params'      => array(
			array(
				'type'        => 'textfield',
				'heading'     =>esc_html__( 'Icon', 'finance' ),
				'param_name'  => 'icon',
				'description' => sprintf(esc_html__( 'Iconmoon Icon name. EX: icon-Wacom-Tablet <a href="%s" target="blank">Full list of icons</a>', 'finance' ), 'http://themesflat.com/assets/line-icons/demo.html' )
			),

			// Title
			array(
				'type'             => 'textfield',
				'heading'          =>esc_html__( 'Title', 'finance' ),
				'param_name'       => 'title',
				'edit_field_class' => 'vc_col-md-6 vc_column'
			),

			array(
				'type'       => 'dropdown',
				'heading'    =>esc_html__( 'Title Element Tag', 'finance' ),
				'param_name' => 'tag',
				'value'      => array(
					'h2' => 'h2',
					'h3' => 'h3',
					'h4' => 'h4',
					'h5' => 'h5',
					'h6' => 'h6'
				)
			),

			array(
				'type'       => 'textarea',
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
				'heading' =>esc_html__( 'Price', 'finance' ),
				'param_name' => 'price',
				'description' =>esc_html__( 'Enter price for item', 'finance' )
			),

			array(
				'type' => 'textfield',
				'heading' =>esc_html__( 'Unit', 'finance' ),
				'param_name' => 'unit',
				'description' =>esc_html__( 'Enter unit count for item', 'finance' )
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

add_shortcode( 'pricingtable', 'themesflat_shortcode_pricingtable' );

/**
 * Pricingtable shortcode handle
 * 
 * @param   array  $atts  Shortcode attributes
 * @return  void
 */
function themesflat_shortcode_pricingtable( $atts, $content = null ) {
	$atts = shortcode_atts( apply_filters( 'themesflat/shortcode/pricingtable_atts', array(
		'class' => '',
		
		// Icon style
		'icon'  => 'cog',
		'image' => '',
		
		// Box style
		'title' =>esc_html__( 'Untitled', 'sparky' ),
		'tag'   => 'h2',

		// Content
		'price' =>'',
		'unit'   => '',
		
		'link'  => '',
		'text'  => '',
		
		'css'   => ''
	) ), $atts );

	$class = apply_filters( 'themesflat/shortcode/pricingtable_class', array( 'flat-price-table', $atts['class'] ), $atts );

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

	return sprintf( '<div class="%2$s">
		%1$s
		<div class="content">			
			<%4$s class="box-title">%3$s</%4$s>
			<p>%5$s</p>
		</div>
		<div class="price">
			<span>%6$s</span>
			<p>%7$s</p>			
		</div>
		<a href="%8$s" class="flat-button bg-black">%9$s</a>
	</div>', $box_icon, esc_attr( implode( ' ', $class ) ), esc_html( $atts['title'] ), $atts['tag'], $content, $atts['price'], $atts['unit'], esc_url ( $atts['link'] ), $atts['text'], $box_readmore );
}


