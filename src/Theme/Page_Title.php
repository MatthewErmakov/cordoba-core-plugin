<?php

namespace Tribe\Project\Theme;

use Tribe\Project\Post_Meta\Instrument_Meta;
use Tribe\Libs\Post_Type\Post_Object;
use Tribe\Project\Post_Types\Guitar\Guitar;
use Tribe\Project\Post_Types\Ukulele\Ukulele;

class Page_Title {

	/**
	 * @return string
	 */
	public function get_title() {

		if ( is_front_page() ) {
			return '';
		}

		// Blog
		if ( is_home() ) {
			$title = __( 'News', 'tribe' );
		}

		// WooCommerce: Shop
		elseif ( is_shop() ) {
			$title = __( 'Shop', 'tribe' );
		}

		// WooCommerce: Product Taxonomy
		elseif ( is_product_taxonomy() ) {
			$title = single_term_title( __( 'Shop:', 'tribe' ) . ' ', false );
		}

		// Category + Search Archives
		elseif ( Search::is_archive_search() ) {
			$cat_id  = ! empty( $_GET['cat'] ) && $_GET['cat'] !== 'none' ? $_GET['cat'] : 0;
			$cat_obj = get_term_by( 'id', $cat_id, 'category' );
			if ( ! empty( $cat_obj ) ) {
				$title = sprintf( '%s %s', __( 'Category:', 'tribe' ), $cat_obj->name );
			} else {
				$title = sprintf( '%s %s', __( 'Category:', 'tribe' ), get_queried_object()->name );
			}
		}

		// Search
		elseif ( is_search() ) {
			$title = __( 'Search Results', 'tribe' );
		}

		// 404
		elseif ( is_404() ) {
			$title = __( 'Page Not Found', 'tribe' );
		}

		// Singular: Instruments
		elseif ( is_singular( [ Guitar::NAME, Ukulele::NAME ] ) ) {
			$post_obj = Post_Object::factory( get_the_ID() );
			$title_pretty = $post_obj->get_meta( Instrument_Meta::SUBTEXT );
			$title = ! empty( $title_pretty ) ? $title_pretty : get_the_title();
		}

		// Singular
		elseif ( is_singular() ) {
			$title = get_the_title();
		}

		// Archives: Instruments
		elseif ( template_is_instrument_archive() ) {
			$title = post_type_archive_title( '', false );
		}

		// Archives
		else {
			$title = get_the_archive_title();
		}

		return $title;
	}
}
