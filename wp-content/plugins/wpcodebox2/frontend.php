<?php
if (!defined('ABSPATH')) {
    exit;
}

if(!current_user_can('manage_options')) {
	exit;
}
$secretKey = new \Wpcb2\Service\SecretKey();
$snippetRepository = new \Wpcb2\Repository\SnippetRepository();
$scssPartials = $snippetRepository->getPartials();
$themeMapper = new \Wpcb2\Service\ThemeMapper();
$manager = new \Wpcb2\FunctionalityPlugin\Manager(false);
$slugifier = new \Wpcb2\FunctionalityPlugin\Service\Slugifier();
?>

<script type="text/javascript">
    window.WPCB_API_BASE_LOCAL_URL = '<?php menu_page_url('wpcodebox2', true);?>';
    window.WPCB_API_BASE_REMOTE_URL = '<?php echo WPCB2_REMOTE_URL; ?>';
    window.WPCB_NONCE = '<?php echo esc_js(wp_create_nonce('wpcb-api-nonce')); ?>';
    window.WPCB_API_KEY = '<?php echo defined('WPCB_API_KEY') ? WPCB_API_KEY : esc_js(get_option("wpcb_settings_api_key"));?>';
    window.WPCB_EDITOR_FONT_SIZE = '<?php echo esc_js(get_option('wpcb_settings_editor_font_size', 16)); ?>';
    window.WPCB_EDITOR_THEME = '<?php echo esc_js($themeMapper->getTheme(get_option('wpcb2_settings_editor_theme', 'dracula')));?>';
    window.WPCB_CHECK_FOR_UPDATES = <?php echo esc_js(get_option('wpcb_check_for_updates', false) ? 'true' : 'false');?>;
    window.WPCB_WRAP_LONG_LINES = <?php echo esc_js(get_option('wpcb_wrap_long_lines', false) ? 'true' : 'false');?>;
    window.WPCB_DARK_MODE = <?php echo esc_js(get_option('wpcb_dark_mode', true) ? 'true' : 'false');?>;
    window.WPCB_EDITOR_IN_THE_MIDDLE = <?php echo esc_js(get_option('wpcb_editor_in_the_middle', true) ? 'true' : 'false');?>;
    window.WPCB_EDITOR_SHOW_CODEMAP = <?php echo esc_js(get_option('wpcb_show_codemap', false) ? 'true' : 'false');?>;
    window.WPCB_SECRET = '<?php echo esc_js($secretKey->generateKey()); ?>';
    window.WPCB_HOME_URL = '<?php echo esc_url_raw(get_home_url()); ?>';
    window.WPCB_ACF_ENABLED = <?php echo class_exists('ACF') ? 'true' : 'false'; ?>;
    window.WPCB_METABOX_ENABLED = <?php echo defined('RWMB_VER') ? 'true' : 'false'; ?>;
    window.WPCB_ENABLE_FUNCTIONALITY_PLUGIN = <?php echo $manager->isEnabled() ? 'true' : 'false'; ?>;
    window.WPCB_SHOW_IN_TOOLS = <?php echo esc_js(get_option('wpcb_show_in_tools', false) ? 'true' : 'false'); ?>;
    window.WPCB_WOOCOMMERCE_ENABLED = <?php echo class_exists('woocommerce') ? 'true' : 'false';?>;
	window.WPCB_SAFE_MODE = <?php echo defined('WPCB_SAFE_MODE') ? 'true' : 'false'; ?>;
    window.WPCB_TOGGLES = <?php echo esc_js(get_option('wpcb_toggles', false) ? 'true' : 'false');?>;
	window.WPCB_API_KEY_IN_WP_CONFIG = <?php echo defined('WPCB_API_KEY') ? 'true' : 'false';?>;
    window.WPCB_OXYGEN_INSTALLED = false;
    window.WPCB_BRICKS_INSTALLED = false;
    window.WPCB_ACSS_INSTALLED = false;
	window.WPCB_FP_PLUGIN_NAME = '<?php echo esc_js( defined('WPCB_FP_PLUGIN_NAME') ? WPCB_FP_PLUGIN_NAME : 'WPCodeBox Functionality Plugin'); ?>';
	window.WPCB_FP_PLUGIN_FILE_NAME = '<?php echo esc_js( $slugifier->slugify(defined('WPCB_FP_PLUGIN_NAME') ? WPCB_FP_PLUGIN_NAME : 'WPCodeBox Functionality Plugin')); ?>.zip';

    <?php


    if(defined('CT_VERSION')) {

    $colors = get_option('oxygen_vsb_global_colors', []);

    if(is_array($colors['colors']) && count($colors['colors'])) {

        ?>
        window.WPCB_OXYGEN_INSTALLED = true;
        window.WPCB_OXYGEN_COLORS = <?php echo json_encode($colors['colors']); ?>;
        <?php
        }

    }

    if(defined('BRICKS_VERSION')) {

        $palettes = get_option('bricks_color_palette', []);
        $bricksColors = [];

        if(is_array($palettes) && count($palettes)) {
            foreach ($palettes as $palette) {
                if (isset($palette['colors']) && is_array($palette['colors'])) {
                    foreach ($palette['colors'] as $color) {
                        if(isset($color['hex']) && isset($color['id'])) {
                            $bricksColors[] = ['color' => $color['hex'], 'id' => $color['id']];
                        }
                    }
                }
            }
        }

        if(is_array($bricksColors) && count($bricksColors)) {
            ?>
            window.WPCB_BRICKS_INSTALLED = true;
            window.WPCB_BRICKS_COLORS = <?php echo json_encode($bricksColors); ?>;
            <?php
        }

    }


    if ($scssPartials) {
        echo "window.WPCB_SCSS_PARTIALS = [" . implode(',', $scssPartials) . "];";
    } else {
        echo "window.WPCB_SCSS_PARTIALS = [];";

    }
    ?>
</script>

<style type="text/css">
    @font-face {
        font-family: 'Droid Sans Mono Regular';
        font-style: normal;
        font-weight: normal;
        src: local('Droid Sans Mono Regular'), url('<?php echo plugin_dir_url('wpcodebox/wpcodebox.php') . 'fonts/DroidSansMono.woff';?>') format('woff');
    }
</style>
<?php if (getenv('WPCODEBOX_DEV')) {
    ?>
    <script type="text/javascript" src="//localhost:3000/ace/ace.js"></script>
    <script type="text/javascript" src="//localhost:3000/ace/ext-language_tools.js"></script>
    <script type="text/javascript" src="//localhost:3000/ace/ext-emmet.js"></script>
    <script type="text/javascript" src="//localhost:3000/ace/theme-ambiance.js"></script>
    <script type="text/javascript" src="//localhost:3000/ace/mode-php.js"></script>
    <script type="text/javascript" src="//localhost:3000/WPCBPHPParser.js"></script>
    <div id="root"></div>
    <script defer async src="http://localhost:3000/static/js/bundle.js"></script>
    <script defer async src="http://localhost:3000/static/js/main.chunk.js"></script>

    <?php
    if(defined('ACSS_PLUGIN_FILE')) { ?>
    <script src="//localhost:3000/ACSS_Variables.js?ver=<?php echo WPCODEBOX2_VERSION; ?>"></script>
<?php } ?>

<?php } else {
    $plugin_url = plugin_dir_url(__FILE__);
    ?>
    <script type="text/javascript"
            src="<?php echo $plugin_url; ?>dist/ace/ace.js?ver=<?php echo WPCODEBOX2_VERSION; ?>"></script>
    <script type="text/javascript" src="<?php echo $plugin_url; ?>dist/WPCBPHPParser.js"></script>
    <script type="text/javascript"
            src="<?php echo $plugin_url; ?>dist/ace/ext-language_tools.js?ver=<?php echo WPCODEBOX2_VERSION; ?>"></script>
    <script type="text/javascript"
            src="<?php echo $plugin_url; ?>dist/ace/theme-ambiance.js?ver=<?php echo WPCODEBOX2_VERSION; ?>"></script>
    <script type="text/javascript"
            src="<?php echo $plugin_url; ?>dist/ace/mode-php.js?ver=<?php echo WPCODEBOX2_VERSION; ?>"></script>
    <div id="root"></div>
    <script src="<?php echo $plugin_url; ?>dist/static/js/main.chunk.js?ver=<?php echo WPCODEBOX2_VERSION; ?>"></script>
    <script defer src="<?php echo $plugin_url; ?>dist/static/js/runtime-main.js?ver=<?php echo WPCODEBOX2_VERSION; ?>"></script>

    <link rel="stylesheet"
          href="<?php echo $plugin_url; ?>dist/static/css/main.chunk.css?ver=<?php echo WPCODEBOX2_VERSION; ?>">


    <?php
    if(defined('ACSS_PLUGIN_FILE')) { ?>
        <script src="<?php echo $plugin_url; ?>dist/ACSS_Variables.js?ver=<?php echo WPCODEBOX2_VERSION; ?>"></script>
    <?php }
}
?>
