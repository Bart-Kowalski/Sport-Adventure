function xInteractions() {

    const extrasInteractions = function ( container ) {
    
        container.querySelectorAll("[data-interactions]").forEach((element) => {

            const configAttr = element.getAttribute('data-interactions')
            const config = configAttr ? JSON.parse(configAttr) : {}

            config.forEach((interaction) => {

                let delay = interaction.xInteractionsDelay ? interaction.xInteractionsDelay : 0;
                let runOnce = interaction.runOnce
                let playertime
                
                    switch (interaction.trigger) {

                        /* Extras window listeners */

                        case "x_sticky_header:active":
                        case "x_sticky_header:inactive":
                        case "x_hide_header:active":
                        case "x_hide_header:inactive":

                            const header = document.querySelector('#brx-header');

                            let headerSticky = false;
                            let headerHide= false;

                            let observeHeader = new MutationObserver(function(mutations) {

                                mutations.forEach(function(mutation) {
                                
                                    if (mutation.type === 'attributes' && mutation.attributeName === 'class') {

                                        if (!headerSticky) {
                                            if (header.classList.contains('x-header_sticky-active')) {
                                                headerSticky = true
                                                setTimeout(() => {
                                                    if ('x_sticky_header:active' === interaction.trigger) {
                                                        bricksInteractionCallbackExecution(element, interaction);
                                                        if ( runOnce ) {
                                                            observeHeader.disconnect();
                                                        }
                                                    }   
                                                }, delay);
                                                headerSticky = true
                                            }
                                        } else {
                                            if (!header.classList.contains('x-header_sticky-active')) {
                                                headerSticky = false
                                                setTimeout(() => {
                                                    if ('x_sticky_header:inactive' === interaction.trigger) {
                                                        bricksInteractionCallbackExecution(element, interaction);
                                                        if ( runOnce ) {
                                                            observeHeader.disconnect();
                                                        }
                                                    }   
                                                }, delay);
                                            }

                                        }

                                        if (!headerHide) {
                                            if (header.classList.contains('x-header_not-pin')) {
                                                headerHide = true
                                                
                                                setTimeout(() => {
                                                    if ('x_hide_header:active' === interaction.trigger) {
                                                        bricksInteractionCallbackExecution(element, interaction);
                                                        if ( runOnce ) {
                                                            observeHeader.disconnect();
                                                        }
                                                    }   
                                                }, delay);
                                                headerHide = true
                                            }
                                        } else {
                                            if (!header.classList.contains('x-header_not-pin')) {
                                                headerHide = false
                                                setTimeout(() => {
                                                    if ('x_hide_header:inactive' === interaction.trigger) {
                                                        bricksInteractionCallbackExecution(element, interaction);
                                                        if ( runOnce ) {
                                                            observeHeader.disconnect();
                                                        }
                                                    }   
                                                }, delay);
                                            }

                                        }

                                    }

                                });
                            });

                            observeHeader.observe(header, {
                                attributes: true
                            });

                            break;

                        

                        case "x_toggle_switch:toggled_{label number}":

                        let labelNumber = interaction.xToggleSwitchLabelNumber ? interaction.xToggleSwitchLabelNumber : 1;
                        let eventTrigger = interaction.trigger.slice(0, -14) + labelNumber.toString()

                            const extrasToggleSwitchLabelNumber = () => {

                                setTimeout(() => {

                                    bricksInteractionCallbackExecution(element, interaction);
                                    if ( runOnce ) {
                                         element.removeEventListener(eventTrigger, extrasToggleSwitchLabelNumber)
                                         element.removeEventListener('x_toggle_switch:checked', extrasToggleSwitchLabelNumber)
                                         element.removeEventListener('x_toggle_switch:unchecked', extrasToggleSwitchLabelNumber)
                                    }
                                }, delay);
                            }

                            if ('1' === labelNumber.toString() ) {
                                element.addEventListener('x_toggle_switch:unchecked', extrasToggleSwitchLabelNumber)
                            } else if ('2' === labelNumber.toString()) {
                                element.addEventListener('x_toggle_switch:checked', extrasToggleSwitchLabelNumber)
                            }
                            element.addEventListener(eventTrigger, extrasToggleSwitchLabelNumber)

                            break;

                        case "x_accordion:expand_{index}":

                        let x_accordion_index = interaction.xAccordionItemIndex ? interaction.xAccordionItemIndex : 0;
                        let x_accordion_trigger = interaction.trigger.slice(0, -7) + x_accordion_index.toString()

                            const extrasAccordionItemIndex = () => {

                                setTimeout(() => {

                                    bricksInteractionCallbackExecution(element, interaction);
                                    if ( runOnce ) {
                                         element.removeEventListener(x_accordion_trigger, extrasAccordionItemIndex)
                                    }
                                }, delay);
                            }

                            element.addEventListener(x_accordion_trigger, extrasAccordionItemIndex)

                            break;


                        case "x_tabs_accordion:expand_{index}":

                        let x_tabs_accordion_index = interaction.xAccordionTabsItemIndex ? interaction.xAccordionTabsItemIndex : 0;
                        let x_tabs_accordion_trigger = interaction.trigger.slice(0, -7) + x_tabs_accordion_index.toString()

                            const extrasTabsAccordionItemIndex = () => {

                                setTimeout(() => {

                                    bricksInteractionCallbackExecution(element, interaction);
                                    if ( runOnce ) {
                                         element.removeEventListener(x_tabs_accordion_trigger, extrasTabsAccordionItemIndex)
                                    }
                                }, delay);
                            }

                            element.addEventListener(x_tabs_accordion_trigger, extrasTabsAccordionItemIndex)

                            break;


                        case "x_image_hotspot:selected_{index}":

                        let x_image_hotspots_index = interaction.xImageHotspotsIndex ? interaction.xImageHotspotsIndex : 0;
                        let x_image_hotspots_trigger = interaction.trigger.slice(0, -7) + x_image_hotspots_index.toString()

                            const extrasImageHotspotsIndex = () => {

                                setTimeout(() => {

                                    bricksInteractionCallbackExecution(element, interaction);
                                    if ( runOnce ) {
                                            element.removeEventListener(x_image_hotspots_trigger, extrasImageHotspotsIndex)
                                    }
                                }, delay);
                            }

                            element.addEventListener(x_image_hotspots_trigger, extrasImageHotspotsIndex)
    
                                break;


                        case "x_favorite:count-reached_{count}":

                            let x_favorite_count_reached_count = interaction.xFavoriteCountReachedCount ? interaction.xFavoriteCountReachedCount : 0;
                            let x_favorite_count_reached_trigger = interaction.trigger.slice(0, -7) + x_favorite_count_reached_count.toString()

                            const extrasFavoriteCountReached = () => {

                                setTimeout(() => {

                                    bricksInteractionCallbackExecution(element, interaction);
                                    if ( runOnce ) {
                                         element.removeEventListener(x_favorite_count_reached_trigger, extrasFavoriteCountReached)
                                    }
                                }, delay);
                            }

                            element.addEventListener(x_favorite_count_reached_trigger, extrasFavoriteCountReached)

                            break;
                            


                        /* Extras Elements */

                        case "x_modal:open":
                        case "x_modal:close":
                        case "x_alert:show":  
                        case "x_alert:close":    
                        case "x_offcanvas:open":
                        case "x_offcanvas:close":   
                        case "x_lightbox:open":
                        case "x_lightbox:close":
                        case "x_toggle_switch:change":
                        case "x_copy:copied":
                        case "x_copy:failed":
                        case "x_copy:reset":
                        case "x_copy:empty":
                        case "x_notification:show":
                        case "x_notification:close":
                        case "x_countdown:ended":

                        case "x_tabs:accordion":
                        case "x_tabs:tabs":
                        case "x_tabs:switch":
                        case "x_tabs_accordion:collapse":
                        case "x_tabs_accordion:expand":
                        case "x_tabs:tab_":
                        case "x_toc:link-clicked":

                        /*
                        case "x_media_playlist:active":
                        case "x_media_playlist:inactive":
                        case "x_media_playlist:paused":
                        case "x_media_playlist:playing":
                        */

                        case "x_readmore:collapsed":
                        case "x_readmore:expanded":
                        case "x_readmore:collapse":
                        case "x_readmore:expand":

                        case "x_slide_menu:expand":
                        case "x_slide_menu:collapse":

                        /* lottie */
                        case "x_lottie:complete":

                        case "x_accordion:expand":

                        /* favorite */
                        case "x_favorite:added":
                        case "x_favorite:removed":
                        case "x_favorite:cleared":
                        case "x_favorite:maximum-reached":


                        let interactionTrigger = interaction.trigger

                            if ('x_tabs:tab_' === interactionTrigger) {
                                interactionTrigger = interaction.xTabNumber ? interactionTrigger + interaction.xTabNumber : interaction.trigger
                            }
                         
                            const extrasElementsEventListener = () => {

                                setTimeout(() => {
                                    bricksInteractionCallbackExecution(element, interaction);
                                    if ( runOnce || "x_countdown:ended" === interaction.trigger ) {
                                        element.removeEventListener(interactionTrigger, extrasElementsEventListener)
                                    }
                                }, delay);
                            }

                            element.addEventListener(interactionTrigger, extrasElementsEventListener)
    
                            break;


                        /* WS Form */

                        case "wsf-submit-success":
                        case "wsf-submit-error":
                        case "wsf-reset-complete":
                        case "wsf-validate-fail":

                            if ("undefined" != typeof jQuery) {
                                jQuery(document).on(interaction.trigger, function(event, form_object, form_id, instance_id) {

                                    if ( element.querySelector('form[data-instance-id="' + instance_id + '"]') ) {
                                        setTimeout(() => {
                                            bricksInteractionCallbackExecution(element, interaction);
                                        }, delay);
                                    }
                                    
                                });
                            }
                            
                            break;


                        /* Fluent Forms */

                        case "fluentform_submission_success":
                        case "fluentform_reset":
                        case "fluentform_submission_failed":

                            if ("undefined" != typeof jQuery) {
                                jQuery( element.querySelector('form') ).on(interaction.trigger, () => {
                                    setTimeout(() => {
                                        bricksInteractionCallbackExecution(element, interaction);
                                    }, delay);
                                    
                                });
                            }
                            break;


                        /* Pro Slider */

                        case "x_slider:moved":
                        case "x_slider:move":

                            const proSliderEventListener = () => {

                                setTimeout(() => {
                                    bricksInteractionCallbackExecution(element, interaction);
                                    if ( runOnce ) {
                                        const slider = xSlider.Instances[element.getAttribute('data-x-id')]
                                        if (!slider) { return } 
                                        slider.off( interaction.trigger.slice(9), proSliderEventListener)
                                    }
                                }, delay);

                            }

                             /* listen for slider being initialised */
                             element.addEventListener( 'x_slider:init', () => {

                                const slider = xSlider.Instances[element.getAttribute('data-x-id')]

                                if (!slider) { return } 

                                slider.on( interaction.trigger.slice(9), proSliderEventListener)

                             })

                            break;


                        case "x_slider:active-slide":

                            let slideIndex = interaction.xSliderNumber ? interaction.xSliderNumber : 0;

                            const proSliderActiveSlideListener = (newIndex) => {

                                    setTimeout(() => {

                                        if (parseInt(slideIndex) !== parseInt(newIndex)) {
                                            return
                                        }

                                        bricksInteractionCallbackExecution(element, interaction);
                                        if ( runOnce ) {
                                            const slider = xSlider.Instances[element.getAttribute('data-x-id')]
                                            if (!slider) { return } 
                                            slider.off( 'move', proSliderActiveSlideListener)
                                        }
                                    }, delay);

                                }

                                /* listen for slider being initialised */
                                element.addEventListener( 'x_slider:init', () => {

                                    const slider = xSlider.Instances[element.getAttribute('data-x-id')]

                                    if (!slider) { return } 
                                    slider.on( 'move', proSliderActiveSlideListener)

                                })


                             break;
                    }
                });
        });

    }

    extrasInteractions(document);

    function xInteractionsAjax(e) {

        if (typeof e.detail.queryId === 'undefined') {
            if ( typeof e.detail.popupElement === 'undefined' ) {
                return;
            } else {
                extrasInteractions( e.detail.popupElement )
            }
        }

        setTimeout(() => {
            if ( document.querySelector('.brxe-' + e.detail.queryId) ) {
                extrasInteractions(document.querySelector('.brxe-' + e.detail.queryId).parentElement);
            }
        }, 0);
      }
      
      document.addEventListener("bricks/ajax/load_page/completed", xInteractionsAjax)
      document.addEventListener("bricks/ajax/pagination/completed", xInteractionsAjax)
      document.addEventListener("bricks/ajax/popup/loaded", xInteractionsAjax)
      document.addEventListener("bricks/ajax/end", xInteractionsAjax)

    // Expose function
    window.doExtrasInteractions = extrasInteractions;

}

/* function for controlling via custom actions */
window.bricksextras = {}

/* helper functions for common usage */
bricksextras = {
    click: (brxParam) => {
        let target = brxParam?.target || false
        if ( target ) {
          target.click()
        }
    },
  }

document.addEventListener("DOMContentLoaded", () => {
    bricksIsFrontend&&xInteractions()
});
