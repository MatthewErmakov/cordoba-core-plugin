<?php
namespace Tribe\Project\CLI;

use Tribe\Project\Post_Types\Guitar\Guitar;
use Tribe\Project\Post_Types\Ukulele\Ukulele;

class Product_ID_Update extends \WP_CLI_Command {

	/**
	 * Updates sageitemno from product feed
	 *
	 * ## EXAMPLES
	 *
	 *     wp cordoba product cordoba product-id-update
	 *
	 * @param $args
	 * @param $assoc_args
	 **/
	public function product_id_update( $args, $assoc_args ) {
		$product_sync = tribe_project()->container()['cordoba-api.product-sync'];
		$products = $product_sync->get_api_products( false );

		if( is_wp_error( $products ) ) {
			\WP_CLI::error( $products->get_error_message() );
		}

		if( empty( $products ) ) {
			\WP_CLI::error( __( 'There are no products or the products feed is invalid.', 'tribe' ) );
		}

		foreach( $products as $product ) {

			$product_hash = hash( 'sha256', json_encode( $product ) );
			$post_type = ( $product_sync::API_GUITAR_TYPE == $product->pcname ) ? Guitar::NAME : Ukulele::NAME;

			$args = [
				'post_type'                 => $post_type,
				'title'                     => $product->pname,
				'post_status'               => [ 'publish', 'private', 'draft', 'future' ],
				'fields'                    => 'ids',
				'update_post_meta_cache'    => false,
				'update_post_term_cache'    => false,
				'no_found_rows'             => true,
			];

			$exists = get_posts( $args );
			if( empty( $exists ) ) {
				\WP_CLI::error( __( 'No products matching', 'tribe' ), false );
				continue;
			}

			$product_id = array_shift( $exists );

			update_post_meta( $product_id, $product_sync::PRODUCT_UNIQUE_ID, $product->{$product_sync::PRODUCT_UNIQUE_ID} );
			$product_sync->update_hash( $product_id, $product_hash );

			\WP_CLI::success( sprintf( __( 'Updated Post %s.', 'tribe' ), (int) $product_id ) );
		}
	}
}