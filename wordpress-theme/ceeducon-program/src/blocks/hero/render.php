<?php
if (!defined('ABSPATH')) {
    exit;
}

$meta = ceeducon_block_array($attributes, 'meta');
$rows = ceeducon_block_array($attributes, 'eventRows');
$stats = ceeducon_block_array($attributes, 'stats');
?>
<section class="hero">
  <span class="hero-ghost" aria-hidden="true">2026</span>
  <div class="hero-ring" aria-hidden="true"></div>
  <div class="hero-inner shell">
    <div class="hero-copy">
      <p class="hero-kicker"><?php echo ceeducon_block_text($attributes, 'kicker'); ?></p>
      <h1><?php echo ceeducon_block_html($attributes, 'title'); ?></h1>
      <p class="hero-lead"><?php echo ceeducon_block_text($attributes, 'lead'); ?></p>
      <?php if ($meta) : ?>
        <div class="hero-meta" aria-label="<?php esc_attr_e('Event highlights', 'ceeducon-program'); ?>">
          <?php foreach ($meta as $item) : ?>
            <span><?php echo wp_kses((string) $item, ceeducon_allowed_inline_html()); ?></span>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <div class="hero-actions" aria-label="<?php esc_attr_e('Quick actions', 'ceeducon-program'); ?>">
        <?php echo ceeducon_render_block_button((string) ceeducon_block_value($attributes, 'primaryText'), (string) ceeducon_block_value($attributes, 'primaryUrl'), 'btn btn--primary'); ?>
        <?php echo ceeducon_render_block_button((string) ceeducon_block_value($attributes, 'secondaryText'), (string) ceeducon_block_value($attributes, 'secondaryUrl'), 'btn btn--ghost'); ?>
      </div>
      <p class="countdown-strip" data-countdown aria-label="<?php esc_attr_e('Countdown to CEEDUCON 2026', 'ceeducon-program'); ?>">
        <strong data-countdown-days>149</strong>
        <span><?php echo ceeducon_block_text($attributes, 'countdownText'); ?></span>
      </p>
    </div>
    <aside class="event-card" aria-label="<?php esc_attr_e('Conference essentials', 'ceeducon-program'); ?>">
      <div class="event-date">
        <strong><?php echo ceeducon_block_text($attributes, 'eventDay'); ?></strong>
        <span><?php echo ceeducon_block_html($attributes, 'eventMonth'); ?></span>
      </div>
      <?php foreach ($rows as $row) : ?>
        <div class="event-card-row">
          <span><?php echo esc_html($row['label'] ?? ''); ?></span>
          <strong><?php echo esc_html($row['value'] ?? ''); ?></strong>
        </div>
      <?php endforeach; ?>
      <?php echo ceeducon_render_block_button((string) ceeducon_block_value($attributes, 'eventCtaText'), (string) ceeducon_block_value($attributes, 'eventCtaUrl'), ''); ?>
      <?php echo ceeducon_render_block_button((string) ceeducon_block_value($attributes, 'calendarText'), (string) ceeducon_block_value($attributes, 'calendarUrl'), ''); ?>
    </aside>
  </div>
  <?php if ($stats) : ?>
    <div class="hero-stats shell" aria-label="<?php esc_attr_e('Conference in numbers', 'ceeducon-program'); ?>">
      <?php foreach ($stats as $stat) : ?>
        <div><strong><?php echo esc_html($stat['value'] ?? ''); ?></strong><span><?php echo esc_html($stat['label'] ?? ''); ?></span></div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
