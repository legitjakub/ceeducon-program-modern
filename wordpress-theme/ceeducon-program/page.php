<?php
get_header();

if (ceeducon_render_elementor_page_content() || ceeducon_render_block_page_content()) {
    get_footer();
    return;
}
?>
<main id="main">
  <?php while (have_posts()) : the_post(); ?>
    <article class="section section--editor-content">
      <div class="shell wp-content">
        <h1><?php the_title(); ?></h1>
        <?php the_content(); ?>
      </div>
    </article>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>

