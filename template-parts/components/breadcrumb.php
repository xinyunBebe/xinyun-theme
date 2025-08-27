<?php
/**
 * Template part for breadcrumb navigation
 *
 * @package Xinyun
 * @since 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

// 不在首页显示面包屑
if (is_front_page()) {
    return;
}
?>

<nav class="breadcrumb" role="navigation" aria-label="<?php _e('Breadcrumb', 'xinyun'); ?>">
    <div class="site-container">
        <ol class="breadcrumb-list" itemscope itemtype="https://schema.org/BreadcrumbList">
            <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="<?php echo esc_url(home_url('/')); ?>" itemprop="item">
                    <span itemprop="name"><?php _e('Home', 'xinyun'); ?></span>
                </a>
                <meta itemprop="position" content="1" />
            </li>
            
            <?php
            $position = 2;
            
            if (is_category() || is_tag() || is_tax()) {
                $term = get_queried_object();
                if ($term->parent) {
                    $ancestors = get_ancestors($term->term_id, $term->taxonomy);
                    $ancestors = array_reverse($ancestors);
                    
                    foreach ($ancestors as $ancestor_id) {
                        $ancestor = get_term($ancestor_id, $term->taxonomy);
                        ?>
                        <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <a href="<?php echo esc_url(get_term_link($ancestor)); ?>" itemprop="item">
                                <span itemprop="name"><?php echo esc_html($ancestor->name); ?></span>
                            </a>
                            <meta itemprop="position" content="<?php echo $position; ?>" />
                        </li>
                        <?php
                        $position++;
                    }
                }
                ?>
                <li class="breadcrumb-item current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span itemprop="name"><?php echo esc_html($term->name); ?></span>
                    <meta itemprop="position" content="<?php echo $position; ?>" />
                </li>
                <?php
            } elseif (is_single()) {
                $post_type = get_post_type();
                
                if ($post_type === 'post') {
                    $categories = get_the_category();
                    if ($categories) {
                        $category = $categories[0];
                        if ($category->parent) {
                            $ancestors = get_ancestors($category->term_id, 'category');
                            $ancestors = array_reverse($ancestors);
                            
                            foreach ($ancestors as $ancestor_id) {
                                $ancestor = get_category($ancestor_id);
                                ?>
                                <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                    <a href="<?php echo esc_url(get_category_link($ancestor)); ?>" itemprop="item">
                                        <span itemprop="name"><?php echo esc_html($ancestor->name); ?></span>
                                    </a>
                                    <meta itemprop="position" content="<?php echo $position; ?>" />
                                </li>
                                <?php
                                $position++;
                            }
                        }
                        ?>
                        <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <a href="<?php echo esc_url(get_category_link($category)); ?>" itemprop="item">
                                <span itemprop="name"><?php echo esc_html($category->name); ?></span>
                            </a>
                            <meta itemprop="position" content="<?php echo $position; ?>" />
                        </li>
                        <?php
                        $position++;
                    }
                } elseif ($post_type !== 'page') {
                    $post_type_obj = get_post_type_object($post_type);
                    if ($post_type_obj) {
                        ?>
                        <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <a href="<?php echo esc_url(get_post_type_archive_link($post_type)); ?>" itemprop="item">
                                <span itemprop="name"><?php echo esc_html($post_type_obj->labels->name); ?></span>
                            </a>
                            <meta itemprop="position" content="<?php echo $position; ?>" />
                        </li>
                        <?php
                        $position++;
                    }
                }
                ?>
                <li class="breadcrumb-item current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span itemprop="name"><?php the_title(); ?></span>
                    <meta itemprop="position" content="<?php echo $position; ?>" />
                </li>
                <?php
            } elseif (is_page()) {
                global $post;
                if ($post->post_parent) {
                    $ancestors = get_post_ancestors($post);
                    $ancestors = array_reverse($ancestors);
                    
                    foreach ($ancestors as $ancestor_id) {
                        ?>
                        <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <a href="<?php echo esc_url(get_permalink($ancestor_id)); ?>" itemprop="item">
                                <span itemprop="name"><?php echo esc_html(get_the_title($ancestor_id)); ?></span>
                            </a>
                            <meta itemprop="position" content="<?php echo $position; ?>" />
                        </li>
                        <?php
                        $position++;
                    }
                }
                ?>
                <li class="breadcrumb-item current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span itemprop="name"><?php the_title(); ?></span>
                    <meta itemprop="position" content="<?php echo $position; ?>" />
                </li>
                <?php
            } elseif (is_search()) {
                ?>
                <li class="breadcrumb-item current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span itemprop="name"><?php printf(__('Search Results for "%s"', 'xinyun'), get_search_query()); ?></span>
                    <meta itemprop="position" content="<?php echo $position; ?>" />
                </li>
                <?php
            } elseif (is_404()) {
                ?>
                <li class="breadcrumb-item current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span itemprop="name"><?php _e('Page Not Found', 'xinyun'); ?></span>
                    <meta itemprop="position" content="<?php echo $position; ?>" />
                </li>
                <?php
            } elseif (is_archive()) {
                ?>
                <li class="breadcrumb-item current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span itemprop="name"><?php the_archive_title(); ?></span>
                    <meta itemprop="position" content="<?php echo $position; ?>" />
                </li>
                <?php
            }
            ?>
        </ol>
    </div>
</nav>