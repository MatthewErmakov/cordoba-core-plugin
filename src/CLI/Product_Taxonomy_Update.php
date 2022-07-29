<?php
namespace Tribe\Project\CLI;


use Tribe\Project\Cordoba_Api\Product_Sync;
use Tribe\Project\Post_Meta\Instrument_Meta;
use Tribe\Project\Post_Types\Guitar\Guitar;
use Tribe\Project\Post_Types\Ukulele\Ukulele;
use Tribe\Project\Rest_Api\Post_Abstract;
use Tribe\Project\Taxonomies\Family\Family;
use Tribe\Project\Taxonomies\Has_Electronics\Has_Electronics;
use Tribe\Project\Taxonomies\Label\Label;
use Tribe\Project\Taxonomies\Sizes\Sizes;

class Product_Taxonomy_Update extends \WP_CLI_Command {

	/**
	 * Converts Most Popular from taxonomy term to meta
	 *
	 * ## OPTIONS
	 *
	 * [--force-cache]
	 * : If designated, bypass cache and retrieve data directly from API
	 *
	 * ## EXAMPLES
	 *
	 *     wp cordoba convert-most-popular
	 *
	 * @param $args
	 * @param $assoc_args
	 *
	 * @subcommand most-popular
	 *
	 **/
	public function covert_most_popular( $args, $assoc_args ) {

		$product_sync = tribe_project()->container()['cordoba-api.product-sync'];

		$force_cache = ( ! empty( $assoc_args['force-cache'] ) ) ? true : false;

		$products = $product_sync->get_api_products( $force_cache );

		if ( is_wp_error( $products ) ) {
			\WP_CLI::error( $products->get_error_message() );
		}

		if ( empty( $products ) ) {
			\WP_CLI::error( __( 'There are no products or the products feed is invalid.', 'tribe' ) );
		}

		foreach ( (array) $products as $product ) {

			if ( empty( $product->pname ) ) {
				continue;
			}

			$post_type = ( Product_Sync::API_GUITAR_TYPE === $product->pcname ) ? Guitar::NAME : Ukulele::NAME;

			$product_id = $product_sync->post_exists( $product->{Product_Sync::PRODUCT_UNIQUE_ID}, $post_type, $product->pname );

			if ( empty( $product_id ) ) {
				continue;
			}

			$most_popular = 0;
			if ( isset( $product->{Product_Sync::LABELS}->{'Most Popular'} ) && $product->{Product_Sync::LABELS}->{'Most Popular'} === 'Yes' ) {
				$most_popular = 1;
				\WP_CLI::line( sprintf( 'Setting product ID %s as Most Popular', $product_id ) );
			}

			update_post_meta( $product_id, Instrument_Meta::MOST_POPULAR, $most_popular );
			wp_remove_object_terms( $product_id, [ Post_Abstract::MOST_POPULAR ], Label::NAME );
		}

		\WP_CLI::success( 'wazaaaa!' );
	}

	/**
	 * Updates has-electronics taxonomy to With or Without terms if Electronics exist.
	 *
	 * ## OPTIONS
	 *
	 * [--force-cache]
	 * : If designated, bypass cache and retrieve data directly from API
	 *
	 * ## EXAMPLES
	 *
	 *     wp cordoba taxonomy-update has-electronics
	 *     wp cordoba taxonomy-update has-electronics --force-cache
	 *
	 * @param $args
	 * @param $assoc_args
	 *
	 * @subcommand has-electronics
	 **/
	public function update_has_electronics( $args, $assoc_args ) {
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
			$product_hash = hash( 'sha256', json_encode( $product ) );
			$post_type = ( Product_Sync::API_GUITAR_TYPE == $product->pcname ) ? Guitar::NAME : Ukulele::NAME;

			if( empty( $product->pname ) ) {
				continue;
			}

			$product_id = $product_sync->post_exists( $product->{Product_Sync::PRODUCT_UNIQUE_ID}, $post_type, $product->pname );
			if( empty( $product_id ) ) {
				continue;
			}

			// Update Electronics
			if( in_array( $product->{$product_sync::OTHER_FEATURES}->{$product_sync::ELECTRONICS}, [ '', '-'] ) ) {
				wp_set_object_terms( $product_id, Has_Electronics::WITHOUT, Has_Electronics::NAME );
				$type = Has_Electronics::WITHOUT;
			} else {
				wp_set_object_terms( $product_id, Has_Electronics::WITH, Has_Electronics::NAME );
				$type = Has_Electronics::WITH;
			}

			$product_sync->update_hash( $product_id, $product_hash );

			\WP_CLI::success( sprintf( __( 'Product %1$s updated. Product is %2$s electronics', 'tribe' ),
					esc_attr( $product->pname ),
					esc_attr( $type )
			) );
		}
	}

	/**
	 * Updates size taxonomy on Ukuleles only
	 *
	 * ## EXAMPLES
	 *
	 *     wp cordoba taxonomy-update update-size
	 *
	 * @param $args
	 * @param $assoc_args
	 *
	 * @subcommand update-size
	 **/
	public function update_size( $args, $assoc_args ) {
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
			if( Guitar::NAME === $post_type ) {
				\WP_CLI::warning( sprintf( __( '%s is a Guitar. Nothing to do.', 'tribe' ), esc_attr( $product->{Product_Sync::PRODUCT_NAME} ) ) );
			}

			$product_id = $product_sync->post_exists( $product->{Product_Sync::PRODUCT_UNIQUE_ID}, $post_type );
			if( empty( $product_id ) ) {
				\WP_CLI::warning( sprintf( __( '%s does not exist so cannot have terms updated', 'tribe' ), esc_attr( $product->{Product_Sync::PRODUCT_NAME} ) ) );
			}

			if( wp_set_object_terms( $product_id, $product->{Product_Sync::BODY}->{'Body Size'}, Sizes::NAME ) ) {
				\WP_CLI::success( sprintf( __( 'Product %1$s updated.', 'tribe' ), esc_attr( $product->pname ) ) );
			}
		}
	}

	/**
	 * Sets special Family terms as Labels
	 *
	 * ## EXAMPLES
	 *
	 *     wp cordoba product update_labels
	 *
	 * @param $args
	 * @param $assoc_args
	 *
	 * @subcommand update-labels
	 */
	public function update_labels( $args, $assoc_args ) {
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
			$product_id = $product_sync->post_exists( $product->{Product_Sync::PRODUCT_UNIQUE_ID}, $post_type, $product->{Product_Sync::PRODUCT_NAME} );

			if( $post_type !== Guitar::NAME ) {
				$labels = [];
				foreach( $product->{Product_Sync::LABELS} as $term_name => $label ) {
					if( '-' === $label ) {
						$labels[] = $term_name;
					}
				}
				$terms = wp_set_object_terms( $product_id, $labels, Label::NAME );
			} else {

				if( in_array( $product->{Product_Sync::BODY}->Family, [ Family::CUTAWAY_ELECTRIC, Family::TRADITIONAL ] ) ) {
					// Client needs these as Labels too
					$terms = wp_set_object_terms( $product_id, $product->{Product_Sync::BODY}->Family, Label::NAME );
				}
			}

			if( is_wp_error( $terms ) ) {
				\WP_CLI::error( $terms->get_error_message(), false );
			} else {
				\WP_CLI::success( __( 'Instrument updated.', 'tribe' ) );
			}
		}
	}
}