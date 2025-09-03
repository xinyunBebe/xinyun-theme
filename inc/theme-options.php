<?php
/**
 * Xinyun Theme - 主题设置页面
 *
 * 独立的主题设置管理页面
 *
 * @package Xinyun
 * @since 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

// 引入拆分后的文件
require_once __DIR__ . '/theme-options/basic-settings.php';
require_once __DIR__ . '/theme-options/homepage-settings.php';
require_once __DIR__ . '/theme-options/post-settings.php';
require_once __DIR__ . '/theme-options/about-settings.php';
require_once __DIR__ . '/theme-options/admin-assets.php';

/**
 * 主题设置页面类
 */
class Xinyun_Theme_Options {
    
    /**
     * 单例实例
     * 
     * @var Xinyun_Theme_Options|null
     */
    private static ?Xinyun_Theme_Options $instance = null;
    
    /**
     * 选项名称
     * 
     * @var string
     */
    private string $option_name = 'xinyun_theme_options';
    
    /**
     * 页面slug
     * 
     * @var string
     */
    private string $page_slug = 'xinyun-theme-options';

    /**
     * 设置子类实例
     */
    private Xinyun_Basic_Settings $basic_settings;
    private Xinyun_Homepage_Settings $homepage_settings;
    private Xinyun_Post_Settings $post_settings;
    private Xinyun_About_Settings $about_settings;
    private Xinyun_Admin_Assets $admin_assets;
    
    /**
     * 获取单例实例
     * 
     * @return Xinyun_Theme_Options
     */
    public static function get_instance(): Xinyun_Theme_Options {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    /**
     * 私有构造函数
     */
    private function __construct() {
        // 初始化设置子类
        $this->basic_settings = new Xinyun_Basic_Settings($this);
        $this->homepage_settings = new Xinyun_Homepage_Settings($this);
        $this->post_settings = new Xinyun_Post_Settings($this);
        $this->about_settings = new Xinyun_About_Settings($this);
        $this->admin_assets = new Xinyun_Admin_Assets($this);

        add_action('admin_menu', [$this, 'add_theme_page']);
        add_action('admin_init', [$this, 'init_settings']);
    }
    
    /**
     * 添加主题设置页面到后台菜单
     */
    public function add_theme_page(): void {
        add_theme_page(
            '心耘主题设置',           // 页面标题
            '心耘主题设置',               // 菜单标题
            'manage_options',            // 权限要求
            $this->page_slug,           // 页面slug
            [$this, 'render_options_page'] // 渲染函数
        );
    }
    
    /**
     * 初始化设置字段
     */
    public function init_settings(): void {
        // 注册设置
        register_setting(
            $this->option_name . '_group',
            $this->option_name,
            [$this, 'sanitize_options']
        );
        
        // 设置子类会自动初始化自己的设置字段
    }

    /**
     * 获取页面slug
     *
     * @return string
     */
    public function get_page_slug(): string {
        return $this->page_slug;
    }

    /**
     * 获取选项名称
     *
     * @return string
     */
    public function get_option_name(): string {
        return $this->option_name;
    }

    /**
     * 获取选项
     *
     * @return array
     */
    public function get_options(): array {
        return get_option($this->option_name, []);
    }

    /**
     * 设置选项
     *
     * @param string $key 选项键
     * @param mixed $value 选项值
     */
    public function set_option(string $key, $value): void {
        $options = $this->get_options();
        $options[$key] = $value;
        update_option($this->option_name, $options);
    }

    /**
     * 获取单个选项
     *
     * @param string $key 选项键
     * @param mixed $default 默认值
     * @return mixed
     */
    public function get_option(string $key, $default = null) {
        $options = $this->get_options();
        return $options[$key] ?? $default;
    }

    /**
     * 渲染关于页面
     *
     * @return void
     */
    public function render_about_tab(): void {
        $this->about_settings->render_about_tab();
    }

    /**
     * 数据清理和验证
     */
    public function sanitize_options(array $input): array {
        $sanitized_input = [];

        // 颜色字段验证
        $color_fields = ['primary_color', 'secondary_color'];
        foreach ($color_fields as $field) {
            if (isset($input[$field])) {
                $sanitized_input[$field] = sanitize_hex_color($input[$field]) ?: '#007cba';
            }
        }

        // 数字字段验证
        $number_fields = [
            'container_width' => ['min' => 800, 'max' => 1600],
            'homepage_carousel_height' => ['min' => 200, 'max' => 800],
            'homepage_carousel_interval' => ['min' => 2000, 'max' => 10000],
            'homepage_carousel_posts_count' => ['min' => 3, 'max' => 10],
            'excerpt_length' => ['min' => 10, 'max' => 100]
        ];

        // 新增：处理默认特色图片ID
        if (isset($input['default_featured_image'])) {
            $sanitized_input['default_featured_image'] = absint($input['default_featured_image']);
        }

        foreach ($number_fields as $field => $range) {
            if (isset($input[$field])) {
                $value = intval($input[$field]);
                $sanitized_input[$field] = max($range['min'], min($range['max'], $value));
            }
        }

        // 布尔字段验证
        $boolean_fields = [
            'homepage_carousel_autoplay',
            'homepage_carousel_arrows',
            'homepage_carousel_pagination',
            'show_featured_image',
            'show_author_info',
            'show_post_date',
            'show_categories'
        ];

        foreach ($boolean_fields as $field) {
            $sanitized_input[$field] = isset($input[$field]) ? (bool)$input[$field] : false;
        }

        // 下拉选择字段验证
        if (isset($input['homepage_carousel_type'])) {
            $carousel_manager = Xinyun_Carousel_Manager::get_instance();
            $valid_types = array_keys($carousel_manager->get_carousel_choices());
            $sanitized_input['homepage_carousel_type'] = in_array($input['homepage_carousel_type'], $valid_types)
                ? $input['homepage_carousel_type']
                : 'post';
        }

        // 自定义轮播图配置验证
        if (isset($input['homepage_carousel_custom_slides']) && is_array($input['homepage_carousel_custom_slides'])) {
            $sanitized_slides = [];
            $max_slides = min(isset($input['homepage_carousel_posts_count']) ? intval($input['homepage_carousel_posts_count']) : 5, 10);

            for ($i = 0; $i < $max_slides; $i++) {
                if (isset($input['homepage_carousel_custom_slides'][$i])) {
                    $slide = $input['homepage_carousel_custom_slides'][$i];

                    $sanitized_slide = [
                        'image_id' => isset($slide['image_id']) ? intval($slide['image_id']) : '',
                        'post_id' => isset($slide['post_id']) ? intval($slide['post_id']) : ''
                    ];

                    // 验证图片ID
                    if ($sanitized_slide['image_id'] > 0) {
                        $image_url = wp_get_attachment_image_url($sanitized_slide['image_id'], 'full');
                        if (!$image_url) {
                            $sanitized_slide['image_id'] = '';
                        }
                    }

                    // 验证文章ID
                    if ($sanitized_slide['post_id'] > 0) {
                        $post = get_post($sanitized_slide['post_id']);
                        if (!$post || $post->post_status !== 'publish') {
                            $sanitized_slide['post_id'] = '';
                        }
                    }

                    $sanitized_slides[$i] = $sanitized_slide;
                }
            }

            $sanitized_input['homepage_carousel_custom_slides'] = $sanitized_slides;
        }

        return $sanitized_input;
    }

    /**
     * 测试设置保存功能
     */
    public function test_settings_save(): void {
        if (!current_user_can('manage_options')) {
            wp_die('权限不足');
        }

        $test_data = [
            'test_field' => 'test_value_' . time(),
            'test_number' => rand(1, 100),
            'test_boolean' => (bool)rand(0, 1)
        ];

        // 保存测试数据
        update_option('xinyun_test_settings', $test_data);

        // 读取测试数据
        $saved_data = get_option('xinyun_test_settings');

        if ($saved_data === $test_data) {
            echo '<div class="notice notice-success"><p>✅ 设置保存功能正常！测试数据已成功保存和读取。</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>❌ 设置保存功能异常！请检查数据库连接和文件权限。</p></div>';
        }

        // 清理测试数据
        delete_option('xinyun_test_settings');
    }
    
    /**
     * 渲染选项页面
     */
    public function render_options_page(): void {
        ?>
        <div class="wrap xinyun-theme-options">
            <h1>
                <span class="dashicons dashicons-admin-appearance"></span> 
                心耘主题设置
            </h1>
            <p class="description">配置心耘主题的各项功能和显示选项。</p>
            
            <?php settings_errors(); ?>

            <?php if (isset($_GET['test_settings'])) $this->test_settings_save(); ?>

            <!-- Tab 导航 -->
            <nav class="xinyun-tab-nav">
                <ul>
                    <li>
                        <a href="#basic-settings" class="nav-tab-active">
                            <span class="dashicons dashicons-admin-settings"></span> 基础设置
                        </a>
                    </li>
                    <li>
                        <a href="#homepage-settings">
                            <span class="dashicons dashicons-admin-home"></span> 首页设置
                        </a>
                    </li>
                    <li>
                        <a href="#post-settings">
                            <span class="dashicons dashicons-admin-post"></span> 文章设置
                        </a>
                    </li>
                    <li>
                        <a href="#about-info">
                            <span class="dashicons dashicons-info"></span> 主题说明
                        </a>
                    </li>
                </ul>
            </nav>
            
            <!-- Tab 内容 -->
            <div id="basic-settings" class="xinyun-tab-content active">
                <form method="post" action="options.php" class="xinyun-options-form">
                    <?php
                    settings_fields($this->option_name . '_group');
                    do_settings_sections($this->page_slug . '_basic');
                    submit_button('保存基础设置', 'primary', 'submit', false);
                    ?>
                </form>
            </div>

            <div id="homepage-settings" class="xinyun-tab-content">
                <form method="post" action="options.php" class="xinyun-options-form">
                    <?php
                    settings_fields($this->option_name . '_group');
                    do_settings_sections($this->page_slug . '_homepage');
                    submit_button('保存首页设置', 'primary', 'submit', false);
                    ?>
                </form>
            </div>

            <div id="post-settings" class="xinyun-tab-content">
                <form method="post" action="options.php" class="xinyun-options-form">
                    <?php
                    settings_fields($this->option_name . '_group');
                    do_settings_sections($this->page_slug . '_post');
                    submit_button('保存文章设置', 'primary', 'submit', false);
                    ?>
                </form>
            </div>

            <div id="about-info" class="xinyun-tab-content">
                <?php $this->render_about_tab(); ?>
            </div>

            <!-- 测试区域 -->
            <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px; border: 1px solid #ddd;">
                <h3>🔧 调试工具</h3>
                <p>使用以下工具测试设置保存功能：</p>
                <div style="display: flex; gap: 10px; margin-top: 10px;">
                    <a href="<?php echo add_query_arg('test_settings', '1'); ?>" class="button button-secondary">
                        🧪 测试设置保存
                    </a>
                    <a href="<?php echo remove_query_arg('test_settings'); ?>" class="button button-secondary">
                        🔄 清除测试
                    </a>
                </div>
                <p style="margin-top: 10px; font-size: 12px; color: #666;">
                    测试完成后请查看页面顶部的结果提示，以及服务器错误日志。
                </p>
            </div>
        </div>
        <?php
    }
}

// 初始化主题设置页面
if (is_admin()) {
    Xinyun_Theme_Options::get_instance();
}