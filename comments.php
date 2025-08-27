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

<div id="comments" class="comments-area max-w-4xl mx-auto">

    <!-- 简洁的评论标题 -->
    <h3 class="text-sm text-gray-600 mb-4 flex items-center gap-1">
        💬 评论(<?php echo number_format_i18n(get_comments_number()); ?>)
    </h3>

    <?php
    // 自定义评论表单
    $comments_args = array(
        'title_reply'         => '', // 隐藏标题，我们已经在上面显示了
        'title_reply_to'      => '回复 %s',
        'cancel_reply_link'   => '取消回复',
        'label_submit'        => '确认提交',
        'submit_button'       => '<div class="flex justify-end mt-4"><input name="%1$s" type="submit" id="%2$s" class="%3$s bg-gray-900 text-white px-6 py-2 border-0 rounded text-sm font-medium cursor-pointer transition-colors hover:bg-gray-800 focus:outline-none" value="%4$s" /></div>',
        'comment_field'       => '',
        'must_log_in'         => '<p class="must-log-in text-center py-8 text-gray-600">您必须 <a href="' . wp_login_url(apply_filters('the_permalink', get_permalink())) . '" class="text-blue-600 no-underline">登录</a> 才能发表评论。</p>',
        'logged_in_as'        => '<p class="logged-in-as mb-4 text-gray-600">已登录为 <a href="' . admin_url('profile.php') . '" class="text-blue-600 no-underline">' . $user_identity . '</a>。<a href="' . wp_logout_url(apply_filters('the_permalink', get_permalink())) . '" title="退出登录" class="text-blue-600 no-underline ml-2">退出？</a></p>',
        'comment_notes_before' => '',
        'comment_notes_after' => '',
        'fields'              => array(
            'author' => '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" placeholder="昵称" required class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm bg-white transition-colors focus:border-blue-500 focus:outline-none" />',
            'email'  => '<input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" placeholder="邮箱" required class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm bg-white transition-colors focus:border-blue-500 focus:outline-none" />',
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

    // 自定义表单布局以匹配图片样式
    add_action('comment_form_top', function() {
        // 开始外层flex容器
        echo '<div class="flex items-start gap-4">';
        
        // 左侧头像
        echo '<div class="w-12 h-12 bg-gray-400 rounded-full flex-shrink-0 flex items-center justify-center">'
           . '<svg width="24" height="24" viewBox="0 0 24 24" fill="white" aria-hidden="true">'
           . '<circle cx="12" cy="8" r="4" />'
           . '<path d="M4 20c0-4 4-6 8-6s8 2 8 6" />'
           . '</svg>'
           . '</div>';
        
        // 右侧表单区域开始
        echo '<div class="flex-1">';
        
        // 输入框行：昵称和邮箱
        echo '<div class="flex gap-3 mb-3">';
    });

    add_action('comment_form_after_fields', function() {
        // 关闭输入框行
        echo '</div>';
        
        // 文本域
        echo '<textarea id="comment" name="comment" cols="45" rows="6" placeholder="" required class="w-full px-3 py-3 border border-gray-300 rounded text-sm bg-white leading-relaxed resize-none transition-colors focus:border-blue-500 focus:outline-none min-h-[120px] mb-4"></textarea>';
    });

    add_action('comment_form_after', function() {
        // 关闭右侧表单区域和外层容器
        echo '</div></div>';
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

    <?php if (have_comments()) : ?>

        <?php the_comments_navigation(array(
            'prev_text' => '← 较早评论',
            'next_text' => '较新评论 →',
        )); ?>

        <div class="comment-list">
            <?php
            wp_list_comments(array(
                'style'       => 'div',
                'short_ping'  => true,
                'avatar_size' => 60,
                'callback'    => 'xinyun_comment',
            ));
            ?>
        </div>

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

</div>

<!-- Tailwind CSS styles are now handled by the build system -->