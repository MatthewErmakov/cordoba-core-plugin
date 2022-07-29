<?php
namespace Tribe\Project\Post_Meta;

use Tribe\Libs\ACF\ACF_Meta_Group;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Group;

class Locations_Meta extends ACF_Meta_Group {

	const NAME = 'locations-meta';

	const FEATURED                  = '_bh_sl_featured';
	const ADDRESS1                  = '_bh_sl_address';
	const ADDRESS2                  = '_bh_sl_address_two';
	const CITY                      = '_bh_sl_city';
	const STATE                     = '_bh_sl_state';
	const POSTAL                    = '_bh_sl_postal';
	const COUNTRY                   = '_bh_sl_country';
	const PHONE                     = '_bh_sl_phone';
	const EMAIL                     = '_bh_sl_email';
	const FAX                       = '_bh_sl_fax';
	const WEB                       = '_bh_sl_web';
	const HOURS1                    = '_bh_sl_hours_one';
	const HOURS2                    = '_bh_sl_hours_two';
	const HOURS3                    = '_bh_sl_hours_three';
	const HOURS4                    = '_bh_sl_hours_four';
	const HOURS5                    = '_bh_sl_hours_five';
	const HOURS6                    = '_bh_sl_hours_six';
	const HOURS7                    = '_bh_sl_hours_seven';
	const LATITUDE                  = 'bh_storelocator_location_lat';
	const LONGITUDE                 = 'bh_storelocator_location_lng';

	const DEALER_NO                 = '_bh_sl_dealer_number';
	const WEBSITE_GUILD             = '_bh_sl_web';
	const WEBSITE_GUILD_ELECTRONICS = '_bh_sl_web_guild';

	public function get_keys() {
		return [
			self::FEATURED,
			self::ADDRESS1,
			self::ADDRESS2,
			self::CITY,
			self::STATE,
			self::POSTAL,
			self::COUNTRY,
			self::PHONE,
			self::EMAIL,
			self::FAX,
			self::WEB,
			self::HOURS1,
			self::HOURS2,
			self::HOURS3,
			self::HOURS4,
			self::HOURS5,
			self::HOURS6,
			self::HOURS7,
			self::LATITUDE,
			self::LONGITUDE,
			self::DEALER_NO,
			self::WEBSITE_GUILD,
			self::WEBSITE_GUILD_ELECTRONICS
		];
	}

	public function get_value( $post_id, $key ) {
		return get_field( $key, $post_id );
	}

	public function get_group_config() {
		$group = $this->create_group( self::NAME, esc_html__( 'Locations Data', 'tribe' ) );
		$group->add_field( $this->get_dealer_number() );
		$group->add_field( $this->get_website_guild() );
		$group->add_field( $this->get_website_guild_electronics() );
		$group->add_field( $this->get_featured_location() );
		$group->add_field( $this->get_address() );
		$group->add_field( $this->get_address_two() );
		$group->add_field( $this->get_city() );
		$group->add_field( $this->get_state() );
		$group->add_field( $this->get_postal() );
		$group->add_field( $this->get_country() );
		$group->add_field( $this->get_phone() );
		$group->add_field( $this->get_fax() );
		$group->add_field( $this->get_website() );
		$group->add_field( $this->get_hours_one() );
		$group->add_field( $this->get_hours_two() );
		$group->add_field( $this->get_hours_three() );
		$group->add_field( $this->get_hours_four() );
		$group->add_field( $this->get_hours_five() );
		$group->add_field( $this->get_hours_six() );
		$group->add_field( $this->get_hours_seven() );
		$group->add_field( $this->get_latitude() );
		$group->add_field( $this->get_longitude() );

		return $group->get_attributes();
	}

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
		$group->set( 'label_placement', 'left' );
		$group->set( 'instruction_placement', 'field' );
		$group->set_post_types( $this->post_types );

		return $group;
	}

	/**
	 * @return Field
	 */
	public function get_dealer_number() {
		$field = new Field( static::NAME . '_' . self::DEALER_NO );
		$field->set_attributes( [
			'label' => esc_html__( 'Dealer Number', 'tribe' ),
			'name'  => self::DEALER_NO,
			'type'  => 'text',
		] );

		return $field;
	}

		/**
		 * @return Field
		 */
	public function get_featured_location() {
		$field = new Field( self::NAME . '_' . self::FEATURED );
		$field->set_attributes( [
			'label' => esc_html__( 'Featured Location', 'tribe' ),
			'name'  => self::FEATURED,
			'type'  => 'checkbox',
			'type'          => 'true_false',
			'default_value' => 0,
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_address() {
		$field = new Field( self::NAME . '_' . self::ADDRESS1 );
		$field->set_attributes( [
			'label' => esc_html__( 'Address', 'tribe' ),
			'name'  => self::ADDRESS1,
			'type'  => 'text',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_address_two() {
		$field = new Field( self::NAME . '_' . self::ADDRESS2 );
		$field->set_attributes( [
			'label' => esc_html__( 'Address 2', 'tribe' ),
			'name'  => self::ADDRESS2,
			'type'  => 'text',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_city() {
		$field = new Field( self::NAME . '_' . self::CITY );
		$field->set_attributes( [
			'label' => esc_html__( 'City', 'tribe' ),
			'name'  => self::CITY,
			'type'  => 'text',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_state() {
		$field = new Field( self::NAME . '_' . self::STATE );
		$field->set_attributes( [
			'label' => esc_html__( 'State/Province', 'tribe' ),
			'name'  => self::STATE,
			'type'  => 'text',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_postal() {
		$field = new Field( self::NAME . '_' . self::POSTAL );
		$field->set_attributes( [
			'label' => esc_html__( 'Postal Code', 'tribe' ),
			'name'  => self::POSTAL,
			'type'  => 'text',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_country() {
		$field = new Field( self::NAME . '_' . self::COUNTRY );
		$field->set_attributes( [
			'label' => esc_html__( 'ccTLD two letter country code', 'tribe' ),
			'name'  => self::COUNTRY,
			'type'  => 'text',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_phone() {
		$field = new Field( self::NAME . '_' . self::PHONE );
		$field->set_attributes( [
			'label' => esc_html__( 'Phone', 'tribe' ),
			'name'  => self::PHONE,
			'type'  => 'text',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_fax() {
		$field = new Field( self::NAME . '_' . self::FAX );
		$field->set_attributes( [
			'label' => esc_html__( 'Fax', 'tribe' ),
			'name'  => self::FAX,
			'type'  => 'text',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_email() {
		$field = new Field( self::NAME . '_' . self::EMAIL );
		$field->set_attributes( [
			'label' => esc_html__( 'Email', 'tribe' ),
			'name'  => self::EMAIL,
			'type'  => 'text',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_website() {
		$field = new Field( self::NAME . '_' . self::WEB );
		$field->set_attributes( [
			'label' => esc_html__( 'Website', 'tribe' ),
			'name'  => self::WEB,
			'type'  => 'text',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_hours_one() {
		$field = new Field( self::NAME . '_' . self::HOURS1 );
		$field->set_attributes( [
			'label' => esc_html__( 'Hours 1', 'tribe' ),
			'name'  => self::HOURS1,
			'type'  => 'text',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_hours_two() {
		$field = new Field( self::NAME . '_' . self::HOURS2 );
		$field->set_attributes( [
			'label' => esc_html__( 'Hours 2', 'tribe' ),
			'name'  => self::HOURS2,
			'type'  => 'text',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_hours_three() {
		$field = new Field( self::NAME . '_' . self::HOURS3 );
		$field->set_attributes( [
			'label' => esc_html__( 'Hours 3', 'tribe' ),
			'name'  => self::HOURS3,
			'type'  => 'text',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_hours_four() {
		$field = new Field( self::NAME . '_' . self::HOURS4 );
		$field->set_attributes( [
			'label' => esc_html__( 'Hours 4', 'tribe' ),
			'name'  => self::HOURS4,
			'type'  => 'text',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_hours_five() {
		$field = new Field( self::NAME . '_' . self::HOURS5 );
		$field->set_attributes( [
			'label' => esc_html__( 'Hours 5', 'tribe' ),
			'name'  => self::HOURS5,
			'type'  => 'text',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_hours_six() {
		$field = new Field( self::NAME . '_' . self::HOURS6 );
		$field->set_attributes( [
			'label' => esc_html__( 'Hours 6', 'tribe' ),
			'name'  => self::HOURS6,
			'type'  => 'text',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_hours_seven() {
		$field = new Field( self::NAME . '_' . self::HOURS7 );
		$field->set_attributes( [
			'label' => esc_html__( 'Hours 7', 'tribe' ),
			'name'  => self::HOURS7,
			'type'  => 'text',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_website_guild() {
		$field = new Field( static::NAME . '_' . self::WEBSITE_GUILD );
		$field->set_attributes( [
			'label' => esc_html__( 'Website Guild', 'tribe' ),
			'name'  => self::WEBSITE_GUILD,
			'type'  => 'text',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_website_guild_electronics() {
		$field = new Field( static::NAME . '_' . self::WEBSITE_GUILD_ELECTRONICS );
		$field->set_attributes( [
			'label' => esc_html__( 'Website Guild - Electronics', 'tribe' ),
			'name'  => self::WEBSITE_GUILD_ELECTRONICS,
			'type'  => 'text',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_latitude() {
		$field = new Field( static::NAME . '_' . self::LATITUDE );
		$field->set_attributes( [
			'label' => esc_html__( 'Latitude', 'tribe' ),
			'name'  => self::LATITUDE,
			'type'  => 'text',
		] );

		return $field;
	}

	/**
	 * @return Field
	 */
	public function get_longitude() {
		$field = new Field( static::NAME . '_' . self::LONGITUDE );
		$field->set_attributes( [
			'label' => esc_html__( 'Longitude', 'tribe' ),
			'name'  => self::LONGITUDE,
			'type'  => 'text',
		] );

		return $field;
	}

}