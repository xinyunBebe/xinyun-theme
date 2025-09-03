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

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300 group h-full flex flex-col'); ?>>
    
    <?php if (has_post_thumbnail()) : ?>
        <div class="relative aspect-[4/3] overflow-hidden bg-gradient-to-br from-blue-50 to-purple-50">
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
        <div class="relative aspect-[4/3] bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
    <?php endif; ?>
    
    <!-- 卡片内容 -->
    <div class="p-4 flex-1 flex flex-col">
        
        <!-- 文章标题 -->
        <h2 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-primary transition-colors">
            <a href="<?php the_permalink(); ?>" class="block">
                <?php the_title(); ?>
            </a>
        </h2>
        
        
        <!-- 标签 -->
        <?php if (has_tag()) : ?>
            <div class="mb-4 flex-1">
                <?php 
                $tags = get_the_tags();
                if ($tags) :
                    $tag_count = 0;
                    foreach ($tags as $tag) :
                        if ($tag_count >= 3) break; // 最多显示3个标签
                ?>
                    <span class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded mr-1 mb-1">
                        <?php echo esc_html($tag->name); ?>
                    </span>
                <?php 
                        $tag_count++;
                    endforeach;
                endif; 
                ?>
            </div>
        <?php else : ?>
            <div class="flex-1"></div>
        <?php endif; ?>
        
        <!-- 底部信息栏：头像、作者、统计信息 -->
        <div class="flex items-center justify-between border-t border-gray-100 pt-3 mt-auto">
            <!-- 左侧：头像和作者 -->
            <div class="flex items-center">
                <?php echo get_avatar(get_the_author_meta('ID'), 24, '', '', ['class' => 'w-6 h-6 rounded-full mr-2']); ?>
                <span class="text-xs text-gray-600 font-medium"><?php the_author(); ?></span>
            </div>
            
            <!-- 右侧：统计信息 -->
            <div class="flex items-center space-x-3 text-xs text-gray-500">
                <!-- 阅读量 -->
                <span class="flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <?php 
                    // 获取文章浏览量，如果没有则显示随机数
                    $views = get_post_meta(get_the_ID(), 'views', true);
                    if (!$views) {
                        $views = rand(100, 999);
                    }
                    echo $views;
                    ?>
                </span>
                
                <!-- 评论数 -->
                <span class="flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <?php echo get_comments_number(); ?>
                </span>
                
                <!-- 发布时间 -->
                <time class="text-gray-400" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                    <?php echo get_the_date('m-d'); ?>
                </time>
            </div>
        </div>
    </div>
</article>