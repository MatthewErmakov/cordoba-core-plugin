<?php
namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class Faq extends Panel_Type_Config {

	const NAME            = 'faq';

	const FIELD_IMAGE     = 'image';

	const FIELD_QUESTIONS = 'questions';
	const FIELD_QUESTION  = 'question';
	const FIELD_ANSWER    = 'answer';

	protected function panel() {
		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'FAQ', 'tribe' ) );
		$panel->set_description( __( 'Set of Q&A.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'module-faq.jpg' ) );

		$questions = new Fields\Repeater( [
			'name'    => self::FIELD_QUESTIONS,
			'label'   => __( 'Questions', 'tribe' ),
			'min'     => 1,
			'max'     => 50,
			'strings' => [
				'button.new'      => __( 'Add Question', 'tribe' ),
				'button.delete'   => __( 'Delete Question', 'tribe' ),
				'label.row_index' => _x( 'Question %{index} |||| Question %{index}', 'Format should be polyglot.js compatible. See https://github.com/airbnb/polyglot.js#pluralization', 'tribe' ),
				'notice.max_rows' => __( 'You have reached the limit of this field', 'tribe' ),
			],
		] );

		$questions->add_field( new Fields\Text( [
			'name'  => self::FIELD_QUESTION,
			'label' => __( 'Question', 'tribe' ),
		] ) );

		$questions->add_field( new Fields\TextArea( [
			'name'          => self::FIELD_ANSWER,
			'label'         => __( 'Answer', 'tribe' ),
			'richtext'      => true,
			'media_buttons' => false,
		] ) );

		$panel->add_field( $questions );

		$panel->add_field( new Fields\Image( [
			'name'        => self::FIELD_IMAGE,
			'label'       => __( 'Image', 'tribe' ),
			'description' => __( 'Optimal image size: 1152 x 1324.', 'tribe' ),
		] ) );

		return $panel;
	}
}
