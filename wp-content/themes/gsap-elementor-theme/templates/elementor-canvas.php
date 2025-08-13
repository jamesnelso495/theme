<?php /*
Template Name: Elementor Canvas
Template Post Type: page
*/
if (!defined('ABSPATH')) { exit; }
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php wp_head(); ?>
</head>
<body <?php body_class('elementor-template-canvas'); ?>>
<?php if (function_exists('wp_body_open')) { wp_body_open(); } ?>
<?php while (have_posts()) : the_post(); ?>
  <div id="content" class="site-content">
    <?php the_content(); ?>
  </div>
<?php endwhile; ?>
<?php wp_footer(); ?>
</body>
</html>