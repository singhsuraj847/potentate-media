<?Php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//load settings
$album_gallery_settings = unserialize(base64_decode(get_post_meta( $post->ID, 'awl_ag_settings_'.$post->ID, true)));
$album_gallery_id = $post->ID;

//js
wp_enqueue_script('awl-ag-bootstrap-js',  AG_PLUGIN_URL .'assets/js/bootstrap.js', array( 'jquery' ), '', true  );
wp_enqueue_script('awl-ag-go-to-top-js',  AG_PLUGIN_URL .'assets/js/go-to-top.js', array( 'jquery' ), '', true  );
//css
wp_enqueue_style('awl-styles-css', AG_PLUGIN_URL .'assets/css/styles.css' );
wp_enqueue_style('awl-animate-css', AG_PLUGIN_URL .'assets/css/awl-animate.css' );
wp_enqueue_style('awl-bootstrap-css', AG_PLUGIN_URL .'assets/css/bootstrap.css' );
wp_enqueue_style('awl-go-to-top-css', AG_PLUGIN_URL .'assets/css/go-to-top.css');
wp_enqueue_style('awl-toogle-button-css', AG_PLUGIN_URL .'assets/css/toogle-button.css' );
wp_enqueue_style('awl-font-awesome-min-css', AG_PLUGIN_URL .'assets/css/font-awesome.min.css' );

?>
<!-- Return to Top -->
<a href="javascript:" id="return-to-top"><i class="fa fa-chevron-up"></i></a>
		<div class="row">
			<div class="col-lg-12 bhoechie-tab-container">
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 bhoechie-tab-menu">
					<div class="list-group">
						<a href="#" class="list-group-item active text-center">
							<span class="dashicons dashicons-format-image"></span><br/><?php _e('Add Images', AGP_TXTDM); ?>
						</a>
						<a href="#" class="list-group-item text-center">
							<span class="dashicons dashicons-admin-generic"></span><br/><?php _e('Config', AGP_TXTDM); ?>
						</a>
						<a href="#" class="list-group-item text-center">
							<span class="dashicons dashicons-admin-appearance"></span><br/><?php _e('Animation & Hover Effect', AGP_TXTDM); ?>
						</a>
						<a href="#" class="list-group-item text-center">
							<span class="dashicons dashicons-cart"></span><br/><?php _e('Upgrade To Pro', AGP_TXTDM); ?>
						</a>
					</div>
				</div>
				<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 bhoechie-tab">
					<div class="bhoechie-tab-content active">
						<h1><?php _e('Add Images', AGP_TXTDM); ?></h1>
						<hr>	
						<div id="album-gallery">
							<input type="button" id="remove-all-image-slides" name="remove-all-image-slides" class="button button-large remove-all-image-slides" rel="" value="<?php _e('Delete All Images', AGP_TXTDM); ?>">
							<ul id="remove-image-slides" class="imagebox">
							<?php
							$allimagesetting = unserialize(base64_decode(get_post_meta( $post->ID, 'awl_ag_settings_'.$post->ID, true)));
							if(isset($allimagesetting['image-slide-ids'])) {
								$count = 0;
							foreach($allimagesetting['image-slide-ids'] as $id) {
								$thumbnail = wp_get_attachment_image_src($id, 'medium', true);
								$attachment = get_post( $id );
								$slide_link =  $allimagesetting['image-slide-link'][$count];
								$slide_type =  $allimagesetting['image-slide-type'][$count];
								?>
								<li class="image-slide">
									<img class="new-image-slide" src="<?php echo $thumbnail[0]; ?>" alt="<?php echo get_the_title($id); ?>" style="height: 150px; width: 98%; border-radius: 8px;">
									<input type="hidden" id="image-slide-ids[]" name="image-slide-ids[]" value="<?php echo $id; ?>" />
									<select id="image-slide-type[]" name="image-slide-type[]" class="form-control selectbox_position_newslide" value="<?php echo $slide_type; ?>" >
										<option value="i" <?php if($slide_type == "i") echo "selected=selected"; ?>>Image</option>
										<option value="v" <?php if($slide_type == "v") echo "selected=selected"; ?>>Video</option>
									</select>
									<!-- Image Title-->
									<input type="text" name="image-slide-title[]" id="image-slide-title[]" class="input_box_newslide" placeholder="Title Here" value="<?php echo get_the_title($id); ?>">
									<input type="text" name="image-slide-link[]" id="image-slide-link[]" class="input_box_newslide" placeholder="Enter URL / ID" value="<?php echo $slide_link; ?>">
									<input type="button" name="remove-image-slide" id="remove-image-slide" class="button remove-single-image-slide button-danger" style="width: 100%;" value="Delete">
								</li>
							<?php $count++; } // end of foreach
							} //end of if
							?>
							</ul>
						</div>
					</div>
					<div class="bhoechie-tab-content">
						<h1><?php _e('Configuration', AGP_TXTDM); ?></h1>
						<hr>
						<!--Grid-->
						<div class="col-md-4">
							<div class="ma_field_discription">
								<h5><?php _e('Loop', AGP_TXTDM); ?></h5>
								<p><?php _e('Select if the album gallery is loopable', AGP_TXTDM); ?></p> 
							</div>
						</div>
						<div class="col-md-8">
							<div class="ma_field p-4">
								<p class="switch-field em_size_field">
									<?php if(isset($album_gallery_settings['loop_lightbox'])) $loop_lightbox = $album_gallery_settings['loop_lightbox']; else $loop_lightbox = "false"; ?>
									<input type="radio" class="form-control" id="loop_lightbox1" name="loop_lightbox" value="true" <?php if($loop_lightbox == "true") echo "checked" ; ?>>
										<label for="loop_lightbox1"><?php _e('Yes', AGP_TXTDM); ?></label>
									<input type="radio" class="form-control" id="loop_lightbox2" name="loop_lightbox" value="false" <?php if($loop_lightbox == "false") echo "checked" ; ?>> 
										<label for="loop_lightbox2"><?php _e('No', AGP_TXTDM); ?></label>				
								</p>
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="ma_field_discription">
								<h5><?php _e('Hide Close Button On Mobile', AGP_TXTDM); ?></h5>
								<p><?php _e('Select if the Close Button is displayed on mobile', AGP_TXTDM); ?></p> 
							</div>
						</div>
						<div class="col-md-8">
							<div class="ma_field p-4">
								<p class="switch-field em_size_field">
									<?php if(isset($album_gallery_settings['hide_close_btn_mobile'])) $hide_close_btn_mobile = $album_gallery_settings['hide_close_btn_mobile']; else $hide_close_btn_mobile = "false"; ?>
									<input type="radio" class="form-control" id="hide_close_btn_mobile1" name="hide_close_btn_mobile" value="true" <?php if($hide_close_btn_mobile == "true") echo "checked" ; ?>>
										<label for="hide_close_btn_mobile1"><?php _e('Hide', AGP_TXTDM); ?></label>
									<input type="radio" class="form-control" id="hide_close_btn_mobile2" name="hide_close_btn_mobile" value="false" <?php if($hide_close_btn_mobile == "false") echo "checked" ; ?>> 
										<label for="hide_close_btn_mobile2"><?php _e('Show', AGP_TXTDM); ?></label>			
								</p>
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="ma_field_discription">
								<h5><?php _e('Remove Bars On Mobile', AGP_TXTDM); ?></h5>
								<p><?php _e('Select if the gallery bars are displayed on mobile', AGP_TXTDM); ?></p> 
							</div>
						</div>
						<div class="col-md-8">
							<div class="ma_field p-4">
								<p class="switch-field em_size_field">
									<?php if(isset($album_gallery_settings['remove_bars_mobile'])) $remove_bars_mobile = $album_gallery_settings['remove_bars_mobile']; else $remove_bars_mobile = "true"; ?>
									<input type="radio" class="form-control" id="remove_bars_mobile1" name="remove_bars_mobile" value="true" <?php if($remove_bars_mobile == "true") echo "checked" ; ?>>
										<label for="remove_bars_mobile1"><?php _e('Yes', AGP_TXTDM); ?></label>
									<input type="radio" class="form-control" id="remove_bars_mobile2" name="remove_bars_mobile" value="false" <?php if($remove_bars_mobile == "false") echo "checked" ; ?>> 
										<label for="remove_bars_mobile2"><?php _e('No', AGP_TXTDM); ?></label>					
								</p>
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="ma_field_discription">
								<h5><?php _e('Hide Bars Delay', AGP_TXTDM); ?></h5>
								<p><?php _e('Sets the hide bars Delay time in seconds', AGP_TXTDM); ?></p> 
							</div>
						</div>
						<div class="col-md-8">
							<div class="ma_field p-4 range-slider">
								<?php if(isset($album_gallery_settings['hide_bars_delay'])) $hide_bars_delay = $album_gallery_settings['hide_bars_delay']; else $hide_bars_delay = 3000; ?>	
								<input type="range" class="range-slider__range" id="hide_bars_delay" name="hide_bars_delay" value="<?php echo $hide_bars_delay; ?>" min="500" max="10000" step="100">
								<span class="range-slider__value">3000</span>
							</div>
						</div>
					</div>
					
					<div class="bhoechie-tab-content">
						<h1><?php _e('Animation & Hover Effect', AGP_TXTDM); ?></h1>
						<hr>
						<div class="col-md-4">
							<div class="ma_field_discription">
								<h5><?php _e('Hover Effects', AGP_TXTDM); ?></h5>
								<p><?php _e('Select the hover effect to apply', AGP_TXTDM); ?></p> 
							</div>
						</div>
						<div class="col-md-8">
							<div class="ma_field p-4">
								<p class="switch-field em_size_field">
									<?php if(isset($album_gallery_settings['hover_effects'])) $hover_effects = $album_gallery_settings['hover_effects']; else $hover_effects = "stacks"; ?>
									<input type="radio" class="form-control" id="hover_effects1" name="hover_effects" value="stacks" <?php if($hover_effects == "stacks") echo "checked" ; ?>>
										<label for="hover_effects1"><?php _e('Stacks', AGP_TXTDM); ?></label>
									<input type="radio" class="form-control" id="hover_effects2" name="hover_effects" value="none" <?php if($hover_effects == "none") echo "checked" ; ?>> 
										<label for="hover_effects2"><?php _e('None', AGP_TXTDM); ?></label>
									<input type="radio" class="form-control" id="hover_effects3" name="hover_effects" value="overlay" <?php if($hover_effects == "overlay") echo "checked" ; ?>> 
										<label for="hover_effects3"><?php _e('Overlay', AGP_TXTDM); ?></label>
								</p>
							</div>
						</div>
						
						<!--Grid-->
						<div class="col-md-4">
							<div class="ma_field_discription">
								<h5><?php _e('Animation', AGP_TXTDM); ?></h5>
								<p><?php _e('Select to apply animation on gallery', AGP_TXTDM); ?></p> 
							</div>
						</div>
						<div class="col-md-8">
							<div class="ma_field p-4">
								<?php if(isset($album_gallery_settings['animations'])) $animations = $album_gallery_settings['animations']; else $animations = "wobble"; ?>
								<select id="animations" name="animations" class="selectbox_position">
									<option value="none" <?php if($animations == "none") echo "selected=selected"; ?>><?php _e('None', AGP_TXTDM); ?></option>
									<option value="wobble" <?php if($animations == "wobble") echo "selected=selected"; ?>><?php _e('Wobble', AGP_TXTDM); ?></option>
									<option value="bounce" <?php if($animations == "bounce") echo "selected=selected"; ?>><?php _e('Bounce', AGP_TXTDM); ?></option>
									<option value="flash" <?php if($animations == "flash") echo "selected=selected"; ?>><?php _e('Flash', AGP_TXTDM); ?></option>
									<option value="jello" <?php if($animations == "jello") echo "selected=selected"; ?>><?php _e('Jello', AGP_TXTDM); ?></option>
									<option value="pulse" <?php if($animations == "pulse") echo "selected=selected"; ?>><?php _e('Pulse', AGP_TXTDM); ?></option>
									<option value="rubberBand" <?php if($animations == "rubberBand") echo "selected=selected"; ?>><?php _e('Rubber Band', AGP_TXTDM); ?></option>
									<option value="shake" <?php if($animations == "shake") echo "selected=selected"; ?>><?php _e('Shake', AGP_TXTDM); ?></option>
									<option value="tada" <?php if($animations == "tada") echo "selected=selected"; ?>><?php _e('Tada', AGP_TXTDM); ?></option>
									<option value="swing" <?php if($animations == "swing") echo "selected=selected"; ?>><?php _e('Swing', AGP_TXTDM); ?></option>
									<option value="rollIn" <?php if($animations == "rollIn") echo "selected=selected"; ?>><?php _e('Roll In', AGP_TXTDM); ?></option>
								</select>
							</div>
						</div>
					
					</div>
					
					<div class="bhoechie-tab-content">
						<h1><?php _e('Upgrade To Pro', AGP_TXTDM); ?></h1>
						<hr>
						<!--Grid-->
						<div class="" style="padding-left: 10px;">
							<p class="ms-title">Upgrade To Premium For Unloack More Features & Settings</p>
						</div>

						<div class="">
							<h1><strong>Offer:</strong> Upgrade To Premium Just In Half Price <strike>$23.98</strike> <strong>$11.99</strong></h1>
							<br>
							<a href="https://awplife.com/wordpress-plugins/album-gallery-wordpress-plugin/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize">Premium Version Details</a>
							<a href="https://awplife.com/demo/album-gallery-premium/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize">Check Live Demo</a>
							<a href="https://awplife.com/demo/album-gallery-premium-admin-demo/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize">Try Pro Version</a>
						</div>

					</div>
				</div>
			</div>
		</div>
<?php
	// syntax: wp_nonce_field( 'name_of_my_action', 'name_of_nonce_field' );
	wp_nonce_field( 'ag_save_settings', 'ag_save_nonce' );
?>

<script>
		// start pulse on page load
		function pulseEff() {
		   jQuery('#shortcode').fadeOut(600).fadeIn(600);
		};
		var Interval;
		Interval = setInterval(pulseEff,1500);

		// stop pulse
		function pulseOff() {
			clearInterval(Interval);
		}
		// start pulse
		function pulseStart() {
			Interval = setInterval(pulseEff,2000);
		}
	
		//range slider
		var rangeSlider = function(){
			var slider = jQuery('.range-slider'),
			range = jQuery('.range-slider__range'),
			value = jQuery('.range-slider__value');

			slider.each(function(){
				value.each(function(){
					var value = jQuery(this).prev().attr('value');
					jQuery(this).html(value);
				});

				range.on('input', function(){
					jQuery(this).next(value).html(this.value);
				});
			});
		};
		rangeSlider();

		//on load 
		var hover_effects = jQuery('input[name="hover_effects"]:checked').val();

		if(hover_effects == "stacks"){
			jQuery('.hover_stack_effect_settings').show();
			jQuery('.hover_overlay_effect_settings').hide();
			jQuery('.effect_show_hide').show();
		}
		if(hover_effects == "none"){
			jQuery('.hover_stack_effect_settings').hide();
			jQuery('.hover_overlay_effect_settings').hide();
			jQuery('.effect_show_hide').hide();
		}
		if(hover_effects == "overlay"){
			jQuery('.hover_overlay_effect_settings').show();
			jQuery('.hover_stack_effect_settings').hide();
			jQuery('.effect_show_hide').show();
		}

	//on change
	jQuery('input[name="hover_effects"]').change(function(){
		var hover_effects = jQuery('input[name="hover_effects"]:checked').val();
		if(hover_effects == "stacks"){
			jQuery('.hover_stack_effect_settings').show();
			jQuery('.hover_overlay_effect_settings').hide();
			jQuery('.effect_show_hide').show();
		}
		if(hover_effects == "none"){
			jQuery('.hover_stack_effect_settings').hide();
			jQuery('.hover_overlay_effect_settings').hide();
			jQuery('.effect_show_hide').hide();
		}
		if(hover_effects == "overlay"){
			jQuery('.hover_overlay_effect_settings').show();
			jQuery('.hover_stack_effect_settings').hide();
			jQuery('.effect_show_hide').show();
		}
	});

// tab
	jQuery("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
		e.preventDefault();
		jQuery(this).siblings('a.active').removeClass("active");
		jQuery(this).addClass("active");
		var index = jQuery(this).index();
		jQuery("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
		jQuery("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
	});
</script>