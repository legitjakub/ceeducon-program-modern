    <footer class="site-footer">
      <div class="shell footer-inner">
        <img src="<?php echo esc_url(ceeducon_asset_url('assets/ceeducon-logo-horizontal-white.png')); ?>" alt="CEEDUCON" />
        <p><?php ceeducon_text('footer_title', 'CEEDUCON 2025'); ?><br /><span><?php ceeducon_text('footer_subtitle', 'Central European Conference on Internationalisation of Higher Education'); ?></span></p>
        <a href="#top"><?php ceeducon_text('footer_back_top', 'Back to top'); ?> ↑</a>
      </div>
    </footer>

    <div class="modal-backdrop" data-modal-backdrop hidden>
      <section class="session-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <button class="modal-close" type="button" data-modal-close aria-label="Close detail">×</button>
        <div class="modal-track" data-modal-track></div>
        <p class="modal-theme" data-modal-theme></p>
        <h2 id="modal-title" data-modal-title></h2>
        <div class="modal-meta">
          <div><span>Time</span><strong data-modal-time></strong></div>
          <div><span>Room</span><strong data-modal-room></strong></div>
        </div>
        <p class="modal-note" data-modal-note></p>
        <div class="modal-actions">
          <button type="button" data-modal-favorite><span>☆</span> Add to my programme</button>
          <button class="modal-calendar" type="button" data-download-ics>Download to calendar <span>↓</span></button>
        </div>
      </section>
    </div>

    <div class="toast" data-toast role="status" aria-live="polite"><span>✓</span><p></p></div>
    <?php wp_footer(); ?>
  </body>
</html>

