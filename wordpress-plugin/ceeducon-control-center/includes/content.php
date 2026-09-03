<?php
/**
 * The visible texts of the site. The theme owns the list of fields — every
 * heading, lead and label it renders — and this screen is a nicer way into it.
 * Reading the list from the theme means a text added there shows up here on the
 * next update without touching this plugin.
 */

if (!defined('ABSPATH')) {
    exit;
}

/** Group names come from the theme in English; these are their Czech titles. */
function ceeducon_cc_group_meta(): array
{
    return [
        'SEO fallback (used only without an SEO plugin)' => [
            'title' => 'SEO popisky',
            'icon' => 'search',
            'note' => 'Použijí se jen tehdy, když web nemá SEO plugin (Yoast, Rank Math…). Popisky ročníku spravuje záložka Ročník konference.',
        ],
        'Global — header & footer' => [
            'title' => 'Hlavička a patička',
            'icon' => 'admin-home',
            'note' => 'Kontakty a odkazy, které jsou na každé stránce.',
        ],
        'Home — hero' => ['title' => 'Titulní strana — úvodní blok', 'icon' => 'welcome-view-site'],
        'Home — sections' => ['title' => 'Titulní strana — sekce', 'icon' => 'layout'],
        'Thematic areas (shared)' => [
            'title' => 'Tematické okruhy',
            'icon' => 'category',
            'note' => 'Sdílené — zobrazují se na titulní straně i na stránce programu.',
        ],
        'Programme days & notices (shared)' => ['title' => 'Dny programu a upozornění', 'icon' => 'calendar-alt'],
        'Organiser & partners (shared)' => ['title' => 'Pořadatel a partneři', 'icon' => 'groups'],
        'About page' => ['title' => 'Stránka O konferenci', 'icon' => 'info'],
        'Programme page' => ['title' => 'Stránka Program', 'icon' => 'list-view'],
        'Practical page' => ['title' => 'Stránka Praktické informace', 'icon' => 'location'],
        'For speakers page' => ['title' => 'Stránka Pro řečníky', 'icon' => 'microphone'],
        'Contact page' => ['title' => 'Stránka Kontakt', 'icon' => 'email'],
    ];
}

/**
 * The programme has its own editor, so its raw JSON is not offered here — a
 * hand-edited copy of it is exactly how a programme gets silently broken.
 */
function ceeducon_cc_content_groups(): array
{
    if (!function_exists('ceeducon_admin_content_fields')) {
        return [];
    }

    $groups = [];
    foreach (ceeducon_admin_content_fields() as $group => $fields) {
        $fields = array_values(array_filter(
            $fields,
            static fn(array $field): bool => (string) ($field[0] ?? '') !== CEEDUCON_CC_PROGRAMME_KEY
        ));
        if ($fields !== []) {
            $groups[$group] = $fields;
        }
    }

    return $groups;
}

function ceeducon_cc_content_values(): array
{
    $content = get_option(CEEDUCON_CC_CONTENT_OPTION, []);
    return is_array($content) ? $content : [];
}

/**
 * What to put in the input. A saved value wins; otherwise the theme's default
 * is shown *unexpanded*, tokens and all.
 *
 * That distinction matters more than it looks: the old screen showed the
 * expanded default, so pressing Save once froze "{{date}}" into "1–2 December
 * 2026" for every field nobody had touched, and the next edition silently
 * stopped updating them.
 */
function ceeducon_cc_field_value(array $values, string $key, string $default): string
{
    return array_key_exists($key, $values) ? (string) $values[$key] : $default;
}

/** What a visitor will actually read, with every {{token}} filled in. */
function ceeducon_cc_field_preview(string $value): string
{
    return function_exists('ceeducon_expand_content_tokens')
        ? ceeducon_expand_content_tokens($value)
        : strtr($value, ceeducon_cc_tokens());
}

function ceeducon_cc_content_stats(): array
{
    $groups = ceeducon_cc_content_groups();
    $values = ceeducon_cc_content_values();

    $total = 0;
    $edited = 0;
    foreach ($groups as $fields) {
        foreach ($fields as [$key, , $default]) {
            $total++;
            if (array_key_exists($key, $values) && (string) $values[$key] !== (string) $default) {
                $edited++;
            }
        }
    }

    return ['groups' => count($groups), 'fields' => $total, 'edited' => $edited];
}

function ceeducon_cc_content_save(): void
{
    if (!ceeducon_cc_can_edit()) {
        wp_die(esc_html__('Nemáte oprávnění měnit texty webu.', 'ceeducon-cc'));
    }
    check_admin_referer('ceeducon_cc_save_content');

    $submitted = isset($_POST['content']) && is_array($_POST['content'])
        ? wp_unslash($_POST['content'])
        : [];

    $existing = ceeducon_cc_content_values();
    $clean = $existing;

    foreach (ceeducon_cc_content_groups() as $fields) {
        foreach ($fields as [$key, , $default, $type]) {
            if (!array_key_exists($key, $submitted)) {
                continue; // a group that was never rendered must not be wiped
            }
            $value = (string) $submitted[$key];

            if ($type === 'url') {
                $url = esc_url_raw($value);
                if ($url === '') {
                    unset($clean[$key]);
                } else {
                    $clean[$key] = $url;
                }
                continue;
            }

            // The site's copy carries links and <br> on purpose, so the same
            // filter WordPress trusts for post content is the right one here.
            $value = wp_kses_post($value);

            // An emptied field means "use what the theme ships", not "print
            // nothing" — dropping the key is how the default comes back.
            if (trim($value) === '') {
                unset($clean[$key]);
                continue;
            }

            $clean[$key] = $value;
        }
    }

    update_option(CEEDUCON_CC_CONTENT_OPTION, $clean);

    $redirect = add_query_arg(
        ['ceeducon-notice' => 'saved', 'group' => isset($_POST['active_group']) ? sanitize_key(wp_unslash($_POST['active_group'])) : ''],
        ceeducon_cc_page_url('ceeducon-cc-content')
    );
    wp_safe_redirect($redirect);
    exit;
}
add_action('admin_post_ceeducon_cc_save_content', 'ceeducon_cc_content_save');

/** Puts one group — or every group — back to what the theme ships. */
function ceeducon_cc_content_reset(): void
{
    if (!ceeducon_cc_can_edit()) {
        wp_die(esc_html__('Nemáte oprávnění měnit texty webu.', 'ceeducon-cc'));
    }
    check_admin_referer('ceeducon_cc_reset_content');

    $target = isset($_POST['group_index']) ? (string) sanitize_text_field(wp_unslash($_POST['group_index'])) : '';
    $groups = ceeducon_cc_content_groups();
    $values = ceeducon_cc_content_values();

    $index = 0;
    foreach ($groups as $fields) {
        $match = $target === 'all' || $target === (string) $index;
        $index++;
        if (!$match) {
            continue;
        }
        foreach ($fields as [$key]) {
            unset($values[$key]);
        }
    }

    update_option(CEEDUCON_CC_CONTENT_OPTION, $values);
    wp_safe_redirect(add_query_arg('ceeducon-notice', 'reset', ceeducon_cc_page_url('ceeducon-cc-content')));
    exit;
}
add_action('admin_post_ceeducon_cc_reset_content', 'ceeducon_cc_content_reset');
