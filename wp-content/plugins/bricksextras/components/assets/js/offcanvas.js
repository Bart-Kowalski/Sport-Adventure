function xOffCanvas(offcanvas, elementConfig) {

  if ( window.xProOffCanvas.Instances[offcanvas.dataset.xId] == offcanvas ) {
    return
  }

   const offcanvasInner = offcanvas.querySelector(".x-offcanvas_inner")
   const offcanvasID = offcanvas.id


   let insideLoop = false;
   let loopContainer;

   let previousFocus;

    if ( null != elementConfig.isLooping ) {
       loopContainer = offcanvas.closest('.brxe-' + elementConfig.isLooping)
    } else {
      loopContainer = document
    }

   offcanvasInner.querySelectorAll('img[loading=lazy]').forEach((lazyImage) => {
    lazyImage.setAttribute('loading','auto')
   })

    setTimeout(() => {
      offcanvasInner.classList.add("x-offcanvas_ready")
    }, "300")

    if ('false' != elementConfig.autoAriaControl && '' !== elementConfig.clickTrigger) {
      loopContainer.querySelectorAll(elementConfig.clickTrigger).forEach((clickTrigger) => {
        clickTrigger.setAttribute('aria-controls', offcanvasInner.id)
        clickTrigger.setAttribute('aria-expanded', 'false')
      });
    }

    function toggleOffcanvas(previousFocus) {
      if ('true' == offcanvas.querySelector(".x-offcanvas_inner").getAttribute('aria-hidden')) {
        xOpenOffCanvas(offcanvasID)
      } else {
        xCloseOffCanvas(offcanvasID,previousFocus)
      }
    }

    if ('false' != elementConfig.escClose) {

      document.addEventListener('keydown', function(e) {
        if((e.key === "Escape" || e.key === "Esc")){
          xCloseOffCanvas(offcanvasID,previousFocus)
        }
      });

    }

    if ('true' == elementConfig.preventScroll) {

      offcanvas.addEventListener('x_offcanvas:open', () => {
        document.documentElement.classList.add("x-offcanvas_prevent-scroll_" + offcanvas.id)
        if (typeof lenis !== 'undefined') {
          lenis.stop()
        }
      })
      offcanvas.addEventListener('x_offcanvas:close', () => {
        document.documentElement.classList.remove("x-offcanvas_prevent-scroll_" + offcanvas.id)
        if (typeof lenis !== 'undefined') {
          lenis.start()
        }
      })

    }

    if ('false' != elementConfig.backdropClose && offcanvas.querySelector(".x-offcanvas_backdrop") ) {
      offcanvas.querySelector(".x-offcanvas_backdrop").addEventListener('click', () => {
        xCloseOffCanvas(offcanvasID,previousFocus)
        });
    }
     
    if ( '' !== elementConfig.clickTrigger ) {
     loopContainer.querySelectorAll(elementConfig.clickTrigger).forEach((clickTrigger) => {
          
          clickTrigger.addEventListener('click', () => {

            if ( clickTrigger.classList.contains('brxe-xlottie') ) {
              if ( 'true' === clickTrigger.getAttribute('aria-expanded' ) ) {
                clickTrigger.setAttribute('aria-expanded', 'false')
              } else {
                clickTrigger.setAttribute('aria-expanded', 'true')
              }
            }

            toggleOffcanvas(previousFocus)

            if ('true' === elementConfig.syncBurgers) {

              loopContainer.querySelectorAll(elementConfig.clickTrigger).forEach((otherTrigger) => {

                if (otherTrigger !== clickTrigger) {

                  if ('true' != clickTrigger.getAttribute('aria-expanded')) {
                    otherTrigger.setAttribute('aria-expanded', 'false')
                    if ( otherTrigger.querySelector(".x-hamburger-box") ) {
                          otherTrigger.querySelector(".x-hamburger-box").classList.remove("is-active")
                    }
                  } else {
                    otherTrigger.setAttribute('aria-expanded', 'true')
                    if ( otherTrigger.querySelector(".x-hamburger-box") ) {
                      otherTrigger.querySelector(".x-hamburger-box").classList.add("is-active")
                    }
                  }
                  
                }

              });
            }

              setTimeout(() => {
                  if ( 'false' !== clickTrigger.getAttribute('aria-expanded' ) ) {
                      previousFocus = clickTrigger;
                  }
              }, 50)

          });
        });
      }

        if ( 'true' !== elementConfig.disableHashlink ) {

          offcanvasInner.querySelectorAll('a[href*=\\#]').forEach(hashLink => {

            if ( ! hashLink.parentElement.classList.contains('menu-item-has-children') ) {
             
              hashLink.addEventListener('click', e => {
                xCloseOffCanvas(offcanvasID,previousFocus)
              })

            }
            
          })

      }

      offcanvas.addEventListener('x_offcanvas:open', () => {
          if ( '' !== elementConfig.clickTrigger ) {
              loopContainer.querySelectorAll(elementConfig.clickTrigger).forEach((clickTrigger) => {
                  if (clickTrigger && clickTrigger.hasAttribute('aria-expanded') && 'false' === clickTrigger.getAttribute('aria-expanded')) {
                      clickTrigger.setAttribute('aria-expanded', 'true')
                  }
              })
          }
      })
      offcanvas.addEventListener('x_offcanvas:close', () => {
          if ( '' !== elementConfig.clickTrigger ) {
              loopContainer.querySelectorAll(elementConfig.clickTrigger).forEach((clickTrigger) => {
                  if (clickTrigger && clickTrigger.hasAttribute('aria-expanded') && 'true' === clickTrigger.getAttribute('aria-expanded') ) {
                      clickTrigger.setAttribute('aria-expanded', 'false')
                  }
              })
          }
      })

      window.xProOffCanvas.Instances[offcanvas.dataset.xId] = offcanvas;
      

}

function xCloseOffCanvas(elementID, previousFocus = null) {
  if ( document.getElementById(elementID).querySelector(".x-offcanvas_inner").hasAttribute('inert' ) ) { return }
  document.getElementById(elementID).querySelector(".x-offcanvas_inner").setAttribute('inert', '')
  document.getElementById(elementID).dispatchEvent(new Event('x_offcanvas:close'))
  xOffCanvasCloseBurger(elementID)
  xOffCanvasCloseOther(elementID)
  if (xOffCanvasConfig(elementID).clickTrigger) {
    document.querySelectorAll(xOffCanvasConfig(elementID).clickTrigger).forEach((clickTrigger) => {
      if ( clickTrigger.classList.contains('brxe-xlottie') ) {
        if ( 'true' === clickTrigger.getAttribute('aria-expanded' ) ) {
          clickTrigger.click()
        }
      }
    });
  }

  document.getElementById(elementID).querySelectorAll( 'iframe:not(media-provider iframe)').forEach(iframe => {
    iframe.src = iframe.src;
  });

  document.getElementById(elementID).querySelectorAll('media-player').forEach(player => {  
    player.pause();
  });

  document.getElementById(elementID).querySelectorAll( 'video:not(media-provider video)').forEach(video => {  
    video.pause();
  });

  document.getElementById(elementID).querySelectorAll('form').forEach(form => {  
    form.reset();
  });

  if (null != previousFocus && xOffCanvasConfig(elementID).returnFocus ) { 
    previousFocus.focus()
  }

}

function xOpenOffCanvas(elementID) {
  document.getElementById(elementID).querySelector(".x-offcanvas_inner").removeAttribute('inert')
  xOffCanvasMoveFocus(elementID)
  document.getElementById(elementID).dispatchEvent(new Event('x_offcanvas:open'))  
  
}

function xToggleOffCanvas(elementID) {
  if ( document.getElementById(elementID).querySelector(".x-offcanvas_inner").hasAttribute('inert' ) ) {
    xOpenOffCanvas(elementID)
  } else {
    xCloseOffCanvas(elementID)
  }
}

function xOffCanvasCloseBurger(elementID) {
  if (xOffCanvasConfig(elementID).clickTrigger) {
    document.querySelectorAll(xOffCanvasConfig(elementID).clickTrigger).forEach((clickTrigger) => {
      if ( clickTrigger.classList.contains('brxe-xburgertrigger') ) {
        
        setTimeout(() => {
          clickTrigger.setAttribute('aria-expanded', 'false')
          if ( clickTrigger.querySelector(".x-hamburger-box") ) {
            clickTrigger.querySelector(".x-hamburger-box").classList.remove("is-active")
          }
        }, 5)
        
      }
    });
  }
}

function xOffCanvasCloseOther(elementID) {
  if (null != xOffCanvasConfig(elementID).secondClose) {
    if ( document.querySelector(xOffCanvasConfig(elementID).secondClose) ) {
      document.querySelector(xOffCanvasConfig(elementID).secondClose).querySelector(".x-offcanvas_inner").setAttribute('inert', '')
    }
  }
}

function xOffCanvasMoveFocus(elementID) {
    setTimeout(function() {
    if ( null == xOffCanvasConfig(elementID).focus ) {
      document.getElementById(elementID).querySelector(".x-offcanvas_inner").focus();
    } else {
      document.getElementById(elementID).querySelector(".x-offcanvas_inner").querySelector(xOffCanvasConfig(elementID).focus).focus();
    }
  }, 0);
}

function xOffCanvasConfig(elementID, extraData = {}) {
  const element = document.getElementById(elementID)
  const configAttr = element.getAttribute('data-x-offcanvas')
  const elementConfig = configAttr ? JSON.parse(configAttr) : {}

  return elementConfig

}
    
document.addEventListener("DOMContentLoaded",function(e){

  if (!bricksIsFrontend) {
    return;
  }

  if ( document.querySelector('body > .brx-body.iframe') ) {
    return
 }

  const extrasOffCanvas = function ( container ) {

    const offcanvases = container.querySelectorAll(".x-offcanvas")
      
    offcanvases.forEach(offcanvas => {

      if ( '' === offcanvas.id ) {
        offcanvas.setAttribute('id','x-offcanvas_' + offcanvas.getAttribute('data-x-id'))
      }

      xOffCanvas(offcanvas, xOffCanvasConfig(offcanvas.id))

        offcanvas.addEventListener("x_offcanvas:open", () => {

           /* force readmore to resize */
           if (offcanvas.querySelector('.brxe-xreadmoreless')) {

            offcanvas.querySelectorAll('.brxe-xreadmoreless').forEach(readMore => {
                
                if ( readMore.classList.contains('x-read-more_ready') ) { return; }

                readMore.style.opacity = 0;

                readMore.querySelector('.x-read-more_content').style.removeProperty('height')
                readMore.querySelector('.x-read-more_content').style.removeProperty('max-height')
                readMore.querySelector('.x-read-more_content').classList.remove('x-read-more_not-collapsable')

                setTimeout(function() {
                    if (readMore.hasAttribute('data-x-fade')) {
                        readMore.classList.add('x-read-more_fade');
                    }
                    window.dispatchEvent(new Event('resize'))

                    doExtrasReadmore(offcanvas)
                    readMore.style.opacity = 1;
                    readMore.classList.add('x-read-more_ready');

                }, 100)

            })

          }

          if ('false' != xOffCanvasConfig(offcanvas.id).trapFocus) {

              const keyboardfocusableElements = offcanvas.querySelectorAll(
                'a[href], button, input, textarea, select, details, [tabindex]'
              );

              if (keyboardfocusableElements.length) {

                keyboardfocusableElements[keyboardfocusableElements.length - 1].addEventListener('keydown', (e) => {

                    if (!e.shiftKey && e.key === 'Tab') {
                        e.preventDefault()
                        keyboardfocusableElements[0].focus()
                    }

                })

                keyboardfocusableElements[0].addEventListener('keydown', (e) => {

                    if (e.shiftKey && e.key === 'Tab') {
                        e.preventDefault()
                        keyboardfocusableElements[keyboardfocusableElements.length - 1].focus()
                    }

                })

            } else {
                offcanvas.querySelector('.x-offcanvas_inner').addEventListener('keydown', (e) => {

                    if (e.key === 'Tab') {

                        if ( xOffCanvasConfig(offcanvas.id).returnFocus && offcanvas.querySelector('.x-offcanvas_backdrop') ) {
                            e.preventDefault()
                            offcanvas.querySelector('.x-offcanvas_backdrop').click()
                        }
                        
                    }

                })
            }

          }

        })

   

    })

  }

  extrasOffCanvas(document);

  function xOffCanvasAJAX(e) {

    if (typeof e.detail.queryId === 'undefined') {
      if ( typeof e.detail.popupElement === 'undefined' ) {
        return;
      } else {
        extrasOffCanvas( e.detail.popupElement )
      }
    }

    setTimeout(() => {
      if ( document.querySelector('.brxe-' + e.detail.queryId) ) {
        extrasOffCanvas(document.querySelector('.brxe-' + e.detail.queryId).parentElement, true);
      }
    }, 0);
  }

  document.addEventListener("bricks/ajax/load_page/completed", xOffCanvasAJAX)
  document.addEventListener("bricks/ajax/pagination/completed", xOffCanvasAJAX)
  document.addEventListener("bricks/ajax/popup/loaded", xOffCanvasAJAX)
  document.addEventListener("bricks/ajax/end", xOffCanvasAJAX)

  // Expose function
  window.doExtrasOffCanvas = extrasOffCanvas;

  // Expose function
  window.xOpenOffCanvas = xOpenOffCanvas;
  window.xCloseOffCanvas = xCloseOffCanvas;
  window.xToggleOffCanvas = xToggleOffCanvas;

  if (typeof bricksextras !== 'undefined') {

    bricksextras.offcanvas = {
      close: (brxParam) => {
        let target = brxParam?.target || false
        if ( target ) {
          xCloseOffCanvas(target.id)
        }
      },
      open: (brxParam) => {
        let target = brxParam?.target || false
        if ( target ) {
          xOpenOffCanvas(target.id)
        }
      },
      toggle: (brxParam) => {
        let target = brxParam?.target || false
        if ( target ) {
          xToggleOffCanvas(target.id)
        }
      }
    }
  
  }


});