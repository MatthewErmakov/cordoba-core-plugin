<?php
namespace Tribe\Project\Settings;

use Tribe\Libs\ACF;

class Organization_JSON_LD_Schema extends Contracts\ACF_Settings {
	const NAME = 'organization_json_ld_schema_settings';

	const NOTE = 'note';

	const TAB_GENERAL                      = 'tab_general';
	const ORGANIZATION_TYPE                = 'organization_type';
	const ORGANIZATION_NAME                = 'organization_name';
	const ORGANIZATION_LEGAL_NAME          = 'organization_legal_name';
	const ORGANIZATION_AUTHOR_AND_CREATOR  = 'organzation_author_and_creator';
	const ORGANIZATION_COPYRIGHT_HOLDER    = 'organization_copyright_holder';
	const ORGANIZATION_PARENT_ORGANIZATION = 'organization_parent_organization';
	const ORGANIZATION_DESCRIPTION         = 'organization_description';
	const ORGANIZATION_LOGO                = 'organization_logo';
	const ORGANIZATION_FOUNDER             = 'organization_founder';
	const ORGANIZATION_FOUNDING_DATE       = 'organization_founding_date';

	const TAB_ADDRESS                   = 'tab_address';
	const ORGANIZATION_STREET_ADDRESS   = 'organization_street_address';
	const ORGANIZATION_ADDRESS_LOCALITY = 'organization_address_locality';
	const ORGANIZATION_ADDRESS_REGION   = 'organization_address_region';
	const ORGANIZATION_POSTAL_CODE      = 'organization_postal_code';
	const ORGANIZATION_ADDRESS_COUNTRY  = 'organization_address_country';

	const TAB_CONTACT_POINTS              = 'tab_contact_points';
	const ORGANIZATION_CONTACT_POINTS     = 'organization_contact_points';
	const ORGANIZATION_CONTACT_TYPE       = 'organization_contact_type';
	const ORGANIZATION_CONTACT_OPTION     = 'organization_contact_option';
	const ORGANIZATION_PHONE_PROGRAMMATIC = 'organization_phone_programmatic';
	const ORGANIZATION_EMAIL_ADDRESS      = 'organization_email_address';
	const ORGANIZATION_FAX_PROGRAMMATIC   = 'organization_fax_programmatic';
	const ORGANIZATION_AREA_SERVED        = 'organization_area_served';
	const ORGANIZATION_AVAILABLE_LANGUAGE = 'organization_available_language';

	public function get_title() {
		return __( 'Organization Schema', 'tribe' );
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
		$group = new ACF\Group( self::NAME );
		$group->set_attributes( [
			'title'      => __( 'Organization Schema', 'tribe' ),
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
		$group->add_field( $this->get_notes_field() );

		$group->add_field( $this->get_general_group_tab() );
		$group->add_field( $this->get_general_type_field() );
		$group->add_field( $this->get_general_name_field() );
		$group->add_field( $this->get_general_legal_name_field() );
		$group->add_field( $this->get_general_author_and_creator_field() );
		$group->add_field( $this->get_general_copyright_holder_field() );
		$group->add_field( $this->get_general_parent_organization_field() );
		$group->add_field( $this->get_general_description_field() );
		$group->add_field( $this->get_general_logo_field() );
		$group->add_field( $this->get_general_founder_field() );
		$group->add_field( $this->get_general_founding_date_field() );

		$group->add_field( $this->get_address_group_tab() );
		$group->add_field( $this->get_address_street_field() );
		$group->add_field( $this->get_address_locality_field() );
		$group->add_field( $this->get_address_region_field() );
		$group->add_field( $this->get_address_postal_code_field() );
		$group->add_field( $this->get_address_country_field() );

		$group->add_field( $this->get_contact_points_group_tab() );
		$group->add_field( $this->get_contact_points_field() );

		return $group->get_attributes();
	}

	private function get_notes_field() {
		$field = new ACF\Field( self::NAME . '_' . self::NOTE );
		$field->set_attributes( [
			'label'   => __( 'Notes', 'tribe' ),
			'name'    => self::NOTE,
			'type'    => 'message',
			'message' => __( 'The following options are used to power your organization\'s JSON-LD structured data, which is useful to search engines and can enrich your search results.', 'tribe' ),
		] );
		return $field;
	}

	private function get_general_group_tab() {
		$field = new ACF\Field( self::NAME . '_' . self::TAB_GENERAL );
		$field->set_attributes( [
			'label'     => __( 'General', 'tribe' ),
			'name'      => self::TAB_GENERAL,
			'type'      => 'tab',
			'placement' => 'top',
		] );
		return $field;
	}

	private function get_general_type_field() {
		$field = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_TYPE );
		$field->set_attributes( [
			'label'         => __( 'Organization Type', 'tribe' ),
			'name'          => self::ORGANIZATION_TYPE,
			'type'          => 'select',
			'required'      => 1,
			'choices'       => [
				'Corporation'             => __( 'Corporation', 'tribe' ),
				'EducationalOrganization' => __( 'Education Organization', 'tribe' ),
				'GovernmentOrganization'  => __( 'Government Organization', 'tribe' ),
				'LocalBusiness'           => __( 'Local Business', 'tribe' ),
				'MedicalOrganization'     => __( 'Medical Organization', 'tribe' ),
				'NGO'                     => __( 'Non-governmental Organization', 'tribe' ),
				'PerformingGroup'         => __( 'Performing Group', 'tribe' ),
				'SportsOrganization'      => __( 'Sports Organization', 'tribe' ),
			],
			'default_value' => [],
			'allow_null'    => 0,
			'multiple'      => 0,
			'ui'            => 0,
			'ajax'          => 0,
			'return_format' => 'value',
		] );
		return $field;
	}

	private function get_general_name_field() {
		$field = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_NAME );
		$field->set_attributes( [
			'label'    => __( 'Organization Name', 'tribe' ),
			'name'     => self::ORGANIZATION_NAME,
			'type'     => 'text',
			'required' => 1,
		] );
		return $field;
	}

	private function get_general_legal_name_field() {
		$field = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_LEGAL_NAME );
		$field->set_attributes( [
			'label'    => __( 'Organization Legal Name', 'tribe' ),
			'name'     => self::ORGANIZATION_LEGAL_NAME,
			'type'     => 'text',
			'required' => 1,
		] );
		return $field;
	}

	private function get_general_author_and_creator_field() {
		$field = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_AUTHOR_AND_CREATOR );
		$field->set_attributes( [
			'label'    => __( 'General Site & Content Author / Creator', 'tribe' ),
			'name'     => self::ORGANIZATION_AUTHOR_AND_CREATOR,
			'type'     => 'text',
			'required' => 1,
		] );
		return $field;
	}

	private function get_general_copyright_holder_field() {
		$field = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_COPYRIGHT_HOLDER );
		$field->set_attributes( [
			'label'    => __( 'Organization Copyright Holder', 'tribe' ),
			'name'     => self::ORGANIZATION_COPYRIGHT_HOLDER,
			'type'     => 'text',
			'required' => 1,
		] );
		return $field;
	}

	private function get_general_parent_organization_field() {
		$field = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_PARENT_ORGANIZATION );
		$field->set_attributes( [
			'label' => __( 'Parent Organization / Member Of', 'tribe' ),
			'name'  => self::ORGANIZATION_PARENT_ORGANIZATION,
			'type'  => 'text',
		] );
		return $field;
	}

	private function get_general_description_field() {
		$field = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_DESCRIPTION );
		$field->set_attributes( [
			'label' => __( 'Short Description', 'tribe' ),
			'name'  => self::ORGANIZATION_DESCRIPTION,
			'type'  => 'text',
		] );
		return $field;
	}

	private function get_general_logo_field() {
		$field = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_LOGO );
		$field->set_attributes( [
			'label'         => __( 'Organization Logo', 'tribe' ),
			'name'          => self::ORGANIZATION_LOGO,
			'type'          => 'image',
			'return_format' => 'url',
			'preview_size'  => 'medium',
			'library'       => 'all',
		] );
		return $field;
	}

	private function get_general_founder_field() {
		$field = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_FOUNDER );
		$field->set_attributes( [
			'label' => __( 'Organization Founder', 'tribe' ),
			'name'  => self::ORGANIZATION_FOUNDER,
			'type'  => 'text',
		] );
		return $field;
	}

	private function get_general_founding_date_field() {
		$field = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_FOUNDING_DATE );
		$field->set_attributes( [
			'label' => __( 'Organization Founding Date', 'tribe' ),
			'name'  => self::ORGANIZATION_FOUNDING_DATE,
			'type'  => 'text',
		] );
		return $field;
	}

	private function get_address_group_tab() {
		$field = new ACF\Field( self::NAME . '_' . self::TAB_ADDRESS );
		$field->set_attributes( [
			'label'     => __( 'Address', 'tribe' ),
			'name'      => self::TAB_ADDRESS,
			'type'      => 'tab',
			'placement' => 'top',
		] );
		return $field;
	}

	private function get_address_street_field() {
		$field = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_STREET_ADDRESS );
		$field->set_attributes( [
			'label' => __( 'Street Address', 'tribe' ),
			'name'  => self::ORGANIZATION_STREET_ADDRESS,
			'type'  => 'text',
		] );
		return $field;
	}

	private function get_address_locality_field() {
		$field = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_ADDRESS_LOCALITY );
		$field->set_attributes( [
			'label'        => __( 'Address Locality', 'tribe' ),
			'name'         => self::ORGANIZATION_ADDRESS_LOCALITY,
			'type'         => 'text',
			'instructions' => __( 'City, town, or village', 'tribe' ),
		] );
		return $field;
	}

	private function get_address_region_field() {
		$field = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_ADDRESS_REGION );
		$field->set_attributes( [
			'label'        => __( 'Address Region', 'tribe' ),
			'name'         => self::ORGANIZATION_ADDRESS_REGION,
			'type'         => 'text',
			'instructions' => __( 'State or province', 'tribe' ),
		] );
		return $field;
	}

	private function get_address_postal_code_field() {
		$field = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_POSTAL_CODE );
		$field->set_attributes( [
			'label' => __( 'Postal / Zip Code', 'tribe' ),
			'name'  => self::ORGANIZATION_POSTAL_CODE,
			'type'  => 'text',
		] );
		return $field;
	}

	private function get_address_country_field() {
		$field = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_ADDRESS_COUNTRY );
		$field->set_attributes( [
			'label'        => __( 'Address Country', 'tribe' ),
			'name'         => self::ORGANIZATION_ADDRESS_COUNTRY,
			'type'         => 'text',
			'instructions' => __( 'The geographical region of this address. The country can be specified concisely using it\'s <a href="https://en.wikipedia.org/wiki/ISO_3166-1#Current_codes" rel="external">standard ISO-3166 two-letter code</a>, as in the example. <strong>Example: US</strong>', 'tribe' ),
		] );
		return $field;
	}

	private function get_contact_points_group_tab() {
		$field = new ACF\Field( self::NAME . '_' . self::TAB_CONTACT_POINTS );
		$field->set_attributes( [
			'label'     => __( 'Contact Points', 'tribe' ),
			'name'      => self::TAB_CONTACT_POINTS,
			'type'      => 'tab',
			'placement' => 'top',
		] );
		return $field;
	}

	private function get_contact_points_field() {
		$repeater = new ACF\Repeater( self::NAME . '_' . self::ORGANIZATION_CONTACT_POINTS );
		$repeater->set_attributes( [
			'label'        => '',
			'name'         => static::ORGANIZATION_CONTACT_POINTS,
			'button_label' => __( 'Add Contact Point', 'tribe' ),
			'layout'       => 'block',
		] );
		$type = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_CONTACT_POINTS . '_' . self::ORGANIZATION_CONTACT_TYPE );
		$type->set_attributes( [
			'label'         => __( 'Contact Type', 'tribe' ),
			'name'          => static::ORGANIZATION_CONTACT_TYPE,
			'type'          => 'select',
			'required'      => 1,
			'choices'       => [
				'customer support'    => __( 'Customer Support', 'tribe' ),
				'technical support'   => __( 'Technical Support', 'tribe' ),
				'billing support'     => __( 'Billing Support', 'tribe' ),
				'bill payment'        => __( 'Bill Payment', 'tribe' ),
				'sales'               => __( 'Sales', 'tribe' ),
				'credit card support' => __( 'Credit Card Support', 'tribe' ),
				'package tracking'    => __( 'Package Tracking', 'tribe' ),
			],
			'default_value' => [
				0 => 'customer support',
			],
			'allow_null'    => '',
			'multiple'      => '',
			'ui'            => '',
			'ajax'          => '',
			'return_format' => '',
		] );
		$repeater->add_field( $type );
		$option = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_CONTACT_POINTS . '_' . self::ORGANIZATION_CONTACT_OPTION );
		$option->set_attributes( [
			'label'         => __( 'Contact Option', 'tribe' ),
			'name'          => static::ORGANIZATION_CONTACT_OPTION,
			'type'          => 'checkbox',
			'instructions'  => __( 'Details about the phone number.', 'tribe' ),
			'choices'       => [
				'TollFree'                 => __( 'Toll Free', 'tribe' ),
				'HearingImpairedSupported' => __( 'Hearing Impaired Supported', 'tribe' ),
			],
			'default_value' => [],
			'layout'        => '',
			'toggle'        => '',
			'return_format' => '',
		] );
		$repeater->add_field( $option );
		$phone = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_CONTACT_POINTS . '_' . self::ORGANIZATION_PHONE_PROGRAMMATIC );
		$phone->set_attributes( [
			'label'        => __( 'Phone Number (Programmatic)', 'tribe' ),
			'name'         => static::ORGANIZATION_PHONE_PROGRAMMATIC,
			'type'         => 'text',
			'instructions' => __( 'An internationalized version of the phone number, starting with the "+" symbol and country code (+1 in the US and Canada).<br><br><strong>Examples: "+1-800-555-1212" or "+44-2078225951"</strong>', 'tribe' ),
		] );
		$repeater->add_field( $phone );
		$email = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_CONTACT_POINTS . '_' . self::ORGANIZATION_EMAIL_ADDRESS );
		$email->set_attributes( [
			'label'        => __( 'Email Address', 'tribe' ),
			'name'         => static::ORGANIZATION_EMAIL_ADDRESS,
			'type'         => 'email',
			'instructions' => __( 'An internationalized version of the phone number, starting with the "+" symbol and country code (+1 in the US and Canada).<br><br><strong>Examples: "+1-800-555-1212" or "+44-2078225951"</strong>', 'tribe' ),
		] );
		$repeater->add_field( $email );
		$fax = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_CONTACT_POINTS . '_' . self::ORGANIZATION_FAX_PROGRAMMATIC );
		$fax->set_attributes( [
			'label'        => __( 'Fax Number (Programmatic)', 'tribe' ),
			'name'         => static::ORGANIZATION_FAX_PROGRAMMATIC,
			'type'         => 'text',
			'instructions' => __( 'An internationalized version of the phone number, starting with the "+" symbol and country code (+1 in the US and Canada).<br><br><strong>Examples: "+1-800-555-1212" or "+44-2078225951"</strong>', 'tribe' ),
		] );
		$repeater->add_field( $fax );
		$area_served = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_CONTACT_POINTS . '_' . self::ORGANIZATION_AREA_SERVED );
		$area_served->set_attributes( [
			'label'        => __( 'Area Served', 'tribe' ),
			'name'         => static::ORGANIZATION_AREA_SERVED,
			'type'         => 'text',
			'instructions' => sprintf(
				_x( 'The geographical region served by the contact point. Countries may be specified concisely using just their %1$sstandard ISO-3166 two-letter code%2$s, as in the examples. If omitted, it is assumed to be global.%3$sExamples: "US" or "US", "CA", "MX".%4$sNote that the quotes around the country code are required, and if you have multiple codes, they should be comma separated, with no trailing comma.%5$s',
					'{<a>, </a>, <br><br><strong>, </strong><br><br><em>, </em> }', 'tribe' ),
					'<a href="https://en.wikipedia.org/wiki/ISO_3166-1#Current_codes" rel="external">',
					'</a>',
					'<br><br><strong>',
					'</strong><br><br><em>',
					'</em>'
				),
		] );
		$repeater->add_field( $area_served );
		$language = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_CONTACT_POINTS . '_' . self::ORGANIZATION_AVAILABLE_LANGUAGE );
		$language->set_attributes( [
			'label'        => __( 'Available Language', 'tribe' ),
			'name'         => static::ORGANIZATION_AVAILABLE_LANGUAGE,
			'type'         => 'text',
			'instructions' => __( 'Details about the language spoken. Languages may be specified by their common English name.<br><br><strong>Examples: "English" or "French", "English".</strong><br><br><em>Note that the quotes around the language are required, and if you have multiple languages, they should be comma separated, with no trailing comma.</em>', 'tribe' ),
		] );
		$repeater->add_field( $language );
		return $repeater;
	}

	/**
	 * @return Organization_JSON_LD_Schema
	 */
	public static function instance() {
		return tribe_project()->container()['settings.organization_json_ld_schema'];
	}
}
