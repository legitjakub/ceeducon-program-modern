<?php
if (!defined('ABSPATH')) {
    exit;
}

$items = ceeducon_block_array($attributes, 'items');
$section_class = !empty($attributes['paper']) ? 'section section--paper' : 'section';
?>
<section class="<?php echo esc_attr($section_class); ?>">
  <div class="shell">
    <div class="section-head">
      <div data-reveal>
        <p class="kicker"><?php echo ceeducon_block_text($attributes, 'kicker'); ?></p>
        <h2 class="display-2"><?php echo ceeducon_block_html($attributes, 'title'); ?></h2>
      </div>
      <?php if (trim((string) ceeducon_block_value($attributes, 'intro')) !== '') : ?>
        <p data-reveal="2"><?php echo ceeducon_block_html($attributes, 'intro'); ?></p>
      <?php endif; ?>
    </div>
    <div class="tile-grid">
      <?php foreach ($items as $index => $item) : ?>
        <?php
        $image_url = (string) ($item['imageUrl'] ?? '');
        if ($image_url !== '' && !preg_match('#^(?:https?:)?//#', $image_url) && !str_starts_with($image_url, '/')) {
            $image_url = ceeducon_asset_url($image_url);
        }
        $tile_class = $image_url !== '' ? 'link-tile link-tile--media' : 'link-tile';
        ?>
        <?php if (!empty($item['url'])) : ?>
        <a class="<?php echo esc_attr($tile_class); ?>" href="<?php echo esc_url($item['url']); ?>" data-reveal="<?php echo esc_attr((string) ($index + 1)); ?>">
          <?php if ($image_url !== '') : ?>
            <span class="link-tile-media"><img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($item['imageAlt'] ?? ''); ?>" width="1600" height="1064" loading="lazy" decoding="async" /></span>
            <span class="link-tile-body"><span class="link-tile-label"><?php echo esc_html($item['label'] ?? ''); ?></span><h3><?php echo esc_html($item['title'] ?? ''); ?></h3><p><?php echo esc_html($item['text'] ?? ''); ?></p></span>
          <?php else : ?>
            <span><?php echo esc_html($item['label'] ?? ''); ?></span>
            <h3><?php echo esc_html($item['title'] ?? ''); ?></h3>
            <p><?php echo esc_html($item['text'] ?? ''); ?></p>
          <?php endif; ?>
        </a>
        <?php else : ?>
        <article class="<?php echo esc_attr($tile_class); ?>" data-reveal="<?php echo esc_attr((string) ($index + 1)); ?>">
          <?php if ($image_url !== '') : ?>
            <span class="link-tile-media"><img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($item['imageAlt'] ?? ''); ?>" width="1600" height="1064" loading="lazy" decoding="async" /></span>
            <span class="link-tile-body"><span class="link-tile-label"><?php echo esc_html($item['label'] ?? ''); ?></span><h3><?php echo esc_html($item['title'] ?? ''); ?></h3><p><?php echo esc_html($item['text'] ?? ''); ?></p></span>
          <?php else : ?>
            <span><?php echo esc_html($item['label'] ?? ''); ?></span>
            <h3><?php echo esc_html($item['title'] ?? ''); ?></h3>
            <p><?php echo esc_html($item['text'] ?? ''); ?></p>
          <?php endif; ?>
        </article>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
