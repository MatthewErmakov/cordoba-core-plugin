<?php
namespace Tribe\Project\Dealers;

use Tribe\Project\Post_Types\Locations\Locations;

class Dealers {
	const NONCE = 'cordoba-dealers-nonce';
	const TIMESTAMP = 'location-database-timestamp';

	const BH_PRIMARY_OPTS = 'bh_storelocator_primary_options';

	public function get_all_locations() {
		$ids = [];
		$args = [
			'post_type'     => Locations::NAME,
			'posts_per_page'=> 100,
			'fields'        => 'ids',
			'post_status'   => 'publish',
			'meta_query'    => [
				'relation'  => 'AND',
				[
					'key'       => Locations::LAT,
					'compare'   => 'EXISTS'
				],
				[
					'key'       => Locations::LNG,
					'compare'   => 'EXISTS'
				]
			]
		];

		$current_page = 1;
		do {
			$args[ 'paged' ] = $current_page;
			$locations = new \WP_Query();
			$locations->query( $args );
			if( ! empty( $locations->posts ) ) {
				$ids = wp_parse_args( $locations->posts, $ids );
			}
			$current_page++;

		} while( $locations->found_posts && $locations->max_num_pages > 1 );

		$dataset = [];
		foreach( $ids as $location_id ) {

			// If there isn't any latitude, why even bother?
			$lat = get_post_meta( $location_id, Locations::LAT, true );
			if( empty( $lat ) ) {
				continue;
			}

			// If there isn't any longitude, be sad, and move on. Acceptance is the first step
			$lng = get_post_meta( $location_id, Locations::LNG, true );
			if( empty( $lng ) ) {
				continue;
			}
			remove_filter( 'the_title', 'wptexturize'   );
			remove_filter( 'the_title', 'convert_chars' );
			$title = get_the_title( $location_id );
			add_filter( 'the_title', 'convert_chars' );
			add_filter( 'the_title', 'wptexturize'   );


			$loc = [
				'name'          => $title,
				Locations::LAT  => $lat,
				Locations::LNG  => $lng,
			];

			$keys = [
				Locations::ADDRESS,
				Locations::ADDRESS2,
				Locations::CITY,
				Locations::STATE,
				Locations::ZIP,
				Locations::COUNTRY,
				Locations::WEB,
				Locations::PHONE,
			];

			foreach( $keys as $key ) {
				$meta = get_post_meta( $location_id, $key, true );
				$loc[ $key ] = $meta;
			}

			$dataset[] = $loc;
		}

		if( empty( $dataset ) ) {
			return false;
		}

		return $dataset;
	}

	public function create_json_file() {

		$path = WP_CONTENT_DIR . '/plugins/core/assets/data/locations.json';

		$dataset = $this->get_all_locations();

		if( false === $dataset ) {
			return false;
		}

		$data = [];
		$iterator = 1;
		foreach( $dataset as $location ) {

			$dealer = new \stdClass();
			foreach( $location as $key => $location_item ) {
				if( empty( $location_item ) ) {
					$location[ $key ] = '';
				}
			}

			$post = self::post_exists( $location['name'], $location[ Locations::PHONE ], $location[ Locations::ADDRESS ], $location[ Locations::ZIP ] );
			if( empty( $post ) ) {
				continue;
			}

			$dealer_terms = wp_get_object_terms( $post->ID, \BH_Store_Locator::BH_SL_TAX );
			$term = array_shift( $dealer_terms );
			if( ! is_a( $term, '\WP_Term' ) ) {
				continue;
			}

			$dealer->id = (string) $iterator;
			$dealer->name = $location['name'];
			$dealer->lat = $location[ Locations::LAT ];
			$dealer->lng = $location[ Locations::LNG ];
			$dealer->address = $location[ Locations::ADDRESS ];
			$dealer->address2 = $location[ Locations::ADDRESS2 ];
			$dealer->city = $location[ Locations::CITY ];
			$dealer->state = $location[ Locations::STATE ];
			$dealer->postal = $location[ Locations::ZIP ];
			$dealer->phone = $location[ Locations::PHONE ];
			$dealer->web = $location[ Locations::WEB ];
			$dealer->hours1 = '';
			$dealer->hours2 = '';
			$dealer->hours3 = '';
			$dealer->{\BH_Store_Locator::BH_SL_TAX} = $term->name;
			$dealer->{\BH_Store_Locator::BH_SL_TAX . '_terms'} = [ $term ];

			$data[] = $dealer;

			$iterator++;
		}


		// We need to version the JSON file so changes don't get browser cached
		$options = get_option( self::BH_PRIMARY_OPTS );
		if( ! empty( $options['datapath'] ) ) {
			$url = $options[ 'datapath' ];
			$urlpath = parse_url( $url, PHP_URL_PATH );
			$url = add_query_arg( 'v', (int) time(), site_url( $urlpath ) );
			$options[ 'datapath' ] = esc_url( $url );

			update_option( self::BH_PRIMARY_OPTS, $options );
		}

		file_put_contents( $path, wp_json_encode( $data ) );
	}

	public function generate_dealers_adminbar( \WP_Admin_Bar $wp_admin_bar ) {
		$nonce = wp_create_nonce( self::NONCE );
		$wp_admin_bar->add_menu( [
			'id'        => 'cordoba-dealers',
			'parent'    => null,
			'group'     => null,
			'title'     => esc_html__( 'Regenerate Location Data', 'tribe' ),
			'href'      => esc_url( add_query_arg( 'dealers-nonce', $nonce, admin_url() ) )
		] );
	}

	/**
	 * Utility function to find if location exists so we don't duplicate. Checks name and phone number combo
	 *
	 * @param $title
	 * @param $phone
	 *
	 * @return array|bool
	 */
	public static function post_exists( $title, $phone, $address, $zip ) {

		$args = [
			'post_type'   => \BH_Store_Locator::BH_SL_CPT,
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

		return empty( $exists ) ? false : array_shift( $exists );
	}


	/**
	 * Indicate the JSON array key path that is the locations array to match the feed from Google.
	 *
	 * @param array $data JSON array of remote feed.
	 *
	 * @filter  bh_sl_remote_locations_array
	 *
	 * @return array
	 */
	public function cordoba_locations_array( array $data ) : array {
		return $data['feed']['entry'];
	}

	/**
	 * Location data structure setup.
	 *
	 * @param array $structure Default array structure to match the feed from Google.
	 *
	 * @filter bh_sl_remote_locations_structure
	 *
	 * @return array
	 */
	public function cordoba_data_structure( array $structure ) : array {
		$structure['name']                   = 'gsx$dealer_2:$t';
		$structure['address']                = 'gsx$address1:$t';
		$structure['address2']               = 'gsx$address2:$t';
		$structure['city']                   = 'gsx$city:$t';
		$structure['state']                  = 'gsx$stateorprovince:$t';
		$structure['postal']                 = 'gsx$ziporpostalcode:$t';
		$structure['country']                = 'gsx$countrycctld:$t';
		$structure['phone']                  = 'gsx$phonenumber:$t';
		$structure['classification']         = 'gsx$classification:$t';
		$structure['brand']                  = 'gsx$brand:$t';
		$structure['website-cordoba']        = 'gsx$website-cordoba:$t';
		$structure['website-guild']          = 'gsx$website-guild:$t';
		$structure['website-guildelectrics'] = 'gsx$website-guildelectrics:$t';
		$structure['lat']                    = 'gsx$latitude:$t';
		$structure['lng']                    = 'gsx$longitude:$t';
		return $structure;
	}
}