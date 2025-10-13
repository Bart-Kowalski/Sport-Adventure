function xSlideMenu(){


  let extrasSlideMenu = function ( container ) {

    container.querySelectorAll('.brxe-xslidemenu').forEach( slideMenu => {

        const configAttr = slideMenu.getAttribute('data-x-slide-menu')
        const elementConfig = configAttr ? JSON.parse(configAttr) : {}
        let inBuilder = document.querySelector('.brx-body.iframe');

        let speedAnimation = elementConfig.slideDuration;

       

        /* add icons */
        slideMenu.querySelectorAll('.menu-item-has-children > a').forEach( menuHasChildren => {

          if ( menuHasChildren.querySelector('.x-slide-menu_dropdown-icon') ) {
            menuHasChildren.querySelector('.x-slide-menu_dropdown-icon').remove()
          }

            var btn = document.createElement("button");
                btn.setAttribute("aria-expanded", "false");
                btn.setAttribute("class", "x-slide-menu_dropdown-icon");
                btn.setAttribute("aria-label", elementConfig.subMenuAriaLabel);

            menuHasChildren.append(btn)

            if (slideMenu.querySelector('.x-sub-menu-icon')) {
              btn.innerHTML += slideMenu.querySelector('.x-sub-menu-icon').innerHTML
            } else {
              btn.innerHTML += '<svg class="x-slide-menu_dropdown-icon-svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/>';
            }

            btn.addEventListener('click', toggleSubMenu);
            
        
            function toggleSubMenu(e) {
                e.preventDefault()
                e.stopPropagation()
    
                let parent = this.closest(".menu-item-has-children");
                
                this.setAttribute("aria-expanded", "true" === this.getAttribute("aria-expanded") ? "false" : "true" );
                
                parent.lastElementChild.xslideToggle(speedAnimation)
    
                let allSiblings = Array.from(parent.parentElement.children).filter(sibling => sibling.textContent !== parent.textContent);
    
                allSiblings.forEach( dropDownSibling => {
    
                    if (dropDownSibling.classList.contains('menu-item-has-children') ){
                      
                        dropDownSibling.lastElementChild.xslideUp(speedAnimation)
    
                        dropDownSibling.children[0].lastElementChild.setAttribute("aria-expanded", "false")
                    }
    
                })
    
            }

        })
       
        /* current menu item open */
        if ( elementConfig.maybeExpandActive && slideMenu.querySelector('.current-menu-ancestor > a > .x-slide-menu_dropdown-icon') ) {
                             
           let currentAncestor = slideMenu.querySelector('.current-menu-ancestor');

           if ( currentAncestor && currentAncestor.querySelector('.sub-menu') && currentAncestor.querySelector('a > .x-slide-menu_dropdown-icon') ) {
              currentAncestor.querySelector('.sub-menu').xslideDown(0);
              currentAncestor.querySelector('a > .x-slide-menu_dropdown-icon').setAttribute('aria-expanded','true')
           }

        }

        slideMenu.querySelectorAll('.menu-item-has-children > a[href*="#"]').forEach( menuHashLink => {

            menuHashLink.addEventListener('click', function(e) {

                e.preventDefault()
                e.stopPropagation()

                menuHashLink.querySelector('.x-slide-menu_dropdown-icon').click()

        })

        if (null != elementConfig.clickSelector && document.querySelectorAll(elementConfig.clickSelector).length) {

          slideMenu.querySelectorAll('.menu-item:not(.menu-item-has-children) > a[href*="#"]').forEach( menuHashLink => {

            if (inBuilder) {return}
            menuHashLink.addEventListener('click', function(e) {
              document.querySelector(elementConfig.clickSelector).click()
            })
          })

        }

      })

        if (null != elementConfig.clickSelector && document.querySelectorAll(elementConfig.clickSelector).length) {

            let slideMenuOpen = false;

            if (!inBuilder) {
              document.querySelectorAll(elementConfig.clickSelector).forEach(trigger => {

                      trigger.addEventListener('click', function(e) {

                        e.preventDefault()
                        e.stopPropagation()

                        if ( !slideMenuOpen ) { slideMenuOpen = true; } 
                        else {  slideMenuOpen = false;  }

                        if ( slideMenuOpen ) {
                          /* opening */
                          slideMenu.classList.add('x-slide-menu_open');
                          xOpenSlideMenu(slideMenu.getAttribute('data-x-id'))
                          

                          document.addEventListener('keydown', xEscClickCloseSlideMenu);
                          document.addEventListener('click', xEscClickCloseSlideMenu);

                        } 
                        
                        /* closing */
                        else {
                          slideMenu.classList.remove('x-slide-menu_open');
                          xCloseSlideMenu(slideMenu.getAttribute('data-x-id'))
                          

                          document.removeEventListener('keydown', xEscClickCloseSlideMenu);
                          document.removeEventListener('click', xEscClickCloseSlideMenu);
                        }


                    })

              })
            }

            function xEscClickCloseSlideMenu(e) {

              if((e.key === "Escape" || e.key === "Esc")){
                document.querySelector(elementConfig.clickSelector).click()
                return;
              }
          
              if (! e.target.closest('.x-slide-menu_open') && ! e.target.closest(elementConfig.clickSelector) ) {
                document.querySelector(elementConfig.clickSelector).click()
              }
          
            }
    
        }

  })  

}

function xOpenSlideMenu(elementIdentifier) {

  const element = document.querySelector('.brxe-xslidemenu[data-x-id="' + elementIdentifier + '"]');
  if (!element) { return; }
  const configAttr = element.getAttribute('data-x-slide-menu');
  const elementConfig = configAttr ? JSON.parse(configAttr) : {}
  element.xslideDown(elementConfig.slideDuration)
  element.dispatchEvent(new Event('x_slide_menu:expand'))

}

function xCloseSlideMenu(elementIdentifier) {

  const element = document.querySelector('.brxe-xslidemenu[data-x-id="' + elementIdentifier + '"]');
  if (!element) { return; }
  const configAttr = element.getAttribute('data-x-slide-menu');
  const elementConfig = configAttr ? JSON.parse(configAttr) : {}

  element.xslideUp(elementConfig.slideDuration)
  element.dispatchEvent(new Event('x_slide_menu:collapse'))

}



extrasSlideMenu(document);

function xSlideMenuAjax(e) {

  if (typeof e.detail.queryId === 'undefined') {
      if ( typeof e.detail.popupElement === 'undefined' ) {
          return;
      } else {
        extrasSlideMenu( e.detail.popupElement, true )
      }
  }

  setTimeout(() => {
      if ( document.querySelector('.brxe-' + e.detail.queryId) ) {
        extrasSlideMenu(document.querySelector('.brxe-' + e.detail.queryId).parentElement);
      }
  }, 0);
}

document.addEventListener("bricks/ajax/load_page/completed", xSlideMenuAjax)
document.addEventListener("bricks/ajax/pagination/completed", xSlideMenuAjax)
document.addEventListener("bricks/ajax/popup/loaded", xSlideMenuAjax)
document.addEventListener("bricks/ajax/end", xSlideMenuAjax)

              
// Expose functions
window.doExtrasSlideMenu = extrasSlideMenu;
window.xOpenSlideMenu = xOpenSlideMenu
window.xCloseSlideMenu = xCloseSlideMenu

if (typeof bricksextras !== 'undefined') {

  bricksextras.slidemenu = {
    expand: (brxParam) => {
      let target = brxParam?.target || false
      if ( target && target.hasAttribute('data-x-id')) {
        if ( 'none' === window.getComputedStyle(target, null).display ) {
         xOpenSlideMenu( target.getAttribute('data-x-id') )
        }
      }
    },
    collapse: (brxParam) => {
      let target = brxParam?.target || false
      if ( target && target.hasAttribute('data-x-id')) {
        if ( 'none' !== window.getComputedStyle(target, null).display ) {
          xCloseSlideMenu( target.getAttribute('data-x-id') )
        }
      }
    },
    toggle: (brxParam) => {
      let target = brxParam?.target || false
      if ( target && target.hasAttribute('data-x-id')) {
        if ( 'none' === window.getComputedStyle(target, null).display ) {
          xOpenSlideMenu( target.getAttribute('data-x-id') )
        } else {
          xCloseSlideMenu( target.getAttribute('data-x-id') )
        }
      }
    },
  }

}

}

document.addEventListener("DOMContentLoaded",function(e){
  bricksIsFrontend&&xSlideMenu()
})