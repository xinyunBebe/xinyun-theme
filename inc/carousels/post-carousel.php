<?php
/**
 * Xinyun Theme - 文章轮播图
 *
 * 基于最新文章的轮播图类型
 *
 * @package Xinyun
 * @since 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 文章轮播图类
 */
class Xinyun_Post_Carousel extends Xinyun_Carousel_Base {
    
    /**
     * 轮播图类型标识
     * 
     * @var string
     */
    protected string $type = 'post';
    
    /**
     * 轮播图名称
     * 
     * @var string
     */
    protected string $name = '智能轮播图';
    
    /**
     * 轮播图描述
     * 
     * @var string
     */
    protected string $description = '优先使用自定义轮播图配置，不足时自动补充最新文章';
    
    /**
     * 默认配置选项
     * 
     * @var array
     */
    protected array $default_options = [
        'posts_per_page' => 5,
        'height' => '400px',
        'mobile_height' => '300px',
        'autoplay' => true,
        'interval' => 5000,
        'arrows' => true,
        'pagination' => true,
        'pauseOnHover' => true,
        'cover' => true,
        'lazyLoad' => 'nearby'
    ];
    
    /**
     * 获取轮播图数据
     * 优先使用主题设置中的自定义轮播图配置，如果没有则使用最新文章
     * 
     * @return array
     */
    public function get_slides(): array {
        // 获取主题设置
        $theme_options = Xinyun_Theme_Options::get_instance();
        $posts_per_page = $theme_options->get_option('homepage_carousel_posts_count', 5);
        
        // 首先尝试获取自定义轮播图配置
        $all_options = $theme_options->get_options();
        $custom_slides = $all_options['homepage_carousel_custom_slides'] ?? [];
        
        $slides = [];
        
        // 如果有自定义轮播图配置，使用自定义配置
        if (!empty($custom_slides)) {
            foreach ($custom_slides as $slide_config) {
                // 获取图片URL
                $image_url = '';
                if (!empty($slide_config['image_id'])) {
                    $image_url = wp_get_attachment_image_url($slide_config['image_id'], 'large');
                }
                
                // 如果有文章ID，获取文章信息
                $post_data = [];
                if (!empty($slide_config['post_id'])) {
                    $post = get_post($slide_config['post_id']);
                    if ($post && $post->post_status === 'publish') {
                        $post_data = [
                            'title' => get_the_title($post),
                            'excerpt' => wp_trim_words(get_the_excerpt($post), 20, '...'),
                            'url' => get_permalink($post),
                            'date' => get_the_date('Y年n月j日', $post),
                            'category' => ''
                        ];
                        
                        $categories = get_the_category($post->ID);
                        if (!empty($categories)) {
                            $post_data['category'] = $categories[0]->name;
                        }
                        
                        // 如果没有自定义图片，尝试使用文章特色图片
                        if (empty($image_url)) {
                            $featured_image = get_the_post_thumbnail_url($post, 'large');
                            if ($featured_image) {
                                $image_url = $featured_image;
                            } else {
                                // 如果文章也没有特色图片，使用默认特色图片
                                $theme_options = Xinyun_Theme_Options::get_instance();
                                $default_featured_image_id = $theme_options->get_option('default_featured_image', '');
                                if ($default_featured_image_id) {
                                    $image_url = wp_get_attachment_image_url($default_featured_image_id, 'large');
                                }
                            }
                        }
                    }
                }
                
                // 覆盖标题与链接（用户手动输入优先）
                if (!empty($slide_config['title'])) {
                    $post_data['title'] = $slide_config['title'];
                }
                if (!empty($slide_config['link'])) {
                    $post_data['url'] = $slide_config['link'];
                }
                
                // 如果还是没有图片，使用默认特色图片
                if (empty($image_url)) {
                    $theme_options = Xinyun_Theme_Options::get_instance();
                    $default_featured_image_id = $theme_options->get_option('default_featured_image', '');
                    if ($default_featured_image_id) {
                        $image_url = wp_get_attachment_image_url($default_featured_image_id, 'large');
                    }
                }
                
                // 只有当有图片时才添加到轮播图中
                if (!empty($image_url)) {
                    $slides[] = [
                        'id' => $slide_config['post_id'] ?? 'custom_' . uniqid(),
                        'title' => $post_data['title'] ?? '轮播图',
                        'excerpt' => $post_data['excerpt'] ?? '',
                        'url' => $post_data['url'] ?? '#',
                        'image' => $image_url,
                        'date' => $post_data['date'] ?? '',
                        'category' => $post_data['category'] ?? ''
                    ];
                }
                
                // 限制轮播图数量
                if (count($slides) >= $posts_per_page) {
                    break;
                }
            }
        }
        
        // 仅显示用户设置的自定义轮播图，不再自动补充最新文章

        return $slides;
    }
    
    /**
     * 渲染轮播图HTML
     * 
     * @param array $options 配置选项
     * @return string
     */
    public function render(array $options = []): string {
        $options = $this->get_options($options);
        $slides = $this->get_slides();

        if (empty($slides)) {
            if (WP_DEBUG) {
                return '<!-- 调试：没有轮播图数据可显示 -->';
            }
            return '';
        }

        $autoplay = $options['autoplay'];
        $interval = (int) $options['interval'];
        $show_arrows = (bool) $options['arrows'];
        $show_pagination = (bool) $options['pagination'];

        // 使用固定容器ID，便于前端初始化
        $carousel_id = 'hero-carousel';

        ob_start();
        ?>
        <section id="<?php echo esc_attr($carousel_id); ?>" class="hero-carousel post-carousel relative w-full mb-8"
                 data-carousel="splide"
                 data-type="loop"
                 data-autoplay="<?php echo $autoplay ? 'true' : 'false'; ?>"
                 data-interval="<?php echo esc_attr($interval); ?>"
                 data-arrows="<?php echo $show_arrows ? 'true' : 'false'; ?>"
                 data-pagination="<?php echo $show_pagination ? 'true' : 'false'; ?>"
                 data-height="<?php echo esc_attr($options['height']); ?>"
                 data-mobile-height="<?php echo esc_attr($options['mobile_height']); ?>">
            <div class="splide carousel-hover-group relative overflow-hidden rounded-xl shadow-xl" style="height: <?php echo esc_attr($options['height']); ?>;" role="group" aria-label="文章轮播图">
                <div class="splide__track">
                    <ul class="splide__list">
                        <?php foreach ($slides as $slide): ?>
                            <li class="splide__slide">
                                <div class="relative w-full h-full flex items-center justify-center">
                                    <div class="absolute inset-0 overflow-hidden">
                                        <img class="w-full h-full object-cover" src="<?php echo $this->esc_output($slide['image'], 'url'); ?>" alt="<?php echo $this->esc_output($slide['title'], 'attr'); ?>" loading="lazy">
                                        <div class="slide-overlay absolute inset-0 bg-gradient-to-tr from-[#007cba]/70 via-[#005a87]/80 to-black/60"></div>
                                    </div>
                                    <div class="slide-info relative z-10 text-white text-center max-w-3xl mx-auto p-6">
                                        <h2 class="text-4xl md:text-5xl font-bold mb-4 leading-tight drop-shadow line-clamp-2">
                                            <a class="text-white no-underline hover:text-blue-100 transition-colors" href="<?php echo $this->esc_output($slide['url'], 'url'); ?>"><?php echo $this->esc_output($slide['title']); ?></a>
                                        </h2>
                                        <p class="text-lg md:text-xl leading-relaxed mb-6 opacity-95"><?php echo $this->esc_output($slide['excerpt']); ?></p>
                                        <div class="flex flex-col items-center gap-4">
                                            <time class="text-blue-100"><?php echo $this->esc_output($slide['date']); ?></time>
                                            <a href="<?php echo $this->esc_output($slide['url'], 'url'); ?>" class="inline-block px-4 py-2 rounded-full bg-white/20 text-white no-underline backdrop-blur border border-white/30 hover:bg-white/30 transition shadow">阅读更多</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <style>
                @media (max-width: 768px) {
                    #<?php echo esc_js($carousel_id); ?> .splide { height: <?php echo esc_attr($options['mobile_height']); ?>; }
                }
            </style>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * 覆盖基础资源加载，避免依赖外部CDN（Splide）。
     */
    public function enqueue_assets(): void {
        // 不加载任何外部库，渲染中已包含必要样式/脚本。
    }
}
