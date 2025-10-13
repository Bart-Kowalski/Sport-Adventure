<?php
/**
 * Plugin Name:     WPCodeBox 2
 * Plugin URI:      https://wpcodebox.com
 * Description:     The most powerful Code Snippets Manager for WordPress.
 * Author:          WPCodeBox
 * Author URI:      https://wpcodebox.com
 * Text Domain:     wpcodebox
 * Domain Path:     /languages
 * Version:         1.1.1
 *
 */

if (!defined('ABSPATH')) {
    exit;
}

define('WPCODEBOX2_VERSION', '1.1.1');

include_once(__DIR__ . '/src/Bootstrap.php');
include_once(__DIR__ . '/api.php');

define('WPCODEBOX2_PATH', plugin_dir_path(__FILE__));

$errorSnippetId = false;

$bootstrap = new \Wpcb2\Bootstrap();

class Wpcb2CurrentSnippet
{
    public static $currentSnippet = false;
}

spl_autoload_register(array($bootstrap, 'autoload'));


function wpcb2_error_handler($message)
{

    global $errorSnippetId;
    if ($errorSnippetId) {
		$snippetRepository = new \Wpcb2\Repository\SnippetRepository();
		$snippetRepository->updateSnippet($errorSnippetId, [
			'enabled' => 0,
			'error' => 1,
			'error_message' => 'Not available in PHP 5.x. For more details install PHP 7.0 or higher.',
			'error_trace' => 'Not available in PHP 5.x. For more details install PHP 7.0 or higher.'

		]);

        @header('Location: ' . $_SERVER['REQUEST_URI']);
    }

    return $message;
}

$wpcb2Service = new \Wpcb2\Service\WPCodeBox2();
$wpcb2Service->executeSnippets(__DIR__);
$wpcb2Service->outputMenuItem(__FILE__);
$wpcb2Service->initWpcb(__FILE__, __DIR__);
$wpcb2Service->outputQuickActions(plugin_dir_url(__FILE__));
$wpcb2Service->checkTokens();
$wpcb2Service->checkForUpdates(wp_normalize_path(__FILE__), wp_normalize_path(plugin_dir_path(__FILE__)));
$wpcb2Service->updateDbSchema();

register_activation_hook(__FILE__, function() {

	global $wpdb;
	$charsetCollate = $wpdb->get_charset_collate();

	$snippetsTableName = $wpdb->prefix . 'wpcb_snippets';
	$foldersTableName = $wpdb->prefix . 'wpcb_folders';

	$sql = "CREATE TABLE `$foldersTableName` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `savedToCloud` smallint NOT NULL DEFAULT '0',
  `remoteId` int NOT NULL,
  `folder_order` int NOT NULL,
  PRIMARY KEY (id)

) $charsetCollate;

CREATE TABLE `$snippetsTableName` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `enabled` tinyint NOT NULL DEFAULT '0',
  `priority` int NOT NULL,
  `runType` varchar(100) NOT NULL,
  `code` longtext NOT NULL,
  `original_code` longtext NOT NULL,
  `codeType` varchar(100) NOT NULL,
  `conditions` text NOT NULL,
  `location` varchar(20) NOT NULL,
  `tagOptions` varchar(100) NOT NULL,
  `hook` varchar(1000) NOT NULL,
  `renderType` varchar(20) NULL,
  `minify` tinyint NOT NULL DEFAULT '0',
  `snippet_order` int NOT NULL,
  `addToQuickActions` smallint NOT NULL DEFAULT '0',
  `savedToCloud` smallint NOT NULL DEFAULT '0',
  `remoteId` INT NOT NULL DEFAULT '0',
  `externalUrl` smallint NOT NULL DEFAULT '0',
  `secret` varchar(50) NOT NULL,
  `folderId` int NOT NULL,
  `error` tinyint NOT NULL DEFAULT '0',
  `errorMessage` text NOT NULL,
  `errorTrace` text NOT NULL,
  `errorLine` INT NOT NULL DEFAULT '0',
  `devMode` smallint NOT NULL DEFAULT '0',
  `lastModified` varchar(100) NOT NULL,
  PRIMARY KEY (id)

) $charsetCollate;


ALTER TABLE `$snippetsTableName` ADD INDEX( `enabled`, `runType`);
ALTER TABLE `$snippetsTableName` ADD INDEX( `priority`);


";

	update_option('wpcb2_version', WPCODEBOX2_VERSION);

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
});


