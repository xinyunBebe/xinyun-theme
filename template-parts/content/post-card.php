<?php
/**
 * 文章卡片组件
 *
 * @package Xinyun
 * @since 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}
?>

<?php
/**
 * 文章卡片组件
 *
 * @package Xinyun
 * @since 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300 group'); ?>>
    
    <?php if (has_post_thumbnail()) : ?>
        <div class="relative aspect-[16/9] overflow-hidden bg-gradient-to-br from-blue-50 to-purple-50">
            <a href="<?php the_permalink(); ?>" class="block h-full">
                <?php 
                the_post_thumbnail('xinyun-featured', [
                    'class' => 'w-full h-full object-cover transition-transform duration-300 group-hover:scale-105',
                    'alt' => the_title_attribute(['echo' => false]),
                    'loading' => 'lazy'
                ]); 
                ?>
            </a>
            
            <!-- 分类标签 -->
            <?php if (has_category()) : ?>
                <div class="absolute top-3 left-3">
                    <?php 
                    $categories = get_the_category();
                    if (!empty($categories)) :
                        $category = $categories[0];
                        $colors = ['bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-pink-500', 'bg-red-500'];
                        $color = $colors[abs(crc32($category->name)) % count($colors)];
                    ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-white <?php echo $color; ?>">
                            <?php echo esc_html($category->name); ?>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <!-- 收费标识 -->
            <div class="absolute top-3 right-3 bg-red-500 text-white px-2 py-1 rounded-md text-xs font-medium">
                付费阅读
            </div>
        </div>
    <?php else : ?>
        <!-- 无特色图片时的占位 -->
        <div class="relative aspect-[16/9] bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
    <?php endif; ?>
    
    <!-- 卡片内容 -->
    <div class="p-4">
        <!-- 分类标签 -->
        <?php
        if (has_category()) {
            $category = get_the_category()[0];
            if ($category) {
                echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="inline-block text-xs font-medium bg-red-100 text-red-700 px-2 py-1 rounded-md hover:bg-red-200 transition-colors whitespace-nowrap overflow-hidden text-ellipsis max-w-full">' . esc_html($category->name) . '</a>';
            }
        }
        ?>

        <!-- 标题 -->
        <h2 class="mt-1 text-lg font-bold text-gray-900 line-clamp-2 group-hover:text-primary transition-colors h-[3.5rem]">
            <a href="<?php the_permalink(); ?>" class="block">
                <?php the_title(); ?>
            </a>
        </h2>

        <!-- 底部信息栏 -->
        <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
            <!-- 左侧：时间 -->
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                <?php printf('%s前', human_time_diff(get_the_time('U'), current_time('timestamp'))); ?>
            </time>

            <!-- 右侧：统计信息 -->
            <div class="flex items-center space-x-4">
                <span class="flex items-center" title="阅读量">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <?php 
                    $views = get_post_meta(get_the_ID(), 'views', true);
                    echo $views ? esc_html($views) : rand(100, 999);
                    ?>
                </span>
                <span class="flex items-center" title="评论数">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    <?php echo get_comments_number(); ?>
                </span>
                <span class="flex items-center" title="点赞数">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.5l1.318-1.182a4.5 4.5 0 116.364 6.364L12 20.25l-7.682-7.682a4.5 4.5 0 010-6.364z"></path></svg>
                    <?php 
                    // 点赞功能需要插件支持，这里用一个占位符
                    echo get_post_meta(get_the_ID(), 'likes', true) ?: rand(0, 100);
                    ?>
                </span>
            </div>
        </div>
    </div>
</article>