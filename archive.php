<?php
/**
 * Xinyun Theme - 归档页面模板
 *
 * 显示分类、标签、作者等归档页面
 *
 * @package Xinyun
 * @since 1.0.0
 */

get_header(); ?>

<div class="site-content">
    <div class="container">
        <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
            <main class="content-area" style="flex: 1; min-width: 300px;">
                
                <?php if (have_posts()) : ?>

                    <header class="page-header" style="margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid #007cba;">
                        <?php
                        the_archive_title('<h1 class="page-title" style="margin: 0 0 0.5rem 0; color: #333;">', '</h1>');
                        the_archive_description('<div class="archive-description" style="color: #666; line-height: 1.6;">', '</div>');
                        ?>
                        
                        <?php if (is_category() || is_tag() || is_author()) : ?>
                            <div class="archive-meta" style="margin-top: 1rem; font-size: 0.9rem; color: #666;">
                                <?php if (is_category()) : ?>
                                    <span>分类归档 - 共 <?php echo esc_html(get_category(get_query_var('cat'))->count); ?> 篇文章</span>
                                <?php elseif (is_tag()) : ?>
                                    <span>标签归档 - 共 <?php echo esc_html(get_queried_object()->count); ?> 篇文章</span>
                                <?php elseif (is_author()) : ?>
                                    <span>作者归档 - <?php the_author(); ?> 的所有文章</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </header>

                    <div class="archive-posts">
                        <?php while (have_posts()) : the_post(); ?>
                            
                            <article id="post-<?php the_ID(); ?>" <?php post_class('archive-post'); ?> style="margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid #eee;">
                                
                                <div style="display: flex; gap: 1.5rem; align-items: flex-start;">
                                    
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="post-thumbnail" style="flex-shrink: 0;">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('medium', array('style' => 'width: 200px; height: 150px; object-fit: cover; border-radius: 5px;')); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                    <div class="post-content" style="flex: 1;">
                                        <header class="entry-header">
                                            <h2 class="entry-title" style="margin: 0 0 0.75rem 0; font-size: 1.5rem;">
                                                <a href="<?php the_permalink(); ?>" style="color: #333; text-decoration: none;">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h2>
                                            
                                            <div class="entry-meta" style="font-size: 0.85rem; color: #666; margin-bottom: 1rem;">
                                                <span class="posted-on">
                                                    <time datetime="<?php echo get_the_date('c'); ?>">
                                                        <?php echo get_the_date(); ?>
                                                    </time>
                                                </span>
                                                
                                                <span class="byline" style="margin-left: 1rem;">
                                                    作者：<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" style="color: #007cba; text-decoration: none;">
                                                        <?php the_author(); ?>
                                                    </a>
                                                </span>
                                                
                                                <?php if (has_category() && !is_category()) : ?>
                                                    <span class="cat-links" style="margin-left: 1rem;">
                                                        分类：<?php the_category(', '); ?>
                                                    </span>
                                                <?php endif; ?>
                                                
                                                <?php if (comments_open() || get_comments_number()) : ?>
                                                    <span class="comments-link" style="margin-left: 1rem;">
                                                        <a href="<?php comments_link(); ?>" style="color: #007cba; text-decoration: none;">
                                                            <?php comments_number('暂无评论', '1 条评论', '% 条评论'); ?>
                                                        </a>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </header>

                                        <div class="entry-summary" style="line-height: 1.6; color: #555;">
                                            <?php the_excerpt(); ?>
                                        </div>

                                        <div class="entry-footer" style="margin-top: 1rem;">
                                            <a href="<?php the_permalink(); ?>" class="btn" style="display: inline-block; padding: 0.5rem 1rem; background: #007cba; color: #fff; text-decoration: none; border-radius: 4px; font-size: 0.9rem;">
                                                阅读全文
                                            </a>
                                            
                                            <?php if (has_tag() && !is_tag()) : ?>
                                                <div class="tag-links" style="margin-top: 0.75rem; font-size: 0.85rem;">
                                                    <span style="color: #666;">标签：</span>
                                                    <?php the_tags('', ', ', ''); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                </div>

                            </article>

                        <?php endwhile; ?>
                    </div>

                    <?php
                    // 分页导航
                    $prev_link = get_previous_posts_link('← 较新文章');
                    $next_link = get_next_posts_link('较旧文章 →');
                    
                    if ($prev_link || $next_link) :
                    ?>
                        <nav class="pagination" style="margin: 2rem 0; padding: 1.5rem; background: #f9f9f9; border-radius: 8px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div><?php echo $next_link; ?></div>
                                <div class="page-numbers" style="font-size: 0.9rem; color: #666;">
                                    <?php
                                    global $wp_query;
                                    $current_page = max(1, get_query_var('paged'));
                                    $total_pages = $wp_query->max_num_pages;
                                    printf('第 %d 页，共 %d 页', $current_page, $total_pages);
                                    ?>
                                </div>
                                <div><?php echo $prev_link; ?></div>
                            </div>
                        </nav>
                    <?php endif; ?>

                <?php else : ?>

                    <section class="no-results not-found">
                        <header class="page-header" style="text-align: center; padding: 3rem 0;">
                            <h1 class="page-title" style="margin-bottom: 1rem;">没有找到内容</h1>
                        </header>

                        <div class="page-content" style="text-align: center;">
                            <?php if (is_category()) : ?>
                                <p>此分类目前还没有文章。</p>
                            <?php elseif (is_tag()) : ?>
                                <p>此标签目前还没有相关文章。</p>
                            <?php elseif (is_author()) : ?>
                                <p>此作者还没有发布任何文章。</p>
                            <?php elseif (is_date()) : ?>
                                <p>此时间段内没有发布文章。</p>
                            <?php else : ?>
                                <p>没有找到符合条件的内容。</p>
                            <?php endif; ?>
                            
                            <div style="margin-top: 2rem;">
                                <?php get_search_form(); ?>
                            </div>
                            
                            <p style="margin-top: 1rem;">
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn">
                                    返回首页
                                </a>
                            </p>
                        </div>
                    </section>

                <?php endif; ?>

            </main>

            <?php get_sidebar(); ?>
            
        </div>
    </div>
</div>

<style>
/* Archive specific styles */
.archive-post:hover {
    background: #fafafa;
    transition: background 0.3s ease;
}

.archive-post .entry-title a:hover {
    color: #007cba;
}

.archive-post .post-thumbnail img {
    transition: transform 0.3s ease;
}

.archive-post .post-thumbnail:hover img {
    transform: scale(1.05);
}

.pagination a {
    color: #007cba;
    text-decoration: none;
    font-weight: 500;
}

.pagination a:hover {
    color: #005a87;
}

@media (max-width: 768px) {
    .archive-post > div {
        flex-direction: column;
    }
    
    .archive-post .post-thumbnail {
        width: 100%;
    }
    
    .archive-post .post-thumbnail img {
        width: 100% !important;
        height: 200px !important;
    }
}
</style>

<?php get_footer(); ?>