<?php
/**
 * Xinyun Theme - 功能文件
 *
 * 主题的核心功能和设置
 *
 * @package Xinyun
 * @since 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 主题版本常量
 */
define('XINYUN_VERSION', '1.0.0');

/**
 * 主题设置
 */
function xinyun_setup() {
    // 添加默认的RSS feed链接到头部
    add_theme_support('automatic-feed-links');

    // 让WordPress管理文档标题
    add_theme_support('title-tag');

    // 启用文章和页面的特色图像
    add_theme_support('post-thumbnails');

    // 添加各种尺寸的图片支持
    add_image_size('xinyun-featured', 1200, 600, true);
    add_image_size('xinyun-thumbnail', 300, 200, true);

    // 注册导航菜单
    register_nav_menus(array(
        'primary' => '主导航菜单',
        'footer'  => '底部菜单',
    ));

    // 启用HTML5标记支持
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // 启用自定义背景
    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
    ));

    // 启用自定义Logo
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // 启用文章格式支持
    add_theme_support('post-formats', array(
        'aside',
        'gallery',
        'link',
        'image',
        'quote',
        'status',
        'video',
        'audio',
        'chat',
    ));

    // 启用编辑器样式
    add_theme_support('editor-styles');
    add_editor_style('editor-style.css');

    // 启用响应式嵌入
    add_theme_support('responsive-embeds');

    // 设置内容宽度
    if (!isset($content_width)) {
        $content_width = 1200;
    }
}
add_action('after_setup_theme', 'xinyun_setup');

/**
 * 注册小工具区域
 */
function xinyun_widgets_init() {
    register_sidebar(array(
        'name'          => '主侧边栏',
        'id'            => 'sidebar-1',
        'description'   => '主要的侧边栏小工具区域',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => '底部小工具区域 1',
        'id'            => 'footer-1',
        'description'   => '底部第一个小工具区域',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar(array(
        'name'          => '底部小工具区域 2',
        'id'            => 'footer-2',
        'description'   => '底部第二个小工具区域',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar(array(
        'name'          => '底部小工具区域 3',
        'id'            => 'footer-3',
        'description'   => '底部第三个小工具区域',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'xinyun_widgets_init');

/**
 * 加载样式和脚本
 */
function xinyun_scripts() {
    // 主样式文件
    wp_enqueue_style('xinyun-style', get_stylesheet_uri(), array(), XINYUN_VERSION);

    // 如果需要导航脚本，可以在这里添加

    // 如果是单篇文章且评论开放，加载评论回复脚本
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'xinyun_scripts');

/**
 * 自定义摘要长度
 */
function xinyun_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'xinyun_excerpt_length', 999);

/**
 * 自定义摘要后缀
 */
function xinyun_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'xinyun_excerpt_more');

/**
 * 自定义评论显示
 */
function xinyun_comment($comment, $args, $depth) {
    $tag = ($args['style'] === 'div') ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?>>
    <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
        <footer class="comment-meta">
            <div class="comment-author vcard">
                <?php echo get_avatar($comment, $args['avatar_size']); ?>
                <?php printf('<cite class="fn">%s</cite>', get_comment_author_link()); ?>
            </div>
            <div class="comment-metadata">
                <a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
                    <time datetime="<?php comment_time('c'); ?>">
                        <?php printf('%1$s %2$s', get_comment_date(), get_comment_time()); ?>
                    </time>
                </a>
                <?php edit_comment_link('编辑', '<span class="edit-link">', '</span>'); ?>
            </div>
        </footer>

        <div class="comment-content">
            <?php comment_text(); ?>
        </div>

        <?php
        comment_reply_link(array_merge($args, array(
            'add_below' => 'div-comment',
            'depth'     => $depth,
            'max_depth' => $args['max_depth'],
            'before'    => '<div class="reply">',
            'after'     => '</div>',
        )));
        ?>
    </article>
    <?php
}

/**
 * 移除WordPress版本号
 */
function xinyun_remove_wp_version() {
    return '';
}
add_filter('the_generator', 'xinyun_remove_wp_version');

/**
 * 自定义body class
 */
function xinyun_body_classes($classes) {
    // 添加页面slug到body class
    if (is_page()) {
        global $post;
        $classes[] = 'page-' . $post->post_name;
    }

    // 如果没有侧边栏，添加full-width class
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }

    return $classes;
}
add_filter('body_class', 'xinyun_body_classes');

/**
 * 自定义搜索表单
 */
function xinyun_search_form($form) {
    $form = '<form role="search" method="get" class="search-form" action="' . home_url('/') . '">
        <label>
            <span class="screen-reader-text">搜索：</span>
            <input type="search" class="search-field" placeholder="搜索..." value="' . get_search_query() . '" name="s" />
        </label>
        <input type="submit" class="search-submit" value="搜索" />
    </form>';

    return $form;
}
add_filter('get_search_form', 'xinyun_search_form');

/**
 * 禁用文件编辑器（安全考虑）
 */
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}

/**
 * 清理wp_head
 */
function xinyun_clean_head() {
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
}
add_action('init', 'xinyun_clean_head');

/**
 * 自定义分页函数
 */
function xinyun_pagination() {
    global $wp_query;

    $big = 999999999;

    echo paginate_links(array(
        'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format'    => '?paged=%#%',
        'current'   => max(1, get_query_var('paged')),
        'total'     => $wp_query->max_num_pages,
        'prev_text' => '← 上一页',
        'next_text' => '下一页 →',
    ));
}

/**
 * 自定义后台管理员页脚文本
 */
function xinyun_admin_footer_text($footer_text) {
    return 'Thanks for using Xinyun Theme!';
}
add_filter('admin_footer_text', 'xinyun_admin_footer_text');

/**
 * 加载主题设置页面
 */
require get_template_directory() . '/inc/theme-options.php';

/**
 * 加载轮播图管理器
 */
require get_template_directory() . '/inc/carousels/carousel-manager.php';

/**
 * 初始化主题组件
 */
function xinyun_init_theme_components(): void {
    // 初始化主题设置页面
    Xinyun_Theme_Options::get_instance();
    
    // 初始化轮播图管理器
    Xinyun_Carousel_Manager::get_instance();
}
add_action('init', 'xinyun_init_theme_components');

/**
 * 渲染首页轮播图（兼容函数）
 */
function xinyun_render_carousel(): string {
    try {
        $carousel_manager = Xinyun_Carousel_Manager::get_instance();
        $result = $carousel_manager->render_homepage_carousel();
        
        // 如果没有内容，返回调试信息
        if (empty($result)) {
            if (current_user_can('manage_options')) {
                $theme_options = Xinyun_Theme_Options::get_instance();
                $carousel_type = $theme_options->get_option('homepage_carousel_type', 'post');
                $all_options = $theme_options->get_options();
                
                $debug_info = '<div style="padding: 20px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; margin-bottom: 20px;">
                    <h3>🔧 轮播图调试信息</h3>
                    <p><strong>当前轮播图类型：</strong>' . esc_html($carousel_type) . '</p>';
                
                if ($carousel_type === 'custom') {
                    $custom_slides = $all_options['homepage_carousel_custom_slides'] ?? [];
                    $debug_info .= '<p><strong>自定义轮播图数量：</strong>' . count($custom_slides) . '</p>';
                    if (!empty($custom_slides)) {
                        $debug_info .= '<p><strong>轮播图配置：</strong></p><ul>';
                        foreach ($custom_slides as $i => $slide) {
                            $debug_info .= '<li>轮播图 ' . ($i + 1) . ': 图片ID=' . ($slide['image_id'] ?? '无') . ', 文章ID=' . ($slide['post_id'] ?? '无') . '</li>';
                        }
                        $debug_info .= '</ul>';
                    }
                } elseif ($carousel_type === 'post') {
                    $custom_slides = $all_options['homepage_carousel_custom_slides'] ?? [];
                    $debug_info .= '<p><strong>自定义轮播图配置数量：</strong>' . count($custom_slides) . '</p>';
                    
                    if (!empty($custom_slides)) {
                        $debug_info .= '<p><strong>轮播图配置：</strong></p><ul>';
                        foreach ($custom_slides as $i => $slide) {
                            $has_image = !empty($slide['image_id']) && wp_get_attachment_image_url($slide['image_id'], 'large');
                            $has_post = !empty($slide['post_id']) && get_post($slide['post_id']);
                            $debug_info .= '<li>轮播图 ' . ($i + 1) . ': 图片=' . ($has_image ? '✅' : '❌') . ', 文章=' . ($has_post ? '✅' : '❌') . '</li>';
                        }
                        $debug_info .= '</ul>';
                    } else {
                        $posts = get_posts([
                            'post_type' => 'post',
                            'posts_per_page' => 5,
                            'post_status' => 'publish',
                            'meta_query' => [['key' => '_thumbnail_id', 'compare' => 'EXISTS']]
                        ]);
                        $debug_info .= '<p><strong>可用的带特色图片的文章数量：</strong>' . count($posts) . '</p>';
                    }
                }
                
                $debug_info .= '<p><strong>说明：</strong></p>
                    <ul>
                        <li><strong>智能轮播图</strong>：优先使用自定义配置，不足时自动补充文章</li>
                        <li><strong>自定义轮播图</strong>：严格按照用户配置显示</li>
                    </ul>
                    <p>如果轮播图未显示，请检查：</p>
                    <ul>
                        <li>是否已在首页设置中配置了自定义轮播图</li>
                        <li>配置的图片是否有效</li>
                        <li>如果指定了文章ID，文章是否存在且已发布</li>
                    </ul>
                    <p><a href="' . admin_url('themes.php?page=xinyun-theme-options#homepage-settings') . '">前往主题设置配置轮播图</a></p>
                </div>';
                
                return $debug_info;
            }
        }
        
        return $result;
    } catch (Exception $e) {
        if (current_user_can('manage_options')) {
            return '<div style="padding: 20px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px; margin-bottom: 20px;">
                <h3>❌ 轮播图错误</h3>
                <p>错误信息：' . esc_html($e->getMessage()) . '</p>
                <p><a href="' . admin_url('themes.php?page=xinyun-theme-options#homepage-settings') . '">前往主题设置检查配置</a></p>
            </div>';
        }
        return '';
    }
}

/**
 * 加载主题自定义器文件
 */
require get_template_directory() . '/inc/customizer.php';