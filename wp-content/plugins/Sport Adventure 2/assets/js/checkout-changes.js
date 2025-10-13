/**
 * Sport Adventure Checkout JavaScript
 * Handles quantity updates and product removal during checkout
 */
jQuery(function($) {
    // console.log('[Debug] Script loaded');
    // console.log('[Debug] checkout_data:', window.checkout_data);

    // Only run on checkout page
    if (!window.checkout_data || !window.checkout_data.is_checkout) {
        // console.log('[Checkout Flow] Not on checkout page, skipping initialization');
        return;
    }

    // Cache frequently used elements
    var $body = $(document.body);
    var $checkout = $('form.checkout');

    // Track page loads
    // console.log('[Checkout Flow] Checkout page loaded');
    // console.log('[Checkout Flow] URL:', window.location.href);
    // console.log('[Checkout Flow] Referrer:', document.referrer);

    // Debug click events
    $(document).on('click', '.quantity-plus, .quantity-minus', function(e) {
        // console.log('[Debug] Button clicked:', this.className);
        e.preventDefault();
        e.stopPropagation();
        
        var $btn = $(this);
        var $control = $btn.closest('.quantity-control');
        // console.log('[Debug] Control found:', $control.length > 0);
        
        // Find the closest custom-fields-wrapper by traversing up
        var $wrapper = $control.closest('.custom-fields-wrapper');
        // console.log('[Debug] Wrapper found:', $wrapper.length > 0);
        
        var cartKey = $wrapper.data('cart-key');
        // console.log('[Debug] Cart key:', cartKey);
        
        if (!cartKey) {
            // console.error('[Debug] No cart key found in wrapper');
            return;
        }
        
        var $input = $control.find('.quantity-number');
        var currentValue = parseInt($input.val()) || 1;
        var min = parseInt($input.attr('min')) || 1;
        var max = parseInt($input.attr('max')) || 99;
        var newQty = currentValue;
        
        // console.log('[Debug] Current value:', currentValue);
        // console.log('[Debug] Min:', min);
        // console.log('[Debug] Max:', max);

        // Increment or decrement
        if ($btn.hasClass('quantity-plus') && (isNaN(max) || currentValue < max)) {
            newQty = currentValue + 1;
            // console.log('[Debug] Incrementing to:', newQty);
        } else if ($btn.hasClass('quantity-minus') && currentValue > min) {
            newQty = currentValue - 1;
            // console.log('[Debug] Decrementing to:', newQty);
        }

        // Track remove_from_cart event if quantity is being reduced
        if (currentValue > newQty && newQty >= 0) {
            // Calculate the quantity being removed
            const removedQuantity = currentValue - newQty;
            if (window.dataLayerInstance) {
                window.dataLayerInstance.trackRemoveFromCart($wrapper, removedQuantity);
            }
        }

        if (newQty !== currentValue) {
            // console.log('[Debug] Updating quantity to:', newQty);
            
            // Save participant data before update
            // console.log('[Debug] Saving participant data for wrapper:', cartKey);
            saveParticipantData($wrapper);
            
            // Display loading state
            $btn.addClass('loading');
            $wrapper.addClass('updating');
            
            // Show full-page loading overlay
            $('body').addClass('checkout-loading');
            if ($('.checkout-loading-overlay').length === 0) {
                $('body').append('<div class="checkout-loading-overlay"><div class="checkout-loading-spinner"></div></div>');
            }
            $('.checkout-loading-overlay').show();

            // Update quantity via AJAX
            $.ajax({
                type: 'POST',
                url: checkout_data.ajax_url,
                data: {
                    action: 'update_cart_quantity',
                    cart_item_key: cartKey,
                    quantity: newQty,
                    security: checkout_data.update_order_review_nonce
                },
                beforeSend: function() {
                    // console.log('[Debug] Sending AJAX request');
                },
                success: function(response) {
                    // console.log('[Debug] AJAX response:', response);
                    if (response.success) {
                        // console.log('[Debug] Debug info:', response.data.debug_info);
                        
                        // Update fragments first
                        if (response.data.fragments) {
                            // Save all participant data before updating fragments
                            // console.log('[Debug] Saving all participant data before fragment updates');
                            $('.custom-fields-wrapper').each(function() {
                                var $w = $(this);
                                var wKey = $w.data('cart-key');
                                // console.log('[Debug] Saving data for wrapper:', wKey);
                                saveParticipantData($w);
                            });

                            // Update fragments
                            $.each(response.data.fragments, function(key, value) {
                                // console.log('[Debug] Updating fragment:', key);
                                var $target = $(key);
                                // console.log('[Debug] Target element found:', $target.length > 0);
                                if ($target.length) {
                                    var $newContent = $(value);
                                    // console.log('[Debug] New content created, length:', $newContent.length);
                                    $target.replaceWith($newContent);
                                }
                            });

                            // Restore all participant data after fragment updates
                            // console.log('[Debug] Restoring participant data after fragment updates');
                            $('.custom-fields-wrapper').each(function() {
                                var $newWrapper = $(this);
                                var wrapperCartKey = $newWrapper.data('cart-key');
                                if (wrapperCartKey) {
                                    // console.log('[Debug] Restoring data for wrapper:', wrapperCartKey);
                                    restoreParticipantData($newWrapper);
                                }
                            });

                            // Re-initialize elements
                            // console.log('[Debug] Re-initializing elements');
                            initializeCheckoutElements();
                        }
                        
                        // Update checkout to reflect changes
                        // console.log('[Debug] Triggering checkout update');
                        $(document.body).trigger('update_checkout');
                    } else {
                        // console.error('[Debug] Update failed:', response);
                        window.location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    // console.error('[Debug] AJAX error:', {xhr: xhr, status: status, error: error});
                    window.location.reload();
                },
                complete: function() {
                    // console.log('[Debug] AJAX request completed');
                    $('.quantity-plus, .quantity-minus').removeClass('loading');
                    $('.custom-fields-wrapper').removeClass('updating');
                    $('body').removeClass('checkout-loading');
                    $('.checkout-loading-overlay').hide();
                }
            });
        } else {
            // console.log('[Debug] No quantity change needed');
        }
    });

    // Listen for WooCommerce checkout update events
    $body.on('update_checkout', function() {
        // console.log('[Debug] Checkout update triggered');
    });

    $body.on('updated_checkout', function() {
        // console.log('[Debug] Checkout update completed');
        // Re-initialize any necessary elements after checkout update
        initializeCheckoutElements();
    });

    function initializeCheckoutElements() {
        // console.log('[Debug] Initializing elements');
        // Make quantity fields readonly
        $('.quantity-number').attr('readonly', true);
        // console.log('[Debug] Found quantity fields:', $('.quantity-number').length);
        
        // Initialize consent dropdowns as collapsed
        $('.consent-dropdown-content').removeClass('expanded');
        $('.consent-dropdown-toggle').removeClass('expanded');
        $('.consent-dropdown-toggle').each(function() {
            var $toggle = $(this);
            var $arrow = $toggle.find('.dropdown-arrow');
            $toggle.contents().first()[0].textContent = 'Rozwiń ';
            $arrow.text('▼');
        });
    }

    // Initialize on page load
    initializeCheckoutElements();

    // Track remove button clicks in checkout order review
    $(document).on('click', '.remove-product, .woocommerce-checkout-review-order-table .remove', function(e) {
        const $removeLink = $(this);
        const cartKey = $removeLink.data('cart-key');
        if (cartKey) {
            // Find the corresponding quantity wrapper to get product data
            const $wrapper = $('.custom-fields-wrapper[data-cart-key="' + cartKey + '"]');
            if ($wrapper.length) {
                const quantity = parseInt($wrapper.find('.quantity-number').val()) || 1;
                if (window.dataLayerInstance) {
                    window.dataLayerInstance.trackRemoveFromCart($wrapper, quantity);
                }
            }
        }
    });

    // Track checkout form submission
    $checkout.on('submit', function() {
        // console.log('[Checkout Flow] Checkout form submitted');
        saveParticipantData();
        return true;
    });

    // Track checkout updates
    $body.on('update_checkout', function() {
        // console.log('[Checkout Flow] Checkout update triggered');
    });

    $body.on('updated_checkout', function() {
        // console.log('[Checkout Flow] Checkout update completed');
    });

    // Track checkout errors
    $body.on('checkout_error', function() {
        // console.log('[Checkout Flow] Checkout error occurred');
    });

    // Track when proceeding to checkout from cart
    $(document).ready(function() {
        if (document.referrer.includes('/koszyk/')) {
            // console.log('[Checkout Flow] Proceeded to checkout from cart page');
        }
    });

    // Function to save participant data before quantity update
    function saveParticipantData($wrapper) {
        // Ensure we have a jQuery object
        $wrapper = $($wrapper);
        
        var participantData = {};
        var cartKey = $wrapper.data('cart-key');
        
        // Save all input values
        $wrapper.find('input[type="text"], input[type="email"], input[type="tel"], input[type="checkbox"]').each(function() {
            var $input = $(this);
            var name = $input.attr('name');
            if (name) {
                participantData[name] = $input.is(':checkbox') ? $input.prop('checked') : $input.val();
            }
        });
        
        // Store in sessionStorage
        if (Object.keys(participantData).length > 0) {
            sessionStorage.setItem('sa_participant_data_' + cartKey, JSON.stringify(participantData));
        }
    }

    // Function to restore participant data after quantity update
    function restoreParticipantData($wrapper) {
        // Ensure we have a jQuery object
        $wrapper = $($wrapper);
        
        var cartKey = $wrapper.data('cart-key');
        var storedData = sessionStorage.getItem('sa_participant_data_' + cartKey);
        
        if (storedData) {
            try {
                var participantData = JSON.parse(storedData);
                
                // Restore values to fields that exist
                Object.keys(participantData).forEach(function(name) {
                    var $field = $wrapper.find('[name="' + name + '"]');
                    if ($field.length) {
                        if ($field.is(':checkbox')) {
                            $field.prop('checked', participantData[name]);
                        } else {
                            $field.val(participantData[name]);
                        }
                    }
                });
                
                // Clean up storage
                sessionStorage.removeItem('sa_participant_data_' + cartKey);
            } catch (e) {
                // console.error('[Debug] Error restoring participant data:', e);
            }
        }
    }

    // Call restore function when document is ready
    $(document).ready(restoreParticipantData);

    // When checkout is updated, re-apply participant data
    $body.on('updated_checkout', restoreParticipantData);

    // Add client-side validation on checkout form submit
    $checkout.on('checkout_place_order', function() {
        console.log('[SA Debug] Starting client-side validation');
        var isValid = true;
        var $errorMessages = [];
        
        // Validate billing fields first
        var requiredBillingFields = [
            'billing_first_name',
            'billing_last_name',
            'billing_phone',
            'billing_email'
        ];
        
        console.log('[SA Debug] Validating billing fields');
        $.each(requiredBillingFields, function(index, fieldId) {
            var $field = $('#' + fieldId);
            console.log('[SA Debug] Checking field:', fieldId, 'Value:', $field.val());
            
            if ($field.length && !$field.val().trim()) {
                isValid = false;
                $field.addClass('woocommerce-invalid');
                console.log('[SA Debug] Field invalid:', fieldId);
            } else {
                $field.removeClass('woocommerce-invalid').addClass('woocommerce-validated');
                console.log('[SA Debug] Field valid:', fieldId);
            }
        });
        
        // Only add visual feedback for consents, let server handle validation messages
        console.log('[SA Debug] Adding visual feedback for consents');
        $('.custom-fields-wrapper').each(function() {
            var $wrapper = $(this);
            var productUniqueId = $wrapper.data('product-id');
            console.log('[SA Debug] Processing wrapper for product:', productUniqueId);
            
            // If we don't have the product ID from data attribute, try to extract it from checkbox name
            if (!productUniqueId) {
                var $firstConsentCheckbox = $wrapper.find('[name*="_0_consent_"]').first();
                if ($firstConsentCheckbox.length) {
                    var checkboxName = $firstConsentCheckbox.attr('name');
                    // Extract product unique ID from name like "participant_product-name-termin_0_consent_regulations"
                    var match = checkboxName.match(/^participant_(.+)_0_consent_/);
                    if (match) {
                        productUniqueId = match[1];
                        console.log('[SA Debug] Extracted product ID from checkbox name:', productUniqueId);
                    }
                }
            }
            
            if (productUniqueId) {
                // Only add visual feedback for required regulations consent
                var $regulationsCheckbox = $wrapper.find('[name="participant_' + productUniqueId + '_0_consent_regulations"]');
                console.log('[SA Debug] Found regulations checkbox:', $regulationsCheckbox.length > 0);
                
                if ($regulationsCheckbox.length && !$regulationsCheckbox.prop('checked')) {
                    $regulationsCheckbox.closest('.checkbox-wrapper').addClass('woocommerce-invalid');
                    console.log('[SA Debug] Regulations not checked for primary participant');
                } else {
                    $regulationsCheckbox.closest('.checkbox-wrapper').removeClass('woocommerce-invalid');
                    console.log('[SA Debug] Regulations checked for primary participant');
                }
            }
            
            // Check each participant form
            var quantity = parseInt($wrapper.find('.quantity-number').val()) || 1;
            console.log('[SA Debug] Participant quantity:', quantity);
            
            if (quantity > 1) {
                for (var i = 1; i < quantity; i++) {
                    var participantNumber = i + 1;
                    console.log('[SA Debug] Validating participant:', participantNumber);
                    
                    // Add visual feedback for required fields
                    $wrapper.find('[name*="_' + i + '_"]:not([type="checkbox"])').each(function() {
                        var $field = $(this);
                        if ($field.closest('.form-row').hasClass('validate-required')) {
                            console.log('[SA Debug] Checking required field:', $field.attr('name'));
                            if (!$field.val().trim()) {
                                $field.addClass('woocommerce-invalid');
                                console.log('[SA Debug] Field is empty');
                            } else {
                                $field.removeClass('woocommerce-invalid').addClass('woocommerce-validated');
                                console.log('[SA Debug] Field has value');
                            }
                        }
                    });
                    
                    // Add visual feedback for regulations consent
                    var $regulationsCheckbox = $wrapper.find('[name="participant_' + productUniqueId + '_' + i + '_consent_regulations"]');
                    console.log('[SA Debug] Found regulations checkbox for participant ' + participantNumber + ':', $regulationsCheckbox.length > 0);
                    
                    if ($regulationsCheckbox.length && !$regulationsCheckbox.prop('checked')) {
                        $regulationsCheckbox.closest('.checkbox-wrapper').addClass('woocommerce-invalid');
                        console.log('[SA Debug] Regulations not checked for participant', participantNumber);
                    } else {
                        $regulationsCheckbox.closest('.checkbox-wrapper').removeClass('woocommerce-invalid');
                        console.log('[SA Debug] Regulations checked for participant', participantNumber);
                    }
                }
            }
        });
        
        console.log('[SA Debug] Client-side validation completed. Valid:', isValid);
        return true; // Always return true to let server handle validation
    });
    
    // Helper function to validate email
    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    // Helper function to re-attach event handlers after AJAX updates
    function attachEventHandlers() {
        // Make quantity fields readonly
        $('.quantity-number').attr('readonly', true);
    }

    // Function to clean product name cells
    function cleanProductNameCells() {
        $('.woocommerce-checkout-review-order-table td.product-name').each(function() {
            // Get the HTML content
            var html = $(this).html();
            // Remove &nbsp; and multiple spaces
            html = html.replace(/&nbsp;/g, '').replace(/\s+/g, ' ').trim();
            $(this).html(html);
        });
    }

    // Run on page load
    cleanProductNameCells();

    // Run after any AJAX updates
    $(document.body).on('updated_checkout', function() {
        cleanProductNameCells();
    });

    // Function to toggle total visibility
    function toggleTotalVisibility() {
        var cartItems = $('.woocommerce-checkout-review-order-table tbody tr.cart_item').length;
        if (cartItems === 1) {
            $('.woocommerce-checkout-review-order-table tfoot tr').hide();
        } else {
            $('.woocommerce-checkout-review-order-table tfoot tr').show();
        }
    }

    // Run on page load
    toggleTotalVisibility();

    // Run after any AJAX updates
    $(document.body).on('updated_checkout', function() {
        toggleTotalVisibility();
    });

    // Add checkout loading overlay if it doesn't exist
    if ($('.checkout-loading-overlay').length === 0) {
        $('body').append('<div class="checkout-loading-overlay"><div class="checkout-loading-spinner"></div></div>');
    }
    
    // Cache the submit button
    var $submitButton = $('#place_order');
    
    // Handle form submit
    $('form.checkout').on('submit', function(e) {
        $submitButton.addClass('processing');
    });

    // Handle WooCommerce checkout events
    $(document.body).on('checkout_place_order', function() {
        $submitButton.addClass('processing');
    });

    // Handle errors from our custom validation
    $(document).on('ajaxComplete', function(event, xhr, settings) {
        if (settings.url && settings.url.indexOf('wc-ajax=checkout') > -1) {
            if (xhr.responseText && xhr.responseText.indexOf('woocommerce-error') > -1) {
                $submitButton.removeClass('processing');
            }
        }
    });

    // Also handle click event directly
    $submitButton.on('click', function() {
        $(this).addClass('processing');
    });
    
    // Add a safety timeout to hide loading overlay
    $(document).on('click', '#place_order', function() {
        setTimeout(function() {
            if ($('.woocommerce-error').length > 0) {
                $submitButton.removeClass('processing');
            }
        }, 2000);
    });

    // Handle coupon form submission
    $(document).on('submit', 'form.checkout_coupon', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $submitButton = $form.find('.button');
        var couponCode = $form.find('input[name="coupon_code"]').val();
        
        if (!couponCode) {
            return;
        }
        
        $submitButton.addClass('loading');
        $('body').addClass('checkout-loading');
        $('.checkout-loading-overlay').show();
        
        // Use WooCommerce's built-in coupon system
        $.ajax({
            type: 'POST',
            url: wc_checkout_params.wc_ajax_url.toString().replace('%%endpoint%%', 'apply_coupon'),
            data: {
                security: wc_checkout_params.apply_coupon_nonce,
                coupon_code: couponCode
            },
            success: function(response) {
                $('.woocommerce-error, .woocommerce-message, .woocommerce-info').remove();
                
                if (response) {
                    $form.before(response);
                    $form.slideUp();
                    
                    // Update checkout to reflect changes
                    $body.trigger('update_checkout');
                }
            },
            complete: function() {
                $submitButton.removeClass('loading');
                $('body').removeClass('checkout-loading');
                $('.checkout-loading-overlay').hide();
            }
        });
    });

    // Remove checkout loading class on validation error
    $(document.body).on('checkout_error', function() {
        $submitButton.removeClass('processing');
    });

    // Handle coupon removal
    $(document).on('click', '.woocommerce-remove-coupon', function(e) {
        e.preventDefault();
        
        $('body').addClass('checkout-loading');
        $('.checkout-loading-overlay').show();
        
        var couponCode = $(this).data('coupon');
        
        // Use WooCommerce's built-in coupon removal
        $.ajax({
            type: 'POST',
            url: wc_checkout_params.wc_ajax_url.toString().replace('%%endpoint%%', 'remove_coupon'),
            data: {
                security: wc_checkout_params.remove_coupon_nonce,
                coupon: couponCode
            },
            success: function(response) {
                $('.woocommerce-error, .woocommerce-message, .woocommerce-info').remove();
                
                if (response) {
                    $('.woocommerce-notices-wrapper:first').html(response);
                    
                    // Update checkout to reflect changes
                    $body.trigger('update_checkout');
                }
            },
            complete: function() {
                $('body').removeClass('checkout-loading');
                $('.checkout-loading-overlay').hide();
            }
        });
    });

    // Handle coupon toggle
    $(document).on('click', '.coupon-toggle', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        
        var $toggle = $(this);
        if ($toggle.data('processing')) {
            return;
        }
        
        var $form = $toggle.next('.coupon-form-wrapper');
        
        // Set processing flag
        $toggle.data('processing', true);
        
        $toggle.toggleClass('active');
        
        if ($toggle.hasClass('active')) {
            $form.stop(true, true).slideDown(300, function() {
                $(this).addClass('active');
                $(this).find('#custom-coupon-code').focus();
                $toggle.data('processing', false);
            });
        } else {
            $form.stop(true, true).slideUp(300, function() {
                $(this).removeClass('active');
                $toggle.data('processing', false);
            });
        }
    });

    // Ensure we remove any stale processing flags on page updates
    $body.on('updated_checkout', function() {
        $('.coupon-toggle').data('processing', false);
    });

    // Also clean up on any errors
    $body.on('checkout_error', function() {
        $('.coupon-toggle').data('processing', false);
    });

    // And on fragment updates
    $(document).ajaxComplete(function(event, xhr, settings) {
        if (settings.url && (settings.url.indexOf('update-order-review') > -1 || 
                           settings.url.indexOf('apply_coupon') > -1 || 
                           settings.url.indexOf('remove_coupon') > -1)) {
            $('.coupon-toggle').data('processing', false);
        }
    });

    // Prevent default WooCommerce coupon behavior
    $(document).on('click', '.showcoupon', function(e) {
        e.preventDefault();
        e.stopPropagation();
        return false;
    });

    // Handle custom coupon application
    $(document).on('click', '.custom-coupon-section .apply-coupon', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var $button = $(this);
        var $section = $button.closest('.custom-coupon-section');
        var $input = $section.find('#custom-coupon-code');
        var couponCode = $input.val().trim();
        
        if (!couponCode) {
            return;
        }
        
        $button.prop('disabled', true).addClass('loading');
        $('body').addClass('checkout-loading');
        $('.checkout-loading-overlay').show();
        
        // Use WooCommerce's built-in coupon system
        $.ajax({
            type: 'POST',
            url: wc_checkout_params.wc_ajax_url.toString().replace('%%endpoint%%', 'apply_coupon'),
            data: {
                security: wc_checkout_params.apply_coupon_nonce,
                coupon_code: couponCode
            },
            success: function(response) {
                $('.woocommerce-error, .woocommerce-message, .woocommerce-info').remove();
                
                if (response) {
                    $('.woocommerce-notices-wrapper:first').html(response);
                    $body.trigger('update_checkout');
                }
            },
            complete: function() {
                $button.prop('disabled', false).removeClass('loading');
                $input.val('');
                $('body').removeClass('checkout-loading');
                $('.checkout-loading-overlay').hide();
            }
        });
    });

    // Handle custom coupon removal
    $(document).on('click', '.custom-coupon-section .remove-coupon', function(e) {
        e.preventDefault();
        
        var $link = $(this);
        var couponCode = $link.data('coupon');
        
        if (!couponCode) {
            return;
        }
        
        $link.addClass('loading');
        
        // Use WooCommerce's built-in coupon removal
        $.ajax({
            type: 'POST',
            url: wc_checkout_params.wc_ajax_url.toString().replace('%%endpoint%%', 'remove_coupon'),
            data: {
                security: wc_checkout_params.remove_coupon_nonce,
                coupon: couponCode
            },
            success: function(response) {
                $('.woocommerce-error, .woocommerce-message, .woocommerce-info').remove();
                
                if (response) {
                    $('.woocommerce-notices-wrapper:first').html(response);
                    $body.trigger('update_checkout');
                }
            },
            complete: function() {
                $link.removeClass('loading');
            }
        });
    });

    // Handle Enter key in coupon input
    $(document).on('keypress', '#custom-coupon-code', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $(this).closest('.custom-coupon-section').find('.apply-coupon').click();
        }
    });

    // Update custom coupon section when checkout is updated
    $body.on('updated_checkout', function() {
        var $customCouponSection = $('.custom-coupon-section');
        var $toggle = $('.coupon-toggle');
        var $form = $('.coupon-form-wrapper');
        
        if ($toggle.hasClass('active')) {
            $form.show().addClass('active');
        }
    });

    // Additional monitoring for checkout updates
    $body.on('update_checkout', function() {
        var $toggle = $('.coupon-toggle');
        var $form = $('.coupon-form-wrapper');
    });

    $body.on('checkout_error', function() {
        var $toggle = $('.coupon-toggle');
        var $form = $('.coupon-form-wrapper');
    });

    // Monitor fragment updates
    $(document).ajaxComplete(function(event, xhr, settings) {
        if (settings.url && settings.url.indexOf('update-order-review') > -1) {
            var $toggle = $('.coupon-toggle');
            var $form = $('.coupon-form-wrapper');
        }
    });

    // Remove old redirect code
    $(document).ready(function() {
        if (sessionStorage.getItem('redirect_to_checkout') === 'yes') {
            sessionStorage.removeItem('redirect_to_checkout');
        }
    });

    // Handle "Select all" checkboxes
    $body.on('change', '.select-all-consents', function() {
        var $checkbox = $(this);
        var participantIndex = $checkbox.data('participant-index');
        var productId = $checkbox.data('product-id');
        var isChecked = $checkbox.prop('checked');
        
        // Find all checkboxes in the same consent group including dropdown content
        var $consentGroup = $checkbox.closest('.primary-participant-consents, .participant-consents');
        var $dropdownContent = $consentGroup.find('.consent-dropdown-content');
        
        // Update all checkboxes in both the consent group and dropdown content
        $consentGroup.find('input[type="checkbox"]').not('.select-all-consents').prop('checked', isChecked);
        $dropdownContent.find('input[type="checkbox"]').prop('checked', isChecked);
    });

    // Update "Select all" checkbox state when individual checkboxes change
    $body.on('change', '.primary-participant-consents input[type="checkbox"], .participant-consents input[type="checkbox"], .consent-dropdown-content input[type="checkbox"]', function() {
        var $checkbox = $(this);
        if (!$checkbox.hasClass('select-all-consents')) {
            var $consentGroup = $checkbox.closest('.primary-participant-consents, .participant-consents');
            var $selectAll = $consentGroup.find('.select-all-consents');
            var $checkboxes = $consentGroup.find('input[type="checkbox"]').not('.select-all-consents');
            var $dropdownCheckboxes = $consentGroup.find('.consent-dropdown-content input[type="checkbox"]');
            
            // Combine all checkboxes for counting
            var $allCheckboxes = $checkboxes.add($dropdownCheckboxes);
            var allChecked = $allCheckboxes.length === $allCheckboxes.filter(':checked').length;
            
            $selectAll.prop('checked', allChecked);
        }
    });

    // Handle dropdown toggle functionality
    $body.on('click', '.consent-dropdown-toggle', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var $toggle = $(this);
        var targetId = $toggle.data('target');
        var $content = $('#' + targetId);
        var $arrow = $toggle.find('.dropdown-arrow');
        var isExpanded = $content.hasClass('expanded');
        
        if (isExpanded) {
            $content.removeClass('expanded');
            $toggle.contents().first()[0].textContent = 'Rozwiń ';
            $arrow.text('▼');
            $toggle.removeClass('expanded');
        } else {
            $content.addClass('expanded');
            $toggle.contents().first()[0].textContent = 'Zwiń ';
            $arrow.text('▲');
            $toggle.addClass('expanded');
        }
    });

    // Cookie consent handling
    $(document).ready(function() {
        const $cookieCheckbox = $('#cookieConsentCheckbox');
        if ($cookieCheckbox.length) {
            // Check if user has already consented to cookies
            const savedConsent = JSON.parse(localStorage.getItem('userCookieConsent') || '{}');
            if (savedConsent.analytics === true) {
                $cookieCheckbox.prop('checked', true);
            }
            // Handle cookie consent changes
            $cookieCheckbox.on('change', function() {
                const isChecked = $(this).prop('checked');
                if (isChecked) {
                    // User consented to cookies
                    const consentData = {
                        analytics: true,
                        marketing: true,
                        preferences: true
                    };
                    // Push to dataLayer - clear any existing ecommerce data first
                    if (window.dataLayerInstance) {
                        window.dataLayerInstance.trackCookieConsentUpdate(consentData);
                    }
                    // Update gtag consent if gtag is available
                    if (typeof gtag === 'function') {
                        gtag('consent', 'update', {
                            ad_storage: 'granted',
                            analytics_storage: 'granted',
                            functionality_storage: 'granted',
                            personalization_storage: 'granted'
                        });
                    }
                    // Save consent to localStorage
                    localStorage.setItem('userCookieConsent', JSON.stringify(consentData));
                } else {
                    // User revoked consent
                    const consentData = {
                        analytics: false,
                        marketing: false,
                        preferences: false
                    };
                    if (window.dataLayerInstance) {
                        window.dataLayerInstance.trackCookieConsentUpdate(consentData);
                    }
                    // Update gtag consent if gtag is available
                    if (typeof gtag === 'function') {
                        gtag('consent', 'update', {
                            ad_storage: 'denied',
                            analytics_storage: 'denied',
                            functionality_storage: 'denied',
                            personalization_storage: 'denied'
                        });
                    }
                    // Remove consent from localStorage
                    localStorage.removeItem('userCookieConsent');
                }
            });
        }
    });
}); 