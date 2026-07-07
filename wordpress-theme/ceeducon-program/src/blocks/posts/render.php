<?php
if (!defined('ABSPATH')) {
    exit;
}

$post_type = sanitize_key((string) ceeducon_block_value($attributes, 'postType', 'post'));
$count = max(1, min(6, (int) ceeducon_block_value($attributes, 'count', 3)));
$section_class = !empty($attributes['paper']) ? 'section section--paper' : 'section';
$query = new WP_Query([
    'post_type' => $post_type,
    'posts_per_page' => $count,
    'post_status' => 'publish',
    'ignore_sticky_posts' => true,
    'no_found_rows' => true,
]);
?>
<section class="<?php echo esc_attr($section_class); ?>">
  <div class="shell">
    <div class="section-head">
      <div data-reveal>
        <p class="kicker"><?php echo ceeducon_block_text($attributes, 'kicker'); ?></p>
        <h2 class="display-2"><?php echo ceeducon_block_html($attributes, 'title'); ?></h2>
      </div>
      <?php if (trim((string) ceeducon_block_value($attributes, 'intro')) !== '') : ?>
        <p data-reveal="2"><?php echo ceeducon_block_html($attributes, 'intro'); ?></p>
      <?php endif; ?>
    </div>
    <div class="tile-grid">
      <?php if ($query->have_posts()) : ?>
        <?php while ($query->have_posts()) : $query->the_post(); ?>
          <a class="link-tile" href="<?php echo esc_url(get_permalink()); ?>" data-reveal>
            <span><?php echo esc_html(get_the_date()); ?></span>
            <h3><?php echo esc_html(get_the_title()); ?></h3>
            <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p>
          </a>
        <?php endwhile; wp_reset_postdata(); ?>
      <?php else : ?>
        <article class="link-tile" data-reveal>
          <span><?php esc_html_e('No posts yet', 'ceeducon-program'); ?></span>
          <h3><?php esc_html_e('Publish the first update', 'ceeducon-program'); ?></h3>
          <p><?php esc_html_e('This dynamic block will show published posts automatically.', 'ceeducon-program'); ?></p>
        </article>
      <?php endif; ?>
    </div>
  </div>
</section>
