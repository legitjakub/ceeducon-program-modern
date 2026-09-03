<?php
/**
 * Plugin Name: CEEDUCON Control Center
 * Plugin URI: https://github.com/legitjakub/ceeducon-program-modern
 * Description: Jedno místo pro celý web CEEDUCON — texty stránek, údaje ročníku a vizuální editor programu.
 * Version: 1.0.1
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: CEEDUCON
 * Text Domain: ceeducon-cc
 */

if (!defined('ABSPATH')) {
    exit;
}

define('CEEDUCON_CC_VERSION', '1.0.1');
define('CEEDUCON_CC_FILE', __FILE__);
define('CEEDUCON_CC_DIR', plugin_dir_path(__FILE__));
define('CEEDUCON_CC_URL', plugin_dir_url(__FILE__));

/** The theme already reads these two options; reusing them means no migration. */
define('CEEDUCON_CC_CONTENT_OPTION', 'ceeducon_content');
define('CEEDUCON_CC_EDITION_OPTION', 'ceeducon_event_settings');
define('CEEDUCON_CC_PROGRAMME_KEY', 'programme_json');

require_once CEEDUCON_CC_DIR . 'includes/edition.php';
require_once CEEDUCON_CC_DIR . 'includes/content.php';
require_once CEEDUCON_CC_DIR . 'includes/programme.php';
require_once CEEDUCON_CC_DIR . 'includes/admin-dashboard.php';
require_once CEEDUCON_CC_DIR . 'includes/admin-content.php';
require_once CEEDUCON_CC_DIR . 'includes/admin-edition.php';
require_once CEEDUCON_CC_DIR . 'includes/admin-programme.php';
require_once CEEDUCON_CC_DIR . 'includes/admin-tools.php';

function ceeducon_cc_capability(): string
{
    return (string) apply_filters('ceeducon_cc_capability', 'edit_theme_options');
}

function ceeducon_cc_can_edit(): bool
{
    return current_user_can(ceeducon_cc_capability());
}

/** True when the CEEDUCON theme is providing the front end this plugin feeds. */
function ceeducon_cc_theme_active(): bool
{
    return function_exists('ceeducon_text_value');
}

function ceeducon_cc_page_url(string $page): string
{
    return admin_url('admin.php?page=' . $page);
}

/* ---------------------------------------------------------------------------
 * Menu
 * ------------------------------------------------------------------------ */

function ceeducon_cc_admin_menu(): void
{
    $cap = ceeducon_cc_capability();

    add_menu_page(
        __('CEEDUCON', 'ceeducon-cc'),
        __('CEEDUCON', 'ceeducon-cc'),
        $cap,
        'ceeducon-cc',
        'ceeducon_cc_render_dashboard',
        'dashicons-megaphone',
        29
    );

    $pages = [
        ['ceeducon-cc', __('Přehled', 'ceeducon-cc'), 'ceeducon_cc_render_dashboard'],
        ['ceeducon-cc-programme', __('Program', 'ceeducon-cc'), 'ceeducon_cc_render_programme'],
        ['ceeducon-cc-content', __('Texty webu', 'ceeducon-cc'), 'ceeducon_cc_render_content'],
        ['ceeducon-cc-edition', __('Ročník konference', 'ceeducon-cc'), 'ceeducon_cc_render_edition'],
        ['ceeducon-cc-tools', __('Zálohy a nástroje', 'ceeducon-cc'), 'ceeducon_cc_render_tools'],
    ];

    foreach ($pages as [$slug, $label, $callback]) {
        add_submenu_page('ceeducon-cc', $label, $label, $cap, $slug, $callback);
    }
}
add_action('admin_menu', 'ceeducon_cc_admin_menu', 9);

/**
 * The theme ships its own raw content screen and the two older plugins each add
 * one more. All of them write the same options this plugin now owns, so leaving
 * them in the menu offers the same data through three different, partly unsafe
 * forms. Hide them and keep one way in.
 */
function ceeducon_cc_hide_superseded_menus(): void
{
    remove_menu_page('ceeducon-content');
    foreach (['ceeducon-content', 'ceeducon-edition', 'ceeducon-programme'] as $slug) {
        remove_submenu_page('ceeducon-content', $slug);
    }
}
add_action('admin_menu', 'ceeducon_cc_hide_superseded_menus', 99);

/**
 * The raw screens stay reachable by URL for anyone who bookmarked them. Send
 * them to the screen that replaced them rather than showing two editors of the
 * same value.
 */
function ceeducon_cc_redirect_superseded_screens(): void
{
    $page = isset($_GET['page']) ? sanitize_key(wp_unslash($_GET['page'])) : '';
    $map = [
        'ceeducon-content' => 'ceeducon-cc-content',
        'ceeducon-edition' => 'ceeducon-cc-edition',
        'ceeducon-programme' => 'ceeducon-cc-programme',
    ];
    if (!isset($map[$page]) || $_SERVER['REQUEST_METHOD'] !== 'GET') {
        return;
    }
    wp_safe_redirect(ceeducon_cc_page_url($map[$page]));
    exit;
}
add_action('admin_init', 'ceeducon_cc_redirect_superseded_screens');

/**
 * The older plugins define functions this one also needs to answer for, so both
 * halves cannot own the same value at once. Stand them down on sight — every
 * setting they held lives in the same option and is already on screen here.
 */
function ceeducon_cc_deactivate_superseded_plugins(): void
{
    if (!is_admin() || !function_exists('deactivate_plugins')) {
        return;
    }

    $superseded = [
        'ceeducon-conference-settings/ceeducon-conference-settings.php',
        'ceeducon-programme-editor/ceeducon-programme-editor.php',
    ];
    $active = (array) get_option('active_plugins', []);
    $found = array_values(array_intersect($superseded, $active));
    if (!$found) {
        return;
    }

    deactivate_plugins($found, true);
    set_transient('ceeducon_cc_superseded_notice', $found, 60);
}
add_action('admin_init', 'ceeducon_cc_deactivate_superseded_plugins', 1);

function ceeducon_cc_admin_notices(): void
{
    if (!ceeducon_cc_can_edit()) {
        return;
    }

    $superseded = get_transient('ceeducon_cc_superseded_notice');
    if (is_array($superseded) && $superseded) {
        delete_transient('ceeducon_cc_superseded_notice');
        echo '<div class="notice notice-info is-dismissible"><p>';
        echo esc_html__('CEEDUCON Control Center nahradil starší pluginy (nastavení ročníku a editor programu) a vypnul je. Žádná data se neztratila — ukládají se na stejné místo.', 'ceeducon-cc');
        echo '</p></div>';
    }

    if (!ceeducon_cc_theme_active()) {
        echo '<div class="notice notice-warning"><p>';
        echo esc_html__('CEEDUCON Control Center běží, ale nenašel šablonu CEEDUCON Programme. Nastavení se ukládá, na webu se ale projeví až po aktivaci šablony.', 'ceeducon-cc');
        echo '</p></div>';
    }
}
add_action('admin_notices', 'ceeducon_cc_admin_notices');

/* ---------------------------------------------------------------------------
 * Assets
 * ------------------------------------------------------------------------ */

function ceeducon_cc_is_own_screen(string $hook): bool
{
    return str_contains($hook, 'ceeducon-cc');
}

function ceeducon_cc_admin_assets(string $hook): void
{
    if (!ceeducon_cc_is_own_screen($hook)) {
        return;
    }

    $css = CEEDUCON_CC_DIR . 'assets/admin.css';
    wp_enqueue_style('ceeducon-cc-admin', CEEDUCON_CC_URL . 'assets/admin.css', [], (string) @filemtime($css));

    $js = CEEDUCON_CC_DIR . 'assets/admin.js';
    wp_enqueue_script('ceeducon-cc-admin', CEEDUCON_CC_URL . 'assets/admin.js', [], (string) @filemtime($js), true);

    if (str_contains($hook, 'ceeducon-cc-edition')) {
        wp_enqueue_media();
    }

    if (str_contains($hook, 'ceeducon-cc-programme')) {
        $prog = CEEDUCON_CC_DIR . 'assets/programme.js';
        wp_enqueue_script('ceeducon-cc-programme', CEEDUCON_CC_URL . 'assets/programme.js', [], (string) @filemtime($prog), true);
    }
}
add_action('admin_enqueue_scripts', 'ceeducon_cc_admin_assets');

/* ---------------------------------------------------------------------------
 * Activation
 * ------------------------------------------------------------------------ */

function ceeducon_cc_activate(): void
{
    // Seed the edition option from whatever the site already holds, so a fresh
    // activation never shows an empty year.
    if (get_option(CEEDUCON_CC_EDITION_OPTION, null) === null) {
        add_option(CEEDUCON_CC_EDITION_OPTION, ceeducon_cc_edition_defaults(), '', false);
    }
    set_transient('ceeducon_cc_just_activated', 1, 60);
}
register_activation_hook(__FILE__, 'ceeducon_cc_activate');
