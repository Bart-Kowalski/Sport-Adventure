# BricksSync WP CLI Commands

This document describes the available WP CLI commands for BricksSync.

## Overview

BricksSync provides a set of WP CLI commands for managing Bricks Builder settings and templates from the command line. These commands enable:

- Export/Import of Bricks Builder settings
- Export/Import of Bricks templates
- Configuration management
- 0.7.7-beta Status checking

## Requirements

- WP CLI must be installed and available
- Active BricksSync license for most operations
- Appropriate file permissions for import/export

## Available Commands

### Settings Management

#### Export Settings
```bash
wp brickssync settings export [--output-file=<path>]
```

Exports all Bricks Builder settings to a JSON file. The file can be saved to a custom location or use the configured path.

**Options:**
- `--output-file`: Path to export the JSON file. If not provided, uses the configured path and filename.

**Examples:**
```bash
wp brickssync settings export
wp brickssync settings export --output-file=/path/to/my-settings.json
```

#### Import Settings
```bash
wp brickssync settings import [--file=<path>]
```

Imports Bricks Builder settings from a JSON file. The file can be read from a custom location or the configured path.

**Options:**
- `--file`: Path to the JSON file to import. If not provided, uses the configured path and filename.

**Examples:**
```bash
wp brickssync settings import
wp brickssync settings import --file=/path/to/import-settings.json
```

### Template Management

#### List Templates
```bash
wp brickssync templates list [--format=<format>]
```

Displays a list of all Bricks templates with their details.

**Options:**
- `--format`: Output format (table, json, csv, yaml, count). Default: table.

**Examples:**
```bash
wp brickssync templates list
wp brickssync templates list --format=csv
```

#### Export Templates
```bash
wp brickssync templates export [--output-dir=<path>] [--templates=<ids>]
```

Exports selected or all Bricks templates to JSON files.

**Options:**
- `--output-dir`: Directory to export templates to. If not provided, uses the configured path.
- `--templates`: Comma-separated list of template IDs to export. If not provided, exports all templates.

**Examples:**
```bash
wp brickssync templates export
wp brickssync templates export --output-dir=/path/to/templates
wp brickssync templates export --templates=123,456
```

#### Import Templates
```bash
wp brickssync templates import [--input-dir=<path>]
```

Imports Bricks templates from JSON files in a directory.

**Options:**
- `--input-dir`: Directory containing template JSON files. If not provided, uses the configured path.

**Examples:**
```bash
wp brickssync templates import
wp brickssync templates import --input-dir=/path/to/templates
```

### Configuration

#### Show Configuration
```bash
wp brickssync config show [--format=<format>]
```

Displays the current BricksSync configuration settings.

**Options:**
- `--format`: Output format (table, json, yaml). Default: table.

**Examples:**
```bash
wp brickssync config show
wp brickssync config show --format=json
```

### Status

#### Check Status
```bash
wp brickssync status [--format=<format>]
```

Displays the current status of BricksSync, including license status, storage path validation, and sync status.

**Options:**
- `--format`: Output format (table, json, yaml). Default: table.

**Examples:**
```bash
wp brickssync status
wp brickssync status --format=json
```

## Features

- Multiple output formats (table, JSON, CSV, YAML)
- Custom file path support for imports/exports
- License validation integration
- Detailed status reporting
- Error handling and logging
- Automatic directory creation
- Permission validation
- Progress reporting

## Error Handling

All commands include error handling and will display appropriate error messages if:
- The license is inactive
- File permissions are insufficient
- Required files or directories are missing
- Invalid parameters are provided

## Notes

- Most commands require an active BricksSync license
- File paths should be absolute
- The plugin must be properly configured before using these commands
- Debug logging can be enabled for troubleshooting 