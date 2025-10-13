<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_File extends \Bricks\Element
{

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-file';
    public $icon = 'fa-solid fa-file';
    public $css_selector = '';
    public $scripts = [];
    public $nestable = false;

    public $default_labels = [];

    public function get_label()
    {
        return esc_html__("File", 'bricksforge');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_style('bricksforge-filepond');
        wp_enqueue_style('bricksforge-pintura');
        wp_enqueue_style('bricksforge-pintura-file-poster');

        //wp_enqueue_script('bricksforge-pintura-validate-type');

        // If Activate Image Editor is not checked
        if (!isset($this->settings['activateImageEditor']) || $this->settings['activateImageEditor'] !== true) {
            wp_enqueue_script('bricksforge-filepond-plugin-image-preview');
            wp_enqueue_style('bricksforge-filepond-plugin-image-preview');
        }

        $locale = isset($this->settings['labelLocale']) ? $this->settings['labelLocale'] : 'en';
        switch ($locale) {
            case 'en':
                break;
            case 'de':
                wp_enqueue_script('bricksforge-file-locale-de');
                break;
            case 'es':
                wp_enqueue_script('bricksforge-file-locale-es');
                break;
            case 'custom':
                wp_enqueue_script('bricksforge-file-locale-custom');
                break;
        }

        wp_enqueue_script('bricksforge-elements');
    }

    public function set_control_groups()
    {
        $this->control_groups['general'] = [
            'title'    => esc_html__('General', 'bricksforge'),
            'tab'      => 'content',
        ];
        $this->control_groups['config'] = [
            'title'    => esc_html__('Config', 'bricksforge'),
            'tab'      => 'content',
        ];
        $this->control_groups['initialFiles'] = [
            'title'    => esc_html__('Initial Files', 'bricksforge'),
            'tab'      => 'content',
        ];
        $this->control_groups['imageEditor'] = [
            'title'    => esc_html__('Image Editor', 'bricksforge'),
            'tab'      => 'content',
        ];
        $this->control_groups['crop'] = [
            'title'    => esc_html__('Crop', 'bricksforge'),
            'tab'      => 'content',
            'required' => [
                'allowCrop',
                '=',
                true
            ]
        ];
        $this->control_groups['sticker'] = [
            'title'    => esc_html__('Stickers', 'bricksforge'),
            'tab'      => 'content',
            'required' => [
                'allowSticker',
                '=',
                true
            ]
        ];
        $this->control_groups['imageQuality'] = [
            'title'    => esc_html__('Image Quality', 'bricksforge'),
            'tab'      => 'content',
        ];
        $this->control_groups['labels'] = [
            'title'    => esc_html__('Labels', 'bricksforge'),
            'tab'      => 'content',
        ];
        $this->control_groups['styles'] = [
            'title'    => esc_html__('Styles', 'bricksforge'),
            'tab'      => 'content',
        ];
        $this->control_groups['conditions'] = [
            'title'    => esc_html__('Conditions', 'bricksforge'),
            'tab'      => 'content',
        ];
    }

    public function set_controls()
    {
        $this->controls = array_merge($this->controls, Helper::get_default_controls('file'));

        $this->controls['fileUploadLimit'] = [
            'group'    => 'config',
            'label'    => esc_html__('Max Files', 'bricks'),
            'type'     => 'number',
            'min'      => 1,
            'max'      => 50,
        ];

        // Replace File (If 1)
        $this->controls['fileUploadReplace'] = [
            'group'    => 'config',
            'label'    => esc_html__('Replace file', 'bricks'),
            'type'     => 'checkbox',
            'default'  => false,
            'description' => esc_html__('If checked, the file will be replaced instead of added.', 'bricks'),
            'required' => ["fileUploadLimit", "=", "1"]
        ];

        $this->controls['fileUploadSize'] = [
            'group'    => 'config',
            'label'    => esc_html__('Max. size', 'bricks') . ' (MB)',
            'type'     => 'number',
            'min'      => 1,
            'max'      => 50,
        ];

        $this->controls['fileUploadDisableDeletion'] = [
            'group'    => 'config',
            'label'    => esc_html__('Disable Deletion', 'bricks'),
            'type'     => 'checkbox',
            'default'  => false,
            'description' => esc_html__('If checked, the file cannot be deleted after upload..', 'bricks'),
        ];

        $this->controls['fileUploadDisableReorder'] = [
            'group' => 'config',
            'label' => esc_html__('Disable Reorder', 'bricks'),
            'type' => 'checkbox',
            'default' => false,
            'description' => esc_html__('If checked, the file cannot be reordered via drag and drop.', 'bricks'),
        ];

        $this->controls['fileUploadDisableDrop'] = [
            'group' => 'config',
            'label' => esc_html__('Disable Drag & Drop', 'bricks'),
            'type' => 'checkbox',
            'default' => false,
            'description' => esc_html__('If checked, the file cannot be uploaded via drag and drop.', 'bricks'),
        ];

        $this->controls['fileUploadDisableBrowse'] = [
            'group' => 'config',
            'label' => esc_html__('Disable Browse', 'bricks'),
            'type' => 'checkbox',
            'default' => false,
            'description' => esc_html__('If checked, the file cannot be uploaded via browse.', 'bricks'),
        ];

        $this->controls['fileUploadAllowedTypes'] = [
            'group'    => 'config',
            'label'       => esc_html__('Allowed file types', 'bricksforge'),
            'placeholder' => 'pdf,jpg,...',
            'type'        => 'text',
        ];

        $this->controls['fileUploadToServer'] = [
            'group' => 'config',
            'label' => esc_html__('Direct Upload to Server', 'bricksforge'),
            'type' => 'checkbox',
            'default' => false,
            'description' => esc_html__('If checked, the file will be uploaded to the server immediately. Important: Direct Upload is not supported inside Repeater Fields.', 'bricksforge'),
        ];

        // Styles 
        $this->controls['theme'] = [
            'group' => 'styles',
            'label' => esc_html__('Theme', 'bricksforge'),
            'type' => 'select',
            'options' => [
                'userPreference' => esc_html__('User Preference', 'bricksforge'),
                'light' => esc_html__('Light', 'bricksforge'),
                'dark' => esc_html__('Dark', 'bricksforge'),
            ],
            'default' => 'light',
        ];

        $this->controls['rootFontSize'] = [
            'group' => 'styles',
            'label' => esc_html__('Root Font Size', 'bricksforge'),
            'type' => 'number',
            'units' => true,
            'default' => 16,
            'css' => [
                [
                    'property' => 'font-size',
                    'selector' => '.filepond--root',
                ]
            ],
        ];
        $this->controls['fileInfoFontSize'] = [
            'group' => 'styles',
            'label' => esc_html__('File Info Font Size', 'bricksforge'),
            'type' => 'number',
            'units' => true,
            'css' => [
                [
                    'property' => 'font-size',
                    'selector' => '.filepond--file',
                ]
            ],
        ];

        $this->controls['dragAreaBackgroundColor'] = [
            'group' => 'styles',
            'label' => esc_html__('Drag Area Background Color', 'bricksforge'),
            'type' => 'color',
            'css' => [
                [
                    'property' => 'background-color',
                    'selector' => 'div.filepond--panel-root',
                ],
                [
                    'property' => 'background-color',
                    'selector' => '.filepond--drop-label',
                ]
            ],
        ];

        $this->controls['dragAreaTypography'] = [
            'group' => 'styles',
            'label' => esc_html__('Drag Area Typography', 'bricksforge'),
            'type' => 'typography',
            'css' => [
                [
                    'property' => 'typography',
                    'selector' => 'div.filepond--drop-label label',
                ]
            ],
        ];

        $this->controls['dragAreaBorder'] = [
            'group' => 'styles',
            'label' => esc_html__('Drag Area Border', 'bricksforge'),
            'type' => 'border',
            'css' => [
                [
                    'property' => 'border',
                    'selector' => 'div.filepond--panel-root',
                ]
            ],
        ];

        $this->controls['dragAreaBoxShadow'] = [
            'group' => 'styles',
            'label' => esc_html__('Drag Area Box Shadow', 'bricksforge'),
            'type' => 'box-shadow',
            'css' => [
                [
                    'property' => 'box-shadow',
                    'selector' => 'div.filepond--panel-root',
                ]
            ],
        ];

        $this->controls['filePosterMaxHeight'] = [
            'group' => 'styles',
            'label' => esc_html__('File Poster Max Height (px)', 'bricksforge'),
            'type' => 'number',
            'units' => false,
            'default' => 256,
            'description' => esc_html__('The maximum height of the file poster.', 'bricksforge'),
        ];

        $this->controls['filePosterWidth'] = [
            'group' => 'styles',
            'label' => esc_html__('File Poster Width', 'bricksforge'),
            'type' => 'number',
            'units' => true,
            'placeholder' => "calc(50% - 0.5em)",
            'description' => esc_html__('The width of the file poster. Write "calc(50% - 0.5em)" for a grid layout.', 'bricksforge'),
            'css' => [
                [
                    'property' => 'width',
                    'selector' => '.filepond--item',
                ]
            ],
        ];

        // Image Editor
        $this->controls['activateImageEditor'] = [
            'group' => 'imageEditor',
            'label' => esc_html__('Activate Image Editor', 'bricksforge'),
            'type' => 'checkbox',
            'default' => false,
            'description' => esc_html__('If checked, the image editor will be activated.', 'bricksforge'),
        ];

        $this->controls['allowCrop'] = [
            'group' => 'imageEditor',
            'label' => esc_html__('Allow Crop', 'bricksforge'),
            'type' => 'checkbox',
            'default' => true,
            'description' => esc_html__('If checked, the crop tool will be available.', 'bricksforge'),
            'required' => ["activateImageEditor", "=", true]
        ];

        $this->controls["cropAspectRatio"] = [
            'group' => 'crop',
            'label' => esc_html__('Aspect Ratio', 'bricksforge'),
            'type' => 'text',
            'default' => '16/9',
            'description' => esc_html__('The aspect ratio of the crop area. The format should be x/y.', 'bricksforge'),
            'required' => ["allowCrop", "=", true]
        ];

        $this->controls["cropShape"] = [
            'group' => 'crop',
            'label' => esc_html__('Shape', 'bricksforge'),
            'type' => 'select',
            'options' => [
                'default' => esc_html__('Default', 'bricksforge'),
                'circle' => esc_html__('Circle', 'bricksforge'),
            ],
            'default' => 'default',
            'description' => esc_html__('The shape of the crop area.', 'bricksforge'),
            'required' => ["allowCrop", "=", true]
        ];

        $this->controls['allowFinetune'] = [
            'group' => 'imageEditor',
            'label' => esc_html__('Allow Finetune', 'bricksforge'),
            'type' => 'checkbox',
            'default' => true,
            'description' => esc_html__('If checked, the finetune tool will be available.', 'bricksforge'),
            'required' => ["activateImageEditor", "=", true]
        ];

        $this->controls['allowAnnotate'] = [
            'group' => 'imageEditor',
            'label' => esc_html__('Allow Annotate', 'bricksforge'),
            'type' => 'checkbox',
            'default' => true,
            'description' => esc_html__('If checked, the annotate tool will be available.', 'bricksforge'),
            'required' => ["activateImageEditor", "=", true]
        ];

        $this->controls['allowFilter'] = [
            'group' => 'imageEditor',
            'label' => esc_html__('Allow Filter', 'bricksforge'),
            'type' => 'checkbox',
            'default' => false,
            'description' => esc_html__('If checked, the filter tool will be available.', 'bricksforge'),
            'required' => ["activateImageEditor", "=", true]
        ];

        $this->controls['allowFrame'] = [
            'group' => 'imageEditor',
            'label' => esc_html__('Allow Frame', 'bricksforge'),
            'type' => 'checkbox',
            'default' => false,
            'description' => esc_html__('If checked, the frame tool will be available.', 'bricksforge'),
            'required' => ["activateImageEditor", "=", true]
        ];

        $this->controls['allowRedact'] = [
            'group' => 'imageEditor',
            'label' => esc_html__('Allow Redact', 'bricksforge'),
            'type' => 'checkbox',
            'default' => false,
            'description' => esc_html__('If checked, the redact tool will be available.', 'bricksforge'),
            'required' => ["activateImageEditor", "=", true]
        ];

        $this->controls['allowResize'] = [
            'group' => 'imageEditor',
            'label' => esc_html__('Allow Resize', 'bricksforge'),
            'type' => 'checkbox',
            'default' => false,
            'description' => esc_html__('If checked, the resize tool will be available.', 'bricksforge'),
            'required' => ["activateImageEditor", "=", true]
        ];

        $this->controls['allowFill'] = [
            'group' => 'imageEditor',
            'label' => esc_html__('Allow Fill', 'bricksforge'),
            'type' => 'checkbox',
            'default' => false,
            'description' => esc_html__('If checked, the fill tool will be available.', 'bricksforge'),
            'required' => ["activateImageEditor", "=", true]
        ];

        $this->controls['allowSticker'] = [
            'group' => 'imageEditor',
            'label' => esc_html__('Allow Sticker', 'bricksforge'),
            'type' => 'checkbox',
            'default' => false,
            'description' => esc_html__('If checked, the sticker tool will be available.', 'bricksforge'),
            'required' => ["activateImageEditor", "=", true]
        ];

        $this->controls['stickers'] = [
            'group' => 'sticker',
            'label' => esc_html__('Stickers', 'bricksforge'),
            'type' => 'repeater',
            'titleProperty' => 'sticker',
            'fields' => [
                'sticker' => [
                    'label' => esc_html__('Emoji or URL', 'bricksforge'),
                    'type' => 'text',
                ],
            ],
            'required' => ["allowSticker", "=", true]
        ];

        // Labels
        $this->default_labels = $this->get_default_labels();

        $this->controls["labelLocale"] = [
            'group' => 'labels',
            'label' => esc_html__('Locale', 'bricksforge'),
            'type' => 'select',
            'options' => [
                'en' => esc_html__('English', 'bricksforge'),
                'es' => esc_html__('Spanish', 'bricksforge'),
                'de' => esc_html__('German', 'bricksforge'),
                'custom' => esc_html__('Custom', 'bricksforge'),
            ],
            'default' => 'en',
        ];

        $this->controls["labelLocaleCustomInfo"] = [
            // File file-locale-custom.js in /wp-content/uploads/bricksforge/pro-forms/ directory
            'group' => 'labels',
            'type' => 'info',
            'content' => esc_html__('You can use a custom JS file to set the values of the labels. Bricksforge will search for the following file in the following directory: /wp-content/uploads/bricksforge/pro-forms/file-locale-custom.js', 'bricksforge'),
            'required' => ["labelLocale", "=", "custom"]
        ];

        $this->controls["labelLocaleInfo2"] = [
            'group' => 'labels',
            'label' => esc_html__('Locale', 'bricksforge'),
            'type' => 'info',
            'content' => esc_html__('Below, you can overwrite the translation of individual fields inside the builder.', 'bricksforge'),
        ];

        foreach ($this->default_labels as $label => $value) {
            $this->controls['label_' . $label . ''] = [
                'group' => 'labels',
                'label' => esc_html__($this->get_formatted_label($label), 'bricksforge'),
                'type' => 'text',
                'placeholder' => esc_html__($value, 'bricksforge'),
            ];
        }

        // Image Quality
        $this->controls['imageQualityInfo'] = [
            'group' => 'imageQuality',
            'label' => esc_html__('Image Quality', 'bricksforge'),
            'type' => 'info',
            'content' => esc_html__('You have the possibility to set the image quality for the image editor to automatically compress the uploaded image. The image quality is a value between 0 and 1. The default value is 0.92. A lower value will result in a lower image quality and a smaller file size. This is only supported for JPEG and WEBP.', 'bricksforge'),
        ];

        $this->controls['imageQuality'] = [
            'group' => 'imageQuality',
            'label' => esc_html__('Image Quality', 'bricksforge'),
            'type' => 'number',
            'units' => false,
            'min' => 0,
            'max' => 1,
            'step' => 0.01,
            'default' => 0.92,
        ];

        $this->controls['imageTargetSizeWidth'] = [
            'group' => 'imageQuality',
            'label' => esc_html__('Image Target Size Width', 'bricksforge'),
            'type' => 'number',
            'units' => false,
            'description' => esc_html__('The target width of the image in pixels. If the image is larger than the target size, the image will be resized to the target size.', 'bricksforge'),
        ];

        $this->controls['imageTargetSizeHeight'] = [
            'group' => 'imageQuality',
            'label' => esc_html__('Image Target Size Height', 'bricksforge'),
            'type' => 'number',
            'units' => false,
            'description' => esc_html__('The target height of the image in pixels. If the image is larger than the target size, the image will be resized to the target size.', 'bricksforge'),
        ];

        $this->controls['imageTargetSizeFit'] = [
            'group' => 'imageQuality',
            'label' => esc_html__('Image Target Size Fit', 'bricksforge'),
            'type' => 'select',
            'options' => [
                'contain' => esc_html__('Contain', 'bricksforge'),
                'cover' => esc_html__('Cover', 'bricksforge'),
                'force' => esc_html__('Force', 'bricksforge'),
            ],
            'default' => 'contain',
            'description' => esc_html__('The fit mode of the target size. The default value is contain. Forcing the image to the target size can result in a distorted image.', 'bricksforge'),
        ];

        // Initial Files
        $this->controls['initialFilesInfo'] = [
            'group' => 'initialFiles',
            'type' => 'info',
            'content' => esc_html__('Only works if Direct Upload To Server is set to true.', 'bricksforge'),
        ];
        $this->controls['initialFilesMethod'] = [
            'group' => 'initialFiles',
            'label' => esc_html__('Method', 'bricksforge'),
            'type' => 'select',
            'options' => [
                'custom' => esc_html__('Custom', 'bricksforge'),
                'acf' => esc_html__('ACF Gallery', 'bricksforge'),
                'metabox' => esc_html__('Metabox Gallery', 'bricksforge'),
                'acpt' => esc_html__('ACPT Gallery', 'bricksforge'),
                'jetengine' => esc_html__('JetEngine Gallery', 'bricksforge'),
            ],
            'default' => 'custom',
        ];

        // Custom (Repeater (URLs))
        $this->controls['initialFiles'] = [
            'group' => 'initialFiles',
            'label' => esc_html__('Files', 'bricksforge'),
            'type' => 'repeater',
            'titleProperty' => 'file',
            'fields' => [
                'file' => [
                    'label' => esc_html__('File URL / Attachment ID', 'bricksforge'),
                    'type' => 'text',
                ],
            ],
            'required' => ["initialFilesMethod", "=", "custom"]
        ];

        // Provider Field
        $this->controls['initialFilesPostId'] = [
            'group' => 'initialFiles',
            'label' => esc_html__('Post ID', 'bricksforge'),
            'type' => 'text',
            'required' => [
                ["initialFilesMethod", "!=", "custom"],
                ["initialFilesMethod", "!=", ""],
            ]
        ];

        // If ACPT, we additionally need a box name
        $this->controls['initialFilesAcptBoxName'] = [
            'group' => 'initialFiles',
            'label' => esc_html__('Box Name', 'bricksforge'),
            'type' => 'text',
            'required' => ["initialFilesMethod", "=", "acpt"]
        ];

        $this->controls['initialFilesField'] = [
            'group' => 'initialFiles',
            'label' => esc_html__('Field Name', 'bricksforge'),
            'type' => 'text',
            'placeholder' => 'the_field_name',
            'required' => [
                ["initialFilesMethod", "!=", "custom"],
                ["initialFilesMethod", "!=", ""],
            ]
        ];

        $this->controls = array_merge($this->controls, Helper::get_condition_controls());
        $this->controls = array_merge($this->controls, Helper::get_advanced_controls());
    }

    public function get_formatted_label($str)
    {
        // Add a space before each uppercase letter
        $str_with_spaces = preg_replace('/([a-z])([A-Z])/', '$1 $2', $str);
        // Capitalize the first letter of the resulting string
        $final_str = ucfirst($str_with_spaces);
        return $final_str;
    }

    public function get_default_labels()
    {
        // These are the lables which we find later on the file in the JS object
        $labels = [
            // Filepond
            "labelIdle" => "Drag & Drop your files or <span class='filepond--label-action'>Browse</span>",
            "labelInvalidField" => "Field contains invalid files",
            "labelFileWaitingForSize" => "Waiting for size",
            "labelFileSizeNotAvailable" => "Size not available",
            "labelFileLoading" => "Loading",
            "labelFileLoadError" => "Error during load",
            "labelFileProcessing" => "Processing",
            "labelFileProcessingComplete" => "Processing complete",
            "labelFileProcessingAborted" => "Processing aborted",
            "labelFileProcessingError" => "Error during processing",
            "labelFileProcessingAborted" => "Processing aborted",
            "labelFileProcessingError" => "Error during processing",
            "labelFileProcessingRevertError" => "Error during revert",
            "labelFileRemoveError" => "Error during remove",
            "labelTapToCancel" => "tap to cancel",
            "labelTapToRetry" => "tap to retry",
            "labelTapToUndo" => "tap to undo",
            "labelButtonRemoveItem" => "Remove",
            "labelButtonAbortItemLoad" => "Abort",
            "labelButtonRetryItemLoad" => "Retry",
            "labelButtonAbortItemProcessing" => "Cancel",
            "labelButtonUndoItemProcessing" => "Undo",
            "labelButtonRetryItemProcessing" => "Retry",
            "labelButtonProcessItem" => "Process",

            // Pintura
            "labelReset" => "Reset",
            "labelDefault" => "Default",
            "labelAuto" => "Auto",
            "labelNone" => "None",
            "labelEdit" => "Edit",
            "labelClose" => "Close",
            "labelSupportError" => "{features} not supported on this browser",
            "labelColor" => "Color",
            "labelWidth" => "Width",
            "labelSize" => "Size",
            "labelOffset" => "Offset",
            "labelAmount" => "Amount",
            "labelInset" => "Inset",
            "labelRadius" => "Radius",
            "labelColorPalette" => "Color palette",
            "labelSizeExtraSmall" => "Extra small",
            "labelSizeSmall" => "Small",
            "labelSizeMediumSmall" => "Medium small",
            "labelSizeMedium" => "Medium",
            "labelSizeMediumLarge" => "Medium large",
            "labelSizeLarge" => "Large",
            "labelSizeExtraLarge" => "Extra large",
            "labelButtonCancel" => "Cancel",
            "labelButtonUndo" => "Undo",
            "labelButtonRedo" => "Redo",
            "labelButtonRevert" => "Revert",
            "labelButtonExport" => "Done",
            "labelZoom" => "Zoom",
            "labelZoomIn" => "Zoom in",
            "labelZoomOut" => "Zoom out",
            "labelZoomFit" => "Fit to view",
            "labelZoomActual" => "Actual size",
            "statusLabelButtonClose" => "Close",
            "statusImageLoadNone" => "Waiting for image",
            "statusImageLoadMinSize" => "Minimum image size is {minWidth} &times; {minHeight}",
            "statusImageLoadPrepare" => "Preparing image&hellip;",
            "statusImageLoadBusy" => "Loading image&hellip;",
            "statusImageLoadError" => "Error loading image",
            "statusImageProcessBusy" => "Processing image&hellip;",
            "statusImageProcessError" => "Error processing image",
            "statusImageUploadBusy" => "Uploading image&hellip;",
            "statusImageUploadError" => "Error uploading image",
            "cropLabel" => "Crop",
            "cropLabelButtonRecenter" => "Recenter",
            "cropLabelButtonRotateLeft" => "Rotate left",
            "cropLabelButtonRotateRight" => "Rotate right",
            "cropLabelButtonFlipHorizontal" => "Flip horizontal",
            "cropLabelButtonFlipVertical" => "Flip vertical",
            "cropLabelSelectPreset" => "Crop shape",
            "cropLabelCropBoundary" => "Crop boundary",
            "cropLabelCropBoundaryEdge" => "Edge of image",
            "cropLabelCropBoundaryNone" => "None",
            "cropLabelTabRotation" => "Rotation",
            "cropLabelTabZoom" => "Scale",
            "filterLabel" => "Filter",
            "filterLabelChrome" => "Chrome",
            "filterLabelFade" => "Fade",
            "filterLabelCold" => "Cold",
            "filterLabelWarm" => "Warm",
            "filterLabelPastel" => "Pastel",
            "filterLabelMonoDefault" => "Mono",
            "filterLabelMonoNoir" => "Noir",
            "filterLabelMonoWash" => "Wash",
            "filterLabelMonoStark" => "Stark",
            "filterLabelSepiaDefault" => "Sepia",
            "filterLabelSepiaBlues" => "Blues",
            "filterLabelSepiaRust" => "Rust",
            "filterLabelSepiaColor" => "Color",
            "finetuneLabel" => "Finetune",
            "finetuneLabelBrightness" => "Brightness",
            "finetuneLabelContrast" => "Contrast",
            "finetuneLabelSaturation" => "Saturation",
            "finetuneLabelExposure" => "Exposure",
            "finetuneLabelTemperature" => "Temperature",
            "finetuneLabelGamma" => "Gamma",
            "finetuneLabelClarity" => "Clarity",
            "finetuneLabelVignette" => "Vignette",
            "resizeLabel" => "Resize",
            "resizeLabelFormCaption" => "Image output size",
            "resizeLabelInputWidth" => "w",
            "resizeTitleInputWidth" => "Width",
            "resizeLabelInputHeight" => "h",
            "resizeTitleInputHeight" => "Height",
            "resizeTitleButtonMaintainAspectRatio" => "Maintain aspect ratio",
            "decorateLabel" => "Decorate",
            "annotateLabel" => "Annotate",
            "stickerLabel" => "Sticker",
            "frameLabel" => "Frame",
            "frameLabelMatSharp" => "Mat",
            "frameLabelMatRound" => "Bevel",
            "frameLabelLineSingle" => "Line",
            "frameLabelLineMultiple" => "Zebra",
            "frameLabelEdgeSeparate" => "Inset",
            "frameLabelEdgeOverlap" => "Plus",
            "frameLabelEdgeCross" => "Lumber",
            "frameLabelCornerHooks" => "Hook",
            "frameLabelPolaroid" => "Polaroid",
            "redactLabel" => "Redact",
            "shapeLabelButtonSelectSticker" => "Select image",
            "shapeTitleButtonTextLayoutAutoWidth" => "Auto width",
            "shapeTitleButtonTextLayoutAutoHeight" => "Auto height",
            "shapeTitleButtonTextLayoutFixedSize" => "Fixed size",
            "shapeTitleButtonFlipHorizontal" => "Flip Horizontal",
            "shapeTitleButtonFlipVertical" => "Flip Vertical",
            "shapeTitleButtonRemove" => "Remove",
            "shapeTitleButtonDuplicate" => "Duplicate",
            "shapeTitleButtonMoveToFront" => "Move to front",
            "shapeLabelInputText" => "Edit text",
            "shapeLabelInputCancel" => "Cancel",
            "shapeLabelInputConfirm" => "Confirm",
            "shapeLabelStrokeNone" => "No outline",
            "shapeLabelFontStyleNormal" => "Normal",
            "shapeLabelFontStyleBold" => "Bold",
            "shapeLabelFontStyleItalic" => "Italic",
            "shapeLabelFontStyleItalicBold" => "Bold Italic",
            "shapeTitleBackgroundColor" => "Fill color",
            "shapeTitleCornerRadius" => "Corner radius",
            "shapeTitleFontFamily" => "Font",
            "shapeTitleFontSize" => "Font size",
            "shapeTitleFontStyle" => "Font style",
            "shapeTitleLineHeight" => "Line height",
            "shapeTitleLineStart" => "Start",
            "shapeTitleLineEnd" => "End",
            "shapeTitleStrokeWidth" => "Line width",
            "shapeTitleStrokeColor" => "Line color",
            "shapeTitleLineDecorationBar" => "Bar",
            "shapeTitleLineDecorationCircle" => "Circle",
            "shapeTitleLineDecorationSquare" => "Square",
            "shapeTitleLineDecorationArrow" => "Arrow",
            "shapeTitleLineDecorationCircleSolid" => "Circle solid",
            "shapeTitleLineDecorationSquareSolid" => "Square solid",
            "shapeTitleLineDecorationArrowSolid" => "Arrow solid",
            "shapeTitleColorTransparent" => "Transparent",
            "shapeTitleColorWhite" => "White",
            "shapeTitleColorSilver" => "Silver",
            "shapeTitleColorGray" => "Gray",
            "shapeTitleColorBlack" => "Black",
            "shapeTitleColorNavy" => "Navy",
            "shapeTitleColorBlue" => "Blue",
            "shapeTitleColorAqua" => "Aqua",
            "shapeTitleColorTeal" => "Teal",
            "shapeTitleColorOlive" => "Olive",
            "shapeTitleColorGreen" => "Green",
            "shapeTitleColorYellow" => "Yellow",
            "shapeTitleColorOrange" => "Orange",
            "shapeTitleColorRed" => "Red",
            "shapeTitleColorMaroon" => "Maroon",
            "shapeTitleColorFuchsia" => "Fuchsia",
            "shapeTitleColorPurple" => "Purple",
            "shapeTitleTextOutline" => "Text outline",
            "shapeTitleTextOutlineWidth" => "Width",
            "shapeTitleTextShadow" => "Text shadow",
            "shapeTitleTextShadowBlur" => "Blur",
            "shapeTitleTextColor" => "Font color",
            "shapeTitleTextAlign" => "Text align",
            "shapeTitleTextAlignLeft" => "Left align text",
            "shapeTitleTextAlignCenter" => "Center align text",
            "shapeTitleTextAlignRight" => "Right align text",
            "shapeLabelToolMove" => "Move",
            "shapeLabelToolView" => "View",
            "shapeLabelToolSharpie" => "Sharpie",
            "shapeLabelToolEraser" => "Eraser",
            "shapeLabelToolPath" => "Path",
            "shapeLabelToolRectangle" => "Rectangle",
            "shapeLabelToolEllipse" => "Ellipse",
            "shapeLabelToolArrow" => "Arrow",
            "shapeLabelToolLine" => "Line",
            "shapeLabelToolText" => "Text",
            "shapeLabelToolPreset" => "Stickers",
            "shapeTitleSelectionMode" => "Selection mode",
            "shapeTitleBrushSize" => "Brush size",
            "shapeLabelSelectionModeNew" => "New",
            "shapeLabelSelectionModeAdd" => "Add",
            "shapeLabelSelectionModeSubtract" => "Remove",
            "shapeLabelToolSelectionBrush" => "Brush",
            "shapeLabelToolSelectionLassoo" => "Lassoo",
            "shapeLabelToolSelectionRectangle" => "Rectangle marquee",
            "shapeLabelToolSelectionEllipse" => "Ellipse marquee",
        ];

        return $labels;
    }

    public function render()
    {
        $settings = $this->settings;
        $parent_settings = Helper::get_nestable_parent_settings($this->element) ? Helper::get_nestable_parent_settings($this->element) : [];

        $id = $this->id ? $this->id : false;

        if (isset($settings['id']) && $settings['id']) {
            $id = $settings['id'];
        }

        $random_id = Helpers::generate_random_id(false);
        $label = isset($settings['label']) ? $settings['label'] : false;

        $show_labels = true;
        if (isset($parent_settings) && !empty($parent_settings) && !isset($parent_settings['showLabels'])) {
            $show_labels = false;
        }

        // Single Show Label
        if (isset($settings['showLabel']) && $settings['showLabel']) {
            $show_labels = true;
        }

        $value = isset($settings['value']) ? bricks_render_dynamic_data($settings['value']) : '';
        $required = isset($settings['required']) ? $settings['required'] : false;

        if (!$id && bricks_is_builder()) {
            return $this->render_element_placeholder(
                [
                    'title' => esc_html__('You have to set an ID for your element.', 'bricksforge'),
                ]
            );
        }

        /**
         * Wrapper
         */
        $this->set_attribute('_root', 'class', 'pro-forms-builder-field');
        $this->set_attribute('_root', 'class', 'form-group');
        $this->set_attribute('_root', 'data-element-id', $this->id);

        if ($id !== $this->id) {
            $this->set_attribute('_root', 'data-custom-id', $id);
        }

        // Post Context
        if (isset($settings['postContext'])) {
            $this->set_attribute('_root', 'data-context', bricks_render_dynamic_data($settings['postContext']));
        }

        // Custom Css Class
        if (isset($settings['cssClass']) && $settings['cssClass']) {
            $this->set_attribute('field', 'class', $settings['cssClass']);
        }

        /**
         * Field
         */
        $this->set_attribute('field', 'type', 'file');
        $this->set_attribute('field', 'id', 'form-field-' . $id);
        $this->set_attribute('field', 'name', 'form-field-' . $id . '[]');
        $this->set_attribute('field', 'data-label', $label);

        if (!isset($settings['fileUploadLimit']) || $settings['fileUploadLimit'] > 1) {
            $this->set_attribute("field", 'multiple');
        }

        if (!empty($settings['fileUploadLimit'])) {
            $this->set_attribute("field", 'data-limit', $settings['fileUploadLimit']);
        }

        if (isset($settings['fileUploadReplace']) && $settings['fileUploadReplace'] == true) {
            $this->set_attribute("field", 'data-replace', 'true');
        }

        if (isset($settings['fileUploadDisableDeletion']) && $settings['fileUploadDisableDeletion'] == true) {
            $this->set_attribute("field", 'data-disable-deletion', 'true');
        }

        if (isset($settings['fileUploadDisableReorder']) && $settings['fileUploadDisableReorder'] == true) {
            $this->set_attribute("field", 'data-disable-reorder', 'true');
        }

        if (isset($settings['fileUploadDisableDrop']) && $settings['fileUploadDisableDrop'] == true) {
            $this->set_attribute("field", 'data-disable-drop', 'true');
        }

        if (isset($settings['fileUploadDisableBrowse']) && $settings['fileUploadDisableBrowse'] == true) {
            $this->set_attribute("field", 'data-disable-browse', 'true');
        }

        if (isset($settings['filePosterMaxHeight'])) {
            $this->set_attribute("field", 'data-file-poster-max-height', $settings['filePosterMaxHeight']);
        }

        if (isset($settings['fileUploadAllowedTypes'])) {
            $types = str_replace('.', '', strtolower($settings['fileUploadAllowedTypes']));
            $types = array_map('trim', explode(',', $types));

            if (in_array('jpg', $types) && !in_array('jpeg', $types)) {
                $types[] = 'jpeg';
            }

            array_walk(
                $types,
                function (&$value) {
                    $value = '.' . $value;
                }
            );

            $this->set_attribute("field", 'accept', implode(',', $types));
        }

        if (!empty($settings['fileUploadSize'])) {
            $this->set_attribute("field", 'data-maxsize', $settings['fileUploadSize']);
        }

        if (!isset($settings['activateImageEditor']) || $settings['activateImageEditor'] !== true) {
            $this->set_attribute("field", 'data-image-editor', 'false');
        }

        if (isset($settings['allowCrop']) && $settings['allowCrop'] == true) {
            $this->set_attribute("field", 'data-allow-crop', 'true');
        }

        if (isset($settings['allowFinetune']) && $settings['allowFinetune'] == true) {
            $this->set_attribute("field", 'data-allow-finetune', 'true');
        }

        if (isset($settings['allowAnnotate']) && $settings['allowAnnotate'] == true) {
            $this->set_attribute("field", 'data-allow-annotate', 'true');
        }

        if (isset($settings['allowFilter']) && $settings['allowFilter'] == true) {
            $this->set_attribute("field", 'data-allow-filter', 'true');
        }

        if (isset($settings['allowFrame']) && $settings['allowFrame'] == true) {
            $this->set_attribute("field", 'data-allow-frame', 'true');
        }

        if (isset($settings['allowRedact']) && $settings['allowRedact'] == true) {
            $this->set_attribute("field", 'data-allow-redact', 'true');
        }

        if (isset($settings['allowResize']) && $settings['allowResize'] == true) {
            $this->set_attribute("field", 'data-allow-resize', 'true');
        }

        if (isset($settings['allowFill']) && $settings['allowFill'] == true) {
            $this->set_attribute("field", 'data-allow-fill', 'true');
        }

        if (isset($settings['allowSticker']) && $settings['allowSticker'] == true) {
            $this->set_attribute("field", 'data-allow-sticker', 'true');
        }

        // Crop Settings
        if (isset($settings['cropAspectRatio']) && $settings['cropAspectRatio']) {
            $this->set_attribute("field", 'data-crop-aspect-ratio', $settings['cropAspectRatio']);
        }

        if (isset($settings['cropShape']) && $settings['cropShape']) {
            $this->set_attribute("field", 'data-crop-shape', $settings['cropShape']);
        }

        // Sticker Settings
        if (isset($settings['stickers']) && $settings['stickers']) {
            $this->set_attribute("field", 'data-stickers', json_encode($settings['stickers']));
        }

        if ($value) {
            $this->set_attribute('field', 'value', $value);
        }

        if ($required) {
            $this->set_attribute('field', 'required', $required);
        }

        // Image Quality
        if (isset($settings['imageQuality']) && $settings['imageQuality']) {
            $this->set_attribute("field", 'data-image-quality', $settings['imageQuality']);
        }

        if (isset($settings['imageTargetSizeWidth']) && $settings['imageTargetSizeWidth']) {
            $this->set_attribute("field", 'data-image-target-size-width', $settings['imageTargetSizeWidth']);
        }

        if (isset($settings['imageTargetSizeHeight']) && $settings['imageTargetSizeHeight']) {
            $this->set_attribute("field", 'data-image-target-size-height', $settings['imageTargetSizeHeight']);
        }

        if (isset($settings['imageTargetSizeFit']) && $settings['imageTargetSizeFit']) {
            $this->set_attribute("field", 'data-image-target-size-fit', $settings['imageTargetSizeFit']);
        }

        // Conditions
        if (isset($settings['hasConditions']) && isset($settings['conditions']) && $settings['conditions']) {
            $this->set_attribute('_root', 'data-brf-conditions', json_encode($settings['conditions']));
        }
        if (isset($settings['conditionsRelation']) && $settings['conditionsRelation']) {
            $this->set_attribute('_root', 'data-brf-conditions-relation', $settings['conditionsRelation']);
        }

        // Theme
        if (isset($settings['theme']) && $settings['theme']) {
            $this->set_attribute('_root', 'data-theme', $settings['theme']);
        }

        // Required Asterisk
        if (isset($parent_settings['requiredAsterisk']) && $parent_settings['requiredAsterisk'] == true && $required) {
            $this->set_attribute("label", 'class', 'required');
        }

        // Locale
        if (isset($settings['labelLocale']) && $settings['labelLocale']) {
            $this->set_attribute('field', 'data-locale', $settings['labelLocale']);
        }

        // Initial Files
        if (isset($settings['initialFilesMethod']) && $settings['initialFilesMethod']) {
            $this->set_attribute('field', 'data-initial-files-method', $settings['initialFilesMethod']);

            if ($settings['initialFilesMethod'] == 'custom') {
                if (isset($settings['initialFiles']) && $settings['initialFiles']) {
                    // We set "Direct Upload to server" to true
                    $settings['fileUploadToServer'] = true;

                    $initial_files_data = $settings['initialFiles'];

                    // We map trough the array. If we find a numeric value, its an attachment ID.
                    if ($initial_files_data) {
                        $initial_files_data = array_map(
                            function ($item) {
                                $item = $item["file"];

                                if ($item && is_numeric($item)) {
                                    $attachment = get_post($item);
                                    if ($attachment) {
                                        return $attachment->guid;
                                    }
                                }
                                return $item;
                            },
                            $initial_files_data
                        );
                    }

                    $this->set_attribute('field', 'data-initial-files', json_encode($initial_files_data));
                }
            } else {
                if (isset($settings['initialFilesField']) && $settings['initialFilesField']) {
                    $initial_files_method = isset($settings['initialFilesMethod']) ? $settings['initialFilesMethod'] : 'custom';
                    $initial_files_post_id = isset($settings['initialFilesPostId']) ? bricks_render_dynamic_data($settings['initialFilesPostId']) : false;
                    $initial_files_field = bricks_render_dynamic_data($settings['initialFilesField']);
                    $initial_files = [];

                    switch ($initial_files_method) {
                        case 'acf':
                            if (function_exists('get_field')) {
                                $initial_files_data = get_field($initial_files_field, $initial_files_post_id);

                                $settings['fileUploadToServer'] = true;

                                if ($initial_files_data) {
                                    // If is associative array, we have a single file field
                                    if (isset($initial_files_data['url'])) {
                                        $initial_files_data = [$initial_files_data['url']];
                                    }

                                    if (
                                        is_array($initial_files_data)
                                    ) {
                                        $initial_files_data = array_map(
                                            function ($item) {
                                                if (isset($item["url"])) {
                                                    return $item["url"];
                                                }

                                                return $item;
                                            },
                                            $initial_files_data
                                        );

                                        // We reset the keys
                                        $initial_files_data = array_values($initial_files_data);
                                    }

                                    $initial_files = $initial_files_data;
                                }
                            }
                            break;
                        case 'metabox':
                            $initial_files_data = rwmb_meta($initial_files_field, [], $initial_files_post_id);

                            $settings['fileUploadToServer'] = true;

                            // We need only the ["full_url"]. We map the associative array to a numeric array.
                            if (
                                $initial_files_data && is_array($initial_files_data)
                            ) {
                                $initial_files_data = array_map(
                                    function ($item) {
                                        if (isset($item["full_url"])) {
                                            return $item["full_url"];
                                        } else if (isset($item["url"])) {
                                            return $item["url"];
                                        }

                                        return $item;
                                    },
                                    $initial_files_data
                                );

                                // We reset the keys
                                $initial_files_data = array_values($initial_files_data);
                            }

                            if ($initial_files_data) {
                                $initial_files = $initial_files_data;
                            }
                            break;
                        case 'jetengine':
                            if (class_exists('Jet_Engine')) {
                                $initial_files_data = get_post_meta($initial_files_post_id, $initial_files_field, true);

                                $settings['fileUploadToServer'] = true;

                                if ($initial_files_data) {
                                    // If is a comma separated string, we convert it to an array
                                    if (is_string($initial_files_data) && strpos($initial_files_data, ',') !== false) {
                                        $initial_files_data = explode(',', $initial_files_data);
                                    }

                                    $initial_files = $initial_files_data;
                                }
                            }
                            break;
                        case 'acpt':
                            if (function_exists('get_acpt_field')) {
                                $acpt_box_name = isset($settings['initialFilesAcptBoxName']) ? $settings['initialFilesAcptBoxName'] : false;

                                $settings['fileUploadToServer'] = true;

                                if (!$acpt_box_name) {
                                    break;
                                }

                                if (is_numeric($initial_files_post_id)) {
                                    $initial_files_post_id = intval($initial_files_post_id);
                                }

                                $initial_files_data = get_post_meta($initial_files_post_id, $acpt_box_name, true);

                                if ($initial_files_data) {
                                    // If is a comma separated string, we convert it to an array
                                    if (is_string($initial_files_data) && strpos($initial_files_data, ',') !== false) {
                                        $initial_files_data = explode(',', $initial_files_data);
                                    }

                                    $initial_files = $initial_files_data;
                                }
                            }
                            break;
                        default:
                            break;
                    }

                    // We map trough the array. If we find a numeric value, its an attachment ID.
                    if ($initial_files && is_array($initial_files)) {
                        $initial_files = array_map(
                            function ($item) {
                                if ($item && is_numeric($item)) {
                                    $attachment = get_post($item);
                                    if ($attachment) {
                                        return $attachment->guid;
                                    }
                                }
                                return $item;
                            },
                            $initial_files
                        );
                    }

                    $this->set_attribute('field', 'data-initial-files', json_encode($initial_files));
                }
            }
        }

        if (isset($settings['fileUploadToServer']) && $settings['fileUploadToServer'] == true) {
            $this->set_attribute("field", 'data-upload-to-server', 'true');
        }

        // Labels
        $label_overwrites = [];
        foreach ($this->default_labels as $l => $value) {
            if (isset($settings['label_' . $l]) && $settings['label_' . $l]) {
                $label_overwrites[$l] = $settings['label_' . $l];
            }
        }

        if (!empty($label_overwrites)) {
            $this->set_attribute('field', 'data-label-overwrites', json_encode($label_overwrites));
        }
?>
        <div <?php echo $this->render_attributes('_root'); ?>>

            <?php if ($label && $show_labels) : ?>
                <label for="form-field-<?php echo $random_id; ?>"><?php echo $label; ?></label>
            <?php endif; ?>

            <?php if (bricks_is_builder() || bricks_is_builder_call()) { ?>
                <div class="filepond--root filepond--hopper" id="form-field-920bf5" data-style-button-remove-item-position="left" data-style-button-process-item-position="right" data-style-load-indicator-position="right" data-style-progress-indicator-position="right" data-style-button-remove-item-align="false" data-style-image-editor-button-edit-item-position="bottom center" style="height: 62px;"><input class="filepond--browser" type="file" id="filepond--browser-4ydlw83st" name="form-field-920bf5[]" aria-controls="filepond--assistant-4ydlw83st" aria-labelledby="filepond--drop-label-4ydlw83st" multiple="">
                    <div class="filepond--drop-label" style="transform: translate3d(0px, 0px, 0px); opacity: 1;"><label for="filepond--browser-4ydlw83st" id="filepond--drop-label-4ydlw83st" aria-hidden="true">Drag &amp; Drop your files or <span class="filepond--label-action" tabindex="0">Browse</span></label></div>
                    <div class="filepond--list-scroller" style="transform: translate3d(0px, 49px, 0px);">
                        <ul class="filepond--list" role="list"></ul>
                    </div>
                    <div class="filepond--panel filepond--panel-root" data-scalable="true">
                        <div class="filepond--panel-top filepond--panel-root"></div>
                        <div class="filepond--panel-center filepond--panel-root" style="transform: translate3d(0px, 7px, 0px) scale3d(1, 0.48, 1);"></div>
                        <div class="filepond--panel-bottom filepond--panel-root" style="transform: translate3d(0px, 55px, 0px);"></div>
                    </div><span class="filepond--assistant" id="filepond--assistant-4ydlw83st" role="status" aria-live="polite" aria-relevant="additions"></span>
                    <fieldset class="filepond--data"></fieldset>
                    <div class="filepond--drip"></div>
                </div> <?php } else { ?>
                <input <?php echo $this->render_attributes('field'); ?>>

                <!-- Hidden Input for the order of the files -->
                <input type="hidden" id="fileOrder" name="fileOrder" value="{}" />
            <?php } ?>

            <script type="module">
                import {
                    // Image editor
                    openEditor,
                    appendDefaultEditor,
                    processImage,
                    createDefaultImageReader,
                    createDefaultImageWriter,
                    createDefaultImageOrienter,
                    createDefaultImageScrambler,

                    // Only needed if loading legacy image editor data
                    legacyDataToImageState,

                    // Import the editor default configuration
                    getEditorDefaults,

                    // The method used to register the plugins
                    setPlugins,

                    // The plugins we want to use
                    plugin_crop,
                    plugin_finetune,
                    plugin_annotate,
                    plugin_decorate,
                    plugin_filter,
                    plugin_frame,
                    plugin_redact,
                    plugin_resize,
                    plugin_fill,
                    plugin_sticker,

                    // The user interface and plugin locale objects
                    locale_en_gb,
                    plugin_crop_locale_en_gb,
                    plugin_finetune_locale_en_gb,
                    plugin_annotate_locale_en_gb,
                    plugin_decorate_locale_en_gb,
                    plugin_filter_locale_en_gb,
                    plugin_frame_locale_en_gb,
                    plugin_redact_locale_en_gb,
                    plugin_resize_locale_en_gb,
                    plugin_fill_locale_en_gb,
                    plugin_sticker_locale_en_gb,

                    // Because we use the annotate plugin we also need
                    // to import the markup editor locale and the shape preprocessor
                    markup_editor_locale_en_gb,
                    createDefaultShapePreprocessor,

                    // Import the default configuration for the markup editor and finetune plugins
                    markup_editor_defaults,
                    plugin_finetune_defaults,
                    plugin_filter_defaults,
                    plugin_frame_defaults,
                } from '/wp-content/plugins/bricksforge/assets/vendor/pintura.js';

                import * as FilePond from '/wp-content/plugins/bricksforge/assets/vendor/filepond.js';
                import FilePondPluginImageEditor from '/wp-content/plugins/bricksforge/assets/vendor/FilePondPluginImageEditor.js';
                import FilePondPluginFilePoster from '/wp-content/plugins/bricksforge/assets/vendor/filepond-plugin-file-poster.js';

                if (!window.bricksforgeData) {
                    window.bricksforgeData = {};
                }

                if (!window.bricksforgeData.processedFiles) {
                    window.bricksforgeData.processedFiles = [];
                }

                window.bricksforgeData.initFileField = (field) => {

                    FilePond.registerPlugin(
                        //FilePondPluginFileValidateType,
                        FilePondPluginFilePoster,
                        FilePondPluginImageEditor,
                    );

                    let localeData = {};

                    if (typeof BricksforgeFileLocale != "undefined") {
                        localeData = BricksforgeFileLocale;
                    }

                    // Values
                    let element = field ? field : document.querySelector('input[type=file]');

                    let form = element.closest("form");
                    let wrapper = element.closest(".brxe-brf-pro-forms-field-file");

                    // We save the original field HTML before mutating it
                    brfInitialFields.push({
                        type: "file",
                        html: element.closest(".brxe-brf-pro-forms-field-file").outerHTML,
                    });

                    let isRequired = element.hasAttribute('required');
                    let isDisabled = element.hasAttribute('disabled');
                    let fileUploadLimit = element.getAttribute('data-limit') ? parseInt(element.getAttribute('data-limit')) : null;
                    let fileUploadReplace = element.getAttribute('data-replace') ? element.getAttribute('data-replace') : false;
                    let fileUploadDisableDeletion = element.getAttribute('data-disable-deletion') ? element.getAttribute('data-disable-deletion') : false;
                    let fileUploadDisableReorder = element.getAttribute('data-disable-reorder') ? element.getAttribute('data-disable-reorder') : false;
                    let fileUploadDisableDrop = element.getAttribute('data-disable-drop') ? element.getAttribute('data-disable-drop') : false;
                    let fileUploadDisableBrowse = element.getAttribute('data-disable-browse') ? element.getAttribute('data-disable-browse') : false;
                    let filePosterMaxHeight = element.getAttribute('data-file-poster-max-height') ? parseInt(element.getAttribute('data-file-poster-max-height')) : 256;
                    let fileUploadToServer = element.getAttribute('data-upload-to-server') ? element.getAttribute('data-upload-to-server') : false;
                    let cropAspectRatio = element.getAttribute('data-crop-aspect-ratio') ? element.getAttribute('data-crop-aspect-ratio') : '16/9';
                    let cropShape = element.getAttribute('data-crop-shape') ? element.getAttribute('data-crop-shape') : 'ellipse';
                    let stickers = element.getAttribute('data-stickers') ? JSON.parse(element.getAttribute('data-stickers')) : [];
                    let imageQuality = element.getAttribute('data-image-quality') ? parseFloat(element.getAttribute('data-image-quality')) : 0.92;
                    let imageTargetSizeWidth = element.getAttribute('data-image-target-size-width') ? parseInt(element.getAttribute('data-image-target-size-width')) : null;
                    let imageTargetSizeHeight = element.getAttribute('data-image-target-size-height') ? parseInt(element.getAttribute('data-image-target-size-height')) : null;
                    let imageTargetSizeFit = element.getAttribute('data-image-target-size-fit') ? element.getAttribute('data-image-target-size-fit') : 'contain';
                    let allowedFileTypes = element.getAttribute('accept') ? element.getAttribute('accept') : null;
                    let maxFileSize = element.getAttribute('data-maxsize') ? parseInt(element.getAttribute('data-maxsize')) : null;

                    let initialFilesMethod = element.getAttribute('data-initial-files-method') ? element.getAttribute('data-initial-files-method') : 'local';
                    let initialFilesData = element.getAttribute('data-initial-files') ? JSON.parse(element.getAttribute('data-initial-files')) : null;
                    let initialFiles = [];

                    if (initialFilesData) {
                        initialFiles = initialFilesData.map((file) => {
                            return {
                                source: file,
                                options: {
                                    type: 'local',
                                },
                            };
                        });

                        if (!window.bricksforgeData.initialFiles) {
                            window.bricksforgeData.initialFiles = [];
                        }

                        window.bricksforgeData.initialFiles.push({
                            fieldId: wrapper.dataset.customId,
                            formId: wrapper.closest('form').dataset.scriptId,
                            files: initialFiles,
                        });
                    }

                    let labelOverwrites = element.getAttribute('data-label-overwrites') ? JSON.parse(element.getAttribute('data-label-overwrites')) : {};

                    if (stickers) {
                        // We map the stickers to the sticker property
                        stickers = stickers.map(s => s.sticker);
                    }

                    // We transform the cropAspectRatio string to a number
                    let cropAspectRatioArray = cropAspectRatio.split('/');
                    let cropAspectRatioNumber = parseFloat(cropAspectRatioArray[0]) / parseFloat(cropAspectRatioArray[1]);
                    cropAspectRatio = cropAspectRatioNumber;

                    let pluginsToLoad = [];

                    if (element.getAttribute("data-allow-crop")) {
                        pluginsToLoad.push(plugin_crop);
                    }

                    if (element.getAttribute("data-allow-finetune")) {
                        pluginsToLoad.push(plugin_finetune);
                    }

                    if (element.getAttribute("data-allow-annotate")) {
                        pluginsToLoad.push(plugin_annotate);
                    }

                    if (element.getAttribute("data-allow-trim")) {
                        pluginsToLoad.push(plugin_trim);
                    }

                    if (element.getAttribute("data-allow-decorate")) {
                        pluginsToLoad.push(plugin_decorate);
                    }

                    if (element.getAttribute("data-allow-filter")) {
                        pluginsToLoad.push(plugin_filter);
                    }

                    if (element.getAttribute("data-allow-frame")) {
                        pluginsToLoad.push(plugin_frame);
                    }

                    if (element.getAttribute("data-allow-redact")) {
                        pluginsToLoad.push(plugin_redact);
                    }

                    if (element.getAttribute("data-allow-resize")) {
                        pluginsToLoad.push(plugin_resize);
                    }

                    if (element.getAttribute("data-allow-fill")) {
                        pluginsToLoad.push(plugin_fill);
                    }

                    if (element.getAttribute("data-allow-sticker")) {
                        pluginsToLoad.push(plugin_sticker);
                    }

                    // This registers the plugins with Pintura Image Editor
                    setPlugins(...pluginsToLoad);

                    let imageEditorData = {
                        // Maps legacy data objects to new imageState objects (optional)
                        legacyDataToImageState: legacyDataToImageState,

                        // Used to create the editor (required)
                        createEditor: openEditor,

                        // Used for reading the image data. See JavaScript installation for details on the `imageReader` property (required)
                        imageReader: [
                            createDefaultImageReader,
                            {
                                // createDefaultImageReader options here
                            },
                        ],

                        // Can leave out when not generating a preview thumbnail and/or output image (required)
                        imageWriter: [
                            createDefaultImageWriter,
                            {
                                quality: imageQuality,
                                // We'll resize images to fit a 512 × 512 square
                                targetSize: {
                                    width: imageTargetSizeWidth,
                                    height: imageTargetSizeHeight,
                                    fit: imageTargetSizeFit,
                                },
                            },
                        ],

                        // Used to generate poster images, runs an invisible "headless" editor instance. (optional)
                        imageProcessor: processImage,

                        // Pintura Image Editor options
                        editorOptions: {
                            // The markup editor default options, tools, shape style controls
                            ...markup_editor_defaults,

                            // The finetune util controls
                            ...plugin_finetune_defaults,

                            ...plugin_filter_defaults,

                            ...plugin_frame_defaults,

                            // This handles complex shapes like arrows / frames
                            shapePreprocessor: createDefaultShapePreprocessor(),

                            // Image Scrambler (optional)
                            imageScrambler: createDefaultImageScrambler({
                                scrambleAmount: 2,
                                blurAmount: 6,
                            }),

                            stickers: stickers,

                            // This will set a square crop aspect ratio
                            imageCropAspectRatio: cropAspectRatio,

                            ...(cropShape != 'circle' ? null : {
                                willRenderCanvas: (shapes, state) => {
                                    const {
                                        utilVisibility,
                                        selectionRect,
                                        lineColor,
                                        backgroundColor,
                                    } = state;

                                    // Exit if crop utils is not visible
                                    if (utilVisibility.crop <= 0) return shapes;

                                    // Get variable shortcuts to the crop selection rect
                                    const {
                                        x,
                                        y,
                                        width,
                                        height
                                    } = selectionRect;

                                    return {
                                        // Copy all props from current shapes
                                        ...shapes,

                                        // Now we add an inverted ellipse shape to the interface shapes array
                                        interfaceShapes: [{
                                                x: x + width * 0.5,
                                                y: y + height * 0.5,
                                                rx: width * 0.5,
                                                ry: height * 0.5,
                                                opacity: utilVisibility.crop,
                                                inverted: true,
                                                backgroundColor: [...backgroundColor, 0.5],
                                                strokeWidth: 1,
                                                strokeColor: [...lineColor],
                                            },
                                            // Spread all existing interface shapes onto the array
                                            ...shapes.interfaceShapes,
                                        ]
                                    }
                                }
                            }),

                            // The icons and labels to use in the user interface (required)
                            locale: {
                                ...locale_en_gb,
                                ...plugin_crop_locale_en_gb,
                                ...plugin_finetune_locale_en_gb,
                                ...plugin_annotate_locale_en_gb,
                                ...markup_editor_locale_en_gb,
                                ...plugin_decorate_locale_en_gb,
                                ...plugin_filter_locale_en_gb,
                                ...plugin_frame_locale_en_gb,
                                ...plugin_redact_locale_en_gb,
                                ...plugin_resize_locale_en_gb,
                                ...plugin_fill_locale_en_gb,
                                ...plugin_sticker_locale_en_gb,

                                // Locale
                                ...localeData,

                                // Label Overwrites
                                ...labelOverwrites,
                            },
                        },
                    };

                    if (typeof FilePondPluginImagePreview != "undefined") {
                        FilePond.registerPlugin(FilePondPluginImagePreview);
                    }

                    let fp = FilePond.create(element, {
                        storeAsFile: true,
                        allowImagePreview: true,
                        imagePreviewMaxHeight: filePosterMaxHeight,
                        required: isRequired,
                        disabled: isDisabled,
                        files: initialFiles,
                        allowDrop: !fileUploadDisableDrop,
                        allowBrowse: !fileUploadDisableBrowse,
                        allowMultiple: fileUploadReplace && fileUploadLimit > 1 ? true : false,
                        maxFiles: fileUploadLimit,
                        maxParallelUploads: 2,
                        allowReplace: fileUploadReplace, // Only works when allowMultiple is false
                        allowRemove: !fileUploadDisableDeletion,
                        allowProcess: true,
                        allowReorder: !fileUploadDisableReorder,
                        checkValidity: false,
                        fileSizeBase: 1000, // 1000 = 1 KB. This is used to calculate the file size in the UI
                        // FilePond generic properties
                        filePosterMaxHeight: filePosterMaxHeight,
                        credits: false,
                        instantUpload: fileUploadToServer,

                        // Locale
                        ...localeData,

                        // Label Overwrites
                        ...labelOverwrites,

                        // FilePond Image Editor plugin properties
                        imageEditor: imageEditorData,
                        ...(!fileUploadToServer ? {} : {
                            server: {
                                process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                                    // fieldName is the name of the input field
                                    // file is the actual file object to send

                                    const formData = new FormData();
                                    formData.append(fieldName, file, file.name);

                                    const request = new XMLHttpRequest();

                                    request.open('POST', BRFELEMENTS.apiurl + 'form_file_upload');
                                    // Add wp_rest nonce as X-WP-Nonce header
                                    request.setRequestHeader('X-WP-Nonce', BRFELEMENTS.nonce);

                                    // We pass the allowed file types to the server
                                    if (allowedFileTypes) {
                                        formData.append('allowed_file_types', allowedFileTypes);
                                    }

                                    // We pass the file size limit to the server
                                    if (maxFileSize) {
                                        formData.append('max_file_size', maxFileSize);
                                    }

                                    // Should call the progress method to update the progress to 100% before calling load
                                    // Setting computable to false switches the loading indicator to infinite mode
                                    request.upload.onprogress = (e) => {
                                        progress(e.lengthComputable, e.loaded, e.total);
                                    };

                                    // Should call the load method when done and pass the returned server file id
                                    // this server file id is then used later on when reverting or restoring a file
                                    // so your server knows which file to return without exposing that info to the client
                                    request.onload = function() {
                                        if (request.status >= 200 && request.status < 300) {
                                            const json = JSON.parse(request.responseText);

                                            if (!json.success || !json.data) {
                                                error('Error uploading file');
                                                return;
                                            }

                                            // Usually "json.data" is an array or object from your code
                                            let uploadedFile = json.data;

                                            // If it’s an array, e.g. [ { url: "...", name: "...", ... } ], pick first
                                            if (Array.isArray(uploadedFile)) {
                                                uploadedFile = uploadedFile[0];
                                            }

                                            // `uploadedFile` might look like { url: "...", name: "...", attachmentId: ... }

                                            // Step 1: Pass final file info as a string:
                                            load(JSON.stringify(uploadedFile));

                                            // Step 2: Optionally store in your custom array
                                            if (!window.bricksforgeData.temporaryFileUploads) {
                                                window.bricksforgeData.temporaryFileUploads = [];
                                            }
                                            window.bricksforgeData.temporaryFileUploads.push({
                                                file: uploadedFile,
                                                field: fieldName,
                                            });

                                        } else {
                                            error('Error uploading file');
                                        }
                                    };

                                    request.send(formData);

                                    // Should expose an abort method so the request can be cancelled
                                    return {
                                        abort: () => {
                                            // This function is entered if the user has tapped the cancel button
                                            request.abort();

                                            // Let FilePond know the request has been cancelled
                                            abort();
                                        },
                                    };
                                },
                                load: (source, load, error, progress, abort, headers) => {
                                    const formData = new FormData();
                                    formData.append('file', source);

                                    const request = new XMLHttpRequest();

                                    request.open('POST', BRFELEMENTS.apiurl + 'form_file_load_initial_files');
                                    request.setRequestHeader('X-WP-Nonce', BRFELEMENTS.nonce);

                                    progress(true, 0, 1024);

                                    request.onload = () => {
                                        if (request.status >= 200 && request.status < 300) {
                                            let response = JSON.parse(request.responseText);

                                            if (!response.success) {
                                                error('Error loading file');
                                                return;
                                            }

                                            let fileData = response.data;
                                            let binary = atob(fileData.content);
                                            let array = [];
                                            for (let i = 0; i < binary.length; i++) {
                                                array.push(binary.charCodeAt(i));
                                            }
                                            let blob = new Blob([new Uint8Array(array)], {
                                                type: fileData.type
                                            });
                                            blob.lastModified = Date.now();
                                            blob.name = fileData.name;

                                            load(blob);
                                        } else {
                                            error('Error loading file');
                                        }
                                    };

                                    request.onerror = () => {
                                        error('Network error');
                                    };

                                    request.send(formData);

                                    return {
                                        abort: () => {
                                            request.abort();
                                            abort();
                                        },
                                    };
                                },
                            }
                        }),
                    });

                    // If a file is removed, we also remove it from the temporary uploads
                    fp.on('removefile', (error, file) => {
                        if (error) {
                            console.log('File removal error', error);
                        }

                        if (window.bricksforgeData.temporaryFileUploads) {
                            window.bricksforgeData.temporaryFileUploads = window.bricksforgeData.temporaryFileUploads.filter((f) => {
                                return f.file.originalFilename != file.filename && f.file.originalFilesize != file.originalFilesize;
                            });
                        }

                        // We remove the file from the initial files as well
                        if (window.bricksforgeData.initialFiles) {
                            let initialFilesData = window.bricksforgeData.initialFiles;
                            let matchingInitialFilesData = initialFilesData.find((d) => d.formId == form.dataset.scriptId);

                            initialFilesData = initialFilesData.map((data) => {
                                if (data.formId == form.dataset.scriptId) {

                                    data.files = data.files.filter((fi) => {
                                        let source = fi.source; // URL

                                        if (source == file.source) {
                                            // We remove this file
                                            return false;
                                        }

                                        return true;
                                    });
                                }

                                return data;
                            });

                            if (initialFilesData) {
                                window.bricksforgeData.initialFiles = initialFilesData;
                            }
                        }
                    });

                    fp.on('processfile', (error, file) => {
                        // Todo
                        return;

                        if (error) return;
                        const data = JSON.parse(file.serverId);
                        file.setMetadata('realUrl', data.url);
                        // That’s it: no extra load() calls, no re-adding the file.
                    });

                    fp.on('reorderfiles', (fileItems) => {
                        // Build array of their sources (URLs, or IDs, whichever you use)
                        const fieldId = element.getAttribute('name');
                        const newOrder = fileItems.map((item) => {
                            const metaUrl = item.getMetadata('realUrl');
                            // If we have a realUrl, use that; if not, fallback to item.source or local blob
                            return metaUrl || item.source;
                        });

                        // Get the existing fileOrder JSON from the hidden input
                        const hidden = document.getElementById('fileOrder');
                        let fileOrderObj = {};
                        try {
                            fileOrderObj = JSON.parse(hidden.value || '{}');
                        } catch (err) {
                            console.warn('Invalid JSON in #fileOrder hidden input, resetting...');
                        }

                        // Update or set the array for this specific field
                        fileOrderObj[fieldId] = newOrder;

                        // Write back to the hidden input
                        hidden.value = JSON.stringify(fileOrderObj);
                    });
                }

                window.bricksforgeData.initFileField();
            </script>

            <?php

            ?>

        </div>
<?php
    }
}
