<?php if (!defined('ABSPATH')) { exit; } ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php if (function_exists('wp_body_open')) { wp_body_open(); } ?>
<a class="screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', GE_TEXT_DOMAIN); ?></a>
<header class="site-header" role="banner">
  <div class="site-container">
    <div class="site-branding">
      <?php if (has_custom_logo()) { the_custom_logo(); } ?>
      <a href="<?php echo esc_url(home_url('/')); ?>" class="site-title" rel="home"><?php bloginfo('name'); ?></a>
    </div>
    <?php if (has_nav_menu('primary')): ?>
      <nav class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Primary Menu', GE_TEXT_DOMAIN); ?>">
        <?php wp_nav_menu([
          'theme_location' => 'primary',
          'menu_class'     => 'menu menu--primary',
          'container'      => false,
        ]); ?>
      </nav>
    <?php endif; ?>
  </div>
</header>
<main id="primary" class="site-main">