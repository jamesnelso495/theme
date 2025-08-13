<?php
if (!defined('ABSPATH')) { exit; }

function ge_enqueue_gsap_scripts(): void {
    $options = ge_theme_get_options();

    // Pass settings to frontend
    $settings = [
        'enable'            => (bool) $options['enable_gsap'],
        'useScrollTrigger'  => (bool) $options['enable_scrolltrigger'],
        'ease'              => (string) $options['default_ease'],
        'duration'          => (float) $options['default_duration'],
        'enableReveal'      => (bool) $options['enable_reveal_on_scroll'],
        'revealSelector'    => (string) $options['reveal_selector'],
        'animateOnMobile'   => (bool) $options['animate_on_mobile'],
        'respectReduceMotion' => (bool) $options['respect_reduce_motion'],
        'debug'             => (bool) $options['debug'],
    ];

    // Localize to main handle registered in functions.php
    wp_localize_script('ge-main', 'GSAPThemeSettings', $settings);

    if (empty($options['enable_gsap'])) {
        return;
    }

    // Enqueue GSAP core
    $gsap_url = $options['gsap_cdn_url'];
    wp_enqueue_script('gsap', $gsap_url, [], null, true);
    if (function_exists('wp_script_add_data')) {
        wp_script_add_data('gsap', 'strategy', 'defer');
    }

    // Optional ScrollTrigger
    if (!empty($options['enable_scrolltrigger'])) {
        $st_url = $options['scrolltrigger_cdn_url'];
        wp_enqueue_script('gsap-scrolltrigger', $st_url, ['gsap'], null, true);
        if (function_exists('wp_script_add_data')) {
            wp_script_add_data('gsap-scrolltrigger', 'strategy', 'defer');
        }
    }
}
add_action('wp_enqueue_scripts', 'ge_enqueue_gsap_scripts', 15);

function ge_body_class(array $classes): array {
    if (!is_admin() && ge_theme_get_option('enable_gsap')) {
        $classes[] = 'gsap-enabled';
    }
    return $classes;
}
add_filter('body_class', 'ge_body_class');