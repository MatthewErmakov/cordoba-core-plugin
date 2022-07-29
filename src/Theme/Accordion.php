<?php

namespace Tribe\Project\Theme;

/**
 * Outputs accordion widget html from a passed array of title/content data in its render method.
 *
 * @param array $rows the rows content as array
 * @param string $mode the mode of layout for the accordion
 * @param string $state Whether first item is closed or open by default
 *
 */

class Accordion {
	private $rows = [];
	private $mode = 'default';
	private $state = 'closed';

	public function __construct( $rows = [], $mode = 'default', $state = 'open' ) {
		$this->rows = $rows;
		$this->mode = $mode;
		$this->state = $state;
	}

	public function render() {
		if( empty( $this->rows ) && ! is_panel_preview() ) {
			return;
		}

        $panel_object = get_the_panel();

		$uid        = uniqid( 'widget-accordion-' );
		$i          = 0;
        $faq_index  = 0;
		$faq_q_html = $this->mode === 'faq' ? sprintf( '<span class="widget-accordion-q">%s:</span>', __( 'Q', 'tribe' ) ) : '';
		$faq_a_html = $this->mode === 'faq' ? sprintf( '<span class="widget-accordion-a">%s:</span>', __( 'A', 'tribe' ) ) : '';
		$base_class = $this->mode === 'faq' ? 'widget-accordion__row widget-accordion--faq-mode' : 'widget-accordion__row';

		echo '<div class="widget-accordion" role="tablist" aria-multiselectable="true" data-js="widget-accordion" data-depth="0" data-name="questions" data-livetext>';

		foreach( $this->rows as $row ){

			$header_id  = sprintf( '%s-header-%s', $uid, $i );
			$content_id = sprintf( '%s-content-%s', $uid, $i );

			$row_classes = [
				$base_class,
				$this->state === 'open' && $i == 0 ? 'active' : '',
			];

			$content_classes = [
				'widget-accordion__content',
				$this->state === 'open' && $i == 0 ? 'first' : '',
			];

            $title   = ! empty( $row['question'] || is_panel_preview() ) ? $row['question'] : '';
            $content = ! empty( $row['answer'] || is_panel_preview() ) ? $row['answer'] : '';

			?>

			<article <?php echo Util::class_attribute( $row_classes ); ?>>
				<h3
					aria-controls="<?php esc_attr_e( $content_id ); ?>"
					aria-expanded="<?php esc_attr_e( $this->state == 'open' && $i == 0 ? 'true' : 'false' ); ?>"
					aria-selected="false"
					class="widget-accordion__header h5 clearfix"
					id="<?php esc_attr_e( $header_id ); ?>"
					role="tab"
					tabindex="0"
				>
					<i class="icon icon-angle-right"></i>
					<?php echo $faq_q_html; ?>
                    <span class="widget-accordion__header-inner"
                          data-depth="<?php echo $panel_object->get_depth(); ?>"
                          data-index="<?php echo $faq_index; ?>"
                          data-name="question"
                          data-livetext>
                        <?php echo esc_html( $title ); ?>
                    </span>
				</h3>
				<div
					aria-hidden="<?php esc_attr_e( $this->state == 'open' && $i == 0 ? 'false' : 'true' ); ?>"
					aria-labelledby="<?php esc_attr_e( $header_id ); ?>"
					<?php echo Util::class_attribute( $content_classes ); ?>
					id="<?php esc_attr_e( $content_id ); ?>"
					role="tabpanel"
				>
					<?php echo $faq_a_html; ?>
                    <div class="widget-accordion__content-inner t-content"
                         data-depth="<?php echo $panel_object->get_depth(); ?>"
                         data-index="<?php echo $faq_index; ?>"
                         data-name="answer"
                         data-livetext
                         data-autop="true">
                        <?php echo $content; ?>
                    </div>
				</div>
			</article>

			<?php $i++; }

		echo '</div>';
	}
}
