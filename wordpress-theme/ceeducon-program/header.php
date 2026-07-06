<!doctype html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="#06304f" />
    <link rel="icon" href="<?php echo esc_url(ceeducon_asset_url('assets/favicon.png')); ?>" />
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?> id="top">
    <?php wp_body_open(); ?>
    <a class="skip-link" href="#main"><?php esc_html_e('Skip to content', 'ceeducon-program'); ?></a>

    <header class="site-header">
      <div class="header-inner shell">
        <a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="CEEDUCON 2026 home">
          <img src="<?php echo esc_url(ceeducon_asset_url('assets/ceeducon-logo-horizontal-white.png')); ?>" alt="CEEDUCON" />
        </a>
        <nav class="header-nav" aria-label="Main navigation">
          <?php foreach (ceeducon_nav_items() as $slug => $label) : ?>
            <a<?php echo ceeducon_is_current($slug) ? ' class="is-active"' : ''; ?> href="<?php echo esc_url(ceeducon_page_url($slug)); ?>"><?php echo esc_html($label); ?></a>
          <?php endforeach; ?>
        </nav>
        <div class="header-actions">
          <button class="menu-toggle" type="button" data-menu-toggle aria-expanded="false" aria-controls="mobile-menu">
            <span><?php esc_html_e('Menu', 'ceeducon-program'); ?></span>
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" /></svg>
          </button>
        </div>
      </div>
      <nav class="mobile-menu shell" id="mobile-menu" data-mobile-menu aria-label="Mobile navigation" hidden>
        <?php foreach (ceeducon_nav_items() as $slug => $label) : ?>
          <a<?php echo ceeducon_is_current($slug) ? ' class="is-active"' : ''; ?> href="<?php echo esc_url(ceeducon_page_url($slug)); ?>"><?php echo esc_html($label); ?></a>
        <?php endforeach; ?>
      </nav>
    </header>
