<?php
/**
 * Xinyun Theme - 基础设置
 *
 * 处理主题的基础设置，包括颜色、布局等
 *
 * @package Xinyun
 * @since 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 基础设置类
 */
class Xinyun_Basic_Settings {

    /**
     * 主题选项实例
     *
     * @var Xinyun_Theme_Options
     */
    private Xinyun_Theme_Options $theme_options;

    /**
     * 构造函数
     *
     * @param Xinyun_Theme_Options $theme_options 主题选项实例
     */
    public function __construct(Xinyun_Theme_Options $theme_options) {
        $this->theme_options = $theme_options;
        $this->init();
    }

    /**
     * 初始化基础设置
     */
    private function init(): void {
        add_action('admin_init', [$this, 'init_basic_settings']);
    }

    /**
     * 初始化基础设置
     */
    public function init_basic_settings(): void {
        // 基础设置section
        add_settings_section(
            'basic_section',
            '基础设置',
            [$this, 'basic_section_callback'],
            $this->theme_options->get_page_slug() . '_basic'
        );

        // 主色调
        add_settings_field(
            'primary_color',
            '主色调',
            [$this, 'color_field_callback'],
            $this->theme_options->get_page_slug() . '_basic',
            'basic_section',
            [
                'field_name' => 'primary_color',
                'default' => '#007cba',
                'description' => '设置主题的主要颜色'
            ]
        );

        // 辅助色
        add_settings_field(
            'secondary_color',
            '辅助色',
            [$this, 'color_field_callback'],
            $this->theme_options->get_page_slug() . '_basic',
            'basic_section',
            [
                'field_name' => 'secondary_color',
                'default' => '#f9f9f9',
                'description' => '设置主题的辅助颜色'
            ]
        );

        // 容器宽度
        add_settings_field(
            'container_width',
            '容器最大宽度',
            [$this, 'number_field_callback'],
            $this->theme_options->get_page_slug() . '_basic',
            'basic_section',
            [
                'field_name' => 'container_width',
                'default' => 1200,
                'min' => 800,
                'max' => 1600,
                'step' => 50,
                'unit' => 'px',
                'description' => '设置网站内容的最大宽度'
            ]
        );
    }

    /**
     * 基础设置section回调
     */
    public function basic_section_callback(): void {
        echo '<p>配置主题的基础显示设置，包括颜色、布局等。</p>';
    }

    /**
     * 颜色字段回调
     */
    public function color_field_callback(array $args): void {
        $options = $this->theme_options->get_options();
        $value = $options[$args['field_name']] ?? $args['default'];

        printf(
            '<input type="color" name="%s[%s]" id="%s" value="%s" class="color-picker" />',
            $this->theme_options->get_option_name(),
            $args['field_name'],
            $args['field_name'],
            esc_attr($value)
        );

        if (!empty($args['description'])) {
            printf('<p class="description">%s</p>', esc_html($args['description']));
        }
    }

    /**
     * 数字输入字段回调
     */
    public function number_field_callback(array $args): void {
        $options = $this->theme_options->get_options();
        $value = $options[$args['field_name']] ?? $args['default'];

        printf(
            '<input type="number" name="%s[%s]" id="%s" value="%s" min="%d" max="%d" step="%s" class="small-text" /> %s',
            $this->theme_options->get_option_name(),
            $args['field_name'],
            $args['field_name'],
            esc_attr($value),
            $args['min'],
            $args['max'],
            $args['step'],
            !empty($args['unit']) ? '<span class="unit">' . esc_html($args['unit']) . '</span>' : ''
        );

        if (!empty($args['description'])) {
            echo '<p class="description">' . esc_html($args['description']) . '</p>';
        }
    }
}

