<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package _s
 */

require(get_template_directory().'/inc/mobiledetect/Mobile_Detect.php');
$mobile;
$detect = new Mobile_Detect;
if ( !$detect->isMobile() ) {
	$mobile = 'desktop'; 	
}
if ( $detect->isTablet() ) {
	$mobile .= ' tablet'; 	
}
if( $detect->isMobile() && !$detect->isTablet() ){
 	$mobile .= ' phone'; 
}

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title><?php wp_title( '&mdash;', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); 

// $descr = get_field('website_description', 'option');
// $title = wp_title( '&mdash;', false, 'right' );

// echo '<meta name="description" content="'.$descr.'"/>'; 

// $attachmentId = get_field('share_image', 'option');
// $image = wp_get_attachment_image_src( $attachmentId, "fb" );
// echo '<meta property="og:type" content="website">';			
// echo '<meta property="og:image" content="'.$image[0].'">';
// echo '<meta property="og:image:width" content="'.$image[1].'">';
// echo '<meta property="og:image:height" content="'.$image[2].'">';
// echo '<meta property="og:title" content="'.$title.'">';
// echo '<meta property="og:url" content="'.curPageURL().'">';
// echo '<meta property="og:site_name" content="'.wp_title( '–', false, 'right' ).'">';
// echo '<meta property="og:description" content="'.$descr.'">';

// the_field('google_analytics', 'option');

?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="<?php echo $mobile; ?>">

	<header id="masthead" class="site-header" role="banner">
		<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'walker' => new MyWalker ) ); ?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
