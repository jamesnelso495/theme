<?php if (!defined('ABSPATH')) { exit; } ?>
</main>
<footer class="site-footer" role="contentinfo">
  <div class="site-container">
    <p>&copy; <?php echo esc_html(date_i18n('Y')); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', GE_TEXT_DOMAIN); ?></p>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>