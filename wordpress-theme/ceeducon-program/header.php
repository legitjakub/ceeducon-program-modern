<!doctype html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="<?php echo esc_attr__('CEEDUCON 2026 takes place on 1-2 December 2026 at O2 universum Prague. Explore the conference overview, practical information and an interactive archive programme module.', 'ceeducon-program'); ?>" />
    <meta name="theme-color" content="#0D5E9D" />
    <link rel="canonical" href="<?php echo esc_url(home_url('/')); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="CEEDUCON 2026 | Internationalisation of Higher Education" />
    <meta property="og:description" content="Central European Conference on Internationalisation of Higher Education. 1-2 December 2026, O2 universum Prague." />
    <meta property="og:url" content="<?php echo esc_url(home_url('/')); ?>" />
    <meta property="og:image" content="<?php echo esc_url(ceeducon_asset_url('assets/reference-program.png')); ?>" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="CEEDUCON 2026 | Internationalisation of Higher Education" />
    <meta name="twitter:description" content="Conference overview, practical information and an interactive programme module." />
    <meta name="twitter:image" content="<?php echo esc_url(ceeducon_asset_url('assets/reference-program.png')); ?>" />
    <link rel="icon" href="<?php echo esc_url(ceeducon_asset_url('assets/favicon.png')); ?>" />
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <a class="skip-link" href="#schedule">Skip to programme</a>

    <header class="site-header">
      <div class="header-inner shell">
        <a class="brand brand--image" href="#top" aria-label="CEEDUCON 2026 home">
          <img src="<?php echo esc_url(ceeducon_asset_url('assets/ceeducon-logo-horizontal-white.png')); ?>" alt="CEEDUCON" />
        </a>
        <nav class="header-nav" aria-label="Main navigation">
          <a class="is-active" href="#programme-2026">Programme</a>
          <a href="#about">About</a>
          <a href="#themes">Themes</a>
          <a href="#practical">Practical</a>
          <a href="#speakers">Speakers</a>
          <a href="#contact">Contact</a>
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
        <a href="#programme-2026">Programme</a>
        <a href="#about">About</a>
        <a href="#themes">Themes</a>
        <a href="#practical">Practical</a>
        <a href="#speakers">Speakers</a>
        <a href="#contact">Contact</a>
      </nav>
    </header>
