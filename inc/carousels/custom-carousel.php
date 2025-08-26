<?php
/**
 * Xinyun Theme - 自定义轮播图
 *
 * 基于用户自定义配置的轮播图类型
 *
 * @package Xinyun
 * @since 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 自定义轮播图类
 */
class Xinyun_Custom_Carousel extends Xinyun_Carousel_Base {
    
    /**
     * 轮播图类型标识
     * 
     * @var string
     */
    protected string $type = 'custom';
    
    /**
     * 轮播图名称
     * 
     * @var string
     */
    protected string $name = '自定义轮播图';
    
    /**
     * 轮播图描述
     * 
     * @var string
     */
    protected string $description = '严格按照用户配置显示，只显示已配置的轮播图';
    
    /**
     * 默认配置选项
     * 
     * @var array
     */
    protected array $default_options = [
        'height' => '400px',
        'mobile_height' => '300px',
        'show_content' => true,
        'show_meta' => true
    ];
    
    /**
     * 获取轮播图数据
     * 实现抽象方法 get_slides
     *
     * @param array $options 配置选项
     * @return array
     */
    public function get_slides(array $options = []): array {
        // 获取主题选项
        if (class_exists('Xinyun_Theme_Options')) {
            $theme_options = Xinyun_Theme_Options::get_instance();
            $all_options = $theme_options->get_options();
            $custom_slides = $all_options['homepage_carousel_custom_slides'] ?? [];
        } else {
            $custom_slides = get_option('xinyun_theme_options', [])['homepage_carousel_custom_slides'] ?? [];
        }
        
        $slides = [];
        
        foreach ($custom_slides as $slide_config) {
            // 跳过空配置
            if (empty($slide_config['image_id']) && empty($slide_config['post_id'])) {
                continue;
            }
            
            // 获取图片URL
            $image_url = '';
            if (!empty($slide_config['image_id'])) {
                $image_url = wp_get_attachment_image_url($slide_config['image_id'], 'large');
            }
            
            $slide_data = [
                'id' => 'custom_' . md5($slide_config['image_id'] . $slide_config['post_id']),
                'image_url' => $image_url,
                'title' => '',
                'content' => '',
                'link' => '',
                'meta' => []
            ];
            
            // 如果指定了文章ID，获取文章信息
            if (!empty($slide_config['post_id'])) {
                $post = get_post($slide_config['post_id']);
                
                if ($post && $post->post_status === 'publish') {
                    $slide_data['title'] = get_the_title($post);
                    $slide_data['content'] = get_the_excerpt($post);
                    $slide_data['link'] = get_permalink($post);
                    
                    // 如果没有自定义图片，尝试使用文章特色图片
                    if (empty($slide_data['image_url'])) {
                        $featured_image = get_the_post_thumbnail_url($post, 'large');
                        if ($featured_image) {
                            $slide_data['image_url'] = $featured_image;
                        }
                    }
                    
                    // 元信息
                    if ($options['show_meta'] ?? true) {
                        $slide_data['meta'] = [
                            'date' => get_the_date('Y-m-d', $post),
                            'author' => get_the_author_meta('display_name', $post->post_author),
                            'categories' => get_the_category_list(', ', '', '', $post->ID)
                        ];
                    }
                }
            }
            
            // 如果没有图片，跳过这个slide
            if (empty($slide_data['image_url'])) {
                continue;
            }
            
            $slides[] = $slide_data;
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
        $options = array_merge($this->default_options, $options);
        $slides = $this->get_slides($options);
        
        if (empty($slides)) {
            return $this->render_empty_state();
        }
        
        $carousel_id = 'xinyun-custom-carousel-' . uniqid();
        $autoplay = $options['autoplay'] ?? true;
        $interval = $options['interval'] ?? 5000;
        $show_arrows = $options['arrows'] ?? true;
        $show_pagination = $options['pagination'] ?? true;
        
        ob_start();
        ?>
        <div class="xinyun-carousel custom-carousel" id="<?php echo esc_attr($carousel_id); ?>" 
             data-autoplay="<?php echo $autoplay ? 'true' : 'false'; ?>" 
             data-interval="<?php echo esc_attr($interval); ?>">
            
            <div class="carousel-container">
                <div class="carousel-track">
                    <?php foreach ($slides as $index => $slide): ?>
                        <div class="carousel-slide <?php echo $index === 0 ? 'active' : ''; ?>" 
                             data-slide="<?php echo esc_attr($index); ?>">
                            
                            <div class="slide-image">
                                <img src="<?php echo esc_url($slide['image_url']); ?>" 
                                     alt="<?php echo esc_attr($slide['title']); ?>"
                                     loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>">
                                
                                <?php if (!empty($slide['title']) && ($options['show_content'] ?? true)): ?>
                                    <div class="slide-overlay">
                                        <div class="slide-content">
                                            <h3 class="slide-title">
                                                <?php if (!empty($slide['link'])): ?>
                                                    <a href="<?php echo esc_url($slide['link']); ?>">
                                                        <?php echo esc_html($slide['title']); ?>
                                                    </a>
                                                <?php else: ?>
                                                    <?php echo esc_html($slide['title']); ?>
                                                <?php endif; ?>
                                            </h3>
                                            
                                            <?php if (!empty($slide['content'])): ?>
                                                <p class="slide-excerpt"><?php echo esc_html($slide['content']); ?></p>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($slide['meta']) && ($options['show_meta'] ?? true)): ?>
                                                <div class="slide-meta">
                                                    <?php if (!empty($slide['meta']['date'])): ?>
                                                        <span class="meta-date">📅 <?php echo esc_html($slide['meta']['date']); ?></span>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($slide['meta']['author'])): ?>
                                                        <span class="meta-author">👤 <?php echo esc_html($slide['meta']['author']); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if ($show_arrows && count($slides) > 1): ?>
                    <button class="carousel-arrow carousel-prev" aria-label="上一张">
                        <span>‹</span>
                    </button>
                    <button class="carousel-arrow carousel-next" aria-label="下一张">
                        <span>›</span>
                    </button>
                <?php endif; ?>
            </div>
            
            <?php if ($show_pagination && count($slides) > 1): ?>
                <div class="carousel-pagination">
                    <?php for ($i = 0; $i < count($slides); $i++): ?>
                        <button class="pagination-dot <?php echo $i === 0 ? 'active' : ''; ?>" 
                                data-slide="<?php echo esc_attr($i); ?>" 
                                aria-label="第<?php echo $i + 1; ?>张">
                        </button>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <style>
            .custom-carousel {
                position: relative;
                margin-bottom: 30px;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            }
            
            .custom-carousel .carousel-container {
                position: relative;
                height: <?php echo esc_attr($options['height']); ?>;
                overflow: hidden;
            }
            
            .custom-carousel .carousel-slide {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                opacity: 0;
                transition: opacity 0.5s ease-in-out;
            }
            
            .custom-carousel .carousel-slide.active {
                opacity: 1;
            }
            
            .custom-carousel .slide-image {
                position: relative;
                width: 100%;
                height: 100%;
            }
            
            .custom-carousel .slide-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center;
            }
            
            .custom-carousel .slide-overlay {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                background: linear-gradient(transparent, rgba(0,0,0,0.7));
                padding: 40px 30px 30px;
                color: white;
            }
            
            .custom-carousel .slide-title {
                margin: 0 0 10px 0;
                font-size: 24px;
                font-weight: 700;
                line-height: 1.3;
            }
            
            .custom-carousel .slide-title a {
                color: white;
                text-decoration: none;
                transition: opacity 0.3s ease;
            }
            
            .custom-carousel .slide-title a:hover {
                opacity: 0.8;
            }
            
            .custom-carousel .slide-excerpt {
                margin: 0 0 15px 0;
                font-size: 16px;
                line-height: 1.5;
                opacity: 0.9;
            }
            
            .custom-carousel .slide-meta {
                display: flex;
                gap: 20px;
                font-size: 14px;
                opacity: 0.8;
            }
            
            @media (max-width: 768px) {
                .custom-carousel .carousel-container {
                    height: <?php echo esc_attr($options['mobile_height']); ?>;
                }
                
                .custom-carousel .slide-overlay {
                    padding: 20px 20px 20px;
                }
                
                .custom-carousel .slide-title {
                    font-size: 20px;
                }
                
                .custom-carousel .slide-excerpt {
                    font-size: 14px;
                }
                
                .custom-carousel .slide-meta {
                    flex-direction: column;
                    gap: 5px;
                }
            }
        </style>
        <?php
        
        return ob_get_clean();
    }
    
    /**
     * 渲染空状态
     * 
     * @return string
     */
    private function render_empty_state(): string {
        return '<div class="xinyun-carousel-empty">
            <p>🎭 还没有配置自定义轮播图。请在主题设置中添加图片和文章配置。</p>
        </div>';
    }
}
