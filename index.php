<?php
/**
 * Xinyun Theme - 主模板文件
 *
 * 这是最重要的模板文件。它显示首页和其他页面。
 *
 * @package Xinyun
 * @since 1.0.0
 */

get_header(); ?>

<div class="site-content">
    <div class="container">
        <?php if (is_home() || is_front_page()) : ?>
            <!-- 首页：全宽布局，无侧边栏 -->
            <main class="content-area" style="max-width: 800px; margin: 0 auto;">
        <?php else : ?>
            <!-- 其他页面：包含侧边栏的布局 -->
            <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
                <main class="content-area" style="flex: 1; min-width: 300px;">
        <?php endif; ?>
                
                <?php if (have_posts()) : ?>
                    
                    <?php if (is_home() && !is_front_page()) : ?>
                        <header class="page-header">
                            <h1 class="page-title"><?php single_post_title(); ?></h1>
                        </header>
                    <?php endif; ?>

                    <?php while (have_posts()) : the_post(); ?>
                        
                        <article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
                            
                            <header class="entry-header">
                                <?php
                                if (is_singular()) :
                                    the_title('<h1 class="entry-title">', '</h1>');
                                else :
                                    the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                                endif;
                                ?>
                                
                                <?php if ('post' === get_post_type()) : ?>
                                    <div class="entry-meta">
                                        <span class="posted-on">
                                            发布于 <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                                        </span>
                                        <span class="byline">
                                            作者：<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php the_author(); ?></a>
                                        </span>
                                        <?php if (has_category()) : ?>
                                            <span class="cat-links">
                                                分类：<?php the_category(', '); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </header>

                            <?php if (has_post_thumbnail() && !is_singular()) : ?>
                                <div class="post-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('large', array('style' => 'width: 100%; height: auto; border-radius: 5px;')); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <div class="entry-content">
                                <?php
                                if (is_singular()) :
                                    the_content();
                                else :
                                    the_excerpt();
                                    echo '<p><a href="' . esc_url(get_permalink()) . '" class="btn">阅读更多</a></p>';
                                endif;

                                wp_link_pages(array(
                                    'before' => '<div class="page-links">页面：',
                                    'after'  => '</div>',
                                ));
                                ?>
                            </div>

                            <?php if (is_singular() && has_tag()) : ?>
                                <footer class="entry-footer">
                                    <div class="tag-links">
                                        标签：<?php the_tags('', ', ', ''); ?>
                                    </div>
                                </footer>
                            <?php endif; ?>

                        </article>

                        <?php if (is_singular()) : ?>
                            <?php
                            // 文章导航
                            $prev_post = get_previous_post();
                            $next_post = get_next_post();
                            
                            if ($prev_post || $next_post) :
                            ?>
                                <nav class="post-navigation" style="margin: 2rem 0; padding: 1rem; background: #f9f9f9; border-radius: 5px;">
                                    <div style="display: flex; justify-content: space-between;">
                                        <?php if ($prev_post) : ?>
                                            <div class="nav-previous">
                                                <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>">
                                                    ← <?php echo esc_html($prev_post->post_title); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($next_post) : ?>
                                            <div class="nav-next">
                                                <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>">
                                                    <?php echo esc_html($next_post->post_title); ?> →
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </nav>
                            <?php endif; ?>

                            <?php
                            // 如果评论开放或存在评论，加载评论模板
                            if (comments_open() || get_comments_number()) :
                                comments_template();
                            endif;
                            ?>
                        <?php endif; ?>

                    <?php endwhile; ?>

                    <?php
                    // 分页导航
                    if (!is_singular()) :
                        $prev_link = get_previous_posts_link('← 较新文章');
                        $next_link = get_next_posts_link('较旧文章 →');
                        
                        if ($prev_link || $next_link) :
                    ?>
                        <nav class="pagination" style="margin: 2rem 0; text-align: center;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div><?php echo $next_link; ?></div>
                                <div><?php echo $prev_link; ?></div>
                            </div>
                        </nav>
                    <?php 
                        endif;
                    endif; 
                    ?>

                <?php else : ?>

                    <section class="no-results not-found">
                        <header class="page-header">
                            <h1 class="page-title">没有找到内容</h1>
                        </header>

                        <div class="page-content">
                            <?php if (is_home() && current_user_can('publish_posts')) : ?>
                                <p>准备好发布您的第一篇文章了吗？<a href="<?php echo esc_url(admin_url('post-new.php')); ?>">开始吧</a>。</p>
                            <?php elseif (is_search()) : ?>
                                <p>抱歉，没有找到符合您搜索条件的内容。请尝试其他关键词。</p>
                                <?php get_search_form(); ?>
                            <?php else : ?>
                                <p>看起来我们无法找到您要查找的内容。也许搜索能帮助您。</p>
                                <?php get_search_form(); ?>
                            <?php endif; ?>
                        </div>
                    </section>

                <?php endif; ?>

            </main>

            <?php if (!(is_home() || is_front_page())) : ?>
                <?php get_sidebar(); ?>
            </div>
            <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>