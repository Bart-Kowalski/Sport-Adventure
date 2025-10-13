<?php
/**
 * Frames Welcome file.
 *
 * @package Frames_Client
 */

namespace Frames_Client\UI\Settings_Page;

use Frames_Client\Traits\Singleton;

/**
 * Frames Welcome class.
 */
class Welcome {

	use Singleton;

	/**
	 * Initialize the Welcome class.
	 *
	 * @return void
	 */
	public function init() {

	}

	/**
	 * Render the settings page.
	 *
	 * @return void
	 */
	public static function settings_page() {
		?>
			<h2>Here's some videos to get you started</h2>
			<iframe width="800" height="450" src="https://www.youtube-nocookie.com/embed/videoseries?si=AlUU4FubIFrFQGnD&amp;list=PL72Ci-T5YC904_CklSfaSrnS-dwXGBlh-" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
		<?php
	}
}
