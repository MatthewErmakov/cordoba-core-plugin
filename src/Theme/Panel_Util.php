<?php

namespace Tribe\Project\Theme;

use ModularContent\Panel;
use Tribe\Project\Panels\Types\Hero;
use Tribe\Project\Panels\Types\Interstitial;
use Tribe\Project\Panels\Types\Video;
use Tribe\Project\Panels\Types\Wysiwyg;

class Panel_Util {

	/**
	 * @param Panel $panel
	 * @param array $classes
	 *
	 * @return string
	 */
	public function wrapper_classes( Panel $panel, $classes = [] ) {
		$type = $panel->get( 'type' );

		$classes[] = sprintf( 'panel--type-%s', esc_attr( $type ) );

		// CASE: Supports Background Image
		if ( $this->has_background_image( $panel ) ) {
			$classes[] = 'panel--supports-bgd-image';
			$classes[] = 'u-bc-shark';
			$classes[] = 't-content--light';
		}

		// CASE: Interstitial Panel
		else if ( $type === Interstitial::NAME ) {
			$classes[] = 'u-bc-desert-storm';
			$classes[] = 't-content--dark';
		}

		// CASE: Everything Else
		else {
			$classes[] = 'u-bc-white';
			$classes[] = 't-content--dark';
		}

		// CASE: WYSIWYG panel style
		if ( $type === Wysiwyg::NAME ) {
			$classes[] = 'panel--type-wysiwyg--style-is-' . $panel->get( Wysiwyg::FIELD_STYLE );
		}

		// CASE: Video panel style
		if ( $type === Video::NAME ) {
			$classes[] = 'panel--type-video--style-is-' . $panel->get( Video::FIELD_STYLE );
		}

		return sprintf( ' class="%s"', implode( ' ', array_unique( $classes ) ) );
	}

	private function has_background_image( Panel $panel ) {
		$type = $panel->get( 'type' );

		if (
			$type === Hero::NAME ||
			(
				$type === Video::NAME &&
				( $panel->get( Video::FIELD_STYLE ) === Video::FIELD_STYLE_FULL )
			)
		) {
			return true;
		}

		return false;
	}

}
