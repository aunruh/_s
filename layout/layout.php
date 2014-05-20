<?php 
/*
Plugin Name: Layout
Plugin URI: 
Description: Layout
Version: 1.0
Author: Armin Unruh
Author URI: http://www.arminunruh.com
License: 
*/

define( 'THE_PATH', plugin_dir_path( __FILE__ ) );
define( 'THE_URL', plugin_dir_url( __FILE__ ) );

require_once( THE_PATH . 'functions.php' );

register_activation_hook( __FILE__, array( 'Layout', 'activate' ) );
register_uninstall_hook( __FILE__, array( 'Layout', 'uninstall' ) );

Layout::get_instance();