<?php
/**
 * Xinyun Theme - 页面模板
 *
 * 显示静态页面内容
 *
 * @package Xinyun
 * @since 1.0.0
 */

get_header(); ?>

<div class="site-content">
    <div class="container">
        <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
            <main class="content-area" style="flex: 1; min-width: 300px;">
                
                <?php while (have_posts()) : the_post(); ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>
                        
                        <header class="entry-header" style="margin-bottom: 2rem;">
                            <h1 class="entry-title" style="margin-bottom: 1rem;">
                                <?php the_title(); ?>
                            </h1>
                            
                            <?php if (get_edit_post_link()) : ?>
                                <div class="entry-meta" style="font-size: 0.9rem; color: #666;">
                                    <a href="<?php echo esc_url(get_edit_post_link()); ?>" style="color: #007cba; text-decoration: none;">
                                        编辑此页面
                                    </a>
                                </div>
                            <?php endif; ?>
                        </header>

                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail" style="margin-bottom: 2rem;">
                                <?php the_post_thumbnail('large', array('style' => 'width: 100%; height: auto; border-radius: 8px;')); ?>
                                <?php 
                                $caption = get_the_post_thumbnail_caption();
                                if ($caption) :
                                ?>
                                    <p class="wp-caption-text" style="text-align: center; font-style: italic; color: #666; margin-top: 0.5rem;">
                                        <?php echo esc_html($caption); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <div class="entry-content" style="line-height: 1.8;">
                            <?php
                            the_content();

                            wp_link_pages(array(
                                'before' => '<div class="page-links" style="margin: 2rem 0; padding: 1rem; background: #f9f9f9; border-radius: 5px;">页面：',
                                'after'  => '</div>',
                            ));
                            ?>
                        </div>

                        <?php if (comments_open() || get_comments_number()) : ?>
                            <footer class="entry-footer" style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #eee;">
                                <?php comments_template(); ?>
                            </footer>
                        <?php endif; ?>

                    </article>

                <?php endwhile; ?>

            </main>

            <?php 
            // 只在特定页面显示侧边栏
            if (is_active_sidebar('sidebar-1') && !is_page_template('page-full-width.php')) :
                get_sidebar();
            endif;
            ?>
            
        </div>
    </div>
</div>

<?php get_footer(); ?>