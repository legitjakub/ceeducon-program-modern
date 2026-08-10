<?php
if (!defined('ABSPATH')) {
    exit;
}

$rows = array_values(array_filter(
    ceeducon_block_array($attributes, 'eventRows'),
    static fn(array $row): bool => strtolower(trim((string) ($row['label'] ?? ''))) !== 'format'
));
$image_id = (int) ceeducon_block_value($attributes, 'imageId', 0);
$image_url = (string) ceeducon_block_value($attributes, 'imageUrl');
$image_alt = (string) ceeducon_block_value($attributes, 'imageAlt');
$kicker = (string) ceeducon_block_value($attributes, 'kicker');
if ($kicker === 'CEEDUCON 2026 · Prague') {
    $kicker = 'CEEDUCON 2026 · CZECHIA';
}
$event_month = preg_replace('/<br\s*\/?>/i', ' ', (string) ceeducon_block_value($attributes, 'eventMonth'));
$event_month = trim((string) preg_replace('/\s+/', ' ', wp_strip_all_tags((string) $event_month)));
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
      <p class="hero-kicker"><?php echo esc_html($kicker); ?></p>
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
      <div class="hero-essentials">
        <span class="hero-essential hero-essential--date"><strong><?php echo ceeducon_block_text($attributes, 'eventDay'); ?> <?php echo esc_html($event_month); ?></strong></span>
        <div class="hero-essential-details">
          <?php foreach ($rows as $row) : ?>
            <?php
            $label = trim((string) ($row['label'] ?? ''));
            $value = trim((string) ($row['value'] ?? ''));
            if ($value === '') {
                continue;
            }
            if (strtolower($label) === 'registration' && strtolower($value) === 'opens in september') {
                $value = 'Registration opens in September';
            }
            ?>
            <span class="hero-essential">
              <?php if ($label !== '') : ?><span class="sr-only"><?php echo esc_html($label); ?>: </span><?php endif; ?>
              <?php echo esc_html($value); ?>
            </span>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="hero-calendar-actions" aria-label="<?php esc_attr_e('Add CEEDUCON 2026 to a calendar', 'ceeducon-program'); ?>">
        <?php foreach (['google', 'outlook'] as $calendar) : ?>
          <?php if (trim((string) ceeducon_block_value($attributes, $calendar . 'CalendarText')) !== '' && trim((string) ceeducon_block_value($attributes, $calendar . 'CalendarUrl')) !== '') : ?>
            <a class="hero-calendar" href="<?php echo ceeducon_block_url($attributes, $calendar . 'CalendarUrl'); ?>" target="_blank" rel="noreferrer"><?php echo ceeducon_block_text($attributes, $calendar . 'CalendarText'); ?> <span class="hero-calendar-icon" aria-hidden="true"><svg viewBox="0 0 20 20"><path d="M5.5 2.5v3M14.5 2.5v3M3 7.5h14M4.5 4h11A1.5 1.5 0 0 1 17 5.5v10a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 3 15.5v-10A1.5 1.5 0 0 1 4.5 4Z"></path><path d="M6.5 11h2v2h-2zM11.5 11h2v2h-2z"></path></svg></span></a>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
