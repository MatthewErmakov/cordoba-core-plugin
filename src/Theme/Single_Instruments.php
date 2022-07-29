<?php
namespace Tribe\Project\Theme;

use Tribe\Libs\Post_Type\Post_Object;
use Tribe\Project\Cordoba_Api\Metaboxes;
use Tribe\Project\Post_Meta\Instrument_Meta;
use Tribe\Project\Post_Types\Guitar\Guitar;
use Tribe\Project\Post_Types\Ukulele\Ukulele;
use Tribe\Project\Taxonomies\Family\Family;
use Tribe\Project\Taxonomies\Has_Electronics\Has_Electronics;
use Tribe\Project\Taxonomies\Style\Style;
use Tribe\Project\Taxonomies\Construction\Construction;
use Tribe\Project\Taxonomies\Electronics\Electronics;
use Tribe\Project\Taxonomies\Country\Country;

class Single_Instruments {

	const LABEL_LEFT_HANDED = 'Left Handed';

	/**
	 * Returns unformatted MSRP
	 *
	 * @param $post_id
	 * @param string $type Can be euro, cad. Any other value will return USD
	 *
	 * @return mixed
	 */
	static public function get_msrp_unformatted( int $post_id, string $type = 'usd' ) {

		$msrp = Post_Object::factory( $post_id )->get_meta( Instrument_Meta::MSRP );
		if( empty( $msrp ) ) {
			return false;
		}
		switch( strtolower( $type ) ) {
			case 'euro':
				return $msrp->mapeurmsrp;
				break;
			case 'cad':
				return $msrp->mapcadmsrp;
				break;
			case 'usd':
				return $msrp->mapusdmsrp;
			default:
				break;
		}
	}

	/**
	 * Returns MSRP
	 *
	 * @param $post_id
	 * @param string $type Can be euro, cad. Any other value will return USD
	 *
	 * @return mixed
	 */
	static public function get_msrp( int $post_id, string $type = 'usd' ) {
		$msrp = Post_Object::factory( $post_id )->get_meta( Instrument_Meta::MSRP );
		switch( strtolower( $type ) ) {
			case 'euro':
				if( ! empty( $msrp->mapeurmsrp ) ) {
					setlocale(LC_MONETARY, 'de_DE.utf8');
					return sprintf(
						'%s <abbr title="%s">%s</abbr>',
						money_format('%+n', $msrp->mapeurmsrp ),
						__( 'European Monetary Units', 'tribe' ),
						__( 'EUR', 'tribe' )
					);
				}
				break;
			case 'cad':
				if( ! empty( $msrp->mapcadmsrp ) ) {
					setlocale(LC_MONETARY, 'en_US.UTF-8');
					return sprintf(
						'%s <abbr title="%s">%s</abbr>',
						money_format( '%.2n', $msrp->mapusdmsrp ),
						$msrp->mapcadmsrp,
						__( 'Canadian Dollars', 'tribe' ),
						__( 'CAD', 'tribe' )
					);
				}
				break;
			case 'usd':
				if( ! empty( $msrp->mapusdmsrp ) ) {
					setlocale(LC_MONETARY, 'en_US.UTF-8');
					return sprintf(
						'%s  <abbr title="%s">%s</abbr>',
						money_format( '%.0n', $msrp->mapusdmsrp ),
						$msrp->mapusdmsrp,
						__( 'USD', 'tribe' )
					);
				}
				break;
			default:
				break;
		}
	}

	/**
	 * Returns Key Features
	 *
	 * @param $post_id
	 *
	 * @return mixed
	 */
	static public function get_key_features( int $post_id ) {
		$output   = '';

		$taxonomies = is_singular( Ukulele::NAME ) ?
			[
				Electronics::NAME,
			]
			:
			[
				Family::NAME,
				Style::NAME,
				Construction::NAME,
				Electronics::NAME,
				Country::NAME,
			];

		// Unset Electronics if instrument doesn't have any
		$has_electronics = wp_get_object_terms( $post_id, Has_Electronics::NAME );
		foreach( $has_electronics as $term ) {
			if( Has_Electronics::WITHOUT === $term->name ) {
				unset( $taxonomies[ array_search( Electronics::NAME, $taxonomies ) ] );
			}
		}

		foreach ( $taxonomies as $tax ) {
			$taxonomy = get_taxonomy( $tax );
			$terms = get_the_terms( $post_id, $tax );

			if( empty( $terms ) ) {
				continue;
			}

			$output .= '<li class="item-instrument__features-list-item">';

			if ( $tax !== Country::NAME ) {
				$output .= sprintf( '<strong class="item-instrument__features-list-label">%s:</strong>', $taxonomy->labels->singular_name );

				$output .= '<ul class="item-instrument__features-list-values u-sep-comma">';
			}

			foreach( $terms as $term ) {
				if ( ( Country::NAME === $tax ) && ( 'China' === $term->name ) ) {
					continue;
				}
				$output .= sprintf(
					'%s%s%s%s',
					$tax !== Country::NAME ? '<li class="item-instrument__features-list-value">' : '',
					$tax === Country::NAME ? __( 'Made in', 'tribe' ) . ' ' : '',
					$term->name,
					$tax !== Country::NAME ? '</li>' : ''
				);
			}

			if ( $tax !== Country::NAME ) {
				$output .= '</ul>';
			}

			$output .= '</li>';
		}

		return $output;
	}

	/**
	 * Returns Key Features Meta
	 * (For instrument single meta that are not taxonomy driven)
	 *
	 * @param $post_id
	 *
	 * @return mixed
	 */
	static public function get_key_single_features( int $post_id ) {
		$output = '';

		$meta = [
			'size' => [
				'label' => __( 'Size', 'tribe' ),
				'item'  => static::get_instrument_size( $post_id ),
			],
			'top_wood' => [
				'label' => __( 'Top Wood', 'tribe' ),
				'item'  => static::get_instrument_top_wood( $post_id ),
			],
			'back_wood' => [
				'label' => __( 'Back & Sides Wood', 'tribe' ),
				'item'  => static::get_instrument_side_wood( $post_id ),
			],
		];

		foreach ( $meta as $_meta ) {
			if( empty( $_meta['item'] ) || ( is_singular( Guitar::NAME ) && ( 'size' === $_meta ) ) ) {
				continue;
			}

			$output .= '<li class="item-instrument__features-list-item">';
			$output .= sprintf( '<strong class="item-instrument__features-list-label">%s:</strong>', $_meta['label'] );
			$output .= '<ul class="item-instrument__features-list-values u-sep-comma">';
			$output .= sprintf(
				'<li class="item-instrument__features-list-value">%s</li>',
				$_meta['item']
			);
			$output .= '</ul>';
			$output .= '</li>';
		}

		return $output;
	}

	/**
	 * Returns options for instrument (P2P)
	 *
	 * @param int $post_id
	 *
	 * @return array
	 */
	static public function get_options( int $post_id ) {
		$p2p = is_singular( Guitar::NAME ) ? Guitar::NAME . '_to_' . Guitar::NAME : Ukulele::NAME . '_to_' . Ukulele::NAME;
		$query = get_posts( [
			'connected_type'   => $p2p,
			'connected_items'  => $post_id,
			'nopaging'         => true,
			'suppress_filters' => false,
		] );
		return $query;
	}

	/**
	 * Returns a flattened array of full specs
	 *
	 * @param int $post_id
	 *
	 * @return array
	 */
	static public function get_full_specs( int $post_id ) {
		$full_specs = [];
		$specs = get_post_meta( $post_id, Metaboxes::META_KEY, true );
		foreach( $specs as $key => $value ){

			if( is_array( $value ) || is_object( $value ) ) {
				foreach( $value as $k => $v  ) {
					$full_specs[ $k ] = $v;
				}
			} else {
				$full_specs[ $key ] = $value;
			}
		}

		$full_specs = self::format_msrp( $full_specs );
		$full_specs = self::remove_blacklist_keys_from_full_specs( $full_specs );
		$full_specs = self::remove_blacklist_keys_from_full_specs_for_ukuleles( $full_specs );
		$full_specs = self::rename_keys_in_full_spec( $full_specs );
		$full_specs = self::remove_empty_specs( $full_specs );
		self::get_sorted_order($full_specs);
		return $full_specs;
	}
	//main sort function
	static public function get_sorted_order(&$arraytosort){
		uksort($arraytosort,"self::get_sorted_order_cmp");
	}

	//the custom compare function
	static public function get_sorted_order_cmp($a,$b){
		/**
		 * Index of sorted keys => values
		 *
		 */
		$order = array(
			'Item Number' => 1,
			'Family' => 2,
			'Build'  => 3,
			'Construction' => 4,
			'Body Size' => 5,
			'Body Type' => 6,
			'Body Shape' => 7,
			'Body Top' => 8,
			'Top Bracing Pattern' => 9,
			'Soundhole Diameter' => 10,
			'Rosette' => 11,
			'Soundhole / F Holes Binding' => 12,
			'Top Purfling Inlay' => 13,
			'Top Binding' => 14,
			'Body Sides' => 15,
			'Side Purfling Inlay' => 16,
			'Back and Sides Wood' => 17,
			'Body Back' => 18,
			'Body Wood (Solid Bodies)' => 19,
			'Back Purfling Inlay' => 20,
			'Purfling' => 21,
			'Body Binding' => 22,
			'Neck Material' => 23,
			'Scale Length' => 24,
			'Neck Shape' => 25,
			'Neck Profile' => 26,
			'Nut Width' => 27,
			'Neck Thickness 1st Fret' => 28,
			'Neck Thickness 9th Fret' => 29,
			'Truss Rod' => 30,
			'Truss Rod Wrench' => 31,
			'Fingerboard Material' => 32,
			'Fingerboard Radius' => 33,
			'Fingerboard Inlays' => 34,
			'Fingerboard Inlay Location' => 35,
			'Frets Total' => 36,
			'Fret Type' => 37,
			'Fingerboard Binding' => 38,
			'Color' => 39,
			'Finish' => 40,
			'Finish - Top' => 41,
			'Finish - Back and Sides' => 42,
			'Finish - Neck' => 43,
			'Bridge Material' => 44,
			'Bridge String Spacing' => 45,
			'Saddle Material' => 46,
			'Bridge Pins' => 47,
			'Bridge' => 48,
			'Tailpiece' => 49,
			'Nut Material' => 50,
			'Tuning Machines' => 51,
			'Endpin' => 52,
			'Strap Buttons' => 53,
			'Hardware Finish / Plating' => 54,
			'Tap Plate' => 55,
			'Pickguard' => 56,
			'Control Knobs' => 57,
			'Electronics' => 58,
			'Bridge Pickup' => 59,
			'Neck Pickup' => 60,
			'Pickup Switch' => 61,
			'Controls' => 62,
			'Strings' => 63,
			'Included Case' => 64,
			'Case Candy' => 65,
			'Upper Bout Width' => 66,
			'Lower Bout Width' => 67,
			'Body Depth Upper Bout' => 68,
			'Body Depth Lower Bout' => 69,
			'Body Length' => 70,
			'Overall Length' => 71,
			'Ukulele Packs' => 72,
			'Parent' => 73,
			'New' => 74,
			'Most Popular' => 75,
			'Good for Kids' => 76,
			'Acoustic Packs' => 77,
			'Limited Edition' => 78,
			'Left Handed' => 79,
			'Country of Origin' => 80,
		);
		return $order[$a] - $order[$b];
		
	}
	/**
	 * Remove items from Full specs that client does not wish to display
	 *
	 * @param array $specs
	 *
	 * @return array
	 */
	public static function remove_blacklist_keys_from_full_specs( array $specs ) {
		$blacklist = [
			'shortdesc',
			'longdesc',
			'pid',
			'porder',
			'psorder',
			'pcname',
			'pname',
			'pcorder',
			'preleasestatus',
			'mapcadmsrp',
			'mapeurmsrp',
			'mapurmsrp',
			'mapusdmsrp',
			'mapusdmap',
			'psname',
//			'Country of Origin',
//			'Build',
//			'Family',
//			'Construction',
			'Rosette Width',
			'Top Purfling - Inlay Pattern & Dimensions',
			'Side Purfling - Inlay Pattern & Dimensions',
			'Back Purfling - Inlay Pattern & Dimensions',
			'Bridge String Spacing',
			'Saddle Width',
			'Saddle Length',
			'Fingerboard Inlay Location',
			'Fingerboard Side Inlay Location',
			'Fingerboard Side Inlays',
			'Fingerboard Radius',
			'Nut Height',
			'Nut Thickness',
			'Case Candy',
			'Similar Products 1',
			'Similar Products 2',
			'Similar Products 3',
			'Parent',
			'Good for Kids',
			'New',
			'Left Handed',
			'Most Popular',
			'Acoustic Packs',
			'Ukulele Packs'
		];

		foreach( $specs as $key => $value ) {
			if( in_array(  $key, $blacklist ) ) {
				unset( $specs[ $key ] );
			}
		}

		return $specs;
	}

	/**
	 * Remove items from Full specs that client does not wish to display
	 * for Ukuleles specifically
	 *
	 * @param array $specs
	 *
	 * @return array
	 */
	public static function remove_blacklist_keys_from_full_specs_for_ukuleles( array $specs ) {
		if ( ! is_singular( Ukulele::NAME ) ) {
			return $specs;
		}

		$blacklist = [
			'Scale Length',
		];

		foreach( $specs as $key => $value ) {
			if( in_array(  $key, $blacklist ) ) {
				unset( $specs[ $key ] );
			}
		}

		return $specs;
	}

	/**
	 * @param array $specs
	 *
	 * @return array
	 */
	public static function rename_keys_in_full_spec( array $specs ) {
		$new_labels = [
			'psname'        => __( 'Series', 'tribe' ),
			'sageitemno'    => __( 'Item Number', 'tribe' ),
			'mapusdmsrp'    => __( 'MSRP', 'tribe' ),
		];

		$new_specs = [];
		foreach( $specs as $key => $value ) {
			if( array_key_exists( $key, $new_labels ) ) {
				$new_specs[ $new_labels[ $key ] ] = $specs[ $key ];
				unset( $specs[ $key ] );
			}
		}

		$specs = array_merge( $new_specs, $specs );

		return $specs;
	}

	/**
	 * @param array $specs
	 *
	 * @return array
	 */
	public static function format_msrp( array $specs ) {
		foreach( $specs as $key => $value ) {
			if( 'mapusdmsrp' === $key ) {

				if( empty( $value ) ) {
					continue;
				}

				setlocale(LC_MONETARY, 'en_US.UTF-8');
				
				$msrp = sprintf(
					'%s %s',
					money_format( '%.0n', $value ),
					'USD'
				);

				$specs[ $key ] = $msrp;
			}
		}

		return $specs;
	}

	/**
	 * @param array $specs
	 *
	 * @return array
	 */
	public static function remove_empty_specs( array $specs ) {
		foreach( $specs as $key => $value ) {
			if( empty( $value ) ) {
				unset( $specs[ $key ] );
			}
			if( '-' === $value ) {
				unset( $specs[ $key ] );
			}
		}

		return $specs;
	}

	/**
	 * @param int $post_id
	 *
	 * @return bool
	 */
	public static function get_instrument_size( int $post_id ) {
		$specs = get_post_meta( $post_id, Metaboxes::META_KEY, true );
		$size = ( ! empty( $specs->Body->{'Body Size'} ) ) ? $specs->Body->{'Body Size'} : false;

		return $size;
	}

	/**
	 * @param int $post_id
	 *
	 * @return bool
	 */
	public static function get_instrument_top_wood( int $post_id ) {
		$specs = get_post_meta( $post_id, Metaboxes::META_KEY, true );
		$wood = ( ! empty( $specs->Body->{'Body Top'} ) ) ? $specs->Body->{'Body Top'} : false;

		return $wood;
	}

	/**
	 * @param int $post_id
	 *
	 * @return bool
	 */
	public static function get_instrument_side_wood( int $post_id ) {
		$specs = get_post_meta( $post_id, Metaboxes::META_KEY, true );
		$wood = ( ! empty( $specs->Body->{'Back and Sides Wood'} ) ) ? $specs->Body->{'Back and Sides Wood'} : false;

		return $wood;
	}
}
