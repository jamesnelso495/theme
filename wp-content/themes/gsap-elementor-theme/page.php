<?php if (!defined('ABSPATH')) { exit; } ?>
<?php get_header(); ?>
<div class="site-container">
<?php while (have_posts()) : the_post(); ?>
  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
  </article>
<?php endwhile; ?>
</div>
<?php get_footer(); ?>