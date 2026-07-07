<?php
if (!defined('ABSPATH')) {
    exit;
}

$items = ceeducon_block_array($attributes, 'items');
$section_class = !empty($attributes['dark']) ? 'section section--navy on-dark' : 'section section--paper';
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
    <div class="testimonial-grid">
      <?php foreach ($items as $index => $item) : ?>
        <figure class="testimonial-card" data-reveal="<?php echo esc_attr((string) ($index + 1)); ?>">
          <blockquote><?php echo esc_html($item['quote'] ?? ''); ?></blockquote>
          <figcaption>
            <strong><?php echo esc_html($item['name'] ?? ''); ?></strong>
            <span><?php echo esc_html($item['role'] ?? ''); ?></span>
          </figcaption>
        </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>
