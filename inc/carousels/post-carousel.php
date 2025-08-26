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
    protected string $name = '文章轮播图';
    
    /**
     * 轮播图描述
     * 
     * @var string
     */
    protected string $description = '展示最新发布的文章，包含特色图片、标题和摘要';
    
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
     * 获取最新的几篇包含特色图片的文章
     * 
     * @return array
     */
    public function get_slides(): array {
        $options = $this->get_options();
        
        $posts = get_posts([
            'post_type' => 'post',
            'posts_per_page' => $options['posts_per_page'],
            'post_status' => 'publish',
            'meta_query' => [
                [
                    'key' => '_thumbnail_id',
                    'compare' => 'EXISTS'
                ]
            ],
            'orderby' => 'date',
            'order' => 'DESC'
        ]);

        $slides = [];
        
        foreach ($posts as $post) {
            $thumbnail_id = get_post_thumbnail_id($post->ID);
            $image_url = wp_get_attachment_image_url($thumbnail_id, 'xinyun-featured');
            
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