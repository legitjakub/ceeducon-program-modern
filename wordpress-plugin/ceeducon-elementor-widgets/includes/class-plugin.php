<?php

namespace CEEDUCON\Elementor;

if (!defined('ABSPATH')) {
    exit;
}

final class Plugin
{
    private static ?self $instance = null;

    public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        add_action('elementor/elements/categories_registered', [$this, 'register_category']);
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
    }

    public function register_category($elements_manager): void
    {
        $elements_manager->add_category('ceeducon-sections', [
            'title' => __('CEEDUCON Sections', 'ceeducon-elementor-widgets'),
            'icon'  => 'eicon-site-identity',
        ]);
    }

    public function register_widgets($widgets_manager): void
    {
        require_once CEEDUCON_ELEMENTOR_WIDGETS_PATH . 'includes/class-section-widget.php';

        $widgets = [
            Hero_Widget::class,
            Page_Hero_Widget::class,
            Text_Section_Widget::class,
            Image_Text_Widget::class,
            Cards_Widget::class,
            Testimonials_Widget::class,
            Faq_Widget::class,
            Cta_Widget::class,
            Contact_Widget::class,
            Posts_Widget::class,
            Programme_Widget::class,
        ];

        foreach ($widgets as $widget) {
            $widgets_manager->register(new $widget());
        }
    }
}
