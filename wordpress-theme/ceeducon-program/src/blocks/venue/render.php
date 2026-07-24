<?php
if (!defined('ABSPATH')) {
    exit;
}
$section_class = !empty($attributes['paper']) ? 'section section--paper' : 'section';
?>
<section class="<?php echo esc_attr($section_class); ?>">
  <div class="shell feature-split" data-reveal>
    <div><p class="kicker"><?php echo ceeducon_block_text($attributes, 'kicker'); ?></p><h2 class="display-2"><?php echo ceeducon_block_html($attributes, 'title'); ?></h2><p><?php echo ceeducon_block_html($attributes, 'text'); ?></p><?php echo ceeducon_render_block_button((string) ceeducon_block_value($attributes, 'buttonText'), (string) ceeducon_block_value($attributes, 'buttonUrl'), 'btn btn--outline'); ?></div>
    <div class="feature-panel"><span><?php echo ceeducon_block_text($attributes, 'panelLabel'); ?></span><strong><?php echo ceeducon_block_text($attributes, 'panelTitle'); ?></strong><p><?php echo ceeducon_block_html($attributes, 'panelText'); ?></p></div>
  </div>
</section>
