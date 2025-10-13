/**
 * Sport Adventure Cart JavaScript
 * Handles cart quantity updates, product removal, and cart badge
 */
jQuery(function($) {
    // Flag to prevent double-triggering of quantity updates
    var isUpdatingQuantity = false;
    
    // Safety timeout to reset flag if it gets stuck
    setInterval(function() {
        isUpdatingQuantity = false;
    }, 1000);
    
    // Prevent WooCommerce's default quantity handlers on cart pages
    $(document).ready(function() {
        var isCartPage = $('.woocommerce-cart-form').length > 0;
        if (isCartPage) {
            // Remove any existing WooCommerce quantity handlers
            $(document).off('click', '.quantity .action');
            $(document).off('click', '.woocommerce-cart-form .quantity .action');
            
            // Also prevent any input change events from WooCommerce
            $('.woocommerce-cart-form .qty').off('change');
        }
    });

    // Cart badge functionality
    function updateCartBadge() {
        var $cartLink = $('.header-top__link-wrapper');
        if ($cartLink.length) {
            // Get cart count from WooCommerce
            $.ajax({
                url: wc_add_to_cart_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_cart_count'
                },
                success: function(response) {
                    if (response.success) {
                        var cartCount = response.data.count || 0;
                        $cartLink.attr('data-cart-count', cartCount);
                    }
                },
                error: function() {
                    // Fallback: try to get count from cart fragments or mini cart
                    var cartCount = 0;
                    
                    // Try to get from mini cart widget
                    var $miniCart = $('.widget_shopping_cart .quantity');
                    if ($miniCart.length) {
                        var cartText = $miniCart.text();
                        var match = cartText.match(/(\d+)/);
                        if (match) {
                            cartCount = parseInt(match[1]);
                        }
                    }
                    
                    // Try to get from cart fragments
                    if (cartCount === 0 && typeof wc_cart_fragments_params !== 'undefined') {
                        // This will be updated when cart fragments refresh
                        $cartLink.attr('data-cart-count', cartCount);
                    } else {
                        $cartLink.attr('data-cart-count', cartCount);
                    }
                }
            });
        }
    }

    // Initialize cart badge on page load
    updateCartBadge();

    // Update cart badge when cart is updated
    $(document.body).on('updated_cart_totals', function() {
        updateCartBadge();
    });

    // Update cart badge when fragments are updated
    $(document.body).on('updated_wc_div', function() {
        updateCartBadge();
    });

    // Handle quantity increment/decrement
    $(document).on('click', '.quantity .action', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        
        // Only handle on cart and checkout pages, not product pages
        // Check for cart form or checkout form presence instead of URL
        var isCartPage = $('.woocommerce-cart-form').length > 0;
        var isCheckoutPage = $('form.checkout').length > 0;
        
        if (isCartPage || isCheckoutPage) {
            // Prevent double-triggering
            if (isUpdatingQuantity) {
                return false;
            }
            
            var $btn = $(this);
            var $wrapper = $btn.closest('.quantity');
            var $input = $wrapper.find('.qty');
            var min = parseInt($input.attr('min')) || 1;
            var max = parseInt($input.attr('max')) || 99;
            var currentValue = parseInt($input.val()) || min;
            var isPlus = $btn.hasClass('plus');
            var newValue = currentValue;
            
            if (isPlus && (isNaN(max) || currentValue < max)) {
                newValue = currentValue + 1;
            } else if (!isPlus && currentValue > min) {
                newValue = currentValue - 1;
                
                // Track remove_from_cart for any quantity reduction
                if (newValue < currentValue) {
                    const removedQuantity = currentValue - newValue;
                    if (window.dataLayerInstance) {
                        window.dataLayerInstance.trackRemoveFromCart($wrapper, removedQuantity);
                    }
                }
            }
            
            // Only update if value actually changed
            if (newValue !== currentValue) {
                isUpdatingQuantity = true;
                
                // Update the input value
                $input.val(newValue);
                
                // Trigger the form update directly
                if (isCartPage) {
                    var $updateButton = $('[name="update_cart"]');
                    if ($updateButton.length) {
                        $updateButton.prop('disabled', false);
                        // Use a small delay to ensure the value is set
                        setTimeout(function() {
                            $updateButton.click();
                            isUpdatingQuantity = false;
                        }, 50);
                    } else {
                        isUpdatingQuantity = false;
                    }
                } else {
                    isUpdatingQuantity = false;
                }
            }
        }
        
        return false;
    });

    // Track explicit product removal via remove links
    $(document).on('click', '.cart .remove, .woocommerce-cart-form .remove', function(e) {
        var $removeLink = $(this);
        var $cartItem = $removeLink.closest('.cart_item, tr');
        var $qtyInput = $cartItem.find('.qty');
        var quantity = parseInt($qtyInput.val()) || 1;
        if (window.dataLayerInstance) {
            window.dataLayerInstance.trackRemoveFromCart($cartItem, quantity);
        }
    });
    
    // Handle checkout button loading state
    $('.checkout-button').on('click', function() {
        $(this).addClass('loading');
    });
}); 