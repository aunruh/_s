<?php
/**
 * _s functions and definitions
 *
 * @package _s
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( '_s_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function _s_setup() {

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	update_option('thumbnail_size_w', 0);
	update_option('thumbnail_size_h', 0);
	update_option('thumbnail_crop', 0);

	update_option('medium_size_w', 0);
	update_option('medium_size_h', 0);

	update_option('large_size_w', 0);
	update_option('large_size_h', 0);

	add_image_size( 'fb', 500, 500, 1 );

	add_image_size( '1920', 1920, 0, 0 );
	add_image_size( '1280', 1280, 0, 0 );
	add_image_size( '1024', 1024, 0, 0 );
	add_image_size( '768', 768, 0, 0 );
	add_image_size( '512', 512, 0, 0 );
	add_image_size( '265', 265, 0, 0 );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', '_s' ),
	) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
		'caption',
	) );
}
endif; // _s_setup
add_action( 'after_setup_theme', '_s_setup' );

// latest jquery
wp_deregister_script('jquery');
wp_register_script('jquery', ("http://code.jquery.com/jquery-latest.min.js"), false, '');
wp_enqueue_script('jquery');

/**
 * Enqueue scripts and styles.
 */
function _s_scripts() {
	wp_enqueue_style( '_s-style', get_template_directory_uri() . '/css/style.css' );

	$templateDir = get_bloginfo('template_directory');
	wp_enqueue_script( '_s-main', get_template_directory_uri() . '/js/main.js', array('jQuery'), '1', true );
	wp_localize_script( '_s-main', 'passedData', array( 'templateDir' => $templateDir ) );
}
add_action( 'wp_enqueue_scripts', '_s_scripts' ); 

function curPageURL() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
	$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
	$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}