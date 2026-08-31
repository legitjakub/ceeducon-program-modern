<?php
/**
 * CEEDUCON 2026 theme setup.
 *
 * Multi-page conference theme with native Gutenberg section blocks.
 * Legacy template defaults remain as fallbacks, while client-editable
 * page content is stored in block attributes in the WordPress editor.
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/inc/section-renderers.php';

function ceeducon_theme_setup(): void
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    add_theme_support('wp-block-styles');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
        'navigation-widgets',
    ]);
    add_editor_style(['css/styles.css', 'css/editor-style.css']);

    register_nav_menus([
        'primary' => __('Primary navigation', 'ceeducon-program'),
    ]);

    add_image_size('ceeducon-gallery', 900, 900, true);
}
add_action('after_setup_theme', 'ceeducon_theme_setup');

function ceeducon_allowed_inline_html(): array
{
    return [
        'br' => [],
        'strong' => [],
        'em' => [],
        'span' => [
            'class' => true,
        ],
        'a' => [
            'href' => true,
            'target' => true,
            'rel' => true,
        ],
    ];
}

function ceeducon_block_value(array $attributes, string $key, $default = '')
{
    if (!array_key_exists($key, $attributes) || $attributes[$key] === null || $attributes[$key] === '') {
        return $default;
    }

    return $attributes[$key];
}

function ceeducon_block_html(array $attributes, string $key, string $default = ''): string
{
    return wp_kses((string) ceeducon_block_value($attributes, $key, $default), ceeducon_allowed_inline_html());
}

function ceeducon_block_text(array $attributes, string $key, string $default = ''): string
{
    return esc_html((string) ceeducon_block_value($attributes, $key, $default));
}

function ceeducon_block_url(array $attributes, string $key, string $default = ''): string
{
    return esc_url((string) ceeducon_block_value($attributes, $key, $default));
}

function ceeducon_block_array(array $attributes, string $key, array $default = []): array
{
    $value = ceeducon_block_value($attributes, $key, $default);
    return is_array($value) ? $value : $default;
}

function ceeducon_render_block_button(string $text, string $url, string $class = 'btn btn--primary'): string
{
    if ($text === '' || $url === '') {
        return '';
    }

    $class_attr = $class !== '' ? ' class="' . esc_attr($class) . '"' : '';

    return '<a' . $class_attr . ' href="' . esc_url($url) . '">' . esc_html($text) . '</a>';
}

function ceeducon_page_has_section_blocks(?WP_Post $post = null): bool
{
    $post = $post ?: get_post();
    if (!$post instanceof WP_Post || trim((string) $post->post_content) === '') {
        return false;
    }

    return str_contains((string) $post->post_content, '<!-- wp:ceeducon/');
}

function ceeducon_render_block_page_content(): bool
{
    if (!is_singular()) {
        return false;
    }

    $post = get_post();
    if (!$post instanceof WP_Post || !ceeducon_page_has_section_blocks($post)) {
        return false;
    }

    echo '<main id="main" class="ceeducon-block-page">';
    echo apply_filters('the_content', $post->post_content);
    echo '</main>';

    return true;
}

function ceeducon_asset_url(string $path): string
{
    return get_template_directory_uri() . '/' . ltrim($path, '/');
}

function ceeducon_text_value(string $key, string $default): string
{
    $content = get_option('ceeducon_content', []);
    if (is_array($content) && array_key_exists($key, $content) && $content[$key] !== '') {
        $value = (string) $content[$key];
    } else {
        $value = (string) get_theme_mod('ceeducon_' . $key, $default);
    }

    /**
     * Lets a small companion plugin centrally own values that change with
     * every conference edition while the theme continues to own the layout.
     */
    $value = (string) apply_filters('ceeducon_text_value', $value, $key, $default);
    return ceeducon_expand_content_tokens($value);
}

function ceeducon_content_tokens(): array
{
    return (array) apply_filters('ceeducon_content_tokens', [
        '{{event_title}}' => 'CEEDUCON 2026',
        '{{year}}' => '2026',
        '{{date}}' => '1–2 December 2026',
        '{{date_short}}' => '1–2 December',
        '{{venue}}' => 'O2 universum Prague',
        '{{city}}' => 'Prague',
        '{{fee}}' => 'Free of charge',
        // Same value with a lowercase first letter, for the sentences that use
        // it mid-clause — "participation is Free of charge" read as a typo.
        '{{fee_lower}}' => 'free of charge',
        '{{registration}}' => 'Registration is open',
    ]);
}

function ceeducon_expand_content_tokens(string $value): string
{
    return strtr($value, ceeducon_content_tokens());
}

function ceeducon_expand_content_tokens_recursive($value)
{
    if (is_string($value)) {
        return ceeducon_expand_content_tokens($value);
    }
    if (!is_array($value)) {
        return $value;
    }
    foreach ($value as $key => $item) {
        $value[$key] = ceeducon_expand_content_tokens_recursive($item);
    }
    return $value;
}

function ceeducon_text(string $key, string $default): void
{
    echo esc_html(ceeducon_text_value($key, $default));
}

/**
 * Same override chain as ceeducon_text(), escaped for the contexts the
 * templates actually use these in: an href/src, and a plain attribute.
 */
function ceeducon_url(string $key, string $default): void
{
    echo esc_url(ceeducon_text_value($key, $default));
}

function ceeducon_attr(string $key, string $default): void
{
    echo esc_attr(ceeducon_text_value($key, $default));
}

function ceeducon_html(string $key, string $default): void
{
    echo wp_kses_post(ceeducon_text_value($key, $default));
}

/**
 * Detect plugins that already own page metadata so the theme never emits
 * duplicate descriptions or social cards.
 */
function ceeducon_has_seo_plugin(): bool
{
    $detected = defined('WPSEO_VERSION')
        || defined('RANK_MATH_VERSION')
        || defined('SEOPRESS_VERSION')
        || defined('AIOSEO_VERSION')
        || class_exists('The_SEO_Framework\\Load');

    return (bool) apply_filters('ceeducon_has_seo_plugin', $detected);
}

function ceeducon_seo_description(): string
{
    if (is_singular() && has_excerpt()) {
        return wp_strip_all_tags((string) get_the_excerpt());
    }

    $defaults = [
        'home' => 'CEEDUCON 2026 — Central European Conference on Internationalisation of Higher Education. 1–2 December 2026, O2 universum Prague.',
        'about' => 'About CEEDUCON, the Central European Conference on Internationalisation of Higher Education, organised by DZS with partners across the region.',
        'programme' => 'Browse the interactive CEEDUCON 2026 programme for 1–2 December at O2 universum Prague: sessions, workshops, rooms and speakers.',
        'practical' => 'Practical information for CEEDUCON 2026: venue, transport, accessibility and accommodation in Prague.',
        'speakers' => 'Practical information, milestones and support for confirmed CEEDUCON 2026 speakers.',
        'media' => 'CEEDUCON 2026 media kit: official visuals, brand assets, press information and media contact.',
        'contact' => 'Contact the CEEDUCON 2026 organising team about registration, programme, speakers, partnerships or media requests.',
    ];

    $slug = is_front_page() ? 'home' : (string) get_post_field('post_name', get_queried_object_id());
    $default = $defaults[$slug] ?? get_bloginfo('description');

    return trim(wp_strip_all_tags(ceeducon_text_value('seo_' . $slug . '_description', $default)));
}

function ceeducon_social_image(): array
{
    if (is_singular() && has_post_thumbnail()) {
        $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
        if (is_array($image)) {
            $alt = (string) get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
            return ceeducon_filter_social_image([
                'url' => (string) $image[0],
                'width' => (int) $image[1],
                'height' => (int) $image[2],
                'alt' => $alt !== '' ? $alt : (string) get_the_title(get_post_thumbnail_id()),
            ]);
        }
    }

    return ceeducon_filter_social_image([
        'url' => ceeducon_asset_url('assets/media/ceeducon-2026-official-banner.png'),
        'width' => 2162,
        'height' => 1067,
        'alt' => __('Official CEEDUCON 2026 conference visual', 'ceeducon-program'),
    ]);
}

function ceeducon_filter_social_image(array $image): array
{
    return (array) apply_filters('ceeducon_social_image', $image);
}

/**
 * Minimal production fallback for installations without an SEO plugin.
 * WordPress core continues to own the title and canonical URL.
 */
function ceeducon_output_seo_fallback(): void
{
    if (is_admin() || is_feed() || is_robots() || (!is_front_page() && !is_singular()) || ceeducon_has_seo_plugin()) {
        return;
    }

    $description = ceeducon_seo_description();
    $title = wp_get_document_title();
    $url = is_singular() ? get_permalink() : home_url('/');
    $image = ceeducon_social_image();

    if ($description !== '') {
        echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
    }
    echo '<meta property="og:type" content="website">' . "\n";
    echo '<meta property="og:locale" content="' . esc_attr(str_replace('-', '_', get_bloginfo('language'))) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";
    echo '<meta property="og:image" content="' . esc_url($image['url']) . '">' . "\n";
    echo '<meta property="og:image:width" content="' . esc_attr((string) $image['width']) . '">' . "\n";
    echo '<meta property="og:image:height" content="' . esc_attr((string) $image['height']) . '">' . "\n";
    echo '<meta property="og:image:alt" content="' . esc_attr($image['alt']) . '">' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url($image['url']) . '">' . "\n";
    echo '<meta name="twitter:image:alt" content="' . esc_attr($image['alt']) . '">' . "\n";
}
add_action('wp_head', 'ceeducon_output_seo_fallback', 5);

function ceeducon_output_event_schema(): void
{
    if (!is_front_page()) {
        return;
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Event',
        '@id' => home_url('/#ceeducon-2026'),
        'name' => 'CEEDUCON 2026 — Central European Conference on Internationalisation of Higher Education',
        'description' => ceeducon_seo_description(),
        'startDate' => '2026-12-01T09:30:00+01:00',
        'endDate' => '2026-12-02T16:00:00+01:00',
        'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
        'eventStatus' => 'https://schema.org/EventScheduled',
        'image' => [ceeducon_asset_url('assets/media/ceeducon-photo-plenary.jpg')],
        'url' => home_url('/'),
        'inLanguage' => 'en',
        'location' => [
            '@type' => 'Place',
            'name' => 'O2 universum',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Českomoravská 2345/17',
                'addressLocality' => 'Praha 9',
                'postalCode' => '190 00',
                'addressCountry' => 'CZ',
            ],
        ],
        'organizer' => [
            '@type' => 'Organization',
            'name' => 'Czech National Agency for International Education and Research (DZS)',
            'url' => 'https://www.dzs.cz/',
        ],
    ];

    $schema = (array) apply_filters('ceeducon_event_schema', $schema);
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'ceeducon_output_event_schema', 20);

/**
 * URL of one of the conference pages (about, programme, practical, speakers, contact).
 * Uses the page with the matching slug when it exists, otherwise a pretty permalink guess.
 */
function ceeducon_page_url(string $slug): string
{
    if ($slug === '' || $slug === 'home') {
        return home_url('/');
    }

    $page = get_page_by_path($slug);
    if ($page instanceof WP_Post) {
        return (string) get_permalink($page);
    }

    return home_url('/' . $slug . '/');
}

function ceeducon_is_current(string $slug): bool
{
    if ($slug === 'home') {
        return is_front_page();
    }

    return is_page($slug) || is_page_template('page-' . $slug . '.php');
}

function ceeducon_nav_items(): array
{
    return [
        'home' => __('Home', 'ceeducon-program'),
        'about' => __('About', 'ceeducon-program'),
        'programme' => __('Programme', 'ceeducon-program'),
        'practical' => __('Practical', 'ceeducon-program'),
        'speakers' => __('For speakers', 'ceeducon-program'),
        'media' => __('Media kit', 'ceeducon-program'),
        'contact' => __('Contact', 'ceeducon-program'),
    ];
}

class CEEDUCON_Anchor_Walker extends Walker_Nav_Menu
{
    public function start_lvl(&$output, $depth = 0, $args = null)
    {
    }

    public function end_lvl(&$output, $depth = 0, $args = null)
    {
    }

    public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0)
    {
        $item = $data_object;
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $active_classes = ['current-menu-item', 'current_page_item', 'current-menu-ancestor', 'current_page_ancestor'];
        $is_active = (bool) array_intersect($active_classes, $classes);
        // aria-current is what a screen reader announces; the class only paints.
        $link_classes = $is_active ? ' class="is-active" aria-current="page"' : '';
        $target = !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $rel_value = !empty($item->xfn) ? (string) $item->xfn : '';
        if ($item->target === '_blank' && stripos($rel_value, 'noopener') === false) {
            $rel_value = trim($rel_value . ' noopener noreferrer');
        }
        $rel = $rel_value !== '' ? ' rel="' . esc_attr($rel_value) . '"' : '';
        $title = apply_filters('the_title', $item->title, $item->ID);
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

        $output .= '<a' . $link_classes . ' href="' . esc_url($item->url) . '"' . $target . $rel . '>' . esc_html($title) . '</a>';
    }

    public function end_el(&$output, $data_object, $depth = 0, $args = null)
    {
    }
}

function ceeducon_nav_attributes(array $attributes): string
{
    $output = '';

    foreach ($attributes as $name => $value) {
        if ($value === false || $value === null) {
            continue;
        }

        if ($value === true || $value === '') {
            $output .= ' ' . esc_attr($name);
            continue;
        }

        $output .= ' ' . esc_attr($name) . '="' . esc_attr((string) $value) . '"';
    }

    return $output;
}

/**
 * The mobile menu is a full-screen overlay, not a copy of the desktop bar: rows
 * are numbered and chevroned, the two primary journeys sit below them as
 * buttons, and the close control repeats at thumb height. Rendered separately
 * so that markup can differ from ceeducon_render_navigation().
 */
function ceeducon_render_mobile_navigation(): void
{
    $items = [];

    if (has_nav_menu('primary')) {
        $menu = wp_nav_menu([
            'theme_location' => 'primary',
            'container' => false,
            'items_wrap' => '%3$s',
            'depth' => 1,
            'echo' => false,
            'fallback_cb' => '__return_empty_string',
            'walker' => new CEEDUCON_Anchor_Walker(),
        ]);
        // Reuse the walker's anchors and only wrap them with the index column.
        if (is_string($menu) && preg_match_all('#<a\b[^>]*>.*?</a>#s', $menu, $m)) {
            $items = $m[0];
        }
    }

    if (!$items) {
        foreach (ceeducon_nav_items() as $slug => $label) {
            $items[] = '<a' . (ceeducon_is_current($slug) ? ' class="is-active" aria-current="page"' : '')
                . ' href="' . esc_url(ceeducon_page_url($slug)) . '">' . esc_html($label) . '</a>';
        }
    }

    echo '<nav class="mobile-menu" id="mobile-menu" data-mobile-menu aria-label="'
        . esc_attr__('Mobile navigation', 'ceeducon-program') . '" hidden>';
    echo '<div class="mobile-menu-inner shell"><ul class="mobile-menu-list">';
    foreach ($items as $i => $anchor) {
        $index = sprintf('<span class="mobile-menu-index">%02d</span>', $i + 1);
        // Insert the index and wrap the label, keeping the anchor's own attributes.
        $row = preg_replace('#(<a\b[^>]*>)(.*?)(</a>)#s', '$1' . $index . '<span>$2</span>$3', $anchor, 1);
        echo '<li>' . $row . '</li>';
    }
    echo '</ul>';
    echo '<div class="mobile-menu-actions">';
    echo ceeducon_render_block_button(
        (string) ceeducon_text_value('nav_cta_primary', __('Explore programme', 'ceeducon-program')),
        ceeducon_page_url('programme'),
        'btn btn--primary'
    );
    echo ceeducon_render_block_button(
        (string) ceeducon_text_value('nav_cta_secondary', __('Plan your visit', 'ceeducon-program')),
        ceeducon_page_url('practical'),
        'btn btn--ghost'
    );
    echo '</div>';
    echo '</div>';
    echo '<button class="mobile-menu-close" type="button" data-menu-close aria-label="'
        . esc_attr__('Close menu', 'ceeducon-program')
        . '"><svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-linecap="round">'
        . '<path d="M6 6l12 12M18 6L6 18"/></svg></button>';
    echo '</nav>';
}

function ceeducon_render_navigation(string $class, string $label, array $attributes = []): void
{
    echo '<nav class="' . esc_attr($class) . '" aria-label="' . esc_attr($label) . '"' . ceeducon_nav_attributes($attributes) . '>';

    if (has_nav_menu('primary')) {
        wp_nav_menu([
            'theme_location' => 'primary',
            'container' => false,
            'items_wrap' => '%3$s',
            'depth' => 1,
            'fallback_cb' => '__return_empty_string',
            'walker' => new CEEDUCON_Anchor_Walker(),
        ]);
    } else {
        foreach (ceeducon_nav_items() as $slug => $item_label) {
            echo '<a' . (ceeducon_is_current($slug) ? ' class="is-active"' : '') . ' href="' . esc_url(ceeducon_page_url($slug)) . '">' . esc_html($item_label) . '</a>';
        }
    }

    echo '</nav>';
}

function ceeducon_is_programme_page(): bool
{
    return is_page('programme') || is_page_template('page-programme.php');
}

function ceeducon_current_page_has_block(string $block_name): bool
{
    if (!is_singular()) {
        return false;
    }

    $post = get_post();
    return $post instanceof WP_Post && has_block($block_name, $post);
}

function ceeducon_current_page_has_shortcode(string $shortcode): bool
{
    if (!is_singular()) {
        return false;
    }

    $post = get_post();
    return $post instanceof WP_Post && has_shortcode((string) $post->post_content, $shortcode);
}

function ceeducon_should_render_programme_ui(): bool
{
    return ceeducon_is_programme_page()
        || ceeducon_current_page_has_block('ceeducon/programme-grid')
        || ceeducon_current_page_has_shortcode('ceeducon_programme')
        || ceeducon_programme_ui_requested();
}

function ceeducon_programme_ui_requested(bool $request = false): bool
{
    static $requested = false;
    if ($request) {
        $requested = true;
    }
    return $requested;
}

function ceeducon_default_programme_json(): string
{
    $path = get_template_directory() . '/data/program.json';
    if (!is_readable($path)) {
        return '';
    }

    $json = file_get_contents($path);
    return is_string($json) ? $json : '';
}

function ceeducon_programme_data(): array
{
    $programme_json = ceeducon_text_value('programme_json', '');
    $programme_data = $programme_json !== '' ? json_decode($programme_json, true) : null;
    if (is_array($programme_data)) {
        return $programme_data;
    }

    $default_json = ceeducon_default_programme_json();
    $default_data = $default_json !== '' ? json_decode($default_json, true) : null;
    return is_array($default_data) ? $default_data : [];
}

function ceeducon_render_programme_seo_fallback(): void
{
    $data = ceeducon_programme_data();
    if (empty($data['days']) || !is_array($data['days'])) {
        return;
    }
    ?>
    <details class="programme-fallback" data-reveal>
      <summary><?php esc_html_e('Text version of the programme', 'ceeducon-program'); ?></summary>
      <div class="programme-fallback-days">
        <?php foreach ($data['days'] as $day) : ?>
          <?php if (empty($day['slots']) || !is_array($day['slots'])) { continue; } ?>
          <section>
            <h3><?php echo esc_html(trim(($day['label'] ?? '') . ' · ' . ($day['title'] ?? ''))); ?></h3>
            <ul>
              <?php foreach ($day['slots'] as $slot) : ?>
                <?php if (empty($slot['sessions']) || !is_array($slot['sessions'])) { continue; } ?>
                <?php foreach ($slot['sessions'] as $session) : ?>
                  <li>
                    <time><?php echo esc_html(($slot['start'] ?? '') . '–' . ($slot['end'] ?? '')); ?></time>
                    <strong><?php echo esc_html($session['title'] ?? ''); ?></strong>
                    <?php if (!empty($session['rooms']) && is_array($session['rooms'])) : ?>
                      <span><?php echo esc_html(implode(' + ', $session['rooms'])); ?></span>
                    <?php endif; ?>
                  </li>
                <?php endforeach; ?>
              <?php endforeach; ?>
            </ul>
          </section>
        <?php endforeach; ?>
      </div>
    </details>
    <?php
}

function ceeducon_render_editor_content(): void
{
    if (!is_singular()) {
        return;
    }

    $post = get_post();
    if (!$post instanceof WP_Post) {
        return;
    }

    $content = get_the_content(null, false, $post);
    if (trim($content) === '') {
        return;
    }

    // The raw content can be non-empty and still render to nothing — an empty
    // paragraph block or a leftover block comment is enough. Test what would
    // actually reach the page, otherwise an empty section is printed.
    $rendered = (string) apply_filters('the_content', $content);
    if (trim(wp_strip_all_tags($rendered)) === '' && !preg_match('/<(img|iframe|video|audio|svg|hr|table)\b/i', $rendered)) {
        return;
    }

    echo '<section class="section section--editor-content">';
    echo '<div class="shell wp-content">';
    echo $rendered; // phpcs:ignore WordPress.Security.EscapeOutput -- already filtered by the_content
    echo '</div>';
    echo '</section>';
}

function ceeducon_theme_scripts(): void
{
    $theme = wp_get_theme();
    $version = $theme->get('Version');

    wp_enqueue_style(
        'ceeducon-program',
        ceeducon_asset_url('css/styles.css'),
        [],
        $version
    );

    wp_enqueue_script(
        'ceeducon-site',
        ceeducon_asset_url('js/site.js'),
        [],
        $version,
        true
    );

    wp_add_inline_script(
        'ceeducon-site',
        'window.CEEDUCON_DATA_URL = ' . wp_json_encode(ceeducon_asset_url('data/program.json')) . ';',
        'before'
    );

    if (!ceeducon_should_render_programme_ui()) {
        return;
    }

    ceeducon_enqueue_programme_assets();
}
add_action('wp_enqueue_scripts', 'ceeducon_theme_scripts');

function ceeducon_enqueue_programme_assets(): void
{
    static $configured = false;

    ceeducon_programme_ui_requested(true);

    $theme = wp_get_theme();
    $version = $theme->get('Version');

    $programme_data = ceeducon_programme_data();

    wp_enqueue_script(
        'ceeducon-program-data',
        ceeducon_asset_url('js/program-data.js'),
        [],
        $version,
        true
    );

    wp_enqueue_script(
        'ceeducon-program',
        ceeducon_asset_url('js/program.js'),
        ['ceeducon-program-data'],
        $version,
        true
    );

    if (!$configured) {
        if (!empty($programme_data)) {
            wp_add_inline_script(
                'ceeducon-program-data',
                'window.CEEDUCON_PROGRAM_DATA = ' . wp_json_encode($programme_data) . '; window.CEEDUCON_PREFER_EMBEDDED_DATA = true;',
                'after'
            );
        }

        $configured = true;
    }
}

function ceeducon_programme_shortcode($atts = []): string
{
    $atts = is_array($atts) ? $atts : [];
    $attributes = shortcode_atts([
        'kicker' => __('Interactive programme', 'ceeducon-program'),
        'title'  => __('Find the right session faster.', 'ceeducon-program'),
        'intro'  => __('Search the programme, compare rooms and times, filter by theme and keep your personal selection in one place.', 'ceeducon-program'),
    ], $atts, 'ceeducon_programme');

    ceeducon_enqueue_programme_assets();
    return ceeducon_render_section('programme-grid', $attributes);
}
add_shortcode('ceeducon_programme', 'ceeducon_programme_shortcode');

function ceeducon_register_blocks(): void
{
    $blocks_dir = get_template_directory() . '/src/blocks';
    if (!is_dir($blocks_dir)) {
        return;
    }

    foreach (glob($blocks_dir . '/*/block.json') ?: [] as $metadata) {
        register_block_type(dirname($metadata));
    }
}
add_action('init', 'ceeducon_register_blocks');

function ceeducon_block_categories(array $categories): array
{
    array_unshift($categories, [
        'slug' => 'ceeducon-sections',
        'title' => __('Sekce webu', 'ceeducon-program'),
        'icon' => 'layout',
    ]);

    return $categories;
}
add_filter('block_categories_all', 'ceeducon_block_categories');

function ceeducon_allowed_block_types($allowed_block_types, WP_Block_Editor_Context $editor_context)
{
    if (empty($editor_context->post) || $editor_context->post->post_type !== 'page') {
        return $allowed_block_types;
    }

    return [
        'ceeducon/hero',
        'ceeducon/page-hero',
        'ceeducon/text-section',
        'ceeducon/image-text',
        'ceeducon/cards',
        'ceeducon/testimonials',
        'ceeducon/faq',
        'ceeducon/cta',
        'ceeducon/contact',
        'ceeducon/posts',
        'ceeducon/programme-grid',
        'ceeducon/photo-gallery',
        'ceeducon/video',
        'core/paragraph',
        'core/heading',
        'core/list',
        'core/image',
        'core/buttons',
        'core/button',
        'core/separator',
        'core/spacer',
    ];
}
add_filter('allowed_block_types_all', 'ceeducon_allowed_block_types', 10, 2);

function ceeducon_block_template_markup(string $block_name, array $attributes = []): string
{
    if (function_exists('serialize_block')) {
        return serialize_block([
            'blockName' => $block_name,
            'attrs' => $attributes,
            'innerBlocks' => [],
            'innerHTML' => '',
            'innerContent' => [],
        ]);
    }

    $encoded_attributes = empty($attributes) ? '' : ' ' . wp_json_encode($attributes);
    return '<!-- wp:' . esc_html($block_name) . $encoded_attributes . ' /-->';
}

function ceeducon_page_template_content(array $blocks): string
{
    $content = '';

    foreach ($blocks as $block) {
        $content .= ceeducon_block_template_markup($block[0], $block[1] ?? []);
    }

    return $content;
}

function ceeducon_page_block_templates(): array
{
    return [
        'home' => ceeducon_page_template_content([
            ['ceeducon/hero'],
            ['ceeducon/text-section', [
                'kicker' => 'The conference',
                'title' => 'Where the people shaping international higher education come together.',
                'text' => 'CEEDUCON connects university leaders, internationalisation professionals, policymakers, national agencies and practitioners from across Europe to exchange ideas, experiences and solutions.',
                'secondText' => 'From emerging challenges to proven approaches, the programme focuses on what is changing, what works in practice, and what we can achieve through stronger Central European cooperation.',
                'chips' => ['University leadership', 'International offices', 'Policymakers', 'National agencies'],
                'buttonText' => 'More about CEEDUCON',
                'buttonUrl' => '/about/',
            ]],
            ['ceeducon/themes'],
            ['ceeducon/video'],
            ['ceeducon/photo-gallery'],
            ['ceeducon/cards', [
                'kicker' => 'Plan ahead',
                'title' => 'Find the essentials quickly.',
                'items' => [
                    ['label' => 'Practical', 'title' => 'Getting to the conference', 'text' => 'Venue, transport from the airport and stations, accessibility and accommodation tips.', 'url' => '/practical-information/', 'imageUrl' => ceeducon_asset_url('assets/media/ceeducon-photo-registration.jpg'), 'imageAlt' => 'Participants arriving and registering at CEEDUCON'],
                    ['label' => 'For speakers', 'title' => 'Speaking at CEEDUCON', 'text' => 'Session expectations, onsite delivery, timeline and speaker support in one overview.', 'url' => '/for-speakers/', 'imageUrl' => ceeducon_asset_url('assets/media/ceeducon-photo-workshop.jpg'), 'imageAlt' => 'A CEEDUCON speaker leading a workshop'],
                    ['label' => 'Media kit', 'title' => 'Official assets and press information', 'text' => 'Download approved visuals, find press updates and contact the team for media requests.', 'url' => '/media-kit/', 'imageUrl' => ceeducon_asset_url('assets/media/ceeducon-photo-plenary.jpg'), 'imageAlt' => 'A packed CEEDUCON plenary session'],
                ],
            ]],
            ['ceeducon/cta'],
            ['ceeducon/contact'],
        ]),
        'about' => ceeducon_page_template_content([
            ['ceeducon/page-hero', [
                'crumb' => 'About',
                'title' => 'About CEEDUCON.',
                'note' => 'Central European Conference on Internationalisation of Higher Education brings together people shaping international higher education across Europe.',
                'cardLabel' => '{{year}} edition',
                'cardTitle' => '{{date}}',
                'cardText' => 'Conference days at {{venue}}.',
            ]],
            ['ceeducon/text-section', [
                'kicker' => 'The conference',
                'title' => 'A platform for strategic internationalisation.',
                'text' => 'CEEDUCON is designed for university leaders, international office professionals, policymakers, national agencies and practitioners who work with internationalisation every day.',
                'secondText' => 'The conference focuses on practical exchange, institutional strategy, partnerships and student-centred international education.',
                'chips' => ['Strategy', 'Partnerships', 'Mobility', 'Student support'],
            ]],
            ['ceeducon/themes', ['dark' => true]],
            ['ceeducon/cta', [
                'kicker' => 'Next step',
                'title' => 'Continue with the programme.',
                'text' => 'See how the CEEDUCON themes translate into sessions, workshops and plenaries.',
                'primaryText' => 'Open programme',
                'primaryUrl' => '/programme/',
                'secondaryText' => 'Practical information',
                'secondaryUrl' => '/practical-information/',
            ]],
        ]),
        'programme' => ceeducon_page_template_content([
            ['ceeducon/page-hero', [
                'crumb' => 'Programme',
                'title' => 'Programme {{year}}.',
                'note' => 'Browse the preliminary two-day programme by day, room, theme and time. Save sessions to your own programme and export them to your calendar.',
                'cardLabel' => 'Interactive schedule',
                'cardTitle' => '70+ sessions',
                'cardText' => 'Across conference rooms, plenaries and workshops.',
            ]],
            ['ceeducon/programme-grid'],
        ]),
        'practical' => ceeducon_page_template_content([
            ['ceeducon/page-hero', [
                'crumb' => 'Practical',
                'title' => 'Practical information.',
                'note' => 'Everything participants need before travelling to {{city}}: venue, transport, accessibility, accommodation and registration basics.',
                'cardLabel' => 'Venue',
                'cardTitle' => '{{venue}}',
                'cardText' => 'Conference venue in {{city}}.',
            ]],
            ['ceeducon/cards', [
                'kicker' => 'Essentials',
                'title' => 'Plan your visit without digging.',
                'items' => [
                    ['label' => 'Venue', 'title' => '{{venue}}', 'text' => 'A modern multi-room conference venue in {{city}}.', 'url' => 'https://www.o2universum.cz/en'],
                    ['label' => 'Transport', 'title' => 'Easy public transport', 'text' => 'Reach the venue by metro, tram, bus or train. Airport transfer takes around 55 minutes.', 'url' => 'https://pid.cz/en/'],
                    ['label' => 'Access', 'title' => 'Accessible venue', 'text' => 'The venue supports barrier-free access and clear navigation between rooms.', 'url' => 'https://www.o2universum.cz/en'],
                ],
            ]],
            ['ceeducon/faq', [
                'kicker' => 'Good to know',
                'title' => 'Frequently asked questions.',
                'items' => [
                    ['question' => 'Is there a conference fee?', 'answer' => 'Participation at {{event_title}} is {{fee_lower}} for registered attendees.'],
                    ['question' => 'How do I register?', 'answer' => '{{registration}}. Places are allocated on a first-come, first-served basis, so register early to secure yours.'],
                    ['question' => 'Where does the conference take place?', 'answer' => '{{event_title}} takes place at {{venue}}.'],
                ],
            ]],
            ['ceeducon/cta', [
                'title' => 'Need help planning your visit?',
                'text' => 'Contact the CEEDUCON team for practical questions about the venue, registration or accessibility.',
                'primaryText' => 'Contact the team',
                'primaryUrl' => '/contact/',
                'secondaryText' => 'Open programme',
                'secondaryUrl' => '/programme/',
            ]],
        ]),
        'speakers' => ceeducon_page_template_content([
            ['ceeducon/page-hero', [
                'crumb' => 'For speakers',
                'title' => 'For speakers.',
                'note' => 'Practical information about formats, milestones and support for confirmed CEEDUCON speakers.',
                'cardLabel' => 'Speaker checklist',
                'cardTitle' => 'Before conference day',
                'cardText' => "Check your session details, complete speaker registration and submit final presentation materials according to the organisers' instructions.",
            ]],
            ['ceeducon/text-section', [
                'kicker' => 'Speaking at CEEDUCON',
                'title' => 'Sessions should be practical, focused and useful for participants.',
                'text' => 'CEEDUCON sessions are designed for professionals working with internationalisation of higher education. The strongest contributions combine strategic context with transferable practice.',
                'secondText' => 'Speaker information can be updated here directly in Gutenberg blocks without changing the theme code.',
                'chips' => ['Plenary', 'Workshop', 'Panel', 'Case study'],
            ]],
            ['ceeducon/cards', [
                'kicker' => 'Timeline',
                'title' => 'Speaker’s timeline.',
                'items' => [
                    ['label' => 'By June 30', 'title' => 'Acceptance notifications', 'text' => ''],
                    ['label' => 'By July 31', 'title' => 'Speaker registration & photo', 'text' => ''],
                    ['label' => 'September', 'title' => 'Contracts & presentation template', 'text' => ''],
                    ['label' => 'Before the conference', 'title' => 'Final materials and technical check', 'text' => ''],
                    ['label' => 'At the conference / online', 'title' => 'Sign the reimbursement contract', 'text' => ''],
                    ['label' => 'By 9 December 2026', 'title' => 'Send us the necessary documents/receipts for the reimbursement', 'text' => ''],
                ],
            ]],
            ['ceeducon/cta', [
                'title' => 'Questions about your session?',
                'text' => 'The CEEDUCON team can help with programme, logistics and technical preparation.',
                'primaryText' => 'Contact CEEDUCON',
                'primaryUrl' => '/contact/',
                'secondaryText' => 'Practical info',
                'secondaryUrl' => '/practical-information/',
            ]],
        ]),
        'contact' => ceeducon_page_template_content([
            ['ceeducon/page-hero', [
                'crumb' => 'Contact',
                'title' => 'Contact the CEEDUCON team.',
                'note' => 'Use this page for questions about registration, programme, speakers, partnerships and practical arrangements.',
                'cardLabel' => 'Email',
                'cardTitle' => 'ceeducon@dzs.cz',
                'cardText' => 'The organising team will direct your question to the right person.',
            ]],
            ['ceeducon/contact'],
            ['ceeducon/cards', [
                'kicker' => 'Quick routing',
                'title' => 'Find the right topic faster.',
                'items' => [
                    ['label' => 'Programme', 'title' => 'Sessions and schedule', 'text' => 'Questions about programme structure, sessions or speakers.', 'url' => '/programme/'],
                    ['label' => 'Practical', 'title' => 'Venue and travel', 'text' => 'Questions about {{venue}}, transport and onsite access.', 'url' => '/practical-information/'],
                    ['label' => 'For speakers', 'title' => 'Speaker information', 'text' => 'Questions about speaking, materials or session delivery.', 'url' => '/for-speakers/'],
                ],
            ]],
        ]),
        'media' => ceeducon_page_template_content([
            ['ceeducon/page-hero', [
                'crumb' => 'Media kit',
                'title' => 'Media resources.',
                'note' => 'Official {{event_title}} visual assets, press information and a direct contact for journalists and partner organisations.',
                'cardLabel' => 'Media contact',
                'cardTitle' => 'ceeducon@dzs.cz',
                'cardText' => 'Please include your outlet, deadline and the material you need.',
            ]],
            ['ceeducon/image-text', [
                'kicker' => 'Official visual',
                'title' => '{{event_title}} banner.',
                'text' => 'Use the official conference visual for editorial coverage and partner communication. Please preserve its proportions and colours.',
                'imageUrl' => '/wp-content/themes/ceeducon-program/assets/media/ceeducon-2026-official-banner.png',
                'imageAlt' => 'Official {{event_title}} conference banner',
                'primaryText' => 'Download banner',
                'primaryUrl' => '/wp-content/themes/ceeducon-program/assets/media/ceeducon-2026-official-banner.png',
                'secondaryText' => '',
                'secondaryUrl' => '',
            ]],
            ['ceeducon/cards', [
                'kicker' => 'Downloads',
                'title' => 'Ready-to-use media assets.',
                'items' => [
                    ['label' => 'Visual', 'title' => 'Conference banner', 'text' => 'Official {{event_title}} key visual for digital publication.', 'url' => '/wp-content/themes/ceeducon-program/assets/media/ceeducon-2026-official-banner.png'],
                    ['label' => 'Logo', 'title' => 'CEEDUCON logo', 'text' => 'Horizontal conference logo for dark backgrounds.', 'url' => '/wp-content/themes/ceeducon-program/assets/ceeducon-logo-horizontal-white.png'],
                    ['label' => 'Partners', 'title' => 'Partner logo row', 'text' => 'The official row of CEEDUCON partner and organiser logos.', 'url' => '/wp-content/themes/ceeducon-program/assets/media/ceeducon-partner-logos-white.png'],
                ],
            ]],
            ['ceeducon/text-section', [
                'kicker' => 'Press releases',
                'title' => 'News for media and partners.',
                'text' => 'Press releases and approved announcements can be added here as they are published. Each item remains normal HTML content that can be indexed and linked.',
                'buttonText' => 'Contact the media team',
                'buttonUrl' => 'mailto:ceeducon@dzs.cz',
            ]],
        ]),
    ];
}

function ceeducon_register_block_patterns(): void
{
    if (!function_exists('register_block_pattern_category') || !function_exists('register_block_pattern')) {
        return;
    }

    register_block_pattern_category('ceeducon-pages', [
        'label' => __('CEEDUCON stránky', 'ceeducon-program'),
    ]);

    $patterns = [
        'home' => __('CEEDUCON homepage', 'ceeducon-program'),
        'about' => __('CEEDUCON about page', 'ceeducon-program'),
        'programme' => __('CEEDUCON programme page', 'ceeducon-program'),
        'practical' => __('CEEDUCON practical page', 'ceeducon-program'),
        'speakers' => __('CEEDUCON for speakers page', 'ceeducon-program'),
        'contact' => __('CEEDUCON contact page', 'ceeducon-program'),
        'media' => __('CEEDUCON media kit page', 'ceeducon-program'),
    ];

    foreach ($patterns as $key => $title) {
        register_block_pattern('ceeducon-program/' . $key, [
            'title' => $title,
            'categories' => ['ceeducon-pages'],
            'content' => ceeducon_page_block_templates()[$key] ?? '',
        ]);
    }
}
add_action('init', 'ceeducon_register_block_patterns');

function ceeducon_enqueue_editor_page_templates(): void
{
    $asset_path = get_template_directory() . '/js/editor-page-templates.js';
    if (!is_readable($asset_path)) {
        return;
    }

    wp_enqueue_script(
        'ceeducon-editor-page-templates',
        ceeducon_asset_url('js/editor-page-templates.js'),
        ['wp-blocks', 'wp-data', 'wp-dom-ready', 'wp-notices'],
        filemtime($asset_path),
        true
    );

    wp_add_inline_script(
        'ceeducon-editor-page-templates',
        'window.CEEDUCON_THEME_URL = ' . wp_json_encode(get_template_directory_uri()) . ';' .
        'window.CEEDUCON_PAGE_BLOCK_TEMPLATES = ' . wp_json_encode(ceeducon_page_block_templates()) . ';' .
        'window.CEEDUCON_HERO_EDITOR_DATA = ' . wp_json_encode((array) apply_filters('ceeducon_hero_editor_data', ['managed' => false, 'attributes' => []])) . ';',
        'before'
    );
}
add_action('enqueue_block_editor_assets', 'ceeducon_enqueue_editor_page_templates');

/**
 * Editable content fields, grouped for the admin screen.
 * [key, label, default, type]
 */
function ceeducon_admin_content_fields(): array
{
    $fields = [
        'SEO fallback (used only without an SEO plugin)' => [
            ['seo_home_description', 'Homepage meta description', '{{event_title}} — Central European Conference on Internationalisation of Higher Education. {{date}}, {{venue}}.', 'textarea'],
            ['seo_about_description', 'About meta description', 'About CEEDUCON, the Central European Conference on Internationalisation of Higher Education, organised by DZS with partners across the region.', 'textarea'],
            ['seo_programme_description', 'Programme meta description', 'Browse the interactive {{event_title}} programme for {{date_short}} at {{venue}}: sessions, workshops, rooms and speakers.', 'textarea'],
            ['seo_practical_description', 'Practical meta description', 'Practical information for {{event_title}}: venue, transport, accessibility and accommodation in {{city}}.', 'textarea'],
            ['seo_speakers_description', 'For speakers meta description', 'Practical information, milestones and support for confirmed {{event_title}} speakers.', 'textarea'],
            ['seo_media_description', 'Media kit meta description', '{{event_title}} media kit: official visuals, brand assets, press information and media contact.', 'textarea'],
            ['seo_contact_description', 'Contact meta description', 'Contact the {{event_title}} organising team about registration, programme, speakers, partnerships or media requests.', 'textarea'],
        ],
        'Global — header & footer' => [
            ['footer_tagline', 'Footer tagline (HTML allowed)', 'Central European Conference on Internationalisation of Higher Education.<br />{{date}} · {{venue}}', 'textarea'],
            ['footer_email', 'Footer email', 'ceeducon@dzs.cz', 'text'],
            ['footer_phone', 'Footer phone', '+420 221 850 100', 'text'],
            ['footer_official_url', 'Official website URL', 'https://www.ceeducon.cz/', 'url'],
            ['footer_dzs_url', 'DZS URL', 'https://www.dzs.cz/', 'url'],
            ['footer_copyright', 'Copyright line', '© {{year}} DZS — Czech National Agency for International Education and Research', 'text'],
        ],
        'Home — hero' => [
            ['home_hero_kicker', 'Kicker', '{{event_title}} · CZECHIA', 'text'],
            ['home_hero_title', 'Title (HTML allowed)', 'Central Europe <em>meets the world</em> of higher education.', 'textarea'],
            ['home_hero_lead', 'Lead', 'Two days of practical exchange for university leaders, international offices, policymakers and national agencies.', 'textarea'],
            ['home_hero_image_url', 'Hero image URL', ceeducon_asset_url('assets/media/ceeducon-photo-plenary.jpg'), 'url'],
            ['home_hero_image_alt', 'Hero image alt text', 'A packed CEEDUCON plenary session', 'text'],
            ['home_cta_primary', 'Primary button', 'Explore programme', 'text'],
            ['home_cta_secondary', 'Secondary button', 'Plan your visit', 'text'],
            ['event_day', 'Event date', '1–2', 'text'],
            ['event_month', 'Event month / year', 'Dec 2026', 'text'],
            ['event_row_1_label', 'Event row 1 label', 'Venue', 'text'],
            ['event_row_1_value', 'Event row 1 value', '{{venue}}', 'text'],
            ['event_row_3_label', 'Event row 3 label', 'Fee', 'text'],
            ['event_row_3_value', 'Event row 3 value', '{{fee}}', 'text'],
            ['event_row_4_label', 'Event row 4 label', 'Registration', 'text'],
            ['event_row_4_value', 'Event row 4 value', '{{registration}}', 'text'],
            ['event_google_calendar_label', 'Google Calendar button', 'Google Calendar', 'text'],
            ['event_google_calendar_url', 'Google Calendar URL', 'https://calendar.google.com/calendar/render?action=TEMPLATE&text=CEEDUCON%202026&dates=20261201T080000Z%2F20261202T170000Z&details=Central%20European%20Conference%20on%20Internationalisation%20of%20Higher%20Education.&location=O2%20universum%2C%20Ceskomoravska%2017%2C%20Prague%209%2C%20Czech%20Republic&ctz=Europe%2FPrague', 'url'],
            ['event_outlook_calendar_label', 'Outlook Calendar button', 'Outlook Calendar', 'text'],
            ['event_outlook_calendar_url', 'Outlook Calendar URL', 'https://outlook.live.com/calendar/0/deeplink/compose?path=%2Fcalendar%2Faction%2Fcompose&rru=addevent&subject=CEEDUCON%202026&startdt=2026-12-01T09%3A00%3A00%2B01%3A00&enddt=2026-12-02T18%3A00%3A00%2B01%3A00&body=Central%20European%20Conference%20on%20Internationalisation%20of%20Higher%20Education.&location=O2%20universum%2C%20Ceskomoravska%2017%2C%20Prague%209%2C%20Czech%20Republic', 'url'],
            ['stat_1_value', 'Stat 1 value', '2', 'text'],
            ['stat_1_label', 'Stat 1 label', 'conference days', 'text'],
            ['stat_2_value', 'Stat 2 value', '900+', 'text'],
            ['stat_2_label', 'Stat 2 label', 'participants in 2025', 'text'],
            ['stat_3_value', 'Stat 3 value', '130+', 'text'],
            ['stat_3_label', 'Stat 3 label', 'speakers in 2025', 'text'],
            ['stat_4_value', 'Stat 4 value', '70+', 'text'],
            ['stat_4_label', 'Stat 4 label', 'sessions in {{year}}', 'text'],
        ],
        'Home — sections' => [
            ['home_about_kicker', 'About kicker', 'The conference', 'text'],
            ['home_about_title', 'About title', 'Where the people shaping international higher education come together.', 'textarea'],
            ['home_about_text_1', 'About paragraph 1', 'CEEDUCON connects university leaders, internationalisation professionals, policymakers, national agencies and practitioners from across Europe to exchange ideas, experiences and solutions.', 'textarea'],
            ['home_about_text_2', 'About paragraph 2', 'From emerging challenges to proven approaches, the programme focuses on what is changing, what works in practice, and what we can achieve through stronger Central European cooperation.', 'textarea'],
            ['home_chip_1', 'Audience chip 1', 'University leadership', 'text'],
            ['home_chip_2', 'Audience chip 2', 'International offices', 'text'],
            ['home_chip_3', 'Audience chip 3', 'Policymakers', 'text'],
            ['home_chip_4', 'Audience chip 4', 'National agencies', 'text'],
            ['home_about_button', 'About button', 'More about CEEDUCON', 'text'],
            ['home_video_kicker', 'Video kicker', 'CEEDUCON in motion', 'text'],
            ['home_video_title', 'Video title', 'See the conference come to life.', 'textarea'],
            ['home_video_text', 'Video text', 'Step inside CEEDUCON and experience the plenaries, practical sessions and conversations that connect the international higher education community.', 'textarea'],
            ['home_video_url', 'YouTube video URL', 'https://www.youtube.com/watch?v=oad5sn8ku1c', 'url'],
            ['home_video_accessible_title', 'Accessible video title', 'CEEDUCON conference video', 'text'],
            ['home_video_button', 'Video link text', 'Watch on YouTube', 'text'],
            ['home_video_caption', 'Video caption', 'Highlights from CEEDUCON', 'text'],
            ['media_kicker', 'Media kicker', 'Conference atmosphere', 'text'],
            ['media_title', 'Media title', 'A professional setting for exchange.', 'textarea'],
            ['media_text', 'Media text', 'CEEDUCON brings the international higher education community together through plenaries, workshops, hallway conversations and practical exchange across the whole venue.', 'textarea'],
            ['media_button_primary', 'Media primary button', 'Browse programme', 'text'],
            ['media_button_secondary', 'Media secondary button', 'Open photo', 'text'],
            ['media_hero_url', 'Media hero image URL', ceeducon_asset_url('assets/media/ceeducon-photo-plenary.jpg'), 'url'],
            ['media_hero_alt', 'Media hero image alt', 'CEEDUCON plenary session with a full audience', 'text'],
            ['media_hero_label', 'Media hero image label', 'Plenary', 'text'],
            ['media_hero_caption', 'Media hero image caption', 'CEEDUCON plenary session', 'text'],
            ['media_image_1_url', 'Media image 1 URL', ceeducon_asset_url('assets/media/ceeducon-photo-networking.jpg'), 'url'],
            ['media_image_1_alt', 'Media image 1 alt', 'CEEDUCON participants talking during a networking break', 'text'],
            ['media_image_1_label', 'Media image 1 label', 'Networking', 'text'],
            ['media_image_1_caption', 'Media image 1 caption', 'CEEDUCON networking moment', 'text'],
            ['media_image_2_url', 'Media image 2 URL', ceeducon_asset_url('assets/media/ceeducon-photo-workshop.jpg'), 'url'],
            ['media_image_2_alt', 'Media image 2 alt', 'CEEDUCON workshop with participants seated around tables', 'text'],
            ['media_image_2_label', 'Media image 2 label', 'Workshops', 'text'],
            ['media_image_2_caption', 'Media image 2 caption', 'CEEDUCON workshop room', 'text'],
            ['media_image_3_url', 'Media image 3 URL', ceeducon_asset_url('assets/media/ceeducon-photo-registration.jpg'), 'url'],
            ['media_image_3_alt', 'Media image 3 alt', 'Participants arriving and registering at CEEDUCON', 'text'],
            ['media_image_3_label', 'Media image 3 label', 'Arrival', 'text'],
            ['media_image_3_caption', 'Media image 3 caption', 'CEEDUCON registration and arrival', 'text'],
            ['media_image_4_url', 'Media image 4 URL', ceeducon_asset_url('assets/media/ceeducon-photo-accessibility.jpg'), 'url'],
            ['media_image_4_alt', 'Media image 4 alt', 'CEEDUCON participants speaking in an accessible lounge area', 'text'],
            ['media_image_4_label', 'Media image 4 label', 'Access', 'text'],
            ['media_image_4_caption', 'Media image 4 caption', 'CEEDUCON inclusive conference atmosphere', 'text'],
            ['home_themes_kicker', 'Themes kicker', 'Thematic areas', 'text'],
            ['home_themes_title', 'Themes title', 'Four themes frame the {{year}} conversation.', 'textarea'],
            ['home_themes_intro', 'Themes intro', 'From responsible technology to the complete student journey — the {{year}} programme connects the questions that matter most to international higher education right now.', 'textarea'],
            ['home_prog_kicker', 'Programme kicker', 'Programme {{year}}', 'text'],
            ['home_prog_title', 'Programme title', 'Two full conference days in {{city}}.', 'textarea'],
            ['home_prog_intro', 'Programme intro', 'The preliminary room-by-room programme is online — sessions, workshops and speakers for both conference days.', 'textarea'],
            ['home_plan_kicker', 'Plan kicker', 'Plan ahead', 'text'],
            ['home_plan_title', 'Plan title', 'Find the essentials quickly.', 'textarea'],
            ['home_link_1_label', 'Quick link 1 label', 'Practical', 'text'],
            ['home_link_1_title', 'Quick link 1 title', 'Getting to the conference', 'text'],
            ['home_link_1_text', 'Quick link 1 text', 'Venue, transport from the airport and stations, accessibility and accommodation tips.', 'textarea'],
            ['home_link_1_image_url', 'Quick link 1 image URL', ceeducon_asset_url('assets/media/ceeducon-photo-registration.jpg'), 'url'],
            ['home_link_1_image_alt', 'Quick link 1 image alt text', 'Participants arriving and registering at CEEDUCON', 'text'],
            ['home_link_2_label', 'Quick link 2 label', 'For speakers', 'text'],
            ['home_link_2_title', 'Quick link 2 title', 'Speaking at CEEDUCON', 'text'],
            ['home_link_2_text', 'Quick link 2 text', 'Session expectations, onsite delivery, timeline and speaker support in one overview.', 'textarea'],
            ['home_link_2_image_url', 'Quick link 2 image URL', ceeducon_asset_url('assets/media/ceeducon-photo-workshop.jpg'), 'url'],
            ['home_link_2_image_alt', 'Quick link 2 image alt text', 'A CEEDUCON speaker leading a workshop', 'text'],
            ['home_link_3_label', 'Quick link 3 label', 'Media kit', 'text'],
            ['home_link_3_title', 'Quick link 3 title', 'Official assets and press information', 'text'],
            ['home_link_3_text', 'Quick link 3 text', 'Download approved visuals, find press updates and contact the team for media requests.', 'textarea'],
            ['home_link_3_image_url', 'Quick link 3 image URL', ceeducon_asset_url('assets/media/ceeducon-photo-plenary.jpg'), 'url'],
            ['home_link_3_image_alt', 'Quick link 3 image alt text', 'A packed CEEDUCON plenary session', 'text'],
            ['home_org_kicker', 'Organisers kicker', 'Organisers', 'text'],
            ['home_org_title', 'Organisers title', "Backed by Central Europe's national agencies.", 'textarea'],
            ['home_org_lead', 'Organisers lead (HTML allowed)', 'CEEDUCON is organised by DZS — the Czech National Agency for International Education and Research — in co-operation with partner organisations across the region. Reach the team at <a href="mailto:ceeducon@dzs.cz">ceeducon@dzs.cz</a>.', 'textarea'],
        ],
        'Thematic areas (shared)' => [
            ['theme_1_title', 'Theme 1 title', 'Navigating the Technological Shift', 'text'],
            ['theme_1_text', 'Theme 1 text', 'AI, digitalisation, data and new tools for smarter internationalisation — with academic values and human judgement in focus.', 'textarea'],
            ['theme_1_question', 'Theme 1 guiding question', 'How can universities use AI, digitalisation and data responsibly while preserving academic values and human judgement?', 'textarea'],
            ['theme_1_details', 'Theme 1 details', 'Key topics include AI in international offices and project work, data-driven strategies and evaluation, responsible data use, digital partnerships and staff skills.', 'textarea'],
            ['theme_2_title', 'Theme 2 title', 'Challenges of Internationalisation', 'text'],
            ['theme_2_text', 'Theme 2 text', 'Funding, safety, wellbeing and inclusion — removing barriers to meaningful international experiences for students and staff.', 'textarea'],
            ['theme_2_question', 'Theme 2 guiding question', 'How can universities remove structural, social and financial barriers while creating safe, inclusive and meaningful international experiences?', 'textarea'],
            ['theme_2_details', 'Theme 2 details', 'Key topics include housing, mobility safety and visas, wellbeing and crisis response, funding, inclusive participation, Green Erasmus and mobility quality.', 'textarea'],
            ['theme_3_title', 'Theme 3 title', 'Global & Regional Partnerships', 'text'],
            ['theme_3_text', 'Theme 3 text', 'Strategic cooperation, European University alliances and equitable partnerships across global regions.', 'textarea'],
            ['theme_3_question', 'Theme 3 guiding question', 'How can universities build sustainable partnerships that balance strategic priorities, mutual benefit and equitable collaboration?', 'textarea'],
            ['theme_3_details', 'Theme 3 details', 'Key topics include European University alliances, regional and global networks, capacity building, Global Gateway, BIPs, joint degrees and more equal partnerships.', 'textarea'],
            ['theme_4_title', 'Theme 4 title', 'From Recruitment to Retention', 'text'],
            ['theme_4_text', 'Theme 4 text', 'The student journey end to end — admissions, support, employability, alumni relations and long-term success.', 'textarea'],
            ['theme_4_question', 'Theme 4 guiding question', 'How can universities connect recruitment, admissions, support and alumni engagement into one coherent student-centred strategy?', 'textarea'],
            ['theme_4_details', 'Theme 4 details', 'Key topics include targeted recruitment, transparent admissions, onboarding, integration and support, retention, employability, alumni relations and stakeholder engagement.', 'textarea'],
        ],
        'Programme days & notices (shared)' => [
            ['day_1_label', 'Day 1 label', 'Day 1 · Tue 1 Dec', 'text'],
            ['day_1_title', 'Day 1 title', 'All-day conference', 'text'],
            ['day_1_text', 'Day 1 text', 'Opening plenary and thematic sessions across the four 2026 themes at O2 universum.', 'textarea'],
            ['day_evening_label', 'Evening label', 'Evening', 'text'],
            ['day_evening_title', 'Evening title', 'Networking reception', 'text'],
            ['day_evening_text', 'Evening text', 'An evening dedicated to informal exchange and new partnerships. Details will follow with the final programme.', 'textarea'],
            ['day_2_label', 'Day 2 label', 'Day 2 · Wed 2 Dec', 'text'],
            ['day_2_title', 'Day 2 title', 'All-day conference', 'text'],
            ['day_2_text', 'Day 2 text', 'A second full day of sessions and workshops, closing with a joint plenary.', 'textarea'],
            ['notice_prog_label', 'Programme notice label', 'Preliminary programme', 'text'],
            ['notice_prog_title', 'Programme notice title', 'Online now.', 'text'],
            ['notice_prog_text', 'Programme notice text', 'Browse the two-day programme — 70+ sessions and workshops across nine rooms. Details remain subject to change.', 'textarea'],
            ['notice_prog_button', 'Programme notice button', 'Open the programme', 'text'],
        ],
        'Organiser & partners (shared)' => [
            ['organiser_label', 'Organiser label', 'Organised by', 'text'],
            ['organiser_name', 'Organiser name', 'Czech National Agency for International Education and Research (DZS)', 'textarea'],
            ['partners_label', 'Partners label', 'In co-operation with', 'text'],
            ['partners_text', 'Partners list', 'OeAD · DAAD · FRSE · SAAIC · Tempus Public Foundation', 'textarea'],
        ],
        'About page' => [
            ['about_hero_title', 'Hero title', 'The conference for internationalisation of higher education.', 'textarea'],
            ['about_hero_note', 'Hero note', "One forum for strategy, practice and cooperation — connecting Central Europe's higher education community with colleagues from around the world.", 'textarea'],
            ['about_card_label', 'Hero card label', '{{event_title}}', 'text'],
            ['about_card_title', 'Hero card title', '{{date_short}} · {{city}}', 'text'],
            ['about_card_text', 'Hero card text', 'Organised by DZS with national agencies from across Central Europe.', 'textarea'],
            ['about_what_kicker', 'Section kicker', 'What is CEEDUCON', 'text'],
            ['about_what_title', 'Section title', 'A practical conference for the people shaping international higher education.', 'textarea'],
            ['about_what_text_1', 'Paragraph 1', 'CEEDUCON focuses on advancing global cooperation, strategy and innovation in higher education. It creates space for knowledge exchange, best practices and practical discussion around internationalisation strategy, digitalisation, inclusion, partnerships, mobility, alumni engagement and employability.', 'textarea'],
            ['about_what_text_2', 'Paragraph 2', 'The conference brings together university leaders, international office professionals, policymakers, national agencies and experts working across higher education — from first-hand practitioners to strategic decision-makers.', 'textarea'],
            ['about_chip_5', 'Audience chip 5', 'Experts & practitioners', 'text'],
            ['about_stat_4_value', 'Stat 4 value', '6', 'text'],
            ['about_stat_4_label', 'Stat 4 label', 'partner agencies', 'text'],
            ['about_themes_title', 'Themes title', 'The {{year}} themes.', 'textarea'],
            ['about_themes_intro', 'Themes intro', 'Four thematic areas structure the sessions, workshops and plenaries of {{event_title}} — connecting technology, inclusion, partnerships and the student journey.', 'textarea'],
            ['about_org_title', 'Organisers title', 'A Central European partnership.', 'textarea'],
            ['about_org_lead', 'Organisers lead', 'CEEDUCON is organised by the Czech National Agency for International Education and Research (DZS) in co-operation with its partner agencies. Together they connect the national perspectives of Austria, Germany, Poland, Slovakia, Hungary and Czechia into one regional conversation.', 'textarea'],
        ],
        'Programme page' => [
            ['prog_hero_title', 'Hero title', 'Two days. One programme.', 'textarea'],
            ['prog_hero_note', 'Hero note', '{{event_title}} runs across the halls of {{venue}} on {{date_short}}. Browse the room-by-room programme below — sessions, workshops and speakers for both days.', 'textarea'],
            ['prog_card_label', 'Hero card label', 'Preliminary programme', 'text'],
            ['prog_card_title', 'Hero card title', 'Online now', 'text'],
            ['prog_card_text', 'Hero card text', '{{registration}} and participation is {{fee_lower}}. The programme remains subject to change.', 'textarea'],
            ['sched_kicker', 'Schedule kicker', 'Interactive programme', 'text'],
            ['sched_title', 'Schedule title', 'Find the right session faster.', 'textarea'],
            ['sched_intro', 'Schedule intro', 'Search the programme, compare rooms and times, filter by theme and keep your personal selection in one place.', 'textarea'],
        ],
        'Practical page' => [
            ['prac_hero_title', 'Hero title', 'Plan your visit to {{city}}.', 'textarea'],
            ['prac_hero_note', 'Hero note', 'Where to go, how to get there and what to prepare — everything participants need before arriving at {{event_title}}.', 'textarea'],
            ['prac_card_label', 'Hero card label', 'Venue', 'text'],
            ['prac_card_title', 'Hero card title', '{{venue}}', 'text'],
            ['prac_card_text', 'Hero card text', 'Conference venue in {{city}} — home of all {{event_title}} conference days.', 'textarea'],
            ['prac_kicker', 'Essentials kicker', 'Essentials', 'text'],
            ['prac_title', 'Essentials title', 'Venue, access and travel basics.', 'textarea'],
            ['prac_intro', 'Essentials intro', 'The conference language is English, participation is free of charge and the venue is fully accessible.', 'textarea'],
            ['info_1_label', 'Card 1 label', 'Venue', 'text'],
            ['info_1_title', 'Card 1 title', '{{venue}}', 'text'],
            ['info_1_text', 'Card 1 text', 'All plenaries, sessions and workshops of {{event_title}} take place at {{venue}}.', 'textarea'],
            ['info_2_label', 'Card 2 label', 'Travel', 'text'],
            ['info_2_title', 'Card 2 title', 'By public transport', 'text'],
            ['info_2_text', 'Card 2 text', 'From the airport around 55 minutes by trolleybus 59 and metro. From the Main Train Station by metro to the Českomoravská station.', 'textarea'],
            ['info_3_label', 'Card 3 label', 'Inclusive', 'text'],
            ['info_3_title', 'Card 3 title', 'Accessible', 'text'],
            ['info_3_text', 'Card 3 text', 'The venue is fully accessible for wheelchair users.', 'textarea'],
            ['faq_kicker', 'FAQ kicker', 'Good to know', 'text'],
            ['faq_title', 'FAQ title', 'Frequently asked questions.', 'textarea'],
            ['faq_1_title', 'FAQ 1 question', 'Is there a conference fee?', 'text'],
            ['faq_1_text', 'FAQ 1 answer', 'Participation at {{event_title}} is {{fee_lower}} for registered attendees. {{registration}}.', 'textarea'],
            ['faq_capacity_title', 'Capacity FAQ question', 'Is there a limit on the number of participants?', 'text'],
            ['faq_capacity_text', 'Capacity FAQ answer', 'Yes. The conference venue has a limited capacity, and registration will be accepted on a first-come, first-served basis. We therefore encourage you to register as early as possible to secure your place. Once the conference reaches its maximum capacity, registration will be closed.', 'textarea'],
            ['faq_2_title', 'FAQ 2 question', 'Where should I stay?', 'text'],
            ['faq_2_text', 'FAQ 2 answer (HTML allowed)', 'Participants are responsible for arranging and covering the cost of their own accommodation. Please note that neither accommodation nor travel expenses can be reimbursed by the conference organizers. Hotels within easy reach of the venue include <a href="https://www.stageshotel.com/" target="_blank" rel="noreferrer">Stages Hotel</a> and <a href="https://www.hotelcarol.cz/en/" target="_blank" rel="noreferrer">Carol Hotel</a>, while Prague city centre is approximately 20 minutes away by metro.', 'textarea'],
            ['faq_3_title', 'FAQ 3 question', 'How do I register?', 'text'],
            ['faq_3_text', 'FAQ 3 answer (HTML allowed)', '{{registration}}. Places are allocated on a first-come, first-served basis, so register early to secure yours.', 'textarea'],
            ['faq_4_title', 'FAQ 4 question', 'Anything to check before travelling?', 'text'],
            ['faq_4_text', 'FAQ 4 answer', 'Check current Prague public transport information before you travel, especially around the Českomoravská metro station, and verify travel requirements for the Czech Republic if you need a visa or an event confirmation from the organisers.', 'textarea'],
            ['map_kicker', 'Map kicker', 'Map & venue', 'text'],
            ['map_title', 'Map title', 'Plan the route before conference day.', 'textarea'],
            ['map_text', 'Map text', 'Check entrances, transport connections and nearby services on the venue website or open the location directly in your maps app.', 'textarea'],
            ['venue_button', 'Venue website button', 'Venue website', 'text'],
            ['venue_url', 'Venue website URL', 'https://www.o2universum.cz/en', 'url'],
            ['venue_map_button', 'Map button', 'Open map', 'text'],
            ['venue_map_url', 'Map URL', 'https://www.google.com/maps/search/?api=1&query=O2%20universum%20Ceskomoravska%2017%20Prague', 'url'],
            ['venue_embed_url', 'Embedded map URL', 'https://www.google.com/maps?q=O2%20universum%20Ceskomoravska%2017%20Prague&output=embed', 'url'],
        ],
        'For speakers page' => [
            ['spk_hero_title', 'Hero title', 'For speakers.', 'textarea'],
            ['spk_hero_note', 'Hero note', 'Practical information about formats, milestones and support for confirmed CEEDUCON speakers.', 'textarea'],
            ['spk_card_label', 'Hero card label', 'Speaker checklist', 'text'],
            ['spk_card_title', 'Hero card title', 'Before conference day', 'text'],
            ['spk_card_text', 'Hero card text', "Check your session details, complete speaker registration and submit final presentation materials according to the organisers' instructions.", 'textarea'],
            ['spk_kicker', 'Section kicker', 'Speaker information', 'text'],
            ['spk_title', 'Section title', 'Clear expectations before conference day.', 'textarea'],
            ['spk_lead', 'Section lead', 'Sessions are planned primarily onsite at {{venue}} and delivered in English. This page keeps the essential speaker information in one place.', 'textarea'],
            ['spk_fact_1', 'Fact 1', 'No speaker fee', 'text'],
            ['spk_fact_2', 'Fact 2', 'Primarily onsite', 'text'],
            ['spk_fact_3', 'Fact 3', 'Up to 3 contributors', 'text'],
            ['spk_fact_4', 'Fact 4', 'English delivery', 'text'],
            ['step_1_title', 'Step 1 title', 'Confirm contributors early', 'text'],
            ['step_1_text', 'Step 1 text', 'List all speakers as early as possible so the programme, contracts and communication can stay accurate.', 'textarea'],
            ['step_2_title', 'Step 2 title', 'Prepare for onsite delivery', 'text'],
            ['step_2_text', 'Step 2 text', 'Plan for an in-person session unless the organisers confirm a different arrangement in advance.', 'textarea'],
            ['step_3_title', 'Step 3 title', 'Share materials on time', 'text'],
            ['step_3_text', 'Step 3 text', 'Follow the speaker timeline for registration, presentation materials, recording preferences and technical checks.', 'textarea'],
            ['spk_cta_text', 'CTA text (HTML allowed)', '<strong>Questions about your session?</strong> Use the contact page for anything not covered here.', 'textarea'],
            ['spk_cta_button', 'CTA button', 'Contact page', 'text'],
            ['spk_cta_url', 'CTA URL', home_url('/contact/'), 'url'],
            ['timeline_kicker', 'Timeline kicker', 'Timeline', 'text'],
            ['timeline_title', 'Timeline title', 'Speaker timeline.', 'textarea'],
            ['milestone_1_label', 'Milestone 1 label', 'By June 30', 'text'],
            ['milestone_1_text', 'Milestone 1 text', 'Acceptance notifications', 'text'],
            ['milestone_2_label', 'Milestone 2 label', 'By July 31', 'text'],
            ['milestone_2_text', 'Milestone 2 text', 'Speaker registration & photo', 'text'],
            ['milestone_3_label', 'Milestone 3 label', 'September', 'text'],
            ['milestone_3_text', 'Milestone 3 text', 'Contracts & presentation template', 'text'],
            ['milestone_4_label', 'Milestone 4 label', 'Before the conference', 'text'],
            ['milestone_4_text', 'Milestone 4 text', 'Final materials and technical check', 'text'],
            ['milestone_5_label', 'Milestone 5 label', 'At the conference / online', 'text'],
            ['milestone_5_text', 'Milestone 5 text', 'Sign the reimbursement contract', 'text'],
            ['milestone_6_label', 'Milestone 6 label', 'By 9 December {{year}}', 'text'],
            ['milestone_6_text', 'Milestone 6 text', 'Send us the necessary documents/receipts for the reimbursement', 'text'],
            ['spk_links_kicker', 'Quick links kicker', 'Also useful', 'text'],
            ['spk_links_title', 'Quick links title', 'Useful next steps.', 'textarea'],
            ['spk_link_1_label', 'Quick link 1 label', 'Programme', 'text'],
            ['spk_link_1_title', 'Quick link 1 title', 'The two-day structure', 'text'],
            ['spk_link_1_text', 'Quick link 1 text', 'See how the conference days are planned and how the detailed programme will be published.', 'textarea'],
            ['spk_link_2_label', 'Quick link 2 label', 'Practical', 'text'],
            ['spk_link_2_title', 'Quick link 2 title', 'Venue & travel', 'text'],
            ['spk_link_2_text', 'Quick link 2 text', 'Transport from the airport and stations, accessibility and accommodation tips.', 'textarea'],
            ['spk_link_3_label', 'Quick link 3 label', 'Contact', 'text'],
            ['spk_link_3_title', 'Quick link 3 title', 'Talk to the organisers', 'text'],
            ['spk_link_3_text', 'Quick link 3 text', 'Reach the CEEDUCON team for anything the speaker information does not cover.', 'textarea'],
        ],
        'Contact page' => [
            ['con_hero_title', 'Hero title', 'Talk to us.', 'textarea'],
            ['con_hero_note', 'Hero note', 'Registration updates, programme questions, speaker communication or partnerships — the organisers are happy to help.', 'textarea'],
            ['con_card_label', 'Hero card label', 'Contact us', 'text'],
            ['con_email', 'Contact email', 'ceeducon@dzs.cz', 'text'],
            ['con_phone', 'Contact phone', '+420 221 850 100', 'text'],
            ['con_kicker', 'Section kicker', 'Organiser', 'text'],
            ['con_title', 'Section title', 'Czech National Agency for International Education and Research.', 'textarea'],
            ['con_lead', 'Section lead (HTML allowed)', 'CEEDUCON is organised by DZS in co-operation with Central European partner organisations. Write to <a href="mailto:ceeducon@dzs.cz">ceeducon@dzs.cz</a> or call +420 221 850 100.', 'textarea'],
            ['con_button_email', 'Email button', 'Email CEEDUCON', 'text'],
            ['con_button_dzs', 'DZS button', 'DZS website', 'text'],
            ['con_links_kicker', 'Quick links kicker', 'Looking for something?', 'text'],
            ['con_links_title', 'Quick links title', 'Quick answers, one page away.', 'textarea'],
            ['con_link_1_label', 'Quick link 1 label', 'Programme', 'text'],
            ['con_link_1_title', 'Quick link 1 title', 'The two conference days', 'text'],
            ['con_link_1_text', 'Quick link 1 text', 'Day structure, thematic areas and the interactive programme grid.', 'textarea'],
            ['con_link_2_label', 'Quick link 2 label', 'Practical', 'text'],
            ['con_link_2_title', 'Quick link 2 title', 'Getting to the venue', 'text'],
            ['con_link_2_text', 'Quick link 2 text', 'Transport, accessibility, accommodation and travel tips for Prague.', 'textarea'],
            ['con_link_3_label', 'Quick link 3 label', 'For speakers', 'text'],
            ['con_link_3_title', 'Quick link 3 title', 'Session guidance', 'text'],
            ['con_link_3_text', 'Quick link 3 text', 'Expectations, delivery format and the speaker timeline for {{year}}.', 'textarea'],
        ],
        'Programme data' => [
            ['programme_json', 'Programme JSON', ceeducon_default_programme_json(), 'code'],
        ],
    ];

    if (function_exists('ceeducon_edition_get')) {
        $annual_keys = [
            'seo_home_description', 'seo_programme_description', 'seo_practical_description',
            'seo_speakers_description', 'seo_media_description', 'seo_contact_description',
            'footer_tagline', 'footer_copyright', 'home_hero_kicker', 'event_day', 'event_month',
            'event_row_1_label', 'event_row_1_value', 'event_row_3_label', 'event_row_3_value',
            'event_row_4_label', 'event_row_4_value', 'event_google_calendar_label',
            'event_google_calendar_url', 'event_outlook_calendar_label', 'event_outlook_calendar_url',
            'stat_1_value', 'stat_1_label', 'stat_2_value', 'stat_2_label',
            'stat_3_value', 'stat_3_label', 'stat_4_value', 'stat_4_label',
        ];

        foreach ($fields as $group => $group_fields) {
            $fields[$group] = array_values(array_filter(
                $group_fields,
                static fn(array $field): bool => !in_array((string) ($field[0] ?? ''), $annual_keys, true)
            ));
            if ($fields[$group] === []) {
                unset($fields[$group]);
            }
        }
    }

    return $fields;
}

function ceeducon_admin_menu(): void
{
    add_menu_page(
        __('CEEDUCON Content', 'ceeducon-program'),
        __('CEEDUCON Content', 'ceeducon-program'),
        'edit_theme_options',
        'ceeducon-content',
        'ceeducon_render_content_admin_page',
        'dashicons-edit-page',
        30
    );
}
add_action('admin_menu', 'ceeducon_admin_menu');

function ceeducon_migrate_default_content(): void
{
    $target_version = '2.4.2';
    if (get_option('ceeducon_content_defaults_version') === $target_version) {
        return;
    }

    $content = get_option('ceeducon_content', []);
    if (!is_array($content)) {
        $content = [];
    }

    $replacements = [
        'home_about_title' => [
            'A focused forum for international higher education.',
            'Where the people shaping international higher education come together.',
        ],
        'home_about_text_1' => [
            'CEEDUCON connects people who work on internationalisation every day: university leadership, international offices, policymakers, national agencies and practitioners from across Europe.',
            'CEEDUCON connects university leaders, internationalisation professionals, policymakers, national agencies and practitioners from across Europe to exchange ideas, experiences and solutions.',
        ],
        'home_about_text_2' => [
            'The programme is built around practical exchange: what is changing, what works in institutions, and where Central European cooperation can move higher education forward.',
            'From emerging challenges to proven approaches, the programme focuses on what is changing, what works in practice, and what we can achieve through stronger Central European cooperation.',
        ],
        'theme_1_text' => [
            'Responsible use of AI, digitalisation, data analytics and new tools in international education — while keeping academic values and human judgement in focus.',
            'AI, digitalisation, data and new tools for smarter internationalisation — with academic values and human judgement in focus.',
        ],
        'theme_2_text' => [
            'Structural, social and financial barriers, safety, wellbeing, funding and inclusive access to meaningful international experiences for all students and staff.',
            'Funding, safety, wellbeing and inclusion — removing barriers to meaningful international experiences for students and staff.',
        ],
        'theme_3_text' => [
            'Sustainable strategic cooperation, European University alliances and equitable academic partnerships across global regions.',
            'Strategic cooperation, European University alliances and equitable partnerships across global regions.',
        ],
        'theme_4_text' => [
            'A student-centred journey from marketing and admissions through support services to employability, alumni relations and graduate success.',
            'The student journey end to end — admissions, support, employability, alumni relations and long-term success.',
        ],
        'faq_2_text' => [
            'Participants arrange accommodation individually. Hotels within easy reach of the venue include Stages Hotel and Carol Hotel; central Prague is around 20 minutes away by metro.',
            'Participants are responsible for arranging and covering the cost of their own accommodation. Please note that neither accommodation nor travel expenses can be reimbursed by the conference organizers. Hotels within easy reach of the venue include <a href="https://www.stageshotel.com/" target="_blank" rel="noreferrer">Stages Hotel</a> and <a href="https://www.hotelcarol.cz/en/" target="_blank" rel="noreferrer">Carol Hotel</a>, while Prague city centre is approximately 20 minutes away by metro.',
        ],
        'con_hero_title' => ['Talk to the CEEDUCON team.', 'Talk to us.'],
        'con_card_label' => ['Write or call', 'Contact us'],
        'media_kicker' => ['Atmosphere', 'Conference atmosphere'],
        'media_title' => ['See the conference before you arrive.', 'A professional setting for exchange.'],
        'media_text' => [
            'Use the photos as a quick sense of the venue, audience and working atmosphere. The core of the website stays simple: programme first, then practical information for participants and speakers.',
            'CEEDUCON brings the international higher education community together through plenaries, workshops, hallway conversations and practical exchange across the whole venue.',
        ],
        'home_plan_title' => ['Everything you need, one page away.', 'Find the essentials quickly.'],
        'day_evening_title' => ['Networking dinner', 'Networking reception'],
        'about_org_lead' => [
            'CEEDUCON is organised by the Czech National Agency for International Education and Research (DZS) in co-operation with its partner agencies. Together they connect the national perspectives of Austria, Germany, Poland, Slovakia, Hungary and the Czech Republic into one regional conversation.',
            'CEEDUCON is organised by the Czech National Agency for International Education and Research (DZS) in co-operation with its partner agencies. Together they connect the national perspectives of Austria, Germany, Poland, Slovakia, Hungary and Czechia into one regional conversation.',
        ],
        'home_link_3_text' => [
            'Questions about registration, the programme or partnerships — the organisers are ready to help.',
            'Use the contact page for registration, programme, speaker or partnership questions.',
        ],
        'sched_title' => ['Explore the conference, room by room.', 'Find the right session faster.'],
        'sched_intro' => [
            'Filter by format, time and room, search sessions, save your personal selection and add sessions to your calendar.',
            'Search the programme, compare rooms and times, filter by theme and keep your personal selection in one place.',
        ],
        'spk_hero_note' => [
            'CEEDUCON sessions are built on practical experience, international cooperation and diverse institutional perspectives. Here is what session contributors need to know.',
            'Guidance for accepted session contributors: format, onsite delivery, timeline and practical support before the conference.',
        ],
        'spk_title' => ['Practical, onsite-first and in English.', 'Clear expectations before conference day.'],
        'spk_lead' => [
            'Sessions are delivered primarily in person at O2 universum Prague, in English, with up to three contributors per session. There is no speaker fee.',
            'Sessions are planned primarily onsite at O2 universum Prague and delivered in English. This page keeps the essential speaker information in one place.',
        ],
        'step_1_title' => ['Include all speakers in your proposal', 'Confirm contributors early'],
        'step_1_text' => [
            'All speakers should be listed during proposal submission. If final names are not confirmed yet, co-speakers can still be indicated in the registration.',
            'List all speakers as early as possible so the programme, contracts and communication can stay accurate.',
        ],
        'step_2_text' => [
            'The conference is planned primarily in person. Limited online participation may be considered only when requested and approved in advance.',
            'Plan for an in-person session unless the organisers confirm a different arrangement in advance.',
        ],
        'step_3_title' => ['Share materials and preferences on time', 'Share materials on time'],
        'step_3_text' => [
            'Accepted speakers receive follow-up information about registration, contracts, presentation templates, recording preferences and technical support.',
            'Follow the speaker timeline for registration, presentation materials, recording preferences and technical checks.',
        ],
        'spk_cta_text' => [
            '<strong>Questions about your session?</strong> The CEEDUCON team supports speakers from acceptance through to conference day.',
            '<strong>Questions about your session?</strong> Use the contact page for anything not covered here.',
        ],
        'spk_cta_button' => ['Contact the team', 'Contact page'],
        'spk_cta_url' => ['mailto:ceeducon@dzs.cz?subject=CEEDUCON%202026%20speaker%20question', home_url('/contact/')],
        'timeline_title' => ['Key dates for speakers.', 'Speaker’s timeline.'],
        'spk_links_title' => ['Before you travel.', 'Useful next steps.'],
    ];

    $changed = false;
    foreach ($replacements as $key => [$old, $new]) {
        if (array_key_exists($key, $content) && (string) $content[$key] === $old) {
            $content[$key] = $new;
            $changed = true;
        }

    }

    $speaker_replacements = [
        'spk_hero_title' => [
            ['Speakers at CEEDUCON.'],
            'For speakers.',
        ],
        'spk_hero_note' => [
            [
                'Meet featured contributors and find practical information about formats, milestones and support for confirmed CEEDUCON speakers.',
                'Guidance for accepted session contributors: format, onsite delivery, timeline and practical support before the conference.',
                'CEEDUCON sessions are built on practical experience, international cooperation and diverse institutional perspectives. Here is what session contributors need to know.',
            ],
            'Practical information about formats, milestones and support for confirmed CEEDUCON speakers.',
        ],
        'seo_speakers_description' => [
            ['Meet featured CEEDUCON 2026 contributors and find practical information, milestones and support for confirmed speakers.'],
            'Practical information, milestones and support for confirmed CEEDUCON 2026 speakers.',
        ],
        'spk_card_label' => [
            ['Programme publication', 'Speaker support'],
            'Speaker checklist',
        ],
        'spk_card_title' => [
            ['By September 1', 'Clear milestones'],
            'Before conference day',
        ],
        'spk_card_text' => [
            [
                'Accepted speakers receive detailed follow-up information about registration, contracts and presentation materials.',
                'Prepare your session, materials and onsite participation.',
            ],
            "Check your session details, complete speaker registration and submit final presentation materials according to the organisers' instructions.",
        ],
        'milestone_4_label' => [
            ['By September 1'],
            'Before the conference',
        ],
        'milestone_4_text' => [
            ['Programme publication'],
            'Final materials and technical check',
        ],
        'timeline_title' => [
            ['Speaker timeline.', 'Key dates for speakers.'],
            'Speaker’s timeline.',
        ],
    ];

    foreach ($speaker_replacements as $key => [$old_values, $new]) {
        if (array_key_exists($key, $content) && in_array((string) $content[$key], $old_values, true)) {
            $content[$key] = $new;
            $changed = true;
        }
    }

    $new_speaker_milestones = [
        'milestone_5_label' => 'At the conference / online',
        'milestone_5_text'  => 'Sign the reimbursement contract',
        'milestone_6_label' => 'By 9 December {{year}}',
        'milestone_6_text'  => 'Send us the necessary documents/receipts for the reimbursement',
    ];
    foreach ($new_speaker_milestones as $key => $value) {
        if (!array_key_exists($key, $content)) {
            $content[$key] = $value;
            $changed = true;
        }
    }

    if ($changed) {
        update_option('ceeducon_content', $content);
    }
    update_option('ceeducon_content_defaults_version', $target_version);
}
add_action('admin_init', 'ceeducon_migrate_default_content');

function ceeducon_render_content_admin_page(): void
{
    if (!current_user_can('edit_theme_options')) {
        return;
    }

    $fields = ceeducon_admin_content_fields();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer('ceeducon_save_content')) {
        $submitted = isset($_POST['ceeducon_content']) && is_array($_POST['ceeducon_content'])
            ? wp_unslash($_POST['ceeducon_content'])
            : [];
        $existing = get_option('ceeducon_content', []);
        if (!is_array($existing)) {
            $existing = [];
        }
        // Preserve values owned by companion plugins when their fields are
        // intentionally hidden from this legacy content screen.
        $clean = $existing;
        $programme_json_error = '';

        foreach ($fields as $group_fields) {
            foreach ($group_fields as [$key, , , $type]) {
                $value = isset($submitted[$key]) ? (string) $submitted[$key] : '';
                if ($type === 'url') {
                    $clean[$key] = esc_url_raw($value);
                } elseif ($type === 'code') {
                    $value = wp_check_invalid_utf8($value);
                    if ($key === 'programme_json' && trim($value) !== '' && json_decode($value, true) === null) {
                        $programme_json_error = json_last_error_msg();
                        $clean[$key] = isset($existing[$key]) ? (string) $existing[$key] : ceeducon_default_programme_json();
                    } else {
                        $clean[$key] = $value;
                    }
                } else {
                    $clean[$key] = wp_kses_post($value);
                }
            }
        }

        update_option('ceeducon_content', $clean);
        if ($programme_json_error !== '') {
            echo '<div class="notice notice-error"><p>' . esc_html(sprintf(__('Programme JSON was not saved because it is not valid JSON: %s', 'ceeducon-program'), $programme_json_error)) . '</p></div>';
        } else {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('CEEDUCON content saved.', 'ceeducon-program') . '</p></div>';
        }
    }

    $content = get_option('ceeducon_content', []);
    if (!is_array($content)) {
        $content = [];
    }
    ?>
    <div class="wrap">
      <h1><?php esc_html_e('CEEDUCON Content', 'ceeducon-program'); ?></h1>
      <p><?php esc_html_e('Edit the visible texts of the CEEDUCON pages. The Programme JSON field controls the rooms, themes and sessions of the interactive programme grid.', 'ceeducon-program'); ?></p>
      <form method="post">
        <?php wp_nonce_field('ceeducon_save_content'); ?>
        <?php foreach ($fields as $group => $group_fields) : ?>
          <h2><?php echo esc_html($group); ?></h2>
          <table class="form-table" role="presentation">
            <tbody>
              <?php foreach ($group_fields as [$key, $label, $default, $type]) : ?>
                <?php $value = array_key_exists($key, $content) ? (string) $content[$key] : ceeducon_text_value($key, $default); ?>
                <tr>
                  <th scope="row">
                    <label for="ceeducon-<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></label>
                  </th>
                  <td>
                    <?php if ($type === 'code') : ?>
                      <textarea id="ceeducon-<?php echo esc_attr($key); ?>" name="ceeducon_content[<?php echo esc_attr($key); ?>]" class="large-text code" rows="22"><?php echo esc_textarea($value); ?></textarea>
                      <p class="description"><?php esc_html_e('Edit rooms, themes and sessions here. Keep valid JSON formatting.', 'ceeducon-program'); ?></p>
                    <?php elseif ($type === 'textarea') : ?>
                      <textarea id="ceeducon-<?php echo esc_attr($key); ?>" name="ceeducon_content[<?php echo esc_attr($key); ?>]" class="large-text" rows="3"><?php echo esc_textarea($value); ?></textarea>
                    <?php else : ?>
                      <input id="ceeducon-<?php echo esc_attr($key); ?>" name="ceeducon_content[<?php echo esc_attr($key); ?>]" type="<?php echo esc_attr($type === 'url' ? 'url' : 'text'); ?>" class="regular-text" value="<?php echo esc_attr($value); ?>" />
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endforeach; ?>
        <?php submit_button(__('Save CEEDUCON content', 'ceeducon-program')); ?>
      </form>
    </div>
    <?php
}
