/*
Theme Name: BrightHub - HTML Template
Version: 1.0
Author: WPThemeBooster
Author URL: 
Description: BrightHub - HTML Template
*/
/*	IE 10 Fix*/

(function ($) {
	'use strict';
	
	jQuery(document).ready(function () {

        // Add Menu Item Current Class Auto
        function dynamicCurrentMenuClass(selector) {
            let FileName = window.location.href.split("/").reverse()[0];
  
            selector.find("li").each(function () {
              let anchor = $(this).find("a");
              if ($(anchor).attr("href") == FileName) {
                $(this).addClass("active");
              }
            });
            // if any li has .current elmnt add class
            selector.children("li").each(function () {
              if ($(this).find(".active").length) {
                $(this).addClass("active");
              }
            });
            // if no file name return
            if ("" == FileName) {
              selector.find("li").eq(0).addClass("active");
            }
        }
          
        if ($('.mainnav .main-menu').length) {
            dynamicCurrentMenuClass($('.mainnav .main-menu'));
        }

        // Mobile Responsive Menu 
        var mobileLogoContent = $('header .logo').html();
        var mobileMenuContent = $('.mainnav').html();
		$('.mr_menu .logo').append(mobileLogoContent);
		$('.mr_menu .mr_navmenu').append(mobileMenuContent);
        $( '.mr_menu .mr_navmenu ul.main-menu li.menu-item-has-children').append( $( "<span class='submenu_opener'><i class='bi bi-chevron-right'></i></span>" ) );

        // Sub-Menu Open On-Click
        $('.mr_menu ul.main-menu li.menu-item-has-children .submenu_opener').on("click", function(e){
            $(this).parent().toggleClass('nav_open');
            $(this).siblings('ul').slideToggle();
            e.stopPropagation();
            e.preventDefault();
        });
        
        // Active Mobile Responsive Menu : Add Class in body tag
        $('.mr_menu_toggle').on('click', function(e) {
            $('body').addClass('mr_menu_active');
            e.stopPropagation();
            e.preventDefault();
        });
        $('.mr_menu_close').on('click', function(e) {
            $('body').removeClass('mr_menu_active');
            e.stopPropagation();
            e.preventDefault();
        });
        
        // $('body').on('click', function(e) {
        //     $('body').removeClass('mr_menu_active');
        //     e.stopPropagation();
        //     e.preventDefault();
        // });
    

        // Aside info bar
        $('.aside_open').on("click", function(e) {
            e.preventDefault();
            $(this).addClass('close');
            $('.aside_info_wrapper').addClass('show');
        });
        $('.aside_close').on("click", function(e) {
            e.preventDefault();
            $('.aside_open').removeClass('close');
            $('.aside_info_wrapper').removeClass('show');
        });

        // Toggle Header Search
        $('.header_search .form-control-submit').on("click", function() {
            $('.open_search').toggleClass('active');
        });

        // Sticky Header
        var header = $("header");
        $(window).scroll(function() {
            var scroll = $(window).scrollTop();

            if (scroll >= 50) {
                header.addClass("sticky");
            } else {
                header.removeClass("sticky");
            }
        });

        
        // Simple Tab Switcher with jQuery Animation
        $('.btn-faculty-tab').on('click', function() {
            const targetId = $(this).attr('data-target');
            
            // Update buttons
            $('.btn-faculty-tab').removeClass('active');
            $(this).addClass('active');

            // Hide all panes with fade out
            $('.tab-pane-custom').removeClass('active').hide();

            // Show target pane with animation
            const $targetPane = $('#' + targetId);
            if ($targetPane.length) {
                $targetPane.fadeIn(400, function() {
                    $(this).addClass('active');
                });
            }
        });


        // WOW Init
        new WOW().init();

        // accordion
        $(".wptb-accordion").on("click",".wptb-item-title", function () {
            $(this).next().slideDown();
            $(".wptb-item--content").not($(this).next()).slideUp();
        });

        $(".wptb-accordion").on("click",".wptb--item", function () {
            $(this).addClass("active").siblings().removeClass("active");
        });


        // Totop Button
        $('.totop a').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({scrollTop: 0}, '300');
        });
    });      
})(jQuery);

// Hide header on scroll down
// const nav = document.querySelector(".header");
// const scrollUp = "top-up";
// let lastScroll = 800;

// window.addEventListener("scroll", () => {
//     const currentScroll = window.pageYOffset;
//     if (currentScroll <= 800) {
//         nav.classList.remove(scrollUp);
//         $('.totop').removeClass('show');
//         return;
//     }
    
//     if (currentScroll > lastScroll) {
//         // down
//         nav.classList.add(scrollUp);
//         $('.totop').addClass('show');
//     } else if (currentScroll < lastScroll) {
//         // up
//         nav.classList.remove(scrollUp);
//         $('.totop').removeClass('show');
//     }
//     lastScroll = currentScroll;
// });