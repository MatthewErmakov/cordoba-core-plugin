<?php
namespace Tribe\Project\Post_Meta;

use Tribe\Libs\ACF\ACF_Meta_Group;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Group;
use Tribe\Libs\ACF\Repeater;

class Instrument_Meta extends ACF_Meta_Group {

	const SUBTEXT = 'subtext';
	const DESCRIPTION = 'description';
	const SHORT_DESCRIPTION = 'short_description';
	const HORIZONTAL_IMAGE = 'horizontal_image';
	const KEY_IMAGE = 'key_image';
	const OPTIONS_DESCRIPTION = 'options_description';
	const HIGHLIGHT_GALLERY = 'highlight_gallery';
	const HIDE_FROM_LOOP = 'hide_from_loop';
	const MOST_POPULAR = 'most-popular';
	const LABEL = 'label';
	const MSRP = 'msrp';
	const SWATCH_ID = 'swatch_id';
	const SWATCH_TITLE = 'swatch_title';
	const SWATCH_IMAGE = 'swatch_image';
	const SWATCH_LABEL = 'swatch_label';
	const DEALERS = 'dealers';
	const DEALERS_NAME = 'dealers_name';
	const DEALERS_URL = 'dealers_url';
	const DEALERS_IMAGE = 'dealers_image';
	const ELECTRONICS = 'cordoba_electronics';

    const PRIORITY = 'priority';

	const DESCRIPTION_MAX = 500;
	const SHORT_DESCRIPTION_MAX = 150;

	/**
	 * @return array
	 */
	public function get_keys() {
		return [
			self::SUBTEXT,
			self::DESCRIPTION,
			self::SHORT_DESCRIPTION,
			self::OPTIONS_DESCRIPTION,
			self::HORIZONTAL_IMAGE,
			self::KEY_IMAGE,
			self::HIGHLIGHT_GALLERY,
			self::HIDE_FROM_LOOP,
			self::MOST_POPULAR,
			self::LABEL,
			self::MSRP,
			self::SWATCH_IMAGE,
			self::SWATCH_LABEL,
			self::DEALERS,
		];
	}

	/**
	 * @param int $post_id
	 * @param string $key
	 *
	 * @return mixed|null
	 */
	public function get_value( $post_id, $key ) {
		return get_field( $key, $post_id );
	}

	/**
	 * Extend this in child class
	 */
	public function get_group_config() {}

	/**
	 * @param string $name
	 * @param string $label
	 *
	 * @return Group
	 */
	public function create_group( string $name, string $label ) {
		$group_key = md5( $name );
		$group = new Group( $group_key );
		$group->set( 'title', $label );
		$group->set_post_types( $this->post_types );

		return $group;
	}

	/**
	 * @return Field
	 */
	public function get_subtext() {
		$field = new Field( static::NAME . '_' . self::SUBTEXT );
		$field->set_attributes( [
			'label'         => esc_html__( 'Beauty Text', 'tribe' ),
			'name'          => self::SUBTEXT,
			'type'          => 'text',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_option_description() {
		$field = new Field( static::NAME . '_' . self::OPTIONS_DESCRIPTION );
		$field->set_attributes( [
			'label'     => esc_html__( 'Options Description', 'tribe' ),
			'name'      => self::OPTIONS_DESCRIPTION,
			'type'      => 'textarea',
			'maxlength' => self::DESCRIPTION_MAX,
			'rows'      => 3,
			'new_lines' => '',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_horizontal_image() {
		$field = new Field( static::NAME . '_' . self::HORIZONTAL_IMAGE );
		$field->set_attributes( [
			'label'         => esc_html__( 'Horizontal Image', 'tribe' ),
			'name'          => self::HORIZONTAL_IMAGE,
			'type'          => 'image',
			'return_format' => 'id',
			'instructions'  => __( 'Optimal image size: minimum of 1755 wide.', 'tribe' ),
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_key_features_image() {
		$field = new Field( static::NAME . '_' . self::KEY_IMAGE );
		$field->set_attributes( [
			'label'         => esc_html__( 'Key Features Image', 'tribe' ),
			'name'          => self::KEY_IMAGE,
			'type'          => 'image',
			'return_format' => 'id',
			'instructions'  => __( 'Optimal image size: 2000 x 1120; recommend featuring focus of the image towards the left.', 'tribe' ),
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_highlight_gallery() {
		$field = new Field( static::NAME . '_' . self::HIGHLIGHT_GALLERY );
		$field->set_attributes( [
			'label'        => esc_html__( 'Highlight Gallery', 'tribe' ),
			'name'         => self::HIGHLIGHT_GALLERY,
			'type'         => 'gallery',
			'instructions' => __( 'Optimal image size: 1755 x 998.', 'tribe' ),
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_hide_from_loop() {
		$field = new Field( static::NAME . '_' .self::HIDE_FROM_LOOP );
		$field->set_attributes( [
			'label'         => esc_html__( 'Hide From Loop', 'tribe' ),
			'name'          => self::HIDE_FROM_LOOP,
			'type'          => 'true_false',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_swatch_image() {
		$field = new Field( static::NAME . '_' . self::SWATCH_IMAGE );
		$field->set_attributes( [
			'label'        => esc_html__( 'Swatch Image', 'tribe' ),
			'name'         => self::SWATCH_IMAGE,
			'type'         => 'image',
			'instructions' => __( 'Optimal image size: 50 x 50.', 'tribe' ),
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_swatch_label() {
		$field = new Field( static::NAME . '_' . self::SWATCH_LABEL );
		$field->set_attributes( [
			'label'         => esc_html__( 'Swatch Label', 'tribe' ),
			'name'          => self::SWATCH_LABEL,
			'type'          => 'text',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_dealer_repeater() {
		$repeater = new Repeater( static::NAME . '_' . self::DEALERS );
		$repeater->set_attributes( [
			'label'        => esc_html__( 'Dealers', 'tribe' ),
			'name'         => self::DEALERS,
			'button_label' => __( 'Add Dealer', 'tribe' ),
			'layout'       => 'block',
		] );

		// Dealer Name
		$field = new Field( static::NAME . '_' . self::DEALERS_NAME );
		$field->set_attributes( [
			'label'         => esc_html__( 'Dealer Name', 'tribe' ),
			'name'          => self::DEALERS_NAME,
			'type'          => 'text',
		] );
		$repeater->add_field( $field );

		// Dealer URL
		$field = new Field( static::NAME . '_' . self::DEALERS_URL );
		$field->set_attributes( [
			'label'         => esc_html__( 'URL to Dealer', 'tribe' ),
			'name'          => self::DEALERS_URL,
			'type'          => 'url',
		] );
		$repeater->add_field( $field );

		// Image Upload
		$field = new Field( static::NAME . '_' . self::DEALERS_IMAGE );
		$field->set_attributes( [
			'label'         => esc_html__( 'Dealer Logo', 'tribe' ),
			'name'          => self::DEALERS_IMAGE,
			'type'          => 'image',
			'instructions'  => __( 'Optimal image size: minimum of 300 wide.', 'tribe' ),
			'return_format' => 'id',
		] );
		$repeater->add_field( $field );

		return $repeater;
	}
    
    /**
	 * @return Field
	 */
	public function get_priority() {
		$field = new Field( static::NAME . '_' . self::PRIORITY );
		$field->set_attributes( [
			'label'         => esc_html__( 'Prority', 'tribe' ),
			'name'          => self::PRIORITY,
			'type'          => 'number',
			'default_value' => 0
		] );

		return $field;
	}
}