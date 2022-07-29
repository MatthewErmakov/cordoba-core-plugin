<?php

namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class Wysiwyg extends Panel_Type_Config {

	const NAME = 'wysiwyg';

	const FIELD_COLUMNS = 'columns';
	const FIELD_CONTENT = 'content';

	const FIELD_STYLE       = 'style';
	const FIELD_STYLE_BOXED = 'boxed';
	const FIELD_STYLE_BRUSH = 'brushed';

	protected function panel() {

		$panel       = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'WYSIWYG Editor', 'tribe' ) );
		$panel->set_description( __( 'Displays custom content.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'module-wysiwyg.jpg' ) );

		$columns = new Fields\Repeater( [
			'label'   => __( 'Columns', 'tribe' ),
			'name'    => self::FIELD_COLUMNS,
			'min'     => 1,
			'max'     => 3,
			'strings' => [
				'button.new'      => __( 'Add Column', 'tribe' ),
				'button.delete'   => __( 'Delete Column', 'tribe' ),
				'label.row_index' => _x( 'Column %{index} |||| Column %{index}', 'Format should be polyglot.js compatible. See https://github.com/airbnb/polyglot.js#pluralization', 'tribe' ),
				'notice.max_rows' => __( 'You have reached the column limit of this field', 'tribe' ),
			],
		] );

		$columns->add_field( new Fields\TextArea( [
			'label'    => __( 'Content', 'tribe' ),
			'name'     => self::FIELD_CONTENT,
			'richtext' => true,
		] ) );

		$panel->add_field( $columns );

		$panel->add_settings_field( new Fields\Select( [
			'name'    => self::FIELD_STYLE,
			'label'   => __( 'Columns Style', 'tribe' ),
			'options' => [
				self::FIELD_STYLE_BOXED => __( 'Boxed', 'tribe' ),
				self::FIELD_STYLE_BRUSH => __( 'Brush Stroke', 'tribe' ),
			],
			'default' => self::FIELD_STYLE_BOXED,
		] ) );

		return $panel;
	}
}
