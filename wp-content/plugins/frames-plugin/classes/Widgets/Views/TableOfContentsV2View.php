<?php
/**
 * Table of Contents View V2 class file.
 *
 * @package Frames
 */

namespace Frames_Client\Widgets\Views;

use Frames_Client\Widgets\Views\Base;
use Frames_Client\Helpers\Flag;
/**
 * TableOfContentsViewV2 class
 */
class TableOfContentsV2View extends Base {
	/**
	 * Default attributes
	 *
	 * @var array
	 */
	protected $defaults = array(
		'root_attr'     => '',
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

		$tocNavOptions = array();

		$frTocContentSelector   = $settings['content_selector'];
		$frTocScrollOffset      = $settings['offset'];
		$frTocListType          = $settings['list_type'];
		$frTocSublistType       = $settings['sublist_type'];
		$frTocAccordion         = 'true' == $settings['use_accordion'] ? true : false;
		$frTocAccordionIsOpen   = 'true' == $settings['accordion_is_open'] ? true : false;
		$frTocHeading           = $settings['show_heading'];
		$frTocHeaderSelector    = $settings['header_selector'];
		$frTocHeaderText        = $settings['header_text'];
		$frTocIcon              = $settings['icon'];
		$frTocUseBottomOffset   = $settings['use_bottom_offset'] ?? false;

		if ( Flag::is_on( 'FRAMES_ENABLE_USE_INTRODUCTION' ) ) {
			$frTocUseIntroduction   = $settings['use_introduction'] ?? false;
		}

		$tocNavOptions = array(
			'frTocContentSelector'  => $frTocContentSelector,
			'frTocScrollOffset'     => $frTocScrollOffset,
			'frTocListType'         => $frTocListType,
			'frTocSubListType'      => $frTocSublistType,
			'frTocAccordion'        => $frTocAccordion ? 'true' : 'false',
			'frTocHeading'          => $frTocHeading,
			'frTocUseBottomOffset'  => $frTocUseBottomOffset ? 'true' : 'false',
		);

		if ( Flag::is_on( 'FRAMES_ENABLE_USE_INTRODUCTION' ) ) {
			$tocNavOptions['frTocUseIntroduction'] = $frTocUseIntroduction ? 'true' : 'false';
		}

		if ( ! empty( $frTocHeaderSelector ) ) {
			$tocNavOptions['frTocHeaderSelector'] = $frTocHeaderSelector;
		}

		?>
		<div <?php echo $settings['root_attr']; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php if ( ! empty( $frTocContentSelector ) ) : ?>
				<nav class="fr-toc" aria-label="<?php echo esc_attr( $frTocHeaderText ); ?>" data-fr-toc-options="<?php echo esc_js( json_encode( $tocNavOptions ) ); ?>">
					<?php if ( $frTocAccordion ) : ?>
						<button class="fr-toc__header" aria-expanded="<?php echo $frTocAccordionIsOpen ? 'true' : 'false'; ?>">
							<span class="fr-toc__heading"><?php echo esc_html( $frTocHeaderText ); ?></span>
							<div class="fr-toc__icon">
								<?php if ( ! empty( $frTocIcon ) ) : ?>
									<?php echo $frTocIcon; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php endif; ?>
							</div>
						</button>
					<?php else : ?>
						<div class="fr-toc__header">
							<span class="fr-toc__heading"><?php echo esc_html( $frTocHeaderText ); ?></span>
						</div>
					<?php endif; ?>

					<div class="fr-toc__body">
						<div class="fr-toc__list-wrapper">
							<ol class="fr-toc__list">
								<li class="fr-toc__item fr-toc__list-item">
									<a class="fr-toc__link fr-toc__list-link" href="#"></a>
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

	/**
	 * Render in the Editor Only
	 *
	 * @return void
	 */
	public static function render_builder() {
		?>
		<script type="text/x-template" id="tmpl-bricks-element-fr-table-of-contents">
			<component>
				<nav
					class="fr-toc"
					v-if="settings.frTocContentSelector"
					:data-fr-toc-options="JSON.stringify({
						frTocContentSelector: settings.frTocContentSelector || '',
						frTocScrollOffset: settings.frTocScrollOffset || '',
						frTocListType: settings.frTocListType || '',
						frTocSubListType: settings.frTocSubListType || '',
						frTocAccordion: settings.frTocUseAccordion ? 'true' : 'false',
						frTocHeading: settings.frTocShowHeadingUpTo || '',
						frTocHeaderSelector: settings.frTocHeaderSelector || '',
						frTocHeaderText: settings.frTocHeaderText || '',
						frTocUseBottomOffset: settings.frTocUseBottomOffset ? 'true' : 'false'
					})"
				>
					<button class="fr-toc__header" :aria-expanded="settings.frTocAccordionIsOpen ? 'true' : 'false'" v-if="settings.frTocUseAccordion">
						<span class="fr-toc__heading">{{ settings.frTocHeaderText }}</span>
						<div class="fr-toc__icon" v-if="settings.frTocAccordionArrowIcon.icon">
							<i :class="settings.frTocAccordionArrowIcon.icon"></i>
						</div>
					</button>
					<div class="fr-toc__header" v-else>
						<span class="fr-toc__heading">{{ settings.frTocHeaderText }}</span>
						<div class="fr-toc__icon" v-if="settings.frTocAccordionArrowIcon.icon">
							<i :class="settings.frTocAccordionArrowIcon.icon"></i>
						</div>
					</div>
					<div class="fr-toc__body">
						<div class="fr-toc__list-wrapper">
							<ol class="fr-toc__list">
								<li class="fr-toc__item fr-toc__list-item">
									<a class="fr-toc__link fr-toc__list-link" href="#"></a>
								</li>
							</ol>
						</div>
					</div>
				</nav>
				<p class="width--full text--l bg--neutral-ultra-light text--black center--all pad--xl" v-else>no selector</p>
			</component>
		</script>
		<?php
	}
}
