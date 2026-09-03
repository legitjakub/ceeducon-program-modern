<?php
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Template Name: CEEDUCON Media kit
 */

get_header();

if (ceeducon_render_block_page_content()) {
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
            <p class="page-hero-note"><?php ceeducon_text('media_hero_note', 'Official {{event_title}} visual assets, press information and a direct contact for journalists and partner organisations.'); ?></p>
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
            <img src="<?php echo esc_url($banner_url); ?>" alt="<?php echo esc_attr(ceeducon_text_value('media_banner_alt', 'Official {{event_title}} conference banner')); ?>" width="2162" height="1067" loading="lazy" decoding="async" />
            <span><?php esc_html_e('Official visual', 'ceeducon-program'); ?></span>
            <h3><?php esc_html_e('Conference banner', 'ceeducon-program'); ?></h3>
            <p><?php ceeducon_text('media_banner_text', 'High-resolution {{event_title}} key visual for digital publication.'); ?></p>
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
        <div class="shell">
          <?php /* The heading belongs outside .press-list: that list paints its
                   own background so the 1px gaps between rows read as hairlines,
                   and anything else placed inside it sits on that colour. */ ?>
          <div class="section-head">
            <div data-reveal>
              <p class="kicker"><?php esc_html_e('Press releases', 'ceeducon-program'); ?></p>
              <h2 class="display-2"><?php esc_html_e('News for media and partners.', 'ceeducon-program'); ?></h2>
            </div>
            <p data-reveal="2"><?php ceeducon_text('media_press_lead', 'Materials from previous editions are below. {{event_title}} releases will be added here as the organisers approve them.'); ?></p>
          </div>
          <?php
          /**
           * Each row offers one or two years. The URL of a year is a setting
           * rather than markup, so a new edition is added from the admin — and
           * a year whose URL is still empty prints nothing at all, instead of a
           * link that leads nowhere.
           */
          $ceeducon_press_rows = [
              [
                  'format' => __('PDF', 'ceeducon-program'),
                  'title' => __('Press releases', 'ceeducon-program'),
                  'text' => __('Download press releases from previous editions of CEEDUCON.', 'ceeducon-program'),
                  'action' => __('View press releases', 'ceeducon-program'),
                  'years' => [
                      '2025' => ceeducon_text_value('media_release_2025_url', 'https://www.dzs.cz/sites/default/files/press_release/2025-11/TZ_CEEDUCON_2025_st%C5%99edoevropsk%C3%A1%20konfernce%20V%C5%A0%20v%20Praze.pdf'),
                      '2024' => ceeducon_text_value('media_release_2024_url', 'https://www.dzs.cz/sites/default/files/press_release/2024-11/Press_Release_CZEDUCON_2024.pdf'),
                  ],
              ],
              [
                  'format' => __('Online', 'ceeducon-program'),
                  'title' => __('Highlight reports', 'ceeducon-program'),
                  'text' => __('Explore the key topics, speakers, figures and moments from previous editions.', 'ceeducon-program'),
                  'action' => __('View highlight reports', 'ceeducon-program'),
                  'years' => [
                      '2025' => ceeducon_text_value('media_report_2025_url', ''),
                      '2024' => ceeducon_text_value('media_report_2024_url', ''),
                  ],
              ],
              [
                  'format' => __('ZIP', 'ceeducon-program'),
                  'title' => __('Conference photos', 'ceeducon-program'),
                  'text' => __('Photographs from CEEDUCON 2025, free to publish with editorial coverage. Please credit DZS.', 'ceeducon-program'),
                  'action' => __('Download conference photos', 'ceeducon-program'),
                  'years' => ['2025' => ceeducon_text_value('media_photos_url', '')],
              ],
          ];
          ?>
          <div class="press-list" data-reveal>
            <?php foreach ($ceeducon_press_rows as $ceeducon_row) : ?>
              <?php $ceeducon_links = array_filter($ceeducon_row['years'], static fn($url): bool => trim((string) $url) !== ''); ?>
              <article class="press-item">
                <span><?php echo esc_html($ceeducon_row['format']); ?></span>
                <div>
                  <h3><?php echo esc_html($ceeducon_row['title']); ?></h3>
                  <p><?php echo esc_html($ceeducon_row['text']); ?></p>
                </div>
                <?php if ($ceeducon_links) : ?>
                  <p class="press-actions">
                    <?php foreach ($ceeducon_links as $ceeducon_year => $ceeducon_url) : ?>
                      <a class="press-year" href="<?php echo esc_url($ceeducon_url); ?>" target="_blank" rel="noreferrer"
                         aria-label="<?php echo esc_attr($ceeducon_row['action'] . ' — ' . $ceeducon_year); ?>"><?php echo esc_html($ceeducon_year); ?></a>
                    <?php endforeach; ?>
                  </p>
                <?php else : ?>
                  <p class="press-actions press-actions--empty"><?php esc_html_e('Links coming shortly', 'ceeducon-program'); ?></p>
                <?php endif; ?>
              </article>
            <?php endforeach; ?>
            <article class="press-item">
              <span><?php esc_html_e('Coming soon', 'ceeducon-program'); ?></span>
              <div>
                <h3><?php ceeducon_text('media_press_title', '{{event_title}} press releases'); ?></h3>
                <p><?php esc_html_e('Approved press releases and announcements will be published here as they become available.', 'ceeducon-program'); ?></p>
              </div>
            </article>
          </div>

          <div class="notice-cards">
            <div class="notice-card notice-card--sky notice-card--wide" data-reveal="2">
              <span><?php esc_html_e('For journalists', 'ceeducon-program'); ?></span>
              <h3><?php ceeducon_text('media_note_title', 'Interested in covering {{event_title}}?'); ?></h3>
              <p><?php ceeducon_html('media_note_text', 'Join us in {{city}} on {{date_short}}. For media accreditation, interview requests or press materials, contact us at <a href="mailto:press@dzs.cz">press@dzs.cz</a>.'); ?></p>
            </div>
          </div>
        </div>
      </section>
    </main>

<?php
get_footer();
