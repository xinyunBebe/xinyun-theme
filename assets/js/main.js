/**
 * Xinyun Theme - Main JavaScript
 * 
 * @package Xinyun
 * @since 1.0.0
 */

(function() {
    'use strict';

    // DOM Ready
    document.addEventListener('DOMContentLoaded', function() {
        initTheme();
    });

    /**
     * Initialize theme functionality
     */
    function initTheme() {
        console.log('Xinyun theme initialized');
        
        // Add your JavaScript functionality here
        initMobileMenu();
        initSearchToggle();
        initScrollToTop();
    }

    /**
     * Initialize mobile menu functionality
     */
    function initMobileMenu() {
        const menuToggle = document.querySelector('.menu-toggle');
        const navigation = document.querySelector('.main-navigation');

        if (menuToggle && navigation) {
            menuToggle.addEventListener('click', function() {
                navigation.classList.toggle('active');
                menuToggle.classList.toggle('active');
            });
        }
    }

    /**
     * Initialize search toggle functionality
     */
    function initSearchToggle() {
        const searchToggle = document.querySelector('.search-toggle');
        const searchForm = document.querySelector('.search-form');

        if (searchToggle && searchForm) {
            searchToggle.addEventListener('click', function(e) {
                e.preventDefault();
                searchForm.classList.toggle('active');
            });
        }
    }

    /**
     * Initialize scroll to top functionality
     */
    function initScrollToTop() {
        const scrollBtn = document.querySelector('.scroll-to-top');

        if (scrollBtn) {
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    scrollBtn.classList.add('visible');
                } else {
                    scrollBtn.classList.remove('visible');
                }
            });

            scrollBtn.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    }

})();