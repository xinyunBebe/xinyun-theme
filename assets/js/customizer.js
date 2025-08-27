/**
 * Xinyun Theme - Customizer JavaScript
 * 
 * @package Xinyun
 * @since 1.0.0
 */

(function($) {
    'use strict';

    wp.customize('blogname', function(value) {
        value.bind(function(to) {
            $('.site-title a').text(to);
        });
    });

    wp.customize('blogdescription', function(value) {
        value.bind(function(to) {
            $('.site-description').text(to);
        });
    });

    // 主色调变化
    wp.customize('xinyun_primary_color', function(value) {
        value.bind(function(to) {
            $('<style id="customizer-primary-color">' + 
                ':root { --primary-color: ' + to + '; }' + 
              '</style>').appendTo('head');
        });
    });

    // 容器宽度变化
    wp.customize('xinyun_container_width', function(value) {
        value.bind(function(to) {
            $('<style id="customizer-container-width">' + 
                '.site-container { max-width: ' + to + 'px; }' + 
              '</style>').appendTo('head');
        });
    });

})(jQuery);