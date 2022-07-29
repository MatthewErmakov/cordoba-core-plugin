<?php
namespace Tribe\Project\Post_Types\Locations;

use Tribe\Libs\Post_Type\Post_Type_Config;

class Config extends Post_Type_Config {
	public function get_args() {

		$locations_labels = array(
			'name'                => _x( 'Locations', 'Post Type General Name', 'bh-storelocator' ),
			'singular_name'       => _x( 'Location', 'Post Type Singular Name', 'bh-storelocator' ),
			'menu_name'           => __( 'Locations', 'bh-storelocator' ),
			'name_admin_bar'      => __( 'Locations', 'bh-storelocator' ),
			'parent_item_colon'   => __( 'Parent Location:', 'bh-storelocator' ),
			'all_items'           => __( 'All Locations', 'bh-storelocator' ),
			'add_new_item'        => __( 'Add New Location', 'bh-storelocator' ),
			'add_new'             => __( 'Add New', 'bh-storelocator' ),
			'new_item'            => __( 'New Location', 'bh-storelocator' ),
			'edit_item'           => __( 'Edit Location', 'bh-storelocator' ),
			'update_item'         => __( 'Update Location', 'bh-storelocator' ),
			'view_item'           => __( 'View Location', 'bh-storelocator' ),
			'search_items'        => __( 'Search Locations', 'bh-storelocator' ),
			'not_found'           => __( 'No Locations found', 'bh-storelocator' ),
			'not_found_in_trash'  => __( 'No Locations found in Trash', 'bh-storelocator' ),
		);

		return [
			'label'               => __( 'Locations', 'bh-storelocator' ),
			'description'         => __( 'Locations', 'bh-storelocator' ),
			'labels'              => $locations_labels,
			'supports'            => array( 'title', 'editor', 'author', 'excerpt', 'revisions', 'thumbnail', 'custom-fields', 'comments' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-location-alt',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'rewrite'             => array( 'slug' => 'locations', 'with_front' => false ),
			'show_in_rest'        => true,
		];
	}
	public function get_labels() {
		return [
			'singular' => __( 'Location', 'tribe' ),
			'plural'   => __( 'Locations', 'tribe' ),
			'slug'     => __( 'locations', 'tribe' ),
		];
	}
}
