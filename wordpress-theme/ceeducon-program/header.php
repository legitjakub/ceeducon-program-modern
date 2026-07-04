<!doctype html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="<?php echo esc_attr__('CEEDUCON is the Central European Conference on Internationalisation of Higher Education. Explore the modern interactive conference programme.', 'ceeducon-program'); ?>" />
    <meta name="theme-color" content="#0D5E9D" />
    <link rel="icon" href="<?php echo esc_url(ceeducon_asset_url('assets/favicon.png')); ?>" />
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <a class="skip-link" href="#schedule">Skip to programme</a>

    <header class="site-header">
      <div class="header-inner shell">
        <a class="brand brand--image" href="#top" aria-label="CEEDUCON 2025 home">
          <img src="<?php echo esc_url(ceeducon_asset_url('assets/ceeducon-logo-horizontal-white.png')); ?>" alt="CEEDUCON" />
        </a>
        <nav class="header-nav" aria-label="Main navigation">
          <a class="is-active" href="#schedule">Programme</a>
          <a href="#about">About</a>
          <a href="#themes">Themes</a>
          <a href="#venue">Venue</a>
        </nav>
        <div class="header-actions">
          <button class="menu-toggle" type="button" data-menu-toggle aria-expanded="false" aria-controls="mobile-menu">
            <span>Menu</span>
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" /></svg>
          </button>
          <button class="header-print" type="button" data-print>
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 8V3h10v5M7 17H5a2 2 0 0 1-2-2v-5h18v5a2 2 0 0 1-2 2h-2M7 14h10v7H7z" /></svg>
            <span>Print / PDF</span>
          </button>
        </div>
      </div>
      <nav class="mobile-menu shell" id="mobile-menu" data-mobile-menu aria-label="Mobile navigation" hidden>
        <a href="#schedule">Programme</a>
        <a href="#about">About</a>
        <a href="#themes">Themes</a>
        <a href="#venue">Venue</a>
      </nav>
    </header>

