<?php
if (!defined('ABSPATH')) {
    exit;
}
$logo_url = (string) ceeducon_block_value($attributes, 'logoUrl');
$logo_id = (int) ceeducon_block_value($attributes, 'logoId', 0);
if ($logo_url !== '' && !preg_match('#^(?:https?:)?//#', $logo_url) && !str_starts_with($logo_url, '/')) {
    $logo_url = ceeducon_asset_url($logo_url);
}
?>
<section class="section">
  <div class="shell contact-band">
    <div data-reveal><p class="kicker"><?php echo ceeducon_block_text($attributes, 'kicker'); ?></p><h2 class="display-2"><?php echo ceeducon_block_html($attributes, 'title'); ?></h2><p class="lead"><?php echo ceeducon_block_html($attributes, 'text'); ?><?php if (trim((string) ceeducon_block_value($attributes, 'email')) !== '') : ?> <a href="mailto:<?php echo esc_attr(sanitize_email((string) ceeducon_block_value($attributes, 'email'))); ?>"><?php echo esc_html((string) ceeducon_block_value($attributes, 'email')); ?></a><?php endif; ?></p></div>
    <div class="partners-card" data-reveal="2"><span><?php echo ceeducon_block_text($attributes, 'cardLabel'); ?></span><strong><?php echo ceeducon_block_text($attributes, 'cardTitle'); ?></strong><span><?php echo ceeducon_block_text($attributes, 'partnersLabel'); ?></span><p><?php echo ceeducon_block_html($attributes, 'partnersText'); ?></p></div>
  </div>
  <?php if ($logo_id > 0 || $logo_url !== '') : ?><div class="shell partner-logo-strip"><?php if ($logo_id > 0) : ?><?php echo wp_get_attachment_image($logo_id, 'full', false, ['alt' => (string) ceeducon_block_value($attributes, 'logoAlt'), 'loading' => 'lazy', 'decoding' => 'async']); ?><?php else : ?><img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr((string) ceeducon_block_value($attributes, 'logoAlt')); ?>" width="2560" height="109" loading="lazy" decoding="async" /><?php endif; ?></div><?php endif; ?>
</section>
