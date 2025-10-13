<?php

namespace Bricksforge\Api;

if (!defined('ABSPATH')) {
  exit;
}

class Helper
{

  /**
   * Render CSS files for the global classes
   */
  public function render_css_files($categories)
  {
    clearstatcache();

    if (!file_exists(BRICKSFORGE_CUSTOM_STYLES_FILE) || !is_readable(BRICKSFORGE_CUSTOM_STYLES_FILE)) {
      return false;
    }

    if (!$categories || empty($categories)) {
      return false;
    }

    file_put_contents(BRICKSFORGE_CUSTOM_STYLES_FILE, ' ');

    $css_content = file_get_contents(BRICKSFORGE_CUSTOM_STYLES_FILE);

    $pattern = '/(?:[\.]{1})([a-zA-Z_]+[\w_]*)(?:[\s\.\,\{\>#\:]{0})/im';

    foreach ($categories as $category) {
      $prefix = $category->prefix;
      $category->code = preg_replace($pattern, '.' . $prefix . '-${1}', $category->code);
      $css_content .= PHP_EOL . $category->code;
    }

    $result = file_put_contents(BRICKSFORGE_CUSTOM_STYLES_FILE, $css_content);

    return $result;
  }
}
