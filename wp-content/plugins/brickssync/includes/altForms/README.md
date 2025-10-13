# AltForms

**AltForms** is a lightweight, fluent, and developer-friendly PHP form framework built for WordPress admin pages. It allows you to build fully functional forms in a clean, declarative way without relying on the WordPress Settings API.

---

## Purpose

AltForms was created to:
- Simplify admin UI creation in WordPress plugins
- Provide complete control over form layout and logic
- Keep your code clean, readable, and minimal
- Handle saving, callbacks, and input toggling natively

---

## Features
- Chainable fluent API
- Automatic saving to `get_option()` / `update_option()`
- Custom submit callbacks
- Built-in success/error messages
- Conditional inputs via radio toggle logic
- Section grouping
- Custom content blocks
- JavaScript-powered conditional logic

---

## Usage Example

```php
$form = new AltForms('my_settings_form');

$form
    ->section('General Settings')
    ->input_text('site_code', 'Site Code')
    ->input_checkbox('debug_mode', 'Enable Debug')

    ->section('Advanced')
    ->input_radio('mode', 'Use default settings', 'default')
    ->input_radio('mode', 'Use custom settings', 'custom', [
        'toggles' => ['custom_input'],
        'toggles_section' => 'advanced_options'
    ])
    ->input_text('custom_input', 'Custom Field')

    ->section('advanced_options')
    ->content('<p>This section is only visible when "custom" mode is selected.</p>')
    ->input_textarea('notes', 'Notes')

    ->success_message('Your settings were saved!')
    ->error_message('Oops, something went wrong.')
    ->submit_label('Apply Settings')

    ->handle()
    ->render();
```

---

## Available Methods

### Basic Inputs
```php
->input_text($name, $label, $default = '')
->input_textarea($name, $label, $default = '')
->input_checkbox($name, $label, $default = '')
->input_select($name, $label, $options = [], $default = '')
```

### Single Radio Buttons (with toggle)
```php
->input_radio($name, $label, $value, [
    'toggles' => ['input_id1', 'input_id2'],
    'toggles_section' => 'section_id'
])
```
- All radios with the same `$name` are grouped
- Toggles input fields and/or sections based on selection

### Sections
```php
->section('My Section Title')
```
- Groups fields visually under a heading
- ID is auto-generated (useful for toggling visibility)

### Content Blocks
```php
->content('<p>This is a helpful instruction paragraph.</p>')
```
- Inserts static content (HTML allowed)

### Settings
```php
->on_submit(function ($data) { /* Custom logic */ })
->success_message('Saved!')
->error_message('Invalid submission')
->submit_label('Apply')
```

### Lifecycle
```php
->handle() // Processes the form data
->render() // Outputs the form HTML
```

---

## Output Demo

Here’s a preview of how the form UI will look:

![Form Demo](https://via.placeholder.com/800x300?text=AltForms+Form+UI+Demo)

> Note: AltForms uses standard WordPress admin styles (`form-table`, `notice`, `button-primary`, etc.)

---

## Integration Tips

- Works great inside tabbed admin pages:
```php
if ($tab === 'settings') {
    $form = new AltForms('my_form');
    $form->input_text('setting', 'Setting')->handle()->render();
}
```

- Can run multiple forms on a single page (each with a unique slug)
- Output can be captured with output buffering if you need to inject into templates

---

## License
MIT

---

Enjoy building cleaner, smarter WordPress admin forms with **AltForms**!

