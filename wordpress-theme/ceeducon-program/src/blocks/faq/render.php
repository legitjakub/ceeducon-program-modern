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
    <div class="faq-list">
      <?php foreach ($items as $index => $item) : ?>
        <details <?php echo $index === 0 ? 'open' : ''; ?>>
          <summary><?php echo esc_html($item['question'] ?? ''); ?></summary>
          <p><?php echo wp_kses_post($item['answer'] ?? ''); ?></p>
        </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>
