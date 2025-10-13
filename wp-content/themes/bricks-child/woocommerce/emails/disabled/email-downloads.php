<?php
/**
 * Email Downloads
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="email-downloads">
    <h2><?php esc_html_e('Downloads', 'woocommerce'); ?></h2>
    
    <div class="download-files">
        <?php foreach ($downloads as $download) : ?>
            <div class="download-file">
                <div class="download-title">
                    <?php echo esc_html($download['title']); ?>
                </div>
                <?php if ($show_downloads_columns) : ?>
                    <div class="download-info">
                        <?php if ($download['access_expires']) : ?>
                            <div class="download-expires">
                                <span class="info-label"><?php esc_html_e('Expires:', 'woocommerce'); ?></span>
                                <span class="info-value"><?php echo esc_html($download['access_expires']); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ($download['downloads_remaining']) : ?>
                            <div class="download-remaining">
                                <span class="info-label"><?php esc_html_e('Downloads remaining:', 'woocommerce'); ?></span>
                                <span class="info-value"><?php echo esc_html($download['downloads_remaining']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="download-button">
                    <a class="download-link" href="<?php echo esc_url($download['download_url']); ?>">
                        <?php esc_html_e('Download', 'woocommerce'); ?>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
    .email-downloads {
        margin-bottom: 40px;
    }
    
    .download-files {
        margin-top: 16px;
    }
    
    .download-file {
        padding: 16px;
        background: #f8f8f8;
        border-radius: 4px;
        margin-bottom: 8px;
    }
    
    .download-file:last-child {
        margin-bottom: 0;
    }
    
    .download-title {
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    .download-info {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 12px;
        font-size: 0.9em;
        color: #666;
    }
    
    .info-label {
        font-weight: 600;
        margin-right: 4px;
    }
    
    .download-button {
        margin-top: 8px;
    }
    
    .download-link {
        display: inline-block;
        padding: 8px 16px;
        background: #ebe9eb;
        color: #515151;
        border-radius: 3px;
        text-decoration: none;
    }
    
    .download-link:hover {
        background: #dfdcde;
        color: #515151;
    }
    
    @media screen and (max-width: 600px) {
        .download-info {
            flex-direction: column;
            gap: 8px;
        }
    }
</style> 