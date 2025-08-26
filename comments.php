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

<div id="comments" class="comments-area" style="margin: 3rem 0; padding: 2rem; background: #f9f9f9; border-radius: 8px;">

    <?php if (have_comments()) : ?>
        <h3 class="comments-title" style="margin: 0 0 2rem 0; padding-bottom: 1rem; border-bottom: 2px solid #007cba; color: #333;">
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

        <ol class="comment-list" style="list-style: none; padding: 0; margin: 0 0 2rem 0;">
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
            <p class="no-comments" style="text-align: center; color: #666; font-style: italic; margin: 2rem 0;">
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
        'comment_field'       => '<p class="comment-form-comment"><label for="comment">评论内容 <span style="color: red;">*</span></label><br /><textarea id="comment" name="comment" cols="45" rows="6" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-family: inherit; resize: vertical;" required></textarea></p>',
        'must_log_in'         => '<p class="must-log-in" style="text-align: center; padding: 2rem; background: #fff; border-radius: 5px; border-left: 4px solid #007cba;">您必须 <a href="' . wp_login_url(apply_filters('the_permalink', get_permalink())) . '" style="color: #007cba; text-decoration: none;">登录</a> 才能发表评论。</p>',
        'logged_in_as'        => '<p class="logged-in-as" style="margin-bottom: 1rem; color: #666;">已登录为 <a href="' . admin_url('profile.php') . '" style="color: #007cba; text-decoration: none;">' . $user_identity . '</a>。<a href="' . wp_logout_url(apply_filters('the_permalink', get_permalink())) . '" title="退出登录" style="color: #007cba; text-decoration: none; margin-left: 0.5rem;">退出？</a></p>',
        'comment_notes_before' => '<p class="comment-notes" style="margin-bottom: 1.5rem; color: #666; font-size: 0.9rem;">您的电子邮箱地址不会被公开。必填项已用 <span style="color: red;">*</span> 标注</p>',
        'comment_notes_after' => '',
        'fields'              => array(
            'author' => '<p class="comment-form-author"><label for="author">姓名 <span style="color: red;">*</span></label><br /><input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;" required /></p>',
            'email'  => '<p class="comment-form-email"><label for="email">电子邮箱 <span style="color: red;">*</span></label><br /><input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;" required /></p>',
            'url'    => '<p class="comment-form-url"><label for="url">网站</label><br /><input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;" /></p>',
        ),
    );

    comment_form($comments_args);
    ?>

</div>

<style>
/* Comments specific styles */
.comments-area {
    font-size: 0.95rem;
}

.comment-list {
    margin: 0;
    padding: 0;
}

.comment-list li {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #fff;
    border-radius: 8px;
    border-left: 4px solid #007cba;
}

.comment-list li.depth-2,
.comment-list li.depth-3,
.comment-list li.depth-4 {
    margin-left: 2rem;
    border-left-color: #666;
}

.comment-body {
    position: relative;
}

.comment-meta {
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #eee;
}

.comment-author {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.5rem;
}

.comment-author img {
    border-radius: 50%;
    border: 2px solid #e1e1e1;
}

.comment-author .fn {
    font-weight: 600;
    color: #333;
    font-style: normal;
}

.comment-metadata {
    font-size: 0.85rem;
    color: #666;
}

.comment-metadata a {
    color: #666;
    text-decoration: none;
}

.comment-metadata a:hover {
    color: #007cba;
}

.comment-content {
    line-height: 1.6;
    margin-bottom: 1rem;
}

.comment-content p {
    margin-bottom: 1rem;
}

.comment-content p:last-child {
    margin-bottom: 0;
}

.reply {
    text-align: right;
}

.comment-reply-link {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #f0f0f0;
    color: #333;
    text-decoration: none;
    border-radius: 15px;
    font-size: 0.85rem;
    transition: all 0.3s ease;
}

.comment-reply-link:hover {
    background: #007cba;
    color: #fff;
}

.comment-form {
    background: #fff;
    padding: 2rem;
    border-radius: 8px;
    margin-top: 2rem;
}

.comment-form h3 {
    margin: 0 0 1.5rem 0;
    color: #333;
    border-bottom: 1px solid #e1e1e1;
    padding-bottom: 0.75rem;
}

.comment-form p {
    margin-bottom: 1.5rem;
}

.comment-form label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #333;
}

.comment-form input[type="text"],
.comment-form input[type="email"],
.comment-form input[type="url"],
.comment-form textarea {
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.comment-form input[type="text"]:focus,
.comment-form input[type="email"]:focus,
.comment-form input[type="url"]:focus,
.comment-form textarea:focus {
    border-color: #007cba;
    box-shadow: 0 0 0 2px rgba(0, 124, 186, 0.1);
    outline: none;
}

.form-submit {
    margin: 0;
}

.comments-navigation {
    margin: 2rem 0;
    text-align: center;
}

.comments-navigation a {
    color: #007cba;
    text-decoration: none;
    font-weight: 500;
    margin: 0 1rem;
}

.comments-navigation a:hover {
    color: #005a87;
}

/* Responsive */
@media (max-width: 768px) {
    .comment-list li.depth-2,
    .comment-list li.depth-3,
    .comment-list li.depth-4 {
        margin-left: 1rem;
    }
    
    .comment-author {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .comment-form {
        padding: 1.5rem;
    }
}

/* Admin comment styling */
.bypostauthor > .comment-body {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-left-color: #28a745;
}

.bypostauthor > .comment-body .comment-author .fn:after {
    content: " 👑";
    font-size: 0.8rem;
}
</style>