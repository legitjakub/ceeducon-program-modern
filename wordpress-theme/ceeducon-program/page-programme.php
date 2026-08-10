<?php
/**
 * Template Name: CEEDUCON Programme
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
            <p class="page-crumbs"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'ceeducon-program'); ?></a><span>/</span><em><?php esc_html_e('Programme', 'ceeducon-program'); ?></em></p>
            <h1><?php ceeducon_text('prog_hero_title', 'Two days. One programme.'); ?></h1>
            <p class="page-hero-note"><?php ceeducon_text('prog_hero_note', 'CEEDUCON 2026 runs across the halls of O2 universum Prague on 1–2 December. Browse the preliminary room-by-room programme below — sessions, workshops and speakers for both days.'); ?></p>
          </div>
          <div class="page-hero-card">
            <span><?php ceeducon_text('prog_card_label', 'Preliminary programme'); ?></span>
            <strong><?php ceeducon_text('prog_card_title', 'Online now'); ?></strong>
            <p><?php ceeducon_text('prog_card_text', 'Registration opens in September and participation is free of charge. The programme remains subject to change.'); ?></p>
          </div>
        </div>
      </section>

      <?php ceeducon_render_editor_content(); ?>

      <section class="schedule-section" id="schedule">
        <div class="shell">
          <div class="section-head">
            <div data-reveal>
              <p class="kicker"><?php ceeducon_text('sched_kicker', 'Interactive programme'); ?></p>
              <h2 class="display-2"><?php ceeducon_text('sched_title', 'Find the right session faster.'); ?></h2>
            </div>
            <p data-reveal="2"><?php ceeducon_text('sched_intro', 'Search the programme, compare rooms and times, filter by theme and keep your personal selection in one place.'); ?></p>
          </div>

          <div class="day-bar" data-day-bar aria-label="Day selection"></div>

          <div class="control-panel" aria-label="Programme tools">
            <label class="search-field" for="program-search">
              <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7" /><path d="m16 16 5 5" /></svg>
              <span class="sr-only"><?php esc_html_e('Search the programme', 'ceeducon-program'); ?></span>
              <input id="program-search" type="search" placeholder="<?php esc_attr_e('Search session or topic…', 'ceeducon-program'); ?>" autocomplete="off" />
            </label>
            <div class="control-actions">
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
              <div class="filter-footer">
                <p data-result-count aria-live="polite"><?php esc_html_e('Loading programme…', 'ceeducon-program'); ?></p>
                <button type="button" data-reset-filters><?php esc_html_e('Clear filters', 'ceeducon-program'); ?> <span>×</span></button>
              </div>
            </div>
          </div>

          <div class="schedule" data-schedule aria-live="polite"></div>

          <div class="empty-state" data-empty hidden>
            <span>0</span><h3><?php esc_html_e('No session matches your selection', 'ceeducon-program'); ?></h3><p><?php esc_html_e('Try another theme, time or search term.', 'ceeducon-program'); ?></p><button type="button" data-empty-reset><?php esc_html_e('Show full programme', 'ceeducon-program'); ?></button>
          </div>

          <?php ceeducon_render_programme_seo_fallback(); ?>
        </div>
      </section>
    </main>

<?php
get_footer();
