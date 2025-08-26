<?php
/**
 * Xinyun Theme - 评论模板
 *
 * 显示文章和页面的评论区域
 *
 * @package Xinyun
 * @since 1.0.0
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if (have_comments()) : ?>
        <h3 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            if ($comment_count == 1) {
                echo '1 条评论';
            } else {
                printf('%1$s 条评论', number_format_i18n($comment_count));
            }
            ?>
        </h3>

        <?php the_comments_navigation(array(
            'prev_text' => '← 较早评论',
            'next_text' => '较新评论 →',
        )); ?>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 60,
                'callback'    => 'xinyun_comment',
            ));
            ?>
        </ol>

        <?php the_comments_navigation(array(
            'prev_text' => '← 较早评论',
            'next_text' => '较新评论 →',
        )); ?>

        <?php if (!comments_open()) : ?>
            <p class="no-comments">
                评论已关闭。
            </p>
        <?php endif; ?>

    <?php endif; // Check for have_comments() ?>

    <?php
    // 自定义评论表单
    $comments_args = array(
        'title_reply'         => '发表评论',
        'title_reply_to'      => '回复 %s',
        'cancel_reply_link'   => '取消回复',
        'label_submit'        => '提交评论',
        'submit_button'       => '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" style="background: #007cba; color: #fff; padding: 0.75rem 2rem; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; transition: background 0.3s ease;" onmouseover="this.style.background=\'#005a87\'" onmouseout="this.style.background=\'#007cba\'" />',
        'comment_field'       => '',
        'must_log_in'         => '<p class="must-log-in" style="text-align: center; padding: 2rem; background: #fff; border-radius: 5px; border-left: 4px solid #007cba;">您必须 <a href="' . wp_login_url(apply_filters('the_permalink', get_permalink())) . '" style="color: #007cba; text-decoration: none;">登录</a> 才能发表评论。</p>',
        'logged_in_as'        => '<p class="logged-in-as" style="margin-bottom: 1rem; color: #666;">已登录为 <a href="' . admin_url('profile.php') . '" style="color: #007cba; text-decoration: none;">' . $user_identity . '</a>。<a href="' . wp_logout_url(apply_filters('the_permalink', get_permalink())) . '" title="退出登录" style="color: #007cba; text-decoration: none; margin-left: 0.5rem;">退出？</a></p>',
        'comment_notes_before' => '',
        'comment_notes_after' => '',
        'fields'              => array(
            'author' => '<div class="comment-form-field"><input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" placeholder="昵称" required /></div>',
            'email'  => '<div class="comment-form-field"><input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" placeholder="邮箱" required /></div>',
        ),
        'format'              => 'html5',
    );

    // 移除浏览器记住信息（cookies）复选框
    add_filter('comment_form_default_fields', function($fields) {
        if (isset($fields['cookies'])) {
            unset($fields['cookies']);
        }
        return $fields;
    });

    // 兜底移除：从最终字段数组中剔除 cookies 复选框
    add_filter('comment_form_fields', function($fields) {
        if (isset($fields['cookies'])) {
            unset($fields['cookies']);
        }
        return $fields;
    }, 99);

    // 兜底移除：直接将 cookies 字段输出置空
    add_filter('comment_form_field_cookies', '__return_empty_string', 99);

    // 外层布局：头像在左，字段与评论框在右
    add_action('comment_form_top', function() {
        echo '<div class="comment-form-layout">'
           . '<div class="comment-avatar-placeholder">'
           . '<svg width="48" height="48" viewBox="0 0 24 24" fill="#bbb" aria-hidden="true">'
           . '<circle cx="12" cy="8" r="4" />'
           . '<path d="M4 20c0-4 4-6 8-6s8 2 8 6" />'
           . '</svg>'
           . '</div>'
           . '<div class="comment-form-fields">';
    });

    add_action('comment_form_after_fields', function() {
        echo '<p class="comment-form-comment">'
            . '<textarea id="comment" name="comment" cols="45" rows="6" placeholder="写下你的评论..." required></textarea>'
            . '</p>';
    });

    add_action('comment_form_after', function() {
        echo '</div></div>'; // close .comment-form-fields and .comment-form-layout
    });
    
    comment_form($comments_args);
    
    // 移除钩子避免影响其他表单
    remove_all_filters('comment_form_default_fields');
    remove_all_filters('comment_form_fields');
    remove_all_filters('comment_form_field_cookies');
    remove_all_actions('comment_form_top');
    remove_all_actions('comment_form_after_fields');
    remove_all_actions('comment_form_after');
    ?>

</div>

<style>
/* 现代化评论区样式 */
.comments-area {
    margin: 4rem 0;
    font-size: 0.95rem;
    line-height: 1.6;
}

/* 评论标题 */
.comments-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0 0 3rem 0;
    position: relative;
    padding-left: 1rem;
}

.comments-title::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: linear-gradient(135deg, #007cba, #005a87);
    border-radius: 2px;
}

.no-comments {
    text-align: center;
    color: #8b9dc3;
    font-style: italic;
    margin: 3rem 0;
    padding: 2rem;
    background: rgba(139, 157, 195, 0.05);
    border-radius: 12px;
    border: 1px dashed rgba(139, 157, 195, 0.3);
}

/* 评论列表 */
.comment-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.comment-list li {
    margin-bottom: 2rem;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.04);
    border: 1px solid rgba(0, 0, 0, 0.06);
    padding: 0;
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
}

.comment-list li:hover {
    box-shadow: 0 8px 40px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
}

/* 嵌套评论 */
.comment-list li.depth-2,
.comment-list li.depth-3,
.comment-list li.depth-4 {
    margin-left: 2.5rem;
    margin-top: 1.5rem;
}

.comment-list li.depth-2 {
    background: #fafbfc;
}

.comment-list li.depth-3,
.comment-list li.depth-4 {
    background: #f5f7fa;
}

/* 评论主体 */
.comment-body {
    padding: 1.5rem;
    position: relative;
}

/* 评论头部 */
.comment-meta {
    margin-bottom: 1.25rem;
    padding-bottom: 0;
    border-bottom: none;
}

.comment-header {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.comment-avatar img {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.comment-avatar img:hover {
    transform: scale(1.05);
}

.comment-header-text {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    flex: 1;
}

.comment-author-name .fn {
    font-weight: 700;
    color: #1a1a1a;
    font-style: normal;
    font-size: 1rem;
    text-decoration: none;
    transition: color 0.3s ease;
}

.comment-author-name .fn:hover {
    color: #007cba;
}

.comment-time {
    font-size: 0.8rem;
    color: #8b9dc3;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.comment-time a {
    color: #8b9dc3;
    text-decoration: none;
    transition: color 0.3s ease;
}

.comment-time a:hover {
    color: #007cba;
}

.edit-link {
    font-size: 0.75rem;
}

/* 评论内容 */
.comment-content {
    line-height: 1.7;
    margin-bottom: 1.25rem;
    color: #374151;
}

.comment-content p {
    margin-bottom: 1rem;
}

.comment-content p:last-child {
    margin-bottom: 0;
}

/* 回复按钮 */
.reply {
    text-align: right;
}

.comment-reply-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: rgba(0, 124, 186, 0.1);
    color: #007cba;
    text-decoration: none;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 124, 186, 0.2);
}

.comment-reply-link:hover {
    background: #007cba;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 124, 186, 0.3);
}

.comment-reply-link::before {
    content: '↩';
    font-size: 0.9rem;
}

/* 评论表单 */
.comment-form {
    background: #fff;
    padding: 2.5rem;
    border-radius: 20px;
    margin-top: 3rem;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.06);
    border: 1px solid rgba(0, 0, 0, 0.06);
    position: relative;
    overflow: hidden;
}

/* 表单整体左右布局 */
.comment-form-layout {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.comment-avatar-placeholder {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.comment-form-fields {
    flex: 1;
}

.comment-form::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #007cba, #005a87, #007cba);
    background-size: 200% 100%;
    animation: shimmer 3s ease-in-out infinite;
}

@keyframes shimmer {
    0%, 100% { background-position: 200% 0; }
    50% { background-position: -200% 0; }
}

.comment-form h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 0 2rem 0;
    color: #1a1a1a;
    position: relative;
    padding-bottom: 0.75rem;
}

.comment-form h3::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 2px;
    background: linear-gradient(90deg, #007cba, #005a87);
    border-radius: 1px;
}

/* 表单字段组 */
.comment-form p {
    margin-bottom: 1.5rem;
    position: relative;
}

/* 同一行的字段容器 */
.comment-form-fields-row {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.comment-form-field {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.comment-form label {
    display: block;
    margin-bottom: 0.75rem;
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
    letter-spacing: 0.025em;
}

.comment-form input[type="text"],
.comment-form input[type="email"],
.comment-form input[type="url"],
.comment-form textarea {
    width: 100%;
    padding: 1rem 1.25rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 0.95rem;
    font-family: inherit;
    background: #fafbfc;
    transition: all 0.3s ease;
    resize: vertical;
}

.comment-form input[type="text"]:focus,
.comment-form input[type="email"]:focus,
.comment-form input[type="url"]:focus,
.comment-form textarea:focus {
    border-color: #007cba;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(0, 124, 186, 0.1);
    outline: none;
    transform: translateY(-1px);
}

.comment-form textarea {
    min-height: 120px;
    line-height: 1.6;
}

/* 提交按钮 */
.form-submit {
    margin: 0;
    text-align: center;
}

.form-submit input[type="submit"] {
    background: linear-gradient(135deg, #007cba, #005a87) !important;
    color: #fff !important;
    padding: 1rem 2.5rem !important;
    border: none !important;
    border-radius: 25px !important;
    font-size: 1rem !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 15px rgba(0, 124, 186, 0.3) !important;
    position: relative !important;
    overflow: hidden !important;
}

.form-submit input[type="submit"]:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 25px rgba(0, 124, 186, 0.4) !important;
}

.form-submit input[type="submit"]:active {
    transform: translateY(0) !important;
}

/* 提示信息 */
.comment-notes,
.must-log-in,
.logged-in-as {
    padding: 1rem 1.5rem;
    background: rgba(0, 124, 186, 0.05);
    border-radius: 12px;
    border-left: 4px solid #007cba;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
    line-height: 1.5;
}

.must-log-in {
    text-align: center;
    background: rgba(255, 193, 7, 0.1);
    border-left-color: #ffc107;
}

/* 导航 */
.comments-navigation {
    margin: 3rem 0;
    text-align: center;
}

.comments-navigation a {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    color: #007cba;
    text-decoration: none;
    font-weight: 600;
    margin: 0 0.5rem;
    border: 2px solid rgba(0, 124, 186, 0.2);
    border-radius: 25px;
    transition: all 0.3s ease;
}

.comments-navigation a:hover {
    background: #007cba;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 124, 186, 0.3);
}

/* 作者评论特殊样式 */
.bypostauthor > .comment-body {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid #28a745;
    position: relative;
}

.bypostauthor > .comment-body::before {
    content: '作者';
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: #28a745;
    color: #fff;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.05em;
}

/* 响应式设计 */
@media (max-width: 768px) {
    .comments-area {
        margin: 2rem 0;
    }
    
    .comment-list li.depth-2,
    .comment-list li.depth-3,
    .comment-list li.depth-4 {
        margin-left: 1rem;
    }
    
    .comment-header {
        gap: 0.75rem;
    }
    
    .comment-avatar img {
        width: 44px;
        height: 44px;
    }
    
    .comment-form {
        padding: 2rem 1.5rem;
        margin-top: 2rem;
    }
    
    .comments-title {
        font-size: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .comments-navigation a {
        display: block;
        margin: 0.5rem 0;
    }
    
    .comment-form-fields-row {
        flex-direction: column;
        gap: 0.75rem;
    }
}

@media (max-width: 480px) {
    .comment-form {
        padding: 1.5rem 1rem;
    }
    
    .comment-body {
        padding: 1rem;
    }
    
    .comment-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .comment-avatar {
        align-self: center;
    }
}
</style>