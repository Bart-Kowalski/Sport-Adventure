<?php
/**
 * Builder Context Service.
 *
 * @package Automatic_CSS\Services
 */

namespace Automatic_CSS\Services;

use Automatic_CSS\Framework\Platforms\Breakdance;
use Automatic_CSS\Framework\Platforms\Bricks;
use Automatic_CSS\Framework\Platforms\Etch;
use Automatic_CSS\Framework\Platforms\Oxygen;

/**
 * Builder Context Service.
 */
class BuilderContext {
	/**
	 * Context info.
	 *
	 * @var array
	 * @noinspection PhpMissingFieldTypeInspection
	 */
	private $context_info;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->context_info = $this->get_context_info();
	}

	/**
	 * Get context info for all builders.
	 *
	 * @return array
	 */
	private function get_context_info() : array {
		$builders = array(
			// NOTE: The order of the builders is important.
			'etch' => Etch::class,
			'oxygen' => Oxygen::class,
			'breakdance' => Breakdance::class,
			'bricks' => Bricks::class,
			// No Gutenberg::class because Gutenberg isn't a builder and is handled differently.
		);

		$context_info = array();
		/* @noinspection PhpUnusedLocalVariableInspection */
		$page_builder_found = false; // If a page builder is found, we don't need to load Gutenberg.

		// STEP: Fill out the context info for each builder.
		foreach ( $builders as $builder_prefix => $builder_class ) {
			/* @var Builder $builder_class */
			$is_active = $builder_class::is_active();
			$is_builder = $builder_class::is_builder_context();
			$is_frontend = $builder_class::is_frontend_context();
			$is_preview = $builder_class::is_preview_context();
			$version = $builder_class::get_version();
			$page_builder_found = $is_active && ( $is_builder || $is_frontend || $is_preview );
			$context_info[ $builder_prefix ] = array(
				'is_active' => $is_active,
				'is_builder' => $is_builder,
				'is_frontend' => $is_frontend,
				'is_preview' => $is_preview,
				'version' => $version,
			);
		}

		// STEP: Fill out the context info for Gutenberg.
		$context_info['gutenberg'] = array(
			'is_active' => ! $page_builder_found,
			'is_builder' => is_admin(),
			'is_frontend' => ! is_admin(),
			'is_preview' => false,
			'version' => '',
		);

		// STEP: Return the context info.
		return $context_info;
	}

	/**
	 * Check if a specific builder is active.
	 *
	 * @param string $builder_name Builder name.
	 * @return bool
	 */
	public function is_builder_active( string $builder_name ) : bool {
		return isset( $this->context_info[ $builder_name ] ) &&
			   $this->context_info[ $builder_name ]['is_active'];
	}

	/**
	 * Get all context info.
	 *
	 * @return array
	 */
	public function get_all_context() : array {
		return $this->context_info;
	}
}
