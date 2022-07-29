<?php

namespace Tribe\Project\Theme;

class Sidebars {

	/**
	 * Register sidebars used throughout the site.
	 */
	public static function register_sidebars() {
		register_sidebar(
			[
				'name'          => __( 'Sidebar: Shop Filters', 'tribe' ),
				'id'            => 'sidebar-shop',
				'description'   => __( 'Shop filters sidebar area', 'tribe' ),
				'class'         => 'shop-filters',
				'before_widget' => '<div class="shop-filters__filter shop-filters__filter--%2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="shop-filters__filter__title">',
				'after_title'   => '</h3>'
			]
		);
	}
}
