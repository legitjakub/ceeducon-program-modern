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

      <section class="section section--paper" id="venue-plan">
        <div class="shell">
          <div class="section-head">
            <div data-reveal>
              <p class="kicker"><?php ceeducon_text('plan_kicker', 'Venue plan'); ?></p>
              <h2 class="display-2"><?php ceeducon_text('plan_title', 'Find your hall before you arrive.'); ?></h2>
            </div>
          </div>

          <div class="floorplan" data-floorplan data-reveal>
            <div class="floorplan-tabs" aria-label="Choose a level">
              <button class="floorplan-tab is-active" type="button" data-level="0" aria-pressed="true">
                <span>Level 0 · Entrance</span><strong>Halls B1–B3</strong>
              </button>
              <button class="floorplan-tab" type="button" data-level="1" aria-pressed="false">
                <span>Level 1 · Conference</span><strong>Halls C1–C3, D2–D7</strong>
              </button>
            </div>

            <div class="floorplan-stage">
              <figure class="floorplan-figure" data-level-plan="0">
                <img src="<?php echo esc_url(ceeducon_asset_url('assets/media/o2-plan-level-0.webp')); ?>" width="1448" height="868" loading="lazy" decoding="async" alt="Plan of the O2 universum entrance level: main entrance, arena, meeting hub and halls B1 to B3." />
                <svg class="floorplan-hotspots" viewBox="0 0 100 100" preserveAspectRatio="none" aria-label="CEEDUCON halls on the entrance level">
                  <a class="vp-room" href="<?php echo esc_url(ceeducon_page_url('programme') . '?room=' . rawurlencode('B3')); ?>" data-room="B3"><title>Hall B3 — 8 sessions</title><rect class="vp-hit" x="80.52" y="13.20" width="14.02" height="20.38" /><rect x="80.52" y="21.20" width="14.02" height="12.21" vector-effect="non-scaling-stroke" /></a>
                  <a class="vp-room" href="<?php echo esc_url(ceeducon_page_url('programme') . '?room=' . rawurlencode('B2')); ?>" data-room="B2"><title>Hall B2 — 10 sessions</title><rect class="vp-hit" x="80.52" y="33.58" width="16.92" height="19.24" /><rect x="80.52" y="33.75" width="16.92" height="18.89" vector-effect="non-scaling-stroke" /></a>
                  <a class="vp-room" href="<?php echo esc_url(ceeducon_page_url('programme') . '?room=' . rawurlencode('B1')); ?>" data-room="B1"><title>Hall B1 — 10 sessions</title><rect class="vp-hit" x="80.46" y="52.96" width="16.99" height="21.85" /><rect x="80.46" y="52.99" width="16.99" height="20.28" vector-effect="non-scaling-stroke" /></a>
                </svg>
                <a class="floorplan-source" href="<?php echo esc_url(ceeducon_asset_url('assets/media/o2-plan-level-0.png')); ?>" target="_blank" rel="noreferrer">
                  Full plan
                  <span class="ui-icon" aria-hidden="true"><svg viewBox="0 0 16 16"><path d="M6 4h6v6M12 4 5 11"></path></svg></span>
                </a>
              </figure>

              <figure class="floorplan-figure" data-level-plan="1" hidden>
                <img src="<?php echo esc_url(ceeducon_asset_url('assets/media/o2-plan-level-1.webp')); ?>" width="1619" height="971" loading="lazy" decoding="async" alt="Plan of the O2 universum conference level: halls C1 to C3, the D halls and E1, E2." />
                <svg class="floorplan-hotspots" viewBox="0 0 100 100" preserveAspectRatio="none" aria-label="CEEDUCON halls on the conference level">
                  <a class="vp-room" href="<?php echo esc_url(ceeducon_page_url('programme') . '?room=' . rawurlencode('C3')); ?>" data-room="C3"><title>Hall C3 — 8 sessions</title><rect class="vp-hit" x="1.30" y="6.31" width="15.32" height="21.83" /><rect x="1.30" y="10.50" width="15.32" height="17.51" vector-effect="non-scaling-stroke" /></a>
                  <a class="vp-room" href="<?php echo esc_url(ceeducon_page_url('programme') . '?room=' . rawurlencode('C2')); ?>" data-room="C2"><title>Hall C2 — 8 sessions</title><rect class="vp-hit" x="1.30" y="28.27" width="15.32" height="11.38" /><rect x="1.30" y="28.53" width="15.32" height="10.92" vector-effect="non-scaling-stroke" /></a>
                  <a class="vp-room" href="<?php echo esc_url(ceeducon_page_url('programme') . '?room=' . rawurlencode('C1')); ?>" data-room="C1"><title>Hall C1 — 8 sessions</title><rect class="vp-hit" x="1.30" y="39.76" width="15.32" height="21.83" /><rect x="1.30" y="39.86" width="15.32" height="17.82" vector-effect="non-scaling-stroke" /></a>
                  <a class="vp-room" href="<?php echo esc_url(ceeducon_page_url('programme') . '?room=' . rawurlencode('D2')); ?>" data-room="D2"><title>Hall D2 — 8 sessions</title><rect class="vp-hit" x="49.04" y="32.37" width="13.10" height="21.46" /><rect x="53.49" y="40.37" width="8.52" height="5.46" vector-effect="non-scaling-stroke" /></a>
                  <a class="vp-room" href="<?php echo esc_url(ceeducon_page_url('programme') . '?room=' . rawurlencode('D3+D4')); ?>" data-room="D3+D4"><title>Hall D3+D4 — 8 sessions</title><rect class="vp-hit" x="62.43" y="36.77" width="13.10" height="21.83" /><rect x="62.45" y="40.16" width="12.35" height="16.79" vector-effect="non-scaling-stroke" /></a>
                  <a class="vp-room" href="<?php echo esc_url(ceeducon_page_url('programme') . '?room=' . rawurlencode('D6+D7')); ?>" data-room="D6+D7"><title>Hall D6+D7 — 8 sessions</title><rect class="vp-hit" x="61.24" y="62.61" width="13.10" height="21.83" /><rect x="62.51" y="64.78" width="10.56" height="15.24" vector-effect="non-scaling-stroke" /></a>
                </svg>
                <a class="floorplan-source" href="<?php echo esc_url(ceeducon_asset_url('assets/media/o2-plan-level-1.png')); ?>" target="_blank" rel="noreferrer">
                  Full plan
                  <span class="ui-icon" aria-hidden="true"><svg viewBox="0 0 16 16"><path d="M6 4h6v6M12 4 5 11"></path></svg></span>
                </a>
              </figure>
            </div>

            <aside class="floorplan-key">
              <div class="floorplan-detail" data-hall-detail hidden aria-live="polite"></div>

              <div class="floorplan-rooms" data-level-list="0">
                <p class="floorplan-rooms-title">Halls on level 0</p>
                <a class="floorplan-room-link" href="<?php echo esc_url(ceeducon_page_url('programme') . '?room=' . rawurlencode('B1')); ?>" data-room="B1"><strong>B1</strong><span>10 sessions</span></a>
                <a class="floorplan-room-link" href="<?php echo esc_url(ceeducon_page_url('programme') . '?room=' . rawurlencode('B2')); ?>" data-room="B2"><strong>B2</strong><span>10 sessions</span></a>
                <a class="floorplan-room-link" href="<?php echo esc_url(ceeducon_page_url('programme') . '?room=' . rawurlencode('B3')); ?>" data-room="B3"><strong>B3</strong><span>8 sessions</span></a>
              </div>
              <div class="floorplan-rooms" data-level-list="1" hidden>
                <p class="floorplan-rooms-title">Halls on level 1</p>
                <a class="floorplan-room-link" href="<?php echo esc_url(ceeducon_page_url('programme') . '?room=' . rawurlencode('C1')); ?>" data-room="C1"><strong>C1</strong><span>8 sessions</span></a>
                <a class="floorplan-room-link" href="<?php echo esc_url(ceeducon_page_url('programme') . '?room=' . rawurlencode('C2')); ?>" data-room="C2"><strong>C2</strong><span>8 sessions</span></a>
                <a class="floorplan-room-link" href="<?php echo esc_url(ceeducon_page_url('programme') . '?room=' . rawurlencode('C3')); ?>" data-room="C3"><strong>C3</strong><span>8 sessions</span></a>
                <a class="floorplan-room-link" href="<?php echo esc_url(ceeducon_page_url('programme') . '?room=' . rawurlencode('D2')); ?>" data-room="D2"><strong>D2</strong><span>8 sessions</span></a>
                <a class="floorplan-room-link" href="<?php echo esc_url(ceeducon_page_url('programme') . '?room=' . rawurlencode('D3+D4')); ?>" data-room="D3+D4"><strong>D3+D4</strong><span>8 sessions</span></a>
                <a class="floorplan-room-link" href="<?php echo esc_url(ceeducon_page_url('programme') . '?room=' . rawurlencode('D6+D7')); ?>" data-room="D6+D7"><strong>D6+D7</strong><span>8 sessions</span></a>
              </div>
            </aside>
          </div>
        </div>
      </section>
    </main>

<?php
get_footer();
