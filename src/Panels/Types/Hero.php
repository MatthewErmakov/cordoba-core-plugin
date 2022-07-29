<?php
namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class Hero extends Panel_Type_Config {

	const NAME = 'hero';

	const FIELD_CONTENT  = 'content';
	const FIELD_LINK_ONE = 'link_one';
	const FIELD_LINK_TWO = 'link_two';
	const FIELD_IMAGE    = 'image';

	const FIELD_LAYOUT        = 'layout';
	const FIELD_LAYOUT_LEFT   = 'left';
	const FIELD_LAYOUT_CENTER = 'center';
	const FIELD_LAYOUT_RIGHT  = 'right';

	const FIELD_STYLE        = 'style';
	const FIELD_STYLE_BOXED  = 'boxed';
	const FIELD_STYLE_INLINE = 'inline';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_label( __( 'Hero', 'tribe' ) );
		$panel->set_description( __( 'Displays the Hero in various layouts. This panel should be one of two first panel\'s used if on a panel\'s only page.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'module-hero.jpg' ) );
		$panel->set_template_dir( $this->ViewFinder );

		$panel->add_field( new Fields\TextArea( [
			'name'  => self::FIELD_CONTENT,
			'label' => __( 'Content', 'tribe' ),
		] ) );

		$panel->add_field( new Fields\Link( [
			'name'  => self::FIELD_LINK_ONE,
			'label' => __( 'Link One', 'tribe' ),
		] ) );

		$panel->add_field( new Fields\Link( [
			'name'  => self::FIELD_LINK_TWO,
			'label' => __( 'Link Two', 'tribe' ),
		] ) );

		$panel->add_field( new Fields\Image( [
			'name'        => self::FIELD_IMAGE,
			'label'       => __( 'Image', 'tribe' ),
			'description' => __( 'Optimal image size: minimum of 2000 wide; add 120 of height if first panel on a panel\'s only page to handle site header overlap. Recommend featuring the focus of the image towards the bottom.', 'tribe' ),
			'size'        => 'medium',
		] ) );

		$panel->add_settings_field( new Fields\Select( [
			'name'    => self::FIELD_LAYOUT,
			'label'   => __( 'Hero Text Alignment', 'tribe' ),
			'options' => [
				self::FIELD_LAYOUT_LEFT   => __( 'Left', 'tribe' ),
				self::FIELD_LAYOUT_CENTER => __( 'Center', 'tribe' ),
				self::FIELD_LAYOUT_RIGHT  => __( 'Right', 'tribe' ),
			],
			'default' => self::FIELD_LAYOUT_CENTER,
		] ) );

		$panel->add_settings_field( new Fields\Select( [
			'name'    => self::FIELD_STYLE,
			'label'   => __( 'Content Style', 'tribe' ),
			'options' => [
				self::FIELD_STYLE_BOXED  => __( 'Boxed', 'tribe' ),
				self::FIELD_STYLE_INLINE => __( 'Inline', 'tribe' ),
			],
			'default' => self::FIELD_STYLE_BOXED,
		] ) );

		return $panel;
	}
}
