<?php
namespace Tribe\Project\Taxonomies\Series;

use Tribe\Libs\Taxonomy\Taxonomy_Config;
use Tribe\Project\Taxonomies;


class Config extends Taxonomy_Config {

	public function get_args() {
		return [
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_in_nav_menus' => true,
			'show_in_menu'      => true,
			'publicly_queryable'=> true,
			'show_in_rest'      => true
		];
	}
	public function get_labels() {
		return [
			'singular' => __( 'Series', 'tribe' ),
			'plural'   => __( 'Series', 'tribe' ),
			'slug'     => __( 'series', 'tribe' ),
		];
	}
}