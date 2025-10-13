<?php

namespace Bricks;

if (!defined('ABSPATH'))
    exit;

class Brf_Three_Js extends Element
{
    public $category = 'bricksforge';
    public $name = 'brf-three-js';
    public $icon = 'ti-layout-slider-alt';
    public $scripts = ['brfThreeJsHandler'];

    public function __construct($element = null)
    {
        parent::__construct($element);

        add_action('add_attachment', [$this, 'process_model_upload'], 10, 1);
        add_action('delete_attachment', [$this, 'delete_model_files'], 10, 1);

        add_filter('upload_mimes', function ($mimes) {
            $mimes['gltf'] = 'model/gltf+json';
            $mimes['glb'] = 'model/gltf-binary';
            return $mimes;
        });
    }

    public function process_model_upload($attachment_id)
    {
        $attachment_file = get_attached_file($attachment_id);

        if (pathinfo($attachment_file, PATHINFO_EXTENSION) == 'zip') {
            $this->unzip_model($attachment_file, $attachment_id);
        }
    }

    public function delete_model_files($attachment_id)
    {
        // Identify the directory to be deleted
        $dir_to_delete = BRICKSFORGE_UPLOADS_DIR . 'models/' . $attachment_id;

        // Call the function to delete the directory
        $this->delete_temp_directory($dir_to_delete);
    }

    public function get_label()
    {
        return esc_html__('3D Model', 'bricksforge');
    }

    public function set_control_groups()
    {
        // Model
        $this->control_groups['model'] = [
            'title' => esc_html__('3D Model', 'bricksforge'),
            'tab'   => 'content',
        ];
        // Canvas
        $this->control_groups['canvas'] = [
            'title' => esc_html__('Canvas', 'bricksforge'),
            'tab'   => 'content',
        ];
        // Camera
        $this->control_groups['camera'] = [
            'title' => esc_html__('Camera', 'bricksforge'),
            'tab'   => 'content',
        ];
        // Light
        $this->control_groups['light'] = [
            'title' => esc_html__('Light', 'bricksforge'),
            'tab'   => 'content',
        ];
        // Interaction
        $this->control_groups['interaction'] = [
            'title' => esc_html__('Interaction', 'bricksforge'),
            'tab'   => 'content',
        ];
        // Animation
        $this->control_groups['animation'] = [
            'title' => esc_html__('Animation', 'bricksforge'),
            'tab'   => 'content',
        ];
        // Renderer
        $this->control_groups['renderer'] = [
            'title' => esc_html__('Renderer', 'bricksforge'),
            'tab'   => 'content',
        ];
    }

    public function set_controls()
    {
        // Model
        $this->controls['model'] = [
            'type'    => 'file',
            'tab'     => 'content',
            'group'   => 'model',
            'label'   => esc_html__('3D Model', 'bricksforge'),
            'description' => esc_html__('Upload a 3D model file. The preferred format is glTF.', 'bricksforge'),
            'default' => '',
        ];

        // Model Scale X
        $this->controls['modelScaleX'] = [
            'type'    => 'number',
            'tab'     => 'content',
            'group'   => 'model',
            'label'   => esc_html__('Scale X', 'bricksforge'),
            'description' => esc_html__('The scale of the model on the X axis.', 'bricksforge'),
            'default' => 2,
        ];

        // Model Scale Y
        $this->controls['modelScaleY'] = [
            'type'    => 'number',
            'tab'     => 'content',
            'group'   => 'model',
            'label'   => esc_html__('Scale Y', 'bricksforge'),
            'description' => esc_html__('The scale of the model on the Y axis.', 'bricksforge'),
            'default' => 2,
        ];

        // Model Scale Z
        $this->controls['modelScaleZ'] = [
            'type'    => 'number',
            'tab'     => 'content',
            'group'   => 'model',
            'label'   => esc_html__(' Scale Z', 'bricksforge'),
            'description' => esc_html__('The scale of the model on the Z axis.', 'bricksforge'),
            'default' => 2,
        ];

        // Model Position X
        $this->controls['modelPositionX'] = [
            'type'    => 'number',
            'tab'     => 'content',
            'group'   => 'model',
            'label'   => esc_html__('Position X', 'bricksforge'),
            'description' => esc_html__('The position of the model on the X axis.', 'bricksforge'),
            'default' => 0,
        ];

        // Model Position Y
        $this->controls['modelPositionY'] = [
            'type'    => 'number',
            'tab'     => 'content',
            'group'   => 'model',
            'label'   => esc_html__('Position Y', 'bricksforge'),
            'description' => esc_html__('The position of the model on the Y axis.', 'bricksforge'),
            'default' => 0,
        ];

        // Model Position Z
        $this->controls['modelPositionZ'] = [
            'type'    => 'number',
            'tab'     => 'content',
            'group'   => 'model',
            'label'   => esc_html__('Position Z', 'bricksforge'),
            'description' => esc_html__('The position of the model on the Z axis.', 'bricksforge'),
            'default' => 0,
        ];

        // Model Rotation X
        $this->controls['modelRotationX'] = [
            'type'    => 'number',
            'tab'     => 'content',
            'group'   => 'model',
            'label'   => esc_html__('Rotation X', 'bricksforge'),
            'description' => esc_html__('The rotation of the model on the X axis.', 'bricksforge'),
            'default' => 0,
        ];

        // Model Rotation Y
        $this->controls['modelRotationY'] = [
            'type'    => 'number',
            'tab'     => 'content',
            'group'   => 'model',
            'label'   => esc_html__('Rotation Y', 'bricksforge'),
            'description' => esc_html__('The rotation of the model on the Y axis.', 'bricksforge'),
            'default' => 0,
        ];

        // Model Rotation Z
        $this->controls['modelRotationZ'] = [
            'type'    => 'number',
            'tab'     => 'content',
            'group'   => 'model',
            'label'   => esc_html__('Rotation Z', 'bricksforge'),
            'description' => esc_html__('The rotation of the model on the Z axis.', 'bricksforge'),
            'default' => 0,
        ];

        // Allow Animation
        $this->controls['allowAnimation'] = [
            'type'    => 'checkbox',
            'tab'     => 'content',
            'group'   => 'model',
            'label'   => esc_html__('Allow Animation', 'bricksforge'),
            'description' => esc_html__('Allow the model to be animated.', 'bricksforge'),
            'default' => true,
        ];

        // Canvas

        // Canvas Width
        $this->controls['canvasWidth'] = [
            'type'    => 'number',
            'tab'     => 'content',
            'group'   => 'canvas',
            'label'   => esc_html__('Canvas Width', 'bricksforge'),
            'description' => esc_html__('The width of the canvas.', 'bricksforge'),
            'default' => '100vw',
            'css' => [
                [
                    'property' => 'width',
                    'selector' => 'canvas',
                    'important' => true,
                ],
                [
                    'property' => 'width',
                    'selector' => '',
                    'important' => true,
                ],
            ],
        ];

        // Canvas Height
        $this->controls['canvasHeight'] = [
            'type'    => 'number',
            'tab'     => 'content',
            'group'   => 'canvas',
            'label'   => esc_html__('Canvas Height', 'bricksforge'),
            'description' => esc_html__('The height of the canvas.', 'bricksforge'),
            'default' => '100vh',
            'css' => [
                [
                    'property' => 'height',
                    'selector' => 'canvas',
                    'important' => true,
                ],
                [
                    'property' => 'height',
                    'selector' => '',
                ],
            ],
        ];

        // Camera

        // Vertical field of view
        $this->controls['cameraFov'] = [
            'type'    => 'number',
            'tab'     => 'content',
            'group'   => 'camera',
            'label'   => esc_html__('Vertical field of view', 'bricksforge'),
            'description' => esc_html__('The vertical field of view of the camera.', 'bricksforge'),
            'default' => 45,
        ];

        // Near clipping plane
        $this->controls['cameraNear'] = [
            'type'    => 'number',
            'tab'     => 'content',
            'group'   => 'camera',
            'label'   => esc_html__('Near clipping plane', 'bricksforge'),
            'description' => esc_html__('The near clipping plane of the camera.', 'bricksforge'),
            'default' => 1,
        ];

        // Far clipping plane
        $this->controls['cameraFar'] = [
            'type'    => 'number',
            'tab'     => 'content',
            'group'   => 'camera',
            'label'   => esc_html__('Far clipping plane', 'bricksforge'),
            'description' => esc_html__('The far clipping plane of the camera.', 'bricksforge'),
            'default' => 10000,
        ];

        // Camera Position X
        $this->controls['cameraPositionX'] = [
            'type'    => 'number',
            'tab'     => 'content',
            'group'   => 'camera',
            'label'   => esc_html__('Position X', 'bricksforge'),
            'description' => esc_html__('The position of the camera on the X axis.', 'bricksforge'),
            'default' => 0,
        ];

        // Camera Position Y
        $this->controls['cameraPositionY'] = [
            'type'    => 'number',
            'tab'     => 'content',
            'group'   => 'camera',
            'label'   => esc_html__('Position Y', 'bricksforge'),
            'description' => esc_html__('The position of the camera on the Y axis.', 'bricksforge'),
            'default' => 0,
        ];

        // Camera Position Z
        $this->controls['cameraPositionZ'] = [
            'type'    => 'number',
            'tab'     => 'content',
            'group'   => 'camera',
            'label'   => esc_html__('Position Z', 'bricksforge'),
            'description' => esc_html__('The position of the camera on the Z axis.', 'bricksforge'),
            'default' => 300,
        ];

        // Light (Repeater)
        $this->controls['light'] = [
            'type'    => 'repeater',
            'tab'     => 'content',
            'group'   => 'light',
            'label'   => esc_html__('Light', 'bricksforge'),
            'titleProperty' => 'type',
            'description' => esc_html__('Add a light to the scene.', 'bricksforge'),
            'default' => [
                [
                    'type' => 'ambient',
                    'color' => '#e0e0e0',
                    'intensity' => 0.2,
                ],
                [
                    'type' => 'directional',
                    'color' => '#e0e0e0',
                    'intensity' => 0.8,
                    'positionX' => 0,
                    'positionY' => 1,
                    'positionZ' => 0,
                ],
                [
                    'type' => 'directional',
                    'color' => '#e0e0e0',
                    'intensity' => 0.3,
                    'positionX' => 0,
                    'positionY' => -1,
                    'positionZ' => 0,
                ]
            ],
            'fields'  => [
                // Light Type
                'type' => [
                    'type'    => 'select',
                    'label'   => esc_html__('Type', 'bricksforge'),
                    'description' => esc_html__('The type of the light.', 'bricksforge'),
                    'default' => 'ambient',
                    'options' => [
                        'ambient' => esc_html__('Ambient', 'bricksforge'),
                        'directional' => esc_html__('Directional', 'bricksforge'),
                        'hemisphere' => esc_html__('Hemisphere', 'bricksforge'),
                        'point' => esc_html__('Point', 'bricksforge'),
                        'rectarea' => esc_html__('RectArea', 'bricksforge'),
                        'spot' => esc_html__('Spot', 'bricksforge'),
                    ],
                ],
                // Light Color
                'color' => [
                    'type'    => 'color',
                    'label'   => esc_html__('Color', 'bricksforge'),
                    'description' => esc_html__('The color of the light.', 'bricksforge'),
                    'default' => '#ffffff',
                ],
                // Ground Color
                'groundColor' => [
                    'type'    => 'color',
                    'label'   => esc_html__('Ground Color', 'bricksforge'),
                    'description' => esc_html__('The ground color of the light.', 'bricksforge'),
                    'default' => '#ffffff',
                    'required' => [
                        ['type', '=', 'hemisphere'],
                    ],
                ],
                // Light Intensity
                'intensity' => [
                    'type'    => 'number',
                    'label'   => esc_html__('Intensity', 'bricksforge'),
                    'description' => esc_html__('The intensity of the light.', 'bricksforge'),
                    'default' => 0.5,
                ],
                // Light Distance
                'distance' => [
                    'type'    => 'number',
                    'label'   => esc_html__('Distance', 'bricksforge'),
                    'description' => esc_html__('The distance of the light.', 'bricksforge'),
                    'default' => 0.5,
                    'required' => [
                        ['type', '=', 'point'],
                        ['type', '=', 'spot'],
                    ],
                ],
                // Light Decay
                'decay' => [
                    'type'    => 'number',
                    'label'   => esc_html__('Decay', 'bricksforge'),
                    'description' => esc_html__('The decay of the light.', 'bricksforge'),
                    'default' => 1,
                    'required' => [
                        ['type', '=', 'point'],
                        ['type', '=', 'spot'],
                    ],
                ],
                // Light Angle
                'angle' => [
                    'type'    => 'number',
                    'label'   => esc_html__('Angle', 'bricksforge'),
                    'description' => esc_html__('The angle of the light.', 'bricksforge'),
                    'default' => 0.5,
                    'required' => [
                        ['type', '=', 'spot'],
                    ],
                ],
                // Light Penumbra
                'penumbra' => [
                    'type'    => 'number',
                    'label'   => esc_html__('Penumbra', 'bricksforge'),
                    'description' => esc_html__('The penumbra of the light.', 'bricksforge'),
                    'default' => 0.3,
                    'required' => [
                        ['type', '=', 'spot'],
                    ],
                ],
                // Light Width
                'width' => [
                    'type'    => 'number',
                    'label'   => esc_html__('Width', 'bricksforge'),
                    'description' => esc_html__('The width of the light.', 'bricksforge'),
                    'default' => 10,
                    'required' => [
                        ['type', '=', 'rectarea'],
                    ],
                ],
                // Light Height
                'height' => [
                    'type'    => 'number',
                    'label'   => esc_html__('Height', 'bricksforge'),
                    'description' => esc_html__('The height of the light.', 'bricksforge'),
                    'default' => 10,
                    'required' => [
                        ['type', '=', 'rectarea'],
                    ],
                ],
                // Light Cast Shadow
                'castShadow' => [
                    'type'    => 'checkbox',
                    'label'   => esc_html__('Cast Shadow', 'bricksforge'),
                    'description' => esc_html__('Whether the light will cast shadows.', 'bricksforge'),
                    'default' => true,
                ],
                // Light Position X
                'positionX' => [
                    'type'    => 'number',
                    'label'   => esc_html__('Position X', 'bricksforge'),
                    'description' => esc_html__('The position of the light on the X axis.', 'bricksforge'),
                    'default' => 100,
                ],
                // Light Position Y
                'positionY' => [
                    'type'    => 'number',
                    'label'   => esc_html__('Position Y', 'bricksforge'),
                    'description' => esc_html__('The position of the light on the Y axis.', 'bricksforge'),
                    'default' => 1000,
                ],
                // Light Position Z
                'positionZ' => [
                    'type'    => 'number',
                    'label'   => esc_html__('Position Z', 'bricksforge'),
                    'description' => esc_html__('The position of the light on the Z axis.', 'bricksforge'),
                    'default' => 100,
                ],

            ],
        ];

        // Interaction

        // Enable orbit controls
        $this->controls['enableInteraction'] = [
            'type'    => 'checkbox',
            'tab'     => 'content',
            'group'   => 'interaction',
            'label'   => esc_html__('Enable Interaction', 'bricksforge'),
            'description' => esc_html__('Enable orbit controls to interact with the model.', 'bricksforge'),
            'default' => true,
        ];

        // Enable zoom
        $this->controls['enableZoom'] = [
            'type'    => 'checkbox',
            'tab'     => 'content',
            'group'   => 'interaction',
            'label'   => esc_html__('Enable zoom', 'bricksforge'),
            'description' => esc_html__('Enable zooming in and out.', 'bricksforge'),
            'default' => true,
            'required' => ['enableInteraction', '=', true]
        ];

        // Enable pan
        $this->controls['enablePan'] = [
            'type'    => 'checkbox',
            'tab'     => 'content',
            'group'   => 'interaction',
            'label'   => esc_html__('Enable pan', 'bricksforge'),
            'description' => esc_html__('Enable panning.', 'bricksforge'),
            'default' => true,
            'required' => ['enableInteraction', '=', true]
        ];

        // Enable rotate
        $this->controls['enableRotate'] = [
            'type'    => 'checkbox',
            'tab'     => 'content',
            'group'   => 'interaction',
            'label'   => esc_html__('Enable rotate', 'bricksforge'),
            'description' => esc_html__('Enable rotating.', 'bricksforge'),
            'default' => true,
            'required' => ['enableInteraction', '=', true]
        ];

        // Min distance
        $this->controls['minDistance'] = [
            'type'    => 'number',
            'tab'     => 'content',
            'group'   => 'interaction',
            'label'   => esc_html__('Min distance', 'bricksforge'),
            'description' => esc_html__('The minimum distance of the camera.', 'bricksforge'),
            'default' => 0,
            'required' => ['enableInteraction', '=', true]
        ];

        // Max distance
        $this->controls['maxDistance'] = [
            'type'    => 'number',
            'tab'     => 'content',
            'group'   => 'interaction',
            'label'   => esc_html__('Max distance', 'bricksforge'),
            'description' => esc_html__('The maximum distance of the camera.', 'bricksforge'),
            'default' => 10000,
            'required' => ['enableInteraction', '=', true]
        ];

        // Prevent Bottom View
        $this->controls['preventBottomView'] = [
            'type'    => 'checkbox',
            'tab'     => 'content',
            'group'   => 'interaction',
            'label'   => esc_html__('Prevent bottom view', 'bricksforge'),
            'description' => esc_html__('Prevent the camera from going below the model.', 'bricksforge'),
            'default' => false,
            'required' => ['enableInteraction', '=', true]
        ];

        // Prevent Top View
        $this->controls['preventTopView'] = [
            'type'    => 'checkbox',
            'tab'     => 'content',
            'group'   => 'interaction',
            'label'   => esc_html__('Prevent top view', 'bricksforge'),
            'description' => esc_html__('Prevent the camera from going above the model.', 'bricksforge'),
            'default' => false,
            'required' => ['enableInteraction', '=', true]
        ];

        // Rotate Speed
        $this->controls['rotateSpeed'] = [
            'type'    => 'number',
            'tab'     => 'content',
            'group'   => 'interaction',
            'label'   => esc_html__('Rotate speed', 'bricksforge'),
            'description' => esc_html__('The speed of the rotation.', 'bricksforge'),
            'default' => 0.5,
            'required' => ['enableInteraction', '=', true]
        ];

        // Zoom Speed
        $this->controls['zoomSpeed'] = [
            'type'    => 'number',
            'tab'     => 'content',
            'group'   => 'interaction',
            'label'   => esc_html__('Zoom speed', 'bricksforge'),
            'description' => esc_html__('The speed of the zoom.', 'bricksforge'),
            'default' => 0.5,
            'required' => ['enableInteraction', '=', true]
        ];

        // Pan Speed
        $this->controls['panSpeed'] = [
            'type'    => 'number',
            'tab'     => 'content',
            'group'   => 'interaction',
            'label'   => esc_html__('Pan speed', 'bricksforge'),
            'description' => esc_html__('The speed of the pan.', 'bricksforge'),
            'default' => 0.5,
            'required' => ['enableInteraction', '=', true]
        ];

        // Animation

        // Enable Animation
        $this->controls['enableAnimation'] = [
            'type'    => 'checkbox',
            'tab'     => 'content',
            'group'   => 'animation',
            'label'   => esc_html__('Enable Animation', 'bricksforge'),
            'description' => esc_html__('Enable animation.', 'bricksforge'),
            'default' => false,
        ];

        // Animation Type
        $this->controls['animationType'] = [
            'type'    => 'select',
            'tab'     => 'content',
            'group'   => 'animation',
            'label'   => esc_html__('Animation Type', 'bricksforge'),
            'description' => esc_html__('The type of animation.', 'bricksforge'),
            'default' => 'rotateLeft',
            'options' => [
                'rotateLeft' => esc_html__('Rotate Left', 'bricksforge'),
                'rotateRight' => esc_html__('Rotate Right', 'bricksforge'),
                'rotateUp' => esc_html__('Rotate Up', 'bricksforge'),
                'rotateDown' => esc_html__('Rotate Down', 'bricksforge'),
            ],
            'required' => ['enableAnimation', '=', true]
        ];

        // Animation Speed
        $this->controls['animationSpeed'] = [
            'type'    => 'number',
            'tab'     => 'content',
            'group'   => 'animation',
            'label'   => esc_html__('Animation Speed', 'bricksforge'),
            'description' => esc_html__('The speed of the animation.', 'bricksforge'),
            'default' => 0.1,
            'required' => ['enableAnimation', '=', true]
        ];

        // Renderer

        // Anti Aliasing
        $this->controls['rendererAntialiasing'] = [
            'type'    => 'checkbox',
            'tab'     => 'content',
            'group'   => 'renderer',
            'label'   => esc_html__('Anti Aliasing', 'bricksforge'),
            'description' => esc_html__('Enable anti aliasing.', 'bricksforge'),
            'default' => true,
        ];
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('bricksforge-elements');

        // Enqueue Three.js (bricksforge-three-js)
        wp_enqueue_script('bricksforge-three-js');
        wp_enqueue_script('bricksforge-gltf-loader');
        wp_enqueue_script('bricksforge-orbit-controls');
    }

    public function unzip_model($zip_file, $attachment_id)
    {
        // Split on /uploads/ to get the relative path
        $relative_path = explode('/uploads/', $zip_file)[1];
        $local_file = wp_upload_dir()['basedir'] . '/' . $relative_path;

        // Create a unique identifier for the subdirectory
        $subdir = $attachment_id;

        // If models not exists, create it
        if (!file_exists(BRICKSFORGE_UPLOADS_DIR . 'models')) {
            mkdir(BRICKSFORGE_UPLOADS_DIR . 'models', 0755, true);
        }

        // Define the temp directory
        $temp_dir = BRICKSFORGE_UPLOADS_DIR . 'models/' . $subdir;

        // Remove the directory or file if it exists
        if (file_exists($temp_dir)) {
            if (is_dir($temp_dir)) {
                // Remove the directory and its contents
                $success = $this->delete_temp_directory($temp_dir);
            } else {
                // Remove the file
                $success = unlink($temp_dir);
            }

            if (!$success) {
                error_log('Failed to remove existing directory or file');
                return;
            }
        }

        // Create the element root dir
        if (!mkdir($temp_dir, 0755, true) && !is_dir($temp_dir)) {
            error_log('Failed to create temp directory');
            return;
        }

        // Open the zip
        $zip = new \ZipArchive;
        $res = $zip->open($local_file);
        if ($res !== true) {
            error_log('Failed to open zip file');
            return;
        }

        // Check for a gltf file in the zip
        $contains_gltf = false;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            if (strpos($zip->getNameIndex($i), '.gltf') !== false || strpos($zip->getNameIndex($i), '.glb') !== false) {
                $contains_gltf = true;
                break;
            }
        }

        if (!$contains_gltf) {
            error_log('Zip file does not contain a .gltf or .glb file');
            $zip->close();
            return;
        }

        // Extract the zip
        $zip->extractTo($temp_dir);
        $zip->close();

        // Get the gltf file from the tmp folder
        $files = scandir($temp_dir);
        foreach ($files as $file) {
            if (strpos($file, '.gltf') !== false) {
                // Return as URL
                return wp_upload_dir()['baseurl'] . '/bricksforge/models/' . $subdir . '/' . $file;
            }

            if (strpos($file, '.glb') !== false) {
                // Return as URL
                return wp_upload_dir()['baseurl'] . '/bricksforge/models/' . $subdir . '/' . $file;
            }
        }

        // Clean up the temp directory if no gltf file was found
        $this->delete_temp_directory($temp_dir);

        return null;
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

        return rmdir($dir);
    }


    public function render()
    {
        $settings = $this->settings;

        $root_classes[] = 'brf-three-js';
        $scene_classes[] = 'brf-three-js-scene';

        $model;

        if (isset($settings['model'])) {
            // If is a zip file, pass the ID. Otherwise, we already have the single file. We pass the URL then.
            if (strpos($settings['model']['url'], '.zip') !== false) {
                $model_dir = BRICKSFORGE_UPLOADS_DIR . 'models/' . $settings['model']['id'];

                $model = $settings['model'];

                // Check for the gltf filename
                $gltf_files = glob($model_dir . '/*.gltf');

                // If no gltf file was found, check for glb
                if (empty($gltf_files)) {
                    $gltf_files = glob($model_dir . '/*.glb');
                }

                if (!empty($gltf_files)) {

                    // Save the file name in $model['filename']
                    $model['filename'] = basename($gltf_files[0]);
                }
            } else {
                $model = $settings['model']['url'];
            }
        } else {
            return $this->render_element_placeholder(
                [
                    'title' => esc_html__('No 3D Model added', 'bricks'),
                ]
            );
        }

        $data = [
            'id' => $this->id,

            // Model
            'model' => $model,
            'modelScaleX' => isset($settings['modelScaleX']) ? $settings['modelScaleX'] : 2,
            'modelScaleY' => isset($settings['modelScaleY']) ? $settings['modelScaleY'] : 2,
            'modelScaleZ' => isset($settings['modelScaleZ']) ? $settings['modelScaleZ'] : 2,
            'modelPositionX' => isset($settings['modelPositionX']) ? $settings['modelPositionX'] : 0,
            'modelPositionY' => isset($settings['modelPositionY']) ? $settings['modelPositionY'] : 0,
            'modelPositionZ' => isset($settings['modelPositionZ']) ? $settings['modelPositionZ'] : 0,
            'modelRotationX' => isset($settings['modelRotationX']) ? $settings['modelRotationX'] : 0,
            'modelRotationY' => isset($settings['modelRotationY']) ? $settings['modelRotationY'] : 0,
            'modelRotationZ' => isset($settings['modelRotationZ']) ? $settings['modelRotationZ'] : 0,
            'allowAnimation' => isset($settings['allowAnimation']) && $settings['allowAnimation'] === true ? true : false,

            // Camera
            'cameraFov' => isset($settings['cameraFov']) ? $settings['cameraFov'] : 45,
            'cameraNear' => isset($settings['cameraNear']) ? $settings['cameraNear'] : 1,
            'cameraFar' => isset($settings['cameraFar']) ? $settings['cameraFar'] : 10000,
            'cameraPositionX' => isset($settings['cameraPositionX']) ? $settings['cameraPositionX'] : 0,
            'cameraPositionY' => isset($settings['cameraPositionY']) ? $settings['cameraPositionY'] : 0,
            'cameraPositionZ' => isset($settings['cameraPositionZ']) ? $settings['cameraPositionZ'] : 300,

            // Light
            'light' => isset($settings['light']) ? $settings['light'] : [],

            // Interaction
            'enableInteraction' => isset($settings['enableInteraction']) ? $settings['enableInteraction'] : false,
            'minDistance' => isset($settings['minDistance']) ? $settings['minDistance'] : 0,
            'maxDistance' => isset($settings['maxDistance']) ? $settings['maxDistance'] : 10000,
            'enableRotate' => isset($settings['enableRotate']) ? $settings['enableRotate'] : false,
            'enablePan' => isset($settings['enablePan']) ? $settings['enablePan'] : false,
            'enableZoom' => isset($settings['enableZoom']) ? $settings['enableZoom'] : false,
            'preventBottomView' => isset($settings['preventBottomView']) ? $settings['preventBottomView'] : false,
            'preventTopView' => isset($settings['preventTopView']) ? $settings['preventTopView'] : false,
            'zoomSpeed' => isset($settings['zoomSpeed']) ? $settings['zoomSpeed'] : 0.5,
            'panSpeed' => isset($settings['panSpeed']) ? $settings['panSpeed'] : 0.5,
            'rotateSpeed' => isset($settings['rotateSpeed']) ? $settings['rotateSpeed'] : 0.5,

            // Animation
            'enableAnimation' => isset($settings['enableAnimation']) && $settings['enableAnimation'] === true ? true : false,
            'animationSpeed' => isset($settings['animationSpeed']) ? $settings['animationSpeed'] : 0.1,
            'animationType' => isset($settings['animationType']) ? $settings['animationType'] : 'rotateLeft',

            // Renderer
            'rendererAntialiasing' => isset($settings['rendererAntialiasing']) && $settings['rendererAntialiasing'] === true ? true : false,
        ];


        $this->set_attribute('_root', 'class', $root_classes);
        $this->set_attribute('_root', 'data-data', json_encode($data));
        $this->set_attribute('_scene', 'class', $scene_classes);

        echo "<div {$this->render_attributes('_root')}>";
        echo "<div {$this->render_attributes('_scene')}></div>";
        echo '</div>';
    }
}
