<?php
if (!defined('ABSPATH')) {
    exit;
}

$source_url = (string) ceeducon_block_value($attributes, 'videoUrl');
$video_id = '';
$parts = wp_parse_url($source_url);

if (is_array($parts)) {
    $host = strtolower((string) ($parts['host'] ?? ''));
    $path = trim((string) ($parts['path'] ?? ''), '/');

    if ($host === 'youtu.be') {
        $video_id = (string) strtok($path, '/');
    } elseif (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com'], true)) {
        if ($path === 'watch') {
            parse_str((string) ($parts['query'] ?? ''), $query);
            $video_id = (string) ($query['v'] ?? '');
        } elseif (str_starts_with($path, 'embed/')) {
            $video_id = (string) strtok(substr($path, 6), '/');
        } elseif (str_starts_with($path, 'shorts/')) {
            $video_id = (string) strtok(substr($path, 7), '/');
        }
    }
}

if (!preg_match('/^[A-Za-z0-9_-]{6,20}$/', $video_id)) {
    $video_id = '';
}

$embed_url = $video_id !== ''
    ? 'https://www.youtube-nocookie.com/embed/' . rawurlencode($video_id) . '?rel=0&playsinline=1'
    : '';
?>
<section class="section video-section">
  <div class="shell video-feature">
    <div class="video-feature-copy" data-reveal>
      <p class="kicker"><?php echo ceeducon_block_text($attributes, 'kicker'); ?></p>
      <h2 class="display-2"><?php echo ceeducon_block_html($attributes, 'title'); ?></h2>
      <p><?php echo ceeducon_block_html($attributes, 'text'); ?></p>
      <?php if ($source_url !== '' && trim((string) ceeducon_block_value($attributes, 'buttonText')) !== '') : ?>
        <a class="video-feature-link" href="<?php echo esc_url($source_url); ?>" target="_blank" rel="noopener noreferrer">
          <?php echo ceeducon_block_text($attributes, 'buttonText'); ?>
          <span class="ui-icon" aria-hidden="true"><svg viewBox="0 0 16 16"><path d="M6 4h6v6M12 4 5 11"></path></svg></span>
        </a>
      <?php endif; ?>
    </div>
    <div class="video-feature-stage" data-reveal="2">
      <?php if ($embed_url !== '') : ?>
        <div class="video-frame">
          <iframe src="<?php echo esc_url($embed_url); ?>" title="<?php echo esc_attr((string) ceeducon_block_value($attributes, 'videoTitle', 'CEEDUCON conference video')); ?>" width="1280" height="720" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
      <?php endif; ?>
      <?php if (trim((string) ceeducon_block_value($attributes, 'caption')) !== '') : ?>
        <span class="video-feature-caption"><?php echo ceeducon_block_text($attributes, 'caption'); ?></span>
      <?php endif; ?>
    </div>
  </div>
</section>
