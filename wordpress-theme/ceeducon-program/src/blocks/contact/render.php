<?php
if (!defined('ABSPATH')) {
    exit;
}

$email = sanitize_email((string) ceeducon_block_value($attributes, 'email'));
$phone = (string) ceeducon_block_value($attributes, 'phone');
$logo_url = (string) ceeducon_block_value($attributes, 'logoUrl');
$logo_id = (int) ceeducon_block_value($attributes, 'logoId', 0);
$logo_alt = (string) ceeducon_block_value($attributes, 'logoAlt');
?>
<section class="section">
  <div class="shell contact-band">
    <div data-reveal>
      <p class="kicker"><?php echo ceeducon_block_text($attributes, 'kicker'); ?></p>
      <h2 class="display-2"><?php echo ceeducon_block_html($attributes, 'title'); ?></h2>
      <p class="lead">
        <?php echo ceeducon_block_html($attributes, 'text'); ?>
        <?php if ($email !== '') : ?>
          <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
        <?php endif; ?>
        <?php echo $phone !== '' ? esc_html(' · ' . $phone) : ''; ?>
      </p>
      <div class="contact-actions">
        <?php echo $email !== '' ? ceeducon_render_block_button((string) ceeducon_block_value($attributes, 'buttonText'), 'mailto:' . $email, 'btn btn--primary') : ''; ?>
        <?php echo ceeducon_render_block_button((string) ceeducon_block_value($attributes, 'secondaryText'), (string) ceeducon_block_value($attributes, 'secondaryUrl'), 'btn btn--outline'); ?>
      </div>
    </div>
    <div class="partners-card" data-reveal="2">
      <?php if ($logo_id > 0) : ?>
        <?php echo wp_get_attachment_image($logo_id, 'medium', false, ['alt' => $logo_alt, 'loading' => 'lazy', 'decoding' => 'async']); ?>
      <?php elseif ($logo_url !== '') : ?>
        <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($logo_alt); ?>" loading="lazy" decoding="async" />
      <?php endif; ?>
      <span><?php echo ceeducon_block_text($attributes, 'cardLabel'); ?></span>
      <strong><?php echo ceeducon_block_html($attributes, 'cardTitle'); ?></strong>
      <span><?php echo ceeducon_block_text($attributes, 'partnersLabel'); ?></span>
      <p><?php echo ceeducon_block_text($attributes, 'partnersText'); ?></p>
    </div>
  </div>
</section>
