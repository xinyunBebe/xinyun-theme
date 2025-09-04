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

        echo '<div class="slide-config" data-index="' . $index . '" style="position: relative; border: 1px solid #ddd; padding: 12px; margin-bottom: 12px; border-radius: 6px; background: #fafafa;">';
        
        // 右上角删除按钮
        echo '<button type="button" class="slide-delete-btn" data-index="' . $index . '" style="position: absolute; top: 8px; right: 8px; background: #dc3232; color: white; border: none; border-radius: 3px; width: 20px; height: 20px; cursor: pointer; font-size: 12px; line-height: 1;" title="删除此轮播图">×</button>';
        
        echo '<div class="slide-grid" style="display:flex; gap:16px; align-items:stretch;">';
        
        // 左侧：图片
        echo '<div class="slide-left" style="width: 180px; flex: 0 0 180px; display:flex; flex-direction:column;">';
        echo '<label style="margin-bottom:6px;"><strong>图片</strong></label>';
        echo '<div class="carousel-image-preview" style="margin: 6px 0; min-height:100px; display:flex; align-items:center; justify-content:center; background:#fff; border:1px dashed #ddd; border-radius:4px; overflow:hidden;">';

        if ($image_id) {
            $image_url = wp_get_attachment_image_url($image_id, 'medium');
            if ($image_url) {
                echo '<img src="' . esc_url($image_url) . '" style="max-width: 100%; height: auto; display:block;">';
            }
        }
        echo '</div>';
        echo '<input type="hidden" name="' . $this->theme_options->get_option_name() . '[' . $field_name . '][' . $index . '][image_id]" value="' . esc_attr($image_id) . '" class="carousel-image-id-input">';
        echo '<div style="display:flex; gap:8px; margin-top:6px;">';
        echo '<button type="button" class="button carousel-select-image" data-index="' . $index . '">选择图片</button>';
        if ($image_id) {
            echo '<button type="button" class="button carousel-remove-image" data-index="' . $index . '" style="margin-left: 6px;">移除图片</button>';
        }
        echo '</div>';
        echo '</div>'; // slide-left

        // 右侧：标题与链接（文章选择）
        $title = $slide['title'] ?? '';
        $link = $slide['link'] ?? '';
        echo '<div class="slide-right" style="flex:1; display:flex; flex-direction:column; gap:10px;">';
        
        // 文章选择区域
        echo '<div class="post-selection-area">';
        echo '<label><strong>关联文章</strong></label><br>';
        echo '<div style="display: flex; gap: 10px; align-items: flex-start; margin-top: 6px;">';
        echo '<button type="button" class="button post-select-btn" data-index="' . $index . '">选择文章</button>';
        if ($post_id) {
            echo '<button type="button" class="button post-clear-btn" data-index="' . $index . '" style="background: #dc3232; border-color: #dc3232; color: white;">清除文章</button>';
        }
        echo '</div>';
        
        // 隐藏的post_id字段
        echo '<input type="hidden" name="' . $this->theme_options->get_option_name() . '[' . $field_name . '][' . $index . '][post_id]" value="' . esc_attr($post_id) . '" class="post-id-input">';
        
        // 显示已选择的文章信息
        if ($post_id) {
            $post = get_post($post_id);
            if ($post) {
                echo '<div class="selected-post-info" style="margin-top: 8px; padding: 8px; background: #e8f4f8; border-radius: 3px; font-size: 13px;">';
                echo '<strong>已选择文章：</strong>' . esc_html($post->post_title);
                echo '<br><small>ID: ' . $post_id . ' | 状态: ' . esc_html($post->post_status) . '</small>';
                echo ' <a href="' . esc_url(get_permalink($post_id)) . '" target="_blank" style="margin-left: 10px;">预览</a>';
                echo '</div>';
            } else {
                echo '<div class="selected-post-info" style="margin-top: 8px; padding: 8px; background: #ffeaa7; border-radius: 3px; color: #d63031; font-size: 13px;">文章ID不存在: ' . $post_id . '</div>';
            }
        }
        echo '</div>';
        
        // 标题输入
        echo '<div><label for="slide-title-' . $index . '"><strong>标题</strong></label><br>';
        echo '<input type="text" id="slide-title-' . $index . '" name="' . $this->theme_options->get_option_name() . '[' . $field_name . '][' . $index . '][title]" value="' . esc_attr($title) . '" style="width: 100%;" placeholder="输入标题（可选，留空将使用文章标题）" class="slide-title-input"></div>';
        
        // 链接输入
        echo '<div><label for="slide-link-' . $index . '"><strong>链接</strong></label><br>';
        echo '<input type="url" id="slide-link-' . $index . '" name="' . $this->theme_options->get_option_name() . '[' . $field_name . '][' . $index . '][link]" value="' . esc_attr($link) . '" style="width: 100%;" placeholder="输入链接（可选，留空将使用文章链接）" class="slide-link-input"></div>';

        echo '</div>'; // slide-right
        echo '</div>'; // slide-grid
        echo '</div>'; // slide-config
    }

    /**
     * 添加媒体选择器相关资源
     */
    private function add_media_selector_assets(): void {
        static $carousel_media_added = false;
        if ($carousel_media_added) {
            return;
        }
        $carousel_media_added = true;

        // 加载WordPress媒体库
        wp_enqueue_media();

        // 获取变量
        $option_name = $this->theme_options->get_option_name();
        $admin_url = admin_url('post.php');
        
        // 输出JavaScript
        ?>
        <script type="text/javascript">
        (function($) {
            "use strict";
            var opt = "<?php echo esc_js($option_name); ?>";
            var carouselMediaUploader;
            var postSelectFrame;

            // 选择图片按钮点击事件
            $(document).on("click", ".carousel-select-image", function(e) {
                e.preventDefault();
                var button = $(this);
                var index = button.data("index");

                if (carouselMediaUploader) {
                    carouselMediaUploader.open();
                    return;
                }

                carouselMediaUploader = wp.media.frames.carousel_file_frame = wp.media({
                    title: "选择轮播图片",
                    button: { text: "选择图片" },
                    multiple: false
                });

                carouselMediaUploader.on("select", function() {
                    var attachment = carouselMediaUploader.state().get("selection").first().toJSON();
                    var container = button.closest(".slide-config");

                    // 更新隐藏字段
                    container.find(".carousel-image-id-input").val(attachment.id);

                    // 更新预览图片
                    var imageUrl = attachment.sizes && attachment.sizes.medium ?
                                   attachment.sizes.medium.url : attachment.url;
                    container.find(".carousel-image-preview").html(
                        "<img src='" + imageUrl + "' style='max-width: 100%; height: auto; display:block;'>"
                    );

                    // 添加移除按钮
                    if (!container.find(".carousel-remove-image").length) {
                        button.after("<button type='button' class='button carousel-remove-image' data-index='" + index + "' style='margin-left: 10px;'>移除图片</button>");
                    }
                });

                carouselMediaUploader.open();
            });

            // 移除图片按钮点击事件
            $(document).on("click", ".carousel-remove-image", function(e) {
                e.preventDefault();
                var container = $(this).closest(".slide-config");
                container.find(".carousel-image-id-input").val("");
                container.find(".carousel-image-preview").html("");
                $(this).remove();
            });

            // 选择文章按钮点击事件
            $(document).on("click", ".post-select-btn", function(e) {
                e.preventDefault();
                var button = $(this);
                var index = button.data("index");
                var container = button.closest(".slide-config");

                // 创建文章选择弹窗
                var postSelectModal = $('<div class="post-select-modal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 100000; display: flex; align-items: center; justify-content: center;">' +
                '<div class="post-select-content" style="background: white; width: 80%; max-width: 600px; max-height: 70%; border-radius: 6px; padding: 20px; overflow: hidden; display: flex; flex-direction: column;">' +
                '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #ddd;">' +
                '<h3 style="margin: 0;">选择文章</h3>' +
                '<button type="button" class="post-select-close" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #666;">×</button>' +
                '</div>' +
                '<div class="post-search-area" style="margin-bottom: 15px;">' +
                '<input type="text" class="post-search-input" placeholder="搜索文章标题..." style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">' +
                '</div>' +
                '<div class="post-list-container" style="flex: 1; overflow-y: auto; border: 1px solid #ddd; border-radius: 4px;">' +
                '<div class="post-list-loading" style="text-align: center; padding: 40px; color: #666;">正在加载文章...</div>' +
                '</div>' +
                '</div>' +
                '</div>');

                $('body').append(postSelectModal);

                // 加载文章列表
                loadPostList(postSelectModal);

                // 搜索功能
                var searchTimeout;
                postSelectModal.find('.post-search-input').on('input', function() {
                    var searchTerm = $(this).val();
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(function() {
                        loadPostList(postSelectModal, searchTerm);
                    }, 500);
                });

                // 关闭弹窗
                postSelectModal.find('.post-select-close').click(function() {
                    postSelectModal.remove();
                });
                postSelectModal.click(function(e) {
                    if (e.target === this) {
                        postSelectModal.remove();
                    }
                });

                // 选择文章
                postSelectModal.on('click', '.post-item', function() {
                    var postId = $(this).data('post-id');
                    var postTitle = $(this).find('.post-title').text();
                    var postUrl = $(this).data('post-url');

                    // 更新容器中的信息
                    container.find('.post-id-input').val(postId);
                    
                    // 自动填入标题和链接（如果当前为空）
                    var titleInput = container.find('.slide-title-input');
                    var linkInput = container.find('.slide-link-input');
                    
                    if (!titleInput.val()) {
                        titleInput.val(postTitle);
                    }
                    if (!linkInput.val()) {
                        linkInput.val(postUrl);
                    }

                    // 更新UI显示
                    updatePostSelectionUI(container, postId, postTitle, postUrl);
                    
                    postSelectModal.remove();
                });
            });

            // 清除文章按钮点击事件
            $(document).on("click", ".post-clear-btn", function(e) {
                e.preventDefault();
                var container = $(this).closest(".slide-config");
                container.find('.post-id-input').val('');
                updatePostSelectionUI(container, '', '', '');
            });

            // 删除轮播图按钮点击事件
            $(document).on("click", ".slide-delete-btn", function(e) {
                e.preventDefault();
                var container = $(this).closest(".slide-config");
                var totalSlides = $("#custom-slides-container .slide-config").length;
                
                if (totalSlides <= 1) {
                    alert("至少需要保留一个轮播图配置");
                    return;
                }
                
                if (confirm("确定要删除这个轮播图配置吗？")) {
                    container.remove();
                    // 重新索引剩余的slides
                    reindexSlides();
                }
            });

            // 添加轮播图
            $("#add-slide").click(function() {
                var container = $("#custom-slides-container");
                var slideCount = container.find(".slide-config").length;

                if (slideCount >= 10) {
                    alert("最多只能添加10个轮播图");
                    return;
                }

                var newSlideHTML = generateSlideHTML(slideCount);
                container.append(newSlideHTML);
            });

            // 生成新轮播图HTML
            function generateSlideHTML(index) {
                return '<div class="slide-config" data-index="' + index + '" style="position: relative; border: 1px solid #ddd; padding: 12px; margin-bottom: 12px; border-radius: 6px; background: #fafafa;">' +
                '<button type="button" class="slide-delete-btn" data-index="' + index + '" style="position: absolute; top: 8px; right: 8px; background: #dc3232; color: white; border: none; border-radius: 3px; width: 20px; height: 20px; cursor: pointer; font-size: 12px; line-height: 1;" title="删除此轮播图">×</button>' +
                '<div class="slide-grid" style="display:flex; gap:16px; align-items:stretch;">' +
                '<div class="slide-left" style="width:180px; flex:0 0 180px; display:flex; flex-direction:column;">' +
                '<label style="margin-bottom:6px;"><strong>图片</strong></label>' +
                '<div class="carousel-image-preview" style="margin:6px 0; min-height:100px; display:flex; align-items:center; justify-content:center; background:#fff; border:1px dashed #ddd; border-radius:4px; overflow:hidden;"></div>' +
                '<input type="hidden" name="' + opt + '[homepage_carousel_custom_slides][' + index + '][image_id]" value="" class="carousel-image-id-input">' +
                '<div style="display:flex; gap:8px; margin-top:6px;">' +
                '<button type="button" class="button carousel-select-image" data-index="' + index + '">选择图片</button>' +
                '</div>' +
                '</div>' +
                '<div class="slide-right" style="flex:1; display:flex; flex-direction:column; gap:10px;">' +
                '<div class="post-selection-area">' +
                '<label><strong>关联文章</strong></label><br>' +
                '<div style="display: flex; gap: 10px; align-items: flex-start; margin-top: 6px;">' +
                '<button type="button" class="button post-select-btn" data-index="' + index + '">选择文章</button>' +
                '</div>' +
                '<input type="hidden" name="' + opt + '[homepage_carousel_custom_slides][' + index + '][post_id]" value="" class="post-id-input">' +
                '</div>' +
                '<div><label><strong>标题</strong></label><br>' +
                '<input type="text" name="' + opt + '[homepage_carousel_custom_slides][' + index + '][title]" value="" style="width: 100%;" placeholder="输入标题（可选，留空将使用文章标题）" class="slide-title-input"></div>' +
                '<div><label><strong>链接</strong></label><br>' +
                '<input type="url" name="' + opt + '[homepage_carousel_custom_slides][' + index + '][link]" value="" style="width: 100%;" placeholder="输入链接（可选，留空将使用文章链接）" class="slide-link-input"></div>' +
                '</div>' +
                '</div>' +
                '</div>';
            }

            // 重新索引轮播图
            function reindexSlides() {
                $("#custom-slides-container .slide-config").each(function(newIndex) {
                    var $slide = $(this);
                    $slide.attr('data-index', newIndex);
                    
                    // 更新所有相关的name属性和id
                    $slide.find('input[name*="[image_id]"]').attr('name', opt + '[homepage_carousel_custom_slides][' + newIndex + '][image_id]');
                    $slide.find('input[name*="[post_id]"]').attr('name', opt + '[homepage_carousel_custom_slides][' + newIndex + '][post_id]');
                    $slide.find('input[name*="[title]"]').attr('name', opt + '[homepage_carousel_custom_slides][' + newIndex + '][title]');
                    $slide.find('input[name*="[link]"]').attr('name', opt + '[homepage_carousel_custom_slides][' + newIndex + '][link]');
                    
                    // 更新按钮的data-index
                    $slide.find('.slide-delete-btn, .carousel-select-image, .post-select-btn, .post-clear-btn').attr('data-index', newIndex);
                });
            }

            // 加载文章列表
            function loadPostList(modal, searchTerm) {
                var listContainer = modal.find('.post-list-container');
                listContainer.html('<div class="post-list-loading" style="text-align: center; padding: 40px; color: #666;">正在加载文章...</div>');

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'get_posts_for_carousel',
                        search: searchTerm || '',
                        nonce: '<?php echo wp_create_nonce('carousel_posts_nonce'); ?>'
                    },
                    success: function(response) {
                        if (response.success && response.data) {
                            var html = '';
                            $.each(response.data, function(i, post) {
                                html += '<div class="post-item" data-post-id="' + post.ID + '" data-post-url="' + post.permalink + '" style="padding: 12px; border-bottom: 1px solid #eee; cursor: pointer; transition: background-color 0.2s;">' +
                                '<div class="post-title" style="font-weight: 600; margin-bottom: 4px;">' + post.post_title + '</div>' +
                                '<div style="font-size: 12px; color: #666;">ID: ' + post.ID + ' | 状态: ' + post.post_status + ' | 日期: ' + post.post_date + '</div>' +
                                '</div>';
                            });
                            
                            if (html) {
                                listContainer.html(html);
                                
                                // 添加hover效果
                                listContainer.find('.post-item').hover(
                                    function() { $(this).css('background-color', '#f0f0f0'); },
                                    function() { $(this).css('background-color', 'white'); }
                                );
                            } else {
                                listContainer.html('<div style="text-align: center; padding: 40px; color: #666;">没有找到文章</div>');
                            }
                        } else {
                            listContainer.html('<div style="text-align: center; padding: 40px; color: #dc3232;">加载失败，请重试</div>');
                        }
                    },
                    error: function() {
                        listContainer.html('<div style="text-align: center; padding: 40px; color: #dc3232;">加载失败，请重试</div>');
                    }
                });
            }

            // 更新文章选择UI
            function updatePostSelectionUI(container, postId, postTitle, postUrl) {
                var selectionArea = container.find('.post-selection-area');
                var buttonArea = selectionArea.find('div').first();
                
                if (postId) {
                    // 显示清除按钮
                    if (!buttonArea.find('.post-clear-btn').length) {
                        buttonArea.append('<button type="button" class="button post-clear-btn" data-index="' + container.data('index') + '" style="background: #dc3232; border-color: #dc3232; color: white;">清除文章</button>');
                    }
                    
                    // 显示已选择的文章信息
                    selectionArea.find('.selected-post-info').remove();
                    buttonArea.after('<div class="selected-post-info" style="margin-top: 8px; padding: 8px; background: #e8f4f8; border-radius: 3px; font-size: 13px;">' +
                        '<strong>已选择文章：</strong>' + postTitle +
                        '<br><small>ID: ' + postId + '</small>' +
                        ' <a href="' + postUrl + '" target="_blank" style="margin-left: 10px;">预览</a>' +
                        '</div>');
                } else {
                    // 移除清除按钮和文章信息
                    buttonArea.find('.post-clear-btn').remove();
                    selectionArea.find('.selected-post-info').remove();
                }
            }

        })(jQuery);
        </script>
        <?php
    }
}