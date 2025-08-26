<?php
/**
 * Xinyun Theme - 轮播图管理器
 *
 * 管理所有轮播图类型的注册、配置和渲染
 *
 * @package Xinyun
 * @since 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 轮播图管理器类
 */
class Xinyun_Carousel_Manager {
    
    /**
     * 单例实例
     * 
     * @var Xinyun_Carousel_Manager|null
     */
    private static ?Xinyun_Carousel_Manager $instance = null;
    
    /**
     * 已注册的轮播图类型
     * 
     * @var array<string, Xinyun_Carousel_Base>
     */
    private array $carousels = [];
    
    /**
     * 获取单例实例
     * 
     * @return Xinyun_Carousel_Manager
     */
    public static function get_instance(): Xinyun_Carousel_Manager {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    /**
     * 私有构造函数，防止外部实例化
     */
    private function __construct() {
        $this->init();
    }
    
    /**
     * 初始化管理器
     */
    private function init(): void {
        // 加载轮播图基础类
        $this->load_carousel_classes();
        
        // 注册默认轮播图类型
        $this->register_default_carousels();
        
        // 添加钩子
        add_action('wp_enqueue_scripts', [$this, 'maybe_enqueue_assets']);
    }
    
    /**
     * 加载轮播图相关类文件
     */
    private function load_carousel_classes(): void {
        $carousel_dir = get_template_directory() . '/inc/carousels/';
        
        // 加载基础类
        require_once $carousel_dir . 'carousel-base.php';
        
        // 加载具体轮播图类型
        $carousel_files = [
            'post-carousel.php',
            'custom-carousel.php',
            // 未来可以添加更多轮播图类型文件
            // 'product-carousel.php',
            // 'gallery-carousel.php',
        ];
        
        foreach ($carousel_files as $file) {
            $file_path = $carousel_dir . $file;
            if (file_exists($file_path)) {
                require_once $file_path;
            }
        }
    }
    
    /**
     * 注册默认轮播图类型
     */
    private function register_default_carousels(): void {
        // 注册文章轮播图 - 使用自定义轮播图配置，但可以自动补充文章
        $this->register('post', new Xinyun_Post_Carousel());
        
        // 注册自定义轮播图 - 严格按照用户配置显示
        $this->register('custom', new Xinyun_Custom_Carousel());
        
        // 未来可以注册更多类型
        // $this->register('product', new Xinyun_Product_Carousel());
        // $this->register('gallery', new Xinyun_Gallery_Carousel());
    }
    
    /**
     * 注册轮播图类型
     * 
     * @param string $type 轮播图类型标识
     * @param Xinyun_Carousel_Base $carousel 轮播图实例
     */
    public function register(string $type, Xinyun_Carousel_Base $carousel): void {
        $this->carousels[$type] = $carousel;
    }
    
    /**
     * 获取已注册的轮播图类型
     * 
     * @return array<string, Xinyun_Carousel_Base>
     */
    public function get_carousels(): array {
        return $this->carousels;
    }
    
    /**
     * 获取特定轮播图类型
     * 
     * @param string $type 轮播图类型标识
     * @return Xinyun_Carousel_Base|null
     */
    public function get_carousel(string $type): ?Xinyun_Carousel_Base {
        return $this->carousels[$type] ?? null;
    }
    
    /**
     * 获取轮播图选择列表（用于设置选项）
     * 
     * @return array<string, string>
     */
    public function get_carousel_choices(): array {
        $choices = ['none' => '不显示轮播图'];
        
        foreach ($this->carousels as $type => $carousel) {
            $choices[$type] = $carousel->get_name();
        }
        
        return $choices;
    }
    
    /**
     * 渲染首页轮播图
     * 
     * @param array $options 配置选项
     * @return string
     */
    public function render_homepage_carousel(array $options = []): string {
        // 获取主题设置实例
        $theme_options = Xinyun_Theme_Options::get_instance();
        
        // 获取主题设置中选择的轮播图类型
        $carousel_type = $theme_options->get_option('homepage_carousel_type', 'post');
        
        // 调试信息（仅管理员可见）
        if (current_user_can('manage_options') && WP_DEBUG) {
            error_log('Xinyun Carousel Debug: Type = ' . $carousel_type);
            error_log('Xinyun Carousel Debug: Available types = ' . implode(', ', array_keys($this->carousels)));
        }
        
        // 如果设置为不显示
        if ($carousel_type === 'none') {
            return '';
        }
        
        $carousel = $this->get_carousel($carousel_type);
        
        if (!$carousel) {
            if (current_user_can('manage_options') && WP_DEBUG) {
                error_log('Xinyun Carousel Debug: Carousel type "' . $carousel_type . '" not found');
            }
            return '';
        }
        
        // 合并用户设置的配置选项
        $default_options = [
            'posts_per_page' => $theme_options->get_option('homepage_carousel_posts_count', 5),
            'height' => $theme_options->get_option('homepage_carousel_height', 400) . 'px',
            'mobile_height' => ($theme_options->get_option('homepage_carousel_height', 400) * 0.75) . 'px',
            'autoplay' => $theme_options->get_option('homepage_carousel_autoplay', true),
            'interval' => $theme_options->get_option('homepage_carousel_interval', 5000),
            'arrows' => $theme_options->get_option('homepage_carousel_arrows', true),
            'pagination' => $theme_options->get_option('homepage_carousel_pagination', true),
            'pauseOnHover' => true,
            'cover' => true,
            'lazyLoad' => 'nearby'
        ];
        
        // 合并传入的选项
        $final_options = wp_parse_args($options, $default_options);
        
        // 加载资源
        $carousel->enqueue_assets();
        
        // 渲染轮播图
        $result = $carousel->render($final_options);
        
        // 调试信息
        if (current_user_can('manage_options') && WP_DEBUG) {
            error_log('Xinyun Carousel Debug: Render result length = ' . strlen($result));
        }
        
        return $result;
    }
    
    /**
     * 根据需要加载轮播图资源
     */
    public function maybe_enqueue_assets(): void {
        // 只在首页加载
        if (is_home() || is_front_page()) {
            $theme_options = Xinyun_Theme_Options::get_instance();
            $carousel_type = $theme_options->get_option('homepage_carousel_type', 'post');
            
            if ($carousel_type !== 'none') {
                $carousel = $this->get_carousel($carousel_type);
                $carousel?->enqueue_assets();
            }
        }
    }
    
    /**
     * 获取轮播图配置选项
     * 
     * @param string $type 轮播图类型
     * @return array
     */
    public function get_carousel_options(string $type): array {
        $carousel = $this->get_carousel($type);
        
        if (!$carousel) {
            return [];
        }
        
        return $carousel->get_default_options();
    }
}