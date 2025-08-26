<?php
/**
 * Xinyun Theme - 头部模板
 *
 * 显示网站的头部区域
 *
 * @package Xinyun
 * @since 1.0.0
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content">跳到主内容</a>

    <header id="masthead" class="site-header">
        <div class="container">
            <div class="site-branding" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                
                <?php if (has_custom_logo()) : ?>
                    <div class="site-logo">
                        <?php the_custom_logo(); ?>
                    </div>
                <?php endif; ?>

                <div class="site-title-area">
                    <?php if (is_front_page() && is_home()) : ?>
                        <h1 class="site-title">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                <?php bloginfo('name'); ?>
                            </a>
                        </h1>
                    <?php else : ?>
                        <p class="site-title">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                <?php bloginfo('name'); ?>
                            </a>
                        </p>
                    <?php endif; ?>

                    <?php
                    $description = get_bloginfo('description', 'display');
                    if ($description || is_customize_preview()) :
                    ?>
                        <p class="site-description" style="margin: 0; color: #666; font-size: 0.9rem;">
                            <?php echo $description; ?>
                        </p>
                    <?php endif; ?>
                </div>

                <nav id="site-navigation" class="main-navigation" style="margin-left: auto;">
                    <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false" style="display: none; background: none; border: 1px solid #ddd; padding: 0.5rem; cursor: pointer;">
                        <span class="menu-toggle-text">菜单</span>
                    </button>
                    
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'menu_class'     => 'primary-menu',
                        'container'      => false,
                        'fallback_cb'    => 'xinyun_fallback_menu',
                    ));
                    ?>
                </nav>

            </div>
        </div>
    </header>

    <div id="content" class="site-content">

<?php
/**
 * 当没有设置菜单时的默认菜单
 */
function xinyun_fallback_menu() {
    echo '<ul class="primary-menu">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">首页</a></li>';
    
    // 显示最近的几个页面
    $pages = get_pages(array(
        'number' => 5,
        'sort_column' => 'menu_order',
    ));
    
    foreach ($pages as $page) {
        echo '<li><a href="' . esc_url(get_permalink($page->ID)) . '">' . esc_html($page->post_title) . '</a></li>';
    }
    
    echo '</ul>';
}
?>