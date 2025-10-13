
function xImageHotspots(){

    const extrasImageHotspots = function ( container ) {

        bricksQuerySelectorAll(container, '.brxe-ximagehotspots').forEach( imageHotspot => {

            const configAttr = imageHotspot.getAttribute('data-x-hotspots')
            const config = configAttr ? JSON.parse(configAttr) : {}
            
            imageHotspot.querySelectorAll('.x-marker').forEach( (marker, index) => {

                if ( marker.querySelector('.x-marker_marker-trigger') ) {

                    let markerTrigger = marker.querySelector('.x-marker_marker-trigger').tagName == 'BUTTON' ? config.interaction : 'mouseenter focus';
                    let markerInteractive = marker.querySelector('.x-marker_marker-trigger').tagName == 'BUTTON' ? true : false;

                    let instance = tippy(marker.querySelector('.x-marker_marker-trigger'), {
                        content: marker.querySelector('.x-marker_popover-content'), 
                        allowHTML: true,     
                        interactive: markerInteractive, 
                        arrow: true,
                        trigger: markerTrigger,
                        appendTo: marker.querySelector('.x-marker_popover'),
                        placement: config.placement,
                        maxWidth: 'none',    
                        animation: 'extras',
                        theme: 'extras',     
                        touch: true, 
                        moveTransition: 'transform ' + config.moveTransition + 'ms ease-out', 
                        offset: [ config.offsetSkidding , config.offsetDistance], 
                        onShow(instance) {
                            marker.addEventListener('keydown', function(e) {
                                if((e.key === "Escape" || e.key === "Esc")){
                                    instance.hide();
                                }
                            })

                            if ( config.moveFocus && marker.querySelector('.tippy-content') ) {
                                if ( 
                                    !marker.querySelector('.tippy-content a[href]:first-child') &&
                                    !marker.querySelector('.tippy-content h1:first-child > a[href]')  && 
                                    !marker.querySelector('.tippy-content h2:first-child > a[href]')  && 
                                    !marker.querySelector('.tippy-content h3:first-child > a[href]')  && 
                                    !marker.querySelector('.tippy-content h4:first-child > a[href]')  && 
                                    !marker.querySelector('.tippy-content h5:first-child > a[href]')  &&
                                    !marker.querySelector('.tippy-content h6:first-child > a[href]')  && 
                                    !marker.querySelector('.tippy-content button:first-child') &&
                                    !marker.querySelector('.tippy-content input:first-child') &&
                                    !marker.querySelector('.tippy-content textarea:first-child') &&
                                    !marker.querySelector('.tippy-content select:first-child') &&
                                    !marker.querySelector('.tippy-content details:first-child') &&
                                    !marker.querySelector('.tippy-content [tabindex]:not([tabindex="-1"]:first-child') ) {
                                    marker.querySelector('.tippy-content').addEventListener('blur', function(e) {
                                        instance.hide();
                                    })
                                }
                            }
                        },
                        onShown(instance) {

                            marker.querySelectorAll('[data-x-close]').forEach(closeButton => {
                                closeButton.addEventListener('click', () => {
                                    instance.hide()
                                })
                            })

                                marker.dispatchEvent(new Event('x_popover:show'))
                                imageHotspot.dispatchEvent(new Event('x_image_hotspot:selected_' + index))
                                
                                if ( config.moveFocus && marker.querySelector('button.x-marker_marker') ) {

                                    marker.querySelector('button.x-marker_marker').addEventListener('keydown', function(e) {

                                        if ( e.code === 'Tab' && !e.shiftKey ){

                                                if ( marker.querySelector('.tippy-content .x-marker_popover-content') ) {

                                                    marker.querySelector('.tippy-content').setAttribute('tabindex', '0')
                                
                                                    if ( 
                                                        !marker.querySelector('.tippy-content a[href]:first-child') &&
                                                        !marker.querySelector('.tippy-content h1:first-child > a[href]')  && 
                                                        !marker.querySelector('.tippy-content h2:first-child > a[href]')  && 
                                                        !marker.querySelector('.tippy-content h3:first-child > a[href]')  && 
                                                        !marker.querySelector('.tippy-content h4:first-child > a[href]')  && 
                                                        !marker.querySelector('.tippy-content h5:first-child > a[href]')  &&
                                                        !marker.querySelector('.tippy-content h6:first-child > a[href]')  && 
                                                        !marker.querySelector('.tippy-content button:first-child') &&
                                                        !marker.querySelector('.tippy-content input:first-child') &&
                                                        !marker.querySelector('.tippy-content textarea:first-child') &&
                                                        !marker.querySelector('.tippy-content select:first-child') &&
                                                        !marker.querySelector('.tippy-content details:first-child') &&
                                                        !marker.querySelector('.tippy-content [tabindex]:not([tabindex="-1"]:first-child') ) {
                        
                                                            e.preventDefault()
                                                            marker.querySelector('.tippy-content').focus()
                        
                                                        }
                        
                                                }
                                            
                                        }
                                    });
                                }

                        }
                    });


                    if ( marker.querySelector('a.x-marker_marker') ) {
                        marker.querySelector('a.x-marker_marker').addEventListener('click', () => {
                            imageHotspot.dispatchEvent(new Event('x_image_hotspot:selected_' + index))
                        })
                    }

                    window.xHotspots.Instances[marker.dataset.xId] = instance;

                    if ( marker.querySelector('.x-marker_marker') ) {
                        marker.querySelector('.x-marker_marker').addEventListener('focus', () => {
                            tippy.hideAll({exclude: instance})
                        })
                    }

                }

            })

        })

    }


    extrasImageHotspots(document)

    function xImageHotspotsAJAX(e) {

        if ( typeof e.detail.queryId === 'undefined' ) {
            if ( typeof e.detail.popupElement === 'undefined' ) {
                return;
            } else {
                extrasImageHotspots( e.detail.popupElement )
            }
        }
    
        setTimeout(() => {
            if ( document.querySelector('.brxe-' + e.detail.queryId) ) {
                extrasImageHotspots(document.querySelector('.brxe-' + e.detail.queryId).parentElement);
            }
        }, 0);
    
      }
    
      document.addEventListener("bricks/ajax/load_page/completed", xImageHotspotsAJAX)
      document.addEventListener("bricks/ajax/pagination/completed", xImageHotspotsAJAX)
      document.addEventListener("bricks/ajax/popup/loaded", xImageHotspotsAJAX)
      document.addEventListener("bricks/ajax/end", xImageHotspotsAJAX)

    // Expose function
    window.doExtrasImageHotspots = extrasImageHotspots;

}

document.addEventListener("DOMContentLoaded",function(e){
    
    if ( !bricksIsFrontend ) {
        return;
    }

    xImageHotspots()
})
