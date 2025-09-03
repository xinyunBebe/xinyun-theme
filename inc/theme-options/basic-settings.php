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

        // 新增：默认特色图片
        add_settings_field(
            'default_featured_image',
            '默认特色图片',
            [$this, 'media_selector_callback'],
            $this->theme_options->get_page_slug() . '_basic',
            'basic_section',
            [
                'field_name' => 'default_featured_image',
                'description' => '当文章没有设置特色图片时，将使用此图片作为默认显示。'
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

    /**
     * 媒体选择器字段回调
     */
    public function media_selector_callback(array $args): void {
        $options = $this->theme_options->get_options();
        $field_name = $args['field_name'];
        $image_id = $options[$field_name] ?? '';

        echo '<div class="basic-image-upload-wrapper" id="' . esc_attr($field_name) . '_wrapper">';
        echo '<div class="basic-image-preview" style="margin: 10px 0; min-height: 100px; border: 2px dashed #ddd; padding: 10px; display: inline-block;">';
        if ($image_id) {
            $image_url = wp_get_attachment_image_url($image_id, 'medium');
            if ($image_url) {
                echo '<img src="' . esc_url($image_url) . '" style="max-width: 200px; height: auto;">';
            }
        }
        echo '</div><br>';
        echo '<input type="hidden" name="' . $this->theme_options->get_option_name() . '[' . $field_name . ']" value="' . esc_attr($image_id) . '" class="basic-image-id-input">';
        echo '<button type="button" class="button basic-select-image">选择图片</button>';
        echo '<button type="button" class="button basic-remove-image" style="margin-left: 10px;' . (!$image_id ? ' display:none;' : '') . '">移除图片</button>';
        echo '</div>';

        if (!empty($args['description'])) {
            echo '<p class="description">' . esc_html($args['description']) . '</p>';
        }

        $this->add_media_selector_assets();
    }

    /**
     * 添加媒体选择器所需的JavaScript
     */
    private function add_media_selector_assets(): void {
        static $basic_media_added = false;
        if ($basic_media_added) {
            return;
        }
        $basic_media_added = true;

        wp_enqueue_media();

        $js_code = <<<JS
        <script type="text/javascript">
        (function($) {
            "use strict";
            
            // 基础设置专用的媒体选择器
            var basicMediaUploader;

            $(document).on("click", ".basic-select-image", function(e) {
                e.preventDefault();
                var wrapper = $(this).closest('.basic-image-upload-wrapper');

                if (basicMediaUploader) {
                    basicMediaUploader.open();
                    return;
                }

                basicMediaUploader = wp.media.frames.basic_file_frame = wp.media({
                    title: "选择图片",
                    button: { text: "选择此图片" },
                    multiple: false
                });

                basicMediaUploader.on("select", function() {
                    var attachment = basicMediaUploader.state().get("selection").first().toJSON();
                    wrapper.find(".basic-image-id-input").val(attachment.id);
                    var imageUrl = attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url;
                    wrapper.find(".basic-image-preview").html('<img src="' + imageUrl + '" style="max-width: 200px; height: auto;">');
                    wrapper.find(".basic-remove-image").show();
                });

                basicMediaUploader.open();
            });

            $(document).on("click", ".basic-remove-image", function(e) {
                e.preventDefault();
                var wrapper = $(this).closest('.basic-image-upload-wrapper');
                wrapper.find(".basic-image-id-input").val("");
                wrapper.find(".basic-image-preview").html("");
                $(this).hide();
            });
        })(jQuery);
        </script>
JS;
        echo $js_code;
    }
}