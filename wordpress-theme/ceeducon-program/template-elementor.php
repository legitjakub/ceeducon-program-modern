<?php
/**
 * Template Name: CEEDUCON Elementor Full Width
 * Template Post Type: page
 */

get_header();
?>
<main id="main" class="ceeducon-elementor-page">
  <?php while (have_posts()) : the_post(); ?>
    <?php the_content(); ?>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>

