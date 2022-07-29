<?php


namespace Tribe\Project\Theme\Resources;


class Editor_Styles {

	/** @var string Path to the root file of the plugin */
	private $plugin_file = '';

	public function __construct( $plugin_file = '' ) {
		$this->plugin_file = $plugin_file;
	}

	/**
	 * Visual Editor Styles
	 * @action after_setup_theme
	 */
	public function visual_editor_styles() {

		$css_dir    = $this->get_css_url();
		$editor_css = 'editor-style.css';

		// Production
		if ( ! defined( 'SCRIPT_DEBUG' ) || SCRIPT_DEBUG === false ) {
			$editor_css = 'dist/editor-style.min.css';
		}

		add_editor_style( $css_dir . $editor_css );

	}
	/**
	 * Visual Editor Body Class
	 * @filter tiny_mce_before_init
	 */
	public function visual_editor_body_class( $settings ) {

		$settings['body_class'] .= ' t-content';

		return $settings;

	}

	private function get_css_url() {
		return plugins_url( 'assets/theme/css/admin/', $this->plugin_file );
	}

	/**
	 * Visual Editor Format Button / Selector
	 */
	public function add_tinymce_format_button( $buttons ) {
		array_unshift( $buttons, 'styleselect' );
		return $buttons;
	}

	/**
	 * Format Styles / Helper Classes
	 */
	public function add_tinymce_formats( $init_array ) {
		$style_formats = [
			[
				'title'    => 'Button',
				'selector' => 'button,a',
				'classes'  => 'button',
				'wrapper'  => false,
			],
			[
				'title'    => 'Button (Mojo)',
				'selector' => 'button,a',
				'classes'  => 'button button--mojo',
				'wrapper'  => false,
			],
			[
				'title'    => 'Button (Mako)',
				'selector' => 'button,a',
				'classes'  => 'button button--mako-alt',
				'wrapper'  => false,
			],
			[
				'title'    => 'Text Button',
				'selector' => 'button,a',
				'classes'  => 'button-text',
				'wrapper'  => false,
			],
		];

		$init_array['style_formats'] = json_encode( $style_formats );

		return $init_array;
	}
}
