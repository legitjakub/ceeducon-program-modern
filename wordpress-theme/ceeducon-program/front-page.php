<?php
if (!defined('ABSPATH')) {
    exit;
}
/**
 * CEEDUCON home page.
 */

get_header();

if (ceeducon_render_block_page_content()) {
    get_footer();
    return;
}

$event_month = preg_replace('/<br\s*\/?>/i', ' ', ceeducon_text_value('event_month', 'Dec 2026'));
$event_month = trim((string) preg_replace('/\s+/', ' ', wp_strip_all_tags((string) $event_month)));
$registration_value = ceeducon_text_value('event_row_4_value', 'Registration is open');
$registration_url = ceeducon_text_value('registration_url', '');
$hero_kicker = ceeducon_text_value('home_hero_kicker', '{{event_title}} · CZECHIA');
$hero_image = (array) apply_filters('ceeducon_home_hero_image', [
    'id' => 0,
    'url' => ceeducon_text_value('home_hero_image_url', ceeducon_asset_url('assets/media/ceeducon-photo-plenary.jpg')),
    'alt' => ceeducon_text_value('home_hero_image_alt', 'A packed CEEDUCON plenary session'),
]);
?>

    <main id="main">
      <section class="hero">
        <div class="hero-media">
          <?php if (!empty($hero_image['id'])) : ?>
            <?php echo wp_get_attachment_image((int) $hero_image['id'], 'full', false, ['alt' => (string) ($hero_image['alt'] ?? ''), 'loading' => 'eager', 'decoding' => 'async', 'fetchpriority' => 'high']); ?>
          <?php elseif (!empty($hero_image['url'])) : ?>
            <img src="<?php echo esc_url((string) $hero_image['url']); ?>" alt="<?php echo esc_attr((string) ($hero_image['alt'] ?? '')); ?>" width="1600" height="1064" decoding="async" fetchpriority="high" />
          <?php endif; ?>
        </div>
        <div class="hero-inner shell">
          <div class="hero-copy">
            <p class="hero-kicker"><?php echo esc_html($hero_kicker); ?></p>
            <h1><?php ceeducon_html('home_hero_title', 'Central Europe <em>meets the world</em> of higher education.'); ?></h1>
            <p class="hero-lead"><?php ceeducon_text('home_hero_lead', 'Two days of practical exchange for university leaders, international offices, policymakers and national agencies.'); ?></p>
            <div class="hero-actions" aria-label="Quick actions">
              <a class="btn btn--primary" href="<?php echo esc_url(ceeducon_page_url('programme')); ?>"><?php ceeducon_text('home_cta_primary', 'Explore programme'); ?></a>
              <a class="btn btn--ghost" href="<?php echo esc_url(ceeducon_page_url('practical')); ?>"><?php ceeducon_text('home_cta_secondary', 'Plan your visit'); ?></a>
            </div>
          </div>
        </div>
        <div class="hero-facts-wrap">
          <div class="hero-facts shell" aria-label="Conference essentials">
            <div class="hero-essentials">
              <span class="hero-essential hero-essential--date"><strong><?php ceeducon_text('event_day', '1–2'); ?> <?php echo esc_html($event_month); ?></strong></span>
              <div class="hero-essential-details">
                <span class="hero-essential"><span class="sr-only"><?php ceeducon_text('event_row_1_label', 'Venue'); ?>: </span><?php ceeducon_text('event_row_1_value', '{{venue}}'); ?></span>
                <span class="hero-essential"><span class="sr-only"><?php ceeducon_text('event_row_3_label', 'Fee'); ?>: </span><?php ceeducon_text('event_row_3_value', 'Free of charge'); ?></span>
                <span class="hero-essential"><span class="sr-only"><?php ceeducon_text('event_row_4_label', 'Registration'); ?>: </span><?php if ($registration_url !== '') : ?><a class="hero-essential-link" href="<?php echo esc_url($registration_url); ?>"><?php echo esc_html($registration_value); ?></a><?php else : ?><?php echo esc_html($registration_value); ?><?php endif; ?></span>
              </div>
            </div>
            <div class="hero-calendar-actions" aria-label="<?php echo esc_attr(sprintf(__('Add %s to a calendar', 'ceeducon-program'), $hero_kicker)); ?>">
              <a class="hero-calendar" href="<?php echo esc_url(ceeducon_text_value('event_google_calendar_url', 'https://calendar.google.com/calendar/render?action=TEMPLATE&text=CEEDUCON%202026&dates=20261201T080000Z%2F20261202T170000Z&details=Central%20European%20Conference%20on%20Internationalisation%20of%20Higher%20Education.&location=O2%20universum%2C%20Ceskomoravska%2017%2C%20Prague%209%2C%20Czech%20Republic&ctz=Europe%2FPrague')); ?>" target="_blank" rel="noreferrer"><?php ceeducon_text('event_google_calendar_label', 'Google Calendar'); ?> <span class="hero-calendar-icon" aria-hidden="true"><svg viewBox="0 0 20 20"><path d="M5.5 2.5v3M14.5 2.5v3M3 7.5h14M4.5 4h11A1.5 1.5 0 0 1 17 5.5v10a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 3 15.5v-10A1.5 1.5 0 0 1 4.5 4Z"></path><path d="M6.5 11h2v2h-2zM11.5 11h2v2h-2z"></path></svg></span></a>
              <a class="hero-calendar" href="<?php echo esc_url(ceeducon_text_value('event_outlook_calendar_url', 'https://outlook.live.com/calendar/0/deeplink/compose?path=%2Fcalendar%2Faction%2Fcompose&rru=addevent&subject=CEEDUCON%202026&startdt=2026-12-01T09%3A00%3A00%2B01%3A00&enddt=2026-12-02T18%3A00%3A00%2B01%3A00&body=Central%20European%20Conference%20on%20Internationalisation%20of%20Higher%20Education.&location=O2%20universum%2C%20Ceskomoravska%2017%2C%20Prague%209%2C%20Czech%20Republic')); ?>" target="_blank" rel="noreferrer"><?php ceeducon_text('event_outlook_calendar_label', 'Outlook Calendar'); ?> <span class="hero-calendar-icon" aria-hidden="true"><svg viewBox="0 0 20 20"><path d="M5.5 2.5v3M14.5 2.5v3M3 7.5h14M4.5 4h11A1.5 1.5 0 0 1 17 5.5v10a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 3 15.5v-10A1.5 1.5 0 0 1 4.5 4Z"></path><path d="M6.5 11h2v2h-2zM11.5 11h2v2h-2z"></path></svg></span></a>
            </div>
          </div>
        </div>
      </section>

      <?php ceeducon_render_editor_content(); ?>

      <section class="section">
        <div class="shell statement-grid">
          <div data-reveal>
            <p class="kicker"><?php ceeducon_text('home_about_kicker', 'The conference'); ?></p>
            <h2 class="display-2"><?php ceeducon_text('home_about_title', 'Where the people shaping international higher education come together.'); ?></h2>
          </div>
          <div class="statement-copy" data-reveal="2">
            <p><?php ceeducon_text('home_about_text_1', 'CEEDUCON connects university leaders, internationalisation professionals, policymakers, national agencies and practitioners from across Europe to exchange ideas, experiences and solutions.'); ?></p>
            <p><?php ceeducon_text('home_about_text_2', 'From emerging challenges to proven approaches, the programme focuses on what is changing, what works in practice, and what we can achieve through stronger Central European cooperation.'); ?></p>
            <div class="fact-chips" aria-label="Who attends">
              <span><?php ceeducon_text('home_chip_1', 'University leadership'); ?></span>
              <span><?php ceeducon_text('home_chip_2', 'International offices'); ?></span>
              <span><?php ceeducon_text('home_chip_3', 'Policymakers'); ?></span>
              <span><?php ceeducon_text('home_chip_4', 'National agencies'); ?></span>
            </div>
            <a class="btn btn--outline mt-lg" href="<?php echo esc_url(ceeducon_page_url('about')); ?>"><?php ceeducon_text('home_about_button', 'More about CEEDUCON'); ?></a>
          </div>
        </div>
      </section>

      <section class="section section--tint theme-section-light">
        <div class="shell">
          <div class="section-head">
            <div data-reveal>
              <p class="kicker"><?php ceeducon_text('home_themes_kicker', 'Thematic areas'); ?></p>
              <h2 class="display-2"><?php ceeducon_text('home_themes_title', 'Four themes frame the {{year}} conversation.'); ?></h2>
            </div>
            <p data-reveal="2"><?php ceeducon_text('home_themes_intro', 'From responsible technology to the complete student journey — the {{year}} programme connects the questions that matter most to international higher education right now.'); ?></p>
          </div>
          <div class="theme-grid" aria-label="Conference thematic areas">
            <?php
            $theme_items = [];
            foreach (ceeducon_default_theme_items() as $index => $item) {
                $number = $index + 1;
                $theme_items[] = [
                    'number' => $item['number'],
                    'title' => ceeducon_text_value("theme_{$number}_title", $item['title']),
                    'text' => ceeducon_text_value("theme_{$number}_text", $item['text']),
                    'question' => ceeducon_text_value("theme_{$number}_question", $item['question']),
                    'details' => ceeducon_text_value("theme_{$number}_details", $item['details']),
                ];
            }
            ceeducon_render_theme_cards($theme_items);
            ?>
          </div>
        </div>
      </section>

      <?php
      ceeducon_print_section('video', [
          'kicker' => ceeducon_text_value('home_video_kicker', 'CEEDUCON in motion'),
          'title' => ceeducon_text_value('home_video_title', 'See the conference come to life.'),
          'text' => ceeducon_text_value('home_video_text', 'Step inside CEEDUCON and experience the plenaries, practical sessions and conversations that connect the international higher education community.'),
          'videoUrl' => ceeducon_text_value('home_video_url', 'https://www.youtube.com/watch?v=oad5sn8ku1c'),
          'videoTitle' => ceeducon_text_value('home_video_accessible_title', 'CEEDUCON conference video'),
          'buttonText' => ceeducon_text_value('home_video_button', 'Watch on YouTube'),
          'caption' => ceeducon_text_value('home_video_caption', 'Highlights from CEEDUCON'),
      ]);
      ?>

      <?php
      ceeducon_print_section('photo-gallery', [
          'kicker' => ceeducon_text_value('media_kicker', 'Conference atmosphere'),
          'title' => ceeducon_text_value('media_title', 'The people and conversations behind the programme.'),
          'text' => ceeducon_text_value('media_text', 'Plenaries, workshops and informal conversations make CEEDUCON a practical meeting point for the international higher education community.'),
          'buttonText' => ceeducon_text_value('media_button_primary', 'Browse programme'),
          'buttonUrl' => ceeducon_page_url('programme'),
          'items' => [
              ['imageUrl' => ceeducon_text_value('media_hero_url', ceeducon_asset_url('assets/media/ceeducon-photo-plenary.jpg')), 'imageAlt' => ceeducon_text_value('media_hero_alt', 'CEEDUCON plenary session with a full audience'), 'label' => ceeducon_text_value('media_hero_label', 'Plenary')],
              ['imageUrl' => ceeducon_text_value('media_image_1_url', ceeducon_asset_url('assets/media/ceeducon-photo-networking.jpg')), 'imageAlt' => ceeducon_text_value('media_image_1_alt', 'CEEDUCON participants talking during a networking break'), 'label' => ceeducon_text_value('media_image_1_label', 'Networking')],
              ['imageUrl' => ceeducon_text_value('media_image_2_url', ceeducon_asset_url('assets/media/ceeducon-photo-workshop.jpg')), 'imageAlt' => ceeducon_text_value('media_image_2_alt', 'CEEDUCON workshop with participants seated around tables'), 'label' => ceeducon_text_value('media_image_2_label', 'Workshops')],
              ['imageUrl' => ceeducon_text_value('media_image_3_url', ceeducon_asset_url('assets/media/ceeducon-photo-registration.jpg')), 'imageAlt' => ceeducon_text_value('media_image_3_alt', 'Participants arriving and registering at CEEDUCON'), 'label' => ceeducon_text_value('media_image_3_label', 'Arrival')],
              ['imageUrl' => ceeducon_text_value('media_image_4_url', ceeducon_asset_url('assets/media/ceeducon-photo-accessibility.jpg')), 'imageAlt' => ceeducon_text_value('media_image_4_alt', 'CEEDUCON participants speaking in an accessible lounge area'), 'label' => ceeducon_text_value('media_image_4_label', 'Community')],
          ],
      ]);
      ?>

      <section class="section">
        <div class="shell">
          <div class="section-head">
            <div data-reveal>
              <p class="kicker"><?php ceeducon_text('home_prog_kicker', 'Programme {{year}}'); ?></p>
              <h2 class="display-2"><?php ceeducon_text('home_prog_title', 'Two full conference days in {{city}}.'); ?></h2>
            </div>
            <p data-reveal="2"><?php ceeducon_text('home_prog_intro', 'The preliminary room-by-room programme is online — sessions, workshops and speakers for both conference days.'); ?></p>
          </div>
          <div class="day-cards" aria-label="<?php echo esc_attr(ceeducon_text_value('home_programme_aria', '{{event_title}} outline')); ?>">
            <article data-reveal>
              <span><?php ceeducon_text('day_1_label', 'Day 1 · Tue 1 Dec'); ?></span>
              <h3><?php ceeducon_text('day_1_title', 'All-day conference'); ?></h3>
              <p><?php ceeducon_text('day_1_text', 'Opening plenary and thematic sessions across the four {{year}} themes at {{venue}}.'); ?></p>
            </article>
            <article data-reveal="2">
              <span><?php ceeducon_text('day_evening_label', 'Evening'); ?></span>
              <h3><?php ceeducon_text('day_evening_title', 'Networking reception'); ?></h3>
              <p><?php ceeducon_text('day_evening_text', 'An evening dedicated to informal exchange and new partnerships. Details will follow with the final programme.'); ?></p>
            </article>
            <article data-reveal="3">
              <span><?php ceeducon_text('day_2_label', 'Day 2 · Wed 2 Dec'); ?></span>
              <h3><?php ceeducon_text('day_2_title', 'All-day conference'); ?></h3>
              <p><?php ceeducon_text('day_2_text', 'A second full day of sessions and workshops, closing with a joint plenary.'); ?></p>
            </article>
          </div>
          <div class="notice-cards">
            <article class="notice-card notice-card--sky notice-card--wide" data-reveal>
              <span><?php ceeducon_text('notice_prog_label', 'Preliminary programme'); ?></span>
              <h3><?php ceeducon_text('notice_prog_title', 'Online now.'); ?></h3>
              <p><?php ceeducon_text('notice_prog_text', 'Browse the two-day programme — 70+ sessions and workshops across nine rooms. Details remain subject to change.'); ?></p>
              <a class="btn btn--dark" href="<?php echo esc_url(ceeducon_page_url('programme')); ?>"><?php ceeducon_text('notice_prog_button', 'Open the programme'); ?></a>
            </article>
          </div>
        </div>
      </section>

      <section class="section section--tint">
        <div class="shell">
          <div class="section-head">
            <div data-reveal>
              <p class="kicker"><?php ceeducon_text('home_plan_kicker', 'Plan ahead'); ?></p>
              <h2 class="display-2"><?php ceeducon_text('home_plan_title', 'Find the essentials quickly.'); ?></h2>
            </div>
          </div>
          <div class="tile-grid">
            <a class="link-tile link-tile--media" href="<?php echo esc_url(ceeducon_page_url('practical')); ?>" data-reveal>
              <span class="link-tile-media">
                <img src="<?php ceeducon_url('home_link_1_image_url', ceeducon_asset_url('assets/media/ceeducon-photo-registration.jpg')); ?>" alt="<?php ceeducon_attr('home_link_1_image_alt', 'Participants arriving and registering at CEEDUCON'); ?>" width="1600" height="1064" loading="lazy" decoding="async" />
              </span>
              <span class="link-tile-body">
                <span class="link-tile-label"><?php ceeducon_text('home_link_1_label', 'Practical'); ?></span>
                <h3><?php ceeducon_text('home_link_1_title', 'Getting to the conference'); ?></h3>
                <p><?php ceeducon_text('home_link_1_text', 'Venue, transport from the airport and stations, accessibility and accommodation tips.'); ?></p>
              </span>
            </a>
            <a class="link-tile link-tile--media" href="<?php echo esc_url(ceeducon_page_url('speakers')); ?>" data-reveal="2">
              <span class="link-tile-media">
                <img src="<?php ceeducon_url('home_link_2_image_url', ceeducon_asset_url('assets/media/ceeducon-photo-workshop.jpg')); ?>" alt="<?php ceeducon_attr('home_link_2_image_alt', 'A CEEDUCON speaker leading a workshop'); ?>" width="1600" height="1064" loading="lazy" decoding="async" />
              </span>
              <span class="link-tile-body">
                <span class="link-tile-label"><?php ceeducon_text('home_link_2_label', 'For speakers'); ?></span>
                <h3><?php ceeducon_text('home_link_2_title', 'Speaking at CEEDUCON'); ?></h3>
                <p><?php ceeducon_text('home_link_2_text', 'Session expectations, onsite delivery, timeline and speaker support in one overview.'); ?></p>
              </span>
            </a>
            <a class="link-tile link-tile--media" href="<?php echo esc_url(ceeducon_page_url('media')); ?>" data-reveal="3">
              <span class="link-tile-media">
                <img src="<?php ceeducon_url('home_link_3_image_url', ceeducon_asset_url('assets/media/ceeducon-photo-plenary.jpg')); ?>" alt="<?php ceeducon_attr('home_link_3_image_alt', 'A packed CEEDUCON plenary session'); ?>" width="1600" height="1064" loading="lazy" decoding="async" />
              </span>
              <span class="link-tile-body">
                <span class="link-tile-label"><?php ceeducon_text('home_link_3_label', 'Media kit'); ?></span>
                <h3><?php ceeducon_text('home_link_3_title', 'Official assets and press information'); ?></h3>
                <p><?php ceeducon_text('home_link_3_text', 'Download approved visuals, find press updates and contact the team for media requests.'); ?></p>
              </span>
            </a>
          </div>
        </div>
      </section>

      <section class="section">
        <div class="shell contact-band">
          <div data-reveal>
            <p class="kicker"><?php ceeducon_text('home_org_kicker', 'Organisers'); ?></p>
            <h2 class="display-2"><?php ceeducon_text('home_org_title', "Backed by Central Europe's national agencies."); ?></h2>
            <p class="lead"><?php ceeducon_html('home_org_lead', 'CEEDUCON is organised by DZS — the Czech National Agency for International Education and Research — in co-operation with partner organisations across the region. Reach the team at <a href="mailto:ceeducon@dzs.cz">ceeducon@dzs.cz</a>.'); ?></p>
          </div>
          <div class="partners-card" data-reveal="2">
            <span><?php ceeducon_text('organiser_label', 'Organised by'); ?></span>
            <strong><?php ceeducon_text('organiser_name', 'Czech National Agency for International Education and Research (DZS)'); ?></strong>
            <span><?php ceeducon_text('partners_label', 'In co-operation with'); ?></span>
            <p><?php ceeducon_text('partners_text', 'OeAD · DAAD · FRSE · SAAIC · Tempus Public Foundation'); ?></p>
          </div>
        </div>
      </section>
    </main>

<?php
get_footer();
