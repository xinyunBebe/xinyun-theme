<?php
/**
 * Xinyun Theme - 后台资源管理
 *
 * 处理主题设置页面的CSS和JavaScript资源
 *
 * @package Xinyun
 * @since 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 后台资源管理类
 */
class Xinyun_Admin_Assets {

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
        $this->init();
    }

    /**
     * 初始化资源管理
     */
    private function init(): void {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }

    /**
     * 加载后台资源
     */
    public function enqueue_admin_assets(string $hook): void {
        if ($hook !== 'appearance_page_' . $this->theme_options->get_page_slug()) {
            return;
        }

        // 添加CSS
        wp_add_inline_style('wp-admin', $this->get_admin_css());

        // 添加JavaScript
        wp_add_inline_script('jquery', $this->get_admin_js());
    }

    /**
     * 获取后台CSS样式
     */
    private function get_admin_css(): string {
        return '
            /* Tab导航样式 */
            .xinyun-tab-nav {
                border-bottom: 1px solid #ccc;
                margin: 20px 0;
                padding: 0;
            }
            .xinyun-tab-nav ul {
                display: flex;
                margin: 0;
                padding: 0;
                list-style: none;
            }
            .xinyun-tab-nav li {
                margin: 0;
            }
            .xinyun-tab-nav a {
                display: block;
                padding: 12px 20px;
                text-decoration: none;
                color: #555;
                border: 1px solid transparent;
                border-bottom: none;
                margin-right: 5px;
                border-radius: 4px 4px 0 0;
                transition: all 0.3s ease;
                position: relative;
                top: 1px;
            }
            .xinyun-tab-nav a:hover {
                color: #2271b1;
                background: #f6f7f7;
            }
            .xinyun-tab-nav a.nav-tab-active {
                background: #fff;
                border-color: #ccc;
                color: #000;
                font-weight: 600;
            }

            /* Tab内容区域 */
            .xinyun-tab-content {
                display: none;
                background: #fff;
                border: 1px solid #ccc;
                border-top: none;
                padding: 20px;
                border-radius: 0 0 4px 4px;
            }
            .xinyun-tab-content.active {
                display: block;
            }

            /* 轮播图配置样式 */
            .carousel-slides-config {
                margin-top: 20px;
            }
            .slide-config-item {
                background: #f8f9fa;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 20px;
                margin-bottom: 15px;
            }
            .slide-config-item h4 {
                margin: 0 0 15px 0;
                color: #333;
                font-size: 16px;
                font-weight: 600;
            }
            .slide-config-row {
                display: flex;
                gap: 30px;
                align-items: flex-start;
            }
            .slide-image-field {
                flex: 1;
            }
            .slide-post-field {
                flex: 1;
            }
            .slide-image-field label,
            .slide-post-field label {
                display: block;
                font-weight: 600;
                margin-bottom: 8px;
                color: #333;
            }
            .image-upload-wrapper {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            .image-preview {
                min-height: 100px;
                border: 2px dashed #ddd;
                border-radius: 6px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #fff;
                padding: 10px;
            }
            .image-preview:empty {
                background: #f9f9f9;
            }
            .image-preview:empty::before {
                content: "未选择图片";
                color: #999;
                font-style: italic;
            }
            .post-title-display {
                color: #666;
                font-style: italic;
                margin-left: 10px;
            }
            .post-title-display.error {
                color: #d63384;
            }
            .select-image-btn, .remove-image-btn {
                align-self: flex-start;
            }

            /* 主题说明页面样式 */
            .theme-header {
                display: flex;
                align-items: center;
                gap: 20px;
                margin-bottom: 30px;
                padding: 20px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 8px;
                color: white;
            }
            .theme-icon {
                font-size: 48px;
                opacity: 0.9;
            }
            .theme-details h2 {
                color: white;
                margin: 0 0 8px 0;
                font-size: 28px;
            }
            .theme-details .version {
                color: rgba(255,255,255,0.8);
                margin: 0 0 10px 0;
                font-size: 14px;
            }
            .theme-details .description {
                color: rgba(255,255,255,0.9);
                margin: 0;
                font-size: 16px;
                line-height: 1.5;
            }

            /* 特性网格 */
            .xinyun-features-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 20px;
                margin-bottom: 30px;
            }
            .feature-card {
                background: #fff;
                border: 1px solid #e1e1e1;
                border-radius: 8px;
                padding: 20px;
                transition: all 0.3s ease;
            }
            .feature-card:hover {
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                transform: translateY(-2px);
            }
            .feature-card h3 {
                margin: 0 0 10px 0;
                font-size: 18px;
                color: #333;
            }
            .feature-card h3 .dashicons {
                margin-right: 8px;
                color: #2271b1;
            }
            .feature-card p {
                margin: 0;
                color: #666;
                line-height: 1.5;
            }

            /* 帮助区域 */
            .xinyun-help-section {
                background: #f8f9fa;
                border: 1px solid #e1e1e1;
                border-radius: 8px;
                padding: 25px;
                text-align: center;
            }
            .xinyun-help-section h3 {
                margin: 0 0 20px 0;
                color: #333;
            }
            .help-buttons {
                display: flex;
                gap: 15px;
                justify-content: center;
                flex-wrap: wrap;
            }
            .help-buttons .button {
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }
            .help-buttons .dashicons {
                margin: 0;
            }

            /* 表单样式优化 */
            .xinyun-options-form .form-table th {
                width: 200px;
                padding: 15px 10px 15px 0;
            }
            .xinyun-options-form .form-table td {
                padding: 15px 10px;
            }
            .color-picker {
                width: 100px;
                height: 40px;
                border: 1px solid #ddd;
                border-radius: 4px;
                cursor: pointer;
            }
            .unit {
                color: #666;
                font-style: italic;
                margin-left: 8px;
                font-size: 13px;
            }
            .description {
                color: #666;
                font-size: 13px;
                line-height: 1.4;
                margin-top: 5px !important;
            }

            /* 保存按钮区域 */
            #xinyun-save-settings {
                margin-top: 20px;
                padding: 20px;
                background: #f8f9fa;
                border-radius: 4px;
                text-align: center;
            }

            /* 标题图标 */
            .wrap h1 .dashicons {
                margin-right: 10px;
                vertical-align: middle;
            }

            /* 自定义轮播图配置样式 */
            .slide-config {
                border: 1px solid #ddd;
                padding: 15px;
                margin-bottom: 15px;
                border-radius: 5px;
                background: #fafafa;
            }
            .slide-config h4 {
                margin: 0 0 15px 0;
                color: #333;
                font-size: 16px;
                font-weight: 600;
            }
            .image-preview img {
                max-width: 200px;
                height: auto;
                border: 1px solid #ddd;
                border-radius: 3px;
            }
        ';
    }

    /**
     * 获取后台JavaScript
     */
    private function get_admin_js(): string {
        return '
            (function($) {
                "use strict";

                $(document).ready(function() {
                    console.log("Xinyun Theme Options: 初始化Tab切换功能");

                    // Tab切换功能
                    const $tabLinks = $(".xinyun-tab-nav a");
                    const $tabContents = $(".xinyun-tab-content");

                    console.log("找到", $tabLinks.length, "个tab链接");
                    console.log("找到", $tabContents.length, "个tab内容");

                    if ($tabLinks.length === 0 || $tabContents.length === 0) {
                        console.error("Tab切换功能初始化失败：未找到必要的DOM元素");
                        return;
                    }

                    function switchTab(targetTabId) {
                        console.log("切换到tab:", targetTabId);

                        // 移除所有active状态
                        $tabLinks.removeClass("nav-tab-active");
                        $tabContents.removeClass("active");

                        // 添加active状态到目标tab
                        const $targetLink = $(".xinyun-tab-nav a[href=\"#" + targetTabId + "\"]");
                        const $targetContent = $("#" + targetTabId);

                        if ($targetLink.length > 0) {
                            $targetLink.addClass("nav-tab-active");
                            console.log("激活链接:", targetTabId);
                        } else {
                            console.warn("未找到目标链接:", targetTabId);
                        }

                        if ($targetContent.length > 0) {
                            $targetContent.addClass("active");
                            console.log("显示内容:", targetTabId);
                        } else {
                            console.warn("未找到目标内容:", targetTabId);
                        }
                    }

                    // 绑定点击事件
                    $tabLinks.on("click", function(e) {
                        e.preventDefault();

                        // 获取目标tab
                        const href = $(this).attr("href");
                        if (!href || href.charAt(0) !== "#") {
                            console.error("无效的tab链接:", href);
                            return;
                        }

                        const targetTab = href.substring(1);
                        console.log("点击了tab:", targetTab);

                        switchTab(targetTab);

                        // 更新URL hash但不跳转
                        if (history.replaceState) {
                            history.replaceState(null, null, "#" + targetTab);
                        }
                    });

                    // 根据URL hash激活对应tab
                    const hash = window.location.hash.substring(1);
                    if (hash && hash.length > 0) {
                        console.log("根据URL hash激活tab:", hash);
                        switchTab(hash);
                    } else {
                        // 确保至少有一个tab是激活的
                        const $activeTab = $(".xinyun-tab-nav a.nav-tab-active");
                        if ($activeTab.length === 0 && $tabLinks.length > 0) {
                            console.log("激活默认tab: basic-settings");
                            switchTab("basic-settings");
                        }
                    }

                    console.log("Xinyun Theme Options: Tab切换功能初始化完成");
                });

                // 防止jQuery冲突
            })(jQuery);
        ';
    }
}

