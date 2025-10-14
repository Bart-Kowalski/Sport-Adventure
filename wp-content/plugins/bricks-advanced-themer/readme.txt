=== Advanced Themer ===
Contributors: Maxime Beguin
Tags: bricks, bricks builder
Requires at least: 4.7
Tested up to: 10.0.0
Requires PHP: 5.6
License: GPL3
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

Boost your Bricks websites. Build faster, easier, and better!

== Description ==

Advanced Themer levels up your efficiency in building websites with Bricks thanks to dozens of productivity hacks designed to facilitate your development process.

== Installation ==

1. Click on the download link in your purchase confirmation email if you have not already downloaded it after your purchase.

2. Download the plugin's zip file.

3. Go to Plugins > Add New in your WordPress admin. Click "Add New" button, then "Upload Plugin" button, then "Choose File", browse to and select the plugin's zip file.

4. Activate the plugin.

5. Enter the license key and activate your plugin license at Bricks → AT - License.

Valid license key should be entered for the plugin to function and to receive automatic updates.


== Changelog ==
= 3.3.7 ( AUG 29, 2025 )
* FIX: Plain Classes: classes were updated only when "bulk edit" was active

= 3.3.6 ( AUG 28, 2025 )
* NEW: Strict Editor View: Hide Quick Access Bar in the Element Panel
* NEW: Strict Editor View: Hide Search Box in the Element Panel
* NEW: Strict Editor View: Hide Element States in the Structure Panel
* NEW: Strict Editor View: Hide Header in the Element Panel
* NEW: Strict Editor View: Hide Element Actions in the Preview Window
* NEW: Strict Editor View: Disable Contextual Menus
* IMPROVE: Plain Classes now supports Bulk Edit
* IMPROVE: GPT-5 / GPT-Mini / GPT-Nano added to the AI models
* IMPROVE: Removed StickyCSS, Visibility Bar, and Responsive Slider/Helper as active by default
* IMPROVE: Advanced CSS: page.css code is now written in the base breakpoint only
* IMPROVE: Content/Style Tabs & Control Group Contextual Menu: smarter handling of element settings when a global class is active
* IMPROVE: Convert values to Logical Properties: new option in the converter to revert changes back to directional properties
* FIX: Focus on First Unlocked Class: could miss the focus
* FIX: Locked Class Indicator stopped working correctly
* FIX: The class lock icon wasn’t clickable when Plain Classes was set as the default class picker
* FIX: SuperPowerCSS: could fail to save the custom CSS
* FIX: Generate Global Query wasn't showing up in the Query Popup anymore
* FIX: Global Query List icon wasn't showing next to the Query Loop icon anymore
* FIX: When opening the Class Manager, the custom CSS of the previously selected class could appear blank
* FIX: Adding custom CSS to a class through the Class Manager could fail to render correctly
* FIX: display:none applied on the Darkmode elements removed the element from the accessibility tree
* FIX: Structure Generator: Dropping an image inside the dropzone wasn't correctly sending the image to the prompt
* DEPRECATED: Strict Editor View: removed toolbar icons related to Role Capabilities (since Bricks 2.0)
* DEPRECATED: Strict Editor View: Disable clickable elements in the iframe (since Bricks 2.0)
* DEPRECATED: Strict Editor View: Reduce Left Panel Visibility
* DEPRECATED: Code Element Tweaks (since Bricks 2.0)

= 3.3.5 ( JUL 25, 2025 )
* IMPROVE: Structure header icons got the same styles as the Left panel header icons
* IMPROVE: Pasting from new contextual menu's is supporting bulk edit
* FIX: Scss compiled on frontend even when the option was turned off in the settings
* FIX: ACF was stripping HTML code inside the Theme Settings
* FIX: Some content inside Control Groups were unclickable

= 3.3.4 ( JUL 24, 2025 )
* NEW: Double-click to Edit Components
* NEW: Remove Classes on Delete
* NEW: Control Group Contextual Menu
* NEW: Content/Style Tabs Contextual Menu
* IMPROVE: Class Preview on Hover now supports multiple elements selected
* IMPROVE: Focus Mode: Jump to parent element
* IMPROVE: Focus Mode: Apply Focus in the iframe
* IMPROVE: Focus Mode: Trigger focus mode by clicking an element inside the iframe
* IMPROVE: SuperPowerCSS: layout shift improved when switching elements
* IMPROVE: Class Contextual Menu: new option to convert all children of a container to logical properties
* FIX: empty UX Builder Settings could generate a JS error that break AT functions
* FIX: Class Preview on hover wasn't working anymore
* FIX: SuperPowerCSS: controls group layout broken when Control Group Visibility was set to "always" in the Bricks Settings
* FIX: Color Manager: Switch to darkmode wasn't applying anymore
* FIX: Editing a class could conflict with right shortcut keyboard shortcuts
* SECURITY: ACF updated to v6.4.3
* DEPRECATED: Strict Editor View: Remove Template & Settings links everywhere (Bricks 2.0)
* DEPRECATED: Strict Editor View: Disable Header & Footer edit button on hover (Bricks 2.0)

= 3.3.3 ( JUL 18, 2025 )
* IMPROVE: New Global option to save builder UX settings in the database and override Local Storage settings
* IMPROVE: New import/export/reset options to builder UX settings
* IMPROVE: New reset option to remove AT's Local Storages
* FIX: A JS error could occur when an element is connected to a missing component
* FIX: Fixed a PHP Warning: Undefined array key "enable_page_transition_page" in frontend.php
* FIX: Visibility Floating bar could show wrong settings when hide/remove settings were set to false in the element object

= 3.3.2 ( JUL 17, 2025 )
* FIX: WordPress Update error

= 3.3.1 ( JUL 17, 2025 )
* FIX: Persistent Link Spacing was set by default, ignoring the Theme Settings option
* FIX: StickyCSS, Visibility Floating Bar & Tag Manager localStorage options weren't correclty applied after builder reload
* FIX: Filterable Structure Panel and Focus Mode weren't correctly working anymore

= 3.3 ( JUL 16, 2025 )
* NEW: Structure Menu
* IMPROVE: New converter to migrate your Hide/Remove Element settings to Vanilla Bricks settings
* IMPROVE: Logical Properties: new option to convert directional properties into logical ones when pasting elements in the structure
* IMPROVE: Options stored in LocalStorage
* IMPROVE: Extended Variable Categories inside the Variable Picker are saved in the localStorage for each element
* IMPROVE: Link Spacing Controls: persistant for all controls during the current session & on builder reload
* IMPROVE: Hide Floating Bar is now suppporting the native hide/remove settings of Bricks
* IMPROVE: Lock ID Style is now integrated with Quick Access Tab of Bricks
* IMRPOVE: Grid Builder: now use the native Grid icon as trigger
* IMPROVE: Right Shortcuts Sibebar: restyled to match 2.0 design
* IMPROVE: Right Shortcuts Sibebar: adding a section will automatically activate the left element tab
* IMPROVE: Box-shadow Generator: compatible with bulk edits
* IMPROVE: Quick Remove Template: generate random element ID's on import to avoid builder conflicts
* IMPROVE: StickyCSS: live computed CSS update
* IMPROVE: StickyCSS: when activated, scrolling the Structure panel could hide the elements within 
* IMPROVE: StickyCSS: when activated, it could hide the Hide/Remove floating bar at the bottom of the Structure Panel
* IMPROVE: StickyCSS: Automatically switch to "Computed CSS" mode once the CSS Tab is open
* IMPROVE: AT Main Menu: added links to the documentation for each feature
* IMPROVE: ACF PRO upgraded to v6.4.2
* FIX: Quick Remote Template: error when inserting headers/footers from the Community Templates
* FIX: CMD + F was wrongly triggering Quick Search inside a CodeMirror instance
* DEPRECATED: Code Element tweaks (Bricks 2.0)
* DEPRECATED: Remove Default Libraries for Icons controls (Bricks 2.0)
* DEPRECATED: Set SVG as the default library for Icons controls (Bricks 2.0)
* DEPRECATED: Hide/Remove Element Controls (Bricks 2.0)
* DEPRECATED: Indicators of styles inherited from a class (Bricks 2.0)
* DEPRECATED: Left Tabs Shortcuts (Bricks 2.0)
* DEPRECATED: Delete Wrappers & Move Children Up (Bricks 2.0)
* DEPRECATED: Elements Manager in the Theme Settings (Bricks 2.0)
* DEPRECATED: Hide Element Shortcut in Contextual Menu of the Structure Panel (Bricks 2.0)
* DEPRECATED: Hide inactive Style accordion panel (Bricks 2.0)
* DEPRECATED: Structure Panel: Styles & Classes Indicators Color option (colored by default)
* DEPRECATED: Tag Manager: Default Mode option in the Theme Settings (replaced by localStorage)
* DEPRECATED: Elements Default Number in the Theme Settings (replaced by localStorage)
* DEPRECATED: Structure Panel - Icon Shortcuts options in the Theme Settings (replaced by Structure Menu)

= 3.2.5 ( JUN 24, 2025 )
* IMPROVE: ATF: Improved logics for nested animations
* IMPROVE: ATF: Changed the default delay-multiplier to 1 instead of 0
* FIX: ATF: .at-anim-repeat wasn't working correcty
* FIX: Variable Color Picker: not mounting correctly inside the builder

= 3.2.4 ( JUN 23, 2025 )
* IMPROVE: ATF 1.2: introducing animation capabilities
* IMPROVE: SuperPowerCSS: Typing a semicolon could incorrectly position the cursor to its left
* FIX: Global Colors: H, S & L dark variables of root colors were overriding the light variants
* FIX: Color Picker: The activation of this tweak was bound to the CSS Variable Picker option in the Theme Settings
* FIX: Strict Edit View: Control Groups were not visible for editors
* FIX: Left Tabs Shortcuts: Compatibility with 2.0-RC
* FIX: Fatal Error related to the Theme variables with 2.0-RC


= 3.2.3 ( JUN 04, 2025 )
* HOTFIX: WP Dashboard showing wrong update alert

= 3.2.2 ( JUN 04, 2025 )
* IMPROVE: Color Manager: Generate any shade/complementary scales from any nested color
* IMPROVE: Color Manager: Added a checkboard background for transparent color variations
* IMPROVE: Color Picker: Compatibility with Nested shades created in the Color Manager
* IMPROVE: Color Picker: Better performance when applying the color variable on hover
* IMPROVE: Focus on first class could fail when clicking inside the iframe window
* IMPROVE: Structure Generator: Tagless Text nodes are now wrapped in a span tag automatically
* FIX: Quick Remote Templates: position issues when the wp admin bar was activated inside the builder
* FIX: Generated Code: could fail to save the changes on builder reload
* FIX: Extend Classes & Styles: a JS error was preventing to work correctly
* FIX: Find & Replace: a JS error was preventing to work correctly

= 3.2.1 ( MAY 29, 2025 )
* IMPROVE: Structure Generator: Remove the image/input text after the prompt has been sent
* IMPROVE: Structure Generator: CSS generated code is now automatically beautified
* IMPROVE: Backend UI: "Options Updated" confirmation message now sits on top of the "Save Settings" button
* FIX: Fluid scales could return NaN values within the calculated clamp function
* FIX: "Set text input as a textarea for Headings" generated a JS error when the Heading element was disabled in the Global Settings.
* FIX: Removed the "Codepen Converter" and "Build Structure with AI" options within the Theme Settings
* FIX: Parsing HTML issue when the Element doesn't have a "customTag" Control property 

= 3.2 ( MAY 27, 2025 )
* NEW: Structure Generator
* NEW: Convert any Image to HTML/CSS/JavaScript Code
* NEW: Generate HTML/CSS/JavaScript Code from a simple text input
* NEW: CSS Grid Utility Class: Manual Converter
* IMPROVE: ATF v1.1
* IMPROVE: ATF: New Form integration (CSS Variables, Global Classes, Theme Styles & Recipes)
* IMPROVE: ATF: New update/restore system
* IMPROVE: ATF: New granular options for Theme Styles
* IMPROVE: ATF: New "AT Contextual" palette
* IMPROVE: ATF: Added --at-radius--l and at-radius--x
* IMPROVE: ATF: Updated .at-colorset to include color styling for classes with .'accent' in the name
* IMPROVE: Global Color: new 'at/color_palettes/override_palette_settings' php filter
* IMPROVE: CSS Variable Manager: New Custom groups
* IMPROVE: CSS Variable Manager: New "Custom Label" input
* IMPROVE: CSS Variable Manager: New option to display the generated CSS by variable category
* IMPROVE: CSS Variable Manager: Type Scale for minimum values
* IMPROVE: CSS Variable Manager: New Border-Radius preview option 
* IMPROVE: Global Class Manager: New option to display the generated CSS by global class category
* IMPROVE: Global Class Manager: Categories are now sortable by drag & drop
* IMPROVE: Backend UX refreshed
* IMPROVE: New "Converters" Tab inside Theme Settings > Global Settings
* IMPROVE: Partial Revamp of Tag Manager for better performances
* IMPROVE: Advanced CSS: improved sanitize css function to allow svg's in background URL’s
* IMPROVE: SuperPowerCSS: autocompletion now sorts the variable suggestions based in the length of the string
* IMPROVE: SuperPowerCSS: css property autocompletion adds a semicolon at the end of the line and position the cursor before it
* IMPROVE: SuperPowerCSS: no more nested var() on autocompletion
* IMPROVE: SuperPowerCSS: mounting improved when jumping on different elements 
* IMPROVE: General Performance optimizations
* IMPROVE: Toolbar icons compatibility with Bricks 2.0 Alpha/Beta
* IMPROVE: Replaced the left shortcut icons the ones available in Bricks 2.0 Alpha/Beta
* IMRPOVE: Focus mode in now triggered by SHIFT + CMD/CTRL + click to avoid conflict with bulk select in Bricks 2.0 Alpha/Beta
* IMPROVE: Lock ID: skip locking the styles on elements that have only locked classes
* IMPROVE: Variable Picker: the V icon has been deprecated. The modal is now triggered by the native Bricks icon.
* IMPROVE: AI Models list updated
* FIX: Modals with multiple panels could generate layout issue on tabbing events
* FIX: ATF: Typo inside the "at-focus-here" utility class
* FIX: Class Manager wasn't mounting correctly when opened through keyboard shortcut
* FIX: Advanced Text Wrapper: the function wasn't remounting correctly when an item inside the tag dropdown was clicked
* FIX: Advanced Text Wrapper: custom tags could produce an empty dropdown on function remount
* FIX: Included add_filter( 'acf/settings/remove_wp_meta_box', '__return_false') to the code to avoid conflicts between ACF and Metabox.
* DEPRECATED: Codepen Converter & Generate Structure with AI - they are now unified in Structure Generator

= 3.1.6 ( APR 28, 2025 )
* HOTFIX: Missing styles for AT's modals

= 3.1.5 ( APR 28, 2025 )
* IMPROVE: Performance improvements for Chromium browsers

= 3.1.4 ( APR 25, 2025 )
* IMPROVE: ATF: New Button size and variants variable categories
* IMPROVE: ATF: Button sizes and variants are now mapped to the Theme Styles
* IMPROVE: ATF: Button utility classes have been deprecated and removed from the framework
* IMPROVE: ATF: Button recipe has been updated with the new variables
* IMPROVE: ATF: New default styles for all button variants
* IMPROVE: ATF: New description text within the ATF variable categories in the CSS Variable Manager
* IMPROVE: CSS Variable Manager: you can now select the base scale value.
* IMPROVE: CSS Variable Manager: The left & right panels are now scrollable independently
* IMPROVE: CSS Variable Manager: Changing a color variable in the input control update the color preview
* IMPROVE: Reduced event listeners on the left element shortcuts
* IMRPOVE: Removed the size, style, circle & outline button properties from being considered as styling controls 
* IMRPOVE: Contextual Variable Picker: Added Grids & Gap contextual categories
* FIX: StickyCSS: the selector wasn't refreshing correcty when changing element in the structure
* FIX: StickyCSS: Fullscreen wasn't displayed correctly on Firefox
* FIX: StickyCSS: The selector dropdown was covered by the codemirror editor on Firefox
* FIX: CSS Variable Manager: additional steps in a scale weren't assigned to the corresponding variable category
* FIX: CSS Variable Manager: filtering inner scale's names could generated undefined errors
* FIX: CSS Variable Manager: scale preview wasn't accurate when --base-font was different to 10
* FIX: CSS Variable Manager: changing a multiplier in a scale would remount the preview and generate unexpected UX errors
* FIX: Codepen Importer: editors were duplicating when toggling any option inside the modal
* FIX: Color Picker: custom shades weren't displayed 
* FIX: Contextual Variable Picker: border and box-shadow won't show spacing variables anymore
* FIX: Quick Remote Templates: not mounting correcty after reopening the modal a second time
* FIX: Grid utility classes: conversion to the Class Manager was failing
* FIX: Color Manager: star icons inside the header dropdown had no colors
* FIX: Structure Helper: fix some design issues

= 3.1.3 ( APR 19, 2025 )
* HOTFIX: CSS conflict on buttons within the builder

= 3.1.2 ( APR 18, 2025 )
* HOTFIX: Color Palettes created with AT and with a custom prefix set in the Theme Settings missed a correct separator in the variable definition on the frontend.

= 3.1.1 ( APR 18, 2025 )
* IMPROVE: ATF now supports custom prefix for all the variables, classes and colors
* IMPROVE: Global Class Manager: new "Find & Replace" option within the bulk action tab
* IMRPOVE: Global Class Manager: splitted the class overview data and the custom css editors for improved performance
* IMPROVE: Global Color Manager: manage the variable prefix for each palette separatly
* IMPROVE: ATF: removed the Misc class category and moved .clickable-box to the Accessibility category
* IMPROVE: ATF: added "h2" as default heading HTML tag
* IMPROVE: Imported Advanced CSS stylesheets through the theme settings > import function now automatically recompile and generate the CSS files
* FIX: ATF: Replaced at-link-cover--hover in at-link-color--hover
* FIX: ATF replace "outline-style: auto" to "outline-style: solid" all over the framework
* FIX: Transform all "select" controls into Comboboxes was running by default and couldn't be disabled
* FIX: Potential PHP error when converting the clamp settings variables
* FIX: Autofocus on search inputs wasn't working anymore on all AT modals
* FIX: Classes created by the Class Importer weren't working correctly anymore
* FIX: Error when importing Advanced CSS stylesheets throught the theme settings > import function

= 3.1 ( APR 14, 2025 )
* NEW: AT CSS Framework
* NEW: Sticky CSS
* NEW: List all the posts/pages/templates/components where the active class is used
* NEW: List & Manage Unused Global Classes across your whole website
* NEW: Create Custom Fluid Scales within the Variable Manager
* NEW: Remove All Styling Controls
* NEW: Sync Heading Text with Element Label
* NEW: Transform all "select" controls into Comboboxes
* IMPROVE: Class Manager: moved the grid utility classes within the Class Manager
* IMPROVE: Class Manager: Grid Utility Classes are now using native Bricks Global Classes
* IMPROVE: Class Manager: new "Modify" section where you can bulk convert your classes to logical properties
* IMPROVE: Class Manager: new "Restore" section where you can restore global classes in buk from the trash
* IMPROVE: Class Manager: new "Permadelete" section where you can permanently delete global classes in buk from the trash
* IMPROVE: CSS Variable Manager: Contextual Categories Generator
* IMPROVE: CSS Variable Manager: Categories can now be reordered by drag and drop
* IMPROVE: CSS Variable Manager: Moved the clamp settings within the Variable Manager
* IMPROVE: CSS Variable Manager: New "Color" variable type
* IMPROVE: CSS Variable Picker: Contextual categories opened based on the selected control
* IMPROVE: CSS Variable Picker: Variables set as colors in the CSS Variable Manager are now synched inside the Picker
* IMPROVE: Filterable Structure: new dropdown to select the property to filter
* IMPROVE: Filterable Structure: added a new "data-attribute" filter
* IMPROVE: Style & Class indicators in Structure Panel: refactored for better performance on Chrome browsers
* IMPROVE: Quick Search: added keyboard shortcuts
* IMPROVE: Quick Search: filtered category are persistent when reopening the modal
* IMPROVE: Color Manager: new “Export” functions & Sync with the CSS Variable Manager
* IMPROVE: Color Manager: new Palette Menu
* IMPROVE: Color Manager: Header UX refactored
* IMPROVE: Color Picker: list static colors & all type of palettes
* IMPROVE: Logical Properties: new global converter for elements on posts/pages/templates/components & global classes
* IMPROVE: Logical Properties: new option to convert your styles to logical properties within the Class Contextual Menu
* IMPROVE: Logical Properties: Automatically converts logical properties when importing templates from Quick Remote Template
* IMPROVE: Quick Remote Template: New topbar shortcut icon
* IMPROVE: Darkmode toggles and buttons are now compatible with ACSS Alternate color scheme & Frames color toggles 
* IMPROVE: Animation Timeline: new option to enqueue the scroll/view timeline polyfill script
* IMPROVE: Page Transition: all the inline styles are now linked to the 'brxc-page-transition' style (instead of being attached to the global styles generated by AT)
* IMPROVE: Added display:none as inline style on all the darkmode elements to avoid layout shift on a cached environment
* IMPROVE: All modals: conditional mounting on user interaction
* FIX: Quick Remote Templates: Top bar wasn't scrollable on wider screens
* FIX: Style Overview: structure list jumped on top on each mount
* FIX: Style Overview: Global Classes with empty styles weren't showing
* FIX: Color Picker: opening/closing accordeons could "loose" the event listener
* FIX: CSS Variable Picker: popup controls was closing down when clicking a variable
* FIX: PHP warning when exporting/importing custom Advanced CSS Partials/Custom Stylesheets
* FIX: Theme Styles weren't correctly imported
* FIX: Full-size window on code element wasn't scrollable on Firefox
* FIX: Unselectable global query loop
* FIX: Global Page Transitions were applying on admin pages
* FIX: CSS Variable Manager: Deleting a category wasn’t targeting the correct item
* FIX: Global Color Variables Reinitialized
* FIX: UTF-8 Characters in SuperPowerCSS
* FIX: Hidden Settings in AT Main Menu
* FIX: AT Main Menu Visibility in Builder's Topbar
* FIX: Stripped HTML tags in Advanced CSS
* FIX: Conditions/Interactions icons had limited clickable surface
* FIX: Variable Picker didn't load the Core Framework typography/spacing variables correctly anymore
* FIX: Quick Remote Template: hitting ESCAPE wasn't closing the modal correctly.
* FIX: The "Active on Page" filter wasn’t working anymore within the Class Manager > Bulk Actions

= 3.0.5 ( FEB 12, 2025 )
* NEW: Animation Tab & Controls
* IMPROVE: Page Transition: removed the JavaScript code - the code is 100% vanilla CSS.
* IMPROVE: Page Transition: removed the target control in the page settings
* IMPROVE: Page Transition: removed the wrapper control in the element settings
* IMPROVE: Page Transition: removed the need to activate page transition in the page settings when an element contains a transition name
* IMPROVE: Left tab shortcut: added new animation tab
* IMPROVE: Filterable Structure: added HTML tags as filterable keywords
* FIX: Typo in the global page transition keyframes declarations
* FIX: Global Transitions wasn't applying on post with gutenberg blocks
* FIX: Nested Elements: layout issue in categories with few elements
* FIX: Nested Elements: convert to component wasn't working correctly
* FIX: Style Overview: toggling off the structure list would mess with the layout
* FIX: AI in SuperPowerCSS was mounting even if no API key was set in the Theme Settings

= 3.0.4 ( FEB 7, 2025 )
* IMPROVE: Class & Style indicators added to the Structure list within Style Overview
* IMPROVE: Class Converter: new class indicators
* IMPROVE: The breakpoint slider is now a separate tweak from the Responsive Helper
* FIX: SVG applied to button icons weren't correctly saved when SVG was set as the default library for Icons
* FIX: Filtered Structure Panel wasn't showing the collapsed elements
* FIX: Elements generated from the right elements shortcuts could be unsync with the structure if the user previously clicked on an iframe element
* FIX: Adding a component from the right elements shortcuts would fail to nest the component inside an active nestable element
* FIX: Legacy Darkmode button had styling properties not applied correctly
* FIX: Responsive Helper could stretch the bottom of the preview window
* FIX: When "Lock Elements Order" was enabled, you could drag items inside unnestable elements within the structure

= 3.0.3 ( FEB 4, 2025 )
* FIX: Tag Manager could stay open and conflict with other active inputs on the page
* FIX: Hide element was ignoring breakpoints/mobile-first approach.
* FIX: Uncategorized CSS variables weren't correctly applied on the builder when applied from the Variable Picker
* FIX: Elements generated from the right elements shortcuts could have different ID's between the iframe and the structure, resulting in misc errors.
* FIX: The Grid Builder CSS declarations are now applied as Custom CSS if a component is detected as child of the parent element.
* FIX: Converting elements to Rich Text could be glitchy
* FIX: Default Link Spacing Controls could interfere with box-shadow/border/text-shadow controls

= 3.0.2 ( JAN 31, 2025 )
* NEW: Filterable Structure Panel
* IMPROVE: Hide/Remove Element: new floating bar on the bottom of the Structure Panel
* IMPROVE: Remove Default Libraries for Icons: now SVG is selected by default.
* IMPROVE: Closing the Color Picker would leave the default Bricks color popup open.
* IMPROVE: Quick Search: filter the structure by content keyword.
* IMPROVE: Quick Search: filter the structure by global class name attached to the elemenets.
* IMPROVE: Responsive Helper is now open by default on load.
* FIX: Using the right elements shortcuts could lead to see the newly added element inside the structure, but not within the iframe.
* FIX: Codepen Converter: solved a JavaScript error that prevented importing a structure without having an active element selected within the structure panel.
* FIX: Mask Helper wasn't applying the mask correctly anymore
* FIX: Clicking on on the "Hide/Remove element" icons within the contextual menu of the structure panel would also toggle the display:none property.
* FIX: Strict Editor View: the logo image triggered a PHP error inside the editor view.
* FIX: Panel groups within the Content tab weren't toggable when Lock ID styles was active.
* FIX: Deleting the data of AT when uninstalling could potentially mess with the existing Theme Styles.
* FIX: CMD/CTRL + F in Advanced CSS wasn't opening the codemirror search function
* FIX: Imported CSS in Advanecd CSS could output a warning on non-SSL sites
* FIX: CMD/CTRL + SHIFT + F was opening the Quick Search Modal instead of the element search function from Bricks
* FIX: Class Manager: couldn't select a class if a component was inserted in current page's Structure Panel
* FIX: Variable Picker: search would return color variables only
* FIX: Theme variables weren't enqueued correctly on Editor View

= 3.0.1 ( JAN 28, 2025 )
* FIX: Confirmation import/export popup wasn't displayed "fixed" on page
* FIX: Importing the Theme Settings would create duplicated entries within the confirmation popup
* FIX: FATAL error in the AJAX response when importing the Strict Editor options
* FIX: darkmode-local-storage.js was enqueued even when global colors or the darkmode on the frontend were disabled
* FIX: The right element shortcuts could generate a js error when no element was selected


= 3.0 ( JAN 27, 2025 )
* NEW: Page Transitions (Experimental)
* NEW: Quick Remote Templates
* NEW: Leverage AI to improve any elements custom CSS
* NEW: AI Prompt Manager
* NEW: Focus mode
* NEW: Zoom-Out
* NEW: Color Variables Picker
* NEW: Logical Properties controls
* NEW: 2 New Elements: Nestable Darkmode Button & Toggle 
* NEW: Lock Elements Order
* NEW: Modified Mode
* NEW: Extract Variables as Global Variables
* NEW: Link Spacing Controls by Default
* NEW: Remove Default Libraries for Icon elements
* NEW: Color Popup set to RAW and displayed as a LIST
* IMPROVE: Full compatibility with the new Components (since Bricks v1.12)
* IMPROVE: Structure Helper: Components extended/collapsed
* IMPROVE: Structure Helper: Filters are now toggable
* IMPROVE: Strict Editor View: all elements are now listed for left-visibility/enable features
* IMPROVE: Revamped Structure Shortcuts including all elements and components
* IMPROVE: ACF requests to the database made by AT dropped to one single request
* IMPROVE: Class Converter: added an active background-color on root elements
* IMPROVE: Class Converter: toggling the include/skip option on a root element will also apply to all the relative children
* IMPROVE: Class Converter: Sync Elements Labels
* IMPROVE: Class Manager: add the trash category
* IMPROVE: Color Manager: hitting ENTER or TAB within the HEX input will now update the HEX value
* IMPROVE: Color Manager: typing a 6-digit hex color will automatically update the color value.
* IMPROVE: Variable Picker: added an option to enable both the V icon and the Right click as event triggers
* IMPROVE: Nested Elements: New PHP filter to manage the default elements
* IMPROVE: Nested Elements: new option to convert custom element as native Bricks Component
* IMPROVE: Box-Shadow Generator: New PHP filter no manage the presets
* IMPROVE: Enable / Disable elements are now hidden when checked
* IMPROVE: More granular control over the exported/imported/reset AT data
* IMPROVE: Added new Bricks exportable data: Global Settings, Components, Pseudo Classes, Breakpoints, Structure Width & Panel Width
* IMPROVE: Plain Classes: Added a lock icon next to all the locked classes
* IMPROVE: Plain Classes: Clicking on an active class will remove it from the class list.
* IMPROVE: Plain Classes: The editor provides a trailing space, allowing you to easily add new classes without needing to press backspace.
* IMPROVE: Added a version number to the custom stylesheets created with Advanced CSS to avoid caching issues.
* IMPROVE: Quick Search: search a structure element by the ID
* IMPROVE: Quick Search: new component section
* IMPROVE: New shortcut icons to remove element on frontend & hide in builder in contextual menus
* IMPROVE: New Quick Edit toggles within the AT Main Menu
* IMPROVE: Drastic reduction of CPU usage when compiling SASS code.
* IMPROVE: Moving elements with keyboard arrows won't be triggered when any input text is selected
* IMPROVE: The "Generate Global Query Loop" button is now visible even when no query type is selected
* IMPROVE: Revamped Tag Manager to allow custom tags from the structure panel
* IMPROVE: New Structure list within the Style Overview modal
* IMPROVE: New Keyboard Shortcuts for AI Prompt Manager and Quick Search
* FIX: ACSS icon covering the keyboard shortcut icon on the bottom right of the screen
* FIX: Focus on first unlocked class could not work properly when the element included deleted classes.
* FIX: Generate AI structure could return a JS error if the openai API key isn't set inside the theme settings.
* FIX: Icon settings weren't exported correctly from the ID to the class using Class Converter
* FIX: Importing global classes missed the Locked list
* FIX: Adding a Nested Component while a child had removed global classes could generated a JS error.
* FIX: The final part of the CSS code - when longer than the screen viewport - could overflow in SuperPowerCSS
* FIX: Moving parent colors inside Color Manager could break the position of the children
* FIX: Convert UX to CSS & Variabilize CSS may not be correctly saved inside the element/class object
* FIX: Advanced CSS now supports Mobile-first css on page.css
* FIX: Control values not visible on classes when "Focus on First Class" was enabled
* FIX: Unable to move rich text elements with keyboard shortcuts due to the autofocus on the tinyMCE editor
* FIX: Right keyboard shortcuts could be wrongly triggered when typing inside the BricksForge Panel
* FIX: Some dynamicooo shortcode could conflict with the autoformat clamp() function.
* FIX: Autoexpanding SuperPowerCSS could be wrongly triggered when switching to elements with locked classes only
* FIX: Switching from an element with locked classes only could prevent SuperPowerCSS from mounting correctly
* FIX: CSS styles applied on global classes not always saved correctly when SuperPowerCSS is activated
* FIX: Ajax error when no Global Query Loop Category is set
* FIX: Focus Point applied on image classes would be wrongly applied on ID level.
* FIX: SuperPowerCSS: CMD+F would trigger the Quick Search function of AT instead of the CodeMirror Search dialog
* FIX: Move arrows within the contextual menu of the structure panel could be incorrectly enabled/disabled
* FIX: CSS Variable Suggestion Dropdown had a EventListener conflict on Safari
* FIX: The "expand" icon within the Structure Panel could be switching position with the BEM icon of ACSS om click.
* FIX: Code Element CSS on full-size wasn't showing all the code on click and wasn't scrollable either
* LEGACY: Darkmode button & toggle are now legacy - use the nestable versions instead

= 2.9.1.3 ( OCT 24, 2024 )
* IMPROVE: When both builder & frontend are set to be hidden, the structure element was painted as orange instead of red.
* IMPROVE: Sass variables are now included inside the suggestion dropdown of AdvancedCSS and SuperPowerCSS when hitting "$"
* IMPROVE: Advanced CSS: decreased the amount of calculation on save leading to better performances
* FIX: Advanced CSS: toggling on a disabled css wasn't reflecting inside the builder
* FIX: ACSS recipe content was converting single quotes in HTML entities code.
* FIX: Invalid Nonce on saving Advanced CSS settings when ACSS is active.
* FIX: Elements hidden inside the builder could disappear in the structure (conflict with 3rd party plugins)
* FIX: Grid guides could generate a JS error when custom breakpoints were added
* FIX: Advanced Text Wrapper could still have work breaking inside the unwrap/wrap buttons
* FIX: SuperPowerCSS didn't extend to the available space using Firefox developer edition

= 2.9.1.2 ( OCT 22, 2024 )
* IMPROVE: Hide element on builder & frontend have now 2 different colors inside the structure panel
* FIX: Advanced CSS: Toggling a stylesheet status could reset the content
* FIX: Advanced CSS: Toggling a partial status could set the content as "false"
* FIX: Advanced CSS: Partials weren't sharing variables from other partials, resulting in potential errors when compiling
* FIX: Advanced CSS: The status toggle was too big in sidebar view - the tooltip was overlapping with the CSS editor
* FIX: SuperPowerCSS: The cursor/selection could be miscalculated after the left panel auto-expanded.
* FIX: SuperPowerCSS: The SASS code could be replaced by the compiled code on breakpoints.
* FIX: Footer buttons inside modals with sidebar view could overflow
* FIX: Not enough background contrast in the grid builder error notification box
* FIX: ACSS transparency background inside the grid color picker wasn't showing correctly
* FIX: Grid Builder: The cell's col/row set by the child inputs weren't correctly saved.
* FIX: Grid Builder: 0px gap could create render/calculation issues.

= 2.9.1.1 ( OCT 21, 2024 )
* IMPROVE: Advanced CSS: new indicator of unsaved changes on stylesheets/recipes
* IMPROVE: SuperPowerCSS: new CMD/CTRL + B shortcut to beautify the CSS
* IMPROVE: Enhanced builder load performance
* FIX: Custom recipe not visible if no custom stylesheet existed
* FIX: Added background/border/outline reset properties to the grid guide container to avoid conflict when targeting tags (section, div) without specificity
* FIX: Clicking Plain Classes inside the Class Contextual Menu wasn't working anymore
* FIX: SuperPowerCSS contextual menu could be cut off if the left panel wasn't wide enough.
* FIX: Unnecessary unsaved changes indicator inside the save button on builder load
* FIX: CMD/CTRL + S wasn't saving the SuperPowerCSS content if the editor wasn't previously blurred.
* FIX: The V icon was wrongly displayed inside the rating/maxRating fields of the new Rating element.


= 2.9.1 ( OCT 17, 2024 )
* IMPROVE: Advanced CSS: Added suggestion dropdown for SCSS files
* IMPROVE: Advanced CSS: Added Beautify CSS for SCSS files
* IMPROVE: Advanced CSS: Manage your own recipes
* IMPROVE: Advanced CSS: New PHP filter to add your custom recipes
* IMPROVE: Advanced CSS: Full ACSS recipes integration
* IMPROVE: Advanced CSS: Introducing Community Recipes
* IMPROVE: Advanced CSS: Sass code in Elements and Global Classes get recompiled in the builder on save
* IMPROVE: Advanced CSS: lastModified timezones are now sync between the browser and the server.
* IMPROVE: Advanced CSS: New "Recompile & Regenerate all CSS files" option in the modal.
* IMPROVE: SuperPowerCSS: ACSS recipes are now fully supported (for both ACSS & AT)
* IMPROVE: SuperPowerCSS: Better on-the-fly mixins/partials render
* IMPROVE: SuperPowerCSS: Live error Checker added for when Sass is enabled
* IMPROVE: Dynamic Data Modal: performance improvements inside the modal
* IMPROVE: Plain Classes: You can navigate the class buttons using the TAB key.
* IMPROVE: Global Colors: new .brxc-reversed-colors & .brxc-initial-colors utility classes
* IMPROVE: Deleting a class with any AT function now supports the new trash function of Bricks since 1.11
* FIX: Advanced CSS: Custom stylesheets missing stripslashes() on SASS content on frontend.
* FIX: SuperPowerCSS: The content could be stripped on mount
* FIX: The advanced options in SuperPowerCSS (beautify, variabilize, convert UX, etc...) required extra typing to correctly save the css to database.
* FIX: Contextual menu not visible when the Structure Panel is undocked.
* FIX: WPCodeBox integration not triggered when the filepath of the plugin was altered
* FIX: Plain class: a js error could prevent the correct focus inside the class editor
* UPDATE: ACF PRO has been updated to version 6.3.9

= 2.9.0.1 ( OCT 10, 2024 )
* IMPROVE: Advanced CSS: The initial commented code inside child.css is now set as readonly to avoid any human error.
* IMPROVE: Advanced CSS: AI disabled in child.css to avoid messing with the initial commented code.
* IMPROVE: Advanced CSS: added a new php filter to disable the WPCodeBox integration.
* IMPROVE: Keyboard Shortcuts: reverted some logics for wider OS compatibility.
* FIX: Copy dynamic data to clipboard icon could be misplaced inside the Dynamic Data Modal
* FIX: The left panel icons could get on top the quick search modal
* FIX: SuperPowerCSS: the "r" %root% shortcut could conflict with some emmet shortcuts
* FIX: Advanced CSS: Custom stylesheets missing stripslashes() on SASS content


= 2.9 ( OCT 09, 2024 )
* NEW: AI Generated Structure
* NEW: Codepen Converter
* NEW: Generated Code & Element HTML parser
* NEW: WPCodeBox integration
* NEW: Quick Search
* NEW: Code Element Tweaks
* NEW: Sync Element's label with the first Global Class name
* IMPROVE: Advanced CSS: revamped from scratch with full SASS functionalities - including mixins and partials
* IMPROVE: Advanced CSS: create/edit/disable separated CSS/SCSS files from the builder
* IMPROVE: Advanced CSS: one-click toggle between your SASS code and the compiled CSS 
* IMPROVE: Advanced CSS: edit style.css of your child theme right from the builder
* IMPROVE: Advanced CSS: new AI integration. Both create new content and edit the existing one. All CSS files supported.
* IMPROVE: Advanced CSS: new Selector Picker feature.
* IMPROVE: Advanced CSS: new live error checker when writing SCSS code.
* IMPROVE: Advanced CSS: new cross-search between all your files
* IMPROVE: Advanced CSS: advanced enqueue options on frontend/builder and Gutenberg
* IMPROVE: SuperPowerCSS: now support mixins and partials declared inside Advanced CSS
* IMPROVE: SuperPowerCSS: new "Variabilize CSS" feature
* IMPROVE: SuperPowerCSS: new "Convert UX values to CSS" feature
* IMPROVE: Notes: Added "Page notes" inside the Page Settings panel
* IMPROVE: Plain Classes Modal: new option to set it as Default Global Class Picker
* IMPROVE: Plain Classes Modal: clicking on a class will now close the modal automatically. ShiftKey + click will allow multiple class selection. Enter key will inside the editor will save the classes.
* IMPROVE: Dynamic Data Modal: New Copy Tag icon
* IMPROVE: Box Shadow Generator - Allow negative spread values
* IMPROVE: Variable Manager: Added a ".brxc-scoped-variables" to the theme variable declarations that allows overriding clamp function variables outside of the :root
* IMPROVE: Variable Manager: adding variables with empty value will show inside the Variable Picker without printing the declarations
* IMPROVE: Variable Manager: new toggle to import variables names in bulk without assigning values
* IMPROVE: Variable Manager: The cursor is now refocused on the "add new variable" input after adding a new variable.
* IMPROVE: Variable Picker: Added the right-click event to open the Variable Picker. 
* IMPROVE: Color Manager: New action icon to declare the @property of the variable created for each color
* IMPROVE: Color Manager: added the H,S & L values as separated variables for the parent colors of each Palette
* IMPROVE: Color Manager: New action icon to set your color as a CSS variable only without declaring any color.
* IMPROVE: Global Colors: dark colors are now disabled by default and require to be manually activated
* IMPROVE: New PHP filters able to set different default positions for Plain Classes, Class Converter, and Advanced CSS.
* IMPROVE: Added the 'o1-preview' and the 'o1-mini' to the available AI models.
* IMPROVE: Go to Parent Shortcut has been recovered.
* IMPROVE: The SASS integrations are not experimental anymore.
* IMPROVE: All the CSS & HTML editors mounted by AT now support emmet abbreviations.
* IMPROVE: AT keyboard shortcuts are not conflicting with the browser/Bricks anymore. They now required at least one click inside the window.
* FIX: Dragging Structure Panel wasn't working correctly since 2.8.1
* FIX: Search settings input was getting stretched
* FIX: PHP Warning "Undefined array key 'settings'" on pages with no settings
* FIX: The Bricks variable picker wasn't displaying correctly inside the code element for HTML/CSS
* FIX: The Bricks Dynamic Data picker wasn't displaying correctly inside the code element
* FIX: Auto-expanding the left panel inside the CSS tab wasn't working correctly.
* FIX: The filter elements were disabled by default inside the theme settings > global settings > builder elements.
* FIX: The icon control was wrongly cleared on the ID level when exporting the styles to a global class.
* FIX: Empty grid-gap value inside the Grid Builder would be ignored and wrongly generate a 20px value in the gap control.
* FIX: Empty columns/rows value inside the parent settings are now removing any existing value in grid-template-columns/rows.
* FIX: r + TAB shortcut wasn't working inside the CSS editor of the Class Manager
* FIX: Clicking on the Breakpoint indicators wasn't always setting the right breakpoint inside the builder
* FIX: Non-standard property keywords (like the logical ones) were not correctly added to the Codemirror autosuggestion dropdown
* FIX: Advanced Text Wrapper could add hrefs to tags other than anchors.
* FIX: Autoformatted clamp functions wouldn't correctly save inside the Variable Manager inputs. 
* FIX: The "Edit with WordPress" icon wasn't correctly hidden in the Strict Editor View
* FIX: Style Overview could generate a JS error when a class deleted, but not removed from an element.
* FIX: Variable suggestion dropdown wasn't showing correctly on spacing controls.


= 2.8.1 ( AUG 28, 2024 )
* NEW: Link indicator inside the Structure Panel
* IMPROVE: Added a prefixed -webkit- fallback for the backdrop-filter property (needed for Safari users).
* IMPROVE: Revamped Plain Class modal with new Category dropdowns
* IMPROVE: New "Rename Mode" inside the Structure Helper
* IMPROVE: Added "Elements with at least one global class that contains Custom CSS" filter inside the Structure Helper Modal.
* IMPROVE: Added "Uncategorized" to the filter options of the groups inside the class manager > bulk actions.
* IMPROVE: The import CSS Variables function could generate long decimal values for clamps. Now it's limited to two decimals.
* IMPROVE: "Hide inactive Style accordion panel" is now compatible with BricksExtras and BricksForge.
* FIX: "Hide inactive Style accordion panel" could potentially hide content groups if some style groups were active.
* FIX: Bricks suggestion dropdown was hidden when the "Suggestions Dropdown for CSS Variables" was enabled.
* FIX: Importing clamp() variables with nested calc() functions would generate a JS error
* FIX: Auto-expanding the left panel inside the CSS tab wasn't working correctly.
* DEPRECATED: Resizable Structure Panel (introduced in Bricks 1.10.2)
* DEPRECATED: Parent Element Shortcut (introduced in Bricks 1.10.2)

= 2.8 ( AUG 13, 2024 )
* NEW: Admin/Editor notes
* NEW: Cleanup deleted global classes from the elements
* IMPROVE: Added GPT-4o and GPT-4o-mini to the AI models list
* IMPROVE: Added "em" in the list of the advanced wrapper tags
* IMPROVE: Added "Recent Search" inside the Dynamic Data modal
* IMPROVE: "Elements with more than one global class" added to the Structure Helper options
* IMPROVE: Removed auto-focus on SuperPowerCSS
* IMPROVE: Reduced lags when editing CSS using SuperPowerCSS on the Global Class level. 
* IMPROVE: Codemirror upgraded to version 5.65.16
* IMPROVE: The builder tweak "Highlight Parent Elements" is now Vanilla CSS only - No JavaScript
* IMPROVE: The highlight functions on the Structure Panel are now more performant
* FIX: Element shortcuts colors conflict since 1.10
* FIX: The Global class manager wasn't opening correctly when using keyboard shortcuts
* FIX: There was a conflict with the tweak “Auto-focus on the First Unlocked Class” and the Global elements of Bricks
* FIX: Importing color variables misses a final parenthesis in the variable value
* FIX: The "Expand structure" icon could move around when the AutoBEM feature of ACSS was enabled
* FIX: The code element wasn't fully scrollable since v1.10
* DEPRECATED: All the builder tweaks related to templates (now available in Bricks 1.10)


= 2.7.3 ( JUL 4, 2024 )
* FIX: Importing CSS variables inside the color manager was generating raw values with a missing final parenthesis
* FIX: Keyboard shortcuts were triggered inside the ACSS 3.0 panel
* FIX: Keyboard shortcuts were triggered inside the Struceezy panel
* FIX: Replaced the ACSS floating button when the right element shortcuts are enabled
* FIX: Floating structure panel wasn't working correctly with ACSS 3.0 installed

= 2.7.2 ( JUN 25, 2024 )
* IMPROVE: AT now includes blank index.php files in all folders to prevent directory listing of AT plugin folders for incorrectly configured web servers
* UPDATE: ACF PRO updated to version 6.3.2.1

= 2.7.1 ( JUN 24, 2024 )
* IMPROVE: Added an outline to the top shortcut icons when the related panel is active
* IMPROVE: The condition/interaction panels were blocking the pseudo-class panels when clicking on the corresponding shortcut
* FIX: The keyboard shortcuts for moving elements inside the structure panel weren't working correctly anymore
* FIX: Grid builder settings not getting saved on global Classes
* FIX: Duplicate Trailing slashes added to the nested elements
* FIX: JS error that could prevent some functions to mount correctly
* FIX: SuperPowerCSS wasn’t respecting the auto-expand settings

= 2.7 ( JUN 18, 2024 )
* NEW: Nested Elements
* IMPROVE: The search input is now automatically focused when opening the Dynamic Data Modal
* IMPROVE: Dynamic Data Modal filters now includes the parent group label of any tag
* IMPROVE: Dynamic Data key filter is now cleared each time you open the modal
* IMPROVE: Holding the Shift key while clicking on dynamic tag will prevent the modal to close
* IMPROVE: "Expand all children" builder tweak now works for collapsing items as well
* IMPROVE: Export AT settings now include the builder settings such as Nested Elements
* IMPROVE: Added the filter elements to the list of element you can enable/disable
* FIX: Dynamic Data Modal filters didn't work correctly with tag names
* FIX: Z-index issue with the class contextual menu since 1.9.9
* FIX: Dynamic items/icons inside Contextual menu of the structure panel weren't updated correctly since 1.9.9
* FIX: Shortcut icons colors were missing inside the left element panel since 1.9.9
* FIX: Updated the new topbar icon opacity values since 1.9.9
* FIX: Export settings inside the Theme Settings wasn't working correctly anymore
* FIX: "Move Children Up" builder tweak could reposition the children elements in the wrong order
* FIX: "Remove template links" in Strict Editor Mode wasn't working correctly anymore
* FIX: "Left panel visibility" element list has been refreshed to match the Bricks editable element list in Editor Mode
* DEPRECATED: "Open link in a new tab" is now part of Bricks Core.

= 2.6.4 ( MAY 31, 2024 )
* NEW: Dynamic Data Modal
* NEW: Image Mask Helper
* IMPROVE: New enqueue options for the CSS Theme variables.
* IMPROVE: New default enqueue position set to Head for CSS Theme variables.
* IMPROVE: Removed a server request when enqueuing CSS Theme variables.
* FIX: Custom CSS codes on the ID level weren't correctly removed with Class Converter
* FIX: Layout issue: style group labels could overlap the SuperPowerCSS control when adding long CSS declarations
* FIX: Content tabs could disappear when "Hide inactive accordion panels" tweak was active
* FIX: Dynamic Data dropdown hidden in popup controls
* FIX: Clicking on the Structure Panel header icons could wrongly trigger the draggable/resize function.
* FIX: CSS Variables Conversion script could lead to conversion failure.

= 2.6.3 ( MAY 17, 2024 )
* IMPROVE: New PHP filter to activate the Core Framework integration inside the Variable Picker
* IMPROVE: Resetting/importing/exporting global classes will also include the corresponding Global Class Categories
* FIX: The left content tab could return empty when clicking on the corresponding left shortcut
* FIX: Switching left tabs shortcuts under 100ms could calculate incorrectly the active Status
* FIX: The Form element could produce an empty style tab when switching from another element
* FIX: In some cases, importing CSS Global Variables could mess with the CSS Variable Categories array

= 2.6.2 ( MAY 16, 2024 )
* FIX: Imported variables with clamp functions and min/max values set to px weren't parsed correctly  
* FIX: Missing prefix during the theme variables conversion
* UPDATE: ACF PRO updated due to a Security Patch 

= 2.6.1 ( MAY 15, 2024 )
* IMPROVE: Added an error message inside AT Variable Manager when the Bricks Variable Manager is disabled inside the Bricks Settings.
* IMPROVE: In the variable manager, the pixel unit was an input and prevented a smooth TAB keyboard switch.
* FIX: Hide Element inside the builder wasn't applying for specific elements such as image and image galleries.
* FIX: When typing in a text control, the bricks variable suggestion dropdown was showing even if AT's suggestion dropdown was activated
* FIX: PHP warnings showing in some cases during the variable conversion

= 2.6 ( MAY 14, 2024 )
* NEW: Text-to-Speech
* NEW: Focus Point
* NEW: Hide/Remove Element
* NEW: Merge Global Classes
* IMPROVE: Revamped Variable Picker
* IMPROVE: New "Fullscreen" mode for the Variable Manager of AT
* IMPROVE: Assign new category to multiple items by drag & drop is now supported inside the Class Manager and the Variable Manager.
* IMPROVE: clamp-builder now supports internal tokens to modify the min/max vewport & the unit values globally.
* IMPROVE: New default toggle to enable Keyboard Shortcuts for creating elements by default on page load.
* IMPROVE: Popups such as Border or Box-Shadow won't close anymore when the user selects a CSS variable from the variable picker.
* IMPROVE: Added the new GPT-4-Turbo model.
* IMPROVE: Renamed "Save Post" button to "Save to Database" inside the Class Manager.
* IMPROVE: Global CSS variables are now editable inside the builder.
* IMPROVE: Global CSS variables are compatible in both the core variable manager and AT's variable manager.
* IMPROVE: Hide/Show element visibility inside the class contextual menu even if a global class is active.
* IMPROVE: Default Theme Settings now reflect the official recommended settings.
* IMPROVE: New option to force a specific default color scheme on the frontend.
* IMPROVE: New control visibility options in Strict Editor View.
* IMPROVE: Updated the delimiter from white space to comma when adding multiple class or variable categories in their corresponding managers.
* FIX: "Extend spacing controls" tweak is now compatible with the new core spacing controls introduced in 1.9.8.
* FIX: The top element shortcut icons could overlap with the class input.
* FIX: Commented code not saved in the database if no further keyup was triggered.
* FIX: "Hide the Dynamic Data trigger" in Strict Editor Mode wasn't including the dynamic icon inside Rich Text Elements.
* FIX: In Strict Editor View, the "revisions" tab wasn't visible anymore.
* FIX: The "left panel visibility" tweak in Strict Editor View was hiding "History", "Pages" or "Settings" permanently.
* FIX: Left tab shortcuts required a double click to switch panels when the CSS tab with SuperPowerCSS enabled was active.
* FIX: Modals were closing when releasing the mouse click outside of the modal itself, instead of firing on mousedown event only.
* FIX: Right-click of the mouse could trigger the Resizable function of the Structure Panel.
* FIX: Creating elements by keyboard shortcuts from the preview window could create a bug leading the element to disappear from the structure.
* FIX: A JavaScript error related to the Tag Manager could prevent the left tabs shortcuts from being correctly mounted.
* FIX: Expanded spacing controls compatibility with 1.9.8.
* FIX: Variable Picker icon compatibility with 1.9.8.
* FIX: The suggestion dropdown wasn't showing correctly in SuperPowerCSS when the editor is in fullscreen mode.
* FIX: "Hide inactive Style accordion panel" could generate an empty style tab.
* FIX: The structure panel was cropped on the bottom on Firefox.
* FIX: Fixed a layout issue on the right sidebar when the Admin Bar is enabled inside the builder.
* FIX: Responsive issues in the generated CSS of the grid builder when no settings are applied on children through different breakpoints.
* FIX: Error when dragging colors if any color in the palette is collapsed inside the Color Manager.
* FIX: The grid utility global classes weren't correctly removed in the builder when deactivating the CSS Classes/Grid utility classes tabs in the theme settings 
* DEPRECATED: Media Queries indicators.
* DEPRECATED: Global CSS variables categories in the backend. Now they are managed inside the builder.
* DEPRECATED: The "expand spacing" switch has been removed since it has been introduced natively in 1.9.8.
* DEPRECATED: ACSS & Core Framework integrations inside the Variable Picker.
* UPDATED: ACF PRO updated.

= 2.5.3 ( APR 4, 2024 )
* FIX: Bug when creating global classes using the Class Converter and Plain Classes.

= 2.5.2 ( APR 3, 2024 )
* IMPROVE: Global Classes extracted and saved from the Advanced CSS Panels are now locked by default
* IMPROVE: Custom CSS and SuperPower CSS controls are now full-height by default when "Hide inactive Style accordion panel" tweak is enabled
* IMPROVE: Added a toggle for enabling/disabling keyboard shortcuts for creating new elements
* IMPROVE: Creating nested elements through the right sidebar could eventually wrongly create siblings
* FIX: "Reduced Left panel visibility" in Strict Editor View was sometime not showing the panel correctly. 

= 2.5.1 ( Mar 29, 2024 )
* IMPROVE: Added SASS integration to the Custom CSS control inside the Global Class Manager
* FIX: Left tabs shorcuts weren't always mounted correctly when Focus On first class is enabled
* FIX: A JS error could popup when an element had a css class added, but no global class attached
* FIX: Increased compatibility with earlier versions of Bricks 1.9.7
* FIX: Changes in Strict Editor Settings weren't saved correctly in database
* FIX: SuperPowerCSS not showing changes when navigating through style tabs

= 2.5 ( Mar 27, 2024 )
* NEW: SASS integration for Advanced CSS & SuperPower CSS (Experimental)
* NEW: AT Main Menu in toolbar
* NEW: Contextual Class Menu
* NEW: Select the controls your clients can see for each element type (Strict Editor View)
* NEW: Copy/Paste/Reset all the global classes from one element to another
* NEW: Add a Featured Image Column in the Templates Admin Screen
* IMPROVE: Theme settings UX improved
* IMPROVE: Right Shortcuts for creating Elements now support Keyboard shortcuts
* IMPROVE: Left Tabs Shortcuts now support Keyboard shortcuts
* IMPROVE: Added an option to disable all the controls by default (Strict Editor View)
* IMPROVE: Builder Tweaks for Strict Editor View improved to quickly enhance the UX.
* IMPROVE: More flexibility added to the left panel visibility tweak (Strict Editor View).
* IMPROVE: Custom dummy content for Lorem Ipsum Generator.
* IMPROVE: Removed the "tab" conditional loading for Builder Tweaks. All the tweaks are visible by default.
* IMPROVE: Increase the input space for margin/padding controls when the variable picker is active
* IMPROVE: Gutenberg CSS declarations are now conditionally enqueued only if a blocks is detected in the page.
* IMPROVE: Advanced CSS has been moved inside Builder Tweak tab.
* IMPROVE: New keyboard shortcuts for Query Loop Manager, Find & Replace and Plain Classes.
* IMPROVE: Keyboard shortcuts are now compatible for WIN users.
* IMPROVE: Generate Global Classes from your Child Theme CSS (Advanced CSS).
* IMPROVE: Added "Divider" as element shortcut, and removed "Social Icons" & "Icon box".
* IMPROVE: Major Dynamic data compatibility in Global Query Manager
* IMPROVE: SuperPowerCSS now integrates a new Fullscreen mode
* IMPROVE: The shorcuts sheatsheet are now hidden by default in SuperPowerCSS
* IMPROVE: License Security enhancement
* IMPROVE: Moving elements through the contextual menu won't close the menu on click
* IMPROVE: Advanced Themer will now deactivate itself if Bricks isn't activated
* FIX: Gutenberg Colors could duplicate the styles loaded by AT on frontend.
* FIX: Gutenberg Colors weren't applying correctly for overlays
* FIX: Focus on class didn't work correctly when clicking on the preview window from the structure panel
* FIX: Remove data when the plugin was uninstalled wasn't working correctly
* FIX: Auto-select Import Images and/or Replace Content in the template popup weren't correctly unselectable in the builder.
* FIX: When using the search function inside the builder, SuperPowerCSS control was automatically focused and created bad UX
* FIX: Imported color shades wouldn't update when the parent color is modified
* FIX: The "Backdrop-filter" control wasn't showing correctly when the new FILTERS / TRANSITIONS tab wasn't enabled
* FIX: Vanilla CSS values and "!important" weren't suggested anymore in SuperPowerCSS
* FIX: The Draggable Structure Panel wasn't working correctly when the admin bar was enabled inside the builder.
* FIX: The Root component toggle for Class Converter was showing inside the Strict Editor View.
* FIX: Centered the "V" inside the variable picker toggle
* FIX: Class Preview on hover wasn't working when after filtering the classes
* FIX: Masonry view inside the template popup was breaking the layout of the unsplash popup
* FIX: Fixed a bug that prevented SuperPowerCSS to mount correctly in some cases
* FIX: Imported CSS files in Advanced CSS could generate an error with OpenSSL activated on the server
* FIX: The AI assistant panel in Advanced CSS was not visible anymore

= 2.4.2 ( Feb 9, 2024 )
* NEW: Masonry Layout for the template popup grid
* NEW: Auto-select Import Images and/or Replace Content inside the template popup
* IMPROVE: Plain classes icon is now visible even if a class is active
* IMPROVE: New attention messages when resetting global settings
* IMPROVE: Hide the class manager topbar icon in Strict Editor View
* IMPROVE: New "Comment" icon inside SuperPowerCSS
* FIX: Quick-save template not working an certain template types
* FIX: Resolved a JS error that caused SuperpowerCSS to fail mounting correctly
* FIX: Fixed a warning "Undefined array key 'query_manager' in builder.php"
* FIX: AT Theme Settings menu item was visible to Editors in Strict Editor View
* FIX: Text Link elements weren't selectable in Strict Editor View
* DEPRECATED: Drag-and-drop Classes (now integrated in core)

= 2.4.1 ( Feb 2, 2024 )
* FIX: Error converting AT's Class Categories into compatible Bricks ones
* FIX: Box-shadow Generator & Grid Builder settings wouldn't apply correctly on pseudo-elements
* FIX: Locked classes becoming wrongly unlocked

= 2.4 ( Jan 31, 2024 ) 
* NEW: Box-shadow Generator
* NEW: Global Query Manager
* NEW: Quick-save Templates
* NEW: Advanced Text Wrapper
* NEW: Copy/paste your Interaction & Condition settings
* NEW: Speech to Text converter
* NEW: Move Styles over different elements, classes or breakpoints
* NEW: More used classes suggestion & group filter
* NEW: ID’s overview
* NEW: Global Query Loop Dropdown
* NEW: Collapse/Expand the Elements list
* GRID BUILDER V2: Repeat Grid
* GRID BUILDER V2: New Span switch
* GRID BUILDER V2: Fill empty cells inside the grid
* GRID BUILDER V2: Use minmax() for your grid-template values
* GRID BUILDER V2: Replace Class/Query loop CSS automatically 
* GRID BUILDER V2: Add/Remove cells
* GRID BUILDER V2: Resize cells from all 4 corners
* GRID BUILDER V2: Clear View from the cell
* GRID BUILDER V2: Cell shortcuts
* GRID BUILDER V2: Generate Bento Grid
* GRID BUILDER V2: Bug Fixes and UX improvements
* Minor Improvements to Style Overview
* CLASS CONVERTER: Support for nested Class Components
* CLASS CONVERTER: Update classes
* CLASS CONVERTER: Group Suggestion
* CLASS CONVERTER: Minor UX improvements
* CLASS MANAGER: New Bulk Actions
* CLASS MANAGER: Enable/Disable specific classes in the bulk actions
* CLASS MANAGER: New “Ungrouped” Category
* CLASS MANAGER: Multiple keywords filter
* CLASS MANAGER: Synched Categories between Bricks and AT
* CLASS MANAGER: Minor UX improvements and Bug fixes.
* FIND & REPLACE: Update values inside global classes
* STRUCTURE HELPER: New icons for the previewed structure
* STRUCTURE HELPER: Show interactions set on global classes
* ELEMENTS SHORTCUTS: New Text-link icon
* INDICATORS OF STYLES INHERITED FROM A CLASS: Group Tabs indicators
* INDICATORS OF STYLES INHERITED FROM A CLASS: Different indicators for ID and classes
* INDICATORS OF STYLES INHERITED FROM A CLASS: Indicators between Classes
* RESOURCE PANEL: New zoom slider
* RESOURCE PANEL: UX Improvements
* RESOURCE PANEL: Bug Fixes
* GRID GUIDES: In some cases the grid guides weren’t covering the entire top/bottom screen.
* IMPORT/EXPORT: Overwrite existing settings
* PLAIN CLASSES: Minor UX improvements and bug fixes
* DEPRECATED: FRONTEND PLAYGROUND


= 2.3 ( Dec 15, 2023 ) 
* NEW: CSS Grid Builder
* IMPROVE: Bricks colors compatibility improved in Gutenberg
* FIX: Unnecessary padding on spacing controls
* FIX: Structure panel wasn't dragging without the elements shortcuts

= 2.2.1 ( Nov 28, 2023 ) 
* NEW: Style Overview Shortcut
* NEW: Export option in Class Manager
* NEW: “Lock ID Styles” for elements with Global Classes
* IMPROVE: Expand Spacing Controls improved
* IMPROVE: Draggable Structure Panel Improved
* IMPROVE: Component Class Manager improved
* IMPROVE: Responsive Helper improved + bug fix
* IMPROVE: Tabs Shortcut improved

= 2.2 ( Nov 20, 2023 ) 
* NEW: Responsive Helper
* NEW: Enable / Disable any Bricks element
* NEW: Draggable Structure Panel
* IMPROVE: Plain Classes improved
* IMPROVE: Preview Classes on Hover has been improved
* IMPROVE: Bulk duplicate classes improved
* IMPROVE: Strict Editor View improved
* IMPROVE: Variable Manager enhancement
* IMPROVE: Import/Export/Reset settings improved
* IMPROVE: Grid guides revamped
* IMPROVE/FIX: SuperPower CSS: bug fixes and enhancements
* FIX: Fatal Error “Uncaught Error: Class “Bricks\Elements” not found”
* FIX: Wrong base font-size calculated inside the builder
* FIX: The structure helper isn’t showing correctly on template pages
* FIX: Error with imported elements generated by Nimbufy
* FIX: Imported Classes not previewing inside the Builder
* FIX: Duplicate Color Palette bug
* FIX: Error with Tab shortcut & Lock ID styles

= 2.1.1 ( Oct 31, 2023 ) =
* FIX: Global base Font-size	
* FIX: Forced Class Preview on hover	
* FIX: The CSS variables not showing in the Strict Editor Mode	
* FIX: Unclickable buttons in the Variable Picker

= 2.1 ( Oct 30, 2023 ) =
* NEW: Variable Manager v1
* NEW: Complementary Color Generator
* NEW: Auto-darkmode
* NEW: Import colors from a CSS variable List
* NEW: Enable/Disable any Color Palette
* NEW: Structure Panel Helper
* NEW: CSS keyboard Shortcuts
* NEW: Class Manager Shorcut
* NEW: Element CSS Shorcut
* NEW: Expand Spacing Controls
* IMPROVE: Advanced CSS improved
* IMPROVE: Variable Picker improved
* IMPROVE: New “full-size” option in Superpower CSS
* IMPROVE: Comments added to Superpower CSS
* IMPROVE: Description added to the Class Manager
* ... and tons of small bug fixes and UX enhancements

= 2.0.4 ( Sep 28, 2023 ) =
* IMPROVE: Superpower CSS improved	
* IMPROVE: Style Overview improved
* IMPROVE: Import ID styles to classes improved
* FIX: Duplicated prefix in the color variables


= 2.0.3 ( Sep 27, 2023 ) =
* IMPROVE: Darkmode toggle and button improved	
* IMPROVE: “Active color on page” indicator improved	
* IMPROVE: Suggestion dropdown inside the Superpower CSS improved
* FIX: Color variables not visible inside the builder


= 2.0.2 ( Sep 26, 2023 ) =
* IMPROVE: Duplicated CSS variable suggestions in the Superpower CSS dropdown
* IMPROVE: Color variables were missing inside the suggestion dropdowns
* IMPROVE: The data-theme attribute is now toggling correctly inside the builder
* IMPROVE: “Focus on first unlocked class” improved
* FIX: “Disable auto-expand” wasn’t respected in Superpower CSS
* FIX: Blank screen errors for editors using the Strict Editor Mode	

= 2.0.1 ( Sep 22, 2023 ) =
* FIX: Error opening the Advanced CSS modal when no CSS is set on the current page
* FIX: Minor CSS fixes inside the builder (light theme)

= 2.0 ( Sep 21, 2023 ) =
* NEW: Group your Classes by Categories
* NEW: Generate CSS with AI
* NEW: Bulk Actions on Classes
* NEW: Convert Vanilla Bricks Colors into CSS variables
* NEW: Manage & Organize Your Colors with Ease
* NEW: Advanced Color Pickr
* NEW: Alpha channel supported
* NEW: Color Shades generator
* NEW: Advanced Scale Generator
* NEW: No-code Darkmode
* NEW: Customize any shade and dark color variant
* NEW: Set Custom Darkmode colors for ACSS variables
* NEW: Expand All Children Elements
* NEW: More CSS Controls
* NEW: Meta Theme Color – Both globally and per page 
* IMPROVE: Copy Color Variable to Clipboard in the Color Manager
* IMPROVE: Active Colors on page indicator inside the Color Manager	
* IMPROVE: Multi-select drag & drop in the Color Manager
* IMPROVE: Improved Default Palettes inside the Color Manager
* IMPROVE: Add you custom Icons inside the Light/Dark mode toggle/button
* IMPROVE: Added a toggle to enable/disable the dark mode color variables on frontend
* IMPROVE: Enhanced the Light/Dark mode to avoid FUC on load
* IMPROVE: Dark mode now supports the browser option "prefers-color-scheme"
* IMPROVE: Multi-select drag & drop inside the Class Manager
* IMPROVE: Copy Class to Clipboard inside the Class Manager
* IMPROVE: Manually Rename Classes for Bulk Actions inside the Class Manager
* IMPROVE: Reset button added to the Bulk Actions tab inside the Class Manager
* IMPROVE: More Granular Control over the classes created with the Class Converter	
* IMPROVE: Manually Rename Classes inside the Class Converter	
* IMPROVE: Improved Import/Export Styles to Classes when using Mobile-first Breakpoints
* IMPROVE: Added an option to import the Variable Framework from the database	
* IMPROVE: Improved Custom Theme compatibility inside the builder
* IMPROVE: The AI responses are now wrapped in a code block
* IMPROVE: Set the default AI model and tones of voice in the AI settings
* IMPROVE: Personalize the CSS generated for the Strict Editor View
* IMPROVE: Set the Overflow control as a dropdown
* IMPROVE: The superpower css has been improved
* IMPROVE: New Classes / ID Tab
* IMPROVE: Darkmode supports “prefers-color-scheme” settings
* IMPROVE: Autoformat works inside the theme styles
* IMPROVE: “body.brxc-dark” has been retired
* FIX: Color picker now accepts HEXA capitalized letters
* FIX: Adapted all the CodeMirror instances to support %root%
* FIX: Fixed a bug when the custom CSS selectors weren’t duplicated correctly when using the Class Manager
* FIX: Tag manager is working again
* FIX: Change element ID even if “Auto-focus on the First Unlocked Class” is enabled
* FIX: Improved compatibility with Mobile-first breakpoints
* ...and tons of small bug fixes.

= 1.3 ( Aug 18, 2023 ) =
* Class Manager
* Component Class Manager
* Drag-n-drop your element’s classes
* Auto-select Import Images in the Template popup	
* Added an option to transform the Heading input into a multiline textarea 
* Builder Light mode enhancement
* Custom HTML tags are now showing in the structure panel	
* Added an option to set “SVG” as default library for Icons elements	
* Enhanced Firefox compatibility	
* Class converter didn’t parse multiple “root” in custom css
* New shortcut elements
* 1-column layout to the elements list	
* Lorem/AI icons visible inside the class dropdown of the header element	
* Superpower CSS wasn’t mounted correctly when switching breakpoints	
* The element shortcut sidebar wasn’t sticky anymore	
* :before and :after styles not applied correctly	
* Typing “!” wasn’t triggering the suggestion dropdown in Superpower CSS	
* CSS doesn’t always apply inside Superpower CSS

= 1.2.4.2 ( Aug 10, 2023 ) =
* Autoformat applies now only on fields that accept a css value
* The clamp() function returned incorrect "middle" values
* The clamp() function has been shortened to max 3 float digits
* Import styles from ID returned a JS error on object values
* CSS suggestion dropdown was incorrectly triggered by the open bracket in Superpower CSS
* Performance boost

= 1.2.4.1 ( Aug 9, 2023 ) =
* Autoformat your control values with advanced CSS functions
* Basic text / Rich text / Heading converter
* Variable preview on hover was triggered even if the mouse was not moving
* ACF flexible content default text was modified by AT	
* Lorem ipsum generator and AI icons not showing on headers	
* Superpower CSS Performance issues
* Tag Manager issues in Firefox
* Buttons in Advanced CSS not working correctly in Firefox	
* Autocompletion issue on foreign keyboards	
* Keyboard shortcuts not working on Windows	
* Expand classes issues	
* History not working correctly in the Builder	
* Element Shortcuts not working on Template Section

= 1.2.4 ( Aug 4, 2023 ) =
* Group classes by Lock Status	
* Save your Global CSS from the builder	
* Import your Custom CSS from each element to your Global CSS
* Autogenerate your CSS declarations	
* Extract your classes from the Advanced CSS and save them as Bricks Global Classes
* Suggestion dropdown incorrectly triggered on German keyboards	
* Element shortcut not working on Archive pages	
* ACF has been upgraded	
* Indicators functions have been refactored
* Empty Accordion Panels in the Style Tab	
* Styles in the Content Tab are now hidden when the “Lock ID styles” feature is enabled
* Compatibility issue with WP Grid Builder	
* Style Overview reporting wrong values on mobile-first breakpoints since 1.8.4	
* The “CSS variables” tab inside the Advanced CSS panel has been deprecated	
* The imported CSS files weren’t showing correctly inside the Advanced CSS panel	
* Autoclose variable brackets didn’t support the !important tag
* Indicators not showing correctly when “autofocus on first unlocked class” is enabled	
* Click outside the Structure Panel didn’t unselect the active element
* Tag Manager improved	
* JS error in the Structure Panel
* Focus on the container when adding a section through shortcuts improved
* Export/Import ID Styles to Classes improved	
* Extend Classes improved	
* Remove a white space in the Structure Panel

= 1.2.3 ( Jul 20, 2023 ) =
* Custom default settings for relevant elements
* Reveal the “class icons” on hover and focus	
* Increase the builder’s inputs size to easily manage CSS variables and custom CSS functions
* Auto-close CSS Variable Brackets
* Review the CSS generated by Bricks for each Element and Classes	
* Autoselect the first unlocked class when browsing elements
* GPT-4 is now the default AI model	
* Style Overview is not experimental anymore
* The container element gets autofocused when adding a section through the “section” shortcut	
* The “Section” shortcut is now added to the Structure Panel Root
* The “Section” shortcut now includes a nested Container by default
* Adding a new element from the shortcut panel will expand the parent inside the structure panel
* Default Structure Panel Size
* CSS property suggestions now include “: ” when autocompleted inside the Superpower CSS
* Added padding-top to the “create element shortcuts” panel	
* CSS values suggestions have been improved in the Superpower CSS	
* Moving items in the structure panel was conflicting with the navigation shortcuts
* Dropdown removed in the “overview” mode of the Tag Manager
* Pseudo indicator not working correctly for classes	
* Missing closing bracket in some Core Framework variable values	
* “!important” suggestion created a double “!!” in Superpower CSS.
* Z-index issue with the Tag Manager Dropdown

= 1.2.2 ( Jul 10, 2023 ) =
* Added the Core Framework integration inside the Variable Pickr
* Fixed Style & Class indicators stopped working since the 1.8.2 upgrade
* Fixed the "Superpower CSS control" was not saving the different breakpoint correctly
* Fixed the suggestion dropdown was triggered by the delete key and the control key inside the "Superpower control"
* Fixed a duplicated tooltip in the theme settings for the Parent Icon builder tweak
* Fixed an issue where the shift + arrows were triggering the Move Elements tweak when the mouse was outside of the structure panel
* Fixed a CSS compatibility issue the Loader Plus plugin
* Fixed an issue where AT wasn't loading the proper version of ACF PRO if the plugin was already installed by the user
* Fixed Style Overview was loaded even if the feature was disabled in the theme settings

= 1.2.1 ( Jul 1, 2023 ) =
* Fixed Style Overview not displaying correctly on Firefox Dev edition
* Fixed the property filter inside Style Overview wasn’t displaying correctly after a 0 result search.
* Fixed Global classes weren’t updated correctly when the Superpower Custom CSS feature was enabled
* Fixed multiple “root” items weren’t parsed correctly inside the code editor controls when the Superpower Custom CSS feature was enabled
* Added the "Non standard" CSS properties to the suggestion dropdown inside the superpowered Custom CSS control
* Fixed changes to a cloned class were affecting the original class too.

= 1.2 ( Jun 29, 2023 ) =
* New Experimental “Style Overview” function inside the structure panel	
* Superpower the Custom CSS control
* Autocomplete CSS Variables Suggestions on Hover is now a separate option in the theme settings.
* Various Bug Fixes


= 1.1 ( Jun 9, 2023 ) =
* New “move element” function inside the structure panel	
* New “Delete Wrappers & Move Children Up” function	
* The “Hide element” function is now dynamic	
* New “Highlight Nested Elements” function in the structure panel 
* New “Highlight Parent Elements” function in the structure panel
* Preview the CSS variables from the autocomplete suggestion dropdown
* Autofocus on the AT’s input fields
* Save input fields with keyboard shortcuts
* Bug fixes


= 1.0.9.1 ( May 30, 2023 ) =
* New "Hide inactive Style accordion panel" function
* Tabs Shortcuts Top Offset
* Added "Shape Dividers" in the Tabs Shortcuts list
* Improved RTL support
* Improved Light Theme compatibility
* Bug fixes


= 1.0.9 ( May 24, 2023 ) =
* The builder tweaks scripts have been refactored
* New “structure panel” tab inside the builder tweaks options
* New “class convertor” feature
* New “elements tag” feature
* New “Breakpoint Indicator” feature
* New “Indicators of styles inherited from a class” feature
* New “Style Indicators in the media queries panel” feature
* New “Locked Class Indicator” updates
* New “copy class to clipboard” feature
* New “clone class” feature	
* New “hide element” feature
* New “class highlight” indicator in the structure panel
* New options in the “extend classes & styles” features
* “Disable styling on ID level” feature has been revamped
* Tons of bug fixes

= 1.0.8.1 ( May 11, 2023 ) =
* Fixed elements not collapsing in the structure panel
* Fixed indicators not showing on the right of the elements inside the structure panel
* Fixed the last element in the structure panel was partially hidden
* Fixed skipped conditions, interactions, attributes, css & class fields (inside the style tab) from triggering the "style on ID" indicator inside the structure panel
* Fixed containers elements weren't nesting elements from the structure panel shortcuts when belonging to the root of the structure
* Fixed a backend design issue on Safari
* Fixed the resize bar inside the structure panel that was overlapping with the expand elements toggle
* Added the class field from the style tab as a trigger for the Class indicator inside the structure panel
* Added filters to customize classes and css variables inside AT

= 1.0.8 ( May 10, 2023 ) =
* New indicators for the left panel and the pseudo elements shortcuts	
* Added the “View page on new tab” Icon
* Fixed various small bugs and errors	
* Borders, Box Shadow, and Width were added as CSS variables	
* The Theme Settings have a new look and functionalities	
* Style & classes indicators inside the structure panel
* Optional enqueue for your imported class	
* Display issue on fields with dynamic icon within the query loop popup
* ACF PRO updated 	
* Error when using acf_form()
* Added A backward compatibility for the clamp() functions	
* Autofocus when adding an element from the sidebar


= 1.0.7 ( May 3, 2023 ) =
* New right sidebar shortcuts
* Resizable Structure Panel
* BricksLabs Integration
* Performance enhancement on Frontend
* Escape key close modals
* Fixed “Disable PIN icon on element list” feature
* Error when deactivating the AT license
* ACSS variables blocked by the server	
* Wrong active state of pseudo-element shortcut
* Update the root value when exporting styles to a Class
* Skip Id and Classes fields when exporting styles to a Class
* Extend Class and Styles not working correctly
* AT License key is now hidden on frontend
* Fixed the Resource Panel

= 1.0.6 ( Apr 28, 2023 ) =
* Added a tooltip including the values of AT CSS variables
* The “class preview on hover” stopped working	
* Moved the color palette selector on top of the popup
* The “import styles from Element ID” function not working 
* Variables not showing inside the Advanced CSS Panel
* OpenAI API key not saving
* Fixed the grid and imported classes on frontend

= 1.0.5 ( Apr 27, 2023 ) =
* New Backend UI for the Theme Settings
* Reset your Theme Settings
* Import/Export your Theme Settings
* Updated the default Typography/Spacing fluid scale
* Resize the AT popups inside the builder
* Import your own CSS Variable Framework
* Allow upload of JSON and CSS files in the Media Library
* Variable Preview on hover
* ACSS variables support for the Variable Picker
* Customize the tabs inside the Advanced CSS Panel
* Solved a plugin conflict related to the license Management
* Fixed a Grid Color design issue on Safari
* Enhanced Firefox support

= 1.0.4 ( Apr 22, 2023 ) =
* The color palette CPT wasn’t loaded by default
* Fixed a bug that prevented updating the color palettes
* The grid classes weren’t loading on frontend 

= 1.0.3 ( Apr 21, 2023 ) =
* min/max values now support 2 decimals values	
* Fixed the dashboard menu order
* Enhanced the Advanced CSS panel	
* Default Column Settings for the elements list
* Enhanced the backend options of the Strict Editor view
* The clamp functions now support the CQI unit
* Fixed a bug with the Extend Global Classes feature
* Fixed the links inside the AT Panels	
* New UI for builder tweaks category	
* Fixed a Javascript with Plain Classes
* Added enqueue options inside the Class Importer
* Fixed the X-mode not showing in the header/footer	
* Fixed the ugly scrollbars on firefox/safari	
* Fixed the class lock issue on reload	
* Fixed wrong URL’s inside the admin bar items when the installed folder isn’t the root
* Enable/Disable any function in AT	
* ACF menu wasn’t showing in Dashboard when ACF Pro was enabled

= 1.0.2 ( Apr 17, 2023 ) =
* Minor improvements and fixes
* The Delete image icon disappeared
* Plaster compatibility CSS fix for the color grid
* “Disable borders and box-shadow” feature wasn’t working correctly on v1.7.3
* Grid classes & imported classes weren’t updated correctly in the builder
* Fixed Plain Classes not deleting the classes correctly
* Fixed Critical error

= 1.0.1 ( Apr 12, 2023 ) =
* Fixed various bugs

= 1.0.0 ( Apr 9, 2023 ) =
* Initial release*  
