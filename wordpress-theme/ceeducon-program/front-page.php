<?php
/**
 * CEEDUCON home page.
 */

get_header();
?>

    <main id="main">
      <section class="hero">
        <span class="hero-ghost" aria-hidden="true">2026</span>
        <div class="hero-ring" aria-hidden="true"></div>
        <div class="hero-inner shell">
          <div class="hero-copy">
            <p class="hero-kicker"><?php ceeducon_text('home_hero_kicker', 'Central European Conference on Internationalisation of Higher Education'); ?></p>
            <h1><?php ceeducon_html('home_hero_title', 'Where Central Europe <em>meets the world</em> of higher education.'); ?></h1>
            <p class="hero-lead"><?php ceeducon_text('home_hero_lead', 'CEEDUCON brings together university leaders, international office professionals, policymakers and national agencies to advance cooperation, strategy and innovation in international higher education.'); ?></p>
            <div class="hero-meta" aria-label="Event highlights">
              <span><?php ceeducon_html('home_meta_1', '<strong>1–2 December</strong> 2026'); ?></span>
              <span><?php ceeducon_html('home_meta_2', '<strong>O2 universum</strong> Prague'); ?></span>
              <span><?php ceeducon_html('home_meta_3', '<strong>Free</strong> of charge'); ?></span>
              <span><?php ceeducon_html('home_meta_4', '<strong>English</strong>'); ?></span>
            </div>
            <div class="hero-actions" aria-label="Quick actions">
              <a class="btn btn--primary" href="<?php echo esc_url(ceeducon_page_url('programme')); ?>"><?php ceeducon_text('home_cta_primary', 'Explore the programme'); ?></a>
              <a class="btn btn--ghost" href="<?php echo esc_url(ceeducon_page_url('about')); ?>"><?php ceeducon_text('home_cta_secondary', 'About the conference'); ?></a>
            </div>
            <p class="countdown-strip" data-countdown aria-label="Countdown to CEEDUCON 2026">
              <strong data-countdown-days>149</strong>
              <span><?php ceeducon_text('countdown_suffix', 'days to the conference'); ?></span>
            </p>
          </div>
          <aside class="event-card" aria-label="Conference essentials">
            <div class="event-date">
              <strong><?php ceeducon_text('event_day', '1–2'); ?></strong>
              <span><?php ceeducon_html('event_month', 'DEC<br />2026'); ?></span>
            </div>
            <div class="event-card-row"><span><?php ceeducon_text('event_row_1_label', 'Venue'); ?></span><strong><?php ceeducon_text('event_row_1_value', 'O2 universum Prague'); ?></strong></div>
            <div class="event-card-row"><span><?php ceeducon_text('event_row_2_label', 'Format'); ?></span><strong><?php ceeducon_text('event_row_2_value', 'Two conference days onsite'); ?></strong></div>
            <div class="event-card-row"><span><?php ceeducon_text('event_row_3_label', 'Fee'); ?></span><strong><?php ceeducon_text('event_row_3_value', 'Free of charge'); ?></strong></div>
            <div class="event-card-row"><span><?php ceeducon_text('event_row_4_label', 'Registration'); ?></span><strong><?php ceeducon_text('event_row_4_value', 'Opens in September'); ?></strong></div>
            <a href="<?php echo esc_url(ceeducon_page_url('practical')); ?>"><?php ceeducon_text('event_cta', 'Plan your visit'); ?> <span>→</span></a>
            <a href="<?php echo esc_url(ceeducon_text_value('event_calendar_url', ceeducon_asset_url('assets/ceeducon-2026.ics'))); ?>" download><?php ceeducon_text('event_calendar_label', 'Add to calendar'); ?> <span>↗</span></a>
          </aside>
        </div>
        <div class="hero-stats shell" aria-label="Conference in numbers">
          <div><strong><?php ceeducon_text('stat_1_value', '2'); ?></strong><span><?php ceeducon_text('stat_1_label', 'conference days'); ?></span></div>
          <div><strong><?php ceeducon_text('stat_2_value', '900+'); ?></strong><span><?php ceeducon_text('stat_2_label', 'participants in 2025'); ?></span></div>
          <div><strong><?php ceeducon_text('stat_3_value', '130+'); ?></strong><span><?php ceeducon_text('stat_3_label', 'speakers in 2025'); ?></span></div>
          <div><strong><?php ceeducon_text('stat_4_value', '70+'); ?></strong><span><?php ceeducon_text('stat_4_label', 'sessions in 2026'); ?></span></div>
        </div>
      </section>

      <?php ceeducon_render_editor_content(); ?>

      <section class="section">
        <div class="shell statement-grid">
          <div data-reveal>
            <p class="kicker"><?php ceeducon_text('home_about_kicker', 'The conference'); ?></p>
            <h2 class="display-2"><?php ceeducon_text('home_about_title', 'A focused forum for international higher education.'); ?></h2>
          </div>
          <div class="statement-copy" data-reveal="2">
            <p><?php ceeducon_text('home_about_text_1', 'CEEDUCON connects people who work on internationalisation every day: university leadership, international offices, policymakers, national agencies and practitioners from across Europe.'); ?></p>
            <p><?php ceeducon_text('home_about_text_2', 'The programme is built around practical exchange: what is changing, what works in institutions, and where Central European cooperation can move higher education forward.'); ?></p>
            <div class="fact-chips" aria-label="Who attends">
              <span><?php ceeducon_text('home_chip_1', 'University leadership'); ?></span>
              <span><?php ceeducon_text('home_chip_2', 'International offices'); ?></span>
              <span><?php ceeducon_text('home_chip_3', 'Policymakers'); ?></span>
              <span><?php ceeducon_text('home_chip_4', 'National agencies'); ?></span>
            </div>
            <a class="btn btn--outline mt-lg" href="<?php echo esc_url(ceeducon_page_url('about')); ?>"><?php ceeducon_text('home_about_button', 'More about CEEDUCON'); ?></a>
          </div>
        </div>
      </section>

      <section class="section section--media">
        <div class="shell media-showcase">
          <div class="media-copy" data-reveal>
            <p class="kicker"><?php ceeducon_text('media_kicker', 'Conference atmosphere'); ?></p>
            <h2 class="display-2"><?php ceeducon_text('media_title', 'A professional setting for exchange.'); ?></h2>
            <p><?php ceeducon_text('media_text', 'Use the photos as a quick sense of the venue, audience and working atmosphere. The core of the website stays simple: programme first, then practical information for participants and speakers.'); ?></p>
            <div class="media-actions">
              <a class="btn btn--primary" href="<?php echo esc_url(ceeducon_page_url('programme')); ?>"><?php ceeducon_text('media_button_primary', 'Browse programme'); ?></a>
              <button class="btn btn--outline" type="button" data-lightbox="<?php echo esc_url(ceeducon_text_value('media_hero_url', ceeducon_asset_url('assets/media/ceeducon-2026-banner.png'))); ?>" data-lightbox-caption="<?php echo esc_attr(ceeducon_text_value('media_hero_caption', 'CEEDUCON 2026 visual identity')); ?>"><?php ceeducon_text('media_button_secondary', 'Open visual'); ?></button>
            </div>
          </div>
          <div class="media-mosaic" aria-label="<?php esc_attr_e('CEEDUCON photo gallery', 'ceeducon-program'); ?>" data-reveal="2">
            <?php
            $media_items = [
                ['media_hero_url', 'assets/media/ceeducon-2026-banner.png', 'media_hero_alt', 'CEEDUCON 2026 banner visual', 'media_hero_label', '2026 identity', 'media_hero_caption', 'CEEDUCON 2026 visual identity', 'media-tile--large'],
                ['media_image_1_url', 'assets/media/ceeducon-gallery-1.jpeg', 'media_image_1_alt', 'CEEDUCON participants at the conference', 'media_image_1_label', 'People', 'media_image_1_caption', 'CEEDUCON conference atmosphere', ''],
                ['media_image_2_url', 'assets/media/ceeducon-gallery-2.jpeg', 'media_image_2_alt', 'CEEDUCON session and networking moment', 'media_image_2_label', 'Sessions', 'media_image_2_caption', 'CEEDUCON sessions and networking', ''],
                ['media_image_3_url', 'assets/media/ceeducon-gallery-3.jpeg', 'media_image_3_alt', 'CEEDUCON venue and audience moment', 'media_image_3_label', 'Venue', 'media_image_3_caption', 'CEEDUCON venue moment', ''],
                ['media_image_4_url', 'assets/media/ceeducon-gallery-4.jpeg', 'media_image_4_alt', 'CEEDUCON discussion and exchange', 'media_image_4_label', 'Exchange', 'media_image_4_caption', 'CEEDUCON discussion and exchange', ''],
            ];
            foreach ($media_items as [$url_key, $default_url, $alt_key, $default_alt, $label_key, $default_label, $caption_key, $default_caption, $class]) :
                $url = ceeducon_text_value($url_key, ceeducon_asset_url($default_url));
                $alt = ceeducon_text_value($alt_key, $default_alt);
                $label = ceeducon_text_value($label_key, $default_label);
                $caption = ceeducon_text_value($caption_key, $default_caption);
                ?>
                <button class="media-tile <?php echo esc_attr($class); ?>" type="button" data-lightbox="<?php echo esc_url($url); ?>" data-lightbox-caption="<?php echo esc_attr($caption); ?>">
                  <img src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr($alt); ?>" loading="lazy" decoding="async" />
                  <span><?php echo esc_html($label); ?></span>
                </button>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <section class="section section--navy on-dark">
        <div class="shell">
          <div class="section-head">
            <div data-reveal>
              <p class="kicker"><?php ceeducon_text('home_themes_kicker', 'Thematic areas'); ?></p>
              <h2 class="display-2"><?php ceeducon_text('home_themes_title', 'Four themes frame the 2026 conversation.'); ?></h2>
            </div>
            <p data-reveal="2"><?php ceeducon_text('home_themes_intro', 'From responsible technology to the complete student journey — the 2026 programme connects the questions that matter most to international higher education right now.'); ?></p>
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
        <div class="shell">
          <div class="section-head">
            <div data-reveal>
              <p class="kicker"><?php ceeducon_text('home_prog_kicker', 'Programme 2026'); ?></p>
              <h2 class="display-2"><?php ceeducon_text('home_prog_title', 'Two full conference days in Prague.'); ?></h2>
            </div>
            <p data-reveal="2"><?php ceeducon_text('home_prog_intro', 'The preliminary room-by-room programme is online — sessions, workshops and speakers for both conference days.'); ?></p>
          </div>
          <div class="day-cards" aria-label="CEEDUCON 2026 outline">
            <article data-reveal>
              <span><?php ceeducon_text('day_1_label', 'Day 1 · Tue 1 Dec'); ?></span>
              <h3><?php ceeducon_text('day_1_title', 'All-day conference'); ?></h3>
              <p><?php ceeducon_text('day_1_text', 'Opening plenary and thematic sessions across the four 2026 themes at O2 universum.'); ?></p>
            </article>
            <article data-reveal="2">
              <span><?php ceeducon_text('day_evening_label', 'Evening'); ?></span>
              <h3><?php ceeducon_text('day_evening_title', 'Networking dinner'); ?></h3>
              <p><?php ceeducon_text('day_evening_text', 'An evening dedicated to informal exchange and new partnerships. Details will follow with the final programme.'); ?></p>
            </article>
            <article data-reveal="3">
              <span><?php ceeducon_text('day_2_label', 'Day 2 · Wed 2 Dec'); ?></span>
              <h3><?php ceeducon_text('day_2_title', 'All-day conference'); ?></h3>
              <p><?php ceeducon_text('day_2_text', 'A second full day of sessions and workshops, closing with a joint plenary.'); ?></p>
            </article>
          </div>
          <div class="notice-cards">
            <article class="notice-card notice-card--sky notice-card--wide" data-reveal>
              <span><?php ceeducon_text('notice_prog_label', 'Preliminary programme'); ?></span>
              <h3><?php ceeducon_text('notice_prog_title', 'Online now.'); ?></h3>
              <p><?php ceeducon_text('notice_prog_text', 'Browse the two-day programme — 70+ sessions and workshops across nine rooms. Details remain subject to change.'); ?></p>
              <a class="btn btn--dark" href="<?php echo esc_url(ceeducon_page_url('programme')); ?>"><?php ceeducon_text('notice_prog_button', 'Open the programme'); ?></a>
            </article>
          </div>
        </div>
      </section>

      <section class="section">
        <div class="shell feature-split" data-reveal>
          <div>
            <p class="kicker"><?php ceeducon_text('home_venue_kicker', 'Venue'); ?></p>
            <h2 class="display-2"><?php ceeducon_text('home_venue_title', 'O2 universum Prague'); ?></h2>
            <p><?php ceeducon_text('home_venue_text', 'Českomoravská 17, Prague 9. One of the largest conference venues in the Czech Republic hosts both CEEDUCON days — easy to reach by metro, fully accessible and built for a multi-room programme.'); ?></p>
            <a class="btn btn--outline" href="<?php echo esc_url(ceeducon_page_url('practical')); ?>"><?php ceeducon_text('home_venue_button', 'Practical information'); ?></a>
          </div>
          <div class="feature-panel">
            <span><?php ceeducon_text('home_venue_panel_label', 'Getting there'); ?></span>
            <strong><?php ceeducon_text('home_venue_panel_title', 'Metro B · Českomoravská'); ?></strong>
            <p><?php ceeducon_text('home_venue_panel_text', 'Around 55 minutes from Prague Airport by public transport, a short walk from Praha-Libeň railway station and steps from the Českomoravská metro stop.'); ?></p>
          </div>
        </div>
      </section>

      <section class="section section--paper">
        <div class="shell">
          <div class="section-head">
            <div data-reveal>
              <p class="kicker"><?php ceeducon_text('home_plan_kicker', 'Plan ahead'); ?></p>
              <h2 class="display-2"><?php ceeducon_text('home_plan_title', 'Find the essentials quickly.'); ?></h2>
            </div>
          </div>
          <div class="tile-grid">
            <a class="link-tile" href="<?php echo esc_url(ceeducon_page_url('practical')); ?>" data-reveal>
              <span><?php ceeducon_text('home_link_1_label', 'Practical'); ?></span>
              <h3><?php ceeducon_text('home_link_1_title', 'Getting to the conference'); ?></h3>
              <p><?php ceeducon_text('home_link_1_text', 'Venue, transport from the airport and stations, accessibility and accommodation tips.'); ?></p>
            </a>
            <a class="link-tile" href="<?php echo esc_url(ceeducon_page_url('speakers')); ?>" data-reveal="2">
              <span><?php ceeducon_text('home_link_2_label', 'For speakers'); ?></span>
              <h3><?php ceeducon_text('home_link_2_title', 'Speaking at CEEDUCON'); ?></h3>
              <p><?php ceeducon_text('home_link_2_text', 'Session expectations, onsite delivery, timeline and speaker support in one overview.'); ?></p>
            </a>
            <a class="link-tile" href="<?php echo esc_url(ceeducon_page_url('contact')); ?>" data-reveal="3">
              <span><?php ceeducon_text('home_link_3_label', 'Contact'); ?></span>
              <h3><?php ceeducon_text('home_link_3_title', 'Talk to the team'); ?></h3>
              <p><?php ceeducon_text('home_link_3_text', 'Use the contact page for registration, programme, speaker or partnership questions.'); ?></p>
            </a>
          </div>
        </div>
      </section>

      <section class="section">
        <div class="shell contact-band">
          <div data-reveal>
            <p class="kicker"><?php ceeducon_text('home_org_kicker', 'Organisers'); ?></p>
            <h2 class="display-2"><?php ceeducon_text('home_org_title', "Backed by Central Europe's national agencies."); ?></h2>
            <p class="lead"><?php ceeducon_html('home_org_lead', 'CEEDUCON is organised by DZS — the Czech National Agency for International Education and Research — in co-operation with partner organisations across the region. Reach the team at <a href="mailto:ceeducon@dzs.cz">ceeducon@dzs.cz</a>.'); ?></p>
          </div>
          <div class="partners-card" data-reveal="2">
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
