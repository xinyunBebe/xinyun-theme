<?php
/**
 * Xinyun Theme - 404错误页面模板
 *
 * 当页面未找到时显示的错误页面
 *
 * @package Xinyun
 * @since 1.0.0
 */

get_header(); ?>

<div class="site-content">
    <div class="container">
        <div style="display: flex; justify-content: center; align-items: center; min-height: 60vh;">
            
            <main class="content-area" style="max-width: 600px; text-align: center;">
                
                <section class="error-404 not-found">
                    
                    <header class="page-header" style="margin-bottom: 3rem;">
                        <div style="font-size: 8rem; font-weight: bold; color: #007cba; margin-bottom: 1rem; line-height: 1;">
                            404
                        </div>
                        <h1 class="page-title" style="font-size: 2.5rem; margin-bottom: 1rem; color: #333;">
                            页面未找到
                        </h1>
                        <p style="font-size: 1.2rem; color: #666; margin: 0;">
                            抱歉，您访问的页面不存在或已被移动。
                        </p>
                    </header>

                    <div class="page-content">
                        
                        <div class="search-section" style="margin: 2rem 0; padding: 2rem; background: #f9f9f9; border-radius: 8px;">
                            <h3 style="margin-bottom: 1rem; color: #333;">尝试搜索您需要的内容</h3>
                            <?php get_search_form(); ?>
                        </div>

                        <div class="helpful-links" style="margin: 2rem 0;">
                            <h3 style="margin-bottom: 1.5rem; color: #333;">或者访问以下页面</h3>
                            
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                                
                                <div class="link-card" style="padding: 1.5rem; background: #fff; border: 1px solid #e1e1e1; border-radius: 8px; text-align: center;">
                                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">🏠</div>
                                    <h4 style="margin-bottom: 0.5rem;">返回首页</h4>
                                    <p style="font-size: 0.9rem; color: #666; margin-bottom: 1rem;">浏览最新内容</p>
                                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn" style="display: inline-block; padding: 0.5rem 1rem; background: #007cba; color: #fff; text-decoration: none; border-radius: 4px;">
                                        首页
                                    </a>
                                </div>

                                <div class="link-card" style="padding: 1.5rem; background: #fff; border: 1px solid #e1e1e1; border-radius: 8px; text-align: center;">
                                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">📝</div>
                                    <h4 style="margin-bottom: 0.5rem;">最新文章</h4>
                                    <p style="font-size: 0.9rem; color: #666; margin-bottom: 1rem;">查看最新发布的内容</p>
                                    <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn" style="display: inline-block; padding: 0.5rem 1rem; background: #007cba; color: #fff; text-decoration: none; border-radius: 4px;">
                                        文章
                                    </a>
                                </div>

                                <div class="link-card" style="padding: 1.5rem; background: #fff; border: 1px solid #e1e1e1; border-radius: 8px; text-align: center;">
                                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">📋</div>
                                    <h4 style="margin-bottom: 0.5rem;">所有分类</h4>
                                    <p style="font-size: 0.9rem; color: #666; margin-bottom: 1rem;">按分类浏览内容</p>
                                    <a href="<?php echo esc_url(home_url('/categories')); ?>" class="btn" style="display: inline-block; padding: 0.5rem 1rem; background: #007cba; color: #fff; text-decoration: none; border-radius: 4px;">
                                        分类
                                    </a>
                                </div>

                            </div>
                        </div>

                        <?php
                        // 显示最新文章
                        $recent_posts = wp_get_recent_posts(array(
                            'numberposts' => 3,
                            'post_status' => 'publish'
                        ));
                        
                        if ($recent_posts) :
                        ?>
                            <div class="recent-posts-section" style="margin: 3rem 0; text-align: left;">
                                <h3 style="text-align: center; margin-bottom: 2rem; color: #333;">最新文章推荐</h3>
                                
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                                    <?php foreach ($recent_posts as $post) : ?>
                                        <article style="background: #fff; border: 1px solid #e1e1e1; border-radius: 8px; overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                                            
                                            <?php if (has_post_thumbnail($post['ID'])) : ?>
                                                <div class="post-thumbnail">
                                                    <a href="<?php echo esc_url(get_permalink($post['ID'])); ?>">
                                                        <?php echo get_the_post_thumbnail($post['ID'], 'medium', array('style' => 'width: 100%; height: 150px; object-fit: cover;')); ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div style="padding: 1.25rem;">
                                                <h4 style="margin: 0 0 0.75rem 0; font-size: 1.1rem;">
                                                    <a href="<?php echo esc_url(get_permalink($post['ID'])); ?>" style="color: #333; text-decoration: none;">
                                                        <?php echo esc_html($post['post_title']); ?>
                                                    </a>
                                                </h4>
                                                
                                                <p style="margin: 0 0 1rem 0; color: #666; font-size: 0.9rem; line-height: 1.5;">
                                                    <?php echo wp_trim_words($post['post_content'], 15, '...'); ?>
                                                </p>
                                                
                                                <div style="font-size: 0.8rem; color: #999;">
                                                    <?php echo get_the_date('Y-m-d', $post['ID']); ?>
                                                </div>
                                            </div>
                                        </article>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div style="margin: 3rem 0; text-align: center;">
                            <p style="color: #666; margin-bottom: 1rem;">
                                如果您认为这是一个错误，请联系网站管理员。
                            </p>
                            
                            <?php if (is_user_logged_in() && current_user_can('manage_options')) : ?>
                                <p style="font-size: 0.9rem; color: #999;">
                                    管理员提示：请检查 .htaccess 文件和永久链接设置。
                                </p>
                            <?php endif; ?>
                        </div>

                    </div>

                </section>

            </main>
            
        </div>
    </div>
</div>

<style>
/* 404 page specific styles */
.error-404 .search-form {
    max-width: 400px;
    margin: 0 auto;
    display: flex;
    gap: 0.5rem;
}

.error-404 .search-field {
    flex: 1;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

.error-404 .search-submit {
    padding: 0.75rem 1.5rem;
    background: #007cba;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    transition: background 0.3s ease;
}

.error-404 .search-submit:hover {
    background: #005a87;
}

.link-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.recent-posts-section article:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
}

.recent-posts-section article a:hover {
    color: #007cba;
}

@media (max-width: 768px) {
    .error-404 .page-header div {
        font-size: 5rem;
    }
    
    .error-404 .page-title {
        font-size: 2rem;
    }
    
    .error-404 .search-form {
        flex-direction: column;
    }
}
</style>

<?php get_footer(); ?>