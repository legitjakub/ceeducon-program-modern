<?php
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Fallback template — generic page content inside the CEEDUCON layout.
 */

get_header();
?>

    <main id="main">
      <section class="page-hero">
        <div class="shell page-hero-grid">
          <div>
            <p class="page-crumbs"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'ceeducon-program'); ?></a><span>/</span><em><?php echo esc_html(get_the_title() ?: get_bloginfo('name')); ?></em></p>
            <h1><?php echo esc_html(get_the_title() ?: get_bloginfo('name')); ?></h1>
          </div>
        </div>
      </section>

      <section class="section">
        <div class="shell">
          <?php
          if (have_posts()) {
              while (have_posts()) {
                  the_post();
                  the_content();
              }
          }
          ?>
        </div>
      </section>
    </main>

<?php
get_footer();
