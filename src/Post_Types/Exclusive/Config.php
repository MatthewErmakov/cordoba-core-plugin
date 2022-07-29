<?php
namespace Tribe\Project\Post_Types\Exclusive;

use Tribe\Libs\Post_Type\Post_Type_Config;

class Config extends Post_Type_Config {
	public function get_args() {
		return [
			'hierarchical'     => false,
			'enter_title_here' => __( 'Exclusive', 'tribe' ),
			'map_meta_cap'     => true,
			'supports'         => [ 'title', 'thumbnail', 'modular-content' ],
			'menu_icon'        => 'dashicons-format-audio',
			'show_in_rest'     => true,
		];
	}

	public function get_labels() {
		return [
			'singular' => __( 'Exclusive', 'tribe' ),
			'plural'   => __( 'Exclusives', 'tribe' ),
			'slug'     => __( 'exclusives', 'tribe' ),
		];
	}
}
