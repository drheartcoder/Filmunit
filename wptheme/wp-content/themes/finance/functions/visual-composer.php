<?php
/**
 * @package     WordPress
 * @subpackage  Themes
 * @author      PetterPhan <themesflat@gmail.com>
 */
if ( ! class_exists( 'Vc_Manager' ) )
	return;

vc_set_shortcodes_templates_dir( get_template_directory() . '/tpl/vc' );

if ( ! function_exists( 'themesflat_vs_params' ) ) {
	add_action( 'admin_init', 'themesflat_vs_params' );

	/**
	 * This action will register custom parameter for visual composer
	 * shortcodes
	 * 
	 * @return  void
	 */
	function themesflat_vs_params() {
		/**
		 * Row params
		 */
		vc_add_param( 'vc_row', array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Font Color', 'finance' ),
			'param_name'       => 'font_color',
			'description'      => esc_html__( 'Select font color', 'finance' ),
			'edit_field_class' => 'vc_col-md-6 vc_column'
		) );

		vc_add_param( 'vc_row', array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Row ID', 'finance' ),
			'param_name'  => 'el_id',
			'description' => esc_html__( 'Enter custom ID for this row', 'finance' ),
		) );

		vc_add_param( 'vc_row', array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Enable Content Width 100%', 'finance' ),
			'param_name'  => 'width_100',
			'value'       => array(
				esc_html__( 'Yes, please', 'finance' ) => 'yes'
			)
		) );

		vc_add_param( 'vc_row', array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Enable overlay Effect', 'finance' ),
			'param_name'  => 'row_overlay',
			'group'       => esc_html__( 'Design options', 'finance' ),
			'value'       => array(
				esc_html__( 'Yes, please', 'finance' ) => 'yes'
			)
		) );

		vc_add_param( 'vc_row', array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Overlay Color', 'finance' ),
			'param_name'       => 'overlay_color',
			'description'      => esc_html__( 'Select overlay color', 'finance' ),
			'edit_field_class' => 'vc_col-md-6 vc_column'
		) );


		vc_add_param( 'vc_row', array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Parallax Animation Speed', 'finance' ),
			'param_name'  => 'parallax_speed',
			'group'       => esc_html__( 'Design options', 'finance' ),
			'value'       => 4
		) );

		vc_add_param( 'vc_row', array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Parallax X Offset', 'finance' ),
			'param_name'  => 'parallax_x',
			'group'       => esc_html__( 'Design options', 'finance' ),
			'value'       => 4
		) );

		vc_add_param( 'vc_row', array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Parallax Y Offset', 'finance' ),
			'param_name'  => 'parallax_y',
			'group'       => esc_html__( 'Design options', 'finance' ),
			'value'       => 4
		) );

		/**
		 * Tabs params
		 */
		vc_add_param( 'vc_tabs', array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Tabs Style', 'finance' ),
			'param_name'  => 'style',
			'value'       => array(
				esc_html__( 'Tab Style 1', 'finance' ) => 'style_1',
				esc_html__( 'Tab Style 2', 'finance' ) => 'style_2'
			)
		) );

		vc_add_param( 'vc_tabs', array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Tabs Alignment', 'finance' ),
			'param_name'  => 'align',
			'value'       => array(
				esc_html__( 'Left', 'finance' ) => 'left',
				esc_html__( 'Center', 'finance' ) => 'center',
				esc_html__( 'Right', 'finance' ) => 'right'
			)
		) );

		/**
		 * Tour
		 */
		vc_add_param( 'vc_tour', array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Navigator Position', 'finance' ),
			'param_name'  => 'align',
			'value'       => array(
				esc_html__( 'Left', 'finance' ) => 'left',
				esc_html__( 'Right', 'finance' ) => 'right'
			)
		) );

		vc_add_param( 'vc_tour', array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Style', 'finance' ),
			'param_name'  => 'style',
			'value'       => array(
				esc_html__( 'Style 1', 'finance' ) => 'style_1',
				esc_html__( 'Style 2', 'finance' ) => 'style_2'
			)
		) );

		/**
		 * Accordion
		 */
		vc_add_param( 'vc_accordion', array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Style', 'finance' ),
			'param_name'  => 'style',
			'value'       => array(
				esc_html__( 'Style 1', 'finance' ) => 'style_1',
				esc_html__( 'Style 2', 'finance' ) => 'style_2'
			)
		) );

		/**
		 * Progress Bar
		 */
		vc_add_param( 'vc_progress_bar', array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Bars Style', 'finance' ),
			'param_name'  => 'style',
			'value'       => array(
				esc_html__( 'Style 1', 'finance' ) => 'style_1',
				esc_html__( 'Style 2', 'finance' ) => 'style_2'
			)
		) );

		/**
		 * Single Image
		 */
		vc_add_param( 'vc_single_image', array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Enable Lightbox For This Image', 'finance' ),
			'param_name'  => 'lightbox',
			'value'       => array(
				esc_html__( 'Yes, please', 'finance' ) => 'yes'
			)
		) );
	}
}


if ( ! function_exists( 'themesflat_vc_scripts' ) ) {
	add_action( 'wp_enqueue_scripts', 'themesflat_vc_scripts', 999 );

	/**
	 * Unregister visual composer styles and scripts
	 * 
	 * @return  void
	 */
	function themesflat_vc_scripts() {		
		wp_deregister_style( 'prettyphoto' );
		wp_deregister_style( 'isotope' );
		wp_deregister_style( 'flexslider' );
		wp_deregister_style( 'waypoints' );
	}
}
