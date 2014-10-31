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

	update_option('medium_size_w', 150);
	update_option('medium_size_h', 150);

	update_option('large_size_w', 0);
	update_option('large_size_h', 0);

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

/**
 * Enqueue scripts and styles.
 */
function _s_scripts() {
	wp_enqueue_style( '_s-style', get_template_directory_uri() . '/css/style.css' );

	// latest jquery
	wp_deregister_script('jquery');
	wp_register_script('jquery', (get_template_directory_uri() . '/js/plugins/jquery-2.1.1.min.js'), false, '');
	wp_enqueue_script('jquery');

	// we dont need jquery ui
	wp_deregister_script('jquery-ui-core');

	$templateDir = get_bloginfo('template_directory');

	wp_enqueue_script( '_s-plugins', get_template_directory_uri() . '/js/plugins/plugins-ck.js', array('jquery'), '1', true );

	wp_enqueue_script( '_s-spa', get_template_directory_uri() . '/js/spa.js', array('jquery'), '1', true );

	wp_enqueue_script( '_s-spa.lazyload', get_template_directory_uri() . '/js/spa.lazyload.js', array('jquery'), '1', true );
	wp_localize_script( '_s-spa.lazyload', 'passedData', array( 'templateDir' => $templateDir ) );

	wp_enqueue_script( '_s-spa.main', get_template_directory_uri() . '/js/spa.main.js', array('jquery'), '1', true );
	wp_localize_script( '_s-spa-main', 'passedData', array( 'templateDir' => $templateDir, 'ajaxUrl' => admin_url( 'admin-ajax.php' ), 'title' => get_bloginfo( 'name' ), 'mobileDetect' => getMobileDetectClasses() ) );

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

function my_body_class(){
	global $post;
	global $wp_query;

	$id = $wp_query->get_queried_object_id();
	$post = $wp_query->get_queried_object();
	$slug = $post->post_name;

	$title = $post->post_title;

	$cat = '';
	$type = '';
	if( is_page() ){
		// profile page, contact page
		$type = 'get_content';
	}
	else if ( is_single() ) {
			$postsCat = get_the_category( $id );
			if($postsCat){
				foreach($postsCat as $category) {
					if($category->term_id == '2'){
						// post is in category 2, so its a project
						$type = 'get_project';
						$cat = '2';
					}
					if($category->term_id == '1'){
						// post is in category 1, so its a single news post (shared)
						$type = 'get_single_news';
						$cat = '1';
					}
				}
			}
	}
	else if( is_category() ){
		$category = get_category( get_query_var( 'cat' ) );
		if(!empty($category)){
			$id = $category->cat_ID;
			$slug = $category->slug;
			$title = $category->name;
			$type = 'get_content';
			$cat = $id;
		}
	}

	if(is_home()){
		$id = 1;
		$slug = 'news';
		$title = 'News';
		$type = 'get_content';
	}

	if($title == ''){
		$title = '404';
	}
	if($slug == ''){
		$slug = '404';
	}

	$classes = '';
	$classes .= 'id-'.$id;
	$classes .= ' slug-'.$slug;
	$classes .= ' '.getMobileDetectClasses();
	if(!empty($cat)){
		$classes .= ' cat-'.$cat;
	}
	$classes = 'class="'.$classes.'"';
	if($type != ''){
		$classes .= ' data-type="'.$type.'"';
	}
	if($title != ''){
		$classes .= ' data-title="'.$title.'"';
	}
	echo $classes;
}

function getMobileDetectClasses(){
	$mobile;
	$detect = new Mobile_Detect;
	if ( !$detect->isMobile() ) {
		$mobile = 'desktop';
	}
	if ( $detect->isTablet() ) {
		$mobile = 'tablet';	
	}
	if( $detect->isMobile() && !$detect->isTablet() ){
	 	$mobile = 'phone';
	}
	return $mobile;
}

function isMobile(){
	$detect = new Mobile_Detect;
	if ( $detect->isMobile() ) {
		return true;
	}
	else{
		return false;
	}
}

function isTablet(){
	$detect = new Mobile_Detect;
	if ( $detect->isTablet() ) {
		return true;
	}
	else{
		return false;
	}
}

/**
 * Backend changes and style.
 */
require get_template_directory() . '/inc/backend.php';

/**
 * Here's what creates the website output
 */
require get_template_directory().'/layout/layout.php';

/**
 * Menu walker that gives me page/post Id's as <a href="#" id="postid">post</a> for ajax
 */
require get_template_directory() . '/inc/mywalker.php';	

/*ajax*/
require get_template_directory() . '/inc/ajax.php';

require get_template_directory() . '/inc/removerss.php';

require(get_template_directory() . '/inc/Mobile_Detect.php');