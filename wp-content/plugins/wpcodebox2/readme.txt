=== Easily manage all your WordPress code ===
Contributors: WPCodeBox 2
Requires at least: 5.0
Tested up to: 6.5.3
Stable tag: 1.1.1

WPCodeBox is a complete WordPress snippet manager. With WPCodeBox you can manage all of your site's code without touching functions.php.

== Description ==

= WPCodeBox - Complete WordPress Snippet Manager =

WPCodeBox is a complete WordPress snippet manager. With WPCodeBox you can manage all of your site's code without touching functions.php.

== Changelog ==


= 1.1.1 (Released on May 22nd 2024) =

* New Feature: Show snippet conditions overview on the snippet edit page
* New Feature: First version of the WPCodeBox API for 3rd party integrations
* Improvement: Show warning if the ZipArchive library is not installed and trying to generate plugins
* Improvement: FP Author URI hardcoded to wpcodebox.com
* Bugfix: "Current URL Is" condition throws error when the FP is enabled
* Bugfix: Generate plugin and FP Download not working correctly when plugin name is not "wpcodebox2"
* Bugfix: Error in logs when the FP is enabled and editing a snippet
* Bugfix: Warnings when the FP is enabled and temp folder is not writable
* Bugfix: Snippet description not updated until page refresh when downloading a snippet from the cloud
* Bugfix: Added the correct safe mode link on the WPCodeBox Error page
* Bugfix: Current user role condition error when the FP is enabled
* Bugfix: Invalid zip when generating plugins on certain server configs

= 1.1.0 (Released on April 11th 2024) =

* New Feature: Export snippets to plugin
* New Feature: Possibility to download the Functionality Plugin
* New Feature: JSON Snippet support (for creating custom ACF Gutenberg Blocks)
* New Feature: Added “Do not render” option for CSS and JS snippets
* New Feature: Sign the code in the Functionality Plugin
* New Feature: Ability to white label the Functionality Plugin
* Improvement: Removed eval from custom conditions in the Functionality Plugin and the generated plugins
* Improvement: Removed snippet IDs from the Functionality Plugin
* Improvement: Functionality Plugin respects folder structure
* Improvement: Various Functionality Plugin improvements and cleanup of generated code
* Improvement: Added “Exactly Matches” option to the URL condition
* Improvement: Support for nested SCSS partials
* Improvement: Removed the auto-reload code from the Functionality Plugin and used it from WPCodeBox. This was breaking the FP when WPCodeBox was removed
* Improvement: Functionality Plugin revamp based on user feedback (bugfixes and improvements)
* Improvement: Add the ability to add API_KEY in wp-config.php file (define(‘WPCB_API_KEY’, ‘YOUR_API_KEY’);)
* Improvement: Disabled autocomplete in CSS comments
* Improvement: "Upload to cloud" changed to "Update&quot when the snippet is already saved to the cloud
* Improvement: Top bar text changes so it can fit on a single row, show the shortcuts in tooltips, and show the relevant key shortcuts based on OS (Cmd on Mac and Ctrl on PC)
* Improvement: Move external CSS and JS snippets to the Functionality Plugin when it is enabled instead of loading them from the wp-uploads folder
* Improvement: Updated the SCSS compiler library to the latest version
* Improvement: Namespaced the update library
* Improvement: On smaller displays, the repository buttons are not visible
* Improvement: Optimize repository calls only when the Repository is open
* Improvement: Added separate classes to the Cloud Snippet list so they can be customized using CSS (.cloud-snippet-list)
* Bugfix: Long snippet names caused snippets not to save
* Bugfix: Snippet toggles slow in some instances
* Bugfix: Issues with minifying and compiling SCSS introduced in the latest beta
* Bugfix: Enable/Disable toggles appearing for TXT snippets
* Bugfix: Functionality Plugin not updated when moving snippets to folders
* Bugfix: Extra spaces are present around HTML snippets that render using shortcodes
* Bugfix: "Invalid Archive&quot error when uploading WPCodeBox on WordPress v6.4.3
* Bugfix: Hook priority isn’t changeable
* Bugfix: AutomaticCSS autocompletion was not appearing when the line ends in something different than “color:”
* Bugfix: Don’t compile partials that are commented out
* Bugfix: When you download a Cloud Snippet, the description is not updated until a page refresh
* Bugfix: When you add more than one custom PHP condition, the condition editor won’t load for the 2nd one
* Bugfix: In some rare cases (probably when two users edit the snippet at the same time), an empty snippet is created that will break the UI and require deletion from the database
* Bugfix: When you download the UI settings from the cloud, the Codemap option is enabled, regardless of the state
* Bugfix: "Download/Upload from the cloud" is still visible in the context menu, even when using a read-only or disabled API key
* Bugfix: Condition builder appearing for SCSS partials
* Bugfix: PHP warning when Oxygen color list is empty
* Bugfix: Minification converts 0% to 0, but 0% is required for HSL CSS rendering
* Bugfix: External CSS files loaded incorrectly in some cases
* Bugfix: Date conditions don’t work in some cases
* Bugfix: admin_head not working for HTML snippets
* Bugfix: "Format Code" not working in SCSS partials
* Bugfix: When dragging a disabled snippet to a folder, the toggle in the UI shows it as enabled
* Bugfix: CSS Snippets not working in Functionality Plugin
* Bugfix: In Safari, the snippet status overlaps the save button
* Bugfix: When uploading a folder to the cloud, the snippets in the folder are duplicated
* Bugfix: Format code shortcut saves snippet instead of formatting the code
* Bugfix: PHP notice when running WP CLI commands


= 1.0.3 (Released on May 28th 2023) =

* Bugfix: Snippet order not preserved when reordering using drag and drop

= 1.0.2 (Released on May 24th 2023) =

* New Feature: Add "Unlink from Cloud" button to snippets context menu
* Bugfix: Autoreload not working when both WPCodeBox and WPCodeBox 2 are installed
* Bugfix: Snippets with very long descriptions not saved
* Bugfix: Snippets with many conditions not saved
* Bugfix: Folder order not preserved
* Bugfix: In some cases, deleting a cloud snippet causes a local error
* Bugfix: Plain text snippets causing errors in some cases
* Bugfix: In some rare cases, CSS and SCSS snippets can be saved with the plugins_loaded hook
* Bugfix: Functionality plugin generating errors in some cases


= 1.0.1 (Released on May 17th 2023) =

* Bugfix: Warning when both WPCodeBox and WPCodeBox 2 are installed
* Bugfix: Async and defer options not rendered on external JS tags
* Bugfix: Custom shortcode parameters are not passed to custom shortcodes
* Bugfix: Create/download from cloud not working for very large snippets
* Bugfix: Frontend header (After pagebuilders) hook not rendering JS and CSS snippets
* Bugfix: Deprecated notices in PHP 8.2 in the update library
* Bugfix: WPCodeBox error page is showing for non-WPCodeBox errors


= 1.0.0 (Released on May 10th 2023) =

* New Feature: Monaco Editor
* New Feature: Autocomplete for all WordPress actions & filters & Parameters
* New Feature: Functionality Plugin (Experimental)
* New Feature: WooCommerce hooks snippet insertion for HTML and PHP Snippets
* New Feature: Color picker for CSS/SCSS/LESS
* New Feature: SCSS Partials
* New Feature: Render PHP/HTML snippets using custom shortcodes
* New Feature: Actions and custom actions for rendering snippets
* New Feature: Option to render CSS/SCSS after page builders’ CSS
* New Feature: Show local variables in autocomplete
* New Feature: Save UI Settings to the cloud
* New Feature: Execute PHP snippets using a secure external URL
* New Feature: Collapse left/right panes using Ctrl + 1/Ctrl + 2
* New Feature: Added do not render to PHP snippets so they can be included via code
* New Feature: Emmet support
* New Feature: Oxygen Color Integration
* New Feature: Bricks Color Integration
* New Feature: Automatic CSS Integration
* New Feature: WordPress hooks and action reference on hover
* New Feature: Code map that can be disabled in settings
* New Feature: CSS Variables support and autocomplete
* New Condition: User logged in
* New Condition: User device (mobile/desktop)
* Improvement: Use custom tables to store data for better performance
* Improvement: Added info about safe mode on the error page
* Improvement: Show notice when Safe Mode is active
* Improvement: Added “Reload Local Snippets” button
* Improvement: Removed jQuery from Live Reload CSS
* Improvement: Close the context menu when clicking on another snippet
* Improvement: Added post name to the WPCodeBox custom post types
* Improvement: Removed arrow from priority input in Firefox
* Improvement: Complete backend rewrite for improved performance
* Improvement: Better error detection and handling
* Improvement: Add loader when running manual snippets
* Improvement: Allow the saving of SCSS/LESS snippets even if the compilation fails
* Improvement: Action/priority/shortcode are saved to the cloud
* Improvement: Set “plugins_loaded” as the default action for PHP snippets
* Improvement: Make the editor fill the height
* Improvement: Removed the plugins_loaded notice
* Improvement: Added wp_body_open hook
* Improvement: Fire wpcb_snippet_disabled action when a snippet is disabled
* Improvement: Small security improvements
* Bugfix: PHP Notice when using the post parent conditions for posts that don't have a parent
* Bugfix: When editing cloud snippets, the name is not updated in the list automatically
* Bugfix: The key is not checked on autoreload, causing compatibility issues with some plugins
* Bugfix: Snippet status not updated when downloading a snippet from the cloud
* Bugfix: Taxonomy “Is not” condition is not working correctly
* Bugfix: LESS is not working on PHP 8
* Bugfix: Current snippet is not always selected when refreshing the page
* Bugfix: Unsaved changes notification appears when there are no unsaved changes
* Bugfix: Delete snippets from the context menu doesn’t always work
