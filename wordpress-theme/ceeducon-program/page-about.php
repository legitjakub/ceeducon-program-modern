<?php
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Template Name: CEEDUCON About
 */

get_header();

if (ceeducon_render_block_page_content()) {
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
            <img class="page-hero-card-logo" src="<?php echo esc_url(ceeducon_asset_url('assets/ceeducon-logo-horizontal-white.png')); ?>" alt="" width="1182" height="604" loading="lazy" decoding="async" />
            <span><?php ceeducon_text('about_card_label', '{{event_title}}'); ?></span>
            <strong><?php ceeducon_text('about_card_title', '{{date_short}} · {{city}}'); ?></strong>
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
              <h2 class="display-2"><?php ceeducon_text('about_themes_title', 'The {{year}} themes.'); ?></h2>
            </div>
            <p data-reveal="2"><?php ceeducon_text('about_themes_intro', 'Four thematic areas structure the sessions, workshops and plenaries of {{event_title}} — connecting technology, inclusion, partnerships and the student journey.'); ?></p>
          </div>
          <div class="theme-grid" aria-label="Conference thematic areas">
            <?php
            $theme_items = [];
            foreach (ceeducon_default_theme_items() as $index => $item) {
                $number = $index + 1;
                $theme_items[] = [
                    'number' => $item['number'],
                    'title' => ceeducon_text_value("theme_{$number}_title", $item['title']),
                    'text' => ceeducon_text_value("theme_{$number}_text", $item['text']),
                    'question' => ceeducon_text_value("theme_{$number}_question", $item['question']),
                    'details' => ceeducon_text_value("theme_{$number}_details", $item['details']),
                ];
            }
            ceeducon_render_theme_cards($theme_items);
            ?>
          </div>
        </div>
      </section>

      <section class="section section--paper">
        <div class="shell contact-band">
          <div data-reveal>
            <p class="kicker"><?php ceeducon_text('home_org_kicker', 'Organisers'); ?></p>
            <h2 class="display-2"><?php ceeducon_text('about_org_title', 'A Central European partnership.'); ?></h2>
            <p class="lead"><?php ceeducon_text('about_org_lead', 'CEEDUCON is organised by the Czech National Agency for International Education and Research (DZS) in co-operation with its partner agencies. Together they connect the national perspectives of Austria, Germany, Poland, Slovakia, Hungary and Czechia into one regional conversation.'); ?></p>
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

    </main>

<?php
get_footer();
