function xHeaderSearch(){

  if ( document.querySelector('body > .brx-body.iframe') ) {
    return
  }

  let resizeTimeout;
  let scrollTimeout;

  // Define debounced functions
  const debouncedHeaderOnResize = debounce(headerOnResize, 50);
  const debouncedHeaderOnScroll = debounce(headerOnScroll, 50);

  function debounce(func, delay) {
    let timeout;
    return function () {
      const context = this;
      const args = arguments;
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(context, args), delay);
    };
  }

  function updateHeaderHeight() {
    if (document.querySelector('#brx-header .x-search-form [data-brx-ls-wrapper]')) {
      document.querySelector('#brx-header div.x-search-form').style.setProperty('--x-header-height', document.querySelector('#brx-header .x-search-form [data-brx-ls-wrapper]').getBoundingClientRect().top + 'px');
    }
  }

  function headerOnResize() {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(updateHeaderHeight, 50); // Adjust delay as needed
  }

  function headerOnScroll() {
    clearTimeout(scrollTimeout);
    scrollTimeout = setTimeout(updateHeaderHeight, 50); // Adjust delay as needed
  }

  if (document.querySelector('#brx-header') && document.querySelector('#brx-header div.x-search-form')) {
    document.addEventListener("bricks/ajax/query_result/displayed", updateHeaderHeight);
    updateHeaderHeight();
  }
  

  document.querySelectorAll('.brxe-xheadersearch').forEach((headerSearch) => {

    if ( headerSearch.querySelector('.x-header-search_toggle-open') ) {
      headerSearch.querySelector('.x-header-search_toggle-open').addEventListener('click', toggleSearch)
    }

    if ( headerSearch.querySelector('.x-header-search_toggle-close') ) {
      headerSearch.querySelector('.x-header-search_toggle-close').addEventListener('click', toggleSearch)
    }

    function toggleSearch(e) {

      e.preventDefault()

      if ( 'false' === headerSearch.querySelector('[class^="x-header-search_toggle"]').getAttribute('aria-expanded') ) {
        openSearch()
      } else {
        closeSearch()
      }

    }

    function openSearch() {
      headerSearch.querySelectorAll('[class^="x-header-search_toggle"]:not(.x-header-search_toggle-open-text)').forEach((headerSearchToggle) => {
        headerSearchToggle.setAttribute('aria-expanded', 'true')
        document.body.classList.add('x-header-search-open')
        headerSearch.dispatchEvent(new Event('x_header_search:open'))
        document.addEventListener('keydown', closeOnEscape);
        document.addEventListener("click", maybeCloseHeaderSearch)
      })

      let searchInput = headerSearch.querySelector('input[type=search');

      if ( searchInput ) {
          setTimeout(function() {
              searchInput.focus();
              searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
          }, 100);
      }
      
    }

    function closeSearch(focus = true) {
      headerSearch.querySelectorAll('[class^="x-header-search_toggle"]:not(.x-header-search_toggle-open-text)').forEach((headerSearchToggle) => {
        headerSearchToggle.setAttribute('aria-expanded', 'false')
        document.body.classList.remove('x-header-search-open')
        headerSearch.dispatchEvent(new Event('x_header_search:close'))
        document.removeEventListener('keydown', closeOnEscape);
        document.removeEventListener("click", maybeCloseHeaderSearch)
      })

      

      setTimeout(() => {
        if ( headerSearch.querySelector('.brx-ls-active') ) {
          headerSearch.querySelector('.brx-ls-active').classList.remove('brx-ls-active')
        }

        if ( headerSearch.querySelector('.brxe-filter-search') ) {

          if (headerSearch.querySelector('input[data-brx-filter]')) {
              headerSearch.querySelector('input[data-brx-filter]').value = '';
          }
          if ( headerSearch.querySelector('.brxe-filter-search').hasAttribute('id') ) {
              
              const elementID = headerSearch.querySelector('.brxe-filter-search').id.split('-')[1];

              if ( bricksData.filterInstances[elementID] ) {
                  bricksData.filterInstances[elementID].currentValue = '';
              }
          }

        }
      }, 200);

     

      if (focus) {
        headerSearch.querySelector('.x-header-search_toggle-open').focus()
      }
      
    }

    function closeOnEscape(e) {
      if((e.key === "Escape" || e.key === "Esc")){
          closeSearch()
        }
    }

    function maybeCloseHeaderSearch(e) {

      if (e.target.hasAttribute('data-brx-ls-wrapper')) {
        closeSearch(false)
      }

      if (!e.target.closest('.brxe-xheadersearch')) {
        closeSearch(false)
      }

    }

    const keyboardfocusableElements = headerSearch.querySelectorAll(
      'a[href], button, input, textarea, select, details, [tabindex]'
    );

    if (keyboardfocusableElements.length) {

      keyboardfocusableElements[keyboardfocusableElements.length - 1].addEventListener('keydown', (e) => {

          if (!e.shiftKey && e.key === 'Tab') {
              if (!e.target.classList.contains('x-header-search_toggle-open')) {
                  e.preventDefault()
                  keyboardfocusableElements[0].focus()
              }
          }

      })

      keyboardfocusableElements[0].addEventListener('keydown', (e) => {

          if (e.shiftKey && e.key === 'Tab') {
              if (!e.target.classList.contains('x-header-search_toggle-open')) {
                  e.preventDefault()
                  keyboardfocusableElements[keyboardfocusableElements.length - 1].focus()
              }
          }

      })

  }

  if ( headerSearch.hasAttribute('data-x-prevent-scroll') ) {

    headerSearch.addEventListener('x_header_search:open', () => {
      document.documentElement.classList.add("x-header-search_prevent-scroll_" + headerSearch.id)
      if (typeof lenis !== 'undefined') {
        lenis.stop()
      }
      if ( headerSearch.querySelector('[data-brx-ls-wrapper] > .brxe-container') ) {
        window.addEventListener('resize', debouncedHeaderOnResize, false);
        window.addEventListener('scroll', debouncedHeaderOnScroll, false);

        headerSearch.querySelector('[data-brx-ls-wrapper] > .brxe-container').addEventListener('scroll', debouncedHeaderOnScroll, false);
      }

      
    })
    headerSearch.addEventListener('x_header_search:close', () => {
      document.documentElement.classList.remove("x-header-search_prevent-scroll_" + headerSearch.id)
      if (typeof lenis !== 'undefined') {
        lenis.start()
      }
      if ( headerSearch.querySelector('[data-brx-ls-wrapper] > .brxe-container') ) {
        window.removeEventListener('resize', debouncedHeaderOnResize, false);
        window.removeEventListener('scroll', debouncedHeaderOnScroll, false);
        headerSearch.querySelector('[data-brx-ls-wrapper] > .brxe-container').removeEventListener('scroll', debouncedHeaderOnScroll, false);
      }
  
    })

  }

  let bricksSearchFilter = headerSearch.querySelector('input[data-brx-filter]')

  if ( headerSearch.hasAttribute('data-search-redirect') && bricksSearchFilter ) {

      const configAttr = headerSearch.querySelector('[data-x-search]').getAttribute('data-x-search')
      const config = configAttr ? JSON.parse(configAttr) : {}

      bricksSearchFilter.addEventListener('keydown', (e) => {

          if (e.key === 'Enter') {

              const searchQuery = bricksSearchFilter.value;
              const siteUrl = window.location.origin;
              let encodedQuery = encodeURIComponent(searchQuery).replace(/%20/g, '+')

              if (null != config.postTypes ) {
                  config.postTypes.forEach(postType => {
                      encodedQuery += '&' + encodeURIComponent('post_type[]') + '=' + postType;
                  })
              }
              
              const searchUrl = `${siteUrl}/?s=${encodedQuery}`;

              window.location.replace(searchUrl);
          }
      })
  }

  });

  

}
    
document.addEventListener("DOMContentLoaded",function(e){
   bricksIsFrontend&&xHeaderSearch()
});


