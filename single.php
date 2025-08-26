<?php
/**
 * Xinyun Theme - 单篇文章模板
 *
 * 显示单篇文章的详细内容
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

                    <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
                        
                        <header class="entry-header" style="margin-bottom: 2rem;">
                            <h1 class="entry-title" style="margin-bottom: 1rem;">
                                <?php the_title(); ?>
                            </h1>
                            
                            <div class="entry-meta" style="font-size: 0.9rem; color: #666; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
                                <span class="posted-on">
                                    <time datetime="<?php echo get_the_date('c'); ?>">
                                        发布于 <?php echo get_the_date(); ?>
                                    </time>
                                </span>
                                
                                <span class="byline" style="margin-left: 1rem;">
                                    作者：<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" style="color: #007cba; text-decoration: none;">
                                        <?php the_author(); ?>
                                    </a>
                                </span>
                                
                                <?php if (has_category()) : ?>
                                    <span class="cat-links" style="margin-left: 1rem;">
                                        分类：<?php the_category(', '); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if (comments_open() || get_comments_number()) : ?>
                                    <span class="comments-link" style="margin-left: 1rem;">
                                        <a href="#comments" style="color: #007cba; text-decoration: none;">
                                            <?php comments_number('暂无评论', '1 条评论', '% 条评论'); ?>
                                        </a>
                                    </span>
                                <?php endif; ?>
                            </div>
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

                        <div class="entry-content" style="line-height: 1.8; margin-bottom: 2rem;">
                            <?php
                            the_content();

                            wp_link_pages(array(
                                'before' => '<div class="page-links" style="margin: 2rem 0; padding: 1rem; background: #f9f9f9; border-radius: 5px;">页面：',
                                'after'  => '</div>',
                            ));
                            ?>
                        </div>

                        <?php if (has_tag()) : ?>
                            <footer class="entry-footer" style="margin-bottom: 2rem; padding: 1rem; background: #f9f9f9; border-radius: 5px;">
                                <div class="tag-links">
                                    <strong>标签：</strong>
                                    <?php the_tags('', ', ', ''); ?>
                                </div>
                            </footer>
                        <?php endif; ?>

                        <?php
                        // 作者信息框
                        $author_description = get_the_author_meta('description');
                        if ($author_description) :
                        ?>
                            <div class="author-info" style="margin: 2rem 0; padding: 1.5rem; background: #f5f5f5; border-radius: 8px; border-left: 4px solid #007cba;">
                                <div class="author-avatar" style="float: left; margin-right: 1rem;">
                                    <?php echo get_avatar(get_the_author_meta('ID'), 80); ?>
                                </div>
                                <div class="author-description">
                                    <h4 style="margin: 0 0 0.5rem 0;">关于 <?php the_author(); ?></h4>
                                    <p style="margin: 0; line-height: 1.6;"><?php echo esc_html($author_description); ?></p>
                                    <p style="margin: 0.5rem 0 0 0;">
                                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" style="color: #007cba; text-decoration: none;">
                                            查看 <?php the_author(); ?> 的所有文章
                                        </a>
                                    </p>
                                </div>
                                <div style="clear: both;"></div>
                            </div>
                        <?php endif; ?>

                    </article>

                    <?php
                    // 文章导航
                    $prev_post = get_previous_post();
                    $next_post = get_next_post();
                    
                    if ($prev_post || $next_post) :
                    ?>
                        <nav class="post-navigation" style="margin: 2rem 0; padding: 1.5rem; background: #f9f9f9; border-radius: 8px;">
                            <h3 style="margin: 0 0 1rem 0; font-size: 1.1rem; color: #333;">相关文章</h3>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <?php if ($prev_post) : ?>
                                    <div class="nav-previous" style="text-align: left;">
                                        <span style="display: block; font-size: 0.8rem; color: #666; margin-bottom: 0.25rem;">上一篇</span>
                                        <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" style="color: #007cba; text-decoration: none; font-weight: 500;">
                                            ← <?php echo esc_html($prev_post->post_title); ?>
                                        </a>
                                    </div>
                                <?php else : ?>
                                    <div></div>
                                <?php endif; ?>
                                
                                <?php if ($next_post) : ?>
                                    <div class="nav-next" style="text-align: right;">
                                        <span style="display: block; font-size: 0.8rem; color: #666; margin-bottom: 0.25rem;">下一篇</span>
                                        <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" style="color: #007cba; text-decoration: none; font-weight: 500;">
                                            <?php echo esc_html($next_post->post_title); ?> →
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </nav>
                    <?php endif; ?>

                    <?php
                    // 相关文章
                    $related_posts = get_posts(array(
                        'category__in' => wp_get_post_categories($post->ID),
                        'numberposts'  => 3,
                        'post__not_in' => array($post->ID)
                    ));
                    
                    if ($related_posts) :
                    ?>
                        <section class="related-posts" style="margin: 2rem 0; padding: 1.5rem; background: #f9f9f9; border-radius: 8px;">
                            <h3 style="margin: 0 0 1.5rem 0; font-size: 1.2rem; color: #333;">相关文章推荐</h3>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                                <?php foreach ($related_posts as $related_post) : ?>
                                    <article style="background: #fff; padding: 1rem; border-radius: 5px; border: 1px solid #e1e1e1;">
                                        <?php if (has_post_thumbnail($related_post->ID)) : ?>
                                            <div style="margin-bottom: 0.75rem;">
                                                <a href="<?php echo esc_url(get_permalink($related_post->ID)); ?>">
                                                    <?php echo get_the_post_thumbnail($related_post->ID, 'thumbnail', array('style' => 'width: 100%; height: 120px; object-fit: cover; border-radius: 3px;')); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        <h4 style="margin: 0 0 0.5rem 0; font-size: 1rem;">
                                            <a href="<?php echo esc_url(get_permalink($related_post->ID)); ?>" style="color: #333; text-decoration: none;">
                                                <?php echo esc_html($related_post->post_title); ?>
                                            </a>
                                        </h4>
                                        <p style="margin: 0; font-size: 0.85rem; color: #666; line-height: 1.4;">
                                            <?php echo wp_trim_words($related_post->post_content, 15, '...'); ?>
                                        </p>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif; ?>

                    <?php
                    // 如果评论开放或存在评论，加载评论模板
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>

                <?php endwhile; ?>

            </main>

            <?php get_sidebar(); ?>
            
        </div>
    </div>
</div>

<?php get_footer(); ?>