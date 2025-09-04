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

        $type = $options['fade_effect'] ? 'fade' : 'loop';

        ob_start();
        ?>
        <section class="hero-carousel gallery-carousel relative w-full mb-8" id="gallery-carousel"
                 data-carousel="splide"
                 data-type="<?php echo esc_attr($type); ?>"
                 data-autoplay="<?php echo $options['autoplay'] ? 'true' : 'false'; ?>"
                 data-interval="<?php echo esc_attr($options['interval']); ?>"
                 data-arrows="<?php echo $options['arrows'] ? 'true' : 'false'; ?>"
                 data-pagination="<?php echo $options['pagination'] ? 'true' : 'false'; ?>"
                 data-height="<?php echo esc_attr($options['height']); ?>"
                 data-mobile-height="<?php echo esc_attr($options['mobile_height']); ?>">
            <div class="splide relative overflow-hidden rounded-xl shadow-xl" role="group" aria-label="图片轮播图" style="height: <?php echo esc_attr($options['height']); ?>;">
                <div class="splide__track">
                    <ul class="splide__list">
                        <?php foreach ($slides as $slide) : ?>
                            <li class="splide__slide">
                                <div class="slide-content gallery-slide">
                                    <div class="slide-image absolute inset-0 overflow-hidden">
                                        <img class="w-full h-full object-cover" src="<?php echo $this->esc_output($slide['image'], 'url'); ?>" 
                                             alt="<?php echo $this->esc_output($slide['alt'] ?: $slide['title'], 'attr'); ?>" loading="lazy">
                                        <div class="slide-overlay gallery-overlay absolute inset-0 bg-gradient-to-t from-black/60 via-black/30 to-transparent"></div>
                                    </div>
                                    
                                    <?php if (!empty($slide['caption']) || !empty($slide['title'])) : ?>
                                        <div class="slide-info gallery-info relative z-10 text-white text-center max-w-3xl mx-auto p-6">
                                            <?php if (!empty($slide['title'])) : ?>
                                                <h2 class="slide-title gallery-title text-3xl md:text-4xl font-bold mb-3 leading-tight line-clamp-2">
                                                    <?php echo $this->esc_output($slide['title']); ?>
                                                </h2>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($slide['caption'])) : ?>
                                                <p class="slide-caption text-base md:text-lg opacity-95 mb-4">
                                                    <?php echo $this->esc_output($slide['caption']); ?>
                                                </p>
                                            <?php endif; ?>
                                            
                                            <div class="slide-meta flex items-center justify-center gap-4 text-sm opacity-90">
                                                <time class="text-blue-100"><?php echo $this->esc_output($slide['date']); ?></time>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <style>
                @media (max-width: 768px) {
                    #gallery-carousel .splide { height: <?php echo esc_attr($options['mobile_height']); ?>; }
                }
            </style>
        </section>
        <?php
        
        return ob_get_clean();
    }
}
