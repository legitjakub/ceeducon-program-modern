<?php
/**
 * Template Name: CEEDUCON Media kit
 */

get_header();

if (ceeducon_render_elementor_page_content() || ceeducon_render_block_page_content()) {
    get_footer();
    return;
}

$banner_url = ceeducon_asset_url('assets/media/ceeducon-2026-official-banner.png');
$logo_url = ceeducon_asset_url('assets/ceeducon-logo-horizontal-white.png');
$partners_url = ceeducon_asset_url('assets/media/ceeducon-partner-logos-white.png');
?>

    <main id="main">
      <section class="page-hero">
        <div class="shell page-hero-grid">
          <div>
            <p class="page-crumbs"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'ceeducon-program'); ?></a><span>/</span><em><?php esc_html_e('Media kit', 'ceeducon-program'); ?></em></p>
            <h1><?php esc_html_e('Media resources.', 'ceeducon-program'); ?></h1>
            <p class="page-hero-note"><?php esc_html_e('Official CEEDUCON 2026 visual assets, press information and a direct contact for journalists and partner organisations.', 'ceeducon-program'); ?></p>
          </div>
          <div class="page-hero-card page-hero-card--orange">
            <span><?php esc_html_e('Media contact', 'ceeducon-program'); ?></span>
            <strong>ceeducon@dzs.cz</strong>
            <p><?php esc_html_e('Please include your outlet, deadline and the material you need.', 'ceeducon-program'); ?></p>
            <a class="btn btn--outline" href="mailto:ceeducon@dzs.cz"><?php esc_html_e('Email the team', 'ceeducon-program'); ?></a>
          </div>
        </div>
      </section>

      <?php ceeducon_render_editor_content(); ?>

      <section class="section section--paper">
        <div class="shell section-head">
          <div data-reveal>
            <p class="kicker"><?php esc_html_e('Downloads', 'ceeducon-program'); ?></p>
            <h2 class="display-2"><?php esc_html_e('Ready-to-use media assets.', 'ceeducon-program'); ?></h2>
          </div>
          <p data-reveal="2"><?php esc_html_e('Use the approved files below for editorial coverage and partner communication. Please preserve proportions, colours and clear space around the logos.', 'ceeducon-program'); ?></p>
        </div>
        <div class="shell media-kit-grid">
          <article class="media-kit-card" data-reveal>
            <img src="<?php echo esc_url($banner_url); ?>" alt="<?php esc_attr_e('Official CEEDUCON 2026 conference banner', 'ceeducon-program'); ?>" width="2162" height="1067" loading="lazy" decoding="async" />
            <span><?php esc_html_e('Official visual', 'ceeducon-program'); ?></span>
            <h3><?php esc_html_e('Conference banner', 'ceeducon-program'); ?></h3>
            <p><?php esc_html_e('High-resolution CEEDUCON 2026 key visual for digital publication.', 'ceeducon-program'); ?></p>
            <a class="btn btn--outline" href="<?php echo esc_url($banner_url); ?>" download><?php esc_html_e('Download PNG', 'ceeducon-program'); ?></a>
          </article>
          <article class="media-kit-card media-kit-card--dark" data-reveal="2">
            <img src="<?php echo esc_url($logo_url); ?>" alt="CEEDUCON" width="1182" height="604" loading="lazy" decoding="async" />
            <span><?php esc_html_e('Logo', 'ceeducon-program'); ?></span>
            <h3><?php esc_html_e('CEEDUCON logo', 'ceeducon-program'); ?></h3>
            <p><?php esc_html_e('Horizontal conference logo prepared for light backgrounds.', 'ceeducon-program'); ?></p>
            <a class="btn btn--outline" href="<?php echo esc_url($logo_url); ?>" download><?php esc_html_e('Download PNG', 'ceeducon-program'); ?></a>
          </article>
          <article class="media-kit-card media-kit-card--dark" data-reveal="3">
            <img src="<?php echo esc_url($partners_url); ?>" alt="<?php esc_attr_e('CEEDUCON organiser and partner logos', 'ceeducon-program'); ?>" width="480" height="20" loading="lazy" decoding="async" />
            <span><?php esc_html_e('Partners', 'ceeducon-program'); ?></span>
            <h3><?php esc_html_e('Partner logo row', 'ceeducon-program'); ?></h3>
            <p><?php esc_html_e('Official organiser and partner logo row for dark backgrounds.', 'ceeducon-program'); ?></p>
            <a class="btn btn--outline" href="<?php echo esc_url($partners_url); ?>" download><?php esc_html_e('Download PNG', 'ceeducon-program'); ?></a>
          </article>
        </div>
      </section>

      <section class="section">
        <div class="shell press-list">
          <div data-reveal>
            <p class="kicker"><?php esc_html_e('Press releases', 'ceeducon-program'); ?></p>
            <h2 class="display-2"><?php esc_html_e('News for media and partners.', 'ceeducon-program'); ?></h2>
          </div>
          <article class="press-item" data-reveal="2">
            <span><?php esc_html_e('Coming soon', 'ceeducon-program'); ?></span>
            <div>
              <h3><?php esc_html_e('CEEDUCON 2026 press releases', 'ceeducon-program'); ?></h3>
              <p><?php esc_html_e('Approved press releases and announcements will be published here as they become available.', 'ceeducon-program'); ?></p>
            </div>
          </article>
        </div>
      </section>
    </main>

<?php
get_footer();
