
$(document).ready(function(){

    if ($('.navBurger').length > 0){
        $('.navBurger').on('click', function(e){
            e.preventDefault();
            $('body').toggleClass('nonScroll');
            $(this).toggleClass('active').parents('.headerSection').find('.nav').togglelass('active');
        });
    }
    if($(".secondSection").size() > 0) {
        $('#fullPage').fullpage({
            anchors: ['firstPage', 'secondPage', '3rdPage', '4thPage', '5thPage', '6thPage'],
            css3: true,
            navigation: true,
            navigationPosition: 'right',
            scrollOverflow: true,
            scrollOverflowOptions: {
                click: false
            },
            slidesNavigation: true,
            slidesNavPosition: 'bottom',
            afterLoad: function(anchorLink, index){
                setTimeout(function () {
                    $('.section').eq(index - 1).addClass('animateSection').find('.slide.active').addClass('animate');
                }, 300);
                if (index == 6) {
                    $('.headerSection').addClass('greyHeader');
                }
                else{
                    $('.headerSection').removeClass('greyHeader');
                }
            },
            afterSlideLoad: function( anchorLink, index, slideAnchor, slideIndex){
                setTimeout(function () {
                    $('.section').eq(index - 1).find('.slide.active').addClass('animate');
                }, 300);
            },
            onLeave: function (index, nextIndex, direction) {
                if (index == 5 && nextIndex == 6) {
                    $('.headerSection').addClass('greyHeader');
                }
                else{
                    $('.headerSection').removeClass('greyHeader');
                }
                if (index == 3){
                    $( ".rentalDatepicker" ).datepicker('hide');
                }
            }


        });
    }

    if ($(window).width() > 1440) {
        var block = $('.gallerySlider .item');
        block.each(function (index) {
            block.eq(3 * index + 2).addClass('secondItem');
            block.eq(3 * index + 1).addClass('secondItem');
            block.eq(3 * index).addClass('firstItem');
        });
        var blockLittle = $('.gallerySlider .secondItem');
        var arrayItem = blockLittle.length;
            for (var i = 0; i <= arrayItem; i += 2) {
                blockLittle.slice(i, i + 2).wrapAll("<div class='gallery_wrap'></div>");
            }
        $('.gallerySlider .firstItem').wrap("<div class='gallery_wrap'></div>");
    }


    setTimeout(function () {
        $('.slider').slick({
            arrows: true,
            slidesToShow: 5,
            slidesToScroll: 5,
            infinite: true,
            dots: true,
            autoplay: false,
            responsive: [
                {
                    breakpoint: 1600,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 4
                    }
                },
                {
                    breakpoint: 1100,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 740,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    }, 100);
    $('.fancybox').fancybox();
});

$(window).load(function(){
    $('.preLoader').fadeOut();
});
$(window).resize(function(){

});
$(window).on('scroll', function() {

});

