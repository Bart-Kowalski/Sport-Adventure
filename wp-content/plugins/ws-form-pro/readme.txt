=== WS Form PRO ===
Contributors: westguard
Requires at least: 5.4
Tested up to: 6.8
Requires PHP: 5.6
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

WS Form PRO allows you to build faster, effective, user friendly forms for WordPress. Build forms in a single click or use the unique drag and drop editor to create forms in seconds.

== Description ==

= Smart. Fast. Forms. =

WS Form lets you create any form for your website in seconds using our unique drag and drop editor. Build everything from simple contact us forms to complex multi-page application forms.

== Installation ==

For help installing WS Form, please see our [Installation](https://wsform.com/knowledgebase/installation/?utm_source=wp_plugins&utm_value=175764&utm_medium=readme) knowledge base article.

== Changelog ==

= 1.10.59 - 10/06/2025 =
* Added: Support for Google Maps JS 'Places API (New)' - Setting in WS Form > Settings > Advanced for accounts created after March 1st, 2025
* Added: Timeout and High Accuracy settings for browser geo lookup in Google Address field
* Added: Improved Google Maps JS API detection
* Added: #post_date_modified, #post_time_modified, #post_date_modified_custom and #post_parent variables
* Added: Demo post variable template
* Added: Sidebar resizing on submissions page
* Added: #field_max_value(id) now works for fields in repeatable sections
* Added: Improved switcher true / false detection for JetEngine
* Added: Improved admin CSS to handle body padding
* Added: Translator comment improvements
* Added: Translation language file updates
* Added: Ability to use tracking variables client-side, where available (e.g. #tracking_referrer)
* Added: Select2 theme setting
* Bug Fix: Function detection for captcha fields in conditional logic
* Bug Fix: Translation warnings during activation

= 1.10.58 - 09/11/2025 =
* Added: Resizable sidebar in layout editor
* Added: Improvement to ACPT data source to avoid errors if field ID changes / missing
* Added: Translation updates
* Bug Fix: Compiled rendering of layout CSS with RTL

= 1.10.57 - 09/04/2025 =
* Added: Abilities class ready for WordPress abilities / MCP APIs
* Added: Improve handling of visual builder includes
* Added: Options and style initialization on activation moved to init action
* Added: Accessibility improvements to Validation field
* Bug Fix: Various CSS calc() fixes
* Bug Fix: Spacer field markup in #email_submission
* Bug Fix: Invalid feedback for radio fields in a fieldset

= 1.10.56 - 08/27/2025 =
* Added: Upgrades for ACPT integration
* Bug Fix: #if variable string comparison

= 1.10.55 - 08/20/2025 =
* Added: E-commerce field lock / unlock methods for PayPal add-on
* Bug Fix: Conditional logic "Matches Field" condition case sensitivity
* Bug Fix: Currency formatting in submission e-commerce section
* Bug Fix: Submission date editing

= 1.10.54 - 08/15/2025 =
* Added: Form import (Button and drag & drop) on Add New form page
* Added: Improved presentation of unavailable settings in LITE edition
* Added: Improved base CSS for fields
* Bug Fix: TinyMCE focus scrolling

= 1.10.53 - 08/01/2025 =
* Added: wsf_action_email_css filter hook
* Added: Validate CSS improvements
* Added: Validation field update on invalid feedback set from Run WordPress Hook
* Added: include_hidden attribute on #checkbox_count and #checkbox_count_total variables
* Added: Summary field can now exclude HTML and Text Editor fields
* Added: Summary field no longer renders labels for HTML and Text Editor fields
* Added: Experimental searchable field mapping dropdowns
* Added: Updated Google Address field to support inconsistent address part types
* Bug Fix: Move down button

= 1.10.52 - 07/09/2025 =
* Added: New Validation field type (See: https://wsform.com/knowledgebase/validate/)
* Added: Additional checks when autofocusing select 2 search field
* Added: #section_id can now be used within #section_rows_start loop
* Added: Improvements to attachment filtering for DropzoneJS scratch files

= 1.10.51 - 06/26/2025 =
* Added: Improved server-side required validation
* Bug Fix: Migrate tool meta key validation

= 1.10.50 - 06/24/2025 =
* Added: New block using API version 3
* Added: Scroll and focus for invalid Visual Editor fields
* Bug Fix: Conditional logic highlighting
* Bug Fix: #field in data grid issue with multiple form instances

= 1.10.49 - 06/17/2025 =
* Bug fix: Akismet headers

= 1.10.48 - 06/09/2025 =
* Added: Email pattern setting now has "Must have TLD" example regex
* Added: wsf_name_prefixes and wsf_name_suffixes filter hooks for #name_prefix and #name_suffix variables
* Added: Updated translations
* Bug Fix: Radio and checkbox vertical padding removed if left label alignment selected
* Bug Fix: Edit in Preview link

= 1.10.47 - 05/22/2025 =
* Added: New #name_prefix(), #name_first(), #name_middle(), #name_last() and #name_suffix() variables (See: https://wsform.com/knowledgebase/extract-first-and-last-names-from-a-full-name/)
* Added: If you have a single form, submissions page now defaults to that form
* Added: Submissions page now remembers last form viewed
* Bug fix: Improved form_bypass_obj_reset method for resetting field prior to form_bypass

= 1.10.46 - 05/20/2025 =
* Removed: Human Presence as third party service no longer available

= 1.10.45 - 05/19/2025 =
* Added: Keyword blocklist string / word match options for wsf_submit_block_keywords filter hook
* Added: 404 returned on DropzoneJS scratch file attachment pages
* Changed: NONCE setting move to Spam Protection tab in Global Settings

= 1.10.44 - 05/17/2025 =
* Added: Keyword blocklist (See: https://wsform.com/knowledgebase/how-to-block-submissions-by-keyword/)
* Added: wsf_submit_block_keywords filter hook (See: https://wsform.com/knowledgebase/wsf_submit_block_keywords/)
* Added: IP blocklist (See: https://wsform.com/knowledgebase/how-to-block-form-submissions-by-ip/)
* Added: wsf_submit_block_ips (See: https://wsform.com/knowledgebase/wsf_submit_limit_ips/)
* Added: Cubic meter calculator template
* Added: Send email action now accepts BCC or CC in isolation
* Added: Improvement to #slug variable
* Bug Fix: Performance improvement with reading CSS option

= 1.10.43 - 05/12/2025 =
* Bug Fix: Mouse up handler in layout editor

= 1.10.42 - 05/09/2025 =
* Added: #field_date_age variable that can be used to calculate age from a date field
* Added: Label translation settings for the DropzoneJS file upload component
* Added: form_bypass reset on conditional logic required set
* Added: Improvements to option_set and option_get methods

= 1.10.41 - 05/06/2025 =
* Added: Additional checks for number formatting to ensure decimals are between 0 and 100
* Bug Fix: Bypass hidden field check if hidden at a section or tab level
* Bug Fix: form_bypass on cascade

= 1.10.40 - 05/04/2025 =
* Added: field_<field_id> and field_values parameters to WS Form short code for populating field values (See: https://wsform.com/knowledgebase/the-ws-form-shortcode/)
* Added: Message shown when copying shortcodes in admin sidebar
* Bug Fix: form_bypass custom validation caching now checks for validity.customError set on element

= 1.10.39 - 05/01/2025 =
* Bug fix: Captcha key obscuring admin side

= 1.10.38 - 04/30/2025 =
* Added: Improved admin config handling
* Added: Improved price checkbox and price radio hidden row handling
* Bug fix: form_bypass on min / max character length check if field hidden

= 1.10.37 - 04/24/2025 =
* Bug Fix: Checkbox and radio row data IDs

= 1.10.36 - 04/23/2025 =
* Bug Fix: Added capability check to config API endpoint

= 1.10.35 - 04/17/2025 =
* Added: data-id attribute added to data grid checkbox and radio field rows
* Bug Fix: Hierarchy indentation CSS for checkbox and radio fields
* Bug Fix: aria-hidden attribute on invalid feedback text
* Bug Fix: form_bypass row referencing

= 1.10.34 - 04/10/2025 =
* Bug Fix: Radio / checkbox field hidden bypass
* Bug Fix: pre_render filter hook no longer runs for styler preview template

= 1.10.33 - 04/08/2025 =
* Bug Fix: Conditional multi-event triggers

= 1.10.32 - 04/07/2025 =
* Bug Fix: Tab click detection in conditional logic
* Bug Fix: Cascade AJAX handle reset

= 1.10.31 - 04/04/2025 =
* Added: Summary field type
* Added: Insert summary HTML button for HTML field
* Added: Webhook array to CSV delimiter setting
* Added: Is Blank setting for redirect fallback
* Added: Data layer conversion reset setting
* Added: Webhook username and password fields can now contain WS Form variables
* Added: Improved client-side REST API response handling
* Added: Patched datetime library with latest verion for date formatting library
* Added: Styler settings for repeatable section icons
* Added: Debug console CSS improvements
* Added: Various styler CSS improvements
* Added: Updated translations
* Added: Google Address location snap setting for Google Maps
* Added: Various improvements to licensing class
* Added: WordPress 6.8 compatibility
* Bug Fix: Google Maps being set from Google Address / Routing in repeaters
* Bug Fix: Checkbox row level data-hidden attribute causing field to submit empty
* Bug Fix: Email validation if Multiple checkbox enabled
* Bug Fix: Action firing via conditional logic

= 1.10.30 - 03/20/2025 =
* Added: Repeaters to arrays setting in Webhook action

= 1.10.29 - 03/19/2025 =
* Added: Additional Google Map parameters stored in address object for ACF
* Bug Fix: Server-side #field data grid column mapping fix for repeaters / duplicate values

= 1.10.28 - 03/18/2025 =
* Added: Patch to fix form select option values bug with Divi
* Added: Translation improvements for product names
* Bug Fix: Admin toolbar menu fix if no supported user capabilities found

= 1.10.27 - 03/12/2025 =
* Added: Warnings added to fields if hidden is checked to explain how hidden fields work
* Added: Form list table now shows orange switches and text if a form publish is pending
* Added: Auto publish setting
* Added: JetEngine options pages support

= 1.10.26 - 03/08/2025 =
* Added: Legacy support for invalid feedback fields in layout CSS file

= 1.10.25 - 03/08/2025 =
* Bug Fix: Coloris fix for LITE

= 1.10.24 - 03/07/2025 =
* Added: Updated detection script for Meta Box and ACPT custom field plugins
* Added: Improved help text for file upload Accepted File Type(s) setting
* Added: Field border placement setting in styler (All sides or bottom only)
* Changed: Invalid feedback CSS moved from layout to base CSS files
* Bug Fix: Invalid feedback positioning if prefix / suffix and ITI used on phone field

= 1.10.23 - 03/03/2025 =
* Added: Patch to fix browser related bug for DropzoneJS hidden input field when rendered using RTL
* Bug Fix: Post data source fix if Meta Box returned an object instead of an array
* Bug Fix: CSS fix for checkbox styled as button with full width

= 1.10.22 - 02/26/2025 =
* Added: Admin CSS improvements
* Added: Improved ACF required field handling
* Added: Meta box data source object bypassing
* Bug Fix: Conversation JS no longer enqueued if dynamic enqueuing is disabled
* Bug Fix: #checkbox_count fix

= 1.10.21 - 02/19/2025 =
* Added: Clamp font size calculator (https://theadminbar.com/simple-responsive-font-size-calculator/)
* Added: #option_get(option_name, parameter/key) variable for use in action settings
* Added: Email address field added to cascading source field types
* Added: Updated language files
* Added: Patched bug with intl-tel-input library returning error -99
* Added: Various CSS improvements
* Changed: Default p bottom margin change to 1em (browser default)
* Changed: Updated Coloris CDN paths

= 1.10.20 - 02/12/2025 =
* Added: Social Security Number field type https://wsform.com/knowledgebase/ssn/
* Added: #checkbox_count_total (Total checkboxes in field) and #select_count_total (Total options in field) variables
* Added: Various styler CSS improvements
* Bug Fix: Home URL referencing

= 1.10.19 - 02/08/2025 =
* Added: Improvements to server-side email address validation
* Changed: Some server-side checks now return an HTTP 403 status code to avoid error emails being sent

* Added: New functionality added for OpenAI generated calculator forms
* Bug fix: Loader CSS enqueuing

= 1.10.17 - 02/05/2025 =
* Added: Fallback URL setting in Redirect action https://wsform.com/knowledgebase/redirect-users-to-the-referring-page-after-login/
* Bug Fix: Woocommerce cart rendering for select, checkbox or radio fields in repeaters

= 1.10.16 - 01/31/2025 =
* Added: TrustedForm support https://wsform.com/knowledgebase/trustedform/
* Added: Default reCAPTCHA version setting in global settings
* Added: Third party custom field changes ready for Option Management add-on
* Added: Stopped tutorial rendering if IntroJS incorrectly enqueued by third party plugins
* Bug Fix: Date/time overlay styling if outside of wrapper

= 1.10.15 - 01/22/2025 =
* Bug Fix: Submission unread bubble escaping
* Bug Fix: Client-side wpautop now checks for non-string input

= 1.10.14 - 01/21/2025 =
* Added: Changes made to form populate sidebar ready for Salesforce add-on improvements
* Added: URL, email and tel outputs no longer anchored if invalid
* Added: Improved escaping throughout
* Added: Changed randomness of debug console hostname generator

= 1.10.13 - 01/20/2025 =
* Added: Improvement to Coloris color picker to ensure it updates if vars entered
* Added: Added height auto on fields to overcome third party CSS fixing heights
* Added: Improved input sanitization of URL and tel fields
* Bug Fix: WordPress media selector disable in visual editor enqueuing to avoid conflicts with ACF

= 1.10.12 - 01/15/2025 =
* Added: Improvements to checkbox and radio field overrides to avoid third party CSS issues
* Bug Fix: Framework fix in visual editors

= 1.10.11 - 01/14/2025 =
* Added: Improvements to CSS overrides for checkbox and radio fields
* Added: Set custom validity on data grid rows in conditional logic
* Bug Fix: CSS fixes for Bootstrap 5

= 1.10.10 - 01/13/2025 =
* Added: Circle style option for radio fields
* Added: Border radius style setting for checkboxes (inherits field border radius by default)
* Added: Styler palette now resolves CSS variables to colors from theme palettes
* Added: Improvements to styler LITE and PRO preview templates
* Bug Fix: Invalid feedback styling for field level validation
* Bug Fix: Meta Box time field population
* Bug Fix: Checkmark positioning in checkbox fields related to border width

= 1.10.9 - 01/10/2025 =
* Added: Background color styler setting added for signature field
* Added: Persistent debug and styler CSS enqueuing
* Bug Fix: Action disabling if all actions disabled with conditional logic

= 1.10.8 - 01/08/2025 =
* Added: Inside label position behavior setting to styler
* Added: ACF fields now exclude option assign fields by default
* Added: Message styling added to LITE edition
* Bug Fix: Removed PHP warning in version prior to 8.0 related to style template SVG's
* Bug Fix: Submission export for LITE edition

= 1.10.7 - 01/07/2025 =
* Added: Improved compatibility of new form components with visual builders

= 1.10.6 - 01/06/2025 =
* Bug Fix: Customize publish button issue when wsf_styler_enabled filter set to true

= 1.10.5 - 01/05/2025 =
* Added: Improvements to style preview templates
* Changed: Date/time field day padding reduced to improve size on mobile
* Changed: CSS value sanitization now accepts zero without unit
* Bug Fix: WooCommerce form locking
* Bug Fix: wsf_styler_enabled filter hook
* Bug Fix: Checkbox and radio disabled opacity

= 1.10.4 - 01/04/2025 =
* Bug Fix: Remove width setting on buttons

= 1.10.3 - 01/02/2025 =
* Added: InstaWP staging install support

= 1.10.2 - 01/02/2025 =
* Bug Fix: Removed descending index for style table to avoid incompatibility issues with some storage engines

= 1.10.1 - 01/02/2025 =
* Bug Fix: Activation debug disabled

= 1.10.0 - 01/02/2025 =
* Important installation notes: https://wsform.com/knowledgebase/upgrade-notes-for-1-10-x/
* Added: New form styles (See: https://wsform.com/knowledgebase/styles/)
* Added: New form styler (See: https://wsform.com/knowledgebase/styler/)
* Added: Accessibility improvement: autocomplete attributes now default for password, URL, email and phone fields
* Added: Accessibility improvement: autocomplete attributes completed in form and section templates
* Added: Accessibility improvement: Checkbox field now defaults to label on
* Added: Accessibility improvement: Removed character / word count help text from textarea by default
* Added: Accessibility improvement: URL field now contains https:// placeholder
* Added: Accessibility improvement: Color contrast improvements throughout
* Added: Accessibility improvement: Coloris color picker
* Added: Accessibility improvement: ARIA label on section fieldsets
* Added: Accessibility improvement: Dark / light color themes
* Added: Auto grow setting for Text Area field
* Added: Checkbox and radio styling setting (button, switch, swatch and image)
* Added: Email allow / deny feature now processed client-side
* Added: Improved Dutch translations
* Added: Increased generated password size to 24 characters
* Added: Form saved on field clone
* Added: Support for j.n.Y date format
* Added: Submission export performance improvements
* Added: Descriptive form limit error message on form submit
* Added: Select2 upgraded to version 4.0.13
* Added: WordPress 6.7 compatibility testing
* Added: Author ID added as column in Posts data source
* Added: Points allocation form template
* Added: Repeater level custom attachment title, excerpt, content and alt text
* Added: Updated logo in conversation template
* Added: Postmark API error handling in Send Email action
* Added: #query_var default value parameter
* Added: Limit submissions per logged in user
* Added: Invalid captcha responses no longer throw a PHP exception
* Added: Phone field ITI validation triggered on paste event
* Added: Additional checks when determining capabilities for conditional logic
* Bug Fix: Loader functionality on form reload
* Bug Fix: Translation issue related to widgets_init / load_plugin_textdomain
* Bug Fix: Escaping when using #text
* Bug Fix: Price radio invalid feedback text location
* Bug Fix: Order by in terms data source for term order
* Bug Fix: Form calc clean method for hidden fields
