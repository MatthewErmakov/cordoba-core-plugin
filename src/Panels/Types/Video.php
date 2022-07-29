<?php
namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class Video extends Panel_Type_Config {

	const NAME = 'video';

	const FIELD_CONTENT = 'content';
	const FIELD_IMAGE   = 'image';
	const FIELD_VIDEO   = 'video';

	const FIELD_STYLE        = 'style';
	const FIELD_STYLE_FULL   = 'full';
	const FIELD_STYLE_INLINE = 'inline';
	const FIELD_STYLE_BOXED  = 'boxed';

	protected function panel() {
		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Video', 'tribe' ) );
		$panel->set_description( __( 'A simple video panel. This panel should be one of two first panel\'s used if on a panel\'s only page.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'module-video.jpg' ) );

		$panel->add_field( new Fields\TextArea( [
			'name'  => self::FIELD_CONTENT,
			'label' => __( 'Content', 'tribe' ),
		] ) );

		$panel->add_field( new Fields\Image( [
			'name'        => self::FIELD_IMAGE,
			'label'       => __( 'Image', 'tribe' ),
			'description' => __( 'Optimal image size: 1152 x 648 for boxed & inline video styles; for full video style image: minimum of 2000 wide; add 120 of height if first panel on a panel\'s only page to handle site header overlap. Recommend featuring the focus of the image towards the bottom.', 'tribe' ),
			'size'        => 'medium',
		] ) );

		$panel->add_field( new Fields\Video( [
			'name'  => self::FIELD_VIDEO,
			'label' => __( 'Video Url (YouTube or Vimeo)', 'tribe' ),
		] ) );

		$panel->add_settings_field( new Fields\Select( [
			'label'   => __( 'Video Style', 'tribe' ),
			'name'    => self::FIELD_STYLE,
			'options' => [
				self::FIELD_STYLE_FULL   => __( 'Full Width', 'tribe' ),
				self::FIELD_STYLE_INLINE => __( 'Inline', 'tribe' ),
				self::FIELD_STYLE_BOXED  => __( 'Boxed', 'tribe' ),
			],
			'default' => self::FIELD_STYLE_FULL,
		] ) );

		return $panel;
	}
}
