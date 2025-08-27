<?php
/**
 * Template part for header content
 *
 * @package Xinyun
 * @since 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="site-branding">
    <?php if (has_custom_logo()) : ?>
        <div class="site-logo">
            <?php the_custom_logo(); ?>
        </div>
    <?php endif; ?>
    
    <div class="site-identity">
        <?php if (is_front_page() && is_home()) : ?>
            <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
        <?php else : ?>
            <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
        <?php endif; ?>
        
        <?php
        $description = get_bloginfo('description', 'display');
        if ($description || is_customize_preview()) : ?>
            <p class="site-description"><?php echo $description; ?></p>
        <?php endif; ?>
    </div>
</div>

<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php _e('Primary Menu', 'xinyun'); ?>">
    <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
        <span class="menu-toggle-text"><?php _e('Menu', 'xinyun'); ?></span>
        <span class="menu-toggle-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </button>
    
    <?php
    wp_nav_menu(array(
        'theme_location' => 'primary',
        'menu_id'        => 'primary-menu',
        'menu_class'     => 'nav-menu',
        'fallback_cb'    => false,
    ));
    ?>
</nav>

<?php if (get_theme_mod('xinyun_show_search', true)) : ?>
<div class="header-search">
    <button class="search-toggle" aria-expanded="false">
        <span class="screen-reader-text"><?php _e('Search', 'xinyun'); ?></span>
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M19 19L13 13L19 19ZM15 8C15 11.866 11.866 15 8 15C4.134 15 1 11.866 1 8C1 4.134 4.134 1 8 1C11.866 1 15 4.134 15 8Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>
    <div class="header-search-form">
        <?php get_search_form(); ?>
    </div>
</div>
<?php endif; ?>