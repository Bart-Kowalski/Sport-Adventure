function xNestableTable() {

    const extrasNestableTable = function ( container ) {
    
    container.querySelectorAll('.brxe-xnestabletable').forEach((table) => {

        const config = table.getAttribute('data-x-nestable-table') ? JSON.parse(table.getAttribute('data-x-nestable-table')) : {}

        function maybeStack() {
            if (window.innerWidth <= config.stack) {
                table.classList.add('x-nestable-table_stacked')
            } else {
                table.classList.remove('x-nestable-table_stacked')
            }
        }

        maybeStack()
        window.addEventListener("resize", debounce(() => {
            maybeStack()
         }, 0));

         let columnHeadings = []

         table.querySelectorAll("th").forEach((columnHeading) => {
            columnHeadings.push( columnHeading.textContent )
        })

        if ( columnHeadings.length === 0 ) {
            table.classList.add('x-nestable-table_no-labels')
        }

        const options = {
            characterData: true,
            childList: true
        }

        function callback(mutationList, observer) {
        
            mutationList.forEach(function(mutation) {
               doMobileTable();
            })
        }

        const observer = new MutationObserver(callback)
        if ( table.querySelector('tbody') ) {
            observer.observe(table.querySelector('tbody'), options)
        }

         setTimeout(() => {
            doMobileTable();
        }, 25)    

        function doMobileTable() {
            if ( columnHeadings.length !== 0 ) {
            
                table.querySelectorAll("tr").forEach((column) => {
                    column.querySelectorAll("td").forEach((row,index) => {
                        if (!row.hasAttribute('data-x-mobile-label')) {
                            row.setAttribute('data-x-mobile-label',columnHeadings[index])
                        }
                    })
                })

            }
        }

    })


}

const debounce = (fn, threshold) => {
    var timeout;
    threshold = threshold || 50;
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


extrasNestableTable(document);


function xNestableTableAJAX(e) {

    if (typeof e.detail.queryId === 'undefined') {
      if ( typeof e.detail.popupElement === 'undefined' ) {
        return;
      } else {
        extrasNestableTable( e.detail.popupElement )
      }
    }

    setTimeout(() => {
        if ( document.querySelector('.brxe-' + e.detail.queryId) ) {
            extrasNestableTable(document.querySelector('.brxe-' + e.detail.queryId).parentElement, true);
        }
    }, 0);
  }

  document.addEventListener("bricks/ajax/load_page/completed", xNestableTableAJAX)
  document.addEventListener("bricks/ajax/pagination/completed", xNestableTableAJAX)
  document.addEventListener("bricks/ajax/popup/loaded", xNestableTableAJAX)
  document.addEventListener("bricks/ajax/end", xNestableTableAJAX)

window.doExtrasNestableTable = extrasNestableTable;


}

document.addEventListener("DOMContentLoaded",function(e){
bricksIsFrontend&&xNestableTable()
});

