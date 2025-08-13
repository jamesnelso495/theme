<?php
if (!defined('ABSPATH')) { exit; }

// Theme constants
define('GE_THEME_VERSION', '1.0.0');
define('GE_THEME_DIR', get_template_directory());
define('GE_THEME_URI', get_template_directory_uri());
if (!defined('GE_TEXT_DOMAIN')) { define('GE_TEXT_DOMAIN', 'gsap-elementor-theme'); }

// Helper to version files by mtime
function ge_file_version(string $relative_path): string {
    $file_path = GE_THEME_DIR . '/' . ltrim($relative_path, '/');
    return file_exists($file_path) ? (string) filemtime($file_path) : GE_THEME_VERSION;
}

// Theme setup
function ge_theme_setup(): void {
    load_theme_textdomain(GE_TEXT_DOMAIN, GE_THEME_DIR . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-width'  => true,
        'flex-height' => true,
    ]);
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('align-wide');

    register_nav_menus([
        'primary' => __('Primary Menu', GE_TEXT_DOMAIN),
    ]);

    // Editor styles (optional)
    add_editor_style('assets/css/theme.css');

    // Content width
    if (!isset($GLOBALS['content_width'])) {
        $GLOBALS['content_width'] = 1200;
    }
}
add_action('after_setup_theme', 'ge_theme_setup');

// Register and enqueue styles/scripts
function ge_enqueue_assets(): void {
    // Styles
    wp_enqueue_style('ge-style', get_stylesheet_uri(), [], ge_file_version('style.css'));
    wp_enqueue_style('ge-theme', GE_THEME_URI . '/assets/css/theme.css', ['ge-style'], ge_file_version('assets/css/theme.css'));

    // Scripts (register main first so other modules can localize to it)
    $main_handle = 'ge-main';
    wp_register_script($main_handle, GE_THEME_URI . '/assets/js/main.js', [], ge_file_version('assets/js/main.js'), true);
    if (function_exists('wp_script_add_data')) {
        wp_script_add_data($main_handle, 'strategy', 'defer');
    }
    wp_enqueue_script($main_handle);
}
add_action('wp_enqueue_scripts', 'ge_enqueue_assets', 10);

// Includes
require_once GE_THEME_DIR . '/inc/settings.php';
require_once GE_THEME_DIR . '/inc/gsap.php';
require_once GE_THEME_DIR . '/inc/elementor.php';