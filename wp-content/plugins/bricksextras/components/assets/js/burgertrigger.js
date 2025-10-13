function xBurgerTrigger(){

  if ( document.querySelector('body > .brx-body.iframe') ) {
    return
  }

  const extrasBurgerTrigger = function ( container ) {

      container.querySelectorAll('.brxe-xburgertrigger').forEach((burger) => {
      
        burger.setAttribute('aria-expanded', 'false')

        burger.addEventListener('click', toggleBurger)

        function closeBurger() {
          burger.setAttribute('aria-expanded', 'false')
          if ( burger.querySelector(".x-hamburger-box") ) {
            burger.querySelector(".x-hamburger-box").classList.remove("is-active")
          }
        }

        function openBurger() {
          burger.setAttribute('aria-expanded', 'true')
          if ( burger.querySelector(".x-hamburger-box") ) {
            burger.querySelector(".x-hamburger-box").classList.add("is-active")
          }
        }

        function toggleBurger() {

          if ('true' == burger.getAttribute('aria-expanded')) {
            closeBurger()
          } else {
            openBurger()
          }

        }

    });

  }


  extrasBurgerTrigger(document);

    function xBurgerTriggerAJAX(e) {

        if (typeof e.detail.queryId === 'undefined') {
            if ( typeof e.detail.popupElement === 'undefined' ) {
                return;
            } else {
              extrasBurgerTrigger( e.detail.popupElement )
            }
        }

        setTimeout(() => {
            if ( document.querySelector('.brxe-' + e.detail.queryId) ) {
              extrasBurgerTrigger(document.querySelector('.brxe-' + e.detail.queryId).parentElement);
            }
        }, 0);
      }
      
      document.addEventListener("bricks/ajax/load_page/completed", xBurgerTriggerAJAX)
      document.addEventListener("bricks/ajax/pagination/completed", xBurgerTriggerAJAX)
      document.addEventListener("bricks/ajax/popup/loaded", xBurgerTriggerAJAX)
      document.addEventListener("bricks/ajax/end", xBurgerTriggerAJAX)

    // Expose function
    window.extrasBurgerTrigger = extrasBurgerTrigger;

}
    
document.addEventListener("DOMContentLoaded",function(e){
   bricksIsFrontend&&xBurgerTrigger()
});


