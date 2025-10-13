<?php

namespace Bricks;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_Steps extends \Bricks\Element
{

    public $category = 'bricksforge';
    public $name = 'brf-pro-forms-steps';
    public $icon = 'ti ti-layout-tab-v';
    public $css_selector = '';
    public $scripts = [];
    public $nestable = false;

    public function get_label()
    {
        return esc_html__("Pro Forms Steps", 'bricksforge');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('bricksforge-elements');
    }

    public function set_control_groups()
    {
        $this->control_groups['multistepConnection'] = [
            'title' => esc_html__('Connection', 'bricks'),
            'tab'   => 'content',
        ];
        $this->control_groups['multistepStep'] = [
            'title' => esc_html__('Step Settings', 'bricks'),
            'tab'   => 'content',
        ];
    }

    public function set_controls()
    {

        $this->controls['multiStepFormId'] = [
            'tab'         => 'content',
            'group'       => 'multistepConnection',
            'label'       => esc_html__('Form ID', 'bricks'),
            'description' => 'To which form do the steps belong? (Example: brxe-ypcblm)',
            'type'        => 'text',
        ];

        $this->controls['multiStepFirstStep'] = [
            'tab'     => 'content',
            'group'   => 'multistepStep',
            'label'   => esc_html__('First Step Text', 'bricks'),
            'type'    => 'text',
            'default' => 'Start'
        ];

        $this->controls['multiStepStepTypography'] = [
            'tab'   => 'content',
            'group' => 'multistepStep',
            'label' => esc_html__('Step Typography', 'bricks'),
            'type'  => 'typography',
            'css'   => [
                [
                    'property' => 'typography',
                    'selector' => '.brf-step',
                ],
            ],
        ];

        $this->controls['multiStepCurrentStepTypography'] = [
            'tab'   => 'content',
            'group' => 'multistepStep',
            'label' => esc_html__('Current Step Typography', 'bricks'),
            'type'  => 'typography',
            'css'   => [
                [
                    'property' => 'typography',
                    'selector' => '.brf-step.current',
                ],
            ],
        ];

        $this->controls['multiStepStepBackgroundColor'] = [
            'tab'   => 'content',
            'group' => 'multistepStep',
            'label' => esc_html__('Step Background Color', 'bricks'),
            'type'  => 'color',
            'css'   => [
                [
                    'property' => 'background-color',
                    'selector' => '.brf-step',
                ],
            ],
        ];

        $this->controls['multiStepCurrentStepBackgroundColor'] = [
            'tab'   => 'content',
            'group' => 'multistepStep',
            'label' => esc_html__('Current Step Background Color', 'bricks'),
            'type'  => 'color',
            'css'   => [
                [
                    'property' => 'background-color',
                    'selector' => '.brf-step.current',
                ],
            ],
        ];

        $this->controls['multiStepStepWidth'] = [
            'tab'   => 'content',
            'group' => 'multistepStep',
            'label' => esc_html__('Step Width', 'bricks'),
            'unix'  => ['px'],
            'type'  => 'number',
            'css'   => [
                [
                    'property' => 'width',
                    'selector' => '.brf-step',
                ],
            ],
        ];

        $this->controls['multiStepStepPadding'] = [
            'tab'   => 'content',
            'group' => 'multistepStep',
            'label' => esc_html__('Step Padding', 'bricks'),
            'unix'  => ['px'],
            'type'  => 'spacing',
            'css'   => [
                [
                    'property' => 'padding',
                    'selector' => '.brf-step',
                ],
            ],
        ];

        $this->controls['multiStepStepFlexDirection'] = [
            'tab'   => 'content',
            'group' => 'multistepStep',
            'label' => esc_html__('Flex Direction', 'bricks'),
            'type'  => 'direction',
            'css'   => [
                [
                    'property' => 'flex-direction',
                    'selector' => '.brf-steps',
                ],
                [
                    'property' => 'flex-direction',
                    'selector' => '',
                ],
            ],
        ];

        $this->controls['multiStepStepJustifyContent'] = [
            'tab'   => 'content',
            'group' => 'multistepStep',
            'label' => esc_html__('Justify Content', 'bricks'),
            'type'  => 'justify-content',
            'css'   => [
                [
                    'property' => 'justify-content',
                    'selector' => '.brf-steps',
                ],
                [
                    'property' => 'justify-content',
                    'selector' => '',
                ],
            ],
        ];

        $this->controls['multiStepStepAlignItems'] = [
            'tab'   => 'content',
            'group' => 'multistepStep',
            'label' => esc_html__('Align Items', 'bricks'),
            'type'  => 'align-items',
            'css'   => [
                [
                    'property' => 'align-items',
                    'selector' => '.brf-steps',
                ],
                [
                    'property' => 'align-items',
                    'selector' => '',
                ],
            ],
        ];

        $this->controls['multiStepStepGap'] = [
            'tab'   => 'content',
            'group' => 'multistepStep',
            'label' => esc_html__('Gap', 'bricks'),
            'type'  => 'number',
            'units' => ['px'],
            'css'   => [
                [
                    'property' => 'gap',
                    'selector' => '.brf-steps',
                ],
                [
                    'property' => 'gap',
                    'selector' => '',
                ],
            ],
        ];

        $this->controls['multiStepStepAllowClicks'] = [
            'tab'     => 'content',
            'group'   => 'multistepStep',
            'label'   => esc_html__('Allow Clicks on Steps', 'bricks'),
            'type'    => 'checkbox',
            'default' => false
        ];

        $this->controls['multiStepStepTransition'] = [
            'tab'         => 'content',
            'group'       => 'multistepStep',
            'label'       => esc_html__('Step Transition', 'bricks'),
            'type'        => 'text',
            'default'     => 'all 150ms linear',
            'placeholder' => 'all 150ms linear',
            'css'         => [
                [
                    'property' => 'transition',
                    'selector' => '.brf-step',
                ],
            ],
        ];
    }

    public function render()
    {
        $settings = $this->settings;

        $this->set_attribute('_root', 'class', 'brf-steps-remote');

        if (isset($settings['multiStepFormId']) && $settings['multiStepFormId']) {
            $this->set_attribute('_root', 'data-brf-form-id', $settings['multiStepFormId']);
        }

        if (isset($settings['multiStepFirstStep']) && $settings['multiStepFirstStep']) {
            $this->set_attribute('_root', 'data-brf-first-step', $settings['multiStepFirstStep']);
        }

        if (isset($settings['multiStepStepAllowClicks']) && $settings['multiStepStepAllowClicks']) {
            $this->set_attribute('_root', 'data-brf-step-allow-clicks', 'true');
        }
?>
        <div <?php echo $this->render_attributes('_root'); ?>>
            <?php
            if (bricks_is_builder() || bricks_is_rest_call()) {
            ?>
                <span class="brf-step current">Step 1</span>
                <span class="brf-step">Step 2</span>
                <span class="brf-step">Step 3</span>
            <?php
            }
            ?>
        </div>
<?php
    }
}
