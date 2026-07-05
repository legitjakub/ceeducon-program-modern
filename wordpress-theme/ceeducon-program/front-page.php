<?php
/**
 * CEEDUCON interactive programme front page.
 */

get_header();
?>

    <main id="top">
      <section class="hero">
        <div class="hero-logo-art" aria-hidden="true">
          <img src="<?php echo esc_url(ceeducon_asset_url('assets/ceeducon-logo-vertical.svg')); ?>" alt="" />
        </div>
        <div class="hero-shape hero-shape--red" aria-hidden="true"></div>
        <div class="hero-shape hero-shape--yellow" aria-hidden="true"></div>
        <div class="hero-inner shell">
          <div class="hero-copy">
            <p class="hero-kicker"><?php ceeducon_text('hero_kicker', 'Central European Conference on Internationalisation of Higher Education'); ?></p>
            <h1><?php ceeducon_text('hero_title', 'CEEDUCON 2026 for internationalisation in higher education.'); ?></h1>
            <p class="hero-lead"><?php ceeducon_text('hero_lead', "Central Europe's conference on higher education internationalisation returns to O2 universum Prague on 1-2 December 2026. The detailed programme will be published by September 1; this WordPress version shows how the content can work as a clear, editable and interactive experience."); ?></p>
            <div class="hero-meta" aria-label="Event highlights">
              <span><?php ceeducon_html('hero_meta_1', '<strong>1-2 Dec</strong> 2026'); ?></span>
              <span><?php ceeducon_html('hero_meta_2', '<strong>O2 universum</strong> Prague'); ?></span>
              <span><?php ceeducon_html('hero_meta_3', '<strong>Registration</strong> opens in September'); ?></span>
            </div>
            <div class="hero-actions" aria-label="Quick actions">
              <a class="hero-action hero-action--primary" href="#programme-2026"><?php ceeducon_text('hero_primary_cta', 'View 2026 overview'); ?></a>
              <a class="hero-action" href="#schedule"><?php ceeducon_text('hero_secondary_cta', 'Open archive programme module'); ?></a>
            </div>
          </div>
          <aside class="event-card" aria-label="Conference day preview">
            <div class="event-date">
              <strong><?php ceeducon_text('event_day', '1-2'); ?></strong>
              <span><?php ceeducon_html('event_month_year', 'DEC<br />2026'); ?></span>
            </div>
            <p><?php ceeducon_text('event_label', 'Conference overview'); ?></p>
            <div class="event-card-row"><span>Day 1</span><strong><?php ceeducon_text('event_start', 'All-day conference'); ?></strong></div>
            <div class="event-card-row"><span>Day 2</span><strong><?php ceeducon_text('event_block', 'All-day conference'); ?></strong></div>
            <div class="event-card-row"><span>Venue</span><strong><?php ceeducon_text('event_venue', 'O2 universum Prague'); ?></strong></div>
            <div class="event-route" aria-label="Programme preview">
              <span><?php ceeducon_text('event_route_1', 'Programme available by September 1'); ?></span>
              <span><?php ceeducon_text('event_route_2', 'Registration opens in September'); ?></span>
              <span><?php ceeducon_text('event_route_3', 'Four main thematic areas'); ?></span>
            </div>
            <a href="#programme-2026"><?php ceeducon_text('event_cta', 'See current status'); ?> <span>↓</span></a>
          </aside>
        </div>
        <div class="hero-stats shell" aria-label="Programme summary">
          <div><strong>2</strong><span>conference days</span></div>
          <div><strong>900+</strong><span>past participants</span></div>
          <div><strong>130+</strong><span>past speakers</span></div>
          <div><strong>50+</strong><span>past sessions</span></div>
        </div>
      </section>

      <section class="about-section" id="about">
        <div class="shell about-grid">
          <div>
            <p class="section-kicker"><?php ceeducon_text('about_kicker', 'About the conference'); ?></p>
            <h2><?php ceeducon_text('about_title', 'A platform for strategy, practice and cooperation across Central Europe.'); ?></h2>
          </div>
          <div class="about-copy">
            <p><?php ceeducon_text('about_text', 'CEEDUCON brings together university leaders, international office professionals, policymakers, national agencies and experts to exchange knowledge, best practices and insights on internationalisation strategy, digitalisation, inclusion, partnerships, mobility, alumni engagement and employability.'); ?></p>
            <div class="about-points">
              <span><?php ceeducon_text('about_point_1', 'around 900 past participants'); ?></span>
              <span><?php ceeducon_text('about_point_2', '130+ past speakers'); ?></span>
              <span><?php ceeducon_text('about_point_3', '50+ sessions and workshops'); ?></span>
              <span><?php ceeducon_text('about_point_4', 'DZS + Central European partners'); ?></span>
            </div>
          </div>
        </div>
      </section>

      <section class="themes-section" id="themes">
        <div class="shell">
          <div class="section-heading section-heading--compact">
            <div>
              <p class="section-kicker"><?php ceeducon_text('themes_kicker', 'Thematic tracks'); ?></p>
              <h2><?php ceeducon_text('themes_title', 'Main thematic areas for CEEDUCON 2026.'); ?></h2>
            </div>
            <p><?php ceeducon_text('themes_intro', 'The 2026 structure connects internationalisation with digitalisation, inclusion, partnerships and the skills graduates need for a changing labour market.'); ?></p>
          </div>
          <div class="theme-story-grid" aria-label="Conference thematic tracks">
            <article class="theme-story theme-story--blue">
              <span>01</span>
              <h3><?php ceeducon_text('theme_1_title', 'Smart & Sustainable International Cooperation'); ?></h3>
              <p><?php ceeducon_text('theme_1_text', 'AI, digitalisation, smart mobility and sustainable funding models for global academic exchange.'); ?></p>
              <button type="button" data-theme-jump="smart"><?php ceeducon_text('theme_button', 'Show sessions'); ?></button>
            </article>
            <article class="theme-story theme-story--red">
              <span>02</span>
              <h3><?php ceeducon_text('theme_2_title', 'Internationalisation for All'); ?></h3>
              <p><?php ceeducon_text('theme_2_text', 'Breaking down barriers, supporting wellbeing and improving meaningful international experiences for students and staff.'); ?></p>
              <button type="button" data-theme-jump="internationalisation"><?php ceeducon_text('theme_button', 'Show sessions'); ?></button>
            </article>
            <article class="theme-story theme-story--yellow">
              <span>03</span>
              <h3><?php ceeducon_text('theme_3_title', 'Global & Regional Partnerships'); ?></h3>
              <p><?php ceeducon_text('theme_3_text', 'Academic ties with emerging regions, talent mobility and cooperation strategies in shifting geopolitical contexts.'); ?></p>
              <button type="button" data-theme-jump="partnerships"><?php ceeducon_text('theme_button', 'Show sessions'); ?></button>
            </article>
            <article class="theme-story theme-story--green">
              <span>04</span>
              <h3><?php ceeducon_text('theme_4_title', 'Alumni — Employability — Future Skills'); ?></h3>
              <p><?php ceeducon_text('theme_4_text', 'Graduate skills, lifelong learning, entrepreneurship and alumni networks for career development.'); ?></p>
              <button type="button" data-theme-jump="alumni"><?php ceeducon_text('theme_button', 'Show sessions'); ?></button>
            </article>
          </div>
        </div>
      </section>

      <section class="programme-tools" aria-label="Programme tools">
        <div class="shell tools-grid">
          <article>
            <span>01</span>
            <h3><?php ceeducon_text('tool_1_title', 'When, where, what'); ?></h3>
            <p><?php ceeducon_text('tool_1_text', 'The programme structure is designed around the key participant questions: time, venue, room and theme.'); ?></p>
          </article>
          <article>
            <span>02</span>
            <h3><?php ceeducon_text('tool_2_title', 'Relevant sessions'); ?></h3>
            <p><?php ceeducon_text('tool_2_text', 'The detailed 2026 programme can later use filters by room, topic and personal selection without changing the layout.'); ?></p>
          </article>
          <article>
            <span>03</span>
            <h3><?php ceeducon_text('tool_3_title', 'Ready for the day'); ?></h3>
            <p><?php ceeducon_text('tool_3_text', 'Session detail, calendar export and print/PDF output are prepared for the final programme data.'); ?></p>
          </article>
        </div>
      </section>

      <section class="programme-overview" id="programme-2026">
        <div class="shell">
          <div class="section-heading">
            <div>
              <p class="section-kicker"><?php ceeducon_text('programme_status_kicker', 'Programme status'); ?></p>
              <h2><?php ceeducon_text('programme_status_title', 'CEEDUCON 2026 is planned as a two-day conference.'); ?></h2>
            </div>
            <p><?php ceeducon_text('programme_status_text', 'The official programme will be available by September 1 and remains subject to change. This page is structured so the detailed programme can be added later without redesigning the experience.'); ?></p>
          </div>
          <div class="programme-days" aria-label="CEEDUCON 2026 programme overview">
            <article>
              <span><?php ceeducon_text('programme_day_1_label', 'Day 1'); ?></span>
              <h3><?php ceeducon_text('programme_day_1_title', 'Tuesday, 1 December 2026'); ?></h3>
              <p><?php ceeducon_text('programme_day_1_text', 'All-day conference at O2 universum. The exact room-by-room programme will be published by September 1.'); ?></p>
            </article>
            <article>
              <span><?php ceeducon_text('programme_evening_label', 'Evening'); ?></span>
              <h3><?php ceeducon_text('programme_evening_title', 'Networking dinner'); ?></h3>
              <p><?php ceeducon_text('programme_evening_text', 'The 2026 outline includes a networking dinner. Detailed timing and venue information can be added once confirmed.'); ?></p>
            </article>
            <article>
              <span><?php ceeducon_text('programme_day_2_label', 'Day 2'); ?></span>
              <h3><?php ceeducon_text('programme_day_2_title', 'Wednesday, 2 December 2026'); ?></h3>
              <p><?php ceeducon_text('programme_day_2_text', 'Second all-day conference block with the same editable programme structure prepared for sessions, rooms and themes.'); ?></p>
            </article>
          </div>
        </div>
      </section>

      <section class="schedule-section" id="schedule">
        <div class="shell">
          <div class="section-heading">
            <div>
              <p class="section-kicker"><?php ceeducon_text('programme_kicker', 'Interactive archive module'); ?></p>
              <h2><?php ceeducon_text('programme_title', 'Archive programme grid from CEEDUCON 2025.'); ?></h2>
            </div>
            <p><?php ceeducon_text('programme_intro', 'This module demonstrates the final publishing approach for a complex programme. Replace the JSON data with 2026 sessions once the official programme is confirmed.'); ?></p>
          </div>

          <div class="day-bar" aria-label="Day selection">
            <button class="day-tab is-active" type="button" data-day="2025-11-19" aria-pressed="true">
              <span>Archive day</span><strong>19 November 2025</strong>
            </button>
            <div class="day-context"><span class="day-context-dot"></span><span>Archived detailed programme sample</span></div>
          </div>

          <div class="control-panel" aria-label="Programme tools">
            <label class="search-field" for="program-search">
              <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7" /><path d="m16 16 5 5" /></svg>
              <span class="sr-only">Search the programme</span>
              <input id="program-search" type="search" placeholder="Search session or topic…" autocomplete="off" />
              <kbd>⌘ K</kbd>
            </label>
            <div class="control-actions">
              <button class="control-button live-button" type="button" data-live-toggle aria-pressed="false">
                <span class="live-dot"></span><span data-live-label>Live preview</span>
              </button>
              <button class="control-button favorites-button" type="button" data-favorites-toggle aria-pressed="false">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.6l6.2-.9z" /></svg>
                <span>My programme</span><b data-favorite-count>0</b>
              </button>
              <button class="control-button mobile-filter-button" type="button" data-filter-toggle aria-expanded="false">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M7 12h10M10 18h4" /></svg>
                <span>Filters</span>
              </button>
            </div>

            <div class="filter-drawer" data-filter-drawer>
              <div class="filter-group">
                <div class="filter-label"><span>Themes</span></div>
                <div class="filter-chips" data-theme-filters></div>
              </div>
              <div class="filter-group">
                <div class="filter-label"><span>Rooms</span></div>
                <div class="filter-chips filter-chips--rooms" data-room-filters></div>
              </div>
              <div class="filter-footer">
                <p data-result-count aria-live="polite">Loading programme…</p>
                <button type="button" data-reset-filters>Clear filters <span>×</span></button>
              </div>
            </div>
          </div>

          <div class="live-banner" data-live-banner hidden>
            <span class="live-dot"></span>
            <p><strong>Demo live preview · 14:26</strong><span>The archive date is not today, so this preview uses a sample time to demonstrate the live state.</span></p>
            <button type="button" data-jump-live>Jump to current block ↓</button>
          </div>

          <div class="schedule" data-schedule aria-live="polite"></div>

          <div class="empty-state" data-empty hidden>
            <span>0</span><h3>No session matches your selection</h3><p>Try another theme, room or search term.</p><button type="button" data-empty-reset>Show full programme</button>
          </div>
        </div>
      </section>

      <section class="info-section" id="practical">
        <div class="shell">
          <div class="section-heading">
            <div>
              <p class="section-kicker"><?php ceeducon_text('practical_kicker', 'Practical information'); ?></p>
              <h2><?php ceeducon_text('practical_title', 'Venue, access and travel basics.'); ?></h2>
            </div>
            <p><?php ceeducon_text('practical_intro', 'Key information from the CEEDUCON practical pages is grouped into short cards so participants can understand logistics without leaving the programme view.'); ?></p>
          </div>
          <div class="info-grid">
            <article>
              <span><?php ceeducon_text('practical_1_label', 'Venue'); ?></span>
              <h3><?php ceeducon_text('practical_1_title', 'O2 universum'); ?></h3>
              <p><?php ceeducon_text('practical_1_text', 'Ceskomoravska 17, Prague 9. The venue hosts the all-day conference blocks for CEEDUCON 2026.'); ?></p>
            </article>
            <article>
              <span><?php ceeducon_text('practical_2_label', 'Transport'); ?></span>
              <h3><?php ceeducon_text('practical_2_title', 'Airport and rail connections'); ?></h3>
              <p><?php ceeducon_text('practical_2_text', 'From Prague Airport, use trolleybus 59 and metro lines A and B. From the Main Train Station, use metro C and B. Praha-Liben is about 10 minutes on foot or by tram 7/8.'); ?></p>
            </article>
            <article>
              <span><?php ceeducon_text('practical_3_label', 'Access'); ?></span>
              <h3><?php ceeducon_text('practical_3_title', 'English and wheelchair access'); ?></h3>
              <p><?php ceeducon_text('practical_3_text', 'Sessions are held in English and the conference venue is accessible for visitors using a wheelchair.'); ?></p>
            </article>
            <article>
              <span><?php ceeducon_text('practical_4_label', 'Fee & stay'); ?></span>
              <h3><?php ceeducon_text('practical_4_title', 'No conference fee'); ?></h3>
              <p><?php ceeducon_text('practical_4_text', 'There is no conference fee for registered attendees. Accommodation is arranged individually; nearby options include Stages Hotel and Carol Hotel.'); ?></p>
            </article>
          </div>
        </div>
      </section>

      <section class="speakers-section" id="speakers">
        <div class="shell speakers-grid">
          <div>
            <p class="section-kicker"><?php ceeducon_text('speakers_kicker', 'For speakers'); ?></p>
            <h2><?php ceeducon_text('speakers_title', 'Speaker information ready for proposals, registration and logistics.'); ?></h2>
          </div>
          <div class="speaker-list">
            <article>
              <span>01</span>
              <h3><?php ceeducon_text('speaker_1_title', 'Registration and participation'); ?></h3>
              <p><?php ceeducon_text('speaker_1_text', 'Speakers are expected to register for the conference. Selected speakers do not pay a participation fee.'); ?></p>
            </article>
            <article>
              <span>02</span>
              <h3><?php ceeducon_text('speaker_2_title', 'Session delivery'); ?></h3>
              <p><?php ceeducon_text('speaker_2_text', 'The conference is primarily onsite. Selected rooms may be recorded, and speakers can indicate recording preferences in the registration process.'); ?></p>
            </article>
            <article>
              <span>03</span>
              <h3><?php ceeducon_text('speaker_3_title', 'Important milestones'); ?></h3>
              <p><?php ceeducon_text('speaker_3_text', 'The original speaker guidance includes notification, registration, contract arrangements and publication of the programme by September 1.'); ?></p>
            </article>
          </div>
        </div>
      </section>

      <section class="venue-section" id="venue">
        <div class="shell venue-card">
          <div>
            <p class="section-kicker"><?php ceeducon_text('venue_kicker', 'Venue'); ?></p>
            <h2><?php ceeducon_text('venue_title', 'O2 universum Prague'); ?></h2>
            <p><?php ceeducon_text('venue_text', 'The venue block is kept as a compact anchor for map links and production information. Detailed 2026 rooms can be added once the final programme is available.'); ?></p>
          </div>
          <a href="<?php echo esc_url(ceeducon_text_value('venue_url', 'https://www.o2universum.cz/en')); ?>" target="_blank" rel="noreferrer"><?php ceeducon_text('venue_button', 'Open venue website'); ?> ↗</a>
        </div>
      </section>

      <section class="contact-section" id="contact">
        <div class="shell contact-grid">
          <div>
            <p class="section-kicker"><?php ceeducon_text('contact_kicker', 'Contact and organisers'); ?></p>
            <h2><?php ceeducon_text('contact_title', 'Need more information?'); ?></h2>
            <p><?php ceeducon_html('contact_text', 'For more information please contact the CEEDUCON team at <a href="mailto:ceeducon@dzs.cz">ceeducon@dzs.cz</a> or +420 221 850 100.'); ?></p>
          </div>
          <div class="partners-card">
            <span><?php ceeducon_text('organiser_label', 'Organised by'); ?></span>
            <strong><?php ceeducon_text('organiser_name', 'Czech National Agency for International Education and Research (DZS)'); ?></strong>
            <span><?php ceeducon_text('partners_label', 'In co-operation with'); ?></span>
            <p><?php ceeducon_text('partners_text', 'OeAD, DAAD, FRSE, SAAIC and Tempus Public Foundation.'); ?></p>
          </div>
        </div>
      </section>
    </main>

<?php
get_footer();
