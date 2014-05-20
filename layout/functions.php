<?php
class Layout{

	protected $version = '1.0';

	protected $plugin_slug = 'layout';

	protected $plugin_name = 'Layout';

	protected static $instance = null;


	public function __construct() {


	}

	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function get_content($postid){

		echo 'content ';
		echo $postid;

	}

}