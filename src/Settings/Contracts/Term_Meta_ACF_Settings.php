<?php

namespace Tribe\Project\Settings\Contracts;

/**
 * Class Abstract_Settings
 *
 * @ToDo: Candidate for tribe-libs
 *
 * @package Tribe\Project\Settings
 */
abstract class Term_Meta_ACF_Settings extends Base_Settings {

	/**
	 * Get setting value
	 *
	 * @param string $key
	 * @param null   $default
	 *
	 * @return mixed
	 */
	public function get_setting( $key, $default = null ) {
		$value = get_field( $key, 'option' );
		if ( empty( $value ) ) {
			$value = $default;
		}

		return $value;
	}

	/**
	 * Registers the settings page with ACF
	 */
	public function register_settings() {
		acf_add_options_sub_page( apply_filters( 'core_settings_acf_sub_page', [
			'page_title'  => $this->get_title(),
			'menu_title'  => $this->get_title(),
			'menu_slug'   => $this->slug,
			'redirect'    => true,
			'capability'  => $this->get_capability(),
			'parent_slug' => $this->get_parent_slug(),
		] ) );
	}

	/**
	 * Adds the settings groups
	 */
	public function register_fields() {}

}
