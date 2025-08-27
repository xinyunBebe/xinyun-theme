/**
 * Xinyun Theme - Admin JavaScript
 * 
 * @package Xinyun
 * @since 1.0.0
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initAdminScripts();
    });

    /**
     * Initialize admin scripts
     */
    function initAdminScripts() {
        console.log('Xinyun admin scripts loaded');
        
        // Customizer live preview handlers
        initCustomizerPreview();
        
        // Admin panel enhancements
        initAdminEnhancements();
    }

    /**
     * Initialize customizer live preview
     */
    function initCustomizerPreview() {
        if (typeof wp !== 'undefined' && wp.customize) {
            // Handle color changes
            wp.customize('xinyun_primary_color', function(value) {
                value.bind(function(to) {
                    $(':root').css('--primary-color', to);
                });
            });

            // Handle layout changes
            wp.customize('xinyun_container_width', function(value) {
                value.bind(function(to) {
                    $('.site-container').css('max-width', to + 'px');
                });
            });
        }
    }

    /**
     * Initialize admin panel enhancements
     */
    function initAdminEnhancements() {
        // Add theme-specific admin functionality here
    }

})(jQuery);