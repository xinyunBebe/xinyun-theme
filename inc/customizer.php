<?php
/**
 * Xinyun Theme - 自定义器设置
 *
 * WordPress自定义器的设置和配置
 *
 * @package Xinyun
 * @since 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 添加自定义器设置
 */
function xinyun_customize_register($wp_customize) {
    
    // 网站标识部分增强
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    
    // 主题颜色设置
    $wp_customize->add_section('xinyun_colors', array(
        'title'    => '主题颜色',
        'priority' => 30,
    ));
    
    // 主色调设置
    $wp_customize->add_setting('xinyun_primary_color', array(
        'default'           => '#007cba',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'xinyun_primary_color', array(
        'label'   => '主色调',
        'section' => 'xinyun_colors',
    )));
    
    // 辅助色设置
    $wp_customize->add_setting('xinyun_secondary_color', array(
        'default'           => '#f9f9f9',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'xinyun_secondary_color', array(
        'label'   => '辅助色（背景色）',
        'section' => 'xinyun_colors',
    )));
    
    // 版式设置
    $wp_customize->add_section('xinyun_typography', array(
        'title'    => '版式设置',
        'priority' => 35,
    ));
    
    // 字体选择
    $wp_customize->add_setting('xinyun_font_family', array(
        'default'           => 'system',
        'sanitize_callback' => 'xinyun_sanitize_font_family',
    ));
    
    $wp_customize->add_control('xinyun_font_family', array(
        'label'   => '字体选择',
        'section' => 'xinyun_typography',
        'type'    => 'select',
        'choices' => array(
            'system'     => '系统默认字体',
            'serif'      => '衬线字体',
            'sans-serif' => '无衬线字体',
            'monospace'  => '等宽字体',
        ),
    ));
    
    // 布局设置
    $wp_customize->add_section('xinyun_layout', array(
        'title'    => '布局设置',
        'priority' => 40,
    ));
    
    // 容器宽度
    $wp_customize->add_setting('xinyun_container_width', array(
        'default'           => '1200',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('xinyun_container_width', array(
        'label'       => '容器最大宽度（像素）',
        'section'     => 'xinyun_layout',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 800,
            'max'  => 1600,
            'step' => 50,
        ),
    ));
    
    // 侧边栏位置
    $wp_customize->add_setting('xinyun_sidebar_position', array(
        'default'           => 'right',
        'sanitize_callback' => 'xinyun_sanitize_sidebar_position',
    ));
    
    $wp_customize->add_control('xinyun_sidebar_position', array(
        'label'   => '侧边栏位置',
        'section' => 'xinyun_layout',
        'type'    => 'radio',
        'choices' => array(
            'left'  => '左侧',
            'right' => '右侧',
            'none'  => '不显示',
        ),
    ));
    
    // 头部设置
    $wp_customize->add_section('xinyun_header', array(
        'title'    => '头部设置',
        'priority' => 45,
    ));
    
    // 头部布局
    $wp_customize->add_setting('xinyun_header_layout', array(
        'default'           => 'horizontal',
        'sanitize_callback' => 'xinyun_sanitize_header_layout',
    ));
    
    $wp_customize->add_control('xinyun_header_layout', array(
        'label'   => '头部布局',
        'section' => 'xinyun_header',
        'type'    => 'select',
        'choices' => array(
            'horizontal' => '水平布局',
            'vertical'   => '垂直布局',
            'centered'   => '居中布局',
        ),
    ));
    
    // 显示搜索框
    $wp_customize->add_setting('xinyun_show_search', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('xinyun_show_search', array(
        'label'   => '在头部显示搜索框',
        'section' => 'xinyun_header',
        'type'    => 'checkbox',
    ));
    
    // 底部设置
    $wp_customize->add_section('xinyun_footer', array(
        'title'    => '底部设置',
        'priority' => 50,
    ));
    
    // 版权文本
    $wp_customize->add_setting('xinyun_copyright_text', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('xinyun_copyright_text', array(
        'label'   => '版权文本',
        'section' => 'xinyun_footer',
        'type'    => 'textarea',
    ));
    
    // 显示主题信息
    $wp_customize->add_setting('xinyun_show_theme_credit', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('xinyun_show_theme_credit', array(
        'label'   => '显示主题信息',
        'section' => 'xinyun_footer',
        'type'    => 'checkbox',
    ));
    
    // 博客设置
    $wp_customize->add_section('xinyun_blog', array(
        'title'    => '博客设置',
        'priority' => 55,
    ));
    
    // 摘要长度
    $wp_customize->add_setting('xinyun_excerpt_length', array(
        'default'           => 30,
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('xinyun_excerpt_length', array(
        'label'       => '文章摘要长度（字数）',
        'section'     => 'xinyun_blog',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 10,
            'max'  => 100,
            'step' => 5,
        ),
    ));
    
    // 显示特色图片
    $wp_customize->add_setting('xinyun_show_featured_image', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('xinyun_show_featured_image', array(
        'label'   => '在文章列表显示特色图片',
        'section' => 'xinyun_blog',
        'type'    => 'checkbox',
    ));
    
    // 显示作者信息
    $wp_customize->add_setting('xinyun_show_author_info', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('xinyun_show_author_info', array(
        'label'   => '显示作者信息',
        'section' => 'xinyun_blog',
        'type'    => 'checkbox',
    ));
    
    // 首页轮播图设置
    $wp_customize->add_section('xinyun_homepage_carousel', array(
        'title'    => '首页轮播图',
        'priority' => 25,
        'description' => '设置首页轮播图的显示类型和配置选项',
    ));
    
    // 轮播图类型选择
    $wp_customize->add_setting('homepage_carousel_type', array(
        'default'           => 'post',
        'sanitize_callback' => 'xinyun_sanitize_carousel_type',
    ));
    
    // 获取轮播图管理器实例和选项
    $carousel_manager = Xinyun_Carousel_Manager::get_instance();
    $carousel_choices = $carousel_manager->get_carousel_choices();
    
    $wp_customize->add_control('homepage_carousel_type', array(
        'label'   => '轮播图类型',
        'section' => 'xinyun_homepage_carousel',
        'type'    => 'select',
        'choices' => $carousel_choices,
        'description' => '选择首页要显示的轮播图类型',
    ));
    
    // 轮播图高度设置
    $wp_customize->add_setting('homepage_carousel_height', array(
        'default'           => 400,
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('homepage_carousel_height', array(
        'label'       => '轮播图高度（像素）',
        'section'     => 'xinyun_homepage_carousel',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 200,
            'max'  => 800,
            'step' => 50,
        ),
        'description' => '设置桌面端轮播图的高度',
    ));
    
    // 自动播放设置
    $wp_customize->add_setting('homepage_carousel_autoplay', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('homepage_carousel_autoplay', array(
        'label'   => '自动播放',
        'section' => 'xinyun_homepage_carousel',
        'type'    => 'checkbox',
        'description' => '启用轮播图自动播放功能',
    ));
    
    // 播放间隔设置
    $wp_customize->add_setting('homepage_carousel_interval', array(
        'default'           => 5000,
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('homepage_carousel_interval', array(
        'label'       => '播放间隔（毫秒）',
        'section'     => 'xinyun_homepage_carousel',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 2000,
            'max'  => 10000,
            'step' => 500,
        ),
        'description' => '自动播放的时间间隔',
        'active_callback' => 'xinyun_is_carousel_autoplay_enabled',
    ));
    
    // 显示数量设置
    $wp_customize->add_setting('homepage_carousel_posts_count', array(
        'default'           => 5,
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('homepage_carousel_posts_count', array(
        'label'       => '显示文章数量',
        'section'     => 'xinyun_homepage_carousel',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 3,
            'max'  => 10,
            'step' => 1,
        ),
        'description' => '轮播图中显示的文章数量',
        'active_callback' => 'xinyun_is_post_carousel_selected',
    ));
}
add_action('customize_register', 'xinyun_customize_register');

/**
 * 净化函数
 */
function xinyun_sanitize_font_family($input) {
    $valid = array('system', 'serif', 'sans-serif', 'monospace');
    return in_array($input, $valid) ? $input : 'system';
}

function xinyun_sanitize_sidebar_position($input) {
    $valid = array('left', 'right', 'none');
    return in_array($input, $valid) ? $input : 'right';
}

function xinyun_sanitize_header_layout($input) {
    $valid = array('horizontal', 'vertical', 'centered');
    return in_array($input, $valid) ? $input : 'horizontal';
}

function xinyun_sanitize_carousel_type($input) {
    $carousel_manager = Xinyun_Carousel_Manager::get_instance();
    $valid_types = array_keys($carousel_manager->get_carousel_choices());
    return in_array($input, $valid_types) ? $input : 'post';
}

/**
 * 条件显示回调函数
 */
function xinyun_is_carousel_autoplay_enabled($control) {
    return $control->manager->get_setting('homepage_carousel_autoplay')->value();
}

function xinyun_is_post_carousel_selected($control) {
    return $control->manager->get_setting('homepage_carousel_type')->value() === 'post';
}

/**
 * 自定义器预览JavaScript
 */
function xinyun_customize_preview_js() {
    wp_enqueue_script('xinyun-customizer', get_template_directory_uri() . '/js/customizer.js', array('customize-preview'), XINYUN_VERSION, true);
}
add_action('customize_preview_init', 'xinyun_customize_preview_js');

/**
 * 应用自定义样式
 */
function xinyun_customize_css() {
    $primary_color = get_theme_mod('xinyun_primary_color', '#007cba');
    $secondary_color = get_theme_mod('xinyun_secondary_color', '#f9f9f9');
    $container_width = get_theme_mod('xinyun_container_width', '1200');
    $font_family = get_theme_mod('xinyun_font_family', 'system');
    
    $css = '';
    
    // 主色调
    if ($primary_color !== '#007cba') {
        $css .= "
        :root {
            --primary-color: {$primary_color};
        }
        a, .btn, .main-navigation a:hover,
        .search-submit, .comment-reply-link:hover,
        .entry-title a:hover, .widget-title {
            color: {$primary_color};
        }
        .btn, .search-submit, .comment-form input[type='submit'] {
            background-color: {$primary_color};
        }
        .widget, .post, .comments-area {
            border-left-color: {$primary_color};
        }
        ";
    }
    
    // 辅助色
    if ($secondary_color !== '#f9f9f9') {
        $css .= "
        .widget, .post-navigation, .pagination,
        .comments-area, .search-section {
            background-color: {$secondary_color};
        }
        ";
    }
    
    // 容器宽度
    if ($container_width !== '1200') {
        $css .= "
        .container {
            max-width: {$container_width}px;
        }
        ";
    }
    
    // 字体设置
    $font_map = array(
        'system'     => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif',
        'serif'      => 'Georgia, "Times New Roman", Times, serif',
        'sans-serif' => 'Arial, Helvetica, sans-serif',
        'monospace'  => 'Consolas, Monaco, "Courier New", monospace',
    );
    
    if (isset($font_map[$font_family])) {
        $css .= "
        body {
            font-family: {$font_map[$font_family]};
        }
        ";
    }
    
    if (!empty($css)) {
        echo '<style type="text/css" id="xinyun-customizer-css">' . $css . '</style>';
    }
}
add_action('wp_head', 'xinyun_customize_css');