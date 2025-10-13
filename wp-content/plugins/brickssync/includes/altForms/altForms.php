<?php
/**
 * AltForms - Simple Settings Framework
 *
 * Provides a basic class for creating WordPress settings pages
 * or simple forms with less boilerplate.
 *
 * @package AltForms
 * @version 0.1.0
 * // Note: This seems to be a custom or third-party library.
 */

class AltForms {
    protected string $slug;
    protected array $sections = [];
    protected string $current_section = 'default';
    protected bool $was_saved = false;
    protected bool $was_invalid = false;
    protected $submit_callback = null;

    protected string $success_message = 'Settings saved.';
    protected string $error_message = 'Invalid form submission.';
    protected string $submit_label = 'Save Settings';

    protected bool $in_radio_group = false;
    protected string $radio_group_label = '';

    public function __construct($slug) {
        $this->slug = $slug;
        $this->sections[$this->current_section] = [
            'title' => '',
            'fields' => []
        ];
    }

    public function section(string $title) {
        $this->current_section = sanitize_title($title);
        $this->sections[$this->current_section] = [
            'title' => $title,
            'fields' => []
        ];
        return $this;
    }

    public function start_radio_group(string $label = '') {
        $this->in_radio_group = true;
        $this->radio_group_label = $label;
        return $this;
    }

    public function end_radio_group() {
        $this->in_radio_group = false;
        $this->radio_group_label = '';
        return $this;
    }

    protected function add_field($type, $name, $label, $default = '', $options = [], $extra = []) {
        $field = compact('type', 'name', 'label', 'default', 'options', 'extra');
        
        if ($this->in_radio_group) {
            $field['grouped'] = true;
            if ($type === 'radio_single' && $this->radio_group_label) {
                $field['group_label'] = $this->radio_group_label;
                $this->radio_group_label = '';
            }
        } else {
            $field['grouped'] = false;
        }
    
        $this->sections[$this->current_section]['fields'][] = $field;
        return $this;
    }

    public function input_hidden($name, $default = '') { return $this->add_field('hidden', $name, '', $default); }
    public function input_text($name, $label, $default = '') { return $this->add_field('text', $name, $label, $default); }
    public function input_textarea($name, $label, $default = '') { return $this->add_field('textarea', $name, $label, $default); }
    public function input_checkbox($name, $label, $default = '') { return $this->add_field('checkbox', $name, $label, $default); }
    public function input_select($name, $label, $options = [], $default = '') { return $this->add_field('select', $name, $label, $default, $options); }
    public function input_radio($name, $label, $value, $extra = []) { return $this->add_field('radio_single', $name, $label, $value, [], $extra); }
    public function content(string $html) { return $this->add_field('content', uniqid('content_'), '', '', [], ['html' => $html]); }
    public function content_full_width(string $html) { return $this->add_field('content_full_width', uniqid('content_full_width_'), '', '', [], ['html' => $html]); }

    public function on_submit(callable $callback) {
        $this->submit_callback = $callback;
        return $this;
    }

    public function success_message(string $message) {
        $this->success_message = $message;
        return $this;
    }

    public function error_message(string $message) {
        $this->error_message = $message;
        return $this;
    }

    public function submit_label(string $label) {
        $this->submit_label = $label;
        return $this;
    }

    /**
     * Get the form slug.
     * @return string
     */
    public function get_slug(): string {
        return $this->slug;
    }

    public function handle() {
        if (!isset($_POST[$this->slug . '_submit'])) return $this;

        if (!check_admin_referer($this->slug . '_nonce')) {
            $this->was_invalid = true;
            return $this;
        }

        $submitted_data = [];
        foreach ($this->sections as $section) {
            foreach ($section['fields'] as $field) {
                if ($field['type'] === 'content') continue;
                $name = $field['name'];
                $value = $_POST[$name] ?? '';
                if ($field['type'] === 'checkbox') {
    $submitted_data[$name] = ($value === '1' ? '1' : '0');
} elseif ($field['type'] === 'textarea') {
    $submitted_data[$name] = sanitize_textarea_field($value);
} else {
    $submitted_data[$name] = sanitize_text_field($value);
}
            }
        }

        if ($this->submit_callback) {
            call_user_func($this->submit_callback, $submitted_data);
        } else {
            foreach ($submitted_data as $name => $value) {
                update_option($name, $value);
            }
        }

        $this->was_saved = true;
        return $this;
    }

    public function render() {
        if ($this->was_saved) {
            echo '<div class="notice notice-success is-dismissible"><p>' . $this->success_message . '</p></div>';
        } elseif ($this->was_invalid) {
            echo '<div class="notice notice-error is-dismissible"><p>' . $this->error_message . '</p></div>';
        }

        echo '<form method="post">';
        wp_nonce_field($this->slug . '_nonce');

        

        foreach ($this->sections as $section_id => $section) {
            echo '<div class="altforms-section" id="' . $section_id . '">';

            if (!empty($section['title'])) {
                echo '<h2>' . $section['title'] . '</h2>';
            }

            // Render hidden fields first
            foreach ($section['fields'] as $index => $field) {
                if ($field['type'] === 'hidden') {
                    $name = $field['name'];
                    $value = get_option($name, $field['default']);
                    echo "<input type='hidden' name='{$name}' id='{$name}' value='{$value}' />";
                }
            }

            echo '<table class="form-table">';
            $grouping = false;
            $group_label = '';

            foreach ($section['fields'] as $index => $field) {
                // Skip hidden fields here since they're already rendered above
                if ($field['type'] === 'hidden') {
                    continue;
                }

                if ($field['type'] === 'content') {
                    echo '<tr><th></th><td>' . $field['extra']['html'] . '</td></tr>';
                    continue;
                }

                if ($field['type'] === 'content_full_width') {
                    echo "<tr><td colspan='2'>" . $field['extra']['html'] . '</td></tr>';
                    continue;
                }
                

                if ($field['type'] === 'radio_single' && !empty($field['grouped'])) {
                    if (!$grouping) {
                        $group_label = $field['group_label'] ?? $field['label'];
                        echo '<tr><th>' . $group_label . '</th><td>';
                        $grouping = true;
                    } else {
                        echo '<tr><th></th><td>';
                       
                    }
                    

                    //echo '<tr><th></th><td>';

                    $name = $field['name'];
                    $value = get_option($name, $field['default']);
                    $id = $name . '_' . sanitize_title($field['default']);
                    echo "<label><input type='radio' name='{$name}' value='{$field['default']}' id='{$id}'" . checked($value, $field['default'], false);
                    $toggle_attrs = '';
                    if (!empty($field['extra']['toggles'])) {
                        $toggle_attrs .= " data-toggle-inputs='" . json_encode($field['extra']['toggles']) . "'";
                    }
                    if (!empty($field['extra']['toggles_section'])) {
                        $toggle_attrs .= " data-toggle-section='" . $field['extra']['toggles_section'] . "'";
                    }
                    echo $toggle_attrs . "> {$field['label']}</label>";

                    echo '</td></tr>';
                    
                    /*
                    $next = $section['fields'][$index + 1] ?? null;
                    if (!$next || $next['type'] !== 'radio_single' || empty($next['grouped'])) {
                        if ($next['type'] === 'content') continue;
                        $grouping = false;
                        $this->radio_group_label = '';
                        $group_label = '';
                    }*/
                    continue;
                }

                $name = $field['name'];
                $label = $field['label'];
                $value = get_option($name, $field['default']);

                echo '<tr>';
                
                if (!empty($field['grouped'])) {

                    
                    echo '<th></th><td>';

                    echo "<label for='{$name}' style='display:block; margin-bottom:4px;'>{$label}:</label>";
                } else {
                    echo '<th><label for="' . $name . '">' . $label . '</label></th><td>';
                }

                switch ($field['type']) {
                    
                    case 'text':
                        echo "<input type='text' name='{$name}' id='{$name}' value='{$value}' class='regular-text' />";
                        break;
                    case 'textarea':
                        echo "<textarea name='{$name}' id='{$name}' rows='5' class='large-text'>{$value}</textarea>";
                        break;
                    case 'checkbox':
                        echo "<input type='checkbox' name='{$name}' id='{$name}' value='1' " . checked($value, '1', false) . " />";
                        break;
                    case 'select':
                        echo "<select name='{$name}' id='{$name}'>";
                        foreach ($field['options'] as $opt_value => $opt_label) {
                            echo "<option value='{$opt_value}' " . selected($value, $opt_value, false) . ">{$opt_label}</option>";
                        }
                        echo "</select>";
                        break;
                    default:
                        echo '<!-- Unknown field type -->';
                        break;
                }

                echo '</td></tr>';
            }
            echo '</table></div>';
        }

        echo '<p class="submit"><button type="submit" name="' . $this->slug . '_submit' . '" class="button-primary">' . $this->submit_label . '</button></p>';
        echo '</form>';
        echo '<style>
        .altforms-section table.form-table th,
        .altforms-section table.form-table td {
            padding-top: 4px !important;
            padding-bottom: 4px !important;
        }

        .altforms-section h2 {
            margin-bottom: 8px !important;
        }

        .altforms-section .regular-text,
        .altforms-section .large-text,
        .altforms-section select {
            margin-top: 2px !important;
            margin-bottom: 2px !important;
        }
        </style>';

        $this->render_toggle_script();
    }

    protected function render_toggle_script() {
        static $printed = false;
        if ($printed) return;
        $printed = true;
        echo "<script>
        document.addEventListener('DOMContentLoaded', function () {
            function updateToggles() {
                const allToggles = document.querySelectorAll('[data-toggle-inputs], [data-toggle-section]');
                const radioGroups = {};

                document.querySelectorAll('input[type=radio]').forEach(radio => {
                    if (!radioGroups[radio.name]) radioGroups[radio.name] = [];
                    radioGroups[radio.name].push(radio);
                });

                for (const name in radioGroups) {
                    const radios = radioGroups[name];
                    radios.forEach(radio => {
                        const inputs = JSON.parse(radio.dataset.toggleInputs || '[]');
                        const sectionId = radio.dataset.toggleSection;
                        inputs.forEach(id => {
                            const el = document.getElementById(id);
                            if (el) el.disabled = true;
                        });
                        if (sectionId) {
                            const section = document.getElementById(sectionId);
                            if (section) section.style.display = 'none';
                        }
                    });

                    const selected = radios.find(r => r.checked);
                    if (selected) {
                        const inputs = JSON.parse(selected.dataset.toggleInputs || '[]');
                        const sectionId = selected.dataset.toggleSection;
                        inputs.forEach(id => {
                            const el = document.getElementById(id);
                            if (el) el.disabled = false;
                        });
                        if (sectionId) {
                            const section = document.getElementById(sectionId);
                            if (section) section.style.display = 'block';
                        }
                    }
                }

                allToggles.forEach(el => {
                    if (el.type === 'checkbox') {
                        const isActive = el.checked;
                        const inputs = JSON.parse(el.dataset.toggleInputs || '[]');
                        inputs.forEach(id => {
                            const input = document.getElementById(id);
                            if (input) input.disabled = !isActive;
                        });
                        const sectionId = el.dataset.toggleSection;
                        if (sectionId) {
                            const section = document.getElementById(sectionId);
                            if (section) section.style.display = isActive ? 'block' : 'none';
                        }
                    } else if (el.tagName === 'SELECT') {
                        const selectedValue = el.value;
                        const isActive = selectedValue !== 'off';
                        const inputs = JSON.parse(el.dataset.toggleInputs || '[]');
                        inputs.forEach(id => {
                            const input = document.getElementById(id);
                            if (input) input.disabled = !isActive;
                        });
                        const sectionId = el.dataset.toggleSection;
                        if (sectionId) {
                            const section = document.getElementById(sectionId);
                            if (section) section.style.display = isActive ? 'block' : 'none';
                        }
                    }
                });
            }

            document.querySelectorAll('input[type=radio]').forEach(el => {
                el.addEventListener('change', updateToggles);
            });

            document.querySelectorAll('[data-toggle-inputs], [data-toggle-section]').forEach(el => {
                if (el.type === 'checkbox' || el.tagName === 'SELECT') {
                    el.addEventListener('change', updateToggles);
                }
            });

            updateToggles();
        });
        </script>";
    }
}
