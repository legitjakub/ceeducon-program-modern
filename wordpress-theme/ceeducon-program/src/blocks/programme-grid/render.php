<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<section class="schedule-section" id="schedule">
  <div class="shell">
    <div class="section-head">
      <div data-reveal>
        <p class="kicker"><?php echo ceeducon_block_text($attributes, 'kicker'); ?></p>
        <h2 class="display-2"><?php echo ceeducon_block_html($attributes, 'title'); ?></h2>
      </div>
      <p data-reveal="2"><?php echo ceeducon_block_html($attributes, 'intro'); ?></p>
    </div>

    <div class="day-bar" data-day-bar aria-label="<?php esc_attr_e('Day selection', 'ceeducon-program'); ?>"></div>

    <div class="control-panel" aria-label="<?php esc_attr_e('Programme tools', 'ceeducon-program'); ?>">
      <label class="search-field" for="program-search">
        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7" /><path d="m16 16 5 5" /></svg>
        <span class="sr-only"><?php esc_html_e('Search the programme', 'ceeducon-program'); ?></span>
        <input id="program-search" type="search" placeholder="<?php esc_attr_e('Search session or topic…', 'ceeducon-program'); ?>" autocomplete="off" />
        <kbd>⌘ K</kbd>
      </label>
      <div class="control-actions">
        <button class="control-button view-toggle-button" type="button" data-view-toggle aria-pressed="false">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" /></svg>
          <span data-view-label><?php esc_html_e('List view', 'ceeducon-program'); ?></span>
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

    <div class="schedule" data-schedule aria-live="polite"></div>

    <div class="empty-state" data-empty hidden>
      <span>0</span><h3><?php esc_html_e('No session matches your selection', 'ceeducon-program'); ?></h3><p><?php esc_html_e('Try another theme, room or search term.', 'ceeducon-program'); ?></p><button type="button" data-empty-reset><?php esc_html_e('Show full programme', 'ceeducon-program'); ?></button>
    </div>

    <?php ceeducon_render_programme_seo_fallback(); ?>
  </div>
</section>
