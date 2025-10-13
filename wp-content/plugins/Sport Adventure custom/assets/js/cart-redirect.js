(function($) {
    'use strict';

    // Add jQuery AJAX queue utility
    $.ajaxQ = (function(){
        var queue = [], xhr = null;
        
        $(document).ajaxSend(function(e, jqXHR) {
            queue.push(jqXHR);
        });
        
        $(document).ajaxComplete(function(e, jqXHR) {
            var index = queue.indexOf(jqXHR);
            if (index > -1) {
                queue.splice(index, 1);
            }
        });
        
        return {
            abortAll: function() {
                queue.forEach(function(jqXHR) {
                    jqXHR.abort();
                });
                queue = [];
            }
        };
    })();

    let isRedirecting = false;
    let redirectTimeout = null;
    let lastRedirectTime = 0;
    const REDIRECT_COOLDOWN = 2000; // 2 second cooldown between redirects

    // Store original button text
    let originalButtonText = '';

    // Handle AJAX add to cart
    $(document.body).on('adding_to_cart', function(e, $button, data) {
        // Store original text and change button text
        originalButtonText = $button.text();
        $button.text('Przekierowuję');
        $button.prop('disabled', true);

        // Centralized add_to_cart event
        if (window.dataLayerInstance) {
            window.dataLayerInstance.trackAddToCart($button);
        }
    });

    // Bind our handler before WooCommerce's handlers
    function bindEarlyHandler() {
        // Remove any existing handlers for our namespace
        $(document.body).off('added_to_cart.sa_redirect');
        
        // Add our handler
        $(document.body).on('added_to_cart.sa_redirect', function(e, fragments, cart_hash, $button) {
            // Stop event propagation
            e.preventDefault();
            e.stopImmediatePropagation();
            
            handleAddToCartResponse(fragments);
            return false;
        });
    }

    // Bind our handler immediately
    bindEarlyHandler();

    function handleAddToCartResponse(fragments) {
        let redirectUrl = null;
        
        // Try to get redirect URL from fragments
        if (fragments && fragments.redirect_url) {
            redirectUrl = fragments.redirect_url;
        }
        
        // If not found in fragments, try to get from data attribute
        if (!redirectUrl && fragments && fragments['.cart-redirect-data']) {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = fragments['.cart-redirect-data'];
            redirectUrl = tempDiv.querySelector('.cart-redirect-data')?.dataset?.redirectUrl;
        }
        
        // If still not found, try to get from localized script params
        if (!redirectUrl && window.sa_redirect_params?.checkout_url) {
            redirectUrl = window.sa_redirect_params.checkout_url;
        }
        
        if (!redirectUrl) {
            resetButton();
            return;
        }

        handleRedirect(redirectUrl);
    }

    function handleRedirect(url) {
        if (isRedirecting) {
            return;
        }

        if (Date.now() - lastRedirectTime < REDIRECT_COOLDOWN) {
            return;
        }

        isRedirecting = true;
        lastRedirectTime = Date.now();

        try {
            // Remove WooCommerce's handlers
            $(document.body).off('added_to_cart.wc-ajax');
            $(document.body).off('added_to_cart.wc_cart_button');
            
            // Prevent any further AJAX requests
            $.ajaxQ.abortAll();
            
            $(document.body).trigger('sa_before_redirect', [url]);
            window.location.replace(url);
        } catch (error) {
            resetButton();
        }
    }

    function resetButton() {
        const $button = $('.single_add_to_cart_button');
        if (originalButtonText) {
            $button.text(originalButtonText);
        }
        $button.prop('disabled', false);
    }

    // Reset state on page load
    $(document).ready(function() {
        isRedirecting = false;
        if (redirectTimeout) {
            clearTimeout(redirectTimeout);
        }
        bindEarlyHandler();
    });

    // Handle errors
    $(document.body).on('wc_ajax_error', resetButton);

    // Safety timeout to reset button
    setTimeout(resetButton, 10000);
})(jQuery); 