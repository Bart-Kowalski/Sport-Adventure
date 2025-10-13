function xProAccordion() {

    if ( document.querySelector('body > .brx-body.iframe') ) {
        return
    }

    const debounce = (fn, threshold) => {
        var timeout;
        threshold = threshold || 100;
        return function debounced() {
           clearTimeout(timeout);
           var args = arguments;
           var _this = this;
    
           function delayed() {
              fn.apply(_this, args);
           }
           timeout = setTimeout(delayed, threshold);
        };
     };

    function toggleAccordionItem(e) {
        xToggleAccordionItem(e.target.closest('.x-accordion_header'),xProAccordionConfig(e.target.closest(".x-accordion")))
    }

    const extrasAccordion = function ( container ) {

        container.querySelectorAll(".x-accordion").forEach((proAccordion) => {

            const config = xProAccordionConfig(proAccordion);
            const identifier = proAccordion.getAttribute('data-x-id')
            let loopIndex

            if ( config.hashLink ) {

                const proAccordionItems = proAccordion.childNodes;
                [...proAccordionItems].forEach((proAccordionItem,index) => {

                    if ( !proAccordionItem.id ) {

                        loopIndex = typeof config.loopIndex !== "undefined" ? config.loopIndex + '_' : '';

                        if ( proAccordion.parentElement && proAccordion.parentElement.closest('.x-accordion') ) {
                            if ('' === loopIndex) {
                                proAccordionItem.id = proAccordion.id + '-' + loopIndex + (index + 1);
                            } else {
                                if ( proAccordion.parentElement.closest('.x-accordion').parentElement.closest('.x-accordion') ) {
                                    proAccordionItem.id = proAccordion.parentElement.closest('.x-accordion').parentElement.closest('.x-accordion').id + '-' + loopIndex + (index + 1);
                                } else {
                                    proAccordionItem.id = proAccordion.parentElement.closest('.x-accordion').id + '-' + loopIndex + (index + 1);
                                }
                            }
                        } 

                        else {
                            proAccordionItem.id = proAccordion.id + '-' + loopIndex + (index + 1);
                        }
                    }

                })

                if(location.hash != null && location.hash != ""){

                    let depth = 0;
                    let delay = 0;

                    setTimeout(() => {
                        if ( document.querySelector(location.hash) ) {

                            let item = document.querySelector(location.hash);

                            if ( item.closest(".x-accordion").parentElement.closest(".x-accordion") ) {
                                depth = 1;
                                if ( item.closest(".x-accordion").parentElement.closest(".x-accordion").parentElement.closest(".x-accordion") ) {
                                    depth = 2;
                                }
                            }
                          
                            
                            if ( document.querySelector(location.hash).classList.contains('x-accordion_item') ) {

                                if ( document.querySelector(location.hash).querySelector('.x-accordion_header') ) {

                                    if (1 === depth) {
                                        xOpenAccordionItem(document.querySelector(location.hash).parentElement.closest('.x-accordion_item').querySelector('.x-accordion_header'),xProAccordionConfig(proAccordion)) 
                                        delay = 50
                                    }
                                    else if (2 === depth) {
                                        xOpenAccordionItem(document.querySelector(location.hash).parentElement.closest('.x-accordion_item').parentElement.closest('.x-accordion_item').querySelector('.x-accordion_header'),xProAccordionConfig(proAccordion)) 
                                        setTimeout(() => {
                                            xOpenAccordionItem(document.querySelector(location.hash).parentElement.closest('.x-accordion_item').querySelector('.x-accordion_header'),xProAccordionConfig(proAccordion))
                                        }, 50)    
                                        delay = 100
                                    }

                                    setTimeout(() => {
                                        xOpenAccordionItem(document.querySelector(location.hash).querySelector('.x-accordion_header'),xProAccordionConfig(proAccordion)) 
                                    }, delay)  
                                }

                            } else {
                                xOpenAccordionItem(document.querySelector(location.hash),xProAccordionConfig(proAccordion)) 
                            }
                            
                        }

                        setTimeout(() => {
                            if ( document.querySelector(location.hash) ) {
                                window.scrollTo({
                                    top: document.querySelector(location.hash).offsetTop - config.scrollOffset,
                                    left: 0,
                                    behavior: 'smooth'
                                });
                            }
                        }, 100)    

                    }, 50)      

                    
                }

                bricksQuerySelectorAll(container, [".x-accordion_link",".x-accordion_link > a"]).forEach((proAccordionLink) => {

                    const hashLink = proAccordionLink.getAttribute('href');

                    if (null != hashLink) {

                        proAccordionLink.addEventListener('click', function(e) {

                            if ( document.querySelector(hashLink) ) {
                                e.preventDefault()

                                window.scrollTo({
                                    top: proAccordion.querySelector(hashLink).offsetTop - config.scrollOffset,
                                    left: 0,
                                    behavior: 'smooth'
                                });
                                
                                setTimeout(() => {
                                    xOpenAccordionItem(document.querySelector(hashLink),xProAccordionConfig(proAccordion)) 
                                }, 50)  
                            }
                        })

                    }

                })
                
            }

            const proAccordionHeaders = [];
            proAccordion.querySelectorAll(".x-accordion_header").forEach(proAccordionHeader => {
                if ( proAccordion === proAccordionHeader.closest(".x-accordion") ) {
                    proAccordionHeaders.push(proAccordionHeader);
                }
            })

            
        
            proAccordionHeaders.forEach((proAccordionHeader,index) => {

                let proAccordionContent = null != proAccordionHeader.nextSibling ? proAccordionHeader.nextSibling : proAccordionHeader.closest('.x-accordion_item') ? proAccordionHeader.closest('.x-accordion_item').querySelector('.x-accordion_content') ? proAccordionHeader.closest('.x-accordion_item').querySelector('.x-accordion_content') : false : false;

                
                if (!proAccordionHeader.id || proAccordionHeader.id.startsWith('x-accordion_header_')) {
                    proAccordionHeader.id = 'x-accordion_header_' + identifier + '_' + index
                }

                if (proAccordionContent) {

                    if (!proAccordionContent.id || proAccordionContent.id.startsWith('x-accordion_content_')) {
                        proAccordionContent.id = 'x-accordion_content_' + identifier + '_' + index
                    }

                    if ( ( config.expandFirst && ( 0 === index ) || config.expandAll ) ) {
                        proAccordionHeader.setAttribute('aria-expanded', 'true')
                        proAccordionContent.xslideDown(0)
                        if (proAccordionHeader.closest('.x-accordion_item')) {
                            proAccordionHeader.closest('.x-accordion_item').classList.add('x-accordion_item-active')
                        }
                    } else {
                        proAccordionHeader.setAttribute('aria-expanded', 'false')
                    }

                    /* arias */
                    proAccordionHeader.setAttribute('aria-controls', proAccordionContent.id)
                    proAccordionContent.setAttribute('aria-labelledby', proAccordionHeader.id)
                    proAccordionContent.setAttribute('role', 'region')

                }

                proAccordionHeader.removeEventListener('click',toggleAccordionItem, true)
                proAccordionHeader.addEventListener('click',toggleAccordionItem, true) 
                
                

                if (proAccordionHeader.tagName != 'BUTTON') {
                    proAccordionHeader.addEventListener('keypress', function(e) {
                        if (e.code === "Enter" || e.code === "Space") {
                            e.preventDefault()
                            xToggleAccordionItem(proAccordionHeader,xProAccordionConfig(proAccordion))
                        } 
                    })
                }

                if ( config.navKeys ) {

                    proAccordionHeader.addEventListener("keydown", (e) => {

                        if ( e.key === "ArrowDown" ) {

                            if ( proAccordionHeaders.length - 1 === index ) {
                                e.preventDefault()
                                proAccordionHeaders[0].focus()
                            } else {
                                e.preventDefault()
                                proAccordionHeaders[index + 1].focus()
                            }

                        } else if ( e.key === "ArrowUp" ) {
                            
                                if (0 === index) {
                                    e.preventDefault()
                                    proAccordionHeaders[proAccordionHeaders.length - 1].focus()
                                } else {
                                    e.preventDefault()
                                    proAccordionHeaders[index - 1].focus()
                                }

                        } else if ( e.key === "Home" ) {
                            e.preventDefault()
                            proAccordionHeaders[0].focus()
                        } else if ( e.key === "End" ) {
                            e.preventDefault()
                            proAccordionHeaders[proAccordionHeaders.length - 1].focus()
                        }

                    })

                }

            })

            let accordionState = true

            function toggleDisableAccordion() {
                
                if ('true' === getComputedStyle(proAccordion).getPropertyValue('--x-disable-accordion').split(" ").join("")) {
                  if (accordionState) {

                    proAccordion.querySelectorAll('.x-accordion_item-active').forEach(activeItem => {
                        activeItem.classList.remove('x-accordion_item-active')
                    })

                    proAccordionHeaders.forEach((proAccordionHeader) => {

                        proAccordionHeader.removeAttribute('tabindex')
                        proAccordionHeader.removeAttribute('role')
                        proAccordionHeader.removeAttribute('aria-expanded')
                        if ( proAccordionHeader.hasAttribute('aria-controls') ) {
                            proAccordionHeader.setAttribute('data-controls', proAccordionHeader.getAttribute('aria-controls') )
                            proAccordionHeader.removeAttribute('aria-controls')
                        }
                        proAccordionHeader.removeEventListener('click',toggleAccordionItem, true)
                    })

                    proAccordion.querySelectorAll('.x-accordion_content').forEach(proAccordionContent => {
                        proAccordionContent.style.removeProperty('display')
                        proAccordionContent.removeAttribute('role')
                        if ( proAccordionContent.hasAttribute('aria-labelledby') ) {
                            proAccordionContent.setAttribute('data-labelledby', proAccordionContent.getAttribute('aria-labelledby') )
                            proAccordionContent.removeAttribute('aria-labelledby')
                        }
                    })

                    proAccordion.dispatchEvent(new Event('x_accordion_item:destroy'))
                    accordionState = false;
                  }
                  
                }
                else {
                    
                    proAccordionHeaders.forEach((proAccordionHeader,index) => {
                        proAccordionHeader.setAttribute('tabindex', '0')
                    })

                    if (!accordionState) {
                        proAccordionHeaders.forEach((proAccordionHeader,index) => {
                            proAccordionHeader.setAttribute('tabindex', '0')
                            proAccordionHeader.setAttribute('role','button')
                            if ( proAccordionHeader.hasAttribute('data-controls') ) {
                                proAccordionHeader.setAttribute('aria-controls', proAccordionHeader.getAttribute('data-controls') )
                                proAccordionHeader.removeAttribute('data-controls')
                            }
                           
                            if ( ( config.expandFirst && ( 0 === index ) || config.expandAll ) ) {
                                let proAccordionContent = null != proAccordionHeader.nextSibling ? proAccordionHeader.nextSibling : proAccordionHeader.closest('.x-accordion_item') ? proAccordionHeader.closest('.x-accordion_item').querySelector('.x-accordion_content') ? proAccordionHeader.closest('.x-accordion_item').querySelector('.x-accordion_content') : false : false;
                                if (proAccordionContent) {
                                    proAccordionContent.xslideDown(0)
                                }
                                proAccordionHeader.setAttribute('aria-expanded', 'true')
                                if (proAccordionHeader.closest('.x-accordion_item')) {
                                    proAccordionHeader.closest('.x-accordion_item').classList.add('x-accordion_item-active')
                                }
                               
                            } else {
                                proAccordionHeader.setAttribute('aria-expanded', 'false')
                            }

                            proAccordionHeader.removeEventListener('click',toggleAccordionItem, true)
                            proAccordionHeader.addEventListener('click',toggleAccordionItem, true)
                        })

                        proAccordion.querySelectorAll('.x-accordion_content').forEach(proAccordionContent => {
                            proAccordionContent.setAttribute('role','region')
                            if ( proAccordionContent.hasAttribute('data-labelledby') ) {
                                proAccordionContent.setAttribute('aria-labelledby', proAccordionContent.getAttribute('data-labelledby') )
                                proAccordionContent.removeAttribute('data-labelledby')
                            }
                        })

                        proAccordion.dispatchEvent(new Event('x_accordion_item:reinit'))
                        accordionState = true;
                    }

                }
            
              }
            
              if (config.conditional) {
              
                toggleDisableAccordion()
                window.addEventListener('resize', debounce(() => {
                    toggleDisableAccordion()
                }, 0))

            }
            

        })

       

    }

    extrasAccordion(document);

    function xAccordionAJAX(e) {

        if (typeof e.detail.queryId === 'undefined') {
            if ( typeof e.detail.popupElement === 'undefined' ) {
                return;
            } else {
                extrasAccordion( e.detail.popupElement );
            }
        }

        setTimeout(() => {

         if ( document.querySelector('.brxe-' + e.detail.queryId) ) {
            
            if ( document.querySelector('.brxe-' + e.detail.queryId).closest('.x-accordion') ) {
                    extrasAccordion( document.querySelector('.brxe-' + e.detail.queryId).closest('.x-accordion').parentElement );
            }
            else if ( document.querySelector('.brxe-' + e.detail.queryId).querySelector('.x-accordion') ) {
                    extrasAccordion( document.querySelector('.brxe-' + e.detail.queryId).parentElement );
            } 

         }

        }, 0);

         
    }
    
    document.addEventListener("bricks/ajax/load_page/completed", xAccordionAJAX)
    document.addEventListener("bricks/ajax/pagination/completed", xAccordionAJAX)
    document.addEventListener("bricks/ajax/popup/loaded", xAccordionAJAX)
    document.addEventListener("bricks/ajax/end", xAccordionAJAX)
    

    // Expose function
    window.doExtrasAccordion = extrasAccordion;


    if (typeof bricksextras !== 'undefined') {

        bricksextras.accordion = {
          open: (brxParam, number) => {
            let target = brxParam?.target || false
            let index = number ? ( parseInt(number) ) : 0
            if ( target && target.querySelector('.x-accordion_item') && target.querySelectorAll('.x-accordion_item')[index].querySelector('.x-accordion_header') ) {
                if ( 'false' === target.querySelectorAll('.x-accordion_item')[index].querySelector('.x-accordion_header').getAttribute('aria-expanded') ) {
                    target.querySelectorAll('.x-accordion_item')[index].querySelector('.x-accordion_header').click()
                }
            }
          },
          close: (brxParam, number) => {
            let target = brxParam?.target || false
            let index = number ? ( parseInt(number) - 1 ) : 0
            if ( target && target.querySelector('.x-accordion_item') && target.querySelectorAll('.x-accordion_item')[index].querySelector('.x-accordion_header') ) {
                if ( 'false' !== target.querySelectorAll('.x-accordion_item')[index].querySelector('.x-accordion_header').getAttribute('aria-expanded') ) {
                    target.querySelectorAll('.x-accordion_item')[index].querySelector('.x-accordion_header').click()
                }
            }
          },
          toggle: (brxParam, number) => {
            let target = brxParam?.target || false
            let index = number ? ( parseInt(number) - 1 ) : 0
            if ( target && target.querySelector('.x-accordion_item') && target.querySelectorAll('.x-accordion_item')[index].querySelector('.x-accordion_header') ) {
                target.querySelectorAll('.x-accordion_item')[index].querySelector('.x-accordion_header').click()
            }
          },
          closeall: (brxParam) => {
            let target = brxParam?.target || false
            if ( target && target.querySelector('.x-accordion_item-active') ) {
                target.querySelectorAll('.x-accordion_item-active').forEach(activeItem => {
                    activeItem.classList.remove('x-accordion_item-active')
                    if ( activeItem.querySelector('.x-accordion_header') ) {
                        activeItem.querySelector('.x-accordion_header').setAttribute('aria-expanded', 'false')   
                    }
                    if ( activeItem.querySelector('.x-accordion_content') ) {
                        activeItem.querySelector('.x-accordion_content').style.display = 'none';
                    }
                    
                })
            }
          },
          openall: (brxParam) => {
            let target = brxParam?.target || false
            if ( target && target.querySelector('.x-accordion_item') ) {
                target.querySelectorAll('.x-accordion_item').forEach(item => {
                    item.classList.add('x-accordion_item-active')
                    if ( item.querySelector('.x-accordion_header') ) {
                        item.querySelector('.x-accordion_header').setAttribute('aria-expanded', 'true')   
                    }
                    if ( item.querySelector('.x-accordion_content') ) {
                        item.querySelector('.x-accordion_content').style.display = 'flex';
                    }
                })
            }
          },
        }
      
      }

}

function xOpenAccordionItem(proAccordionHeader, config) {
    
    proAccordionHeader.setAttribute('aria-expanded', 'true')

    let proAccordionContent = null != proAccordionHeader.nextSibling ? proAccordionHeader.nextSibling : proAccordionHeader.closest('.x-accordion_item') ? proAccordionHeader.closest('.x-accordion_item').querySelector('.x-accordion_content') ? proAccordionHeader.closest('.x-accordion_item').querySelector('.x-accordion_content') : false : false;
    
    if ( proAccordionContent ) {
         proAccordionContent.xslideDown(config.duration)
    }

    if (proAccordionHeader.closest('.x-accordion_item')) {
        proAccordionHeader.closest('.x-accordion_item').classList.add('x-accordion_item-active')
    }

    if (!config.closeSibling) {

        const parentItem = proAccordionHeader.closest('.x-accordion_item');

        if ( parentItem ) {

            // Get the sibling items (excluding the nested ones)
            const siblingItems = Array.from(parentItem.parentElement.children).filter(item => 
                item !== parentItem && item.classList.contains('x-accordion_item')
            );

            // Get the headers of the sibling items
            const siblingHeaders = siblingItems.map(item => 
                item.querySelector('.x-accordion_header')
            );
            
                siblingHeaders.forEach((siblingAccordionHeader,index) => {

                let siblingAccordionContent = null != siblingAccordionHeader.nextSibling ? siblingAccordionHeader.nextSibling : siblingAccordionHeader.closest('.x-accordion_item').querySelector('.x-accordion_content')
                if (siblingAccordionHeader != proAccordionHeader ) {
                    siblingAccordionHeader.setAttribute('aria-expanded', 'false')
                    siblingAccordionContent.xslideUp(config.duration)
                    if (siblingAccordionHeader.closest('.x-accordion_item')) {
                        siblingAccordionHeader.closest('.x-accordion_item').classList.remove('x-accordion_item-active')
                    }
                }
            })

        }
    }
}

function xToggleAccordionItem(proAccordionHeader, config) {

    if (!config.closeSibling) {

        const parentItem = proAccordionHeader.closest('.x-accordion_item');

        if ( parentItem ) {

            // Get the sibling items (excluding the nested ones)
            const siblingItems = Array.from(parentItem.parentElement.children).filter(item => 
                item !== parentItem && item.classList.contains('x-accordion_item')
            );

            // Get the headers of the sibling items
            const siblingHeaders = siblingItems.map(item => 
                item.querySelector('.x-accordion_header')
            );

                siblingHeaders.forEach((siblingAccordionHeader,index) => {

                let siblingAccordionContent = null != siblingAccordionHeader.nextSibling ? siblingAccordionHeader.nextSibling : siblingAccordionHeader.closest('.x-accordion_item').querySelector('.x-accordion_content')
                if (siblingAccordionHeader != proAccordionHeader ) {
                    siblingAccordionHeader.setAttribute('aria-expanded', 'false')
                    siblingAccordionContent.xslideUp(config.duration)
                    if (siblingAccordionHeader.closest('.x-accordion_item')) {
                        siblingAccordionHeader.closest('.x-accordion_item').classList.remove('x-accordion_item-active')
                    }
                }
            })

        }

    }

    let proAccordionContent = null != proAccordionHeader.nextSibling ? proAccordionHeader.nextSibling : proAccordionHeader.closest('.x-accordion_item') ? proAccordionHeader.closest('.x-accordion_item').querySelector('.x-accordion_content') ? proAccordionHeader.closest('.x-accordion_item').querySelector('.x-accordion_content') : false : false;

    let proAccordionEl = proAccordionHeader.closest('.x-accordion');

    proAccordionEl.dispatchEvent(new Event('x_accordion:expand'))

    if ( proAccordionHeader.closest('.x-accordion_item') && proAccordionHeader.closest('.x-accordion_item').parentElement ) {

        let itemIndex = [...proAccordionHeader.closest('.x-accordion_item').parentElement.children].indexOf(proAccordionHeader.closest('.x-accordion_item'))

        if ( 'false' === proAccordionHeader.getAttribute('aria-expanded') ) {
            proAccordionEl.dispatchEvent(new Event('x_accordion:expand_' + itemIndex))
        }

    }

    if ( proAccordionContent ) {
        if ( 'true' !== proAccordionHeader.getAttribute('aria-expanded') ) {
            proAccordionHeader.setAttribute('aria-expanded', 'true')
            proAccordionContent.xslideDown(config.duration)
            window.dispatchEvent(new Event('resize'))
            if ( proAccordionContent.querySelector('.x-read-more_content') ) {
                proAccordionContent.querySelector('.x-read-more_content').style.maxHeight = "";
                setTimeout(() => {
                        doExtrasReadmore(proAccordionContent)
                        window.dispatchEvent(new Event('resize'))
                }, 0)
            }
            if (proAccordionHeader.closest('.x-accordion_item')) {
                proAccordionHeader.closest('.x-accordion_item').classList.add('x-accordion_item-active')
            }
            proAccordionHeader.dispatchEvent(new Event('x_accordion_item:expand'))
        } else {
            proAccordionHeader.setAttribute('aria-expanded', 'false')
            proAccordionContent.xslideUp(config.duration)
            if (proAccordionHeader.closest('.x-accordion_item')) {
                proAccordionHeader.closest('.x-accordion_item').classList.remove('x-accordion_item-active')
            }
            proAccordionHeader.dispatchEvent(new Event('x_accordion_item:collapse'))
        }
    }


}



function xProAccordionConfig(element) {
    const configAttr = element.getAttribute('data-x-accordion')
    return configAttr ? JSON.parse(configAttr) : {}
}

document.addEventListener("DOMContentLoaded",function(e){
    bricksIsFrontend&&xProAccordion()
});