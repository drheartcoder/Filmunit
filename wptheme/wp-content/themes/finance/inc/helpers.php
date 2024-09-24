<?php
/**
 * WARNING: This file is part of the finance theme. DO NOT edit
 * this file under any circumstances.
 */

/**
 * Return the built-in header styles for this theme
 *
 * @return  array
 */
Class themesflat_options_helpers {
	public function recognize_control_class( $name ) {
        $segments = explode( '-', $name );
        $segments = array_map( 'ucfirst', $segments );
        
        return implode( '', $segments );
    }
}

function themesflat_predefined_header_styles() {
	static $styles;

	if ( empty( $styles ) ) {
		$styles = apply_filters( 'themesflat/header_styles', array(
			'header-v1' => esc_html__( 'Classic', 'finance' ),
			'header-v2' => esc_html__( 'Header style 02', 'finance' ),
			'header-v4' => esc_html__( 'Modern', 'finance' ),
		) );
	}

	return $styles;
}

/**
 * Render header style this theme
 *
 * @return  array
 */
function themesflat_render_header_styles() {
	static $header_style;
	
	if ( themesflat_meta( 'custom_header' ) == 1 ) {
		$header_style = themesflat_meta( 'header_style' );
	} else {
		$header_style = get_theme_mod( 'header_style', 'Header-v1' );
	}

	return $header_style;
}

function themesflat_available_social_icons() {
	$icons = apply_filters( 'op_available_social_icons', array(
		'twitter'        => array( 'icon_class' => 'fa-twitter', 'title' => 'Twitter' ),
		'facebook'       => array( 'icon_class' => 'fa-facebook', 'title' => 'Facebook' ),
		'google-plus'    => array( 'icon_class' => 'fa-google-plus', 'title' => 'Google+' ),
		'pinterest'      => array( 'icon_class' => 'fa-pinterest', 'title' => 'Pinterest' ),
		'instagram'      => array( 'icon_class' => 'fa-instagram', 'title' => 'Instagram' ),
		'youtube'        => array( 'icon_class' => 'fa-youtube-play', 'title' => 'Youtube' ),
		'vimeo'          => array( 'icon_class' => 'fa-vimeo-square', 'title' => 'Vimeo' ),
		'linkedin'       => array( 'icon_class' => 'fa-linkedin', 'title' => 'LinkedIn' ),
		'behance'        => array( 'icon_class' => 'fa-behance', 'title' => 'Behance' ),
		'bitcoin'        => array( 'icon_class' => 'fa-bitcoin', 'title' => 'Bitcoin' ),
		'bitbucket'      => array( 'icon_class' => 'fa-bitbucket', 'title' => 'BitBucket' ),
		'codepen'        => array( 'icon_class' => 'fa-codepen', 'title' => 'Codepen' ),
		'delicious'      => array( 'icon_class' => 'fa-delicious', 'title' => 'Delicious' ),
		'deviantart'     => array( 'icon_class' => 'fa-deviantart', 'title' => 'DeviantArt' ),
		'digg'           => array( 'icon_class' => 'fa-digg', 'title' => 'Digg' ),
		'dribbble'       => array( 'icon_class' => 'fa-dribbble', 'title' => 'Dribbble' ),
		'flickr'         => array( 'icon_class' => 'fa-flickr', 'title' => 'Flickr' ),
		'foursquare'     => array( 'icon_class' => 'fa-foursquare', 'title' => 'Foursquare' ),
		'github'         => array( 'icon_class' => 'fa-github-alt', 'title' => 'Github' ),
		'jsfiddle'       => array( 'icon_class' => 'fa-jsfiddle', 'title' => 'JSFiddle' ),
		'reddit'         => array( 'icon_class' => 'fa-reddit', 'title' => 'Reddit' ),
		'skype'          => array( 'icon_class' => 'fa-skype', 'title' => 'Skype' ),
		'slack'          => array( 'icon_class' => 'fa-slack', 'title' => 'Slack' ),
		'soundcloud'     => array( 'icon_class' => 'fa-soundcloud', 'title' => 'SoundCloud' ),
		'spotify'        => array( 'icon_class' => 'fa-spotify', 'title' => 'Spotify' ),
		'stack-exchange' => array( 'icon_class' => 'fa-stack-exchange', 'title' => 'Stack Exchange' ),
		'stack-overflow' => array( 'icon_class' => 'fa-stack-overflow', 'title' => 'Stach Overflow' ),
		'steam'          => array( 'icon_class' => 'fa-steam', 'title' => 'Steam' ),
		'stumbleupon'    => array( 'icon_class' => 'fa-stumbleupon', 'title' => 'Stumbleupon' ),
		'tumblr'         => array( 'icon_class' => 'fa-tumblr', 'title' => 'Tumblr' ),
		'rss'            => array( 'icon_class' => 'fa-rss', 'title' => 'RSS' )
	) );

	$icons['__icons_ordering__'] = array_keys( $icons );

	return $icons;
}

function themes_predefined_background_patterns() {
	static $patterns;

	if ( empty( $patterns ) || ! is_array( $patterns ) ) {
		$patterns = array();
		$template_directory = get_template_directory();
		$stylesheet_directory = get_stylesheet_directory();

		// Find background pattern from template's assets
		foreach( glob( THEMESFLAT_LINK . '/images/controls/patterns/*' ) as $file ) {
			if ( is_dir( $file ) )
				continue;

			$patterns['parent_' . basename($file)] = THEME_URL . '/images/controls/patterns/' . basename($file);
		}

		if ( $template_directory != $stylesheet_directory ) {
			if ( is_dir( THEMESFLAT_LINK . '/images/controls/patterns' ) ) {
				// Find background patterns from child theme's assets
				foreach( glob( THEMESFLAT_LINK . '/images/controls/patterns/*' ) as $file ) {
					if ( is_dir( $file ) )
						continue;

					$patterns['child_' . basename($file)] = THEME_URL . '/images/controls/patterns/' . basename($file);
				}
			}
		}

		$patterns = apply_filters( 'themesflat/themes_predefined_background_patterns', $patterns );
	}

	return $patterns;
}

/**
 * Menu fallback
 */
function themesflat_menu_fallback() {
	echo '<a class="menu-fallback" href="' . esc_url(admin_url('nav-menus.php')) . '">' . esc_html__( 'Create a Menu', 'finance' ) . '</a>';
}

function themesflat_esc_attr($attr) {
	echo esc_attr($attr);
}

function themesflat_esc_html($attr) {
	echo esc_html($attr);
}

/**
 * Change the excerpt length
 */
function themesflat_excerpt_length( $length ) {  
  	$excerpt = themesflat_choose_opt('blog_archive_post_excepts_length');
  	return $excerpt;
}

add_filter( 'excerpt_length', 'themesflat_excerpt_length', 999 );

/**
 * Blog layout
 */
function themesflat_blog_layout() {
	if ( themesflat_meta('enable_custom_layout') == 1 ) {
		$blog_layout = themesflat_meta('sidebar_layout');
	}
	else {
		if ( is_home() ) {
			$blog_layout = themesflat_get_opt( 'blog_layout' );
		} else {
			$blog_layout = themesflat_get_opt( 'page_layout' );
		}
		
	}
	return $blog_layout;
}

function themesflat_font_style($style) {
	if (strlen($style) > 4) {
	  	switch (substr($style, 0,3)) {
			case 'reg':
			    $a[0] = '400';
			    $a[1] = 'normal';
			break;
			case 'ita':
			    $a[0] = '400';
			    $a[1] = 'italic';			    
			break;
			default:
			    $a[0] = substr($style, 0,3);
			    $a[1] = substr($style, 4);
			break;
		}
		  
	}
	else {
	  	$a[0] = $style;
	  	$a[1] = 'normal';
	}
	return $a;
}

/**
 * Get post meta, using rwmb_meta() function from Meta Box class
 */
function themesflat_meta( $key) {
	global $post;
	if (!is_null($post)) :
	    return get_post_meta( $post->ID,$key, true );
	endif;
}

/**
 * Move_comment_field_to_bottom
 */
function themesflat_move_comment_field_to_bottom( $fields ) {
	$comment_field = $fields['comment'];
	unset( $fields['comment'] );
	$fields['comment'] = $comment_field;
	return $fields;
}

add_filter( 'comment_form_fields', 'themesflat_move_comment_field_to_bottom' );

if ( ! function_exists( 'themesflat_favicon' ) ) {
	add_action( 'wp_head', 'themesflat_favicon' );

	/**
	 * Display the custom favicon setup for the theme
	 *	 
	 * @return  void
	 */
	 
	function themesflat_favicon() {
		if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) {		
			printf ('<link rel="shortcut icon" href="'.esc_url( THEMESFLAT_LINK . 'icon/favicon.png').'" />');		
		}
	}
}

if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) ) :
	/**
	 * Filters wp_title to print a neat <title> tag based on what is being viewed.
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep Optional separator.
	 * @return string The filtered title.
	 */
	function themesflat_wp_title( $title, $sep ) {
		if ( is_feed() ) {
			return $title;
		}

		global $page, $paged;

		// Add the blog name
		$title .= get_bloginfo( 'name', 'display' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title .= " $sep $site_description";
		}

		// Add a page number if necessary:
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title .= " $sep " . sprintf( esc_html__( 'Page %s', 'finance' ), max( $paged, $page ) );
		}

		return $title;
	}

	add_filter( 'wp_title', 'themesflat_wp_title', 10, 2 );

	/**
	 * Title shim for sites older than WordPress 4.1.
	 *
	 * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
	 * @todo Remove this function when WordPress 4.3 is released.
	 */
	if ( ! function_exists( '_wp_render_title_tag' ) ) {
		function themesflat_render_title() {
			?>
			<title><?php wp_title( '|', true, 'right' ); ?></title>
			<?php
		}
		add_action( 'wp_head', 'themesflat_render_title' );
	}
	
endif;

	function themesflat_get_json($key) {
		if ( get_theme_mod($key) == '' ) return themesflat_customize_default_options2($key);
		if (!is_array(get_theme_mod($key))) {
        $decoded_value = json_decode(str_replace('&quot;', '"',  get_theme_mod( $key )), true );
	    }
	    else {
	    	$decoded_value = get_theme_mod($key);
	    }
        return $decoded_value;
	}

	function themesflat_decode($value) {
		if (!is_array($value)) {
            $decoded_value = json_decode(str_replace('&quot;', '"',  $value) , true );
        }
        else {
            $decoded_value = $value;
        }
        return $decoded_value;
	}

	function themesflat_get_opt( $key ) {
		return get_theme_mod( $key, themesflat_customize_default_options2( $key ) );
	}

	function themesflat_dynamic_sidebar($sidebar) {
		if ( is_active_sidebar ( $sidebar ) ) {
                dynamic_sidebar( $sidebar );        
            } else {         
                if ( is_user_logged_in() ) {                  
                    $message = sprintf( 'This is the %s widget area.Please go to <a href="%s">Admin</a> to this area', esc_attr($sidebar), esc_url( admin_url( 'widgets.php' ) ) );
                    echo wp_kses_post ( $message, 'finance' );
                }
        }
	}

	function themesflat_choose_opt ($key) {
		$flatopts = ( get_option('flatopts') );
		if ( isset( $flatopts[$key] ) && themesflat_meta( $flatopts[$key]) == 1 ) {
			return themesflat_meta( $key );			
		}
		else 
			return themesflat_get_opt( $key );
	}


	function themesflat_load_page_menu($params) {
		if ( themesflat_meta( 'enable_custom_navigator' ) == 1 && themesflat_meta('menu_location_primary') != false ) {
			if ($params['theme_location'] == 'primary') {
				$params['menu'] = (int)themesflat_meta('menu_location_primary');
			}
		}
		return ($params);
	}

	add_filter( 'wp_nav_menu_args', 'themesflat_load_page_menu' );

 	


	function themesflat_render_social() {
 		ob_start(); 		
 		$value = themesflat_get_json('social_links');
		?>
        <ul class="flat-socials">
            <?php
            foreach ( $value as $key => $val ) {
                printf(
                    '<li>
                        <a href="%s" target="_blank" rel="alternate" title="%s">
                            <i class="fa fa-%s"></i>
                        </a>
                    </li>',
                    esc_url( $val ),
                    esc_attr( $val ),
                    esc_attr( $key )
                );
        }
            ?>
        </ul><!-- /.social -->       
 	<?php }
