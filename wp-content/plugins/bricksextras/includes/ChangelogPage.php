<?php

namespace BricksExtras;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ChangelogPage {

	public static function init() { ?>
		<h2>Changelog <?php if ( true !== BricksExtrasLicense::is_activated_license() ) {
			echo '<span> - Activate license to receive updates</span>';
		} ?></h2>

		<div class="changelog-entry">
			<h3>1.5.3
				<small>( Nov 08, 2024 )</small>
			</h3>
			<ul>
				<li>[Pro Countdown] - Added support for city/county timezone format.</li>
				<li>[Toggle Switch] - Fixed an issue from v1.5.2 preventing toggle to work in some cases.</li>
				<li>[General] - Added little 'x' next to all elements in builder to help distinguish between native elements when searching. Screenshot: https://d.pr/i/hqhtiS.</li>
			</ul>
		</div>

		<div class="changelog-entry">
			<h3>1.5.2
				<small>( Nov 05, 2024 )</small>
			</h3>
			<ul>
				<li>[Favorite Button] - New element to allow users to add/remove individual posts to favourite lists / wishlists.</li>
				<li>[Query Loop Extras] - New Favorites Query Loop, for displaying user's favorite posts.</li>
				<li>[Query Loop Extras] - New WP Menu Query for looping through WP menu items.</li>
				<li>[Query Loop Extras] - New Gallery Query for looping through images from a gallery (supports ACF Gallery field and Meta Box Image Advanced fields).</li>
				<li>[Dynamic Tags] - Dynamic tags added for menu item labels, URLs, descriptions and classes.</li>
				<li>[Conditions] - New "current taxonomy term has parent" condition.</li>
				<li>[Site breadcrumbs] - Added an option to choose which taxonomy to display for each CPT.</li>
				<li>[WooCommerce Conditions] - New condition for cart total excluding shipping cost.</li>
				<li>[Star Rating] - Added an option to change color to show rating, rather than different icons.</li>
				<li>[Pro Countdown] - New action "Count Up" for counting back up once the end date is in the past.</li>
				<li>[General] - Better RTL support across elements.</li>
				<li>[Pro Slider] - Added support for mobile-first breakpoints.</li>
				<li>[Pro Slider] - Add support for using Bricks' infinite scroll on slide element query loop to add new slides dynamically as the slider moves.</li>
				<li>[Pro Slider Gallery] - Added support for captions and thumbnails when linked with for Bricks' Lightbox.</li>
				<li>[General Conditions] - "Is parent" condition now supports hierarchical CPTs.</li>
				<li>[Header Search] - Now supports use with Bricks' live search and filter elements nested in search overlay.</li>
				<li>[Dynamic Chart] - New option to show data labels inside charts.</li>
				<li>[Dynamic Table] - Can now sort columns by date.</li>
				<li>[Dynamic Table] - Can now remove sorting per column.</li>
				<li>[Interactive Cursor] - Can now change cursor colors when moving over elements.</li>
				<li>[Popover] - Added "append to footer" to allow popover to always be above all other elements.</li>
				<li>[Member Conditions] - Added "active" or "cancelled" status to Wishlist Member conditions.</li>
				<li>[Member Conditions] - Added "active", "cancelled", "expired", "pending" status to RCP conditions.</li>
				<li>[Pro Accordion] - Improved behavior when nesting accordions inside accordions.</li>
				<li>[Slide Menu] - Added option to have slide menu open at current active page.</li>
				<li>[Social Share] - Added option to have links open in the same tab.</li>
				<li>[Social Share] - Fixed the issue where custom URL would output as NULL.</li>
				<li>[Read More] - Fixed the issue where readmore wouldn't re-open inside modal.</li>
				<li>[Read More] - Fixed the issue where readmore could cause page scroll.</li>
				<li>[Lottie] - Fixed the issue where URL was not accepting dynamic data.</li>
				<li>[Header Extras / Rows] - Fixed the issue where Bricks' mega menu dropdown could have wrong position when opened if inside hidden header row.</li>
				<li>[WooCommerce Conditions] - Fixed the issue where "Current Product in cart" set to 'false' wouldn't work.</li>
				<li>[General] - Amended some default CSS to prevent ACSS from adding gaps inside nested element structures.</li>
				<li>[Read More] - Prevent ACSS from affecting height transition with interpolate-size.</li>
				<li>[WooCommerce Conditions] - Added "Product in cart has a coupon applied" condition. Enables you to render elements if the selected product is in the cart and has a valid coupon applied.</li>
			</ul>
			
			<h3>1.5.1
				<small>( Apr 22, 2024 )</small>
			</h3>
			<ul>
				<li>[Conditions] - Added a new "Current Taxonomy Term Has Child" condition for use on taxonomy archives.</li>
				<li>[Dynamic Lightbox] - Now supports using Cloudflare CDN image delivery URLs when using gallery mode.</li>
				<li>[Popovers] - Added two new "action" interactions to allow for controlling via interaction settings.</li>
				<li>[Pro Accordion] - Now supports filtering individual accordion items using WPGridBuilder facets.</li>
				<li>[Toggle Switch] - Can now be used inside query loops.</li>
				<li>[Pro Slider] - Adjusted the default "wheel sleep" setting to 700ms when using wheel navigation, preventing skipping multiple slides.</li>
				<li>[WPGB Styler] - Now uses min-height rather than height for the select facet, preventing overflow when using combobox and multiselect.</li>
				<li>[Pro Slider Control] - Fixed an issue where the progress bar value did not match the slide index.</li>
				<li>[General] - Added more control over ARIA labeling and roles across multiple elements.</li>
			</ul>
			
			<h3>1.5.0
				<small>( Apr 18, 2024 )</small>
			</h3>
			<ul>
				<li>[Dynamic Tags] - Improved reading time logic to support use with additional languages.</li>
				<li>[Header Search] - Now allows adding text inside the open toggle button.</li>
				<li>[Image Hotspots] - Added an option to replace icons with custom images for each marker individually.</li>
				<li>[Pro Accordion] - Moved the header tag outside the accordion header by default.</li>
				<li>[Pro OffCanvas] - Added an option to change inner content HTML tag.</li>
				<li>[Pro Slider] - Fixed an issue where Splide was adding `role=group` to slides that were list items.</li>
				<li>[General] - Enhanced keyboard navigation across some elements.</li>
			</ul>
			
			<h3>1.4.9
				<small>( Apr 16, 2024 )</small>
			</h3>
			<ul>
				<li>Social Share - Added more print options, including the ability to print specific parts of the page.</li>
				<li>Social Share - Added an option to customize the subject/body text of email.</li>
				<li>Copy to Clipboard - Added support for copying user-added content from inputs/textareas.</li>
				<li>Table of Contents - Conditional display - choose a minimum number of headings found in content to display the table.</li>
				<li>Pro Tabs - Improved keyboard accessibility.</li>
				<li>Pro Tabs - Added a "tab orientation" setting to allow for changing left/right key navigation to up/down if building a vertical layout.</li>
				<li>Image Hotspots - Added a new interaction option for selected markers.</li>
				<li>Popover - Added new interaction options for popovers opening/closing.</li>
				<li>Image Hotspots - Added an option to toggle moving user focus into popover content when a marker is selected via keyboard.</li>
				<li>Toggle Switch - Labels now support HTML.</li>
				<li>WPGB Facet styler - Added a "gap" control for the pagination facet.</li>
				<li>Dynamic Table - Column headings now support using custom functions with the echo tag.</li>
				<li>Pro Slider - Fixed an issue with conditional slider where it could also hide slider controls from nearby sliders.</li>
			</ul>
			
			<h3>1.4.8
				<small>( Mar 21, 2024 )</small>
			</h3>
			<ul>
				<li>[Popover] - Now supports being used inside query loops using Bricks' infinite scroll.</li>
				<li>[Social Share] - Added Print button as new option.</li>
				<li>[Hotspots] - Exposed the instance so can be controlled via JS (see dev docs).</li>
				<li>[Hotspots] - Can be now closed with data-x-close attribute inside hotspot content.</li>
				<li>[Pro Slider Gallery] - Fixed the issue with Splide lazy loading sometimes causing images to not display.</li>
				<li>[Pro Slider] - Adjusted the default arrow CSS to prevent user uploaded SVGs from being targeted.</li>
				<li>[Dynamic Table] - Fixed the issue where table wouldn't complete loading if empty attributes were added to columns.</li>
			</ul>
			
			<h3>1.4.7
				<small>( Mar 18, 2024 )</small>
			</h3>
			<ul>
				<li>[Modal / Modal Template] - Added the ability to show modal only once per session.</li>
				<li>[Slide Menu] - Can now use custom icons for the sub menu dropdown button and control over transform/colors.</li>
				<li>[Read More] - New interactions added to toggle/collapse/expand via Bricks' interactions.</li>
				<li>[Read More] - Fixed the issue with read more links no longer collapsing when used with Bricks' Load more interaction.</li>
				<li>[Pro Tabs] - New interactions added to open any tab (or accordion) at the chosen index.</li>
				<li>[Nestable Table] - Added sticky table header option.</li>
				<li>[Pro Slider] - Performance - icon library no longer loaded by default for prev/next icons.</li>
				<li>[Pro Slider Control] - Performance - icon library no longer loaded by default for icons.</li>
				<li>[Nestable Table] - Fixed the issue where labels weren't updating after changing.</li>
				<li>[General] - JS Performance improvements on frontend.</li>
			</ul>
			
			<h3>1.4.6
				<small>( Mar 15, 2024 )</small>
			</h3>
			<ul>
				<li>[Lottie] - Fixed JS error in builder when on hover mode.</li>
				<li>[Nestable Table] - Added more style settings & better default styles.</li>
			</ul>
			
			<h3>1.4.5
				<small>( Mar 15, 2024 )</small>
			</h3>
			<ul>
				<li>[Nestable Table] - New element for creating nestable tables. Supports Bricks' AJAX filters, search and pagination, column stacking for mobile and using any elements inside the table.</li>
			</ul>
			
			<h3>1.4.4
				<small>( Mar 13, 2024 )</small>
			</h3>
			<ul>
				<li>[General] - Security improvement.</li>
			</ul>
			
			<h3>1.4.3
				<small>( Mar 12, 2024 )</small>
			</h3>
			<ul>
				<li>[Star Ratings] - Fixed issue with icon size not working with SVGs.</	li>
				<li>[Lottie] - Addressed issue with multiple lottie elements not being able to be used when inside a GutenBricks template.</li>
				<li>[General] - Security patch update.</li>
			</ul>
			
			<h3>1.4.2
				<small>( Feb 27, 2024 )</small>
			</h3>
			
			<ul>
				<li>[Header Extras] - Overlay/sticky header now compatible with sites/pages using Bricks' "boxed" layout option.</li>
				<li>[Dynamic Table] - Fixed the issue where 'echo' dynamic tag wasn't always outputting correctly inside of cells.</li>
			</ul>
			
			<h3>1.4.1
				<small>( Feb 15, 2024 )</small>
			</h3>
			
			<ul>
				<li> [General] - Added support for elements to be used inside Bricks' new AJAX search results, AJAX filters and AJAX popup content.</li>
				<li> [Element Interactions] - New "triggers" added in Bricks interactions options for many elements.</li>
				<li> [Pro Accordion] - New option for toggling accordion behavior across breakpoints.</li>
				<li> [Countdown] - Now includes fixed end times/date via dynamic data (renamed to Pro Countdown).</li>
				<li> [Pro Tabs] - Can now open tabs/accordion with URL parameters.</li>
				<li> [General Conditions] - Added "post ancestor" & "post type" conditions.</li>
				<li> [Dynamic Table] - New option for adding custom attributes to cells (for custom styling per column).</li>
				<li> [WS Form] - New styling options for international phone inputs and country dropdown.</li>
				<li> [Site Breadcrumbs] - Added PHP filter for customizing/adding to breadcrumb trail output.</li>
				<li> [General] - Added better support for JetSmartFilters facets.</li>
				<li> [General] - Better support for WPML auto-translate across elements.</li>
				<li> [Interactive Options] - New option to change glare color.</li>
				<li> [Pro Slider Gallery] - Added option to change animation type for Bricks' Lightbox.</li>
				<li> [Pro Slider Gallery] - Fixed issue where offset/randomize could cause error if no images found in gallery.</li>
				<li> [Pro Slider Gallery] - Fixed issue where Bricks' lightbox would group all images if slider was repeated inside query loop.</li>
				<li> [Social Share] - Fixed "links not crawlable" warning if using Mastodon link.</li>
				<li> [Developer] - Instances for dynamic table / dynamic chart now exposed, now can be used inside filtered content.</li>
				<li> [Developer] - Lost of helper functions added to allow control elements dynamically via interactions (see interactions tab in docs).</li>
			</ul>
			
			<h3>1.4.0
				<small>( Dec 1, 2023 )</small>
			</h3>
			
			<ul>
				<li>[Pro Slider Control] - Added the ability to display data from prev/next or current slides.</li>
				<li>[Breadcrumbs] - Added an option to remove current page/post title from breadcrumbs.</li>
				<li>[Dynamic Lightbox] - Added an option to reposition the captions directly underneath the lightbox content.</li>
				<li>[Pro Offcanvas] - Focus moving back to trigger when Offcanvas closed is now optional.</li>
				<li>[Pro Tabs] - Now supports multiple "Read more" elements inside tab content.</li>
				<li>[WPGridBuilder Facets] - Added options to align radio/checkboxes horizontally.</li>
				<li>[Copy to Clipboard] - Added option to strip HTML tags from copied content.</li>
				<li>[WS Forms] - More typography settings for general text/headings inside the form.</li>
				<li>[Conditions] - Added != operator to "Author has CPT" entry condition.</li>
				<li>[General] - Elements/features will now continue to function if the license is removed from client sites (needs to be active to receive updates).</li>
			</ul>
			
			<h3>1.3.9
				<small>( Nov 17, 2023 )</small>
			</h3>
			
			<ul>
				<li>[Pro Slider] - Now supports multiple synced sliders.</li>
				<li>[Pro Slider] - New option for allowing independent slider scrolling when using syncing.</li>
				<li>[Fluent Forms] - Submit button styling will now override Fluent Forms new default inline styles.</li>
				<li>[Fluent Forms] - Reworked the checkbox/radio styling for more control.</li>
				<li>[Pro Tabs] - Addressed an issue causing first item to be open in mobile accordion.</li>
				<li>[General] - Fixed stdClass() issue.</li>
			</ul>
			
			<h3>1.3.8
				<small>( Nov 09, 2023 )</small>
			</h3>
			
			<ul>
				<li>[Slide Menu] - Fixed WordPress 6.4 preventing slide menu from opening in some cases.</li>
				<li>[General] - Fixed CSS from v1.3.7 causing a few ACSS variables to not be applied.</li>
			</ul>
			
			<h3>1.3.7
				<small>( Nov 07, 2023 )</small>
			</h3>
			
			<ul>
				<li>[Copy to Clipboard] - New nestable button element for copying text from elements, or from dynamic data.</li>
				<li>[Dynamic Lightbox] - Added support for captions (for both gallery and manual link options).</li>
				<li>[Pro Slider] - Added gradient fade effect option for 'loop' sliders.</li>
				<li>[Pro Slider] - Defaults changed for new sliders - no default background styles added to slide element.</li>
				<li>[Slide Menu] - Accessibility improvement, will now close with ESC key or clicking outside the menu.</li>
				<li>[Popover] - Accessibility improvement, Open a popover via keyboard will close the previously opened ones.</li>
				<li>[Popover] - Accessibility improvement, focused popover will now close with ESC key.</li>
				<li>[Social Share] - Custom URL option now accepts dynamic tags.</li>
				<li>[Pro Accordion] - Support added for having pro accordions inside of pro accordions.</li>
				<li>[WS Forms] - Can now style the 'success messages' that appears outside of the form itself.</li>
				<li>[WPGB styler] - New style options for the selection facet buttons.</li>
				<li>[Content Timeline] - Option added to alternate text-align.</li>
				<li>[Conditions] - Fixed the issue with some conditions where zero value would still show element.</li>
				<li>[Pro Tabs] - Fixed z-index issue with animated tabs showing behind tab background color.</li>
				<li>[Site Breadcrumbs] - Fixed issue where the 'name' attribute wasn't being output on the 'blog' page.</li>
			</ul>
			
			<h3>1.3.6
				<small>( Sep 20, 2023 )</small>
			</h3>
			
			<ul>
				<li>[General] Support for Bricks' infinite scroll + AJAX pagination added to all interactive elements.</li>
				<li>[WPGB Facet Styler] Added more style controls for dropdowns (multiple selection).</li>
				<li>[Star Rating] Fixed an issue from v1.3.5 with some dynamic tags not working for star values.</li>
				<li>[Pro Slider Gallery] Added "offset" option for images.</li>
			</ul>

			<h3>1.3.5
				<small>( Sep 19, 2023 )</small>
			</h3>
			
			<ul>
				<li>[Pro Tabs] New nestable element for adding accessible tabs with mobile accordion.</li>
				<li>[WPGB Facet Styler] - A new element based on WP Grid Builder's facet element for easier and comprehensive styling.</li>
				<li>[Popover] Added an option for popover to follow cursor.</li>
				<li>[Popover] Added interaction delay option.</li>
				<li>[Popover] Improvement on how the popover moves when moving between multiple </li>triggers.</li>
				<li>[Pro Slider Gallery] Added options to add max no. of images and randomize order.</li>
				<li>[Pro Slider] Accessibility improvement - keyboard setting default changed to "focused".</li>
				<li>[General Conditions] "Published during last.." condition can now be used for any post type.</li>
				<li>[WooCommerce Conditions] Added "Current product in cart" condition.</li>
				<li>[Table of Contents] - Added JS event that fires conditionally if table of contents is empty.</li>
				<li>[General] Performance improvement - reduced database queries on front end.</li>
				<li>[Header Extras] - Fix issue where sticky header wouldn't be applied when using across multiple header templates.</li>
				<li>[Star Rating] Addressed PHP deprecation notice for PHP 8.1+.</li>
			</ul>
		
		<h3>1.3.4
				<small>( Aug 30, 2023 )</small>
			</h3>
			<ul>
				<li>[General Conditions] - Added "Date field value", "Datetime field value", "Has child category", "TranslatePress language", "Author has CPT entry" and "At least 1 entry exists (for the selected CPT)" conditions.</li>
				<li>[WooCommerce Conditions] - Added "Product allows backorders", "Product on backorder", "Product upsell count", "Product Cross-sell count", "Product has category" and "Product has tag" conditions.</li>
				<li>[Pro Slider] - Added "intersection" options to autoplay & autoscroll, for starting only when slider comes into view.</li>
				<li>[Site Breadcrumbs] - Added an option to include CPT archive link for CPTs.</li>
				<li>[Social Share] - Now can be used to share taxonomy archive URLs.</li>
				<li>[Read More] - Improved button width setting to allow any units and variables.</li>
				<li>[Header Extras] - Sticky header CSS removed from pages where it is disabled.</li>
				<li>[Member Conditions] - Fixed an issue with EDD subscriptions product dropdown not displaying.</li>
				<li>[General Conditions] - Fixed an issue that could cause JetEngine custom fields not to display on archive pages.</li>
			</ul>

			<h3>1.3.3
				<small>( Aug 02, 2023 )</small>
			</h3>
			<ul>
				<li>[WooCommerce Conditions] - New conditions based around products/cart/users.</li>
				<li>[General Conditons] - New general conditions (now 24 in total) for various common use-cases within WP. Added these: Body classes, Current day of month, Current month, Current year, Has custom excerpt, Has post content, Language (Polylang), Loop item number, Published during the last.</li>
				<li>[Membership Conditions] - Added Paid Membership Pro.</li>
				<li>[Membership Conditions] - Added WooCommerce Subscriptions.</li>
				<li>[Pro Accordion] - Improved the scroll-to logic to make it easier to have reader-friendly hash links when using loops.</li>
				<li>[General] - Improvement on Lenis support, to make sure popup type elements are still scrollable if they contain content larger than the viewport height.</li>
			</ul>

			<h3>1.3.2
				<small>( Jul 31, 2023 )</small>
			</h3>
			<ul>
				<li>[Membership Conditions] - Added SureMembers (Access group) condition.</li>
				<li>[General Conditions] - Added "Page type", "Page parent" and "WPML language" conditions.</li>
				<li>[Modal/OffCanvas/Lightbox] - Added support for stopping scroll when opened if using Lenis smooth scrolling (like with BricksForge).</li>
				<li>[Pro Slider] - Fixed an issue with clip direction not being applied.</li>
				<li>[General] - Fixed PHP warning for PHP 8.2.</li>
			</ul>
			
			<h3>1.3.1
				<small>( Jul 27, 2023 )</small>
			</h3>
			<ul>
				<li>[Conditions] - Fixed an issue where some conditions weren't being applied.</li>
				<li>[Conditions] - Added WishList Member condition.</li>
			</ul>
			
			<h3>1.3.0
				<small>( Jul 27, 2023 )</small>
			</h3>
			<ul>
				<li>[Conditions] - Added the first batch of extra conditions to Bricks for a variety of use cases + integrations with membership plugins.</li>
				<li>[Dynamic Lightbox] - Added support for adding custom prev/next buttons inside lightbox content area when using nest elements and grouping.</li>
				<li>[Site Breadcrumbs] - Added truncate option to add post title character limit.</li>
				<li>[Site Breadcrumbs] - Added an option to use icon as home link.</li>
				<li>[Pro Slider] - Added better support for adding sliders inside sliders.</li>
				<li>[Content Timeline] - Can now change the HTML tag on the inner container (ideal for ul > li).</li>
				<li>[Dynamic Tags] - Added "x_parent_loop_index" for getting index from parent / grand parent query loops.</li>
				<li>[Pro Slider] - Fixed an issue where image elements wouldn't always be picked up by Bricks lazy load if autoscroll was enabled.</li>
				<li>[Dynamic Chart] - Fixed issue where tooltips wouldn't pick up values from doughnut type charts.</li>
				<li>[Dynamic Chart] - Updated from v3.x to v4.x of chartJS.</li>
				<li>[General] - Fixed an issue where Piotnet Grid's AJAX search facet wouldn't work.</li>
				<li>[General] - Improved how element CSS is being added to allow for elements to be used inside post content area or inside Bricks' popup templates.</li>
			</ul>

			<h3>1.2.9
				<small>( Jun 16, 2023 )</small>
			</h3>
			<ul>
				<li>[General] - Compatibility for Bricks v1.8.1+.</li>
				<li>[Pro Accordion] - Improve default structure (heading instead of text element for accordion title).</li>
				<li>[Pro Accordion] - Improve how Schema markup is added to allow for use with Perfmatters' "delay until user interaction" setting.</li>
				<li>[Fluent Form] - Added checkbox typography settings.</li>
				<li>[Fluent Form] - New style settings for form wrapper.</li>
			</ul>
			
			<h3>1.2.8
				<small>( Jun 02, 2023 )</small>
			</h3>
			<ul>
				<li>[Pro Slider] - Fixed the issue that was causing the new autoplay control to not be correctly synced with the slider.</li>
			</ul>
			
			<h3>1.2.7
				<small>( Jun 02, 2023 )</small>
			</h3>
			<ul>
				<li>[WS Form] - New element for adding/styling individual WS Form forms.</li>
				<li>[Dynamic Lightbox] - No longer restricted by styling by class only.</li>
				<li>[Evergreen Countdown] - Added "redirect to URL" to actions.</li>
				<li>[Pro Slider] - Added "clip direction" option for creating one-way overflowing sliders.</li>
				<li>[Pro Slider Control] - New control type - "Autoplay Progress" displaying interval progress.</li>
				<li>[Pro Slider Control] - New control type - "Slide content" for displaying any dynamic slide content outside of the slider.</li>
				<li>[Pro Slider] - Added option to change easing on the slider.</li>
				<li>[Pro Slider] - Added support for Bricks' v.1.8+ lazy loading change.</li>
				<li>[Pro Slider] - Nav aria-labels now added in at the PHP level to prevent Google Page Speed not finding them.</li>
				<li>[Pro Slider Gallery] - Added option to group images when using inside Bricks' Lightbox.</li>
				<li>[Pro Slider] - Now loads the Splide CSS earlier on pages using Pro Slider to minimise FOUC.</li>
				<li>[Dynamic Table] - Added new option for stacked columns to move labels onto their own line.</li>
				<li>[OffCanvas] - Renamed to "Pro OffCanvas" in builder to prevent confusion with  Bricks' v.1.8+ element of same name.</li>
				<li>[Content Timeline] - Added support for filtering timeline items with WP Grid Builder.</li>
				<li>[Dynamic Chart] - (Dev) - Made chart Instance accessible for changing options/data dynamically using JS (see developer docs).</li>
				<li>[Pro Slider] - Fixed the width issue in Safari when combining gallery links and autoWidth setting.</li>
				<li>[interactive Cursor] - Fixed the issue where cursor would remain expanded after back to top was hidden.</li>
			</ul>
			
			<h3>1.2.6
				<small>( May 10, 2023 )</small>
			</h3>
			<ul>
				<li>[Evergreen Countdown] - New element for evergreen countdowns.</li>
				<li>[Dynamic Chart] - New query loop option for dynamically adding new data points.</li>
				<li>[Header Search] - Added "expand" search type.</li>
				<li>[Pro Accordion] - Added an option to auto-add FAQ schema to all accordion items.</li>
				<li>[Notification Bar] - The "show again until user clicks dismiss" now allows for showing again after a set time.</li>
				<li>[Pro Slider Gallery] - Added an option to change the size of image when adding links (for use with Dynamic Lightbox).</li>
				<li>[Hotspots] - Markers can now be links.</li>
				<li>[Slide Menu] - Added customizable aria-label for dropdown arrows.</li>
				<li>[Table of Contents] - Added "conditional display" option to remove table if no headings found.</li>
				<li>[X-Ray] - Moved position of icon and can now be enabled/disabled from plugin settings page.</li>
				<li>[Dynamic Lightbox] - Plyr assets no longer being fetched from the CDN.</li>
				<li>[General] - Added support for using elements inside content filtered by Piotnet Grid's facets.</li>
				<li>[General] - Some minor UI changes ready for Bricks v1.8.</li>
				<li>[Lottie] - Fixed an issue with "hover" trigger where child elements would restart animation.</li>
				<li>[Pro Slider] - Fixed an issue where styling wasn't correct inside builder if was inside Bricks' template element.</li>
				<li>[Table of Contents] - Fixed an issue where adding non-numeric values in offset wasn't accepted.</li>
				<li>[Modal] - Fixed an issue where very bottom of modal sometimes wasn't visible on mobile devices.</li>
			</ul>
			
			<h3>1.2.5
				<small>( Apr 12, 2023 )</small>
			</h3>
			<ul>
				<li>[Adjacent Posts] - New Query Loop extension for building custom prev/next post layout.</li>
				<li>[Related Posts] - New Query Loop extension for displaying related posts within specific taxonomies.</li>
				<li>[General] - Support added for JetSmartFilters for using the elements inside loops being filtered (Modal, Lightbox, OffCanvas, Pro Accordion, Social Share, Read More, Pro Slider & Popover).</li>
				<li>[Dynamic Lightbox] - Now supports being inside nested query loops.</li>
				<li>[Pro Slider] - Now supports being inside nested query loops (including syncing and controls).</li>
				<li>[Pro Slider] - Added "adaptive height" option to allow slider to adapt to current active slide height.</li>
				<li>[Pro Slider] - Added support for re-triggering "big" inner animations - fadeInUpBig, fadeInDownBig etc. from Bricks' interactions.</li>
				<li>[Pro Slider Gallery] - Gallery images can now be linked to Bricks v1.7.2+ Lightbox.</li>
				<li>[Site breadcrumbs] - Added more controls over output - excluding specific post/product categories or disabling categories.</li>
				<li>[Dynamic Chart] - New horizontal option for bar/line charts. (labels up the Y-axis, values along the X-axis).</li>
				<li>[Dynamic Chart] - Now allows for adding units before or after the values.</li>
				<li>[Dynamic Chart] - Added option to add tooltips to charts.</li>
				<li>[Dynamic Chart] - Fixed an issue where using large 7+ digit numbers (that resemble telephone numbers) as values wouldn't plot correctly if viewed on iOS.</li>
				<li>[Header Extras] - Fixed Header Extras tab sometimes not appearing inside page settings.</li>
				<li>[Header Search] - Fixed an issue with icons not changing size/colors if using custom SVGs.</li>
				<li>[Dynamic Table] - Fixed an issue with "if no rows found" text not being customizable when pagination is disabled.</li>
			</ul>
			
			<h3>1.2.4
				<small>( Mar 09, 2023 )</small>
			</h3>
			<ul>
				<li>[Social Share] - New element for adding social share buttons.</li>
				<li>[Site Breadcrumbs] - New element for adding site-wide breadcrumbs.</li>
				<li>[Modal] - Now supports being inside nested query loops (up to 3 levels deep).</li>
				<li>[OffCanvas] - Now supports being inside nested query loops (up to 3 levels deep)</li>
				<li>[OffCanvas] - Added an option to fade in in addition to slide in for the Transition type.</li>
				<li>[Pro Slider] - Added Splide lazy loading option.</li>
				<li>[Pro Slider Gallery] - Added a setting to enable slider lazy loading for all gallery images (either Bricks or Splide lazy load).</li>
				<li>[Pro Slider Gallery] - Added a setting to enable/disable SRCSET on images.</li>
				<li>[General] - Added out-of-the-box support for WP Grid Builder's facets. Modal, OffCanvas, Pro Accordion, Pro Slider, Dynamic Lightbox, Social Share, Read More, Popover (for being used inside query loops when building filtered).</li>
				<li>[Tilt Effect] - Scale now accepts decimal values ( 1.5 = 150%).</li>
				<li>[Pro Accordion] - Added an option to change the HTML tag on the wrapper (for doing ul>li etc).</li>
				<li>[Dynamic Table] - Added an option to set a fixed height for a scrollable table (header & footer remain fixed).</li>
				<li>[Dynamic Lightbox] - Added an option to change UI color for video player in manual link mode.</li>
				<li>[Dynamic Lightbox] - Can now use class added to headings or images directly for link selectors (before you may have needed to use `.my-class a` due to Bricks not adding the classes to the links, this is done automatically now).</li>
				<li>[Content Timeline] - Reworked the logic for the line, for more accurate positioning.</li>
				<li>[Content Timeline] - Added a `—x-timeline-progress` CSS variable that changes value from 1 - 100 as the timeline progresses (can be used to change styles on any inner elements based on timeline position).</li>
				<li>[Interactive Cursor] - Added an option to change border-radius of ball/trails.</li>
				<li>[X-Ray] - Slightly darker outlines by default for better visibility (users can change `—x-xray-color` CSS variable from Bricks CSS settings if wishing to change color).</li>
				<li>[Modal] - Fixed the issue with close button not triggering if multiple modals are open simultaneously.</li>
				<li>[Slide Menu] - Fixed the issue of not correctly outputting menu when populating `menu slug` using dynamic tags.</li>
			</ul>
			
			<h3>1.2.3
				<small>( Jan 26, 2023 )</small>
			</h3>
			<ul>
				<li>[Read More] - Accessibility improvement (aria-expanded label).</li>
				<li>[Dynamic Lightbox] - Fixed specificity issue causing width to appear incorrectly in builder.</li>
				<li>[General] - Fixed an issue with Bricks v1.6.2 where some style settings were hidden if class selected.</li>
			</ul>

			<h3>1.2.2
				<small>( Jan 23, 2023 )</small>
			</h3>
			<ul>
				<li>[Content Timeline] - New element for creating content timeline layouts.</li>
				<li>[X-Ray Mode] - In-builder option for quickly viewing layout structures visually.</li>
				<li>[Dynamic Lightbox] - Added "gallery" option to lightbox content for pulling in galleries into single lightbox.</li>
				<li>[Dynamic Lightbox] - Added better support for WPGB infinite scroll (see support FAQ in docs).</li>
				<li>[Image hotspots] - Added an option to add custom alt text to image.</li>
				<li>[Dynamic Table] - No longer outputs "NaN" if column set to numbers and cell has no value.</li>
				<li>[Dynamic Lightbox] - Fixed the issue with UI styles styling both close buttons and navigation together.</li>
				<li>[Fluent Form] - Updated some selectors to match Fluent Forms flex-box column gaps.</li>
				<li>[Header Extras] - Slight CSS change to avoid small jump if header set to be sticky immediately after scrolling.</li>
				<li>[General] - Removed various default CSS settings for better support for mobile-first.</li>
			</ul>
			
			<h3>1.2.1
				<small>( Jan 04, 2023 )</small>
			</h3>
			<ul>
				<li>[Pro Slider / Gallery] - Added an option to change the slide list HTML tag (for changing to <ul> lists etc. if needed).</li>
				<li>[Interactive Cursor] - Fixed the issue where cursor wasn't visible on some desktop devices that have touchscreen.</li>
				<li>[Header Extras] - Fixed breakpoint issue causing warning with some older versions of PHP.</li>
			</ul>
			
			<h3>1.2.0
				<small>( Jan 03, 2023 )</small>
			</h3>
			<ul>
				<li>[Header Extras] - New features added to Bricks' header template - (overlay headers, sticky on scroll, hide header after scrolling X added to any breakpoint globally, or per page/template).</li>
				<li>[Header Row] - New element for more easily building headers in bricks (supports conditionally appearing in overlay or sticky headers, change styles when sticky etc).</li>
				<li>[Pro Slider] - Added an option to change all aria-labels for pagination, nav arrows etc.</li>
				<li>[Pro Slider] - Added a "conditional slider" option to disable slider if not enough slides to fill the slider viewport.</li>
				<li>[Pro Slider] - Added an option to set horizontal flex alignment if there are not enough slides.</li>
				<li>[Pro Slider] - Added an option to delay the first staggered animation.</li>
				<li>[Pro Slider] - "Focus" setting can now be changed per breakpoint.</li>
				<li>[Toggle Switch] - Query loop can now be used to populate "multiple labels".</li>
				<li>[Dynamic Table] - New "Stackable table" option for stacking columns on mobile.</li>
				<li>[Burger Trigger] - Now possible to hide the button text at different breakpoints.</li>
				<li>[Interactive Cursor] - Cursor will now automatically shrink if moving position over an iFrame.</li>
				<li>[Interactive Cursor] - Fixed the issue with cursor not reacting to readmore/less buttons.</li>
				<li>[General] - Fixed compatibility issue with OffCanvas/Modal template dropdown with Bricks v1.6+.</li>
				<li>[Dynamic Lightbox] - Fixed an issue with overflow resetting to "auto" on mobile.</li>
				<li>[Table of Contents] - Fixed an issue where collapse depth wouldn't apply.</li>
			</ul>
			
			<h3>1.1.9
				<small>( Nov 23, 2022 )</small>
			</h3>
			<ul>
				<li>[Pro Accordion] - New element for building nestable and accessible accordions.</li>
				<li>[Table of Contents] - Now supports different open/close positions at different screen widths.</li>
				<li>[Pro Slider] - Added support for Bricks v.1.5.6 interactions (for triggering fadein type animations on elements inside slides).</li>
				<li>[Pro Slider] - Added overflow setting to allow slides to go outside of slide track.</li>
				<li>[Dynamic Table] - Added an option to change "no records found" text if there are no rows.</li>
				<li>[Toggle Switch] - Added an option to disable labels and just use the toggle switch.</li>
			</ul>
			
			<h3>1.1.8
				<small>( Nov 15, 2022 )</small>
			</h3>
			<ul>
				<li>[Toggle Switch] - Added label layout controls to allow for stacking labels on mobile.</li>
				<li>[Toggle Switch / Switcher] - Accessibility improvement - tablist/tabpanel & ARIA labels added when using multiple labels like tabs.</li>
				<li>[Back to Top] - Fixed BricksProps CSS overriding the button SVG.</li>
				<li>[Back to Top] - Fixed background circle being slightly visible over progress when on darker backgrounds.</li>
				<li>[Read More] - Fixed read more not always opening when inside query loops.</li>
				<li>[Dynamic Table] - Fixed an issue with columns not being resizable.</li>
			</ul>
			
			<h3>1.1.7
				<small>( Nov 10, 2022 )</small>
			</h3>
			<ul>
				<li>[Back to Top] - New element for creating animated back to top buttons.</li>
				<li>[Interactive Cursor] - New element for adding cursors that interact with other elements.</li>
				<li>[Popovers/Tooltips] - New element for adding popovers or tooltips to elements.</li>
				<li>[Modal] - Modals using "click" as trigger can now be used inside query loops.</li>
				<li>[Interactive features] - Now added to most native elements.</li>
				<li>[Fluent Form] - Added "progress steps" to progress bar style controls.</li>
				<li>[Lightbox] - Fixed the issue where CSS grid inside lightbox content wouldn't display correctly inside the builder.</li>
			</ul>
			
			<h3>1.1.6
				<small>( Oct 30, 2022 )</small>
			</h3>
			<ul>
				<li>[Pro Slider] Fixed a bug with the counter being blank unless navigated.</li>
			</ul>
			
			<h3>1.1.5
				<small>( Oct 28, 2022 )</small>
			</h3>
			<ul>
				<li>[Pro Slider Control] - Added an option to create custom navigation arrows/buttons for Slider.</li>
				<li>[Pro Slider Gallery] - Added an ability to link gallery images to Bricks' Lightbox.</li>
				<li>[Dynamic Table] - Added "static" option - add rows/cells manually without query loop.</li>
				<li>[Dynamic Table] - Alternative row styles for background / text colors.</li>
				<li>[Modal] - Added "hashlink to close" option.</li>
				<li>[Dynamic Lightbox] - Added "hashlink to close" option.</li>
				<li>[Dynamic Lightbox] - Added "manual links" option to populate content dynamically from links. Supports images/videos/iFrames.</li>
				<li>[Modal] - Fixed the issue with exit intent trigger not triggering in Safari.</li>
			</ul>
			
			<h3>1.1.4
				<small>( Oct 21, 2022 )</small>
			</h3>
			<ul>
				<li>[Reading Progress Bar] - New element for adding reading progress bars based on scroll position of containers, or of the whole page.</li>
				<li>[Before / After Image] - New element for adding accessible before/after image sliders.</li>
				<li>[Table of Contents] - Added option to automatically use heading text for the anchor links.</li>
				<li>[Table of Contents] - Better support for Bricks' "Add Element ID & class as needed" setting (no longer required to add an ID to the element).</li>
				<li>[Modal] - Added an option to disable "auto focus on first focusable element" when opened.</li>
				<li>[Dynamic Table] - Added options to change/translate all text inside the pagination summary.</li>
				<li>[Dynamic Table] - Bumped to the latest GridJS version.</li>
				<li>[Dynamic Table] - Added an option to customize the number of pagination buttons.</li>
				<li>[Dynamic Table] - Fixed the issue with certain characters ( åäö ) not displaying correctly inside the builder.</li>
				<li>[Dynamic Lightbox] - Removed the default 900px max-width restriction on the container.</li>
			</ul>
			
			<h3>1.1.3
				<small>( Oct 13, 2022 )</small>
			</h3>
			<ul>
				<li>[Pro Slider] - Added option to change "focus" (was originally set to "center" as default).</li>
				<li>[Pro Slider] - Added controls for navigation by mouse wheel.</li>
				<li>[Pro Slider] - Reduced default slide padding and now no default padding if using code element to add custom slides.</li>
				<li>[Image Hotspots] - Better style control over marker icon.</li>
			</ul>
			
			<h3>1.1.2
				<small>( Oct 07, 2022 )</small>
			</h3>
			<ul>
				<li>[Pro Slider] - New element for building sliders/carousels.</li>
				<li>[Pro Slider Control] - New element for adding extras to sliders: Progress bars, counters, autoplay play/pause button.</li>
				<li>[Pro Slider Gallery] - New element for allowing to use the Pro Slider for dynamic galleries ex.: use ACF Gallery field or Meta Box Image Advanced or Media Library as the source of slide images.</li>
				<li>[Dynamic Chart] - Added "pie / doughnut" chart type.</li>
				<li>[Dynamic Lightbox] - Added easy way to add custom close buttons - "data-x-lightbox-close" attribute.</li>
				<li>[Burger Trigger] - Added option to add button text.</li>
				<li>[Table of Contents] - Smooth scrolling can now be disabled.</li>
				<li>[Modal] - Clicking backdrop to close and ESC key to close now optional.</li>
				<li>[Read More / Less] - Fixed issue where read more wouldn't size correctly when inside a modal.</li>
				<li>[OffCanvas] - Fixed issue where Safari that would cause lazy loaded images not to render.</li>
				<li>[Developer docs] - gLightbox instance now exposed, so lightbox can be controlled programmatically easily.</li>
			</ul>
			
			<h3>1.1.1
				<small>( Sep 14, 2022 )</small>
			</h3>
			<ul>
				<li>[Header Search] - Fixed an issue with return (enter) key in Safari causing the search form to close.</li>
				<li>[Fluent Forms] - Switched over some control types to allow for CSS variables.</li>
				<li>[Modal] - When using custom link for closing, browser will now follow the link after closing the modal.</li>
				<li>[General] - Addressed an issue that was causing styling not to be applied when elements were being pulled from other templates inside of templates (ex.: the "template" element or "post content" element).</li>
				<li>[Modal/OffCanvas] - Fixed issue with "template" versions that would cause the "hide in builder" setting to reset sometimes when moving the elements in the structure panel.</li>
			</ul>
			
			<h3>1.1
				<small>( Sep 13, 2022 )</small>
			</h3>
			<ul>
				<li>[General] - Fixed an issue where inline CSS wasn't correctly output on some installs.</li>
			</ul>
			
			<h3>1.0.9
				<small>( Sep 12, 2022 )</small>
			</h3>
			<ul>
				<li>[Header Search] - New element for creating different types of header searches.</li>
				<li>[Lottie] - New element for adding interactive Lottie animations.</li>
				<li>[Toggle Switch] - New element for adding toggle switches (supports multiple toggles).</li>
				<li>[Content Switcher] - New element for switching multiple versions of any content.</li>
				<li>[Lightbox] - Added iFrame option for lightbox content.</li>
				<li>[Dynamic Table] - Now supports being inside nested query loops.</li>
				<li>[Image Hotspots] - Now supports being inside nested query loops.</li>
				<li>[Dynamic Chart] - Now supports being inside nested query loops.</li>
				<li>[Dynamic Chart] - If no data found, will now output nothing, instead of an empty chart.</li>
				<li>[Modal] - Performance improvement (prevent images/video loading until modal opened).</li>
				<li>[Star rating] - Added "gap" setting for stars.</li>
				<li>[Lightbox] - Fixed lazy loading images not always showing in Safari.</li>
				<li>[General] - Removed all slider controls to replace with number controls (to align with Bricks v1.5.1 removing the units dropdown).</li>
				<li>[General] - Minor CSS specificity changes for Bricks v1.5.1.</li>
				<li>[General] - Performance improvement for element CSS to prevent FOUC (also now respects user preferred CSS loading method as set in Bricks settings - inline or external files).</li>
			</ul>
			
			<h3>1.0.8
				<small>( Aug 23, 2022 )</small>
			</h3>
			<ul>
				<li>[Dynamic tag - Loop Index] - New tag "x_loop_index" for count inside query loops (with offset filter).</li>
				<li>[Interactive features] - Now available for the div and block elements.</li>
				<li>[Modal, Lightbox] - Added flex layout controls to main "content" settings for easier control over the inner content layout.</li>
				<li>[Table of Contents] - Now supports including headings found inside separate containers.</li>
				<li>[Burger Trigger] - Added "active line color" control.</li>
			</ul>
			
			<h3>1.0.7
				<small>( Aug 17, 2022 )</small>
			</h3>
			<ul>
				<li>[Modal] - Added option to disable scroll when open.</li>
				<li>[Star Rating] - Improved logic for star rating to avoid issue when using empty values from dynamic data.</li>
			</ul>
			
			<h3>1.0.6
				<small>( Aug 16, 2022 )</small>
			</h3>
			<ul>
				<li>[Dynamic Lightbox] - New element for being able to popup dynamic content from inside post loops.</li>
				<li>[Offcanvas, Modal] - New nestable elements to replace older versions (previous "template" versions still available for backward compatibility or if you prefer to use templates).</li>
				<li>[Read More / Less, Header Notification Bar, Pro Alert, Shortcode Wrapper, Pro Alert] - now supports nesting elements.</li>
				<li>[Dynamic Tags] - Now supports being used inside AJAX added content (ex.: when using inside content dynamically added using WP Grid Builder's filters).</li>
				<li>[Star Rating] - Now allows for any number of total stars.</li>
				<li>[Slide Menu] - Now allows for elements being inside, either above/below the menu items.</li>
				<li>[Table of Contents] - Added ability to have "closed on page load".</li>
				<li>[Burger Trigger] - Better default CSS to prevent bottom gap if changing the display setting.</li>
				<li>[Fluent Form] - Fixed issue with dynamic data for Form ID not displaying form correctly.</li>
				<li>[Fluent Form] - Fixed CSS specificity issues where styles added via class were being overridden by defaults.</li>
				<li>[General] - A few small CSS fixes to account for Bricks v1.5's new default CSS.</li>
				<li>[General] - Added support for Bricks' experimental feature "Add Element ID & class only as needed" (elements that need the element ID to function, will have an ID added automatically if there isn't one).</li>
			</ul>
			
			<h3>1.0.5
				<small>( Jul 31, 2022 )</small>
			</h3>
			<ul>
				<li>Fixed a fatal error due to change in the attributes of bricks/element/render_attributes filter.</li>
			</ul>
			
			<h3>1.0.4
				<small>( Jul 05, 2022 )</small>
			</h3>
			<ul>
				<li>[Dynamic Table] - Added option to specify column data type as "number", to allow for sorting by numbers.</li>
				<li>[Dynamic Table] - Now allows for HTML tags from WYSIWYG fields inside the cells.</li>
				<li>[Slide Menu] - Added "text indent" setting for indenting nested sub menu items.</li>
				<li>[Image Hotspots] - Fixed the issue where marker background style wasn't visible.</li>
				<li>[Table of Contents] - Fixed the issue with unique ID setting causing table not to show.</li>
			</ul>
			
			<h3>1.0.3
				<small>( Jun 24, 2022 )</small>
			</h3>
			<ul>
				<li>[Parallax] - Improved the parallax scroll feature, new "default" setting for all devices.</li>
				<li>[Star Rating] - Small improvement for the default CSS.</li>
			</ul>
			
			<h3>1.0.2
				<small>( Jun 21, 2022 )</small>
			</h3>
			<ul>
				<li>[Dynamic Chart] - New element for displaying line/bar charts using dynamic data.</li>
				<li>[Read More/Less] - New element for expanding/revealing content.</li>
				<li>[Star Rating] - New element for displaying star ratings (for testimonials, reviews etc).</li>
				<li>[Dynamic Data Tags] - Added multiple new tags for use inside Bricks' dynamic data options.</li>
				<li>[Dynamic Table] - Added an option to make columns resizable.</li>
				<li>[Dynamic Table] - Added an option to prevent column text wrapping.</li>
				<li>[Dynamic Table] - Added cell overflow control.</li>
				<li>[Dynamic Table] - Added an option to specify a min-width for each column.</li>
				<li>[OffCanvas] - Added an option to prevent site scrolling when open.</li>
				<li>[Modal & OffCanvas] - iFrame embeds, videos and forms inside will now be automatically reset/stopped when closed.</li>
			</ul>

			<h3>1.0.1
				<small>( Jun 08, 2022 )</small>
			</h3>
			<ul>
				<li>[Image hotspots] - Added the ability to control animation for popovers.</li>
				<li>[Fluent form] - Fixed the issue with box-shadow not being applied.</li>
				<li>[General] - Fixed an error when using WP Toolkit searching for updates.</li>
				<li>[General] - Fixed an error if Bricks theme is deactivated with BricksExtras still active.</li>
			</ul>
			
			<h3>1.0.0
				<small>( Jun 06, 2022 )</small>
			</h3>
			<ul>
				<li>Initial release.</li>
			</ul>
		</div>


	<?php }

}