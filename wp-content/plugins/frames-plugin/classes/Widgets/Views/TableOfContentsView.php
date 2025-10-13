<?php
/**
 * Table of Contents View class file.
 *
 * @package Frames_Client
 */

namespace Frames_Client\Widgets\Views;

use Frames_Client\Widgets\Views\Base;

/**
 * TableOfContentsView class
 */
class TableOfContentsView extends Base {
	/**
	 * Default attributes
	 *
	 * @var array
	 */
	protected $defaults = array(
		'root_attr' => '',
		'content_selector'  => null,
		'header_selector'   => 'h6',
		'header_text'   => '',
		'offset'        => '',
		'list_type'     => '',
		'sublist_type'  => '',
		'show_heading'  => '',
		'use_accordion' => 'false',
		'accordion_is_open' => 'false',
		'icon'  => '',
	);

	/**
	 * Generate the HTML
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->settings;

		$useAccordion = 'true' == $settings['use_accordion'] ? true : false;
		$accordionIsOpen = 'true' == $settings['accordion_is_open'] ? true : false;

		$tocNavOptions = array(
			'fr-toc-content-selector' => isset( $settings['content_selector'] ) ? wp_kses_post( $settings['content_selector'] ) : '',
			'fr-toc-scroll-offset' => isset( $settings['offset'] ) ? wp_kses_post( $settings['offset'] ) : '',
			'fr-toc-list-type' => isset( $settings['list_type'] ) ? wp_kses_post( $settings['list_type'] ) : '',
			'fr-toc-sublist-type' => isset( $settings['sublist_type'] ) ? wp_kses_post( $settings['sublist_type'] ) : '',
			'fr-toc-accordion' => $useAccordion ? 'true' : 'false',
			'fr-toc-heading' => isset( $settings['show_heading'] ) ? wp_kses_post( $settings['show_heading'] ) : '',
		);

		$tocNavAttributesString = array();
		foreach ( $tocNavOptions as $key => $value ) {
			if ( '' != $value ) {
				$tocNavAttributesString[] = sprintf( 'data-%s=%s', $key, htmlspecialchars( $value, ENT_QUOTES, 'UTF-8' ) );
			} else {
				$tocNavAttributesString[] = sprintf( 'data-%s', $key );
			}
		}

		?>
		<div <?php echo $this->settings['root_attr']; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php if ( isset( $settings['content_selector'] ) && true === (bool) $settings['content_selector'] ) : ?>
				<nav class="fr-toc" aria-label="<?php echo isset( $settings['header_text'] ) ? wp_kses_post( $settings['header_text'] ) : ''; ?>"
					<?php echo esc_attr( implode( ' ', $tocNavAttributesString ) ); ?>
				>
					<?php if ( $useAccordion ) : ?>
						<button class="fr-toc__header"
							<?php if ( $accordionIsOpen ) : ?>
								aria-expanded="true"
							<?php else : ?>
								aria-expanded="false"
							<?php endif; ?>
						>
							<span class="fr-toc__heading"><?php echo isset( $settings['header_text'] ) ? wp_kses_post( $settings['header_text'] ) : ''; ?></span>
							<div class="fr-toc__icon">
								<?php if ( isset( $this->settings['icon'] ) ) : ?>
									<?php echo $this->settings['icon']; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php endif; ?>
							</div>
						</button>
					<?php else : ?>
						<div class="fr-toc__header">
							<span class="fr-toc__heading"><?php echo isset( $settings['header_text'] ) ? wp_kses_post( $settings['header_text'] ) : ''; ?></span>
						</div>
					<?php endif; ?>
					<div class="fr-toc__body">
						<div class="fr-toc__list-wrapper">
							<ol class="fr-toc__list">
								<li class="fr-toc__item fr-toc__list-item">
									<a class="fr-toc__link fr-toc__list-link"></a>
								</li>
							</ol>
						</div>

					</div>
				</nav>
			<?php else : ?>
				<p class="width--full text--l bg--neutral-ultra-light text--black center--all pad--xl">Choose a selector</p>
			<?php endif; ?>
		</div>
		<?php
	}
}
