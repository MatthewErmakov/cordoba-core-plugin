<?php
namespace Tribe\Project\Settings;

use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Group;
use Tribe\Project\Settings\Contracts\Term_Meta_ACF_Settings;
use Tribe\Project\Taxonomies\Brand\Brand;
use Tribe\Project\Taxonomies\Construction\Construction;
use Tribe\Project\Taxonomies\Country\Country;
use Tribe\Project\Taxonomies\Electronics\Electronics;
use Tribe\Project\Taxonomies\Family\Family;
use Tribe\Project\Taxonomies\Label\Label;
use Tribe\Project\Taxonomies\Series\Series;
use Tribe\Project\Taxonomies\Style\Style;

class Cordoba_API_Term_Meta extends Term_Meta_ACF_Settings {

	const NAME = 'cordoba-api-term-description';
	const FIELD_NAME = 'term_definition';
	const FIELD_LABEL = 'Term: ';

	public function get_title() {
		return __( 'Cordoba API Term Descriptions', 'tribe' );
	}

	public function get_capability() {
		return 'manage_options';
	}

	public function get_parent_slug() {
		return 'options-general.php';
	}
	/**
	 * Adds the settings group
	 */
	public function register_fields() {
		acf_add_local_field_group( $this->get_settings_group() );
	}

	private function get_settings_group() {
		$key   = self::NAME;
		$group = new Group( $key );
		$group->set_attributes( [
			'title'      => __( 'Term Definitions', 'tribe' ),
			'location'   => [
				[
					[
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => $this->slug,
					],
				],
			],
		] );

		$taxonomies = [
			Construction::NAME,
			Country::NAME,
			Electronics::NAME,
			Family::NAME,
			Label::NAME,
			Style::NAME,
			Series::NAME
		];

		foreach( $taxonomies as $taxonomy ) {
			$terms = get_terms( [
				'hide_empty'    => false,
				'taxonomy'      => $taxonomy,
				'number'        => 0
			] );

			if( ! empty( $terms ) ) {
				foreach( $terms as $term ) {
					$group->add_field( $this->get_term_field( $term ) );
				}
			}
		}

		return $group->get_attributes();
	}

	private function get_term_field( \WP_Term $term ) {
		$field = new Field( self::NAME . '_' . $term->name );
		$field->set_attributes( [
			'label' => ucfirst( $term->taxonomy ) . ' ' . self::FIELD_LABEL . $term->name,
			'name' => self::FIELD_NAME . $term->taxonomy . $term->name,
			'type' => 'text',
			'instructions' => esc_html__( 'These descriptions will be used for tooltips. Descriptions should be no longer than 150 characters long.', 'tribe' ),
			'conditional_logic' => 0,
			'maxlength' => 150,
		] );

		return $field;
	}
}