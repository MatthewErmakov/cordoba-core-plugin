<?php
namespace Tribe\Project\Theme;

use Tribe\Project\Post_Types\Guitar\Guitar;
use Tribe\Project\Post_Types\Ukulele\Ukulele;
use Tribe\Project\Post_Types\Page\Page;
use Tribe\Project\Post_Types\Post\Post;

class Search {

	/**
	 * Customizes search query
	 *
	 * @param $query \WP_Query
	 *
	 * @return \WP_Query
	 */
	public function customize_search_query( $query ) {
		if ( ! $query->is_main_query() ) {
			return $query;
		}

		if ( ! $query->is_search() ) {
			return $query;
		}

		if ( is_admin() ) {
			return $query;
		}

		$query->set( 'post_type', [ Post::NAME, Page::NAME, Guitar::NAME, Ukulele::NAME ] );

		return $query;
	}

	/**
	 * Intercept a search and determine if it's a normal search or if it's coming from special archive search
	 *
	 * @param $template
	 *
	 * @return string
	 */
	public function route_to_proper_search( $template ) {

		// This is not a search query so bail
		if( ! is_search() ) {
			return $template;
		}

		if( ! empty( $_GET['searchArchive'] ) && 'yes' === $_GET['searchArchive'] ) {
			$archive = locate_template( 'index.php' );
		}

		if( ! empty( $archive ) ) {
			return $archive;
		}

		return $template;
	}

	/**
	 * Utility conditional for determining if this is a search from a category archive
	 *
	 * @return bool
	 */
	public static function is_archive_search() {

		if( ! is_search() ) {
			return false;
		}

		if( ! is_category() ) {
			return false;
		}

		if( empty( $_GET['searchArchive'] ) ) {
			return false;
		}

		if( 'yes' !== $_GET['searchArchive'] ) {
			return false;
		}

		return true;
	}
}
