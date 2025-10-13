<?php
/**
 * WooCommerce Archive Status
 */

defined('ABSPATH') || exit;

// Register new post status
function sa_add_custom_product_status() {
    register_post_status('archiwum', array(
        'label'                     => _x('Archiwum', 'woocommerce'),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Archiwum <span class="count">(%s)</span>', 'Archiwum <span class="count">(%s)</span>')
    ));
}
add_action('init', 'sa_add_custom_product_status');

// Add status to product dropdown
function sa_add_to_product_status_dropdown() {
    global $post;
    if ($post->post_type !== 'product') return;
    
    ?>
    <script>
        jQuery(document).ready(function($){
            $('select#post_status').append('<option value="archiwum" <?php echo ($post->post_status === 'archiwum' ? 'selected="selected"' : ''); ?>>Archiwum</option>');
        });
    </script>
    <?php
}
add_action('admin_footer-post.php', 'sa_add_to_product_status_dropdown');
add_action('admin_footer-post-new.php', 'sa_add_to_product_status_dropdown');

// Add to status list
function sa_add_to_product_status_list($views) {
    global $wpdb;
    
    $count = $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(*) 
        FROM $wpdb->posts 
        WHERE post_type = 'product' 
        AND post_status = %s", 
        'archiwum'
    ));
    
    if ($count > 0) {
        $views['archiwum'] = '<a href="edit.php?post_status=archiwum&post_type=product">' . __('Archiwum', 'woocommerce') . ' <span class="count">(' . $count . ')</span></a>';
    }
    
    return $views;
}
add_filter('views_edit-product', 'sa_add_to_product_status_list'); 