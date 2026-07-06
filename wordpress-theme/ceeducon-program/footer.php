    <footer class="site-footer">
      <div class="shell">
        <div class="footer-cta">
          <h2><?php ceeducon_html('footer_cta_title', 'See you in <em>Prague</em>.'); ?></h2>
          <a class="btn btn--primary" href="<?php echo esc_url(ceeducon_text_value('footer_cta_url', 'mailto:ceeducon@dzs.cz?subject=CEEDUCON%202026%20updates')); ?>"><?php ceeducon_text('footer_cta_button', 'Get updates'); ?></a>
        </div>
        <div class="footer-grid">
          <div>
            <img src="<?php echo esc_url(ceeducon_asset_url('assets/ceeducon-logo-horizontal-white.png')); ?>" alt="CEEDUCON" />
            <p><?php ceeducon_html('footer_tagline', 'Central European Conference on Internationalisation of Higher Education.<br />1–2 December 2026 · O2 universum Prague'); ?></p>
          </div>
          <div>
            <h4><?php esc_html_e('Conference', 'ceeducon-program'); ?></h4>
            <nav aria-label="Footer conference links">
              <a href="<?php echo esc_url(ceeducon_page_url('about')); ?>"><?php esc_html_e('About', 'ceeducon-program'); ?></a>
              <a href="<?php echo esc_url(ceeducon_page_url('programme')); ?>"><?php esc_html_e('Programme', 'ceeducon-program'); ?></a>
              <a href="<?php echo esc_url(ceeducon_page_url('practical')); ?>"><?php esc_html_e('Practical information', 'ceeducon-program'); ?></a>
              <a href="<?php echo esc_url(ceeducon_page_url('speakers')); ?>"><?php esc_html_e('For speakers', 'ceeducon-program'); ?></a>
              <a href="<?php echo esc_url(ceeducon_page_url('contact')); ?>"><?php esc_html_e('Contact', 'ceeducon-program'); ?></a>
            </nav>
          </div>
          <div>
            <h4><?php esc_html_e('Connect', 'ceeducon-program'); ?></h4>
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
        <div class="footer-bottom">
          <p><?php ceeducon_text('footer_copyright', '© 2026 DZS — Czech National Agency for International Education and Research'); ?></p>
          <a href="#top"><?php esc_html_e('Back to top', 'ceeducon-program'); ?> ↑</a>
        </div>
      </div>
      <span class="footer-ghost" aria-hidden="true">CEEDUCON</span>
    </footer>

    <?php if (ceeducon_is_programme_page()) : ?>
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
        <p class="modal-note" data-modal-note></p>
        <div class="modal-actions">
          <button type="button" data-modal-favorite><span>☆</span> <?php esc_html_e('Add to my programme', 'ceeducon-program'); ?></button>
          <button class="modal-calendar" type="button" data-download-ics><?php esc_html_e('Download to calendar', 'ceeducon-program'); ?> <span>↓</span></button>
        </div>
      </section>
    </div>

    <div class="toast" data-toast role="status" aria-live="polite"><span>✓</span><p></p></div>
    <div class="cookie-banner" data-cookie-banner>
      <p><?php ceeducon_text('cookie_note', '“My programme” selections are stored only in your browser\'s local storage. This site sets no analytics cookies.'); ?></p>
      <button type="button" data-cookie-accept>OK</button>
    </div>
    <?php endif; ?>

    <?php wp_footer(); ?>
  </body>
</html>
