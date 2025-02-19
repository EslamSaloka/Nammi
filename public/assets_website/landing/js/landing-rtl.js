(function ($) {
    "use strict";

    // ______________ Page Loading
    $("#global-loader").fadeOut("slow");

    // CARD
    const DIV_CARD = 'div.card';

    // FUNCTIONS FOR COLLAPSED CARD
    $(document).on('click', '[data-bs-toggle="card-collapse"]', function (e) {
        let $card = $(this).closest(DIV_CARD);
        $card.toggleClass('card-collapsed');
        e.preventDefault();
        return false;
    });

    // BACK TO TOP BUTTON
    $(window).on("scroll", function (e) {
        if ($(this).scrollTop() > 0) {
            $('#back-to-top').fadeIn('slow');
        } else {
            $('#back-to-top').fadeOut('slow');
        }
    });
    $(document).on("click", "#back-to-top", function (e) {
        $("html, body").animate({
            scrollTop: 0
        }, 0);
        return false;
    });

    $('.testimonial-carousel').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 1000,
        arrows: true,
        dots: false,
        rtl:true,
        pauseOnHover: false,
        responsive: [{
            breakpoint: 768,
            settings: {
                slidesToShow: 1
            }
        }, {
            breakpoint: 520,
            settings: {
                slidesToShow: 1
            }
        }]
    });

    $('.feature-logos').slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 1000,
        arrows: false,
        dots: false,
        rtl:true,
        pauseOnHover: false,
        responsive: [{
            breakpoint: 992,
            settings: {
                slidesToShow: 4
            }
        }, {
            breakpoint: 520,
            settings: {
                slidesToShow: 2
            }
        }]
    });
    let bodyhorizontal = $('body').hasClass('horizontalmenu');
    if (bodyhorizontal) {
        if (window.innerWidth >= 992) {
            let subNavSub = document.querySelectorAll('.sub-nav-sub');
            subNavSub.forEach((e) => {
                e.style.display = '';
            })
            let subNav = document.querySelectorAll('.nav-sub')
            subNav.forEach((e) => {
                e.style.display = '';
            })
        }
        $('body').addClass('horizontalmenu');
        $('body').removeClass('leftmenu');
        $('body').removeClass('main-body');
        $('.main-content').addClass('hor-content');
        $('.main-header').addClass(' hor-header');
        $('.main-header').removeClass('sticky');
        $('.main-content').removeClass('side-content');
        $('.main-container').addClass('container');
        $('.main-container-1').addClass('container');
        $('.main-container').removeClass('container-fluid');
        $('.main-menu').addClass('main-navbar hor-menu');
        $('.main-menu').removeClass('main-sidebar main-sidebar-sticky side-menu');
        $('.main-container-1').removeClass('main-sidebar-header');
        $('.main-body-1').removeClass('main-sidebar-body');
        $('.menu-icon').removeClass('sidemenu-icon');
        $('.menu-icon').addClass('hor-icon');
        $('body').removeClass('default-menu');
        $('body').removeClass('closed-leftmenu');
        $('body').removeClass('icontext-menu');
        $('body').removeClass('main-sidebar-hide');
        $('body').removeClass('main-sidebar-open');
        $('body').removeClass('icon-overlay');
        $('body').removeClass('hover-submenu');
        $('body').removeClass('hover-submenu1');
        if (document.querySelector('body').classList.contains('horizontalmenu')) {
            checkHoriMenu();
        }
        responsive();
    }

    //sticky-header
    $(window).on("scroll", function (e) {
        if ($(window).scrollTop() >= 70) {
            $('.app-header').addClass('fixed-header');
            $('.app-header').addClass('visible-title');
        } else {
            $('.app-header').removeClass('fixed-header');
            $('.app-header').removeClass('visible-title');
        }
    });

    $(window).on("scroll", function (e) {
        if ($(window).scrollTop() >= 70) {
            $('.horizontalmenu-main').addClass('fixed-header');
            $('.horizontalmenu-main').addClass('visible-title');
        } else {
            $('.horizontalmenu-main').removeClass('fixed-header');
            $('.horizontalmenu-main').removeClass('visible-title');
        }
    });
    $(document).on('click', '#mainSidebarToggle', function (event) {
        event.preventDefault();
        $('.app').toggleClass('sidenav-toggled');
    });

    if (window.innerWidth <= 992) {
        $('body').removeClass('sidenav-toggled');
    }

})(jQuery);

// FOOTER
document.getElementById("year").innerHTML = new Date().getFullYear();

window.addEventListener('scroll', reveal);

function reveal() {
    var reveals = document.querySelectorAll('.reveal');

    for (var i = 0; i < reveals.length; i++) {

        var windowHeight = window.innerHeight;
        var cardTop = reveals[i].getBoundingClientRect().top;
        var cardRevealPoint = 150;

        //   console.log('condition', windowHeight - cardRevealPoint)

        if (cardTop < windowHeight - cardRevealPoint) {
            reveals[i].classList.add('active');
        }
        else {
            reveals[i].classList.remove('active');
        }
    }
}

reveal();

// ==== for menu scroll
const pageLink = document.querySelectorAll(".side-menu__item");

pageLink.forEach((elem) => {
    elem.addEventListener("click", (e) => {
        e.preventDefault();
        document.querySelector(elem.getAttribute("href")).scrollIntoView({
            behavior: "smooth",
            offsetTop: 1 - 60,
        });
    });
});

// section menu active
function onScroll(event) {
    const sections = document.querySelectorAll(".side-menu__item");
    const scrollPos =
        window.pageYOffset ||
        document.documentElement.scrollTop ||
        document.body.scrollTop;

    sections.forEach((elem) => {
        const val = elem.getAttribute("href");
        const refElement = document.querySelector(val);
        const scrollTopMinus = scrollPos + 73;
        if (
            refElement.offsetTop <= scrollTopMinus &&
            refElement.offsetTop + refElement.offsetHeight > scrollTopMinus
        ) {
            elem.classList.add("active");
        } else {
            elem.classList.remove("active");
        }
    })
}
window.document.addEventListener("scroll", onScroll);

