/*	Table OF Contents
 ==========================
 Carousel
 Customs Script [Modal Thumb | List View  Grid View + Add to Wishlist Click Event + Others ]
 Custom Parallax
 Custom Scrollbar
 Custom animated.css effect
 Equal height ( subcategory thumb)
 Responsive fix
 PRODUCT DETAILS 5 Customs Script
 */

var isMobile = function () {
    return /(iphone|ipod|ipad|android|blackberry|windows ce|palm|symbian)/i.test(navigator.userAgent);
};
var productContainer = $('.equalHeightCategoryProduct');

$(document).ready(function () {

    /*==================================
     Carousel
     ====================================*/

    // NEW ARRIVALS Carousel

    function customPager() {

        $.each(this.owl.userItems, function (i) {

            var pagination1 = $('.owl-controls .owl-pagination > div:first-child');
            var pagination = $('.owl-controls .owl-pagination');
            $(pagination[i]).append("<div class=' owl-has-nav owl-next'><i class='fa fa-angle-right'></i>  </div>");
            $(pagination1[i]).before("<div class=' owl-has-nav owl-prev'><i class='fa fa-angle-left'></i> </div>");


        });

    }



    var dealsSlider = $("#dealsSlider");
    dealsSlider.owlCarousel({
        navigation: false, // Show next and prev buttons
        items: 4,
        itemsTablet: [768, 3],
        itemsMobile: [420, 2]
    });



    var latestProductSlider = $("#productslider");
    latestProductSlider.owlCarousel({
        navigation: false, // Show next and prev buttons
        items: 4,
        itemsTablet: [768, 3],
        itemsMobile: [420, 2]
    });


    // BRAND  carousel
    var owl = $(".brand-carousel");

    owl.owlCarousel({
        //navigation : true, // Show next and prev buttons
        navigation: false,
        pagination: false,
        items: 12,
        itemsDesktop: [1199, 11],
        itemsDesktopSmall: [979, 8],
        itemsTablet: [768, 7],
        itemsMobile: [420, 5]
    });

    // Custom Navigation Events
    $("#nextBrand").click(function () {
        owl.trigger('owl.next');
    })
    $("#prevBrand").click(function () {
        owl.trigger('owl.prev');
    })

    // YOU MAY ALSO LIKE  carousel

    $("#SimilarProductSlider").owlCarousel({
        items: 4,
        itemsTablet: [768, 3],
        itemsMobile: [420, 2],
        navigation: false, // Show next and prev buttons
    });


    var SimilarProductSlider = $("#SimilarProductSlider");
    SimilarProductSlider.owlCarousel({
        navigation: false, // Show next and prev buttons
        afterInit: customPager,
        afterUpdate: customPager
    });

    // Custom Navigation Events
    $("#SimilarProductSlider .owl-next").click(function () {
        SimilarProductSlider.trigger('owl.next');
    })

    $("#SimilarProductSlider .owl-prev").click(function () {
        SimilarProductSlider.trigger('owl.prev');
    })

    // Linked Products  carousel

    $("#LinkedProductSlider").owlCarousel({
        navigation: false, // Show next and prev buttons
        afterInit: customPager,
        afterUpdate: customPager
    });


    var LinkedProductSlider = $("#LinkedProductSlider");
    LinkedProductSlider.owlCarousel({
        items: 4,
        itemsTablet: [768, 3],
        itemsMobile: [420, 2],
        navigation: false, // Show next and prev buttons
    });

    // Custom Navigation Events
    $("#LinkedProductSlider .owl-next").click(function () {
        LinkedProductSlider.trigger('owl.next');
    })

    $("#LinkedProductSlider .owl-prev").click(function () {
        LinkedProductSlider.trigger('owl.prev');
    })


    // Home Look 2 || Single product showcase 

    // productShowCase  carousel
    var pshowcase = $("#productShowCase");

    pshowcase.owlCarousel({
        autoPlay: 4200,
        stopOnHover: true,
        navigation: false,
        paginationSpeed: 1000,
        goToFirstSpeed: 2000,
        singleItem: true,
        autoHeight: true


    });

    // Custom Navigation Events
    $("#ps-next").click(function () {
        pshowcase.trigger('owl.next');
    })
    $("#ps-prev").click(function () {
        pshowcase.trigger('owl.prev');
    })


    // Home Look 3 || image Slider

    // imageShowCase  carousel
    var imageShowCase = $("#imageShowCase");

    imageShowCase.owlCarousel({
        autoPlay: 4200,
        stopOnHover: true,
        navigation: false,
        pagination: false,
        paginationSpeed: 1000,
        goToFirstSpeed: 2000,
        singleItem: true,
        autoHeight: true
    });

    // Custom Navigation Events
    $("#ps-next").click(function () {
        imageShowCase.trigger('owl.next');
    })
    $("#ps-prev").click(function () {
        imageShowCase.trigger('owl.prev');
    })


    /*=======================================================================================
     Code for equal height - // your div will never broken if text get overflow
     ========================================================================================*/


    function equalHeightDivs() {
        $('.subCategoryList > div').matchHeight({byRow: false})
        $('.featuredImgLook2 .inner').matchHeight({byRow: false})
        $('.featuredImageLook3 .inner').matchHeight({byRow: false})
    };
    equalHeightDivs();


    /* testing page code only, you wont need this! */

    productContainer.each(function () {
        $(this).children('.item').matchHeight({
            byRaw: true
        });
    });


    var equalHeightUpdate = function () {
        // equal height reload function

        productContainer.each(function () {
            $(this).children('.item').matchHeight({
                remove: true
            });
        });

        setTimeout(function () {
                //  reload function after 0.5 second
                productContainer.each(function () {
                    $(this).children('.item').matchHeight({
                        byRaw: true
                    });
                });
            }
            , 500);
        // update all heights
        $.fn.matchHeight._update();
    };


    window.addEventListener("orientationchange", function () {
        // ipad, tab orientation
        equalHeightUpdate();
    }, false);


    if (!isMobile()) {
        // avoid touch event issue on resize
        $(window).resize(function () {
            equalHeightUpdate();
            console.log('resized')
        });
    }

    //if you need to call it at page load to resize elements etc.

    (function () {
        /* matchHeight category product  */
        $(function () {
            // apply matchHeight to each item container's items
            $('.equalheightItem').each(function () {
                $(this).children('.item').matchHeight({
                    byRow: true
                });
            });

        });

    })();


    /*==================================
     Customs Script
     ====================================*/

    // Product Details Modal Change large image when click thumb image
    $(".modal-product-thumb a").click(function () {
        var largeImage = $(this).find("img").attr('data-large');
        $(".product-largeimg").attr('src', largeImage);
        $(".zoomImg").attr('src', largeImage);
    });


    // Support

    $("#accordionNo .panel-collapse").each(function () {

        $(this).on('hidden.bs.collapse', function () {
            // do something…
            $(this).parent().find('.collapseWill').removeClass('active').addClass('hasPlus');
        });

        $(this).on('show.bs.collapse', function () {
            // do something…

            $(this).parent().find('.collapseWill').removeClass('hasPlus').addClass('active');
        });


    });


    $(".getFullSearch").on('click', function (e) {
        $('.search-full').addClass("active"); //you can list several class names 
        e.preventDefault();
    });

    $('.search-close').on('click', function (e) {
        $('.search-full').removeClass("active"); //you can list several class names 
        e.preventDefault();
    });


    // Customs tree menu script
    $(".dropdown-tree-a").click(function () { //use a class, since your ID gets mangled
        $(this).parent('.dropdown-tree').toggleClass("open-tree active"); //add the class to the clicked element
    });


    // NEW TREE MENU


    $(function () {
        var navTree = $('.nav-tree li:has(ul)');
        var navTreeA = navTree.addClass('parent_li').find(' > a');

        navTreeA.each(function () {

            if ($(this).hasClass("child-has-open")) {

            } else {
                $(this).addClass("child-has-close");
                var navTreeAchildren = $(this).parent('li.parent_li').find(' > ul > li');
                navTreeAchildren.hide();
            }

        });


        $('.nav-tree li.parent_li > a').on('click', function (e) {
            var children = $(this).parent('li.parent_li').find(' > ul > li');
            if (children.is(":visible")) {
                children.hide('fast');
                $(this).addClass('child-has-close').removeClass('child-has-open');

            } else {
                children.show('fast');
                $(this).addClass('child-has-open').removeClass('child-has-close');
            }
            e.stopPropagation();
        });
    });

    // Add to Wishlist Click Event	 // Only For Demo Purpose	

    $('.add-fav').click(function (e) {
        e.preventDefault();
        $(this).addClass("active"); // ADD TO WISH LIST BUTTON 
        $(this).attr('data-original-title', 'Added to Wishlist');// Change Tooltip text
    });

    // List view and Grid view

    $(".change-view .list-view, .change-view-flat .list-view").click(function (e) { //use a class, since your ID gets mangled
        e.preventDefault();
        $('.categoryProduct > .item').addClass("list-view"); //add the class to the clicked element
        $('.add-fav').attr("data-placement", $(this).attr("left"));
    });

    $(".change-view .grid-view, .change-view-flat .grid-view").click(function (e) { //use a class, since your ID gets mangled
        e.preventDefault();
        $('.categoryProduct > .item').removeClass("list-view"); //add the class to the clicked element

        setTimeout(function () {
            }
            , 300);
    });

    // v 7
    $('div.has-equal-height-child .product-item ').detectGridColumns();
    setTimeout(function () {
            //  reload function after 0.5 second
            $('div.has-equal-height-child .product-item').responsiveEqualHeightGrid();
        }
        , 500);
    $('ul.myAccountList > li').responsiveEqualHeightGrid();

    $('div.has-equal-height-child > div.is-equal').responsiveEqualHeightGrid();


    // product details color switch
    $(".swatches li").click(function () {
        $(".swatches li.selected").removeClass("selected");
        $(this).addClass('selected');
    });

    $(".product-color a").click(function () {
        $(".product-color a").removeClass("active");
        $(this).addClass('active');

    });

    // Modal thumb link selected
    $(".modal-product-thumb a").click(function () {
        $(".modal-product-thumb a.selected").removeClass("selected");
        $(this).addClass('selected');

    });


    if (/IEMobile/i.test(navigator.userAgent)) {
        // Detect windows phone//..
        $('.navbar-brand').addClass('windowsphone');
    }


    // top navbar IE & Mobile Device fix

    if (isMobile()) {
        // For  mobile , ipad, tab
        $('.introContent').addClass('ismobile');


    } else {


        $(function () {

            //Keep track of last scroll
            var tshopScroll = 0;
            $(window).scroll(function (event) {
                //Sets the current scroll position
                var ts = $(this).scrollTop();
                //Determines up-or-down scrolling
                if (ts > tshopScroll) {
                    // downward-scrolling
                    $('.navbar').addClass('stuck');

                } else {
                    // upward-scrolling
                    $('.navbar').removeClass('stuck');
                }

                if (ts < 600) {
                    // downward-scrolling
                    $('.navbar').removeClass('stuck');
                    //alert()
                }


                tshopScroll = ts;

                //Updates scroll position

            });
        });


    } // end Desktop else


    /*==================================
     Parallax
     ====================================*/
    if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
        // Detect ios User // 
        $('.parallax-section').addClass('isios');
        $('.navbar-header').addClass('isios');
        $('.blog-intro').addClass('isios');
        $('.product-story-hasbg').addClass('isios');
    }

    if (/iPhone/i.test(navigator.userAgent)) {
        // Detect ios User //
        $('.product').addClass('isIphone');
    }

    if (/Android|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        // Detect Android User // 
        $('.parallax-section').addClass('isandroid');
    }

    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        // Detect Mobile User // No parallax
        $('.parallax-section').addClass('ismobile');
        $('.parallaximg').addClass('ismobile');


    } else {
        // All Desktop 
        $(window).bind('scroll', function (e) {
            parallaxScroll();
        });

        function parallaxScroll() {
            var scrolledY = $(window).scrollTop();
            var sc = ((scrolledY * 0.3)) + 'px';
            $('.parallaximg').css('marginTop', '' + ((scrolledY * 0.3)) + 'px');
            $('.parallax-windowz').css('background-position', 'center -' + ((scrolledY * 0.1)) + 'px');
        }


        if ($(window).width() < 768) {
        } else {
            $('.parallax-image-aboutus').parallax("50%", 0, 0.15, true);
            $('.about-3').parallax("50%", 0, 0.2, true);
            $('.parallaxbg').parallax("50%", 0, 0.2, true);
        }


    }


    /*==================================
     Custom Scrollbar for Dropdown Cart
     ====================================*/
    $(".scroll-pane").mCustomScrollbar({
        advanced: {
            updateOnContentResize: true

        },

        scrollButtons: {
            enable: false
        },

        mouseWheelPixels: "200",
        theme: "dark-2"

    });


    $(".smoothscroll").mCustomScrollbar({
        advanced: {
            updateOnContentResize: true

        },

        scrollButtons: {
            enable: false
        },

        mouseWheelPixels: "100",
        theme: "dark-2"

    });


    /*==================================
     Custom  animated.css effect
     ====================================*/

    window.onload = (function () {
        $(window).scroll(function () {
            if ($(window).scrollTop() > 86) {
                // Display something
                $('.parallax-image-aboutus .animated').removeClass('fadeInDown');
                $('.parallax-image-aboutus .animated').addClass('fadeOutUp');
            } else {
                // Display something
                $('.parallax-image-aboutus .animated').addClass('fadeInDown');
                $('.parallax-image-aboutus .animated').removeClass('fadeOutUp');


            }

            if ($(window).scrollTop() > 250) {
                // Display something
            } else {
                // Display something

            }

        })
    })


    /*=======================================================================================
     Code for tablet device || responsive check
     ========================================================================================*/

    $(window).bind('resize load', function () {
        if ($(this).width() < 767) {

            $("#accordionNo .panel-collapse:not(#collapseCategory)").collapse('hide');

        }

    }); // end resize load


    $(".tbtn").click(function () {
        $(".themeControll").toggleClass("active");
    });


    /*==================================
     Global Plugin
     ====================================*/

    // customs select by select2

    // $("select").minimalect(); // REMOVED with  selct2.min.js

    $(document).ready(function () {
   //     $('select.form-control').select2(); -- COMMENTED BY NOUR
    });

    // cart quantity changer sniper
    $("input[name='quanitySniper']").TouchSpin({
        buttondown_class: "btn btn-link",
        buttonup_class: "btn btn-link"
    });


    // bootstrap tooltip
    // $('.tooltipHere').tooltip();
    $('.tooltipHere').tooltip('hide')


    // dropdown-menu  Fix || Stop just one dropdown toggle from closing on click

    var options = [];

    $(".subscribe-dropdown .dropdown-menu div, .dropdown-menu input[type='radio'], .subscribe-dropdown  .dropdown-menu input[type='checkbox'], .subscribe-dropdown .dropdown-menu input[type='button']").on('click', function (event) {

        var $target = $(event.currentTarget),
            val = $target.attr('data-value'),
            $inp = $target.find('input'),
            idx;

        if (( idx = options.indexOf(val) ) > -1) {
            options.splice(idx, 1);
            setTimeout(function () {
                $inp.prop('checked', false)
            }, 0);
        } else {
            options.push(val);
            setTimeout(function () {
                $inp.prop('checked', true)
            }, 0);
        }

        $(event.target).blur();

        console.log(options);
        return false;
    });


    // scroll to certain anchor/div

    $(".scrollto").click(function (event) {
        event.preventDefault();
        //calculate destination place
        var dest = 0;
        if ($(this.hash).offset().top > $(document).height() - $(window).height()) {
            dest = $(document).height() - $(window).height();
        } else {
            dest = $(this.hash).offset().top;
        }
        //go to destination
        $('html,body').animate({scrollTop: dest - 51}, 1000, 'swing');
    });

    /*=======================================================================================
     PRODUCT DETAILS 5 Customs Script
     ========================================================================================*/

    $(document).ready(function () {
        $('.swatches-rounded a').click(function () {
            var colorName = $(this).attr('title');
            $('.color-value').html(colorName)

        });


        // Modal Event


        $('#productSetailsModalAjax').on('shown.bs.modal', function () {
            $(this).removeData('bs.modal');
        });

        $('#productSetailsModalAjax').on('loaded.bs.modal', function (e) {
            // Do ajax modal is loaded

            // Product Details Modal Change large image when click thumb image
            $(".modal-product-thumb a").click(function () {
                // alert();

                $(".modal-product-thumb a.selected").removeClass("selected");
                $(this).addClass('selected');
                var largeImage = $(this).find("img").attr('data-large');
                $(".product-largeimg").attr('src', largeImage);
                $(".zoomImg").attr('src', largeImage);
            });

            // product details color switch
            $(".swatches li").click(function () {
                $(".swatches li.selected").removeClass("selected");
                $(this).addClass('selected');
            });

            $('.swatches-rounded a').click(function () {
                var colorName = $(this).attr('title');
                $('.color-value').html(colorName)

            });

        })


    });


    /*=======================================================================================
     static search box with dropdown menu
     ========================================================================================*/

    $(function () {

        $(".search-menu-list .search-menu-list-ul li a").click(function () {
            $(".btn .search-list-tag").text($(this).text());
        });

    });

    /*=======================================================================================
     CATEGORY 2
     ========================================================================================*/

    $('.shrtByP a').click(function () {
        $('.shrtByP a').removeClass('active');
        $(this).addClass('active');
    });

    $('.filterToggle').click(function () {
        $(this).toggleClass('filter-is-off');
        $('.filterToggle span').toggleClass('is-off');
        $('.catColumnWrapper').toggleClass('filter-is-off');
        $('.catColumnWrapper .col-sm-4').toggleClass('col-sm-3 col-lg-3 col-md-3');

        // reload product equal height
        equalHeightUpdate();

    });

    $(window).bind('resize load', function () {

        if ($(this).width() < 767) {
            $('#accordion.panel-group .panel-collapse').collapse('hide');

            $('#accordion.panel-group .panel-heading ').find('span').removeClass('hasMinus').addClass('hasPlus');

        } else {
            $('#accordion.panel-group .panel-collapse').removeClass('out').addClass('in').css('height', 'auto');
            $('#accordion.panel-group .panel-heading ').find('span').removeClass('hasPlus').addClass('hasMinus');


        }
    });


    $('[data-toggle="collapse"]').click(function (e) {

        $('#accordion.panel-group').on('show.bs.collapse', function (e) {
            $(e.target).prev().addClass('active').find('span').removeClass('hasPlus').addClass('hasMinus');
        })

        $('#accordion.panel-group').on('hide.bs.collapse', function (e) {
            $(e.target).prev().addClass('active').find('span').addClass('hasPlus').removeClass('hasMinus');

        })
    });

    /*=======================================================================================
     end
     ========================================================================================*/


}); // end Ready


// onclick event for tablet
var touchTrigger = 0;
// onclick event for tablet
window.addEventListener('touchstart', function() {
    // the user touched the screen!
    // console.log('touch')

    if (touchTrigger == 0) {
        if ($(window).width() > 766) {

            $(document).click(function () {
                $(".navbar li.dropdown .dropdown-menu").hide();
            });

            $(".navbar  li.dropdown").click(function (e) {
                e.stopPropagation();
                $('.navbar li.dropdown').removeClass('menu-open');
                $(this).addClass('menu-open');
                $(this).find('.dropdown-menu').show();
            });

            $(".navbar li.dropdown.menu-open").click(function (e) {
                e.stopPropagation();
                $('.navbar li.dropdown').removeClass('menu-open');
                $(this).find('.dropdown-menu').hide();
            });
        }

        touchTrigger = 1;
    }
});


;(function($){
  
  /**
   * jQuery function to prevent default anchor event and take the href * and the title to make a share popup
   *
   * @param  {[object]} e           [Mouse event]
   * @param  {[integer]} intWidth   [Popup width defalut 500]
   * @param  {[integer]} intHeight  [Popup height defalut 420]
   * @param  {[boolean]} blnResize  [Is popup resizeabel default true]
   */
  $.fn.customerPopup = function (e, intWidth, intHeight, blnResize) {
    
    // Prevent default anchor event
    e.preventDefault();
    
    // Set values for window
    intWidth = intWidth || '500';
    intHeight = intHeight || '420';
    strResize = (blnResize ? 'yes' : 'no');

    // Set title and open popup with focus on it
    var strTitle = ((typeof this.attr('title') !== 'undefined') ? this.attr('title') : 'Social Share'),
        strParam = 'width=' + intWidth + ',height=' + intHeight + ',resizable=' + strResize,            
        objWindow = window.open(this.attr('href'), strTitle, strParam).focus();
  }
  
  /* ================================================== */
  
    $(document).ready(function ($) {
        $('.customer.share').on("click", function(e) {
            $(this).customerPopup(e);
        });
    });

    $('[data-toggle="slide-collapse"]').on('click', function() {
        $navMenuCont = $($(this).data('target'));
        $navMenuCont.animate({
            'width': 'toggle'
        }, 350);
        $(".menu-overlay").fadeIn(500);

    });
    
    $(".menu-overlay").click(function(event) {
        $(".navbar-toggle").trigger("click");
        $(".menu-overlay").fadeOut(500);
    });

    $('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
        if (!$(this).next().hasClass('show')) {
            $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
        }
        var $subMenu = $(this).next(".dropdown-menu");
        $subMenu.toggleClass('show');
        $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
            $('.dropdown-submenu .show').removeClass("show");
        });
        return false;
    });

    $('.show-hide').click(function() {
        $("div[class*='side-filter']").toggleClass('toggle');
    });

    var didScroll;
    var lastScrollTop = 0;
    var delta = 5;
    var navbarHeight = $('.navbar-collapse').outerHeight();

    $(window).scroll(function(event){
        didScroll = true;
    });

    setInterval(function() {
        if (didScroll) {
            hasScrolled();
            didScroll = false;
        }
    }, 250);

    function hasScrolled() {
        var st = $(this).scrollTop();
        
        // Make sure they scroll more than delta
        if(Math.abs(lastScrollTop - st) <= delta)
            return;
        
        // If they scrolled down and are past the navbar, add class .nav-up.
        // This is necessary so you never see what is "behind" the navbar.
        if (st > lastScrollTop && st > navbarHeight){
            // Scroll Down
            $('.navbar-collapse').removeClass('nav-down').addClass('nav-up');
        } else {
            // Scroll Up
            if(st + $(window).height() < $(document).height()) {
                $('.navbar-collapse').removeClass('nav-up').addClass('nav-down');
            }
        }
        
        lastScrollTop = st;
    }

    
}(jQuery));