<?php

namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class ImageText extends Panel_Type_Config {

	const NAME = 'imagetext';

	const FIELD_CONTENT = 'content';
	const FIELD_LINK    = 'link';
	const FIELD_IMAGE   = 'image';

	const FIELD_LAYOUT       = 'layout';
	const FIELD_LAYOUT_LEFT  = 'left';
	const FIELD_LAYOUT_RIGHT = 'right';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Image + Text', 'tribe' ) );
		$panel->set_description( __( 'An image and text with layout options.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'module-imagetext.jpg' ) );

		$panel->add_field( new Fields\TextArea( [
			'name'          => self::FIELD_CONTENT,
			'label'         => __( 'Content', 'tribe' ),
			'richtext'      => true,
			'media_buttons' => false,
		] ) );

		$panel->add_field( new Fields\Link( [
			'name'  => self::FIELD_LINK,
			'label' => __( 'Link', 'tribe' ),
		] ) );

		$panel->add_field( new Fields\Image( [
			'name'        => self::FIELD_IMAGE,
			'label'       => __( 'Image', 'tribe' ),
			'description' => __( 'Optimal image size: 1222 x 982.', 'tribe' ),
			'size'        => 'medium',
		] ) );

		$panel->add_settings_field( new Fields\ImageSelect( [
			'name'    => self::FIELD_LAYOUT,
			'label'   => __( 'Layout', 'tribe' ),
			'options' => [
				self::FIELD_LAYOUT_LEFT  => $this->handler->layout_icon_url( 'module-imagetext-left.png' ),
				self::FIELD_LAYOUT_RIGHT => $this->handler->layout_icon_url( 'module-imagetext-right.png' ),
			],
			'default' => self::FIELD_LAYOUT_LEFT,
		] ) );

		return $panel;

	}
}
