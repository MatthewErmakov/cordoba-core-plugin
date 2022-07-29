<?php

namespace Tribe\Project\CLI;

use Tribe\Project\Post_Types\Locations;
use Tribe\Project\Dealers\Dealers;
use Tribe\Project\Taxonomies\Brand\Brand;

/**
 * Implements dealer-locator command
 *
 * @package Tribe\Project\CLI
 */
class Dealer_Locator extends \WP_CLI_Command {

	const LOCATION_CATEGORY_TAXONOMY_TYPE = 'bh_sl_loc_cat';
	const GUILD_IDENTIFIER = 'G';

	/**
	 * Imports Dealer Search data
	 *
	 * ## OPTIONS
	 *
	 * [--dry-run]
	 * : If designated, the command will not do an import but iterate over the JSON and determine how many dealers will be imported when not run with dry-run.
	 *
	 * ## EXAMPLES
	 *
	 *     wp cordoba dealer import
	 *     wp cordoba dealer import --dry-run
	 *
	 */
	public function import( $args, $assoc_args ) {
		$dealer_json_file = WP_PLUGIN_DIR . '/core/assets/data/dealers.json';


		if ( ! file_exists( $dealer_json_file ) ) {
			\WP_CLI::error( sprintf( __( '%s must exist', 'tribe' ), $dealer_json_file ) );
		}

		$dealers = json_decode( file_get_contents( $dealer_json_file ) );
		$field_meta_map = [
			'_bh_sl_address'        => 'address',
			'_bh_sl_address_two'    => 'address_2',
			'_bh_sl_city'           => 'city',
			'_bh_sl_state'          => 'state',
			'_bh_sl_postal'         => 'zip',
			'_bh_sl_country'        => 'country',
			'_bh_sl_phone'          => 'phone',
			'_bh_sl_web'            => 'website_cordoba',
			'_bh_sl_web_guild'      => 'website_guild_electrics',
			'_bh_sl_dealer_number'  => 'dealer_number'
		];

		$iterator = 0;
		foreach ( $dealers as $dealer ) {

			if( self::GUILD_IDENTIFIER === $dealer->brand ) {
				\WP_CLI::warning( sprintf( __( '%s is a Guild only brand. Skipping...', 'tribe' ), esc_attr__( $dealer->dealer_name ) ) );
				continue;
			}

			$post = Dealers::post_exists( $dealer->dealer_name, $dealer->phone, $dealer->address, $dealer->zip );
			if ( false !== $post ) {
				\WP_CLI::warning( sprintf( __( '%s already exists. Moving on...', 'tribe' ), esc_attr( $dealer->dealer_name ) ) );
				continue;
			}

			$iterator ++;

			if ( empty( $assoc_args['dry-run'] ) ) {

				// Create the Location
				$post_id = wp_insert_post( [
					'post_type'   => Locations\Locations::NAME,
					'post_title'  => $dealer->dealer_name,
					'post_status' => 'publish',
				] );

				$whitelist = [
					'address',
					'address_2',
					'city',
					'state',
					'zip'
				];

				if ( ! is_wp_error( $post_id ) ) {

					foreach ( $field_meta_map as $meta_key => $meta_value ) {
						update_post_meta( $post_id, $meta_key, $dealer->$meta_value );
					}

					$address_parts = [];
					foreach( $whitelist as $val ) {
						if( in_array( $val, $whitelist ) && ! empty( $dealer->$val ) ) {
							$address_parts[] = $dealer->$val;
						}
					}

					if( ! empty( $address_parts ) ) {
						$address = implode( ' ', $address_parts );

						// Geocode
						if( ! empty( $address ) ) {
							if( empty( $this->bh_storelocator_latlng_meta( $post_id, $address ) ) ) {
								wp_update_post( [ 'ID' => $post_id, 'post_status' => 'draft' ] );
							}
						}
					}
				}

				// Assign Location Term
				wp_set_object_terms( $post_id, [ $dealer->classification ], \BH_Store_Locator::BH_SL_TAX );

				// Assign Brand Term
				wp_set_object_terms( $post_id, $dealer->brand, Brand::NAME );

				// Set Featured Image Logo
				if( ! empty( $dealer->featured_image ) ) {
					$this->sideload_logo( $post_id, $dealer->featured_image );
				}


				\WP_CLI::success( sprintf( __( "%s has been imported with ID %d.", 'tribe' ), esc_attr( $dealer->dealer_name ), (int) $post_id ) );
			} else {
				\WP_CLI::success( sprintf( __( "Dry Run Implemented. %s would be imported.", 'tribe' ), esc_attr( $dealer->dealer_name ) ) );
			}
		}

		if ( ! empty( $assoc_args['dry-run'] ) ) {
			\WP_CLI::success( sprintf( __( "%d dealers would be imported", 'tribe' ), (int) $iterator ) );
		}
	}

	/**
	 * Adds Just Dealer Number and meta for guild websites
	 *
	 * ## OPTIONS
	 *
	 * ## EXAMPLES
	 *
	 *     wp cordoba dealer update_guild_data
	 *
	 */
	public function update_guild_data( $args, $assoc_args ) {
		$dealer_json_file = WP_PLUGIN_DIR . '/core/assets/data/dealers.json';

		if ( ! file_exists( $dealer_json_file ) ) {
			\WP_CLI::error( sprintf( __( '%s must exist', 'tribe' ), $dealer_json_file ) );
		}

		$dealers = json_decode( file_get_contents( $dealer_json_file ) );
		foreach( $dealers as $dealer ) {
			$existing_posts = $this->post_exists( $dealer->dealer_name, $dealer->phone, $dealer->address, $dealer->zip );
			if( empty( $existing_posts ) ) {
				\WP_CLI::warning( sprintf( __( 'No existing posts. Moving on...', 'tribe' ), esc_attr__( $dealer->dealer_name ) ) );
				continue;
			}

			$existing_post = array_shift( $existing_posts );
			if( ! is_a( $existing_post, '\WP_Post' ) ) {
				\WP_CLI::warning( sprintf( __( 'Existing Dealer not of type WP_Post. Nothing to do. Moving on...', 'tribe' ), esc_attr__( $dealer->dealer_name ) ) );
				continue;
			}

			$existing_post_id = $existing_post->ID;
			if( empty( $existing_post_id ) ) {
				\WP_CLI::warning( sprintf( __( 'Existing Dealer not found. Nothing to do. Moving on...', 'tribe' ), esc_attr__( $dealer->dealer_name ) ) );
				continue;
			}

			$meta_keys = [
				'_bh_sl_web'            => 'website_cordoba',
				'_bh_sl_web_guild'      => 'website_guild_electrics',
				'_bh_sl_dealer_number'  => 'dealer_number'
			];
			foreach( $meta_keys as $key => $json_key ) {
				update_post_meta( $existing_post_id, $key, $dealer->{$json_key} );
			}
			\WP_CLI::success( sprintf( __( "%s has been updated.", 'tribe' ), esc_attr( $dealer->dealer_name ) ) );
		}
	}

	/**
	 * Sideloads logo and sets as featured image
	 *
	 * @param int $post_id
	 * @param string $url
	 *
	 * @return int|mixed|object
	 */
	public function sideload_logo( int $post_id, string $url ) {
		$tmp = download_url( $url );
		$file_array = [
			'name'      => basename( $url ),
			'tmp_name'  => $tmp
		];

		if( is_wp_error( $tmp ) ) {
			unlink( $file_array['tmp_name'] );
			return $tmp;
		}

		$attachment_id = media_handle_sideload( $file_array, $post_id );

		if( is_wp_error($attachment_id ) ) {
			unlink( $file_array['tmp_name'] );
			return $attachment_id;
		}

		set_post_thumbnail( $post_id, $attachment_id );
	}

	/**
	 * Utility function to find if location exists so we don't duplicate. Checks name and phone number combo
	 *
	 * @param $title
	 * @param $phone
	 *
	 * @return array|bool
	 */
	private function post_exists( $title, $phone, $address, $zip ) {

		$args = [
			'post_type'   => Locations\Locations::NAME,
			'post_title'  => $title,
			'post_status' => [ 'publish', 'private', 'draft', 'future' ],
			'meta_query'  => [
				'relation'=> 'AND',
				[
					'key'     => '_bh_sl_phone',
					'value'   => $phone,
					'compare' => '='
				],
				[
					'key'     => '_bh_sl_address',
					'value'   => $address,
					'compare' => '='
				],
				[
					'key'     => '_bh_sl_postal',
					'value'   => $zip,
					'compare' => '='
				]
			]
		];

		$exists = get_posts( $args );

		return empty( $exists ) ? false : $exists;
	}

	/**
	 * Stolen from Cardinal Locator because it's nearly impossible to get it loaded otherwise
	 *
	 * Add lat/lng post meta
	 *
	 * @param integer $post_id Post ID.
	 * @param string  $address Address.
	 * @param string  $region Country code.
	 *
	 * @return boolean
	 */
	private function bh_storelocator_latlng_meta( $post_id, $address, $region = '' ) {
		// Geocode the address.
		if ( isset( $address ) && ! empty( $address ) ) {
			$location_data = $this->bh_storelocator_geocode_address( $address, $region );
			// Check for no results.
			if ( 'ZERO_RESULTS' === $location_data['status'] || 'ERROR' === $location_data['status'] ) {
				return false;
			}

			// Get the latitude and longitude from the JSON data.
			if ( isset( $location_data ) ) {
				if ( isset( $location_data['results'][0]['geometry']['location']['lat'] ) ) {
					$new_lat = $location_data['results'][0]['geometry']['location']['lat'];
				}
				if ( isset( $location_data['results'][0]['geometry']['location']['lng'] ) ) {
					$new_lng = $location_data['results'][0]['geometry']['location']['lng'];
				}
				if ( isset( $location_data['results'][0]['place_id'] ) ) {
					$place_id = $location_data['results'][0]['place_id'];
				}

				// Add or update post meta with the coordinates.
				if ( isset( $new_lat ) ) {
					update_post_meta( $post_id, 'bh_storelocator_location_lat', $new_lat );
				}
				if ( isset( $new_lng ) ) {
					update_post_meta( $post_id, 'bh_storelocator_location_lng', $new_lng );
				}
				if ( isset( $place_id ) ) {
					update_post_meta( $post_id, 'bh_storelocator_location_placeid', $place_id );
				}

				// Also add coordinates to a custom table.
				if ( isset( $new_lat ) && isset( $new_lng ) ) {
					$this->bh_storelocator_latlng_db( $post_id, $new_lat, $new_lng );
				}

				return true;
			}
		}
	}

	/**
	 * Stolen from Cardinal Locator
	 *
	 * Google geocode request
	 *
	 * @param string $address Address.
	 * @param string $region Two letter country code.
	 *
	 * @return array|mixed|null
	 */
	private function bh_storelocator_geocode_address( $address, $region = '' ) {
		$apiurl = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode( $address ) . $region;
		if( defined( 'GOOGLE_ENCODE_API_KEY' ) && GOOGLE_ENCODE_API_KEY ) {
			$apiurl = add_query_arg( 'key', urlencode( GOOGLE_ENCODE_API_KEY ), $apiurl );
		}

		$geocode_data = wp_safe_remote_get( esc_url_raw( $apiurl ) );
		if ( is_wp_error( $geocode_data ) ) {
			$location_data['status'] = 'ERROR';
			$location_data['error'] = $geocode_data->get_error_message();
		} else {
			$location_data = json_decode( $geocode_data['body'], true );
		}

		return $location_data;
	}

	/**
	 * Stolen from Cardinal Locator
	 *
	 * Add lat/lng to the custom table
	 *
	 * @param integer $post_id Post ID.
	 * @param float   $lat     Latitude.
	 * @param float   $lng     Longitude.
	 */
	private function bh_storelocator_latlng_db( $post_id, $lat, $lng ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'cardinal_locator_coords';

		// Insert into custom table.
		$wpdb->query( $wpdb->prepare("INSERT IGNORE
					INTO $table_name
					( id, lat, lng )
					VALUES ( %d, %f, %f )
					ON DUPLICATE KEY UPDATE lat = %f, lng = %f",
			$post_id,
			$lat,
			$lng,
			$lat,
			$lng
		));
	}

	/**
	 * Command updates dealer location terms
	 *
	 * ## EXAMPLES
	 *
	 *     wp cordoba dealer update-location
	 *
	 * @param $args
	 * @param $assoc_args
	 *
	 * @subcommand update-location
	 */
	public function update_location_category_terms( $args, $assoc_args ) {
		$dealer_json_file = WP_PLUGIN_DIR . '/core/assets/data/dealers.json';

		if ( ! file_exists( $dealer_json_file ) ) {
			\WP_CLI::error( sprintf( __( '%s must exist', 'tribe' ), $dealer_json_file ) );
		}

		$dealers = json_decode( file_get_contents( $dealer_json_file ) );

		foreach ( $dealers as $dealer ) {

			$posts = Dealers::post_exists( $dealer->dealer_name, $dealer->phone, $dealer->address, $dealer->zip );
			if( empty( $posts ) ) {
				continue;
			}

			// Assign Location Term
			wp_set_object_terms( $posts->ID, [ $dealer->classification ], self::LOCATION_CATEGORY_TAXONOMY_TYPE );
		}
	}
}