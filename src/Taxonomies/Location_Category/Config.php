<?php
namespace Tribe\Project\Taxonomies\Location_Category;

use Tribe\Libs\Taxonomy\Taxonomy_Config;

class Config extends Taxonomy_Config {

	public function get_args() {
		$loc_cat_labels = [
			'name'                       => _x( 'Location Categories', 'Taxonomy General Name', 'bh-storelocator' ),
			'singular_name'              => _x( 'Location Category', 'Taxonomy Singular Name', 'bh-storelocator' ),
			'menu_name'                  => __( 'Location Categories', 'bh-storelocator' ),
			'all_items'                  => __( 'All Categories', 'bh-storelocator' ),
			'parent_item'                => __( 'Parent Category', 'bh-storelocator' ),
			'parent_item_colon'          => __( 'Parent Category:', 'bh-storelocator' ),
			'new_item_name'              => __( 'New Item Name', 'bh-storelocator' ),
			'add_new_item'               => __( 'Add New Category', 'bh-storelocator' ),
			'edit_item'                  => __( 'Edit Category', 'bh-storelocator' ),
			'update_item'                => __( 'Update Category', 'bh-storelocator' ),
			'view_item'                  => __( 'View Category', 'bh-storelocator' ),
			'separate_items_with_commas' => __( 'Separate categories with commas', 'bh-storelocator' ),
			'add_or_remove_items'        => __( 'Add or remove categories', 'bh-storelocator' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'bh-storelocator' ),
			'popular_items'              => __( 'Popular Categories', 'bh-storelocator' ),
			'search_items'               => __( 'Search Categories', 'bh-storelocator' ),
			'not_found'                  => __( 'Not Found', 'bh-storelocator' ),
		];

		return [
			'labels'                     => $loc_cat_labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'rewrite'                    => array( 'slug' => 'locations-category' ),
		];
	}

	public function get_labels() {
		return [
			'singular' => __( 'Location Category', 'tribe' ),
			'plural'   => __( 'Location Categories', 'tribe' ),
			'slug'     => __( 'locations-category', 'tribe' ),
		];
	}
}