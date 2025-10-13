<?php
/**
 * BricksSync Template Import Record Helpers
 * 
 * Stores and retrieves import record for template JSON files.
 *
 * @since 0.1
 */

/**
 * Get the import record (mtime/hash) for template files.
 * @return array
 */
function brickssync_get_template_import_record() {
    return get_option('brickssync_template_import_record', []);
}

/**
 * Update the import record (overwrite the whole record)
 * @param array $record
 * @return void
 */
function brickssync_update_template_import_record($record) {
    update_option('brickssync_template_import_record', $record);
}

/**
 * Update a single file entry in the import record.
 * @param string $filename
 * @param int $mtime
 * @param string $hash
 * @return void
 */
function brickssync_update_template_import_record_entry($filename, $mtime, $hash) {
    $record = brickssync_get_template_import_record();
    $record[$filename] = [
        'mtime' => $mtime,
        'hash' => $hash,
        'last_imported' => time(),
    ];
    brickssync_update_template_import_record($record);
}

/**
 * Get a single file entry from the import record.
 * @param string $filename
 * @return array|null
 */
function brickssync_get_template_import_record_entry($filename) {
    $record = brickssync_get_template_import_record();
    return $record[$filename] ?? null;
}
