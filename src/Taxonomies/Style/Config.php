<?php
namespace Tribe\Project\Taxonomies\Style;

use Tribe\Libs\Taxonomy\Taxonomy_Config;

class Config extends Taxonomy_Config {

	public function get_args() {
		return [
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => false,
			'show_in_nav_menus' => false,
			'show_in_menu'      => false,
			'publicly_queryable'=> true,
			'show_in_rest'      => true
		];
	}

	public function get_labels() {
		return [
			'singular' => __( 'Style', 'tribe' ),
			'plural'   => __( 'Styles', 'tribe' ),
			'slug'     => __( 'style', 'tribe' ),
		];
	}
}