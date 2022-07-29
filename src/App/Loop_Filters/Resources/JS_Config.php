<?php

namespace Tribe\Project\App\Loop_Filters\Resources;

use Tribe\Project\App\Archive;
use Tribe\Project\Post_Meta\Instrument_Meta;
use Tribe\Project\Post_Types\Exclusive\Exclusive;
use Tribe\Project\Rest_Api\Initial_Data;
use Tribe\Project\Post_Types\Guitar\Guitar;
use Tribe\Project\Post_Types\Ukulele\Ukulele;
use Tribe\Project\Settings\Cordoba_API_Term_Meta;
use Tribe\Project\Settings\General;
use Tribe\Project\Taxonomies\Has_Electronics\Has_Electronics;
use Tribe\Project\Taxonomies\Label\Label;
use Tribe\Project\Taxonomies\Family\Family;
use Tribe\Project\Taxonomies\Series\Series;
use Tribe\Project\Taxonomies\Sizes\Sizes;
use Tribe\Project\Taxonomies\Style\Style;
use Tribe\Project\Taxonomies\Construction\Construction;
use Tribe\Project\Taxonomies\Electronics\Electronics;
use Tribe\Project\Theme\Single_Instruments;

class JS_Config {

	const TERM_ORDER_OPTION = 'tribe-term-order';

	private $data;

	public function get_data() {
		if ( ! isset( $this->data ) ) {
			global $wp_query;
			$this->data = [
				'api_endpoint_root'     => trailingslashit( esc_url( home_url( rest_get_url_prefix() ) ) ) . 'wp/v2/',
				'api_route_type'        => $this->get_route_type(),
				'api_nonce'             => wp_create_nonce( 'wp_json' ),
				'filters'               => $this->get_filters_config(),
				'images_url'            => plugins_url( 'assets/theme/img/', dirname( __DIR__ ) ),
				'page_total'            => (integer)$wp_query->max_num_pages,
				'posts'                 => Initial_Data::instance()->get_post_data(),
				'post_per_page'         => Archive::POSTS_PER_PAGE,
				'results_total'         => (integer)$wp_query->found_posts,
				'routes'                => [
					'basename' => $this->get_routes_base(),
				],
				'tax_label_name'        => Label::NAME,
				'tax_family_name'       => Family::NAME,
				'tax_style_name'        => Style::NAME,
				'tax_construction_name' => Construction::NAME,
				'tax_electronics_name'  => Has_Electronics::NAME,
				'tax_size'              => Sizes::NAME,
				'tax_series'            => Series::NAME
			];
			$this->data = apply_filters( 'app_loop_filters_js_config', $this->data );
		}

		return $this->data;
	}

	private function get_route_type() {
		if ( is_post_type_archive( Guitar::NAME ) ) {
			$route = Guitar::NAME;
		} elseif ( is_post_type_archive( Ukulele::NAME ) ) {
			$route = Ukulele::NAME;
		} elseif ( is_post_type_archive( Exclusive::NAME ) ) {
            $route = Exclusive::NAME;
        } else {
			$route = '';
		}

		return $route;
	}

	private function get_routes_base() {
		$post_type = get_queried_object();

		if ( is_post_type_archive( [ Guitar::NAME, Ukulele::NAME, Exclusive::NAME ] ) ) {
			$route = ! empty( $post_type->rewrite['slug'] ) ? $post_type->rewrite['slug'] : $this->get_route_type();
		} else {
			$route = '';
		}

		return '/' . $route;
	}

	private function get_filters_config() {
		// Filters shared by both

		// Add Guitar only filters
		if( ! is_post_type_archive( Ukulele::NAME ) ) {
			$config = [
				Label::NAME             => $this->get_terms( 'main-guitar' ),
				Style::NAME             => $this->get_terms( Style::NAME ),
				Has_Electronics::NAME   => $this->get_terms( Has_Electronics::NAME ),
				Construction::NAME      => $this->get_terms( Construction::NAME ),
				Series::NAME            => $this->get_terms( Series::NAME ),
			];
		} else {
			$config = [
				Label::NAME             => $this->get_terms( 'main-uke' ),
				Sizes::NAME             => $this->get_terms( Sizes::NAME ),
				Has_Electronics::NAME   => $this->get_terms( Has_Electronics::NAME ),
				Construction::NAME      => $this->get_terms( Construction::NAME ),
			];
		}

		return $config;
	}

	/**
	 * Client wants a very specific grouping and sorting of filters
	 * that require manaul definition
	 * @param $taxonomy
	 *
	 * @return array
	 */
	private function get_terms( $taxonomy ) {

		$label_tax = get_taxonomy( $taxonomy );
		$label = $label_tax ? $label_tax->label : '';

		switch ( $taxonomy ) {
			case 'main-guitar':
				$data = [
					$this->get_term( 'New', Label::NAME ),
					[ 'id' => - 1, 'label' => 'Most Popular' ],
					$this->get_term( 'Good for Kids', Label::NAME ),
					$this->get_term( 'Acoustic Packs', Label::NAME ),
					$this->get_term( 'Cutaway & Electric', Label::NAME ),
					$this->get_term( 'Traditional', Label::NAME ),
				];
				break;

			case 'main-uke':
				$data = [
					$this->get_term( 'New', Label::NAME ),
					[ 'id' => - 1, 'label' => 'Most Popular' ],
					$this->get_term( 'Limited Edition', Label::NAME ),
					$this->get_term( 'Ukulele Packs', Label::NAME ),
				];
				break;
			case Style::NAME:
				$data = [
					$this->get_term( 'Classical', Style::NAME ),
					$this->get_term( 'Flamenco', Style::NAME ),
					$this->get_term( 'Fusion', Style::NAME ),
					$this->get_term( 'Thinbody', Style::NAME ),
					$this->get_term( 'Small body', Style::NAME ),
					$this->get_term( 'Lefty', Style::NAME ),
				];
				break;

			case Sizes::NAME:
				$data = [
					$this->get_term( 'Soprano', Sizes::NAME ),
					$this->get_term( 'Concert', Sizes::NAME ),
					$this->get_term( 'Tenor', Sizes::NAME ),
					$this->get_term( 'Tenor Cutaway', Sizes::NAME ),
					$this->get_term( 'Baritone', Sizes::NAME ),
				];
				break;

			case Has_Electronics::NAME:
				$data = [
					$this->get_term( 'With', Has_Electronics::NAME ),
					$this->get_term( 'Without', Has_Electronics::NAME ),
				];
				break;

			case Construction::NAME:
				$data = [
					$this->get_term( 'Layered', Construction::NAME ),
					$this->get_term( 'Solid Top', Construction::NAME ),
					$this->get_term( 'All Solid Woods', Construction::NAME ),
				];
				break;

			case Series::NAME:
				$data = [
					$this->get_term( 'Protege by Córdoba', Series::NAME ),
					$this->get_term( 'Iberia', Series::NAME ),
					$this->get_term( 'Luthier', Series::NAME ),
					$this->get_term( 'Fusion', Series::NAME ),
					$this->get_term( 'Espana', Series::NAME ),
					$this->get_term( 'Master', Series::NAME ),
					$this->get_term( 'Mini', Series::NAME ),
					$this->get_term( 'Coco', Series::NAME ),
                    $this->get_term( 'Luthier Select', Series::NAME ),
				];
				break;

			default:
				$data = [];
		}


		return [
			'label'   => $label,
			'options' => array_values( array_filter( $data ) ),
		];
	}

	public function get_term( $name, $taxonomy ) {
		$term = get_term_by( 'name', $name, $taxonomy );

		if ( empty( $term ) ) {
			return [];
		}

		return [
			'id'          => $term->term_id,
			'label'       => $term->name,
			'description' => General::instance()->get_setting( Cordoba_API_Term_Meta::FIELD_NAME . $taxonomy . $term->name ),
		];
	}

	private function old_get_terms( $taxonomy ) {
		$label = get_taxonomy( $taxonomy )->label;

		$_args = [
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,
			'orderby'    => 'count',
			'order'      => 'ASC',
		];

		if ( Label::NAME === $taxonomy ) {
			$_args = wp_parse_args(
				[
					'include'  => $this->get_label_term_order(),
					'orderby'  => 'include',
					'taxonomy' => [
						Label::NAME,
						Family::NAME,
					],
				],
				$_args
			);
		}

		$terms = get_terms( $_args );

		$data = [];
		foreach ( $terms as $_term ) {
			$term = [
				'id'          => $_term->term_id,
				'label'       => $_term->name,
				'description' => General::instance()->get_setting( Cordoba_API_Term_Meta::FIELD_NAME . $taxonomy . $_term->name ),
			];

			if ( get_post_type() === Ukulele::NAME && Label::NAME === $taxonomy ) {
				$include = [
					Ukulele::LABEL_LOOP_INCLUDE_LIMITED,
					Ukulele::LABEL_LOOP_INCLUDE_NEW,
					Ukulele::LABEL_LOOP_INCLUDE_POPULAR,
					Ukulele::LABEL_LOOP_INCLUDE_UKE_PACKS,
				];
				if ( in_array( $_term->name, $include ) ) {
					$data[] = $term;
				}
			} else if ( get_post_type() === Guitar::NAME && Label::NAME === $taxonomy ) {
				if ( $_term->name === Ukulele::LABEL_LOOP_INCLUDE_UKE_PACKS ) {
					continue;
				}
				if ( $_term->name === Single_Instruments::LABEL_LEFT_HANDED ) {
					continue;
				}
				$data[] = $term;
			} else {
				$data[] = $term;
			}
		}

		if ( $taxonomy === Label::NAME ) {
			$new_data = [];
			foreach ( $data as $item ) {

				$new_data[] = $item;

				// Client wants the pseudo "Most Popular" label to come right after "New"
				if ( $item['label'] === ucwords( Label::NEW ) ) {
					$new_data[] = [
						'id'          => - 1,
						'label'       => 'Most Popular',
						'description' => '',
					];
				}
			}

			$data = $new_data;
		}

		return [
			'label'   => $label,
			'options' => (array) $data,
		];
	}

	public function get_label_term_order(): array {
		$cpt = get_post_type();
		if( empty( $cpt ) ) {
			return false;
		}

		$option_name = self::TERM_ORDER_OPTION . $cpt;

		$term_ids = General::instance()->get_setting( $option_name );
		if( empty( $term_ids ) ) {
			$term_ids = [];

			if( Guitar::NAME === $cpt ) {
				$terms = [
					Label::NEW  => Label::NAME,
					Label::GOOD_FOR_KIDS => Label::NAME,
					Label::ACOUSTIC_PACKS => Label::NAME,
					Family::CUTAWAY_ELECTRIC => Label::NAME,
					Family::TRADITIONAL => Label::NAME
				];
			} else {
				$terms = [
					Label::NEW => Label::NAME,
					Label::LIMITED_EDITION => Label::NAME,
					Label::UKULELE_PACKS => Label::NAME
				];
			}

			foreach( $terms as $term => $taxonomy ) {
				$term_object = get_term_by( 'slug', $term, $taxonomy );
				if( ! empty( $term_object ) ) {
					$term_ids[] = $term_object->term_id;
				}
			}

			update_option( $option_name, $term_ids );
		}

		return $term_ids;
	}
}
