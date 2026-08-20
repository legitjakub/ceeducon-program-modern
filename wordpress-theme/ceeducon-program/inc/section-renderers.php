<?php
/**
 * Shared section rendering adapter.
 *
 * Gutenberg dynamic blocks and the companion Elementor plugin use the same
 * render templates, so frontend markup remains owned by the theme.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ceeducon_default_theme_items(): array
{
    return [
        [
            'number' => '01',
            'title' => 'Navigating the Technological Shift',
            'text' => 'AI, digitalisation, data and new tools for smarter internationalisation — with academic values and human judgement in focus.',
            'question' => 'How can universities use AI, digitalisation and data responsibly while preserving academic values and human judgement?',
            'details' => 'Key topics include AI in international offices and project work, data-driven strategies and evaluation, responsible data use, digital partnerships and staff skills.',
        ],
        [
            'number' => '02',
            'title' => 'Challenges of Internationalisation',
            'text' => 'Funding, safety, wellbeing and inclusion — removing barriers to meaningful international experiences for students and staff.',
            'question' => 'How can universities remove structural, social and financial barriers while creating safe, inclusive and meaningful international experiences?',
            'details' => 'Key topics include housing, mobility safety and visas, wellbeing and crisis response, funding, inclusive participation, Green Erasmus and mobility quality.',
        ],
        [
            'number' => '03',
            'title' => 'Global & Regional Partnerships',
            'text' => 'Strategic cooperation, European University alliances and equitable partnerships across global regions.',
            'question' => 'How can universities build sustainable partnerships that balance strategic priorities, mutual benefit and equitable collaboration?',
            'details' => 'Key topics include European University alliances, regional and global networks, capacity building, Global Gateway, BIPs, joint degrees and more equal partnerships.',
        ],
        [
            'number' => '04',
            'title' => 'From Recruitment to Retention',
            'text' => 'The student journey end to end — admissions, support, employability, alumni relations and long-term success.',
            'question' => 'How can universities connect recruitment, admissions, support and alumni engagement into one coherent student-centred strategy?',
            'details' => 'Key topics include targeted recruitment, transparent admissions, onboarding, integration and support, retention, employability, alumni relations and stakeholder engagement.',
        ],
    ];
}

function ceeducon_render_theme_cards(array $items = []): void
{
    if ($items === []) {
        $items = ceeducon_default_theme_items();
    }

    $variants = ['sky', 'orange', 'white', 'navy'];
    foreach ($items as $index => $item) {
        $number = (string) ($item['number'] ?? sprintf('%02d', $index + 1));
        $title = (string) ($item['title'] ?? '');
        $text = (string) ($item['text'] ?? '');
        $question = (string) ($item['question'] ?? '');
        $details = (string) ($item['details'] ?? '');
        ?>
        <details class="theme-card theme-card--<?php echo esc_attr($variants[$index % count($variants)]); ?>" data-reveal="<?php echo esc_attr((string) ($index + 1)); ?>">
          <summary>
            <span><?php echo esc_html($number); ?></span>
            <h3><?php echo esc_html($title); ?></h3>
            <p><?php echo wp_kses_post($text); ?></p>
            <?php if ($question !== '' || $details !== '') : ?><span class="theme-card-toggle"><?php esc_html_e('Explore theme', 'ceeducon-program'); ?></span><?php endif; ?>
          </summary>
          <?php if ($question !== '' || $details !== '') : ?>
            <div class="theme-card-more">
              <?php if ($question !== '') : ?><p class="theme-card-question"><?php echo wp_kses_post($question); ?></p><?php endif; ?>
              <?php if ($details !== '') : ?><p><?php echo wp_kses_post($details); ?></p><?php endif; ?>
            </div>
          <?php endif; ?>
        </details>
        <?php
    }
}

function ceeducon_render_section(string $section, array $attributes = []): string
{
    static $rendered_singletons = [];

    $allowed = [
        'hero',
        'page-hero',
        'text-section',
        'image-text',
        'cards',
        'testimonials',
        'faq',
        'cta',
        'contact',
        'posts',
        'programme-grid',
        'photo-gallery',
        'video',
        'themes',
        'schedule-overview',
        'venue',
        'partners',
    ];

    if (!in_array($section, $allowed, true)) {
        return '';
    }

    $template = get_template_directory() . '/src/blocks/' . $section . '/render.php';
    if (!is_readable($template)) {
        return '';
    }

    /**
     * Annual conference data can be injected here once for Gutenberg,
     * Elementor and direct PHP rendering without duplicating templates.
     */
    $attributes = (array) apply_filters('ceeducon_section_attributes', $attributes, $section);
    if (function_exists('ceeducon_expand_content_tokens_recursive')) {
        $attributes = (array) ceeducon_expand_content_tokens_recursive($attributes);
    }

    // The interactive programme owns page-level IDs and can only be mounted once.
    if ($section === 'programme-grid') {
        if (!empty($rendered_singletons[$section])) {
            return '';
        }
        $rendered_singletons[$section] = true;
    }

    ob_start();
    include $template;
    return (string) ob_get_clean();
}

function ceeducon_print_section(string $section, array $attributes = []): void
{
    echo ceeducon_render_section($section, $attributes); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
