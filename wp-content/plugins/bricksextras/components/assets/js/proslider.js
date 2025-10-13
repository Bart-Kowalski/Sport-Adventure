function xProSlider() {

  const xIsIphone = () => {
    if (typeof window === `undefined` || typeof navigator === `undefined`) return false;
    return /iPhone/i.test(navigator.userAgent || navigator.vendor || (window.opera && opera.toString() === `[object Opera]`));
  };

  /* for the builder */
  if ( document.querySelector('body > .brx-body.iframe') && null == document.querySelector('body').getAttribute('data-x-proslider-init' ) ) {

        function removeClassesByPrefix(el, prefix) {
          if (el) {
            for(var i = el.classList.length - 1; i >= 0; i--) {
                if(el.classList[i].startsWith(prefix)) {
                    el.classList.remove(el.classList[i]);
                }
            }
          }
      }

      function onDirectionChange() {

          bricksQuerySelectorAll(document, "component.x-slider").forEach(function (slider) {

            if (slider) {
        
              let sliderBuilder = slider.querySelector('.x-slider_builder')
          
              if (!sliderBuilder) { return }
              
              let direction = getComputedStyle(sliderBuilder).getPropertyValue('--xslidedirection')
          
              removeClassesByPrefix(sliderBuilder,'splide--')
              sliderBuilder.classList.add('splide--' + direction.split(" ").join(""))
              
              if ( sliderBuilder.querySelector('.splide__pagination') ) {
                removeClassesByPrefix(sliderBuilder.querySelector('.splide__pagination'),'splide__pagination--')
                sliderBuilder.querySelector('.splide__pagination').classList.add('splide__pagination--' + direction.split(" ").join(""))
              }
          
              if ( sliderBuilder.querySelector('.splide__arrows') ) {
                removeClassesByPrefix(sliderBuilder.querySelector('.splide__arrows'),'splide__arrows--')
                sliderBuilder.querySelector('.splide__arrows').classList.add('splide__arrows--' + direction.split(" ").join(""))
              }

              if ( sliderBuilder.querySelector('.x-slider_slide:first-of-type') ) {
                sliderBuilder.querySelector('.x-slider_slide:first-of-type').classList.add('is-active')
              }
              
            }

        })
        
      }

      document.body.addEventListener('x_resize_throttled', onDirectionChange)
      document.body.addEventListener('x_style_setting_changed', onDirectionChange)
      onDirectionChange()

      //return
      
    }

    /* front end */

    const extrasSlider = function ( container ) {

    bricksQuerySelectorAll(container, ".x-slider:not(component)").forEach(function (slider,i) {
      
      const configAttr = slider.getAttribute('data-x-slider')
      const sliderConfig = configAttr ? JSON.parse(configAttr) : {}

      // if no ID, force an ID.
      if (!slider.id) {
        slider.id = 'brxe-' + slider.getAttribute('data-x-id');
      }

      const customSlideWrappers = bricksQuerySelectorAll(slider, [
        ".splide__list > .brxe-code:not(.splide__list)", 
      ]);

      customSlideWrappers.forEach(function (wrapper) {

        if ( null != wrapper ) {

          var docFrag = container.createDocumentFragment();
          while (wrapper.firstChild) {
              var child = wrapper.removeChild(wrapper.firstChild);
              docFrag.appendChild(child);
          }

          wrapper.parentNode.replaceChild(docFrag, wrapper);

        }

      });
      
       const prosliderSlides = bricksQuerySelectorAll(slider, [
          ".splide__list > .brxe-container", 
          ".splide__list > .brxe-block",
          ".splide__list > .brxe-div",
        ]);

        prosliderSlides.forEach(function (slide) {
          slide.classList.add("splide__slide"), 
          slide.dataset.id = slide.id
        });

        let finalConfig = sliderConfig.rawConfig;
        let useScrollConfig = {
          useScroll: true
        }

        /* fix for splide issue on some iphones */
        if ( xIsIphone() && 'fade' !== sliderConfig.rawConfig.type ) {
          
          finalConfig = {
            ...sliderConfig.rawConfig,
            ...useScrollConfig
          };
        
        }

      if ( slider.querySelector('.splide__slide') ) {

        const xSplideInstance = new Splide(slider,finalConfig);

        xSplideInstance.on( 'ready', function () {

          /* ensure lazy images visible after ready */
          if (typeof bricksLazyLoad == 'function') {
            bricksLazyLoad();
          }
  

            setTimeout(() => {
              window.dispatchEvent(new Event('resize'));
          }, 200)


          if ( sliderConfig.syncSelector && sliderConfig.isIndepententNav ) {

            xSplideInstance.on( 'click', function (Slide) {

                    if ( container.querySelector(sliderConfig.syncSelector) ) {

                      let otherSliders = container.querySelectorAll(sliderConfig.syncSelector)
                      
                      otherSliders.forEach(otherSlider => {

                       let otherSliderInstance = xSlider.Instances[otherSlider.getAttribute('data-x-id')]
                            xSplideInstance.go( Slide.index )
                            if ( Slide.isClone ) {
                              let getslide = otherSliderInstance.Components.Slides.getAt( Slide.index );
                              if (getslide == null){
                                otherSliderInstance.go( Slide.slideIndex );
                              } else {
                                otherSliderInstance.go( Slide.index );
                              }
                            } else {
                              otherSliderInstance.go( Slide.index );
                            }
                     

                      })

                  }

            })
          }

        })

     
      // controls
      container.querySelectorAll( '.x-slider-control:not(component)' ).forEach(function (sliderControl) {

        const sliderControlConfig = sliderControl.getAttribute('data-x-slider-control')
        const controlConfig = sliderControlConfig ? JSON.parse(sliderControlConfig) : {}

        if ( Object.keys(controlConfig).length === 0 ) {
          return
        }

        let sliderToControl

        if ( false === controlConfig.slider ) {
          if ( null != controlConfig.isLooping ) {

            let loopingID = controlConfig.isLooping
            let loopingElement = sliderControl.closest('.brxe-' + loopingID)

            if ( loopingElement && loopingElement.querySelector('.x-slider') ) {
              sliderToControl = '.x-slider[data-x-id="' + loopingElement.querySelector('.x-slider').getAttribute('data-x-id') + '"]';
            } else if ( sliderControl.closest('section') && sliderControl.closest('section').querySelector('.x-slider') ) {
              sliderToControl = '.x-slider[data-x-id="' + sliderControl.closest('section').querySelector('.x-slider').getAttribute('data-x-id') + '"]';
            } else if ( sliderControl.closest('.brxe-xdynamiclightbox') && sliderControl.closest('.brxe-xdynamiclightbox').querySelector('.x-slider') ) {
              sliderToControl = '.x-slider[data-x-id="' + sliderControl.closest('.brxe-xdynamiclightbox').querySelector('.x-slider').getAttribute('data-x-id') + '"]';
            }

          } else {
            if ( sliderControl.closest('section') && sliderControl.closest('section').querySelector('.x-slider') ) {
              sliderToControl = '.x-slider[data-x-id="' + sliderControl.closest('section').querySelector('.x-slider').getAttribute('data-x-id') + '"]';
            } else {
              if ( sliderControl.closest('.gcontainer') && sliderControl.closest('.gcontainer').querySelector('.x-slider') ) {
                sliderToControl = '.x-slider[data-x-id="' + sliderControl.closest('.gcontainer').querySelector('.x-slider').getAttribute('data-x-id') + '"]';
              } else {
                console.log('BricksExtras: No section element found for the pro slider control')
              }
              
            }
          }
          
        } else {
          sliderToControl = controlConfig.slider
        }

        if ( sliderToControl && container.querySelector( sliderToControl ) ) {

          if ( slider === container.querySelector( sliderToControl ) ) {

            if ( container.querySelector( sliderToControl ).hasAttribute('data-x-id') ) {
              sliderControl.setAttribute('data-x-control-for', container.querySelector( sliderToControl ).getAttribute('data-x-id') )
            }

            if ( 'progressBar' ===  controlConfig.type ) {

              xSplideInstance.on( 'mounted active overflow', function () {
                const end  = xSplideInstance.Components.Controller.getEnd() + 1;
                const rate = Math.min( ( xSplideInstance.index + 1 ) / end, 1 );
                sliderControl.querySelector( '.x-slider_progress-bar' ).style.width = String( 100 * rate ) + '%';

                let totalSlides  = xSplideInstance.Components.Controller ? xSplideInstance.Components.Controller.getEnd() + 1 : 1

                  sliderControl.querySelector( '.x-slider_progress').setAttribute('aria-valuemax', totalSlides)

                if ( sliderControl.querySelector( '.x-slider_progress' ) ) {
                  sliderControl.querySelector( '.x-slider_progress' ).setAttribute('aria-valuenow', xSplideInstance.index + 1)
                  sliderControl.querySelector( '.x-slider_progress').setAttribute('aria-valuetext', parseInt( xSplideInstance.index + 1 ) + ' of ' + totalSlides + ' slides');
                }
              } );

              if (controlConfig.progressBarClickable) {

                setTimeout(function () {

                  let totalSlides  = xSplideInstance.Components.Controller ? xSplideInstance.Components.Controller.getEnd() + 1 : 1

                  sliderControl.querySelector( '.x-slider_progress' ).addEventListener('keydown', function(e) {

                    if ( e.code === "Home" ) {
                      e.preventDefault()
                      xSplideInstance.go(0)
                    }

                    else if ( e.code === "End" ) {
                      e.preventDefault()
                      xSplideInstance.go(totalSlides - 1)
                    }

                    else if ( ( 'rtl' !== controlConfig.direction && e.key === 'ArrowRight' ) ||
                         ( 'rtl' === controlConfig.direction && e.key === 'ArrowLeft' ) ||
                         ( e.key === 'ArrowUp' ) ) {
                          e.preventDefault()
                         xSplideInstance.go('>')
                    }

                    else if ( ( 'rtl' !== controlConfig.direction && e.key === 'ArrowLeft' ) ||
                         ( 'rtl' === controlConfig.direction && e.key === 'ArrowRight' ) ||
                         ( e.key === 'ArrowDown' ) ) {
                          e.preventDefault()
                          xSplideInstance.go('<')
                    }

                  })

                  let percentProgress

                  sliderControl.querySelector( '.x-slider_progress').addEventListener("click", function(e) {

                    if ( e.target.classList.contains( 'x-slider_progress') ) {

                      if ( 'rtl' !== controlConfig.direction ) {
                        percentProgress = ( ( e.clientX - e.target.getBoundingClientRect().left ) ) / e.target.offsetWidth * 100
                      } else {
                        percentProgress = 100 - ( ( ( e.clientX - e.target.getBoundingClientRect().left ) ) / e.target.offsetWidth * 100 )
                      }

                      let totalSlides  = xSplideInstance.Components.Controller ? xSplideInstance.Components.Controller.getEnd() + 1 : 1

                      xSplideInstance.go( Math.round( ( percentProgress - (100/totalSlides/2) ) / (100 / totalSlides) ) )

                    }

                  })

                }, 100)  

              } 

            } 

            if ( 'counter' ===  controlConfig.type) {

              let currentSlideEl = sliderControl.querySelector('.x-slider_counter-index-number')
              let totalSlidesEl = sliderControl.querySelector('.x-slider_counter-total-number')
              
              xSplideInstance.on( 'mounted active overflow', function () {
                currentSlideEl.innerHTML = xSplideInstance.index + 1;
                totalSlidesEl.innerHTML = xSplideInstance.Components.Controller.getEnd() + 1
                sliderControl.querySelector('.x-slider_counter').style.opacity = 1
              })

            }

            if ( 'playPause' ===  controlConfig.type) {

              var toggleButton = sliderControl.querySelector( '.x-splide__toggle' );

              if ( toggleButton ) {

                if ( 'pause' !== sliderConfig.rawConfig.autoplay ) {
                  toggleButton.setAttribute('aria-label',controlConfig.pauseAriaLabel)
                  toggleButton.classList.add('is-active')
                }

                toggleButton.classList.add('x-splide__toggle_ready')

                setTimeout(function () {
                  toggleButton.setAttribute('aria-controls',slider.querySelector('.splide__track').id )
                }, 100)

              }

              setTimeout(function () {

              var Autoplay = xSplideInstance.Components.Autoplay;

              if ( toggleButton ) {

                if ( 'pause' !== sliderConfig.rawConfig.autoplay ) {
                  toggleButton.setAttribute('aria-label',controlConfig.pauseAriaLabel)
                  toggleButton.classList.add('is-active')
                }
                toggleButton.addEventListener( 'click', function (e) {
                  if ( toggleButton.classList.contains('is-active') ) {
                    Autoplay.pause();
                    toggleButton.classList.remove('is-active')
                    toggleButton.setAttribute('aria-label',controlConfig.playAriaLabel)
                    slider.setAttribute('aria-live','polite')
                  } else {
                    Autoplay.play();
                    toggleButton.setAttribute('aria-label',controlConfig.pauseAriaLabel)
                    toggleButton.classList.add('is-active')
                  }

                } );
              }

            }, 100)

          }

          if ( 'navArrow' ===  controlConfig.type) {

            setTimeout(function () {

              sliderControl.querySelector( '.x-slider-control_nav' ).setAttribute("aria-controls", slider.querySelector('.splide__track').id)

              const nextButton = sliderControl.querySelector( '.x-slider-control_nav--next' )
              const prevButton = sliderControl.querySelector( '.x-slider-control_nav--prev' )

              function disableArrows(prevButton,nextButton) {
                if ( prevButton ) {
                  if ( xSplideInstance.Components.Controller.getPrev() === -1 ) {
                      prevButton.setAttribute('disabled','')
                    } else {
                      prevButton.removeAttribute('disabled')
                    }
                }

                if ( nextButton ) {
                  if ( xSplideInstance.Components.Controller.getNext() === -1 ) {
                      nextButton.setAttribute('disabled','')
                    } else {
                      nextButton.removeAttribute('disabled')
                    }
                }
              }

              if ( xSplideInstance.state.is( Splide.STATES.IDLE ) ) {
                disableArrows(prevButton,nextButton)
              }

              xSplideInstance.on( 'active moved overflow', function () {
                disableArrows(prevButton,nextButton)
              })

              if ( nextButton ) {
                nextButton.addEventListener( 'click', function (e) {
                  xSplideInstance.go( '>' );
                })
              }

              if ( prevButton ) {
                prevButton.addEventListener( 'click', function (e) {
                  xSplideInstance.go( '<' );
                })
              }

            }, 100)

          }

            if ( 'autoplayProgress' ===  controlConfig.type) {

              let autoplayEnded = false;
              let dragging = false;

              xSplideInstance.on( 'move active', function (newIndex) {
                const lastIndex  = xSplideInstance.Components.Controller.getEnd();
                
                if ( lastIndex === newIndex ) {
                  if (!sliderConfig.rawConfig.rewind && 'loop' !== sliderConfig.rawConfig.type) {
                    autoplayEnded = true;
                    sliderControl.style.setProperty('--x-slider-autoplay', 1);
                  }
                }

              } );

              xSplideInstance.on( 'autoplay:playing', function ( rate ) {
                if (!autoplayEnded) {
                  sliderControl.style.setProperty('--x-slider-autoplay', rate);
                }
              } );

              xSplideInstance.on( 'move drag', function ( ) {
                 dragging = true; 
                 sliderControl.style.setProperty('--x-slider-autoplay', 1);
              } );

              
              xSplideInstance.on( 'moved', function ( ) {
                dragging = false; 
                if ( xSplideInstance.Components.Controller.getNext() === -1 ) {
                  autoplayEnded = true
                } else {
                  autoplayEnded = false
                }
             } );
             

            }

            if ( 'slideContent' ===  controlConfig.type) {

              xSplideInstance.on( 'move active', function (newIndex) {

                let currentSlide =  xSplideInstance.Components.Slides.getAt( typeof( newIndex ) == 'number' ? newIndex : xSplideInstance.index ).slide;
                
                if ('previous' === controlConfig.currentSlide) {
                  
                  if(typeof xSplideInstance.Components.Slides.getAt( typeof( newIndex ) == 'number' ? (newIndex - 1) : (xSplideInstance.index - 1) ) !== "undefined"){ 
                    currentSlide = xSplideInstance.Components.Slides.getAt( typeof( newIndex ) == 'number' ? (newIndex - 1) : (xSplideInstance.index - 1) ).slide;
                   } else {
                    currentSlide = false;
                   }
                  
                } else if ('next' === controlConfig.currentSlide) {
                 if(typeof xSplideInstance.Components.Slides.getAt( typeof( newIndex ) == 'number' ? (newIndex + 1) : (xSplideInstance.index + 1) ) !== "undefined"){ 
                    currentSlide = xSplideInstance.Components.Slides.getAt( typeof( newIndex ) == 'number' ? (newIndex + 1) : (xSplideInstance.index + 1) ).slide;
                   } else {
                    currentSlide = false;
                   }
                }

                if ('caption' === controlConfig.slideContentType) {
                  if ( !!currentSlide && currentSlide.hasAttribute('data-x-caption') ) {
                    sliderControl.style.opacity = '1'
                    sliderControl.style.visibility = 'visible'
                    sliderControl.querySelector('.x-slider-control_content').innerHTML = currentSlide.getAttribute('data-x-caption');
                    if (sliderControl.closest('.x-slider-control_nav')) {
                      sliderControl.closest('.x-slider-control_nav').style.removeProperty('visibility')
                    }
                  } else {
                    sliderControl.style.opacity = '0'
                    sliderControl.style.visibility = 'hidden'
                    sliderControl.querySelector('.x-slider-control_content').innerHTML = "\xA0";
                    if (sliderControl.closest('.x-slider-control_nav')) {
                      sliderControl.closest('.x-slider-control_nav').style.visibility = 'hidden'
                    }
                  }
                } else if ('attribute' === controlConfig.slideContentType) {
                  if ( !!currentSlide ) {
                    const slideAttribute = currentSlide.getAttribute(controlConfig.slideAttribute);  
                    if (sliderControl.closest('.x-slider-control_nav')) {
                      sliderControl.closest('.x-slider-control_nav').style.removeProperty('visibility')
                    }
                    if ( slideAttribute ) {
                      sliderControl.style.opacity = '1'
                      sliderControl.style.visibility = 'visible'
                      sliderControl.querySelector('.x-slider-control_content').innerHTML = slideAttribute;
                    } else {
                      sliderControl.style.opacity = '0'
                      sliderControl.style.visibility = 'hidden'
                      sliderControl.querySelector('.x-slider-control_content').innerHTML = "\xA0";
                    }
                  } else {
                    sliderControl.style.opacity = '0'
                    sliderControl.style.visibility = 'hidden'
                    sliderControl.querySelector('.x-slider-control_content').innerHTML = "\xA0";
                    if (sliderControl.closest('.x-slider-control_nav')) {
                      sliderControl.closest('.x-slider-control_nav').style.visibility = 'hidden'
                    }
                  }
                } else if ('custom' === controlConfig.slideContentType) {
                  if ( !!currentSlide ) {
                    
                    const contentElement = currentSlide.querySelector(controlConfig.slideContentSelector);  
                    if (sliderControl.closest('.x-slider-control_nav')) {
                      sliderControl.closest('.x-slider-control_nav').style.removeProperty('visibility')
                    }
                    if ( contentElement ) {
                      sliderControl.style.opacity = '1'
                      sliderControl.style.visibility = 'visible'
                      sliderControl.querySelector('.x-slider-control_content').innerHTML = contentElement.innerHTML;
                    } else {
                      sliderControl.style.opacity = '0'
                      sliderControl.style.visibility = 'hidden'
                      sliderControl.querySelector('.x-slider-control_content').innerHTML = "\xA0";
                    }
                  } else {
                    sliderControl.style.opacity = '0'
                    sliderControl.style.visibility = 'hidden'
                    sliderControl.querySelector('.x-slider-control_content').innerHTML = "\xA0";
                    if (sliderControl.closest('.x-slider-control_nav')) {
                      sliderControl.closest('.x-slider-control_nav').style.visibility = 'hidden'
                    }
                  }
                }
              })

            }

          }

        }

      })

      if ( null != sliderConfig.animationTrigger ) {

        xSplideInstance.on( 'ready', function (newIndex) { 

          bricksQuerySelectorAll(slider, ".x-slider_slide").forEach(function (slide) {

            bricksQuerySelectorAll(slide, ["[data-interactions]"]).forEach(function (animatedElement,i) {

            var interactionAnimationType
              const arr = JSON.parse(animatedElement.dataset.interactions);

              arr.forEach((interaction) => {
                if (interaction.trigger) {
                    if ( ("enterView" === interaction.trigger) && "startAnimation" === interaction.action ) {
                      if ( interaction.animationType ) {
                        interactionAnimationType = interaction.animationType
                      }
                    }
                  }
                })

                if ( interactionAnimationType ) {
                  animatedElement.setAttribute("data-x-interaction-animation",interactionAnimationType)
                }

                var animationType = animatedElement.dataset.xInteractionAnimation;

                if ( animationType && animatedElement.closest('.x-slider_slide').classList.contains(sliderConfig.animationTrigger) ) {
                 animatedElement.classList.add("brx-x-animate-".concat(animationType));
                }

            })
          
            bricksQuerySelectorAll(slide, [".brx-animated:not([data-interactions])"]).forEach(function (animatedElement,i) {

              if ( sliderConfig.animationStagger && !animatedElement.classList.contains('no-stagger') ) {
                const animationDelay = sliderConfig.animationStagger ? sliderConfig.animationDelay : 0;
                const animationDelayFirst = sliderConfig.animationStagger ? sliderConfig.animationDelayFirst : 0;
                if (0 === i) {
                  animatedElement.style.animationDelay = animationDelayFirst + 'ms'
                } else {
                  animatedElement.style.animationDelay = animationDelayFirst + (i * animationDelay) + 'ms'
                }
                
              }

              /* remove usual bricks animation cycle */
              var animationType = animatedElement.dataset.animation;
              if ( animationType ) {
                animatedElement.setAttribute("data-x-animation",animationType)
                animatedElement.classList.remove("brx-animate-".concat(animationType))
                animatedElement.removeAttribute("data-animation")
              }

              /* add it back in when splide says so */
              if ( slide.classList.contains(sliderConfig.animationTrigger) ) {

                setTimeout(function () {
                  var animationType = animatedElement.dataset.xAnimation;
                  animationType && (animatedElement.classList.add("brx-animate-".concat(animationType)));
                }, 100)

              }
              
            })

            bricksQuerySelectorAll(slide, ["[data-interactions]"]).forEach(function (animatedElement,i) {

              if ( sliderConfig.animationStagger && !animatedElement.classList.contains('no-stagger') ) {
                const animationDelay = sliderConfig.animationStagger ? sliderConfig.animationDelay : 0;
                const animationDelayFirst = sliderConfig.animationStagger ? sliderConfig.animationDelayFirst : 0;
                if (0 === i) {
                  animatedElement.style.animationDelay = animationDelayFirst + 'ms'
                } else {
                  animatedElement.style.animationDelay = animationDelayFirst + (i * animationDelay) + 'ms'
                }
                
              }

              /* remove usual bricks animation cycle */
              var animationType = animatedElement.dataset.animation;
              if ( animationType ) {
                animatedElement.setAttribute("data-x-animation",animationType)
                animatedElement.classList.remove("brx-animate-".concat(animationType))
                animatedElement.removeAttribute("data-animation")
              }

              /* add it back in when splide says so */
              if ( slide.classList.contains(sliderConfig.animationTrigger) ) {

                setTimeout(function () {
                  var animationType = animatedElement.dataset.xAnimation;
                  animationType && (animatedElement.classList.add("brx-animate-".concat(animationType)));
                }, 100)

              }
              
            })

          })

        })

      }

      if ( null != sliderConfig.hashNav ) {

        bricksQuerySelectorAll(slider, [
          ".splide__list > .brxe-container", 
          ".splide__list > .brxe-block", 
          ".splide__list > .brxe-div",
          ".x-splide__list > .splide__slide"
        ]).forEach(function (slide,i) {

          let splideHash = slider.id ? slider.id : slider.getAttribute('data-x-id');

          if ( null == slide.getAttribute( 'data-splide-hash') ) {
           slide.setAttribute( 'data-splide-hash', splideHash + '-' + (i + 1) )
          }
       })
      }

      xSplideInstance.on( 'intersection:out', function ( entry ) {
        setTimeout(() => {
          if (xSplideInstance.Components.AutoScroll) {
            xSplideInstance.Components.AutoScroll.pause()
          }
        }, 20);
      } );

      /* overflow (conditional slider) */

    
      if (sliderConfig.conditional) {

        xSplideInstance.on( 'overflow', function ( isOverflow ) {

          let arrowOption = sliderConfig.rawConfig.arrows !== false ? isOverflow : false

          let sliderIdentifier = slider.getAttribute('data-x-id')

            if ( 'loop' === sliderConfig.rawConfig.type ) {
              xSplideInstance.go( 0 )
              xSplideInstance.options = {
                arrows    : arrowOption,
                pagination: isOverflow ? sliderConfig.rawConfig.pagination : false,
                drag      : isOverflow,
                clones    : isOverflow ? undefined : 0,
              };
            } else {
              xSplideInstance.options = {
                arrows    : arrowOption,
                pagination: isOverflow ? sliderConfig.rawConfig.pagination : false,
                drag      : isOverflow,
              };
            }
    
            if ( isOverflow  ) {
              slider.classList.remove('x-no-slider')
              container.querySelectorAll( '.x-slider-control[data-x-control-for="' + sliderIdentifier + '"]' ).forEach(function (sliderControl) {
                  sliderControl.style.removeProperty('display')
              })
            } else {
              slider.classList.add('x-no-slider')
              container.querySelectorAll( '.x-slider-control[data-x-control-for="' + sliderIdentifier + '"]' ).forEach(function (sliderControl) {
                  sliderControl.style.display = 'none';
              })
              
            }

        })

      }


      /* if using list */
      if ( slider.querySelector('.splide__slide') && slider.querySelector('.splide__slide').tagName == 'LI') {
        xSplideInstance.on( 'mounted', function () {
          slider.querySelectorAll('.splide__slide').forEach(slide => {
            slide.setAttribute('role', 'presentation')
            slide.removeAttribute('aria-label')
          })
        })
      }
      


      if ( document.querySelector('body > .brx-body.iframe') ) {
        if ( null != sliderConfig.rawConfig.autoScroll || false != sliderConfig.hashNav ) {
         // if ( !slider.classList.contains('is-initialized') ) {
          if (slider.querySelector('.splide__pagination li')) {
            slider.querySelectorAll('.splide__pagination li').forEach(dot => {
              dot.remove()
            })
          }
            if(typeof window.splide !== "undefined") {
              xSplideInstance.mount( window.splide.Extensions );
            } else {
              xSplideInstance.mount();
            }
          //}
        } else {
          if ( !slider.classList.contains('is-initialized') || null != document.querySelector('body').getAttribute('data-x-proslider-init' ) ) {
            if (slider.querySelector('.splide__pagination li')) {
              slider.querySelectorAll('.splide__pagination li').forEach(dot => {
                dot.remove()
              })
            }
            xSplideInstance.mount();
          }
        }

      } else {

        let newExtentionsObject = {};

        if ( null != window.splide) {

          let customAutoScroll  = window.splide.Extensions.AutoScroll
          let customURLHash  = window.splide.Extensions.URLHash
          let customIntersection  = window.splide.Extensions.Intersection

          if ( null != sliderConfig.rawConfig.autoScroll ) {
            newExtentionsObject.AutoScroll = customAutoScroll
          }
          
          if ( null !=  sliderConfig.rawConfig.intersection ) {
            newExtentionsObject.Intersection = customIntersection
          } 
          
          if (false != sliderConfig.hashNav ) {
            newExtentionsObject.URLHash = customURLHash
          } 

        }

        if ( slider.querySelector('.splide__pagination') ) {
          slider.querySelector('.splide__pagination').innerHTML = "";
        }

        if ( null == sliderConfig.rawConfig.autoScroll && null == sliderConfig.rawConfig.intersection && false == sliderConfig.hashNav  ) {
          xSplideInstance.mount();
        } else {
          xSplideInstance.mount( newExtentionsObject );
        }


      }
      

      



      setTimeout(function () {
        if ( window.xSlider ) {
            window.xSlider.Instances[slider.dataset.xId] = xSplideInstance;
        }
          slider.dispatchEvent(new Event('x_slider:init'))
      }, 150)

      


      /* adaptive height */

      if ( null != sliderConfig.adaptiveHeight ) {

        xSplideInstance.on( 'active move resize', (newIndex) => {

          let slide = xSplideInstance.Components.Slides.getAt( typeof( newIndex ) == 'number' ? newIndex : xSplideInstance.index ).slide

          if ( 'true' === sliderConfig.adaptiveHeight ) {

            if ( getComputedStyle(slider.querySelector('.splide__list')).getPropertyValue('--xadaptiveheight').includes("flex-start") ) {
              slide.parentElement.parentElement.style.height = slide.offsetHeight + 'px';
              slide.parentElement.parentElement.style.maxHeight = slide.offsetHeight + 'px';
            } else {
              slide.parentElement.parentElement.style.removeProperty('height')
              slide.parentElement.parentElement.style.removeProperty('max-height')
            }

          } 

        })

      }

     
      /* sort out lazy loading */
      prosliderSlides.forEach(function (prosliderSlide, i) {

        if (prosliderSlide.dataset.id) {
          
          prosliderSlide.id = prosliderSlide.dataset.id;
  
          var prosliderSlidePagination = slider.querySelector(".splide__pagination");
  
          if (prosliderSlidePagination) {
            var prosliderSlidePaginationDot = prosliderSlidePagination.querySelector("li:nth-child(".concat(i + 1, ") .splide__pagination__page"));
  
            prosliderSlidePaginationDot && prosliderSlidePaginationDot.setAttribute("aria-controls", prosliderSlide.id);
          }

        }
  
        if (!prosliderSlide.classList.contains("bricks-lazy-hidden")) {
          var prosliderSlideStyles = prosliderSlide.getAttribute("style") || "";
  
          prosliderSlide.dataset.style && (prosliderSlideStyles += prosliderSlide.dataset.style, prosliderSlide.setAttribute("style", prosliderSlideStyles));
        }
      });

      xSplideInstance.on( 'active', () => {
        if (typeof bricksLazyLoad == 'function') {
          bricksLazyLoad();
        }

        if ( 'loop' === sliderConfig.rawConfig.type && typeof doExtrasLottie == 'function' ) {
          if (typeof doExtrasLottie == 'function') {
            doExtrasLottie(slider,true)
          }
        }

        if ( slider.querySelector('.brxe-xmediaplayer') ) {

          slider.querySelectorAll('.brxe-xmediaplayer').forEach(mediaPlayer => {
            if ( window.xMediaPlayer.Instances[mediaPlayer.getAttribute('data-x-id')] ) {
             window.xMediaPlayer.Instances[mediaPlayer.getAttribute('data-x-id')].paused = true
            }
          })

        }

      })


        xSplideInstance.on( 'move', () => {

          if (typeof bricksLazyLoad == 'function') {
            bricksLazyLoad();
          }

          /* support inner animations on elements */

          if ( null != sliderConfig.animationTrigger ) {

              bricksQuerySelectorAll(slider, ".x-slider_slide:not(." + sliderConfig.animationTrigger + ") .brx-animated:not(.x-animated)").forEach(function (animatedElement) {

                let animationType = animatedElement.getAttribute("data-x-animation")
                animatedElement.classList.remove('brx-animate-' + animationType);

              })

              bricksQuerySelectorAll(slider, ".x-slider_slide." + sliderConfig.animationTrigger + " [data-x-interaction-animation]:not(.x-animated)").forEach(function (animatedElement,i) {

                var animationType = animatedElement.dataset.xInteractionAnimation;

                if ( animationType ) {

                  if ( sliderConfig.animateOnce ) {
                    
                  }

                }

            })

          }

        } );

        xSplideInstance.on( 'moved', () => {

          if ( null != sliderConfig.animationTrigger ) {

            bricksQuerySelectorAll(slider, ".x-slider_slide." + sliderConfig.animationTrigger + " .brx-animated:not([data-x-interaction-animation])").forEach(function (animatedElement,i) {

                var animationType = animatedElement.dataset.xAnimation;
                animationType && (animatedElement.classList.add("brx-animate-".concat(animationType)));

                if ( sliderConfig.animateOnce ) {
                  animatedElement.classList.add('x-animated')
                }

                if ( animationType ) {
                  animatedElement.classList.remove("brx-x-animate-".concat(animationType));
                }

            })

            bricksQuerySelectorAll(slider, ".x-slider_slide." + sliderConfig.animationTrigger + " [data-x-interaction-animation]:not(.x-animated)").forEach(function (animatedElement,i) {

              var animationType = animatedElement.dataset.xInteractionAnimation;

              if ( animationType ) {
                animatedElement.classList.add("brx-x-animate-".concat(animationType));
              }

            })

        }

        } );


        

      /* ensure IDs are preserved when splide resizes over breakpoints */
       xSplideInstance.on( 'resized', () => {
          const prosliderSlides = bricksQuerySelectorAll(slider, [
            ".splide__list > .brxe-container", 
            ".splide__list > .brxe-block",
            ".splide__list > .brxe-div"
          ]);
          prosliderSlides.forEach(function (slide) {
            slide.id = slide.dataset.id

            /* ensure dynamic backgrounds are added in */
            if (null != slide.getAttribute('data-style')) {
              setTimeout(function () {
                slide.style.cssText += slide.getAttribute('data-style');
              }, 100)
            }
          });

       })

      } else {
        slider.classList.add('x-slider_no-slides')
      }

    });



    /* syncing after init */

    bricksQuerySelectorAll(container, ".x-slider[data-x-slider*=isNavigation]").forEach(function (sliderNav,i) {

      const thisSliderIdentifier = sliderNav.closest('.x-slider').dataset.xId;

      const thisSliderAttr = sliderNav.getAttribute('data-x-slider')
      const thisSliderConfig = thisSliderAttr ? JSON.parse(thisSliderAttr) : {}

      setTimeout(function () {

        if ( container.querySelector(thisSliderConfig.syncSelector) ) {

          container.querySelectorAll(thisSliderConfig.syncSelector).forEach(mainSlider => {

            const mainSliderIdentifier = mainSlider.dataset.xId;

            const main = xSlider.Instances[mainSliderIdentifier]
            const thumbnail = xSlider.Instances[thisSliderIdentifier]

            if( main && thumbnail) {
              main.sync(thumbnail)
            }

          })
          

        } else {
          console.log('BricksExtras: Looking for slider to sync, but not found. Check the ID/Class on the slider being controlled.')
        }

      }, 250)

    })

  }

  extrasSlider(document);

  function xSliderAJAX(e) {

    if (typeof e.detail.queryId === 'undefined') {
      if ( typeof e.detail.popupElement === 'undefined' ) {
          return;
      } else {
        extrasSlider( e.detail.popupElement )
      }
    }

    setTimeout(() => {
      if ( document.querySelector('.brxe-' + e.detail.queryId) ) {
        if ( document.querySelector('.brxe-' + e.detail.queryId).querySelector('.x-slider') ) {
          extrasSlider( document.querySelector('.brxe-' + e.detail.queryId).parentElement );
        } else if ( document.querySelector('.brxe-' + e.detail.queryId).closest('.x-slider') ) {

          if ( xSlider.Instances[document.querySelector('.brxe-' + e.detail.queryId).closest('.x-slider').getAttribute('data-x-id')] ) {

              let sliderList = document.querySelector('.brxe-' + e.detail.queryId).closest('.splide__list')

              if ( sliderList ) {

                  let transitionValue = sliderList.style.transition;

                  if ( '' === transitionValue ) {
                      xSlider.Instances[document.querySelector('.brxe-' + e.detail.queryId).closest('.x-slider').getAttribute('data-x-id')].refresh()
                  } else {
                      const checkTransition = setInterval(() => {
                           transitionValue = sliderList.style.transition;
                      
                          if (!transitionValue) {
                              xSlider.Instances[document.querySelector('.brxe-' + e.detail.queryId).closest('.x-slider').getAttribute('data-x-id')].refresh()
                              clearInterval(checkTransition);
                          } 
                      }, 50);
                  }
                  
              }

            
          } else {
              document.querySelector('.brxe-' + e.detail.queryId).closest('.x-slider').classList.remove('x-slider_no-slides')
              extrasSlider( document.querySelector('.brxe-' + e.detail.queryId).closest('.x-slider').parentElement, true );
          }

        } 
      } else {
        window.dispatchEvent(new Event('resize'));
      }
    }, 30);

  }

  document.addEventListener("bricks/ajax/load_page/completed", xSliderAJAX)
  document.addEventListener("bricks/ajax/pagination/completed", xSliderAJAX)
  document.addEventListener("bricks/ajax/popup/loaded", xSliderAJAX)
  document.addEventListener("bricks/ajax/end", xSliderAJAX)

  // Expose function
  window.doExtrasSlider = extrasSlider;

  if (typeof bricksextras !== 'undefined') {

    bricksextras.slider = {
      forward: (brxParam, number) => {
        let target = brxParam?.target || false
        let index = number ? parseInt(number) : 1
        let slider = target ? xSlider.Instances[target.getAttribute('data-x-id')] : false;
        if (slider) {
          slider.go( '+' + index )
        }
      },
      backward: (brxParam, number) => {
        let target = brxParam?.target || false
        let index = number ? parseInt(number) : 1
        let slider = target ? xSlider.Instances[target.getAttribute('data-x-id')] : false;
        if (slider) {
          slider.go( '-' + index )
        }
      },
      toslide: (brxParam, number) => {
        let target = brxParam?.target || false
        let index = number ? parseInt(number) : 0
        let slider = target ? xSlider.Instances[target.getAttribute('data-x-id')] : false;
        if (slider) {
          if (-1 === index) {
            slider.go( slider.Components.Controller.getEnd() )
          } else {
            slider.go( index )
          }
          
        }
      },
      topage: (brxParam, number) => {
        let target = brxParam?.target || false
        let index = number ? parseInt(number) : 0
        let slider = target ? xSlider.Instances[target.getAttribute('data-x-id')] : false;
        if (slider) {
          slider.go( '>' + index )
        }
      },
    }
  
  }

}

  document.addEventListener("DOMContentLoaded",function(e){
    bricksIsFrontend&&xProSlider()
});