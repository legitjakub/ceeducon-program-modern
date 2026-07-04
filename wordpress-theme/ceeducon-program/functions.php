<?php
/**
 * CEEDUCON Programme theme setup.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ceeducon_theme_setup(): void
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
}
add_action('after_setup_theme', 'ceeducon_theme_setup');

function ceeducon_asset_url(string $path): string
{
    return get_template_directory_uri() . '/' . ltrim($path, '/');
}

function ceeducon_text_value(string $key, string $default): string
{
    return get_theme_mod('ceeducon_' . $key, $default);
}

function ceeducon_text(string $key, string $default): void
{
    echo esc_html(ceeducon_text_value($key, $default));
}

function ceeducon_html(string $key, string $default): void
{
    echo wp_kses_post(ceeducon_text_value($key, $default));
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

    wp_add_inline_script(
        'ceeducon-program',
        'window.CEEDUCON_DATA_URL = ' . wp_json_encode(ceeducon_asset_url('data/program.json')) . ';',
        'before'
    );
}
add_action('wp_enqueue_scripts', 'ceeducon_theme_scripts');

function ceeducon_customize_register(WP_Customize_Manager $wp_customize): void
{
    $wp_customize->add_panel('ceeducon_content', [
        'title' => __('CEEDUCON content', 'ceeducon-program'),
        'priority' => 30,
    ]);

    $sections = [
        'hero' => __('Hero', 'ceeducon-program'),
        'about' => __('About', 'ceeducon-program'),
        'themes' => __('Thematic areas', 'ceeducon-program'),
        'tools' => __('Programme tools', 'ceeducon-program'),
        'programme' => __('Programme intro', 'ceeducon-program'),
        'venue' => __('Venue', 'ceeducon-program'),
        'footer' => __('Footer', 'ceeducon-program'),
    ];

    foreach ($sections as $section_id => $title) {
        $wp_customize->add_section('ceeducon_' . $section_id, [
            'title' => $title,
            'panel' => 'ceeducon_content',
        ]);
    }

    $fields = [
        ['hero', 'hero_kicker', 'Hero kicker', 'Central European Conference on Internationalisation of Higher Education', 'text'],
        ['hero', 'hero_title', 'Hero title', 'CEEDUCON 2025 programme for internationalisation in higher education.', 'textarea'],
        ['hero', 'hero_lead', 'Hero lead', "Central Europe's conference on higher education internationalisation, brought into a clear interactive schedule. Follow the day by when, where and what: time, room, thematic area and session detail.", 'textarea'],
        ['hero', 'hero_meta_1', 'Hero meta 1', '<strong>19 Nov</strong> 2025', 'textarea'],
        ['hero', 'hero_meta_2', 'Hero meta 2', '<strong>8 rooms</strong> C1-E2', 'textarea'],
        ['hero', 'hero_meta_3', 'Hero meta 3', '<strong>4 areas</strong> AI, mobility, partnerships, skills', 'textarea'],
        ['hero', 'hero_primary_cta', 'Primary CTA', 'Open programme', 'text'],
        ['hero', 'hero_secondary_cta', 'Secondary CTA', 'Explore themes', 'text'],
        ['hero', 'event_label', 'Event card label', 'Conference day', 'text'],
        ['hero', 'event_day', 'Event day', '19', 'text'],
        ['hero', 'event_month_year', 'Event month and year', 'NOV<br />2025', 'textarea'],
        ['hero', 'event_start', 'Event start', '08:30 Registration', 'text'],
        ['hero', 'event_block', 'Event main block', '11:00-16:50 Sessions', 'text'],
        ['hero', 'event_venue', 'Event venue', 'O2 universum Prague', 'text'],
        ['hero', 'event_route_1', 'Event route 1', 'When: time blocks', 'text'],
        ['hero', 'event_route_2', 'Event route 2', 'Where: room grid', 'text'],
        ['hero', 'event_route_3', 'Event route 3', 'What: thematic sessions', 'text'],
        ['hero', 'event_cta', 'Event card CTA', 'Jump to room grid', 'text'],

        ['about', 'about_kicker', 'About kicker', 'About the conference', 'text'],
        ['about', 'about_title', 'About title', 'A clear map of ideas, practice and cooperation.', 'textarea'],
        ['about', 'about_text', 'About text', 'CEEDUCON brings together people working with internationalisation of higher education. The programme reflects the main questions of the field: responsible digital change, inclusive international experiences, sustainable partnerships and future skills.', 'textarea'],
        ['about', 'about_point_1', 'About point 1', 'AI and digitalisation', 'text'],
        ['about', 'about_point_2', 'About point 2', 'inclusive mobility', 'text'],
        ['about', 'about_point_3', 'About point 3', 'global partnerships', 'text'],
        ['about', 'about_point_4', 'About point 4', 'alumni and careers', 'text'],

        ['themes', 'themes_kicker', 'Themes kicker', 'Thematic tracks', 'text'],
        ['themes', 'themes_title', 'Themes title', 'Main thematic areas of the conference.', 'textarea'],
        ['themes', 'themes_intro', 'Themes intro', 'The structure follows the CEEDUCON themes and helps participants quickly understand which sessions connect to their work.', 'textarea'],
        ['themes', 'theme_1_title', 'Theme 1 title', 'Navigating the Technological Shift', 'text'],
        ['themes', 'theme_1_text', 'Theme 1 text', 'Responsible use of AI, digitalisation and data analytics for smarter internationalisation strategies.', 'textarea'],
        ['themes', 'theme_2_title', 'Theme 2 title', 'Challenges of Internationalisation', 'text'],
        ['themes', 'theme_2_text', 'Theme 2 text', 'Structural, social and financial barriers, wellbeing, access and safe international experiences for all.', 'textarea'],
        ['themes', 'theme_3_title', 'Theme 3 title', 'Global & Regional Partnerships', 'text'],
        ['themes', 'theme_3_text', 'Theme 3 text', 'Sustainable partnerships, European University alliances and cooperation across global regions.', 'textarea'],
        ['themes', 'theme_4_title', 'Theme 4 title', 'From Recruitment to Retention', 'text'],
        ['themes', 'theme_4_text', 'Theme 4 text', 'A student-centred journey from recruitment and admissions to support, alumni and employability.', 'textarea'],
        ['themes', 'theme_button', 'Theme button label', 'Show sessions', 'text'],

        ['tools', 'tool_1_title', 'Tool 1 title', 'When, where, what', 'text'],
        ['tools', 'tool_1_text', 'Tool 1 text', 'The schedule translates the conference programme into time blocks, rooms and thematic context.', 'textarea'],
        ['tools', 'tool_2_title', 'Tool 2 title', 'Relevant sessions', 'text'],
        ['tools', 'tool_2_text', 'Tool 2 text', 'Filter by room or theme and save sessions that match your internationalisation priorities.', 'textarea'],
        ['tools', 'tool_3_title', 'Tool 3 title', 'Ready for the day', 'text'],
        ['tools', 'tool_3_text', 'Tool 3 text', 'Open session detail, export selected sessions to calendar or print the programme as a PDF.', 'textarea'],

        ['programme', 'programme_kicker', 'Programme kicker', 'Programme', 'text'],
        ['programme', 'programme_title', 'Programme title', 'Time grid by room.', 'text'],
        ['programme', 'programme_intro', 'Programme intro', 'A practical view of the CEEDUCON programme: desktop keeps the room grid, mobile turns it into a readable timeline with filters and personal selection.', 'textarea'],

        ['venue', 'venue_kicker', 'Venue kicker', 'Venue', 'text'],
        ['venue', 'venue_title', 'Venue title', 'O2 universum Prague', 'text'],
        ['venue', 'venue_text', 'Venue text', 'The programme is designed to make room changes clear on desktop and mobile across halls C1, C2, C3, D2, D3+D4, D6+D7, E1 and E2.', 'textarea'],
        ['venue', 'venue_button', 'Venue button', 'Open venue website', 'text'],
        ['venue', 'venue_url', 'Venue URL', 'https://www.o2universum.cz/en', 'url'],

        ['footer', 'footer_title', 'Footer title', 'CEEDUCON 2025', 'text'],
        ['footer', 'footer_subtitle', 'Footer subtitle', 'Central European Conference on Internationalisation of Higher Education', 'text'],
        ['footer', 'footer_back_top', 'Back to top label', 'Back to top', 'text'],
    ];

    foreach ($fields as [$section, $key, $label, $default, $type]) {
        $setting_id = 'ceeducon_' . $key;
        $wp_customize->add_setting($setting_id, [
            'default' => $default,
            'sanitize_callback' => $type === 'url' ? 'esc_url_raw' : 'wp_kses_post',
        ]);

        $wp_customize->add_control($setting_id, [
            'label' => __($label, 'ceeducon-program'),
            'section' => 'ceeducon_' . $section,
            'type' => $type === 'textarea' ? 'textarea' : ($type === 'url' ? 'url' : 'text'),
        ]);
    }
}
add_action('customize_register', 'ceeducon_customize_register');

