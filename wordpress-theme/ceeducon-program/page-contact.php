<?php
/**
 * Template Name: CEEDUCON Contact
 */

get_header();

$con_email = ceeducon_text_value('con_email', 'ceeducon@dzs.cz');
$con_phone = ceeducon_text_value('con_phone', '+420 221 850 100');

if (ceeducon_render_elementor_page_content() || ceeducon_render_block_page_content()) {
    get_footer();
    return;
}
?>

    <main id="main">
      <section class="page-hero">
        <div class="shell page-hero-grid">
          <div>
            <p class="page-crumbs"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'ceeducon-program'); ?></a><span>/</span><em><?php esc_html_e('Contact', 'ceeducon-program'); ?></em></p>
            <h1><?php ceeducon_text('con_hero_title', 'Talk to the CEEDUCON team.'); ?></h1>
            <p class="page-hero-note"><?php ceeducon_text('con_hero_note', 'Registration updates, programme questions, speaker communication or partnerships — the organisers are happy to help.'); ?></p>
          </div>
          <div class="page-hero-card">
            <span><?php ceeducon_text('con_card_label', 'Write or call'); ?></span>
            <strong><a href="mailto:<?php echo esc_attr($con_email); ?>"><?php echo esc_html($con_email); ?></a></strong>
            <p><?php echo esc_html($con_phone); ?></p>
          </div>
        </div>
      </section>

      <?php ceeducon_render_editor_content(); ?>

      <section class="section">
        <div class="shell contact-band">
          <div data-reveal>
            <p class="kicker"><?php ceeducon_text('con_kicker', 'Organiser'); ?></p>
            <h2 class="display-2"><?php ceeducon_text('con_title', 'Czech National Agency for International Education and Research.'); ?></h2>
            <p class="lead"><?php ceeducon_html('con_lead', 'CEEDUCON is organised by DZS in co-operation with Central European partner organisations. Write to <a href="mailto:ceeducon@dzs.cz">ceeducon@dzs.cz</a> or call +420 221 850 100.'); ?></p>
            <div class="contact-actions">
              <a class="btn btn--primary" href="mailto:<?php echo esc_attr($con_email); ?>"><?php ceeducon_text('con_button_email', 'Email CEEDUCON'); ?></a>
              <a class="btn btn--outline" href="<?php echo esc_url(ceeducon_text_value('footer_dzs_url', 'https://www.dzs.cz/')); ?>" target="_blank" rel="noreferrer"><?php ceeducon_text('con_button_dzs', 'DZS website'); ?></a>
            </div>
          </div>
          <div class="partners-card" data-reveal="2">
            <img src="<?php echo esc_url(ceeducon_asset_url('assets/dzs-logo.png')); ?>" alt="DZS" width="1024" height="522" loading="lazy" decoding="async" />
            <span><?php ceeducon_text('organiser_label', 'Organised by'); ?></span>
            <strong><?php ceeducon_text('organiser_name', 'Czech National Agency for International Education and Research (DZS)'); ?></strong>
            <span><?php ceeducon_text('partners_label', 'In co-operation with'); ?></span>
            <p><?php ceeducon_text('partners_text', 'OeAD · DAAD · FRSE · SAAIC · Tempus Public Foundation'); ?></p>
          </div>
        </div>
      </section>

      <section class="section section--paper">
        <div class="shell">
          <div class="section-head">
            <div data-reveal>
              <p class="kicker"><?php ceeducon_text('con_links_kicker', 'Looking for something?'); ?></p>
              <h2 class="display-2"><?php ceeducon_text('con_links_title', 'Quick answers, one page away.'); ?></h2>
            </div>
          </div>
          <div class="tile-grid">
            <a class="link-tile" href="<?php echo esc_url(ceeducon_page_url('programme')); ?>" data-reveal>
              <span><?php ceeducon_text('con_link_1_label', 'Programme'); ?></span>
              <h3><?php ceeducon_text('con_link_1_title', 'The two conference days'); ?></h3>
              <p><?php ceeducon_text('con_link_1_text', 'Day structure, thematic areas and the interactive programme grid.'); ?></p>
            </a>
            <a class="link-tile" href="<?php echo esc_url(ceeducon_page_url('practical')); ?>" data-reveal="2">
              <span><?php ceeducon_text('con_link_2_label', 'Practical'); ?></span>
              <h3><?php ceeducon_text('con_link_2_title', 'Getting to the venue'); ?></h3>
              <p><?php ceeducon_text('con_link_2_text', 'Transport, accessibility, accommodation and travel tips for Prague.'); ?></p>
            </a>
            <a class="link-tile" href="<?php echo esc_url(ceeducon_page_url('speakers')); ?>" data-reveal="3">
              <span><?php ceeducon_text('con_link_3_label', 'For speakers'); ?></span>
              <h3><?php ceeducon_text('con_link_3_title', 'Session guidance'); ?></h3>
              <p><?php ceeducon_text('con_link_3_text', 'Expectations, delivery format and the speaker timeline for 2026.'); ?></p>
            </a>
          </div>
        </div>
      </section>
    </main>

<?php
get_footer();
