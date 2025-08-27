<?php
/**
 * Template part for footer content
 *
 * @package Xinyun
 * @since 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}
?>

<?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3')) : ?>
<div class="footer-widgets">
    <div class="site-container">
        <div class="footer-widgets-grid">
            <?php if (is_active_sidebar('footer-1')) : ?>
                <div class="footer-widget-area footer-widget-1">
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
            <?php endif; ?>
            
            <?php if (is_active_sidebar('footer-2')) : ?>
                <div class="footer-widget-area footer-widget-2">
                    <?php dynamic_sidebar('footer-2'); ?>
                </div>
            <?php endif; ?>
            
            <?php if (is_active_sidebar('footer-3')) : ?>
                <div class="footer-widget-area footer-widget-3">
                    <?php dynamic_sidebar('footer-3'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="site-info">
    <div class="site-container">
        <div class="site-info-content">
            <div class="copyright">
                <?php
                $copyright_text = get_theme_mod('xinyun_copyright_text');
                if ($copyright_text) :
                    echo wp_kses_post($copyright_text);
                else :
                    printf(
                        /* translators: %1$s: current year, %2$s: site name */
                        __('Copyright © %1$s %2$s. All rights reserved.', 'xinyun'),
                        date('Y'),
                        get_bloginfo('name')
                    );
                endif;
                ?>
            </div>
            
            <?php if (get_theme_mod('xinyun_show_theme_credit', true)) : ?>
                <div class="theme-credit">
                    <span><?php _e('Powered by', 'xinyun'); ?> <a href="https://wordpress.org" target="_blank" rel="nofollow">WordPress</a></span>
                    <span class="sep"> | </span>
                    <span><?php _e('Theme:', 'xinyun'); ?> <a href="#" target="_blank">Xinyun</a></span>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (has_nav_menu('footer')) : ?>
            <nav class="footer-navigation" role="navigation" aria-label="<?php _e('Footer Menu', 'xinyun'); ?>">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'menu_class'     => 'footer-menu',
                    'depth'          => 1,
                    'fallback_cb'    => false,
                ));
                ?>
            </nav>
        <?php endif; ?>
    </div>
</div>

<button class="scroll-to-top" aria-label="<?php _e('Scroll to top', 'xinyun'); ?>">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
        <path d="M7 14L12 9L17 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</button>