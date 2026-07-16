<?php
/**
 * Plugin Name: CEEDUCON Elementor Widgets
 * Description: Native CEEDUCON section widgets for Elementor Free, rendered by the CEEDUCON theme.
 * Version: 1.1.5
 * Requires at least: 6.4
 * Requires PHP: 8.0
 * Requires Plugins: elementor
 * Author: CEEDUCON
 * Text Domain: ceeducon-elementor-widgets
 */

if (!defined('ABSPATH')) {
    exit;
}

define('CEEDUCON_ELEMENTOR_WIDGETS_VERSION', '1.1.5');
define('CEEDUCON_ELEMENTOR_WIDGETS_FILE', __FILE__);
define('CEEDUCON_ELEMENTOR_WIDGETS_PATH', plugin_dir_path(__FILE__));

function ceeducon_elementor_widgets_boot(): void
{
    static $booted = false;
    if ($booted) {
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
    if (did_action('elementor/loaded') || !current_user_can('activate_plugins')) {
        return;
    }

    echo '<div class="notice notice-warning"><p>';
    echo esc_html__('CEEDUCON Elementor Widgets requires the free Elementor plugin to be active.', 'ceeducon-elementor-widgets');
    echo '</p></div>';
}
add_action('admin_notices', 'ceeducon_elementor_widgets_missing_dependency_notice');
