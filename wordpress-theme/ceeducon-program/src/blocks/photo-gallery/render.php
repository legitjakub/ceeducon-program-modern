<?php
if (!defined('ABSPATH')) {
    exit;
}

$items = is_array($attributes['items'] ?? null) ? $attributes['items'] : [];
?>
<section class="section section--media section--navy on-dark photo-section">
  <div class="shell photo-showcase">
    <div class="photo-showcase-head">
      <div class="media-copy" data-reveal>
        <p class="kicker"><?php echo ceeducon_block_text($attributes, 'kicker'); ?></p>
        <h2 class="display-2"><?php echo ceeducon_block_html($attributes, 'title'); ?></h2>
      </div>
      <div class="photo-showcase-copy" data-reveal="2">
        <p><?php echo ceeducon_block_html($attributes, 'text'); ?></p>
        <?php echo ceeducon_render_block_button((string) ceeducon_block_value($attributes, 'buttonText'), (string) ceeducon_block_value($attributes, 'buttonUrl'), 'btn btn--ghost'); ?>
      </div>
    </div>
    <div class="photo-gallery-grid" aria-label="<?php esc_attr_e('CEEDUCON photo gallery', 'ceeducon-program'); ?>" data-reveal="3">
      <?php foreach ($items as $item) : ?>
        <?php if (!empty($item['imageUrl'])) : ?>
          <button class="photo-gallery-item" type="button" data-lightbox="<?php echo esc_url((string) $item['imageUrl']); ?>" data-lightbox-caption="<?php echo esc_attr((string) ($item['label'] ?? '')); ?>">
            <?php if (!empty($item['imageId'])) : ?>
              <?php
              echo wp_get_attachment_image(
                  (int) $item['imageId'],
                  'ceeducon-gallery',
                  false,
                  [
                      'alt' => (string) ($item['imageAlt'] ?? ''),
                      'loading' => 'lazy',
                      'decoding' => 'async',
                      'sizes' => '(max-width: 720px) 50vw, (max-width: 980px) 33vw, 20vw',
                  ]
              );
              ?>
            <?php else : ?>
              <img src="<?php echo esc_url((string) $item['imageUrl']); ?>" alt="<?php echo esc_attr((string) ($item['imageAlt'] ?? '')); ?>" width="900" height="900" loading="lazy" decoding="async" />
            <?php endif; ?>
            <?php if (!empty($item['label'])) : ?><span><?php echo esc_html((string) $item['label']); ?></span><?php endif; ?>
          </button>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
