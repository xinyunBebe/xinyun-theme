<?php
/**
 * Xinyun Theme - 轮播图基础类
 *
 * 所有轮播图类型的基础抽象类
 *
 * @package Xinyun
 * @since 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 轮播图基础抽象类
 */
abstract class Xinyun_Carousel_Base {
    
    /**
     * 轮播图类型标识
     * 
     * @var string
     */
    protected string $type = '';
    
    /**
     * 轮播图名称
     * 
     * @var string
     */
    protected string $name = '';
    
    /**
     * 轮播图描述
     * 
     * @var string
     */
    protected string $description = '';
    
    /**
     * 默认配置选项
     * 
     * @var array
     */
    protected array $default_options = [];
    
    /**
     * 构造函数
     */
    public function __construct() {
        $this->init();
    }
    
    /**
     * 初始化方法
     * 子类可以重写此方法进行自定义初始化
     */
    protected function init(): void {
        // 子类实现
    }
    
    /**
     * 获取轮播图类型
     * 
     * @return string
     */
    public function get_type(): string {
        return $this->type;
    }
    
    /**
     * 获取轮播图名称
     * 
     * @return string
     */
    public function get_name(): string {
        return $this->name;
    }
    
    /**
     * 获取轮播图描述
     * 
     * @return string
     */
    public function get_description(): string {
        return $this->description;
    }
    
    /**
     * 获取默认配置
     * 
     * @return array
     */
    public function get_default_options(): array {
        return $this->default_options;
    }
    
    /**
     * 获取轮播图数据
     * 抽象方法，子类必须实现
     * 
     * @return array
     */
    abstract public function get_slides(): array;
    
    /**
     * 渲染轮播图HTML
     * 抽象方法，子类必须实现
     * 
     * @param array $options 配置选项
     * @return string
     */
    abstract public function render(array $options = []): string;
    
    /**
     * 加载轮播图所需的CSS和JS资源
     * 子类可以重写此方法加载特定资源
     */
    public function enqueue_assets(): void {
        // 不再从 CDN 加载 Splide，由 Vite 构建的 dist/js.css 和 dist/js.js 提供样式与脚本
    }
    
    /**
     * 获取配置选项
     * 合并默认配置和用户配置
     * 
     * @param array $options 用户配置
     * @return array
     */
    protected function get_options(array $options = []): array {
        return wp_parse_args($options, $this->default_options);
    }
    
    /**
     * 安全转义HTML输出
     * 
     * @param mixed $value 要转义的值
     * @param string $context 转义上下文
     * @return string
     */
    protected function esc_output($value, string $context = 'html'): string {
        return match($context) {
            'attr' => esc_attr($value),
            'url' => esc_url($value),
            'js' => wp_json_encode($value),
            default => esc_html($value)
        };
    }
}
