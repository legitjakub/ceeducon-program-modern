<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="#0d5e9d" />
    <link rel="preload" href="<?php echo esc_url(ceeducon_asset_url('assets/fonts/Tabac-Sans-Regular.woff2')); ?>" as="font" type="font/woff2" crossorigin />
    <link rel="preload" href="<?php echo esc_url(ceeducon_asset_url('assets/fonts/Tabac-Sans-Medium.woff2')); ?>" as="font" type="font/woff2" crossorigin />
    <link rel="icon" type="image/png" sizes="64x64" href="<?php echo esc_url(add_query_arg('ver', wp_get_theme()->get('Version'), ceeducon_asset_url('assets/favicon.png'))); ?>" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url(add_query_arg('ver', wp_get_theme()->get('Version'), ceeducon_asset_url('assets/apple-touch-icon.png'))); ?>" />
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?> id="top">
    <?php wp_body_open(); ?>
    <a class="skip-link" href="#main"><?php esc_html_e('Skip to content', 'ceeducon-program'); ?></a>

    <header class="site-header">
      <div class="header-inner shell">
        <a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(ceeducon_text_value('site_home_label', '{{event_title}} home')); ?>">
          <?php $custom_logo_id = (int) get_theme_mod('custom_logo'); ?>
          <?php if ($custom_logo_id > 0) : ?>
            <?php echo wp_get_attachment_image($custom_logo_id, 'full', false, ['class' => 'custom-logo', 'decoding' => 'async']); ?>
          <?php else : ?>
            <img class="brand-mark" src="<?php echo esc_url(ceeducon_asset_url('assets/ceeducon-logo-horizontal-black.png')); ?>" alt="" width="1182" height="604" decoding="async" />
          <?php endif; ?>
        </a>
        <?php ceeducon_render_navigation('header-nav', __('Main navigation', 'ceeducon-program')); ?>
        <div class="header-actions">
          <?php $ceeducon_registration_url = ceeducon_registration_url(); ?>
          <?php if ($ceeducon_registration_url !== '') : ?>
            <a class="header-cta" href="<?php echo esc_url($ceeducon_registration_url); ?>" target="_blank" rel="noreferrer">
              <span><?php echo esc_html(ceeducon_text_value('nav_cta_register', __('Register', 'ceeducon-program'))); ?></span>
              <svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h13M12.5 5.5 19 12l-6.5 6.5"/></svg>
            </a>
          <?php endif; ?>
          <button class="menu-toggle" type="button" data-menu-toggle aria-expanded="false" aria-controls="mobile-menu">
            <span><?php esc_html_e('Menu', 'ceeducon-program'); ?></span>
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" /></svg>
          </button>
        </div>
      </div>
        <?php ceeducon_render_mobile_navigation(); ?>
    </header>
