<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! SEO::generate() !!}

    <!-- Bootstrap core CSS -->
    <link href="/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- sweetalert CSS -->
    <link href="/cms/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/home-v7.css" rel="stylesheet">
    <link href="/css/cart-nav.css" rel="stylesheet">
    <link href="/css/category-2.css" rel="stylesheet">
    <link href="/css/product-details-5.css" rel="stylesheet">
    <link href="/css/blog.css" rel="stylesheet">
    <link href="/css/style_added.css" rel="stylesheet">
    <link href="/css/custom_style.css" rel="stylesheet">
    <link href="/css/search_bar.css" rel="stylesheet">

    <!-- CSS dedicated to the product details page where user selects the attributes dynamically -->
    <link href="/css/product-details-attribute-selection.css" rel="stylesheet">

    <!-- gall-item Gallery for gallery page -->
    <link href="/plugins/magnific/magnific-popup.css" rel="stylesheet">
    @csrf

    <!-- Just for debugging purposes. -->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <!-- include pace script for automatic web page progress bar  -->
    <script src="/js/jquery/jquery-2.1.3.min.js"></script>

    <script>
        paceOptions = {
            elements: true
        };
    </script>
    <script src="/js/pace.min.js"></script>
    
    <meta name="google-site-verification" content="ZCINyClFDq7YfixMVeL1nxg6Zg_FYjzmOCA1n5WmREU" />

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-Z77PXEPEPL"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-Z77PXEPEPL');
    </script>
    
    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '725247858715469');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=725247858715469&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->

    <meta name="facebook-domain-verification" content="9oqdkwjbwfhw6a2jlbvscr00gdxrvu" />

</head>
<body>

    @include('frontend.layouts.header2')

    <div id="page-content">
        @yield('content')
    </div>

    @include('frontend.layouts.footer')

    <!-- Newsletter Modal -->
    <!-- @if(Route::currentRouteName() == 'home_path')
    <div class="modal fade" id="modalAds" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color:#333;margin-right:10px;"> ×</button>

                <div class="col-lg-6 col-sm-8 col-xs-12">
                    <h3>enter your <br>email to receive</h3>

                    <p class="discountLg">10% OFF </p>

                    <p>our journey as a #hairtoolsguru and be the first to know about our latest news!</p>

                    <div class="clearfx">
                        <form id="newsletter" class="newsletter">
                            <input type="text" id="subscribe" name="s" placeholder="Enter email">
                            <button class="subscribe-btn">Subscribe</button>
                        </form>
                    </div>

                    <p><a href="" class="link shoplink"> SHOP NOW <i class="fa fa-caret-right"> </i> </a> </p>

                </div>
                <div class="col-lg-6 col-sm-4 hidden-xs">
                    <img src="images/modal.jpg" class="img-responsive" alt="Newsletter Modal Image">
                </div>

            </div>
        </div>
    </div>
    @endif -->
    <!-- End Newsletter Modal -->

    <!-- Javascript
    ================================================== -->
    <script type="text/javascript">
        var s3_url = <?=json_encode(getenv('S3_URL')) ?>;
        var product_details_route = '{{ route("product_details_path", ":product_id") }}';

    </script>
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/bootstrap/js/bootstrap.min.js"></script>

    <!-- PRODUCT DETAILS PAGE SCRIPTS -->
    <script src="https://code.jquery.com/jquery-migrate-1.2.1.js"></script>

    <!-- include jqueryCycle plugin -->
    <script src="/js/jquery.cycle2.min.js"></script>

    <!-- include easing plugin -->
    <script src="/js/jquery.easing.1.3.js"></script>

    <!-- include  parallax plugin -->
    <script src="/js/jquery.parallax-1.1.js"></script>

    <!-- optionally include helper plugins -->
    <script src="/js/helper-plugins/jquery.mousewheel.min.js"></script>

    <!-- include mCustomScrollbar plugin //Custom Scrollbar  -->

    <script src="/js/jquery.mCustomScrollbar.js"></script>

    <!-- include grid.js // for equal Div height  -->
    <script src="/plugins/jquery-match-height-master/dist/jquery.matchHeight-min.js"></script>

    <!-- include smoothproducts // product zoom plugin  -->
    <script type="text/javascript" src="/plugins/smoothproducts-master/js/smoothproducts_custom_nour.js"></script>

    <script type="text/javascript">
        /* wait for images to load */
        $(window).load(function() {
            $('.sp-wrap').smoothproducts();
        });
    </script>

    <script src="/js/grids.js"></script>

    <!-- include carousel slider plugin  -->
    <script src="/js/owl.carousel.min.js"></script>

    <script src="/plugins/magnific/jquery.magnific-popup.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#bx-pager .popup-youtube, #bx-pager .popup-vimeo, #bx-pager .popup-gmaps').click(function (ev) {
                // stop click event in bxslider
                ev.preventDefault();
                ev.stopPropagation();
            });

            $('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
                //  disableOn: 700,
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,
                fixedContentPos: false
            });
        });

    </script>

    <!-- include touchspin.js // touch friendly input spinner component   -->
    <script src="/js/bootstrap.touchspin.js"></script>

    <!-- include custom script for only homepage  -->
    <script src="/js/home.js"></script>

    <!-- include custom script for site  -->
    <script src="/js/script.js"></script>

    <script src="/js/sidebar-nav.js"></script>

    <!-- search bar scirpt -->
    <script src="/js/search_bar.js"></script>

    <!-- scrollme || onscroll parallax effect for category page  -->
    <script src="/js/jquery.scrollme.min.js"></script>
    <script>

        $(function () {
            var target = $("div.has-overly-shade"),
                targetHeight = target.outerHeight();
            $(document).scroll(function () {
                var scrollPercent = (targetHeight - window.scrollY) / targetHeight;
                scrollPercent >= 0 && (target.css("background-color", "rgba(0,0,0," + (1.1 - scrollPercent) + ")"))
            })
        });

        $(function () {
            if (navigator.userAgent.match(/(iPod|iPhone|iPad|Android)/)) {
                $('#ios-notice').removeClass('hidden');
                $('.parallax-container').height($(window).height() * 0.5 | 0);
            } else {
                $(window).resize(function () {
                    var parallaxHeight = Math.max($(window).height() * 0.7, 200) | 0;
                    $('.parallax-container').height(parallaxHeight);
                }).trigger('resize');
            }
        });

        $(document).ready(function () {
            var isMobile = function () {
                //console.log("Navigator: " + navigator.userAgent);
                return /(iphone|ipod|ipad|android|blackberry|windows ce|palm|symbian)/i.test(navigator.userAgent);
            };

            if (isMobile()) {
                // For  mobile , ipad, tab
                $('.animateme').removeClass('animateme');
                $('.if-is-mobile').addClass('ismobile');

            } else {}
        }); // end

        // this script required for subscribe modal
        $(window).load(function () {
            // full load
            $('#modalAds').modal('show');
            $('#modalAds').removeClass('hide');
        });


    // function that decrement the count of the cart from the client side    
    function decrementCartCount()
    {
      current_count = $('.js_cart_nb').html(); // get the actual cart value

      if(current_count != 0)
      {
        current_count = parseInt(current_count); // convert from string to int
        new_count = current_count-1; // decrement
        $('.js_cart_nb').html(new_count); // display the new value
      }
    }    


    // function that increment the count of the cart from the client side    
    function incrementCartCount()
    {
       // if the cart number exist => increment it  
       if ($(".js_cart_nb")[0])
       {
          current_count = $('.js_cart_nb').html(); // get the actual cart value  
          current_count = parseInt(current_count); // convert from string to int
          new_count = current_count+1; // increment 
         $('.js_cart_nb').html(new_count); // display the new value
       }

       // the cart number doesn't exist => create it (desktop and mobile)
       else 
       {
        cart = '<span class="js_cart_nb cart_count hidden-xs">1</span>\
                <span class="js_cart_nb cart_count_responsive visible-xs">1</span>';

        $("#js_cart").prepend(cart);
       }
    }  

    </script>

    <!-- Reveal Animations When You Scroll  -->
    <script src="/js/wow.min.js"></script>
    <script>
        new WOW().init();

        $('#myModal').modal('show');
    </script>


    <!-- Sweet Alert -->
    <script src="/cms/js/plugins/sweetalert/sweetalert.min.js"></script>

    <div class="overlay_for_search" style="display:none;"/>


    
</body>
</html>
