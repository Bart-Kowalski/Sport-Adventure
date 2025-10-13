<?php
/**
 * Custom Order Email Template
 *
 * @version 1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action('woocommerce_email_header', $email_heading, $email);

// Get order data
if (!isset($order) || !is_a($order, 'WC_Order')) {
    return;
}

$order_data = $order->get_data();
$items = $order->get_items();
$order_number = $order->get_order_number();

// Get shipping method
$shipping_method = $order->get_shipping_method();

// Get billing info
$billing_first_name = $order->get_billing_first_name();
$billing_last_name = $order->get_billing_last_name();
$billing_full_name = trim($billing_first_name . ' ' . $billing_last_name);
$billing_email = $order->get_billing_email();
$billing_phone = $order->get_billing_phone();

// Get participant data and deposit info (from plugin)
$deposit_handler = function_exists('sa_deposit_handler') ? sa_deposit_handler() : null;
$participant_handler = function_exists('sa_participant_handler') ? sa_participant_handler() : null;
?>

<!-- Greeting -->
<p>Cześć <?php echo esc_html($billing_first_name); ?>,</p>

<!-- Success Message Section -->
<div class="order-success-message">
    <h2>Dziękujemy! Twoje zamówienie zostało przyjęte.</h2>
    <p>Na Twój adres email wysłaliśmy potwierdzenie zamówienia.</p>
</div>

<!-- Bank Account Information -->
<div class="order-bank-details">
    <h3>Numery kont do płatności</h3>
    <div class="bank-account-section">
        <p class="bank-account-heading">Opłat w złotówkach należy dokonać przelewem na rachunek bankowy:</p>
        <div class="bank-account-number">PL  65 1140 2004 0000 3502 8268 8764;<br>SWIFT:BREXPLPWMBK</div>
    </div>
    <div class="transfer-details">
        <div class="bank-detail">
            <span class="bank-detail__label">Tytuł przelewu:</span>
            <span class="bank-detail__value">
                <?php 
                $order_item = reset($items);
                if ($order_item) {
                    echo esc_html($order_item->get_name());
                    
                    // Try to get the dates from variation
                    $variation_id = $order_item->get_variation_id();
                    if ($variation_id) {
                        $variation = wc_get_product($variation_id);
                        if ($variation) {
                            $attributes = $variation->get_variation_attributes();
                            if (isset($attributes['attribute_pa_termin'])) {
                                $termin = $attributes['attribute_pa_termin'];
                                // Format the date for display
                                echo ' - ' . str_replace('-', '.', $termin);
                            }
                        }
                    }
                }
                ?>
            </span>
        </div>
    </div>
</div>

<div class="order-company-details">
<h3>Dane firmy</h3>
<p>
    Sport Adventure Sp. z o. o.
    ul. Dywizjonu 303 155/30
    01-470 Warszawa, Polska
</p>
</div>


<!-- Order Details Section -->
<div class="order-details">
    <h3>Szczegóły zamówienia</h3>
    
    <?php foreach ($items as $item_id => $item) : 
        $product = $item->get_product();
        $product_id = $item->get_product_id();
        $product_name = $item->get_name();
        $quantity = $item->get_quantity();
        $total = $item->get_total();
        $variation_id = $item->get_variation_id();
    ?>
    <div class="order-item">
        <h4 class="order-item__title"><?php echo esc_html($product_name); ?></h4>
        
        <?php 
        // Get and display variation attributes
        if ($variation_id) {
            $variation = wc_get_product($variation_id);
            if ($variation) {
                $attributes = $variation->get_variation_attributes();
                if (!empty($attributes)) {
                    foreach ($attributes as $attribute_name => $attribute_value) {
                        $taxonomy = str_replace('attribute_', '', $attribute_name);
                        
                        // Get proper label based on attribute type
                        if ($taxonomy === 'pa_termin') {
                            $label = 'Termin';
                            $formatted_value = preg_replace('/(\d{2})-(\d{2})-(\d{4})-(\d{2})-(\d{2})-(\d{4})/', '$1.$2.$3 - $4.$5.$6', $attribute_value);
                        } else {
                            // For all other attributes, get the proper label from taxonomy
                            $label = wc_attribute_label($taxonomy, $variation);
                            // Get term name if it's a taxonomy
                            if (taxonomy_exists($taxonomy)) {
                                $term = get_term_by('slug', $attribute_value, $taxonomy);
                                $formatted_value = $term ? $term->name : $attribute_value;
                            } else {
                                $formatted_value = $attribute_value;
                            }
                        }
                        
                        echo $label . ': ' . esc_html($formatted_value) . '<br>';
                    }
                }
            }
        }
        ?>
        
        <div class="order-item__detail">
            <span class="order-item__label">Liczba uczestników:</span>
            <span class="order-item__value"><?php echo esc_html($quantity); ?></span>
        </div>
        
        <?php 
        // Display price details using the deposit handler if available
        if ($deposit_handler && $variation_id) {
            echo $deposit_handler->get_email_deposit_html($variation_id, $quantity);
        } else {
            // Fallback pricing display
            ?>
            <div class="order-item__detail">
                <span class="order-item__label">Cena:</span>
                <span class="order-item__value"><?php echo wc_price($total, array('currency' => $order->get_currency())); ?></span>
            </div>
            <?php
        }
        
        // Display participant data if available
        if ($participant_handler) {
            echo $participant_handler->get_formatted_email_participant_data($item);
        }
        ?>
    </div>
    <?php endforeach; ?>
    
    <!-- Applied Coupons Section -->
    <?php
    $applied_coupons = $order->get_coupon_codes();
    if (!empty($applied_coupons)) : ?>
    <div class="order-coupons">
        <div class="order-coupons__header">
            <h3>Zastosowane kupony</h3>
        </div>
        <?php foreach ($applied_coupons as $coupon_code) :
            $coupon = new WC_Coupon($coupon_code);
            $discount_amount = $order->get_discount_total();
        ?>
        <div class="order-coupons__detail">
            <span class="order-coupons__label">Kupon:</span>
            <span class="order-coupons__code"><?php echo esc_html($coupon_code); ?></span>
            <?php if ($discount_amount > 0) : ?>
            <span class="order-coupons__amount">(<?php echo wc_price($discount_amount, array('currency' => $order->get_currency(), 'decimals' => 0)); ?> zniżki)</span>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Order Totals -->
    <div class="order-totals">
        <div class="order-totals__header">
            <h3>Podsumowanie zamówienia</h3>
        </div>
        
        <?php
        $order_subtotal = $order->get_subtotal();
        $order_total = $order->get_total();
        $order_payment_method = $order->get_payment_method_title();
        ?>
        
        <div class="order-totals__detail">
            <span class="order-totals__label">Wartość zamówienia:</span>
            <span class="order-totals__amount"><?php echo wc_price($order_subtotal, array('currency' => $order->get_currency(), 'decimals' => 0)); ?> zł</span>
        </div>

        <?php if ($order->get_discount_total() > 0) : ?>
        <div class="order-totals__detail">
            <span class="order-totals__label">Rabat:</span>
            <span class="order-totals__amount">-<?php echo wc_price($order->get_discount_total(), array('currency' => $order->get_currency(), 'decimals' => 0)); ?> zł</span>
        </div>
        <?php endif; ?>
        
        <div class="order-totals__detail order-totals__detail--total">
            <span class="order-totals__label">Łączna kwota do zapłaty:</span>
            <span class="order-totals__amount"><?php echo wc_price($order_total, array('currency' => $order->get_currency(), 'decimals' => 0)); ?> zł</span>
        </div>
        
        <div class="order-totals__detail">
            <span class="order-totals__label">Metoda płatności:</span>
            <span class="order-totals__value"><?php echo esc_html($order_payment_method); ?></span>
        </div>
        
        <div class="order-totals__detail">
            <span class="order-totals__label">Numer zamówienia:</span>
            <span class="order-totals__value">#<?php echo esc_html($order_number); ?></span>
        </div>
        
        <div class="order-totals__detail">
            <span class="order-totals__label">Data zamówienia:</span>
            <span class="order-totals__value"><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></span>
        </div>
        
        <div class="order-totals__detail">
            <span class="order-totals__label">Email:</span>
            <span class="order-totals__value"><?php echo esc_html($billing_email); ?></span>
        </div>
        
        <?php if ($billing_phone) : ?>
        <div class="order-totals__detail">
            <span class="order-totals__label">Telefon:</span>
            <span class="order-totals__value"><?php echo esc_html($billing_phone); ?></span>
        </div>
        <?php endif; ?>
    </div>
</div>


<?php
// Call email footer action - this loads the footer template
do_action('woocommerce_email_footer', $email);
?> 