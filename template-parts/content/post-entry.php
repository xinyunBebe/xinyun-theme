<?php
/**
 * Template part for displaying post entry
 *
 * @package Xinyun
 * @since 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
    <?php if (has_post_thumbnail() && get_theme_mod('xinyun_show_featured_image', true)) : ?>
        <div class="entry-thumbnail">
            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php the_post_thumbnail('xinyun-featured', array(
                    'alt' => the_title_attribute(array('echo' => false)),
                )); ?>
            </a>
        </div>
    <?php endif; ?>
    
    <header class="entry-header">
        <?php if (is_singular()) : ?>
            <h1 class="entry-title"><?php the_title(); ?></h1>
        <?php else : ?>
            <h2 class="entry-title">
                <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
            </h2>
        <?php endif; ?>
        
        <?php if ('post' === get_post_type()) : ?>
            <div class="entry-meta">
                <?php if (get_theme_mod('xinyun_show_author', true)) : ?>
                    <span class="entry-author">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M8 8C10.21 8 12 6.21 12 4C12 1.79 10.21 0 8 0C5.79 0 4 1.79 4 4C4 6.21 5.79 8 8 8ZM8 10C5.33 10 0 11.34 0 14V16H16V14C16 11.34 10.67 10 8 10Z" fill="currentColor"/>
                        </svg>
                        <span class="author vcard">
                            <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo esc_html(get_the_author()); ?></a>
                        </span>
                    </span>
                <?php endif; ?>
                
                <span class="entry-date">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M11 1V3H5V1H3V3H2C1.45 3 1 3.45 1 4V14C1 14.55 1.45 15 2 15H14C14.55 15 15 14.55 15 14V4C15 3.45 14.55 3 14 3H13V1H11ZM14 14H2V6H14V14Z" fill="currentColor"/>
                    </svg>
                    <time class="published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                        <?php echo esc_html(get_the_date()); ?>
                    </time>
                </span>
                
                <?php if (has_category()) : ?>
                    <span class="entry-categories">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M7 2L8.5 5H12L9.5 7.5L10.5 11L7 9L3.5 11L4.5 7.5L2 5H5.5L7 2Z" fill="currentColor"/>
                        </svg>
                        <?php the_category(', '); ?>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </header>
    
    <div class="entry-content">
        <?php
        if (is_singular()) :
            the_content();
            
            wp_link_pages(array(
                'before' => '<div class="page-links">' . __('Pages:', 'xinyun'),
                'after'  => '</div>',
            ));
        else :
            the_excerpt();
            ?>
            <div class="entry-more">
                <a href="<?php the_permalink(); ?>" class="more-link">
                    <?php _e('Read more', 'xinyun'); ?>
                    <span class="screen-reader-text"><?php printf(__('about %s', 'xinyun'), get_the_title()); ?></span>
                </a>
            </div>
            <?php
        endif;
        ?>
    </div>
    
    <?php if (is_singular() && (has_tag() || get_the_author_meta('description'))) : ?>
        <footer class="entry-footer">
            <?php if (has_tag()) : ?>
                <div class="entry-tags">
                    <span class="tags-label"><?php _e('Tags:', 'xinyun'); ?></span>
                    <?php the_tags('', ', '); ?>
                </div>
            <?php endif; ?>
            
            <?php if (get_the_author_meta('description')) : ?>
                <div class="author-bio">
                    <div class="author-avatar">
                        <?php echo get_avatar(get_the_author_meta('ID'), 60); ?>
                    </div>
                    <div class="author-info">
                        <h4 class="author-title"><?php _e('About', 'xinyun'); ?> <?php echo esc_html(get_the_author()); ?></h4>
                        <p class="author-description"><?php echo wp_kses_post(get_the_author_meta('description')); ?></p>
                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="author-link">
                            <?php _e('View all posts', 'xinyun'); ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </footer>
    <?php endif; ?>
</article>