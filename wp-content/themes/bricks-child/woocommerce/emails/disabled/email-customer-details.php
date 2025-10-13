<?php
/**
 * Additional Customer Details
 */

if (!defined('ABSPATH')) {
    exit;
}

?>
<div class="customer-details">
    <h2><?php esc_html_e('Customer details', 'woocommerce'); ?></h2>
    <div class="customer-info">
        <?php foreach ($fields as $field) : ?>
            <div class="info-row">
                <strong class="info-label"><?php echo wp_kses_post($field['label']); ?>:</strong>
                <span class="info-value"><?php echo wp_kses_post($field['value']); ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
    .customer-details {
        padding: 24px;
        margin-top: 24px;
        background: #f8f8f8;
        border-radius: 4px;
    }
    
    .customer-info {
        margin-top: 16px;
    }
    
    .info-row {
        margin-bottom: 8px;
        display: flex;
        gap: 8px;
    }
    
    .info-label {
        min-width: 120px;
        color: #666;
    }
    
    @media screen and (max-width: 600px) {
        .customer-details {
            padding: 16px;
        }
        
        .info-row {
            flex-direction: column;
            gap: 4px;
        }
        
        .info-label {
            min-width: auto;
        }
    }
</style> 