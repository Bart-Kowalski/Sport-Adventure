=== WS Form PRO - Post Management ===
Contributors: westguard
Requires at least: 5.4
Tested up to: 6.8
Stable tag: trunk
Requires PHP: 5.6
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Post Management add-on for WS Form PRO.

== Description ==

Post Management add-on for WS Form PRO.

== Installation ==

For help installing this plugin, please see our [Installation](https://wsform.com/knowledgebase/installation/?utm_source=wp_plugins&utm_medium=readme) knowledgebase article.

== Changelog ==

= 1.6.11 - 08/26/2025 =
* Added: ACPT updates

= 1.6.10 - 06/30/2025 =
* Changed: Disabled ACF validation by default

= 1.6.9 - 04/09/2025 =
* Bug Fix: Updated ACF deduplication

= 1.6.8 - 04/01/2025 =
* Added: WordPress 6.8 compatibility

= 1.6.7 - 02/15/2025 =
* Added: Improved debug for Meta Box custom table add and update methods

= 1.6.6 - 02/13/2025 =
* Bug Fix: Deduplication setting

= 1.6.5 - 01/31/2025 =
* Added: Taxonomy dropdown now shows the taxonomy name

= 1.6.4 - 01/08/2025 =
* Added: Default setting for comment and ping status settings

= 1.6.3 - 11/26/2024 =
* Bug Fix: Meta box updates to custom tables

= 1.6.2 - 11/11/2024 =
* Bug Fix: Fixed load_plugin_textdomain warning

= 1.6.1 - 10/31/2024 =
* Added: Trash added to post status setting
* Bug Fix: JetEngine relationship from string

= 1.6.0 - 05/14/2024 =
* Added: ACPT integration
* Added: Restructured custom field integrations to improve performance

= 1.5.14 - 11/27/2023 =
* Bug Fix: Pods escaping

= 1.5.13 - 11/02/2023 =
* Added: PHP 8.2 compatibility support

= 1.5.12 - 09/06/2023 =
* Bug Fix: Deduplication no longer runs on post updates

= 1.5.11 - 05/24/2023 =
* Added: Improved handling of various ACF field types

= 1.5.10 - 05/04/2023 =
* Bug Fix: Additional data format fixes for Meta Box file and image field types

= 1.5.9 - 05/03/2023 =
* Bug Fix: Data format fixes for Meta Box file and image field types

= 1.5.8 - 04/20/2023 =
* Bug Fix: JetEngine Relations support (Parent assignment)

= 1.5.7 - 04/20/2023 =
* Added: JetEngine Relations support

= 1.5.6 - 03/10/2023 =
* Added: Forward slashes now escaped in post and custom field data to follow method used by WordPress

= 1.5.5 - 03/28/2023 =
* Added: Post parent setting
* Changed: Removed error settings as these are now controlled in Form Settings

= 1.5.4 - 03/24/2023 =
* Bug Fix: JetEngine media field data when set to both format

= 1.5.3 - 03/23/2023 =
* Bug Fix: JetEngine media and gallery field data formats

= 1.5.2 - 02/06/2023 =
* Added: Meta Box custom table support

= 1.5.1 - 02/05/2023 =
* Added: Improved WooCommerce product gallery handling

= 1.5.0 - 01/22/2023 =
* Added: JetEngine support

= 1.4.19 - 01/19/2023 =
* Bug Fix: post_updated hook

= 1.4.18 - 12/16/2022 =
* Added: Action firing after custom field plugins add/change meta data
* Bug Fix: ACF image assignment in groups

= 1.4.17 - 11/26/2022 =
* Added: Additional performance improvements on meta config filter

= 1.4.16 - 11/25/2022 =
* Added: Custom field configuration no longer loading on client side to improve performance

= 1.4.15 - 11/15/2022 =
* Bug Fix: Section index issue if WooCommerce enabled

= 1.4.14 - 10/11/2022 =
* Added: Support for removing file uploads in custom field plugin groups and repeaters

= 1.4.13 - 10/10/2022 =
* Added: Update previous post setting

= 1.4.12 - 10/04/2022 =
* Added: Pass field_id when converting WS Form values to Meta Box values

= 1.4.11 - 09/15/2022 =
* Bug Fix: Featured image no longer removed if not mapped

= 1.4.10 - 08/27/2022 =
* Added: Added support for removing file uploads

= 1.4.9 - 06/15/2022 =
* Changed: Learn more link changed to newer knowledge base article for the Post ID setting

= 1.4.8 - 03/30/2022 =
* Bug Fix: Adding multiple post types

= 1.4.7 - 12/17/2021 =
* Added: Post date fix

= 1.4.6 - 12/04/2021 =
* Added: WordPress version 5.9 compatibility updates

= 1.4.5 - 11/30/2021 =
* Added: New 'Terms' setting allows taxonomy terms to be assigned to posts

= 1.4.4 - 11/22/2021 =
* Added: WooCommerce price meta data handling

= 1.4.3 - 11/22/2021 =
* Added: Improved error checking for serialize meta data and term updates

= 1.4.2 - 11/19/2021 =
* Added: WooCommerce field mapping (Products)
* Added: Ability to add file attachments to WooCommerce product galleries

= 1.4.1 - 10/13/2021 =
* Added: Moved main class to include

= 1.4.0 - 10/12/2021 =
* Added: Toolset support

= 1.3.5 =
* Added: Languages folder

= 1.3.4 =
* Added: Ability to map serialized strings to meta data

= 1.3.3 =
* Added: Post status 'Default' option. This sets the status to 'Draft' for new posts and leaves the status as is for post updates.
* Added: Post password

= 1.3.2 =
* Added: If the post management action is configured to run on form save, WS Form now retains post ID so that subsequent saves update the same post

= 1.3.1 =
* Bug Fix: Taxonomy retrieval when taxonomy assigned to more than one post type

= 1.3.0 =
* Added: Pods support

= 1.2.1 =
* Added: ACF validation setting

= 1.2.0 =
* Added: Meta Box support
* Added: ACF Custom Database Tables plugin support

= 1.1.24 =
* Added: ACF field validation

= 1.1.23 =
* Bug Fix: Encoding of serialized strings with custom meta mapping

= 1.1.22 =
* Added: Support for populating / setting post featured images using DropzoneJS

= 1.1.21 =
* Changed: Default post ID setting and help text

= 1.1.20 =
* Bug Fix: File field DropzoneJS attachment ID mapping

= 1.1.19 =
* Bug Fix: File fields in repeaters

= 1.1.18 =
* Added: Restrict Populate by Author

= 1.1.17 =
* Bug Fix: ACF boolean field

= 1.1.16 =
* Added: Restrict Updates to Author setting

= 1.1.15 =
* Added: Support for ACF gallery field

= 1.1.14 =
* Added: Support for ACF Google Maps field

= 1.1.13 =
* Added: Extended characters allowed for list slugs

= 1.1.12 =
* Added: Improved handling of empty array values to ACF

= 1.1.11 =
* Added: Improved handling of ACF fields with blank labels

= 1.1.10 =
* Added: Clear hidden fields setting added

= 1.1.9 =
* Bug Fix: Images in repeaters sometimes did not map due to inconsistent parent value in ACF object data

= 1.1.8 =
* Added: Unique field mapping

= 1.1.7 =
* Changed: REST endpoint initialization for WordPress 5.5
* Bug Fix: Pre-population on multiple ACF groups

= 1.1.6 =
* Added: Support for ACF group/repeater widths

= 1.1.5 =
* Bug Fix: ACF bug fixes for repeater and group form data tab

= 1.1.4 =
* Added: Improved default Post ID field for form data population sidebar

= 1.1.3 =
* Added: Data source support

= 1.1.2 =
* Added: Repeatable image mapping
* Added: Image mime type mapping

= 1.1.1 =
* Added: Expose on added posts

= 1.1.0 =
* Added: Full ACF support

= 1.0.12 =
* Bug fix: List fields

= 1.0.11 =
* Added: ACF true/false for data grids

= 1.0.10 =
* Added: ACF true/false type fix

= 1.0.9 =
* Added: ACF checkbox field type fix

= 1.0.8 =
* Added: ACF support
* Added: Save of post ID to $submit on add for use in other actions

= 1.0.7 =
* Added: Ability to update posts
* Added: Check to see what post type supports when added fields on 'Add New'
* Added: Security checks to ensure matching post type is get, added, or updated
* Added: Term population

= 1.0.6 =
* Changes: Tag assignment for non-logged in users

= 1.0.5 =
* Changes: Parsing of custom meta values

= 1.0.4 =
* Bug fix: WordPress 5.1 fix

= 1.0.3 =
* Bug fix: Taxonomies now limited to post type

= 1.0.2 =
* Bug fix: Hide tag mapping in auto complete tab

= 1.0.0 =
* Initial release.
