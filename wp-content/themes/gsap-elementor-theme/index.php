<?php if (!defined('ABSPATH')) { exit; } ?>
<?php get_header(); ?>
<div class="site-container">
<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('gsap-anim'); ?>>
      <header class="entry-header">
        <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      </header>
      <div class="entry-content">
        <?php the_excerpt(); ?>
      </div>
    </article>
  <?php endwhile; ?>
  <?php the_posts_pagination(); ?>
<?php else : ?>
  <article class="no-results not-found">
    <h2><?php esc_html_e('Nothing Found', GE_TEXT_DOMAIN); ?></h2>
    <p><?php esc_html_e('It seems we can’t find what you’re looking for.', GE_TEXT_DOMAIN); ?></p>
  </article>
<?php endif; ?>
</div>
<?php get_footer(); ?>