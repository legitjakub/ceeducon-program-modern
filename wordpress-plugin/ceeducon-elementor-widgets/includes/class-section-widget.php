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
        $this->start_controls_section('content', [
            'label' => __('Content', 'ceeducon-elementor-widgets'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        foreach ($this->attribute_schema() as $key => $definition) {
            $this->register_attribute_control((string) $key, (array) $definition);
        }

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
        $file = get_template_directory() . '/src/blocks/' . $this->section_slug() . '/block.json';
        if (!is_readable($file)) {
            return [];
        }

        $metadata = json_decode((string) file_get_contents($file), true);
        return is_array($metadata['attributes'] ?? null) ? $metadata['attributes'] : [];
    }

    private function register_attribute_control(string $key, array $definition): void
    {
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
            ]);
            return;
        }

        $rich_fields = ['title', 'lead', 'text', 'secondText', 'intro', 'cardText', 'noteText'];
        $this->add_control($key, [
            'label'   => $label,
            'type'    => in_array($key, $rich_fields, true) ? Controls_Manager::WYSIWYG : Controls_Manager::TEXT,
            'default' => $default,
        ]);
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
            $control_type = $this->ends_with((string) $item_key, 'url') || $this->ends_with((string) $item_key, 'Url')
                ? Controls_Manager::URL
                : (in_array($item_key, ['text', 'answer', 'quote'], true) ? Controls_Manager::TEXTAREA : Controls_Manager::TEXT);
            $config = [
                'label'   => ucwords((string) preg_replace('/(?<!^)[A-Z]/', ' $0', (string) $item_key)),
                'type'    => $control_type,
                'default' => $control_type === Controls_Manager::URL ? ['url' => ''] : '',
            ];
            $repeater->add_control((string) $item_key, $config);
        }

        $repeater_default = array_map(static function (array $item): array {
            foreach ($item as $item_key => $value) {
                if (substr_compare((string) $item_key, 'url', -3) === 0 || substr_compare((string) $item_key, 'Url', -3) === 0) {
                    $item[$item_key] = ['url' => (string) $value];
                }
            }
            return $item;
        }, $default);

        $this->add_control($key, [
            'label'       => $label,
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => $repeater_default,
            'title_field' => '{{{ title || question || name || label || "Item" }}}',
        ]);
    }

    private function normalized_settings(): array
    {
        $settings = $this->get_settings_for_display();
        $schema = $this->attribute_schema();
        $attributes = [];

        foreach ($schema as $key => $definition) {
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
