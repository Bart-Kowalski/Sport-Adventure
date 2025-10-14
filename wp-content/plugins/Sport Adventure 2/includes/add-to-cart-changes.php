<?php

// Change add to cart text on single product page and handle availability
add_filter('woocommerce_product_single_add_to_cart_text', 'woocommerce_add_to_cart_button_text_single', 20); 
function woocommerce_add_to_cart_button_text_single() {
    global $product;
    
    // Check if product exists
    if (!$product) return __('Zapisz się na wyprawę', 'woocommerce');
    
    // Let WooCommerce handle stock validation, we just change the text
    return __('Zapisz się na wyprawę', 'woocommerce');
}

// Change add to cart text on product archives page
add_filter('woocommerce_product_add_to_cart_text', 'woocommerce_add_to_cart_button_text_archives');  
function woocommerce_add_to_cart_button_text_archives() {
    global $product;
    // Let WooCommerce handle stock validation, we just change the text
    return __('Zapisz się na wyprawę', 'woocommerce');
}

// Modify the stock validation message
add_filter('woocommerce_get_availability_text', 'custom_get_availability_text', 10, 2);
function custom_get_availability_text($availability_text, $product) {
    if (!$product->is_in_stock()) {
        return 'Brak miejsc w tym terminie. Wybierz inny termin lub spróbuj wybrać inny wariant wyprawy';
    }
    return $availability_text;
}

// Change the add to cart validation message
add_filter('woocommerce_add_to_cart_validation_message', 'custom_add_to_cart_validation_message', 10, 3);
function custom_add_to_cart_validation_message($message, $product_id, $quantity) {
    return 'Brak miejsc w tym terminie. Wybierz inny termin lub spróbuj wybrać inny wariant wyprawy';
}

// Change the stock validation message in JavaScript
add_action('wp_footer', 'custom_stock_message_script');
function custom_stock_message_script() {
    if (!is_product()) return;
    ?>
    <script type="text/javascript">
    jQuery(function($){
        // Override WooCommerce's stock message
        if (typeof wc_add_to_cart_variation_params !== 'undefined') {
            wc_add_to_cart_variation_params.i18n_unavailable_text = 'Brak miejsc w tym terminie. Wybierz inny termin lub spróbuj wybrać inny wariant wyprawy';
        }
    });
    </script>
    <?php
}

add_action('woocommerce_before_variations_form', 'add_liczba_osob_label');

function add_liczba_osob_label() {
    // No need for inline styles as they are now in the CSS file
}

// Add quantity label and styling
add_action('woocommerce_before_add_to_cart_quantity', 'add_quantity_label_before');
function add_quantity_label_before() {
    echo '<label for="quantity" class="quantity-label">Liczba osób</label>';
}

// Add "ostatnie miejsce" message after quantity field
add_action('woocommerce_after_add_to_cart_quantity', 'add_ostatnie_miejsce_message');
function add_ostatnie_miejsce_message() {
    ?>
    <div class="ostatnie-miejsce-wrapper" style="display: none;">
        <p>Zostało ostatnie miejsce</p>
    </div>
    <script>
    jQuery(document).ready(function($) {
        var $ostanieMiejsceWrapper = $('.ostatnie-miejsce-wrapper');
        var $form = $('form.variations_form');
        
        function checkStockLevel(variation) {
            if (!variation || !variation.variation_id) {
                $ostanieMiejsceWrapper.hide();
                return;
            }
            
            // Check if the variation is in stock first
            if (!variation.is_in_stock) {
                $ostanieMiejsceWrapper.hide();
                return;
            }
            
            // Get stock quantity from variation data
            var stockQuantity = variation.stock_quantity || variation.max_qty || 0;
            
            // If stock management is disabled, don't show the message
            if (!variation.manage_stock && !variation.backorders_allowed) {
                $ostanieMiejsceWrapper.hide();
                return;
            }
            
            // Show "Zostało ostatnie miejsce" if stock is between 1-2 spots
            if (stockQuantity >= 1 && stockQuantity <= 2) {
                $ostanieMiejsceWrapper.show();
            } else {
                $ostanieMiejsceWrapper.hide();
            }
        }
        
        // Check on variation change
        $form.on('show_variation', function(event, variation) {
            checkStockLevel(variation);
        });
        
        // Hide when no variation selected
        $form.on('hide_variation', function() {
            $ostanieMiejsceWrapper.hide();
        });
        
        // Also listen for variation form updates
        $form.on('woocommerce_variation_has_changed', function() {
            var variationId = $form.find('input[name="variation_id"]').val();
            if (variationId) {
                var variations = $form.data('product_variations');
                if (variations) {
                    var variation = variations.find(function(v) {
                        return v.variation_id == variationId;
                    });
                    if (variation) {
                        checkStockLevel(variation);
                    }
                }
            }
        });
        
        // Check when attributes change
        $form.on('change', 'select[name^="attribute_"]', function() {
            setTimeout(function() {
                var variationId = $form.find('input[name="variation_id"]').val();
                if (variationId) {
                    var variations = $form.data('product_variations');
                    if (variations) {
                        var variation = variations.find(function(v) {
                            return v.variation_id == variationId;
                        });
                        if (variation) {
                            checkStockLevel(variation);
                        }
                    }
                }
            }, 100);
        });
        
        // Check initial state after form is loaded
        $form.on('wc_variation_form', function() {
            var $select = $(this).find('select[name^="attribute_"]');
            if ($select.val() !== '') {
                // Trigger change to check stock
                $select.trigger('change');
            }
        });
        
        // Also check when the form first loads with a default selection
        setTimeout(function() {
            var currentVariation = $form.find('input[name="variation_id"]').val();
            if (currentVariation) {
                var variations = $form.data('product_variations');
                if (variations) {
                    var variation = variations.find(function(v) {
                        return v.variation_id == currentVariation;
                    });
                    if (variation) {
                        checkStockLevel(variation);
                    }
                }
            }
        }, 500);
    });
    </script>
    <?php
}

// Update quantity field type
add_filter('woocommerce_quantity_input_args', 'custom_quantity_input_args', 10, 2);
function custom_quantity_input_args($args, $product) {
    $args['type'] = 'number';
    $args['label'] = 'Liczba uczestników wyprawy';
    $args['placeholder'] = 'Wprowadź liczbę uczestników';
    return $args;
}

// Disable add to cart button and show message when no spots available - use WooCommerce's default handling
add_filter('woocommerce_available_variation', 'check_variation_availability', 10, 3);
function check_variation_availability($variation_data, $product, $variation) {
    // Let WooCommerce handle stock validation
    if (!$variation->is_purchasable() || !$variation->is_in_stock()) {
        $variation_data['availability_html'] = '<p class="stock out-of-stock">Brak miejsc w tym terminie</p>';
    }
    
    // Add stock quantity information to variation data
    $variation_data['stock_quantity'] = $variation->get_stock_quantity();
    $variation_data['manage_stock'] = $variation->get_manage_stock();
    $variation_data['backorders_allowed'] = $variation->backorders_allowed();
    
    return $variation_data;
}

// Add price section at the very beginning of the form
add_action('woocommerce_before_add_to_cart_form', 'add_custom_price_section', 5);
function add_custom_price_section() {
    global $product;
    
    // Get variation data
    $variations = $product->get_available_variations();
    if (!empty($variations)) {
        $has_flight_price = false;
        $flight_price = 0;
        
        // We'll just create an empty placeholder that will be filled by JavaScript
        ?>
        <div class="custom-price-section">
            <div class="price-main"></div>
            <div class="price-flights-note"></div>
        </div>
        <script>
        jQuery(document).ready(function($) {
            // Remove the setTimeout and add immediate initialization
            var $form = $('form.variations_form');
            var $priceMain = $('.price-main');
            var $priceFlightsNote = $('.price-flights-note');
            
            function updatePriceSection(variation) {
                if (!variation || !variation.acf_fields) {
                    $priceMain.html('');
                    $priceFlightsNote.removeClass('active');
                    return;
                }
                
                var price = variation.acf_fields['wyprawa-termin__cena-nie-liczac-lotow'];
                var currency = variation.acf_fields['wyprawa-termin__waluta'];
                currency = currency && currency.trim() !== '' ? currency : 'PLN';
                var flightPrice = variation.acf_fields['wyprawa-termin__cena-lotu'];
                
                if (price) {
                    $priceMain.html('<span>Cena wyprawy</span><span>' + number_format(price, 0, ',', '') + ' ' + currency + '</span>');
                }
                
                if (flightPrice) {
                    $priceFlightsNote.html('<span>+ Stała cena biletów lotniczych</span><span>' + number_format(flightPrice, 0, ',', '') + ' PLN</span>').addClass('active');
                } else {
                    $priceFlightsNote.removeClass('active');
                }
            }
            
            function number_format(number, decimals, dec_point, thousands_sep) {
                number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
                var n = !isFinite(+number) ? 0 : +number,
                    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                    sep = (typeof thousands_sep === 'undefined') ? '' : thousands_sep,
                    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                    s = '',
                    toFixedFix = function (n, prec) {
                        var k = Math.pow(10, prec);
                        return '' + Math.round(n * k) / k;
                    };
                s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
                if (s[0].length > 3) {
                    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                }
                if ((s[1] || '').length < prec) {
                    s[1] = s[1] || '';
                    s[1] += new Array(prec - s[1].length + 1).join('0');
                }
                return s.join(dec);
            }
            
            // Initialize form
            $form.on('wc_variation_form', function() {
                // Let WooCommerce handle the default selection
            });
            
            // Update on variation change
            $form.on('show_variation', function(event, variation) {
                updatePriceSection(variation);
            });
            
            // Hide price when no variation is selected
            $form.on('hide_variation', function() {
                $priceMain.html('');
                $priceFlightsNote.removeClass('active');
            });
        });
        </script>
        <?php
    }
}

// Add payment info section to handle quantity
add_action('woocommerce_after_add_to_cart_quantity', 'add_payment_info_section');
function add_payment_info_section() {
    global $product;
    
    // Get variation data
    $variations = $product->get_available_variations();
    if (!empty($variations[0]['acf_fields'])) {
        $cena_bez_lotow = isset($variations[0]['acf_fields']['wyprawa-termin__cena-nie-liczac-lotow']) 
            ? $variations[0]['acf_fields']['wyprawa-termin__cena-nie-liczac-lotow'] 
            : 0;
            
        $waluta = isset($variations[0]['acf_fields']['wyprawa-termin__waluta']) 
            ? $variations[0]['acf_fields']['wyprawa-termin__waluta'] 
            : 'PLN';
        
        $termin_platnosci = isset($variations[0]['acf_fields']['wyprawa-termin__termin-platnosci-zaliczki']) 
            ? $variations[0]['acf_fields']['wyprawa-termin__termin-platnosci-zaliczki'] 
            : '';
            
        // Format the payment date
        $payment_date = 'do 60 dni przed wyprawą';
        if (!empty($termin_platnosci)) {
            $date_obj = DateTime::createFromFormat('d/m/Y', $termin_platnosci);
            $payment_date = $date_obj ? 'do ' . $date_obj->format('d.m.Y') : 'do 30 dni przed wyprawą';
        }
        
        ?>
        <div class="payment-info-section">
            <div class="deposit-row">
                <span class="deposit-label">Łączna zaliczka płatna do 7 dni</span>
                <span class="deposit-amount" data-deposit-amount></span>
            </div>
            <?php if ($cena_bez_lotow > 0): ?>
                <div class="remaining-payment">
                    <?php 
                    $cena_lotu = isset($variations[0]['acf_fields']['wyprawa-termin__cena-lotu']) 
                        ? $variations[0]['acf_fields']['wyprawa-termin__cena-lotu'] 
                        : 0;
                    
                    // Display with appropriate currency
                    if ($waluta !== 'PLN') {
                        if ($cena_lotu > 0) {
                            echo 'do 90 dni przed wyprawą płatne ' . number_format($cena_bez_lotow, 0, ',', ' ') . ' ' . $waluta . '<br>';
                            echo 'do 60 dni przed wyprawą płatne ' . number_format($cena_lotu, 0, ',', ' ') . ' PLN';
                        } else {
                            echo 'do 90 dni przed wyprawą płatne ' . number_format($cena_bez_lotow, 0, ',', ' ') . ' ' . $waluta;
                        }
                    } else {
                        if ($cena_lotu > 0) {
                            echo 'do 90 dni przed wyprawą płatne ' . number_format($cena_bez_lotow, 0, ',', ' ') . ' PLN<br>';
                            echo 'do 60 dni przed wyprawą płatne ' . number_format($cena_lotu, 0, ',', ' ') . ' PLN';
                        } else {
                            echo 'do 90 dni przed wyprawą płatne ' . number_format($cena_bez_lotow, 0, ',', ' ') . ' PLN';
                        }
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
        <script>
        jQuery(document).ready(function($) {
            function updatePrice(variation) {
                if (!variation || !variation.display_price) return;
                
                var qty = parseInt($('input.qty').val()) || 1;
                var currency = variation.acf_fields && variation.acf_fields['wyprawa-termin__waluta'] 
                    ? variation.acf_fields['wyprawa-termin__waluta'] 
                    : 'PLN';
                
                // Calculate deposit (woo price)
                var deposit = variation.display_price * qty;
                
                // Format deposit price - always in PLN
                var formatted_price = '<span class="woocommerce-Price-amount amount">';
                formatted_price += '<bdi>' + deposit.toLocaleString('pl-PL').replace(/\s/g, '') + '<span class="woocommerce-Price-currencySymbol">PLN</span></bdi>';
                formatted_price += '</span>';
                
                $('.deposit-amount').html(formatted_price);

                // Update the remaining payment text with correct currency
                if (variation.acf_fields) {
                    var cenaLotu = parseFloat(variation.acf_fields['wyprawa-termin__cena-lotu']) || 0;
                    var cenaBezLotow = parseFloat(variation.acf_fields['wyprawa-termin__cena-nie-liczac-lotow']) || 0;
                    var waluta = variation.acf_fields['wyprawa-termin__waluta'] || 'PLN';
                    var depositPrice = variation.display_price * qty;
                    
                    // Array to hold all payments so we can sort them
                    var payments = [];

                    // Handle Polish currency (PLN) cases
                    if (waluta === 'PLN') {
                        // Case 1: Polish trips (no flight price)
                        if (cenaLotu <= 0) {
                            var remaining = (cenaBezLotow * qty) - depositPrice;
                            if (remaining > 0) {
                                payments.push({
                                    days: 30,
                                    text: 'do 30 dni przed wyprawą płatne ' + remaining.toLocaleString('pl-PL').replace(/\s/g, '') + ' PLN'
                                });
                            }
                        } else {
                            // Case 2: Polish trips with flights
                            // First cover flight cost from deposit
                            var depositAfterFlight = depositPrice - (cenaLotu * qty);
                            
                            // Then use any remaining deposit to reduce main trip cost
                            var mainTripRemaining = (cenaBezLotow * qty);
                            if (depositAfterFlight > 0) {
                                mainTripRemaining -= depositAfterFlight;
                            }
                            
                            if (mainTripRemaining > 0) {
                                payments.push({
                                    days: 90,
                                    text: 'do 90 dni przed wyprawą płatne ' + mainTripRemaining.toLocaleString('pl-PL').replace(/\s/g, '') + ' PLN'
                                });
                            }
                            
                            // Only show flight payment if it's not fully covered by deposit
                            if (depositAfterFlight < 0) {
                                var flightRemaining = Math.abs(depositAfterFlight);
                                payments.push({
                                    days: 60,
                                    text: 'do 60 dni przed wyprawą płatne ' + flightRemaining.toLocaleString('pl-PL').replace(/\s/g, '') + ' PLN'
                                });
                            }
                        }
                    }
                    // Handle other currencies (e.g. USD)
                    else {
                        // For non-PLN currencies, always show the full trip cost in that currency
                        if (cenaBezLotow > 0) {
                            payments.push({
                                days: 90,
                                text: 'do 90 dni przed wyprawą płatne ' + (cenaBezLotow * qty).toLocaleString('pl-PL').replace(/\s/g, '') + ' ' + waluta
                            });
                        }
                        
                        // Flight payment (60 days) - always in PLN, subtract deposit (also in PLN)
                        if (cenaLotu > 0) {
                            var flightTotal = (cenaLotu * qty) - depositPrice;
                            if (flightTotal > 0) {
                                payments.push({
                                    days: 60,
                                    text: 'do 60 dni przed wyprawą płatne ' + flightTotal.toLocaleString('pl-PL').replace(/\s/g, '') + ' PLN'
                                });
                            }
                        }
                    }
                    
                    // Sort payments by days (chronologically)
                    payments.sort(function(a, b) {
                        return a.days - b.days;
                    });
                    
                    // Build the payment text
                    var paymentText = '';
                    for (var i = 0; i < payments.length; i++) {
                        if (i > 0) paymentText += '<br>';
                        paymentText += payments[i].text;
                    }
                    
                    $('.remaining-payment').html(paymentText);
                }
            }
            
            var $form = $('form.variations_form');
            
            // Update on variation change
            $form.on('show_variation', function(event, variation) {
                updatePrice(variation);
            });
            
            
            // Update on quantity change (for plus/minus buttons)
            $(document).on('click', '.quantity .plus, .quantity .minus', function() {
                setTimeout(function() {
                    var variation_id = $form.find('input[name="variation_id"]').val();
                    if (variation_id) {
                        var variation = $form.data('product_variations').find(function(v) {
                            return v.variation_id == variation_id;
                        });
                        updatePrice(variation);
                    }
                }, 100);
            });
        });
        </script>
        <?php
    }
}


// Safe date sorting for variation options (preserves user selection)
add_action('wp_footer', function() {
    if (!is_product()) return;
    ?>
    <script>
    jQuery(document).ready(function($) {
        // Function to safely sort options by date
        function safeSortTerminOptions($select) {
            if (!$select.length || $select.attr('id') !== 'pa_termin') return;
            
            // Get current selected value BEFORE sorting
            var currentValue = $select.val();
            
            // Get all options (except the first "choose an option")
            var $options = $select.find('option:not(:first)');
            var optionsArray = $options.toArray();

            // Sort options by date
            optionsArray.sort(function(a, b) {
                var dateA = a.value.match(/^(\d{2})-(\d{2})-(\d{4})/);
                var dateB = b.value.match(/^(\d{2})-(\d{2})-(\d{4})/);
                
                if (dateA && dateB) {
                    var yearA = dateA[3], monthA = dateA[2], dayA = dateA[1];
                    var yearB = dateB[3], monthB = dateB[2], dayB = dateB[1];
                    return (yearA + monthA + dayA) - (yearB + monthB + dayB);
                }
                return 0;
            });

            // Append sorted options back after the first option
            var $firstOption = $select.find('option:first');
            $select.html('').append($firstOption).append(optionsArray);
            
            // Restore the previously selected value
            if (currentValue) {
                $select.val(currentValue);
            }
        }

        // Sort only on initial page load (before user interaction)
        $(document).ready(function() {
            safeSortTerminOptions($('#pa_termin'));
        });

        // Sort when variations are first loaded (but not on subsequent changes)
        var hasUserInteracted = false;
        $(document).on('wc_variation_form', function() {
            if (!hasUserInteracted) {
                safeSortTerminOptions($('#pa_termin'));
            }
        });
        
        // Mark when user has interacted with the form
        $(document).on('change', 'select[name^="attribute_"]', function() {
            hasUserInteracted = true;
        });
    });
    </script>
    <?php
});

// Keep the original format_termin_attribute function
add_filter('bricks/dynamic_data/cf_attribute_pa_termin', 'format_termin_attribute');
function format_termin_attribute($value) {
    if (empty($value)) return $value;
    return str_replace('-', '.', $value);
}

// Add variation stock data to the page
add_action('woocommerce_before_variations_form', function() {
    global $product;
    if (!$product || !$product->is_type('variable')) return;
    
    $variations = $product->get_available_variations();
    $stock_data = array();
    $attributes = array();
    
    // First, collect all attributes and their values
    foreach ($variations as $variation) {
        foreach ($variation['attributes'] as $attr_key => $attr_value) {
            if (!isset($attributes[$attr_key])) {
                $attributes[$attr_key] = array();
            }
            if ($attr_value) {
                $attributes[$attr_key][$attr_value] = array();
            }
        }
    }
    
    // Then, for each variation, store its stock status and quantity for each attribute combination
    foreach ($variations as $variation) {
        $variation_obj = wc_get_product($variation['variation_id']);
        if ($variation_obj) {
            $is_in_stock = $variation_obj->is_in_stock() && $variation_obj->get_stock_quantity() !== 0;
            $stock_quantity = $variation_obj->get_stock_quantity();
            
            // For each attribute in this variation
            foreach ($variation['attributes'] as $current_attr_key => $current_attr_value) {
                if (!$current_attr_value) continue;
                
                // Create a key for other attributes combination
                $other_attrs = array();
                foreach ($variation['attributes'] as $attr_key => $attr_value) {
                    if ($attr_key !== $current_attr_key && $attr_value) {
                        $other_attrs[$attr_key] = $attr_value;
                    }
                }
                
                // Store the stock status and quantity for this combination
                $combination_key = $current_attr_value . '|' . json_encode($other_attrs);
                if (!isset($attributes[$current_attr_key][$current_attr_value][$combination_key])) {
                    $attributes[$current_attr_key][$current_attr_value][$combination_key] = array(
                        'in_stock' => $is_in_stock,
                        'stock_quantity' => $stock_quantity
                    );
                }
            }
        }
    }
    
    echo '<script>
        var variationAttributesData = ' . json_encode($attributes) . ';
        jQuery(document).ready(function($) {
            function updateAttributeOptions() {
                $("form.variations_form select").each(function() {
                    var $select = $(this);
                    var attributeName = $select.attr("name");
                    if (!attributeName || !variationAttributesData[attributeName]) return;
                    
                    // Get current values of other attributes
                    var otherAttrs = {};
                    $("form.variations_form select").not(this).each(function() {
                        var name = $(this).attr("name");
                        var value = $(this).val();
                        if (name && value) {
                            otherAttrs[name] = value;
                        }
                    });
                    
                    // Check each option in this select
                    $select.find("option").each(function() {
                        var $option = $(this);
                        var value = $option.val();
                        if (!value) return; // Skip empty value
                        
                        var combinations = variationAttributesData[attributeName][value] || {};
                        var hasInStockCombination = false;
                        var hasLowStockCombination = false;
                        
                        // Check if there\'s at least one in-stock combination for this value
                        for (var combinationKey in combinations) {
                            var [attrValue, otherAttrsJson] = combinationKey.split("|");
                            var combinationOtherAttrs = JSON.parse(otherAttrsJson);
                            var matches = true;
                            
                            // Check if this combination matches current other attribute values
                            for (var attr in otherAttrs) {
                                if (combinationOtherAttrs[attr] && combinationOtherAttrs[attr] !== otherAttrs[attr]) {
                                    matches = false;
                                    break;
                                }
                            }
                            
                            if (matches && combinations[combinationKey].in_stock) {
                                hasInStockCombination = true;
                                
                                // Check if this combination has low stock (1-2 items)
                                var stockQty = combinations[combinationKey].stock_quantity;
                                if (stockQty >= 1 && stockQty <= 2) {
                                    hasLowStockCombination = true;
                                }
                                break;
                            }
                        }
                        
                        // Update option text
                        var text = $option.text();
                        // Remove existing labels first
                        text = text.replace(" (brak miejsc)", "").replace(" (ostatnie miejsce)", "");
                        
                        if (!hasInStockCombination) {
                            $option.text(text + " (brak miejsc)");
                        } else if (hasLowStockCombination) {
                            $option.text(text + " (ostatnie miejsce)");
                        } else {
                            $option.text(text);
                        }
                    });
                });
            }
            
            // Update on page load
            updateAttributeOptions();
            
            // Update when any attribute changes
            $("form.variations_form").on("change", "select", function() {
                updateAttributeOptions();
            });
            
            // Update when variations are updated
            $("form.variations_form").on("woocommerce_update_variation_values", updateAttributeOptions);
        });
    </script>';
});