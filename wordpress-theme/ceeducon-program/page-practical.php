<?php
/**
 * Template Name: CEEDUCON Practical
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
            <p class="page-crumbs"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'ceeducon-program'); ?></a><span>/</span><em><?php esc_html_e('Practical information', 'ceeducon-program'); ?></em></p>
            <h1><?php ceeducon_text('prac_hero_title', 'Plan your visit to Prague.'); ?></h1>
            <p class="page-hero-note"><?php ceeducon_text('prac_hero_note', 'Where to go, how to get there and what to prepare — everything participants need before arriving at CEEDUCON 2026.'); ?></p>
          </div>
          <div class="page-hero-card">
            <span><?php ceeducon_text('prac_card_label', 'Venue'); ?></span>
            <strong><?php ceeducon_text('prac_card_title', 'O2 universum'); ?></strong>
            <p><?php ceeducon_text('prac_card_text', 'Českomoravská 17, Prague 9 — home of both CEEDUCON 2026 conference days.'); ?></p>
          </div>
        </div>
      </section>

      <?php ceeducon_render_editor_content(); ?>

      <section class="section">
        <div class="shell">
          <div class="section-head">
            <div data-reveal>
              <p class="kicker"><?php ceeducon_text('prac_kicker', 'Essentials'); ?></p>
              <h2 class="display-2"><?php ceeducon_text('prac_title', 'Venue, access and travel basics.'); ?></h2>
            </div>
            <p data-reveal="2"><?php ceeducon_text('prac_intro', 'The conference language is English, participation is free of charge and the venue is fully accessible.'); ?></p>
          </div>
          <div class="info-grid" aria-label="Practical essentials">
            <article data-reveal>
              <span><?php ceeducon_text('info_1_label', 'Venue'); ?></span>
              <h3><?php ceeducon_text('info_1_title', 'O2 universum'); ?></h3>
              <p><?php ceeducon_text('info_1_text', 'Českomoravská 17, Prague 9. All plenaries, sessions and workshops of CEEDUCON 2026 take place here.'); ?></p>
            </article>
            <article data-reveal="2">
              <span><?php ceeducon_text('info_2_label', 'From the airport'); ?></span>
              <h3><?php ceeducon_text('info_2_title', 'Around 55 minutes'); ?></h3>
              <p><?php ceeducon_text('info_2_text', 'Take trolleybus 59 to Nádraží Veleslavín, then metro line A and line B towards Českomoravská.'); ?></p>
            </article>
            <article data-reveal="3">
              <span><?php ceeducon_text('info_3_label', 'By train'); ?></span>
              <h3><?php ceeducon_text('info_3_title', 'Main station & Libeň'); ?></h3>
              <p><?php ceeducon_text('info_3_text', 'From the Main Train Station use metro lines C and B. From Praha-Libeň it is a 10-minute walk or a short ride on tram 7 or 8.'); ?></p>
            </article>
            <article data-reveal="4">
              <span><?php ceeducon_text('info_4_label', 'Accessibility'); ?></span>
              <h3><?php ceeducon_text('info_4_title', 'Accessible & in English'); ?></h3>
              <p><?php ceeducon_text('info_4_text', 'The conference is held in English and the venue is accessible for visitors using a wheelchair.'); ?></p>
            </article>
          </div>
        </div>
      </section>

      <section class="section section--paper">
        <div class="shell">
          <div class="section-head">
            <div data-reveal>
              <p class="kicker"><?php ceeducon_text('faq_kicker', 'Good to know'); ?></p>
              <h2 class="display-2"><?php ceeducon_text('faq_title', 'Frequently asked questions.'); ?></h2>
            </div>
          </div>
          <div class="faq-list" aria-label="Practical FAQ" data-reveal>
            <details open>
              <summary><?php ceeducon_text('faq_1_title', 'Is there a conference fee?'); ?></summary>
              <p><?php ceeducon_html('faq_1_text', 'No — participation at CEEDUCON 2026 is free of charge for registered attendees. Registration opens in September.'); ?></p>
            </details>
            <details>
              <summary><?php ceeducon_text('faq_2_title', 'Where should I stay?'); ?></summary>
              <p><?php ceeducon_html('faq_2_text', 'Participants arrange accommodation individually. Hotels within easy reach of the venue include Stages Hotel and Carol Hotel; central Prague is around 20 minutes away by metro.'); ?></p>
            </details>
            <details>
              <summary><?php ceeducon_text('faq_3_title', 'How do I register?'); ?></summary>
              <p><?php ceeducon_html('faq_3_text', 'Registration opens in September. The registration form and practical details will be published on the official CEEDUCON website once confirmed.'); ?></p>
            </details>
            <details>
              <summary><?php ceeducon_text('faq_4_title', 'Anything to check before travelling?'); ?></summary>
              <p><?php ceeducon_html('faq_4_text', 'Check current Prague public transport information before you travel, especially around the Českomoravská metro station, and verify travel requirements for the Czech Republic if you need a visa or an event confirmation from the organisers.'); ?></p>
            </details>
          </div>
        </div>
      </section>

      <section class="section">
        <div class="shell venue-band" data-reveal>
          <div>
            <p class="kicker"><?php ceeducon_text('map_kicker', 'Map & venue'); ?></p>
            <h2 class="display-2"><?php ceeducon_text('map_title', 'Plan the route before conference day.'); ?></h2>
            <p><?php ceeducon_text('map_text', 'Check entrances, transport connections and nearby services on the venue website or open the location directly in your maps app.'); ?></p>
            <div class="venue-actions">
              <a class="btn btn--dark" href="<?php echo esc_url(ceeducon_text_value('venue_url', 'https://www.o2universum.cz/en')); ?>" target="_blank" rel="noreferrer"><?php ceeducon_text('venue_button', 'Venue website'); ?></a>
              <a class="btn btn--outline" href="<?php echo esc_url(ceeducon_text_value('venue_map_url', 'https://www.google.com/maps/search/?api=1&query=O2%20universum%20Ceskomoravska%2017%20Prague')); ?>" target="_blank" rel="noreferrer"><?php ceeducon_text('venue_map_button', 'Open map'); ?></a>
            </div>
          </div>
          <div class="venue-map" aria-label="<?php esc_attr_e('Map of O2 universum Prague', 'ceeducon-program'); ?>">
            <iframe
              title="<?php esc_attr_e('O2 universum Prague on Google Maps', 'ceeducon-program'); ?>"
              src="<?php echo esc_url(ceeducon_text_value('venue_embed_url', 'https://www.google.com/maps?q=O2%20universum%20Ceskomoravska%2017%20Prague&output=embed')); ?>"
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
      </section>
    </main>

<?php
get_footer();
