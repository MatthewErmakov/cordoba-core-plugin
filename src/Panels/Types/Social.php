<?php
namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class Social extends Panel_Type_Config {

	const NAME = 'social';

	const FIELD_VIDEOS = 'gallery';
	const FIELD_VIDEO  = 'video';

	protected function panel() {
		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Social', 'tribe' ) );
		$panel->set_description( __( 'A slider to display YouTube / Vimeo videos.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'module-social.jpg' ) );

		$videos = new Fields\Repeater( [
			'label'   => __( 'Videos', 'tribe' ),
			'name'    => self::FIELD_VIDEOS,
			'min'     => 2,
			'max'     => 8,
			'strings' => [
				'button.new'      => __( 'Add Video', 'tribe' ),
				'button.delete'   => __( 'Delete Video', 'tribe' ),
				'label.row_index' => _x( 'Video %{index} |||| Video %{index}', 'Format should be polyglot.js compatible. See https://github.com/airbnb/polyglot.js#pluralization', 'tribe' ),
				'notice.max_rows' => __( 'You have reached the video limit of this field', 'tribe' ),
			],
		] );

		$videos->add_field( new Fields\Video( [
			'label' => __( 'Video URL (YouTube or Vimeo)', 'tribe' ),
			'name'  => self::FIELD_VIDEO,
		] ) );

		$panel->add_field( $videos );

		return $panel;
	}
}
