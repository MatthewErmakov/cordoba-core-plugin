<?php
namespace Tribe\Project\CLI;
use Tribe\Project\Cordoba_Api\Product_Sync;
use Tribe\Project\Post_Types\Exclusive\Exclusive;
use Tribe\Project\Post_Types\Guitar\Guitar;
use Tribe\Project\Post_Types\Ukulele\Ukulele;
use Tribe\Project\Taxonomies\Electronics\Electronics;
use Tribe\Project\Taxonomies\Has_Electronics\Has_Electronics;
use Tribe\Project\Taxonomies\Series\Series;

/**
 * Implements product-import command to interact with the Cordoba API
 *
 * @package Tribe\Project\CLI
 */
class Product_Import extends \WP_CLI_Command  {

	/**
	 * Imports Cordoba API Products
	 *
	 * ## OPTIONS
	 *
	 * [--force-cache]
	 * : If designated, bypass cache and retrieve data directly from API
	 *
	 * [--post_status=<post_status>]
	 * : If designated, posts will be given this status. `draft` by default
	 *
	 * [--pid=<pid>]
	 * : If designated only the product with the unique pid will be processed
	 *
	 * [--force-update]
	 * : If designated, the product will be updated even if there are no changes
	 *
	 * ## EXAMPLES
	 *
	 *     wp cordoba product import
	 *     wp cordoba product import --force-cache --pid=67 --post_status=publish
	 *
	 * @param $args
	 * @param $assoc_args
	 */
	public function import( array $args, array $assoc_args ) {
		$product_sync = tribe_project()->container()['cordoba-api.product-sync'];

		$force_cache = ( ! empty( $assoc_args['force-cache'] ) ) ? true : false;

		$products = $product_sync->get_api_products( $force_cache );
		if( is_wp_error( $products ) ) {
			\WP_CLI::error( $products->get_error_message() );
		}

		if( empty( $products ) ) {
			\WP_CLI::error( __( 'There are no products or the products feed is invalid.', 'tribe' ) );
		}

		$feed_changed = false;

		foreach( $products as $product ) {

			if( ! empty( $assoc_args['pid'] ) ) {
				if( $product->{Product_Sync::PRODUCT_UNIQUE_ID} !== $assoc_args['pid'] ) {
					continue;
				}
			}

			if( isset( $assoc_args['pid'] ) && $product->pid !== $assoc_args['pid'] ) {
				continue;
			}

			$product_id = 0;
			$update = false;
			$product_hash = hash( 'sha256', json_encode( $product ) );

			$post_type = ( Product_Sync::API_GUITAR_TYPE == $product->pcname ) ? Guitar::NAME : Ukulele::NAME;

            if ($product->General->Exclusive === 'Yes') {
                $post_type = Exclusive::NAME;
            }

			$product_id = $product_sync->post_exists( $product->{Product_Sync::PRODUCT_UNIQUE_ID}, $post_type, $product->pcname );

			if( ! empty( $product_id ) && $post_type !== 'exclusive' ) {

				$existing_product_hash = get_post_meta( $product_id, $product_sync::HASH, true );

				// Product exists and there are no changes
				if( $existing_product_hash === $product_hash) {
					\WP_CLI::warning( sprintf( __( 'Product with product name "%s" already exists. Nothing to do. Moving on.', 'tribe' ), esc_attr( $product->pname ) ) );
					continue;
				}

				$update = true;
			}

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

			$product_id = $product_sync->insert_instrument( $post_type, $product, $product_id, $post_status );

			if( is_wp_error( $product_id ) ) {
				\WP_CLI::error( $product_id, false );
				continue;
			}

			$product_sync->add_meta( $product_id, $product );
			$product_sync->add_terms( $product_id, $product );

			$product_sync->update_hash( $product_id, $product_hash );

			$insert_type = ( false === $update ) ? 'created' : 'updated';

			\WP_CLI::success( sprintf( __( 'Product %1$s %2$s with ID %3$d', 'tribe' ),
				esc_attr( $product->pname ),
				esc_attr( $insert_type ),
				(int) $product_id )
			);

			$feed_changed = true;
		}

		if( true === $feed_changed ) {
			file_put_contents( WP_CONTENT_DIR . '/uploads/cordoba/' . date('Y-m-d_g:i:s' ) . '.json', wp_json_encode( $products ) );
		}
	}

	/**
	 * Imports Series Taxonomy
	 *
	 * ## EXAMPLES
	 *
	 *     wp cordoba product import_series
	 *
	 * @param $args
	 * @param $assoc_args
	 */
	public function import_series( $args, $assoc_args ) {
		$product_sync = tribe_project()->container()['cordoba-api.product-sync'];
		$force_cache = ( ! empty( $assoc_args['force-cache'] ) ) ? true : false;

		$products = $product_sync->get_api_products( $force_cache );
		if( is_wp_error( $products ) ) {
			\WP_CLI::error( $products->get_error_message() );
		}

		if( empty( $products ) ) {
			\WP_CLI::error( __( 'There are no products or the products feed is invalid.', 'tribe' ) );
		}

		foreach( $products as $product ) {
			$post_type = ( Product_Sync::API_GUITAR_TYPE == $product->pcname ) ? Guitar::NAME : Ukulele::NAME;
			if( $post_type !== Guitar::NAME ) {
				// Not a guitar so skip
				continue;
			}

			$product_id = $product_sync->post_exists( $product->{Product_Sync::PRODUCT_UNIQUE_ID}, Guitar::NAME, $product->{Product_Sync::PRODUCT_NAME} );

			$terms = wp_set_object_terms( $product_id, $product->{Product_Sync::PRODUCT_SERIES_NAME},Series::NAME );

			if( is_wp_error( $terms ) ) {
				\WP_CLI::error( $terms->get_error_message(), false );
			} else {
				\WP_CLI::success( __( 'Instrument updated.', 'tribe' ) );
			}
		}
	}
}