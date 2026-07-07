<?php
if (!defined('ABSPATH')) {
    exit;
}

$section_class = !empty($attributes['paper']) ? 'section section--media section--paper' : 'section section--media';
$show_secondary = trim((string) ceeducon_block_value($attributes, 'secondaryText')) !== '' && trim((string) ceeducon_block_value($attributes, 'secondaryUrl')) !== '';
?>
<section class="<?php echo esc_attr($section_class); ?>">
  <div class="shell media-showcase <?php echo !empty($attributes['reverse']) ? 'is-reversed' : ''; ?>">
    <div class="media-copy" data-reveal>
      <p class="kicker"><?php echo ceeducon_block_text($attributes, 'kicker'); ?></p>
      <h2 class="display-2"><?php echo ceeducon_block_html($attributes, 'title'); ?></h2>
      <p><?php echo ceeducon_block_html($attributes, 'text'); ?></p>
      <div class="media-actions">
        <?php echo ceeducon_render_block_button((string) ceeducon_block_value($attributes, 'primaryText'), (string) ceeducon_block_value($attributes, 'primaryUrl'), 'btn btn--primary'); ?>
        <?php if ($show_secondary) : ?>
          <?php echo ceeducon_render_block_button((string) ceeducon_block_value($attributes, 'secondaryText'), (string) ceeducon_block_value($attributes, 'secondaryUrl'), 'btn btn--outline'); ?>
        <?php endif; ?>
      </div>
    </div>
    <div class="media-mosaic media-mosaic--single" data-reveal="2">
      <button class="media-tile media-tile--large" type="button" data-lightbox="<?php echo ceeducon_block_url($attributes, 'imageUrl'); ?>" data-lightbox-caption="<?php echo esc_attr((string) ceeducon_block_value($attributes, 'imageLabel')); ?>">
        <img src="<?php echo ceeducon_block_url($attributes, 'imageUrl'); ?>" alt="<?php echo esc_attr((string) ceeducon_block_value($attributes, 'imageAlt')); ?>" loading="lazy" decoding="async" />
        <?php if (trim((string) ceeducon_block_value($attributes, 'imageLabel')) !== '') : ?>
          <span><?php echo ceeducon_block_text($attributes, 'imageLabel'); ?></span>
        <?php endif; ?>
      </button>
    </div>
  </div>
</section>
