<?php
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcopenAIOverlay';
$prefix_id = 'brxcOpenAI';
$prefix_class = 'brxc-openai';
// Heading
$modal_heading_title = 'OpenAI Assistant';
$modal_heading_link = '/';

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
                <!-- Modal Header External Link Icon-->
                <a href="<?php echo esc_attr($modal_heading_link);?>" target="_blank" class="brxc-overlay__header-link">
                    <i class="fa-solid fa-up-right-from-square"></i>
                </a>
            </div>
            <!-- Modal Error Container for OpenAI -->
            <div class="brxc-overlay__error-message-wrapper"></div>
            <!-- Modal Container -->
            <div class="brxc-overlay__container">
                <!-- Modal Panel Switch -->
                <div class="brxc-overlay__panel-switcher-wrapper">
                    <!-- Label/Input Switchers -->

                    <!-- End of Label/Input Switchers -->
                </div>
                <!-- End of Panel Switch -->
                <!-- Modal Panels Wrapper -->
                <div class="brxc-overlay__pannels-wrapper">
                    <!-- Modal Panel -->
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-1">
                        <!-- Panel Content -->
                        <div class="brxc-overlay__pannel-top--sticky">
                        </div>
                        <!-- End of Panel Content -->
                    </div>
                    <!-- End of Modal Panel -->
                    <!-- Modal Panel -->
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-2">
                        <!-- Panel Content -->

                        <!-- End of Panel Content -->
                    </div>
                    <!-- End of Modal Panel -->
                    <!-- Modal Panel -->
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-3">
                        <!-- Panel Content -->

                        <!-- End of Panel Content -->
                    </div>
                    <!-- End of Modal Panel -->
                </div>
                <!-- End of Modal Panels Wrapper -->
            </div>
            <!-- End of Modal Container -->
        </div>
        <!-- End of Modal Wrapper -->
    </div>
    <!-- End of Main Inner -->
</div>
<!-- End of Main -->