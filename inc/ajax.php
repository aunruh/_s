<?php
function get_content(){
	$id = intval($_POST['id']);
	$type = $_POST['type'];
	$currentSize = $_POST['currentSize'];
	switch($type){
		case 'get_content':
			Layout::get_content($id);
		break;
	}
	die();
}
add_action( 'wp_ajax_nopriv_get_content', 'get_content' );
add_action( 'wp_ajax_get_content', 'get_content' );