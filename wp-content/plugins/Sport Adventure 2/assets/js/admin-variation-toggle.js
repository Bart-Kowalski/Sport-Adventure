/**
 * Toggle to show/hide disabled product variations in WooCommerce admin
 */
(function($) {
    'use strict';
    
    // Wait for DOM and WooCommerce scripts to be ready
    $(document).ready(function() {
        // Initialize after a short delay to ensure WooCommerce has loaded
        setTimeout(initVariationToggle, 500);
    });
    
    function initVariationToggle() {
        // Check if we're on the variations tab
        if ($('#variable_product_options').length === 0) {
            return;
        }
        
        // Add the toggle button to the toolbar
        addToggleButton();
        
        // Listen for variations being loaded
        $('#woocommerce-product-data').on('woocommerce_variations_loaded', function() {
            updateVariationVisibility();
        });
        
        // Initial check
        updateVariationVisibility();
    }
    
    function addToggleButton() {
        // Find the toolbar - try multiple selectors
        var $toolbar = $('#variable_product_options .toolbar-variations-defaults');
        
        if ($toolbar.length === 0) {
            $toolbar = $('#variable_product_options .toolbar').first();
        }
        
        if ($toolbar.length === 0) {
            console.log('Variation toggle: Could not find toolbar');
            return;
        }
        
        // Check if button already exists
        if ($('#sa-toggle-disabled-variations').length > 0) {
            return;
        }
        
        // Get saved state from localStorage (default to hiding disabled)
        var hideDisabled = localStorage.getItem('sa_hide_disabled_variations');
        if (hideDisabled === null) {
            // Default to hiding disabled variations
            hideDisabled = true;
            localStorage.setItem('sa_hide_disabled_variations', 'true');
        } else {
            hideDisabled = hideDisabled === 'true';
        }
        
        // Create the toggle button
        var buttonText = hideDisabled ? saVariationToggle.showDisabledText : saVariationToggle.hideDisabledText;
        var buttonTooltip = hideDisabled ? saVariationToggle.showDisabledTooltip : saVariationToggle.hideDisabledTooltip;
        var buttonClass = hideDisabled ? 'sa-showing-enabled-only' : '';
        
        var $button = $('<button>', {
            type: 'button',
            id: 'sa-toggle-disabled-variations',
            class: 'button sa-toggle-disabled-btn ' + buttonClass,
            'data-tip': buttonTooltip,
            html: '<span class="dashicons dashicons-visibility"></span> ' + buttonText
        });
        
        // Add click handler
        $button.on('click', function(e) {
            e.preventDefault();
            toggleDisabledVariations();
        });
        
        // Find the bulk actions select or pagination controls
        var $bulkActions = $toolbar.find('.variations-pagenav');
        
        if ($bulkActions.length > 0) {
            // Insert before pagination
            $button.insertBefore($bulkActions);
        } else {
            // Append to toolbar
            $toolbar.append($button);
        }
        
        // Add a separator for better visual spacing
        if ($bulkActions.length > 0) {
            $('<span class="sa-toggle-separator"></span>').insertBefore($bulkActions);
        }
    }
    
    function toggleDisabledVariations() {
        var $button = $('#sa-toggle-disabled-variations');
        var hideDisabled = !$button.hasClass('sa-showing-enabled-only');
        
        // Update button state
        if (hideDisabled) {
            $button.addClass('sa-showing-enabled-only');
            $button.find('.dashicons').removeClass('dashicons-visibility').addClass('dashicons-hidden');
            $button.html('<span class="dashicons dashicons-hidden"></span> ' + saVariationToggle.showDisabledText);
            $button.attr('data-tip', saVariationToggle.showDisabledTooltip);
        } else {
            $button.removeClass('sa-showing-enabled-only');
            $button.find('.dashicons').removeClass('dashicons-hidden').addClass('dashicons-visibility');
            $button.html('<span class="dashicons dashicons-visibility"></span> ' + saVariationToggle.hideDisabledText);
            $button.attr('data-tip', saVariationToggle.hideDisabledTooltip);
        }
        
        // Save state
        localStorage.setItem('sa_hide_disabled_variations', hideDisabled);
        
        // Update visibility
        updateVariationVisibility();
        
        // Show notification
        showNotification(hideDisabled);
    }
    
    function updateVariationVisibility() {
        var hideDisabled = localStorage.getItem('sa_hide_disabled_variations') === 'true';
        
        if (!hideDisabled) {
            // Show all variations
            $('.woocommerce_variation').removeClass('sa-variation-hidden').show();
            $('#sa-disabled-variations-notice').remove();
            return;
        }
        
        // Hide disabled variations
        var $variations = $('.woocommerce_variation');
        var hiddenCount = 0;
        
        $variations.each(function() {
            var $variation = $(this);
            var $enabledCheckbox = $variation.find('input[name^="variable_enabled"]');
            
            if ($enabledCheckbox.length > 0 && !$enabledCheckbox.is(':checked')) {
                $variation.addClass('sa-variation-hidden').hide();
                hiddenCount++;
            } else {
                $variation.removeClass('sa-variation-hidden').show();
            }
        });
        
        // Update or add notice about hidden variations
        updateHiddenNotice(hiddenCount);
    }
    
    function updateHiddenNotice(hiddenCount) {
        var $notice = $('#sa-disabled-variations-notice');
        
        if (hiddenCount === 0) {
            $notice.remove();
            return;
        }
        
        if ($notice.length === 0) {
            var noticeHtml = '<div id="sa-disabled-variations-notice" class="notice notice-info inline">' +
                '<p><span class="dashicons dashicons-info"></span> ' +
                'Wariantów wyłączonych: <strong>' + hiddenCount + '</strong> ' +
                '<a href="#" id="sa-show-all-variations">Pokaż wszystkie</a>' +
                '</p>' +
                '</div>';
            
            var $container = $('#variable_product_options_inner');
            if ($container.length > 0) {
                $container.prepend(noticeHtml);
                
                // Add click handler for "Show all" link
                $('#sa-show-all-variations').on('click', function(e) {
                    e.preventDefault();
                    $('#sa-toggle-disabled-variations').click();
                });
            }
        } else {
            // Update count
            $notice.find('p').html(
                '<span class="dashicons dashicons-info"></span> ' +
                'Wariantów wyłączonych: <strong>' + hiddenCount + '</strong> ' +
                '<a href="#" id="sa-show-all-variations">Pokaż wszystkie</a>'
            );
            
            // Re-attach click handler
            $('#sa-show-all-variations').on('click', function(e) {
                e.preventDefault();
                $('#sa-toggle-disabled-variations').click();
            });
        }
    }
    
    function showNotification(hideDisabled) {
        // Create a temporary notification
        var message = hideDisabled ? 
            'Wyłączone warianty są teraz ukryte' : 
            'Wszystkie warianty są teraz widoczne';
        
        var $notification = $('<div>', {
            class: 'sa-variation-notification',
            text: message
        });
        
        $('body').append($notification);
        
        // Trigger animation
        setTimeout(function() {
            $notification.addClass('show');
        }, 10);
        
        // Remove after 3 seconds
        setTimeout(function() {
            $notification.removeClass('show');
            setTimeout(function() {
                $notification.remove();
            }, 300);
        }, 3000);
    }
    
    // Watch for checkbox changes to update visibility in real-time
    $(document).on('change', 'input[name^="variable_enabled"]', function() {
        setTimeout(updateVariationVisibility, 100);
    });
    
    // Re-initialize when switching to variations tab
    $(document).on('click', '.variations_tab a', function() {
        setTimeout(initVariationToggle, 500);
    });
    
})(jQuery);

