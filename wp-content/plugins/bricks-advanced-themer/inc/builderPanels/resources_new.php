<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcResourcesOverlay';
$prefix_id = 'brxcResources';
$prefix_class = 'brxc-resources';
// Heading
$modal_heading_title = 'Resources';
$modal_heading_link = \get_admin_url() . 'admin.php?page=bricks-advanced-themer#field_63d8cb54c801e';

ob_start();

if (!AT__Helpers::is_extras_category_activated()){
    $theme_settings = \get_admin_url() . 'admin.php?page=bricks-advanced-themer';
    $error_title = "Feature not enabled";
    $error_desc = "It seems like this feature hasn't been enabled inside the theme settings. Click on the button below and make sure that the Extras settings are enabled inside <strong class='accent'>Global Settings > General > Customize the functions included in Advanced Themer</strong>.";
    include \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/_default_error.php';
} else {
?>
<div id="<?php echo esc_attr($overlay_id);?>" class="brxc-overlay__wrapper" style="opacity:0" data-input-target="" onmousedown="ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>', true);" >
    <div class="brxc-overlay__inner brxc-large">
        <div class="brxc-overlay__close-btn" onClick="ADMINBRXC.closeModal(event, event.target, '#<?php echo esc_attr($overlay_id);?>', true)">
            <i class="bricks-svg ti-close"></i>
        </div>
        <div class="brxc-overlay__inner-wrapper">
            <div class="brxc-overlay__header">
                <h3 class="brxc-overlay__header-title"><?php echo esc_attr($modal_heading_title);?></h3>
                <a href="<?php echo esc_attr($modal_heading_link);?>" target="_blank" class="brxc-overlay__header-link">
                    <i class="fa-solid fa-up-right-from-square"></i>
                </a>
                <div class="brxc-overlay__resize-icons">
                    <i class="fa-solid fa-window-maximize" onclick="ADMINBRXC.maximizeModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                </div>
            </div>
            <div class="brxc-overlay__error-message-wrapper"></div>
            <div class="brxc-overlay__container">
                <div class="brxc-overlay__pannels-wrapper">
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-1">
                        <div id="brxcCSSContainer" class="isotope-wrapper" data-gutter="20" data-filter-layout="masonry">
                            <div id="brxcCSSColLeft" class="brxc-overlay__col-left">
                                <div class="brxc-overlay__action-btn-wrapper m-bottom-10"> 
                                    <div class="filterbtn brxc-overlay__action-btn outline active" data-filter="*">All</div>
                                    <?php
                                    if( have_rows('field_63dd51rw1b209', 'bricks-advanced-themer' ) ):
                                        while( have_rows('field_63dd51rw1b209', 'bricks-advanced-themer' ) ) : the_row(); 
                                            if ( have_rows( 'field_63d8cb65c801f', 'bricks-advanced-themer' ) ) :
                                                while ( have_rows( 'field_63d8cb65c801f', 'bricks-advanced-themer' ) ) :
                                                    the_row();
                                                    $category = get_sub_field('field_63d8cbb7c8020', 'bricks-advanced-themer');
                                                    ?>
                                                    <div class="filterbtn brxc-overlay__action-btn outline" data-filter="<?php echo strtolower( preg_replace( '/\s+/', '-', esc_attr($category) ) );?>"><?php echo esc_attr($category)?></div>
                                                <?php endwhile;
                                            endif;
                                        endwhile;
                                    endif;
                                    ?>
                                </div>
                                <div class="brxc-overlay__search-box">
                                    <input type="text" class="iso-search" name="typography-search" placeholder="Type the title here to filter the grid" data-type="title">
                                    <div class="iso-search-icon">
                                        <i class="bricks-svg ti-search"></i>
                                    </div>
                                    <div class="iso-reset">
                                        <i class="bricks-svg ti-close"></i>
                                    </div>
                                </div>
                            </div>
                            <div id="brxcCSSColRight">
                                <div id="brxcPageCSSWrapper" class="brxc-overlay-resources__wrappe">
                                    <div class="brxc-gallery__container isotope-container">
                                    <?php
                                    if( have_rows('field_63dd51rw1b209', 'bricks-advanced-themer' ) ):
                                        while( have_rows('field_63dd51rw1b209', 'bricks-advanced-themer' ) ) : the_row(); 
                                            if ( have_rows( 'field_63d8cb65c801f', 'bricks-advanced-themer' ) ) :
                                                $index = 0; 
                                                while ( have_rows( 'field_63d8cb65c801f', 'bricks-advanced-themer' ) ) :
                                                    the_row();
                                                    $category = get_sub_field('field_63d8cbb7c8020', 'bricks-advanced-themer');
                                                    $gallery = get_sub_field('field_63d8cbd8c8021', 'bricks-advanced-themer');
                                                    if ( $gallery && is_array($gallery) && !empty($gallery)) : ?>
                                                        <?php foreach( $gallery as $image ) : ?>
                                                            <div class="isotope-selector brxc-isotope__col" data-filter="<?php echo strtolower( preg_replace( '/\s+/', '-', esc_attr($category) ) );?>" title="<?php echo esc_attr( $image['title'] ); ?>" data-transform="calc(-100% - 80px)" onClick="ADMINBRXC.setInnerContent(this);ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);">
                                                                <?php
                                                                $img_url = $image['url'];
                                                                ?> 
                                                                <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>"/>
                                                                <div class="brxc-gallery__title">
                                                                    <span><?php echo esc_attr( $image['title'] ); ?></span>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                <?php endwhile;
                                            endif;
                                        endwhile;
                                    endif;
                                    ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-2 p-top-0">
                        <div class="brxc-overlay__pannel-top--sticky flex align-center space-between m-bottom-10">
                            <div class="brxc-overlay__action-btn" data-transform="0" onClick="ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);">BACK</div>
                            <h3 class="brxc-overlay__header-title"></h3>
                            <div class="brxc-overlay__action-btn-wrapper" style="flex-basis:40%;">
                                <div class="brxc__range" style="flex-grow:1;">
                                    <input type="range" min="0.01" max="2" step="0.01"class="brxc-input__range" oninput="ADMINBRXC.resizeResourceImg(this);">
                                </div>
                                <div class="brxc-overlay-btn__open brxc-overlay__action-btn" onClick="window.open(this.parentNode.closest('.brxc-overlay__container').querySelector('img.inner__img').src, '_blank').focus();">Open in a new tab</div>
                                <div class="brxc-overlay-btn__copy brxc-overlay__action-btn primary" onClick="ADMINBRXC.copytoClipboard(this,this.parentNode.closest('.brxc-overlay__container').querySelector('img.inner__img').src,'URL Copied!','Copy URL to Clipboard');">Copy URL to Clipboard</div>
                            </div>
                        </div>
                        <div class="brxc-overlay__img"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }

$output = ob_get_clean();
$output = preg_replace('/>\s+</s', '><', $output);
$brxc_modals['resources'] = [
    "id" => $overlay_id,
    "html" => trim($output)
];