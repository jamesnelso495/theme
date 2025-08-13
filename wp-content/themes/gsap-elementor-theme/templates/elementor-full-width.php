<?php /*
Template Name: Elementor Full Width
Template Post Type: page, post
*/
if (!defined('ABSPATH')) { exit; }
get_header();
?>
<div class="site-container elementor-template-full-width">
  <?php while (have_posts()) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
    </article>
  <?php endwhile; ?>
</div>
<?php get_footer(); ?>