<?php
/**
 * Xinyun Theme - 侧边栏模板
 *
 * 显示主要的侧边栏小工具区域
 *
 * @package Xinyun
 * @since 1.0.0
 */

if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<aside id="secondary" class="widget-area" role="complementary">
    
    <?php dynamic_sidebar('sidebar-1'); ?>

    <?php if (!dynamic_sidebar('sidebar-1')) : ?>
        
        <!-- 默认小工具 - 当没有添加小工具时显示 -->
        <section class="widget widget_search">
            <h3 class="widget-title">搜索</h3>
            <?php get_search_form(); ?>
        </section>

        <section class="widget widget_recent_entries">
            <h3 class="widget-title">最新文章</h3>
            <ul>
                <?php
                $recent_posts = wp_get_recent_posts(array(
                    'numberposts' => 5,
                    'post_status' => 'publish'
                ));
                
                foreach ($recent_posts as $post) :
                ?>
                    <li>
                        <a href="<?php echo esc_url(get_permalink($post['ID'])); ?>">
                            <?php echo esc_html($post['post_title']); ?>
                        </a>
                        <span class="post-date">
                            <?php echo get_the_date('Y-m-d', $post['ID']); ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="widget widget_recent_comments">
            <h3 class="widget-title">最新评论</h3>
            <ul id="recentcomments">
                <?php
                $recent_comments = get_comments(array(
                    'number' => 5,
                    'status' => 'approve'
                ));
                
                foreach ($recent_comments as $comment) :
                ?>
                    <li class="recentcomments">
                        <span class="comment-author-link">
                            <?php echo esc_html($comment->comment_author); ?>
                        </span>
                        发表在
                        <a href="<?php echo esc_url(get_permalink($comment->comment_post_ID)); ?>">
                            <?php echo esc_html(get_the_title($comment->comment_post_ID)); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="widget widget_archive">
            <h3 class="widget-title">文章归档</h3>
            <ul>
                <?php wp_get_archives(array(
                    'type' => 'monthly',
                    'limit' => 12
                )); ?>
            </ul>
        </section>

        <section class="widget widget_categories">
            <h3 class="widget-title">分类目录</h3>
            <ul>
                <?php wp_list_categories(array(
                    'orderby' => 'count',
                    'order' => 'DESC',
                    'show_count' => 1,
                    'title_li' => '',
                    'number' => 10
                )); ?>
            </ul>
        </section>

        <section class="widget widget_tag_cloud">
            <h3 class="widget-title">标签云</h3>
            <div class="tagcloud">
                <?php wp_tag_cloud(array(
                    'smallest' => 0.8,
                    'largest' => 1.2,
                    'unit' => 'rem',
                    'number' => 20
                )); ?>
            </div>
        </section>

        <section class="widget widget_meta">
            <h3 class="widget-title">功能</h3>
            <ul>
                <?php wp_register(); ?>
                <li><?php wp_loginout(); ?></li>
                <li><a href="<?php echo esc_url(get_bloginfo('rss2_url')); ?>">文章RSS</a></li>
                <li><a href="<?php echo esc_url(get_bloginfo('comments_rss2_url')); ?>">评论RSS</a></li>
                <?php wp_meta(); ?>
            </ul>
        </section>

    <?php endif; ?>

</aside><!-- #secondary -->

<style>
/* Sidebar specific styles */
.widget-area {
    font-size: 0.9rem;
}

.widget-area .widget {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #f9f9f9;
    border-radius: 8px;
    border-left: 4px solid #007cba;
}

.widget-area .widget:last-child {
    margin-bottom: 0;
}

.widget-area .widget-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #333;
    border-bottom: 1px solid #e1e1e1;
    padding-bottom: 0.5rem;
}

.widget-area .widget ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.widget-area .widget ul li {
    margin-bottom: 0.75rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #e8e8e8;
}

.widget-area .widget ul li:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.widget-area .widget a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
}

.widget-area .widget a:hover {
    color: #007cba;
}

.widget-area .post-date {
    display: block;
    font-size: 0.8rem;
    color: #666;
    margin-top: 0.25rem;
}

.widget-area .tagcloud a {
    display: inline-block;
    margin: 0.25rem 0.5rem 0.25rem 0;
    padding: 0.25rem 0.75rem;
    background: #007cba;
    color: #fff;
    border-radius: 15px;
    font-size: 0.8rem !important;
    text-decoration: none;
    transition: background 0.3s ease;
}

.widget-area .tagcloud a:hover {
    background: #005a87;
}

.widget-area .search-form {
    display: flex;
    gap: 0.5rem;
}

.widget-area .search-field {
    flex: 1;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.widget-area .search-submit {
    padding: 0.5rem 1rem;
    background: #007cba;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.widget-area .search-submit:hover {
    background: #005a87;
}

/* Category count styling */
.widget-area .widget_categories a {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.widget-area .widget_categories .count {
    background: #007cba;
    color: #fff;
    padding: 0.125rem 0.5rem;
    border-radius: 10px;
    font-size: 0.75rem;
}

/* Recent comments styling */
.widget-area .recentcomments {
    font-size: 0.85rem;
    line-height: 1.4;
}

.widget-area .comment-author-link {
    font-weight: 600;
    color: #007cba;
}

/* Archive widget styling */
.widget-area .widget_archive select {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #fff;
}

@media (max-width: 768px) {
    .widget-area {
        margin-top: 2rem;
        margin-left: 0;
    }
    
    .widget-area .widget {
        margin-bottom: 1.5rem;
    }
}
</style>