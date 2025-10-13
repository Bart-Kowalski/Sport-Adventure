<?php

namespace Bricksforge;

/**
 * Global Classes Handler
 */
class Elements
{

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {

        $elements = [
            [
                'id'   => 0,
                'path' => __DIR__ . '/flip-everything/FlipEverything.php',
            ],
            [
                'id'   => 1,
                'path' => __DIR__ . '/font-awesome/FontAwesome.php',
            ],
            [
                'id'   => 2,
                'path' => __DIR__ . '/before-and-after/BeforeAndAfter.php',
            ],
            [
                'id'   => 3,
                'path' => __DIR__ . '/popup-trigger/PopupTrigger.php',
            ],
            [
                'id'   => 4,
                'path' => __DIR__ . '/table-of-contents/TableOfContents.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/ProForms.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms-steps/ProFormsSteps.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/Text.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/Textarea.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/Number.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/Email.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/Date.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/Tel.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/Url.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/Hidden.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/Password.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/File.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/Select.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/OptionGroup.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/Option.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/CheckboxWrapper.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/Checkbox.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/CardCheckbox.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/ImageCheckbox.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/RadioWrapper.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/Radio.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/CardRadio.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/ImageRadio.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/Rating.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/RichText.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/Slider.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/ColorPicker.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/Calculation.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/Signature.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/FileDownload.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/Step.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/Previous.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/Next.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/SummaryButton.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/SubmitButton.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/Turnstile.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/Honeypot.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/ConditionalWrapper.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/Repeater.php',
            ],
            [
                'id'   => 5,
                'path' => __DIR__ . '/pro-forms/elements/LiveValue.php',
            ],
            [
                'id'   => 6,
                'path' => __DIR__ . '/scroll-video/ScrollVideo.php',
            ],
            [
                'id'   => 7,
                'path' => __DIR__ . '/option/Option.php',
            ],
            [
                'id'   => 8,
                'path' => __DIR__ . '/three-js/ThreeJs.php',
            ],
            [
                'id'   => 9,
                'path' => __DIR__ . '/lottie/Lottie.php',
            ],
            [
                'id'   => 10,
                'path' => __DIR__ . '/tour/Tour.php',
            ],
            [
                'id'   => 11,
                'path' => __DIR__ . '/nestable-button/NestableButton.php',
            ]

        ];

        $options = get_option('brf_activated_elements') ? get_option('brf_activated_elements') : false;

        if ($options === false) {
            return;
        }

        foreach ($elements as $element) {
            $activated = false;

            foreach ($options as $option) {
                if ($option->id == $element['id']) {
                    $activated = true;
                }
            }

            if ($activated === true) {
                // If is ID 5, load the ProForms Helper Class
                if ($element['id'] === 5) {
                    require_once __DIR__ . '/pro-forms/Helper.php';
                }

                \Bricks\Elements::register_element($element['path']);
            }
        }

        $this->injectData();
    }

    private function delete_temp_directory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->delete_temp_directory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        rmdir($dir);
    }

    public function injectData()
    {
        add_action('wp_enqueue_scripts', function () {
            $args = array(
                'siteurl'                   => get_option('siteurl'),
                'uploadsDirectory'         => wp_upload_dir()['baseurl'],
                'pluginurl'                 => BRICKSFORGE_URL,
                'nonce'                     => wp_create_nonce('wp_rest'),
                'ajaxNonce'                     => wp_create_nonce('bricksforge_ajax'),
                'apiurl'                    => get_rest_url() . "bricksforge/v1/",
                'ajaxurl'                  => admin_url('admin-ajax.php'),
            );

            wp_localize_script('bricksforge-elements', 'BRFELEMENTS', $args);
        });
    }

    public function get_current_user_role()
    {
        global $current_user;

        $user_roles = $current_user->roles;
        $user_role = array_shift($user_roles);

        return $user_role;
    }
}
