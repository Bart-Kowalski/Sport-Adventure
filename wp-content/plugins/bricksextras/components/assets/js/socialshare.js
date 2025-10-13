var PrintElements = (function () {
    "use strict";

    var _hide = function (element) {
       
        if (!element.classList.contains("x-print-preserve-print")) {
            element.classList.add("x-print-no-print");
        }
    };

    var _preserve = function (element, isStartingElement) {
        element.classList.remove("x-print-no-print");
        element.classList.add("x-print-preserve-print");
        if (!isStartingElement) {
            element.classList.add("x-print-preserve-ancestor");
        }
    };

    var _clean = function (element) {
        element.classList.remove("x-print-no-print");
        element.classList.remove("x-print-preserve-print");
        element.classList.remove("x-print-preserve-ancestor");
    };

    var _walkSiblings = function (element, callback) {
        var sibling = element.previousElementSibling;
        while (sibling) {
            callback(sibling);
            sibling = sibling.previousElementSibling;
        }
        sibling = element.nextElementSibling;
        while (sibling) {
            callback(sibling);
            sibling = sibling.nextElementSibling;
        }
    };

    var _attachPrintClasses = function (element, isStartingElement) {
        _preserve(element, isStartingElement);
        _walkSiblings(element, _hide);
    };

    var _cleanup = function (element, isStartingElement) {
        _clean(element);
        _walkSiblings(element, _clean);
    };

    var _walkTree = function (element, callback) {
        var currentElement = element;
        callback(currentElement, true);
        currentElement = currentElement.parentElement;
        while (currentElement && currentElement.nodeName !== "BODY") {
            callback(currentElement, false);
            currentElement = currentElement.parentElement;
        }
    };

    var _print = function (elements) {
        for (var i = 0; i < elements.length; i++) {
            _walkTree(elements[i], _attachPrintClasses);
        }
        window.print();

        setTimeout(() => {
            for (i = 0; i < elements.length; i++) {
                _walkTree(elements[i], _cleanup);
            }
          }, 1000);
        
    };

    return {
        print: _print
    };
})();

function xSocialShare() {

    if ( document.querySelector('body > .brx-body.iframe') ) {
        return
    }

    const extrasSocialShare = function ( container ) {

        container.querySelectorAll(".brxe-xsocialshare").forEach((socialShare) => {

            let socialWidth = 600;
            let socialHeight = 600;
            
            let leftPosition = (window.screen.width / 2) - ((socialWidth / 2) + 10);
            let topPosition = (window.screen.height / 2) - ((socialHeight / 2) + 50);
            let windowFeatures = "width="+ socialWidth +",height="+ socialHeight +",scrollbars=yes,left=" + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY=" + topPosition + ",toolbar=no,menubar=no,location=no,directories=no";

            let printEl = document.body;

            const configAttr = socialShare.getAttribute('data-x-social')
            const config = configAttr ? JSON.parse(configAttr) : {}

            let insideLoop = false;

            if ( null != config.isLooping ) {
                insideLoop = true
            }

            const maybePopup = 'true' === socialShare.getAttribute('data-x-popup');

            if ( maybePopup ) {

                socialShare.querySelectorAll(".x-social-share_link:not(.email):not(.print):not(.copy):not(.mastodon)").forEach((link) => {

                    link.addEventListener('click', (e) => {
                        e.preventDefault();
                        window.open(
                            link.getAttribute('href'),
                            "popupWindow",
                            windowFeatures
                        );
                    })

                })

            }

            socialShare.querySelectorAll(".x-social-share_link.mastodon").forEach((link) => {

                link.addEventListener('click', (e) => {

                    e.preventDefault();

                    // Gather the source text and URL
                    let src = e.currentTarget.getAttribute("data-src");

                    // Gather the Mastodon domain
                    let domain = prompt("Enter your Mastodon domain", "");

                    if (domain == "" || domain == null){
                        return;
                    }

                    if ( domain.endsWith('/') ) {
                        domain = domain.slice(0, -1)
                    }

                    if (domain.startsWith('https://')) {
                        domain = domain.slice('https://'.length);
                    }

                    // Build the URL
                    let url = "https://" + domain + "/share?text=" + src;

                    if ( maybePopup ) {
                        window.open( 
                            url,
                            "popupWindow",
                            windowFeatures
                        );
                    } else {
                        window.open(url, '_blank');
                    }

                })

            })

            socialShare.querySelectorAll(".x-social-share_link.copy").forEach((copyLink) => {

                copyLink.addEventListener('click', (e) => {
                    e.preventDefault();
                    if ( null != navigator.clipboard ) {
                        navigator.clipboard.writeText(copyLink.getAttribute('data-copy-url')).then(() => {
                            if ( copyLink.querySelector('.x-social-share_label' ) && '' != copyLink.dataset.copiedLabel ) {
                                copyLink.querySelector('.x-social-share_label' ).innerHTML = copyLink.dataset.copiedLabel
                            }
                        }, () => {
                            console.log('BricksExtras: Copy to clipboard failed. This feature is available only in with HTTPS')
                        });
                    } else {
                        console.log('BricksExtras: Copy to clipboard failed. This feature is available only in with HTTPS')
                    }
                })

            })

            socialShare.querySelectorAll(".x-social-share_link.print").forEach((printLink) => {

                if ( printLink.getAttribute('data-x-hide-print') ) {
                    socialShare.setAttribute('data-x-hide-print','true')
                }

                if ( printLink.getAttribute('data-print-exact') ) {
                    document.documentElement.style.setProperty('print-color-adjust', 'exact');
                    document.documentElement.style.setProperty('-webkit-print-color-adjust', 'exact');
                }

                printLink.addEventListener('click', (e) => {

                    e.preventDefault();

                    let printSelector = printLink.getAttribute('data-print-selector')

                    if ( printSelector ) {
                        printEl = document.querySelector(printSelector);

                        if (insideLoop) {
                            printEl = printLink.closest(printSelector)
                        }
                    }

                    PrintElements.print([printEl]);
                })

            })

        })

    }

    extrasSocialShare(document);

    function xSocialShareAjax(e) {

        if (typeof e.detail.queryId === 'undefined') {
            if ( typeof e.detail.popupElement === 'undefined' ) {
                return;
            } else {
                extrasSocialShare( e.detail.popupElement )
            }
        }

        setTimeout(() => {
            if ( document.querySelector('.brxe-' + e.detail.queryId) ) {
                extrasSocialShare(document.querySelector('.brxe-' + e.detail.queryId).parentElement);
            }
        }, 0);
      }
      
      document.addEventListener("bricks/ajax/load_page/completed", xSocialShareAjax)
      document.addEventListener("bricks/ajax/pagination/completed", xSocialShareAjax)
      document.addEventListener("bricks/ajax/popup/loaded", xSocialShareAjax)
      document.addEventListener("bricks/ajax/end", xSocialShareAjax)

    // Expose function
    window.doExtrasSocialShare = extrasSocialShare;

}

document.addEventListener("DOMContentLoaded",function(e){
    bricksIsFrontend&&xSocialShare()
});