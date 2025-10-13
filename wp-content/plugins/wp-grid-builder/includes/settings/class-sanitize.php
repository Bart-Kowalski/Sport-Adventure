<?php
/**
 * Sanitize
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes\Settings;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Validate and sanitize control values
 *
 * @class WP_Grid_Builder\Includes\Settings\Sanitize
 * @since 2.0.0
 */
trait Sanitize {

	/**
	 * Sanitize boolean
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $value Value to sanitize.
	 * @return boolean
	 */
	final public function sanitize_boolean( $value ) {

		return ! empty( $value ) ? true : false;

	}

	/**
	 * Sanitize choices
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $value   Value to sanitize.
	 * @param  array $control Holds control settings.
	 * @return string/array
	 */
	final public function sanitize_choices( $value, $control = [] ) {

		$choices = [];

		if ( ! empty( $control['options'] ) ) {

			$options = array_column( $control['options'], 'options' );
			$choices = array_column( array_merge( $control['options'], ...$options ), 'value' );

		}

		if ( is_array( $value ) ) {

			$value = array_values( $value );
			$value = array_map( [ $this, 'sanitize_choice' ], $value, $choices );
			$value = array_unique( $value );
			$value = array_filter(
				$value,
				function( $val ) {
					return '' !== $val;
				}
			);
		} else {
			$value = $this->sanitize_choice( $value, $choices );
		}

		return $value;

	}

	/**
	 * Sanitize choice
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $value   Value to sanitize.
	 * @param  array $choices Holds choices to check against.
	 * @return string
	 */
	final public function sanitize_choice( $value, $choices = [] ) {

		if ( ! empty( $choices ) && in_array( $value, (array) $choices, true ) ) {
			return $value;
		}

		// Because of async and the dynamic creation of choice in select component.
		return $this->sanitize_text( $value );

	}

	/**
	 * Sanitize code
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $value   Value to sanitize.
	 * @param  array $control Holds control settings.
	 * @return string
	 */
	final public function sanitize_code( $value, $control = [] ) {

		if ( ! is_scalar( $value ) ) {
			return '';
		}

		$mode = isset( $control['mode'] ) ? $control['mode'] : '';

		switch ( strtolower( $mode ) ) {
			case 'css':
				$value = $this->sanitize_css( $value );
				break;
			case 'javascript':
				// The $value variable is explicitly not sanitized, as JavaScript is allowed.
				// and other HTML elements could be constructed piece by piece even if filtered.
				$value = esc_html( $value );
				break;
			default:
				$tags = [];
				// We replace dynamic tags to preserve them while sanitizing content.
				$value = preg_replace_callback(
					'/({{[\p{L}\w\s.\/:@|,\'()-]+}})/ui',
					function( $matches ) use ( &$tags ) {

						$tags[] = $matches[0];

						return 'wpgb_tag_' . ( count( $tags ) - 1 );

					},
					$value
				);

				$value = wp_kses_post( $value );

				// We restore dynamic tags after sanitizing content.
				$value = preg_replace_callback(
					'/wpgb_tag_(\d+)/',
					function( $matches ) use ( $tags ) {
						return $tags[ (int) $matches[1] ] ?? $matches[0];
					},
					$value
				);

				break;
		}

		return $value;

	}

	/**
	 * Sanitize color
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $value   Value to sanitize.
	 * @param  array $control Holds control settings.
	 * @return string
	 */
	final public function sanitize_color( $value, $control = [] ) {

		if ( ! is_scalar( $value ) || '' === $value ) {
			return '';
		}

		$hex_color = sanitize_hex_color( $value );

		if ( $hex_color ) {
			return $hex_color;
		}

		$css_variable = $this->sanitize_css_variable( $value );

		if ( $css_variable ) {
			return $value;
		}

		$keyword_color = $this->sanitize_css_keyword_color( $value );

		if ( $keyword_color ) {
			return $keyword_color;
		}

		$functional_color = $this->sanitize_css_functional_color( $value );

		if ( $functional_color ) {
			return $functional_color;
		}

		if ( empty( $control['gradient'] ) ) {
			return '';
		}

		$gradient_color = $this->sanitize_css_gradient_color( $value );

		if ( $gradient_color ) {
			return $gradient_color;
		}

		return '';

	}

	/**
	 * Sanitize CSS
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $value Value to sanitize.
	 * @return string
	 */
	final public function sanitize_css( $value ) {

		if ( empty( $value ) ) {
			return $value;
		}

		$value = preg_replace( '/\\\\([0-9a-fA-F]{4})/', '\\\\\\\\$1', $value );
		// Prevent content: '\1234' from turning into '\\1234'.
		$value = str_replace( [ '\'\\\\', '"\\\\' ], [ '\'\\', '"\\' ], $value );
		// Some people put weird stuff in their CSS, KSES tends to be greedy.
		$value = str_replace( '<=', '&lt;=', $value );
		// KSES to strip tags.
		$value = wp_kses_split( $value, [], [] );
		// Kses replaces lone '>' with &gt;.
		$value = str_replace( '&gt;', '>', $value );
		// Because '>' was added previously.
		$value = wp_strip_all_tags( $value );
		// Removes executable JS for older browsers.
		$value = str_replace( 'javascript:', '', $value );
		// Prevent using @import at-rule.
		$value = preg_replace( '/@import[ ]*[\'\"]{0,}(url\()*[\'\"]*([^;\'\"\)]*)[\'\"\)](?:[ ]*;)?/', '', $value );
		// Prevent using @charset at-rule.
		$value = preg_replace( '/@charset[ ]*[\'"][^\'"]+[\'"](?:[ ]*;)?/', '', $value );
		// Validate URLs used in CSS properties.
		$value = preg_replace_callback(
			'/url\(\s*[\'"]?([^\)\'"]+?)[\'"]?\s*\)/i',
			function( $matches ) {

				$url = wp_kses_bad_protocol( $matches[1], [ 'http', 'https' ] );

				if ( empty( $url ) || strtolower( $url ) !== strtolower( $matches[1] ) ) {
					return '';
				}

				return "url('" . addcslashes( trim( $url ), '\'\\' ) . "')";

			},
			$value
		);

		return $value;

	}

	/**
	 * Sanitize CSS color func
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $value Value to sanitize.
	 * @return string
	 */
	final public function sanitize_css_functional_color( $value ) {

		$is_functional_color = ! preg_replace( '/^\s*\b(?:rgb|rgba|hsl|hsla|hwb|lab|lch|oklab|oklch|color|color-mix)(\((?:[^()](?![\\\&={}\/\'":;])|(?1))*\))\s*/', '', $value );

		if ( ! $is_functional_color ) {
			return '';
		}

		return sanitize_text_field( $value );

	}

	/**
	 * Sanitize CSS gradient
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $value Value to sanitize.
	 * @return string
	 */
	final public function sanitize_css_gradient_color( $value ) {

		$is_gradient_color = preg_match_all( '/\b((?:repeating-)?(?:linear|radial|conic)-gradient)\((?<=\()(?:[^()]+|\([^)]+\))+\)/', $value, $test_string );

		if ( ! $is_gradient_color ) {
			return '';
		}

		$is_valid_gradient = str_replace( $test_string[0], '', $value );

		if ( preg_match( '%[\\\(&=}\'":;]|/\*%', $is_valid_gradient ) ) {
			return '';
		}

		return sanitize_text_field( $value );

	}

	/**
	 * Sanitize CSS color keywords
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $value Value to sanitize.
	 * @return string
	 */
	final public function sanitize_css_keyword_color( $value ) {

		$is_keyword_color = ! preg_replace(
			'/^\s*(?:yellowgreen|yellow|whitesmoke|white|wheat|VisitedText|violet|turquoise|transparent|tomato|thistle|teal|tan|steelblue|springgreen|snow|slategrey|slategray|slateblue|skyblue|silver|
			sienna|SelectedItemText|SelectedItem|seashell|seagreen|sandybrown|salmon|saddlebrown|royalblue|rosybrown|red|rebeccapurple|purple|powderblue|plum|pink|peru|peachpuff|papayawhip|
			palevioletred|paleturquoise|palegreen|palegoldenrod|orchid|orangered|orange|olivedrab|olive|oldlace|navy|navajowhite|moccasin|mistyrose|mintcream|midnightblue|mediumvioletred|
			mediumturquoise|mediumspringgreen|mediumslateblue|mediumseagreen|mediumpurple|mediumorchid|mediumblue|mediumaquamarine|maroon|MarkText|Mark|magenta|LinkText|linen|limegreen|
			lime|lightyellow|lightsteelblue|lightslategrey|lightslategray|lightskyblue|lightseagreen|lightsalmon|lightpink|lightgrey|lightgreen|lightgray|lightgoldenrodyellow|lightcyan|
			lightcoral|lightblue|lemonchiffon|lawngreen|lavenderblush|lavender|khaki|ivory|indigo|indianred|hotpink|honeydew|HighlightText|Highlight|grey|greenyellow|green|GrayText|gray|
			goldenrod|gold|ghostwhite|gainsboro|fuchsia|forestgreen|floralwhite|firebrick|FieldText|Field|dodgerblue|dimgrey|dimgray|deepskyblue|deeppink|darkviolet|darkturquoise|darkslategrey|
			darkslategray|darkslateblue|darkseagreen|darksalmon|darkred|darkorchid|darkorange|darkolivegreen|darkmagenta|darkkhaki|darkgrey|darkgreen|darkgray|darkgoldenrod|darkcyan|darkblue|
			cyan|currentColor|crimson|cornsilk|cornflowerblue|coral|chocolate|chartreuse|CanvasText|Canvas|cadetblue|ButtonText|ButtonFace|ButtonBorder|burlywood|brown|blueviolet|blue|blanchedalmond|
			black|bisque|beige|azure|aquamarine|aqua|antiquewhite|aliceblue|ActiveText|AccentColorText|AccentColor|inherit|initial|revert|revert-layer|unset)\s*$/',
			'',
			$value
		);

		if ( ! $is_keyword_color ) {
			return '';
		}

		return trim( $value );

	}

	/**
	 * Sanitize CSS number value
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $value Value to sanitize.
	 * @return integer/string
	 */
	final public function sanitize_css_number( $value ) {

		if ( ! is_scalar( $value ) || '' === $value ) {
			return '';
		}

		$valid_css_units = 'cm|mm|Q|in|pc|pt|px|%|em|ex|ch|rem|lh|rlh|vw|vh|vmin|vmax|vb|vi|svw|svh|lvw|lvh|dvw|dvh|deg|rad|grad|turn|ms|s';
		$is_number_unit  = preg_match( '/^(\s*[-+]?\s*\d*\s*(?:[\d\s]*[,.]?[\d\s]*)?)\s*(' . $valid_css_units . ')\s*/', $value, $matches );

		if ( $is_number_unit ) {
			return $this->sanitize_float( $matches[1] ) . $matches[2];
		}

		// Remove allowed CSS functions.
		$test_string = preg_replace( '/\b(?:calc|clamp|fit-content|max|min|minmax|var|repeat)(\((?:[^()](?![\\\&={}\'":;])|(?1))*\))/', '', $value );
		// Remove allowed CSS keywords.
		$test_string = preg_replace( '/\b(auto|fit-content|initial|inherit|max-content|min-content|none|normal|revert|unset|-webkit-fill-available)\b/', '', $test_string );

		// Only spaces are allowed at this stage (match all conditions).
		if ( '' !== trim( $test_string ) ) {
			return '';
		}

		return sanitize_text_field( trim( $value ) );

	}

	/**
	 * Sanitize CSS variable
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $value Value to sanitize.
	 * @return string
	 */
	final public function sanitize_css_variable( $value ) {

		$is_css_variable = ! preg_replace( '/^\s*\bvar(\((?:[^()](?![\\\&={}\'":;])|(?1))*\))\s*/', '', $value );

		if ( ! $is_css_variable ) {
			return '';
		}

		return sanitize_text_field( $value );

	}

	/**
	 * Sanitize date
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $value   Value to sanitize.
	 * @param  array $control Holds control settings.
	 * @return string
	 */
	final public function sanitize_date( $value, $control = [] ) {

		if ( empty( $value ) || ! is_scalar( $value ) ) {
			return '';
		}

		// Remove timezone to correctly format the date.
		$value = explode( '(', $value );
		$value = trim( $value[0] );

		try {
			$datetime = new DateTime( $value );
		} catch ( \Exception $e ) {
			return '';
		}

		$format = isset( $control['format'] ) ? $control['format'] : '';

		switch ( $format ) {
			case 'datetime':
				$value = $datetime->format( 'Y-m-d h:i:s' );
				break;
			case 'time':
				$value = $datetime->format( 'h:i:s' );
				break;
			default:
				$value = $datetime->format( 'Y-m-d' );
		}

		return $value;

	}

	/**
	 * Sanitize email
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $value Value to sanitize.
	 * @return string
	 */
	final public function sanitize_email( $value ) {

		if ( empty( $value ) || ! is_string( $value ) ) {
			return '';
		}

		return sanitize_email( trim( $value ) );

	}

	/**
	 * Sanitize file
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $value Value to sanitize.
	 * @return string
	 */
	final public function sanitize_file( $value ) {

		if ( empty( $value ) || ! is_string( $value ) ) {
			return '';
		}

		$file = wp_check_filetype(
			$value,
			[
				// Image formats.
				'jpg|jpeg|jpe' => 'image/jpeg',
				'gif'          => 'image/gif',
				'png'          => 'image/png',
				'bmp'          => 'image/bmp',
				'tif|tiff'     => 'image/tiff',
				'ico'          => 'image/x-icon',
				'svg'          => 'image/svg+xml',
				'webp'         => 'image/webp',
				'heic'         => 'image/heic',
				// Video formats.
				'avi'          => 'video/avi',
				'divx'         => 'video/divx',
				'mov|qt'       => 'video/quicktime',
				'mpeg|mpg|mpe' => 'video/mpeg',
				'mp4|m4v'      => 'video/mp4',
				'ogv'          => 'video/ogg',
				'webm'         => 'video/webm',
				'mkv'          => 'video/x-matroska',
				// Audio formats.
				'mp3|m4a|m4b'  => 'audio/mpeg',
				'wav'          => 'audio/wav',
				'ogg|oga'      => 'audio/ogg',
			]
		);

		if ( false === $file['ext'] ) {
			return '';
		}

		return esc_url_raw( $value );

	}

	/**
	 * Sanitize font families and variants
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $value Value to sanitize.
	 * @return array
	 */
	final public function sanitize_fonts( $value ) {

		if ( ! is_array( $value ) ) {
			return [];
		}

		$fonts = [];

		foreach ( $value as $font => $variants ) {

			if ( ! is_string( $font ) || ! is_array( $variants ) ) {
				continue;
			}

			$fonts[ sanitize_text_field( $font ) ] = array_map( 'intval', $variants );

		}

		return array_filter( $fonts );

	}

	/**
	 * Sanitize float
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $value Value to sanitize.
	 * @return integer/string
	 */
	final public function sanitize_float( $value ) {

		if ( ! is_scalar( $value ) || '' === $value ) {
			return '';
		}

		$value = (float) str_replace( ',', '.', preg_replace( '/\s+/', '', $value ) );

		// If the precision level is high.
		if ( abs( (int) $value - $value ) < 0.00001 ) {
			return (int) $value;
		}

		// Keep as string to prevent precision errors.
		return rtrim( $value, 0 );

	}

	/**
	 * Sanitize integer
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $value Value to sanitize.
	 * @return string|integer
	 */
	final public function sanitize_integer( $value ) {

		if ( ! is_scalar( $value ) ) {
			return '';
		}

		return intval( $value );

	}

	/**
	 * Sanitize integers
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $value   Value to sanitize.
	 * @param  array $control Holds control settings.
	 * @return array
	 */
	final public function sanitize_integers( $value, $control = [] ) {

		if ( empty( $value ) || ! is_array( $value ) ) {
			return [];
		}

		$value = array_values( $value );
		$value = array_map( [ $this, 'sanitize_integer' ], $value, $control );
		$value = array_unique( $value );
		$value = array_filter(
			$value,
			function( $val ) {
				return '' !== $val;
			}
		);

		return $value;

	}

	/**
	 * Sanitize number
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $value   Value to sanitize.
	 * @param  array $control Holds control settings.
	 * @return integer/string
	 */
	final public function sanitize_number( $value, $control = [] ) {

		if ( ! is_scalar( $value ) || '' === $value ) {
			return '';
		}

		$is_numeric = preg_match( '/^\s*[-+]?\s*\d*\s*(?:[\d\s]*[,.]?[\d\s]*)?\s*$/', $value );

		if ( $is_numeric ) {
			return $this->sanitize_float( $value, $control );
		}

		if ( ! isset( $control['units'] ) ) {
			return '';
		}

		return $this->sanitize_css_number( $value );

	}

	/**
	 * Sanitize password
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $value Value to sanitize.
	 * @return string
	 */
	final public function sanitize_password( $value ) {

		if ( ! is_scalar( $value ) ) {
			return '';
		}

		return wp_filter_nohtml_kses( trim( $value ) );

	}

	/**
	 * Sanitize text
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $value   Value to sanitize.
	 * @param  array $control Holds control settings.
	 * @return string
	 */
	final public function sanitize_text( $value, $control = [] ) {

		if ( ! is_scalar( $value ) ) {
			return '';
		}

		$value = (string) $value;

		// Preserve white spaces at ends while using native sanitize_text_field.
		if ( ! empty( $control['whiteSpaces'] ) ) {

			// If only white spaces.
			if ( ctype_space( $value ) ) {
				return $value;
			}

			preg_match( '/^\A\s*/', $value, $s_spaces );
			preg_match( '/\s*\z/', $value, $e_spaces );

		}

		// Preserve angle quotes.
		if ( ! empty( $control['angleQuotes'] ) ) {
			$value = str_replace( '<', '&lt;', $value );
		}

		return (
			( ! empty( $s_spaces[0] ) ? $s_spaces[0] : '' ) .
			sanitize_text_field( $value ) .
			( ! empty( $e_spaces[0] ) ? $e_spaces[0] : '' )
		);
	}

	/**
	 * Sanitize url
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $value Value to sanitize.
	 * @param  array $control Holds control settings.
	 * @return string
	 */
	final public function sanitize_url( $value, $control ) {

		if ( empty( $value ) || ! is_string( $value ) ) {
			return '';
		}

		// Allow dynamic data and shortcodes in the URL.
		if ( ! empty( $control['dynamicData'] ) || ! empty( $control['shortcode'] ) ) {
			return sanitize_text_field( $value );
		}

		return esc_url_raw( trim( $value ) );

	}
}
