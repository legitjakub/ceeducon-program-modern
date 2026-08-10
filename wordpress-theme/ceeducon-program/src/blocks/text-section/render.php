<?php
if (!defined('ABSPATH')) {
    exit;
}

$chips = ceeducon_block_array($attributes, 'chips');
$section_class = !empty($attributes['paper']) ? 'section section--paper' : 'section';
?>
<section class="<?php echo esc_attr($section_class); ?>">
  <div class="shell statement-grid">
    <div data-reveal>
      <p class="kicker"><?php echo ceeducon_block_text($attributes, 'kicker'); ?></p>
      <h2 class="display-2"><?php echo ceeducon_block_html($attributes, 'title'); ?></h2>
    </div>
    <div class="statement-copy" data-reveal="2">
      <p><?php echo ceeducon_block_html($attributes, 'text'); ?></p>
      <?php if (trim((string) ceeducon_block_value($attributes, 'secondText')) !== '') : ?>
        <p><?php echo ceeducon_block_html($attributes, 'secondText'); ?></p>
      <?php endif; ?>
      <?php if ($chips) : ?>
        <div class="fact-chips" aria-label="<?php esc_attr_e('Who attends', 'ceeducon-program'); ?>">
          <?php foreach ($chips as $chip) : ?>
            <?php if ($chip !== '') : ?><span><?php echo esc_html((string) $chip); ?></span><?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <?php echo ceeducon_render_block_button((string) ceeducon_block_value($attributes, 'buttonText'), (string) ceeducon_block_value($attributes, 'buttonUrl'), 'btn btn--outline mt-lg'); ?>
    </div>
  </div>
</section>
