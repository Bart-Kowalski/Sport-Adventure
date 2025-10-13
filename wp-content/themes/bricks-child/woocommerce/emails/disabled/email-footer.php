<?php
/**
 * Email Footer
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="template_footer">
                            <?php if ($credit = get_option('woocommerce_email_footer_text')) : ?>
                                <div id="credit"><?php echo wpautop(wp_kses_post(wptexturize($credit))); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </body>
        </html> 