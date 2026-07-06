<?php
/**
 * Template Name: CEEDUCON Speakers
 */

get_header();
?>

    <main id="main">
      <section class="page-hero page-hero--orange">
        <div class="shell page-hero-grid">
          <div>
            <p class="page-crumbs"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'ceeducon-program'); ?></a><span>/</span><em><?php esc_html_e('For speakers', 'ceeducon-program'); ?></em></p>
            <h1><?php ceeducon_text('spk_hero_title', 'Speaking at CEEDUCON.'); ?></h1>
            <p class="page-hero-note"><?php ceeducon_text('spk_hero_note', 'CEEDUCON sessions are built on practical experience, international cooperation and diverse institutional perspectives. Here is what session contributors need to know.'); ?></p>
          </div>
          <div class="page-hero-card">
            <span><?php ceeducon_text('spk_card_label', 'Programme publication'); ?></span>
            <strong><?php ceeducon_text('spk_card_title', 'By September 1'); ?></strong>
            <p><?php ceeducon_text('spk_card_text', 'Accepted speakers receive detailed follow-up information about registration, contracts and presentation materials.'); ?></p>
          </div>
        </div>
      </section>

      <?php ceeducon_render_editor_content(); ?>

      <section class="section section--navy on-dark">
        <div class="shell speakers-grid">
          <div data-reveal>
            <p class="kicker"><?php ceeducon_text('spk_kicker', 'Speaker information'); ?></p>
            <h2 class="display-2"><?php ceeducon_text('spk_title', 'Practical, onsite-first and in English.'); ?></h2>
            <p class="speakers-lead"><?php ceeducon_text('spk_lead', 'Sessions are delivered primarily in person at O2 universum Prague, in English, with up to three contributors per session. There is no speaker fee.'); ?></p>
            <div class="fact-chips" aria-label="Speaker quick facts">
              <span><?php ceeducon_text('spk_fact_1', 'No speaker fee'); ?></span>
              <span><?php ceeducon_text('spk_fact_2', 'Primarily onsite'); ?></span>
              <span><?php ceeducon_text('spk_fact_3', 'Up to 3 contributors'); ?></span>
              <span><?php ceeducon_text('spk_fact_4', 'English delivery'); ?></span>
            </div>
          </div>
          <div class="step-list" data-reveal="2">
            <article>
              <span>01</span>
              <div>
                <h3><?php ceeducon_text('step_1_title', 'Include all speakers in your proposal'); ?></h3>
                <p><?php ceeducon_text('step_1_text', 'All speakers should be listed during proposal submission. If final names are not confirmed yet, co-speakers can still be indicated in the registration.'); ?></p>
              </div>
            </article>
            <article>
              <span>02</span>
              <div>
                <h3><?php ceeducon_text('step_2_title', 'Prepare for onsite delivery'); ?></h3>
                <p><?php ceeducon_text('step_2_text', 'The conference is planned primarily in person. Limited online participation may be considered only when requested and approved in advance.'); ?></p>
              </div>
            </article>
            <article>
              <span>03</span>
              <div>
                <h3><?php ceeducon_text('step_3_title', 'Share materials and preferences on time'); ?></h3>
                <p><?php ceeducon_text('step_3_text', 'Accepted speakers receive follow-up information about registration, contracts, presentation templates, recording preferences and technical support.'); ?></p>
              </div>
            </article>
          </div>
          <div class="speaker-cta" data-reveal>
            <p><?php ceeducon_html('spk_cta_text', '<strong>Questions about your session?</strong> The CEEDUCON team supports speakers from acceptance through to conference day.'); ?></p>
            <a class="btn btn--primary" href="<?php echo esc_url(ceeducon_text_value('spk_cta_url', 'mailto:ceeducon@dzs.cz?subject=CEEDUCON%202026%20speaker%20question')); ?>"><?php ceeducon_text('spk_cta_button', 'Contact the team'); ?></a>
          </div>
        </div>
      </section>

      <section class="section">
        <div class="shell">
          <div class="section-head">
            <div data-reveal>
              <p class="kicker"><?php ceeducon_text('timeline_kicker', 'Timeline'); ?></p>
              <h2 class="display-2"><?php ceeducon_text('timeline_title', 'Key dates for speakers.'); ?></h2>
            </div>
          </div>
          <div class="timeline timeline--light" aria-label="Speaker timeline">
            <article data-reveal>
              <span><?php ceeducon_text('milestone_1_label', 'By June 30'); ?></span>
              <strong><?php ceeducon_text('milestone_1_text', 'Acceptance notifications'); ?></strong>
            </article>
            <article data-reveal="2">
              <span><?php ceeducon_text('milestone_2_label', 'By July 31'); ?></span>
              <strong><?php ceeducon_text('milestone_2_text', 'Speaker registration & photo'); ?></strong>
            </article>
            <article data-reveal="3">
              <span><?php ceeducon_text('milestone_3_label', 'September'); ?></span>
              <strong><?php ceeducon_text('milestone_3_text', 'Contracts & presentation template'); ?></strong>
            </article>
            <article data-reveal="4">
              <span><?php ceeducon_text('milestone_4_label', 'By September 1'); ?></span>
              <strong><?php ceeducon_text('milestone_4_text', 'Programme publication'); ?></strong>
            </article>
          </div>
        </div>
      </section>

      <section class="section section--paper">
        <div class="shell">
          <div class="section-head">
            <div data-reveal>
              <p class="kicker"><?php ceeducon_text('spk_links_kicker', 'Also useful'); ?></p>
              <h2 class="display-2"><?php ceeducon_text('spk_links_title', 'Before you travel.'); ?></h2>
            </div>
          </div>
          <div class="tile-grid">
            <a class="link-tile" href="<?php echo esc_url(ceeducon_page_url('programme')); ?>" data-reveal>
              <span><?php ceeducon_text('spk_link_1_label', 'Programme'); ?></span>
              <h3><?php ceeducon_text('spk_link_1_title', 'The two-day structure'); ?></h3>
              <p><?php ceeducon_text('spk_link_1_text', 'See how the conference days are planned and how the detailed programme will be published.'); ?></p>
            </a>
            <a class="link-tile" href="<?php echo esc_url(ceeducon_page_url('practical')); ?>" data-reveal="2">
              <span><?php ceeducon_text('spk_link_2_label', 'Practical'); ?></span>
              <h3><?php ceeducon_text('spk_link_2_title', 'Venue & travel'); ?></h3>
              <p><?php ceeducon_text('spk_link_2_text', 'Transport from the airport and stations, accessibility and accommodation tips.'); ?></p>
            </a>
            <a class="link-tile" href="<?php echo esc_url(ceeducon_page_url('contact')); ?>" data-reveal="3">
              <span><?php ceeducon_text('spk_link_3_label', 'Contact'); ?></span>
              <h3><?php ceeducon_text('spk_link_3_title', 'Talk to the organisers'); ?></h3>
              <p><?php ceeducon_text('spk_link_3_text', 'Reach the CEEDUCON team for anything the speaker information does not cover.'); ?></p>
            </a>
          </div>
        </div>
      </section>
    </main>

<?php
get_footer();
