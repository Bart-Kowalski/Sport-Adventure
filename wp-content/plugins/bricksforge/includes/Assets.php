<?php

namespace Bricksforge;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Scripts and Styles Class
 */
class Assets
{

    function __construct()
    {

        if (is_admin()) {
            add_action('admin_enqueue_scripts', [$this, 'register'], 5);
        } else {
            add_action('wp_enqueue_scripts', [$this, 'register'], 5);
        }
    }

    /**
     * Register our app scripts and styles
     *
     * @return void
     */
    public function register()
    {
        $this->register_scripts($this->get_scripts());
        $this->register_styles($this->get_styles());
    }

    /**
     * Register scripts
     *
     * @param  array $scripts
     *
     * @return void
     */
    private function register_scripts($scripts)
    {
        // Return if JS folder not exists
        if (!is_dir(BRICKSFORGE_PATH . '/assets/js')) {
            return;
        }

        foreach ($scripts as $handle => $script) {
            $deps = isset($script['deps']) ? $script['deps'] : false;
            $in_footer = isset($script['in_footer']) ? $script['in_footer'] : false;
            $version = isset($script['version']) ? $script['version'] : null;
            $is_module = isset($script['module']) && $script['module'] === true;

            if ($is_module && function_exists('wp_register_script_module')) {
                wp_register_script_module($handle, $script['src']);
            } else {
                wp_register_script($handle, $script['src'], $deps, $version, $in_footer);
            }
        }
    }

    /**
     * Register styles
     *
     * @param  array $styles
     *
     * @return void
     */
    public function register_styles($styles)
    {
        foreach ($styles as $handle => $style) {
            $deps = isset($style['deps']) ? $style['deps'] : false;

            wp_register_style($handle, $style['src'], $deps, BRICKSFORGE_VERSION);
        }
    }

    /**
     * Get all registered scripts
     *
     * @return array
     */
    public function get_scripts()
    {
        // Return if JS folder not exists
        if (!is_dir(BRICKSFORGE_PATH . '/assets/js')) {
            return;
        }

        $prefix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.min' : '';

        $scripts = [
            'bricksforge-runtime'        => [
                'src'       => BRICKSFORGE_ASSETS . '/bundle/runtime.js',
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/bundle/runtime.js'),
                'in_footer' => true
            ],
            'bricksforge-vendor'         => [
                'src'       => BRICKSFORGE_ASSETS . '/bundle/vendors.js',
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/bundle/vendors.js'),
                'in_footer' => true
            ],
            'bricksforge-builder'       => [
                'src'       => BRICKSFORGE_ASSETS . '/bundle/builder.js',
                'deps'      => ['bricksforge-vendor', 'bricksforge-runtime'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/bundle/builder.js'),
                'in_footer' => true
            ],
            'bricksforge-admin'          => [
                'src'       => BRICKSFORGE_ASSETS . '/bundle/admin.js',
                'deps'      => ['bricksforge-vendor', 'bricksforge-runtime'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/bundle/admin.js'),
                'in_footer' => true
            ],
            'bricksforge-font-uploader'  => [
                'src'       => BRICKSFORGE_ASSETS . '/js/bricksforge_font_uploader.js',
                'deps'      => [],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/js/bricksforge_font_uploader.js'),
                'in_footer' => true
            ],
            'bricksforge-gsap'                   => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/gsap.min.js',
                'deps'      => ['bricks-scripts'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/gsap.min.js'),
                'in_footer' => true
            ],
            'bricksforge-gsap-motionpath'        => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/MotionPathPlugin.min.js',
                'deps'      => ['bricks-scripts', 'bricksforge-gsap'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/MotionPathPlugin.min.js'),
                'in_footer' => true
            ],
            'bricksforge-gsap-motionpath-helper' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/MotionPathHelper.min.js',
                'deps'      => ['bricks-scripts', 'bricksforge-gsap'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/MotionPathHelper.min.js'),
                'in_footer' => true
            ],
            'bricksforge-gsap-scrolltrigger'     => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/ScrollTrigger.min.js',
                'deps'      => ['bricks-scripts', 'bricksforge-gsap'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/ScrollTrigger.min.js'),
                'in_footer' => true
            ],
            'bricksforge-gsap-morphsvg' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/MorphSVGPlugin.min.js',
                'deps'      => ['bricksforge-gsap'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/MorphSVGPlugin.min.js'),
                'in_footer' => true
            ],
            'bricksforge-gsap-draggable'         => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/Draggable.min.js',
                'deps'      => ['bricksforge-gsap'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/Draggable.min.js'),
                'in_footer' => true
            ],
            'bricksforge-gsap-flip'              => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/Flip.min.js',
                'deps'      => ['bricksforge-gsap'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/Flip.min.js'),
                'in_footer' => true
            ],
            'bricksforge-gsap-scrollsmoother'    => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/ScrollSmoother.min.js',
                'deps'      => ['bricksforge-gsap', 'bricksforge-gsap-scrolltrigger'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/ScrollSmoother.min.js'),
                'in_footer' => true
            ],
            'bricksforge-gsap-splittext'         => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/SplitText.min.js',
                'deps'      => ['bricksforge-gsap'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/SplitText.min.js'),
                'in_footer' => true
            ],
            'bricksforge-gsap-drawsvg' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/DrawSVGPlugin.min.js',
                'deps'      => ['bricksforge-gsap'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/DrawSVGPlugin.min.js'),
                'in_footer' => true
            ],
            'bricksforge-gsap-scrollto-plugin' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/ScrollToPlugin.min.js',
                'deps'      => ['bricksforge-gsap'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/ScrollToPlugin.min.js'),
                'in_footer' => true
            ],
            'bricksforge-animator'       => [
                'src'       => BRICKSFORGE_ASSETS . '/js/bricksforge_animator.js',
                'deps'      => [],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/js/bricksforge_animator.js'),
                'in_footer' => true
            ],
            'bricksforge-elements'       => [
                'src'       => BRICKSFORGE_ASSETS . '/js/bricksforge_elements.js',
                'deps'      => [],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/js/bricksforge_elements.js'),
                'in_footer' => true
            ],
            'bricksforge-panel'          => [
                'src'       => BRICKSFORGE_ASSETS . '/js/bricksforge_panel.js',
                'deps'      => ['bricks-scripts'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/js/bricksforge_panel.js'),
                'in_footer' => true
            ],
            'bricksforge-terminal'       => [
                'src'       => BRICKSFORGE_ASSETS . '/js/bricksforge_terminal.js',
                'deps'      => ['bricks-scripts'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/js/bricksforge_terminal.js'),
                'in_footer' => true
            ],
            'bricksforge-builder-scripts' => [
                'src'       => BRICKSFORGE_ASSETS . '/js/bricksforge_builder.js',
                'deps'      => [],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/js/bricksforge_builder.js'),
                'in_footer' => true
            ],
            'bricksforge-popups'         => [
                'src'       => BRICKSFORGE_ASSETS . '/js/bricksforge_popups.js',
                'deps'      => ['bricksforge-gsap'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/js/bricksforge_popups.js'),
                'in_footer' => true
            ],
            'bricksforge-scrollsmoother' => [
                'src'       => BRICKSFORGE_ASSETS . '/js/bricksforge_scrollsmoother.js',
                'deps'      => ['bricks-scripts'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/js/bricksforge_scrollsmoother.js'),
                'in_footer' => true
            ],
            'bricksforge-lenis'          => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/lenis.js',
                'deps'      => [],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/lenis.js'),
                'in_footer' => true
            ],
            'bricksforge-scrolly-video'  => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/scrolly-video.js',
                'deps'      => ['bricks-scripts'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/scrolly-video.js'),
                'in_footer' => true
            ],
            'bricksforge-hcaptcha'               => [
                'src'       => 'https://js.hcaptcha.com/1/api.js',
                'deps'      => ['bricks-scripts'],
                'version'   => '1.0',
                'in_footer' => true
            ],
            'bricksforge-turnstile'     => [
                'src'       => 'https://challenges.cloudflare.com/turnstile/v0/api.js',
                'deps'      => [],
                'version'   => null,
                'in_footer' => true
            ],
            'bricksforge-quill' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/quill.min.js',
                'deps'      => ['bricks-scripts'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/quill.min.js'),
                'in_footer' => true
            ],
            'bricksforge-three-js' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/three.min.js',
                'deps'      => ['bricks-scripts'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/three.min.js'),
                'in_footer' => true
            ],
            'bricksforge-gltf-loader' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/GLTFLoader.js',
                'deps'      => ['bricksforge-three-js'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/GLTFLoader.js'),
                'in_footer' => true
            ],
            'bricksforge-orbit-controls' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/OrbitControls.min.js',
                'deps'      => ['bricksforge-three-js'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/OrbitControls.min.js'),
                'in_footer' => true
            ],
            'bricksforge-lottie' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/lottie-player.min.js',
                'deps'      => ['bricks-scripts'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/lottie-player.min.js'),
                'in_footer' => true
            ],
            'bricksforge-rellax' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/rellax.min.js',
                'deps'      => ['bricks-scripts'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/rellax.min.js'),
                'in_footer' => true
            ],
            'bricksforge-nouislider' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/nouislider.min.js',
                'deps'      => ['bricks-scripts'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/nouislider.min.js'),
                'in_footer' => true
            ],
            'bricksforge-swup' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/swup.min.js',
                'deps'      => ['bricks-scripts'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/swup.min.js'),
                'in_footer' => true
            ],
            'bricksforge-swup-head-plugin' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/swup-head-plugin.min.js',
                'deps'      => ['bricks-scripts', 'bricksforge-swup'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/swup-head-plugin.min.js'),
                'in_footer' => true
            ],
            'bricksforge-swup-body-class-plugin' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/swup-body-class-plugin.min.js',
                'deps'      => ['bricks-scripts', 'bricksforge-swup'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/swup-body-class-plugin.min.js'),
                'in_footer' => true
            ],
            'bricksforge-swup-js-plugin' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/swup-js-plugin.min.js',
                'deps'      => ['bricks-scripts', 'bricksforge-swup'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/swup-js-plugin.min.js'),
                'in_footer' => true
            ],
            'bricksforge-swup-accessibility-plugin' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/swup-accessibility-plugin.min.js',
                'deps'      => ['bricks-scripts', 'bricksforge-swup'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/swup-accessibility-plugin.min.js'),
                'in_footer' => true
            ],
            'bricksforge-swup-morph-plugin' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/swup-morph-plugin.min.js',
                'deps'      => ['bricks-scripts', 'bricksforge-swup'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/swup-morph-plugin.min.js'),
                'in_footer' => true
            ],
            'bricksforge-choices' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/choices.min.js',
                'deps'      => ['bricks-scripts'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/choices.min.js'),
                'in_footer' => true
            ],
            'bricksforge-confetti' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/confetti.browser.min.js',
                'deps'      => ['bricks-scripts'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/confetti.browser.min.js'),
                'in_footer' => true
            ],
            'bricksforge-coloris' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/coloris.min.js',
                'deps'      => ['bricks-scripts'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/coloris.min.js'),
                'in_footer' => true
            ],
            'bricksforge-imask' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/imask.js',
                'deps'      => ['bricks-scripts'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/imask.js'),
                'in_footer' => true
            ],
            'bricksforge-shepherd' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/shepherd.min.js',
                'deps'      => ['bricks-scripts'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/shepherd.min.js'),
                'in_footer' => true
            ],
            'bricksforge-expr-eval' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/expr-eval.min.js',
                'deps'      => ['bricks-scripts'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/expr-eval.min.js'),
                'in_footer' => true
            ],
            'bricksforge-signature-pad' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/signature_pad.umd.min.js',
                'deps'      => ['bricks-scripts'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/signature_pad.umd.min.js'),
                'in_footer' => true
            ],
            'bricksforge-filepond' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/filepond.js',
                'deps'      => ['bricks-scripts'],
                'version' => filemtime(BRICKSFORGE_PATH . '/assets/vendor/filepond.js'),
                'in_footer' => true
            ],
            'bricksforge-filepond-plugin-image-preview' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/filepond-plugin-image-preview.js',
                'deps'      => ['bricks-scripts'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/filepond-plugin-image-preview.js'),
                'in_footer' => true,
                'module' => false
            ],
            'bricksforge-pintura-validate-type' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/filepond-plugin-file-validate-type.js',
                'deps'      => ['bricks-scripts'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/filepond-plugin-file-validate-type.js'),
                'in_footer' => true,
                'module' => false
            ],
            'bricksforge-file-locale-de' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/file-locale-de.js',
                'deps'      => ['bricks-scripts'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/file-locale-de.js'),
                'in_footer' => true,
                'module' => false
            ],
            'bricksforge-file-locale-es' => [
                'src'       => BRICKSFORGE_ASSETS . '/vendor/file-locale-es.js',
                'deps'      => ['bricks-scripts'],
                'version'   => filemtime(BRICKSFORGE_PATH . '/assets/vendor/file-locale-es.js'),
                'in_footer' => true,
                'module' => false
            ],
            'bricksforge-file-locale-custom' => [
                'src'       => wp_upload_dir()['baseurl'] . '/bricksforge/pro-forms/file-locale-custom.js',
                'deps'      => ['bricks-scripts'],
                'version'   => '1.0',
                'in_footer' => true,
            ],
        ];

        return $scripts;
    }

    /**
     * Get registered styles
     *
     * @return array
     */
    public function get_styles()
    {

        $styles = [
            'bricksforge-style'    => [
                'src'  => BRICKSFORGE_ASSETS . '/css/style.css'
            ],
            'bricksforge-builder' => [
                'src' => BRICKSFORGE_ASSETS . '/css/builder.css',
                'deps' => ['bricks-frontend']
            ],
            'bricksforge-admin'    => [
                'src' => BRICKSFORGE_ASSETS . '/css/admin.css'
            ],
            'bricksforge-vendors'    => [
                'src' => BRICKSFORGE_ASSETS . '/css/vendors.css',
                'deps' => ['bricks-frontend']
            ],
            'bricksforge-quill-snow' => [
                'src' => BRICKSFORGE_ASSETS . '/vendor/quill.snow.css'
            ],
            'bricksforge-quill-bubble' => [
                'src' => BRICKSFORGE_ASSETS . '/vendor/quill.bubble.css'
            ],
            'bricksforge-nouislider' => [
                'src' => BRICKSFORGE_ASSETS . '/vendor/nouislider.min.css'
            ],
            'bricksforge-choices' => [
                'src' => BRICKSFORGE_ASSETS . '/vendor/choices.min.css'
            ],
            'bricksforge-coloris' => [
                'src' => BRICKSFORGE_ASSETS . '/vendor/coloris.min.css'
            ],
            'bricksforge-shepherd' => [
                'src' => BRICKSFORGE_ASSETS . '/vendor/shepherd.css'
            ],
            'bricksforge-filepond' => [
                'src' => BRICKSFORGE_ASSETS . '/vendor/filepond.css'
            ],
            'bricksforge-filepond-plugin-image-preview' => [
                'src' => BRICKSFORGE_ASSETS . '/vendor/filepond-plugin-image-preview.css'
            ],
            'bricksforge-pintura' => [
                'src' => BRICKSFORGE_ASSETS . '/vendor/pintura.css'
            ],
            'bricksforge-pintura-file-poster' => [
                'src' => BRICKSFORGE_ASSETS . '/vendor/filepond-plugin-file-poster.css'
            ],
        ];

        return $styles;
    }
}
