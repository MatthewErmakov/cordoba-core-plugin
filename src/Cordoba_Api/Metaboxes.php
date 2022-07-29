<?php
namespace Tribe\Project\Cordoba_Api;

use Tribe\Project\Post_Types\Guitar\Guitar;
use Tribe\Project\Post_Types\Ukulele\Ukulele;
use Tribe\Project\Post_Types\Exclusive\Exclusive;
use Tribe\Project\Taxonomies\Label\Label;

class Metaboxes {
	const API_TAX_META_ID   = 'api-taxonomy';
	const DECRIPTION        = 'description';
	const SHORT_DESCRIPTION = 'shortdesc';
	const META_KEY = 'cordoba_api_meta';

	public $api_meta;

	public function __construct() {
		$this->api_meta = get_post_meta( get_the_ID(), self::META_KEY, true );
	}

	public function add_meta_boxes() {
		add_meta_box(
		self::API_TAX_META_ID,
			__( 'Cordoba API Taxonomies', 'tribe' ),
			[ $this, 'api_taxonomy_display' ],
			[ Guitar::NAME, Ukulele::NAME, Exclusive::NAME ],
			'normal',
			'high'
		);

		add_meta_box(
		self::DECRIPTION,
			__( 'Description', 'tribe' ),
			[ $this, 'api_description' ],
			[ Guitar::NAME, Ukulele::NAME, Exclusive::NAME ],
			'normal',
			'high'
		);

		add_meta_box(
		self::SHORT_DESCRIPTION,
			__( 'Short Description', 'tribe' ),
			[ $this, 'api_short_description' ],
			[ Guitar::NAME, Ukulele::NAME, Exclusive::NAME ],
			'normal',
			'high'
		);
	}

	/**
	 * Displays API-driven shadow-taxonomy terms
	 *
	 * @param \WP_Post $post
	 */
	public function api_taxonomy_display( \WP_Post $post ) {
		$taxonomies = get_object_taxonomies( [ Guitar::NAME, Ukulele::NAME, Exclusive::NAME ] );

		$list_items = [];

		foreach ( $taxonomies as $key => $taxonomy ) {
			$terms_for_instrument = get_the_term_list( get_the_ID(), $taxonomy, null, ', ' );
			if ( ! empty( $terms_for_instrument ) ) {
				$list_items[] = sprintf( '<li><strong>%1$s:</strong> %2$s</li>', esc_html( ucwords( $taxonomy ) ), $terms_for_instrument );
			}
		}


		if ( ! empty( $list_items ) ) {
			$html = '<ul>';
			foreach ( $list_items as $list_item ) {
				$html .= $list_item;
			}
			$html .= '</ul>';

			echo $html;
		} else {
			echo sprintf( '<p><em>%s</em></p>', esc_html__( 'No Information Collected', 'tribe' ) );
		}
	}

	/**
	 * Displays API-collected Description
	 */
	public function api_description() {
		setup_postdata( get_the_ID() );

		if( get_the_content() ) {
			the_content();
		} else {
			echo sprintf( '<p><em>%s</em></p>', esc_html__( 'No Description Collected', 'tribe' ) );
		}
	}

	/**
	 * Displays API-collected Short Description
	 */
	public function api_short_description() {
		if( get_the_excerpt( get_the_ID() ) ) {
			the_excerpt();
		} else {
			echo sprintf( '<p><em>%s</em></p>', esc_html__( 'No Short Description Collected', 'tribe' ) );
		}
	}
}