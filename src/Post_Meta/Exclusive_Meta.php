<?php
namespace Tribe\Project\Post_Meta;

use Tribe\Libs\ACF\Group;
use Tribe\Project\Post_Types\Exclusive\Exclusive;

class Exclusive_Meta extends Instrument_Meta {

	const NAME = 'exclusive_meta';

	public function get_group_config() {

		$group = $this->create_group( self::NAME, esc_html__( 'Exclusive Data', 'tribe' ) );

		$group->add_field( $this->get_subtext() );
		$group->add_field( $this->get_horizontal_image() );
		$group->add_field( $this->get_key_features_image() );
		$group->add_field( $this->get_option_description() );
		$group->add_field( $this->get_highlight_gallery() );
		$group->add_field( $this->get_hide_from_loop() );
		$group->add_field( $this->get_swatch_image() );
		$group->add_field( $this->get_swatch_label() );
		$group->add_field( $this->get_dealer_repeater() );

		return $group->get_attributes();
	}
}
