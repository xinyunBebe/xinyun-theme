<?php
/**
 * Template part for pagination
 *
 * @package Xinyun
 * @since 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

// 检查是否需要显示分页
global $wp_query;
if ($wp_query->max_num_pages <= 1) {
    return;
}
?>

<nav class="pagination-wrapper" role="navigation" aria-label="<?php _e('Posts navigation', 'xinyun'); ?>">
    <div class="pagination">
        <?php
        $big = 999999999; // 需要一个不太可能出现的数字
        
        $paginate_links = paginate_links(array(
            'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format'    => '?paged=%#%',
            'current'   => max(1, get_query_var('paged')),
            'total'     => $wp_query->max_num_pages,
            'prev_text' => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M10 12L6 8L10 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg><span>' . __('Previous', 'xinyun') . '</span>',
            'next_text' => '<span>' . __('Next', 'xinyun') . '</span><svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M6 12L10 8L6 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'mid_size'  => 2,
            'end_size'  => 1,
            'type'      => 'array',
        ));
        
        if ($paginate_links) {
            echo '<ul class="page-numbers-list">';
            foreach ($paginate_links as $link) {
                if (strpos($link, 'current') !== false) {
                    echo '<li class="page-number current">' . $link . '</li>';
                } elseif (strpos($link, 'prev') !== false) {
                    echo '<li class="page-number prev">' . $link . '</li>';
                } elseif (strpos($link, 'next') !== false) {
                    echo '<li class="page-number next">' . $link . '</li>';
                } elseif (strpos($link, 'dots') !== false) {
                    echo '<li class="page-number dots">' . $link . '</li>';
                } else {
                    echo '<li class="page-number">' . $link . '</li>';
                }
            }
            echo '</ul>';
        }
        ?>
    </div>
</nav>