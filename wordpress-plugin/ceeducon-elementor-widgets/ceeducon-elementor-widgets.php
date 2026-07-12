<?php
/**
 * Plugin Name: CEEDUCON Elementor Widgets
 * Description: Native CEEDUCON section widgets for Elementor Free, rendered by the CEEDUCON theme.
 * Version: 1.0.1
 * Requires at least: 6.4
 * Requires PHP: 8.0
 * Requires Plugins: elementor
 * Author: CEEDUCON
 * Text Domain: ceeducon-elementor-widgets
 */

if (!defined('ABSPATH')) {
    exit;
}

define('CEEDUCON_ELEMENTOR_WIDGETS_VERSION', '1.0.1');
define('CEEDUCON_ELEMENTOR_WIDGETS_FILE', __FILE__);
define('CEEDUCON_ELEMENTOR_WIDGETS_PATH', plugin_dir_path(__FILE__));

function ceeducon_elementor_widgets_boot(): void
{
    if (!did_action('elementor/loaded')) {
        add_action('admin_notices', static function (): void {
            if (!current_user_can('activate_plugins')) {
                return;
            }
            echo '<div class="notice notice-warning"><p>';
            echo esc_html__('CEEDUCON Elementor Widgets requires the free Elementor plugin to be active.', 'ceeducon-elementor-widgets');
            echo '</p></div>';
        });
        return;
    }

    require_once CEEDUCON_ELEMENTOR_WIDGETS_PATH . 'includes/class-section-widget.php';
    require_once CEEDUCON_ELEMENTOR_WIDGETS_PATH . 'includes/class-plugin.php';
    \CEEDUCON\Elementor\Plugin::instance();
}
add_action('plugins_loaded', 'ceeducon_elementor_widgets_boot');
