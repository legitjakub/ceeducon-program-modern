<?php
/**
 * Plugin Name: CEEDUCON Elementor Widgets
 * Description: Native CEEDUCON section widgets for Elementor Free, rendered by the CEEDUCON theme.
 * Version: 1.2.4
 * Requires at least: 6.5
 * Requires PHP: 8.0
 * Requires Plugins: elementor
 * Author: CEEDUCON
 * Text Domain: ceeducon-elementor-widgets
 */

if (!defined('ABSPATH')) {
    exit;
}

define('CEEDUCON_ELEMENTOR_WIDGETS_VERSION', '1.2.4');
define('CEEDUCON_ELEMENTOR_WIDGETS_MIN_ELEMENTOR', '3.20.0');
define('CEEDUCON_ELEMENTOR_WIDGETS_FILE', __FILE__);
define('CEEDUCON_ELEMENTOR_WIDGETS_PATH', plugin_dir_path(__FILE__));

function ceeducon_elementor_widgets_boot(): void
{
    static $booted = false;
    if ($booted) {
        return;
    }

    if (!defined('ELEMENTOR_VERSION') || version_compare(ELEMENTOR_VERSION, CEEDUCON_ELEMENTOR_WIDGETS_MIN_ELEMENTOR, '<')) {
        return;
    }

    $booted = true;
    require_once CEEDUCON_ELEMENTOR_WIDGETS_PATH . 'includes/class-plugin.php';
    \CEEDUCON\Elementor\Plugin::instance();
}

if (did_action('elementor/loaded')) {
    ceeducon_elementor_widgets_boot();
} else {
    add_action('elementor/loaded', 'ceeducon_elementor_widgets_boot');
}

function ceeducon_elementor_widgets_missing_dependency_notice(): void
{
    if (!current_user_can('activate_plugins')) {
        return;
    }

    if (did_action('elementor/loaded') && defined('ELEMENTOR_VERSION') && version_compare(ELEMENTOR_VERSION, CEEDUCON_ELEMENTOR_WIDGETS_MIN_ELEMENTOR, '>=')) {
        return;
    }

    echo '<div class="notice notice-warning"><p>';
    if (!did_action('elementor/loaded')) {
        echo esc_html__('CEEDUCON Elementor Widgets requires the free Elementor plugin to be active.', 'ceeducon-elementor-widgets');
    } else {
        printf(
            esc_html__('CEEDUCON Elementor Widgets requires Elementor %s or newer. The widgets remain disabled until Elementor is updated.', 'ceeducon-elementor-widgets'),
            esc_html(CEEDUCON_ELEMENTOR_WIDGETS_MIN_ELEMENTOR)
        );
    }
    echo '</p></div>';
}
add_action('admin_notices', 'ceeducon_elementor_widgets_missing_dependency_notice');

function ceeducon_elementor_widgets_theme_notice(): void
{
    if (!current_user_can('activate_plugins') || function_exists('ceeducon_print_section')) {
        return;
    }

    echo '<div class="notice notice-warning"><p>';
    echo esc_html__('CEEDUCON Elementor Widgets is active, but the CEEDUCON Programme theme is not active. Widgets are kept safe and will render after the supplied theme is activated.', 'ceeducon-elementor-widgets');
    echo '</p></div>';
}
add_action('admin_notices', 'ceeducon_elementor_widgets_theme_notice');
