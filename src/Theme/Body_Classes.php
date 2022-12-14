<?php

namespace Tribe\Project\Theme;

class Body_Classes {

	/**
	 * @param $classes
	 * @return array
	 * @filter body_class
	 */
	public function body_classes( $classes ) {

		// Category + Search Archives: remove search body classes
		if ( Search::is_archive_search() ) {
			unset( $classes[ array_search( 'search', $classes ) ] );
			unset( $classes[ array_search( 'search-results', $classes ) ] );
		}

		if ( ! $this->is_singular() ) {
			return $classes;
		}

		global $post;

		// Panels
		if ( $this->have_panels() ) {
			$classes[] = 'has-panels';
			if ( empty( $post->post_content ) ) {
				$classes[] = 'is-panels-page';
			} else {
				$classes[] = 'has-page-content';
			}
		}

		$classes[] = $this->post_name_class( $post );

		return $classes;
	}

	private function is_singular() {
		if ( ! is_singular() ) {
			return false;
		}

		global $post;

		if ( empty( $post ) || empty( $post->ID ) ) {
			return false;
		}

		return true;
	}

	private function have_panels() {
		return ( function_exists( 'have_panels' ) && have_panels() );
	}

	private function post_name_class( $post ) {
		return sanitize_html_class( $post->post_type . '-' . $post->post_name );
	}
}
