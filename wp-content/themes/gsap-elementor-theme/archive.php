<?php if (!defined('ABSPATH')) { exit; } ?>
<?php get_header(); ?>
<div class="site-container">
  <header class="page-header">
    <h1 class="page-title"><?php the_archive_title(); ?></h1>
    <?php the_archive_description('<div class="archive-description">', '</div>'); ?>
  </header>

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
      <h2><?php esc_html_e('No posts found', GE_TEXT_DOMAIN); ?></h2>
    </article>
  <?php endif; ?>
</div>
<?php get_footer(); ?>