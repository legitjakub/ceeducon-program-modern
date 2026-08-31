<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
    <footer class="site-footer">
      <div class="shell">
        <div class="footer-grid">
          <div>
            <img src="<?php echo esc_url(ceeducon_asset_url('assets/ceeducon-logo-horizontal-white.png')); ?>" alt="CEEDUCON" width="1182" height="604" loading="lazy" decoding="async" />
            <p><?php ceeducon_html('footer_tagline', 'Central European Conference on Internationalisation of Higher Education.<br />{{date}} · {{venue}}'); ?></p>
          </div>
          <div>
            <h3><?php esc_html_e('Conference', 'ceeducon-program'); ?></h3>
            <?php ceeducon_render_navigation('footer-menu', __('Footer conference links', 'ceeducon-program')); ?>
          </div>
          <div>
            <h3><?php esc_html_e('Connect', 'ceeducon-program'); ?></h3>
            <nav aria-label="Footer contact links">
              <?php $footer_email = ceeducon_text_value('footer_email', 'ceeducon@dzs.cz'); ?>
              <?php $footer_phone = ceeducon_text_value('footer_phone', '+420 221 850 100'); ?>
              <a href="mailto:<?php echo esc_attr($footer_email); ?>"><?php echo esc_html($footer_email); ?></a>
              <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $footer_phone)); ?>"><?php echo esc_html($footer_phone); ?></a>
              <a href="<?php echo esc_url(ceeducon_text_value('footer_official_url', 'https://www.ceeducon.cz/')); ?>" target="_blank" rel="noreferrer">ceeducon.cz</a>
              <a href="<?php echo esc_url(ceeducon_text_value('footer_dzs_url', 'https://www.dzs.cz/')); ?>" target="_blank" rel="noreferrer">dzs.cz</a>
            </nav>
          </div>
        </div>
        <div class="partner-logo-strip">
          <?php
          $partner_logos = [
              ['dzs', 'DZS — Czech National Agency for International Education and Research', 'https://www.dzs.cz/en', 198],
              ['msmt', 'Ministry of Education, Youth and Sports of the Czech Republic', 'https://msmt.gov.cz/?lang=2', 267],
              ['european-union', 'European Union', 'https://european-union.europa.eu/index_en', 367],
              ['oead', "OeAD — Austria's Agency for Education and Internationalisation", 'https://oead.at/en/', 203],
              ['daad', 'DAAD — German Academic Exchange Service', 'https://www.daad.de/en/', 412],
              ['frse', 'FRSE — Foundation for the Development of the Education System', 'https://www.frse.org.pl/', 198],
              ['saaic', 'SAAIC — Slovak Academic Association for International Cooperation', 'https://www.saaic.sk/', 183],
              ['tempus', 'Tempus Public Foundation', 'https://tka.hu/english', 275],
          ];
          ?>
          <nav class="partner-logo-grid" aria-label="<?php esc_attr_e('CEEDUCON organising and partner organisations', 'ceeducon-program'); ?>">
            <?php foreach ($partner_logos as [$logo_slug, $logo_name, $logo_url, $logo_width]) : ?>
              <a class="partner-logo-link" href="<?php echo esc_url($logo_url); ?>" target="_blank" rel="noreferrer"><img src="<?php echo esc_url(ceeducon_asset_url('assets/media/partners/' . $logo_slug . '.png')); ?>" alt="<?php echo esc_attr($logo_name); ?>" width="<?php echo esc_attr((string) $logo_width); ?>" height="109" loading="lazy" decoding="async" /></a>
            <?php endforeach; ?>
          </nav>
        </div>
        <div class="footer-bottom">
          <p><?php ceeducon_text('footer_copyright', '© {{year}} DZS — Czech National Agency for International Education and Research'); ?></p>
          <a href="#top"><?php esc_html_e('Back to top', 'ceeducon-program'); ?> <span class="ui-icon" aria-hidden="true"><svg viewBox="0 0 16 16"><path d="M8 13V3M4 7l4-4 4 4"></path></svg></span></a>
        </div>
      </div>
      <span class="footer-ghost" aria-hidden="true">CEEDUCON</span>
    </footer>

    <?php if (ceeducon_should_render_programme_ui()) : ?>
    <div class="modal-backdrop" data-modal-backdrop hidden>
      <section class="session-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <button class="modal-close" type="button" data-modal-close aria-label="Close detail">×</button>
        <div class="modal-track" data-modal-track></div>
        <p class="modal-theme" data-modal-theme></p>
        <h2 id="modal-title" data-modal-title></h2>
        <div class="modal-meta">
          <div><span><?php esc_html_e('Time', 'ceeducon-program'); ?></span><strong data-modal-time></strong></div>
          <div><span><?php esc_html_e('Room', 'ceeducon-program'); ?></span><strong data-modal-room></strong></div>
        </div>
        <div class="modal-abstract" data-modal-abstract hidden></div>
        <button class="modal-abstract-toggle" type="button" data-abstract-toggle aria-expanded="false" hidden>
          <span data-abstract-toggle-label><?php esc_html_e('Read the full abstract', 'ceeducon-program'); ?></span>
          <svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <p class="modal-note" data-modal-note></p>
        <div class="modal-actions">
          <button type="button" data-modal-favorite><span>☆</span> <?php esc_html_e('Add to my programme', 'ceeducon-program'); ?></button>
          <button class="modal-calendar" type="button" data-add-calendar><?php esc_html_e('Add to Google Calendar', 'ceeducon-program'); ?> <span class="ui-icon" aria-hidden="true"><svg viewBox="0 0 16 16"><path d="M6 4h6v6M12 4 5 11"></path></svg></span></button>
          <button class="modal-calendar" type="button" data-add-outlook><?php esc_html_e('Add to Outlook Calendar', 'ceeducon-program'); ?> <span class="ui-icon" aria-hidden="true"><svg viewBox="0 0 16 16"><path d="M6 4h6v6M12 4 5 11"></path></svg></span></button>
        </div>
      </section>
    </div>

    <div class="toast" data-toast role="status" aria-live="polite"><span>✓</span><p></p></div>
    <?php endif; ?>

    <?php wp_footer(); ?>
  </body>
</html>
