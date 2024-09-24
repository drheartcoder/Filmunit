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
add_action( 'vc_before_init', 'themesflat_skill_shortcode_params' );

/**
 * Register parameters for iconbox shortcode
 * 
 * @return  void
 */
function themesflat_skill_shortcode_params() {
	vc_map( array(
		'base'        => 'skillbar',
		'name'        =>esc_html__( 'Themesflat: SkillBar', 'finance' ),
		'icon'        => 'themesflat-shortcode',
		'category'    =>esc_html__( 'Themesflat', 'finance' ),
		'params'      => array(

			// Title
			array(
				'type'             => 'textfield',
				'heading'          =>esc_html__( 'Title', 'finance' ),
				'param_name'       => 'title',
				'edit_field_class' => 'vc_col-md-6 vc_column'
			),

			array(
				'type'             => 'textfield',
				'heading'          =>esc_html__( 'Percent', 'finance' ),
				'param_name'       => 'percent',
				'value'            => 80
			),

			array(
				'type'             => 'textfield',
				'heading'          =>esc_html__( 'Duration', 'finance' ),
				'param_name'       => 'duration',
				'value'            => 3000
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

add_shortcode( 'skillbar', 'themesflat_shortcode_skillbar' );

/**
 * Iconbox shortcode handle
 * 
 * @param   array  $atts  Shortcode attributes
 * @return  void
 */
function themesflat_shortcode_skillbar( $atts, $content = null ) {
	$atts = shortcode_atts( apply_filters( 'themesflat/shortcode/skillbar_atts', array(
		'class' => '',
		
		// Box style
		'title' =>esc_html__( 'General Contracting', 'finance' ),
		'percent'   => '80',
		
		'duration'  => '3000',
		
		'css'   => ''
	) ), $atts );

	$class = apply_filters( 'themesflat/shortcode/skill_class', array( 'flat-progress', $atts['class'] ), $atts );
	
	return sprintf( '
		<div class="%1$s">
			<div class="name">%2$s</div>
			<div class="perc">%3$s</div>
			<div class="progress-bar" data-percent="%3$s" data-waypoint-active="yes">
	            <div class="progress-animate" data-duration="%4$s"></div>
	        </div>
		</div>', esc_attr( implode( ' ', $class ) ), $atts['title'], $atts['percent'], $atts['duration'] );

}



