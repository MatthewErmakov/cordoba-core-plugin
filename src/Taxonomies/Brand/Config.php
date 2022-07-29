<?php
namespace Tribe\Project\Taxonomies\Brand;

use Tribe\Libs\Taxonomy\Taxonomy_Config;

class Config extends Taxonomy_Config {

	public function get_args() {
		return [
			'hierarchical'      => true,
			'public'            => true,
			'publicly_queryable'=> true,
			'show_in_rest'      => true
		];
	}

	public function get_labels() {
		return [
			'singular' => __( 'Brand', 'tribe' ),
			'plural'   => __( 'Brands', 'tribe' ),
			'slug'     => __( 'brand', 'tribe' ),
		];
	}
}