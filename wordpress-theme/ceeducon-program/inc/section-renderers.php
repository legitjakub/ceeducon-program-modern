<?php
/**
 * Shared section rendering adapter.
 *
 * Gutenberg dynamic blocks and the companion Elementor plugin use the same
 * render templates, so frontend markup remains owned by the theme.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ceeducon_render_section(string $section, array $attributes = []): string
{
    $allowed = [
        'hero',
        'page-hero',
        'text-section',
        'image-text',
        'cards',
        'testimonials',
        'faq',
        'cta',
        'contact',
        'posts',
        'programme-grid',
    ];

    if (!in_array($section, $allowed, true)) {
        return '';
    }

    $template = get_template_directory() . '/src/blocks/' . $section . '/render.php';
    if (!is_readable($template)) {
        return '';
    }

    ob_start();
    include $template;
    return (string) ob_get_clean();
}

function ceeducon_print_section(string $section, array $attributes = []): void
{
    echo ceeducon_render_section($section, $attributes); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

