jQuery(document).ready(function ($) {
    var $container = $('#kshm-container');
    var $hamburger = $('#kshm-hamburger');
    var $overlay = $('#kshm-overlay');
    var $close = $('#kshm-close');
    var breakpoint = parseInt($container.data('breakpoint')) || 768;
    var isMenuOpen = false;

    function checkBreakpoint() {
        var windowWidth = $(window).width();

        if (windowWidth <= breakpoint) {
            $container.show();
        } else {
            $container.hide();
            if (isMenuOpen) {
                closeMenu();
            }
        }
    }

    function openMenu() {
        $overlay.addClass('active');
        $hamburger.addClass('active');
        $('body').css('overflow', 'hidden');
        isMenuOpen = true;
    }

    function closeMenu() {
        $overlay.removeClass('active');
        $hamburger.removeClass('active');
        $('body').css('overflow', '');
        isMenuOpen = false;
    }

    $hamburger.on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        openMenu();
    });

    $close.on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        closeMenu();
    });

    $overlay.on('click', function (e) {
        if (e.target === this) {
            closeMenu();
        }
    });

    $(document).on('keydown', function (e) {
        if (e.keyCode === 27 && isMenuOpen) {
            closeMenu();
        }
    });

    $(window).on('resize', function () {
        checkBreakpoint();
    });

    checkBreakpoint();

    $('.kshm-menu-link').on('click', function () {
        if (isMenuOpen) {
            closeMenu();
        }
    });
});
