<?php
/**
@package New Image Gallery
Plugin Name: New Image Gallery [Standard]
Plugin URI: http://awplife.com/
Description: Image gallery plugin with lightbox preview for Wordpress
Version: 0.3.8
Author: A WP Life
Author URI: http://awplife.com/
License: GPLv2 or later
Text Domain: IGP_TXTDM
Domain Path: /languages
*/

if ( ! class_exists( 'New_Image_Gallery' ) ) {

	class New_Image_Gallery {
		
		protected $protected_plugin_api;
		protected $ajax_plugin_nonce;
		
		public function __construct() {
			$this->_constants();
			$this->_hooks();
		}
		
		protected function _constants() {
			//Plugin Version
			define( 'IG_PLUGIN_VER', '0.3.5' );
			
			//Plugin Text Domain
			define("IGP_TXTDM","awl-image-gallery" );

			//Plugin Name
			define( 'IG_PLUGIN_NAME', __( 'Image Gallery', 'IGP_TXTDM' ) );

			//Plugin Slug
			define( 'IG_PLUGIN_SLUG', 'image_gallery' );

			//Plugin Directory Path
			define( 'IG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

			//Plugin Directory URL
			define( 'IG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

			/**
			 * Create a key for the .htaccess secure download link.
			 * @uses    NONCE_KEY     Defined in the WP root config.php
			 */
			define( 'IG_SECURE_KEY', md5( NONCE_KEY ) );
			
		} // end of constructor function
		
		
		/**
		 * Setup the default filters and actions
		 * @uses      add_action()  To add various actions
		 * @access    private
		 * @return    void
		 */
		protected function _hooks() {
			
			//Load text domain
			add_action( 'plugins_loaded', array( $this, '_load_textdomain' ) );
			
			//add gallery menu item, change menu filter for multisite
			add_action( 'admin_menu', array( $this, '_srgallery_menu' ), 101 );
			
			//add gallery menu item, change menu filter for multisite
			add_action( 'admin_menu', array( $this, '_featured_menu' ), 105 );
			
			//Create Image Gallery Custom Post
			add_action( 'init', array( $this, '_New_Image_Gallery' ));
			
			//Add meta box to custom post
			add_action( 'add_meta_boxes', array( $this, '_admin_add_meta_box' ) );
			 
			//loaded during admin init 
			add_action( 'admin_init', array( $this, '_admin_add_meta_box' ) );
			
			add_action('wp_ajax_image_gallery_js', array(&$this, '_ajax_image_gallery'));
		
			add_action('save_post', array(&$this, '_ig_save_settings'));

			//Shortcode Compatibility in Text Widgets
			add_filter('widget_text', 'do_shortcode');

		} // end of hook function		
		
		/**
		 * Loads the text domain.
		 * @return    void
		 * @access    private
		 */
		public function _load_textdomain() {
			load_plugin_textdomain( 'IGP_TXTDM', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}		
		
		/**
		 * Adds the Gallery menu item
		 * @access    private
		 * @return    void
		 */
		public function _srgallery_menu() {
			$help_menu = add_submenu_page( 'edit.php?post_type='.IG_PLUGIN_SLUG, __( 'Docs', 'IGP_TXTDM' ), __( 'Docs', 'IGP_TXTDM' ), 'administrator', 'sr-doc-page', array( $this, '_ig_doc_page') );
			$plugin_upgrade_menu = add_submenu_page( 'edit.php?post_type='.IG_PLUGIN_SLUG, __( 'Upgrade Plugin', 'IGP_TXTDM' ), __( 'Upgrade Plugin', 'IGP_TXTDM' ), 'administrator', 'sr-upgrade-ig-page', array( $this, '_upgrade_ig_plugin_page') );
		}		
		
		public function _featured_menu() {
			$menu_feature_plugin = add_submenu_page( 'edit.php?post_type='.IG_PLUGIN_SLUG, __( 'Featured Plugins', 'IGP_TXTDM' ), __( 'Featured Plugins', 'IGP_TXTDM' ), 'administrator', 'fp-page', array( $this, '_ig_fp_page') );
		}		
		
		/**
		 * Image Gallery Custom Post
		 * Create gallery post type in admin dashboard.
		 * @access    private
		 * @return    void      Return custom post type.
		 */
		public function _New_Image_Gallery() {
			$labels = array(
				'name'                => _x( 'Image Gallery', 'Post Type General Name', 'IGP_TXTDM' ),
				'singular_name'       => _x( 'Image Gallery', 'Post Type Singular Name', 'IGP_TXTDM' ),
				'menu_name'           => __( 'New Image Gallery', 'IGP_TXTDM' ),
				'name_admin_bar'      => __( 'Image Gallery', 'IGP_TXTDM' ),
				'parent_item_colon'   => __( 'Parent Item:', 'IGP_TXTDM' ),
				'all_items'           => __( 'All Image Gallery', 'IGP_TXTDM' ),
				'add_new_item'        => __( 'Add New Image Gallery', 'IGP_TXTDM' ),
				'add_new'             => __( 'Add Image Gallery', 'IGP_TXTDM' ),
				'new_item'            => __( 'New Image Gallery', 'IGP_TXTDM' ),
				'edit_item'           => __( 'Edit Image Gallery', 'IGP_TXTDM' ),
				'update_item'         => __( 'Update Image Gallery', 'IGP_TXTDM' ),
				'search_items'        => __( 'Search Image Gallery', 'IGP_TXTDM' ),
				'not_found'           => __( 'Image Gallery Not found', 'IGP_TXTDM' ),
				'not_found_in_trash'  => __( 'Image Gallery Not found in Trash', 'IGP_TXTDM' ),
			);
			$args = array(
				'label'               => __( 'Image Gallery', 'IGP_TXTDM' ),
				'description'         => __( 'Custom Post Type For Image Gallery', 'IGP_TXTDM' ),
				'labels'              => $labels,
				'supports'            => array( 'title'),
				'taxonomies'          => array(),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 65,
				'menu_icon'           => 'dashicons-images-alt2',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => true,		
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
			);
			register_post_type( 'image_gallery', $args );
			
		} // end of post type function
		
		/**
		 * Adds Meta Boxes
		 * @access    private
		 * @return    void
		 */
		public function _admin_add_meta_box() {
			// Syntax: add_meta_box( $id, $title, $callback, $screen, $context, $priority, $callback_args );
			add_meta_box( '', __('Add Image', IGP_TXTDM), array(&$this, 'ig_upload_multiple_images'), 'image_gallery', 'normal', 'default' );
		}
		
		public function ig_upload_multiple_images($post) { 
				wp_enqueue_script('media-upload');
				wp_enqueue_script('awl-ig-uploader.js', IG_PLUGIN_URL . 'js/awl-ig-uploader.js', array('jquery'));
				wp_enqueue_style('awl-ig-uploader-css', IG_PLUGIN_URL . 'css/awl-ig-uploader.css');
				wp_enqueue_media();
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'awl-ig-color-picker-js', plugins_url('js/ig-color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
				?>
				<div id="slider-gallery">
					<input type="button" id="remove-all-slides" name="remove-all-slides" class="button button-large remove-all-slides" rel="" value="<?php _e('Delete All Images', IGP_TXTDM); ?>">
					<ul id="remove-slides" class="sbox">
					<?php
					$allimagesetting = unserialize(base64_decode(get_post_meta( $post->ID, 'awl_ig_settings_'.$post->ID, true)));
					if(isset($allimagesetting['slide-ids'])) {
						$count = 0;
					foreach($allimagesetting['slide-ids'] as $id) {
						$thumbnail = wp_get_attachment_image_src($id, 'medium', true);
						$attachment = get_post( $id );
						?>
						<li class="slide">
							<img class="new-slide" src="<?php echo $thumbnail[0]; ?>" alt="<?php echo get_the_title($id); ?>" style="height: 150px; width: 98%; border-radius: 8px;">
							<input type="hidden" id="slide-ids[]" name="slide-ids[]" value="<?php echo $id; ?>" />
							<!-- Image Title, Caption, Alt Text, Description-->
							<input type="text" name="slide-title[]" id="slide-title[]" style="width: 100%;" placeholder="Image Title" value="<?php echo get_the_title($id); ?>">
							<!--<textarea name="slide-desc[]" id="slide-desc[]" style="width: 280px;" placeholder="Image Description" style="height: 120px; width: 280px; border-radius: 8px;"><?php echo $attachment->post_content; ?></textarea>-->
							<!--<input type="text" name="slide-link[]" id="slide-link[]" style="width: 100%;" placeholder="Image Link URL" value="<?php echo $image_link; ?>">-->
							<input type="button" name="remove-slide" id="remove-slide" class="button remove-single-slide button-danger" style="width: 100%;" value="Delete">
						</li>
					<?php $count++; } // end of foreach
					} //end of if
					?>
					</ul>
				</div>
				
				<!--Add New Image Button-->
				<div name="add-new-slider" id="add-new-slider" class="new-slider" style="height: 160px; width: 160px; border-radius: 8px;">
					<div class="menu-icon dashicons dashicons-format-image"></div>
					<div class="add-text"><?php _e('Add Image', IGP_TXTDM); ?></div>
				</div>
				<div style="clear:left;"></div>
				<br>
				<br>
				<h1>Copy Image Gallery Shortcode</h1>
				<hr>
				<p class="input-text-wrap">
					<p><?php _e('Copy & Embed shotcode into any Page/ Post / Text Widget to display your image gallery on site.', IGP_TXTDM); ?><br></p>
					<input type="text" name="shortcode" id="shortcode" value="<?php echo "[IMG-Gal id=".$post->ID."]"; ?>" readonly style="height: 60px; text-align: center; font-size: 24px; border: 2px dashed;" onmouseover="return pulseOff();" onmouseout="return pulseStart();">
				</p>
				<br>
				<br>
				<h1><?php _e('Image Gallery Setting', IGP_TXTDM); ?></h1>
				<hr>
				<?php
				require_once('gallery-settings.php');
		} // end of upload multiple image
		
		public function _ig_ajax_callback_function($id) {
			//wp_get_attachment_image_src ( int $attachment_id, string|array $size = 'thumbnail', bool $icon = false )
			//thumb, thumbnail, medium, large, post-thumbnail
			$thumbnail = wp_get_attachment_image_src($id, 'medium', true);
			$attachment = get_post( $id ); // $id = attachment id
			?>
			<li class="slide">
				<img class="new-slide" src="<?php echo $thumbnail[0]; ?>" alt="<?php echo get_the_title($id); ?>" style="height: 150px; width: 98%; border-radius: 8px;">
				<input type="hidden" id="slide-ids[]" name="slide-ids[]" value="<?php echo $id; ?>" />
				<input type="text" name="slide-title[]" id="slide-title[]" style="width: 100%;" placeholder="Image Title" value="<?php echo get_the_title($id); ?>">
				<!--<textarea name="slide-desc[]" id="slide-desc[]" placeholder="Image Description" style="height: 120px; width: 280px;"><?php echo $attachment->post_content; ?></textarea>-->
				<!--<input type="text" name="slide-link[]" id="slide-link[]" style="width: 100%;" placeholder="Image Link URL">-->
				<input type="button" name="remove-slide" id="remove-slide" style="width: 100%;" class="button" value="Delete">
			</li>
			<?php
		}
		
		public function _ajax_image_gallery() {
			echo $this->_ig_ajax_callback_function($_POST['slideId']);
			die;
		}
		
		public function _ig_save_settings($post_id) {
			if ( isset( $_POST['ig-settings'] ) == "ig-save-settings" ) {
				$image_ids = $_POST['slide-ids'];
				$image_titles = $_POST['slide-title'];
				$i = 0;
				foreach($image_ids as $image_id) {
					$single_image_update = array(
						'ID'           => $image_id,
						'post_title'   => $image_titles[$i],
					);
					wp_update_post( $single_image_update );
					$i++;
				}				
				$awl_image_gallery_shortcode_setting = "awl_ig_settings_".$post_id;
				update_post_meta($post_id, $awl_image_gallery_shortcode_setting, base64_encode(serialize($_POST)));
			}
		}// end save setting
		
		/**
		 * Image Gallery Docs Page
		 * Create doc page to help user to setup plugin
		 * @access    private
		 * @return    void.
		 */
		public function _ig_doc_page() {
			require_once('docs.php');
		}
		
		public function _ig_fp_page() {
			require_once('featured-plugins/featured-plugins.php');
		}
			
		public function _upgrade_ig_plugin_page() {
			require_once('buy-image-gallery-premium.php');
		}
		
	} // end of class

	/**
	 * Instantiates the Class
	 * @global    object	$ig_gallery_object
	 */
	$ig_gallery_object = new New_Image_Gallery();
	require_once('shortcode.php');
} // end of class exists

?>