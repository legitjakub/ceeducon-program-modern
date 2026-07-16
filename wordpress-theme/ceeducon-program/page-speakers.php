<?php
/**
 * Template Name: CEEDUCON Speakers
 */

get_header();

if (ceeducon_render_elementor_page_content() || ceeducon_render_block_page_content()) {
    get_footer();
    return;
}
?>

    <main id="main">
      <section class="page-hero page-hero--orange">
        <div class="shell page-hero-grid">
          <div>
            <p class="page-crumbs"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'ceeducon-program'); ?></a><span>/</span><em><?php esc_html_e('Speakers', 'ceeducon-program'); ?></em></p>
            <h1><?php ceeducon_text('spk_hero_title', 'Speakers at CEEDUCON.'); ?></h1>
            <p class="page-hero-note"><?php ceeducon_text('spk_hero_note', 'Meet featured contributors and find practical information about formats, milestones and support for confirmed CEEDUCON speakers.'); ?></p>
          </div>
          <div class="page-hero-card">
            <span><?php ceeducon_text('spk_card_label', 'Programme publication'); ?></span>
            <strong><?php ceeducon_text('spk_card_title', 'By September 1'); ?></strong>
            <p><?php ceeducon_text('spk_card_text', 'Accepted speakers receive detailed follow-up information about registration, contracts and presentation materials.'); ?></p>
          </div>
        </div>
      </section>

      <?php ceeducon_render_editor_content(); ?>

      <section class="section">
        <div class="shell">
          <div class="section-head">
            <div data-reveal>
              <p class="kicker"><?php ceeducon_text('featured_speakers_kicker', 'Programme voices'); ?></p>
              <h2 class="display-2"><?php ceeducon_text('featured_speakers_title', 'Featured speakers and contributors.'); ?></h2>
            </div>
            <p data-reveal="2"><?php ceeducon_text('featured_speakers_intro', 'A first look at people already appearing in the preliminary CEEDUCON 2026 programme. The full speaker list will follow as confirmations are completed.'); ?></p>
          </div>
          <div class="speaker-card-grid" aria-label="<?php esc_attr_e('Featured CEEDUCON speakers', 'ceeducon-program'); ?>">
            <article class="speaker-card" data-reveal>
              <span class="speaker-initials" aria-hidden="true">MU</span>
              <div>
                <span><?php ceeducon_text('featured_speaker_1_label', 'Opening & strategy'); ?></span>
                <h3><?php ceeducon_text('featured_speaker_1_name', 'Michal Uhl'); ?></h3>
                <p><?php ceeducon_text('featured_speaker_1_text', 'Czech National Agency for International Education and Research (DZS)'); ?></p>
              </div>
            </article>
            <article class="speaker-card" data-reveal="2">
              <span class="speaker-initials" aria-hidden="true">VS</span>
              <div>
                <span><?php ceeducon_text('featured_speaker_2_label', 'AI & student journey'); ?></span>
                <h3><?php ceeducon_text('featured_speaker_2_name', 'Valentýna Škrabálková'); ?></h3>
                <p><?php ceeducon_text('featured_speaker_2_text', 'DZS'); ?></p>
              </div>
            </article>
            <article class="speaker-card" data-reveal="3">
              <span class="speaker-initials" aria-hidden="true">JVD</span>
              <div>
                <span><?php ceeducon_text('featured_speaker_3_label', 'Digital tools'); ?></span>
                <h3><?php ceeducon_text('featured_speaker_3_name', 'Jeroen Van Dijk'); ?></h3>
                <p><?php ceeducon_text('featured_speaker_3_text', 'Royal College of Art'); ?></p>
              </div>
            </article>
            <article class="speaker-card" data-reveal>
              <span class="speaker-initials" aria-hidden="true">LB</span>
              <div>
                <span><?php ceeducon_text('featured_speaker_4_label', 'Global partnerships'); ?></span>
                <h3><?php ceeducon_text('featured_speaker_4_name', 'Louis Brau'); ?></h3>
                <p><?php ceeducon_text('featured_speaker_4_text', "European Students' Union"); ?></p>
              </div>
            </article>
            <article class="speaker-card" data-reveal="2">
              <span class="speaker-initials" aria-hidden="true">JHS</span>
              <div>
                <span><?php ceeducon_text('featured_speaker_5_label', 'Young professionals'); ?></span>
                <h3><?php ceeducon_text('featured_speaker_5_name', 'Jody Hoekstra-Selten'); ?></h3>
                <p><?php ceeducon_text('featured_speaker_5_text', 'EAIE'); ?></p>
              </div>
            </article>
            <article class="speaker-card" data-reveal="3">
              <span class="speaker-initials" aria-hidden="true">JN</span>
              <div>
                <span><?php ceeducon_text('featured_speaker_6_label', 'European cooperation'); ?></span>
                <h3><?php ceeducon_text('featured_speaker_6_name', 'Jiří Nantl'); ?></h3>
                <p><?php ceeducon_text('featured_speaker_6_text', 'University of Applied Sciences St. Pölten'); ?></p>
              </div>
            </article>
          </div>
        </div>
      </section>

      <section class="section section--navy on-dark">
        <div class="shell speakers-grid">
          <div data-reveal>
            <p class="kicker"><?php ceeducon_text('spk_kicker', 'Speaker information'); ?></p>
            <h2 class="display-2"><?php ceeducon_text('spk_title', 'Clear expectations before conference day.'); ?></h2>
            <p class="speakers-lead"><?php ceeducon_text('spk_lead', 'Sessions are planned primarily onsite at O2 universum Prague and delivered in English. This page keeps the essential speaker information in one place.'); ?></p>
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
                <h3><?php ceeducon_text('step_1_title', 'Confirm contributors early'); ?></h3>
                <p><?php ceeducon_text('step_1_text', 'List all speakers as early as possible so the programme, contracts and communication can stay accurate.'); ?></p>
              </div>
            </article>
            <article>
              <span>02</span>
              <div>
                <h3><?php ceeducon_text('step_2_title', 'Prepare for onsite delivery'); ?></h3>
                <p><?php ceeducon_text('step_2_text', 'Plan for an in-person session unless the organisers confirm a different arrangement in advance.'); ?></p>
              </div>
            </article>
            <article>
              <span>03</span>
              <div>
                <h3><?php ceeducon_text('step_3_title', 'Share materials on time'); ?></h3>
                <p><?php ceeducon_text('step_3_text', 'Follow the speaker timeline for registration, presentation materials, recording preferences and technical checks.'); ?></p>
              </div>
            </article>
          </div>
          <div class="speaker-cta" data-reveal>
            <p><?php ceeducon_html('spk_cta_text', '<strong>Questions about your session?</strong> Use the contact page for anything not covered here.'); ?></p>
            <a class="btn btn--primary" href="<?php echo esc_url(ceeducon_text_value('spk_cta_url', ceeducon_page_url('contact'))); ?>"><?php ceeducon_text('spk_cta_button', 'Contact page'); ?></a>
          </div>
        </div>
      </section>

      <section class="section">
        <div class="shell">
          <div class="section-head">
            <div data-reveal>
              <p class="kicker"><?php ceeducon_text('timeline_kicker', 'Timeline'); ?></p>
              <h2 class="display-2"><?php ceeducon_text('timeline_title', 'Speaker timeline.'); ?></h2>
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
              <h2 class="display-2"><?php ceeducon_text('spk_links_title', 'Useful next steps.'); ?></h2>
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
