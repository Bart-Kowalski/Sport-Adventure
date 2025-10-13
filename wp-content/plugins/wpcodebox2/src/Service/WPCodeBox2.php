<?php

namespace Wpcb2\Service;


use Wpcb2\FunctionalityPlugin\Manager;

class WPCodeBox2
{

    public function checkForUpdates($path, $packagePath)
    {

        require_once plugin_dir_path(__FILE__) . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'wp-package-updater' . DIRECTORY_SEPARATOR . 'class-wp-package-updater.php';

        new \WPCB_WP_Package_Updater(
            'https://wpcodeboxupdates.com',
            $path,
            $packagePath);

    }

    public function checkTokens()
    {
        add_action('init', function(){

            if(isset($_GET['wpcb_token'])) {

                $manager = new \Wpcb2\FunctionalityPlugin\Manager(false);
                if($manager->isEnabled()) {
                    return false;
                }

                $secret = $_GET['wpcb_token'];

                if(!ctype_alnum($secret)) {
                    return false;
                }

                if(empty($secret)){
                    return false;
                }


				$snippetRepository = new \Wpcb2\Repository\SnippetRepository();

				$snippets = $snippetRepository->getExternalRunSnippetsBySecret($secret);

				if(!$snippets || count($snippets) === 0) {
					return false;
				}

				if(is_array($snippets)) {
					$snippet = $snippets[0];
				} else {
					return false;
				}

                // Check if run type is external
                if($snippet['runType'] !== 'external') {
                    return false;
                }

				$code = $snippet['code'];
                $pos = strpos($code, '<?php');
                if ($pos !== false) {
                    $code = substr_replace($code, '', $pos, strlen('<?php'));
                }

                eval($code);
            }
			return true;

        });
    }

    public function executeSnippets($dir)
    {
            $manager = new Manager(false);

            if($manager->isEnabled()) {
                return false;
            }

            if(($_SERVER['REQUEST_URI'] === '/wp-admin/tools.php?page=wpcodebox2&safe_mode=1'
                || $_SERVER['REQUEST_URI'] === '/wp-admin/admin.php?page=wpcodebox2&safe_mode=1')) {
                return true;
            }

            // Execute PHP Snippets that should run at the root level
            // Do not run snippets if SAFE MODE is on.
            if (defined('WPCB_SAFE_MODE')) {
                return true;
            }

            // Detect WPCB request and don't execute snippets
            if (isset($_GET['wpcb2_route'])) {
                if (!function_exists('getallheaders')) {
                    function getallheaders()
                    {
                        $headers = [];
                        foreach ($_SERVER as $name => $value) {
                            if (substr($name, 0, 5) == 'HTTP_') {
                                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                            }
                        }
                        return $headers;
                    }
                }

                $headers = array_change_key_case(getallheaders(), CASE_LOWER);

                $secret = $headers['x-wpcb-secret'];

                $secretKey = new \Wpcb2\Service\SecretKey();

                if ($secretKey->checkSecretKey($secret)) {
                    return;
                }

            }

            $queryRunner = new \Wpcb2\Runner\QueryRunner();
            $queryRunner->runQueries();

    }


    public function outputQuickActions($dir)
    {
        add_action('admin_bar_menu', function ($wp_admin_bar) {

            if (current_user_can('manage_options') && is_admin_bar_showing()) {

                $snippetRepository = new \Wpcb2\Repository\SnippetRepository();
                $snippets_added_to_quick_actions = $snippetRepository->getQuickActionsSnippets();

                if (count($snippets_added_to_quick_actions)) {

                    $args = array(
                        'id' => 'wpcodebox_quick_actions',
                        'title' => apply_filters('wpcb_quick_actions_text', 'WPCodeBox Quick Actions')
                    );
                    $wp_admin_bar->add_menu($args);


                }
            }
        }, 999);

        add_action('plugins_loaded', function () use ($dir) {

            $wpcb_quick_actions_function = function () use ($dir) {

                if (current_user_can('manage_options') && is_admin_bar_showing()) {

                    $snippetRepository = new \Wpcb2\Repository\SnippetRepository();

                    $snippets_added_to_quick_actions = $snippetRepository->getQuickActionsSnippets();

                    if(count($snippets_added_to_quick_actions)) {

                        wp_enqueue_script("jquery");

                        ?>
                        <style type="text/css">
                            #wpcb-quick-actions-menu {
                                display: none;
                                margin-left: 10px;
                                flex-direction: column;
                                position: absolute !important;
                                align-items: flex-start;
                            }

                            #wpcb-quick-actions-menu.visible {
                                display: flex !important;
                            }

                            ul#wpcb-quick-actions-menu li {
                                padding-left: 10px;
                                padding-right: 10px;
                                user-select: none;
                                cursor: pointer;
                                background-color: #1D2327;
                                display: flex;
                                width: 350px;
                            }

                            ul#wpcb-quick-actions-menu li:hover {
                                background-color: #2c3338;
                                color: #72aee6;
                            }

                            ul#wpcb-quick-actions-menu li > img {
                                margin-right: 10px;
                            }

                            ul#wpcb-quick-actions-menu li > img.running {
                                display: none;
                            }

                            #wp-admin-bar-wpcodebox_quick_actions {
                                background-color: #1d2327 !important;
                            }

                            #wp-admin-bar-wpcodebox_quick_actions div {
                                cursor: pointer !important;
                            }

                        </style>
                        <script type="text/javascript">
                            (function ($) {

                                $(document).ready(function () {

                                    var menuTimer;

                                    $(document).on('click', '.quick-action-snippet', function () {
                                        var id = $(this).data('snippet-id');

                                        $('#snippet-' + id).find('.running').show();
                                        $('#snippet-' + id).find('.play').hide();

                                        jQuery.ajax({

                                            url: '<?php echo get_admin_url(); ?>?page=wpcodebox2&wpcb2_route=/acs/snippets/' + id + '/run',
                                            type: 'post',
                                            headers: {
                                                'x-wpcb-authorization': '<?php echo wp_create_nonce('wpcb-api-nonce'); ?>'
                                            },
                                            success: function () {
                                                $('#snippet-' + id).find('.running').hide();
                                                $('#snippet-' + id).find('.play').css('display', 'flex');
                                            }
                                        });

                                    });

                                    $('<ul id="wpcb-quick-actions-menu" style="background-color: #1d2327;">' +
                                        <?php foreach($snippets_added_to_quick_actions as $snippet) { ?>
                                        '<li data-snippet-id="<?php echo $snippet['id']; ?>" id="snippet-<?php echo $snippet['id']; ?>" class="quick-action-snippet">' +
                                        '<img class="play" style="width: 10px;" src="<?php echo $dir;?>/icons/play-solid.svg" />' +
                                        '<img class="running" style="width: 10px;" src="<?php echo $dir;?>/icons/sync-solid.svg" />' +
                                        '<span><?php echo $snippet['title']; ?></span></li>' +
                                        <?php } ?>
                                        '</ul>').appendTo('#wp-admin-bar-wpcodebox_quick_actions');


                                    $('#wp-admin-bar-wpcodebox_quick_actions').hover(function () {
                                        menuTimer = setTimeout(function () {
                                            $('#wpcb-quick-actions-menu').addClass('visible');
                                        }, 300);

                                    }, function () {
                                        clearTimeout(menuTimer);
                                        $('#wpcb-quick-actions-menu').removeClass('visible');

                                    });
                                });


                            })(jQuery)

                        </script>
                        <?php
                    }
                }
            };

            add_action('admin_footer', $wpcb_quick_actions_function);
            add_action('wp_footer', $wpcb_quick_actions_function);


        });
    }

    function initWpcb($file, $dir) {

        $wpcb_first = function ($plugins) use ($file) {
            $path = str_replace(WP_PLUGIN_DIR . '/', '', $file);
            if ( $key = array_search( $path, $plugins ) ) {
                unset( $plugins[ $key ] );
                array_unshift( $plugins, $path );
            }
            return $plugins;
        };
        add_action('pre_update_option_active_plugins', $wpcb_first, 998, 1);
        add_action('pre_update_option_active_sitewide_plugins', $wpcb_first, 998, 1);

        register_activation_hook($file, function() use ($dir) {
            $api_key = get_option('wpcb_settings_api_key');

            if (!$api_key && file_exists($dir. DIRECTORY_SEPARATOR . 'apikey.php')) {

                require_once $dir . DIRECTORY_SEPARATOR . 'apikey.php';

                if (isset($wpcb_default_api_key)) {
                    update_option('wpcb_settings_api_key', $wpcb_default_api_key);
                    @unlink($dir . DIRECTORY_SEPARATOR . 'apikey.php');
                }
            }
        });

    }

    function outputMenuItem($file) {

        add_action('admin_head', function () {
            echo '<style type="text/css">#toplevel_page_wpcodebox2 > a > div.wp-menu-image.dashicons-before > img {width: 24px; padding-top: 7px;}</style>';
        });


        add_action('admin_menu', function() use ($file)
        {

            if (!get_option('wpcb_show_in_tools', false)) {
                add_menu_page('WPCodeBox 2', 'WPCodeBox 2', 'manage_options', 'wpcodebox2', function () {
                    include __DIR__ . '/../../frontend.php';
                }, plugin_dir_url($file) . '/logo.svg', 111);
            } else {
                add_management_page('WPCodeBox 2', 'WPCodeBox 2', 'manage_options', 'wpcodebox2', function () {
                    include __DIR__ .'/../../frontend.php';
                }, 111);
            }
        });

    }

	function updateDbSchema()
	{
		add_action('admin_init', function(){
			$currentVersion = get_option('wpcb2_version', 0);
			if(!$currentVersion) {
				return;
			}

			global $wpdb;

			if($currentVersion === '1.0.0' || $currentVersion === '1.0.1') {
				$wpdb->query('ALTER TABLE `' . $wpdb->prefix . 'wpcb_snippets` CHANGE `description` `description` TEXT NOT NULL');
				$wpdb->query('ALTER TABLE `' . $wpdb->prefix . 'wpcb_snippets` CHANGE `conditions` `conditions` TEXT NOT NULL');
				update_option('wpcb2_version', '1.0.2');
			}
			if($currentVersion === '1.0.0') {
				// Run this query with $wpdb->prefix
				$wpdb->query('ALTER TABLE `' . $wpdb->prefix . 'wpcb_snippets` CHANGE `code` `code` LONGTEXT NOT NULL, CHANGE `original_code` `original_code` LONGTEXT NOT NULL');
				update_option('wpcb2_version', '1.0.1');
			}
		});

	}
}
