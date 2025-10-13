/**
 * ACF Variation Fields JavaScript
 * 
 * Handles initialization and management of ACF fields within WooCommerce product variations.
 * Ensures proper field functionality including date pickers, file uploads, and relationship fields.
 * 
 * @package Sport Adventure Custom
 * @version 1.0.0
 */

jQuery(document).ready(function($) {
    var initializedVariations = new Set();
    
    /**
     * Initialize ACF fields for all variations
     */
    function initVariationACFFields() {
        $('.woocommerce_variation .acf-fields').each(function() {
            var $container = $(this);
            var $variation = $container.closest('.woocommerce_variation');
            var variationId = $variation.data('variation_id') || $variation.attr('data-variation_id') || $container.data('variation-id');
            
            // Create a unique identifier for this variation
            var variationKey = variationId || $variation.index();
            
            // Skip if already initialized
            if (initializedVariations.has(variationKey)) {
                return;
            }
            
            if (typeof acf !== 'undefined') {
                // Mark as initialized
                initializedVariations.add(variationKey);
                $container.addClass('sa-acf-initialized');
                
                // Initialize ACF fields for this variation
                acf.doAction('ready', $container);
                acf.doAction('append', $container);
                
                // Force refresh of specific field types
                $container.find('.acf-field-relationship').each(function() {
                    if (typeof acf.getField !== 'undefined') {
                        var field = acf.getField($(this));
                        if (field && field.refresh) {
                            field.refresh();
                        }
                    }
                });
                
                $container.find('.acf-field-date_picker').each(function() {
                    if (typeof acf.getField !== 'undefined') {
                        var field = acf.getField($(this));
                        if (field && field.refresh) {
                            field.refresh();
                        }
                    }
                });
                
                $container.find('.acf-field-file').each(function() {
                    if (typeof acf.getField !== 'undefined') {
                        var field = acf.getField($(this));
                        if (field && field.refresh) {
                            field.refresh();
                        }
                    }
                });
            }
        });
    }
    
    // Event handlers for variation lifecycle
    $('#woocommerce-product-data').on('woocommerce_variations_loaded woocommerce_variations_added', function() {
        initializedVariations.clear();
        setTimeout(initVariationACFFields, 100);
    });
    
    $('#woocommerce-product-data').on('woocommerce_variations_saved', function() {
        initializedVariations.clear();
        setTimeout(initVariationACFFields, 200);
    });
    
    $(document).on('woocommerce_variation_form_updated', function() {
        initializedVariations.clear();
        setTimeout(initVariationACFFields, 300);
    });
    
    // ACF integration
    if (typeof acf !== 'undefined') {
        acf.addAction('ready', function($el) {
            if ($el.closest('.woocommerce_variation').length) {
                setTimeout(initVariationACFFields, 200);
            }
        });
    }
    
    // Initial load
    setTimeout(initVariationACFFields, 1000);
    
    // Fallback periodic check (reduced frequency for production)
    setInterval(function() {
        var uninitializedFields = $('.woocommerce_variation .acf-fields').not('.sa-acf-initialized');
        if (uninitializedFields.length > 0) {
            initializedVariations.clear();
            initVariationACFFields();
        }
    }, 5000);
});
