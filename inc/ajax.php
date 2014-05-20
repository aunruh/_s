<?php
function get_content(){
	$id = intval($_POST['id']);
	if($id != ''){
		echo Layout::get_content($id);
	}
	die();
}
add_action( 'wp_ajax_nopriv_get_content', 'get_content' );
add_action( 'wp_ajax_get_content', 'get_content' );