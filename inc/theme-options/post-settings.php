<?php
/**
 * Xinyun Theme - 文章设置
 *
 * 处理文章显示相关设置
 *
 * @package Xinyun
 * @since 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 文章设置类
 */
class Xinyun_Post_Settings {

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
     * 初始化文章设置
     */
    private function init(): void {
        add_action('admin_init', [$this, 'init_post_settings']);
    }

    /**
     * 初始化文章设置
     */
    public function init_post_settings(): void {
        // 文章设置section
        add_settings_section(
            'post_section',
            '文章显示设置',
            [$this, 'post_section_callback'],
            $this->theme_options->get_page_slug() . '_post'
        );

        // 摘要长度
        add_settings_field(
            'excerpt_length',
            '文章摘要长度',
            [$this, 'number_field_callback'],
            $this->theme_options->get_page_slug() . '_post',
            'post_section',
            [
                'field_name' => 'excerpt_length',
                'default' => 30,
                'min' => 10,
                'max' => 100,
                'step' => 5,
                'unit' => '字',
                'description' => '设置文章列表中摘要的字数'
            ]
        );

        // 显示特色图片
        add_settings_field(
            'show_featured_image',
            '显示特色图片',
            [$this, 'checkbox_field_callback'],
            $this->theme_options->get_page_slug() . '_post',
            'post_section',
            [
                'field_name' => 'show_featured_image',
                'default' => true,
                'description' => '在文章列表中显示文章的特色图片'
            ]
        );

        // 显示作者信息
        add_settings_field(
            'show_author_info',
            '显示作者信息',
            [$this, 'checkbox_field_callback'],
            $this->theme_options->get_page_slug() . '_post',
            'post_section',
            [
                'field_name' => 'show_author_info',
                'default' => true,
                'description' => '在文章中显示作者信息'
            ]
        );

        // 显示发布日期
        add_settings_field(
            'show_post_date',
            '显示发布日期',
            [$this, 'checkbox_field_callback'],
            $this->theme_options->get_page_slug() . '_post',
            'post_section',
            [
                'field_name' => 'show_post_date',
                'default' => true,
                'description' => '在文章中显示发布日期'
            ]
        );

        // 显示分类信息
        add_settings_field(
            'show_categories',
            '显示分类信息',
            [$this, 'checkbox_field_callback'],
            $this->theme_options->get_page_slug() . '_post',
            'post_section',
            [
                'field_name' => 'show_categories',
                'default' => true,
                'description' => '在文章中显示分类信息'
            ]
        );
    }

    /**
     * 文章设置section回调
     */
    public function post_section_callback(): void {
        echo '<p>配置文章和博客相关的显示选项。</p>';
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

    /**
     * 复选框字段回调
     */
    public function checkbox_field_callback(array $args): void {
        $options = $this->theme_options->get_options();
        $value = $options[$args['field_name']] ?? $args['default'];

        printf(
            '<label><input type="checkbox" name="%s[%s]" id="%s" value="1" %s /> %s</label>',
            $this->theme_options->get_option_name(),
            $args['field_name'],
            $args['field_name'],
            checked(1, $value, false),
            !empty($args['label']) ? esc_html($args['label']) : ''
        );

        if (!empty($args['description'])) {
            echo '<p class="description">' . esc_html($args['description']) . '</p>';
        }
    }
}

