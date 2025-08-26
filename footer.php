<?php
/**
 * Xinyun Theme - 底部模板
 *
 * 显示网站的底部区域
 *
 * @package Xinyun
 * @since 1.0.0
 */
?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        
        <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3')) : ?>
            <div class="footer-widgets">
                <div class="container">
                    <div class="footer-widget-area" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; padding: 2rem 0;">
                        
                        <?php if (is_active_sidebar('footer-1')) : ?>
                            <div class="footer-widget-column">
                                <?php dynamic_sidebar('footer-1'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (is_active_sidebar('footer-2')) : ?>
                            <div class="footer-widget-column">
                                <?php dynamic_sidebar('footer-2'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (is_active_sidebar('footer-3')) : ?>
                            <div class="footer-widget-column">
                                <?php dynamic_sidebar('footer-3'); ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="site-info">
            <div class="container">
                <div class="footer-content" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; padding: 1.5rem 0; border-top: 1px solid #444;">
                    
                    <div class="copyright-info">
                        <p style="margin: 0; color: #ccc;">
                            &copy; <?php echo date('Y'); ?> 
                            <a href="<?php echo esc_url(home_url('/')); ?>" style="color: #fff; text-decoration: none;">
                                <?php bloginfo('name'); ?>
                            </a>
                            . 保留所有权利。
                        </p>
                    </div>

                    <?php if (has_nav_menu('footer')) : ?>
                        <nav class="footer-navigation">
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'footer',
                                'menu_id'        => 'footer-menu',
                                'menu_class'     => 'footer-menu',
                                'container'      => false,
                                'depth'          => 1,
                            ));
                            ?>
                        </nav>
                    <?php endif; ?>

                    <div class="theme-credit">
                        <p style="margin: 0; color: #999; font-size: 0.9rem;">
                            Powered by 
                            <a href="https://wordpress.org/" target="_blank" rel="noopener" style="color: #ccc; text-decoration: none;">
                                WordPress
                            </a>
                            | Theme: Xinyun
                        </p>
                    </div>

                </div>

                <?php if (is_user_logged_in() && current_user_can('manage_options')) : ?>
                    <div class="admin-links" style="text-align: center; padding: 1rem 0; border-top: 1px solid #444;">
                        <p style="margin: 0; font-size: 0.9rem;">
                            <a href="<?php echo esc_url(admin_url()); ?>" style="color: #ccc; text-decoration: none; margin-right: 1rem;">
                                管理后台
                            </a>
                            <a href="<?php echo esc_url(admin_url('customize.php')); ?>" style="color: #ccc; text-decoration: none; margin-right: 1rem;">
                                自定义
                            </a>
                            <a href="<?php echo esc_url(admin_url('widgets.php')); ?>" style="color: #ccc; text-decoration: none;">
                                小工具
                            </a>
                        </p>
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

<style>
/* Footer specific styles */
.site-footer {
    background: #333;
    color: #fff;
    margin-top: auto;
}

.footer-widgets .widget {
    background: transparent;
    color: #ccc;
}

.footer-widgets .widget-title {
    color: #fff;
    border-bottom: 2px solid #007cba;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
}

.footer-widgets .widget a {
    color: #ccc;
    text-decoration: none;
}

.footer-widgets .widget a:hover {
    color: #fff;
}

.footer-widgets .widget ul {
    list-style: none;
    padding: 0;
}

.footer-widgets .widget ul li {
    padding: 0.5rem 0;
    border-bottom: 1px solid #444;
}

.footer-widgets .widget ul li:last-child {
    border-bottom: none;
}

.footer-menu {
    list-style: none;
    display: flex;
    gap: 1.5rem;
    margin: 0;
    padding: 0;
}

.footer-menu a {
    color: #ccc;
    text-decoration: none;
    font-size: 0.9rem;
}

.footer-menu a:hover {
    color: #fff;
}

@media (max-width: 768px) {
    .footer-content {
        flex-direction: column;
        text-align: center;
    }
    
    .footer-menu {
        flex-wrap: wrap;
        justify-content: center;
    }
}
</style>

</body>
</html>