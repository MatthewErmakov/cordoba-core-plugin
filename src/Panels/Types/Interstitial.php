<?php
namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class Interstitial extends Panel_Type_Config {
	const NAME = 'interstitial';

	const FIELD_CONTENT  = 'content';
	const FIELD_IMAGE    = 'image';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_label( __( 'Interstitial', 'tribe' ) );
		$panel->set_description( __( 'Displays full bleed image with content. This panel should not be used as the last panel.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'module-interstitial.jpg' ) );
		$panel->set_template_dir( $this->ViewFinder );

		$panel->add_field( new Fields\TextArea( [
			'name'  => self::FIELD_CONTENT,
			'label' => __( 'Content', 'tribe' ),
		] ) );

		$panel->add_field( new Fields\Image( [
			'name'        => self::FIELD_IMAGE,
			'label'       => __( 'Image', 'tribe' ),
			'description' => __( 'Optimal image size: 2000 x 875', 'tribe' ),
			'size'        => 'medium',
		] ) );

		return $panel;
	}
}
