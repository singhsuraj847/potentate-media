<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
@package New Album Gallery
Plugin Name: New Album Gallery
Plugin URI: https://awplife.com/
Description: A Newly Amazing Different Most Powerful Responsive Easy To Use Album Gallery Plugin For WordPress
Version: 1.3.5
Author: A WP Life
Author URI: https://awplife.com/
Text Domain: new-album-gallery
Domain Path: /languages
License: GPL2

New Album Gallery is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
New Album Gallery is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with New Album Gallery. If not, see https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html.
*/

if ( ! class_exists ( 'Awl_Album_Gallery' ) ) {
	
	class Awl_Album_Gallery {
		
		public function __construct() {
			$this->_constants();
			$this->_hooks();
		}
		
		protected function _constants() {
			
			//Plugin Version
			define( 'AG_PLUGIN_VER', '1.3.5' );
			
			//Plugin Text Domain
			define("AGP_TXTDM","new-album-gallery");
			
			//Plugin Name
			define( 'AG_PLUGIN_NAME', __( 'Album Gallery', AGP_TXTDM ) );
			
			//Plugin Slug
			define( 'AG_PLUGIN_SLUG', 'album_gallery');
			
			//Plugin Directory Path
			define( 'AG_PLUGIN_DIR', plugin_dir_path(__FILE__) );
			
			//Plugin Driectory URL
			define( 'AG_PLUGIN_URL', plugin_dir_url(__FILE__) );
			
			/**
			 * Create a key for the .htaccess secure download link.
			 * @uses    NONCE_KEY     Defined in the WP root config.php
			 */
			define( 'AGP_SECURE_KEY', md5( NONCE_KEY ) );
			
		} // end of constructor function
		
		/**
		 * Setup the default filters and actions
		 */
		protected function _hooks() {
			
			//Load Text Domain
			add_action( 'plugins_loaded', array( $this , '_load_textdomain' ) );
			
			//add gallery menu item, change menu filter for multisite
			add_action( 'admin_menu', array( $this, 'ag_gallery_menu' ), 101 );
			
			//Create Album Gallery Custom Post
			add_action( 'init', array( $this, '_Album_Gallery') );
			
			//Add Meta Box To Custom Post
			add_action( 'add_meta_boxes', array( $this, '_ag_admin_add_meta_box') );
			
			add_action( 'wp_ajax_album_gallery_js', array(&$this, 'ajax_album_gallery') );
			
			add_action( 'save_post', array( &$this, '_ag_save_settings') );
			
			//Shortcode Compatibility in Text Widegts
			add_filter( 'widget_text', 'do_shortcode');
			
			// add pfg cpt shortcode column - manage_{$post_type}_posts_columns
			add_filter( 'manage_album_gallery_posts_columns', array(&$this, 'set_album_gallery_shortcode_column_name') );
			
			// add pfg cpt shortcode column data - manage_{$post_type}_posts_custom_column
			add_action( 'manage_album_gallery_posts_custom_column' , array(&$this, 'custom_album_gallery_shodrcode_data'), 10, 2 );
			
			add_action( 'wp_enqueue_scripts', array(&$this, 'enqueue_scripts_in_header') );
		
		}// end of hook function
		
		public function enqueue_scripts_in_header() {
			wp_enqueue_script('jquery');
		}
		
		// Album Gallery cpt shortcode column before date columns
		public function set_album_gallery_shortcode_column_name($defaults) {
			$new = array();
			$shortcode = $columns['album_gallery_shortcode'];  // save the tags column
			unset($defaults['tags']);   // remove it from the columns list

			foreach($defaults as $key=>$value) {
				if($key=='date') {  // when we find the date column
				   $new['album_gallery_shortcode'] = __( 'Shortcode', AGP_TXTDM );  // put the tags column before it
				}    
				$new[$key] = $value;
			}
			return $new;  
		}
		
		// Albym Gallery cpt shortcode column data
		public function custom_album_gallery_shodrcode_data( $column, $post_id ) {
			switch ( $column ) {
				case 'album_gallery_shortcode' :
					echo "<input type='text' class='button button-primary' id='album-gallery-shortcode-$post_id' value='[AGAL id=$post_id]' style='font-weight:bold; background-color:#32373C; color:#FFFFFF; text-align:center;' />";
					echo "<input type='button' class='button button-primary' onclick='return ALBUMCopyShortcode$post_id();' readonly value='Copy' style='margin-left:4px;' />";
					echo "<span id='copy-msg-$post_id' class='button button-primary' style='display:none; background-color:#32CD32; color:#FFFFFF; margin-left:4px; border-radius: 4px;'>copied</span>";
					echo "<script>
						function ALBUMCopyShortcode$post_id() {
							var copyText = document.getElementById('album-gallery-shortcode-$post_id');
							copyText.select();
							document.execCommand('copy');
							
							//fade in and out copied message
							jQuery('#copy-msg-$post_id').fadeIn('1000', 'linear');
							jQuery('#copy-msg-$post_id').fadeOut(2500,'swing');
						}
						</script>
					";
				break;
			}
		}
		
		
		public function _load_textdomain() {
			load_plugin_textdomain( AGP_TXTDM, false, dirname( plugin_basename(__FILE__) ) .'/languages' );			
		}
		
		/* Add Gallery menu*/
		public function ag_gallery_menu() {
			$help_menu = 	add_submenu_page( 'edit.php?post_type='.AG_PLUGIN_SLUG, __( 'Column Settings', AGP_TXTDM ), __( 'Column Settings', AGP_TXTDM ), 'administrator', 'ag-column-page', array( $this, '_ag_column_page') );
			$docs_menu = 	add_submenu_page( 'edit.php?post_type='.AG_PLUGIN_SLUG, __( 'Docs', AGP_TXTDM ), __( 'Docs', AGP_TXTDM ), 'administrator', 'ag-doc-page', array( $this, '_ag_doc_page') );
			$theme_menu = 	add_submenu_page( 'edit.php?post_type='.AG_PLUGIN_SLUG, __( 'Our Theme', AGP_TXTDM ), __( 'Our Theme', AGP_TXTDM ), 'administrator', 'sr-theme-page', array( $this, '_ag_theme_page') );
		}
		
		/**
		 * Album Gallery Custom Post
		 * Create gallery post type in admin dashboard.
		*/
		public function _Album_Gallery() {
			$labels = array(
				'name'                => __( 'Album Gallery', 'post type general name', AGP_TXTDM ),
				'singular_name'       => __( 'Album Gallery', 'post type singular name', AGP_TXTDM ),
				'menu_name'           => __( 'Album Gallery', AGP_TXTDM ),
				'name_admin_bar'      => __( 'Album Gallery', AGP_TXTDM ),
				'parent_item_colon'   => __( 'Parent Item:', AGP_TXTDM ),
				'all_items'           => __( 'All Album Gallery', AGP_TXTDM ),
				'add_new_item'        => __( 'Add Album Gallery', AGP_TXTDM ),
				'add_new'             => __( 'Add Album Gallery', AGP_TXTDM ),
				'new_item'            => __( 'Album Gallery', AGP_TXTDM ),
				'edit_item'           => __( 'Edit Album Gallery', AGP_TXTDM ),
				'update_item'         => __( 'Update Album Gallery', AGP_TXTDM ),
				'search_items'        => __( 'Search Album Gallery', AGP_TXTDM ),
				'not_found'           => __( 'Album Gallery Not found', AGP_TXTDM ),
				'not_found_in_trash'  => __( 'Album Gallery Not found in Trash', AGP_TXTDM ),
			);

			$args = array(
				'label'               => __( 'Album Gallery', AGP_TXTDM ),
				'description'         => __( 'Custom Post Type For Album Gallery', AGP_TXTDM ),
				'labels'              => $labels,
				'supports'            => array( 'title'),
				'taxonomies'          => array(),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 65,
				'menu_icon'           => 'dashicons-images-alt',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
			);

			register_post_type( 'album_gallery', $args );
		}//end of post type function
		
		/**
		 * Adds Meta Boxes
		*/
		public function _ag_admin_add_meta_box() {
			// Syntax: add_meta_box( $id, $title, $callback, $screen, $context, $priority, $callback_args );
			add_meta_box( '1', __('Copy Album Gallery Shortcode', AGP_TXTDM), array(&$this, '_ag_shortcode_left_metabox'), 'album_gallery', 'side', 'default' );
			add_meta_box( '', __('Add Images', AGP_TXTDM), array(&$this, 'ag_upload_multiple_images'), 'album_gallery', 'normal', 'default' );
		}
		// album gallery copy shortcode meta box under publish button
		public function _ag_shortcode_left_metabox($post) { ?>
			<p class="input-text-wrap">
				<input type="text" name="ALBUMCopyShortcode" id="ALBUMCopyShortcode" value="<?php echo "[AGAL id=".$post->ID."]"; ?>" readonly style="height: 60px; text-align: center; width:100%;  font-size: 24px; border: 2px dashed;">
				<p id="ag-copy-code"><?php _e('Shortcode copied to clipboard!', AGP_TXTDM); ?></p>
				<p style="margin-top: 10px"><?php _e('Copy & Embed shotcode into any Page/ Post to display gallery.', AGP_TXTDM); ?></p>
			</p>
			<span onclick="copyToClipboard('#ALBUMCopyShortcode')" class="ag-copy dashicons dashicons-clipboard"></span>
			<style>
				.ag-copy {
					position: absolute;
					top: 9px;
					right: 24px;
					font-size: 26px;
					cursor: pointer;
				}
			</style>
			<script>
				jQuery( "#ag-copy-code" ).hide();
				function copyToClipboard(element) {
				  var $temp = jQuery("<input>");
				  jQuery("body").append($temp);
				  $temp.val(jQuery(element).val()).select();
				  document.execCommand("copy");
				  $temp.remove();
				  jQuery( "#ALBUMCopyShortcode" ).select();
				  jQuery( "#ag-copy-code" ).fadeIn();
				}
			</script>
			<?php
		}
		
		public function ag_upload_multiple_images($post) {
				wp_enqueue_script('jquery');
				wp_enqueue_script('media-upload');
				wp_enqueue_script('awl-ag-uploader.js', AG_PLUGIN_URL . 'assets/js/awl-ag-uploader.js', array('jquery'));
				wp_enqueue_style('awl-ag-uploader-css', AG_PLUGIN_URL . 'assets/css/awl-ag-uploader.css');
				wp_enqueue_style('awl-metabox-css', AG_PLUGIN_URL .'assets/css/metabox.css' );

				wp_enqueue_media();

				?>
				<div id="album-gallery-note">
				<p><strong><?php echo _e('Tips 1')?>:</strong> <?php echo _e('The First Image Will Be Album Gallery Cover.', AGP_TXTDM); ?></p>
				<p><strong><?php echo _e('Tips 2')?>:</strong> <?php echo _e(': Always Use Same Size Image (Height & Width Same) Album Cover For Each Gallery.', AGP_TXTDM); ?></p>
				</div>
				<div class="row">
				<!--Add New Image Button-->
				<div class="file-upload">
					<div class="image-upload-wrap">
						<input class="add-new-image-slides file-upload-input" id="add-new-image-slides" name="add-new-image-slides" value="Upload Image" />
						<div class="drag-text">
							<h3><?php _e('ADD IMAGES', AGP_TXTDM); ?></h3>
						</div>
					</div>
				</div>
			</div>
				<br><br>
				<div style="clear:left;"></div>
				<br>
				<style>
				.selectbox_position_newslide {
					border-width: 1px 1px 1px 6px !important;
					border-color: #32CC24 !important;
					width: 100% !important; 
					margin-bottom : 3px;
					margin-left: 4px;
					margin-top: 3px;
				}
				.input_box_newslide {
					border-width: 2px 2px 2px 2px !important;
					border-color: #32CC24 !important;
					width: 100% !important;
					border-radius : 5px;
					margin-bottom : 3px;
					margin-left: 4px;
				}
				</style>
				<h1 class="text-center" style="font-family:Geneva;"><?php _e('ALBUM &nbsp; GALLERY &nbsp; SETTINGS', AGP_TXTDM); ?></h1>
				<hr>
				<?php
				require_once('include/album-gallery-settings.php');
		}
		
		public function _ag_ajax_callback_function($id) {
			$thumbnail = wp_get_attachment_image_src($id, 'medium', true);
			$attachment = get_post( $id ); // $id = attachment id
			$description = $attachment->post_content; // desc
			?>
			<li class="image-slide">
				<img class="new-image-slide" src="<?php echo $thumbnail[0]; ?>" alt="<?php echo get_the_title($id); ?>" style="height: 150px; width: 98%; border-radius: 8px;">
				<input type="hidden" id="image-slide-ids[]" name="image-slide-ids[]" value="<?php echo $id; ?>" />
				<select id="image-slide-type[]" name="image-slide-type[]" class="form-control" style="width: 100%;" value="<?php echo $slide_type; ?>" >
					<option value="i" <?php if($slide_type == "i") echo "selected=selected"; ?>>Image</option>
					<option value="v" <?php if($slide_type == "v") echo "selected=selected"; ?>>Video</option>
				</select>
				<!-- Image Title-->
				<input type="text" name="image-slide-title[]" id="image-slide-title[]" style="width: 100%;" placeholder="Title Here" value="<?php echo get_the_title($id); ?>">
				<input type="text" name="image-slide-link[]" id="image-slide-link[]" style="width: 100%;" placeholder="Enter URL / ID" value="<?php echo $slide_link; ?>">
				<input type="button" name="remove-image-slide" id="remove-image-slide" style="width: 100%;" class="button" value="Delete">
			</li>
			<?php
		}
		
		public function ajax_album_gallery() {
			echo $this->_ag_ajax_callback_function($_POST['slideId']);
			die;
		}
		
		public function _ag_save_settings($post_id) {
			if(isset($_POST['ag_save_nonce'])) {
				if ( !isset( $_POST['ag_save_nonce'] ) || !wp_verify_nonce( $_POST['ag_save_nonce'], 'ag_save_settings' ) ) {
				   print 'Sorry, your nonce did not verify.';
				   exit;
				} else {
				$image_ids = $_POST['image-slide-ids'];
				$image_titles = $_POST['image-slide-title'];
				$image_types = $_POST['image-slide-type'];
				$i = 0;
				foreach($image_ids as $image_id) {
					$single_image_update = array(
						'ID'           => $image_id,
						'post_title'   => $image_titles[$i],
					);
					wp_update_post( $single_image_update );
					$i++;
					}
					$awl_album_gallery_shortcode_setting = "awl_ag_settings_".$post_id;
					update_post_meta($post_id, $awl_album_gallery_shortcode_setting, base64_encode(serialize($_POST)));
				}
			}
		}// end save setting
		
		public function _ag_column_page() {
			require('include/album-gallery-column-settings.php');
		}
		
		public function _ag_doc_page() {
			require('include/docs.php');
		}
		
		// theme page
		public function _ag_theme_page() {
			require('our-theme/awp-theme.php');
		}
		
	}// end of class
	
	$ag_gallery_object = new Awl_Album_Gallery();
	require_once('shortcode.php');
}
?>