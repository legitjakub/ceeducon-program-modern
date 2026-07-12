<?php
/**
 * Template Name: CEEDUCON About
 */

get_header();

if (ceeducon_render_elementor_page_content() || ceeducon_render_block_page_content()) {
    get_footer();
    return;
}
?>

    <main id="main">
      <section class="page-hero">
        <div class="shell page-hero-grid">
          <div>
            <p class="page-crumbs"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'ceeducon-program'); ?></a><span>/</span><em><?php esc_html_e('About', 'ceeducon-program'); ?></em></p>
            <h1><?php ceeducon_text('about_hero_title', 'The conference for internationalisation of higher education.'); ?></h1>
            <p class="page-hero-note"><?php ceeducon_text('about_hero_note', "One forum for strategy, practice and cooperation — connecting Central Europe's higher education community with colleagues from around the world."); ?></p>
          </div>
          <div class="page-hero-card">
            <img src="<?php echo esc_url(ceeducon_asset_url('assets/ceeducon-logo-vertical.svg')); ?>" alt="" width="145" height="283" loading="lazy" decoding="async" />
            <span><?php ceeducon_text('about_card_label', 'CEEDUCON 2026'); ?></span>
            <strong><?php ceeducon_text('about_card_title', '1–2 December · Prague'); ?></strong>
            <p><?php ceeducon_text('about_card_text', 'Organised by DZS with national agencies from across Central Europe.'); ?></p>
          </div>
        </div>
      </section>

      <?php ceeducon_render_editor_content(); ?>

      <section class="section">
        <div class="shell statement-grid">
          <div data-reveal>
            <p class="kicker"><?php ceeducon_text('about_what_kicker', 'What is CEEDUCON'); ?></p>
            <h2 class="display-2"><?php ceeducon_text('about_what_title', 'A practical conference for the people shaping international higher education.'); ?></h2>
          </div>
          <div class="statement-copy" data-reveal="2">
            <p><?php ceeducon_text('about_what_text_1', 'CEEDUCON focuses on advancing global cooperation, strategy and innovation in higher education. It creates space for knowledge exchange, best practices and practical discussion around internationalisation strategy, digitalisation, inclusion, partnerships, mobility, alumni engagement and employability.'); ?></p>
            <p><?php ceeducon_text('about_what_text_2', 'The conference brings together university leaders, international office professionals, policymakers, national agencies and experts working across higher education — from first-hand practitioners to strategic decision-makers.'); ?></p>
            <div class="fact-chips" aria-label="Conference community">
              <span><?php ceeducon_text('home_chip_1', 'University leadership'); ?></span>
              <span><?php ceeducon_text('home_chip_2', 'International offices'); ?></span>
              <span><?php ceeducon_text('home_chip_3', 'Policymakers'); ?></span>
              <span><?php ceeducon_text('home_chip_4', 'National agencies'); ?></span>
              <span><?php ceeducon_text('about_chip_5', 'Experts & practitioners'); ?></span>
            </div>
          </div>
        </div>
        <div class="shell">
          <div class="stat-row" aria-label="CEEDUCON 2025 in numbers" data-reveal>
            <div><strong><?php ceeducon_text('stat_2_value', '900+'); ?></strong><span><?php ceeducon_text('stat_2_label', 'participants in 2025'); ?></span></div>
            <div><strong><?php ceeducon_text('stat_3_value', '130+'); ?></strong><span><?php ceeducon_text('stat_3_label', 'speakers in 2025'); ?></span></div>
            <div><strong><?php ceeducon_text('stat_4_value', '50+'); ?></strong><span><?php ceeducon_text('stat_4_label', 'sessions & workshops'); ?></span></div>
            <div><strong><?php ceeducon_text('about_stat_4_value', '6'); ?></strong><span><?php ceeducon_text('about_stat_4_label', 'partner agencies'); ?></span></div>
          </div>
        </div>
      </section>

      <section class="section section--navy on-dark">
        <div class="shell">
          <div class="section-head">
            <div data-reveal>
              <p class="kicker"><?php ceeducon_text('home_themes_kicker', 'Thematic areas'); ?></p>
              <h2 class="display-2"><?php ceeducon_text('about_themes_title', 'The 2026 themes.'); ?></h2>
            </div>
            <p data-reveal="2"><?php ceeducon_text('about_themes_intro', 'Four thematic areas structure the sessions, workshops and plenaries of CEEDUCON 2026 — connecting technology, inclusion, partnerships and the student journey.'); ?></p>
          </div>
          <div class="theme-grid" aria-label="Conference thematic areas">
            <article class="theme-card theme-card--sky" data-reveal>
              <span>01</span>
              <h3><?php ceeducon_text('theme_1_title', 'Navigating the Technological Shift'); ?></h3>
              <p><?php ceeducon_text('theme_1_text', 'Responsible use of AI, digitalisation, data analytics and new tools in international education — while keeping academic values and human judgement in focus.'); ?></p>
            </article>
            <article class="theme-card theme-card--orange" data-reveal="2">
              <span>02</span>
              <h3><?php ceeducon_text('theme_2_title', 'Challenges of Internationalisation'); ?></h3>
              <p><?php ceeducon_text('theme_2_text', 'Structural, social and financial barriers, safety, wellbeing, funding and inclusive access to meaningful international experiences for all students and staff.'); ?></p>
            </article>
            <article class="theme-card theme-card--white" data-reveal="3">
              <span>03</span>
              <h3><?php ceeducon_text('theme_3_title', 'Global & Regional Partnerships'); ?></h3>
              <p><?php ceeducon_text('theme_3_text', 'Sustainable strategic cooperation, European University alliances and equitable academic partnerships across global regions.'); ?></p>
            </article>
            <article class="theme-card theme-card--navy" data-reveal="4">
              <span>04</span>
              <h3><?php ceeducon_text('theme_4_title', 'From Recruitment to Retention'); ?></h3>
              <p><?php ceeducon_text('theme_4_text', 'A student-centred journey from marketing and admissions through support services to employability, alumni relations and graduate success.'); ?></p>
            </article>
          </div>
        </div>
      </section>

      <section class="section section--paper">
        <div class="shell contact-band">
          <div data-reveal>
            <p class="kicker"><?php ceeducon_text('home_org_kicker', 'Organisers'); ?></p>
            <h2 class="display-2"><?php ceeducon_text('about_org_title', 'A Central European partnership.'); ?></h2>
            <p class="lead"><?php ceeducon_text('about_org_lead', 'CEEDUCON is organised by the Czech National Agency for International Education and Research (DZS) in co-operation with its partner agencies. Together they connect the national perspectives of Austria, Germany, Poland, Slovakia, Hungary and the Czech Republic into one regional conversation.'); ?></p>
          </div>
          <div class="partners-card" data-reveal="2">
            <img src="<?php echo esc_url(ceeducon_asset_url('assets/dzs-logo.png')); ?>" alt="DZS" width="1024" height="522" loading="lazy" decoding="async" />
            <span><?php ceeducon_text('organiser_label', 'Organised by'); ?></span>
            <strong><?php ceeducon_text('organiser_name', 'Czech National Agency for International Education and Research (DZS)'); ?></strong>
            <span><?php ceeducon_text('partners_label', 'In co-operation with'); ?></span>
            <p><?php ceeducon_text('partners_text', 'OeAD · DAAD · FRSE · SAAIC · Tempus Public Foundation'); ?></p>
          </div>
        </div>
      </section>

      <section class="section">
        <div class="shell feature-split" data-reveal>
          <div>
            <p class="kicker"><?php ceeducon_text('home_venue_kicker', 'Venue'); ?></p>
            <h2 class="display-2"><?php ceeducon_text('home_venue_title', 'O2 universum Prague'); ?></h2>
            <p><?php ceeducon_text('about_venue_text', "Českomoravská 17, Prague 9. The venue's halls host the plenaries, thematic sessions and workshops of both conference days — fully accessible and minutes from the metro."); ?></p>
            <a class="btn btn--outline" href="<?php echo esc_url(ceeducon_page_url('practical')); ?>"><?php ceeducon_text('about_venue_button', 'Plan your visit'); ?></a>
          </div>
          <div class="feature-panel">
            <span><?php ceeducon_text('about_panel_label', 'Conference days'); ?></span>
            <strong><?php ceeducon_text('about_panel_title', '1–2 December 2026'); ?></strong>
            <p><?php ceeducon_text('about_panel_text', 'Registration opens in September. The preliminary programme is online and remains subject to change.'); ?></p>
          </div>
        </div>
      </section>
    </main>

<?php
get_footer();
