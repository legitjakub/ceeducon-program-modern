<?php
if (!defined('ABSPATH')) {
    exit;
}

$section_class = !empty($attributes['dark']) ? 'section section--navy on-dark' : 'section section--paper';
?>
<section class="<?php echo esc_attr($section_class); ?>">
  <div class="shell contact-band">
    <div data-reveal>
      <p class="kicker"><?php echo ceeducon_block_text($attributes, 'kicker'); ?></p>
      <h2 class="display-2"><?php echo ceeducon_block_html($attributes, 'title'); ?></h2>
      <p class="lead"><?php echo ceeducon_block_html($attributes, 'text'); ?></p>
      <div class="contact-actions">
        <?php echo ceeducon_render_block_button((string) ceeducon_block_value($attributes, 'primaryText'), (string) ceeducon_block_value($attributes, 'primaryUrl'), 'btn btn--primary'); ?>
        <?php echo ceeducon_render_block_button((string) ceeducon_block_value($attributes, 'secondaryText'), (string) ceeducon_block_value($attributes, 'secondaryUrl'), !empty($attributes['dark']) ? 'btn btn--ghost' : 'btn btn--outline'); ?>
      </div>
    </div>
    <div class="notice-card notice-card--sky" data-reveal="2">
      <span><?php echo ceeducon_block_text($attributes, 'noteLabel'); ?></span>
      <h3><?php echo ceeducon_block_html($attributes, 'noteTitle'); ?></h3>
      <p><?php echo ceeducon_block_html($attributes, 'noteText'); ?></p>
    </div>
  </div>
</section>
