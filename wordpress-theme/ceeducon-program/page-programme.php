<?php
/**
 * Template Name: CEEDUCON Programme
 */

get_header();
?>

    <main id="main">
      <section class="page-hero page-hero--orange">
        <div class="shell page-hero-grid">
          <div>
            <p class="page-crumbs"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'ceeducon-program'); ?></a><span>/</span><em><?php esc_html_e('Programme', 'ceeducon-program'); ?></em></p>
            <h1><?php ceeducon_text('prog_hero_title', 'Two days. Four themes. One programme.'); ?></h1>
            <p class="page-hero-note"><?php ceeducon_text('prog_hero_note', 'CEEDUCON 2026 runs across the halls of O2 universum Prague on 1–2 December. The detailed room-by-room programme will be published by September 1.'); ?></p>
          </div>
          <div class="page-hero-card">
            <span><?php ceeducon_text('prog_card_label', 'Detailed programme'); ?></span>
            <strong><?php ceeducon_text('prog_card_title', 'Published by September 1'); ?></strong>
            <p><?php ceeducon_text('prog_card_text', 'Registration opens in September and participation is free of charge. The programme remains subject to change.'); ?></p>
          </div>
        </div>
      </section>

      <section class="section" id="programme-2026">
        <div class="shell">
          <div class="section-head">
            <div data-reveal>
              <p class="kicker"><?php ceeducon_text('prog_overview_kicker', 'Overview'); ?></p>
              <h2 class="display-2"><?php ceeducon_text('prog_overview_title', 'How the two days are planned.'); ?></h2>
            </div>
            <p data-reveal="2"><?php ceeducon_text('prog_overview_intro', 'Both days run as full conference days with plenaries, thematic sessions and workshops — connected by a networking dinner on the first evening.'); ?></p>
          </div>
          <div class="day-cards" aria-label="CEEDUCON 2026 programme overview">
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
            <article class="notice-card notice-card--orange" data-reveal>
              <span><?php ceeducon_text('notice_reg_label', 'Registration'); ?></span>
              <h3><?php ceeducon_text('notice_reg_title', 'Opens in September.'); ?></h3>
              <p><?php ceeducon_text('notice_reg_text', 'Participation is free of charge. Contact the CEEDUCON team to be notified as soon as registration opens.'); ?></p>
              <a class="btn btn--primary" href="<?php echo esc_url(ceeducon_text_value('notice_reg_url', 'mailto:ceeducon@dzs.cz?subject=CEEDUCON%202026%20registration%20updates')); ?>"><?php ceeducon_text('notice_reg_button', 'Get notified'); ?></a>
            </article>
            <article class="notice-card notice-card--sky" data-reveal="2">
              <span><?php ceeducon_text('prog_grid_label', 'Interactive grid'); ?></span>
              <h3><?php ceeducon_text('prog_grid_title', 'See how the programme will work.'); ?></h3>
              <p><?php ceeducon_text('prog_grid_text', 'The interactive grid below shows the format of the detailed programme — filters, room view, personal selection and calendar export.'); ?></p>
              <a class="btn btn--dark" href="#schedule"><?php ceeducon_text('prog_grid_button', 'Open the grid'); ?></a>
            </article>
          </div>
        </div>
      </section>

      <section class="schedule-section" id="schedule">
        <div class="shell">
          <div class="section-head">
            <div data-reveal>
              <p class="kicker"><?php ceeducon_text('sched_kicker', 'Interactive programme'); ?></p>
              <h2 class="display-2"><?php ceeducon_text('sched_title', 'Explore the conference, room by room.'); ?></h2>
            </div>
            <p data-reveal="2"><?php ceeducon_text('sched_intro', 'Filter by theme, time and room, search sessions, build your personal selection and export it to your calendar.'); ?></p>
          </div>

          <div class="archive-notice" role="note">
            <span><?php ceeducon_text('archive_label', 'Archive sample'); ?></span>
            <p><?php ceeducon_text('archive_text', 'This is not the final CEEDUCON 2026 programme. The grid currently shows archived sessions from CEEDUCON 2025 to demonstrate how the detailed programme will work. The official 2026 programme will replace it by September 1.'); ?></p>
          </div>

          <div class="day-bar" aria-label="Day selection">
            <button class="day-tab is-active" type="button" data-day="2025-11-19" aria-pressed="true">
              <span><?php ceeducon_text('archive_day_label', 'Archive day'); ?></span><strong><?php ceeducon_text('archive_day_date', '19 November 2025'); ?></strong>
            </button>
            <div class="day-context"><span class="day-context-dot"></span><span><?php ceeducon_text('archive_day_context', 'Archived programme from CEEDUCON 2025'); ?></span></div>
          </div>

          <div class="control-panel" aria-label="Programme tools">
            <label class="search-field" for="program-search">
              <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7" /><path d="m16 16 5 5" /></svg>
              <span class="sr-only"><?php esc_html_e('Search the programme', 'ceeducon-program'); ?></span>
              <input id="program-search" type="search" placeholder="<?php esc_attr_e('Search session or topic…', 'ceeducon-program'); ?>" autocomplete="off" />
              <kbd>⌘ K</kbd>
            </label>
            <div class="control-actions">
              <button class="control-button live-button" type="button" data-live-toggle aria-pressed="false">
                <span class="live-dot"></span><span data-live-label><?php esc_html_e('Live preview', 'ceeducon-program'); ?></span>
              </button>
              <button class="control-button favorites-button" type="button" data-favorites-toggle aria-pressed="false">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.6l6.2-.9z" /></svg>
                <span><?php esc_html_e('My programme', 'ceeducon-program'); ?></span><b data-favorite-count>0</b>
              </button>
              <button class="control-button mobile-filter-button" type="button" data-filter-toggle aria-expanded="false">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M7 12h10M10 18h4" /></svg>
                <span><?php esc_html_e('Filters', 'ceeducon-program'); ?></span>
              </button>
            </div>

            <div class="filter-drawer" data-filter-drawer>
              <div class="filter-group">
                <div class="filter-label"><span><?php esc_html_e('Themes', 'ceeducon-program'); ?></span></div>
                <div class="filter-chips" data-theme-filters></div>
              </div>
              <div class="filter-group">
                <div class="filter-label"><span><?php esc_html_e('Time', 'ceeducon-program'); ?></span></div>
                <div class="filter-chips filter-chips--periods" data-period-filters></div>
              </div>
              <div class="filter-group">
                <div class="filter-label"><span><?php esc_html_e('Rooms', 'ceeducon-program'); ?></span></div>
                <div class="filter-chips filter-chips--rooms" data-room-filters></div>
              </div>
              <div class="filter-footer">
                <p data-result-count aria-live="polite"><?php esc_html_e('Loading programme…', 'ceeducon-program'); ?></p>
                <button type="button" data-reset-filters><?php esc_html_e('Clear filters', 'ceeducon-program'); ?> <span>×</span></button>
              </div>
            </div>
          </div>

          <div class="live-banner" data-live-banner hidden>
            <span class="live-dot"></span>
            <p><strong><?php esc_html_e('Demo live preview · 14:26', 'ceeducon-program'); ?></strong><span><?php esc_html_e('The archive date is not today, so this preview uses a sample time to demonstrate the live state.', 'ceeducon-program'); ?></span></p>
            <button type="button" data-jump-live><?php esc_html_e('Jump to current block', 'ceeducon-program'); ?> ↓</button>
          </div>

          <div class="schedule" data-schedule aria-live="polite"></div>

          <div class="empty-state" data-empty hidden>
            <span>0</span><h3><?php esc_html_e('No session matches your selection', 'ceeducon-program'); ?></h3><p><?php esc_html_e('Try another theme, room or search term.', 'ceeducon-program'); ?></p><button type="button" data-empty-reset><?php esc_html_e('Show full programme', 'ceeducon-program'); ?></button>
          </div>
        </div>
      </section>
    </main>

<?php
get_footer();
