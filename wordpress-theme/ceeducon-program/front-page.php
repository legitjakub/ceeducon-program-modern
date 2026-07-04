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
            <h1><?php ceeducon_text('hero_title', 'CEEDUCON 2025 programme for internationalisation in higher education.'); ?></h1>
            <p class="hero-lead"><?php ceeducon_text('hero_lead', "Central Europe's conference on higher education internationalisation, brought into a clear interactive schedule. Follow the day by when, where and what: time, room, thematic area and session detail."); ?></p>
            <div class="hero-meta" aria-label="Event highlights">
              <span><?php ceeducon_html('hero_meta_1', '<strong>19 Nov</strong> 2025'); ?></span>
              <span><?php ceeducon_html('hero_meta_2', '<strong>8 rooms</strong> C1-E2'); ?></span>
              <span><?php ceeducon_html('hero_meta_3', '<strong>4 areas</strong> AI, mobility, partnerships, skills'); ?></span>
            </div>
            <div class="hero-actions" aria-label="Quick actions">
              <a class="hero-action hero-action--primary" href="#schedule"><?php ceeducon_text('hero_primary_cta', 'Open programme'); ?></a>
              <a class="hero-action" href="#themes"><?php ceeducon_text('hero_secondary_cta', 'Explore themes'); ?></a>
            </div>
          </div>
          <aside class="event-card" aria-label="Conference day preview">
            <div class="event-date">
              <strong><?php ceeducon_text('event_day', '19'); ?></strong>
              <span><?php ceeducon_html('event_month_year', 'NOV<br />2025'); ?></span>
            </div>
            <p><?php ceeducon_text('event_label', 'Conference day'); ?></p>
            <div class="event-card-row"><span>Start</span><strong><?php ceeducon_text('event_start', '08:30 Registration'); ?></strong></div>
            <div class="event-card-row"><span>Main block</span><strong><?php ceeducon_text('event_block', '11:00-16:50 Sessions'); ?></strong></div>
            <div class="event-card-row"><span>Venue</span><strong><?php ceeducon_text('event_venue', 'O2 universum Prague'); ?></strong></div>
            <div class="event-route" aria-label="Programme preview">
              <span><?php ceeducon_text('event_route_1', 'When: time blocks'); ?></span>
              <span><?php ceeducon_text('event_route_2', 'Where: room grid'); ?></span>
              <span><?php ceeducon_text('event_route_3', 'What: thematic sessions'); ?></span>
            </div>
            <a href="#schedule"><?php ceeducon_text('event_cta', 'Jump to room grid'); ?> <span>↓</span></a>
          </aside>
        </div>
        <div class="hero-stats shell" aria-label="Programme summary">
          <div><strong data-session-count>—</strong><span>sessions</span></div>
          <div><strong data-room-count>—</strong><span>rooms</span></div>
          <div><strong data-theme-count>—</strong><span>thematic tracks</span></div>
          <div><strong>1</strong><span>day of ideas</span></div>
        </div>
      </section>

      <section class="about-section" id="about">
        <div class="shell about-grid">
          <div>
            <p class="section-kicker"><?php ceeducon_text('about_kicker', 'About the conference'); ?></p>
            <h2><?php ceeducon_text('about_title', 'A clear map of ideas, practice and cooperation.'); ?></h2>
          </div>
          <div class="about-copy">
            <p><?php ceeducon_text('about_text', 'CEEDUCON brings together people working with internationalisation of higher education. The programme reflects the main questions of the field: responsible digital change, inclusive international experiences, sustainable partnerships and future skills.'); ?></p>
            <div class="about-points">
              <span><?php ceeducon_text('about_point_1', 'AI and digitalisation'); ?></span>
              <span><?php ceeducon_text('about_point_2', 'inclusive mobility'); ?></span>
              <span><?php ceeducon_text('about_point_3', 'global partnerships'); ?></span>
              <span><?php ceeducon_text('about_point_4', 'alumni and careers'); ?></span>
            </div>
          </div>
        </div>
      </section>

      <section class="themes-section" id="themes">
        <div class="shell">
          <div class="section-heading section-heading--compact">
            <div>
              <p class="section-kicker"><?php ceeducon_text('themes_kicker', 'Thematic tracks'); ?></p>
              <h2><?php ceeducon_text('themes_title', 'Main thematic areas of the conference.'); ?></h2>
            </div>
            <p><?php ceeducon_text('themes_intro', 'The structure follows the CEEDUCON themes and helps participants quickly understand which sessions connect to their work.'); ?></p>
          </div>
          <div class="theme-story-grid" aria-label="Conference thematic tracks">
            <article class="theme-story theme-story--blue">
              <span>01</span>
              <h3><?php ceeducon_text('theme_1_title', 'Navigating the Technological Shift'); ?></h3>
              <p><?php ceeducon_text('theme_1_text', 'Responsible use of AI, digitalisation and data analytics for smarter internationalisation strategies.'); ?></p>
              <button type="button" data-theme-jump="smart"><?php ceeducon_text('theme_button', 'Show sessions'); ?></button>
            </article>
            <article class="theme-story theme-story--red">
              <span>02</span>
              <h3><?php ceeducon_text('theme_2_title', 'Challenges of Internationalisation'); ?></h3>
              <p><?php ceeducon_text('theme_2_text', 'Structural, social and financial barriers, wellbeing, access and safe international experiences for all.'); ?></p>
              <button type="button" data-theme-jump="internationalisation"><?php ceeducon_text('theme_button', 'Show sessions'); ?></button>
            </article>
            <article class="theme-story theme-story--yellow">
              <span>03</span>
              <h3><?php ceeducon_text('theme_3_title', 'Global & Regional Partnerships'); ?></h3>
              <p><?php ceeducon_text('theme_3_text', 'Sustainable partnerships, European University alliances and cooperation across global regions.'); ?></p>
              <button type="button" data-theme-jump="partnerships"><?php ceeducon_text('theme_button', 'Show sessions'); ?></button>
            </article>
            <article class="theme-story theme-story--green">
              <span>04</span>
              <h3><?php ceeducon_text('theme_4_title', 'From Recruitment to Retention'); ?></h3>
              <p><?php ceeducon_text('theme_4_text', 'A student-centred journey from recruitment and admissions to support, alumni and employability.'); ?></p>
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
            <p><?php ceeducon_text('tool_1_text', 'The schedule translates the conference programme into time blocks, rooms and thematic context.'); ?></p>
          </article>
          <article>
            <span>02</span>
            <h3><?php ceeducon_text('tool_2_title', 'Relevant sessions'); ?></h3>
            <p><?php ceeducon_text('tool_2_text', 'Filter by room or theme and save sessions that match your internationalisation priorities.'); ?></p>
          </article>
          <article>
            <span>03</span>
            <h3><?php ceeducon_text('tool_3_title', 'Ready for the day'); ?></h3>
            <p><?php ceeducon_text('tool_3_text', 'Open session detail, export selected sessions to calendar or print the programme as a PDF.'); ?></p>
          </article>
        </div>
      </section>

      <section class="schedule-section" id="schedule">
        <div class="shell">
          <div class="section-heading">
            <div>
              <p class="section-kicker"><?php ceeducon_text('programme_kicker', 'Programme'); ?></p>
              <h2><?php ceeducon_text('programme_title', 'Time grid by room.'); ?></h2>
            </div>
            <p><?php ceeducon_text('programme_intro', 'A practical view of the CEEDUCON programme: desktop keeps the room grid, mobile turns it into a readable timeline with filters and personal selection.'); ?></p>
          </div>

          <div class="day-bar" aria-label="Day selection">
            <button class="day-tab is-active" type="button" data-day="2025-11-19" aria-pressed="true">
              <span>Wednesday</span><strong>19 November</strong>
            </button>
            <div class="day-context"><span class="day-context-dot"></span><span>Main conference day</span></div>
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
                <span class="live-dot"></span><span data-live-label>Live mode</span>
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
            <p><strong>Happening now · 14:26</strong><span>The current programme block is highlighted.</span></p>
            <button type="button" data-jump-live>Jump to current block ↓</button>
          </div>

          <div class="schedule" data-schedule aria-live="polite"></div>

          <div class="empty-state" data-empty hidden>
            <span>0</span><h3>No session matches your selection</h3><p>Try another theme, room or search term.</p><button type="button" data-empty-reset>Show full programme</button>
          </div>
        </div>
      </section>

      <section class="venue-section" id="venue">
        <div class="shell venue-card">
          <div>
            <p class="section-kicker"><?php ceeducon_text('venue_kicker', 'Venue'); ?></p>
            <h2><?php ceeducon_text('venue_title', 'O2 universum Prague'); ?></h2>
            <p><?php ceeducon_text('venue_text', 'The programme is designed to make room changes clear on desktop and mobile across halls C1, C2, C3, D2, D3+D4, D6+D7, E1 and E2.'); ?></p>
          </div>
          <a href="<?php echo esc_url(ceeducon_text_value('venue_url', 'https://www.o2universum.cz/en')); ?>" target="_blank" rel="noreferrer"><?php ceeducon_text('venue_button', 'Open venue website'); ?> ↗</a>
        </div>
      </section>
    </main>

<?php
get_footer();

