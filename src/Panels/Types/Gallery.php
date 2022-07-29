<?php

namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class Gallery extends Panel_Type_Config {

	const NAME = 'gallery';

	const FIELD_LINK        = 'link';
	const FIELD_GALLERY     = 'gallery';
	const FIELD_IMAGE       = 'image';
	const FIELD_IMAGE_TITLE = 'image_title';
	const FIELD_IMAGE_LINK  = 'image_link';

	const FIELD_LAYOUT        = 'layout';
	const FIELD_LAYOUT_LEFT   = 'left';
	const FIELD_LAYOUT_CENTER = 'center';
	const FIELD_LAYOUT_RIGHT  = 'right';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Gallery', 'tribe' ) );
		$panel->set_description( __( 'An image gallery.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'module-gallery.jpg' ) );

		$panel->add_field( new Fields\Link( [
			'name'  => self::FIELD_LINK,
			'label' => __( 'Link', 'tribe' ),
		] ) );

		$gallery = new Fields\Repeater( [
			'label' => __( 'Gallery', 'tribe' ),
			'name'  => self::FIELD_GALLERY,
			'min'     => 1,
			'max'     => 50,
			'strings' => [
				'button.new'      => __( 'Add Image', 'tribe' ),
				'button.delete'   => __( 'Delete Image', 'tribe' ),
				'label.row_index' => _x( 'Image %{index} |||| Image %{index}', 'Format should be polyglot.js compatible. See https://github.com/airbnb/polyglot.js#pluralization', 'tribe' ),
				'notice.max_rows' => __( 'You have reached the image limit of this field', 'tribe' ),
			],
		] );

		$gallery->add_field( new Fields\Image( [
			'label'       => __( 'Image', 'tribe' ),
			'name'        => self::FIELD_IMAGE,
			'description' => __( 'Optimal image size: 1200 wide and for an optimal grid layout it is recommended that you pay strict attention to the heights of all images. Base them all on the same base height where landscape oriented images get a height of 1 image tall while portrait oriented images should get a height of exactly 2 images tall (ex. landscape height of 800px & portrait height of 1600px). Lastly, it is recommended to feature the focus of the image towards the center.', 'tribe' ),
			'size'        => 'medium',
		] ) );

		$gallery->add_field( new Fields\Text( [
			'label'       => __( 'Title', 'tribe' ),
			'name'        => self::FIELD_IMAGE_TITLE,
			'description' => __( 'Recommend keeping titles short and concise.', 'tribe' ),
		] ) );

		$gallery->add_field( new Fields\Link( [
			'label' => __( 'Link', 'tribe' ),
			'name'  => self::FIELD_IMAGE_LINK,
		] ) );

		$panel->add_field( $gallery );

		$panel->add_settings_field( new Fields\Select( [
			'name'    => self::FIELD_LAYOUT,
			'label'   => __( 'Gallery Layout', 'tribe' ),
			'options' => [
				self::FIELD_LAYOUT_LEFT   => __( 'Left', 'tribe' ),
				self::FIELD_LAYOUT_CENTER => __( 'Center', 'tribe' ),
				self::FIELD_LAYOUT_RIGHT  => __( 'Right', 'tribe' )
			],
			'default' => self::FIELD_LAYOUT_CENTER,
		] ) );

		return $panel;

	}
}
