$(document).ready(function() {
    'use strict';

    // ================================================================
    // 1. MODAL POPUP - TAMPIL DENGAN DELAY
    // ================================================================
    setTimeout(function() {
        $('#announcementModal').modal('show');
    }, 500);

    // Tutup modal dengan tombol ESC
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            $('#announcementModal').modal('hide');
        }
    });

    // ================================================================
    // 2. SLICK CAROUSEL - HERO SLIDER
    // ================================================================
    $('.hero-slider').slick({
        dots: true,
        arrows: true,
        infinite: true,
        speed: 800,
        autoplay: true,
        autoplaySpeed: 5000,
        fade: true,
        cssEase: 'ease-in-out',
        pauseOnHover: true,
        pauseOnFocus: true,
        swipe: true,
        touchMove: true,
        prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    arrows: true,
                    dots: true
                }
            },
            {
                breakpoint: 768,
                settings: {
                    arrows: false,
                    dots: true
                }
            },
            {
                breakpoint: 480,
                settings: {
                    arrows: false,
                    dots: true,
                    autoplaySpeed: 4000
                }
            }
        ]
    });

    // ================================================================
    // 3. NAVBAR SCROLL - DENGAN THROTTLE
    // ================================================================
    let isScrolling = false;

    $(window).on('scroll', function() {
        if (!isScrolling) {
            window.requestAnimationFrame(function() {
                const scrollTop = $(window).scrollTop();
                if (scrollTop > 50) {
                    $('#mainNav').addClass('navbar-scrolled');
                } else {
                    $('#mainNav').removeClass('navbar-scrolled');
                }
                isScrolling = false;
            });
            isScrolling = true;
        }
    });

    // Trigger saat halaman dimuat (jika sudah di-scroll)
    if ($(window).scrollTop() > 50) {
        $('#mainNav').addClass('navbar-scrolled');
    }

    // ================================================================
    // 4. NAVBAR ACTIVE LINK SAAT SCROLL
    // ================================================================
    const sections = $('section[id]');
    const navLinks = $('.navbar .nav-link');

    $(window).on('scroll', function() {
        let current = '';
        const scrollPos = $(window).scrollTop() + 120;

        sections.each(function() {
            const sectionTop = $(this).offset().top;
            const sectionHeight = $(this).outerHeight();
            if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                current = $(this).attr('id');
            }
        });

        navLinks.removeClass('active');
        navLinks.each(function() {
            const href = $(this).attr('href');
            if (href && href.substring(1) === current) {
                $(this).addClass('active');
            }
        });
    });

    // ================================================================
    // 5. COUNTER ANIMATION - SMOOTH DENGAN requestAnimationFrame
    // ================================================================
    function animateNumbers() {
        $('.stat-number-card .number').each(function() {
            const target = parseInt($(this).data('target'), 10);
            const duration = 2500;
            const startTime = performance.now();
            const el = $(this);

            function updateCounter(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                // Easing function (ease-out cubic)
                const eased = 1 - Math.pow(1 - progress, 3);
                const current = Math.floor(eased * target);
                el.text(current.toLocaleString('id-ID'));

                if (progress < 1) {
                    requestAnimationFrame(updateCounter);
                } else {
                    el.text(target.toLocaleString('id-ID'));
                }
            }

            requestAnimationFrame(updateCounter);
        });
    }

    // Intersection Observer untuk trigger counter
    const statsObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                animateNumbers();
                statsObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3, rootMargin: '0px 0px -50px 0px' });

    const statsSection = document.querySelector('.stats-numbers');
    if (statsSection) {
        statsObserver.observe(statsSection);
    }

    // ================================================================
    // 6. SMOOTH SCROLL - DENGAN EASING
    // ================================================================
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const targetId = $(this).attr('href');
        if (targetId === '#') return;

        const target = $(targetId);
        if (target.length) {
            const offsetTop = target.offset().top - 70;
            $('html, body').animate({
                scrollTop: offsetTop
            }, 900, 'easeInOutCubic');
        }
    });

    // ================================================================
    // 7. FAQ ACCORDION - ICON TOGGLE
    // ================================================================
    $('.faq-header').on('click', function() {
        const icon = $(this).find('i');
        const isExpanded = $(this).attr('aria-expanded') === 'true';

        if (isExpanded) {
            icon.css('transform', 'rotate(0deg)');
        } else {
            icon.css('transform', 'rotate(180deg)');
        }
    });

    // Reset icon saat accordion ditutup oleh Bootstrap
    $('.faq-header').on('hidden.bs.collapse', function() {
        $(this).find('i').css('transform', 'rotate(0deg)');
    });

    // ================================================================
    // 8. RUNNING TEXT - PAUSE ON HOVER
    // ================================================================
    $('.running-text-content').hover(
        function() {
            $(this).css('animation-play-state', 'paused');
        },
        function() {
            $(this).css('animation-play-state', 'running');
        }
    );

    // ================================================================
    // 9. FORM VALIDATION - PREVENT EMPTY SUBMIT
    // ================================================================
    $('form').on('submit', function(e) {
        let hasError = false;
        const requiredFields = $(this).find('[required]');

        requiredFields.each(function() {
            const val = $(this).val().trim();
            if (!val) {
                $(this).addClass('is-invalid');
                hasError = true;
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        // Validasi checkbox
        const checkbox = $(this).find('input[type="checkbox"][required]');
        if (checkbox.length && !checkbox.is(':checked')) {
            checkbox.addClass('is-invalid');
            hasError = true;
        }

        if (hasError) {
            e.preventDefault();
            // Scroll ke field pertama yang error
            const firstError = $(this).find('.is-invalid:first');
            if (firstError.length) {
                $('html, body').animate({
                    scrollTop: firstError.offset().top - 120
                }, 500);
            }
        }
    });

    // Hilangkan error saat user mulai mengetik
    $('form input, form select, form textarea').on('input change', function() {
        if ($(this).hasClass('is-invalid') && $(this).val().trim()) {
            $(this).removeClass('is-invalid');
        }
    });

    // ================================================================
    // 10. HIDE ALERT SETELAH BEBERAPA DETIK (jika ada)
    // ================================================================
    $('.alert').not('.alert-permanent').each(function() {
        const $this = $(this);
        setTimeout(function() {
            $this.fadeOut(500, function() {
                $(this).remove();
            });
        }, 6000);
    });

    // ================================================================
    // 11. CONSOLE WELCOME - PROFESIONAL
    // ================================================================
    console.log('%c🏛️ BANTUAN DANA HIBAH INDONESIA 2026', 'font-size:20px; font-weight:bold; color:#0d2b45;');
    console.log('%c🔒 Sistem Informasi Hibah - Transparan & Akuntabel', 'font-size:14px; color:#FFE959; background:#0d2b45; padding:4px 12px; border-radius:4px;');
    console.log('%c📢 Pendaftaran dibuka hingga 31 Desember 2026', 'font-size:13px; color:#1a4a6e;');
    console.log('%c✅ Versi 7.0 | Dukungan Teknis: helpdesk@hibahindonesia.go.id', 'font-size:12px; color:#6c7a8a;');

    // ================================================================
    // 12. DETEKSI PERANGKAT MOBILE
    // ================================================================
    const isMobile = window.innerWidth < 768;
    if (isMobile) {
        $('.hero-content h1').addClass('mobile-title');
        console.log('📱 Mode Mobile Aktif');
    }

    // ================================================================
    // 13. LAZY LOAD IMAGES (jika pakai data-src)
    // ================================================================
    if ('IntersectionObserver' in window) {
        const lazyImages = document.querySelectorAll('img[data-src]');
        const imageObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    imageObserver.unobserve(img);
                }
            });
        });

        lazyImages.forEach(function(img) {
            imageObserver.observe(img);
        });
    }

// ================================================================
// JQUERY EASING - EASE IN OUT CUBIC
// ================================================================
if (!jQuery.easing || !jQuery.easing.easeInOutCubic) {
    jQuery.easing.easeInOutCubic = function (x, t, b, c, d) {
        if ((t /= d / 2) < 1) return c / 2 * t * t * t + b;
        return c / 2 * ((t -= 2) * t * t + 2) + b;
    };
}
    // ================================================================
    // 14. RESIZE HANDLER - RESPONSIVE
    // ================================================================
    let resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            const width = window.innerWidth;
            if (width < 768) {
                $('.hero-content h1').addClass('mobile-title');
            } else {
                $('.hero-content h1').removeClass('mobile-title');
            }
            console.log('📐 Window resized to: ' + width + 'px');
        }, 250);
    });
});
