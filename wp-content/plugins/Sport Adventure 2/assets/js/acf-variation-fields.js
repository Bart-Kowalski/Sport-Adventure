/**
 * ACF Variation Fields JavaScript (OPTIMIZED)
 * 
 * Handles initialization and management of ACF fields within WooCommerce product variations.
 * Ensures proper field functionality including date pickers, file uploads, and relationship fields.
 * 
 * PERFORMANCE OPTIMIZATIONS:
 * - Reduced timeouts from 100-1000ms to 50-250ms for faster initialization
 * - Prevents duplicate initialization using a Set to track initialized variations
 * - Combines multiple operations in single timeouts to reduce delay
 * 
 * @package Sport Adventure Custom
 * @version 1.0.1
 */

jQuery(document).ready(function($) {
    var initializedVariations = new Set();
    
    /**
     * Initialize ACF fields for all variations
     */
    function initVariationACFFields() {
        $('.woocommerce_variation .acf-variation-fields').each(function() {
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
                
                // Find ACF fields container within the variation fields
                var $acfFields = $container.find('.acf-fields');
                
                if ($acfFields.length > 0) {
                    // Only initialize ACF fields if not already initialized
                    if (!$acfFields.hasClass('sa-acf-fields-initialized')) {
                        $acfFields.addClass('sa-acf-fields-initialized');
                        // Initialize ACF fields for this variation
                        acf.doAction('ready', $acfFields);
                        acf.doAction('append', $acfFields);
                    }
                    
                    // Force refresh of specific field types (but only if not already initialized)
                    $acfFields.find('.acf-field-relationship').each(function() {
                        if (typeof acf.getField !== 'undefined') {
                            var field = acf.getField($(this));
                            if (field && field.refresh && !$(this).hasClass('sa-field-refreshed')) {
                                field.refresh();
                                $(this).addClass('sa-field-refreshed');
                            }
                        }
                    });
                    
                    $acfFields.find('.acf-field-date_picker').each(function() {
                        if (typeof acf.getField !== 'undefined') {
                            var field = acf.getField($(this));
                            if (field && field.refresh && !$(this).hasClass('sa-field-refreshed')) {
                                field.refresh();
                                $(this).addClass('sa-field-refreshed');
                            }
                        }
                    });
                    
                    $acfFields.find('.acf-field-file').each(function() {
                        if (typeof acf.getField !== 'undefined') {
                            var field = acf.getField($(this));
                            if (field && field.refresh && !$(this).hasClass('sa-field-refreshed')) {
                                field.refresh();
                                $(this).addClass('sa-field-refreshed');
                            }
                        }
                    });
                    
                    $acfFields.find('.acf-field-radio').each(function() {
                        if (typeof acf.getField !== 'undefined') {
                            var field = acf.getField($(this));
                            if (field && field.refresh && !$(this).hasClass('sa-field-refreshed')) {
                                field.refresh();
                                $(this).addClass('sa-field-refreshed');
                            }
                        }
                    });
                    
                    $acfFields.find('.acf-field-number').each(function() {
                        if (typeof acf.getField !== 'undefined') {
                            var field = acf.getField($(this));
                            if (field && field.refresh && !$(this).hasClass('sa-field-refreshed')) {
                                field.refresh();
                                $(this).addClass('sa-field-refreshed');
                            }
                        }
                    });
                }
            }
        });
    }
    
    /**
     * Clean up duplicate expand details buttons in media library
     */
    function cleanupDuplicateExpandDetails() {
        // Check if we're in a media library context
        if ($('.media-frame-router').length > 0) {
            var $expandButtons = $('.acf-expand-details');
            // Remove all but the first one
            $expandButtons.slice(1).remove();
        }
    }
    
    // Event handlers for variation lifecycle
    $('#woocommerce-product-data').on('woocommerce_variations_loaded woocommerce_variations_added', function() {
        initializedVariations.clear();
        // Reduced timeout for faster loading
        setTimeout(function() {
            initVariationACFFields();
            cleanupDuplicateExpandDetails();
        }, 50);
    });
    
    $('#woocommerce-product-data').on('woocommerce_variations_saved', function() {
        initializedVariations.clear();
        // Reduced timeout for faster loading
        setTimeout(function() {
            initVariationACFFields();
            cleanupDuplicateExpandDetails();
        }, 50);
    });
    
    $(document).on('woocommerce_variation_form_updated', function() {
        initializedVariations.clear();
        // Reduced timeout for faster loading
        setTimeout(function() {
            initVariationACFFields();
            cleanupDuplicateExpandDetails();
        }, 50);
    });
    
    // ACF integration
    if (typeof acf !== 'undefined') {
        acf.addAction('ready', function($el) {
            if ($el.closest('.woocommerce_variation').length) {
                // Reduced timeout for faster loading
                setTimeout(initVariationACFFields, 50);
            }
        });
    }
    
            // Initial load - faster timeout
            setTimeout(initVariationACFFields, 250);

            // Fix stock field accessibility
            function fixStockFieldAccessibility() {
                // Fix all WooCommerce form fields to ensure they're clickable
                $('.woocommerce_variation .form-field input[type="number"], .woocommerce_variation .form-field input[type="text"], .woocommerce_variation .form-field input[type="email"], .woocommerce_variation .form-field select, .woocommerce_variation .form-field textarea').each(function() {
                    var $input = $(this);
                    $input.css({
                        'z-index': '70',
                        'position': 'relative',
                        'pointer-events': 'auto'
                    });
                });
                
                // Specifically fix stock management fields
                $('.woocommerce_variation .show_if_variation_manage_stock input[type="number"]').each(function() {
                    var $input = $(this);
                    $input.css({
                        'z-index': '80',
                        'position': 'relative',
                        'pointer-events': 'auto'
                    });
                    
                    // Ensure the parent container also has proper z-index
                    $input.closest('.show_if_variation_manage_stock').css({
                        'z-index': '60',
                        'position': 'relative'
                    });
                });
                
                // Fix price fields specifically
                $('.woocommerce_variation .form-field.woocommerce_variation_pricing input').each(function() {
                    var $input = $(this);
                    $input.css({
                        'z-index': '75',
                        'position': 'relative',
                        'pointer-events': 'auto'
                    });
                });
                
                // Ensure ACF fields stay below WooCommerce fields
                $('.acf-variation-fields').css({
                    'z-index': '1',
                    'position': 'relative'
                });
                
                $('.acf-variation-fields .acf-fields').css({
                    'z-index': '1',
                    'position': 'relative'
                });
            }

            // Run stock field fix on various events
            $('#woocommerce-product-data').on('woocommerce_variations_loaded woocommerce_variations_added woocommerce_variations_saved', function() {
                setTimeout(fixStockFieldAccessibility, 50);
            });

            // Initial stock field fix - faster timeout
            setTimeout(fixStockFieldAccessibility, 250);

            // Fallback periodic check (reduced frequency for production)
            setInterval(function() {
                var uninitializedFields = $('.woocommerce_variation .acf-fields').not('.sa-acf-fields-initialized');
                if (uninitializedFields.length > 0) {
                    // Only clear and reinitialize if there are actually uninitialized fields
                    var hasUninitialized = false;
                    uninitializedFields.each(function() {
                        if (!$(this).hasClass('sa-acf-fields-initialized')) {
                            hasUninitialized = true;
                            return false; // break
                        }
                    });
                    
                    if (hasUninitialized) {
                        // Only clear specific variations that need reinitialization
                        uninitializedFields.each(function() {
                            var $container = $(this).closest('.acf-variation-fields');
                            var $variation = $container.closest('.woocommerce_variation');
                            var variationId = $variation.data('variation_id') || $variation.attr('data-variation_id') || $container.data('variation-id');
                            var variationKey = variationId || $variation.index();
                            initializedVariations.delete(variationKey);
                        });
                        initVariationACFFields();
                    }
                }
                fixStockFieldAccessibility();
                cleanupDuplicateExpandDetails();
            }, 10000); // Increased interval to 10 seconds
});
