<?php
//remove admin bar bump
add_action('get_header', 'my_filter_head');

//remove admin bar from front-end
function my_filter_head() {
	remove_action('wp_head', '_admin_bar_bump_cb');
	remove_action( 'wp_footer', 'wp_admin_bar_render', 1000 );
	wp_deregister_script('admin-bar');
	wp_deregister_style('admin-bar'); 	
}

function remove_admin_bar_links() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('comments');
    $wp_admin_bar->remove_menu('new-content');  
}
add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_links' );

//remove menu-items from backend
function remove_menus () {  
global $menu;  
        $restricted = array(__('Comments'),);   
        end ($menu);  
        while (prev($menu)){  
            $value = explode(' ',$menu[key($menu)][0]);  
            if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}  
        }  
}

function admin_theme_style() {
    wp_enqueue_style('_s-admin-style', get_template_directory_uri() . '/css/admin.css');
}
add_action('admin_enqueue_scripts', 'admin_theme_style');