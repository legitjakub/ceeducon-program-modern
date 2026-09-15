<?php
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Template Name: CEEDUCON Previous Editions
 */

get_header();

$editions = [
    ['year' => '2025', 'location' => 'Prague', 'stats' => ['Participants' => '900+', 'Speakers' => '130+', 'Sessions' => '50+'], 'topics' => ['Smart & Sustainable International Cooperation', 'Internationalisation for All', 'Global & Regional Partnerships', 'Alumni – Employability – Future Skills'], 'image' => 'assets/media/ceeducon-photo-plenary.jpg'],
    ['year' => '2024', 'location' => 'Prague', 'stats' => ['Participants' => '700', 'Speakers' => '120', 'Sessions' => '60+'], 'topics' => ['Well-being', 'Internationalisation for All', 'Digital Transformation', 'Academic Cooperation'], 'image' => 'assets/media/ceeducon-photo-networking.jpg'],
    ['year' => '2023', 'location' => 'Brno', 'stats' => ['Participants' => '450', 'Speakers' => '150', 'Sessions' => 'Varied'], 'topics' => ['Inclusion', 'Digitalisation', 'Internationalisation'], 'image' => 'assets/media/ceeducon-photo-workshop.jpg'],
    ['year' => '2022', 'location' => 'Prague', 'stats' => ['Participants' => '450', 'Focus' => 'Strategy', 'Format' => 'In-person'], 'topics' => ['Development of strategies and policies in the field of higher education'], 'image' => 'assets/media/ceeducon-photo-registration.jpg'],
    ['year' => '2021', 'location' => 'Online', 'stats' => ['Participants' => '700+', 'Format' => 'Virtual', 'Focus' => 'Adaptation'], 'topics' => ['(Un)prepared for Change'], 'image' => 'assets/media/ceeducon-photo-accessibility.jpg'],
    ['year' => '2019', 'location' => 'Prague', 'stats' => ['Participants' => '500+', 'Speakers' => '70', 'Sessions' => '42'], 'topics' => ['Internationalisation of Higher Education'], 'image' => 'assets/72786963_2476887475758406_1483797598883020800_o_2476887465758407.jpg', 'gallery' => true],
];

$archive_images = glob(get_template_directory() . '/assets/*.jpg') ?: [];
?>
<main id="main">
  <section class="page-hero">
    <div class="shell">
      <p class="page-crumbs"><a href="<?php echo esc_url(home_url('/')); ?>">Home</a><span>/</span><em>Previous editions</em></p>
      <h1>CEEDUCON through the years.</h1>
      <p class="page-hero-note">Explore the previous editions of CEEDUCON and how the conference has evolved together with the international higher education community.</p>
    </div>
  </section>

  <section class="section section--paper">
    <div class="shell">
      <div class="section-head">
        <div data-reveal>
          <p class="kicker">Conference archive</p>
          <h2 class="display-2">The steps that shaped CEEDUCON.</h2>
        </div>
        <p data-reveal="2">From earlier regional gatherings to the broad conference experience of today, each edition has reflected the priorities, challenges and momentum of the international higher education community.</p>
      </div>

      <div class="archive-grid" aria-label="Previous editions of CEEDUCON">
        <?php foreach ($editions as $index => $edition) : ?>
          <details class="edition-card edition-card--<?php echo esc_attr($index % 3 === 0 ? 'navy' : ($index % 3 === 1 ? 'orange' : 'sky')); ?>" data-reveal="<?php echo esc_attr((string) (($index % 3) + 1)); ?>">
            <summary>
              <div class="edition-card-header">
                <span class="edition-year"><?php echo esc_html($edition['year']); ?></span>
                <span class="edition-location"><?php echo esc_html($edition['location']); ?></span>
              </div>
              <div class="edition-stats" aria-label="<?php echo esc_attr($edition['year']); ?> conference statistics">
                <?php foreach ($edition['stats'] as $label => $value) : ?>
                  <div class="edition-stat"><span><?php echo esc_html($label); ?></span><strong><?php echo esc_html($value); ?></strong></div>
                <?php endforeach; ?>
              </div>
            </summary>
            <div class="edition-preview">
              <img src="<?php echo esc_url(ceeducon_asset_url($edition['image'])); ?>" alt="CEEDUCON <?php echo esc_attr($edition['year']); ?> archive preview" loading="lazy" decoding="async" />
              <?php if (!empty($edition['gallery'])) : ?>
                <div class="edition-gallery" aria-label="CEEDUCON 2019 photo gallery">
                  <?php foreach ($archive_images as $archive_image) : ?>
                    <img src="<?php echo esc_url(ceeducon_asset_url('assets/' . basename($archive_image))); ?>" alt="CEEDUCON 2019 archive photo" loading="lazy" decoding="async" />
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
              <div class="edition-preview-copy">
                <span><?php echo !empty($edition['gallery']) ? 'Photo archive' : 'Archive preview'; ?></span>
                <strong><?php echo esc_html($edition['year'] === '2019' ? 'Foundations of the modern conference.' : 'A CEEDUCON edition shaped by practical exchange.'); ?></strong>
                <p><?php echo esc_html($edition['year'] === '2019' ? 'The 2019 edition helped define the programme style and community focus that CEEDUCON continues to build on today.' : 'Explore the priorities, conversations and community that shaped this edition of CEEDUCON.'); ?></p>
              </div>
              <div class="edition-topics">
                <span>Topics</span>
                <ul class="topic-list">
                  <?php foreach ($edition['topics'] as $topic) : ?><li><?php echo esc_html($topic); ?></li><?php endforeach; ?>
                </ul>
              </div>
            </div>
          </details>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>
