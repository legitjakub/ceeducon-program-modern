<?php
if (!defined('ABSPATH')) {
    exit;
}
$items = ceeducon_block_array($attributes, 'items');
$dark = !empty($attributes['dark']);
$section_class = $dark ? 'section section--navy on-dark' : 'section section--tint theme-section-light';
?>
<section class="<?php echo esc_attr($section_class); ?>">
  <div class="shell">
    <div class="section-head">
      <div data-reveal><p class="kicker"><?php echo ceeducon_block_text($attributes, 'kicker'); ?></p><h2 class="display-2"><?php echo ceeducon_block_html($attributes, 'title'); ?></h2></div>
      <?php if (trim((string) ceeducon_block_value($attributes, 'intro')) !== '') : ?><p data-reveal="2"><?php echo ceeducon_block_html($attributes, 'intro'); ?></p><?php endif; ?>
    </div>
    <div class="theme-grid" aria-label="<?php esc_attr_e('Conference thematic areas', 'ceeducon-program'); ?>">
      <?php $variants = ['sky', 'orange', 'white', 'navy']; foreach ($items as $index => $item) : ?>
        <article class="theme-card theme-card--<?php echo esc_attr($variants[$index % count($variants)]); ?>" data-reveal="<?php echo esc_attr((string) ($index + 1)); ?>">
          <span><?php echo esc_html($item['number'] ?? sprintf('%02d', $index + 1)); ?></span>
          <h3><?php echo esc_html($item['title'] ?? ''); ?></h3>
          <p><?php echo esc_html($item['text'] ?? ''); ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
