<?php
/**
 * The programme model: one JSON document holding the event, its rooms, its
 * themes, the session formats and types, and two days of time slots.
 *
 * It is stored inside the same option the theme reads, under the same key the
 * theme has always used, so the editor and the front end can never drift.
 */

if (!defined('ABSPATH')) {
    exit;
}

/** Break kinds the theme's renderer understands. */
function ceeducon_cc_break_kinds(): array
{
    return [
        'registration' => __('Registrace', 'ceeducon-cc'),
        'coffee' => __('Přestávka na kávu', 'ceeducon-cc'),
        'lunch' => __('Oběd', 'ceeducon-cc'),
    ];
}

function ceeducon_cc_prog_bundled(): array
{
    if (function_exists('ceeducon_default_programme_json')) {
        $data = json_decode(ceeducon_default_programme_json(), true);
        if (is_array($data)) {
            return $data;
        }
    }
    return [];
}

function ceeducon_cc_prog_stored_json(): string
{
    $content = get_option(CEEDUCON_CC_CONTENT_OPTION, []);
    if (is_array($content) && isset($content[CEEDUCON_CC_PROGRAMME_KEY])) {
        return (string) $content[CEEDUCON_CC_PROGRAMME_KEY];
    }
    return '';
}

/**
 * Reads the programme the way the theme does — saved copy first, the file
 * bundled with the theme as the fallback — and fills the gaps a copy saved by
 * an older editor cannot carry (abstracts, session types) from that file.
 */
function ceeducon_cc_prog_load(): array
{
    $bundled = ceeducon_cc_prog_bundled();
    $stored = ceeducon_cc_prog_stored_json();
    $data = $stored !== '' ? json_decode($stored, true) : null;

    if (is_array($data) && !empty($data['days'])) {
        if (function_exists('ceeducon_backfill_programme')) {
            $data = ceeducon_backfill_programme(ceeducon_cc_prog_normalise($data), $bundled);
        }
        return ceeducon_cc_prog_normalise($data);
    }

    return ceeducon_cc_prog_normalise($bundled);
}

function ceeducon_cc_prog_taxonomy(array $rows, string $fallback_color): array
{
    $out = [];
    foreach ($rows as $row) {
        if (is_string($row) && $row !== '') {
            $out[] = ['id' => $row, 'label' => $row, 'color' => $fallback_color];
            continue;
        }
        if (!is_array($row) || (string) ($row['id'] ?? '') === '') {
            continue;
        }
        $out[] = [
            'id' => (string) $row['id'],
            'label' => (string) ($row['label'] ?? $row['id']),
            'color' => (string) ($row['color'] ?? $fallback_color),
        ];
    }
    return $out;
}

/** Fills in every key the editor and the front end expect, so nothing is undefined. */
function ceeducon_cc_prog_normalise(array $d): array
{
    $event = is_array($d['event'] ?? null) ? $d['event'] : [];
    $d['event'] = [
        'title' => (string) ($event['title'] ?? ''),
        'dates' => array_values(array_filter(array_map('strval', (array) ($event['dates'] ?? [])))),
        'location' => (string) ($event['location'] ?? ''),
        'timezone' => (string) ($event['timezone'] ?? 'Europe/Prague'),
    ];

    $d['rooms'] = array_values(array_filter(array_map(
        static fn($room): string => trim((string) $room),
        (array) ($d['rooms'] ?? [])
    ), static fn(string $room): bool => $room !== ''));

    $themes = [];
    foreach ((array) ($d['themes'] ?? []) as $theme) {
        if (!is_array($theme) || (string) ($theme['id'] ?? '') === '') {
            continue;
        }
        $themes[] = [
            'id' => (string) $theme['id'],
            'label' => (string) ($theme['label'] ?? $theme['id']),
            'color' => (string) ($theme['color'] ?? '#0d5e9d'),
            'softColor' => (string) ($theme['softColor'] ?? '#d9e2f3'),
        ];
    }
    $d['themes'] = $themes;
    $d['formats'] = ceeducon_cc_prog_taxonomy((array) ($d['formats'] ?? []), '#0d5e9d');
    $d['types'] = ceeducon_cc_prog_taxonomy((array) ($d['types'] ?? []), '#0d5e9d');

    $days = [];
    foreach ((array) ($d['days'] ?? []) as $day) {
        if (!is_array($day)) {
            continue;
        }

        $slots = [];
        foreach ((array) ($day['slots'] ?? []) as $slot) {
            if (!is_array($slot)) {
                continue;
            }
            $type = ($slot['type'] ?? '') === 'break' ? 'break' : 'sessions';
            $row = [
                'id' => (string) ($slot['id'] ?? ''),
                'start' => (string) ($slot['start'] ?? ''),
                'end' => (string) ($slot['end'] ?? ''),
                'type' => $type,
            ];

            if ($type === 'break') {
                $row['title'] = (string) ($slot['title'] ?? '');
                $row['break'] = array_key_exists((string) ($slot['break'] ?? ''), ceeducon_cc_break_kinds())
                    ? (string) $slot['break']
                    : 'coffee';
                // The grid reads this to stretch a break band across every room column.
                $row['span'] = (string) ($slot['span'] ?? 'all');
                $row['sessions'] = [];
            } else {
                $sessions = [];
                foreach ((array) ($slot['sessions'] ?? []) as $session) {
                    if (!is_array($session)) {
                        continue;
                    }
                    $sessions[] = [
                        'title' => (string) ($session['title'] ?? ''),
                        'rooms' => array_values(array_filter(array_map('strval', (array) ($session['rooms'] ?? [])))),
                        'theme' => (string) ($session['theme'] ?? ''),
                        'speakers' => array_values(array_filter(array_map(
                            static fn($s): string => trim((string) $s),
                            (array) ($session['speakers'] ?? [])
                        ), static fn(string $s): bool => $s !== '')),
                        'format' => (string) ($session['format'] ?? ''),
                        'type' => (string) ($session['type'] ?? ''),
                        'abstract' => (string) ($session['abstract'] ?? ''),
                    ];
                }
                $row['sessions'] = $sessions;
            }

            if ($row['id'] === '') {
                $row['id'] = 'slot-' . substr(md5($row['start'] . $row['end'] . count($slots) . wp_rand()), 0, 8);
            }
            $slots[] = $row;
        }

        $days[] = [
            'date' => (string) ($day['date'] ?? ''),
            'label' => (string) ($day['label'] ?? ''),
            'title' => (string) ($day['title'] ?? ''),
            'slots' => $slots,
        ];
    }
    $d['days'] = $days;

    return $d;
}

/** Same shape, but every string has been through WordPress's sanitisers. */
function ceeducon_cc_prog_sanitize(array $d): array
{
    $d = ceeducon_cc_prog_normalise($d);
    $text = static fn($v): string => sanitize_text_field((string) $v);
    $hex = static fn($v, string $fallback): string => sanitize_hex_color((string) $v) ?: $fallback;

    $d['event']['title'] = $text($d['event']['title']);
    $d['event']['location'] = $text($d['event']['location']);
    $d['event']['timezone'] = $text($d['event']['timezone']);
    $d['event']['dates'] = array_map($text, $d['event']['dates']);
    $d['rooms'] = array_values(array_unique(array_filter(array_map($text, $d['rooms']))));

    foreach ($d['themes'] as $i => $theme) {
        $d['themes'][$i] = [
            'id' => sanitize_key($theme['id']),
            'label' => $text($theme['label']),
            'color' => $hex($theme['color'], '#0d5e9d'),
            'softColor' => $hex($theme['softColor'], '#d9e2f3'),
        ];
    }
    foreach (['formats', 'types'] as $taxonomy) {
        foreach ($d[$taxonomy] as $i => $row) {
            $d[$taxonomy][$i] = [
                'id' => sanitize_key($row['id']),
                'label' => $text($row['label']),
                'color' => $hex($row['color'], '#0d5e9d'),
            ];
        }
        $d[$taxonomy] = array_values(array_filter($d[$taxonomy], static fn(array $r): bool => $r['id'] !== ''));
    }
    $d['themes'] = array_values(array_filter($d['themes'], static fn(array $r): bool => $r['id'] !== ''));

    foreach ($d['days'] as $di => $day) {
        $d['days'][$di]['date'] = $text($day['date']);
        $d['days'][$di]['label'] = $text($day['label']);
        $d['days'][$di]['title'] = $text($day['title']);

        foreach ($day['slots'] as $si => $slot) {
            $d['days'][$di]['slots'][$si]['id'] = $text($slot['id']);
            $d['days'][$di]['slots'][$si]['start'] = ceeducon_cc_prog_time($slot['start']);
            $d['days'][$di]['slots'][$si]['end'] = ceeducon_cc_prog_time($slot['end']);

            if ($slot['type'] === 'break') {
                $d['days'][$di]['slots'][$si]['title'] = $text($slot['title']);
                $d['days'][$di]['slots'][$si]['span'] = sanitize_key($slot['span']) ?: 'all';
                continue;
            }

            foreach ($slot['sessions'] as $ei => $session) {
                $d['days'][$di]['slots'][$si]['sessions'][$ei] = [
                    'title' => $text($session['title']),
                    'rooms' => array_values(array_filter(array_map($text, $session['rooms']))),
                    'theme' => sanitize_key($session['theme']),
                    'speakers' => array_values(array_filter(array_map($text, $session['speakers']))),
                    'format' => sanitize_key($session['format']),
                    'type' => sanitize_key($session['type']),
                    // Blank lines separate the abstract's paragraphs; keep them.
                    'abstract' => trim(sanitize_textarea_field($session['abstract'])),
                ];
            }
            // An untitled session is how the editor deletes one.
            $d['days'][$di]['slots'][$si]['sessions'] = array_values(array_filter(
                $d['days'][$di]['slots'][$si]['sessions'],
                static fn(array $s): bool => $s['title'] !== ''
            ));
        }
    }

    $dates = array_values(array_filter(array_column($d['days'], 'date')));
    if ($dates) {
        $d['event']['dates'] = $dates;
    }

    return $d;
}

function ceeducon_cc_prog_time(string $value): string
{
    $value = trim($value);
    return preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $value) === 1 ? $value : '';
}

function ceeducon_cc_prog_stats(array $d): array
{
    $sessions = 0;
    $breaks = 0;
    $speakers = [];
    $missing_abstract = 0;
    $missing_type = 0;

    foreach ($d['days'] as $day) {
        foreach ($day['slots'] as $slot) {
            if ($slot['type'] === 'break') {
                $breaks++;
                continue;
            }
            foreach ($slot['sessions'] as $session) {
                $sessions++;
                if (trim((string) $session['abstract']) === '') {
                    $missing_abstract++;
                }
                if (trim((string) $session['type']) === '') {
                    $missing_type++;
                }
                foreach ($session['speakers'] as $name) {
                    $speakers[$name] = true;
                }
            }
        }
    }

    return [
        'days' => count($d['days']),
        'sessions' => $sessions,
        'breaks' => $breaks,
        'rooms' => count($d['rooms']),
        'themes' => count($d['themes']),
        'speakers' => count($speakers),
        'missing_abstract' => $missing_abstract,
        'missing_type' => $missing_type,
    ];
}

/** Flags the things that quietly break the front end if left as they are. */
function ceeducon_cc_prog_warnings(array $d): array
{
    $warnings = [];
    $rooms = $d['rooms'];
    $theme_ids = array_column($d['themes'], 'id');
    $type_ids = array_column($d['types'], 'id');
    $format_ids = array_column($d['formats'], 'id');

    foreach ($d['days'] as $di => $day) {
        $where = sprintf(__('Den %d', 'ceeducon-cc'), $di + 1);
        if ($day['date'] === '') {
            $warnings[] = sprintf(__('%s nemá datum.', 'ceeducon-cc'), $where);
        }

        foreach ($day['slots'] as $slot) {
            $slot_where = $where . ' · ' . ($slot['start'] !== '' ? $slot['start'] : __('blok bez času', 'ceeducon-cc'));
            if ($slot['start'] === '' || $slot['end'] === '') {
                $warnings[] = sprintf(__('%s: chybí začátek nebo konec.', 'ceeducon-cc'), $slot_where);
            }
            if ($slot['start'] !== '' && $slot['end'] !== '' && $slot['end'] <= $slot['start']) {
                $warnings[] = sprintf(__('%s: konec není po začátku.', 'ceeducon-cc'), $slot_where);
            }

            $taken = [];
            foreach ($slot['sessions'] as $session) {
                foreach ($session['rooms'] as $room) {
                    if (!in_array($room, $rooms, true)) {
                        $warnings[] = sprintf(__('%s: „%s" má sál „%s", který není v seznamu sálů.', 'ceeducon-cc'), $slot_where, $session['title'], $room);
                    }
                    if (isset($taken[$room])) {
                        $warnings[] = sprintf(__('%s: sál %s má ve stejný čas dvě přednášky („%s" a „%s").', 'ceeducon-cc'), $slot_where, $room, $taken[$room], $session['title']);
                    }
                    $taken[$room] = $session['title'];
                }
                if ($session['rooms'] === []) {
                    $warnings[] = sprintf(__('%s: „%s" nemá sál, v mřížce se nezobrazí.', 'ceeducon-cc'), $slot_where, $session['title']);
                }
                if ($session['theme'] !== '' && !in_array($session['theme'], $theme_ids, true)) {
                    $warnings[] = sprintf(__('%s: „%s" má neznámé téma „%s".', 'ceeducon-cc'), $slot_where, $session['title'], $session['theme']);
                }
                if ($session['type'] !== '' && !in_array($session['type'], $type_ids, true)) {
                    $warnings[] = sprintf(__('%s: „%s" má neznámý typ „%s".', 'ceeducon-cc'), $slot_where, $session['title'], $session['type']);
                }
                if ($session['format'] !== '' && !in_array($session['format'], $format_ids, true)) {
                    $warnings[] = sprintf(__('%s: „%s" má neznámý formát „%s".', 'ceeducon-cc'), $slot_where, $session['title'], $session['format']);
                }
            }
        }
    }

    return array_slice(array_values(array_unique($warnings)), 0, 20);
}

/**
 * A correction made in the repository reaches the bundled programme file, but
 * the copy saved in the database wins over that file — so a fix to a speaker's
 * institution never arrives on a site that has ever saved its programme.
 *
 * Rewrite such a value once, and only where it is byte-for-byte the superseded
 * text: anything an editor typed themselves is left alone, the pass records the
 * version it ran and never repeats, and the save it makes keeps the usual
 * one-step backup, so it can be undone from the programme screen.
 */
function ceeducon_cc_prog_superseded_speakers(): array
{
    return [
        // The organisers' schedule renamed the ESN sections in December 2026.
        '(ESN Czechia)' => '(ESN Czech Republic)',
    ];
}

function ceeducon_cc_prog_migrate_stored(): void
{
    $version = '1.0.1';
    if (get_option('ceeducon_cc_programme_migration') === $version) {
        return;
    }

    $stored = ceeducon_cc_prog_stored_json();
    if ($stored === '') {
        update_option('ceeducon_cc_programme_migration', $version, false);
        return;
    }

    $data = json_decode($stored, true);
    if (!is_array($data) || empty($data['days'])) {
        update_option('ceeducon_cc_programme_migration', $version, false);
        return;
    }

    $replacements = ceeducon_cc_prog_superseded_speakers();
    $changed = 0;

    foreach ($data['days'] as $di => $day) {
        foreach ((array) ($day['slots'] ?? []) as $si => $slot) {
            foreach ((array) ($slot['sessions'] ?? []) as $ei => $session) {
                foreach ((array) ($session['speakers'] ?? []) as $ni => $name) {
                    $fixed = strtr((string) $name, $replacements);
                    if ($fixed !== (string) $name) {
                        $data['days'][$di]['slots'][$si]['sessions'][$ei]['speakers'][$ni] = $fixed;
                        $changed++;
                    }
                }
            }
        }
    }

    if ($changed > 0) {
        ceeducon_cc_prog_store(ceeducon_cc_prog_sanitize($data));
    }

    update_option('ceeducon_cc_programme_migration', $version, false);
}
add_action('admin_init', 'ceeducon_cc_prog_migrate_stored', 5);

function ceeducon_cc_prog_encode(array $data): string
{
    return (string) wp_json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

/**
 * JSON handed to the page inside a <script> block. An abstract that happens to
 * contain "</script>" would otherwise close the block and spill the rest of the
 * programme into the document as markup; "<\/" is a legal JSON escape for the
 * same character, so the data survives and the parser never sees a closing tag.
 */
function ceeducon_cc_json_for_script($value): string
{
    $json = (string) wp_json_encode($value, JSON_UNESCAPED_UNICODE);
    return str_replace(['</', '<!--'], ['<\\/', '<\\u0021--'], $json);
}

function ceeducon_cc_prog_store(array $data): void
{
    $content = get_option(CEEDUCON_CC_CONTENT_OPTION, []);
    if (!is_array($content)) {
        $content = [];
    }

    // One undo step, kept beside the programme rather than in a separate table.
    if (isset($content[CEEDUCON_CC_PROGRAMME_KEY]) && (string) $content[CEEDUCON_CC_PROGRAMME_KEY] !== '') {
        update_option('ceeducon_cc_programme_backup', [
            'json' => (string) $content[CEEDUCON_CC_PROGRAMME_KEY],
            'saved' => current_time('mysql'),
            'user' => wp_get_current_user()->display_name,
        ], false);
    }

    $content[CEEDUCON_CC_PROGRAMME_KEY] = ceeducon_cc_prog_encode($data);
    update_option(CEEDUCON_CC_CONTENT_OPTION, $content);
}

/**
 * The whole programme arrives as one JSON field rather than a few thousand
 * inputs. That is not only tidier: PHP's max_input_vars stops at 1000 by
 * default, and a form with one input per room checkbox per session passes that
 * long before the last day is posted — silently, losing whatever came after.
 */
function ceeducon_cc_prog_save(): void
{
    if (!ceeducon_cc_can_edit()) {
        wp_die(esc_html__('Nemáte oprávnění měnit program.', 'ceeducon-cc'));
    }
    check_admin_referer('ceeducon_cc_save_programme');

    $payload = isset($_POST['programme_payload']) ? wp_unslash($_POST['programme_payload']) : '';
    $data = json_decode((string) $payload, true);

    if (!is_array($data) || !isset($data['days'])) {
        wp_safe_redirect(add_query_arg('ceeducon-notice', 'invalid', ceeducon_cc_page_url('ceeducon-cc-programme')));
        exit;
    }

    ceeducon_cc_prog_store(ceeducon_cc_prog_sanitize($data));
    wp_safe_redirect(add_query_arg('ceeducon-notice', 'saved', ceeducon_cc_page_url('ceeducon-cc-programme')));
    exit;
}
add_action('admin_post_ceeducon_cc_save_programme', 'ceeducon_cc_prog_save');

function ceeducon_cc_prog_restore(): void
{
    if (!ceeducon_cc_can_edit()) {
        wp_die(esc_html__('Nemáte oprávnění měnit program.', 'ceeducon-cc'));
    }
    check_admin_referer('ceeducon_cc_restore_programme');

    $backup = get_option('ceeducon_cc_programme_backup', []);
    $json = is_array($backup) ? (string) ($backup['json'] ?? '') : '';
    $data = $json !== '' ? json_decode($json, true) : null;

    if (!is_array($data)) {
        wp_safe_redirect(add_query_arg('ceeducon-notice', 'nobackup', ceeducon_cc_page_url('ceeducon-cc-programme')));
        exit;
    }

    ceeducon_cc_prog_store(ceeducon_cc_prog_sanitize($data));
    wp_safe_redirect(add_query_arg('ceeducon-notice', 'restored', ceeducon_cc_page_url('ceeducon-cc-programme')));
    exit;
}
add_action('admin_post_ceeducon_cc_restore_programme', 'ceeducon_cc_prog_restore');
