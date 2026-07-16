<?php
/**
 * CEEDUCON home page.
 */

get_header();

if (ceeducon_render_elementor_page_content() || ceeducon_render_block_page_content()) {
    get_footer();
    return;
}
?>

    <main id="main">
      <section class="hero">
        <div class="hero-media">
          <img src="<?php echo esc_url(ceeducon_text_value('home_hero_image_url', ceeducon_asset_url('assets/media/ceeducon-photo-plenary.jpg'))); ?>" alt="<?php echo esc_attr(ceeducon_text_value('home_hero_image_alt', 'A packed CEEDUCON plenary session')); ?>" width="1600" height="1064" decoding="async" fetchpriority="high" />
        </div>
        <div class="hero-inner shell">
          <div class="hero-copy">
            <p class="hero-kicker"><?php ceeducon_text('home_hero_kicker', 'CEEDUCON 2026 · Prague'); ?></p>
            <h1><?php ceeducon_html('home_hero_title', 'Central Europe <em>meets the world</em> of higher education.'); ?></h1>
            <p class="hero-lead"><?php ceeducon_text('home_hero_lead', 'Two days of practical exchange for university leaders, international offices, policymakers and national agencies.'); ?></p>
            <div class="hero-actions" aria-label="Quick actions">
              <a class="btn btn--primary" href="<?php echo esc_url(ceeducon_page_url('programme')); ?>"><?php ceeducon_text('home_cta_primary', 'Explore programme'); ?></a>
              <a class="btn btn--ghost" href="<?php echo esc_url(ceeducon_page_url('practical')); ?>"><?php ceeducon_text('home_cta_secondary', 'Plan your visit'); ?></a>
            </div>
          </div>
        </div>
        <div class="hero-facts-wrap">
          <div class="hero-facts shell" aria-label="Conference essentials">
            <div class="hero-date"><strong><?php ceeducon_text('event_day', '1–2'); ?></strong><span><?php ceeducon_html('event_month', 'DEC<br />2026'); ?></span></div>
            <div class="hero-fact hero-fact--1"><span><?php ceeducon_text('event_row_1_label', 'Venue'); ?></span><strong><?php ceeducon_text('event_row_1_value', 'O2 universum Prague'); ?></strong></div>
            <div class="hero-fact hero-fact--2"><span><?php ceeducon_text('event_row_2_label', 'Format'); ?></span><strong><?php ceeducon_text('event_row_2_value', 'Two conference days onsite'); ?></strong></div>
            <div class="hero-fact hero-fact--3"><span><?php ceeducon_text('event_row_3_label', 'Fee'); ?></span><strong><?php ceeducon_text('event_row_3_value', 'Free of charge'); ?></strong></div>
            <div class="hero-fact hero-fact--4"><span><?php ceeducon_text('event_row_4_label', 'Registration'); ?></span><strong><?php ceeducon_text('event_row_4_value', 'Opens in September'); ?></strong></div>
            <a class="hero-calendar" href="<?php echo esc_url(ceeducon_text_value('event_calendar_url', ceeducon_asset_url('assets/ceeducon-2026.ics'))); ?>" download><?php ceeducon_text('event_calendar_label', 'Add to calendar'); ?> <span class="ui-icon" aria-hidden="true"><svg viewBox="0 0 16 16"><path d="M6 4h6v6M12 4 5 11"></path></svg></span></a>
          </div>
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

      <?php
      ceeducon_print_section('video', [
          'kicker' => ceeducon_text_value('home_video_kicker', 'CEEDUCON in motion'),
          'title' => ceeducon_text_value('home_video_title', 'See the conference come to life.'),
          'text' => ceeducon_text_value('home_video_text', 'Step inside CEEDUCON and experience the plenaries, practical sessions and conversations that connect the international higher education community.'),
          'videoUrl' => ceeducon_text_value('home_video_url', 'https://www.youtube.com/watch?v=oad5sn8ku1c'),
          'videoTitle' => ceeducon_text_value('home_video_accessible_title', 'CEEDUCON conference video'),
          'buttonText' => ceeducon_text_value('home_video_button', 'Watch on YouTube'),
          'caption' => ceeducon_text_value('home_video_caption', 'Highlights from CEEDUCON'),
      ]);
      ?>

      <?php
      ceeducon_print_section('photo-gallery', [
          'kicker' => ceeducon_text_value('media_kicker', 'Conference atmosphere'),
          'title' => ceeducon_text_value('media_title', 'The people and conversations behind the programme.'),
          'text' => ceeducon_text_value('media_text', 'Plenaries, workshops and informal conversations make CEEDUCON a practical meeting point for the international higher education community.'),
          'buttonText' => ceeducon_text_value('media_button_primary', 'Browse programme'),
          'buttonUrl' => ceeducon_page_url('programme'),
          'items' => [
              ['imageUrl' => ceeducon_text_value('media_hero_url', ceeducon_asset_url('assets/media/ceeducon-photo-plenary.jpg')), 'imageAlt' => ceeducon_text_value('media_hero_alt', 'CEEDUCON plenary session with a full audience'), 'label' => ceeducon_text_value('media_hero_label', 'Plenary')],
              ['imageUrl' => ceeducon_text_value('media_image_1_url', ceeducon_asset_url('assets/media/ceeducon-photo-networking.jpg')), 'imageAlt' => ceeducon_text_value('media_image_1_alt', 'CEEDUCON participants talking during a networking break'), 'label' => ceeducon_text_value('media_image_1_label', 'Networking')],
              ['imageUrl' => ceeducon_text_value('media_image_2_url', ceeducon_asset_url('assets/media/ceeducon-photo-workshop.jpg')), 'imageAlt' => ceeducon_text_value('media_image_2_alt', 'CEEDUCON workshop with participants seated around tables'), 'label' => ceeducon_text_value('media_image_2_label', 'Workshops')],
              ['imageUrl' => ceeducon_text_value('media_image_3_url', ceeducon_asset_url('assets/media/ceeducon-photo-registration.jpg')), 'imageAlt' => ceeducon_text_value('media_image_3_alt', 'Participants arriving and registering at CEEDUCON'), 'label' => ceeducon_text_value('media_image_3_label', 'Arrival')],
              ['imageUrl' => ceeducon_text_value('media_image_4_url', ceeducon_asset_url('assets/media/ceeducon-photo-accessibility.jpg')), 'imageAlt' => ceeducon_text_value('media_image_4_alt', 'CEEDUCON participants speaking in an accessible lounge area'), 'label' => ceeducon_text_value('media_image_4_label', 'Community')],
          ],
      ]);
      ?>

      <section class="section section--tint theme-section-light">
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

      <section class="section">
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

      <section class="section section--paper">
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

      <section class="section section--tint">
        <div class="shell">
          <div class="section-head">
            <div data-reveal>
              <p class="kicker"><?php ceeducon_text('home_plan_kicker', 'Plan ahead'); ?></p>
              <h2 class="display-2"><?php ceeducon_text('home_plan_title', 'Find the essentials quickly.'); ?></h2>
            </div>
          </div>
          <div class="tile-grid">
            <a class="link-tile link-tile--media" href="<?php echo esc_url(ceeducon_page_url('practical')); ?>" data-reveal>
              <span class="link-tile-media">
                <img src="<?php ceeducon_url('home_link_1_image_url', ceeducon_asset_url('assets/media/ceeducon-photo-registration.jpg')); ?>" alt="<?php ceeducon_attr('home_link_1_image_alt', 'Participants arriving and registering at CEEDUCON'); ?>" width="1600" height="1064" loading="lazy" decoding="async" />
              </span>
              <span class="link-tile-body">
                <span class="link-tile-label"><?php ceeducon_text('home_link_1_label', 'Practical'); ?></span>
                <h3><?php ceeducon_text('home_link_1_title', 'Getting to the conference'); ?></h3>
                <p><?php ceeducon_text('home_link_1_text', 'Venue, transport from the airport and stations, accessibility and accommodation tips.'); ?></p>
              </span>
            </a>
            <a class="link-tile link-tile--media" href="<?php echo esc_url(ceeducon_page_url('speakers')); ?>" data-reveal="2">
              <span class="link-tile-media">
                <img src="<?php ceeducon_url('home_link_2_image_url', ceeducon_asset_url('assets/media/ceeducon-photo-workshop.jpg')); ?>" alt="<?php ceeducon_attr('home_link_2_image_alt', 'A CEEDUCON speaker leading a workshop'); ?>" width="1600" height="1064" loading="lazy" decoding="async" />
              </span>
              <span class="link-tile-body">
                <span class="link-tile-label"><?php ceeducon_text('home_link_2_label', 'For speakers'); ?></span>
                <h3><?php ceeducon_text('home_link_2_title', 'Speaking at CEEDUCON'); ?></h3>
                <p><?php ceeducon_text('home_link_2_text', 'Session expectations, onsite delivery, timeline and speaker support in one overview.'); ?></p>
              </span>
            </a>
            <a class="link-tile link-tile--media" href="<?php echo esc_url(ceeducon_page_url('media')); ?>" data-reveal="3">
              <span class="link-tile-media">
                <img src="<?php ceeducon_url('home_link_3_image_url', ceeducon_asset_url('assets/media/ceeducon-photo-plenary.jpg')); ?>" alt="<?php ceeducon_attr('home_link_3_image_alt', 'A packed CEEDUCON plenary session'); ?>" width="1600" height="1064" loading="lazy" decoding="async" />
              </span>
              <span class="link-tile-body">
                <span class="link-tile-label"><?php ceeducon_text('home_link_3_label', 'Media kit'); ?></span>
                <h3><?php ceeducon_text('home_link_3_title', 'Official assets and press information'); ?></h3>
                <p><?php ceeducon_text('home_link_3_text', 'Download approved visuals, find press updates and contact the team for media requests.'); ?></p>
              </span>
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
