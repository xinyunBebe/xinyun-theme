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
        add_action('admin_menu', [$this, 'add_theme_page']);
        add_action('admin_init', [$this, 'init_settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
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
        
        // 基础设置 Tab
        $this->init_basic_settings();
        
        // 首页设置 Tab  
        $this->init_homepage_settings();
        
        // 文章设置 Tab
        $this->init_post_settings();
        
        // 主题说明 Tab (无需设置字段)
    }
    
    /**
     * 初始化基础设置
     */
    private function init_basic_settings(): void {
        // 基础设置section
        add_settings_section(
            'basic_section',
            '基础设置',
            [$this, 'basic_section_callback'],
            $this->page_slug . '_basic'
        );
        
        // 主色调
        add_settings_field(
            'primary_color',
            '主色调',
            [$this, 'color_field_callback'],
            $this->page_slug . '_basic',
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
            $this->page_slug . '_basic',
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
            $this->page_slug . '_basic',
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
     * 初始化首页设置
     */
    private function init_homepage_settings(): void {
        // 轮播图设置section
        add_settings_section(
            'carousel_section',
            '首页轮播图设置',
            [$this, 'carousel_section_callback'],
            $this->page_slug . '_homepage'
        );
        
        // 获取轮播图管理器
        $carousel_manager = Xinyun_Carousel_Manager::get_instance();
        $carousel_choices = $carousel_manager->get_carousel_choices();
        
        // 轮播图类型选择
        add_settings_field(
            'homepage_carousel_type',
            '轮播图类型',
            [$this, 'select_field_callback'],
            $this->page_slug . '_homepage',
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
            $this->page_slug . '_homepage',
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
            $this->page_slug . '_homepage',
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
            $this->page_slug . '_homepage',
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
            $this->page_slug . '_homepage',
            'carousel_section',
            [
                'field_name' => 'homepage_carousel_posts_count',
                'default' => 5,
                'min' => 3,
                'max' => 10,
                'step' => 1,
                'unit' => '篇',
                'description' => '轮播图中显示的文章数量（仅对文章轮播图生效）'
            ]
        );
        
        // 显示箭头
        add_settings_field(
            'homepage_carousel_arrows',
            '显示导航箭头',
            [$this, 'checkbox_field_callback'],
            $this->page_slug . '_homepage',
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
            $this->page_slug . '_homepage',
            'carousel_section',
            [
                'field_name' => 'homepage_carousel_pagination',
                'default' => true,
                'description' => '显示轮播图底部的分页指示器'
            ]
        );
    }
    
    /**
     * 初始化文章设置
     */
    private function init_post_settings(): void {
        // 文章设置section
        add_settings_section(
            'post_section',
            '文章显示设置',
            [$this, 'post_section_callback'],
            $this->page_slug . '_post'
        );
        
        // 摘要长度
        add_settings_field(
            'excerpt_length',
            '文章摘要长度',
            [$this, 'number_field_callback'],
            $this->page_slug . '_post',
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
            $this->page_slug . '_post',
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
            $this->page_slug . '_post',
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
            $this->page_slug . '_post',
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
            $this->page_slug . '_post',
            'post_section',
            [
                'field_name' => 'show_categories',
                'default' => true,
                'description' => '在文章中显示分类信息'
            ]
        );
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

            <!-- 测试设置保存功能 -->
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
    
    /**
     * 渲染主题说明页面
     */
    private function render_about_tab(): void {
        ?>
        <div class="xinyun-about-content">
            <div class="theme-header">
                <div class="theme-icon">
                    <span class="dashicons dashicons-admin-appearance"></span>
                </div>
                <div class="theme-details">
                    <h2>心耘 WordPress 主题</h2>
                    <p class="version">版本 <?php echo XINYUN_VERSION; ?></p>
                    <p class="description">一个现代化、响应式的 WordPress 主题，具有强大的自定义功能和优雅的设计。</p>
                </div>
            </div>
            
            <div class="xinyun-features-grid">
                <div class="feature-card">
                    <h3><span class="dashicons dashicons-smartphone"></span> 响应式设计</h3>
                    <p>完美适配各种设备，从桌面电脑到手机平板，都能提供优秀的用户体验。</p>
                </div>
                
                <div class="feature-card">
                    <h3><span class="dashicons dashicons-images-alt2"></span> 轮播图功能</h3>
                    <p>支持多种轮播图类型，包括文章轮播、图片轮播等，完全可定制的设置选项。</p>
                </div>
                
                <div class="feature-card">
                    <h3><span class="dashicons dashicons-admin-customizer"></span> 易于定制</h3>
                    <p>通过直观的设置页面，您可以轻松配置主题的各种参数，无需编程知识。</p>
                </div>
                
                <div class="feature-card">
                    <h3><span class="dashicons dashicons-performance"></span> 性能优化</h3>
                    <p>使用现代 Web 技术，优化后的代码确保快速加载和流畅的用户体验。</p>
                </div>
                
                <div class="feature-card">
                    <h3><span class="dashicons dashicons-universal-access-alt"></span> 无障碍访问</h3>
                    <p>遵循 Web 无障碍标准，支持屏幕阅读器和键盘导航，让所有用户都能访问。</p>
                </div>
                
                <div class="feature-card">
                    <h3><span class="dashicons dashicons-admin-tools"></span> 模块化架构</h3>
                    <p>采用模块化设计，易于扩展和维护，支持添加新的功能组件。</p>
                </div>
            </div>
            
            <div class="xinyun-help-section">
                <h3>需要帮助？</h3>
                <div class="help-buttons">
                    <a href="<?php echo admin_url('themes.php'); ?>" class="button button-secondary">
                        <span class="dashicons dashicons-admin-appearance"></span> 返回主题页面
                    </a>
                    <a href="<?php echo home_url(); ?>" class="button button-secondary" target="_blank">
                        <span class="dashicons dashicons-external"></span> 查看网站
                    </a>
                    <a href="<?php echo admin_url('customize.php'); ?>" class="button button-primary">
                        <span class="dashicons dashicons-admin-customizer"></span> WordPress 自定义器
                    </a>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * 基础设置section回调
     */
    public function basic_section_callback(): void {
        echo '<p>配置主题的基础显示设置，包括颜色、布局等。</p>';
    }
    
    /**
     * 轮播图设置section回调
     */
    public function carousel_section_callback(): void {
        echo '<p>设置首页轮播图的显示类型和各项参数。轮播图会显示在首页标题栏下方。</p>';
    }
    
    /**
     * 文章设置section回调
     */
    public function post_section_callback(): void {
        echo '<p>配置文章和博客相关的显示选项。</p>';
    }
    
    /**
     * 颜色字段回调
     */
    public function color_field_callback(array $args): void {
        $options = $this->get_options();
        $value = $options[$args['field_name']] ?? $args['default'];
        
        printf(
            '<input type="color" name="%s[%s]" id="%s" value="%s" class="color-picker" />',
            $this->option_name,
            $args['field_name'],
            $args['field_name'],
            esc_attr($value)
        );
        
        if (!empty($args['description'])) {
            printf('<p class="description">%s</p>', esc_html($args['description']));
        }
    }
    
    /**
     * 下拉选择字段回调
     */
    public function select_field_callback(array $args): void {
        $options = $this->get_options();
        $value = $options[$args['field_name']] ?? $args['default'];
        
        echo '<select name="' . $this->option_name . '[' . $args['field_name'] . ']" id="' . $args['field_name'] . '">';
        
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
     * 数字输入字段回调
     */
    public function number_field_callback(array $args): void {
        $options = $this->get_options();
        $value = $options[$args['field_name']] ?? $args['default'];
        
        printf(
            '<input type="number" name="%s[%s]" id="%s" value="%s" min="%d" max="%d" step="%s" class="small-text" /> %s',
            $this->option_name,
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
        $options = $this->get_options();
        $value = $options[$args['field_name']] ?? $args['default'];
        
        printf(
            '<label><input type="checkbox" name="%s[%s]" id="%s" value="1" %s /> %s</label>',
            $this->option_name,
            $args['field_name'],
            $args['field_name'],
            checked(1, $value, false),
            !empty($args['description']) ? esc_html($args['description']) : '启用此选项'
        );
    }
    
    /**
     * 获取主题选项
     *
     * @return array
     */
    public function get_options(): array {
        $options = get_option($this->option_name, []);
        error_log('Xinyun Theme Options - Retrieved: ' . print_r($options, true));
        return $options;
    }
    
    /**
     * 获取单个选项值
     * 
     * @param string $key 选项键名
     * @param mixed $default 默认值
     * @return mixed
     */
    public function get_option(string $key, $default = null) {
        $options = $this->get_options();
        return $options[$key] ?? $default;
    }
    
    /**
     * 设置选项值
     * 
     * @param string $key 选项键名
     * @param mixed $value 值
     */
    public function set_option(string $key, $value): void {
        $options = $this->get_options();
        $options[$key] = $value;
        update_option($this->option_name, $options);
    }
    
    /**
     * 净化选项数据
     * 
     * @param array $input 输入数据
     * @return array
     */
    public function sanitize_options(array $input): array {
        // 调试日志
        error_log('Xinyun Theme Options - Input: ' . print_r($input, true));

        $sanitized = [];
        
        // 颜色字段
        $color_fields = ['primary_color', 'secondary_color'];
        foreach ($color_fields as $field) {
            if (isset($input[$field])) {
                $sanitized[$field] = sanitize_hex_color($input[$field]);
            }
        }
        
        // 轮播图类型
        if (isset($input['homepage_carousel_type'])) {
            $carousel_manager = Xinyun_Carousel_Manager::get_instance();
            $valid_types = array_keys($carousel_manager->get_carousel_choices());
            $sanitized['homepage_carousel_type'] = in_array($input['homepage_carousel_type'], $valid_types) 
                ? $input['homepage_carousel_type'] 
                : 'post';
        }
        
        // 数字字段
        $number_fields = [
            'container_width' => [800, 1600],
            'homepage_carousel_height' => [200, 800],
            'homepage_carousel_interval' => [2000, 10000],
            'homepage_carousel_posts_count' => [3, 10],
            'excerpt_length' => [10, 100]
        ];
        
        foreach ($number_fields as $field => $range) {
            if (isset($input[$field])) {
                $value = intval($input[$field]);
                $sanitized[$field] = max($range[0], min($range[1], $value));
            }
        }
        
        // 布尔字段
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
            $sanitized[$field] = !empty($input[$field]);
        }

        // 调试日志
        error_log('Xinyun Theme Options - Sanitized: ' . print_r($sanitized, true));

        return $sanitized;
    }

    /**
     * 测试设置保存功能
     */
    public function test_settings_save(): void {
        if (!current_user_can('manage_options')) {
            return;
        }

        // 测试数据
        $test_data = [
            'primary_color' => '#ff0000',
            'container_width' => 1400,
            'excerpt_length' => 50
        ];

        // 保存测试数据
        update_option($this->option_name, $test_data);

        // 立即读取验证
        $saved_data = get_option($this->option_name, []);

        echo '<div class="notice notice-info is-dismissible">';
        echo '<p><strong>设置保存测试：</strong></p>';
        echo '<p>测试数据: ' . esc_html(json_encode($test_data)) . '</p>';
        echo '<p>保存的数据: ' . esc_html(json_encode($saved_data)) . '</p>';
        if ($saved_data === $test_data) {
            echo '<p style="color: green;"><strong>✅ 设置保存正常！</strong></p>';
        } else {
            echo '<p style="color: red;"><strong>❌ 设置保存异常！</strong></p>';
        }
        echo '</div>';
    }
    
    /**
     * 加载后台资源
     */
    public function enqueue_admin_assets(string $hook): void {
        if ($hook !== 'appearance_page_' . $this->page_slug) {
            return;
        }

        // 添加后台样式
        wp_add_inline_style('wp-admin', $this->get_admin_css());

        // 添加后台脚本 - 使用正确的句柄
        wp_add_inline_script('jquery', $this->get_admin_js());
    }
    
    /**
     * 获取后台样式
     * 
     * @return string
     */
    private function get_admin_css(): string {
        return '
            /* Tab导航样式 */
            .xinyun-tab-nav {
                border-bottom: 1px solid #ccc;
                margin: 20px 0;
                padding: 0;
            }
            .xinyun-tab-nav ul {
                display: flex;
                margin: 0;
                padding: 0;
                list-style: none;
            }
            .xinyun-tab-nav li {
                margin: 0;
            }
            .xinyun-tab-nav a {
                display: block;
                padding: 12px 20px;
                text-decoration: none;
                color: #555;
                border: 1px solid transparent;
                border-bottom: none;
                margin-right: 5px;
                border-radius: 4px 4px 0 0;
                transition: all 0.3s ease;
                position: relative;
                top: 1px;
            }
            .xinyun-tab-nav a:hover {
                color: #2271b1;
                background: #f6f7f7;
            }
            .xinyun-tab-nav a.nav-tab-active {
                background: #fff;
                border-color: #ccc;
                color: #000;
                font-weight: 600;
            }
            
            /* Tab内容区域 */
            .xinyun-tab-content {
                display: none;
                background: #fff;
                border: 1px solid #ccc;
                border-top: none;
                padding: 20px;
                border-radius: 0 0 4px 4px;
            }
            .xinyun-tab-content.active {
                display: block;
            }
            
            /* 主题说明页面样式 */
            .theme-header {
                display: flex;
                align-items: center;
                gap: 20px;
                margin-bottom: 30px;
                padding: 20px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 8px;
                color: white;
            }
            .theme-icon {
                font-size: 48px;
                opacity: 0.9;
            }
            .theme-details h2 {
                color: white;
                margin: 0 0 8px 0;
                font-size: 28px;
            }
            .theme-details .version {
                color: rgba(255,255,255,0.8);
                margin: 0 0 10px 0;
                font-size: 14px;
            }
            .theme-details .description {
                color: rgba(255,255,255,0.9);
                margin: 0;
                line-height: 1.5;
            }
            
            /* 功能特性网格 */
            .xinyun-features-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 20px;
                margin: 30px 0;
            }
            .feature-card {
                background: #fff;
                border: 1px solid #e1e1e1;
                border-radius: 8px;
                padding: 20px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
                transition: all 0.3s ease;
            }
            .feature-card:hover {
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                transform: translateY(-2px);
            }
            .feature-card h3 {
                color: #2c3e50;
                margin: 0 0 12px 0;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .feature-card h3 .dashicons {
                color: #667eea;
            }
            .feature-card p {
                color: #666;
                line-height: 1.6;
                margin: 0;
            }
            
            /* 帮助区域 */
            .xinyun-help-section {
                background: #f8f9fa;
                border-radius: 8px;
                padding: 25px;
                text-align: center;
                margin-top: 30px;
            }
            .xinyun-help-section h3 {
                color: #2c3e50;
                margin: 0 0 20px 0;
            }
            .help-buttons {
                display: flex;
                justify-content: center;
                gap: 15px;
                flex-wrap: wrap;
            }
            .help-buttons .button {
                display: flex;
                align-items: center;
                gap: 6px;
            }
            
            /* 表单样式优化 */
            .xinyun-options-form .form-table th {
                width: 200px;
                padding: 15px 10px 15px 0;
            }
            .xinyun-options-form .form-table td {
                padding: 15px 10px;
            }
            .color-picker {
                width: 100px;
                height: 40px;
                border: 1px solid #ddd;
                border-radius: 4px;
                cursor: pointer;
            }
            .unit {
                color: #666;
                font-style: italic;
                margin-left: 8px;
                font-size: 13px;
            }
            .description {
                color: #666;
                font-size: 13px;
                line-height: 1.4;
                margin-top: 5px !important;
            }
            
            /* 保存按钮区域 */
            #xinyun-save-settings {
                margin-top: 20px;
                padding: 20px;
                background: #f8f9fa;
                border-radius: 4px;
                text-align: center;
            }
            
            /* 标题图标 */
            .wrap h1 .dashicons {
                margin-right: 10px;
                vertical-align: middle;
            }
        ';
    }
    
    /**
     * 获取后台JavaScript
     *
     * @return string
     */
    private function get_admin_js(): string {
        return '
            (function($) {
                "use strict";

                $(document).ready(function() {
                    console.log("Xinyun Theme Options: 初始化Tab切换功能");

                    // Tab切换功能
                    const $tabLinks = $(".xinyun-tab-nav a");
                    const $tabContents = $(".xinyun-tab-content");

                    console.log("找到", $tabLinks.length, "个tab链接");
                    console.log("找到", $tabContents.length, "个tab内容");

                    if ($tabLinks.length === 0 || $tabContents.length === 0) {
                        console.error("Tab切换功能初始化失败：未找到必要的DOM元素");
                        return;
                    }

                    function switchTab(targetTabId) {
                        console.log("切换到tab:", targetTabId);

                        // 移除所有active状态
                        $tabLinks.removeClass("nav-tab-active");
                        $tabContents.removeClass("active");

                        // 添加active状态到目标tab
                        const $targetLink = $(".xinyun-tab-nav a[href=\"#" + targetTabId + "\"]");
                        const $targetContent = $("#" + targetTabId);

                        if ($targetLink.length > 0) {
                            $targetLink.addClass("nav-tab-active");
                            console.log("激活链接:", targetTabId);
                        } else {
                            console.warn("未找到目标链接:", targetTabId);
                        }

                        if ($targetContent.length > 0) {
                            $targetContent.addClass("active");
                            console.log("显示内容:", targetTabId);
                        } else {
                            console.warn("未找到目标内容:", targetTabId);
                        }
                    }

                    // 绑定点击事件
                    $tabLinks.on("click", function(e) {
                        e.preventDefault();

                        // 获取目标tab
                        const href = $(this).attr("href");
                        if (!href || href.charAt(0) !== "#") {
                            console.error("无效的tab链接:", href);
                            return;
                        }

                        const targetTab = href.substring(1);
                        console.log("点击了tab:", targetTab);

                        switchTab(targetTab);

                        // 更新URL hash但不跳转
                        if (history.replaceState) {
                            history.replaceState(null, null, "#" + targetTab);
                        }
                    });

                    // 根据URL hash激活对应tab
                    const hash = window.location.hash.substring(1);
                    if (hash && hash.length > 0) {
                        console.log("根据URL hash激活tab:", hash);
                        switchTab(hash);
                    } else {
                        // 确保至少有一个tab是激活的
                        const $activeTab = $(".xinyun-tab-nav a.nav-tab-active");
                        if ($activeTab.length === 0 && $tabLinks.length > 0) {
                            console.log("激活默认tab: basic-settings");
                            switchTab("basic-settings");
                        }
                    }

                    console.log("Xinyun Theme Options: Tab切换功能初始化完成");
                });

                // 防止jQuery冲突
            })(jQuery);
        ';
    }
}