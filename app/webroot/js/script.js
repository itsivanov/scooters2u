$(document).ready(function() {

    // animation

    new WOW().init();

    // end animation  - page details




    $('.styled').click(function () {
        var value = $(this).val();
    });
    $('body').on('click', 'div #test', function(){
        console.log(12);
        // window.location = "/get-started";
    });

    <!-- Initialize Swiper -->
    // if($(".gallery-top").size() > 0) {
    //     var galleryTop = new Swiper('.gallery-top', {
    //         nextButton: '.swiper-button-next',
    //         prevButton: '.swiper-button-prev',
    //         spaceBetween: 10
    //     });
    //
    //     var galleryThumbs = new Swiper('.gallery-thumbs', {
    //         spaceBetween: 5,
    //         centeredSlides: true,
    //         direction: 'vertical',
    //         slidesPerView: 'auto',
    //         touchRatio: 0.2,
    //         slideToClickedSlide: true
    //     });
    //     galleryTop.params.control = galleryThumbs;
    //     galleryThumbs.params.control = galleryTop;
    // }

    if($(".main-page-home .banner-carousel-top").size() > 0) {
        var swiper = new Swiper('.banner-carousel-top .swiper-container', {
            pagination: '.banner-carousel-top .swiper-pagination',
            paginationClickable: true
        });
    }

    if($(".main-page-home .bl-navoc").size() > 0) {
        var swiper = new Swiper('.bl-navoc .swiper-container', {
            pagination: '.bl-navoc .swiper-pagination',
            paginationClickable: true
        });
    }

    if($(".main-page-home .bl-how-it-works").size() > 0) {
        var swiper = new Swiper('.bl-how-it-works .swiper-container', {
            pagination: '.bl-how-it-works .swiper-pagination',
            paginationClickable: true
        });
    }

    if($(".main-page-home .bl-scooter-rental").size() > 0) {
        var swiper = new Swiper('.bl-scooter-rental .swiper-container', {
            pagination: '.bl-scooter-rental .swiper-pagination',
            paginationClickable: true
        });
    }

    if($(".main-page-home .bl-gallery").size() > 0) {
        var swiper = new Swiper('.bl-gallery .swiper-container', {
            pagination: '.bl-gallery .swiper-pagination',
            paginationClickable: true
        });
    }

    if($(".gallery-top").size() > 0) {
        //scroll content
        jQuery(function()
        {
            // jQuery('.scroll-pane').jScrollPane();
            $(".get-started ul li .show-content").hide();
        });
        //end scroll
    }


    //open-faqs

    $(".faq ul li a").click(function () {
        $(this).toggleClass("active");
        $(this).parents("li").find(".txt").slideToggle();
    });

    //end open-faqs

    //open-get-started

    $(".get-started ul li > a").click(function () {
        $(this).toggleClass("active");
        $(this).parents("li").find(".show-content").slideToggle();
    });

    $('.get_start_btn').click(function () {
        var id = $(this).val();
        location="/get-started/"+id;
        // console.log(id);
        }
    );

    //details111


    //end open-get-started

    // tabs

    $(".tabs-link a").click(function () {
        $(this).addClass("active");
        $(".tabs-link a").not(this).removeClass('active');
        var obj = $(this);
        $('.wrapp-tabs-content .box-tabs-content').each(function(){

            if(('#'+$(this).attr('id')) != $(obj).attr('href')) {
                $(this).hide();
            }

            else if(('#'+$(this).attr('id')) == $(obj).attr('href')) {
                $(this).show();
            }

        });
        return false;
    });

    // end tabs


    //open-mobile-menu

    $(".open-nav").click(function () {
        $(".nav-holder").toggleClass('show');
        $('body').toggleClass('nonScroll');
        $(this).toggleClass('current open');
    });
    //end open-mobile-menu

    // show share soc link

    $(".news-inside-bl .bl-left .share-link a.show-link").click(function () {
        $(this).next(".link-soc").toggleClass("open");
    });

    // end show share soc link

    //select-box
    if($("select").size() > 0) {
        $(function () {
            // $('select.styled').selectbox();
            $('select.styled').selectBox();
            // $('select.styled').select2();
        });
    }
    //end select-box
});