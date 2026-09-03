<?php
/**
 * Everything that changes with each conference edition: dates, venue, fee,
 * registration, the annual statistics and the two annual images. One saved
 * value here feeds the hero, the calendar links, the SEO description, the
 * structured data and every {{token}} used in the site copy.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ceeducon_cc_edition_defaults(): array
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
        'registration_text' => 'Registration is open',
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

function ceeducon_cc_edition_settings(): array
{
    $stored = get_option(CEEDUCON_CC_EDITION_OPTION, []);
    return wp_parse_args(is_array($stored) ? $stored : [], ceeducon_cc_edition_defaults());
}

function ceeducon_cc_edition_get(string $key, $default = '')
{
    $settings = ceeducon_cc_edition_settings();
    return array_key_exists($key, $settings) ? $settings[$key] : $default;
}

/**
 * The theme asks whether an edition plugin is present by looking for this
 * function: when it exists the annual keys drop out of the raw text screen,
 * because they are owned here. Older plugin still active? Leave its copy alone.
 */
if (!function_exists('ceeducon_edition_get')) {
    function ceeducon_edition_get(string $key, $default = '')
    {
        return ceeducon_cc_edition_get($key, $default);
    }
}

function ceeducon_cc_edition_datetime(string $date_key, string $time_key): ?DateTimeImmutable
{
    $date = (string) ceeducon_cc_edition_get($date_key);
    $time = (string) ceeducon_cc_edition_get($time_key);

    try {
        $timezone = new DateTimeZone((string) ceeducon_cc_edition_get('timezone', 'Europe/Prague'));
        $value = DateTimeImmutable::createFromFormat('!Y-m-d H:i', $date . ' ' . $time, $timezone);
        return $value instanceof DateTimeImmutable ? $value : null;
    } catch (Exception $exception) {
        return null;
    }
}

function ceeducon_cc_edition_start(): ?DateTimeImmutable
{
    return ceeducon_cc_edition_datetime('start_date', 'start_time');
}

function ceeducon_cc_edition_end(): ?DateTimeImmutable
{
    return ceeducon_cc_edition_datetime('end_date', 'end_time');
}

function ceeducon_cc_edition_year(): int
{
    $start = ceeducon_cc_edition_start();
    return $start ? (int) $start->format('Y') : (int) ceeducon_cc_edition_get('edition_year', 2026);
}

function ceeducon_cc_edition_day_count(): int
{
    $start = ceeducon_cc_edition_start();
    $end = ceeducon_cc_edition_end();
    return ($start && $end && $end >= $start) ? max(1, (int) $start->diff($end)->format('%a') + 1) : 1;
}

function ceeducon_cc_month_name(int $month, bool $short = false): string
{
    $long = [1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    $name = $long[$month] ?? '';
    return $short ? substr($name, 0, 3) : $name;
}

function ceeducon_cc_edition_event_day(): string
{
    $start = ceeducon_cc_edition_start();
    $end = ceeducon_cc_edition_end();
    if (!$start || !$end) {
        return '';
    }

    if ($start->format('Y-m') === $end->format('Y-m')) {
        return $start->format('j') === $end->format('j')
            ? $start->format('j')
            : $start->format('j') . '–' . $end->format('j');
    }

    return $start->format('j') . ' ' . ceeducon_cc_month_name((int) $start->format('n'), true)
        . '–' . $end->format('j') . ' ' . ceeducon_cc_month_name((int) $end->format('n'), true);
}

function ceeducon_cc_edition_event_month(): string
{
    $start = ceeducon_cc_edition_start();
    $end = ceeducon_cc_edition_end();
    if (!$start || !$end) {
        return (string) ceeducon_cc_edition_get('edition_year');
    }

    if ($start->format('Y-m') === $end->format('Y-m')) {
        return ceeducon_cc_month_name((int) $start->format('n'), true) . ' ' . $start->format('Y');
    }

    return $end->format('Y');
}

function ceeducon_cc_edition_full_date(): string
{
    $start = ceeducon_cc_edition_start();
    $end = ceeducon_cc_edition_end();
    if (!$start || !$end) {
        return '';
    }

    if ($start->format('Y-m') === $end->format('Y-m')) {
        $days = $start->format('j') === $end->format('j')
            ? $start->format('j')
            : $start->format('j') . '–' . $end->format('j');
        return $days . ' ' . ceeducon_cc_month_name((int) $start->format('n')) . ' ' . $start->format('Y');
    }

    return $start->format('j') . ' ' . ceeducon_cc_month_name((int) $start->format('n')) . ' '
        . $start->format('Y') . '–' . $end->format('j') . ' '
        . ceeducon_cc_month_name((int) $end->format('n')) . ' ' . $end->format('Y');
}

function ceeducon_cc_edition_short_date(): string
{
    return trim(str_replace((string) ceeducon_cc_edition_year(), '', ceeducon_cc_edition_full_date()));
}

/**
 * Lowercase the first letter for tokens used mid-sentence, leaving a value that
 * opens with an acronym or a number alone (e.g. "EUR 0", "CZK 500").
 */
function ceeducon_cc_lcfirst(string $value): string
{
    if ($value === '' || !preg_match('/^\p{Lu}\p{Ll}/u', $value)) {
        return $value;
    }

    return function_exists('mb_strtolower')
        ? mb_strtolower(mb_substr($value, 0, 1)) . mb_substr($value, 1)
        : lcfirst($value);
}

/**
 * A default only applies while a value has never been saved, and this screen
 * saves every field at once — so an install set up in spring still carries the
 * defaults of that day, "Registration opens in September" among them. Rewrite
 * such a value only while it is byte-for-byte the superseded default, which
 * means nobody ever edited it. Running twice changes nothing.
 */
function ceeducon_cc_edition_migrate(): void
{
    $settings = get_option(CEEDUCON_CC_EDITION_OPTION, []);
    if (!is_array($settings) || $settings === []) {
        return;
    }

    $superseded = [
        'registration_text' => ['Registration opens in September' => 'Registration is open'],
    ];

    $changed = false;
    foreach ($superseded as $key => $replacements) {
        $current = isset($settings[$key]) ? (string) $settings[$key] : '';
        if ($current !== '' && isset($replacements[$current])) {
            $settings[$key] = $replacements[$current];
            $changed = true;
        }
    }

    if ($changed) {
        update_option(CEEDUCON_CC_EDITION_OPTION, $settings);
    }
}
add_action('admin_init', 'ceeducon_cc_edition_migrate');

/** The theme steps aside from its own stopgap once this exists. */
if (!function_exists('ceeducon_edition_migrate_superseded_defaults')) {
    function ceeducon_edition_migrate_superseded_defaults(): void
    {
        ceeducon_cc_edition_migrate();
    }
}

/* ---------------------------------------------------------------------------
 * Tokens and derived values
 * ------------------------------------------------------------------------ */

function ceeducon_cc_tokens(): array
{
    return [
        '{{event_title}}' => (string) ceeducon_cc_edition_get('event_title'),
        '{{year}}' => (string) ceeducon_cc_edition_year(),
        '{{date}}' => ceeducon_cc_edition_full_date(),
        '{{date_short}}' => ceeducon_cc_edition_short_date(),
        '{{venue}}' => (string) ceeducon_cc_edition_get('venue_name'),
        '{{city}}' => (string) ceeducon_cc_edition_get('city_label'),
        '{{fee}}' => (string) ceeducon_cc_edition_get('fee_text'),
        '{{fee_lower}}' => ceeducon_cc_lcfirst((string) ceeducon_cc_edition_get('fee_text')),
        '{{registration}}' => (string) ceeducon_cc_edition_get('registration_text'),
    ];
}

function ceeducon_cc_token_filter(array $tokens): array
{
    return array_merge($tokens, ceeducon_cc_tokens());
}
add_filter('ceeducon_content_tokens', 'ceeducon_cc_token_filter', 30);

function ceeducon_cc_edition_location(): string
{
    return implode(', ', array_filter([
        (string) ceeducon_cc_edition_get('venue_name'),
        (string) ceeducon_cc_edition_get('street_address'),
        (string) ceeducon_cc_edition_get('address_locality'),
        (string) ceeducon_cc_edition_get('country_code'),
    ]));
}

function ceeducon_cc_google_calendar_url(): string
{
    $start = ceeducon_cc_edition_start();
    $end = ceeducon_cc_edition_end();
    if (!$start || !$end) {
        return '';
    }

    $utc = new DateTimeZone('UTC');
    return add_query_arg([
        'action' => 'TEMPLATE',
        'text' => (string) ceeducon_cc_edition_get('event_title'),
        'dates' => $start->setTimezone($utc)->format('Ymd\THis\Z') . '/' . $end->setTimezone($utc)->format('Ymd\THis\Z'),
        'details' => (string) ceeducon_cc_edition_get('calendar_description'),
        'location' => ceeducon_cc_edition_location(),
        'ctz' => (string) ceeducon_cc_edition_get('timezone'),
    ], 'https://calendar.google.com/calendar/render');
}

function ceeducon_cc_outlook_calendar_url(): string
{
    $start = ceeducon_cc_edition_start();
    $end = ceeducon_cc_edition_end();
    if (!$start || !$end) {
        return '';
    }

    return add_query_arg([
        'path' => '/calendar/action/compose',
        'rru' => 'addevent',
        'subject' => (string) ceeducon_cc_edition_get('event_title'),
        'startdt' => $start->format(DateTimeInterface::ATOM),
        'enddt' => $end->format(DateTimeInterface::ATOM),
        'body' => (string) ceeducon_cc_edition_get('calendar_description'),
        'location' => ceeducon_cc_edition_location(),
    ], 'https://outlook.live.com/calendar/0/deeplink/compose');
}

/** The text keys this screen owns; the raw text editor hides every one of them. */
function ceeducon_cc_edition_owned_values(): array
{
    $year = (string) ceeducon_cc_edition_year();
    $title = (string) ceeducon_cc_edition_get('event_title');
    $date = ceeducon_cc_edition_full_date();
    $venue = (string) ceeducon_cc_edition_get('venue_name');
    $city = (string) ceeducon_cc_edition_get('city_label');

    return [
        'home_hero_kicker' => $title . ' · ' . (string) ceeducon_cc_edition_get('country_label'),
        'event_day' => ceeducon_cc_edition_event_day(),
        'event_month' => ceeducon_cc_edition_event_month(),
        'event_row_1_value' => $venue,
        'event_row_3_value' => (string) ceeducon_cc_edition_get('fee_text'),
        'event_row_4_value' => (string) ceeducon_cc_edition_get('registration_text'),
        'registration_url' => (string) ceeducon_cc_edition_get('registration_url'),
        'event_google_calendar_url' => ceeducon_cc_google_calendar_url(),
        'event_outlook_calendar_url' => ceeducon_cc_outlook_calendar_url(),
        'stat_1_value' => (string) ceeducon_cc_edition_day_count(),
        'stat_1_label' => (string) ceeducon_cc_edition_get('stat_1_label'),
        'stat_2_value' => (string) ceeducon_cc_edition_get('stat_2_value'),
        'stat_2_label' => (string) ceeducon_cc_edition_get('stat_2_label'),
        'stat_3_value' => (string) ceeducon_cc_edition_get('stat_3_value'),
        'stat_3_label' => (string) ceeducon_cc_edition_get('stat_3_label'),
        'stat_4_value' => (string) ceeducon_cc_edition_get('stat_4_value'),
        'stat_4_label' => (string) ceeducon_cc_edition_get('stat_4_label'),
        'about_card_label' => $title,
        'about_card_title' => $date . ' · ' . $city,
        'home_prog_kicker' => 'Programme ' . $year,
        'footer_tagline' => 'Central European Conference on Internationalisation of Higher Education.<br />' . $date . ' · ' . $venue,
        'footer_copyright' => '© ' . $year . ' DZS — Czech National Agency for International Education and Research',
        'seo_home_description' => $title . ' — Central European Conference on Internationalisation of Higher Education. ' . $date . ', ' . $venue . '.',
        'seo_programme_description' => 'Browse the interactive ' . $title . ' programme for ' . $date . ' at ' . $venue . ': sessions, workshops and speakers.',
        'seo_practical_description' => 'Practical information for ' . $title . ': venue, transport, accessibility and accommodation in ' . $city . '.',
        'seo_speakers_description' => 'Practical information, milestones and support for confirmed ' . $title . ' speakers.',
        'seo_media_description' => $title . ' media kit: official visuals, brand assets, press information and media contact.',
        'seo_contact_description' => 'Contact the ' . $title . ' organising team about registration, programme, speakers, partnerships or media requests.',
    ];
}

function ceeducon_cc_text_filter(string $value, string $key, string $default): string
{
    $values = ceeducon_cc_edition_owned_values();
    $value = array_key_exists($key, $values) && $values[$key] !== '' ? (string) $values[$key] : $value;

    // Copy written for an earlier edition still names the old year, date or
    // venue. Rewrite those in passing — but never inside programme data, an
    // archive reference or a URL, where the old value is the correct one.
    if ($key !== CEEDUCON_CC_PROGRAMME_KEY && !str_contains($key, 'past') && !str_ends_with($key, '_url')) {
        $value = str_replace(
            ['CEEDUCON 2026', '1–2 December 2026', '1–2 December', 'O2 universum Prague'],
            [
                (string) ceeducon_cc_edition_get('event_title'),
                ceeducon_cc_edition_full_date(),
                ceeducon_cc_edition_short_date(),
                (string) ceeducon_cc_edition_get('venue_name'),
            ],
            $value
        );
        $value = (string) preg_replace('/\b2026\b/', (string) ceeducon_cc_edition_year(), $value);
    }

    return $value;
}
add_filter('ceeducon_text_value', 'ceeducon_cc_text_filter', 30, 3);

function ceeducon_cc_section_filter(array $attributes, string $section): array
{
    $title = (string) ceeducon_cc_edition_get('event_title');
    $date = ceeducon_cc_edition_full_date();
    $venue = (string) ceeducon_cc_edition_get('venue_name');

    if ($section === 'hero') {
        $attributes['kicker'] = $title . ' · ' . (string) ceeducon_cc_edition_get('country_label');
        $attributes['eventDay'] = ceeducon_cc_edition_event_day();
        $attributes['eventMonth'] = ceeducon_cc_edition_event_month();
        $attributes['eventRows'] = [
            ['label' => 'Venue', 'value' => $venue],
            ['label' => 'Fee', 'value' => (string) ceeducon_cc_edition_get('fee_text')],
            ['label' => 'Registration', 'value' => (string) ceeducon_cc_edition_get('registration_text')],
        ];
        $attributes['registrationUrl'] = (string) ceeducon_cc_edition_get('registration_url');
        $attributes['googleCalendarText'] = 'Google Calendar';
        $attributes['googleCalendarUrl'] = ceeducon_cc_google_calendar_url();
        $attributes['outlookCalendarText'] = 'Outlook Calendar';
        $attributes['outlookCalendarUrl'] = ceeducon_cc_outlook_calendar_url();

        $image_id = (int) ceeducon_cc_edition_get('hero_image_id');
        if ($image_id > 0) {
            $attributes['imageId'] = $image_id;
            $attributes['imageUrl'] = (string) wp_get_attachment_image_url($image_id, 'full');
            $attributes['imageAlt'] = (string) ceeducon_cc_edition_get('hero_image_alt');
        }
    }

    if ($section === 'page-hero') {
        $attributes['cardLabel'] = $title;
        $attributes['cardTitle'] = $date . ' · ' . (string) ceeducon_cc_edition_get('city_label');
    }

    if ($section === 'cta') {
        $attributes['noteLabel'] = $title;
        $attributes['noteTitle'] = $date . ' · ' . $venue;
        $attributes['noteText'] = (string) ceeducon_cc_edition_get('registration_text');
    }

    if ($section === 'schedule-overview') {
        $attributes['kicker'] = 'Programme ' . (string) ceeducon_cc_edition_year();
        $items = isset($attributes['items']) && is_array($attributes['items']) ? $attributes['items'] : [];
        $start = ceeducon_cc_edition_start();
        $end = ceeducon_cc_edition_end();
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
add_filter('ceeducon_section_attributes', 'ceeducon_cc_section_filter', 30, 2);

function ceeducon_cc_home_hero_image(array $image): array
{
    $attachment_id = (int) ceeducon_cc_edition_get('hero_image_id');
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
        'alt' => (string) ceeducon_cc_edition_get('hero_image_alt'),
    ];
}
add_filter('ceeducon_home_hero_image', 'ceeducon_cc_home_hero_image', 30);

function ceeducon_cc_hero_editor_data(array $data): array
{
    $image_id = (int) ceeducon_cc_edition_get('hero_image_id');
    $image_url = $image_id > 0 ? wp_get_attachment_image_url($image_id, 'full') : '';

    return [
        'managed' => true,
        'settingsUrl' => admin_url('admin.php?page=ceeducon-cc-edition'),
        'attributes' => [
            'kicker' => (string) ceeducon_cc_edition_get('event_title') . ' · ' . (string) ceeducon_cc_edition_get('country_label'),
            'eventDay' => ceeducon_cc_edition_event_day(),
            'eventMonth' => ceeducon_cc_edition_event_month(),
            'eventRows' => [
                ['label' => 'Venue', 'value' => (string) ceeducon_cc_edition_get('venue_name')],
                ['label' => 'Fee', 'value' => (string) ceeducon_cc_edition_get('fee_text')],
                ['label' => 'Registration', 'value' => (string) ceeducon_cc_edition_get('registration_text')],
            ],
            'googleCalendarText' => 'Google Calendar',
            'googleCalendarUrl' => ceeducon_cc_google_calendar_url(),
            'outlookCalendarText' => 'Outlook Calendar',
            'outlookCalendarUrl' => ceeducon_cc_outlook_calendar_url(),
            'imageId' => $image_id,
            'imageUrl' => is_string($image_url) ? $image_url : '',
            'imageAlt' => (string) ceeducon_cc_edition_get('hero_image_alt'),
        ],
    ];
}
add_filter('ceeducon_hero_editor_data', 'ceeducon_cc_hero_editor_data', 30);

function ceeducon_cc_schema_filter(array $schema): array
{
    $start = ceeducon_cc_edition_start();
    $end = ceeducon_cc_edition_end();

    $schema['@id'] = home_url('/#ceeducon-' . sanitize_title((string) ceeducon_cc_edition_year()));
    $schema['name'] = (string) ceeducon_cc_edition_get('event_title') . ' — Central European Conference on Internationalisation of Higher Education';
    if ($start) {
        $schema['startDate'] = $start->format(DateTimeInterface::ATOM);
    }
    if ($end) {
        $schema['endDate'] = $end->format(DateTimeInterface::ATOM);
    }
    $schema['inLanguage'] = (string) ceeducon_cc_edition_get('language_code', 'en');
    $schema['location'] = [
        '@type' => 'Place',
        'name' => (string) ceeducon_cc_edition_get('venue_schema_name'),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => (string) ceeducon_cc_edition_get('street_address'),
            'addressLocality' => (string) ceeducon_cc_edition_get('address_locality'),
            'postalCode' => (string) ceeducon_cc_edition_get('postal_code'),
            'addressCountry' => (string) ceeducon_cc_edition_get('country_code'),
        ],
    ];

    // A free event still deserves an Offer — it is what lets a search result
    // say "Free", and it is true whether or not registration is open yet.
    $registration_url = (string) ceeducon_cc_edition_get('registration_url');
    $fee_text = (string) ceeducon_cc_edition_get('fee_text');
    $is_free = stripos($fee_text, 'free') !== false || trim($fee_text) === '0';

    if ($is_free || $registration_url !== '') {
        $offer = ['@type' => 'Offer', 'availability' => 'https://schema.org/InStock'];
        if ($is_free) {
            $offer['price'] = '0';
            $offer['priceCurrency'] = (string) apply_filters('ceeducon_cc_price_currency', 'EUR');
        }
        if ($registration_url !== '') {
            $offer['url'] = esc_url_raw($registration_url);
        }
        $schema['offers'] = $offer;
    } else {
        unset($schema['offers']);
    }

    $social_id = (int) ceeducon_cc_edition_get('social_image_id');
    if ($social_id > 0) {
        $url = wp_get_attachment_image_url($social_id, 'full');
        if (is_string($url) && $url !== '') {
            $schema['image'] = [$url];
        }
    }

    return $schema;
}
add_filter('ceeducon_event_schema', 'ceeducon_cc_schema_filter', 30);

function ceeducon_cc_social_image_filter(array $image): array
{
    $attachment_id = (int) ceeducon_cc_edition_get('social_image_id');
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
        'alt' => $alt !== '' ? $alt : (string) ceeducon_cc_edition_get('event_title') . ' conference visual',
    ];
}
add_filter('ceeducon_social_image', 'ceeducon_cc_social_image_filter', 30);

/* ---------------------------------------------------------------------------
 * Saving
 * ------------------------------------------------------------------------ */

function ceeducon_cc_sanitize_date($value, string $fallback): string
{
    $value = sanitize_text_field((string) $value);
    $parts = explode('-', $value);
    if (count($parts) !== 3 || !checkdate((int) $parts[1], (int) $parts[2], (int) $parts[0])) {
        return $fallback;
    }
    return $value;
}

function ceeducon_cc_edition_sanitize(array $input): array
{
    $defaults = ceeducon_cc_edition_defaults();
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
    $output['start_date'] = ceeducon_cc_sanitize_date($input['start_date'] ?? '', $defaults['start_date']);
    $output['end_date'] = ceeducon_cc_sanitize_date($input['end_date'] ?? '', $defaults['end_date']);

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

    // "CEEDUCON 2026" is a generated name, not a chosen one: keep it in step
    // with the dates. A title someone actually wrote is left alone.
    if ($output['event_title'] === '' || preg_match('/^CEEDUCON\s+\d{4}$/i', $output['event_title'])) {
        $output['event_title'] = 'CEEDUCON ' . $output['edition_year'];
    }

    return $output;
}

function ceeducon_cc_edition_save(): void
{
    if (!ceeducon_cc_can_edit()) {
        wp_die(esc_html__('Nemáte oprávnění měnit nastavení ročníku.', 'ceeducon-cc'));
    }
    check_admin_referer('ceeducon_cc_save_edition');

    $raw = isset($_POST['edition']) && is_array($_POST['edition']) ? wp_unslash($_POST['edition']) : [];
    $settings = ceeducon_cc_edition_sanitize($raw);

    $timezone = new DateTimeZone($settings['timezone']);
    $start = DateTimeImmutable::createFromFormat('!Y-m-d H:i', $settings['start_date'] . ' ' . $settings['start_time'], $timezone);
    $end = DateTimeImmutable::createFromFormat('!Y-m-d H:i', $settings['end_date'] . ' ' . $settings['end_time'], $timezone);

    if ($start && $end && $end < $start) {
        wp_safe_redirect(add_query_arg('ceeducon-notice', 'range', ceeducon_cc_page_url('ceeducon-cc-edition')));
        exit;
    }

    if ($start && $end) {
        $settings['edition_year'] = (int) $start->format('Y');
        $settings['stat_1_value'] = (string) max(1, (int) $start->diff($end)->format('%a') + 1);
    }

    update_option(CEEDUCON_CC_EDITION_OPTION, $settings, false);

    // Some of the theme's own copy is stored rather than filtered; keep the
    // stored side in step so a template that reads it directly stays right.
    $content = get_option(CEEDUCON_CC_CONTENT_OPTION, []);
    $content = is_array($content) ? $content : [];
    $content['event_day'] = ceeducon_cc_edition_event_day();
    $content['event_month'] = ceeducon_cc_edition_event_month();
    $content['event_row_1_value'] = $settings['venue_name'];
    $content['event_row_3_value'] = $settings['fee_text'];
    $content['event_row_4_value'] = $settings['registration_text'];
    $content['event_google_calendar_url'] = ceeducon_cc_google_calendar_url();
    $content['event_outlook_calendar_url'] = ceeducon_cc_outlook_calendar_url();
    $content['registration_url'] = $settings['registration_url'];
    foreach (range(1, 4) as $index) {
        $content['stat_' . $index . '_value'] = $settings['stat_' . $index . '_value'];
        $content['stat_' . $index . '_label'] = $settings['stat_' . $index . '_label'];
    }
    update_option(CEEDUCON_CC_CONTENT_OPTION, $content, false);

    wp_safe_redirect(add_query_arg('ceeducon-notice', 'saved', ceeducon_cc_page_url('ceeducon-cc-edition')));
    exit;
}
add_action('admin_post_ceeducon_cc_save_edition', 'ceeducon_cc_edition_save');
