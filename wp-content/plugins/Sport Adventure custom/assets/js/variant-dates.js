jQuery(document).ready(function($) {
    function formatDate(value, $input) {
        if (!value) return '';
        
        // Get the expected format from the field's data attribute or default to d/m/Y
        var expectedFormat = $input.closest('.acf-field').data('date_format') || 'd/m/Y';
        var expectedSeparator = expectedFormat.indexOf('/') !== -1 ? '/' : '.';
        
        // Check if it's in the correct format with either separator
        var dateRegex = /^\d{2}[\/\.]\d{2}[\/\.]\d{4}$/;
        if (dateRegex.test(value)) {
            // Convert separators to match expected format
            return value.replace(/[\/\.]/g, expectedSeparator);
        }
        
        return '';
    }

    function initDatePickers() {
        $('.acf-field-date-picker input').each(function() {
            var $input = $(this);
            
            // Remove any existing event listeners
            $input.off('change blur');
            
            // Format and store initial value
            var initialValue = $input.val();
            if (initialValue) {
                var formattedValue = formatDate(initialValue, $input);
                if (formattedValue) {
                    $input.val(formattedValue).attr('data-saved-value', formattedValue);
                }
            }
            
            // Handle both change and blur events
            $input.on('change blur', function(e) {
                e.preventDefault();
                
                var newValue = $(this).val();
                
                if (newValue) {
                    newValue = formatDate(newValue, $(this));
                    
                    if (newValue) {
                        // Update all related fields
                        $(this).val(newValue).attr('data-saved-value', newValue);
                        
                        // Update hidden field if it exists
                        var hiddenField = $(this).siblings('input[type="hidden"]');
                        if (hiddenField.length) {
                            hiddenField.val(newValue);
                        }
                        
                        // Force variation update
                        var $variation = $(this).closest('.woocommerce_variation');
                        if ($variation.length) {
                            $variation.addClass('variation-needs-update');
                        }
                        
                        // Trigger events
                        $(this)
                            .trigger('input')
                            .trigger('change')
                            .trigger('keyup');
                    } else {
                        // If invalid format, revert to saved value
                        var savedValue = $(this).attr('data-saved-value') || '';
                        $(this).val(savedValue);
                    }
                }
            });
        });
    }

    // Initialize on variation load and add
    $('#woocommerce-product-data').on('woocommerce_variations_loaded woocommerce_variations_added', function() {
        acf.doAction('append', $('.woocommerce_variation'));
        setTimeout(initDatePickers, 100);
    });
    
    // Initialize when variations are saved
    $('#woocommerce-product-data').on('woocommerce_variations_saved', function() {
        setTimeout(initDatePickers, 100);
    });
    
    // Initialize when ACF fields are added
    acf.add_action('ready append', function($el) {
        initDatePickers();
    });
}); 