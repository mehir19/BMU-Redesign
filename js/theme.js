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

        // Testimonial
        var SwiperTestimonial = new Swiper('.swiper-testimonial', {
            loop: true,
            autoplay: {
                delay: 4000,
            },
            speed: 1500,
            slidesPerView: 1,
            centeredSlides: true,
            spaceBetween: 10,            
            navigation: {
                nextEl: '.swiper-testimonial-button-next',
                prevEl: '.swiper-testimonial-button-prev',
            },
            pagination: {
                el: '.swiper-testimonial-pagination',
                clickable: true
            },
            
            breakpoints: {
                576: {
                    slidesPerView: 2,
                },
                768: {
                  slidesPerView: 3,
                },
            }
        });
        
        // Testimonial Two
        var SwiperTestimonialTwo = new Swiper('.swiper-testimonial-two', {
            loop: true,
            autoplay: {
                delay: 0,
                disableOnInteraction: false
            },
            speed: 15000,
            freeMode: true,
            slidesPerView: 1,
            centeredSlides: false,
            spaceBetween: 30,            
            navigation: {
                nextEl: '.swiper-testimonial-two-button-next',
                prevEl: '.swiper-testimonial-two-button-prev',
            },
            pagination: {
                el: '.swiper-testimonial-two-pagination',
                clickable: true
            },
            
            breakpoints: {
                1200: {
                  slidesPerView: 2,
                },
            }
        });
        
        // Testimonial Two Reverse
        var SwiperTestimonialTwoReverse = new Swiper('.swiper-testimonial-two-reverse', {
            loop: true,
            autoplay: {
                delay: 0,
                disableOnInteraction: false,
                reverseDirection: true,
            },
            speed: 15000,
            freeMode: true,
            slidesPerView: 1,
            centeredSlides: false,
            spaceBetween: 30,            
            navigation: {
                nextEl: '.swiper-testimonial-two-button-next',
                prevEl: '.swiper-testimonial-two-button-prev',
            },
            pagination: {
                el: '.swiper-testimonial-two-pagination',
                clickable: true
            },
            
            breakpoints: {
                1200: {
                  slidesPerView: 2,
                },
            }
        });


        // WOW Init
        new WOW().init();


        // SVG Effects
        function animatePaths(svg) {
            const paths = svg.querySelectorAll(".animPath");
            const rects = svg.querySelectorAll(".purpleRect");

            paths.forEach((path, i) => {
            const rect = rects[i];
            const length = path.getTotalLength();

            function step(timestamp) {
                const progress = (timestamp / 8000) % 1; // 3s loop
                const distance = progress * length;

                const point = path.getPointAtLength(distance);
                const nextPoint = path.getPointAtLength(Math.min(distance + 1, length));
                const angle =
                Math.atan2(nextPoint.y - point.y, nextPoint.x - point.x) *
                (180 / Math.PI);

                rect.setAttribute(
                "transform",
                `translate(${point.x}, ${point.y}) rotate(${angle}) translate(-16,-1)`
                );

                requestAnimationFrame(step);
            }
            requestAnimationFrame(step);
            });
        }

        // Run for all SVGs on page
        document.querySelectorAll("svg").forEach(animatePaths);

        // accordion
        $(".wptb-accordion").on("click",".wptb-item-title", function () {
            $(this).next().slideDown();
            $(".wptb-item--content").not($(this).next()).slideUp();
        });

        $(".wptb-accordion").on("click",".wptb--item", function () {
            $(this).addClass("active").siblings().removeClass("active");
        });

        // Add auto active class 
        const bannerBoxes = document.querySelectorAll(".wptb-banner-box__top");
        let bannerIndex = 0;
        const bannerDelay = 1500; // 1 second

        function activateBannerNext() {
            bannerBoxes.forEach(box => {
                const items = box.querySelectorAll("li");
                if (items.length === 0) return;

                // Remove active
                items.forEach(item => item.classList.remove("active"));

                // Add active
                items[bannerIndex % items.length].classList.add("active");
            });

            bannerIndex++;
        }

        activateBannerNext();
        setInterval(activateBannerNext, bannerDelay);


        // Add auto active class 
        const stepFeatures = document.querySelectorAll(".pxl-step__feature li");
        const relatedBoxes = document.querySelectorAll(".pxl-step__content li"); // replace with actual
        const stepLine = document.querySelector(".pxl-step__feature-line");

        let stepIndex = 0;
        const stepDelay = 2000; // 2 seconds
        let stepInterval;

        // Function to activate item by index
        function activateStep(index) {
            stepFeatures.forEach(item => item.classList.remove("active"));
            relatedBoxes.forEach(item => item.classList.remove("active"));

            stepFeatures[index].classList.add("active");
            if (relatedBoxes.length > 0) {
                relatedBoxes[index].classList.add("active");
            }

            // Update vertical line gradient height
            const totalItems = stepFeatures.length;
            const heightPercent = ((index + 1) / totalItems) * 100; // e.g., 33%, 66%, 100%
            if (stepLine) {
                // Using gradient: top = active color, bottom = dark
                stepLine.style.background = `linear-gradient(to bottom, rgb(46, 144, 250) 0%, rgb(46, 144, 250) ${heightPercent}%, rgb(37, 43, 55) ${heightPercent}%, rgb(37, 43, 55) 100%)`;
            }

            stepIndex = index; // update global index
        }

        // Auto-rotation
        function startAutoRotation() {
            stepInterval = setInterval(() => {
                activateStep((stepIndex + 1) % stepFeatures.length);
            }, stepDelay);
        }

        // Stop auto-rotation
        function stopAutoRotation() {
            clearInterval(stepInterval);
        }

        // Click event
        stepFeatures.forEach((item, index) => {
            item.addEventListener("click", () => {
                activateStep(index);
                stopAutoRotation();
                startAutoRotation();
            });
        });

        // Initialize
        activateStep(stepIndex);
        startAutoRotation();






        // Related Products
        // var swiperRelatedProducts = new Swiper('.swiper_related_products', {
        //     slidesPerView: '1',
        //     centeredSlides: false,
        //     speed: 1400,
        //     spaceBetween: 30,
        //     parallax: true,
        //     autHeight: true,
        //     effect: 'slide',
        //     controller: {
        //         inverse: true,
        //     },
        //     slideToClickedSlide: true,
        //     lazyLoading: true,
        //     loop: false,
        //     keyboard: {
        //         enabled: true,
        //     },
        //     navigation: {
        //         nextEl: '.swiper-related-products-button-next',
        //         prevEl: '.swiper-related-products-button-prev',
        //     },
        //     pagination: {
        //         el: '.swiper-related-products-pagination',
        //         clickable: true
        //     },
            
        //     breakpoints: {
        //         768: {
        //             slidesPerView: 2,
        //             spaceBetween: 30,
        //         },
        //         1200: {
        //             slidesPerView: 3,
        //             spaceBetween: 65,
        //         },
        //     }
        // }); 

        // Totop Button
        $('.totop a').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({scrollTop: 0}, '300');
        });
    });      
})(jQuery);

// Hide header on scroll down
const nav = document.querySelector(".header");
const scrollUp = "top-up";
let lastScroll = 800;

window.addEventListener("scroll", () => {
    const currentScroll = window.pageYOffset;
    if (currentScroll <= 800) {
        nav.classList.remove(scrollUp);
        $('.totop').removeClass('show');
        return;
    }
    
    if (currentScroll > lastScroll) {
        // down
        nav.classList.add(scrollUp);
        $('.totop').addClass('show');
    } else if (currentScroll < lastScroll) {
        // up
        nav.classList.remove(scrollUp);
        $('.totop').removeClass('show');
    }
    lastScroll = currentScroll;
});