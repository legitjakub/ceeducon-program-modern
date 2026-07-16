<?php
if (!defined('ABSPATH')) {
    exit;
}

$rows = ceeducon_block_array($attributes, 'eventRows');
$image_id = (int) ceeducon_block_value($attributes, 'imageId', 0);
$image_url = (string) ceeducon_block_value($attributes, 'imageUrl');
$image_alt = (string) ceeducon_block_value($attributes, 'imageAlt');
?>
<section class="hero">
  <div class="hero-media">
    <?php if ($image_id > 0) : ?>
      <?php echo wp_get_attachment_image($image_id, 'full', false, ['alt' => $image_alt, 'loading' => 'eager', 'decoding' => 'async', 'fetchpriority' => 'high']); ?>
    <?php elseif ($image_url !== '') : ?>
      <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" decoding="async" fetchpriority="high" />
    <?php endif; ?>
  </div>
  <div class="hero-inner shell">
    <div class="hero-copy">
      <p class="hero-kicker"><?php echo ceeducon_block_text($attributes, 'kicker'); ?></p>
      <h1><?php echo ceeducon_block_html($attributes, 'title'); ?></h1>
      <p class="hero-lead"><?php echo ceeducon_block_text($attributes, 'lead'); ?></p>
      <div class="hero-actions" aria-label="<?php esc_attr_e('Quick actions', 'ceeducon-program'); ?>">
        <?php echo ceeducon_render_block_button((string) ceeducon_block_value($attributes, 'primaryText'), (string) ceeducon_block_value($attributes, 'primaryUrl'), 'btn btn--primary'); ?>
        <?php echo ceeducon_render_block_button((string) ceeducon_block_value($attributes, 'secondaryText'), (string) ceeducon_block_value($attributes, 'secondaryUrl'), 'btn btn--ghost'); ?>
      </div>
    </div>
  </div>
  <div class="hero-facts-wrap">
    <div class="hero-facts shell" aria-label="<?php esc_attr_e('Conference essentials', 'ceeducon-program'); ?>">
      <div class="hero-date"><strong><?php echo ceeducon_block_text($attributes, 'eventDay'); ?></strong><span><?php echo ceeducon_block_html($attributes, 'eventMonth'); ?></span></div>
      <?php foreach ($rows as $index => $row) : ?>
        <div class="hero-fact hero-fact--<?php echo esc_attr((string) ($index + 1)); ?>">
          <span><?php echo esc_html($row['label'] ?? ''); ?></span>
          <strong><?php echo esc_html($row['value'] ?? ''); ?></strong>
        </div>
      <?php endforeach; ?>
      <?php if (trim((string) ceeducon_block_value($attributes, 'calendarText')) !== '' && trim((string) ceeducon_block_value($attributes, 'calendarUrl')) !== '') : ?>
        <a class="hero-calendar" href="<?php echo ceeducon_block_url($attributes, 'calendarUrl'); ?>" download><?php echo ceeducon_block_text($attributes, 'calendarText'); ?> <span class="ui-icon" aria-hidden="true"><svg viewBox="0 0 16 16"><path d="M6 4h6v6M12 4 5 11"></path></svg></span></a>
      <?php endif; ?>
    </div>
  </div>
</section>
