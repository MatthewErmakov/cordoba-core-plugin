<?php
namespace Tribe\Project\Post_Types\Guitar;

use Tribe\Libs\Post_Type\Post_Type_Config;

class Config extends Post_Type_Config {
	public function get_args() {
		return [
			'hierarchical'     => false,
			'enter_title_here' => __( 'Guitar', 'tribe' ),
			'map_meta_cap'     => true,
			'supports'         => [ 'title', 'thumbnail', 'modular-content' ],
			'menu_icon'        => 'dashicons-format-audio',
			'show_in_rest'     => true,
		];
	}

	public function get_labels() {
		return [
			'singular' => __( 'Guitar', 'tribe' ),
			'plural'   => __( 'Guitars', 'tribe' ),
			'slug'     => __( 'guitars', 'tribe' ),
		];
	}
}
