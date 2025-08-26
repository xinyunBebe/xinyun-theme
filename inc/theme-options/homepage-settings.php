<?php
/**
 * Xinyun Theme - 首页设置
 *
 * 处理首页轮播图等相关设置
 *
 * @package Xinyun
 * @since 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 首页设置类
 */
class Xinyun_Homepage_Settings {

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
     * 初始化首页设置
     */
    private function init(): void {
        add_action('admin_init', [$this, 'init_homepage_settings']);
    }

    /**
     * 初始化首页设置
     */
    public function init_homepage_settings(): void {
        // 轮播图设置section
        add_settings_section(
            'carousel_section',
            '首页轮播图设置',
            [$this, 'carousel_section_callback'],
            $this->theme_options->get_page_slug() . '_homepage'
        );

        // 获取轮播图管理器
        $carousel_manager = Xinyun_Carousel_Manager::get_instance();
        $carousel_choices = $carousel_manager->get_carousel_choices();

        // 轮播图类型选择
        add_settings_field(
            'homepage_carousel_type',
            '轮播图类型',
            [$this, 'select_field_callback'],
            $this->theme_options->get_page_slug() . '_homepage',
            'carousel_section',
            [
                'field_name' => 'homepage_carousel_type',
                'choices' => $carousel_choices,
                'default' => 'post',
                'description' => '选择首页要显示的轮播图类型'
            ]
        );

        // 轮播图高度
        add_settings_field(
            'homepage_carousel_height',
            '轮播图高度',
            [$this, 'number_field_callback'],
            $this->theme_options->get_page_slug() . '_homepage',
            'carousel_section',
            [
                'field_name' => 'homepage_carousel_height',
                'default' => 400,
                'min' => 200,
                'max' => 800,
                'step' => 50,
                'unit' => 'px',
                'description' => '设置桌面端轮播图的高度（像素）'
            ]
        );

        // 自动播放
        add_settings_field(
            'homepage_carousel_autoplay',
            '自动播放',
            [$this, 'checkbox_field_callback'],
            $this->theme_options->get_page_slug() . '_homepage',
            'carousel_section',
            [
                'field_name' => 'homepage_carousel_autoplay',
                'default' => true,
                'description' => '启用轮播图自动播放功能'
            ]
        );

        // 播放间隔
        add_settings_field(
            'homepage_carousel_interval',
            '播放间隔',
            [$this, 'number_field_callback'],
            $this->theme_options->get_page_slug() . '_homepage',
            'carousel_section',
            [
                'field_name' => 'homepage_carousel_interval',
                'default' => 5000,
                'min' => 2000,
                'max' => 10000,
                'step' => 500,
                'unit' => '毫秒',
                'description' => '自动播放的时间间隔'
            ]
        );

        // 文章数量
        add_settings_field(
            'homepage_carousel_posts_count',
            '显示文章数量',
            [$this, 'number_field_callback'],
            $this->theme_options->get_page_slug() . '_homepage',
            'carousel_section',
            [
                'field_name' => 'homepage_carousel_posts_count',
                'default' => 5,
                'min' => 3,
                'max' => 10,
                'step' => 1,
                'unit' => '篇',
                'description' => '轮播图中显示的文章数量，最大值为10篇'
            ]
        );

        // 显示箭头
        add_settings_field(
            'homepage_carousel_arrows',
            '显示导航箭头',
            [$this, 'checkbox_field_callback'],
            $this->theme_options->get_page_slug() . '_homepage',
            'carousel_section',
            [
                'field_name' => 'homepage_carousel_arrows',
                'default' => true,
                'description' => '显示轮播图的左右导航箭头'
            ]
        );

        // 显示分页器
        add_settings_field(
            'homepage_carousel_pagination',
            '显示分页指示器',
            [$this, 'checkbox_field_callback'],
            $this->theme_options->get_page_slug() . '_homepage',
            'carousel_section',
            [
                'field_name' => 'homepage_carousel_pagination',
                'default' => true,
                'description' => '显示轮播图底部的分页指示器'
            ]
        );

        // 自定义轮播图配置
        add_settings_field(
            'homepage_carousel_custom_slides',
            '自定义轮播图',
            [$this, 'custom_slides_field_callback'],
            $this->theme_options->get_page_slug() . '_homepage',
            'carousel_section',
            [
                'field_name' => 'homepage_carousel_custom_slides',
                'description' => '自定义轮播图内容，可以指定特定的图片和文章'
            ]
        );
    }

    /**
     * 轮播图设置section回调
     */
    public function carousel_section_callback(): void {
        echo '<p>设置首页轮播图的显示类型和各项参数。轮播图会显示在首页标题栏下方。</p>';
    }

    /**
     * 下拉选择字段回调
     */
    public function select_field_callback(array $args): void {
        $options = $this->theme_options->get_options();
        $value = $options[$args['field_name']] ?? $args['default'];

        echo '<select name="' . $this->theme_options->get_option_name() . '[' . $args['field_name'] . ']" id="' . $args['field_name'] . '">';

        foreach ($args['choices'] as $key => $label) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($key),
                selected($value, $key, false),
                esc_html($label)
            );
        }

        echo '</select>';

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
     * 自定义轮播图字段回调
     */
    public function custom_slides_field_callback(array $args): void {
        $options = $this->theme_options->get_options();
        $slides = $options[$args['field_name']] ?? [];

        // 确保至少有一个空的配置项
        if (empty($slides)) {
            $slides = [['image_id' => '', 'post_id' => '']];
        }

        echo '<div id="custom-slides-container">';
        echo '<p class="description">' . esc_html($args['description']) . '</p>';

        foreach ($slides as $index => $slide) {
            $this->render_custom_slide_config($index, $slide, $args['field_name']);
        }

        echo '</div>';

        echo '<div style="margin-top: 15px;">';
        echo '<button type="button" id="add-slide" class="button button-secondary">+ 添加轮播图</button>';
        echo '<button type="button" id="remove-slide" class="button button-secondary" style="margin-left: 10px;">- 删除最后一个</button>';
        echo '</div>';

        // 添加JavaScript和CSS
        $this->add_media_selector_assets();
    }

    /**
     * 渲染自定义轮播图配置项
     */
    private function render_custom_slide_config(int $index, array $slide, string $field_name): void {
        $image_id = $slide['image_id'] ?? '';
        $post_id = $slide['post_id'] ?? '';

        echo '<div class="slide-config" data-index="' . $index . '" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 5px; background: #fafafa;">';
        echo '<h4>轮播图 ' . ($index + 1) . '</h4>';

        // 图片选择
        echo '<div style="margin-bottom: 15px;">';
        echo '<label><strong>选择图片：</strong></label><br>';
        echo '<div class="image-preview" style="margin: 10px 0;">';

        if ($image_id) {
            $image_url = wp_get_attachment_image_url($image_id, 'medium');
            if ($image_url) {
                echo '<img src="' . esc_url($image_url) . '" style="max-width: 200px; height: auto; border: 1px solid #ddd; border-radius: 3px;">';
            }
        }

        echo '</div>';
        echo '<input type="hidden" name="' . $this->theme_options->get_option_name() . '[' . $field_name . '][' . $index . '][image_id]" value="' . esc_attr($image_id) . '" class="image-id-input">';
        echo '<button type="button" class="button select-image" data-index="' . $index . '">选择图片</button>';
        if ($image_id) {
            echo '<button type="button" class="button remove-image" data-index="' . $index . '" style="margin-left: 10px;">移除图片</button>';
        }
        echo '</div>';

        // 文章ID选择
        echo '<div>';
        echo '<label for="post-id-' . $index . '"><strong>文章ID：</strong></label><br>';
        echo '<input type="number" id="post-id-' . $index . '" name="' . $this->theme_options->get_option_name() . '[' . $field_name . '][' . $index . '][post_id]" value="' . esc_attr($post_id) . '" min="1" style="width: 100px;" placeholder="留空自动选择">';
        echo '<button type="button" class="button preview-post" data-index="' . $index . '" style="margin-left: 10px;">预览文章</button>';

        // 显示文章信息
        if ($post_id) {
            $post = get_post($post_id);
            if ($post) {
                echo '<div style="margin-top: 10px; padding: 10px; background: #e8f4f8; border-radius: 3px;">';
                echo '<strong>文章：</strong>' . esc_html($post->post_title) . '<br>';
                echo '<strong>状态：</strong>' . esc_html($post->post_status) . '<br>';
                echo '<a href="' . esc_url(get_permalink($post_id)) . '" target="_blank">查看文章</a>';
                echo '</div>';
            } else {
                echo '<div style="margin-top: 10px; padding: 10px; background: #ffeaa7; border-radius: 3px; color: #d63031;">文章ID不存在</div>';
            }
        }

        echo '</div>';
        echo '</div>';
    }

    /**
     * 添加媒体选择器相关资源
     */
    private function add_media_selector_assets(): void {
        // 加载WordPress媒体库
        wp_enqueue_media();

        // 添加自定义JavaScript
        echo '<script type="text/javascript">
        jQuery(document).ready(function($) {
            var mediaUploader;

            // 选择图片按钮点击事件
            $(document).on("click", ".select-image", function(e) {
                e.preventDefault();
                var button = $(this);
                var index = button.data("index");

                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }

                mediaUploader = wp.media({
                    title: "选择轮播图片",
                    button: { text: "选择图片" },
                    multiple: false
                });

                mediaUploader.on("select", function() {
                    var attachment = mediaUploader.state().get("selection").first().toJSON();
                    var container = button.closest(".slide-config");

                    // 更新隐藏字段
                    container.find(".image-id-input").val(attachment.id);

                    // 更新预览图片
                    var imageUrl = attachment.sizes && attachment.sizes.medium ?
                                   attachment.sizes.medium.url : attachment.url;
                    container.find(".image-preview").html(
                        "<img src=\"" + imageUrl + "\" style=\"max-width: 200px; height: auto; border: 1px solid #ddd; border-radius: 3px;\">"
                    );

                    // 添加移除按钮
                    if (!container.find(".remove-image").length) {
                        button.after("<button type=\"button\" class=\"button remove-image\" data-index=\"" + index + "\" style=\"margin-left: 10px;\">移除图片</button>");
                    }
                });

                mediaUploader.open();
            });

            // 移除图片按钮点击事件
            $(document).on("click", ".remove-image", function(e) {
                e.preventDefault();
                var container = $(this).closest(".slide-config");
                container.find(".image-id-input").val("");
                container.find(".image-preview").html("");
                $(this).remove();
            });

            // 预览文章按钮点击事件
            $(document).on("click", ".preview-post", function(e) {
                e.preventDefault();
                var container = $(this).closest(".slide-config");
                var postId = container.find("input[type=number]").val();

                if (!postId) {
                    alert("请先输入文章ID");
                    return;
                }

                var postUrl = "' . admin_url('post.php') . '?post=" + postId + "&action=edit";
                window.open(postUrl, "_blank");
            });

            // 添加轮播图
            $("#add-slide").click(function() {
                var container = $("#custom-slides-container");
                var slideCount = container.find(".slide-config").length;

                if (slideCount >= 10) {
                    alert("最多只能添加10个轮播图");
                    return;
                }

                var newSlide = \'<div class="slide-config" data-index="\' + slideCount + \'" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 5px; background: #fafafa;">\' +
                    \'<h4>轮播图 \' + (slideCount + 1) + \'</h4>\' +
                    \'<div style="margin-bottom: 15px;">\' +
                    \'<label><strong>选择图片：</strong></label><br>\' +
                    \'<div class="image-preview" style="margin: 10px 0;"></div>\' +
                    \'<input type="hidden" name="' . $this->theme_options->get_option_name() . '[homepage_carousel_custom_slides][\' + slideCount + \'][image_id]" value="" class="image-id-input">\' +
                    \'<button type="button" class="button select-image" data-index="\' + slideCount + \'">选择图片</button>\' +
                    \'</div>\' +
                    \'<div>\' +
                    \'<label for="post-id-\' + slideCount + \'"><strong>文章ID：</strong></label><br>\' +
                    \'<input type="number" id="post-id-\' + slideCount + \'" name="' . $this->theme_options->get_option_name() . '[homepage_carousel_custom_slides][\' + slideCount + \'][post_id]" value="" min="1" style="width: 100px;" placeholder="留空自动选择">\' +
                    \'<button type="button" class="button preview-post" data-index="\' + slideCount + \'" style="margin-left: 10px;">预览文章</button>\' +
                    \'</div>\' +
                    \'</div>\';

                container.append(newSlide);
            });

            // 删除最后一个轮播图
            $("#remove-slide").click(function() {
                var slides = $("#custom-slides-container .slide-config");

                if (slides.length > 1) {
                    slides.last().remove();
                } else {
                    alert("至少需要保留一个轮播图配置");
                }
            });
        });
        </script>';
    }
}

