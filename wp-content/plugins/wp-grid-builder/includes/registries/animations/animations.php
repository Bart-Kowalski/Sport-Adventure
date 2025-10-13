<?php
/**
 * Animations
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
	'wpgb_animation_1'  => [
		'name'    => __( 'Fade in', 'wp-grid-builder' ),
		'visible' => [
			'opacity' => 1,
		],
		'hidden'  => [
			'opacity' => 0,
		],
	],
	'wpgb_animation_2'  => [
		'name'    => __( 'Zoom In', 'wp-grid-builder' ),
		'visible' => [
			'transform' => 'scale(1)',
			'opacity'   => 1,
		],
		'hidden'  => [
			'transform' => 'scale(0.001)',
			'opacity'   => 0,
		],
	],
	'wpgb_animation_3'  => [
		'name'    => __( 'Zoom Out', 'wp-grid-builder' ),
		'visible' => [
			'transform' => 'scale(1)',
			'opacity'   => 1,
		],
		'hidden'  => [
			'transform' => 'scale(1.5)',
			'opacity'   => 0,
		],
	],
	'wpgb_animation_4'  => [
		'name'    => __( 'From Bottom', 'wp-grid-builder' ),
		'visible' => [
			'transform' => 'translateY(0)',
			'opacity'   => 1,
		],
		'hidden'  => [
			'transform' => 'translateY(100px)',
			'opacity'   => 0,
		],
	],
	'wpgb_animation_5'  => [
		'name'    => __( 'From Top', 'wp-grid-builder' ),
		'visible' => [
			'transform' => 'translateY(0)',
			'opacity'   => 1,
		],
		'hidden'  => [
			'transform' => 'translateY(-100px)',
			'opacity'   => 0,
		],
	],
	'wpgb_animation_6'  => [
		'name'    => __( 'From Left', 'wp-grid-builder' ),
		'visible' => [
			'transform' => 'translateX(0)',
			'opacity'   => 1,
		],
		'hidden'  => [
			'transform' => 'translateX(-100px)',
			'opacity'   => 0,
		],
	],
	'wpgb_animation_7'  => [
		'name'    => __( 'From Right', 'wp-grid-builder' ),
		'visible' => [
			'transform' => 'translateX(0)',
			'opacity'   => 1,
		],
		'hidden'  => [
			'transform' => 'translateX(100px)',
			'opacity'   => 0,
		],
	],
	'wpgb_animation_8'  => [
		'name'    => __( 'From Top Left', 'wp-grid-builder' ),
		'visible' => [
			'transform' => 'translate(0,0)',
			'opacity'   => 1,
		],
		'hidden'  => [
			'transform' => 'translate(-100px,-100px)',
			'opacity'   => 0,
		],
	],
	'wpgb_animation_9'  => [
		'name'    => __( 'From Top Right', 'wp-grid-builder' ),
		'visible' => [
			'transform' => 'translate(0,0)',
			'opacity'   => 1,
		],
		'hidden'  => [
			'transform' => 'translate(100px,-100px)',
			'opacity'   => 0,
		],
	],
	'wpgb_animation_10' => [
		'name'    => __( 'From Bottom/left', 'wp-grid-builder' ),
		'visible' => [
			'transform' => 'translate(0,0)',
			'opacity'   => 1,
		],
		'hidden'  => [
			'transform' => 'translate(-100px,100px)',
			'opacity'   => 0,
		],
	],
	'wpgb_animation_11' => [
		'name'    => __( 'From Bottom/Right', 'wp-grid-builder' ),
		'visible' => [
			'transform' => 'translate(0,0)',
			'opacity'   => 1,
		],
		'hidden'  => [
			'transform' => 'translate(100px,100px)',
			'opacity'   => 0,
		],
	],
	'wpgb_animation_12' => [
		'name'    => __( 'Rotate in X', 'wp-grid-builder' ),
		'visible' => [
			'transform' => 'perspective(2000px) rotateX(0) scale(1)',
			'opacity'   => 1,
		],
		'hidden'  => [
			'transform' => 'perspective(2000px) rotateX(180deg) scale(0.5)',
			'opacity'   => 0,
		],
	],
	'wpgb_animation_13' => [
		'name'    => __( 'Rotate in Y', 'wp-grid-builder' ),
		'visible' => [
			'transform' => 'perspective(2000px) rotateY(0) scale(1)',
			'opacity'   => 1,
		],
		'hidden'  => [
			'transform' => 'perspective(2000px) rotateY(180deg) scale(0.5)',
			'opacity'   => 0,
		],
	],
	'wpgb_animation_14' => [
		'name'    => __( 'Flip in X', 'wp-grid-builder' ),
		'visible' => [
			'transform' => 'perspective(2000px) rotateX(0) scale(1)',
			'opacity'   => 1,
		],
		'hidden'  => [
			'transform' => 'perspective(2000px) rotateX(60deg) scale(0.8)',
			'opacity'   => 0,
		],
	],
	'wpgb_animation_15' => [
		'name'    => __( 'Flip in Y', 'wp-grid-builder' ),
		'visible' => [
			'transform' => 'perspective(2000px) rotateY(0) scale(1)',
			'opacity'   => 1,
		],
		'hidden'  => [
			'transform' => 'perspective(2000px) rotateY(60deg) scale(0.8)',
			'opacity'   => 0,
		],
	],
	'wpgb_animation_16' => [
		'name'    => __( 'Flip X from Bottom', 'wp-grid-builder' ),
		'visible' => [
			'transform' => 'perspective(2000px) translateY(0) rotateX(0) scale(1)',
			'opacity'   => 1,
		],
		'hidden'  => [
			'transform-origin' => '50% 100%',
			'transform'        => 'perspective(2000px) translateY(200px) rotateX(60deg) scale(0.8)',
			'opacity'          => 0,
		],
	],
	'wpgb_animation_17' => [
		'name'    => __( 'Flip Y from Bottom', 'wp-grid-builder' ),
		'visible' => [
			'transform' => 'perspective(2000px) translateY(0) rotateY(0) scale(1)',
			'opacity'   => 1,
		],
		'hidden'  => [
			'transform-origin' => '50% 100%',
			'transform'        => 'perspective(2000px) translateY(200px) rotateY(60deg) scale(0.8)',
			'opacity'          => 0,
		],
	],
	'wpgb_animation_18' => [
		'name'    => __( 'Flip X from Top', 'wp-grid-builder' ),
		'visible' => [
			'transform' => 'perspective(2000px) translateY(0) rotateX(0) scale(1)',
			'opacity'   => 1,
		],
		'hidden'  => [
			'transform-origin' => '50% 0',
			'transform'        => 'perspective(2000px) translateY(-200px) rotateX(60deg) scale(0.8)',
			'opacity'          => 0,
		],
	],
	'wpgb_animation_19' => [
		'name'    => __( 'Flip Y from Top', 'wp-grid-builder' ),
		'visible' => [
			'transform' => 'perspective(2000px) translateY(0) rotateY(0) scale(1)',
			'opacity'   => 1,
		],
		'hidden'  => [
			'transform-origin' => '50% 0',
			'transform'        => 'perspective(2000px) translateY(-200px) rotateY(60deg) scale(0.8)',
			'opacity'          => 0,
		],
	],
	'wpgb_animation_20' => [
		'name'    => __( 'Flip X from Left', 'wp-grid-builder' ),
		'visible' => [
			'transform' => 'perspective(2000px) translateX(0) rotateX(0) scale(1)',
			'opacity'   => 1,
		],
		'hidden'  => [
			'transform-origin' => '0 50%',
			'transform'        => 'perspective(2000px) translateX(-200px) rotateX(60deg) scale(0.8)',
			'opacity'          => 0,
		],
	],
	'wpgb_animation_21' => [
		'name'    => __( 'Flip Y from Left', 'wp-grid-builder' ),
		'visible' => [
			'transform' => 'perspective(2000px) translateX(0) rotateY(0) scale(1)',
			'opacity'   => 1,
		],
		'hidden'  => [
			'transform-origin' => '0 50%',
			'transform'        => 'perspective(2000px) translateX(-200px) rotateY(60deg) scale(0.8)',
			'opacity'          => 0,
		],
	],
	'wpgb_animation_22' => [
		'name'    => __( 'Flip X from Right', 'wp-grid-builder' ),
		'visible' => [
			'transform' => 'perspective(2000px) translateX(0) rotateX(0) scale(1)',
			'opacity'   => 1,
		],
		'hidden'  => [
			'transform-origin' => '100% 50%',
			'transform'        => 'perspective(2000px) translateX(200px) rotateX(60deg) scale(0.8)',
			'opacity'          => 0,
		],
	],
	'wpgb_animation_23' => [
		'name'    => __( 'Flip Y from Right', 'wp-grid-builder' ),
		'visible' => [
			'transform' => 'perspective(2000px) translateX(0) rotateY(0) scale(1)',
			'opacity'   => 1,
		],
		'hidden'  => [
			'transform-origin' => '100% 50%',
			'transform'        => 'perspective(2000px) translateX(200px) rotateY(60deg) scale(0.8)',
			'opacity'          => 0,
		],
	],
];
