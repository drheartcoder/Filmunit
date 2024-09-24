<?php
//load settings
$gallery_settings = unserialize(base64_decode(get_post_meta( $post->ID, 'awl_ig_settings_'.$post->ID, true)));
//print_r($gallery_settings);
$image_gallery_id = $post->ID;

//toggle button CSS
wp_enqueue_style('awl-toogle-button-css', IG_PLUGIN_URL . 'css/toogle-button.css');
//wp_enqueue_style( 'awl-bootstrap-css', IG_PLUGIN_URL .'css/bootstrap.css' );
wp_enqueue_style('awl-font-awesome-css', IG_PLUGIN_URL . 'css/font-awesome.css');
wp_enqueue_style('awl-styles-css', IG_PLUGIN_URL . 'css/styles.css');
//js
wp_enqueue_script('jquery');
wp_enqueue_script( 'awl-bootstrap-js',  IG_PLUGIN_URL .'js/bootstrap.min.js', array( 'jquery' ), '', true  );


$col_large_desktops = $gallery_settings['col_large_desktops'];
$col_desktops = $gallery_settings['col_desktops'];
$col_tablets = $gallery_settings['col_tablets'];
$col_phones = $gallery_settings['col_phones'];
?>
<style>
.nig_settings {
	padding: 8px 0px 8px 8px !important;
	margin: 10px 10px 4px 0px !important;
}
.nig_settings label {
	font-size: 16px !important;
}
.be-right {
	float: right;
	text-align: right;
	text-decoration: none;
}


</style>
<div class="nig_settings">
	<p class="bg-title">1. Gallery Thumbnail Size</p></br>
	<?php if(isset($gallery_settings['gal_thumb_size'])) $gal_thumb_size = $gallery_settings['gal_thumb_size']; else $gal_thumb_size = "thumbnail"; ?>
	<select id="gal_thumb_size" name="gal_thumb_size" class="form-control">
		<option value="thumbnail" <?php if($gal_thumb_size == "thumbnail") echo "selected=selected"; ?>>Thumbnail – 150 × 150</option>
		<option value="medium" <?php if($gal_thumb_size == "medium") echo "selected=selected"; ?>>Medium – 300 × 169</option>
		<option value="large" <?php if($gal_thumb_size == "large") echo "selected=selected"; ?>>Large – 840 × 473</option>
		<option value="full" <?php if($gal_thumb_size == "full") echo "selected=selected"; ?>>Full Size – 1280 × 720</option>
	</select>
	<h4><?php _e('Select gallery thumnails size to display into gallery', IGP_TXTDM); ?></h4>
</div>

<!--Colums Layout Settings Start-->
	<div class="nig_settings">
		<p class="bg-title">2. Colums Layout Settings</p>
		<p class="bg-lower-title">A .Colums On Large Desktops</p>
		<?php if(isset($gallery_settings['col_large_desktops'])) $col_large_desktops = $gallery_settings['col_large_desktops']; else $col_large_desktops = "col-lg-2"; ?>
		<select id="col_large_desktops" name="col_large_desktops" class="form-control">
			<option value="col-lg-12" <?php if($col_large_desktops == "col-lg-12") echo "selected=selected"; ?>>1 Column</option>
			<option value="col-lg-6" <?php if($col_large_desktops == "col-lg-6") echo "selected=selected"; ?>>2 Column</option>
			<option value="col-lg-4" <?php if($col_large_desktops == "col-lg-4") echo "selected=selected"; ?>>3 Column</option>
			<option value="col-lg-3" <?php if($col_large_desktops == "col-lg-3") echo "selected=selected"; ?>>4 Column</option>
			<option value="col-lg-2" <?php if($col_large_desktops == "col-lg-2") echo "selected=selected"; ?>>6 Column</option>
			<option value="col-lg-1" <?php if($col_large_desktops == "col-lg-1") echo "selected=selected"; ?>>12 Column</option>
		</select>
		<h4><?php _e('Select gallery column layout for large desktop devices', IGP_TXTDM); ?></h4>

		<p class="bg-lower-title">B. Colums On Desktops</p>
		<?php if(isset($gallery_settings['col_desktops'])) $col_desktops = $gallery_settings['col_desktops']; else $col_desktops = "col-md-3"; ?>
		<select id="col_desktops" name="col_desktops" class="form-control">
			<option value="col-md-12" <?php if($col_desktops == "col-md-12") echo "selected=selected"; ?>>1 Column</option>
			<option value="col-md-6" <?php if($col_desktops == "col-md-6") echo "selected=selected"; ?>>2 Column</option>
			<option value="col-md-4" <?php if($col_desktops == "col-md-4") echo "selected=selected"; ?>>3 Column</option>
			<option value="col-md-3" <?php if($col_desktops == "col-md-3") echo "selected=selected"; ?>>4 Column</option>
			<option value="col-md-2" <?php if($col_desktops == "col-md-2") echo "selected=selected"; ?>>6 Column</option>
			<option value="col-md-1" <?php if($col_desktops == "col-md-1") echo "selected=selected"; ?>>12 Column</option>
		</select>
		<h4><?php _e('Select gallery column layout for desktop devices', IGP_TXTDM); ?></h4>

		<p class="bg-lower-title">C. Colums On Tablets</p>
		<?php if(isset($gallery_settings['col_tablets'])) $col_tablets = $gallery_settings['col_tablets']; else $col_tablets = "col-sm-4"; ?>
		<select id="col_tablets" name="col_tablets" class="form-control">
			<option value="col-sm-12" <?php if($col_tablets == "col-sm-12") echo "selected=selected"; ?>>1 Column</option>
			<option value="col-sm-6" <?php if($col_tablets == "col-sm-12") echo "selected=selected"; ?>>2 Column</option>
			<option value="col-sm-4" <?php if($col_tablets == "col-sm-4") echo "selected=selected"; ?>>3 Column</option>
			<option value="col-sm-3" <?php if($col_tablets == "col-sm-3") echo "selected=selected"; ?>>4 Column</option>
			<option value="col-sm-2" <?php if($col_tablets == "col-sm-2") echo "selected=selected"; ?>>6 Column</option>
		</select>
		<h4><?php _e('Select gallery column layout for tablet devices', IGP_TXTDM); ?></h4>
			
		<p class="bg-lower-title">D. Colums On Phones</p>
		<?php if(isset($gallery_settings['col_phones'])) $col_phones = $gallery_settings['col_phones']; else $col_phones = "col-xs-6"; ?>
		<select id="col_phones" name="col_phones" class="form-control">
			<option value="col-xs-12" <?php if($col_phones == "col-xs-12") echo "selected=selected"; ?>>1 Column</option>
			<option value="col-xs-6" <?php if($col_phones == "col-xs-6") echo "selected=selected"; ?>>2 Column</option>
			<option value="col-xs-4" <?php if($col_phones == "col-xs-4") echo "selected=selected"; ?>>3 Column</option>
			<option value="col-xs-3" <?php if($col_phones == "col-xs-3") echo "selected=selected"; ?>>4 Column</option>
		</select>
		<h4><?php _e('Select gallery column layout for phone devices', IGP_TXTDM); ?></h4>
	</div>
<!--Colums Layout Settings End-->

<div class="nig_settings">
	<p class="bg-title"><?php _e('3. Light Box Style', IGP_TXTDM); ?></p></br>
	<?php if(isset($gallery_settings['light-box'])) $light_box = $gallery_settings['light-box']; else $light_box = 1; ?>
	<select name="light-box" id="light-box">	
		<option value="0" <?php if($light_box == 0) echo "selected=selected"; ?>>None</option>
		<option value="6" <?php if($light_box == 6) echo "selected=selected"; ?>>Bootstrap Light Box</option>
	</select>
	<h4><?php _e('Select a light box style', IGP_TXTDM); ?></h4>
</div><br>

<!--Hover Effects Settings Start-->
<div class="nig_settings">
	<p class="bg-title"><?php _e('4. Image Hover Effect Type', IGP_TXTDM); ?></p></br>
	<div class="switch-field em_size_field">
		<?php if(isset($gallery_settings['image_hover_effect_type'])) $image_hover_effect_type = $gallery_settings['image_hover_effect_type']; else $image_hover_effect_type = "sg"; ?>
		<input type="radio" name="image_hover_effect_type" id="image_hover_effect_type1" value="no" <?php if($image_hover_effect_type == "no") echo "checked=checked"; ?>>
		<label for="image_hover_effect_type1"><?php _e('None', IGP_TXTDM); ?></label>
		<input type="radio" name="image_hover_effect_type" id="image_hover_effect_type2" value="sg" <?php if($image_hover_effect_type == "sg") echo "checked=checked"; ?>>
		<label for="image_hover_effect_type2"><?php _e('2D Transitions', IGP_TXTDM); ?></label>
	</div>
	<h4><?php _e('Select and Set a image hover effect type for Gallery', IGP_TXTDM); ?><h4>

	<!-- 4 -->
	<div class="he_four nig_settings">
		<label><?php _e('Image Hover Effects', IGP_TXTDM); ?></label><br>
		<?php if(isset($gallery_settings['image_hover_effect_four'])) $image_hover_effect_four = $gallery_settings['image_hover_effect_four']; else $image_hover_effect_four = "hvr-glow"; ?>
		<select name="image_hover_effect_four" id="image_hover_effect_four">
			<optgroup label="Shadow and Glow Transitions Effects" class="sg">
				<option value="hvr-grow-shadow" <?php if($image_hover_effect_four == "hvr-grow-shadow") echo "selected=selected"; ?>>Grow Shadow</option>
				<option value="hvr-float-shadow" <?php if($image_hover_effect_four == "hvr-float-shadow") echo "selected=selected"; ?>>Float Shadow</option>
				<option value="hvr-glow" <?php if($image_hover_effect_four == "hvr-glow") echo "selected=selected"; ?>>Glow</option>
				<option value="hvr-box-shadow-inset" <?php if($image_hover_effect_four == "hvr-box-shadow-inset") echo "selected=selected"; ?>>Box-Shadow-Inset</option>
				<option value="hvr-box-shadow-outset" <?php if($image_hover_effect_four == "hvr-box-shadow-outset") echo "selected=selected"; ?>>Box Shadow Outset</option>
			</optgroup>
		</select>
	</div>
</div><br>

<div class="nig_settings">
	<p class="bg-title"><?php _e('5. Hide Thumbnails Spacing', IGP_TXTDM); ?></p><br>
	<p class=" switch-field em_size_field">
		<?php if(isset($gallery_settings['no_spacing'])) $no_spacing = $gallery_settings['no_spacing']; else $no_spacing = 0; ?>
		<input type="radio" name="no_spacing" id="no_spacing1" value="1" <?php if($no_spacing == 1) echo "checked=checked"; ?>>
		<label for="no_spacing1"><?php _e('Yes', IGP_TXTDM); ?></label>
		<input type="radio" name="no_spacing" id="no_spacing2" value="0" <?php if($no_spacing == 0) echo "checked=checked"; ?>>
		<label for="no_spacing2"><?php _e('No', IGP_TXTDM); ?></label>
	</p>
	<h4><?php _e('Hide gap / margin / padding / spacing between gallery thumbnails', IGP_TXTDM); ?></h4>
	
	<p class="bg-title"><?php _e('5. Hide Thumbnails Title', IGP_TXTDM); ?></p><br>
	<p class=" switch-field em_size_field">
		<?php if(isset($gallery_settings['img_title'])) $img_title = $gallery_settings['img_title']; else $img_title = 0; ?>
		<input type="radio" name="img_title" id="img_title1" value="1" <?php if($img_title == 1) echo "checked=checked"; ?>>
		<label for="img_title1"><?php _e('Yes', IGP_TXTDM); ?></label>
		<input type="radio" name="img_title" id="img_title2" value="0" <?php if($img_title == 0) echo "checked=checked"; ?>>
		<label for="img_title2"><?php _e('No', IGP_TXTDM); ?></label>
	</p>
</div>

<div class="nig_settings">
	<p class="bg-title"><?php _e('6. Gallery Thumbnail Order', IGP_TXTDM); ?></p><br>
	<p class="switch-field em_size_field">	
		<?php if(isset($gallery_settings['thumbnail_order'])) $thumbnail_order = $gallery_settings['thumbnail_order']; else $thumbnail_order = "ASC"; ?>
		<input type="radio" name="thumbnail_order" id="thumbnail_order1" value="ASC" <?php if($thumbnail_order == "ASC") echo "checked=checked"; ?>>
		<label for="thumbnail_order1"><?php _e('Old First', IGP_TXTDM); ?></label>
		<input type="radio" name="thumbnail_order" id="thumbnail_order2" value="DESC" <?php if($thumbnail_order == "DESC") echo "checked=checked"; ?>>
		<label for="thumbnail_order2"><?php _e('New First', IGP_TXTDM); ?></label>
		<input type="radio" name="thumbnail_order" id="thumbnail_order3" value="RANDOM" <?php if($thumbnail_order == "RANDOM") echo "checked=checked"; ?>>
		<label for="thumbnail_order3"><?php _e('Random', IGP_TXTDM); ?></label><br><br>
		<h4><?php _e('Set a image order in which you want to display gallery thumbnails', IGP_TXTDM); ?></h4>
	</p>
</div>
<div class="nig_settings">
	<p class="bg-title"><?php _e('7. Custom CSS', IGP_TXTDM); ?></p></br>
	<?php if(isset($gallery_settings['custom-css'])) $custom_css = $gallery_settings['custom-css']; else $custom_css = ""; ?>
	<textarea name="custom-css" id="custom-css" style="width: 100%; height: 120px;" placeholder="Type direct CSS code here. Don't use <style>...</style> tag."><?php echo $custom_css; ?></textarea><br>
	<h4><?php _e('Apply own css on image gallery and dont use style tag', IGP_TXTDM); ?></h4>
</div><br>
<hr>
<div class="">
	<h1><strong>Offer:</strong> Upgrade To Premium Just In Half Price <strike>$15</strike> <strong>$7</strong></h1>
	<br>
	<a href="http://awplife.com/product/image-gallery-premium/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize">Premium Version Details</a>
	<a href="http://demo.awplife.com/image-gallery-premium/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize">Check Live Demo</a>
	<a href="http://demo.awplife.com/image-gallery-premium-admin-demo/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize">Try Pro Version</a>
	<a href="http://localhost/free/wp-admin/edit.php?post_type=image_gallery&page=fp-page" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize">Our Other Plugins</a>
</div><hr>
<style>
	.awp_bale_offer {
		background-image: url("<?php echo IG_PLUGIN_URL ?>/img/awp-bale.jpg");
		background-repeat:no-repeat;
		padding:20px;
	}
	.awp_bale_offer h1 {
		font-size:35px;
		color:#000000;
	}
	.awp_bale_offer h3 {
		font-size:25px;
		color:#000000;
	}
	.awp_bale_offer h4 {
		font-size:18px;
		color:#000000;
	}
	
	.btn-primary {
		margin: 5px;
	}
</style>
<div class="col-md-12">
	<div class="awp_bale_offer text-center">
		<div class="col-md-6">
			<h1>Plugin's Bale Offer</h1>
			<h3>Get All Premium Plugin - 18+ Premium Plugins ( Personal Licence) in just $149 </h3>
			<h4> 8+ gallery plugins, 3+ Slider Plugin , Event , Testimonial , Contact Form, Social media, Popup Box, Weather Effect, Social share </h4>
			<h3><strike>$249</strike> For $149 Only</h3>
		</div>
		<div class="col-md-6">
			<a href="http://awplife.com/account/signup/all-premium-plugins" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize">BUY NOW</a>
		</div>
	</div>
</div>
<input type="hidden" name="ig-settings" id="ig-settings" value="ig-save-settings">

<!-- Return to Top -->
<a href="javascript:" id="return-to-top"><i class="fa fa-chevron-up"></i></a>

<script>
// ===== Scroll to Top ==== 
jQuery(window).scroll(function() {
    if (jQuery(this).scrollTop() >= 50) {        // If page is scrolled more than 50px
        jQuery('#return-to-top').fadeIn(200);    // Fade in the arrow
    } else {
        jQuery('#return-to-top').fadeOut(200);   // Else fade out the arrow
    }
});
jQuery('#return-to-top').click(function() {      // When arrow is clicked
    jQuery('body,html').animate({
        scrollTop : 0                       // Scroll to top of body
    }, 500);
});

var effect_type = jQuery('input[name="image_hover_effect_type"]:checked').val();
//alert(effect_type);
if(effect_type == "no") {
	jQuery('.he_one').hide();
	jQuery('.he_two').hide();
	jQuery('.he_three').hide();
	jQuery('.he_four').hide();
	jQuery('.he_five').hide();
	jQuery('.image-hover-color').show();
	jQuery('.title-bg-color').show();
}
if(effect_type == "sg") {
	jQuery('.he_one').hide();
	jQuery('.he_two').hide();
	jQuery('.he_three').hide();
	jQuery('.he_four').show();
	jQuery('.he_five').hide();
	jQuery('.title-bg-color').show();
	jQuery('.image-hover-color').show();
}

//on change effect
jQuery(document).ready(function() {
	jQuery('input[name="image_hover_effect_type"]').change(function(){
		var effect_type = jQuery('input[name="image_hover_effect_type"]:checked').val();
		if(effect_type == "no") {
			jQuery('.he_one').hide();
			jQuery('.he_two').hide();
			jQuery('.he_three').hide();
			jQuery('.he_four').hide();
			jQuery('.he_five').hide();
			jQuery('.title-bg-color').hide();
			jQuery('.image-hover-color').hide();
		}
		if(effect_type == "sg") {
			jQuery('.he_one').hide();
			jQuery('.he_two').hide();
			jQuery('.he_three').hide();
			jQuery('.he_four').show();
			jQuery('.he_five').hide();
			jQuery('.title-bg-color').show();
			jQuery('.image-hover-color').show();
		}
	});
});
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

</script>