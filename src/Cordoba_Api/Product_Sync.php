<?php
namespace Tribe\Project\Cordoba_Api;

use Pimple\Container;
use Tribe\Libs\Post_Type\Post_Object;
use Tribe\Project\Post_Meta\Exclusive_Meta;
use Tribe\Project\Post_Meta\Guitar_Meta;
use Tribe\Project\Post_Meta\Instrument_Meta;
use Tribe\Project\Post_Meta\Ukulele_Meta;
use Tribe\Project\Post_Types\Exclusive\Exclusive;
use Tribe\Project\Post_Types\Guitar\Guitar;
use Tribe\Project\Post_Types\Ukulele\Ukulele;
use Tribe\Project\Taxonomies\Construction\Construction;
use Tribe\Project\Taxonomies\Country\Country;
use Tribe\Project\Taxonomies\Electronics\Electronics;
use Tribe\Project\Taxonomies\Family\Family;
use Tribe\Project\Taxonomies\Has_Electronics\Has_Electronics;
use Tribe\Project\Taxonomies\Label\Label;
use Tribe\Project\Taxonomies\Series\Series;
use Tribe\Project\Taxonomies\Sizes\Sizes;
use Tribe\Project\Taxonomies\Style\Style;

class Product_Sync {

	const API_URL = 'https://json.cmg-portal.com/cordoba-json.php';
	const CACHE_KEY = 'cordoba-product-api';
	const CACHE_GROUP = 'products';
	const HASH = '_cordoba_product_hash';
	const ELECTRONICS_META_KEY = 'cordoba_electronics';

	// Array Keys
	const PRODUCT_UNIQUE_ID = 'pid';
	const PRODUCT_NAME = 'pname';
	const PRODUCT_SERIES_NAME = 'psname';
	const MSRP = 'map';
	const GENERAL = 'General';
	const BODY = 'Body';
	const NECK = 'Neck';
	const OTHER_FEATURES = 'Other Features';
	const ACCESSORIES = 'Strings, Case and Other Included Items';
	const LABELS = 'Website Fields';
	const SHORT_DESC = 'shortdesc';
	const TYPE = 'pcname';
	const ELECTRONICS = 'Electronics';
	const COUNTRY = 'Country of Origin';

	const API_GUITAR_TYPE = 'Cordoba Guitars';

	const CACHE_LENGTH = 300;

	public function product_sync() {

		$products = $this->get_api_products();
		if( is_wp_error( $products ) ) {
			return false;
		}

		if( empty( $products ) ) {
			return false;
		}

		$feed_changed = false;

		foreach( $products as $product ) {

			$product_hash = hash( 'sha256', json_encode( $product ) );
			$post_type = ( self::API_GUITAR_TYPE == $product->pcname ) ? Guitar::NAME : Ukulele::NAME;

            if ($product->General->Exclusive === 'Yes') {
                $post_type = Exclusive::NAME;
            }

			if( $product_id = $this->post_exists( $product->{self::PRODUCT_UNIQUE_ID}, $post_type, $product->pcname ) ) {

				$existing_product_hash = get_post_meta( $product_id, self::HASH, true );

				// Product exists and there are no changes
				if( $existing_product_hash === $product_hash ) {
					continue;
				}
			}

			// Set default status to draft
			$post_status = 'draft';
			if( ! empty( $product_id ) ) {
				// ...However if the product already exists, we need to keep it's existing status
				$post_status = get_post_status( $product_id );
			}

			if( ! empty( $assoc_args['post_status'] ) ) {
				if( array_key_exists( $assoc_args['post_status'], get_post_statuses() ) ) {
					$post_status = $assoc_args['post_status'];
				}
			}

			$product_id = $this->insert_instrument( $post_type, $product, $product_id, $post_status );
			if( is_wp_error( $product_id ) ) {
				continue;
			}

			$this->add_meta( $product_id, $product );
			$this->add_terms( $product_id, $product );
			$this->update_hash( $product_id, $product_hash );

			$feed_changed = true;
		}

		if( true === $feed_changed ) {
			file_put_contents( WP_CONTENT_DIR . '/uploads/cordoba/' . date('Y-m-d_g:i:s' ) . '.json', wp_json_encode( $products ) );
		}

	}

	/**
	 * Retrieve API data from cache or API
	 *
	 * @param $force_cache
	 *
	 * @return array|mixed|object|\WP_Error
	 */
	public function get_api_products( $force_cache = false) {
		$json = wp_cache_get( self::CACHE_KEY, self::CACHE_GROUP );
		if( false === $json || false !== $force_cache ) {

			$request = wp_remote_get( self::API_URL, [ 'timeout' => 15000, 'sslverify' => false ] );

			$http_code = wp_remote_retrieve_response_code( $request );

			if( ! in_array( $http_code, [ 200, 301, 302 ] ) ) {
				return new \WP_Error(
					'unable-to-reach-api',
					sprintf( __( 'Unable to reach remote API. Error Given: %d', 'tribe' ), (int) $http_code ) );
			}

			$json = wp_remote_retrieve_body( $request );
			if( ! empty( $json ) ) {
				wp_cache_set( self::CACHE_KEY, $json, self::CACHE_GROUP, self::CACHE_LENGTH );
			} else {
				return new \WP_Error( 'invalid-response-code', __( 'Response is empty or not JSON.', 'tribe' ) );
			}
		}

		return json_decode( $json );
	}

	/**
	 * Insert the product
	 *
	 * @param $post_type
	 * @param $product
	 * @param $product_id   int An existing WP product post ID or 0 (default) if new
	 * @param $status
	 *
	 * @return int|\WP_Error
	 */
	public function insert_instrument( $post_type, $product, $product_id = 0, $status = 'draft' ) {
		$args = [
			'post_title'            => $product->pname,
			'post_content'          => $product->longdesc,
			'post_excerpt'          => $product->shortdesc,
			'post_status'           => $status,
			'post_type'             => $post_type,
		];

		if( 0 !== $product_id && false !== $product_id ) {
			$args['ID'] = (int) $product_id;
			$product_id = wp_update_post( $args );
		} else {
			$product_id = wp_insert_post( $args );
		}
		if( is_wp_error( $product_id ) ) {
			return new \WP_Error(
				'product-insert-fail',
				sprintf( __( 'Failed to create Product %s', 'tribe' ), esc_attr( $product->pname ) )
			);
		}

		update_post_meta( $product_id, self::PRODUCT_UNIQUE_ID, $product->{self::PRODUCT_UNIQUE_ID} );

		return $product_id;
	}

	/**
	 * Add Product post meta
	 *
	 * @param $product_id
	 * @param $product
	 */
	public function add_meta( int $product_id, \stdClass $product ) {
		$post_meta = ( Ukulele::NAME === get_post_type( $product_id ) ) ? Ukulele_Meta::class : Guitar_Meta::class;
        if (get_post_type( $product_id ) === Exclusive::NAME) {
            $post_meta = Exclusive_Meta::class;
        }

		$electronics = self::OTHER_FEATURES;

		$meta_keys = [
			$post_meta::MSRP => self::MSRP,
			$post_meta::SHORT_DESCRIPTION => self::SHORT_DESC,
		];
		foreach( $meta_keys as $key => $mapping ) {
			$existing_meta = get_post_meta( $product_id, $key, true );
			if( $existing_meta !== $product->$mapping ) {
				update_post_meta( $product_id, $key, $product->$mapping );
			}
		}


		// Don't overwrite Subtext if it is different from Product portal. This is an editable field. If the PP changes it
		$subtext = Post_Object::factory( $product_id )->get_meta( Instrument_Meta::SUBTEXT );
		if( empty( $subtext ) ) {
			update_post_meta( $product_id, Instrument_Meta::SUBTEXT, $product->{self::PRODUCT_NAME} );
		}

		update_post_meta( $product_id, Metaboxes::META_KEY, $product );
	}

	/**
	 * @param $product_id
	 * @param $product
	 */
	public function add_terms( int $product_id, \stdClass $product ) {
		// Add Labels
		$labels = [];
		$most_popular = 0;

		$labels_from_api =  array_filter( (array) $product->{self::LABELS} );

		foreach ( $labels_from_api as $term_name => $value ) {
			if ( '-' !== $value && ! empty( $value ) ) {
				if ( $term_name === 'Most Popular' ) {
					$most_popular = 1;
				}else{
					$labels[] = $term_name;
				}
			}
		}

		// We want to cache most_popular as meta for ease of querying
		update_post_meta( $product_id, Instrument_Meta::MOST_POPULAR, $most_popular );

		wp_set_object_terms( $product_id, $labels, Label::NAME  );

		// Set Construction Term
		wp_set_object_terms( $product_id, $product->{self::BODY}->Construction, Construction::NAME );

		// Set Family Term
		wp_set_object_terms( $product_id, $product->{self::BODY}->Family, Family::NAME );

		if( in_array( $product->{self::BODY}->Family, [ Family::CUTAWAY_ELECTRIC, Family::TRADITIONAL ] ) ) {
			// Client needs these as Labels too
			wp_set_object_terms( $product_id, $product->{self::BODY}->Family, Label::NAME, true );
		}

		// Set Style Term
		wp_set_object_terms( $product_id, $product->{self::BODY}->Build, Style::NAME );

		// Set Country of Origin
		wp_set_object_terms( $product_id, $product->{self::GENERAL}->{self::COUNTRY}, Country::NAME );

		// Set Electronics
		if( in_array( $product->{self::OTHER_FEATURES}->{self::ELECTRONICS}, [ '', '-'] ) ) {
			wp_set_object_terms( $product_id, $product->{self::OTHER_FEATURES}->{self::ELECTRONICS}, Electronics::NAME );
			wp_set_object_terms( $product_id, Has_Electronics::WITHOUT, Has_Electronics::NAME );
		} else {
			wp_set_object_terms( $product_id, Has_Electronics::WITH, Has_Electronics::NAME );
		}

		// Set sizes for Ukuleles
		if( Ukulele::NAME === get_post_type( $product_id ) ) {
			wp_set_object_terms( $product_id, $product->{self::BODY}->{'Body Size'}, Sizes::NAME );
		}

		// Set Series for Guitars
		if( Guitar::NAME === get_post_type( $product_id ) ) {
			wp_set_object_terms( $product_id, $product->{Product_Sync::PRODUCT_SERIES_NAME},Series::NAME );
		}

	}

	/**
	 * Helper method that checks if a post exists in a particular product CPT
	 *
	 * @param $product_number
	 * @param $post_type
	 *
	 * @return array|bool
	 */
	public function post_exists( string $product_number, string $post_type, string $title ) {

		$args = [
			'post_type'                 => $post_type,
			'post_status'               => [ 'publish', 'private', 'draft', 'future' ],
			'fields'                    => 'ids',
			'update_post_meta_cache'    => false,
			'update_post_term_cache'    => false,
			'no_found_rows'             => true,
			'meta_query'                => [
				[
					'key'   => self::PRODUCT_UNIQUE_ID,
					'value' => $product_number,
				]
			]
		];

		$exists = get_posts( $args );

		// Fallback in case our custom query fails
		if( empty( $exists ) ) {
			if( ! function_exists( 'post_exists' ) ) {
				require_once ABSPATH.'/wp-admin/includes/post.php';
			}
			$post_id = post_exists( $title );
		} else {
			foreach( $exists as $id ) {
				$post_id = (int) $id;
			}
		}

		return empty( $post_id ) ? false : $post_id;
	}

	/**
	 * Sets product hash for comparisons
	 *
	 * @param $product_id
	 * @param $hash
	 */
	public function update_hash( $product_id, $hash ) {
		update_post_meta( $product_id, self::HASH, $hash );
	}

	/**
	 * Add a dashboard metabox to allow for manual sync of specs.
	 */
	public function add_dashboard_widget() {
		wp_add_dashboard_widget(
			'sync_specs_widget',
			'Sync Specs',
			array( $this, 'sync_specs_button' )
		);
	}

	/**
	 * Sync Specs Now button for dashboard metabox.
	 */
	public function sync_specs_button() {

		// Add a nonce to the URL.
		$href = wp_nonce_url( get_dashboard_url(), 'product_specs', 'sync_specs' ); ?>

		<a href="<?php echo esc_url( $href ); ?>" class="button-primary">
			<?php _e( 'Sync Specs Now', 'sync_specs' ); ?>
		</a><?php

	}

}