<?php

	/*
	Plugin Name: Super Testimonial
	Plugin URI: http://themepoints.com/product/super-testimonial-pro/
	Description: Super Testimonials is a component ready to use on mobile devices and desktop devices.
	Version: 1.3
	Author: Themepoints
	Author URI: http://themepoints.com
	TextDomain: ktsttestimonial
	License: GPLv2
	*/



	if ( ! defined( 'ABSPATH' ) )
	die( "Can't load this file directly" );
	

	define('TPS_TESTIMONIAL_PLUGIN_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
	define('tps_testimonials_plugin_dir', plugin_dir_path( __FILE__ ) );
	add_filter('widget_text', 'do_shortcode');
	
	require_once( plugin_dir_path( __FILE__ ) . 'admin/tp-testimonials-admin.php');
	
	/*============================================
		Super Testimonials Load Translation
	==============================================*/
	
	function tps_super_testimonials_load_textdomain(){
		
		load_plugin_textdomain('ktsttestimonial', false, dirname( plugin_basename( __FILE__ ) ) .'/languages/' );
		
	}
	add_action('plugins_loaded', 'tps_super_testimonials_load_textdomain');
	
	/*============================================
		Super Testimonials enqueue scripts
	==============================================*/	
	
	function tps_super_testimonials_enqueue_script(){
		
		wp_enqueue_style('tps-super-font-awesome-css', TPS_TESTIMONIAL_PLUGIN_PATH.'css/font-awesome.css');
		wp_enqueue_style('tps-super-owl.carousel-css', TPS_TESTIMONIAL_PLUGIN_PATH.'css/owl.carousel.css');
		wp_enqueue_style('tps-super-style-css', TPS_TESTIMONIAL_PLUGIN_PATH.'css/theme-style.css');
		
		wp_enqueue_script('tps-super-star-js', plugins_url('js/jquery.raty-fa.js', __FILE__), array('jquery'), '2.4', true);
		wp_enqueue_script('tps-super-owl-js', plugins_url('js/owl.carousel.js', __FILE__), array('jquery'), '2.4', true);
		wp_enqueue_script('tps-super-main-js', plugins_url('js/main.js', __FILE__), array('jquery'), '2.4', true);

	}
	add_action('wp_enqueue_scripts', 'tps_super_testimonials_enqueue_script');
	
	
	function tps_super_testimonialspro_version_link( $links ) {
	   $links[] = '<a style="color:red;font-weight:bold;" href="http://themepoints.com/product/super-testimonial-pro/" target="_blank">Upgrade Pro</a>';
	   return $links;
	}
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'tps_super_testimonialspro_version_link' );	
	
	/*=====================================================
		Super Testimonials Admin enqueue scripts
	=======================================================*/
	function tps_super_testimonials_admin_enqueue_scripts(){
		global $typenow;

		if(($typenow == 'ktsprotype')){
			
	    wp_enqueue_style('tps-super-admin-css', TPS_TESTIMONIAL_PLUGIN_PATH.'admin/css/tp-testimonial-admin.css');		
		wp_enqueue_style('wp-color-picker');	
		wp_enqueue_script( 'testimonial_pro_color_picker', plugins_url('/js/color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );	
		}
	}
	add_action('admin_enqueue_scripts', 'tps_super_testimonials_admin_enqueue_scripts');


	
	/*==========================================================================
		Super Testimonials Register Shortcode
	============================================================================*/
	
	
	function tps_super_testimonials_shortcode_register($atts, $content=null) {
		extract(shortcode_atts( array(  
			'category' => '-1',
			'themes' => 'theme1',
			'columns_number' => '2',
			'order_by' => 'date',
			'order' => 'DESC',
			'number' => '-1',
			'auto_play' => 'true',
			'navigation' => 'true',
			'stars_color' => '#1a1a1a',
		), $atts));
		
		
		// 	query posts
		
		$args =	array ( 'post_type' => 'ktsprotype',
						'posts_per_page' => $number,
						'orderby' => $order_by,
						'order' => $order );
		
		if($category > -1) {
			$args['tax_query'] = array(array('taxonomy' => 'ktspcategory','field' => 'id','terms' => $category ));
		}
		
		
		
		
		$tstrndsk = rand(1,1000);
		if($themes=="theme1"){
					
		
			$testimonials_query = new WP_Query( $args );
			$result='';
			$result.='
			<style type="text/css">
 			div#testimonial-slider-'.$themes.' {
				display: block;
				overflow: hidden;
				padding-top: 10px;
			}
			.testimonial-'.$themes.'{
				text-align: center;
			}
			.testimonial-'.$themes.' .testimonial-thumb-'.$themes.'{
				width: 85px;
				height: 85px;
				border-radius: 50%;
				margin: 0 auto 40px;
				border: 4px solid #eb7260;
				overflow: hidden;
			}
			.testimonial-'.$themes.' .testimonial-thumb-'.$themes.' img{
				width: 100%;
				height: 100%;
			}
			.testimonial-'.$themes.' .testimonial-description-'.$themes.'{
				color: #8a9aad;
				font-size: 15px;
				font-style: italic;
				line-height: 24px;
				margin-bottom: 20px;
			}
			.testimonial-'.$themes.' .testimonial-description-profiles-'.$themes.'{
				margin:20px 0;
				text-align:center;
			}
			.testimonial-'.$themes.' .testimonial-description-title-'.$themes.'{
				font-size: 20px;
				color: #eb7260;
				margin-right: 20px;
				text-transform: capitalize;
			}
			.testimonial-'.$themes.' .testimonial-description-title-'.$themes.':after{
				content: "";
				margin-left: 30px;
				border-right: 1px solid #808080;
			}
			.testimonial-'.$themes.' .testimonial-description-profiles-'.$themes.' small{
				display: inline-block;
				color: #8a9aad;
				font-size: 17px;
				text-transform: capitalize;
			}
			.testimonial-'.$themes.' .testimonial-description-profiles-'.$themes.' small a, a:hover {
			  text-decoration: none;
			  box-shadow: none;
			}
			.testimonial-'.$themes.' .fa-fw {
			  text-align: center;
			  width: 1.28571em;
			  color:'.$stars_color.';
			}
			.testimonial-'.$themes.' .super-testimonial-'.$themes.' {
			  display: block;
			  overflow: hidden;
			  text-align: center;
			}
			.owl-theme .owl-controls .owl-buttons div{
				background: transparent;
				opacity: 1;
			}
			.owl-buttons{
				position: absolute;
				top: 8%;
				width: 100%;
			}
			.owl-prev{
				position: absolute;
				left:30%;
			}
			.owl-next{
				position: absolute;
				right:30%;
			}
			@media only screen and (max-width: 479px){
				.owl-prev{
					left: 10%;
				}
				.owl-next{
					right: 10%;
				}
			}
			</style>
			';
			$result.='
			<script type="text/javascript">
				jQuery(document).ready(function($){
					$("#testimonial-slider-'.$themes.'").owlCarousel({
						items:1,
						autoPlay: 1000,
						itemsDesktop:[1199,1],
						itemsDesktopSmall:[979,1],
						itemsTablet:[768,1],
						pagination: false,
						navigation:'.$navigation.',
						navigationText:["<",">"],
						autoPlay:'.$auto_play.',
					});
					$(".super-testimonial-'.$themes.'").raty({
						readOnly: true,
						score: function() {
						return $(this).attr("data-score");
						},
						number: function() {
						return $(this).attr("data-number");
						}
					});	
				});
			</script>
			';
			$result .='<div id="testimonial-slider-'.$themes.'" class="owl-carousel">';
			// Creating a new side loop
			while ( $testimonials_query->have_posts() ) : $testimonials_query->the_post();
		 
				$client_name_value = get_post_meta(get_the_ID(), 'name', true);
				$link_value = get_post_meta(get_the_ID(), 'position', true);
				$company_value = get_post_meta(get_the_ID(), 'company', true);
				$company_url = get_post_meta(get_the_ID(), 'company_website', true);
				$company_url_target = get_post_meta(get_the_ID(), 'company_link_target', true);
				$testimonial_information = get_post_meta(get_the_ID(), 'testimonial_text', true);
				$company_ratings_target = get_post_meta(get_the_ID(), 'company_rating_target', true);
				$imgurl = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) );
				
				$result .='
					<div class="testimonial-'.$themes.'">
						<div class="testimonial-thumb-'.$themes.'">
							<img src="'.$imgurl.'" alt="">
						</div>
						<p class="testimonial-description-'.$themes.'">'.$testimonial_information.'
						</p>
						<div class="super-testimonial-'.$themes.'" data-number="5" data-score="'.$company_ratings_target.'"></div>
						<div class="testimonial-description-profiles-'.$themes.'">
							<span class="testimonial-description-title-'.$themes.'">'.esc_attr($client_name_value).'</span><small><a target="'.$company_url_target.'" href="'.esc_url($company_url).'">'.$link_value.'</a></small>
						</div>
					</div>';
			endwhile;
			$result .='</div>';
			wp_reset_postdata();
			
			
			return $result; 
			
		}
		
		elseif($themes=="theme2"){
			
			$testimonials_query = new WP_Query( $args );
			$result='';
			$result.='
			<style type="text/css">
				#ktsttestimonial_list_style .fa-fw {
				  text-align: center;
				  width: 1.28571em;
				  color:'.$stars_color.';
				}
			</style>
			';
			$result.='
			<script type="text/javascript">
				jQuery(document).ready(function($){
					$(".super-testimonial-'.$themes.'").raty({
						readOnly: true,
						score: function() {
						return $(this).attr("data-score");
						},
						number: function() {
						return $(this).attr("data-number");
						}
					});	
				});
			</script>
			';
			$result .='<div class="testimonials_list_area">';
			

			// Creating a new side loop
			while ( $testimonials_query->have_posts() ) : $testimonials_query->the_post();
		 
				$client_name_value = get_post_meta(get_the_ID(), 'name', true);
				$link_value = get_post_meta(get_the_ID(), 'position', true);
				$company_value = get_post_meta(get_the_ID(), 'company', true);
				$company_url = get_post_meta(get_the_ID(), 'company_website', true);
				$company_url_target = get_post_meta(get_the_ID(), 'company_link_target', true);
				$testimonial_information = get_post_meta(get_the_ID(), 'testimonial_text', true);
				$company_ratings_target = get_post_meta(get_the_ID(), 'company_rating_target', true);
				$imgurl = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) );

				
				$result .='<div id="ktsttestimonial_list_style">
					<div class="client_names">'.$client_name_value.'</div>
					<div class="client_names_photo">
						<img src="'.$imgurl.'" alt="" class="photo" />
					</div>
					<div class="client_content"><span class="laquo">&nbsp;</span>'.$testimonial_information.'<span class="raquo">&nbsp;</span></div>
					<div class="client_content_info">
					<div class="super-testimonial-'.$themes.'" data-number="5" data-score="'.$company_ratings_target.'"></div>
						<a target="'.$company_url_target.'" href="'.$company_url.'">'.$company_value.'</a>
						<p>'.$link_value.'</p>
					</div>
				</div>';			
			
		 
			endwhile;
			wp_reset_postdata();
			$result .='</div>';
			return $result; 
			
			
		}
		elseif($themes=="theme3"){
			

			$testimonials_query = new WP_Query( $args );
			
			$result='';
			$result.='
			<style type="text/css">
			div#testimonial-slider-'.$themes.' {
				display: block;
				overflow: hidden;
				padding-top: 10px;
			}
			.testimonial-theme3-'.$themes.'{
				margin: 0 15px;
			}
			.testimonial-theme3-'.$themes.' .testimonial-theme3-description-'.$themes.'{
				position: relative;
				font-size: 16px;
				line-height:26px;
				color: #696969;
				padding: 25px 20px;
				border:1px solid #d3d3d3;
			}
			.testimonial-theme3-'.$themes.' .testimonial-theme3-description-'.$themes.':after{
				content: "";
				width: 20px;
				height: 20px;
				background: #fff;
				border-style: none none solid solid;
				border-width: 0 0 1px 1px;
				border-color: #d3d3d3;
				position: absolute;
				bottom: -11px;
				left: 6%;
				transform: skewY(-45deg);
			}
			.testimonial-theme3-'.$themes.' .testimonial-theme3-pic-'.$themes.'{
				width: 80px;
				height: 80px;
				border-radius: 50%;
				overflow: hidden;
				margin:20px 30px;
				display: inline-block;
				float: left;
			}
			.testimonial-theme3-'.$themes.' .testimonial-theme3-pic-'.$themes.' img{
				width: 100%;
				height: 100%;
			}
			.testimonial-theme3-'.$themes.' .testimonial-theme3-'.$themes.'-title{
				display: inline-block;
				text-transform: capitalize;
				margin-top: 15px;
			}
			.testimonial-theme3-'.$themes.' .testimonial-theme3-'.$themes.'-title span{
				color: #3498db;
				display: block;
				font-size:17px;
				font-weight: bold;
				margin-bottom: 10px;
			}
			.testimonial-theme3-'.$themes.' .testimonial-theme3-'.$themes.'-title small{
				display: block;
				font-size:14px;
			}
			.owl-theme .owl-controls{
				position: absolute;
				bottom: 10%;
				right: 10px;
			}
			.owl-theme .owl-controls .owl-buttons div {
			  background: #000 none repeat scroll 0 0;
			  border-radius: 0;
			  color: #fff;
			  float: left;
			  margin-right: 5px;
			  padding: 0 10px;
			}
			@media only screen and (max-width: 767px){
				.testimonial-theme3-'.$themes.' .testimonial-theme3-description-'.$themes.'{
					font-size: 14px;
				}
				.testimonial-theme3-'.$themes.' .testimonial-theme3-description-'.$themes.':after{
						left: 14%;
				}
			}
			@media only screen and (max-width: 479px){
				.owl-theme .owl-controls{
					bottom: 0;
				}
				.testimonial-theme3-'.$themes.' .testimonial-theme3-description-'.$themes.':after{
					left: 18%;
				}
			}
			</style>
			';
			$result.='
			<style type="text/css">
				.testimonial-theme3-'.$themes.' .fa-fw {
				  text-align: center;
				  width: 1.28571em;
				  color:'.$stars_color.';
				}
			</style>
			';
			$result.='
			<script type="text/javascript">
				jQuery(document).ready(function($){
					$("#testimonial-slider-'.$themes.'").owlCarousel({
						items:1,
						autoPlay: 1000,
						itemsDesktop:[1199,1],
						itemsDesktopSmall:[979,1],
						itemsTablet:[768,1],
						pagination: false,
						navigation:'.$navigation.',
						navigationText:["<",">"],
						autoPlay:'.$auto_play.',
					});
					$(".super-testimonial-'.$themes.'").raty({
						readOnly: true,
						score: function() {
						return $(this).attr("data-score");
						},
						number: function() {
						return $(this).attr("data-number");
						}
					});					
				});
			</script>
			';
			$result .='<div id="testimonial-slider-'.$themes.'" class="owl-carousel">';
			// Creating a new side loop
			while ( $testimonials_query->have_posts() ) : $testimonials_query->the_post();
		 
				$client_name_value = get_post_meta(get_the_ID(), 'name', true);
				$link_value = get_post_meta(get_the_ID(), 'position', true);
				$company_value = get_post_meta(get_the_ID(), 'company', true);
				$company_url = get_post_meta(get_the_ID(), 'company_website', true);
				$company_url_target = get_post_meta(get_the_ID(), 'company_link_target', true);
				$testimonial_information = get_post_meta(get_the_ID(), 'testimonial_text', true);
				$company_ratings_target = get_post_meta(get_the_ID(), 'company_rating_target', true);
				$imgurl = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) );

					

				
				$result .='
					<div class="testimonial-theme3-'.$themes.'">
						<p class="testimonial-theme3-description-'.$themes.'">'.$testimonial_information.'</p>
						<div class="testimonial-theme3-pic-'.$themes.'">
							<img src="'.$imgurl.'" alt="">
						</div>
						<div class="super-testimonial-'.$themes.'" data-number="5" data-score="'.$company_ratings_target.'"></div>
						<div class="testimonial-theme3-'.$themes.'-title">
							<span>'.$client_name_value.'</span>
							<small>'.$link_value.'</small>
						</div>
						
					</div>';			

		 
			endwhile;
			$result .='</div>';
			wp_reset_postdata();
			
			
			return $result; 
			
		}
		elseif($themes=="theme4"){
			

			$testimonials_query = new WP_Query( $args );
			
			$result='';
			/*$result.='
			<style type="text/css">
			
			.testimonial-theme4-'.$themes.'{
				text-align: center;
				background: #fff;
			}
			.testimonial-theme4-'.$themes.' .testimonial-theme4-pic-'.$themes.'{
				width: 100px;
				height: 100px;
				border-radius: 50%;
				border: 5px solid rgba(255,255,255,0.3);
				display: inline-block;
				margin-top: 0px;
				overflow: hidden;
				box-shadow:0 2px 6px rgba(0, 0, 0, 0.15);
				margin: 0 auto;
				display:block;
			}
			.testimonial-theme4-'.$themes.' .testimonial-theme4-pic-'.$themes.' img{
				width: 100%;
				height: 100%;
			}
			.testimonial-theme4-'.$themes.' .testimonial-theme4-description-'.$themes.'{
				font-size: 16px;
				font-style: italic;
				color: #808080;
				line-height: 30px;
				margin: 10px 0 20px;
			}
			.testimonial-theme4-'.$themes.' .testimonial-theme4-title-'.$themes.'{
				font-size: 14px;
				font-weight: bold;
				margin: 0;
				color: #333;
				text-transform: uppercase;
				text-align:center;
			}
			.testimonial-theme4-'.$themes.' .testimonial-theme4-post-'.$themes.'{
				display: block;
				font-size: 13px;
				color: #777;
				margin-bottom: 15px;
				text-transform: capitalize;
				text-align:center;
			}
			.testimonial-theme4-'.$themes.' .testimonial-theme4-post-'.$themes.':before{
				content: "";
				width: 30px;
				display: block;
				margin: 10px auto;
				border: 1px solid #d3d3d3;
			}
			.testimonial-theme4-'.$themes.' .super-testimonial-'.$themes.' {
			  display: block;
			  overflow: hidden;
			  text-align: center;
			}
			</style>
			';
			$result.='
			<style type="text/css">
				.testimonial-theme4-'.$themes.' .fa-fw {
				  text-align: center;
				  width: 1.28571em;
				  color:'.$stars_color.';
				}
			</style>
			';
			$result.='
			<script type="text/javascript">
				jQuery(document).ready(function($){
					$("#testimonial-slider-'.$themes.'").owlCarousel({
						items:1,
						autoPlay: 1000,
						itemsDesktop:[1199,1],
						itemsDesktopSmall:[979,1],
						itemsTablet:[768,1],
						pagination: false,
						autoPlay:'.$auto_play.',
					});
					$(".super-testimonial-'.$themes.'").raty({
						readOnly: true,
						score: function() {
						return $(this).attr("data-score");
						},
						number: function() {
						return $(this).attr("data-number");
						}
					});					
				});
			</script>
			';*/
			$result .='<div id="testimonial-slider-'.$themes.'" class="owl-carousel">';
			// Creating a new side loop
			while ( $testimonials_query->have_posts() ) : $testimonials_query->the_post();
		 
				$client_name_value = get_post_meta(get_the_ID(), 'name', true);
				$link_value = get_post_meta(get_the_ID(), 'position', true);
				$company_value = get_post_meta(get_the_ID(), 'company', true);
				$company_url = get_post_meta(get_the_ID(), 'company_website', true);
				$company_url_target = get_post_meta(get_the_ID(), 'company_link_target', true);
				$testimonial_information = get_post_meta(get_the_ID(), 'testimonial_text', true);
				$company_ratings_target = get_post_meta(get_the_ID(), 'company_rating_target', true);
				$imgurl = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) );

				
				$result .='
					<div class="testimonial-theme4-'.$themes.'">
						<div class="testimonial-theme4-pic-'.$themes.'">
						<img src="'.$imgurl.'" alt="">
						</div>
						<div class="testimonial-theme4-description-'.$themes.'">'.$testimonial_information.'</div>
						<h3 class="testimonial-theme4-title-'.$themes.'">'.$client_name_value.'</h3><span class="testimonial-theme4-post-'.$themes.'">'.$company_value.'</span><div class="super-testimonial-'.$themes.'" data-number="5" data-score="'.$company_ratings_target.'"></div>
					</div>';			

		 
			endwhile;
			$result .='</div>';
			wp_reset_postdata();
			
			
			return $result; 
			
		}	
		
		else{
			echo 'Sorry Not Found Any Testimonial !!';
		}
		
		  
	}  
	add_shortcode('tpsscode', 'tps_super_testimonials_shortcode_register');	
	
	
	
	
	
	
	/*==========================================================================
		Super Testimonials Shortcode Page
	============================================================================*/
	
	function tps_super_testimonials_custom_submenu_page() {
		add_submenu_page( 'edit.php?post_type=ktsprotype', 'shortcode', 'shortcode', 'manage_options', 'testimonial_pro_shortcode', 'tps_super_testimonials_custom_shortcode_callback' ); 
	}

	function tps_super_testimonials_custom_shortcode_callback() {
		
		include('includes/tps_super_testimonial_options.php');

	}
	add_action('admin_menu', 'tps_super_testimonials_custom_submenu_page');
	

	

	function tps_super_testimonials_free_redirect_options_page( $plugin ) {
		if ( $plugin == plugin_basename( __FILE__ ) ) {
			exit( wp_redirect( admin_url( 'options-general.php' ) ) );
		}
	}

	add_action( 'activated_plugin', 'tps_super_testimonials_free_redirect_options_page' );	




	// admin menu
	function tps_super_testimonials_free_plugins_options_framwrork() {
		add_options_page( 'Super Testimonial Pro Help & Features', '', 'manage_options', 'tps-testimonial-features', 'tps_super_testimonials_frees_options_framework' );
	}
	add_action( 'admin_menu', 'tps_super_testimonials_free_plugins_options_framwrork' );


	if ( is_admin() ) : // Load only if we are viewing an admin page

	function tp_accordions_options_framework_settings() {
		// Register settings and call sanitation functions
		register_setting( 'accordion_free_options', 'tp_accordion_free_options', 'tpls_accordion_free_options' );
	}
	add_action( 'admin_init', 'tp_accordions_options_framework_settings' );



	function tps_super_testimonials_frees_options_framework() {

		if ( ! isset( $_REQUEST['updated'] ) ) {
			$_REQUEST['updated'] = false;
		} ?>


		<div class="wrap about-wrap">
			<h1>Welcome to Super Testimonial - V1.3</h1>

			<div class="about-text">Thank you for using Super Testimonial plugin free version.</div>
			<strong>Submit a Review</strong>			
			<hr>

			<p>We spend plenty of time to develop a plugin like this and give you freely. If you like this plugin, please <a style="color:red;font-weight:bold" href="https://wordpress.org/support/plugin/super-testimonial/reviews/" target="_blank">rate it 5 stars</a>. If you have any problems with the plugin, please <a href="http://themepoints.com/questions-answer/" target="_blank">let us know</a> before leaving a review.</p>
			
			<hr>

			<h3>We create a <a target="_blank" href="http://themepoints.com/product/super-testimonial-pro/">premium version</a> of this plugin with some amazing cool features?</h3>
			<br>

			<hr>
			<br>
			<a target="_blank" class="button button-primary load-customize hide-if-no-customize" href="http://themepoints.com/testimonials/">Live Preview</a>
			<a target="_blank" class="button button-primary load-customize hide-if-no-customize" href="http://themepoints.com/testimonials/documentation/">Online Doc</a>
			<a target="_blank" class="button button-primary load-customize hide-if-no-customize" href="http://themepoints.com/product/super-testimonial-pro/">Unlimited License Only $14</a>
			<br>
			<br>
			<hr>

			<div class="feature-section two-col">
				<h2>Premium Version Amazing Features</h2>
				<div class="col">
					<ul>
						<li><span class="dashicons dashicons-yes"></span> All Features of the free version.</li>
						<li><span class="dashicons dashicons-yes"></span> Fully responsive.</li>
						<li><span class="dashicons dashicons-yes"></span> 20 Slider Style & 60 Ready Skin.</li>
						<li><span class="dashicons dashicons-yes"></span> 10 List Style & 30 Ready Skin.</li>
						<li><span class="dashicons dashicons-yes"></span> 05 Grid Style & 15 Ready Skin.</li>
						<li><span class="dashicons dashicons-yes"></span> 100+ Ready Shortcode.</li>
						<li><span class="dashicons dashicons-yes"></span> Highly customized for User Experience.</li>
						<li><span class="dashicons dashicons-yes"></span> Widget Ready.</li>
						<li><span class="dashicons dashicons-yes"></span> License: Unlimited Domain</li>
						<li><span class="dashicons dashicons-yes"></span> Supports unlimited Testimonial per page.</li>
						<li><span class="dashicons dashicons-yes"></span> Touch & Swipe Enable per page.</li>
						<li><span class="dashicons dashicons-yes"></span> Display Testimonial by Category.</li>
						<li><span class="dashicons dashicons-yes"></span> Testimonial order_by (Publish date, Order, Random).</li>
						<li><span class="dashicons dashicons-yes"></span> Testimonial order (DESC, ASC).</li>
						<li><span class="dashicons dashicons-yes"></span> Create Testimonial by group.</li>
						<li><span class="dashicons dashicons-yes"></span> Show all testimonials via a shortcode.</li>
						<li><span class="dashicons dashicons-yes"></span> Testimonial Slider AutoPlay Option.</li>
						<li><span class="dashicons dashicons-yes"></span> Testimonial Support Multiple Column.</li>
						<li><span class="dashicons dashicons-yes"></span> Life Time Self hosted auto updated enable.</li>
						<li><span class="dashicons dashicons-yes"></span> 24/7 dedicated support forum.</li>
						<li><span class="dashicons dashicons-yes"></span> Cross-browser compatibility.</li>
						<li><span class="dashicons dashicons-yes"></span> Use via short-codes.</li>				
						<li><span class="dashicons dashicons-yes"></span> Well Documentation.</li>				
						<li><span class="dashicons dashicons-yes"></span> & Many More...</li>				
					</ul>
				</div>
				<div class="col">
					<ul>
						<li><span class="dashicons dashicons-yes"></span> Shortcode Parameters :</li>
						<li><span class="dashicons dashicons-yes"></span> themes</li>
						<li><span class="dashicons dashicons-yes"></span> navigation</li>
						<li><span class="dashicons dashicons-yes"></span> navigation_color</li>
						<li><span class="dashicons dashicons-yes"></span> navigation_bg_color</li>
						<li><span class="dashicons dashicons-yes"></span> pagination</li>
						<li><span class="dashicons dashicons-yes"></span> auto_play</li>
						<li><span class="dashicons dashicons-yes"></span> autoplay_speed</li>
						<li><span class="dashicons dashicons-yes"></span> text_align</li>
						<li><span class="dashicons dashicons-yes"></span> stars_color</li>
						<li><span class="dashicons dashicons-yes"></span> tcontent_color</li>
						<li><span class="dashicons dashicons-yes"></span> tcontent_size</li>
						<li><span class="dashicons dashicons-yes"></span> ttitle_color</li>
						<li><span class="dashicons dashicons-yes"></span> ttitle_size</li>
						<li><span class="dashicons dashicons-yes"></span> tsubtitle_size</li>
						<li><span class="dashicons dashicons-yes"></span> tsubtitle_color</li>
						<li><span class="dashicons dashicons-yes"></span> tbg_color</li>
						<li><span class="dashicons dashicons-yes"></span> tborder_radious</li>
						<li><span class="dashicons dashicons-yes"></span> columns_number</li>
						<li><span class="dashicons dashicons-yes"></span> columns_tablet</li>
						<li><span class="dashicons dashicons-yes"></span> tsubtitle_color</li>
						<li><span class="dashicons dashicons-yes"></span> contentbg_color</li>
						<li><span class="dashicons dashicons-yes"></span> tborder_color</li>
						<li><span class="dashicons dashicons-yes"></span> And Many More</li>
					</ul>
				</div>
			</div>

			<h2><a href="http://themepoints.com/product/super-testimonial-pro/" class="button button-primary button-hero" target="_blank">Buy Unlimited License Only $14</a>
			</h2>
			<br>
			<br>
			<br>
			<br>

		</div>

		<?php
	}


	endif;  // EndIf is_admin()




	register_activation_hook( __FILE__, 'tps_super_testimonial_free_plugin_active_hook' );
	add_action( 'admin_init', 'tps_super_testimonial_free_main_active_redirect_hook' );

	function tps_super_testimonial_free_plugin_active_hook() {
		add_option( 'tps_super_testimonial_plugin_active_free_redirect_hook', true );
	}

	function tps_super_testimonial_free_main_active_redirect_hook() {
		if ( get_option( 'tps_super_testimonial_plugin_active_free_redirect_hook', false ) ) {
			delete_option( 'tps_super_testimonial_plugin_active_free_redirect_hook' );
			if ( ! isset( $_GET['activate-multi'] ) ) {
				wp_redirect( "options-general.php?page=tps-testimonial-features" );
			}
		}
	}


?>