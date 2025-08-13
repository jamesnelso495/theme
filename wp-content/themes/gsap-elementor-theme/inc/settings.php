<?php
if (!defined('ABSPATH')) { exit; }

// Defaults
function ge_theme_default_options(): array {
    return [
        'enable_gsap'             => true,
        'enable_scrolltrigger'    => true,
        'load_source'             => 'cdn', // cdn | custom
        'gsap_cdn_url'            => 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js',
        'scrolltrigger_cdn_url'   => 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js',
        'default_ease'            => 'power1.out',
        'default_duration'        => 0.6,
        'enable_reveal_on_scroll' => true,
        'reveal_selector'         => '.gsap-anim',
        'animate_on_mobile'       => true,
        'respect_reduce_motion'   => true,
        'debug'                   => false,
    ];
}

function ge_theme_get_options(): array {
    $defaults = ge_theme_default_options();
    $options  = (array) get_option('ge_theme_options', []);
    return array_merge($defaults, $options);
}

function ge_theme_get_option(string $key, $default = null) {
    $options = ge_theme_get_options();
    return array_key_exists($key, $options) ? $options[$key] : $default;
}

// Ensure defaults on theme activation
function ge_theme_set_default_options(): void {
    if (get_option('ge_theme_options') === false) {
        add_option('ge_theme_options', ge_theme_default_options());
    }
}
add_action('after_switch_theme', 'ge_theme_set_default_options');

// Admin page and settings
function ge_register_admin_menu(): void {
    add_options_page(
        __('GSAP Settings', GE_TEXT_DOMAIN),
        __('GSAP', GE_TEXT_DOMAIN),
        'manage_options',
        'ge-gsap-settings',
        'ge_render_settings_page'
    );
}
add_action('admin_menu', 'ge_register_admin_menu');

function ge_register_settings(): void {
    register_setting('ge_theme_options_group', 'ge_theme_options', [
        'type'              => 'array',
        'sanitize_callback' => 'ge_sanitize_options',
        'default'           => ge_theme_default_options(),
    ]);

    add_settings_section('ge_main_section', __('Global Animation Settings', GE_TEXT_DOMAIN), function () {
        echo '<p>' . esc_html__('Control how GSAP loads and default animation behavior across the site.', GE_TEXT_DOMAIN) . '</p>';
    }, 'ge-gsap-settings');

    add_settings_field('enable_gsap', __('Enable GSAP', GE_TEXT_DOMAIN), 'ge_field_enable_gsap', 'ge-gsap-settings', 'ge_main_section');
    add_settings_field('enable_scrolltrigger', __('Enable ScrollTrigger', GE_TEXT_DOMAIN), 'ge_field_enable_scrolltrigger', 'ge-gsap-settings', 'ge_main_section');
    add_settings_field('load_source', __('Load Source', GE_TEXT_DOMAIN), 'ge_field_load_source', 'ge-gsap-settings', 'ge_main_section');
    add_settings_field('gsap_cdn_url', __('GSAP CDN URL', GE_TEXT_DOMAIN), 'ge_field_gsap_cdn_url', 'ge-gsap-settings', 'ge_main_section');
    add_settings_field('scrolltrigger_cdn_url', __('ScrollTrigger CDN URL', GE_TEXT_DOMAIN), 'ge_field_scrolltrigger_cdn_url', 'ge-gsap-settings', 'ge_main_section');
    add_settings_field('default_ease', __('Default Ease', GE_TEXT_DOMAIN), 'ge_field_default_ease', 'ge-gsap-settings', 'ge_main_section');
    add_settings_field('default_duration', __('Default Duration (s)', GE_TEXT_DOMAIN), 'ge_field_default_duration', 'ge-gsap-settings', 'ge_main_section');
    add_settings_field('enable_reveal_on_scroll', __('Enable Reveal-on-scroll', GE_TEXT_DOMAIN), 'ge_field_enable_reveal_on_scroll', 'ge-gsap-settings', 'ge_main_section');
    add_settings_field('reveal_selector', __('Reveal Selector', GE_TEXT_DOMAIN), 'ge_field_reveal_selector', 'ge-gsap-settings', 'ge_main_section');
    add_settings_field('animate_on_mobile', __('Animate On Mobile', GE_TEXT_DOMAIN), 'ge_field_animate_on_mobile', 'ge-gsap-settings', 'ge_main_section');
    add_settings_field('respect_reduce_motion', __('Respect Prefers-Reduced-Motion', GE_TEXT_DOMAIN), 'ge_field_respect_reduce_motion', 'ge-gsap-settings', 'ge_main_section');
    add_settings_field('debug', __('Debug Console Logs', GE_TEXT_DOMAIN), 'ge_field_debug', 'ge-gsap-settings', 'ge_main_section');
}
add_action('admin_init', 'ge_register_settings');

function ge_sanitize_options($input): array {
    $defaults = ge_theme_default_options();
    $output   = [];

    $output['enable_gsap']             = !empty($input['enable_gsap']);
    $output['enable_scrolltrigger']    = !empty($input['enable_scrolltrigger']);

    $load_source = isset($input['load_source']) ? sanitize_text_field($input['load_source']) : 'cdn';
    $output['load_source'] = in_array($load_source, ['cdn', 'custom'], true) ? $load_source : 'cdn';

    $output['gsap_cdn_url']          = isset($input['gsap_cdn_url']) ? esc_url_raw($input['gsap_cdn_url']) : $defaults['gsap_cdn_url'];
    $output['scrolltrigger_cdn_url'] = isset($input['scrolltrigger_cdn_url']) ? esc_url_raw($input['scrolltrigger_cdn_url']) : $defaults['scrolltrigger_cdn_url'];

    $output['default_ease']     = isset($input['default_ease']) ? sanitize_text_field($input['default_ease']) : $defaults['default_ease'];
    $duration                   = isset($input['default_duration']) ? floatval($input['default_duration']) : $defaults['default_duration'];
    $output['default_duration'] = max(0, $duration);

    $output['enable_reveal_on_scroll'] = !empty($input['enable_reveal_on_scroll']);
    $output['reveal_selector']         = isset($input['reveal_selector']) ? sanitize_text_field($input['reveal_selector']) : $defaults['reveal_selector'];

    $output['animate_on_mobile']     = !empty($input['animate_on_mobile']);
    $output['respect_reduce_motion'] = !empty($input['respect_reduce_motion']);
    $output['debug']                 = !empty($input['debug']);

    return array_merge($defaults, $output);
}

// Field renderers
function ge_field_checkbox(string $name, string $label): void {
    $options = ge_theme_get_options();
    $checked = !empty($options[$name]);
    printf(
        '<label><input type="checkbox" name="ge_theme_options[%1$s]" value="1" %2$s> %3$s</label>',
        esc_attr($name),
        checked($checked, true, false),
        esc_html($label)
    );
}

function ge_field_enable_gsap(): void { ge_field_checkbox('enable_gsap', __('Load GSAP library on the frontend', GE_TEXT_DOMAIN)); }
function ge_field_enable_scrolltrigger(): void { ge_field_checkbox('enable_scrolltrigger', __('Load ScrollTrigger plugin (when GSAP is enabled)', GE_TEXT_DOMAIN)); }
function ge_field_enable_reveal_on_scroll(): void { ge_field_checkbox('enable_reveal_on_scroll', __('Run default reveal-on-scroll animations', GE_TEXT_DOMAIN)); }
function ge_field_animate_on_mobile(): void { ge_field_checkbox('animate_on_mobile', __('Allow animations on mobile devices', GE_TEXT_DOMAIN)); }
function ge_field_respect_reduce_motion(): void { ge_field_checkbox('respect_reduce_motion', __('Disable animations for users who prefer reduced motion', GE_TEXT_DOMAIN)); }
function ge_field_debug(): void { ge_field_checkbox('debug', __('Output helpful logs to browser console', GE_TEXT_DOMAIN)); }

function ge_field_load_source(): void {
    $options = ge_theme_get_options();
    $value   = isset($options['load_source']) ? $options['load_source'] : 'cdn';
    echo '<select name="ge_theme_options[load_source]">';
    echo '<option value="cdn"' . selected($value, 'cdn', false) . '>' . esc_html__('CDN (recommended)', GE_TEXT_DOMAIN) . '</option>';
    echo '<option value="custom"' . selected($value, 'custom', false) . '>' . esc_html__('Custom URL', GE_TEXT_DOMAIN) . '</option>';
    echo '</select>';
}

function ge_field_gsap_cdn_url(): void {
    $options = ge_theme_get_options();
    printf(
        '<input type="url" class="regular-text ltr" name="ge_theme_options[gsap_cdn_url]" value="%s" placeholder="https://cdn.jsdelivr.net/.../gsap.min.js" />',
        esc_attr($options['gsap_cdn_url'])
    );
}

function ge_field_scrolltrigger_cdn_url(): void {
    $options = ge_theme_get_options();
    printf(
        '<input type="url" class="regular-text ltr" name="ge_theme_options[scrolltrigger_cdn_url]" value="%s" placeholder="https://cdn.jsdelivr.net/.../ScrollTrigger.min.js" />',
        esc_attr($options['scrolltrigger_cdn_url'])
    );
}

function ge_field_default_ease(): void {
    $options = ge_theme_get_options();
    printf(
        '<input type="text" class="regular-text" name="ge_theme_options[default_ease]" value="%s" placeholder="power1.out" />',
        esc_attr($options['default_ease'])
    );
}

function ge_field_default_duration(): void {
    $options = ge_theme_get_options();
    printf(
        '<input type="number" step="0.1" min="0" class="small-text" name="ge_theme_options[default_duration]" value="%s" />',
        esc_attr($options['default_duration'])
    );
}

function ge_field_reveal_selector(): void {
    $options = ge_theme_get_options();
    printf(
        '<input type="text" class="regular-text code" name="ge_theme_options[reveal_selector]" value="%s" placeholder=".gsap-anim" />',
        esc_attr($options['reveal_selector'])
    );
}

// Render settings page
function ge_render_settings_page(): void {
    if (!current_user_can('manage_options')) { return; }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('GSAP Settings', GE_TEXT_DOMAIN); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('ge_theme_options_group');
            do_settings_sections('ge-gsap-settings');
            submit_button();
            ?>
        </form>
        <hr />
        <p><strong><?php echo esc_html__('Quick tip:', GE_TEXT_DOMAIN); ?></strong> <?php echo esc_html__('Add the class', GE_TEXT_DOMAIN); ?> <code>.gsap-anim</code> <?php echo esc_html__('to any Elementor widget/section to animate it on scroll. You can override per-element with attributes: ', GE_TEXT_DOMAIN); ?><code>data-gsap-duration</code>, <code>data-gsap-ease</code>, <code>data-gsap-y</code>, <code>data-gsap-opacity</code>, <code>data-gsap-stagger</code>.</p>
    </div>
    <?php
}