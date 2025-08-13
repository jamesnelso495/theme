<?php
if (!defined('ABSPATH')) { exit; }

// Register Elementor Theme Builder locations when available (Elementor Pro)
function ge_register_elementor_locations($elementor_theme_manager): void {
    if (is_object($elementor_theme_manager) && method_exists($elementor_theme_manager, 'register_all_core_location')) {
        $elementor_theme_manager->register_all_core_location();
    }
}
add_action('elementor/theme/register_locations', 'ge_register_elementor_locations');

// Add useful theme supports for Elementor
add_action('after_setup_theme', function () {
    add_theme_support('elementor-responsive-typography');
}, 20);

// Ensure animations refresh when Elementor loads frontend
add_action('wp_enqueue_scripts', function () {
    $js = 'window.addEventListener("elementor/frontend/init",function(){ if(window.GSAPTheme && typeof window.GSAPTheme.refresh==="function"){ window.GSAPTheme.refresh(); }});';
    wp_add_inline_script('ge-main', $js, 'after');
}, 20);