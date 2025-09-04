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
        
        // 如果自定义轮播图不足，用最新文章补充
        if (count($slides) < $posts_per_page) {
            $remaining_count = $posts_per_page - count($slides);
            
            $posts = get_posts([
                'post_type' => 'post',
                'posts_per_page' => $remaining_count,
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC'
            ]);
            
            foreach ($posts as $post) {
                // 检查是否已经在自定义轮播图中
                $already_exists = false;
                foreach ($slides as $existing_slide) {
                    if ($existing_slide['id'] == $post->ID) {
                        $already_exists = true;
                        break;
                    }
                }
                
                if (!$already_exists) {
                    // 优先使用特色图片，如果没有则使用默认特色图片
                    $thumbnail_id = get_post_thumbnail_id($post->ID);
                    $image_url = '';
                    if ($thumbnail_id) {
                        $image_url = wp_get_attachment_image_url($thumbnail_id, 'large');
                    }
                    
                    // 如果没有特色图片，尝试使用默认特色图片
                    if (empty($image_url)) {
                        $theme_options = Xinyun_Theme_Options::get_instance();
                        $default_featured_image_id = $theme_options->get_option('default_featured_image', '');
                        if ($default_featured_image_id) {
                            $image_url = wp_get_attachment_image_url($default_featured_image_id, 'large');
                        }
                    }
                    
                    // 只要有图片（特色图片或默认图片）就添加到轮播图
                    if ($image_url) {
                        $categories = get_the_category($post->ID);
                        $slides[] = [
                            'id' => $post->ID,
                            'title' => get_the_title($post->ID),
                            'excerpt' => wp_trim_words(get_the_excerpt($post->ID), 20, '...'),
                            'url' => get_permalink($post->ID),
                            'image' => $image_url,
                            'date' => get_the_date('Y年n月j日', $post->ID),
                            'category' => $categories[0]->name ?? ''
                        ];
                    }
                }
            }
        }

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
            // 调试：如果没有幻灯片数据，返回调试信息（仅在WP_DEBUG时显示）
            if (WP_DEBUG) {
                return '<!-- 调试：没有轮播图数据可显示 -->';
            }
            return '';
        }

        ob_start();
        ?>
        <section class="hero-carousel post-carousel" id="hero-carousel">
            <div class="splide" role="group" aria-label="文章轮播图">
                <div class="splide__track">
                    <ul class="splide__list">
                        <?php foreach ($slides as $slide) : ?>
                            <li class="splide__slide">
                                <div class="slide-content">
                                    <div class="slide-image">
                                        <img src="<?php echo $this->esc_output($slide['image'], 'url'); ?>" 
                                             alt="<?php echo $this->esc_output($slide['title'], 'attr'); ?>"
                                             loading="lazy">
                                        <div class="slide-overlay"></div>
                                    </div>
                                    <div class="slide-info">
                                        <?php if (!empty($slide['category'])) : ?>
                                            <span class="slide-category"><?php echo $this->esc_output($slide['category']); ?></span>
                                        <?php endif; ?>
                                        <h2 class="slide-title">
                                            <a href="<?php echo $this->esc_output($slide['url'], 'url'); ?>">
                                                <?php echo $this->esc_output($slide['title']); ?>
                                            </a>
                                        </h2>
                                        <p class="slide-excerpt">
                                            <?php echo $this->esc_output($slide['excerpt']); ?>
                                        </p>
                                        <div class="slide-meta">
                                            <time><?php echo $this->esc_output($slide['date']); ?></time>
                                            <a href="<?php echo $this->esc_output($slide['url'], 'url'); ?>" class="slide-read-more">
                                                阅读更多
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <?php if ($options['pagination']) : ?>
                <!-- 分页指示器 -->
                <div class="splide__pagination"></div>
                <?php endif; ?>
                
                <?php if ($options['arrows']) : ?>
                <!-- 导航箭头 -->
                <div class="splide__arrows">
                    <button class="splide__arrow splide__arrow--prev" aria-label="上一张">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                        </svg>
                    </button>
                    <button class="splide__arrow splide__arrow--next" aria-label="下一张">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
                        </svg>
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof Splide !== 'undefined') {
                    new Splide('#hero-carousel .splide', <?php echo $this->esc_output([
                        'type' => 'loop',
                        'autoplay' => $options['autoplay'],
                        'interval' => $options['interval'],
                        'pauseOnHover' => $options['pauseOnHover'],
                        'pauseOnFocus' => true,
                        'resetProgress' => false,
                        'height' => $options['height'],
                        'cover' => $options['cover'],
                        'arrows' => $options['arrows'],
                        'pagination' => $options['pagination'],
                        'lazyLoad' => $options['lazyLoad'],
                        'breakpoints' => [
                            768 => [
                                'height' => $options['mobile_height'],
                                'arrows' => false,
                            ]
                        ]
                    ], 'js'); ?>).mount();
                }
            });
        </script>
        <?php
        
        return ob_get_clean();
    }
}