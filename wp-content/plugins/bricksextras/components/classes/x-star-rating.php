<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Star_Rating extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xstarrating';
	public $icon         = 'ion-md-star-outline';
	public $css_selector = '';

  
  public function get_label() {
	  return esc_html__( 'Star Rating', 'extras' );
  }
  public function set_control_groups() {

  }

  public function set_controls() {

    $this->controls['starRating'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Star Rating', 'bricks' ),
			'type' => 'text',
			'min' => 0,
			'step' => 1, 
			'inline' => true,
			'placeholder' => esc_html__( '4', 'bricksextras' ),
		  ];

	$this->controls['totalStars'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Total stars', 'bricks' ),
		'type' => 'text',
		'min' => 0,
		'step' => 1, 
		'inline' => true,
		'placeholder' => esc_html__( '5', 'bricksextras' ),
		];	  

		$this->controls['iconSeparator'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Icons', 'bricks' ),
			'type' => 'separator',
			'inline' => true,
		];
	
		$this->controls['iconBehaviour'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Swap icons or colors', 'bricks' ),
			'type' => 'select',
			'inline' => true,
			'small' => true,
			'options' => [
				'color' => esc_html__( 'Colors', 'bricks' ),
				'icons' => esc_html__( 'Icons', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Icons', 'bricksextras' ),
		];
	
		$this->controls['markedIcon'] = [
				'tab'     => 'content',
				'label'   => esc_html__( 'Icon', 'bricks' ),
				'type'    => 'icon',
				'css'     => [
					[
						'selector' => '.x-star-rating_star-marked > *',
					],
				],
				'default' => [
					'library' => 'fontawesomeSolid',
					'icon'    => 'fas fa-star',
				],
			];
	
		$this->controls['halfmarkedIcon'] = [
				'tab'     => 'content',
				'label'   => esc_html__( 'Half marked icon', 'bricks' ),
				'type'    => 'icon',
				'css'     => [
					[
						'selector' => '.x-star-rating_star-half-marked > *', 
					],
				],
				'default' => [
					'library' => 'fontawesomeSolid',
					'icon'    => 'fas fa-star-half-stroke',
				],
				'required' => [
					['iconBehaviour', '!=', 'color'],
				],
			];
	
		$this->controls['icon'] = [
				'tab'     => 'content',
				'label'   => esc_html__( 'Empty Icon', 'bricks' ),
				'type'    => 'icon',
				'css'     => [
					[
						'selector' => '.x-star-rating_star > *', 
					],
				],
				'default' => [
					'library' => 'fontawesomeRegular',
					'icon'    => 'fa fa-star',
				],
				'required' => [
					['iconBehaviour', '!=', 'color'],
				],
			];

			$this->controls['iconColor'] = [
				'tab'      => 'content',
				'label'    => esc_html__( 'Color', 'bricks' ),
				'type'     => 'color',
				'css'      => [
					[
						'property' => 'color',
					],
				],
				'required' => [
					['iconBehaviour', '!=', 'color'],
				],
			];

		$this->controls['fullColor'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Color', 'bricks' ),
			'type'     => 'color',
			'css'      => [
				[
					'selector' => '&[data-x-icon=color]',
					'property' => '--x-star-color',
				],
			],
			'required' => [
					['iconBehaviour', '=', 'color'],
				],
		];

		$this->controls['empytyColor'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Empty color', 'bricks' ),
			'type'     => 'color',
			'css'      => [
				[
					'selector' => '&[data-x-icon=color]',
					'property' => '--x-star-empty-color',
				],
			],
			'required' => [
					['iconBehaviour', '=', 'color'],
				],
		];

		$this->controls['iconSize'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Icon size', 'bricks' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => [
				[
					'property' => 'font-size',
          			'selector' => 'i'
				],
				[
					'property' => 'font-size',
				],
			],
			'placeholder' => '20px',
			
		];

		$this->controls['iconGap'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Gap between stars', 'bricks' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => [
				[
					'property' => 'gap',
          			'selector' => ''
				],
			],
			'placeholder' => '0',
			
		];


    $this->controls['iconMargin'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Star margin', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'margin',
					'selector' => 'i'
				],
			],
		];

  }

  public function enqueue_scripts() {
	if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
		wp_enqueue_style( 'x-star-rating', BRICKSEXTRAS_URL . 'components/assets/css/starrating.css', [], '' );
	}
  }
  
  public function render() {

	$this->set_attribute( 'x-star-rating_star-marked', 'class', 'x-star-rating_star-marked' );
	$this->set_attribute( 'x-star-rating_star-half-marked', 'class', 'x-star-rating_star-half-marked' );
	$this->set_attribute( 'x-star-rating_star', 'class', 'x-star-rating_star' );

    $settings = $this->settings;

	$iconBehaviour = isset( $settings['iconBehaviour'] ) ? $settings['iconBehaviour'] : 'icons';

	$starRatingSetting = isset( $settings['starRating'] ) ? $settings['starRating'] : 4;
    $totalStarsSetting = isset( $settings['totalStars'] ) ? $settings['totalStars'] : 5;

	$starRatingOriginal = strstr( $starRatingSetting, '{') ? $this->render_dynamic_data_tag( $starRatingSetting, 'text' ) : $starRatingSetting;
	$starRatingOriginal = is_numeric($starRatingOriginal) ? (float) $starRatingOriginal : 0;
	$totalStars = strstr( $totalStarsSetting, '{') ? $this->render_dynamic_data_tag( $totalStarsSetting, 'text' ) : $totalStarsSetting;
	$totalStars = is_numeric($totalStars) ? (float) $totalStars : 0; 

	$starRating = round( floatval( $starRatingOriginal  ) * 2 ) / 2;

	if ( 'color' == $iconBehaviour ) {

		$icon     = ! empty( $settings['markedIcon'] ) ? "<div {$this->render_attributes( 'x-star-rating_star' )}>" . self::render_icon( $settings['markedIcon'] ) . "</div>" : false;
		$markedIcon     = ! empty( $settings['markedIcon'] ) ? "<div {$this->render_attributes( 'x-star-rating_star-marked' )}>" . self::render_icon( $settings['markedIcon'] ) . "</div>" : false;
		$halfmarkedIcon = ! empty( $settings['markedIcon'] ) ? "<div {$this->render_attributes( 'x-star-rating_star-half-marked' )}>" . self::render_icon( $settings['markedIcon'] ) . "</div>" : false;

		if ( $icon && str_contains($icon, 'svg') ) {
			$halfmarkedIcon = ! empty( $settings['markedIcon'] ) ? "<div {$this->render_attributes( 'x-star-rating_star-half-marked' )}>" . self::render_icon( $settings['markedIcon'] ) . "<span>" . self::render_icon( $settings['markedIcon'] ) . "</span></div>" : false;
		}

	} else {

		$icon     = ! empty( $settings['icon'] ) ? "<div {$this->render_attributes( 'x-star-rating_star' )}>" . self::render_icon( $settings['icon'] ) . "</div>" : false;
		$markedIcon     = ! empty( $settings['markedIcon'] ) ? "<div {$this->render_attributes( 'x-star-rating_star-marked' )}>" . self::render_icon( $settings['markedIcon'] ) . "</div>" : false;
		$halfmarkedIcon = ! empty( $settings['halfmarkedIcon'] ) ? "<div {$this->render_attributes( 'x-star-rating_star-half-marked' )}>" . self::render_icon( $settings['halfmarkedIcon'] ) . "</div>" : false;

	}

   

    $ariaLabel = esc_attr__( "Rating: " . $starRatingOriginal . " out of " . $totalStars . " stars" );

    $this->set_attribute( '_root', 'aria-label', $ariaLabel );
    $this->set_attribute( '_root', 'role', 'img' );
	$this->set_attribute( '_root', 'data-x-star-rating', esc_attr($starRatingOriginal) );
	$this->set_attribute( '_root', 'data-x-icon', esc_attr( $iconBehaviour ) );

	$percentage = ( $starRatingOriginal - floor( $starRatingOriginal ) ) * 100;

	if (0 == $percentage && floatval($totalStars) == floatval($starRating)) {
		$percentage = 100;
	}

	$this->set_attribute( '_root', 'style', esc_attr( "--x-star-percentage: {$percentage}%" ) );
	
	

	$includeHalfStar = ( $starRating * 2 ) % 2 != 0;

	$roundingUp = floor( $starRating ) > $starRatingOriginal;

	$output = '';

	if ( is_numeric( $starRating ) && is_numeric( $totalStars ) ) {

		if ( 'color' == $iconBehaviour && !$includeHalfStar ) {

			$markedIcons = $roundingUp ? str_repeat( $markedIcon, floor($starRating) - 1 ) : str_repeat( $markedIcon, floor($starRating) );
			$output .= $totalStars > $starRating ? $markedIcons . "<div {$this->render_attributes( 'x-star-rating_star-half-marked' )}>" . self::render_icon( $settings['markedIcon'] ) . "<span>" . self::render_icon( $settings['markedIcon'] ) . "</span></div>" : str_repeat( $markedIcon, $totalStars );
		} else {
			$output .= $totalStars >= $starRating ? str_repeat( $markedIcon, floor($starRating) ) : str_repeat( $markedIcon, $totalStars );
		}

		$output .= $includeHalfStar ? $halfmarkedIcon : '';

		if ( $totalStars - $starRating > 0 ) {

			if ( 'color' == $iconBehaviour && !$roundingUp && !$includeHalfStar && ( floatval($totalStars) != floatval($starRating) ) ) {
				$output .= str_repeat( $icon, $totalStars - 1 - round( $starRating ) );
			} else {
				$output .= str_repeat( $icon, $totalStars - round( $starRating ) );
			}
			
		} 
	}

	echo "<div {$this->render_attributes( '_root' )}>";
	echo $output;
    echo "</div>";
    
  }

}