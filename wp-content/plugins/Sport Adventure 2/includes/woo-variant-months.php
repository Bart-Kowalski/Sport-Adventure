<?php
/**
 * WooCommerce Variant Months Taxonomy
 */

defined('ABSPATH') || exit;

// Register taxonomy if it doesn't exist
add_action('init', function() {
    if (!taxonomy_exists('miesiace')) {
        register_taxonomy('miesiace', ['product', 'product_variation'], [
            'label' => 'Miesiące',
            'hierarchical' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'miesiace'],
        ]);
    }
});

// Add custom fields to miesiace taxonomy
add_action('miesiace_add_form_fields', 'sa_add_month_fields');
add_action('miesiace_edit_form_fields', 'sa_edit_month_fields');
add_action('created_miesiace', 'sa_save_month_fields');
add_action('edited_miesiace', 'sa_save_month_fields');

function sa_add_month_fields() {
    ?>
    <div class="form-field">
        <label for="rok">Rok</label>
        <input type="number" name="rok" id="rok" value="" min="2025" max="2030" />
        <p class="description">Rok dla tego miesiąca (2025 = rok 0)</p>
    </div>
    <div class="form-field">
        <label for="numer_miesiaca">Numer miesiąca</label>
        <input type="number" name="numer_miesiaca" id="numer_miesiaca" value="" min="1" max="12" />
        <p class="description">Numer miesiąca (1-12)</p>
    </div>
    <div class="form-field">
        <label for="custom_slug">Slug</label>
        <input type="text" name="custom_slug" id="custom_slug" value="" />
        <p class="description">Niestandardowy slug (np. styczen-2025)</p>
    </div>
    <?php
}

function sa_edit_month_fields($term) {
    $rok = get_term_meta($term->term_id, 'rok', true);
    $numer_miesiaca = get_term_meta($term->term_id, 'numer_miesiaca', true);
    $custom_slug = get_term_meta($term->term_id, 'custom_slug', true);
    ?>
    <tr class="form-field">
        <th scope="row"><label for="rok">Rok</label></th>
        <td>
            <input type="number" name="rok" id="rok" value="<?php echo esc_attr($rok); ?>" min="2025" max="2030" />
            <p class="description">Rok dla tego miesiąca (2025 = rok 0)</p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="numer_miesiaca">Numer miesiąca</label></th>
        <td>
            <input type="number" name="numer_miesiaca" id="numer_miesiaca" value="<?php echo esc_attr($numer_miesiaca); ?>" min="1" max="12" />
            <p class="description">Numer miesiąca (1-12)</p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="custom_slug">Slug</label></th>
        <td>
            <input type="text" name="custom_slug" id="custom_slug" value="<?php echo esc_attr($custom_slug); ?>" />
            <p class="description">Niestandardowy slug (np. styczen-2025)</p>
        </td>
    </tr>
    <?php
}

function sa_save_month_fields($term_id) {
    if (isset($_POST['rok'])) {
        update_term_meta($term_id, 'rok', sanitize_text_field($_POST['rok']));
    }
    if (isset($_POST['numer_miesiaca'])) {
        update_term_meta($term_id, 'numer_miesiaca', sanitize_text_field($_POST['numer_miesiaca']));
    }
    if (isset($_POST['custom_slug'])) {
        update_term_meta($term_id, 'custom_slug', sanitize_text_field($_POST['custom_slug']));
    }
}

// Helper function to get month term by year and month number
function sa_get_month_term($year, $month_number) {
    $terms = get_terms([
        'taxonomy' => 'miesiace',
        'hide_empty' => false,
        'meta_query' => [
            'relation' => 'AND',
            [
                'key' => 'rok',
                'value' => $year,
                'compare' => '='
            ],
            [
                'key' => 'numer_miesiaca',
                'value' => $month_number,
                'compare' => '='
            ]
        ]
    ]);
    
    return !empty($terms) ? $terms[0] : null;
}

// Helper function to create month term if it doesn't exist
function sa_create_month_term($year, $month_number) {
    $debug_log = [];
    $debug_log[] = "Creating month term for year: {$year}, month: {$month_number}";
    
    $month_names = [
        1 => 'Styczeń', 2 => 'Luty', 3 => 'Marzec', 4 => 'Kwiecień',
        5 => 'Maj', 6 => 'Czerwiec', 7 => 'Lipiec', 8 => 'Sierpień',
        9 => 'Wrzesień', 10 => 'Październik', 11 => 'Listopad', 12 => 'Grudzień'
    ];
    
    $month_name = $month_names[$month_number] ?? 'Nieznany';
    $term_name = $month_name . ' ' . $year;
    $term_slug = strtolower(sanitize_title($month_name)) . '-' . $year;
    $year_offset = sa_calculate_year_number($year);
    $calculated_number = sa_calculate_month_number($year, $month_number);
    
    $debug_log[] = "Creating term - Year: {$year}, Month: {$month_number}, Year offset: {$year_offset}";
    $debug_log[] = "Term name: {$term_name}, slug: {$term_slug}, calculated number: {$calculated_number}";
    
    // Check if term already exists
    $existing_term = get_term_by('slug', $term_slug, 'miesiace');
    if ($existing_term) {
        $debug_log[] = "Term already exists with ID: {$existing_term->term_id}";
        if (sa_is_php_debug_enabled()) {
            error_log("SA Month Creation Debug: " . implode(" | ", $debug_log));
        }
        return $existing_term;
    }
    
    // Create the term
    $term_result = wp_insert_term($term_name, 'miesiace', [
        'slug' => $term_slug
    ]);
    
    if (is_wp_error($term_result)) {
        $debug_log[] = "Failed to create term: " . $term_result->get_error_message();
        if (sa_is_php_debug_enabled()) {
            error_log("SA Month Creation Debug: " . implode(" | ", $debug_log));
        }
        return null;
    }
    
    $term_id = $term_result['term_id'];
    $debug_log[] = "Created term with ID: {$term_id}";
    
    // Add meta fields
    $rok_result = update_term_meta($term_id, 'rok', $year);
    $month_result = update_term_meta($term_id, 'numer_miesiaca', $month_number);
    $slug_result = update_term_meta($term_id, 'custom_slug', $term_slug);
    $calc_result = update_term_meta($term_id, 'calculated_number', $calculated_number);
    
    $debug_log[] = "Meta fields updated - rok: " . ($rok_result ? 'OK' : 'FAIL') . 
                   ", month: " . ($month_result ? 'OK' : 'FAIL') . 
                   ", slug: " . ($slug_result ? 'OK' : 'FAIL') . 
                   ", calculated: " . ($calc_result ? 'OK' : 'FAIL');
    
    if (sa_is_php_debug_enabled()) {
        error_log("SA Month Creation Debug: " . implode(" | ", $debug_log));
    }
    
    return get_term($term_id);
}

// Helper function to calculate year number (2025 = 0, 2026 = 1, etc.)
function sa_calculate_year_number($year) {
    return $year - 2025;
}

// Helper function to calculate month number (Jan 2025 = 1, Dec 2025 = 12, Jan 2026 = 13, etc.)
function sa_calculate_month_number($year, $month) {
    $year_offset = sa_calculate_year_number($year);
    return $year_offset * 12 + $month;
}

// Keep only the basic functionality and remove the default attributes handling
remove_filter('woocommerce_product_get_default_attributes', 10);
// Note: Removed the remove_action for woocommerce_save_product_variation as it was breaking ACF field saving 