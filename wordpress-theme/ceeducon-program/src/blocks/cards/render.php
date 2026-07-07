<?php
if (!defined('ABSPATH')) {
    exit;
}

$items = ceeducon_block_array($attributes, 'items');
$section_class = !empty($attributes['paper']) ? 'section section--paper' : 'section';
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
      <?php foreach ($items as $index => $item) : ?>
        <?php if (!empty($item['url'])) : ?>
        <a class="link-tile" href="<?php echo esc_url($item['url']); ?>" data-reveal="<?php echo esc_attr((string) ($index + 1)); ?>">
          <span><?php echo esc_html($item['label'] ?? ''); ?></span>
          <h3><?php echo esc_html($item['title'] ?? ''); ?></h3>
          <p><?php echo esc_html($item['text'] ?? ''); ?></p>
        </a>
        <?php else : ?>
        <article class="link-tile" data-reveal="<?php echo esc_attr((string) ($index + 1)); ?>">
          <span><?php echo esc_html($item['label'] ?? ''); ?></span>
          <h3><?php echo esc_html($item['title'] ?? ''); ?></h3>
          <p><?php echo esc_html($item['text'] ?? ''); ?></p>
        </article>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
