<?php
/**
 * WooCommerce Variant Month Synchronization
 */

defined('ABSPATH') || exit;

// Add admin menu item
add_action('admin_menu', function() {
    add_submenu_page(
        'woocommerce',
        'Synchronizuj miesiące',
        'Synchronizuj miesiące',
        'manage_woocommerce',
        'sync-variant-months',
        'sa_render_sync_months_page'
    );
});

function sa_render_sync_months_page() {
    global $wpdb;
    $updated = false;
    $errors = [];
    
    // Handle form submission
    if (isset($_POST['sync_months']) && check_admin_referer('sync_variant_months')) {
        $updated = sa_sync_variant_months();
    }

    // Handle earliest date sync
    if (isset($_POST['sync_earliest_dates']) && check_admin_referer('sync_variant_months')) {
        $updated = sa_sync_earliest_dates();
    }
    
    // Handle create default months
    if (isset($_POST['create_default_months']) && check_admin_referer('sync_variant_months')) {
        $created = sa_create_default_months();
        $updated = true;
    }
    
    // Handle show debug logs
    if (isset($_POST['show_debug_logs']) && check_admin_referer('sync_variant_months')) {
        $debug_logs = sa_get_recent_debug_logs();
    }
    
    // Handle test calculations
    if (isset($_POST['test_calculations']) && check_admin_referer('sync_variant_months')) {
        $test_results = sa_test_month_calculations();
    }
    
    // Handle test ACF fields
    if (isset($_POST['test_acf_fields']) && check_admin_referer('sync_variant_months')) {
        $acf_test_results = sa_test_acf_fields();
    }
    
    // Handle debug products
    if (isset($_POST['debug_products']) && check_admin_referer('sync_variant_months')) {
        $product_debug_results = sa_debug_products_with_months();
    }
    
    // Handle debug taxonomy counts
    if (isset($_POST['debug_taxonomy_counts']) && check_admin_referer('sync_variant_months')) {
        $taxonomy_debug_results = sa_debug_taxonomy_counts();
    }
    
    // Handle debug ACF fields
    if (isset($_POST['debug_acf_fields']) && check_admin_referer('sync_variant_months')) {
        $acf_field_debug_results = sa_debug_acf_fields_on_variations();
    }
    
    // Handle cleanup months
    if (isset($_POST['cleanup_months']) && check_admin_referer('sync_variant_months')) {
        $cleanup_results = sa_cleanup_month_terms();
    }
    
    // Handle reset months
    if (isset($_POST['reset_months']) && check_admin_referer('sync_variant_months')) {
        $reset_results = sa_reset_month_terms();
    }
    
    // Get statistics
    $stats = sa_get_sync_statistics();
    
    ?>
    <div class="wrap">
        <h1>Synchronizacja miesięcy i terminów w wariantach</h1>
        
        <?php if ($updated): ?>
            <div class="notice notice-success">
                <p>Synchronizacja zakończona pomyślnie. Zaktualizowano <?php echo $updated; ?> produktów.</p>
            </div>
        <?php endif; ?>
        
        <?php if (isset($created) && $created > 0): ?>
            <div class="notice notice-success">
                <p>Utworzono <?php echo $created; ?> nowych terminów miesięcy.</p>
            </div>
        <?php endif; ?>
        
        <?php if (sa_is_php_debug_enabled()): ?>
            <div class="card">
                <h2>Debug Information</h2>
                <p><strong>Debug Mode:</strong> Enabled</p>
                <p>Check your error log for detailed debug information during sync operations.</p>
                <p><strong>Debug Log Location:</strong> <?php echo ini_get('error_log'); ?></p>
            </div>
        <?php endif; ?>
        
        <?php if (isset($debug_logs) && !empty($debug_logs)): ?>
            <div class="card">
                <h2>Recent Debug Logs</h2>
                <div style="background: #f1f1f1; padding: 10px; max-height: 400px; overflow-y: auto; font-family: monospace; font-size: 12px;">
                    <?php foreach ($debug_logs as $log): ?>
                        <div><?php echo esc_html($log); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (isset($test_results) && !empty($test_results)): ?>
            <div class="card">
                <h2>Test Results - Month Calculations</h2>
                <div style="background: #f1f1f1; padding: 10px; max-height: 400px; overflow-y: auto; font-family: monospace; font-size: 12px;">
                    <?php foreach ($test_results as $result): ?>
                        <div><?php echo esc_html($result); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (isset($acf_test_results) && !empty($acf_test_results)): ?>
            <div class="card">
                <h2>Test Results - ACF Fields</h2>
                <div style="background: #f1f1f1; padding: 10px; max-height: 400px; overflow-y: auto; font-family: monospace; font-size: 12px;">
                    <?php foreach ($acf_test_results as $result): ?>
                        <div><?php echo esc_html($result); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (isset($product_debug_results) && !empty($product_debug_results)): ?>
            <div class="card">
                <h2>Debug Products with Months</h2>
                <div style="background: #f1f1f1; padding: 10px; max-height: 400px; overflow-y: auto; font-family: monospace; font-size: 12px;">
                    <?php foreach ($product_debug_results as $result): ?>
                        <div><?php echo esc_html($result); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (isset($acf_field_debug_results) && !empty($acf_field_debug_results)): ?>
            <div class="card">
                <h2>Debug ACF Fields on Variations</h2>
                <div style="background: #f1f1f1; padding: 10px; max-height: 400px; overflow-y: auto; font-family: monospace; font-size: 12px;">
                    <?php foreach ($acf_field_debug_results as $result): ?>
                        <div><?php echo esc_html($result); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (isset($cleanup_results) && !empty($cleanup_results)): ?>
            <div class="card">
                <h2>Cleanup Results</h2>
                <div style="background: #f1f1f1; padding: 10px; max-height: 400px; overflow-y: auto; font-family: monospace; font-size: 12px;">
                    <?php foreach ($cleanup_results as $result): ?>
                        <div><?php echo esc_html($result); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (isset($reset_results) && !empty($reset_results)): ?>
            <div class="card">
                <h2>Reset Results</h2>
                <div style="background: #f1f1f1; padding: 10px; max-height: 400px; overflow-y: auto; font-family: monospace; font-size: 12px;">
                    <?php foreach ($reset_results as $result): ?>
                        <div><?php echo esc_html($result); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <h2>Statystyki</h2>
            <p>Liczba wariantów bez przypisanego miesiąca: <?php echo $stats['no_month']; ?></p>
            <p>Liczba wariantów z niezgodnym miesiącem: <?php echo $stats['mismatched']; ?></p>
            <p>Liczba wszystkich terminów miesięcy: <?php echo $stats['total_terms']; ?></p>
        </div>
        
        <div class="card">
            <h2>Dostępne miesiące</h2>
            <?php
            $all_terms = get_terms([
                'taxonomy' => 'miesiace',
                'hide_empty' => false,
                'orderby' => 'meta_value_num',
                'meta_key' => 'rok',
                'order' => 'ASC'
            ]);
            
            if (!empty($all_terms)) {
                echo '<table class="wp-list-table widefat fixed striped">';
                echo '<thead><tr><th>Nazwa</th><th>Rok</th><th>Numer miesiąca</th><th>Slug</th><th>Obliczony numer</th></tr></thead>';
                echo '<tbody>';
                
                foreach ($all_terms as $term) {
                    $rok = get_term_meta($term->term_id, 'rok', true);
                    $numer_miesiaca = get_term_meta($term->term_id, 'numer_miesiaca', true);
                    $custom_slug = get_term_meta($term->term_id, 'custom_slug', true);
                    $calculated_number = sa_calculate_month_number($rok, $numer_miesiaca);
                    
                    echo '<tr>';
                    echo '<td>' . esc_html($term->name) . '</td>';
                    echo '<td>' . esc_html($rok) . '</td>';
                    echo '<td>' . esc_html($numer_miesiaca) . '</td>';
                    echo '<td>' . esc_html($custom_slug) . '</td>';
                    echo '<td>' . esc_html($calculated_number) . '</td>';
                    echo '</tr>';
                }
                
                echo '</tbody></table>';
            } else {
                echo '<p>Brak terminów miesięcy.</p>';
            }
            ?>
        </div>
        
        <form method="post" action="">
            <?php wp_nonce_field('sync_variant_months'); ?>
            <p class="submit">
                <input type="submit" name="sync_months" class="button button-primary" value="Synchronizuj miesiące" style="margin-right: 10px;">
                <input type="submit" name="sync_earliest_dates" class="button button-primary" value="Synchronizuj najwcześniejsze terminy" style="margin-right: 10px;">
                <input type="submit" name="create_default_months" class="button button-secondary" value="Utwórz domyślne miesiące (2025-2026)" style="margin-right: 10px;">
                <input type="submit" name="show_debug_logs" class="button button-secondary" value="Pokaż logi debug" style="margin-right: 10px;">
                <input type="submit" name="test_calculations" class="button button-secondary" value="Testuj obliczenia" style="margin-right: 10px;">
                <input type="submit" name="test_acf_fields" class="button button-secondary" value="Testuj pola ACF" style="margin-right: 10px;">
                <input type="submit" name="debug_products" class="button button-secondary" value="Debug Products" style="margin-right: 10px;">
                <input type="submit" name="debug_taxonomy_counts" class="button button-secondary" value="Debug Taxonomy Counts" style="margin-right: 10px;">
                <input type="submit" name="debug_acf_fields" class="button button-secondary" value="Debug ACF Fields" style="margin-right: 10px;">
                <input type="submit" name="cleanup_months" class="button button-secondary" value="Wyczyść miesiące" style="margin-right: 10px;">
                <input type="submit" name="reset_months" class="button button-secondary" value="Resetuj wszystkie miesiące" style="color: red;" onclick="return confirm('Czy na pewno chcesz usunąć wszystkie miesiące i ich przypisania? Ta operacja jest nieodwracalna!');">
            </p>
        </form>
    </div>
    <?php
}

function sa_get_sync_statistics() {
    global $wpdb;
    
    $stats = [
        'no_month' => 0,
        'mismatched' => 0,
        'updated' => 0,
        'total_terms' => 0
    ];
    
    // Get total terms count
    $all_terms = get_terms([
        'taxonomy' => 'miesiace',
        'hide_empty' => false
    ]);
    $stats['total_terms'] = !empty($all_terms) ? count($all_terms) : 0;
    
    // Get all product variations with start dates using ACF (including unpublished)
    $variations = $wpdb->get_results("
        SELECT p.ID
        FROM {$wpdb->posts} p
        WHERE p.post_type = 'product_variation'
        AND p.post_status IN ('publish', 'private', 'draft')
    ");
    
    foreach ($variations as $variation) {
        // Get parent product ID
        $parent_product_id = wp_get_post_parent_id($variation->ID);
        if (!$parent_product_id) {
            continue;
        }
        
        // Get current month term on parent product
        $current_terms = wp_get_object_terms($parent_product_id, 'miesiace', ['fields' => 'ids']);
        $current_month_id = !empty($current_terms) ? $current_terms[0] : null;
        
        // Get expected month from start date using ACF
        $start_date = get_field('wyprawa-termin__data-poczatkowa', $variation->ID);
        if (!$start_date) {
            continue;
        }
        
        // Parse the start date - try different formats
        $date = null;
        $date_formats = ['d.m.Y', 'Ymd', 'Y-m-d', 'Y/m/d', 'd-m-Y', 'd/m/Y'];
        
        foreach ($date_formats as $format) {
            $date = DateTime::createFromFormat($format, $start_date);
            if ($date) {
                break;
            }
        }
        
        if (!$date) {
            continue;
        }
        
        // Get the year and month number
        $year = $date->format('Y');
        $month_number = $date->format('n');
        
        // Get or create the term for this month
        $expected_term = sa_get_month_term($year, $month_number);
        
        if (!$expected_term) {
            // Create the month term if it doesn't exist
            $expected_term = sa_create_month_term($year, $month_number);
        }
        
        if (!$expected_term) {
            continue;
        }
        
        if (!$current_month_id) {
            $stats['no_month']++;
        } elseif ($current_month_id != $expected_term->term_id) {
            $stats['mismatched']++;
        }
    }
    
    return $stats;
}

function sa_sync_variant_months() {
    global $wpdb;
    $updated = 0;
    $debug_log = [];
    
    // Get all product variations with start dates using ACF (including unpublished)
    $variations = $wpdb->get_results("
        SELECT p.ID, p.post_title
        FROM {$wpdb->posts} p
        WHERE p.post_type = 'product_variation'
        AND p.post_status IN ('publish', 'private', 'draft')
    ");
    
    $debug_log[] = "Found " . count($variations) . " variations to check";
    
    $variations_with_dates = 0;
    $variations_processed = 0;
    
    foreach ($variations as $variation) {
        $debug_entry = "Variation ID {$variation->ID} ({$variation->post_title}): ";
        
        // Get start date using ACF
        $start_date = get_field('wyprawa-termin__data-poczatkowa', $variation->ID);
        if (!$start_date) {
            $debug_entry .= "No ACF start date found";
            $debug_log[] = $debug_entry;
            continue;
        }
        
        $variations_with_dates++;
        $debug_entry .= "ACF date: {$start_date}, ";
        
        // Parse the start date - try different formats
        $date = null;
        $date_formats = ['d.m.Y', 'Ymd', 'Y-m-d', 'Y/m/d', 'd-m-Y', 'd/m/Y'];
        
        foreach ($date_formats as $format) {
            $date = DateTime::createFromFormat($format, $start_date);
            if ($date) {
                $debug_entry .= "parsed with format {$format}, ";
                break;
            }
        }
        
        if (!$date) {
            $debug_entry .= "Invalid date format: {$start_date}";
            $debug_log[] = $debug_entry;
            continue;
        }
        
        // Get the year and month number
        $year = $date->format('Y');
        $month_number = $date->format('n');
        $year_offset = sa_calculate_year_number($year);
        $calculated_month_number = sa_calculate_month_number($year, $month_number);
        
        $debug_entry .= "Parsed date: {$date->format('Y-m-d')}, Year: {$year}, Month: {$month_number}, Year offset: {$year_offset}, Calculated: {$calculated_month_number}";
        
        // Get or create the term for this month
        $term = sa_get_month_term($year, $month_number);
        
        if (!$term) {
            $debug_entry .= ", Creating new term";
            // Create the month term if it doesn't exist
            $term = sa_create_month_term($year, $month_number);
            if ($term) {
                $debug_entry .= " (Created: {$term->name}, ID: {$term->term_id})";
            } else {
                $debug_entry .= " (FAILED to create)";
            }
        } else {
            $debug_entry .= ", Found existing term: {$term->name} (ID: {$term->term_id})";
        }
        
        if ($term) {
            // Get parent product ID
            $parent_product_id = wp_get_post_parent_id($variation->ID);
            if (!$parent_product_id) {
                $debug_entry .= ", No parent product found";
                $debug_log[] = $debug_entry;
                continue;
            }
            
            // Get current terms on parent product
            $current_terms = wp_get_object_terms($parent_product_id, 'miesiace', ['fields' => 'ids']);
            $current_month_id = !empty($current_terms) ? $current_terms[0] : null;
            
            $debug_entry .= ", Parent product ID: {$parent_product_id}, Current term ID: " . ($current_month_id ?: 'none');
            
            // Update only if different
            if ($current_month_id != $term->term_id) {
                $result = wp_set_object_terms($parent_product_id, [$term->term_id], 'miesiace');
                if (!is_wp_error($result)) {
                    $updated++;
                    $variations_processed++;
                    $debug_entry .= ", UPDATED parent product to term {$term->term_id}";
                } else {
                    $debug_entry .= ", FAILED to update parent product: " . $result->get_error_message();
                }
            } else {
                $debug_entry .= ", No change needed on parent product";
            }
        } else {
            $debug_entry .= ", No term available";
        }
        
        $debug_log[] = $debug_entry;
    }
    
    // Add summary to debug log
    $debug_log[] = "";
    $debug_log[] = "SYNC SUMMARY:";
    $debug_log[] = "Total variations checked: " . count($variations);
    $debug_log[] = "Variations with ACF dates: " . $variations_with_dates;
    $debug_log[] = "Variations processed: " . $variations_processed;
    $debug_log[] = "Total updates made: " . $updated;
    
    // Log debug information
    if (sa_is_php_debug_enabled()) {
        error_log("SA Month Sync Debug: " . implode("\n", $debug_log));
    }
    
    return $updated;
}

function sa_sync_earliest_dates() {
    global $wpdb;
    $updated = 0;
    
    // Get all products that have variations
    $products = $wpdb->get_results("
        SELECT DISTINCT p.post_parent as product_id
        FROM {$wpdb->posts} p
        WHERE p.post_type = 'product_variation'
        AND p.post_status = 'publish'
    ");
    
    foreach ($products as $product) {
        // Get current value for comparison
        $current_earliest = get_field('wyprawa__najblizszy-termin', $product->product_id);
        
        // Get all published variations for this product
        $variations = $wpdb->get_results($wpdb->prepare("
            SELECT p.ID, pm.meta_value as start_date
            FROM {$wpdb->posts} p
            LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            WHERE p.post_type = 'product_variation'
            AND p.post_parent = %d
            AND p.post_status = 'publish'
            AND pm.meta_key = 'wyprawa-termin__data-poczatkowa'
            AND pm.meta_value != ''
        ", $product->product_id));
        
        if (empty($variations)) {
            continue;
        }
        
        // Find earliest date among variations
        $earliest_date = null;
        foreach ($variations as $variation) {
            $date = DateTime::createFromFormat('Ymd', $variation->start_date);
            if (!$date) {
                continue;
            }
            
            if ($earliest_date === null || $date < $earliest_date) {
                $earliest_date = $date;
            }
        }
        
        if ($earliest_date) {
            // Format the date in the ACF field's expected format (d.m.Y)
            $new_date_value = $earliest_date->format('d.m.Y');
            
            // Check if the date actually needs updating
            if ($current_earliest !== $new_date_value) {
                $update_result = update_field('wyprawa__najblizszy-termin', $new_date_value, $product->product_id);
                
                if ($update_result) {
                    $updated++;
                }
            }
        }
    }
    
    return $updated;
}

function sa_create_default_months() {
    $created = 0;
    
    // Create months for 2025 and 2026
    for ($year = 2025; $year <= 2026; $year++) {
        for ($month = 1; $month <= 12; $month++) {
            // Check if month already exists
            $existing_term = sa_get_month_term($year, $month);
            if (!$existing_term) {
                $term = sa_create_month_term($year, $month);
                if ($term) {
                    $created++;
                }
            }
        }
    }
    
    return $created;
}

function sa_get_recent_debug_logs() {
    $log_file = ini_get('error_log');
    if (!$log_file || !file_exists($log_file)) {
        return ['No debug log file found or accessible.'];
    }
    
    $logs = file_get_contents($log_file);
    $lines = explode("\n", $logs);
    
    // Filter for SA debug logs and get last 50 lines
    $sa_logs = array_filter($lines, function($line) {
        return strpos($line, 'SA Month') !== false;
    });
    
    return array_slice(array_reverse($sa_logs), 0, 50);
}

function sa_test_month_calculations() {
    $results = [];
    $results[] = "Testing month calculations:";
    $results[] = "========================";
    
    // Test the year calculation function directly
    $results[] = "Testing year calculation function:";
    for ($year = 2024; $year <= 2028; $year++) {
        $year_offset = sa_calculate_year_number($year);
        $results[] = "Year {$year} -> Offset: {$year_offset}";
    }
    $results[] = "";
    
    // Test years 2025-2027
    for ($year = 2025; $year <= 2027; $year++) {
        $results[] = "Year {$year}:";
        for ($month = 1; $month <= 12; $month++) {
            $year_number = sa_calculate_year_number($year);
            $month_number = sa_calculate_month_number($year, $month);
            $month_names = [
                1 => 'Styczeń', 2 => 'Luty', 3 => 'Marzec', 4 => 'Kwiecień',
                5 => 'Maj', 6 => 'Czerwiec', 7 => 'Lipiec', 8 => 'Sierpień',
                9 => 'Wrzesień', 10 => 'Październik', 11 => 'Listopad', 12 => 'Grudzień'
            ];
            $month_name = $month_names[$month];
            $results[] = "  {$month_name} {$year}: Year#={$year_number}, Month#={$month_number}";
        }
        $results[] = "";
    }
    
    // Test some specific dates
    $test_dates = [
        '20250101', // Styczeń 2025
        '20251201', // Grudzień 2025
        '20260101', // Styczeń 2026
        '20261201', // Grudzień 2026
        '20270101', // Styczeń 2027
    ];
    
    $results[] = "Testing specific dates:";
    $results[] = "=====================";
    
    foreach ($test_dates as $date_str) {
        $date = DateTime::createFromFormat('Ymd', $date_str);
        if ($date) {
            $year = $date->format('Y');
            $month = $date->format('n');
            $month_name = $date->format('F');
            $year_offset = sa_calculate_year_number($year);
            $calculated = sa_calculate_month_number($year, $month);
            $results[] = "Date: {$date_str} ({$month_name} {$year}) -> Year offset: {$year_offset}, Calculated: {$calculated}";
        }
    }
    
    return $results;
}

function sa_test_acf_fields() {
    $results = [];
    $results[] = "Testing ACF field access:";
    $results[] = "=======================";
    
    // Get some product variations to test
    global $wpdb;
    $variations = $wpdb->get_results("
        SELECT p.ID, p.post_title
        FROM {$wpdb->posts} p
        WHERE p.post_type = 'product_variation'
        AND p.post_status = 'publish'
        LIMIT 10
    ");
    
    $results[] = "Found " . count($variations) . " variations to test";
    $results[] = "";
    
    foreach ($variations as $variation) {
        $results[] = "Variation ID {$variation->ID} ({$variation->post_title}):";
        
        // Test ACF field access
        $acf_date = get_field('wyprawa-termin__data-poczatkowa', $variation->ID);
        $results[] = "  ACF field value: " . ($acf_date ? $acf_date : 'NULL/EMPTY');
        
        // Test post meta access
        $meta_date = get_post_meta($variation->ID, 'wyprawa-termin__data-poczatkowa', true);
        $results[] = "  Post meta value: " . ($meta_date ? $meta_date : 'NULL/EMPTY');
        
        // Test all meta fields for this variation
        $all_meta = get_post_meta($variation->ID);
        $date_meta = [];
        foreach ($all_meta as $key => $value) {
            if (strpos($key, 'data') !== false || strpos($key, 'termin') !== false) {
                $date_meta[$key] = $value[0] ?? 'empty';
            }
        }
        
        if (!empty($date_meta)) {
            $results[] = "  All date/termin meta fields:";
            foreach ($date_meta as $key => $value) {
                $results[] = "    {$key}: {$value}";
            }
        }
        
        $results[] = "";
    }
    
    return $results;
}

function sa_debug_products_with_months() {
    $results = [];
    $results[] = "Debugging products with month assignments:";
    $results[] = "==========================================";
    
    // Test the exact query that's failing
    $results[] = "Testing your exact query:";
    $terms_2026 = get_terms([
        'taxonomy' => 'miesiace',
        'meta_key' => 'rok',
        'meta_value' => '2026',
        'fields' => 'ids'
    ]);
    
    $results[] = "Found " . count($terms_2026) . " terms with rok=2026";
    $results[] = "Term IDs: " . (empty($terms_2026) ? 'NONE' : implode(', ', $terms_2026));
    $results[] = "";
    
    if (empty($terms_2026)) {
        $results[] = "❌ No terms found - this is why your query fails!";
        return $results;
    }
    
    // Test the WP_Query that should work
    $results[] = "Testing WP_Query with these terms:";
    $query_args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => 10,
        'orderby' => 'title',
        'order' => 'ASC',
        'tax_query' => [
            [
                'taxonomy' => 'miesiace',
                'field' => 'term_id',
                'terms' => $terms_2026,
                'operator' => 'IN'
            ]
        ]
    ];
    
    $products_query = new WP_Query($query_args);
    $results[] = "WP_Query found " . $products_query->found_posts . " products";
    $results[] = "";
    
    if ($products_query->found_posts == 0) {
        $results[] = "❌ No products assigned to these month terms!";
        $results[] = "This is why your Bricks Builder query returns nothing.";
        $results[] = "";
        $results[] = "Solutions:";
        $results[] = "1. Run 'Synchronizuj miesiące' to assign terms to products";
        $results[] = "2. Check if products have ACF date fields";
        $results[] = "3. Verify the sync process worked correctly";
    } else {
        $results[] = "✅ Found products! Your query should work.";
        $results[] = "Products found:";
        foreach ($products_query->posts as $product) {
            $results[] = "  - {$product->post_title} (ID: {$product->ID})";
        }
    }
    
    $results[] = "";
    $results[] = "Checking individual product assignments:";
    
    // Check a few products to see their month assignments
    $sample_products = get_posts([
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => 5
    ]);
    
    foreach ($sample_products as $product) {
        $month_terms = wp_get_object_terms($product->ID, 'miesiace', ['fields' => 'all']);
        $results[] = "Product: {$product->post_title} (ID: {$product->ID})";
        $results[] = "  Month terms assigned: " . count($month_terms);
        
        foreach ($month_terms as $term) {
            $rok = get_term_meta($term->term_id, 'rok', true);
            $year = get_term_meta($term->term_id, 'year', true);
            $results[] = "    - {$term->name} (ID: {$term->term_id}) - rok: {$rok}, year: {$year}";
        }
        $results[] = "";
    }
    
    return $results;
}

function sa_debug_acf_fields_on_variations() {
    $results = [];
    $results[] = "Debugging ACF fields on product variations:";
    $results[] = "=============================================";
    
    // Get some product variations to test (including unpublished)
    global $wpdb;
    $variations = $wpdb->get_results("
        SELECT p.ID, p.post_title, p.post_parent, p.post_status
        FROM {$wpdb->posts} p
        WHERE p.post_type = 'product_variation'
        AND p.post_status IN ('publish', 'private', 'draft')
        ORDER BY p.post_status, p.ID
        LIMIT 15
    ");
    
    $results[] = "Found " . count($variations) . " variations to check";
    $results[] = "";
    
    foreach ($variations as $variation) {
        $results[] = "Variation ID {$variation->ID} ({$variation->post_title}) [Status: {$variation->post_status}]:";
        $results[] = "  Parent Product ID: {$variation->post_parent}";
        
        // Test ACF field access
        $acf_date = get_field('wyprawa-termin__data-poczatkowa', $variation->ID);
        $results[] = "  ACF field 'wyprawa-termin__data-poczatkowa': " . ($acf_date ? $acf_date : 'NULL/EMPTY');
        
        // Test post meta access
        $meta_date = get_post_meta($variation->ID, 'wyprawa-termin__data-poczatkowa', true);
        $results[] = "  Post meta 'wyprawa-termin__data-poczatkowa': " . ($meta_date ? $meta_date : 'NULL/EMPTY');
        
        // Test all ACF fields on this variation
        $all_fields = get_fields($variation->ID);
        if ($all_fields) {
            $results[] = "  All ACF fields on this variation:";
            foreach ($all_fields as $field_name => $field_value) {
                if (strpos($field_name, 'data') !== false || strpos($field_name, 'termin') !== false) {
                    $results[] = "    {$field_name}: " . (is_array($field_value) ? json_encode($field_value) : $field_value);
                }
            }
        } else {
            $results[] = "  No ACF fields found on this variation";
        }
        
        // Test all meta fields
        $all_meta = get_post_meta($variation->ID);
        $date_meta = [];
        foreach ($all_meta as $key => $value) {
            if (strpos($key, 'data') !== false || strpos($key, 'termin') !== false) {
                $date_meta[$key] = $value[0] ?? 'empty';
            }
        }
        
        if (!empty($date_meta)) {
            $results[] = "  All date/termin meta fields:";
            foreach ($date_meta as $key => $value) {
                $results[] = "    {$key}: {$value}";
            }
        }
        
        $results[] = "";
    }
    
    $results[] = "If no ACF fields are found, the issue might be:";
    $results[] = "1. ACF fields are not set up on product variations";
    $results[] = "2. Field name is different than 'wyprawa-termin__data-poczatkowa'";
    $results[] = "3. ACF is not active or not working properly";
    $results[] = "4. Fields are set on parent products, not variations";
    
    return $results;
}

function sa_cleanup_month_terms() {
    $results = [];
    $results[] = "Starting cleanup of month terms...";
    
    // Get all existing month terms
    $all_terms = get_terms([
        'taxonomy' => 'miesiace',
        'hide_empty' => false
    ]);
    
    $results[] = "Found " . count($all_terms) . " existing month terms";
    
    $updated = 0;
    $errors = 0;
    $deleted = 0;
    
    // Month name mapping
    $month_names = [
        1 => 'Styczeń', 2 => 'Luty', 3 => 'Marzec', 4 => 'Kwiecień',
        5 => 'Maj', 6 => 'Czerwiec', 7 => 'Lipiec', 8 => 'Sierpień',
        9 => 'Wrzesień', 10 => 'Październik', 11 => 'Listopad', 12 => 'Grudzień'
    ];
    
    foreach ($all_terms as $term) {
        $year = get_term_meta($term->term_id, 'year', true);
        $rok = get_term_meta($term->term_id, 'rok', true);
        $month_number = get_term_meta($term->term_id, 'numer_miesiaca', true);
        $calculated_number = get_term_meta($term->term_id, 'calculated_number', true);
        
        $results[] = "Processing term: {$term->name} (ID: {$term->term_id})";
        $results[] = "  Current year: '{$year}', rok: '{$rok}', month: '{$month_number}', calculated: '{$calculated_number}'";
        
        // Migrate from 'year' to 'rok' if needed
        if ($year && !$rok) {
            update_term_meta($term->term_id, 'rok', $year);
            $rok = $year;
            $results[] = "  Migrated year to rok: {$rok}";
            $updated++;
        }
        
        // Try to extract year and month from term name if meta is missing
        $extracted_year = null;
        $extracted_month = null;
        
        // Extract year from term name (e.g., "Styczeń 2025" -> 2025)
        if (preg_match('/(\d{4})/', $term->name, $matches)) {
            $extracted_year = intval($matches[1]);
        }
        
        // Extract month from term name
        foreach ($month_names as $num => $name) {
            if (strpos($term->name, $name) !== false) {
                $extracted_month = $num;
                break;
            }
        }
        
        $results[] = "  Extracted from name - year: {$extracted_year}, month: {$extracted_month}";
        
        // Use extracted values if meta is missing or invalid
        if (!$rok && $extracted_year) {
            $rok = $extracted_year;
            update_term_meta($term->term_id, 'rok', $rok);
            $results[] = "  Updated rok from name: {$rok}";
            $updated++;
        }
        
        if (!$month_number && $extracted_month) {
            $month_number = $extracted_month;
            update_term_meta($term->term_id, 'numer_miesiaca', $month_number);
            $results[] = "  Updated month from name: {$month_number}";
            $updated++;
        }
        
        // Check if month number is invalid (should be 1-12)
        if ($month_number && ($month_number < 1 || $month_number > 12)) {
            $results[] = "  WARNING: Invalid month number {$month_number}, trying to fix...";
            
            // Try to find correct month number from name
            if ($extracted_month && $extracted_month >= 1 && $extracted_month <= 12) {
                $month_number = $extracted_month;
                update_term_meta($term->term_id, 'numer_miesiaca', $month_number);
                $results[] = "  Fixed month number to: {$month_number}";
                $updated++;
            } else {
                $results[] = "  Cannot fix invalid month number, skipping...";
                $errors++;
                continue;
            }
        }
        
        if ($rok && $month_number && $month_number >= 1 && $month_number <= 12) {
            // Recalculate the correct values
            $correct_calculated = sa_calculate_month_number($rok, $month_number);
            $correct_slug = strtolower(sanitize_title($month_names[$month_number])) . '-' . $rok;
            
            $results[] = "  Correct calculated: {$correct_calculated}, correct slug: {$correct_slug}";
            
            // Update calculated number
            update_term_meta($term->term_id, 'calculated_number', $correct_calculated);
            $results[] = "  Updated calculated number to {$correct_calculated}";
            $updated++;
            
            // Update slug if different
            if ($term->slug != $correct_slug) {
                // Check if slug already exists
                $existing_term = get_term_by('slug', $correct_slug, 'miesiace');
                if ($existing_term && $existing_term->term_id != $term->term_id) {
                    $results[] = "  Slug {$correct_slug} already exists, using alternative...";
                    $correct_slug = $correct_slug . '-' . $term->term_id;
                }
                
                $update_result = wp_update_term($term->term_id, 'miesiace', [
                    'slug' => $correct_slug
                ]);
                if (!is_wp_error($update_result)) {
                    $results[] = "  Updated slug to {$correct_slug}";
                    $updated++;
                } else {
                    $results[] = "  FAILED to update slug: " . $update_result->get_error_message();
                    $errors++;
                }
            }
            
            // Update custom_slug meta
            update_term_meta($term->term_id, 'custom_slug', $correct_slug);
            
        } else {
            $results[] = "  WARNING: Cannot process term {$term->name} - missing or invalid data";
            $results[] = "  Rok: '{$rok}', Month: '{$month_number}'";
            $errors++;
        }
        
        $results[] = "";
    }
    
    $results[] = "Cleanup completed. Updated: {$updated}, Errors: {$errors}";
    
    return $results;
}

function sa_reset_month_terms() {
    $results = [];
    $results[] = "Starting complete reset of month terms...";
    
    // First, remove all taxonomy assignments
    $results[] = "Removing all taxonomy assignments...";
    global $wpdb;
    
    // Remove all term relationships
    $deleted_relationships = $wpdb->delete(
        $wpdb->term_relationships,
        ['term_taxonomy_id' => $wpdb->get_col("SELECT term_taxonomy_id FROM {$wpdb->term_taxonomy} WHERE taxonomy = 'miesiace'")]
    );
    $results[] = "Removed {$deleted_relationships} term relationships";
    
    // Get all existing month terms
    $all_terms = get_terms([
        'taxonomy' => 'miesiace',
        'hide_empty' => false
    ]);
    
    $results[] = "Found " . count($all_terms) . " existing month terms to delete";
    
    $deleted = 0;
    $errors = 0;
    
    foreach ($all_terms as $term) {
        $results[] = "Deleting term: {$term->name} (ID: {$term->term_id})";
        
        // Force delete the term
        $delete_result = wp_delete_term($term->term_id, 'miesiace');
        if (!is_wp_error($delete_result)) {
            $results[] = "  Successfully deleted";
            $deleted++;
        } else {
            $results[] = "  FAILED to delete: " . $delete_result->get_error_message();
            $errors++;
            
            // Try to force delete by removing from database directly
            $results[] = "  Attempting force delete...";
            $force_delete = $wpdb->delete($wpdb->terms, ['term_id' => $term->term_id]);
            $wpdb->delete($wpdb->term_taxonomy, ['term_id' => $term->term_id]);
            $wpdb->delete($wpdb->termmeta, ['term_id' => $term->term_id]);
            
            if ($force_delete) {
                $results[] = "  Force delete successful";
                $deleted++;
                $errors--;
            } else {
                $results[] = "  Force delete also failed";
            }
        }
    }
    
    $results[] = "";
    $results[] = "Reset completed. Deleted: {$deleted}, Errors: {$errors}";
    $results[] = "You can now use 'Utwórz domyślne miesiące' to create clean month terms.";
    
    return $results;
}

// Add quick sync action to WooCommerce products list
add_filter('bulk_actions-edit-product', function($bulk_actions) {
    $bulk_actions['sync_variant_months'] = __('Synchronizuj miesiące w wariantach', 'woocommerce');
    $bulk_actions['sync_earliest_dates'] = __('Synchronizuj najwcześniejsze terminy', 'woocommerce');
    return $bulk_actions;
});

add_filter('handle_bulk_actions-edit-product', function($redirect_to, $action, $post_ids) {
    if ($action === 'sync_variant_months') {
        $updated = 0;
        
        foreach ($post_ids as $post_id) {
            // Get all variations for this product
            $variations = get_posts([
                'post_type' => 'product_variation',
                'post_parent' => $post_id,
                'posts_per_page' => -1,
                'post_status' => 'publish'
            ]);
            
            foreach ($variations as $variation) {
                $start_date = get_field('wyprawa-termin__data-poczatkowa', $variation->ID);
                if (!$start_date) {
                    continue;
                }
                
                // Parse the start date - try different formats
                $date = null;
                $date_formats = ['Ymd', 'Y-m-d', 'd.m.Y', 'Y/m/d'];
                
                foreach ($date_formats as $format) {
                    $date = DateTime::createFromFormat($format, $start_date);
                    if ($date) {
                        break;
                    }
                }
                
                if (!$date) {
                    continue;
                }
                
                $year = $date->format('Y');
                $month_number = $date->format('n');
                
                // Get or create the term for this month
                $term = sa_get_month_term($year, $month_number);
                
                if (!$term) {
                    // Create the month term if it doesn't exist
                    $term = sa_create_month_term($year, $month_number);
                }
                
                if ($term) {
                    // Get parent product ID - taxonomy should be assigned to parent, not variation
                    $parent_product_id = wp_get_post_parent_id($variation->ID);
                    if ($parent_product_id) {
                        wp_set_object_terms($parent_product_id, [$term->term_id], 'miesiace');
                        $updated++;
                    }
                }
            }
        }
        
        $redirect_to = add_query_arg('synced_variants', $updated, $redirect_to);
        return $redirect_to;
    }
    
    if ($action === 'sync_earliest_dates') {
        $updated = 0;
        
        foreach ($post_ids as $post_id) {
            // Get all variations for this product
            $variations = get_posts([
                'post_type' => 'product_variation',
                'post_parent' => $post_id,
                'posts_per_page' => -1,
                'post_status' => 'publish'
            ]);
            
            if (empty($variations)) {
                continue;
            }
            
            $earliest_date = null;
            foreach ($variations as $variation) {
                $start_date = get_field('wyprawa-termin__data-poczatkowa', $variation->ID);
                if (!$start_date) {
                    continue;
                }
                
                // Parse the start date - try different formats
                $date = null;
                $date_formats = ['Ymd', 'Y-m-d', 'd.m.Y', 'Y/m/d'];
                
                foreach ($date_formats as $format) {
                    $date = DateTime::createFromFormat($format, $start_date);
                    if ($date) {
                        break;
                    }
                }
                
                if (!$date) {
                    continue;
                }
                
                if ($earliest_date === null || $date < $earliest_date) {
                    $earliest_date = $date;
                }
            }
            
            if ($earliest_date) {
                update_field('wyprawa__najblizszy-termin', $earliest_date->format('Ymd'), $post_id);
                $updated++;
            }
        }
        
        $redirect_to = add_query_arg('synced_earliest_dates', $updated, $redirect_to);
        return $redirect_to;
    }
    
    return $redirect_to;
}, 10, 3);

// Show admin notice after bulk sync
add_action('admin_notices', function() {
    if (!empty($_REQUEST['synced_variants'])) {
        $count = intval($_REQUEST['synced_variants']);
        ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <?php printf(
                    _n(
                        'Zaktualizowano miesiąc w %s wariancie.',
                        'Zaktualizowano miesiące w %s wariantach.',
                        $count,
                        'woocommerce'
                    ),
                    number_format_i18n($count)
                ); ?>
            </p>
        </div>
        <?php
    }
    
    if (!empty($_REQUEST['synced_earliest_dates'])) {
        $count = intval($_REQUEST['synced_earliest_dates']);
        ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <?php printf(
                    _n(
                        'Zaktualizowano najwcześniejszy termin w %s produkcie.',
                        'Zaktualizowano najwcześniejsze terminy w %s produktach.',
                        $count,
                        'woocommerce'
                    ),
                    number_format_i18n($count)
                ); ?>
            </p>
        </div>
        <?php
    }
}); 