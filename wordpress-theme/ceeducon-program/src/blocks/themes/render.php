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
      <?php ceeducon_render_theme_cards($items); ?>
    </div>
  </div>
</section>
