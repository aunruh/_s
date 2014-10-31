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
		$result;
		$trans = get_template_directory_uri()."/img/trans.png";
		$elements = get_field('elements', $postid);

		if($elements){
			foreach($elements as $row){

				// var_dump($row['image']);
				// $alt = $img['alt'];
				$img = $row['image'];

				$_1920 = $img['sizes']['1920'];
				$_1280 = $img['sizes']['1280'];
				$_1024 = $img['sizes']['1024'];
				$_768 = $img['sizes']['768'];				
				$_512 = $img['sizes']['512'];				
				$_265 = $img['sizes']['265'];			
				$full = $img['sizes']['full'];

				$result .= 
				'<div class="c">
					<div>
						<a href="#">
							<img src="'.$trans.'" class="lazy" data-r265="'.$_265.'" data-r512="'.$_512.'" data-r768="'.$_768.'" data-r1024='.$_1024.' data-r1280='.$_1280.' data-r1920='.$_1920.'>
						</a>
					</div>
				</div>';
			}
		}

		return $result;

	}

}