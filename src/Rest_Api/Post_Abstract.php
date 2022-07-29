<?php

namespace Tribe\Project\Rest_Api;

use Tribe\Libs\Post_Type\Post_Object;
use Tribe\Project\Post_Meta\Instrument_Meta;
use Tribe\Project\Post_Types\Guitar\Guitar as Guitar_CPT;
use Tribe\Project\Post_Types\Ukulele\Ukulele as Ukulele_CPT;
use Tribe\Project\Taxonomies\Family\Family;
use Tribe\Project\Taxonomies\Label\Label;
use Tribe\Project\Taxonomies\Style\Style;
use Tribe\Project\Theme\Single_Instruments;

abstract class Post_Abstract {

	const MOST_POPULAR = 'most-popular';

	/**
	 * taxonomies
	 *
	 * which taxonomies will show in object
	 *
	 * @var array
	 */
	protected $taxonomies = [];

	/**
	 * allowed_meta_keys
	 *
	 * Which keys may be queried against
	 *
	 * @var array
	 */
	protected $allowed_meta_keys = [];

	/**
	 * related
	 *
	 * Which connected may be queried against
	 *
	 * @var array
	 */
	protected $related = [];


	/**
	 * Hooks
	 */
	public function hook() {
		add_action( 'rest_api_init', [ $this, 'add_fields' ] );
		add_filter( 'rest_prepare_' . static::POST_TYPE, [ $this, 'add_stripped_content' ], 10, 3 );
		add_filter( 'rest_' . static::POST_TYPE . '_query', [ $this, 'allow_meta_queries' ], 10, 2 );
		add_filter( 'rest_' . static::POST_TYPE . '_query', [ $this, 'allow_related_queries' ], 10, 2 );
		add_filter( 'rest_' . static::POST_TYPE . '_query', [ $this, 'sort_options' ], 10, 2 );

		add_filter( 'rest_' . static::POST_TYPE . '_collection_params', [ $this, 'add_msrp_orderby' ], 10, 1 );

		add_filter( 'pre_get_posts', [ $this, 'set_hide_from_loop' ] );
		add_filter( 'pre_get_posts', [ $this, 'inital_sort' ] );
	}

	/**
	 * Register custom REST fields
	 */
	public function add_fields() {

		register_rest_field(
			static::POST_TYPE,
			'thumbnail',
			[
				'get_callback' => [ $this, 'render_thumbnail' ],
			]
		);

		register_rest_field(
			static::POST_TYPE,
			'meta',
			[
				'get_callback' => [ $this, 'render_meta' ],
			]
		);

		register_rest_field(
			static::POST_TYPE,
			'swatches',
			[
				'get_callback' => [ $this, 'render_swatches' ],
				'schema'       => null,
			]
		);
	}

	/**
	 * @param $response
	 * @param $post
	 *
	 * @return mixed
	 */
	public function add_stripped_content( $response, $post ) {
		$content = $post->post_content;
		$content = str_replace( PHP_EOL, " ", $content );
		$content = str_replace( '  ', ' ', $content );

		$response->data['content']['stripped'] = wp_strip_all_tags( $content );

		return $response;
	}

	/**
	 * Adds thumbnail property to rest response
	 *
	 * @param $object
	 *
	 * @return false|string
	 */
	public function render_thumbnail( $object ) {
		$url = get_the_post_thumbnail_url( $object['id'] );

		return $url ?: 'test';
	}

	/**
	 * Add meta property to rest response
	 *
	 * @param $object
	 * @param $field_name
	 * @param $request
	 *
	 * @return mixed
	 */
	public function render_meta( $object, $field_name, $request ) {
		$meta = get_post_meta( $object['id'] );

		foreach ( (array) $meta as $_key => $_item ) {
			if ( 'msrp' === $_key ) {
				$meta[ $_key ] = Single_Instruments::get_msrp( $object['id'] );
			} else if ( is_protected_meta( $_key, 'post' ) ) {
				unset( $meta[ $_key ] );
			} else {
				if ( is_array( $meta[ $_key ] ) && count( $meta[ $_key ] ) === 1 ) {
					$meta[ $_key ] = array_shift( $meta[ $_key ] );
				}
			}
		}

		return $meta;
	}

	/**
	 * Add swatches property to REST response
	 *
	 * @param $object
	 * @param $field_name
	 * @param $request
	 *
	 * @return array
	 */
	public function render_swatches( $object, $field_name, $request ) {
		return array_values( $this->get_p2p_swatched_posts( $object['id'] ) );
	}

	/**
	 * Allows REST request to include meta
	 *
	 * @param $query_args
	 * @param $request
	 *
	 * @return mixed
	 */
	public function allow_meta_queries( $query_args, $request ) {
		foreach ( $this->allowed_meta_keys as $_key ) {
			$query_args['meta_query'] = $this->get_hide_from_loop_meta_query();
		}

		return $query_args;
	}

	/**
	 * Removes items from REST response that are set to be hidden
	 *
	 * @param $query
	 *
	 * @return mixed
	 */
	public function set_hide_from_loop( $query ) {
		if ( is_post_type_archive( [ Guitar::POST_TYPE, Ukulele::POST_TYPE ] ) && ! is_admin() && $query->is_main_query() ) {
			$query->set( 'meta_query', $this->get_hide_from_loop_meta_query() );
		}

		return $query;
	}

	/**
	 * Puts the Most Popular items at the top
	 *
	 * @param $query
	 *
	 * @return mixed
	 */
	public function inital_sort( $query ) {
		if ( is_post_type_archive( [ Guitar::POST_TYPE, Ukulele::POST_TYPE ] ) && ! is_admin() && $query->is_main_query() ) {

			$query->set( 'meta_key', Instrument_Meta::MOST_POPULAR );

			$query->set(
				'orderby',
				[
					'meta_value_num' => 'DESC',
					'date'           => 'DESC',
					'name'           => 'ASC',
				]
			);

		}

		return $query;
	}

	/**
	 * Adds appropriate meta_query to the WP_Query
	 *
	 * @return array
	 */
	public function get_hide_from_loop_meta_query() {
		return [[
			'relation' => 'OR',
			[
				'key'     => 'hide_from_loop',
				'compare' => 'NOT EXISTS',
			],
			[
				'key'     => 'hide_from_loop',
				'value'   => '0',
				'compare' => '=',
			],
		]];
	}

	/**
	 * Handle orderby MSRP in WP_Query
	 *
	 * @param                  $query_args
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed
	 */
	public function sort_options( $query_args, \WP_REST_Request $request ) {
		$orderby = $request->get_param( 'orderby' );
		$order   = ! empty( $request->get_param( 'order' ) ) ? $request->get_param( 'order' ) : 'desc';
		$label   = $request->get_param( 'label' );


		// Default sort is a bit tricky because it wants the Most Popular
		// Instruments first, then by date, and for equal date, alphabetical
		if ( ( empty( $orderby ) || $orderby === 'date' ) && ! in_array( '-1', $label ) ) {

			// Needed for the sort to work
			$query_args['meta_key'] = Instrument_Meta::MOST_POPULAR;

			$query_args['orderby'] = [
				'meta_value_num' => 'DESC',
				'date'           => $order,
				'name'           => 'ASC',
			];

		} elseif ( Instrument_Meta::MSRP === $orderby ) {
			$query_args['orderby']  = 'meta_value';
			$query_args['meta_key'] = Instrument_Meta::MSRP;
			$query_args['order']    = $order;
		} else {
			$query_args['order'] = $order;
		}


		//Special case for new Most Popular Meta
		if ( in_array( '-1', $label ) ) {
			if ( empty( $query_args['meta_query'] ) ) {
				$query_args['meta_query'] = [];
			}

			$query_args['meta_query']['relation'] = 'AND';
			$query_args['meta_query'][] = [
				'key'     => Instrument_Meta::MOST_POPULAR,
				'value'   => 1,
				'compare' => '=',
			];
		}

		// Lefty which is not really Lefty
		if ( ! empty( $_GET[ Style::NAME ] ) && in_array( '188', $_GET[ Style::NAME ] ) ) {
			if ( empty( $query_args['tax_query'] ) ) {
				$query_args['tax_query'] = [];
			}

			$query_args['tax_query']['relation'] = 'OR';
			$label[] = 26;
		}


		$label = array_filter(
			$label,
			function ( $item ) {
				return $item > 0;
			}
		);

		$request->set_param( 'label', $label );

		return $query_args;
	}

	/**
	 * Enables p2p querying for REST response
	 *
	 * @param $query_args
	 * @param $request
	 *
	 * @return mixed
	 */
	public function allow_related_queries( $query_args, $request ) {
		foreach ( $this->related as $_connection ) {
			if ( isset( $request[ $_connection ] ) ) {
				$query_args['connected_type']   = $_connection;
				$query_args['connected_items']  = $request[ $_connection ];
				$query_args['suppress_filters'] = false;
			}
		}

		return $query_args;
	}

	/**
	 * Adds p2p swatch query to WP_Query
	 *
	 * @param $post_id
	 *
	 * @return array
	 */
	public function get_p2p_swatched_posts( $post_id ) {

		$p2p = is_post_type_archive( Guitar_CPT::NAME ) ? Guitar_CPT::NAME . '_to_' . Guitar_CPT::NAME : Ukulele_CPT::NAME . '_to_' . Ukulele_CPT::NAME;

		$query = new \WP_Query(
			[
				'connected_type'  => $p2p,
				'connected_items' => $post_id,
			]
		);


		if ( ! $query->have_posts() ) {
			return [];
		}

		$data = [];

		while ( $query->have_posts() ) {
			$query->the_post();

			$label = Post_Object::factory( get_the_ID() )->get_meta( Instrument_Meta::SWATCH_LABEL );
			$image = Post_Object::factory( get_the_ID() )->get_meta( Instrument_Meta::SWATCH_IMAGE );

			if ( empty( $label ) || empty( $image ) ) {
				continue;
			}

			$image_id = $image['ID'];
			$title    = get_the_title( get_the_ID() );

			$image_url = wp_get_attachment_image_url( $image_id, 'swatch' );

			$data[ get_the_ID() ][ Instrument_Meta::SWATCH_ID ]    = $image_id;
			$data[ get_the_ID() ][ Instrument_Meta::SWATCH_TITLE ] = $title;
			$data[ get_the_ID() ][ Instrument_Meta::SWATCH_LABEL ] = $label;
			$data[ get_the_ID() ][ Instrument_Meta::SWATCH_IMAGE ] = $image_url;
		}

		wp_reset_postdata();

		return $data;
	}


	/**
	 * Add MSRP as a legal orderby
	 *
	 * @param $params
	 *
	 * @return mixed
	 */
	public function add_msrp_orderby( $params ) {
		$params['orderby']['enum'][] = 'msrp';

		return $params;
	}
}
