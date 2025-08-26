<?php
/**
 * Xinyun Theme - 图片轮播图
 *
 * 基于媒体库图片的轮播图类型（示例）
 *
 * @package Xinyun
 * @since 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 图片轮播图类
 * 
 * 这是一个示例类，展示如何创建不同类型的轮播图
 * 可以基于媒体库的图片创建轮播效果
 */
class Xinyun_Gallery_Carousel extends Xinyun_Carousel_Base {
    
    /**
     * 轮播图类型标识
     * 
     * @var string
     */
    protected string $type = 'gallery';
    
    /**
     * 轮播图名称
     * 
     * @var string
     */
    protected string $name = '图片轮播图';
    
    /**
     * 轮播图描述
     * 
     * @var string
     */
    protected string $description = '展示媒体库中的精选图片，纯图片展示效果';
    
    /**
     * 默认配置选项
     * 
     * @var array
     */
    protected array $default_options = [
        'images_count' => 6,
        'height' => '500px',
        'mobile_height' => '350px',
        'autoplay' => true,
        'interval' => 4000,
        'arrows' => true,
        'pagination' => true,
        'pauseOnHover' => true,
        'cover' => true,
        'fade_effect' => false
    ];
    
    /**
     * 获取轮播图数据
     * 获取媒体库中的图片
     * 
     * @return array
     */
    public function get_slides(): array {
        $options = $this->get_options();
        
        // 获取媒体库中的图片
        $images = get_posts([
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'post_status' => 'inherit',
            'posts_per_page' => $options['images_count'],
            'orderby' => 'date',
            'order' => 'DESC'
        ]);

        $slides = [];
        
        foreach ($images as $image) {
            $image_url = wp_get_attachment_image_url($image->ID, 'large');
            $image_meta = wp_get_attachment_metadata($image->ID);
            
            if ($image_url) {
                $slides[] = [
                    'id' => $image->ID,
                    'title' => get_the_title($image->ID),
                    'description' => get_post_field('post_content', $image->ID),
                    'url' => $image_url,
                    'image' => $image_url,
                    'alt' => get_post_meta($image->ID, '_wp_attachment_image_alt', true),
                    'caption' => wp_get_attachment_caption($image->ID),
                    'date' => get_the_date('Y年n月j日', $image->ID)
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

        $effect_class = $options['fade_effect'] ? 'fade' : 'slide';

        ob_start();
        ?>
        <section class="hero-carousel gallery-carousel" id="gallery-carousel">
            <div class="splide splide--<?php echo $this->esc_output($effect_class, 'attr'); ?>" role="group" aria-label="图片轮播图">
                <div class="splide__track">
                    <ul class="splide__list">
                        <?php foreach ($slides as $slide) : ?>
                            <li class="splide__slide">
                                <div class="slide-content gallery-slide">
                                    <div class="slide-image">
                                        <img src="<?php echo $this->esc_output($slide['image'], 'url'); ?>" 
                                             alt="<?php echo $this->esc_output($slide['alt'] ?: $slide['title'], 'attr'); ?>"
                                             loading="lazy">
                                        <div class="slide-overlay gallery-overlay"></div>
                                    </div>
                                    
                                    <?php if (!empty($slide['caption']) || !empty($slide['title'])) : ?>
                                        <div class="slide-info gallery-info">
                                            <?php if (!empty($slide['title'])) : ?>
                                                <h2 class="slide-title gallery-title">
                                                    <?php echo $this->esc_output($slide['title']); ?>
                                                </h2>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($slide['caption'])) : ?>
                                                <p class="slide-caption">
                                                    <?php echo $this->esc_output($slide['caption']); ?>
                                                </p>
                                            <?php endif; ?>
                                            
                                            <div class="slide-meta">
                                                <time><?php echo $this->esc_output($slide['date']); ?></time>
                                            </div>
                                        </div>
                                    <?php endif; ?>
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
                    const config = <?php echo $this->esc_output([
                        'type' => $options['fade_effect'] ? 'fade' : 'loop',
                        'autoplay' => $options['autoplay'],
                        'interval' => $options['interval'],
                        'pauseOnHover' => $options['pauseOnHover'],
                        'pauseOnFocus' => true,
                        'resetProgress' => false,
                        'height' => $options['height'],
                        'cover' => $options['cover'],
                        'arrows' => $options['arrows'],
                        'pagination' => $options['pagination'],
                        'lazyLoad' => 'nearby',
                        'breakpoints' => [
                            768 => [
                                'height' => $options['mobile_height'],
                                'arrows' => false,
                            ]
                        ]
                    ], 'js'); ?>;
                    
                    new Splide('#gallery-carousel .splide', config).mount();
                }
            });
        </script>
        <?php
        
        return ob_get_clean();
    }
}