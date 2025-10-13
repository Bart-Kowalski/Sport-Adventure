# Changelog

All notable changes to the BricksSync plugin are documented in this file.

## 1.1.1
- Auto-export now respects global/group/site sync mode settings in multisite. Subsites inherit sync mode if not set locally.
- Fixed require_once path for effective config resolution on multisite.
- Fully tested with Bricks 2.0.1.

## 1.1.0
- Skips template export if slug is empty.
- Added full support for Bricks Components in settings export. Global CSS is already included in Bricks Settings.
- Documentation and code cleanup.
- Thoroughly tested with Bricks 2.0-rc2.

## 1.0.0

### Major Changes
- Official 1.0 release!
- Compatibility fix for get_plugin_data() on older WordPress versions.
- Textarea fields (like excluded options) now preserve line breaks when saving.
- General documentation and version bump.

## 0.8.0-alpha

### Added
- **Multisite/Network Admin Support:**
  - BricksSync now fully supports WordPress Multisite installations. A dedicated Network Admin interface allows super admins to manage global, group, and per-site configuration for all subsites from a single dashboard.
  - New "Sites & Effective Configuration" tab displays all subsites with their effective (inherited/overridden) configuration in a unified table.
  - Site-specific overrides can be set and visualized for any subsite. Overrides are clearly indicated with a vertical badge at the start of each row.
  - **Reset Button:** Instantly remove site-specific overrides and revert to inherited configuration. The UI updates immediately after reset.
  - Group configuration: Manage shared settings for groups of sites, with inheritance logic for network > group > site.

### Improved
- **UI/UX Enhancements:**
  - Redesigned multisite tabs for clarity and consistency with single-site UI.
  - Override status is visually prominent and easy to identify.
  - Horizontal scrolling for large configuration tables.
  - All admin tabs are now more accessible and visually unified.
- **Security:**
  - Nonce verification and input sanitization for all multisite admin actions.

### Compatibility
- **Single Site:**
  - All multisite features are isolated—single site installs remain unaffected.
- **WP Cron & WP CLI:**
  - Cron jobs and WP CLI commands work on both single-site and multisite. For multisite, use `--url=` with WP CLI to target subsites. No network-wide CLI yet.

### Documentation
- Added detailed documentation for multisite/network admin features to all docs and README.

---

## 0.7.7-beta

### Fixed
- WP CLI functionality was fixed and expanded while at it.

## 0.7.6-beta

### Fixed
- A path error inside the template import function.

## 0.7.5-beta

### Fixed
- Another Error inside the licensing tab.

## 0.7.4-beta

### Fixed
- Error inside the licensing tab.

## 0.7.3-beta
### Added
- Expanded guides for Apache, Nginx, and lighttpd security in documentation.
- Added “Plugin Updates” section to documentation.
- Option to disable licensing checks via constant.
- General documentation and code cleanup.

### Changed
- Unified and modernized the admin UI for all plugin tabs for a consistent user experience.
- Improved error handling and validation for storage path and licensing.

### Fixed
- Syntax and layout issues in the configuration and debug tabs.
- Removal of duplicate or legacy UI remnants in all admin tabs.

---

## 0.7.2
### Added
- Automatic `.htaccess` creation to block direct access to JSON files on Apache.
- New recommendations for advanced server security and storing JSON outside web root.

---

## 0.7.1
### Added
- Debug tab with real-time log viewer, log clearing, and logging toggle.
- Improved admin feedback and error handling.

---

## 0.7.0
### Added
- Selective automation for settings and template sync.
- More granular configuration for file naming, subdirectories, and exclusion options.
- Improved WP-CLI integration for automation and advanced workflows.
- SureCart-based licensing system with activation and deactivation UI.
- Option to disable licensing checks via constant.
- Improved cron support for scheduled sync and automation.

---

## 0.6.0
### Added
- Support for custom JSON storage paths outside web root.

### Changed
- Improved error handling for unwritable directories.

---

## 0.5.0
### Added
- WP-CLI support for export/import commands.

### Changed
- Improved status messages and admin feedback.
- Added troubleshooting section to documentation.

---

## 0.4.0
### Added
- Automatic and scheduled sync modes (manual, import-only, export-only, full sync).
- Option to exclude specific settings from export.

---

## 0.3.0
### Added
- Support for exporting/importing Bricks templates (individual/all).
- Improved file naming options for settings and templates.
- Tabbed interface for Settings and Templates.

---

## 0.2.0
### Added
- Ability to configure storage location (Child Theme, Uploads, Custom Path).
- Improved admin interface with configuration and licensing tabs.

---

## 0.1.0
### Added
- Initial release: Basic plugin structure with admin interface and tab navigation.
- Manual export/import of Bricks Builder global settings to/from JSON.
- Basic admin tabs for configuration and settings.
