<?php /* Get Custom User Options Pertaining to this Page */

/* Also Get Overrides if they are using Template Tag or Shortcode */

function output_slYder_html( $slYder_parameters='' ) {

	parse_str($slYder_parameters);


	/*Detect if there is an over-ride, if not use global values*/

if(!$shellWidth)
	$shellWidth = get_option('wpft_shellWidth');
if(!$shellHeight)
	$shellHeight = get_option('wpft_shellHeight');
if(!$thumbWidth)
	$thumbWidth = get_option('wpft_thumbWidth');
if(!$thumbHeight)
	$thumbHeight = get_option('wpft_thumbHeight');
if(!$bannerWidth)
	$bannerWidth = get_option('wpft_bannerWidth');
if(!$bannerHeight)
	$bannerHeight = get_option('wpft_bannerHeight');
if(!$titleWidth)
	$titleWidth = get_option('wpft_titleWidth');
if(!$titleHeight)
	$titleHeight = get_option('wpft_titleHeight');
if(!$excerptWords)
	$excerptWords = get_option('slYder_excerpt');
if(!$slYder_posts)
	$slYder_posts = intval(get_option('slYder_posts')); // # of posts to show
if(!$slYder_cat)
	$slYder_cat = get_option('slYder_cat');
if(!$slYder_top)
	$slYder_top = get_option('slYder_top');
if(!$slYder_right)
	$slYder_right = get_option('slYder_right');

if(!$bannerHeight){ $bannerHeight=240; }

if(!$slYder_top){$slYder_top = 120;}
$slYder_titleY = intval($bannerHeight)-intval($slYder_top);

if(!$slYder_posts){ $slYder_posts = 4; }
if(!$excerptWords){ $excerptWords = 13;}

	/* Handle Percentages or px in the inputs, clean up input for CSS */
	if(!strstr($shellWidth, '%') && !strstr($shellWidth, 'px'))
		$shellWidth = $shellWidth . 'px';
	if(!strstr($thumbWidth, '%') && !strstr($thumbWidth, 'px'))
		$thumbWidth = $thumbWidth . 'px';
	if(!strstr($thumbHeight, '%') && !strstr($thumbHeight, 'px'))
		$thumbHeight = $thumbHeight . 'px';
	if(!strstr($shellHeight, '%') && !strstr($shellHeight, 'px'))
		$shellHeight = $shellHeight . 'px';
	if(!strstr($bannerWidth, '%') && !strstr($bannerWidth, 'px'))
		$bannerWidth = $bannerWidth . 'px';
	if(!strstr($bannerHeight, '%') && !strstr($bannerHeight, 'px'))
		$bannerHeight = $bannerHeight . 'px';
	if(!strstr($titleWidth, '%') && !strstr($titleWidth, 'px'))
		$titleWidth = $titleWidth . 'px';
	if(!strstr($titleHeight, '%') && !strstr($titleHeight, 'px'))
		$titleHeight = $titleHeight . 'px';
?>



	<div class="heroHome" style="width: <?php echo $shellWidth; ?>; height: <?php echo $shellHeight; ?>;">
    <!-- EASY WORDPRESS LOOP WITH PAGINATION -->
<?php //Define the Loop
$temp = $wp_query;
$wp_query= null;
$wp_query = new WP_Query();
$wp_query->query('showposts=1&offset=0&cat='.$slYder_cat);
?>

<?php // Function to limit Wordpress Excerpt by exact amount
function string_limit_words($string, $word_limit)
{
  $words = explode(' ', $string, ($word_limit + 1));
  if(count($words) > $word_limit)
  array_pop($words);
  return implode(' ', $words);
}


?>

<?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
<?php $newid = getThumbId(); ?>
<?php $imgArray=wp_get_attachment_image_src($newid, 'large'); ?>
<div>
<a href="<?php the_permalink() ?>"><img src="<?php echo $imgArray[0]  ?>" class="largeBGHome" style="width: <?php echo $bannerWidth ?>; height: <?php echo $bannerHeight; ?>;" alt="<?php the_title(); ?>"/></a></div>
<div style="display: none;"><a href="<?php the_permalink() ?>"><img src="<?php echo $imgArray[0]  ?>" class="largeBGHome1" alt="Best Apps"/></a></div>
<?php /* <div class="heroImage" style="background: url(<?php echo $imgArray[0]  ?>) center right no-repeat; width: 660px; height: 290px; background-size: 100%;"> */ ?>

	<div class="homeHeroTitleBG" style="width: <?php echo $titleWidth; ?>; height: <?php echo $titleHeight; ?>; top: <?php echo '-'.$slYder_titleY ?>px; left: <?php echo '-' .$slYder_right ?>px;"><a href="<?php the_permalink() ?>" class="homeHeroTitle">
<?php if(get_post_meta($post->ID, "Alternate Title", $single = true)): //If the Post Has an Alternate Title ?>
<?php echo get_post_meta($post->ID, "Alternate Title", $single = true); ?>
<?php else: ?>
<?php the_title() ?>

<?php endif; ?>
</a><br/><span style="color: #efefef;"></span></div>
	<div style="float: left; width: 90px; position: relative; top: -<?php echo $bannerHeight; ?>" class="homeThumbs">
<?php $newid = getThumbId2(); ?>
	<div class="homeHeroThumb active"><span class="homeThumbClick  heroThumb1" onclick="clearTimeout(rotate); loadThumb(1); return false;">
<?php $imgArray=wp_get_attachment_image_src($newid, 'thumbnail'); ?>
<img src="<?php echo $imgArray[0]  ?>" style="width: <?php echo $thumbWidth ?>; height: <?php echo $thumbHeight ?>;" />
</span></div>
<div class="homeHeroTitleContent1" style="display: none;"><a href="<?php the_permalink() ?>" class="homeHeroTitle">
<?php if(get_post_meta($post->ID, "Alternate Title", $single = true)): //If the Post Has an Alternate Title ?>
<?php echo get_post_meta($post->ID, "Alternate Title", $single = true); ?>
<?php else: ?>
<?php the_title() ?>

<?php endif; ?>
</a><br/><span style="color: #efefef;"><?php $excerpt = get_the_excerpt();  echo string_limit_words($excerpt,$excerptWords); ?>...</span></div>

<?php endwhile; ?>

<?php //Define the Loop
$temp = $wp_query;
$wp_query= null;
$wp_query = new WP_Query();
$slYder_posts-=1;
$wp_query->query('showposts='.$slYder_posts.'&offset=1&cat='.$slYder_cat);
$numThumb = 2;
?>

<?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
<?php $newid = getThumbId2(); ?>
<div class="homeHeroThumb"><span href="<?php the_permalink() ?>" class="homeThumbClick heroThumb<?php echo $numThumb ?>" onclick=" clearTimeout(rotate); loadThumb(<?php echo $numThumb ?>); return false;">
<?php $imgArray=wp_get_attachment_image_src($newid, 'thumbnail'); ?>
<img src="<?php echo $imgArray[0]  ?>" style="width: <?php echo $thumbWidth ?>; height: <?php echo $thumbHeight ?>;" />
</span></div>
<?php $newid = getThumbId(); ?>
<?php $imgArray=wp_get_attachment_image_src($newid, 'large'); ?>
<div style="display: none;"><a href="<?php the_permalink() ?>"><img src="<?php echo $imgArray[0]  ?>" class="largeBGHome<?php echo $numThumb ?>" /></a></div>
<div class="homeHeroTitleContent<?php echo $numThumb ?>" style="display: none;"><a href="<?php the_permalink() ?>" class="homeHeroTitle">
<?php if(get_post_meta($post->ID, "Alternate Title", $single = true)): //If the Post Has an Alternate Title ?>
<?php echo get_post_meta($post->ID, "Alternate Title", $single = true); ?>
<?php else: ?>
<?php the_title() ?>

<?php endif; ?>
</a><br/><span style="color: #efefef;"><?php $excerpt = get_the_excerpt();  echo string_limit_words($excerpt,$excerptWords); ?>...</span></div>
<?php $numThumb++ ?>
<?php endwhile; ?>

</div><!-- End Float left for Thumbs -->

</div> <!-- end Home Rotator -->

<?php
}
?>
