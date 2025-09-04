<?php
/**
 * Xinyun Theme - 自定义轮播图（Splide 方案）
 *
 * 基于用户自定义配置的轮播图类型（本地集成 Splide）
 *
 * @package Xinyun
 * @since 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

class Xinyun_Custom_Carousel extends Xinyun_Carousel_Base {
    protected string $type = 'custom';
    protected string $name = '自定义轮播图';
    protected string $description = '严格按照用户配置显示，只显示已配置的轮播图';

    protected array $default_options = [
        'height' => '400px',
        'mobile_height' => '300px',
        'show_content' => true,
        'show_meta' => true,
        'autoplay' => true,
        'interval' => 5000,
        'arrows' => true,
        'pagination' => true,
    ];

    /**
     * 获取轮播图数据
     */
    public function get_slides(array $options = []): array {
        if (class_exists('Xinyun_Theme_Options')) {
            $theme_options = Xinyun_Theme_Options::get_instance();
            $all_options = $theme_options->get_options();
            $custom_slides = $all_options['homepage_carousel_custom_slides'] ?? [];
        } else {
            $custom_slides = get_option('xinyun_theme_options', [])['homepage_carousel_custom_slides'] ?? [];
        }

        $slides = [];

        foreach ($custom_slides as $slide_config) {
            if (empty($slide_config['image_id']) && empty($slide_config['post_id'])) {
                continue;
            }

            $image_url = '';
            if (!empty($slide_config['image_id'])) {
                $image_url = wp_get_attachment_image_url($slide_config['image_id'], 'large');
            }

            $slide_data = [
                'id' => 'custom_' . md5(($slide_config['image_id'] ?? '') . ($slide_config['post_id'] ?? '')),
                'image_url' => $image_url,
                'title' => '',
                'content' => '',
                'link' => '',
                'meta' => [],
            ];

            if (!empty($slide_config['post_id'])) {
                $post = get_post($slide_config['post_id']);
                if ($post && $post->post_status === 'publish') {
                    $slide_data['title'] = get_the_title($post);
                    $slide_data['content'] = get_the_excerpt($post);
                    $slide_data['link'] = get_permalink($post);

                    if (empty($slide_data['image_url'])) {
                        $featured_image = get_the_post_thumbnail_url($post, 'large');
                        if ($featured_image) {
                            $slide_data['image_url'] = $featured_image;
                        }
                    }

                    if ($options['show_meta'] ?? true) {
                        $slide_data['meta'] = [
                            'date' => get_the_date('Y-m-d', $post),
                            'author' => get_the_author_meta('display_name', $post->post_author),
                        ];
                    }
                }
            }

            if (empty($slide_data['image_url'])) {
                continue;
            }

            $slides[] = $slide_data;
        }

        return $slides;
    }

    /**
     * 渲染轮播图（Splide DOM + Tailwind 样式）
     */
    public function render(array $options = []): string {
        $options = $this->get_options($options);
        $slides = $this->get_slides($options);

        if (empty($slides)) {
            return $this->render_empty_state();
        }

        $section_id = 'custom-carousel';
        $autoplay = $options['autoplay'] ?? true;
        $interval = (int) ($options['interval'] ?? 5000);
        $show_arrows = (bool) ($options['arrows'] ?? true);
        $show_pagination = (bool) ($options['pagination'] ?? true);

        ob_start();
        ?>
        <section id="<?php echo esc_attr($section_id); ?>" class="xinyun-carousel custom-carousel relative w-full mb-8"
                 data-carousel="splide"
                 data-type="loop"
                 data-autoplay="<?php echo $autoplay ? 'true' : 'false'; ?>"
                 data-interval="<?php echo esc_attr($interval); ?>"
                 data-arrows="<?php echo $show_arrows ? 'true' : 'false'; ?>"
                 data-pagination="<?php echo $show_pagination ? 'true' : 'false'; ?>"
                 data-height="<?php echo esc_attr($options['height']); ?>"
                 data-mobile-height="<?php echo esc_attr($options['mobile_height']); ?>">
            <div class="splide carousel-hover-group relative overflow-hidden rounded-xl shadow-xl" style="height: <?php echo esc_attr($options['height']); ?>;">
                <div class="splide__track">
                    <ul class="splide__list">
                        <?php foreach ($slides as $slide): ?>
                            <li class="splide__slide">
                                <div class="relative w-full h-full flex items-center justify-center">
                                    <div class="absolute inset-0 overflow-hidden">
                                        <img class="w-full h-full object-cover" src="<?php echo esc_url($slide['image_url']); ?>" alt="<?php echo esc_attr($slide['title']); ?>" loading="lazy">
                                        <div class="slide-overlay absolute inset-0 bg-gradient-to-t from-black/60 via-black/30 to-transparent"></div>
                                    </div>
                                    <?php if (!empty($slide['title']) && ($options['show_content'] ?? true)): ?>
                                        <div class="slide-info relative z-10 text-white text-center max-w-3xl mx-auto p-6">
                                            <h3 class="text-3xl md:text-4xl font-bold mb-3 leading-tight line-clamp-2">
                                                <?php if (!empty($slide['link'])): ?>
                                                    <a class="text-white no-underline hover:text-blue-100 transition-colors" href="<?php echo esc_url($slide['link']); ?>"><?php echo esc_html($slide['title']); ?></a>
                                                <?php else: ?>
                                                    <?php echo esc_html($slide['title']); ?>
                                                <?php endif; ?>
                                            </h3>
                                            <?php if (!empty($slide['content'])): ?>
                                                <p class="text-base md:text-lg opacity-95 mb-4"><?php echo esc_html($slide['content']); ?></p>
                                            <?php endif; ?>
                                            <?php if (!empty($slide['meta']) && ($options['show_meta'] ?? true)): ?>
                                                <div class="flex items-center justify-center gap-4 text-sm opacity-90">
                                                    <?php if (!empty($slide['meta']['date'])): ?>
                                                        <span>📅 <?php echo esc_html($slide['meta']['date']); ?></span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($slide['meta']['author'])): ?>
                                                        <span>👤 <?php echo esc_html($slide['meta']['author']); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
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
                    #<?php echo esc_js($section_id); ?> .splide { height: <?php echo esc_attr($options['mobile_height']); ?>; }
                }
            </style>
        </section>
        <?php
        return ob_get_clean();
    }

    private function render_empty_state(): string {
        return '<div class="xinyun-carousel-empty">
            <p>🎭 还没有配置自定义轮播图。请在主题设置中添加图片和文章配置。</p>
        </div>';
    }
}
