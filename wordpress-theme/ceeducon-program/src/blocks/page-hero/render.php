<?php
if (!defined('ABSPATH')) {
    exit;
}

$section_class = !empty($attributes['orange']) ? 'page-hero page-hero--orange' : 'page-hero';
?>
<section class="<?php echo esc_attr($section_class); ?>">
  <div class="shell page-hero-grid">
    <div>
      <p class="page-crumbs"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'ceeducon-program'); ?></a><span>/</span><em><?php echo ceeducon_block_text($attributes, 'crumb'); ?></em></p>
      <h1><?php echo ceeducon_block_html($attributes, 'title'); ?></h1>
      <p class="page-hero-note"><?php echo ceeducon_block_html($attributes, 'note'); ?></p>
    </div>
    <div class="page-hero-card">
      <span><?php echo ceeducon_block_text($attributes, 'cardLabel'); ?></span>
      <strong><?php echo ceeducon_block_html($attributes, 'cardTitle'); ?></strong>
      <p><?php echo ceeducon_block_html($attributes, 'cardText'); ?></p>
    </div>
  </div>
</section>
