<?php
/*
Plugin Name: slYder - Lightweight Wordpress Slider (Wordpress Content Slider)
Plugin URI: http://www.bestappsrated.com/slYder-wordpress-slider/
Description: slYder is an extremely lightweight wordpress content slider that allows you to customize every aspect of the slider (dimensions, number of posts, etc..) the slider is also easily configurable via CSS and can be inserted just about anywhere on your theme via Template Tags or built in Shortcodes.
Version: 1.2
Author: JordashTalon
Author URI: http://bestappsrated.com/
License: GPL2
 */

/*  Copyright 2012  JordashTalon  (email : jordashtalon@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/* Get Users Custom Vars pertaining to this screen */

function wpFeatures_register_scripts() { // Adds JQuery and proper CSS to the Theme
	wp_enqueue_script("jquery");
}

add_action('wp_print_scripts', 'wpFeatures_register_scripts');
add_shortcode('slYder', 'slYder');
add_action('wp_head', 'add_slYder_styles');
include('css.php'); //Include the necessary css styling, can be over-ridden
 
function slYder_scripts( $slYder_parameters='' )
{ // Appends neccesary Javascript to end of the theme, also configures no conflict Jquery to not interfere with other plugins / scripts
	parse_str($slYder_parameters);
	$j = '$j';
	if(!$slYder_delay)
	 	$slYder_delay=get_option('slYder_delay');
	if(!$slYder_delay){ $slYder_delay = 8000; }
	if(!$slYder_posts)	
		$slYder_posts=get_option('slYder_posts');
	if(!$slYder_posts){ $slYder_posts = 4; }
	$wpFeaturesJavascript = "
<script type=\"text/javascript\">
var $j = jQuery.noConflict();
</script>
<script type=\"text/javascript\">
/* Home Rotater */
var delay = ".$slYder_delay.";
var next=1;
var max=".$slYder_posts.";
loadThumb(1);
var rotate;
	
function loadThumb(thumb) {

	if(rotate){
		clearTimeout(rotate);
	}
	
	var newBackground = $j('.largeBGHome'+thumb).attr('src');
	$j('.largeBGHome').attr('src', newBackground);
	$j('.largeBGHome').parent('a').attr('href', $j('.homeHeroTitleContent' + thumb + ' .homeHeroTitle').attr('href'));
	$j('.largeBGHome').hide();
	$j('.largeBGHome').fadeIn('fast');
	$j('.homeHeroTitleBG').html($j('.homeHeroTitleContent' + thumb).html());
	$j('.homeHeroThumb').each(function(event) {
           $j(this).removeClass('active');
	});
	$j('.heroThumb'+thumb).parent('.homeHeroThumb').addClass('active');
	next=thumb+1;
	if(next > max){
		next = 1;
	}
	rotate=setTimeout(function() {
	    loadThumb(next);
	}, delay);
	
}
</script>

";

echo $wpFeaturesJavascript; // echo out Javascript end of theme footer

}

/*Template Tag*/
function slYder($slYder_parameters='') { // Template tag for Outputting the Featured Post Slider
	parse_str($slYder_parameters); // Parse user parameters for use.
	include('contentloop.php'); // Output the loop HTML (show the top featured post based on user specification
	output_slYder_html( $slYder_parameters ); //Prints out the slYder HTML Content, Passes Template Tag Parameters through if needed.
	slYder_scripts($slYder_parameters); // Add in the necessary Javascript and Jquery files
}


function getThumbId() { //Function to get the Banner Image, future plan, customizable choice on banner #
    $attachments = get_children( array(
        'post_parent' => get_the_ID(),
        'post_status' => 'inherit',
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'order' => 'ASC',
	'orderby' => 'menu_order ID',
	'offset' => 0,
        'numberposts' => 1)
    );
    $attachment = array_shift($attachments);
    $thumb_id = $attachment->ID;
    return $thumb_id;
}
function getThumbId2() { //Function to get the Banner Thumbnail
    $attachments = get_children( array(
        'post_parent' => get_the_ID(),
        'post_status' => 'inherit',
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'order' => 'ASC',
	'orderby' => 'menu_order ID',
	'offset' => 1,
        'numberposts' => 1)
    );
    $attachment = array_shift($attachments);
    $thumb_id = $attachment->ID;
    return $thumb_id;
}


?>
<?php // add the admin options page to the wordpress menu
add_action('admin_menu', 'plugin_admin_add_page');
function plugin_admin_add_page() {
	//add_options_page('WP Features Options', 'WPFeatures', 'manage_options', 'plugin', 'plugin_options_page');
	add_menu_page( 'slYder Settings', 'slYder', 'manage_options', 'wp-features-admin', 'wp_features_administration', plugin_dir_url( __FILE__ ) . 'slYderIcon.png' );
}
?>
<?php

function wp_features_administration() {
?>
<div class="wrap">

<h2>slYder - Global Settings Page</h2>

<?php

	if($_REQUEST['submit']){
		update_wp_features();
	}
	if($_REQUEST['reset']){
		reset_slYder();
	}
	print wp_features_form();

?>
</div>
<?php
}

?>
<?php /* THE UPDATE INSERTION FORM */
function update_wp_features(){

	//Clean up these variables to prevent SQL Injection
	//
	
	$wpft_thumbWidth = mysql_real_escape_string($_REQUEST['wpft_thumbWidth']);
	$wpft_thumbHeight = mysql_real_escape_string($_REQUEST['wpft_thumbHeight']);
	$wpft_shellWidth = mysql_real_escape_string($_REQUEST['wpft_shellWidth']);
	$wpft_shellHeight = mysql_real_escape_string($_REQUEST['wpft_shellHeight']);
	$wpft_bannerWidth = mysql_real_escape_string($_REQUEST['wpft_bannerWidth']);
	$wpft_bannerHeight = mysql_real_escape_string($_REQUEST['wpft_bannerHeight']);
	$wpft_titleWidth = mysql_real_escape_string($_REQUEST['wpft_titleWidth']);
	$wpft_titleHeight = mysql_real_escape_string($_REQUEST['wpft_titleHeight']);
	$slYder_delay = mysql_real_escape_string($_REQUEST['slYder_delay']);
	$slYder_posts = mysql_real_escape_string($_REQUEST['slYder_posts']);
	$slYder_cat = mysql_real_escape_string($_REQUEST['slYder_cat']);
	$slYder_top = mysql_real_escape_string($_REQUEST['slYder_top']);
	$slYder_right = mysql_real_escape_string($_REQUEST['slYder_right']);

	update_option('wpft_thumbWidth', $wpft_thumbWidth);
	update_option('wpft_thumbHeight', $_REQUEST['wpft_thumbHeight']);
	update_option('wpft_shellWidth', $_REQUEST['wpft_shellWidth']); 
	update_option('wpft_shellHeight', $_REQUEST['wpft_shellHeight']); 
	update_option('wpft_bannerWidth', $_REQUEST['wpft_bannerWidth']);
	update_option('wpft_bannerHeight', $_REQUEST['wpft_bannerHeight']);
	update_option('wpft_titleWidth', $_REQUEST['wpft_titleWidth']);
	update_option('wpft_titleHeight', $_REQUEST['wpft_titleHeight']);
	update_option('slYder_delay', $_REQUEST['slYder_delay']);
	update_option('slYder_excerpt', $_REQUEST['slYder_excerpt']);
	update_option('slYder_posts', $_REQUEST['slYder_posts']);
	update_option('slYder_cat', $_REQUEST['slYder_cat']);
	update_option('slYder_top', $_REQUEST['slYder_top']);
	update_option('slYder_right', $_REQUEST['slYder_right']);

	$ok=true;

	if ($ok){ ?>

	<div id="message" class="updated fade">
		<p>Options saved successfully.</p>
	</div>
		
	<?php } else { ?>

		<div id="message" class="error fade">
			<p>Error: Failed to Save Options</p>
		</div>
	<?php }
}
?>
<?php 

function wp_features_form() {  // The form where they can input data

	/* Set Default Values */
	$dfltShellWidth = '100%';
	$dfltShellHeight = '243';
	$dfltThumbWidth = '80';
	$dfltThumbHeight = '40';
	$dfltBannerWidth = '84%';
	$dfltBannerHeight = '240';
	$dfltTitleWidth = '70%';
	$dfltTitleHeight = '';
	$dfltDelay = 8000;
	$dfltExcerpt = 13;
	$dfltPosts = 4;
	$dfltTop = 120;
	$dfltRight = 0;

	/* Get Users Values (If Set) */
	$shellWidth = get_option('wpft_shellWidth');
	$shellHeight = get_option('wpft_shellHeight');
	$thumbWidth = get_option('wpft_thumbWidth');
	$thumbHeight = get_option('wpft_thumbHeight');
	$bannerWidth = get_option('wpft_bannerWidth');
	$bannerHeight = get_option('wpft_bannerHeight');
	$titleWidth = get_option('wpft_titleWidth');
	$titleHeight = get_option('wpft_titleHeight');
	$slYder_delay = get_option('slYder_delay');
	$slYder_excerpt = get_option('slYder_excerpt');
	$slYder_posts = get_option('slYder_posts');
	$slYder_cat = get_option('slYder_cat');
	$slYder_top = get_option('slYder_top');
	$slYder_right = get_option('slYder_right');

?>
<div style="float: right; text-align: center;">
<p><b>Please Consider: </b></p>
<p> <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="RN3WNPB2M2XFS">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form></p>
</div>

<p><a href="http://bestappsrated.com/slYder-wordpress-slider/" target="_blank"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'slYderLogo.png'; ?>" alt="slYder Lightweight Wordpress Slider" /></a></p>

<p style="font-size: 200%; font-weight: bold;"><a href="http://bestappsrated.com/slYder-wordpress-slider/" target="_blank">Click Here To Learn How To Use slYder</a></p>

<form method="post">

<small>Note: All Settings will default to pixel dimensions unless % is specified.</small>

<p>Each of these settings can be individually over-ridden with a template tag</p>

<p>If you leave a setting blank, the default value will be used.</p>

<h3>Timing Options</h3>

<p>
	<label for="shellWidth">Time Between Transitions:</label><br/>
	<input type="text" name="slYder_delay" value="<?php echo $slYder_delay ?>" />
	Default Value: <?php echo $dfltDelay ?> in milliseconds (e.g. 8,000 = 8 seconds)
</p>

<h3>Post Options</h3>

<p>
	<label for="shellWidth">Number of Posts:</label><br/>
	<input type="text" name="slYder_posts" value="<?php echo $slYder_posts ?>" />
	Default Value: <?php echo $dfltPosts ?> posts (specify the number of posts to slide through e.g. 6)
</p>

<p>
	<label for="shellWidth">Category ID:</label><br/>
	<input type="text" name="slYder_cat" value="<?php echo $slYder_cat ?>" />
	Default Value: All Categories (leave blank to show all categories, or specify a category which will be the only one shown, you can comma seperate multiple categories e.g. 4, 3, 2 or exclude a category like this: -4, -3    etc..)
</p>

<h3>Outer Shell Settings</h3>

<p>
	<label for="shellWidth">Outer Shell Width:</label><br/>
	<input type="text" name="wpft_shellWidth" value="<?php echo $shellWidth ?>" />
	Default Value: <?php echo $dfltShellWidth ?> (examples: 150 or 20%)
</p>
</p>
	<label for="shellWidth">Outer Shell Height:</label><br/>
	<input type="text" name="wpft_shellHeight" value="<?php echo $shellHeight ?>" />
	Default Value: <?php echo $dfltShellHeight ?> (examples: 150 or 20%)
</p>

<h3>Banner Options</b></h3>
<p>
	<label for="shellWidth">Banner Width: </label><br/>
	<input type="text" name="wpft_bannerWidth" value="<?php echo $bannerWidth ?>" />
	Default Value <?php echo $dfltBannerWidth ?> (examples: 150 or 20%)
</p>
<p>

	<label for="shellWidth">Banner Height: </label><br/>
	<input type="text" name="wpft_bannerHeight" value="<?php echo $bannerHeight ?>" />
	Default Value: <?php echo $dfltBannerHeight ?> (examples: 150 or 20%)
</p>

<h3>Thumbnail Options</b></h3>
<p>
	<label for="shellWidth">Thumbnail Width:	</label><br/>
	<input type="text" name="wpft_thumbWidth" value="<?php echo $thumbWidth ?>" />
	Default Value <?php echo $dfltThumbWidth ?> (examples: 150 or 20%)
</p>
<p>

	<label for="shellWidth">Thumbnail Height:	</label><br/>
	<input type="text" name="wpft_thumbHeight" value="<?php echo $thumbHeight ?>" />
	Default Value: <?php echo $dfltThumbHeight ?> (examples: 150 or 20%)
</p>

<h3>Title Bar Options</b></h3>
<p>
	<label for="shellWidth">Title Width:	</label><br/>
	<input type="text" name="wpft_titleWidth" value="<?php echo $titleWidth ?>" />
	Default Value <?php echo $dfltTitleWidth ?> (examples: 150 or 20%) <b>*Important*</b> Be careful, setting to wide of a width will push your thumbnails off the screen!
</p>
<p>

	<label for="shellWidth">Title Height:	</label><br/>
	<input type="text" name="wpft_titleHeight" value="<?php echo $titleHeight ?>" />
	Default Value: <?php echo $dfltTitleHeight ?> * Note: Usually you want to leave this blank for auto sizing *(examples: 150 or 20%)
</p>
<p>
	<label for="shellWidth">Position From Top:	</label><br/>
	<input type="text" name="slYder_top" value="<?php echo $slYder_top ?>" />
	Default Value: <?php echo $dfltTop ?> pixels (offset the title from the top)
</p>
<p>

	<label for="shellWidth">Position From Right:	</label><br/>
	<input type="text" name="slYder_right" value="<?php echo $slYder_right ?>" />
	Default Value: <?php echo $dfltRight ?> pixels (offset the title from the right hand side)
</p>
<p>

	<label for="shellWidth">Excerpt Length:	</label><br/>
	<input type="text" name="slYder_excerpt" value="<?php echo $slYder_excerpt ?>" />
	Default Value: <?php echo $dfltExcerpt ?> In # of words (e.g. 34)
</p>

	<p><input type="submit" name="submit" value="Save Settings" class="button-primary" /></p>
<p> ----------------------- </p>
<p><input type="submit" name="reset" value="Reset"></p>
<small>Reset every setting to plugin default.  (Warning, Can't be undone)</small>
</form>

<?php
  
}


?>
<?php

function reset_slYder() {
	update_option('wpft_thumbWidth', '');
	update_option('wpft_thumbHeight', '');
	update_option('wpft_shellWidth', ''); 
	update_option('wpft_shellHeight', ''); 
	update_option('wpft_bannerWidth', '');
	update_option('wpft_bannerHeight', '');
	update_option('wpft_titleWidth', '');
	update_option('wpft_titleHeight', '');
	update_option('slYder_delay', '');
	update_option('slYder_excerpt', '');
	update_option('slYder_posts', '');
	update_option('slYder_cat', '');
	update_option('slYder_top', '');
	update_option('slYder_right', '');
}

?>
