<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


add_shortcode('AGAL', 'nag_album_gallery_shortcode');

function nag_album_gallery_shortcode($post_id) {
	//js
	wp_enqueue_script('nag-isotope-js', AG_PLUGIN_URL .'assets/js/isotope.pkgd.js', array('jquery'), '' , true);
	wp_enqueue_script('nag-imagesloaded-js', AG_PLUGIN_URL .'assets/js/imagesloaded.pkgd.js', array('jquery'), '' , true);
	//wp_enqueue_script('nag-jquery-swipebox-js',  AG_PLUGIN_URL .'assets/js/jquery.swipebox.min.js', array( 'jquery' ), '', true );
	
	//PhotoBox Lightbox
	wp_enqueue_style('fagp-photobox-css', AG_PLUGIN_URL.'assets/lightbox/photobox/photobox.css');
	wp_enqueue_script('fagp-photobox-js', AG_PLUGIN_URL.'assets/lightbox/photobox/jquery.photobox.js', array('jquery'));

	//css
	wp_enqueue_style('nag-bootstrap-css', AG_PLUGIN_URL .'assets/css/bootstrap-front.css');
	//wp_enqueue_style('nag-swipebox-css', AG_PLUGIN_URL .'assets/css/awl-swipebox.css');
	wp_enqueue_style('nag-animate-css', AG_PLUGIN_URL .'assets/css/awl-animate.css');
	wp_enqueue_style('nag-hover-stack-style-css', AG_PLUGIN_URL .'assets/css/awl-hover-stack-style.css');
	wp_enqueue_style('nag-hover-overlay-effects-css', AG_PLUGIN_URL .'assets/css/awl-hover-overlay-effects.css');
	wp_enqueue_style('nag-hover-overlay-effects-style-css', AG_PLUGIN_URL .'assets/css/awl-hover-overlay-effects-style.css');
	
	
	ob_start();
	//output code file
	require("include/album-gallery-output.php");
	return ob_get_clean();
}
?>