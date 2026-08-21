<?php
/**
 * Plugin Name: CEEDUCON Conference Edition
 * Description: One central WordPress screen for the CEEDUCON details that change every year.
 * Version: 1.2.0
 * Requires at least: 6.5
 * Requires PHP: 8.0
 * Author: CEEDUCON
 * Text Domain: ceeducon-conference-settings
 */

if (!defined('ABSPATH')) {
    exit;
}

define('CEEDUCON_EDITION_VERSION', '1.1.0');
define('CEEDUCON_EDITION_OPTION', 'ceeducon_event_settings');
define('CEEDUCON_EDITION_FILE', __FILE__);

function ceeducon_edition_defaults(): array
{
    return [
        'event_title' => 'CEEDUCON 2026',
        'edition_year' => 2026,
        'country_label' => 'CZECHIA',
        'start_date' => '2026-12-01',
        'start_time' => '09:30',
        'end_date' => '2026-12-02',
        'end_time' => '16:00',
        'timezone' => 'Europe/Prague',
        'venue_name' => 'O2 universum Prague',
        'city_label' => 'Prague',
        'venue_schema_name' => 'O2 universum',
        'street_address' => 'Českomoravská 2345/17',
        'address_locality' => 'Prague 9',
        'postal_code' => '190 00',
        'country_code' => 'CZ',
        'fee_text' => 'Free of charge',
        'registration_text' => 'Registration opens in September',
        'registration_url' => '',
        'language' => 'English',
        'language_code' => 'en',
        'calendar_description' => 'Central European Conference on Internationalisation of Higher Education.',
        'stat_1_value' => '2',
        'stat_1_label' => 'conference days',
        'stat_2_value' => '900+',
        'stat_2_label' => 'participants in 2025',
        'stat_3_value' => '130+',
        'stat_3_label' => 'speakers in 2025',
        'stat_4_value' => '50+',
        'stat_4_label' => 'sessions & workshops',
        'hero_image_id' => 0,
        'hero_image_alt' => 'A packed CEEDUCON plenary session',
        'social_image_id' => 0,
    ];
}

function ceeducon_edition_settings(): array
{
    $stored = get_option(CEEDUCON_EDITION_OPTION, []);
    return wp_parse_args(is_array($stored) ? $stored : [], ceeducon_edition_defaults());
}

function ceeducon_edition_get(string $key, $default = '')
{
    $settings = ceeducon_edition_settings();
    return array_key_exists($key, $settings) ? $settings[$key] : $default;
}

function ceeducon_edition_year(): int
{
    $start = ceeducon_edition_start();
    return $start ? (int) $start->format('Y') : (int) ceeducon_edition_get('edition_year', 2026);
}

function ceeducon_edition_day_count(): int
{
    $start = ceeducon_edition_start();
    $end = ceeducon_edition_end();
    return ($start && $end && $end >= $start) ? max(1, (int) $start->diff($end)->format('%a') + 1) : 1;
}

function ceeducon_edition_datetime(string $date_key, string $time_key): ?DateTimeImmutable
{
    $date = (string) ceeducon_edition_get($date_key);
    $time = (string) ceeducon_edition_get($time_key);
    $timezone_name = (string) ceeducon_edition_get('timezone', 'Europe/Prague');

    try {
        $timezone = new DateTimeZone($timezone_name);
        $value = DateTimeImmutable::createFromFormat('!Y-m-d H:i', $date . ' ' . $time, $timezone);
        return $value instanceof DateTimeImmutable ? $value : null;
    } catch (Exception $exception) {
        return null;
    }
}

function ceeducon_edition_start(): ?DateTimeImmutable
{
    return ceeducon_edition_datetime('start_date', 'start_time');
}

function ceeducon_edition_end(): ?DateTimeImmutable
{
    return ceeducon_edition_datetime('end_date', 'end_time');
}

function ceeducon_edition_month_name(int $month, bool $short = false): string
{
    $long = [1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    $name = $long[$month] ?? '';
    return $short ? substr($name, 0, 3) : $name;
}

function ceeducon_edition_event_day(): string
{
    $start = ceeducon_edition_start();
    $end = ceeducon_edition_end();
    if (!$start || !$end) {
        return '';
    }

    if ($start->format('Y-m') === $end->format('Y-m')) {
        return $start->format('j') === $end->format('j')
            ? $start->format('j')
            : $start->format('j') . '–' . $end->format('j');
    }

    return $start->format('j') . ' ' . ceeducon_edition_month_name((int) $start->format('n'), true)
        . '–' . $end->format('j') . ' ' . ceeducon_edition_month_name((int) $end->format('n'), true);
}

function ceeducon_edition_event_month(): string
{
    $start = ceeducon_edition_start();
    $end = ceeducon_edition_end();
    if (!$start || !$end) {
        return (string) ceeducon_edition_get('edition_year');
    }

    if ($start->format('Y-m') === $end->format('Y-m')) {
        return ceeducon_edition_month_name((int) $start->format('n'), true) . ' ' . $start->format('Y');
    }

    return $end->format('Y');
}

function ceeducon_edition_full_date(): string
{
    $start = ceeducon_edition_start();
    $end = ceeducon_edition_end();
    if (!$start || !$end) {
        return '';
    }

    if ($start->format('Y-m') === $end->format('Y-m')) {
        $days = $start->format('j') === $end->format('j')
            ? $start->format('j')
            : $start->format('j') . '–' . $end->format('j');
        return $days . ' ' . ceeducon_edition_month_name((int) $start->format('n')) . ' ' . $start->format('Y');
    }

    return $start->format('j') . ' ' . ceeducon_edition_month_name((int) $start->format('n')) . ' '
        . $start->format('Y') . '–' . $end->format('j') . ' '
        . ceeducon_edition_month_name((int) $end->format('n')) . ' ' . $end->format('Y');
}

function ceeducon_edition_short_date(): string
{
    $date = ceeducon_edition_full_date();
    return trim(str_replace((string) ceeducon_edition_year(), '', $date));
}

function ceeducon_edition_token_values(array $tokens): array
{
    return array_merge($tokens, [
        '{{event_title}}' => (string) ceeducon_edition_get('event_title'),
        '{{year}}' => (string) ceeducon_edition_year(),
        '{{date}}' => ceeducon_edition_full_date(),
        '{{date_short}}' => ceeducon_edition_short_date(),
        '{{venue}}' => (string) ceeducon_edition_get('venue_name'),
        '{{city}}' => (string) ceeducon_edition_get('city_label'),
        '{{fee}}' => (string) ceeducon_edition_get('fee_text'),
        '{{registration}}' => (string) ceeducon_edition_get('registration_text'),
    ]);
}
add_filter('ceeducon_content_tokens', 'ceeducon_edition_token_values');

function ceeducon_edition_location(): string
{
    return implode(', ', array_filter([
        (string) ceeducon_edition_get('venue_name'),
        (string) ceeducon_edition_get('street_address'),
        (string) ceeducon_edition_get('address_locality'),
        (string) ceeducon_edition_get('country_code'),
    ]));
}

function ceeducon_edition_google_calendar_url(): string
{
    $start = ceeducon_edition_start();
    $end = ceeducon_edition_end();
    if (!$start || !$end) {
        return '';
    }

    $utc = new DateTimeZone('UTC');
    return add_query_arg([
        'action' => 'TEMPLATE',
        'text' => (string) ceeducon_edition_get('event_title'),
        'dates' => $start->setTimezone($utc)->format('Ymd\THis\Z') . '/' . $end->setTimezone($utc)->format('Ymd\THis\Z'),
        'details' => (string) ceeducon_edition_get('calendar_description'),
        'location' => ceeducon_edition_location(),
        'ctz' => (string) ceeducon_edition_get('timezone'),
    ], 'https://calendar.google.com/calendar/render');
}

function ceeducon_edition_outlook_calendar_url(): string
{
    $start = ceeducon_edition_start();
    $end = ceeducon_edition_end();
    if (!$start || !$end) {
        return '';
    }

    return add_query_arg([
        'path' => '/calendar/action/compose',
        'rru' => 'addevent',
        'subject' => (string) ceeducon_edition_get('event_title'),
        'startdt' => $start->format(DateTimeInterface::ATOM),
        'enddt' => $end->format(DateTimeInterface::ATOM),
        'body' => (string) ceeducon_edition_get('calendar_description'),
        'location' => ceeducon_edition_location(),
    ], 'https://outlook.live.com/calendar/0/deeplink/compose');
}

function ceeducon_edition_text_filter(string $value, string $key, string $default): string
{
    $year = (string) ceeducon_edition_year();
    $title = (string) ceeducon_edition_get('event_title');
    $date = ceeducon_edition_full_date();
    $venue = (string) ceeducon_edition_get('venue_name');

    $values = [
        'home_hero_kicker' => $title . ' · ' . (string) ceeducon_edition_get('country_label'),
        'event_day' => ceeducon_edition_event_day(),
        'event_month' => ceeducon_edition_event_month(),
        'event_row_1_value' => $venue,
        'event_row_3_value' => (string) ceeducon_edition_get('fee_text'),
        'event_row_4_value' => (string) ceeducon_edition_get('registration_text'),
        'registration_url' => (string) ceeducon_edition_get('registration_url'),
        'event_google_calendar_url' => ceeducon_edition_google_calendar_url(),
        'event_outlook_calendar_url' => ceeducon_edition_outlook_calendar_url(),
        'stat_1_value' => (string) ceeducon_edition_day_count(),
        'stat_1_label' => (string) ceeducon_edition_get('stat_1_label'),
        'stat_2_value' => (string) ceeducon_edition_get('stat_2_value'),
        'stat_2_label' => (string) ceeducon_edition_get('stat_2_label'),
        'stat_3_value' => (string) ceeducon_edition_get('stat_3_value'),
        'stat_3_label' => (string) ceeducon_edition_get('stat_3_label'),
        'stat_4_value' => (string) ceeducon_edition_get('stat_4_value'),
        'stat_4_label' => (string) ceeducon_edition_get('stat_4_label'),
        'about_card_label' => $title,
        'about_card_title' => $date . ' · ' . (string) ceeducon_edition_get('city_label'),
        'home_prog_kicker' => 'Programme ' . $year,
        'footer_tagline' => 'Central European Conference on Internationalisation of Higher Education.<br />' . $date . ' · ' . $venue,
        'footer_copyright' => '© ' . $year . ' DZS — Czech National Agency for International Education and Research',
        'seo_home_description' => $title . ' — Central European Conference on Internationalisation of Higher Education. ' . $date . ', ' . $venue . '.',
        'seo_programme_description' => 'Browse the interactive ' . $title . ' programme for ' . $date . ' at ' . $venue . ': sessions, workshops and speakers.',
        'seo_practical_description' => 'Practical information for ' . $title . ': venue, transport, accessibility and accommodation in ' . (string) ceeducon_edition_get('city_label') . '.',
        'seo_speakers_description' => 'Practical information, milestones and support for confirmed ' . $title . ' speakers.',
        'seo_media_description' => $title . ' media kit: official visuals, brand assets, press information and media contact.',
        'seo_contact_description' => 'Contact the ' . $title . ' organising team about registration, programme, speakers, partnerships or media requests.',
    ];

    $value = array_key_exists($key, $values) && $values[$key] !== '' ? (string) $values[$key] : $value;

    // Keep legacy copy from earlier theme versions in sync without touching
    // archived programme data, asset paths or URLs.
    if ($key !== 'programme_json' && !str_contains($key, 'past') && !str_ends_with($key, '_url')) {
        $value = str_replace(
            ['CEEDUCON 2026', '1–2 December 2026', '1–2 December', 'O2 universum Prague'],
            [$title, $date, ceeducon_edition_short_date(), $venue],
            $value
        );
        $value = (string) preg_replace('/\b2026\b/', $year, $value);
    }

    return $value;
}
add_filter('ceeducon_text_value', 'ceeducon_edition_text_filter', 10, 3);

function ceeducon_edition_section_filter(array $attributes, string $section): array
{
    $title = (string) ceeducon_edition_get('event_title');
    $date = ceeducon_edition_full_date();
    $venue = (string) ceeducon_edition_get('venue_name');

    if ($section === 'hero') {
        $attributes['kicker'] = $title . ' · ' . (string) ceeducon_edition_get('country_label');
        $attributes['eventDay'] = ceeducon_edition_event_day();
        $attributes['eventMonth'] = ceeducon_edition_event_month();
        $attributes['eventRows'] = [
            ['label' => 'Venue', 'value' => $venue],
            ['label' => 'Fee', 'value' => (string) ceeducon_edition_get('fee_text')],
            ['label' => 'Registration', 'value' => (string) ceeducon_edition_get('registration_text')],
        ];
        $attributes['registrationUrl'] = (string) ceeducon_edition_get('registration_url');
        $attributes['googleCalendarText'] = 'Google Calendar';
        $attributes['googleCalendarUrl'] = ceeducon_edition_google_calendar_url();
        $attributes['outlookCalendarText'] = 'Outlook Calendar';
        $attributes['outlookCalendarUrl'] = ceeducon_edition_outlook_calendar_url();

        $image_id = (int) ceeducon_edition_get('hero_image_id');
        if ($image_id > 0) {
            $attributes['imageId'] = $image_id;
            $attributes['imageUrl'] = (string) wp_get_attachment_image_url($image_id, 'full');
            $attributes['imageAlt'] = (string) ceeducon_edition_get('hero_image_alt');
        }
    }

    if ($section === 'page-hero') {
        $attributes['cardLabel'] = $title;
        $attributes['cardTitle'] = $date . ' · ' . (string) ceeducon_edition_get('city_label');
    }

    if ($section === 'cta') {
        $attributes['noteLabel'] = $title;
        $attributes['noteTitle'] = $date . ' · ' . $venue;
        $attributes['noteText'] = (string) ceeducon_edition_get('registration_text');
    }

    if ($section === 'schedule-overview') {
        $attributes['kicker'] = 'Programme ' . (string) ceeducon_edition_year();
        $items = isset($attributes['items']) && is_array($attributes['items']) ? $attributes['items'] : [];
        $start = ceeducon_edition_start();
        $end = ceeducon_edition_end();
        if ($start && isset($items[0]) && is_array($items[0])) {
            $items[0]['label'] = 'Day 1 · ' . $start->format('D j M');
        }
        if ($end && isset($items[2]) && is_array($items[2])) {
            $items[2]['label'] = 'Day 2 · ' . $end->format('D j M');
        }
        $attributes['items'] = $items;
    }

    return $attributes;
}
add_filter('ceeducon_section_attributes', 'ceeducon_edition_section_filter', 10, 2);

function ceeducon_edition_home_hero_image(array $image): array
{
    $attachment_id = (int) ceeducon_edition_get('hero_image_id');
    if ($attachment_id < 1) {
        return $image;
    }
    $url = wp_get_attachment_image_url($attachment_id, 'full');
    if (!is_string($url) || $url === '') {
        return $image;
    }
    return [
        'id' => $attachment_id,
        'url' => $url,
        'alt' => (string) ceeducon_edition_get('hero_image_alt'),
    ];
}
add_filter('ceeducon_home_hero_image', 'ceeducon_edition_home_hero_image');

function ceeducon_edition_hero_editor_data(array $data): array
{
    $image_id = (int) ceeducon_edition_get('hero_image_id');
    $image_url = $image_id > 0 ? wp_get_attachment_image_url($image_id, 'full') : '';
    return [
        'managed' => true,
        'settingsUrl' => admin_url('admin.php?page=ceeducon-edition'),
        'attributes' => [
            'kicker' => (string) ceeducon_edition_get('event_title') . ' · ' . (string) ceeducon_edition_get('country_label'),
            'eventDay' => ceeducon_edition_event_day(),
            'eventMonth' => ceeducon_edition_event_month(),
            'eventRows' => [
                ['label' => 'Venue', 'value' => (string) ceeducon_edition_get('venue_name')],
                ['label' => 'Fee', 'value' => (string) ceeducon_edition_get('fee_text')],
                ['label' => 'Registration', 'value' => (string) ceeducon_edition_get('registration_text')],
            ],
            'googleCalendarText' => 'Google Calendar',
            'googleCalendarUrl' => ceeducon_edition_google_calendar_url(),
            'outlookCalendarText' => 'Outlook Calendar',
            'outlookCalendarUrl' => ceeducon_edition_outlook_calendar_url(),
            'imageId' => $image_id,
            'imageUrl' => is_string($image_url) ? $image_url : '',
            'imageAlt' => (string) ceeducon_edition_get('hero_image_alt'),
        ],
    ];
}
add_filter('ceeducon_hero_editor_data', 'ceeducon_edition_hero_editor_data');

function ceeducon_edition_schema_filter(array $schema): array
{
    $start = ceeducon_edition_start();
    $end = ceeducon_edition_end();
    $year = (string) ceeducon_edition_year();

    $schema['@id'] = home_url('/#ceeducon-' . sanitize_title($year));
    $schema['name'] = (string) ceeducon_edition_get('event_title') . ' — Central European Conference on Internationalisation of Higher Education';
    if ($start) {
        $schema['startDate'] = $start->format(DateTimeInterface::ATOM);
    }
    if ($end) {
        $schema['endDate'] = $end->format(DateTimeInterface::ATOM);
    }
    $schema['inLanguage'] = (string) ceeducon_edition_get('language_code', 'en');
    $schema['location'] = [
        '@type' => 'Place',
        'name' => (string) ceeducon_edition_get('venue_schema_name'),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => (string) ceeducon_edition_get('street_address'),
            'addressLocality' => (string) ceeducon_edition_get('address_locality'),
            'postalCode' => (string) ceeducon_edition_get('postal_code'),
            'addressCountry' => (string) ceeducon_edition_get('country_code'),
        ],
    ];

    $registration_url = (string) ceeducon_edition_get('registration_url');
    if ($registration_url !== '') {
        $schema['offers'] = [
            '@type' => 'Offer',
            'url' => esc_url_raw($registration_url),
            'availability' => 'https://schema.org/InStock',
        ];
    } else {
        unset($schema['offers']);
    }

    $social_id = (int) ceeducon_edition_get('social_image_id');
    if ($social_id > 0) {
        $url = wp_get_attachment_image_url($social_id, 'full');
        if (is_string($url) && $url !== '') {
            $schema['image'] = [$url];
        }
    }

    return $schema;
}
add_filter('ceeducon_event_schema', 'ceeducon_edition_schema_filter');

function ceeducon_edition_social_image_filter(array $image): array
{
    $attachment_id = (int) ceeducon_edition_get('social_image_id');
    if ($attachment_id < 1) {
        return $image;
    }

    $source = wp_get_attachment_image_src($attachment_id, 'full');
    if (!is_array($source)) {
        return $image;
    }

    $alt = (string) get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
    return [
        'url' => (string) $source[0],
        'width' => (int) $source[1],
        'height' => (int) $source[2],
        'alt' => $alt !== '' ? $alt : (string) ceeducon_edition_get('event_title') . ' conference visual',
    ];
}
add_filter('ceeducon_social_image', 'ceeducon_edition_social_image_filter');

function ceeducon_edition_sanitize_date($value, string $fallback): string
{
    $value = sanitize_text_field((string) $value);
    $parts = explode('-', $value);
    if (count($parts) !== 3 || !checkdate((int) $parts[1], (int) $parts[2], (int) $parts[0])) {
        return $fallback;
    }
    return $value;
}

function ceeducon_edition_sanitize(array $input): array
{
    $defaults = ceeducon_edition_defaults();
    $output = [];
    $text_fields = [
        'event_title', 'country_label', 'venue_name', 'city_label', 'venue_schema_name',
        'street_address', 'address_locality', 'postal_code', 'country_code', 'fee_text',
        'registration_text', 'language', 'hero_image_alt', 'stat_1_value', 'stat_1_label',
        'stat_2_value', 'stat_2_label', 'stat_3_value', 'stat_3_label', 'stat_4_value', 'stat_4_label',
    ];

    foreach ($text_fields as $key) {
        $output[$key] = sanitize_text_field((string) ($input[$key] ?? $defaults[$key]));
    }

    $output['calendar_description'] = sanitize_textarea_field((string) ($input['calendar_description'] ?? $defaults['calendar_description']));
    $output['registration_url'] = esc_url_raw((string) ($input['registration_url'] ?? ''));
    $output['start_date'] = ceeducon_edition_sanitize_date($input['start_date'] ?? '', $defaults['start_date']);
    $output['end_date'] = ceeducon_edition_sanitize_date($input['end_date'] ?? '', $defaults['end_date']);

    foreach (['start_time', 'end_time'] as $key) {
        $time = sanitize_text_field((string) ($input[$key] ?? $defaults[$key]));
        $output[$key] = preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $time) ? $time : $defaults[$key];
    }

    $timezone = sanitize_text_field((string) ($input['timezone'] ?? $defaults['timezone']));
    $output['timezone'] = in_array($timezone, timezone_identifiers_list(), true) ? $timezone : $defaults['timezone'];
    $language_code = strtolower(sanitize_key((string) ($input['language_code'] ?? $defaults['language_code'])));
    $output['language_code'] = preg_match('/^[a-z]{2,3}(?:-[a-z]{2})?$/', $language_code) ? $language_code : $defaults['language_code'];
    $output['hero_image_id'] = absint($input['hero_image_id'] ?? 0);
    $output['social_image_id'] = absint($input['social_image_id'] ?? 0);

    $start_year = (int) substr($output['start_date'], 0, 4);
    $output['edition_year'] = ($start_year >= 2000 && $start_year <= 2200) ? $start_year : (int) $defaults['edition_year'];
    if ($output['event_title'] === '' || preg_match('/^CEEDUCON\s+\d{4}$/i', $output['event_title'])) {
        $output['event_title'] = 'CEEDUCON ' . $output['edition_year'];
    }

    return $output;
}

function ceeducon_edition_can_edit(): bool
{
    return current_user_can((string) apply_filters('ceeducon_edition_capability', 'edit_theme_options'));
}

function ceeducon_edition_admin_menu(): void
{
    $capability = (string) apply_filters('ceeducon_edition_capability', 'edit_theme_options');
    if (function_exists('ceeducon_admin_menu')) {
        add_submenu_page(
            'ceeducon-content',
            __('Conference edition', 'ceeducon-conference-settings'),
            __('Conference edition', 'ceeducon-conference-settings'),
            $capability,
            'ceeducon-edition',
            'ceeducon_edition_render_admin'
        );
        return;
    }

    add_menu_page(
        __('CEEDUCON edition', 'ceeducon-conference-settings'),
        __('CEEDUCON edition', 'ceeducon-conference-settings'),
        $capability,
        'ceeducon-edition',
        'ceeducon_edition_render_admin',
        'dashicons-calendar-alt',
        30
    );
}
add_action('admin_menu', 'ceeducon_edition_admin_menu', 20);

function ceeducon_edition_admin_assets(string $hook): void
{
    if (!str_contains($hook, 'ceeducon-edition')) {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_style('ceeducon-edition-admin', plugins_url('assets/admin.css', CEEDUCON_EDITION_FILE), [], CEEDUCON_EDITION_VERSION);
    wp_enqueue_script('ceeducon-edition-admin', plugins_url('assets/admin.js', CEEDUCON_EDITION_FILE), ['jquery'], CEEDUCON_EDITION_VERSION, true);
}
add_action('admin_enqueue_scripts', 'ceeducon_edition_admin_assets');

function ceeducon_edition_save(): void
{
    if (!ceeducon_edition_can_edit()) {
        wp_die(esc_html__('You are not allowed to edit the conference edition.', 'ceeducon-conference-settings'));
    }
    check_admin_referer('ceeducon_save_edition');

    $raw = isset($_POST['ceeducon_edition']) && is_array($_POST['ceeducon_edition'])
        ? wp_unslash($_POST['ceeducon_edition'])
        : [];
    $settings = ceeducon_edition_sanitize($raw);

    $timezone = new DateTimeZone($settings['timezone']);
    $start = DateTimeImmutable::createFromFormat('!Y-m-d H:i', $settings['start_date'] . ' ' . $settings['start_time'], $timezone);
    $end = DateTimeImmutable::createFromFormat('!Y-m-d H:i', $settings['end_date'] . ' ' . $settings['end_time'], $timezone);
    if ($start && $end && $end < $start) {
        add_settings_error('ceeducon-edition', 'invalid_range', __('The conference end must be after its start.', 'ceeducon-conference-settings'), 'error');
        set_transient('settings_errors', get_settings_errors(), 30);
        wp_safe_redirect(add_query_arg('settings-updated', 'false', admin_url('admin.php?page=ceeducon-edition')));
        exit;
    }

    if ($start && $end) {
        $settings['edition_year'] = (int) $start->format('Y');
        $settings['stat_1_value'] = (string) max(1, (int) $start->diff($end)->format('%a') + 1);
        if ($settings['event_title'] === '' || preg_match('/^CEEDUCON\s+\d{4}$/i', $settings['event_title'])) {
            $settings['event_title'] = 'CEEDUCON ' . $settings['edition_year'];
        }
    }

    update_option(CEEDUCON_EDITION_OPTION, $settings, false);

    $legacy_content = get_option('ceeducon_content', []);
    $legacy_content = is_array($legacy_content) ? $legacy_content : [];
    $legacy_content['event_day'] = ceeducon_edition_event_day();
    $legacy_content['event_month'] = ceeducon_edition_event_month();
    $legacy_content['event_row_1_value'] = $settings['venue_name'];
    $legacy_content['event_row_3_value'] = $settings['fee_text'];
    $legacy_content['event_row_4_value'] = $settings['registration_text'];
    $legacy_content['event_google_calendar_url'] = ceeducon_edition_google_calendar_url();
    $legacy_content['event_outlook_calendar_url'] = ceeducon_edition_outlook_calendar_url();
    foreach (range(1, 4) as $index) {
        $legacy_content['stat_' . $index . '_value'] = $settings['stat_' . $index . '_value'];
        $legacy_content['stat_' . $index . '_label'] = $settings['stat_' . $index . '_label'];
    }
    update_option('ceeducon_content', $legacy_content, false);
    wp_safe_redirect(add_query_arg('settings-updated', 'true', admin_url('admin.php?page=ceeducon-edition')));
    exit;
}
add_action('admin_post_ceeducon_save_edition', 'ceeducon_edition_save');

function ceeducon_edition_admin_notice(): void
{
    if (!current_user_can('activate_plugins') || function_exists('ceeducon_text_value')) {
        return;
    }

    echo '<div class="notice notice-warning"><p>';
    echo esc_html__('CEEDUCON Conference Edition is active, but its frontend integration requires the supplied CEEDUCON Programme theme.', 'ceeducon-conference-settings');
    echo '</p></div>';
}
add_action('admin_notices', 'ceeducon_edition_admin_notice');

function ceeducon_edition_field(string $key, string $label, string $type = 'text', string $help = ''): void
{
    $value = ceeducon_edition_get($key);
    ?>
    <label class="ceeducon-edition-field">
        <span><?php echo esc_html($label); ?></span>
        <?php if ($type === 'textarea') : ?>
            <textarea name="ceeducon_edition[<?php echo esc_attr($key); ?>]" rows="3"><?php echo esc_textarea((string) $value); ?></textarea>
        <?php else : ?>
            <input type="<?php echo esc_attr($type); ?>" name="ceeducon_edition[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr((string) $value); ?>"<?php echo in_array($key, ['edition_year', 'country_code'], true) ? ' maxlength="4"' : ''; ?> />
        <?php endif; ?>
        <?php if ($help !== '') : ?><small><?php echo esc_html($help); ?></small><?php endif; ?>
    </label>
    <?php
}

function ceeducon_edition_media_field(string $key, string $label, string $help): void
{
    $attachment_id = (int) ceeducon_edition_get($key);
    $preview = $attachment_id > 0 ? wp_get_attachment_image_url($attachment_id, 'medium') : '';
    ?>
    <div class="ceeducon-edition-field ceeducon-edition-media" data-media-field>
        <span><?php echo esc_html($label); ?></span>
        <input type="hidden" name="ceeducon_edition[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr((string) $attachment_id); ?>" data-media-id />
        <div class="ceeducon-edition-media__preview" data-media-preview><?php if ($preview) : ?><img src="<?php echo esc_url($preview); ?>" alt="" /><?php endif; ?></div>
        <div><button type="button" class="button" data-media-select><?php esc_html_e('Choose image', 'ceeducon-conference-settings'); ?></button> <button type="button" class="button-link-delete" data-media-remove<?php echo $attachment_id < 1 ? ' hidden' : ''; ?>><?php esc_html_e('Remove', 'ceeducon-conference-settings'); ?></button></div>
        <small><?php echo esc_html($help); ?></small>
    </div>
    <?php
}

function ceeducon_edition_render_admin(): void
{
    if (!ceeducon_edition_can_edit()) {
        return;
    }
    $days = ceeducon_edition_day_count();
    ?>
    <div class="wrap ceeducon-edition-admin">
        <h1><?php esc_html_e('CEEDUCON conference edition', 'ceeducon-conference-settings'); ?></h1>
        <p class="description"><?php esc_html_e('Change the facts that are different every year. The theme keeps the layout fixed and automatically reuses these values in the hero, calendar links, SEO and Gutenberg output.', 'ceeducon-conference-settings'); ?></p>
        <?php settings_errors(); ?>

        <div class="ceeducon-edition-summary">
            <span><?php echo esc_html((string) ceeducon_edition_get('event_title')); ?></span>
            <strong><?php echo esc_html(ceeducon_edition_full_date()); ?></strong>
            <p><?php echo esc_html((string) ceeducon_edition_get('venue_name')); ?> · <?php echo esc_html((string) ceeducon_edition_get('fee_text')); ?></p>
            <small><?php printf(esc_html(_n('%d conference day', '%d conference days', $days, 'ceeducon-conference-settings')), $days); ?></small>
        </div>

        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
            <input type="hidden" name="action" value="ceeducon_save_edition" />
            <?php wp_nonce_field('ceeducon_save_edition'); ?>

            <section class="ceeducon-edition-card">
                <h2><?php esc_html_e('Edition and date', 'ceeducon-conference-settings'); ?></h2>
                <div class="ceeducon-edition-grid">
                    <?php ceeducon_edition_field('event_title', __('Public event name', 'ceeducon-conference-settings')); ?>
                    <?php ceeducon_edition_field('country_label', __('Hero country label', 'ceeducon-conference-settings')); ?>
                    <?php ceeducon_edition_field('timezone', __('Timezone', 'ceeducon-conference-settings'), 'text', 'IANA name, for example Europe/Prague.'); ?>
                    <?php ceeducon_edition_field('start_date', __('Start date', 'ceeducon-conference-settings'), 'date'); ?>
                    <?php ceeducon_edition_field('start_time', __('Start time', 'ceeducon-conference-settings'), 'time'); ?>
                    <?php ceeducon_edition_field('end_date', __('End date', 'ceeducon-conference-settings'), 'date'); ?>
                    <?php ceeducon_edition_field('end_time', __('End time', 'ceeducon-conference-settings'), 'time'); ?>
                </div>
            </section>

            <section class="ceeducon-edition-card">
                <h2><?php esc_html_e('Venue and attendance', 'ceeducon-conference-settings'); ?></h2>
                <div class="ceeducon-edition-grid">
                    <?php ceeducon_edition_field('venue_name', __('Venue shown to visitors', 'ceeducon-conference-settings')); ?>
                    <?php ceeducon_edition_field('city_label', __('Public city name', 'ceeducon-conference-settings'), 'text', __('Used in headings and visitor-facing copy.', 'ceeducon-conference-settings')); ?>
                    <?php ceeducon_edition_field('venue_schema_name', __('Venue name for Google', 'ceeducon-conference-settings')); ?>
                    <?php ceeducon_edition_field('street_address', __('Street address', 'ceeducon-conference-settings')); ?>
                    <?php ceeducon_edition_field('address_locality', __('City / district', 'ceeducon-conference-settings')); ?>
                    <?php ceeducon_edition_field('postal_code', __('Postal code', 'ceeducon-conference-settings')); ?>
                    <?php ceeducon_edition_field('country_code', __('Country code', 'ceeducon-conference-settings')); ?>
                    <?php ceeducon_edition_field('fee_text', __('Fee information', 'ceeducon-conference-settings')); ?>
                    <?php ceeducon_edition_field('language', __('Conference language', 'ceeducon-conference-settings')); ?>
                    <?php ceeducon_edition_field('language_code', __('Language code for SEO', 'ceeducon-conference-settings'), 'text', __('For example en, cs or de.', 'ceeducon-conference-settings')); ?>
                    <?php ceeducon_edition_field('registration_text', __('Registration status', 'ceeducon-conference-settings')); ?>
                    <?php ceeducon_edition_field('registration_url', __('Registration URL', 'ceeducon-conference-settings'), 'url', 'May stay empty until registration opens.'); ?>
                </div>
            </section>

            <section class="ceeducon-edition-card">
                <h2><?php esc_html_e('Calendar and statistics', 'ceeducon-conference-settings'); ?></h2>
                <?php ceeducon_edition_field('calendar_description', __('Calendar description', 'ceeducon-conference-settings'), 'textarea'); ?>
                <div class="ceeducon-edition-grid ceeducon-edition-grid--stats">
                    <label class="ceeducon-edition-field">
                        <span><?php esc_html_e('Statistic 1 value', 'ceeducon-conference-settings'); ?></span>
                        <input type="text" value="<?php echo esc_attr((string) $days); ?>" readonly />
                        <small><?php esc_html_e('Derived automatically from the start and end dates.', 'ceeducon-conference-settings'); ?></small>
                    </label>
                    <?php ceeducon_edition_field('stat_1_label', __('Statistic 1 label', 'ceeducon-conference-settings')); ?>
                    <?php for ($index = 2; $index <= 4; $index++) : ?>
                        <?php ceeducon_edition_field('stat_' . $index . '_value', sprintf(__('Statistic %d value', 'ceeducon-conference-settings'), $index)); ?>
                        <?php ceeducon_edition_field('stat_' . $index . '_label', sprintf(__('Statistic %d label', 'ceeducon-conference-settings'), $index)); ?>
                    <?php endfor; ?>
                </div>
                <div class="ceeducon-edition-generated">
                    <p><strong><?php esc_html_e('Generated automatically:', 'ceeducon-conference-settings'); ?></strong> <?php esc_html_e('Google Calendar URL, Outlook Calendar URL, date labels and Event structured data.', 'ceeducon-conference-settings'); ?></p>
                </div>
            </section>

            <section class="ceeducon-edition-card">
                <h2><?php esc_html_e('Reusable annual text tokens', 'ceeducon-conference-settings'); ?></h2>
                <p><?php esc_html_e('Use these placeholders in CEEDUCON text fields or Gutenberg blocks. Visitors always see the current saved values.', 'ceeducon-conference-settings'); ?></p>
                <p><code>{{event_title}}</code> <code>{{year}}</code> <code>{{date}}</code> <code>{{date_short}}</code> <code>{{venue}}</code> <code>{{city}}</code> <code>{{fee}}</code> <code>{{registration}}</code></p>
            </section>

            <section class="ceeducon-edition-card">
                <h2><?php esc_html_e('Programme review', 'ceeducon-conference-settings'); ?></h2>
                <p><?php esc_html_e('Changing the edition does not rewrite sessions, rooms, speakers or themes. Review the structured programme before publishing a new year.', 'ceeducon-conference-settings'); ?></p>
                <?php if (function_exists('ceeducon_admin_menu')) : ?>
                    <p><a class="button" href="<?php echo esc_url(admin_url('admin.php?page=ceeducon-content')); ?>"><?php esc_html_e('Open CEEDUCON programme content', 'ceeducon-conference-settings'); ?></a></p>
                <?php endif; ?>
            </section>

            <section class="ceeducon-edition-card">
                <h2><?php esc_html_e('Annual visuals', 'ceeducon-conference-settings'); ?></h2>
                <div class="ceeducon-edition-grid">
                    <?php ceeducon_edition_media_field('hero_image_id', __('Homepage hero image', 'ceeducon-conference-settings'), __('Leave empty to keep the image supplied by the theme or page.', 'ceeducon-conference-settings')); ?>
                    <?php ceeducon_edition_media_field('social_image_id', __('Social sharing image', 'ceeducon-conference-settings'), __('Used by the theme fallback for Open Graph and Event structured data.', 'ceeducon-conference-settings')); ?>
                    <?php ceeducon_edition_field('hero_image_alt', __('Hero image alt text', 'ceeducon-conference-settings')); ?>
                </div>
            </section>

            <?php submit_button(__('Save conference edition', 'ceeducon-conference-settings')); ?>
        </form>
    </div>
    <?php
}

function ceeducon_edition_activation(): void
{
    if (get_option(CEEDUCON_EDITION_OPTION, null) === null) {
        $defaults = ceeducon_edition_defaults();
        $legacy = get_option('ceeducon_content', []);
        if (is_array($legacy)) {
            $mapping = [
                'event_row_1_value' => 'venue_name',
                'event_row_3_value' => 'fee_text',
                'event_row_4_value' => 'registration_text',
                'stat_1_value' => 'stat_1_value',
                'stat_1_label' => 'stat_1_label',
                'stat_2_value' => 'stat_2_value',
                'stat_2_label' => 'stat_2_label',
                'stat_3_value' => 'stat_3_value',
                'stat_3_label' => 'stat_3_label',
                'stat_4_value' => 'stat_4_value',
                'stat_4_label' => 'stat_4_label',
                'home_hero_image_alt' => 'hero_image_alt',
            ];
            foreach ($mapping as $legacy_key => $new_key) {
                if (isset($legacy[$legacy_key]) && $legacy[$legacy_key] !== '') {
                    $defaults[$new_key] = sanitize_text_field((string) $legacy[$legacy_key]);
                }
            }
        }
        add_option(CEEDUCON_EDITION_OPTION, $defaults, '', false);
    }
}
register_activation_hook(__FILE__, 'ceeducon_edition_activation');
