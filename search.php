<?php
/**
 * Xinyun Theme - 搜索结果页面模板
 *
 * 显示搜索结果
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

                    <header class="page-header" style="margin-bottom: 2rem; padding: 1.5rem; background: #f9f9f9; border-radius: 8px; border-left: 4px solid #007cba;">
                        <h1 class="page-title" style="margin: 0 0 0.5rem 0; color: #333;">
                            搜索结果
                        </h1>
                        <p style="margin: 0; color: #666; font-size: 1.1rem;">
                            关键词："<strong style="color: #007cba;"><?php echo get_search_query(); ?></strong>"
                            <?php
                            global $wp_query;
                            $total_results = $wp_query->found_posts;
                            printf(' - 找到 %d 个结果', $total_results);
                            ?>
                        </p>
                        
                        <div style="margin-top: 1.5rem;">
                            <p style="margin: 0 0 0.75rem 0; font-size: 0.9rem; color: #666;">重新搜索：</p>
                            <?php get_search_form(); ?>
                        </div>
                    </header>

                    <div class="search-results">
                        <?php $result_count = 0; ?>
                        <?php while (have_posts()) : the_post(); ?>
                            <?php $result_count++; ?>
                            
                            <article id="post-<?php the_ID(); ?>" <?php post_class('search-result'); ?> style="margin-bottom: 2rem; padding: 1.5rem; background: #fff; border: 1px solid #e1e1e1; border-radius: 8px;">
                                
                                <header class="entry-header">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                                        <span class="result-number" style="background: #007cba; color: #fff; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 500;">
                                            #<?php echo $result_count; ?>
                                        </span>
                                        <span class="post-type" style="background: #f0f0f0; color: #666; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem;">
                                            <?php 
                                            $post_type_obj = get_post_type_object(get_post_type());
                                            echo $post_type_obj ? $post_type_obj->labels->singular_name : '内容';
                                            ?>
                                        </span>
                                    </div>
                                    
                                    <h2 class="entry-title" style="margin: 0 0 0.75rem 0; font-size: 1.5rem;">
                                        <a href="<?php the_permalink(); ?>" style="color: #333; text-decoration: none;">
                                            <?php 
                                            $title = get_the_title();
                                            $search_query = get_search_query();
                                            if ($search_query) {
                                                $title = preg_replace('/(' . preg_quote($search_query, '/') . ')/i', '<mark style="background: #ffeb3b; padding: 0.125rem 0.25rem;">$1</mark>', $title);
                                            }
                                            echo $title;
                                            ?>
                                        </a>
                                    </h2>
                                    
                                    <div class="entry-meta" style="font-size: 0.85rem; color: #666; margin-bottom: 1rem;">
                                        <span class="posted-on">
                                            <time datetime="<?php echo get_the_date('c'); ?>">
                                                <?php echo get_the_date(); ?>
                                            </time>
                                        </span>
                                        
                                        <?php if (get_post_type() === 'post') : ?>
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
                                        <?php endif; ?>
                                        
                                        <span class="post-url" style="margin-left: 1rem; color: #999;">
                                            <?php echo esc_url(get_permalink()); ?>
                                        </span>
                                    </div>
                                </header>

                                <div class="entry-summary" style="line-height: 1.6; color: #555; margin-bottom: 1rem;">
                                    <?php 
                                    $excerpt = get_the_excerpt();
                                    $search_query = get_search_query();
                                    if ($search_query) {
                                        $excerpt = preg_replace('/(' . preg_quote($search_query, '/') . ')/i', '<mark style="background: #ffeb3b; padding: 0.125rem 0.25rem;">$1</mark>', $excerpt);
                                    }
                                    echo $excerpt;
                                    ?>
                                </div>

                                <div class="entry-footer" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                                    <a href="<?php the_permalink(); ?>" class="btn" style="display: inline-block; padding: 0.5rem 1rem; background: #007cba; color: #fff; text-decoration: none; border-radius: 4px; font-size: 0.9rem;">
                                        查看详情
                                    </a>
                                    
                                    <?php if (has_tag() && get_post_type() === 'post') : ?>
                                        <div class="tag-links" style="font-size: 0.85rem;">
                                            <span style="color: #666;">标签：</span>
                                            <?php the_tags('', ', ', ''); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </article>

                        <?php endwhile; ?>
                    </div>

                    <?php
                    // 分页导航
                    $prev_link = get_previous_posts_link('← 上一页');
                    $next_link = get_next_posts_link('下一页 →');
                    
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
                        <header class="page-header" style="text-align: center; padding: 3rem; background: #f9f9f9; border-radius: 8px; margin-bottom: 2rem;">
                            <div style="font-size: 4rem; margin-bottom: 1rem;">🔍</div>
                            <h1 class="page-title" style="margin-bottom: 1rem; color: #333;">未找到搜索结果</h1>
                            <p style="font-size: 1.1rem; color: #666; margin: 0;">
                                抱歉，没有找到与 "<strong style="color: #007cba;"><?php echo get_search_query(); ?></strong>" 相关的内容。
                            </p>
                        </header>

                        <div class="page-content">
                            
                            <div class="search-suggestions" style="margin-bottom: 3rem;">
                                <h3 style="text-align: center; margin-bottom: 2rem; color: #333;">搜索建议</h3>
                                
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                                    
                                    <div class="suggestion-card" style="padding: 1.5rem; background: #fff; border: 1px solid #e1e1e1; border-radius: 8px;">
                                        <h4 style="margin-bottom: 1rem; color: #007cba;">💡 尝试其他关键词</h4>
                                        <ul style="list-style: none; padding: 0; margin: 0; color: #666;">
                                            <li style="margin-bottom: 0.5rem;">• 使用更简单的词语</li>
                                            <li style="margin-bottom: 0.5rem;">• 检查拼写是否正确</li>
                                            <li style="margin-bottom: 0.5rem;">• 尝试同义词或相关词</li>
                                        </ul>
                                    </div>

                                    <div class="suggestion-card" style="padding: 1.5rem; background: #fff; border: 1px solid #e1e1e1; border-radius: 8px;">
                                        <h4 style="margin-bottom: 1rem; color: #007cba;">🔍 搜索技巧</h4>
                                        <ul style="list-style: none; padding: 0; margin: 0; color: #666;">
                                            <li style="margin-bottom: 0.5rem;">• 使用引号搜索完整短语</li>
                                            <li style="margin-bottom: 0.5rem;">• 减少搜索关键词</li>
                                            <li style="margin-bottom: 0.5rem;">• 尝试更通用的术语</li>
                                        </ul>
                                    </div>

                                    <div class="suggestion-card" style="padding: 1.5rem; background: #fff; border: 1px solid #e1e1e1; border-radius: 8px;">
                                        <h4 style="margin-bottom: 1rem; color: #007cba;">📂 浏览内容</h4>
                                        <ul style="list-style: none; padding: 0; margin: 0;">
                                            <li style="margin-bottom: 0.75rem;">
                                                <a href="<?php echo esc_url(home_url('/')); ?>" style="color: #007cba; text-decoration: none;">• 返回首页</a>
                                            </li>
                                            <li style="margin-bottom: 0.75rem;">
                                                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" style="color: #007cba; text-decoration: none;">• 浏览所有文章</a>
                                            </li>
                                            <li style="margin-bottom: 0.75rem;">
                                                <a href="#" onclick="window.history.back(); return false;" style="color: #007cba; text-decoration: none;">• 返回上一页</a>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                            </div>

                            <div class="retry-search" style="text-align: center; padding: 2rem; background: #f0f8ff; border-radius: 8px; border: 1px solid #007cba;">
                                <h3 style="margin-bottom: 1rem; color: #333;">重新搜索</h3>
                                <?php get_search_form(); ?>
                            </div>

                            <?php
                            // 显示热门文章作为推荐
                            $popular_posts = get_posts(array(
                                'numberposts' => 6,
                                'meta_key' => 'post_views_count',
                                'orderby' => 'meta_value_num',
                                'order' => 'DESC'
                            ));
                            
                            if (empty($popular_posts)) {
                                $popular_posts = get_posts(array(
                                    'numberposts' => 6,
                                    'orderby' => 'comment_count',
                                    'order' => 'DESC'
                                ));
                            }
                            
                            if ($popular_posts) :
                            ?>
                                <div class="popular-posts" style="margin-top: 3rem;">
                                    <h3 style="text-align: center; margin-bottom: 2rem; color: #333;">热门文章推荐</h3>
                                    
                                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                                        <?php foreach ($popular_posts as $post) : ?>
                                            <article style="background: #fff; border: 1px solid #e1e1e1; border-radius: 8px; overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                                                
                                                <?php if (has_post_thumbnail($post->ID)) : ?>
                                                    <div class="post-thumbnail">
                                                        <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">
                                                            <?php echo get_the_post_thumbnail($post->ID, 'medium', array('style' => 'width: 100%; height: 150px; object-fit: cover;')); ?>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <div style="padding: 1.25rem;">
                                                    <h4 style="margin: 0 0 0.75rem 0; font-size: 1.1rem;">
                                                        <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" style="color: #333; text-decoration: none;">
                                                            <?php echo esc_html($post->post_title); ?>
                                                        </a>
                                                    </h4>
                                                    
                                                    <p style="margin: 0 0 1rem 0; color: #666; font-size: 0.9rem; line-height: 1.5;">
                                                        <?php echo wp_trim_words($post->post_content, 15, '...'); ?>
                                                    </p>
                                                    
                                                    <div style="font-size: 0.8rem; color: #999;">
                                                        <?php echo get_the_date('Y-m-d', $post->ID); ?>
                                                    </div>
                                                </div>
                                            </article>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    </section>

                <?php endif; ?>

            </main>

            <?php get_sidebar(); ?>
            
        </div>
    </div>
</div>

<style>
/* Search results specific styles */
.search-result:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.search-result .entry-title a:hover {
    color: #007cba;
}

.search-form {
    display: flex;
    gap: 0.5rem;
    max-width: 400px;
    margin: 0 auto;
}

.search-field {
    flex: 1;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

.search-submit {
    padding: 0.75rem 1.5rem;
    background: #007cba;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    transition: background 0.3s ease;
}

.search-submit:hover {
    background: #005a87;
}

.suggestion-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.popular-posts article:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
}

.popular-posts article a:hover {
    color: #007cba;
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
    .search-form {
        flex-direction: column;
    }
    
    .entry-footer {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<?php get_footer(); ?>