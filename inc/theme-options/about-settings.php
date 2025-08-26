<?php
/**
 * Xinyun Theme - 关于页面设置
 *
 * 处理主题说明页面相关内容
 *
 * @package Xinyun
 * @since 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 关于页面设置类
 */
class Xinyun_About_Settings {

    /**
     * 主题选项实例
     *
     * @var Xinyun_Theme_Options
     */
    private Xinyun_Theme_Options $theme_options;

    /**
     * 构造函数
     *
     * @param Xinyun_Theme_Options $theme_options 主题选项实例
     */
    public function __construct(Xinyun_Theme_Options $theme_options) {
        $this->theme_options = $theme_options;
    }

    /**
     * 渲染主题说明页面
     */
    public function render_about_tab(): void {
        ?>
        <div class="xinyun-about-content">
            <div class="theme-header">
                <div class="theme-icon">
                    <span class="dashicons dashicons-admin-appearance"></span>
                </div>
                <div class="theme-details">
                    <h2>心耘 WordPress 主题</h2>
                    <p class="version">版本 <?php echo defined('XINYUN_VERSION') ? XINYUN_VERSION : '1.0.0'; ?></p>
                    <p class="description">一个现代化、响应式的 WordPress 主题，具有强大的自定义功能和优雅的设计。</p>
                </div>
            </div>

            <div class="xinyun-features-grid">
                <div class="feature-card">
                    <h3><span class="dashicons dashicons-smartphone"></span> 响应式设计</h3>
                    <p>完美适配各种设备，从桌面电脑到手机平板，都能提供优秀的用户体验。</p>
                </div>

                <div class="feature-card">
                    <h3><span class="dashicons dashicons-images-alt2"></span> 轮播图功能</h3>
                    <p>支持多种轮播图类型，包括文章轮播、图片轮播等，完全可定制的设置选项。</p>
                </div>

                <div class="feature-card">
                    <h3><span class="dashicons dashicons-admin-customizer"></span> 易于定制</h3>
                    <p>通过直观的设置页面，您可以轻松配置主题的各种参数，无需编程知识。</p>
                </div>

                <div class="feature-card">
                    <h3><span class="dashicons dashicons-performance"></span> 性能优化</h3>
                    <p>使用现代 Web 技术，优化后的代码确保快速加载和流畅的用户体验。</p>
                </div>

                <div class="feature-card">
                    <h3><span class="dashicons dashicons-universal-access-alt"></span> 无障碍访问</h3>
                    <p>遵循 Web 无障碍标准，支持屏幕阅读器和键盘导航，让所有用户都能访问。</p>
                </div>

                <div class="feature-card">
                    <h3><span class="dashicons dashicons-admin-tools"></span> 模块化架构</h3>
                    <p>采用模块化设计，易于扩展和维护，支持添加新的功能组件。</p>
                </div>
            </div>

            <div class="xinyun-help-section">
                <h3>需要帮助？</h3>
                <div class="help-buttons">
                    <a href="<?php echo admin_url('themes.php'); ?>" class="button button-secondary">
                        <span class="dashicons dashicons-admin-appearance"></span> 返回主题页面
                    </a>
                    <a href="<?php echo home_url(); ?>" class="button button-secondary" target="_blank">
                        <span class="dashicons dashicons-external"></span> 查看网站
                    </a>
                    <a href="<?php echo admin_url('customize.php'); ?>" class="button button-primary">
                        <span class="dashicons dashicons-admin-customizer"></span> WordPress 自定义器
                    </a>
                </div>
            </div>
        </div>
        <?php
    }
}

