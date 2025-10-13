<?php
/**
 * Facets
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return [
	'checkbox'     => include_once WPGB_PATH . '/includes/registries/facets/checkbox.php',
	'radio'        => include_once WPGB_PATH . '/includes/registries/facets/radio.php',
	'select'       => include_once WPGB_PATH . '/includes/registries/facets/select.php',
	'button'       => include_once WPGB_PATH . '/includes/registries/facets/button.php',
	'hierarchy'    => include_once WPGB_PATH . '/includes/registries/facets/hierarchy.php',
	'number'       => include_once WPGB_PATH . '/includes/registries/facets/number.php',
	'range'        => include_once WPGB_PATH . '/includes/registries/facets/range.php',
	'date'         => include_once WPGB_PATH . '/includes/registries/facets/date.php',
	'rating'       => include_once WPGB_PATH . '/includes/registries/facets/rating.php',
	'color'        => include_once WPGB_PATH . '/includes/registries/facets/color.php',
	'az_index'     => include_once WPGB_PATH . '/includes/registries/facets/az-index.php',
	'search'       => include_once WPGB_PATH . '/includes/registries/facets/search.php',
	'autocomplete' => include_once WPGB_PATH . '/includes/registries/facets/autocomplete.php',
	'selection'    => include_once WPGB_PATH . '/includes/registries/facets/selection.php',
	'pagination'   => include_once WPGB_PATH . '/includes/registries/facets/pagination.php',
	'load_more'    => include_once WPGB_PATH . '/includes/registries/facets/load-more.php',
	'per_page'     => include_once WPGB_PATH . '/includes/registries/facets/per-page.php',
	'result_count' => include_once WPGB_PATH . '/includes/registries/facets/result-count.php',
	'sort'         => include_once WPGB_PATH . '/includes/registries/facets/sort.php',
	'reset'        => include_once WPGB_PATH . '/includes/registries/facets/reset.php',
	'apply'        => include_once WPGB_PATH . '/includes/registries/facets/apply.php',
];
