window.WP_Grid_Builder && WP_Grid_Builder.on('init', function(wpgb) {

    wpgb.facets.on('appended', function(content) {

        content.forEach((container,index) => {

            /* Pro Accordion */
            if (typeof doExtrasAccordion == 'function') {

                doExtrasAccordion(container)

                /* targeting items inside accordion*/
                if ( ( ( content.length - 1 ) === index ) && container.closest('.x-accordion.wpgb-enabled') ) {
                    if ( container.parentNode && container.parentNode.parentNode ) {
                        doExtrasAccordion(container.parentNode.parentNode)
                    }
                }
            }

            /* Pro Slider */
            if (typeof doExtrasSlider == 'function') {
                doExtrasSlider(container)
            }

            /* Read More / Less */
            if (typeof doExtrasReadmore == 'function') {
                setTimeout(() => {
                    doExtrasReadmore(container)
                  }, 100);
                
            }

            /* Dynamic Lightbox */
            if (typeof doExtrasLightbox == 'function') {

                if ( container.parentElement ) {

                    /* destroy other lightboxes in loop first */
                    container.parentElement.querySelectorAll('.brxe-xdynamiclightbox').forEach(otherLightbox => {
                        if ( undefined !== window.xDynamicLightbox.Instances[otherLightbox.dataset.xId] ) {
                            window.xDynamicLightbox.Instances[otherLightbox.dataset.xId].destroy()
                            
                        }
                        
                    })

                    /* last element to load, init all in loop */
                    if (  ( content.length - 1 ) === index ) {
                        setTimeout(() => {
                            doExtrasLightbox(container.parentElement, true)
                        }, 1000)

                    }

                }
               
            }
          
            /* Social share */
            if (typeof doExtrasSocialShare == 'function') {
                doExtrasSocialShare(container)
            }
          
          	/* OffCanvas */
            if (typeof doExtrasOffCanvas == 'function') {
                doExtrasOffCanvas(container)
            }
          
            /* modal */
            if (typeof doExtrasModal == 'function') {
                doExtrasModal(container)
            }
          
            /* popover */
            if (typeof doExtrasPopover == 'function') {
                doExtrasPopover(container)
            }

            /* timeline */
            if (typeof doExtrasTimeline == 'function') {
                window.dispatchEvent(new Event('resize'))
            }

             /* tabs */
             if (typeof doExtrasTabs == 'function') {
                doExtrasTabs(container)
            }

            /* lottie */
            if (typeof doExtrasLottie == 'function') {
                doExtrasLottie(container, true)
            }

            /* media player */
            if (typeof doExtrasMediaPlayer == 'function') {
                doExtrasMediaPlayer(container)
            }

            /* copy to clipboard */
            if (typeof doExtrasCopyToClipBoard == 'function') {
                doExtrasCopyToClipBoard(container)
            }
            if (typeof doExtrasCopyToClipBoardPopover == 'function') {
                doExtrasCopyToClipBoardPopover(container)
            }
            
            /* dynamic map */
            if (typeof doExtrasDynamicMap == 'function') {
                if (container.closest('.brxe-section')) {
                    doExtrasDynamicMap(container.closest('.brxe-section'))
                }
            }

            /* parallax */
            if (typeof doExtrasParallax == 'function') {
                doExtrasParallax(container)
            }

            /* tilt */
            if (typeof doExtrasTilt == 'function') {
                doExtrasTilt(container)
            }

            /* interactions */
            if (typeof doExtrasInteractions == 'function') {
                doExtrasInteractions(container)
            }

            /* table */
            if (typeof doExtrasTable == 'function') {
                doExtrasTable(container)
            }

            /* chart */
            if (typeof doExtrasChart == 'function') {
                doExtrasChart(container)
            }

            /* before after */
            if (typeof doExtrasBeforeAfterImage == 'function') {
                doExtrasBeforeAfterImage(container)
            }

            /* countdown */
            if (typeof doExtrasCountdown == 'function') {
                doExtrasCountdown(container)
            }
            
            /* image hotspots */
            if (typeof doExtrasImageHotspots == 'function') {
                doExtrasImageHotspots(container)
            }

            /* toggle switch */
            if (typeof doExtrasToggleSwitch == 'function') {
                doExtrasToggleSwitch(container)
            }

            /* favorites */
            if (typeof doExtrasFavorite == 'function') {
                doExtrasFavorite(container)
                if (typeof doExtrasFavoritePopover == 'function') {
                    doExtrasFavoritePopover(container)
                }
                
            }

            
            
            

        })

    })

})