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
    add_editor_style('assets/css/editor-style.css');

    // 启用响应式嵌入
    add_theme_support('responsive-embeds');
    
    // 确保admin bar正常工作
    add_theme_support('admin-bar', array('callback' => '__return_false'));

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
    // 基础样式文件（最先加载）
    wp_enqueue_style('xinyun-base-style', get_template_directory_uri() . '/assets/css/style.css', array(), XINYUN_VERSION);
    
    // 主样式文件（通过根目录的style.css引入）
    wp_enqueue_style('xinyun-style', get_stylesheet_uri(), array('xinyun-base-style'), XINYUN_VERSION);
    
    // Tailwind CSS (通过Vite构建) - 最后加载，确保优先级最高
    wp_enqueue_style('xinyun-tailwind', get_template_directory_uri() . '/dist/main.css', array('xinyun-style'), XINYUN_VERSION);
    
    // Vite构建的JavaScript文件
    wp_enqueue_script('xinyun-vite-js', get_template_directory_uri() . '/dist/js.js', array(), XINYUN_VERSION, true);
    
    // 主JavaScript文件
    wp_enqueue_script('xinyun-main', get_template_directory_uri() . '/assets/js/main.js', array('xinyun-vite-js'), XINYUN_VERSION, true);
    
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
        <div class="comment-header">
            <div class="comment-avatar">
                <?php echo get_avatar($comment, $args['avatar_size']); ?>
            </div>
            <div class="comment-main">
                <div class="comment-author-name">
                    <?php printf('<cite class="fn">%s</cite>', get_comment_author_link()); ?>
                </div>
                <div class="comment-content">
                    <?php comment_text(); ?>
                </div>
                <div class="comment-meta">
                    <div class="comment-time">
                        <a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
                            <time datetime="<?php comment_time('c'); ?>">
                                <?php printf('%1$s %2$s', get_comment_date(), get_comment_time()); ?>
                            </time>
                        </a>
                        <?php edit_comment_link('编辑', '<span class="edit-link">', '</span>'); ?>
                    </div>
                    <div class="comment-reply">
                        <?php
                        comment_reply_link(array_merge($args, array(
                            'add_below' => 'div-comment',
                            'depth'     => $depth,
                            'max_depth' => $args['max_depth'],
                        )));
                        ?>
                    </div>
                </div>
            </div>
        </div>


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
        return $result;
    } catch (Exception $e) {
        return '';
    }
}

/**
 * 加载主题自定义器文件
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * 设置文章阅读量
 */
function set_post_views($postID) {
    $count_key = 'views';
    $count = get_post_meta($postID, $count_key, true);
    if($count == ''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

/**
 * 在访问单篇文章时增加阅读量
 */
function track_post_views ($post_id) {
    if ( !is_single() ) return;
    if ( empty ( $post_id) ) {
        global $post;
        $post_id = $post->ID;    
    }
    set_post_views($post_id);
}
add_action( 'wp_head', 'track_post_views');
