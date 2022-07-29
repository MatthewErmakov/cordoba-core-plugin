<?php
namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;
use Tribe\Project\Post_Types;

class CardGrid extends Panel_Type_Config {
	const NAME = 'cardgrid';

	const FIELD_CONTENT = 'content';
	const FIELD_POSTS   = 'posts';
	const FIELD_LINK    = 'link';

	const FIELD_STYLE        = 'style';
	const FIELD_STYLE_CARDS  = 'cards';
	const FIELD_STYLE_SLIDER = 'slider';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Card Grid', 'tribe' ) );
		$panel->set_description( __( 'Grid of 3 or more cards.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'module-cardgrid.jpg' ) );

		$panel->add_field( new Fields\TextArea( [
			'name'  => self::FIELD_CONTENT,
			'label' => __( 'Content', 'tribe' ),
		] ) );

		$panel->add_field( new Fields\Post_List( [
			'name'             => self::FIELD_POSTS,
			'label'            => __( 'Posts', 'tribe' ),
			'min'              => 2,
			'max'              => 12,
			'suggested'        => 3,
			'show_max_control' => true,
			'post_types'       => [
				Post_Types\Page\Page::NAME,
				Post_Types\Post\Post::NAME,
				Post_Types\Guitar\Guitar::NAME,
				Post_Types\Ukulele\Ukulele::NAME,
			],
			'description'      => __( 'Optimal image size: 600 x 525; for instrument images: 540 x 800 and recommend centering the instrument horizontally and then also filling vertical space.', 'tribe' ),
		] ) );

		$panel->add_field( new Fields\Link( [
			'name'  => self::FIELD_LINK,
			'label' => __( 'Link', 'tribe' ),
		] ) );

		$panel->add_settings_field( new Fields\Select( [
			'name'    => self::FIELD_STYLE,
			'label'   => __( 'Content Style', 'tribe' ),
			'options' => [
				self::FIELD_STYLE_CARDS  => __( 'Grid', 'tribe' ),
				self::FIELD_STYLE_SLIDER => __( 'Slider', 'tribe' ),
			],
			'default' => self::FIELD_STYLE_CARDS,
		] ) );

		return $panel;
	}
}
