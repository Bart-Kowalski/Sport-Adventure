function xFavoritePopover(){

    const extrasFavoritePopover = function ( container ) {

        container.querySelectorAll('.brxe-xfavorite[data-x-tooltip]').forEach( favorite => {

            const configAttr = favorite.getAttribute('data-x-favorite')
            const config = configAttr ? JSON.parse(configAttr) : {}

            const type = favorite.getAttribute('data-x-type')

            let tippyProps, tippyEl;

            let content = config.tooltipAddedText

            let disabledText = config.disabledText;

            const encodedString = disabledText;

           
            const parser = new DOMParser();
            const doc = parser.parseFromString(encodedString, 'text/html');
            const decodedString = doc.body.textContent;
            const disabledContent = decodedString

            if ( !favorite.querySelector('.x-favorite_added') ) {
                content = config.removedText
            }

            if ( 'remove' === type ) {
                content = config.removeTooltipText
            }

            if ( 'clear' === type ) {
                content = config.clearTooltipText
            }

            if ( favorite.querySelector('.x-favorite[disabled]') ) {
                content = disabledContent
            }

                if ( document.querySelector('body > .brx-body.iframe') ) {

                    tippyEl = favorite.querySelector('.x-favorite')
                    tippyProps = {
                        render(instance) {
                            const popper = favorite.querySelector('[data-tippy-root]');
                            return {
                                popper,
                            };
                        },
                        allowHTML: true,     
                        interactive: true, 
                        arrow: true,
                        trigger: 'click',
                        appendTo: favorite.querySelector('.x-favorite-tooltip-content'), 
                        placement: config.placement,
                        maxWidth: 'none',    
                        animation: 'extras',
                        theme: 'extras',     
                        touch: true, 
                        offset: [ config.offsetSkidding , config.offsetDistance], 
                        
                    }

                } else {

                    tippyEl = favorite.querySelector('.x-favorite')
                    tippyProps = {
                        content: content, 
                        allowHTML: true,     
                        interactive: true, 
                        arrow: true,
                        trigger: 'mouseenter focus click',
                        appendTo: favorite,
                        placement: config.placement,
                        maxWidth: 'none',    
                        animation: 'extras',
                        theme: 'extras',     
                        touch: ["hold", 1000], 
                        interactiveDebounce: 50,
                        followCursor: 'false' === config.followCursor ? false : config.followCursor,
                        delay: config.delay,
                        offset: [ config.offsetSkidding , config.offsetDistance], 
                        onShow(instance) {

                            tippy.hideAll({exclude: instance})
                            
                            favorite.addEventListener('keydown', function(e) {
                                if((e.key === "Escape" || e.key === "Esc")){
                                    instance.hide();
                                }
                            });

                            favorite.addEventListener('mouseleave', function() {
                                    //instance.hide();
                            });
                        },
                    };
                }  

                let identifier = favorite.dataset.xId;

                if ( config.isLooping ) {
                    identifier = favorite.dataset.xId + '_' + config.isLooping;
                }

                if ( typeof( window.xFavoriteTippy.Instances[identifier] ) != "undefined" ) {
                    return;
                }

                let xTippyInstance = tippy(tippyEl,tippyProps);

                window.xFavoriteTippy.Instances[identifier] = xTippyInstance;

                function xFavoriteAdded() {

                    xTippyInstance.setContent(config.tooltipAddedText)

                    if (tippy.currentInput.isTouch) {
                        xTippyInstance.show()
                    }
                }

                favorite.addEventListener('x_favorite:added',xFavoriteAdded)
                favorite.addEventListener('x_favorite:also-added',xFavoriteAdded)

                function xFavoriteRemoved() {

                    if ('remove' !== type) {

                        if (tippy.currentInput.isTouch) {
                            xTippyInstance.setContent(config.removedTooltipText)
                        } else {
                            xTippyInstance.setContent(config.removedText)
                        }
                        

                        if (tippy.currentInput.isTouch) {
                            xTippyInstance.show()
                        }

                    }
                }

                favorite.addEventListener('x_favorite:removed', xFavoriteRemoved)
                favorite.addEventListener('x_favorite:also-removed', xFavoriteRemoved)

                favorite.addEventListener('x_favorite:cleared', (e) => {

                    if ('clear' === type) {

                        xTippyInstance.setContent(config.clearedTooltipText)
                        if (tippy.currentInput.isTouch) {
                            xTippyInstance.show()
                        }

                    } else if ( 'add_remove' === type && config.removedText ) {
                        xTippyInstance.setContent(config.removedText)
                    }

                })

                favorite.addEventListener('x_favorite:maximum-reached', () => { 

                    if ('add_remove' === type) {
                    
                        if ( config.maxReachedTooltipText  ) {
                            xTippyInstance.setContent(config.maxReachedTooltipText)
                            xTippyInstance.show()
                            xTippyInstance.setProps({
                                onHidden(instance) {
                                    instance.setContent(content)
                                }
                              });
                        }

                        if (tippy.currentInput.isTouch) {
                            xTippyInstance.show()
                        }

                    }

                })

                window.addEventListener('x_favorite:disallowed', (e) => {
                    xTippyInstance.setContent(disabledContent)
                })

                if ('clear' === type) {

                    window.addEventListener('x_favorite:updated', (e) => {

                        const postType = e.detail.postType
                        const data = e.detail.data
        
                        if ('' == data[postType]) {
                            xTippyInstance.setContent(config.clearedTooltipText)
                        } else {
                            xTippyInstance.setContent(config.clearTooltipText)
                        }

                    })

                }

                window.addEventListener('x_favorite:allowed', (e) => {

                    if ( !favorite.querySelector('.x-favorite_added') ) {
                        content = config.removedText
                    } else {
                        content = config.tooltipAddedText
                    }
        
                    if ( 'remove' === type ) {
                        content = config.removeTooltipText
                    }
        
                    if ( 'clear' === type ) {
                        content = config.clearTooltipText
                    }

                    xTippyInstance.setContent(content)

                })


        })

    }

    extrasFavoritePopover(document);

    function xFavoritePopoverAjax(e) {

        if (typeof e.detail.queryId === 'undefined') {
            if ( typeof e.detail.popupElement === 'undefined' ) {
                return;
            } else {
                extrasFavoritePopover( e.detail.popupElement )
            }
        }

        setTimeout(() => {
            if ( document.querySelector('.brxe-' + e.detail.queryId) ) {
                extrasFavoritePopover(document.querySelector('.brxe-' + e.detail.queryId).parentElement);
            }
        }, 0);
   }
   
   document.addEventListener("bricks/ajax/load_page/completed", xFavoritePopoverAjax)
   document.addEventListener("bricks/ajax/pagination/completed", xFavoritePopoverAjax)
   document.addEventListener("bricks/ajax/popup/loaded", xFavoritePopoverAjax)
   document.addEventListener("bricks/ajax/end", xFavoritePopoverAjax)

    // Expose function
    window.doExtrasFavoritePopover = extrasFavoritePopover;

}

document.addEventListener("DOMContentLoaded",function(e){
    bricksIsFrontend&&xFavoritePopover()
})
