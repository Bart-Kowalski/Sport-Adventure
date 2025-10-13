=== WS Form PRO ===
Contributors: westguard
Requires at least: 5.3
Tested up to: 6.6
Requires PHP: 5.6
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

WS Form PRO allows you to build faster, effective, user friendly WordPress forms. Build forms in a single click or use the unique drag and drop editor to create forms in seconds.

== Description ==

= Build Better WordPress Forms =

WS Form lets you create any form for your website in seconds using our unique drag and drop editor. Build everything from simple contact us forms to complex multi-page application forms.

== Installation ==

For help installing WS Form, please see our [Installation](https://wsform.com/knowledgebase/installation/?utm_source=wp_plugins&utm_medium=readme) knowledge base article.

== Changelog ==

= 1.9.244 - 08/21/2024 =
* Bug Fix: ISO 8601 formatted date 'c'
* Bug Fix: ITI phone number validation (For error -99)
* Bug Fix: XSS security fix for nested HTML tags

= 1.9.243 - 08/19/2024 =
* Added: Date / time picker pop-up class
* Changed: Date regex examples
* Bug Fix: Settings license page translation
* Bug Fix: Google routing waypoint initial event firing

= 1.9.242 - 08/13/2024 =
* Added: Field sort support for SQLite
* Bug Fix: Form bypass method

= 1.9.241 - 08/07/2024 =
* Added: Setting in file upload field that allows files to be uploaded to Media Library upload path
* Added: Fathom conversion event can now include WS Form variables
* Added: $temp_path added to wsf_action_email_attachments filter hook
* Added: Improved capabilities checks for customize tool
* Added: get_header method added to WS_Form_Submit_Export class
* Bug Fix: Required field string translation

= 1.9.240 - 08/02/2024 =
* Bug Fix: Progress bar in Bootstrap framework

= 1.9.239 - 07/31/2024 =
* Added: RTL support for the Send Email action
* Bug Fix: Conditional logic bug fix

= 1.9.238 - 07/29/2024 =
* Added: Google Address field validation to ensure a valid address has been selected (Enabled by default, but can be disabled in Advanced > Validation)
* Added: Improved message return action handling for Run WordPress Hook action
* Added: cookie_set return action added Run WordPress Hook action
* Bug Fix: Conditional logic sidebar no longer jumps when adding new conditions
* Bug Fix: Data grid import if ID's are blank
* Bug Fix: Repeat form submission user duration tracking
* Bug Fix: Capability check on db_draft method

= 1.9.237 - 07/26/2024 =
* Bug Fix: Redirect on Run WordPress Hook

= 1.9.236 - 07/26/2024 =
* Added: Ability to use #field in repeaters for custom filenames in File Upload field
* Added: Updated Divi form selector code
* Changed: Default form font size changed to 16px to improve accessibility
* Bug Fix: Fixed date / time handling in Metabox date field population
* Bug Fix: Date field initial validation

= 1.9.235 - 07/22/2024 =
* Bug Fix: Fix for Bricks styler

= 1.9.234 - 07/22/2024 =
* Added: Variable helper added to conversion tracking action
* Bug Fix: Permission error on Save Submission auto expiry cron job

= 1.9.233 - 07/22/2024 =
* Bug Fix: Visual builder form list population

= 1.9.232 - 07/21/2024 =
* Added: Integraton support for DataKit https://www.datakit.org/
* Added: Quizzes form template category
* Change: Submissions form selector now only shows forms with submissions

= 1.9.231 - 07/19/2024 =
* Bug Fix: Action variable parsing

= 1.9.230 - 07/19/2024 =
* Added: Support for object return data from Salesforce
* Bug Fix: Removed empty elements in parse variable lookup table

= 1.9.229 - 07/18/2024 =
* Added: New variable helper modal accessible from the # icon in the sidebar
* Added: WordPress 6.6 compatibility testing
* Bug Fix: Akismet agent parameter

= 1.9.228 - 07/15/2024 =
* Bug Fix: Parse variables date fixes

= 1.9.227 - 07/15/2024 =
* Bug Fix: Client-side date translation

= 1.9.226 - 07/14/2024 =
* Bug Fix: Conversational progress bar adjusted to use default max value of 1

= 1.9.225 - 07/13/2024 =
* Bug Fix: Google Tag Manager gtm.formSubmit element parameter compatibility issue with input mask component

= 1.9.224 - 07/12/2024 =
* Added: Referrer URL added to error emails
* Added: UK sort code regex pattern to examples dropdown and form template
* Bug Fix: Migrate function image processing
* Bug Fix: Removed conditional events from rating field that don't fire
* Bug Fix: Cloudflare Turnstile timeout message

= 1.9.223 - 07/05/2024 =
* Added: PHP 8.3 compatibility checks
* Added: Post data source parent post ID setting
* Added: Upgraded Google map marker to use AdvancedMarkerElement class
* Changed: Google map styling now uses Map ID and cloud styling (Required for AdvancedMarkerElement upgrade)
* Bug Fix: Improved Google map loaded handling
* Bug Fix: Updated customizer help text
* Bug Fix: Updated encryption library to remove deprecated messages
* Bug Fix: Updated Breakdance element to remove deprecated function

= 1.9.222 - 06/26/2024 =
* Added: Support for JetEngine fields using Post, User and Term queries in JetEngine data source

= 1.9.221 - 06/23/2024 =
* Added: Improved Progress demo template
* Added: Progress percentage forced rounding to improve presentation of custom values
* Changed: Progress field now defaults max to 1 instead of 100 to comply with HTML spec

= 1.9.220 - 06/17/2024 =
* Added: Improved #calc processing
* Changed: WS Form dashboard widget now requires read_submission and read_form capabilities
* Bug Fix: HTML stripping removed from textarea when using #calc or #text
* Bug Fix: Progress bar when max set to value other than 100
* Bug Fix: Implemented workaround for jQuery mobile causing invalid and valid pseudo classes to fail
* Bug Fix: message_hide parameter on Run WordPress Hook when using message action

= 1.9.219 - 06/12/2024 =
* Added: Additional debug output for reCAPTCHA HTTP request failures
* Bug Fix: PHP 7.2 sprintf issue

= 1.9.218 - 06/05/2024 =
* Added: Mark as Read setting on Save Submission setting
* Added: OWASP guideline compliance for CSV exports: https://owasp.org/www-community/attacks/CSV_Injection

= 1.9.217 - 06/01/2024 =
* Added: Improved performance of tracking variables
* Bug Fix: #email_tracking

= 1.9.216 - 06/01/2024 =
* Bug Fix: Select default value via pre_render hook

= 1.9.215 - 05/31/2024 =
* Added: Full URL and hash options added to tracking
* Bug Fix: Metabox taxonomy advanced field type fix
* Bug Fix: Radio validation selector

= 1.9.214 - 05/28/2024 =
* Added: Webhook "Process Response" setting
* Added: Webhook wsf_action_webhook_http_headers and wsf_action_webhook_http_payload filter hook
* Added: #hash_md5 and #hash_sha256 server-side variables
* Added: Improved error email handling
* Bug Fix: Typo in Membership Application form template
* Bug Fix: Hidden field change event firing in conditional logic

= 1.9.213 - 05/21/2024 =
* Added: Improved handling of default max file upload setting
* Added: Webhook response added to debug console log
* Added: Improvements to system table in settings

= 1.9.212 - 05/19/2024 =
* Added: Form level invalid feedback text setting
* Added: Webhook response code mapping setting
* Added: Updated translations
* Changed: Default invalid feedback message changed to "This field is required."
* Bug Fix: Radio invalid feedback positioning when using horizontal orientation

= 1.9.211 - 05/17/2024 =
* Bug Fix: Double HTML encoding issue with #text
* Bug Fix: Akismet integration fix

= 1.9.210 - 05/14/2024 =
* Bug Fix: Radio field accessibility handling in conversational forms

= 1.9.209 - 05/13/2024 =
* Added: Always Visible setting on Loader
* Added: Improved Always Visible position calculations
* Added: street-address auto complete option added to Text fields
* Added: ACPT Country field type support
* Bug Fix: Signature processing for Media Library handler

= 1.9.208 - 05/07/2024 =
* Added: ACPT integration support for Post Management (1.6.0) add-on
* Added: ACPT integration support for User Management (1.6.0) add-on
* Added: ACPT data source
* Added: Pattern testing template
* Added: Accessibility improvement for radio fields on conversational forms
* Changed: Pods file handler now uses media library
* Changed: LITE support requests now directed to wordpress.org support forum
* Changed: #acf_repeater_field variable discontinued in favor of Post Management add-on
* Bug Fix: Debug population of number fields with min, max and step
* Bug Fix: Various updates to accommodate braking changes in ITI library
* Bug Fix: Conversational form vertical scrolling on focussed fields

= 1.9.207 - 04/29/2024 =
* Added: Inline validation "On field change / blur" setting that will only show inline validation after a field is changed
* Added: Set custom validation conditional action for rating field
* Bug Fix: ITI default country

= 1.9.206 - 04/22/2024 =
* Changed: Event property when determining source DOM element

= 1.9.205 - 04/21/2024 =
* Bug Fix: Removed debug from wsf_field_get_objects function

= 1.9.204 - 04/20/2024 =
* Bug Fix: Fix to WS Form event firing

= 1.9.203 - 04/20/2024 =
* Added: PHP functions to retrieve submit value by field class name (https://wsform.com/knowledgebase_category/php-functions/)
* Added: Fathom conversion tracking value now supports WS Form variables
* Added: Exclude Blank Parameters setting in Redirect action for query string parameters
* Added: Checkboxes can now be unchecked with conditional logic if disabled
* Bug Fix: Conditional event firing

= 1.9.202 - 04/16/2024 =
* Added: Focus conditional action for rating fields
* Added: Dragend event for Google Address map geolocate
* Changed: Conversational form error alerts now positioned above nav to improve legibility
* Bug Fix: Loader sprite color

= 1.9.201 - 04/14/2024 =
* Added: Loader now always visible automatically regardless of form height
* Added: New min / max variables for series of fields (See: https://wsform.com/knowledgebase/calculating-minimum-and-maximum-field-attributes/)
* Added: Toggle for enabling geolocate on load for Google Address field
* Added: New condition to run geolocate on Google Address field
* Added: Gelocate on click setting for map associated with Google Address field
* Added: Geolocate event under loader settings

= 1.9.200 - 04/11/2024 =
* Added: WordPress 6.5 compatibility testing
* Added: Google Address field auto complete by browser location or IP lookup
* Added: #file_repeater_index for custom file names
* Bug Fix: RTL CSS fixes
* Bug Fix: Select2 population by array

= 1.9.199 - 03/24/2024 =
* Added: ACF meta mapping in post data source now outputs as formatted value
* Bug Fix: Row population in submission admin page
* Bug Fix: Small typo in WordPress block

= 1.9.198 - 03/18/2024 =
* Added: Default value setting on select, checkbox and radio fields

= 1.9.197 - 03/17/2024 =
* Added: Transform on email field

= 1.9.196 - 03/03/2024 =
* Bug Fix: Date handling in custom field integrations when populating a form

= 1.9.195 - 03/01/2024 =
* Bug Fix: Updated ITI flag images

= 1.9.194 - 02/29/2024 =
* Added: #acos, #asin, #atan variables
* Added: Update ITI libary
* Added: wsf_form_checksum_check filter hook
* Added: #calc performance improvement by reducing duplicate calculations
* Changed: Removed transform setting on email field due to incompatibility
* Bug Fix: Repeatable section changes breaking #calc array
* Bug Fix: Fix to #calc handling of price range fields

= 1.9.193 - 02/12/2024 =
* Added: Support for Save on WooCommerce product with variations
* Bug Fix: Progress calculation for checkbox fields with min / max parameters
* Bug Fix: Submissions page selector
* Bug Fix: JetEngine data source when using glossary

= 1.9.192 - 02/08/2024 =
* Bug Fix: Meta data generation when creating form from action.

= 1.9.191 - 02/07/2024 =
* Bug Fix: Run JavaScript action

= 1.9.190 - 02/07/2024 =
* Bug Fix: Minor code change in submit classes to maintain compatibility with EOL PHP versions

= 1.9.189 - 02/05/2024 =
* Added: wsf-tab-clicked event
* Added: New functionality required for Webex integration
* Added: Improved Italian translations
* Bug Fix: Field level deduplication issue
* Bug Fix: Run JavaScript actions would fail if a comment was present at the end

= 1.9.188 - 02/01/2024 =
* Added: Google Address Test template that includes all mappings
* Added: Performance improvement in editor when outputting section template JSON
* Bug Fix: wsf_submit_validate now only runs once per form submit

= 1.9.187 - 01/27/2024 =
* Added: Improved checking of API key for Google field types
* Bug Fix: Time formatting fix in emails

= 1.9.186 - 01/22/2024 =
* Added: Limit by IP in Form Settings
* Bug Fix: Select2 cascade enqueue warning

= 1.9.185 - 01/21/2024 =
* Added: Submission error email notifications
* Added: Sortable setting on file upload field for DropzoneJS multiple configuration
* Added: Google address field now has same conditional logic options as text fields
* Added: Convert arrays to strings if mapped custom field is string based
* Added: wsf_submit_validate to compliment wsf_submit_field_validate filter hook
* Added: Bulk action in data grid now retains checked rows to make toggling features on and off easier
* Bug Fix: Week date format in emails
* Bug Fix: Translated dates in email_submission

= 1.9.184 - 01/16/2024 =
* Added: Bulk select CSV Export in submissions now uses paged process to improve performance

= 1.9.183 - 01/16/2024 =
* Bug Fix: Submission export paging

= 1.9.182 - 01/15/2024 =
* Added: Improved debug console population of first and last name fields using library of names
* Added: Improved debug console population of emails with first and last name and more random domain name
* Added: Improved debug console population of select and checkboxes with support for multiple, min and max settings
* Added: Get Data in Data Sources now clears last error log if successful
* Added: Improved template file error handling
* Changed: Number step setting moved to basic tab with improved help text
* Changed: Improvements to submission filtering and exporting code
* Changed: Removed Hidden setting from captcha fields due to misinterpration
* Changed: Tag Mapping to Term Mapping in Form Settings > Data > Populate Using Action
* Bug Fix: Submissions export with date filters applied in languages other than English
* Bug Fix: Required checkbox rows now excluded from validation if hidden
* Bug Fix: Clear button on signatures if prefix applied to field

= 1.9.181 - 01/07/2024 =
* Added: Conversion Tracking action now support Bento JavaScript events
* Added: Conditional action for increasing / decreasing numeric based field values by a given step or WS Form variable
* Added: Cloudflare Turnstile size and appearance settings
* Added: Updated French translations

= 1.9.180 - 01/04/2024 =
* Added: Google map auto centering options (By browser or IP geo lookup)
* Bug Fix: Workaround implemented for PHP ZEND_FETCH_DIM_RW bug (Not a WS Form bug)
* Bug Fix: Cookie decodeURIComponent handling
* Bug Fix: Phone field now has inputmode="tel" attribute to ensure input make library does not break keyboard on mobile
* Bug Fix: Deduplication functionality now only checks submissions with a status of publish

= 1.9.179 - 12/27/2023 =
* Added: Submissions keyword search
* Added: Improved error handling for cookie and query string parsing
* Bug Fix: Conditional logic case sensitivity on select, checkbox and radio value conditions

= 1.9.178 - 12/17/2023 =
* Added: Ignore empty key value setting in Webhook action
* Added: Improved debug toggle script enqueuing
* Added: Defer setting for enqueued JavaScript
* Added: wsf_enqueue_script_strategy_defer and wsf_enqueue_script_in_footer filter hooks
* Added: Disable translation setting to force English (US)
* Added: Test All Fields template in Demos
* Bug Fix: Legal field with progress

= 1.9.177 - 12/10/2023 =
* Added: Performance improvement on preset loading and submit variables
* Added: Transform functionality now retains cursor position
* Added: Flush rewrite on welcome screen to improve REST API availability on new installs
* Bug Fix: Action repair on import
* Bug Fix: Case sensitive fix on Selected Value Equals condition
* Bug Fix: Typo in form import modal

= 1.9.176 - 11/26/2023 =
* Added: Support for Fathom Analytics in the Conversion Tracking action
* Added: Ability to enable and disable the debug console from the admin bar menu
* Added: Date From / To template in section library
* Added: Link to Files setting in global settings that allows file links in PDF templates
* Changed: Endpoint updated for ipapi.co
* Bug Fix: Inline validation when using tab validation

= 1.9.175 - 11/19/2023 =
* Added: Accessibility improvements - aria-invalid, aria-describedby for select and textarea fields

= 1.9.174 - 11/18/2023 =
* Added: Accessibility improvements - aria-invalid, aria-describedby for inputs
* Added: field_value action added to Run WordPress Hook action
* Bug Fix: Inside label offset calculations on fields with prefix now assessed when field is shown

= 1.9.173 - 11/16/2023 =
* Added: wsf_enqueue_scripts and wsf_enqueue_styles action hooks for enqueuing custom CSS or JavaScript when a form is present on a page
* Added: From address is now optional for Send Email action
* Added: Help text added to progress fields for Bootstrap and Foundation frameworks
* Bug Fix: Loader CSS was not enqueued if dynamic enqueuing was disabled

= 1.9.172 - 11/01/2023 =
* Added: New #submit_date_added and #submit_date_added_custom("format") variables
* Added: New wsf_submit_export_csv_header and wsf_submit_export_csv_row filter hooks
* Added: Default row set for ACF and Meta Box custom field data source
* Added: User Ratings Total mapping added for Google Address field
* Added: Save button in form label when focussed
* Bug Fix: Action setting direct numeric setting replacement during import
* Bug Fix: Undo meta key issue
* Bug Fix: wpautop on submissions in edit mode

= 1.9.171 - 10/25/2023 =
* Bug Fix: PHP 8.2 compatibility support
* Bug Fix: Admin form view order and orderby query variables now limited to fixed values to improve security

= 1.9.170 - 10/25/2023 =
* Added: New #tracking_duration variable for returning the time it took to submit the form in seconds

= 1.9.169 - 10/19/2023 =
* Added: New #field_count_char(id, "regex_filter") variable for returning the number of characters in a string
* Added: New #field_count_word(id, "regex_filter") variable for returning the number of words in a string
* Added: New "All rows selected" and "Not all rows selected" conditional logic IF conditions for select fields
* Added: New "All rows checked" and "Not all rows checked" conditional logic IF conditions for checkbox fields

= 1.9.168 - 10/11/2023 =
* Bug Fix: Row specific conditional logic

= 1.9.167 - 10/10/2023 =
* Added: Improved conditional logic debug logging
* Added: Improved syntax error output when evaluating variables
* Added: Improved handling or conditional logic select, checkbox and radio row handling
* Changed: DropzoneJS default placeholder text

= 1.9.166 - 10/04/2023 =
* Added: Improved handling of sidebar default values in admin
* Added: Changed captcha resetting to only occur on form submit

= 1.9.165 - 09/30/2023 =
* Bug Fix: Class meta key used on file and password fields

= 1.9.164 - 09/29/2023 =
* Bug Fix: Fields with suffix had formatting issue with new help text system

= 1.9.163 - 09/29/2023 =
* Added: Help text positioning (Top or bottom) that can be set at a form or field level

= 1.9.162 - 09/23/2023 =
* Added: Google routing field waypoint support
* Added: Cascade source now supports hidden fields
* Added: WordPress plugin standards compliance improvements
* Bug Fix: #file_index on custom file names

= 1.9.161 - 09/19/2023 =
* Added: New priority for Run WordPress Hook action: Before submission created
* Bug Fix: Number field step attribute when hidden

= 1.9.160 - 09/17/2023 =
* Added: Clear and reset conditional logic options for cart price fields
* Added: Improved handling of embedded variables in attributes on server-side

= 1.9.159 - 09/16/2023 =
* Added: File upload image restrictions for image max width and max height
* Added: File upload image restrictions now shown against each file when using DropzoneJS
* Added: Debug console populate function now obeys input masks
* Added: wsf_loaded hook
* Added: Form import now strips BOM if found
* Bug Fix: Debug console populate date fields with min / max ranges
* Bug Fix: Input mask enqueuing for currency if only cart detail / cart total fields present

= 1.9.158 - 09/13/2023 =
* Added: File upload image restrictions for image width, height and aspect ratio
* Added: Form limits without message types no support server-side WS Form variables

= 1.9.157 - 09/07/2023 =
* Added: Form limit messages now support server-side WS Form variables
* Bug Fix: ACF radio and button group meta value

= 1.9.156 - 09/06/2023 =
* Bug Fix: ACF post object mapping meta value

= 1.9.155 - 09/05/2023 =
* Added: ACF field validation override if validation filters return invalid response.
* Bug Fix: Phone field inside label positioning if ITI configured to use country code selector

= 1.9.154 - 08/23/2023 =
* Added: gtag automatically exposed to dataLayer for Google Tag Manager implementations
* Added: Legal field calculation improvements
* Added: Privacy improvements with author variables
* Bug Fix: Server-side Belgian French date translations
* Bug Fix: Conversational date picker

= 1.9.153 - 08/21/2023 =
* Added: Google Tag Manager (Data Layer) conversion type that only runs dataLayer.push(params)

= 1.9.152 - 08/18/2023 =
* Added: Custom attributes for hidden fields
* Added: Accessibility improvements in the layout editor (Improved color contrasts and ARIA attribute review)
* Added: Reduced page size when exporting submissions to reduce server memory usage
* Bug Fix: Language string issue when clicking to add fields

= 1.9.151 - 08/14/2023 =
* Added: Support for GA4 Enhanced Event Measurement form_start & form_submit events

= 1.9.150 - 08/09/2023 =
* Added: Added InstaWP to LITE readme.txt
* Bug Fix: Select2 default option preload in admin sidebar

= 1.9.149 - 08/08/2023 =
* Added: WordPress 6.3 compatibility testing
* Added: Additional functionality added to action form engine for InstaWP add-on

= 1.9.148 - 08/03/2023 =
* Bug Fix: Conditional logic on fields within repeatable sections with a source outside of a repeatable section

= 1.9.147 - 08/03/2023 =
* Added: Conditional logic actions for loader
* Added: Form instance style targeting for loader

= 1.9.146 - 08/02/2023 =
* Added: Loader / spinner system for form submit, save, action and render events
* Added: Debug console color improvements
* Bug Fix: Webhook custom mapping on legacy data
* Bug Fix: Signature resize event if hidden
* Bug Fix: Required indicator on legal field

= 1.9.145 - 07/28/2023 =
* Bug Fix: ACF custom field column mapping in posts data source

= 1.9.144 - 07/27/2023 =
* Bug Fix: Selector escaping in admin

= 1.9.143 - 07/26/2023 =
* Added: Session ID now automatically clears if the associated submission record has been trashed
* Added: Improved text area formatting in submissions sidebar
* Added: Improved JS selector escaping throughout
* Added: Post data source now supports reading field data contained within a group
* Bug Fix: If a tab is clicked using conditional logic it now shows the tab to ensure the fields are submitted

= 1.9.142 - 07/22/2023 =
* Added: Cascading select fields configured to use AJAX can now be pre-populated
* Added: Improved German translations in debug console
* Added: ID deduplication during data grid CSV import
* Added: Improved scroll detection for legal field

= 1.9.141 - 07/19/2023 =
* Added: Geolocation Lookup by IP in Form Settings that allows you to populate fields with gelocation data such as city and region
* Added: Two additional IP lookup endpoints; ipinfo.io and ipapi.co
* Added: Setting to include admin traffic in statistics data added to Basic tab of Global Settings

= 1.9.140 - 07/11/2023 =
* Bug Fix: Escaping issue in admin sidebar for disabling unique values in mapping repeaters

= 1.9.139 - 07/08/2023 =
* Added: Ability to set license keys using a named constant in wp-config.php https://wsform.com/knowledgebase/setting-license-keys-with-php-constants/

= 1.9.138 - 07/06/2023 =
* Added: AI generated form support for OpenAI add-on
* Added: Updated DropzoneJS to version 5.9.3
* Bug Fix: data-hidden-bypass fix

= 1.9.137 - 07/04/2023 =
* Bug Fix: Running calculations when repeatable section is moved up or down

= 1.9.136 - 07/03/2023 =
* Added: JetEngine checkbox data format updates

= 1.9.135 - 07/03/2023 =
* Added: Ability to use the form locate feature when using Bricks site builder
* Bug Fix: JetEngine user field options

= 1.9.134 - 06/29/2023 =
* Added: Support for non-English dates in submission date range search
* Added: Add Media button toggle for the visual editor text area type, only visible to logged in users with the upload_files capability
* Bug Fix: Initial tab validation if conditions existed

= 1.9.133 - 06/26/2023 =
* Added: Removed fonts.googleapis.com dependency in admin CSS
* Bug Fix: CSS for template SVG's enqueuing incorrectly

= 1.9.132 - 06/23/2023 =
* Added: Multiple setting for Radio fields in IF conditions
* Added: Paste as Text setting for text area visual editor to determine default state of Paste as Text button
* Added: Hooks to customize text area visual editor buttons https://wsform.com/knowledgebase/customize-the-visual-editor-toolbar/
* Bug Fix: Removed form-control class around captcha fields for Bootstrap 5

= 1.9.131 - 06/20/2023 =
* Added: Improved handling of Send Email action CC / BCC filters

= 1.9.130 - 06/13/2023 =
* Bug Fix: Removed use of :is pseudo element in hidden field handler

= 1.9.129 - 06/13/2023 =
* Bug Fix: Fixed regex example for Phone - General
* Bug Fix: Fixed required field management for conversational forms

= 1.9.128 - 06/10/2023 =
* Added: Additional Send Email action hooks for BCC, CC and Reply To settings
* Bug Fix: Conditional logic initial firing on duplicate elements

= 1.9.127 - 06/06/2023 =
* Bug Fix: Fixed JavaScript issue with form selector on submissions page

= 1.9.126 - 06/05/2023 =
* Added: Tab active / not active conditional logic
* Bug Fix: Output buffer clear fix

= 1.9.125 - 06/01/2023 =
* Added: Improved population of ACF number fields
* Bug Fix: Ensured variable escaping is not considered translatable throughout

= 1.9.124 - 05/29/2023 =
* Added: #number_format(number, decimals, decimal_separator, thousands_separator) variable
* Added: #field_date_Format(field_id, date_format) variable

= 1.9.123 - 05/28/2023 =
* Added: Support for pushing select, checkbox and radio values to Pods text fields
* Bug Fix: File upload handler setting featured image on posts if using default file upload type

= 1.9.122 - 05/26/2023 =
* Added: Required setting on price fields
* Added: Price fields converted to float prior to being pushed to ACF number fields

= 1.9.121 - 05/25/2023 =
* Added: Required indicator added to checkbox label if 'Minimum Checked' setting is set
* Added: Added additional field types that can be mapped to Google Address fields
* Added: Override CSS to overcome styling issues found with poorly targeted CSS from third party plugins
* Changed: Removed single pixel GIF that was used to overcome an old formatting issue with Mac mail

= 1.9.120 - 05/24/2023 =
* Added: Improved saved data format for ACF mappings

= 1.9.119 - 05/23/2023 =
* Bug Fix: Review admin message

= 1.9.118 - 05/22/2023 =
* Added: Support for additional classes on block
* Added: Ability to specify custom title, caption, description and alt tag on media library uploads
* Added: Translation improvements
* Changed: Block preview now changed to GIF as opposed to SVG to avoid CSS issues with preview being in iframe
* Changed: Captcha fields can no longer be bypassed if they are hidden to improve security
* Bug Fix: Progress on repeaters
* Bug Fix: Mobile breakpoint font size in email template
* Bug Fix: Down arrow on select fields no longer obscured by long option text

= 1.9.117 - 05/18/2023 =
* Added: Accessibility improvements for honeypot field
* Added: Accessibility improvements for help text
* Added: Repeatable section row delete confirmation setting
* Added: Translated month and day names during date validation to support other languages
* Bug Fix: Conditional logic initial firing on repeatable section row adding

= 1.9.116 - 05/13/2023 =
* Added: Multiple row selection in conditional logic for row visibility, required, disabled, class
* Bug Fix: Image / file custom field integration issue with existing attachments

= 1.9.115 - 05/13/2023 =
* Bug Fix: #calc initial value was causing issues with HTML fields

= 1.9.114 - 05/11/2023 =
* Added: Multiple row selection in conditional logic for row select/check
* Added: Support for Google Routing in repeatable sections
* Bug Fix: #field delimiter parameter was trimmed

= 1.9.113 - 05/09/2023 =
* Changed: Quantity min / max bound checking now only occurs on field change

= 1.9.112 - 05/08/2023 =
* Bug Fix: WS Form (Private) file handler in repeatable sections
* Bug Fix: PDF image width in custom templates

= 1.9.111 - 05/05/2023 =
* Added: Removed invalid event handler inputmask fields to fix blur issue if setCustomValidity applied to it

= 1.9.110 - 05/04/2023 =
* Added: CSS max height in email template for signature field to avoid large images showing
* Changed: Webhook no longer halts action processing if node not found in response data
* Bug Fix: Custom validity recall issue if set in conditional logic and field then hidden

= 1.9.109 - 05/03/2023 =
* Bug Fix: #calc price value

= 1.9.108 - 05/02/2023 =
* Added: Input mask validation

= 1.9.107 - 05/01/2023 =
* Added: zxcvbn no longer enqueued if not required by password field
* Added: Improved handling of UTC date for 'Current Day' deduplication setting
* Bug Fix: Uncompiled non-inline API CSS path

= 1.9.106 - 04/25/2023 =
* Added: Flush on output if zip or file downloaded to overcome issues with rogue character in output buffer
* Added: Admin sidebar changes to prevent issues with poorly enqueued jQuery from third party plugins
* Bug Fix: Admin sidebar would not open if form text editor or HTML field contained wsf-field-wrapper class
* Bug Fix: #calc issue with calc object registering incorrect source field ID

= 1.9.105 - 04/24/2023 =
* Added: Support for JetEngine checkbox 'Save as Array' setting
* Bug Fix: #calc support in #field_date_offset

= 1.9.104 - 04/22/2023 =
* Added: Elementor widget updates

= 1.9.103 - 04/21/2023 =
* Added: Performance improvements for #calc / #text

= 1.9.102 - 04/21/2023 =
* Added: Support for JetEngine 'Is Timestamp' setting on date fields
* Bug Fix: Email validation

= 1.9.101 - 04/18/2023 =
* Added: Improved data source error handling and scheduling
* Added: Overrides for latest WordPress block editor CSS

= 1.9.100 - 04/17/2023 =
* Added: 'Importing ...' notice added to import progress bar
* Bug Fix: Calculation triggering on hidden fields

= 1.9.99 - 04/17/2023 =
* Added: Edit in Preview now supports file upload edits
* Added: Improved performance on file upload previews

= 1.9.98 - 04/16/2023 =
* Bug Fix: Dynamic #text label and help text on fields that did not allow dynamic #text on value
* Bug Fix: Statistics divide by zero issue

= 1.9.97 - 04/14/2023 =
* Added: Google Routing field. Provides distance and duration calculations between a start and end location. Learn more: https://wsform.com/knowledgebase/google-route/
* Added: Google Maps template category
* Added: Google Routing templates
* Added: Mapping category in toolbox fields sidebar

= 1.9.96 - 04/12/2023 =
* Added: Support for plain permalink URL structure
* Added: Improved #email_logo handling to avoid bugs caused by third party software
* Bug Fix: #text with color fields now sets color panel preview

= 1.9.95 - 04/10/2023 =
* Bug Fix: Reports scheduling when upgrading from LITE to PRO

= 1.9.94 - 04/10/2023 =
* Added: Type selector for WebHook field and custom mapping

= 1.9.93 - 04/07/2023 =
* Bug Fix: Checkbox and radio deduplication in repeatable sections

= 1.9.92 - 04/06/2023 =
* Added: Additional checks to ensure data passed to remote requests is valid
* Added: Support for roles that do not have capabilities assigned to them
* Bug Fix: Conditions no longer assesssed if source event is outside of repeatable section and IF condition contains fields in a repeatable section

= 1.9.91 - 04/05/2023 =
* Bug Fix: Dashboard date range fix

= 1.9.90 - 04/04/2023 =
* Added: WS Form --> Settings --> Reports setting allows for daily, weekly or monthly form statistics report sent via email
* Added: 'Show File Name and Size' setting under WS Form --> Settings --> Variables which removes file name and size under files and signatures in email and PDF templates
* Added: Signatures now recalled in saved forms
* Added: Date/time picker now has setting for moving the element within the form to avoid issues with parent elements having position: relative
* Changed: Signature cropping now moved server-side (Requires Imagick)
* Changed: WS Form --> Settings --> Email is now Variables
* Bug Fix: Patch implemented to overcome Safari issue with times

= 1.9.89 - 04/03/2023 =
* Added: Moved date/time picker element within form instead of within body to allow styling by form
* Added: Added ID to each date/time picker element
* Added: Custom class setting for date/time picker element

= 1.9.88 - 03/31/2023 =
* Added: Performance improvements to select, checkbox and radio fields (empty row attributes removed)
* Added: Performance improvements to repeatable section initialization
* Added: Performance improvements to form validation
* Added: Functionality for new PDF add-on variables #pdf_url and #pdf_link
* Bug Fix: WS Form framework was not being used in block editor for layout CSS if using Bootstrap
* Bug Fix: Section ID repair in imported meta data

= 1.9.87 - 03/29/2023 =
* Added: WordPress 6.2 compatibility testing
* Added: Requirements added to main PHP file
* Bug Fix: Capitalize transform feature was not working for some european characters

= 1.9.86 - 03/29/2023 =
* Changed: Simplified Human Presence functionality to rely on its own confidence level

= 1.9.85 - 03/28/2023 =
* Bug Fix: Layout editor no longer saves if sidebar is locked (e.g. requesting data from an API)

= 1.9.84 - 03/26/2023 =
* Added: Update ready for Breakdance v1.3
* Added: Check on SVG creation to ensure form object is valid

= 1.9.83 - 03/23/2023 =
* Bug Fix: JetEngine media and gallery field population

= 1.9.82 - 03/23/2023 =
* Added: Improved button locking functionality
* Added: Improved form checksum validation on import
* Bug Fix: Media library meta data when custom file name specified
* Bug Fix: Webhook encoding of values containing arrays

= 1.9.81 - 03/18/2023 =
* Added: Patch to ensure adding parameters to API URL using add_query_arg does not convert periods to underscores

= 1.9.80 - 03/18/2023 =
* Bug Fix: API call fix for AWeber

= 1.9.79 - 03/17/2023 =
* Added: Spam level indicators added to LITE edition
* Added: Additional admin CSS fixes for third party plugin that incorrectly enqueued Bootstrap on every page
* Bug Fix: Submission records were still created if Human Presence detected spam

= 1.9.78 - 03/15/2023 =
* Added: PDF previews on DropzoneJS file uploads
* Added: Ability to preview / download DropzoneJS file uploads in submissions when form saved
* Added: Admin CSS fixes for third party plugin that incorrectly enqueued Bootstrap on every page

= 1.9.77 - 03/10/2023 =
* Bug Fix: db_get_submit_meta duplicating values if run via WooCommerce extension

= 1.9.76 - 03/09/2023 =
* Added: Webhook API response mapping now supports array in JSON response
* Added: Webhook now has improved handling of query variables in GET requests

= 1.9.75 - 03/08/2023 =
* Added: Improved Google Events functionality https://wsform.com/knowledgebase/google-events/
* Added: Improved handling of repeatable fallback values on submissions page 

= 1.9.74 - 03/06/2023 =
* Bug Fix: Label positioning adjustment on phone fields

= 1.9.73 - 03/02/2023 =
* Added: Support for glossaries in JetEngine Field Options data source
* Added: Set minimum/maximum date and time conditional logic for date/time field type
* Added: Enabled times feature on date/time field type
* Added: Separated min/max time setting on date/time field configured as type 'Date/time' or 'Time'
* Added: Field match conditional logic support for values inside a repeater
* Bug Fix: CSS fix for Phone field configured to use inside label with international telephone input enabled

= 1.9.72 - 02/28/2023 =
* Added: Context to JetEngine get field settings method to support user fields

= 1.9.71 - 02/27/2023 =
* Added: Set title of email template to email subject
* Added: Default file type set to DropzoneJS on file upload fields
* Bug Fix: Custom file name on media library files

= 1.9.70 - 02/22/2023 =
* Added: Patch to overcome older browsers not supporting date formats in Y-m-d H:i:s format

= 1.9.69 - 02/21/2023 =
* Added: Form validation when required status of field changes
* Bug Fix: Redirect action URL parsing

= 1.9.68 - 02/17/2023 =
* Added: Additional functionality for PDF add-on

= 1.9.67 - 02/15/2023 =
* Bug Fix: ITI (International Telephone Input) for multiple form instances on a single page

= 1.9.66 - 02/14/2023 =
* Added: Ability to assign multiple values to a single key in Webhook (converted to array with dedupe setting)
* Added: wsf_dropzonejs_upload_path filter for DropzoneJS upload path
* Added: Dependencies for DropzoneJS enqueue

= 1.9.65 - 02/07/2023 =
* Added: Improved rendering of conditional logic in admin sidebar
* Added: Support for population of hidden fields for Google Address field
* Added: Edit in Preview link on submissions (Must be enabled in settings)

= 1.9.64 - 02/06/2023 =
* Added: Meta Box custom table support
* Bug Fix: Changing a hidden required field to no required was reverted to required with made visible
* Bug Fix: Dashboard component registration if user capability not set

= 1.9.63 - 02/05/2023 =
* Added: Event resetting on form reload
* Bug Fix: #calc / #text now processes original source instead of parsed source 
* Bug Fix: Conditional logic on conversational forms

= 1.9.62 - 02/03/2023 =
* Added: Patch to overcome known bug with :valid / :invalid selectors in certain browsers

= 1.9.61 - 02/03/2023 =
* Added: Support for population of select fields configured with Select2 + AJAX
* Added: Custom attributes for divider field type
* Bug Fix: Field class setting for divider field type

= 1.9.60 - 02/02/2023 =
* Bug Fix: Setting values in textarea fields using TinyMCE

= 1.9.59 - 02/02/2023 =
* Bug Fix: WooCommerce extension file upload field processing
* Bug Fix: Sidebar select2 caching issue

= 1.9.58 - 02/01/2023 =
* Bug Fix: Setting price values with conditional logic or Webhook return

= 1.9.57 - 01/30/2023 =
* Bug Fix: reCAPTCHA V3 timeout issue

= 1.9.56 - 01/29/2023 =
* Bug Fix: Setting values on hidden fields with conditional logic or Webhook return

= 1.9.55 - 01/28/2023 =
* Added: Saved File Name setting for file uploads to allow for custom file names

= 1.9.54 - 01/27/2023 =
* Bug Fix: Setting label on button configured as submit using conditional logic

= 1.9.53 - 01/26/2023 =
* Added: Forced 200 HTTP response if API requests successful (third parties were changing this in hook)
* Added: Firefox JS number rounding bug patch (their number field is limited to 14 decimal places if certain languages are set)
* Added: Change to node value set function for Webhook function to avoid error if node key used twice in mappings
* Bug Fix: Sidebar select2 initialization issue

= 1.9.52 - 01/25/2023 =
* Added: Improved CSS escaping for skin output
* Bug Fix: Conversational progress bar

= 1.9.51 - 01/25/2023 =
* Bug Fix: Tab validation showing validation errors was not working with Bootstrap framework
* Bug Fix: get_user_id function

= 1.9.50 - 01/24/2023 =
* Changed: Push to Custom Endpoint renamed to Webhook
* Added: Return field mapping functionality on Webhook action allows return from API requests to populate form fields
* Added: Support for dot notation in Webhook field, custom and return mapping (e.g. result[0].name.first)
* Added: SSL Verify, Timeout and Cookie Passthrough options to Webhook
* Added: Webhook Return Mapping demo template
* Added: Change event to Hidden field conditional logic
* Bug Fix: Select2 AJAX results conditional logic no longer fires on form render

= 1.9.49 - 01/23/2023 =
* Bug Fix: Next tab button disabling using prop method

= 1.9.48 - 01/22/2023 =
* Added: JetEngine integration support for Post Management (1.5.0) add-on
* Added: JetEngine integration support for User Management (1.5.0) add-on
* Added: JetEngine field option data source
* Added: Select2 custom message settings
* Added: Select2 AJAX results conditional logic
* Added: Improved custom action detection

= 1.9.47 - 01/19/2023 =
* Bug Fix: wpautop on text/plain content type emails

= 1.9.46 - 01/19/2023 =
* Added: Additional parameters added to cookies when set 'SameSite=Strict; Secure'
* Bug Fix: ACF button group saving

= 1.9.45 - 01/18/2023 =
* Bug Fix: Button disabling with WooCommerce extension

= 1.9.44 - 01/17/2023 =
* Added: ip-api as a geolocation service
* Added: geoPlugin / ip-api API key settings for commercial versions of geolocation services
* Added: Improved geolocation request headers to avoid invalid usage messages
* Added: Google Address component mapping options added for Street full - Short/Long (Reverse order)
* Bug Fix: Submission not assessing available fields correctly when layout only fields or user role restrictions imposed

= 1.9.43 - 01/16/2023 =
* Added: Updated Elementor widget registration code for version 3.5+
* Added: Updated PHP sort functions

= 1.9.42 - 01/15/2023 =
* Added: Meta value sorting option for posts data source

= 1.9.41 - 01/13/2023 =
* Added: New support functions for OpenAI add-on

= 1.9.40 - 01/09/2023 =
* Bug Fix: Deduplication using 'Current Day' method

= 1.9.39 - 01/09/2023 =
* Added: Do not apply wpautop functionality to client side #field use
* Added: Ability to use HTML in #field delimiter

= 1.9.38 - 01/08/2023 =
* Changed: Removed fitBounds event when Google Map search place is clicked on to stop zoom irregularities

= 1.9.37 - 12/31/2022 =
* Bug Fix: International telephone input threw error if form submitted too quickly (e.g. with conditional logic)

= 1.9.36 - 12/28/2022 =
* Added: Improved mark-up in email templates

= 1.9.35 - 12/25/2022 =
* Bug Fix: wpautop on submissions page

= 1.9.34 - 12/21/2022 =
* Added: Server side validation now applies to all mappable fields
* Changed: wpautop is now applied to Text Area fields configured as 'Default' when output in actions (e.g. Emails). #wpautop is no longer required to format this content. A 'Do Not Apply wpautop' setting is also available under 'Advanced' for Text Area fields if you want to revert to an unformatted output.

= 1.9.33 - 12/16/2022 =
* Added: Performance improvement on form submit

= 1.9.32 - 12/13/2022 =
* Added: Sidebar lock when retrieving integration data
* Bug Fix: CodeMirror mode for JavaScript editors

= 1.9.31 - 12/09/2022 =
* Added: Global setting for reCAPTCHA, hCaptcha and Turnstile keys

= 1.9.30 - 12/05/2022 =
* Bug Fix: Captcha silent validation

= 1.9.29 - 12/02/2022 =
* Added: Further improvements to license management

= 1.9.28 - 12/01/2022 =
* Added: Improved licensing management to avoid license deactivation on DB locks

= 1.9.27 - 11/30/2022 =
* Bug Fix: Tags label when creating form from action template

= 1.9.26 - 11/29/2022 =
* Added: Improved honeypot field for accessibility
* Bug Fix: Logging error in LITE edition for captcha fields

= 1.9.25 - 11/25/2022 =
* Added: Performance improvement on client side. Custom field configuration no longer loading in data sources.
* Added: Support for serialized data in submissions page when added to hidden field meta data

= 1.9.24 - 11/18/2022 =
* Added: Support for WooCommerce booking plugin

= 1.9.23 - 11/16/2022 =
* Added: Validate number setting under international telephone input setting on phone field
* Added: #session_storage_get(key) variable
* Added: #local_storage_get(key) variable
* Added: Placeholder setting to date / time field

= 1.9.22 - 11/15/2022 =
* Bug Fix: Form validation on Select2 + AJAX pre-populated fields

= 1.9.21 - 11/15/2022 =
* Added: Support for Meta Box relationship fields
* Bug Fix: Field / tab focus on form submit

= 1.9.20 - 11/12/2022 =
* Added: Ability to use min/max with repeatable #field(123) input
* Changed: Progress bar CSS to ensure right border radius appears at 100%

= 1.9.19 - 11/10/2022 =
* Change: Duration cookie removed in LITE edition
* Bug Fix: Next tab validation to show invalid feedback if tabs were hidden

= 1.9.18 - 11/09/2022 =
* Added: Support for dynamic enqueuing on payment forms that don't include e-commerce fields

= 1.9.17 - 11/09/2022 =
* Bug Fix: wsf_field_row_add function fixed

= 1.9.16 - 11/07/2022 =
* Added: Breakdance website builder element
* Added: Ability to use variables in the custom endpoint action URL
* Bug Fix: Field step bypass on hidden fields 

= 1.9.15 - 11/02/2022 =
* Bug Fix: Data format fix for Meta Box select fields without multiple setting enabled

= 1.9.14 - 10/30/2022 =
* Added: Support for WooCommerce geolocalize users with cache
* Added: wsf_form_get_count_submit function for returning total public form submissions by form ID

= 1.9.13 - 10/29/2022 =
* Added: Support for quantity values being populated using different decimal separators
* Bug Fix: Quantity field with decimals when using character other than period for decimal separator

= 1.9.12 - 10/27/2022 =
* Changed: Database primary key and relational field types changed to match WordPress
* Bug Fix: Show dynamic enqueuing setting for all frameworks

= 1.9.11 - 10/26/2022 =
* Added: Honeypot SEO improvement
* Bug Fix: Submissions read/unread display
* Bug Fix: wpautop JavaScript function

= 1.9.10 - 10/22/2022 =
* Bug Fix: Conditional logic for setting repeatable section rows

= 1.9.9 - 10/22/2022 =
* Added: wsf_form_get_fields_by_label function
* Bug Fix: JavaScript action registration

= 1.9.8 - 10/21/2022 =
* Added: unfiltered_html capability extended to data grids

= 1.9.7 - 10/20/2022 =
* Added: Compliance with the unfiltered_html capability which prevents saving of unfiltered HTML on all object data (https://wsform.com/knowledgebase/preventing-users-from-saving-markup-in-the-layout-editor/)
* Bug Fix: Default button colors
* Bug Fix: Cascade dynamic enqueuing

= 1.9.6 - 10/19/2022 =
* Added: Reviewed wpdb functionality throughout
* Added: Improved DropzoneJS error handling
* Added: Form and submit objects to email filters
* Bug Fix: Submission export animated gif

= 1.9.5 - 10/18/2022 =
* Added: Support for performance plugins with dynamic enqueue
* Changed: Removed WP_List_Table class to rely on class included with WordPress core
* Bug Fix: Password confirmation conditional logic if password strength invalid feedback enabled

= 1.9.4 - 10/17/2022 =
* Bug Fix: GeoIP lookup endpoint
* Bug Fix: Tab indexing on refresh

= 1.9.3 - 10/17/2022 =
* Bug Fix: Date variables without date fields

= 1.9.2 - 10/17/2022 =
* Added: #field with delimiter support for price select, price checkbox and price radio fields
* Added: Quality and price total auto map on clone
* Bug Fix: Tab indexing on clone

= 1.9.1 - 10/16/2022 =
* Added: Reviewed santitization, escaping and validation throughout
* Added: Improved code to make security reviews easier in future

= 1.9.0 - 10/14/2022 =
* Added: Dynamic enqueuing setting (Global settings)
* Added: Public JS optimized throughout to dramatically reduce file download size
* Added: Minimum password strength setting on password field
* Added: Suggest password setting on password field
* Added: Generate password option in conditional logic for password fields
* Bug Fix: Population of price checkbox fields
