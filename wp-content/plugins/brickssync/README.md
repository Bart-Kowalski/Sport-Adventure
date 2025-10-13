# BricksSync Plugin Documentation
===============================

Version: 1.1.1
Tested: Bricks 2.0.1
Author: Gert Wierbos - BricksSync
License: GPL-2.0+

Description
-----------
BricksSync allows you to synchronize and migrate Bricks Builder global settings and templates between different WordPress installations using Git-friendly JSON files. It provides tools for manual export/import and options for automatic synchronization via WP Cron.

Features
--------

What's New in 1.1.1: Multisite Sync Mode Fallback
--------------------------------------------------------------------
- Auto-export now respects global/group/site sync mode settings in multisite. Subsites inherit sync mode if not set locally.
- Fixed require_once path for effective config resolution on multisite.
- Fully tested with Bricks 2.0.1.

What's New in 1.1.0: Export Improvements & Bricks Components Support
--------------------------------------------------------------------
- Skips template export if slug is empty.
- Added full support for Bricks Components in settings export. Global CSS is already included in Bricks Settings.
- Documentation and code cleanup.
- Thoroughly tested with Bricks 2.0-rc2.

What's New in 1.0.0: Official Release & Compatibility Fixes
------------------------------------------------------------
- Official 1.0 release!
- Fixed compatibility for get_plugin_data() on older WordPress.
- Textarea fields (excluded options) now preserve line breaks.
- General documentation and version bump.

What's New in 0.8.0-alpha: Multisite & Network Admin Support
-----------------------------------------------------------
- **Full WordPress Multisite Compatibility:** BricksSync now works seamlessly on multisite networks. Network admins can centrally manage BricksSync settings across all subsites.
- **Network Admin UI:** A dedicated network admin dashboard features tabs for Global, Group, and Sites & Effective Configuration management.
- **Sites & Effective Configuration Tab:** View all subsites in a single table, showing the effective (inherited or overridden) configuration for each. Easily identify which sites have custom overrides.
- **Site-Specific Overrides:** Set, view, or reset configuration overrides for individual subsites. Overrides are marked with a vertical badge for clarity.
- **Reset Button:** Remove site-specific overrides instantly and revert to inherited settings. The UI refreshes immediately after reset.
- **Group Configuration:** Define groups of sites with shared settings. Inheritance follows: Network > Group > Site.
- **UI/UX Improvements:** Redesigned admin tabs, improved accessibility, and horizontal scrolling for large tables. Override status is visually prominent.
- **Security:** All multisite admin actions are protected by nonce verification and input sanitization.
- **Compatibility:** Multisite features are isolated from single-site installs. Cron and WP CLI work per site; use `--url=` for WP CLI operations on subsites.
- **Documentation:** All docs and README updated for multisite/network admin features.

### Multisite/Network Admin Usage
- Access BricksSync network admin via **Network Admin > Settings > BricksSync**.
- Configure global settings, manage groups, and override/revert site-specific settings from the Sites & Effective Configuration tab.
- Changes made at network or group level cascade down unless a site override is present.
- Resetting an override instantly removes the custom config for that site and re-applies inherited settings.
- All changes are immediately reflected in the UI with no page reload required after reset.
- WP Cron and WP CLI continue to function per site. Use `wp --url=site.example.com brickssync ...` for CLI on a subsite.

*   Export Bricks Builder global settings to a JSON file.
*   Import Bricks Builder global settings from a JSON file.
*   Export Bricks Builder templates (individually or all) to JSON files.
*   Import Bricks Builder templates from JSON files.
*   Configure a storage location for JSON files (Child Theme, Uploads Folder, Custom Path).
*   Different synchronization modes: Manual, Automatic Import, Automatic Export, Full Automatic Sync.
* WP-CLI integration for automated and advanced workflows.
* Debug tab with real-time log viewer, log clearing, and logging toggle.

Requirements
------------
*   WordPress (latest version recommended)
*   Bricks Builder Theme (latest version recommended)
*   PHP 8.0 or higher recommended

Recommended
-----------
*   Use Child Theme

Installation
------------
1.  Download: Obtain the BricksSync plugin .zip file from BricksSync.com
2.  Upload:
    *   Log in to your WordPress admin area.
    *   Navigate to Plugins > Add New.
    *   Click Upload Plugin.
    *   Choose the brickssync.zip file you downloaded.
    *   Click Install Now.
3.  Activate: Once installed, click Activate Plugin.

Configuration
-------------
After activation, configure BricksSync via "Bricks > BricksSync".

### 1. Licensing Tab

*   Activate License: Enter your key and click "Activate License".
*   License Status: Shows active license details (masked key and license status).
*   Deactivate License: Button appears if active to remove activation.

### 2. Configuration Tab

*   JSON Storage Location: Choose Child Theme, Uploads Folder, or Custom Path.
*   File naming & options:
    *   Bricks builder settings filename
    *   Bricks templates filename pattern
    *   JSON subdirectory name
    *   Excluded options (one per line) (e.g., bricks_license_key)
*   Sync Mode:
    *   Manual Only: No auto actions. (Default)
    *   Automatic Import Only: Imports from storage path if files change.
    *   Automatic Export Only: Exports current data to storage path.
    *   Full Automatic Sync: Imports THEN Exports. (Use with caution!)
*   Enable settings sync automation: Disabling this option is useful if you want to automate template sync but prefer to manage global Bricks settings manually (for example, to avoid overwriting environment-specific settings)
    

### 3. Settings Tab

*   Export Settings: Saves current global settings to defined filename.json in storage path.
*   Import Settings: Loads from defined filename.json in storage path. WARNING: Overwrites current settings. Backup first!

### 4. Templates Tab

*   Export Templates: Choose All or specific templates. Saves as defined filename.json' in defined subfolder of storage path.
*   Import Templates: Scans defined subfolder in storage path for .json files and lets you choose All or specific templates to import. May update existing templates. Backup first!

Usage Scenario examples
-----------------------
*   Manual Development GIT Workflow (Local -> Staging -> Prod):
    1. Use Child Theme storage.
    2. Use Manual Only mode.
    3. Export locally.
    4. Commit generated JSON files in child theme via Git.
    5. Pull on Staging/Prod.
    6. Import on Staging/Prod. Test.

*   Partially Automated GIT Development Workflow (Local -> Staging -> Prod):
    1. Use Child Theme storage.
    2. Use Export Only on Local and Import Only on Staging/Prod.
    3. Exports on template saving.
    4. Commit generated JSON files in child theme via Git.
    5. Pull on Staging/Prod when you are ready.
    6. Automated imports on Staging/Prod. Test.

*   Simple Backup:
    1. Use Uploads Folder storage.
    2. Use Manual or Auto Export mode.
    3. Export settings/templates to uploads folder.
    4. Restore by importing as needed.

*   Automatic Sync:
  1. Use shared custom path or version-controlled theme.
  2. Set Automatic Import/Export or Full Sync as needed.

### WP-CLI

BricksSync provides a set of WP-CLI commands for managing Bricks Builder settings and templates from the command line.

#### Overview
BricksSync enables:
- Export/Import of Bricks Builder settings
- Export/Import of Bricks templates
- Configuration management
- Status checking

#### Requirements
- WP CLI must be installed and available
- Active BricksSync license for most operations
- Appropriate file permissions for import/export

#### Available Commands

##### Settings Management

###### Export Settings
```bash
wp brickssync settings export [--output-file=<path>]
```
Exports all Bricks Builder settings to a JSON file. The file can be saved to a custom location or use the configured path.

Options:
- `--output-file`: Path to export the JSON file. If not provided, uses the configured path and filename.

Examples:
```bash
wp brickssync settings export
wp brickssync settings export --output-file=/path/to/my-settings.json
```

###### Import Settings
```bash
wp brickssync settings import [--file=<path>]
```
Imports Bricks Builder settings from a JSON file. The file can be read from a custom location or the configured path.

Options:
- `--file`: Path to the JSON file to import. If not provided, uses the configured path and filename.

Examples:
```bash
wp brickssync settings import
wp brickssync settings import --file=/path/to/import-settings.json
```

##### Template Management

###### List Templates
```bash
wp brickssync templates list [--format=<format>]
```
Displays a list of all Bricks templates with their details.

Options:
- `--format`: Output format (table, json, csv, yaml, count). Default: table.

Examples:
```bash
wp brickssync templates list
wp brickssync templates list --format=csv
```

###### Export Templates
```bash
wp brickssync templates export [--output-dir=<path>] [--templates=<ids>]
```
Exports selected or all Bricks templates to JSON files.

Options:
- `--output-dir`: Directory to export templates to. If not provided, uses the configured path.
- `--templates`: Comma-separated list of template IDs to export. If not provided, exports all templates.

Examples:
```bash
wp brickssync templates export
wp brickssync templates export --output-dir=/path/to/templates
wp brickssync templates export --templates=123,456
```

###### Import Templates
```bash
wp brickssync templates import [--input-dir=<path>]
```
Imports Bricks templates from JSON files in a directory.

Options:
- `--input-dir`: Directory containing template JSON files. If not provided, uses the configured path.

Examples:
```bash
wp brickssync templates import
wp brickssync templates import --input-dir=/path/to/templates
```

##### Configuration

###### Show Configuration
```bash
wp brickssync config show [--format=<format>]
```
Displays the current BricksSync configuration settings.

Options:
- `--format`: Output format (table, json, yaml). Default: table.

Examples:
```bash
wp brickssync config show
wp brickssync config show --format=json
```

##### Status

###### Check Status
```bash
wp brickssync status [--format=<format>]
```
Displays the current status of BricksSync, including license status, storage path validation, and sync status.

Options:
- `--format`: Output format (table, json, yaml). Default: table.

Examples:
```bash
wp brickssync status
wp brickssync status --format=json
```

#### Features
- Multiple output formats (table, JSON, CSV, YAML)
- Custom file path support for imports/exports
- License validation integration
- Detailed status reporting
- Error handling and logging
- Automatic directory creation
- Permission validation
- Progress reporting

#### Error Handling
All commands include error handling and will display appropriate error messages if:
- The license is inactive
- File permissions are insufficient
- Required files or directories are missing
- Invalid parameters are provided

#### Notes
- Most commands require an active BricksSync license
- File paths should be absolute
- The plugin must be properly configured before using these commands
- Debug logging can be enabled for troubleshooting

### Troubleshooting

*   "Error retrieving license details": Check internet connection.
*   "Storage path ...": Check Configuration tab settings. Verify directory exists and web server has read/write permissions.
*   Imports/Exports Failing: Check path permissions. Enable Debug Logging and check PHP error logs.
*   Fatal Errors: Check PHP error logs for details (missing files, conflicts).

Security: Protecting Your JSON Files
------------------------------------
BricksSync stores your settings and template JSON files in a directory you configure (child theme, uploads, or custom path). **It is critical to protect this directory from direct web access to prevent unauthorized downloads.**

### Apache (Automatic)
BricksSync automatically creates a `.htaccess` file in your JSON directory with the following rules:

```
Order allow,deny
Deny from all
<IfModule mod_authz_core.c>
    Require all denied
</IfModule>
```

This blocks all direct HTTP access to the JSON files on Apache servers. No further action is needed for most users.

### Nginx (Manual Configuration Required)
Nginx does not support `.htaccess` files. You must add a rule to your main Nginx configuration to block access. Example:

```
location ^~ /wp-content/your-json-directory/ {
    deny all;
    return 403;
}
```
Replace `/wp-content/your-json-directory/` with the actual path to your JSON directory, relative to your web root. After editing your config, reload Nginx.

### lighttpd (Manual Configuration Required)
lighttpd also does not support `.htaccess` files. Add this to your `lighttpd.conf`:

```
$HTTP["url"] =~ "^/wp-content/your-json-directory/" {
    url.access-deny = ( "" )
}
```
Again, replace `/wp-content/your-json-directory/` with the correct path. Restart lighttpd after making changes.

### Why?
- These rules prevent anyone from downloading your BricksSync JSON files if they guess or know the URL.
- Your site and data are safer from leaks or unwanted access.

### Advanced Security
For maximum protection, you may store your JSON files **outside the web root** (not directly accessible via HTTP), and use custom code to serve/import them as needed.

If you need help configuring your server, contact your hosting provider or consult the BricksSync documentation/support.

Plugin Updates
--------------
If your BricksSync plugin has an active license, updates are delivered through the default WordPress plugin update mechanism. You can update BricksSync manually from the Plugins page, or enable auto-updates to keep your installation up-to-date automatically. This ensures you receive the latest features, improvements, and security fixes as soon as they are released.