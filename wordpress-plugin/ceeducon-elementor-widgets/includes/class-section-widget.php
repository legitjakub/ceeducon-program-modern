<?php

namespace CEEDUCON\Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit;
}

abstract class Section_Widget extends Widget_Base
{
    abstract protected function section_slug(): string;
    abstract protected function section_title(): string;

    public function get_name(): string
    {
        return 'ceeducon_' . str_replace('-', '_', $this->section_slug());
    }

    public function get_title(): string
    {
        return $this->section_title();
    }

    public function get_icon(): string
    {
        return 'eicon-columns';
    }

    public function get_categories(): array
    {
        return ['ceeducon-sections'];
    }

    public function get_keywords(): array
    {
        return ['ceeducon', 'section', 'event', $this->section_slug()];
    }

    protected function register_controls(): void
    {
        if ($this->section_slug() === 'hero' && function_exists('ceeducon_edition_get')) {
            $this->start_controls_section('ceeducon_annual_settings', [
                'label' => __('Conference edition', 'ceeducon-elementor-widgets'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]);
            $this->add_control('ceeducon_annual_settings_note', [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => sprintf(
                    '%s <a href="%s" target="_blank" rel="noopener">%s</a>',
                    esc_html__('The year, date, venue, registration, statistics, hero image and calendar links are managed centrally.', 'ceeducon-elementor-widgets'),
                    esc_url(admin_url('admin.php?page=ceeducon-edition')),
                    esc_html__('Open Conference edition', 'ceeducon-elementor-widgets')
                ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]);
            $this->end_controls_section();
        }

        $groups = [
            'content' => __('Main content', 'ceeducon-elementor-widgets'),
            'items'   => __('Items', 'ceeducon-elementor-widgets'),
            'details' => __('Additional details', 'ceeducon-elementor-widgets'),
            'actions' => __('Buttons and links', 'ceeducon-elementor-widgets'),
            'media'   => __('Media', 'ceeducon-elementor-widgets'),
            'display' => __('Display', 'ceeducon-elementor-widgets'),
        ];
        $grouped_attributes = array_fill_keys(array_keys($groups), []);

        $central_hero_keys = [
            'kicker', 'imageId', 'imageUrl', 'imageAlt', 'eventDay', 'eventMonth',
            'eventRows', 'googleCalendarText', 'googleCalendarUrl',
            'outlookCalendarText', 'outlookCalendarUrl',
        ];
        foreach ($this->attribute_schema() as $key => $definition) {
            if ($this->section_slug() === 'hero' && function_exists('ceeducon_edition_get') && in_array((string) $key, $central_hero_keys, true)) {
                continue;
            }
            $group = $this->control_group((string) $key, (array) $definition);
            $grouped_attributes[$group][(string) $key] = (array) $definition;
        }

        foreach ($groups as $group => $label) {
            if (!$grouped_attributes[$group]) {
                continue;
            }

            $this->start_controls_section('ceeducon_' . $group, [
                'label' => $label,
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]);

            foreach ($grouped_attributes[$group] as $key => $definition) {
                $this->register_attribute_control($key, $definition);
            }

            $this->end_controls_section();
        }

        $this->register_style_controls();
        $this->register_responsive_controls();
    }

    private function brand_colors(): array
    {
        return [
            ''        => __('Theme default', 'ceeducon-elementor-widgets'),
            '#ffffff' => __('White', 'ceeducon-elementor-widgets'),
            '#0d5e9d' => __('CEEDUCON blue', 'ceeducon-elementor-widgets'),
            '#45c0ea' => __('CEEDUCON sky blue', 'ceeducon-elementor-widgets'),
            '#ec722f' => __('CEEDUCON orange', 'ceeducon-elementor-widgets'),
            '#000000' => __('Black', 'ceeducon-elementor-widgets'),
        ];
    }

    private function register_style_controls(): void
    {
        $this->start_controls_section('ceeducon_style', [
            'label' => __('Style', 'ceeducon-elementor-widgets'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('ceeducon_background_color', [
            'label'     => __('Background', 'ceeducon-elementor-widgets'),
            'type'      => Controls_Manager::SELECT,
            'options'   => $this->brand_colors(),
            'default'   => '',
            'selectors' => ['{{WRAPPER}} > .elementor-widget-container > section' => 'background: {{VALUE}};'],
        ]);
        $this->add_control('ceeducon_heading_color', [
            'label'     => __('Heading colour', 'ceeducon-elementor-widgets'),
            'type'      => Controls_Manager::SELECT,
            'options'   => $this->brand_colors(),
            'default'   => '',
            'selectors' => ['{{WRAPPER}} > .elementor-widget-container > section h1, {{WRAPPER}} > .elementor-widget-container > section h2, {{WRAPPER}} > .elementor-widget-container > section h3' => 'color: {{VALUE}};'],
        ]);
        $this->add_control('ceeducon_text_color', [
            'label'     => __('Text colour', 'ceeducon-elementor-widgets'),
            'type'      => Controls_Manager::SELECT,
            'options'   => $this->brand_colors(),
            'default'   => '',
            'selectors' => ['{{WRAPPER}} > .elementor-widget-container > section p, {{WRAPPER}} > .elementor-widget-container > section li' => 'color: {{VALUE}};'],
        ]);
        $this->add_control('ceeducon_accent_color', [
            'label'     => __('Accent colour', 'ceeducon-elementor-widgets'),
            'type'      => Controls_Manager::SELECT,
            'options'   => $this->brand_colors(),
            'default'   => '',
            'selectors' => [
                '{{WRAPPER}} > .elementor-widget-container > section .btn--primary' => 'box-shadow: inset 0 -4px 0 {{VALUE}};',
                '{{WRAPPER}} > .elementor-widget-container > section .btn--plain' => 'border-bottom-color: {{VALUE}};',
            ],
        ]);
        $this->add_control('ceeducon_content_width', [
            'label'   => __('Content width', 'ceeducon-elementor-widgets'),
            'type'    => Controls_Manager::SELECT,
            'options' => [
                ''       => __('Theme default', 'ceeducon-elementor-widgets'),
                '1360px' => __('Wide (1360 px)', 'ceeducon-elementor-widgets'),
                '1080px' => __('Compact (1080 px)', 'ceeducon-elementor-widgets'),
                '880px'  => __('Reading width (880 px)', 'ceeducon-elementor-widgets'),
                '100%'   => __('Full width', 'ceeducon-elementor-widgets'),
            ],
            'default'   => '',
            'selectors' => ['{{WRAPPER}} > .elementor-widget-container > section .shell' => 'max-width: {{VALUE}};'],
        ]);
        if (in_array($this->section_slug(), ['hero', 'image-text', 'cards', 'photo-gallery', 'partners'], true)) {
            $this->add_control('ceeducon_image_position', [
                'label'   => __('Image focal point', 'ceeducon-elementor-widgets'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    ''       => __('Theme default', 'ceeducon-elementor-widgets'),
                    'center' => __('Centre', 'ceeducon-elementor-widgets'),
                    'top'    => __('Top', 'ceeducon-elementor-widgets'),
                    'bottom' => __('Bottom', 'ceeducon-elementor-widgets'),
                    'left'   => __('Left', 'ceeducon-elementor-widgets'),
                    'right'  => __('Right', 'ceeducon-elementor-widgets'),
                ],
                'default'   => '',
                'selectors' => ['{{WRAPPER}} > .elementor-widget-container > section img' => 'object-position: {{VALUE}};'],
            ]);
        }

        $this->end_controls_section();
    }

    private function register_responsive_controls(): void
    {
        $this->start_controls_section('ceeducon_responsive', [
            'label' => __('Responsive', 'ceeducon-elementor-widgets'),
            'tab'   => Controls_Manager::TAB_RESPONSIVE,
        ]);

        $spacing = [
            'unit' => 'px',
            'size' => '',
        ];
        $this->add_responsive_control('ceeducon_padding_top', [
            'label'      => __('Top spacing', 'ceeducon-elementor-widgets'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 240]],
            'default'    => $spacing,
            'selectors'  => ['{{WRAPPER}} > .elementor-widget-container > section' => 'padding-top: {{SIZE}}{{UNIT}};'],
        ]);
        $this->add_responsive_control('ceeducon_padding_bottom', [
            'label'      => __('Bottom spacing', 'ceeducon-elementor-widgets'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 240]],
            'default'    => $spacing,
            'selectors'  => ['{{WRAPPER}} > .elementor-widget-container > section' => 'padding-bottom: {{SIZE}}{{UNIT}};'],
        ]);
        $this->add_responsive_control('ceeducon_text_alignment', [
            'label'   => __('Text alignment', 'ceeducon-elementor-widgets'),
            'type'    => Controls_Manager::CHOOSE,
            'options' => [
                'left'   => ['title' => __('Left', 'ceeducon-elementor-widgets'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Centre', 'ceeducon-elementor-widgets'), 'icon' => 'eicon-text-align-center'],
                'right'  => ['title' => __('Right', 'ceeducon-elementor-widgets'), 'icon' => 'eicon-text-align-right'],
            ],
            'default'   => '',
            'selectors' => ['{{WRAPPER}} > .elementor-widget-container > section' => 'text-align: {{VALUE}};'],
        ]);

        $grid_selector = $this->responsive_grid_selector();
        if ($grid_selector !== '') {
            $this->add_responsive_control('ceeducon_columns', [
                'label'      => __('Columns', 'ceeducon-elementor-widgets'),
                'type'       => Controls_Manager::NUMBER,
                'min'        => 1,
                'max'        => 6,
                'default'    => '',
                'selectors'  => [$grid_selector => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));'],
            ]);
        }

        if ($this->section_slug() === 'image-text') {
            $this->add_responsive_control('ceeducon_image_order', [
                'label'   => __('Image order', 'ceeducon-elementor-widgets'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    ''      => __('Theme default', 'ceeducon-elementor-widgets'),
                    '1'     => __('Text first', 'ceeducon-elementor-widgets'),
                    '-1'    => __('Image first', 'ceeducon-elementor-widgets'),
                ],
                'default'   => '',
                'selectors' => ['{{WRAPPER}} > .elementor-widget-container > section .media-mosaic' => 'order: {{VALUE}};'],
            ]);
        }

        $this->add_control('ceeducon_reset_note', [
            'type'            => Controls_Manager::RAW_HTML,
            'raw'             => __('Clear a control to return to the original GitHub design. Elementor responsive values can be reset from the device control menu.', 'ceeducon-elementor-widgets'),
            'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
        ]);
        $this->end_controls_section();
    }

    protected function render(): void
    {
        if (!function_exists('ceeducon_print_section')) {
            $elementor = \Elementor\Plugin::$instance ?? null;
            if ($elementor && isset($elementor->editor) && $elementor->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-warning">';
                echo esc_html__('Activate the CEEDUCON theme to render this widget.', 'ceeducon-elementor-widgets');
                echo '</div>';
            }
            return;
        }

        ceeducon_print_section($this->section_slug(), $this->normalized_settings());
    }

    private function attribute_schema(): array
    {
        $relative_path = $this->section_slug() . '.json';
        $candidates = [
            get_template_directory() . '/src/blocks/' . $this->section_slug() . '/block.json',
            CEEDUCON_ELEMENTOR_WIDGETS_PATH . 'schemas/' . $relative_path,
        ];

        foreach ($candidates as $file) {
            if (!is_readable($file)) {
                continue;
            }

            $metadata = json_decode((string) file_get_contents($file), true);
            if (is_array($metadata['attributes'] ?? null)) {
                return $metadata['attributes'];
            }
        }

        return [];
    }

    private function register_attribute_control(string $key, array $definition): void
    {
        if (in_array($key, ['imageId', 'logoId'], true)) {
            return;
        }

        $type = (string) ($definition['type'] ?? 'string');
        $default = $definition['default'] ?? ($type === 'boolean' ? false : '');
        $label = ucwords((string) preg_replace('/(?<!^)[A-Z]/', ' $0', $key));

        if ($type === 'boolean') {
            $this->add_control($key, [
                'label'        => $label,
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => $default ? 'yes' : '',
            ]);
            return;
        }

        if ($type === 'number') {
            $this->add_control($key, [
                'label'   => $label,
                'type'    => Controls_Manager::NUMBER,
                'default' => $default,
            ]);
            return;
        }

        if ($type === 'array') {
            $this->register_array_control($key, $label, is_array($default) ? $default : []);
            return;
        }

        if (in_array($key, ['imageUrl', 'logoUrl'], true)) {
            $this->add_control($key, [
                'label'   => $label,
                'type'    => Controls_Manager::MEDIA,
                'default' => ['url' => (string) $default],
            ]);
            return;
        }

        if ($this->ends_with($key, 'Url')) {
            $this->add_control($key, [
                'label'       => $label,
                'type'        => Controls_Manager::URL,
                'placeholder' => 'https://',
                'default'     => ['url' => (string) $default],
                'label_block' => true,
            ]);
            return;
        }

        $textarea_fields = [
            'title',
            'lead',
            'text',
            'secondText',
            'intro',
            'note',
            'cardTitle',
            'cardText',
            'noteText',
            'partnersText',
        ];
        $control_type = in_array($key, $textarea_fields, true)
            ? Controls_Manager::TEXTAREA
            : Controls_Manager::TEXT;
        $config = [
            'label'       => $label,
            'type'        => $control_type,
            'default'     => $default,
            'label_block' => true,
        ];

        if ($control_type === Controls_Manager::TEXTAREA) {
            $config['rows'] = in_array($key, ['text', 'secondText', 'intro', 'noteText', 'cardText'], true) ? 5 : 3;
        }

        $this->add_control($key, $config);
    }

    private function register_array_control(string $key, string $label, array $default): void
    {
        $first = reset($default);
        if (!is_array($first)) {
            $this->add_control($key, [
                'label'       => $label,
                'type'        => Controls_Manager::TEXTAREA,
                'description' => __('One item per line.', 'ceeducon-elementor-widgets'),
                'default'     => implode("\n", array_map('strval', $default)),
            ]);
            return;
        }

        $repeater = new Repeater();
        foreach (array_keys($first) as $item_key) {
            if (in_array($item_key, ['imageId', 'logoId'], true)) {
                continue;
            }

            $control_type = $item_key === 'imageUrl'
                ? Controls_Manager::MEDIA
                : (($this->ends_with((string) $item_key, 'url') || $this->ends_with((string) $item_key, 'Url'))
                    ? Controls_Manager::URL
                    : (in_array($item_key, ['text', 'answer', 'quote', 'question', 'details'], true) ? Controls_Manager::TEXTAREA : Controls_Manager::TEXT));
            $config = [
                'label'   => ucwords((string) preg_replace('/(?<!^)[A-Z]/', ' $0', (string) $item_key)),
                'type'    => $control_type,
                'default' => in_array($control_type, [Controls_Manager::URL, Controls_Manager::MEDIA], true) ? ['url' => ''] : '',
            ];
            $repeater->add_control((string) $item_key, $config);
        }

        $repeater_default = array_map(static function (array $item): array {
            foreach ($item as $item_key => $value) {
                if ($item_key === 'imageUrl' || substr_compare((string) $item_key, 'url', -3) === 0 || substr_compare((string) $item_key, 'Url', -3) === 0) {
                    $item[$item_key] = ['url' => (string) $value];
                }
            }
            return $item;
        }, $default);

        $title_key = 'label';
        foreach (['title', 'question', 'name', 'label', 'number'] as $candidate) {
            if (array_key_exists($candidate, $first)) {
                $title_key = $candidate;
                break;
            }
        }

        $this->add_control($key, [
            'label'       => $label,
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => $repeater_default,
            'title_field' => '{{{ ' . $title_key . ' }}}',
        ]);
    }

    private function control_group(string $key, array $definition): string
    {
        $type = (string) ($definition['type'] ?? 'string');

        if ($type === 'boolean') {
            return 'display';
        }

        if ($type === 'array') {
            return 'items';
        }

        if (str_starts_with($key, 'image') || str_starts_with($key, 'logo')) {
            return 'media';
        }

        if ($this->ends_with($key, 'Url') || in_array($key, [
            'buttonText',
            'primaryText',
            'secondaryText',
            'eventCtaText',
            'googleCalendarText',
            'outlookCalendarText',
        ], true)) {
            return 'actions';
        }

        if (in_array($key, [
            'count',
            'postType',
            'email',
            'phone',
            'eventDay',
            'eventMonth',
            'noteLabel',
            'noteTitle',
            'noteText',
            'cardLabel',
            'cardTitle',
            'cardText',
            'partnersLabel',
            'partnersText',
        ], true)) {
            return 'details';
        }

        return 'content';
    }

    private function normalized_settings(): array
    {
        $settings = $this->get_settings_for_display();
        $schema = $this->attribute_schema();
        $attributes = [];

        foreach ($schema as $key => $definition) {
            if (in_array($key, ['imageId', 'logoId'], true)) {
                continue;
            }

            $type = (string) ($definition['type'] ?? 'string');
            $value = $settings[$key] ?? ($definition['default'] ?? '');

            if ($type === 'boolean') {
                $attributes[$key] = $value === 'yes';
            } elseif ($type === 'number') {
                $attributes[$key] = (int) $value;
            } elseif ($type === 'array' && is_string($value)) {
                $attributes[$key] = array_values(array_filter(array_map('trim', preg_split('/\R/', $value) ?: [])));
            } elseif ($type === 'array' && is_array($value)) {
                $attributes[$key] = array_map([$this, 'normalize_repeater_item'], $value);
            } elseif (is_array($value) && array_key_exists('url', $value)) {
                $attributes[$key] = (string) $value['url'];
                if ($key === 'imageUrl' && !empty($value['id'])) {
                    $attributes['imageId'] = (int) $value['id'];
                }
                if ($key === 'logoUrl' && !empty($value['id'])) {
                    $attributes['logoId'] = (int) $value['id'];
                }
            } else {
                $attributes[$key] = $value;
            }
        }

        return $attributes;
    }

    private function normalize_repeater_item($item): array
    {
        if (!is_array($item)) {
            return [];
        }
        unset($item['_id']);
        foreach ($item as $key => $value) {
            if (is_array($value) && array_key_exists('url', $value)) {
                if ($key === 'imageUrl' && !empty($value['id'])) {
                    $item['imageId'] = (int) $value['id'];
                }
                $item[$key] = (string) $value['url'];
            }
        }
        return $item;
    }

    private function ends_with(string $value, string $suffix): bool
    {
        if ($suffix === '') {
            return true;
        }

        return strlen($value) >= strlen($suffix)
            && substr_compare($value, $suffix, -strlen($suffix)) === 0;
    }

    private function responsive_grid_selector(): string
    {
        $selectors = [
            'cards'             => '.tile-grid',
            'testimonials'      => '.testimonial-grid',
            'photo-gallery'     => '.photo-gallery-grid',
            'themes'            => '.theme-grid',
            'schedule-overview' => '.day-cards',
        ];
        $selector = $selectors[$this->section_slug()] ?? '';

        return $selector === ''
            ? ''
            : '{{WRAPPER}} > .elementor-widget-container > section ' . $selector;
    }
}

final class Hero_Widget extends Section_Widget { protected function section_slug(): string { return 'hero'; } protected function section_title(): string { return __('Hero Section', 'ceeducon-elementor-widgets'); } }
final class Page_Hero_Widget extends Section_Widget { protected function section_slug(): string { return 'page-hero'; } protected function section_title(): string { return __('Page Hero', 'ceeducon-elementor-widgets'); } }
final class Text_Section_Widget extends Section_Widget { protected function section_slug(): string { return 'text-section'; } protected function section_title(): string { return __('Text Section', 'ceeducon-elementor-widgets'); } }
final class Image_Text_Widget extends Section_Widget { protected function section_slug(): string { return 'image-text'; } protected function section_title(): string { return __('Image + Text', 'ceeducon-elementor-widgets'); } }
final class Cards_Widget extends Section_Widget { protected function section_slug(): string { return 'cards'; } protected function section_title(): string { return __('Cards', 'ceeducon-elementor-widgets'); } }
final class Testimonials_Widget extends Section_Widget { protected function section_slug(): string { return 'testimonials'; } protected function section_title(): string { return __('Testimonials', 'ceeducon-elementor-widgets'); } }
final class Faq_Widget extends Section_Widget { protected function section_slug(): string { return 'faq'; } protected function section_title(): string { return __('FAQ', 'ceeducon-elementor-widgets'); } }
final class Cta_Widget extends Section_Widget { protected function section_slug(): string { return 'cta'; } protected function section_title(): string { return __('CTA Section', 'ceeducon-elementor-widgets'); } }
final class Contact_Widget extends Section_Widget { protected function section_slug(): string { return 'contact'; } protected function section_title(): string { return __('Contact', 'ceeducon-elementor-widgets'); } }
final class Posts_Widget extends Section_Widget { protected function section_slug(): string { return 'posts'; } protected function section_title(): string { return __('Posts', 'ceeducon-elementor-widgets'); } }
final class Programme_Widget extends Section_Widget
{
    protected function section_slug(): string { return 'programme-grid'; }
    protected function section_title(): string { return __('Programme', 'ceeducon-elementor-widgets'); }

    protected function render(): void
    {
        if (function_exists('ceeducon_enqueue_programme_assets')) {
            ceeducon_enqueue_programme_assets();
        }
        parent::render();
    }
}
final class Photo_Gallery_Widget extends Section_Widget { protected function section_slug(): string { return 'photo-gallery'; } protected function section_title(): string { return __('Photo Gallery', 'ceeducon-elementor-widgets'); } }
final class Video_Widget extends Section_Widget { protected function section_slug(): string { return 'video'; } protected function section_title(): string { return __('Video Section', 'ceeducon-elementor-widgets'); } }
final class Themes_Widget extends Section_Widget { protected function section_slug(): string { return 'themes'; } protected function section_title(): string { return __('Conference Themes', 'ceeducon-elementor-widgets'); } }
final class Schedule_Overview_Widget extends Section_Widget { protected function section_slug(): string { return 'schedule-overview'; } protected function section_title(): string { return __('Two-day Overview', 'ceeducon-elementor-widgets'); } }
final class Venue_Widget extends Section_Widget { protected function section_slug(): string { return 'venue'; } protected function section_title(): string { return __('Venue', 'ceeducon-elementor-widgets'); } }
final class Partners_Widget extends Section_Widget { protected function section_slug(): string { return 'partners'; } protected function section_title(): string { return __('Organisers and Partners', 'ceeducon-elementor-widgets'); } }
