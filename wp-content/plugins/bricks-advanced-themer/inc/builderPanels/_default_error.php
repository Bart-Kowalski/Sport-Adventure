<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

?>
<!-- Main -->
<div id="<?php echo esc_attr($overlay_id);?>" class="brxc-overlay__wrapper" style="opacity:0" data-input-target="" onclick="ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>');" >
    <!-- Main Inner -->
    <div class="brxc-overlay__inner brxc-medium">
        <!-- Close Modal Button -->
        <div class="brxc-overlay__close-btn" onClick="ADMINBRXC.closeModal(event, event.target, '#<?php echo esc_attr($overlay_id);?>')">
            <i class="bricks-svg ti-close"></i>
        </div>
        <!-- Modal Wrapper -->
        <div class="brxc-overlay__inner-wrapper">
            <!-- Modal Header -->
            <div class="brxc-overlay__header">
                <!-- Modal Header Title-->
                <h3 class="brxc-overlay__header-title"><?php echo esc_attr($modal_heading_title);?></h3>
            </div>
            <div class="brxc-overlay__container">
                <div class="brxc-feature-error__wrapper">
                    <div class="brxc-feature-error__container">
                        <i class="fa-solid fas fa-circle-exclamation"></i>
                        <div class="brxc-feature-error__title-wrapper">
                            <span class="brxc-feature-error__title"><strong><?php echo esc_html($error_title)?></strong></span>
                            <span class="brxc-feature-error__desc"><?php echo wp_kses_post($error_desc)?></span>
                        </div>
                        <a href="<?php echo esc_html($theme_settings); ?>" target="_blank" class="brxc-overlay__action-btn primary">Open Theme Settings</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Wrapper -->
    </div>
    <!-- End of Main Inner -->
</div>
<!-- End of Main -->