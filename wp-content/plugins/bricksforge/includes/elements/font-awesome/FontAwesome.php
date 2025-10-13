<?php

namespace Bricks;

if (!defined('ABSPATH'))
	exit;

class Brf_Font_Awesome extends Element
{
	public $category = 'bricksforge';
	public $name = 'brf-font-awesome';
	public $icon = 'ti-star';

	public $brf_settings = [];

	public function get_label()
	{
		return esc_html__('Font Awesome', 'bricksforge');
	}

	public function set_controls()
	{
		$element = get_option('brf_activated_elements');
		$settings = array_column($element, null, 'id')[1] ?? false;
		$settings = $settings->settings;

		$is_locally = isset($settings->locally) && $settings->locally;

		if (!isset($settings) || (!isset($settings->kitID) || empty($settings->kitID)) && !$is_locally) {
			$this->controls['info'] = [
				'tab'     => 'content',
				'content' => esc_html__('You must enter a valid Kit ID to use the Font Awesome Pro Library. You can find the setting in the Bricksforge options.', 'bricksforge'),
				'type'    => 'info',
			];
			return;
		}

		$this->controls['style'] = [
			'tab'     => 'content',
			'label'   => esc_html__('Icon Style', 'bricksforge'),
			'type'    => 'select',
			'options' => [
				"fa-duotone" => "Duotone",
				"fa-light"   => "Light",
				"fa-regular" => "Regular",
				"fa-solid"   => "Solid",
				"fa-thin"    => "Thin"
			],
			'default' => 'fa-duotone'
		];
		$this->controls['icon'] = [
			'tab'         => 'content',
			'label'       => esc_html__('Icon Class', 'bricksforge'),
			'type'        => 'text',
			'placeholder' => 'fa-layer-group',
			'default'     => 'fa-layer-group'
		];

		$this->controls['spin'] = [
			'tab'     => 'content',
			'label'   => esc_html__('Spin', 'bricksforge'),
			'type'    => 'checkbox',
			'default' => false
		];

		$this->controls['sharp'] = [
			'required' => [['style', '=', 'fa-solid']],
			'tab'      => 'content',
			'label'    => esc_html__('Sharp', 'bricksforge'),
			'type'     => 'checkbox',
			'default'  => false
		];


		$this->controls['iconColor'] = [
			'tab'      => 'content',
			'label'    => esc_html__('Color', 'bricksforge'),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'color',
				],
				[
					'property' => 'fill',
				],
			],
			'required' => ['icon', '!=', ''],
			'default'  => ['hex' => '#ffc107']
		];

		// If duotone, we need a second color
		$this->controls['iconColor2'] = [
			'tab'      => 'content',
			'label'    => esc_html__('Color 2', 'bricksforge'),
			'type'     => 'color',
			'css'      => [
				[
					'selector' => '.fa-secondary',
					'property' => 'color',
				],
				[
					'selector' => '.fa-secondary',
					'property' => 'opacity',
					'value' => "1"
				],
			],
			'required' => ['style', '=', 'fa-duotone'],
		];

		$this->controls['iconSize'] = [
			'tab'      => 'content',
			'label'    => esc_html__('Size', 'bricksforge'),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'font-size',
				],
			],
			'required' => ['icon', '!=', ''],
			'default'  => 35
		];

		$this->controls['link'] = [
			'tab'   => 'content',
			'label' => esc_html__('Link', 'bricksforge'),
			'type'  => 'link',
		];
	}

	public function get_icons()
	{
		$json = file_get_contents(__DIR__ . '/inc/icon-list.json');
		$icons = json_decode($json);

		$list = [];
		foreach ($icons as $icon) {
			$list[$icon] = "$icon";
		}

		return $list;
	}

	public function get_token()
	{

		$settings = $this->brf_settings;
		$token = isset($settings->kitID) && !empty($settings->kitID) ? $settings->kitID : false;

		$utils = new \Bricksforge\Api\Utils();
		$decrypted_token = $utils->decrypt($token);

		return $decrypted_token === false ? false : $decrypted_token;
	}

	public function is_locally()
	{
		$element = get_option('brf_activated_elements');
		$settings = array_column($element, null, 'id')[1] ?? false;

		$this->brf_settings = $settings->settings;

		if (isset($this->brf_settings->locally) && $this->brf_settings->locally) {
			return true;
		}

		return false;
	}

	public function enqueue_scripts()
	{

		if ($this->is_locally() === true) {
			// Enqueue local scripts
			foreach ($this->brf_settings->jsFiles as $file) {
				if (isset($file->url) && !empty($file->url)) {
					wp_enqueue_script('bricksforge-font-awesome-6-' . $file->id, $file->url, false, time(), true);
					add_filter(
						'script_loader_tag',
						function ($tag, $handle, $source) use ($file) {
							if ($handle != 'bricksforge-font-awesome-6-' . $file->id) {
								return $tag;
							}

							$tag = '<script defer src="' . $source . '"></script>';
							return $tag;
						},
						10,
						3
					);
				}
			}
		} else {

			if ($this->get_token() === false) {
				return;
			}

			wp_enqueue_script('bricksforge-font-awesome-6', "https://kit.fontawesome.com/" . $this->get_token() . "}.js", false, time(), true);
			add_filter(
				'script_loader_tag',
				function ($tag, $handle, $source) {
					if ($handle != 'bricksforge-font-awesome-6') {
						return $tag;
					}

					$tag = '<script type="text/javascript" src="' . $source . '" crossorigin="anonymous"></script>';
					return $tag;
				},
				10,
				3
			);
		}
	}

	public function render()
	{
		$settings = $this->settings;
		$style = !empty($settings['style']) ? $settings['style'] : 'fa-solid';
		$icon = !empty($settings['icon']) ? $settings['icon'] : false;
		$link = !empty($settings['link']) ? $settings['link'] : false;
		$spin = !empty($settings['spin']) && $settings['spin'] === true ? 'fa-spin ' : '';
		$sharp = !empty($settings['sharp']) && $settings['sharp'] === true ? 'fa-sharp ' : '';

		if (!$icon) {
			return $this->render_element_placeholder(
				[
					'title' => esc_html__('No icon selected.', 'bricks'),
				]
			);
		}

		$icon = ["icon" => $style . ' ' . $icon . ' ' . $spin . ' ' . $sharp];
		$icon = self::render_icon($icon, $this->attributes['_root']);

		if ($link) {
			$this->set_link_attributes('link', $link);
			echo "<a {$this->render_attributes('link')}>{$icon}</a>";
		} else {
			echo $icon;
		}
	}
}
