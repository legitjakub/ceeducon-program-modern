<?php
/**
 * Plugin Name: CEEDUCON Programme Editor
 * Description: Edits the conference programme — days, time slots and sessions — as a form instead of raw JSON. Saves into the same option the theme already reads, so no theme change is needed.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: Jakub Hrncir
 * Text Domain: ceeducon-programme-editor
 */

if (!defined('ABSPATH')) {
    exit;
}

const CEEDUCON_PROG_OPTION = 'ceeducon_content';
const CEEDUCON_PROG_KEY = 'programme_json';

/** Break kinds the theme's renderer understands. */
function ceeducon_prog_break_kinds(): array
{
    return [
        'registration' => __('Registrace', 'ceeducon-programme-editor'),
        'coffee' => __('Přestávka na kávu', 'ceeducon-programme-editor'),
        'lunch' => __('Oběd', 'ceeducon-programme-editor'),
    ];
}

/**
 * Reads the programme exactly the way the theme does: the saved option first,
 * the file bundled with the theme as the fallback. Keeping one source of truth
 * means this editor and the front end can never drift apart.
 */
function ceeducon_prog_load(): array
{
    $stored = '';
    $content = get_option(CEEDUCON_PROG_OPTION, []);
    if (is_array($content) && isset($content[CEEDUCON_PROG_KEY])) {
        $stored = (string) $content[CEEDUCON_PROG_KEY];
    }

    $data = $stored !== '' ? json_decode($stored, true) : null;
    if (is_array($data) && !empty($data['days'])) {
        return ceeducon_prog_normalise($data);
    }

    if (function_exists('ceeducon_default_programme_json')) {
        $fallback = json_decode(ceeducon_default_programme_json(), true);
        if (is_array($fallback)) {
            return ceeducon_prog_normalise($fallback);
        }
    }

    return ceeducon_prog_normalise([]);
}

/** Fills in every key the editor and the front end expect, so nothing is undefined. */
function ceeducon_prog_normalise(array $d): array
{
    $d['event'] = is_array($d['event'] ?? null) ? $d['event'] : [];
    $d['event'] += ['title' => '', 'dates' => [], 'location' => '', 'timezone' => 'Europe/Prague'];
    $d['rooms'] = array_values(array_filter(array_map('strval', (array) ($d['rooms'] ?? []))));
    $d['formats'] = is_array($d['formats'] ?? null) ? $d['formats'] : [];

    $themes = [];
    foreach ((array) ($d['themes'] ?? []) as $t) {
        if (!is_array($t) || ($t['id'] ?? '') === '') {
            continue;
        }
        $themes[] = [
            'id' => (string) $t['id'],
            'label' => (string) ($t['label'] ?? $t['id']),
            'color' => (string) ($t['color'] ?? '#0d5e9d'),
            'softColor' => (string) ($t['softColor'] ?? '#d9e2f3'),
        ];
    }
    $d['themes'] = $themes;

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
                $row['break'] = array_key_exists((string) ($slot['break'] ?? ''), ceeducon_prog_break_kinds())
                    ? (string) $slot['break']
                    : 'coffee';
                // The grid reads this to stretch a break band across every room column.
                $row['span'] = (string) ($slot['span'] ?? 'all');
                $row['sessions'] = [];
            } else {
                $sessions = [];
                foreach ((array) ($slot['sessions'] ?? []) as $s) {
                    if (!is_array($s)) {
                        continue;
                    }
                    $sessions[] = [
                        'title' => (string) ($s['title'] ?? ''),
                        'rooms' => array_values(array_filter(array_map('strval', (array) ($s['rooms'] ?? [])))),
                        'theme' => (string) ($s['theme'] ?? ''),
                        'speakers' => array_values(array_filter(array_map('strval', (array) ($s['speakers'] ?? [])))),
                        'format' => (string) ($s['format'] ?? ''),
                    ];
                }
                $row['sessions'] = $sessions;
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

/**
 * The programme file stores formats as a list of {id,label,color}; the select
 * needs id => label. Falls back to the formats actually used by the sessions.
 */
function ceeducon_prog_format_choices(array $d): array
{
    $choices = [];
    foreach ((array) ($d['formats'] ?? []) as $f) {
        if (is_array($f) && ($f['id'] ?? '') !== '') {
            $choices[(string) $f['id']] = (string) ($f['label'] ?? $f['id']);
        } elseif (is_string($f)) {
            $choices[$f] = $f;
        }
    }
    if ($choices) {
        return $choices;
    }
    foreach ($d['days'] as $day) {
        foreach ($day['slots'] as $slot) {
            foreach ($slot['sessions'] as $s) {
                if ($s['format'] !== '') {
                    $choices[$s['format']] = $s['format'];
                }
            }
        }
    }
    return $choices;
}

/** Counts used by the summary bar and by the post-save notice. */
function ceeducon_prog_stats(array $d): array
{
    $sessions = 0;
    $breaks = 0;
    foreach ($d['days'] as $day) {
        foreach ($day['slots'] as $slot) {
            if ($slot['type'] === 'break') {
                $breaks++;
                continue;
            }
            $sessions += count($slot['sessions']);
        }
    }
    return [
        'days' => count($d['days']),
        'sessions' => $sessions,
        'breaks' => $breaks,
        'rooms' => count($d['rooms']),
        'themes' => count($d['themes']),
    ];
}

/* ---------------------------------------------------------------------------
 * Saving
 * ------------------------------------------------------------------------ */

function ceeducon_prog_sanitize_time(string $v): string
{
    $v = trim($v);
    return preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $v) === 1 ? $v : '';
}

/** Turns the posted form arrays back into the shape the front end consumes. */
function ceeducon_prog_from_post(array $post, array $current): array
{
    $out = $current;

    $out['rooms'] = array_values(array_unique(array_filter(array_map(
        static fn($r) => trim(sanitize_text_field($r)),
        preg_split('/[\r\n,]+/', (string) ($post['rooms'] ?? '')) ?: []
    ))));

    $themes = [];
    foreach ((array) ($post['themes'] ?? []) as $t) {
        $id = sanitize_key((string) ($t['id'] ?? ''));
        if ($id === '') {
            continue;
        }
        $themes[] = [
            'id' => $id,
            'label' => sanitize_text_field((string) ($t['label'] ?? $id)),
            'color' => sanitize_hex_color((string) ($t['color'] ?? '')) ?: '#0d5e9d',
            'softColor' => sanitize_hex_color((string) ($t['softColor'] ?? '')) ?: '#d9e2f3',
        ];
    }
    if ($themes) {
        $out['themes'] = $themes;
    }

    $days = [];
    foreach ((array) ($post['days'] ?? []) as $day) {
        $slots = [];
        foreach ((array) ($day['slots'] ?? []) as $slot) {
            $type = ($slot['type'] ?? '') === 'break' ? 'break' : 'sessions';
            $start = ceeducon_prog_sanitize_time((string) ($slot['start'] ?? ''));
            $end = ceeducon_prog_sanitize_time((string) ($slot['end'] ?? ''));

            $row = [
                'id' => sanitize_text_field((string) ($slot['id'] ?? '')),
                'start' => $start,
                'end' => $end,
                'type' => $type,
            ];

            if ($type === 'break') {
                $row['title'] = sanitize_text_field((string) ($slot['title'] ?? ''));
                $kind = (string) ($slot['break'] ?? 'coffee');
                $row['break'] = array_key_exists($kind, ceeducon_prog_break_kinds()) ? $kind : 'coffee';
                $row['span'] = sanitize_key((string) ($slot['span'] ?? 'all')) ?: 'all';
            } else {
                $sessions = [];
                foreach ((array) ($slot['sessions'] ?? []) as $s) {
                    $title = sanitize_text_field((string) ($s['title'] ?? ''));
                    if ($title === '') {
                        continue; // an empty row is how the editor deletes a session
                    }
                    $sessions[] = [
                        'title' => $title,
                        'rooms' => array_values(array_filter(array_map(
                            'sanitize_text_field',
                            (array) ($s['rooms'] ?? [])
                        ))),
                        'theme' => sanitize_key((string) ($s['theme'] ?? '')),
                        'speakers' => array_values(array_filter(array_map(
                            static fn($x) => trim(sanitize_text_field($x)),
                            preg_split('/\r\n|\r|\n/', (string) ($s['speakers'] ?? '')) ?: []
                        ))),
                        'format' => sanitize_key((string) ($s['format'] ?? '')),
                    ];
                }
                $row['sessions'] = $sessions;
            }

            if ($row['start'] === '' && $row['type'] === 'sessions' && empty($row['sessions'])) {
                continue; // fully blank slot
            }
            if ($row['id'] === '') {
                $row['id'] = 'slot-' . substr(md5($row['start'] . $row['end'] . count($slots)), 0, 8);
            }
            $slots[] = $row;
        }

        $date = sanitize_text_field((string) ($day['date'] ?? ''));
        if ($date === '' && empty($slots)) {
            continue;
        }
        $days[] = [
            'date' => $date,
            'label' => sanitize_text_field((string) ($day['label'] ?? '')),
            'title' => sanitize_text_field((string) ($day['title'] ?? '')),
            'slots' => $slots,
        ];
    }
    $out['days'] = $days;

    $dates = array_values(array_filter(array_column($days, 'date')));
    if ($dates) {
        $out['event']['dates'] = $dates;
    }

    return ceeducon_prog_normalise($out);
}

/** Flags things that will silently break the front end if left as they are. */
function ceeducon_prog_warnings(array $d): array
{
    $w = [];
    $rooms = $d['rooms'];
    $themeIds = array_column($d['themes'], 'id');

    foreach ($d['days'] as $di => $day) {
        $where = sprintf(__('den %d', 'ceeducon-programme-editor'), $di + 1);
        if ($day['date'] === '') {
            $w[] = sprintf(__('%s nemá datum.', 'ceeducon-programme-editor'), ucfirst($where));
        }
        foreach ($day['slots'] as $slot) {
            $slotWhere = $where . ', ' . ($slot['start'] !== '' ? $slot['start'] : __('blok bez času', 'ceeducon-programme-editor'));
            if ($slot['start'] === '' || $slot['end'] === '') {
                $w[] = sprintf(__('%s: chybí začátek nebo konec.', 'ceeducon-programme-editor'), $slotWhere);
            }
            foreach ($slot['sessions'] as $s) {
                foreach ($s['rooms'] as $r) {
                    if (!in_array($r, $rooms, true)) {
                        $w[] = sprintf(
                            __('%s: „%s" má sál „%s", který není v seznamu sálů.', 'ceeducon-programme-editor'),
                            $slotWhere, $s['title'], $r
                        );
                    }
                }
                if ($s['theme'] !== '' && !in_array($s['theme'], $themeIds, true)) {
                    $w[] = sprintf(
                        __('%s: „%s" má neznámé téma „%s".', 'ceeducon-programme-editor'),
                        $slotWhere, $s['title'], $s['theme']
                    );
                }
            }
        }
    }
    return array_slice(array_unique($w), 0, 12);
}

function ceeducon_prog_store(array $data): void
{
    $content = get_option(CEEDUCON_PROG_OPTION, []);
    if (!is_array($content)) {
        $content = [];
    }
    $content[CEEDUCON_PROG_KEY] = (string) wp_json_encode(
        $data,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
    update_option(CEEDUCON_PROG_OPTION, $content);
}

/* ---------------------------------------------------------------------------
 * Admin screen
 * ------------------------------------------------------------------------ */

function ceeducon_prog_capability(): string
{
    return (string) apply_filters('ceeducon_prog_capability', 'edit_theme_options');
}

function ceeducon_prog_admin_menu(): void
{
    $cap = ceeducon_prog_capability();
    // Sits under the theme's CEEDUCON menu when it exists, stands alone otherwise.
    if (function_exists('ceeducon_admin_menu')) {
        add_submenu_page(
            'ceeducon-content',
            __('Program konference', 'ceeducon-programme-editor'),
            __('Program', 'ceeducon-programme-editor'),
            $cap,
            'ceeducon-programme',
            'ceeducon_prog_render_admin'
        );
        return;
    }
    add_menu_page(
        __('Program konference', 'ceeducon-programme-editor'),
        __('Program CEEDUCON', 'ceeducon-programme-editor'),
        $cap,
        'ceeducon-programme',
        'ceeducon_prog_render_admin',
        'dashicons-list-view',
        31
    );
}
add_action('admin_menu', 'ceeducon_prog_admin_menu', 21);

function ceeducon_prog_admin_assets(string $hook): void
{
    if (!str_contains($hook, 'ceeducon-programme')) {
        return;
    }
    $base = plugin_dir_url(__FILE__) . 'assets/';
    $dir = plugin_dir_path(__FILE__) . 'assets/';
    wp_enqueue_style('ceeducon-prog-admin', $base . 'admin.css', [], (string) @filemtime($dir . 'admin.css'));
    wp_enqueue_script('ceeducon-prog-admin', $base . 'admin.js', [], (string) @filemtime($dir . 'admin.js'), true);
}
add_action('admin_enqueue_scripts', 'ceeducon_prog_admin_assets');

function ceeducon_prog_session_row(string $name, array $s, array $rooms, array $themes, array $formats): void
{
    ?>
    <div class="cp-session" data-cp-row>
      <div class="cp-session-main">
        <label class="cp-field cp-grow">
          <span><?php esc_html_e('Název', 'ceeducon-programme-editor'); ?></span>
          <input type="text" name="<?php echo esc_attr($name); ?>[title]" value="<?php echo esc_attr($s['title']); ?>" />
        </label>
        <label class="cp-field">
          <span><?php esc_html_e('Téma', 'ceeducon-programme-editor'); ?></span>
          <select name="<?php echo esc_attr($name); ?>[theme]">
            <option value=""><?php esc_html_e('— bez tématu —', 'ceeducon-programme-editor'); ?></option>
            <?php foreach ($themes as $t) : ?>
              <option value="<?php echo esc_attr($t['id']); ?>" <?php selected($s['theme'], $t['id']); ?>><?php echo esc_html($t['label']); ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label class="cp-field">
          <span><?php esc_html_e('Formát', 'ceeducon-programme-editor'); ?></span>
          <select name="<?php echo esc_attr($name); ?>[format]">
            <option value=""><?php esc_html_e('— žádný —', 'ceeducon-programme-editor'); ?></option>
            <?php foreach ($formats as $id => $label) : ?>
              <option value="<?php echo esc_attr($id); ?>" <?php selected($s['format'], $id); ?>><?php echo esc_html($label); ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <button type="button" class="button-link cp-remove" data-cp-remove
                title="<?php esc_attr_e('Odebrat přednášku', 'ceeducon-programme-editor'); ?>">&times;</button>
      </div>
      <div class="cp-session-meta">
        <div class="cp-field cp-rooms">
          <span><?php esc_html_e('Sály', 'ceeducon-programme-editor'); ?></span>
          <div class="cp-room-list">
            <?php foreach ($rooms as $room) : ?>
              <label class="cp-room">
                <input type="checkbox" name="<?php echo esc_attr($name); ?>[rooms][]"
                       value="<?php echo esc_attr($room); ?>" <?php checked(in_array($room, $s['rooms'], true)); ?> />
                <span><?php echo esc_html($room); ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        </div>
        <label class="cp-field cp-grow">
          <span><?php esc_html_e('Řečníci (každý na svůj řádek)', 'ceeducon-programme-editor'); ?></span>
          <textarea name="<?php echo esc_attr($name); ?>[speakers]" rows="2"><?php echo esc_textarea(implode("\n", $s['speakers'])); ?></textarea>
        </label>
      </div>
    </div>
    <?php
}

function ceeducon_prog_render_admin(): void
{
    if (!current_user_can(ceeducon_prog_capability())) {
        return;
    }

    $data = ceeducon_prog_load();
    $saved = false;
    $warnings = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer('ceeducon_prog_save')) {
        $posted = isset($_POST['prog']) && is_array($_POST['prog']) ? wp_unslash($_POST['prog']) : [];
        $data = ceeducon_prog_from_post($posted, $data);
        ceeducon_prog_store($data);
        $warnings = ceeducon_prog_warnings($data);
        $saved = true;
    }

    $stats = ceeducon_prog_stats($data);
    $formats = ceeducon_prog_format_choices($data);
    ?>
    <div class="wrap cp-wrap">
      <h1><?php esc_html_e('Program konference', 'ceeducon-programme-editor'); ?></h1>
      <p class="description">
        <?php esc_html_e('Tady se mění obsah interaktivního programu. Ukládá se do stejného místa, ze kterého web program načítá — po uložení je změna hned vidět.', 'ceeducon-programme-editor'); ?>
      </p>

      <?php if ($saved) : ?>
        <div class="notice notice-success is-dismissible"><p>
          <?php printf(
              esc_html__('Uloženo: %1$d dny, %2$d přednášek, %3$d přestávek.', 'ceeducon-programme-editor'),
              (int) $stats['days'], (int) $stats['sessions'], (int) $stats['breaks']
          ); ?>
        </p></div>
      <?php endif; ?>

      <?php if ($warnings) : ?>
        <div class="notice notice-warning"><p><strong><?php esc_html_e('Uloženo, ale zkontrolujte:', 'ceeducon-programme-editor'); ?></strong></p>
          <ul class="cp-warnings"><?php foreach ($warnings as $w) : ?><li><?php echo esc_html($w); ?></li><?php endforeach; ?></ul>
        </div>
      <?php endif; ?>

      <form method="post" class="cp-form">
        <?php wp_nonce_field('ceeducon_prog_save'); ?>

        <div class="cp-summary">
          <span><strong><?php echo (int) $stats['days']; ?></strong> <?php esc_html_e('dny', 'ceeducon-programme-editor'); ?></span>
          <span><strong><?php echo (int) $stats['sessions']; ?></strong> <?php esc_html_e('přednášek', 'ceeducon-programme-editor'); ?></span>
          <span><strong><?php echo (int) $stats['breaks']; ?></strong> <?php esc_html_e('přestávek', 'ceeducon-programme-editor'); ?></span>
          <span><strong><?php echo (int) $stats['rooms']; ?></strong> <?php esc_html_e('sálů', 'ceeducon-programme-editor'); ?></span>
        </div>

        <details class="cp-panel">
          <summary><?php esc_html_e('Sály a témata', 'ceeducon-programme-editor'); ?></summary>
          <div class="cp-panel-body">
            <label class="cp-field">
              <span><?php esc_html_e('Sály (jeden na řádek nebo oddělené čárkou)', 'ceeducon-programme-editor'); ?></span>
              <textarea name="prog[rooms]" rows="3"><?php echo esc_textarea(implode(", ", $data['rooms'])); ?></textarea>
            </label>
            <p class="description"><?php esc_html_e('Pořadí určuje pořadí sloupců v mřížce programu.', 'ceeducon-programme-editor'); ?></p>

            <table class="widefat cp-themes">
              <thead><tr>
                <th><?php esc_html_e('ID', 'ceeducon-programme-editor'); ?></th>
                <th><?php esc_html_e('Název tématu', 'ceeducon-programme-editor'); ?></th>
                <th><?php esc_html_e('Barva', 'ceeducon-programme-editor'); ?></th>
              </tr></thead>
              <tbody>
                <?php foreach ($data['themes'] as $i => $t) : ?>
                  <tr>
                    <td><input type="text" name="prog[themes][<?php echo (int) $i; ?>][id]" value="<?php echo esc_attr($t['id']); ?>" readonly /></td>
                    <td><input type="text" name="prog[themes][<?php echo (int) $i; ?>][label]" value="<?php echo esc_attr($t['label']); ?>" /></td>
                    <td>
                      <input type="color" name="prog[themes][<?php echo (int) $i; ?>][color]" value="<?php echo esc_attr($t['color']); ?>" />
                      <input type="hidden" name="prog[themes][<?php echo (int) $i; ?>][softColor]" value="<?php echo esc_attr($t['softColor']); ?>" />
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </details>

        <?php foreach ($data['days'] as $di => $day) : ?>
          <section class="cp-day">
            <header class="cp-day-head">
              <label class="cp-field">
                <span><?php esc_html_e('Datum', 'ceeducon-programme-editor'); ?></span>
                <input type="date" name="prog[days][<?php echo (int) $di; ?>][date]" value="<?php echo esc_attr($day['date']); ?>" />
              </label>
              <label class="cp-field">
                <span><?php esc_html_e('Označení', 'ceeducon-programme-editor'); ?></span>
                <input type="text" name="prog[days][<?php echo (int) $di; ?>][label]" value="<?php echo esc_attr($day['label']); ?>" placeholder="Day 1" />
              </label>
              <label class="cp-field cp-grow">
                <span><?php esc_html_e('Nadpis dne', 'ceeducon-programme-editor'); ?></span>
                <input type="text" name="prog[days][<?php echo (int) $di; ?>][title]" value="<?php echo esc_attr($day['title']); ?>" />
              </label>
            </header>

            <div class="cp-slots" data-cp-slots>
              <?php foreach ($day['slots'] as $si => $slot) :
                  $base = sprintf('prog[days][%d][slots][%d]', $di, $si); ?>
                <article class="cp-slot cp-slot--<?php echo esc_attr($slot['type']); ?>" data-cp-row>
                  <div class="cp-slot-head">
                    <input type="hidden" name="<?php echo esc_attr($base); ?>[id]" value="<?php echo esc_attr($slot['id']); ?>" />
                    <input type="hidden" name="<?php echo esc_attr($base); ?>[span]" value="<?php echo esc_attr($slot['span'] ?? 'all'); ?>" />
                    <label class="cp-field cp-time">
                      <span><?php esc_html_e('Od', 'ceeducon-programme-editor'); ?></span>
                      <input type="time" name="<?php echo esc_attr($base); ?>[start]" value="<?php echo esc_attr($slot['start']); ?>" />
                    </label>
                    <label class="cp-field cp-time">
                      <span><?php esc_html_e('Do', 'ceeducon-programme-editor'); ?></span>
                      <input type="time" name="<?php echo esc_attr($base); ?>[end]" value="<?php echo esc_attr($slot['end']); ?>" />
                    </label>
                    <label class="cp-field">
                      <span><?php esc_html_e('Typ bloku', 'ceeducon-programme-editor'); ?></span>
                      <select name="<?php echo esc_attr($base); ?>[type]" data-cp-type>
                        <option value="sessions" <?php selected($slot['type'], 'sessions'); ?>><?php esc_html_e('Přednášky', 'ceeducon-programme-editor'); ?></option>
                        <option value="break" <?php selected($slot['type'], 'break'); ?>><?php esc_html_e('Přestávka', 'ceeducon-programme-editor'); ?></option>
                      </select>
                    </label>
                    <button type="button" class="button-link cp-remove" data-cp-remove
                            title="<?php esc_attr_e('Odebrat blok', 'ceeducon-programme-editor'); ?>">&times;</button>
                  </div>

                  <div class="cp-break-fields" <?php echo $slot['type'] === 'break' ? '' : 'hidden'; ?>>
                    <label class="cp-field cp-grow">
                      <span><?php esc_html_e('Popis přestávky', 'ceeducon-programme-editor'); ?></span>
                      <input type="text" name="<?php echo esc_attr($base); ?>[title]" value="<?php echo esc_attr($slot['title'] ?? ''); ?>" />
                    </label>
                    <label class="cp-field">
                      <span><?php esc_html_e('Druh', 'ceeducon-programme-editor'); ?></span>
                      <select name="<?php echo esc_attr($base); ?>[break]">
                        <?php foreach (ceeducon_prog_break_kinds() as $id => $label) : ?>
                          <option value="<?php echo esc_attr($id); ?>" <?php selected($slot['break'] ?? 'coffee', $id); ?>><?php echo esc_html($label); ?></option>
                        <?php endforeach; ?>
                      </select>
                    </label>
                  </div>

                  <div class="cp-sessions" data-cp-sessions <?php echo $slot['type'] === 'break' ? 'hidden' : ''; ?>>
                    <?php foreach ($slot['sessions'] as $ei => $s) :
                        ceeducon_prog_session_row($base . '[sessions][' . $ei . ']', $s, $data['rooms'], $data['themes'], $formats);
                    endforeach; ?>
                    <button type="button" class="button cp-add" data-cp-add-session>
                      <?php esc_html_e('+ Přidat přednášku', 'ceeducon-programme-editor'); ?>
                    </button>
                  </div>
                </article>
              <?php endforeach; ?>
            </div>

            <button type="button" class="button cp-add" data-cp-add-slot data-day="<?php echo (int) $di; ?>">
              <?php esc_html_e('+ Přidat časový blok', 'ceeducon-programme-editor'); ?>
            </button>
          </section>
        <?php endforeach; ?>

        <p class="submit">
          <button type="submit" class="button button-primary button-hero"><?php esc_html_e('Uložit program', 'ceeducon-programme-editor'); ?></button>
        </p>
      </form>

      <details class="cp-panel">
        <summary><?php esc_html_e('Záloha a obnovení (JSON)', 'ceeducon-programme-editor'); ?></summary>
        <div class="cp-panel-body">
          <p class="description"><?php esc_html_e('Zkopírujte si obsah před větší úpravou. Vložením zpět na stránce CEEDUCON Content se program vrátí do tohoto stavu.', 'ceeducon-programme-editor'); ?></p>
          <textarea class="cp-json" rows="10" readonly onclick="this.select()"><?php
              echo esc_textarea((string) wp_json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
          ?></textarea>
        </div>
      </details>
    </div>
    <?php
}
