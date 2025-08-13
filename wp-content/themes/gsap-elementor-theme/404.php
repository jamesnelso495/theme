<?php if (!defined('ABSPATH')) { exit; } ?>
<?php get_header(); ?>
<div class="site-container">
  <article class="error-404 not-found">
    <h1><?php esc_html_e('Page not found', GE_TEXT_DOMAIN); ?></h1>
    <p><?php esc_html_e('It looks like nothing was found at this location.', GE_TEXT_DOMAIN); ?></p>
    <p><a class="button" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Go to homepage', GE_TEXT_DOMAIN); ?></a></p>
  </article>
</div>
<?php get_footer(); ?>