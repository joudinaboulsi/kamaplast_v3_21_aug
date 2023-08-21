<?php use App\Http\Controllers\Frontend\ProductsController; ?>

@extends('frontend.layouts.app')

@section('content')
    <!-- Mobile Categories  -->
    <div class="container-fluid white-bg visible-xs visible-sm">
        <div class="row">
            <div class="col-lg-12 padd-10">
                <ul class="brand-carousel owl-carousel owl-theme">

                    @foreach ($data['highlights'] as $h)
                        <!-- generate SEO URL link -->
                        <?php $seo_link = ProductsController::generateSeoUrlLink($h->name, $h->category_id); ?>

                        <li>
                            <a class="mobile-cat" href="{{ route('product_list_path', $seo_link) }}">
                                <div class="cat-img"><img src="{{ getenv('S3_URL') }}/categories/{{ $h->img }}"
                                        alt="{{ $h->name }}"></div>
                                <p>{{ $h->name }}</p>
                            </a>
                        </li>
                    @endforeach

                </ul>
            </div>
        </div>
    </div>
    <!-- End Mobile Categories-->

    <!-- Slideshow  -->
    <div class="banner">
        <div class="full-container">
            <div class="slider-content">
                <span class="prevControl sliderControl"> <i class="fa fa-angle-left fa-3x "></i> </span>
                <span class="nextControl sliderControl"> <i class="fa fa-angle-right fa-3x "></i> </span>
                <div class="slider slider-v1" data-cycle-swipe="true" data-cycle-prev=".prevControl"
                    data-cycle-next=".nextControl" data-cycle-pause-on-hover="true">

                    @foreach ($data['slides'] as $s)
                        <!-- Slide  -->
                        <div class="slider-item slider-item-img1">
                            @if ($s->title || $s->subtitle)
                                <div class="sliderInfo">
                                    <div class="container">
                                        <div class="col-lg-12 col-md-12 col-sm-12 sliderTextFull">
                                            <div class="inner text-center">
                                                @if ($s->title)
                                                    <h1 class="uppercase">{{ $s->title }}</h1>
                                                    @if ($s->subtitle)
                                                        <h3 class="hidden-xs">{{ $s->subtitle }}</h3>
                                                    @endif
                                                    @if ($s->action)
                                                        <a href="{{ $s->action }}"
                                                            class="btn btn-stroke thin lite">{{ $s->button_name }}</a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($s->action)
                                <a href="{{ $s->action }}">
                            @endif
                            <img src="{{ getenv('S3_URL') . '/slides/' . $s->image }}" loading="lazy" class="parallaximg sliderImg"
                                alt="{{ $s->title }}">
                            @if ($s->action)
                                </a>
                            @endif
                        </div>
                        <!-- End Slide  -->
                    @endforeach

                </div>
            </div>
        </div>
    </div>
    <!-- End Slideshow -->


    <!-- BLOCS DESCRIPTION  -->
    <section class="white-bg" id="section-category">
        <!-- Description Bloc  -->
        <div class="container">
            <div class="about-content-text about-home-text">
                <div class="col-sm-8 col-sm-push-2 text-center">
                    <h1>Since 1982</h1>
                    <p>Kamaplast is the leading plastic manufacturer in Lebanon producing the widest range of plastic
                        products since 1982.</p>
                    <p>The company’s motto is </p>
                    <img src="/images/signature.png" loading="lazy" width="400">
                    <br>
                </div>
                <div class="col-xs-12 text-center">
                    <br><br>
                    <a class="btn btn-stroke btn-dark thin btn-lg shop-online" href="#section-best">Shop online here</a>
                </div>
            </div>
        </div>

        <div class="clear"></div>

        <div class="container-fluid hidden-xs blue-bg">

            <div class="container">

                <div class="about-content-text text-center">
                    <h3 class="colorWhite" style="line-height: 20px">About Kamaplast</h3>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="content-box">
                            <div class="content">
                                <a href="{{ route('about_path') }}" target="_blank">
                                    <div class="content-overlay"></div>
                                    <img class="content-image" loading="lazy" src="/images/1b.jpg">
                                    <div class="content-details fadeIn-bottom">
                                        <p class="content-text">Discover more about Kamaplast. <br>
                                            Kamaplast is the leading plastic manufacturer in Lebanon producing the widest
                                            range of plastic products since 1982.</p>
                                    </div>
                                </a>
                            </div>
                            <h3>Company</h3>
                            <div class="bar"></div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="content-box">
                            <div class="content">
                                <a href="{{ route('odm_path') }}" target="_blank">
                                    <div class="content-overlay"></div>
                                    <img class="content-image" loading="lazy" src="/images/2b.jpg">
                                    <div class="content-details fadeIn-bottom">
                                        <p class="content-text">Discover more about Kamaplast’s services. <br>
                                            If you have a great idea and don't know what to do, Kamaplast is the partner you
                                            are looking for. </p>
                                    </div>
                                </a>
                            </div>
                            <h3>ODM</h3>
                            <div class="bar"></div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="content-box">
                            <div class="content">
                                <a href="{{ route('contact_path') }}" target="_blank">
                                    <div class="content-overlay"></div>
                                    <img class="content-image" loading="lazy" src="/images/3b.jpg">
                                    <div class="content-details fadeIn-bottom">
                                        <p class="content-text">If you would like to speak to us personally,<br>
                                           A member of our team will be more than happy to run through everything we can offer! </p>
                                    </div>
                                 
                                  
                                </a>
                            </div>
                            <h3>Contact us</h3>
                            <div class="bar"></div>
                        </div>
                    </div>
                </div>

            </div>

            <img src="/images/kamaplast.png" loading="lazy" class="kama">

        </div>

    </section>
    <!-- END BLOCS DESCRIPTION  -->




    <!-- Categories  -->
    <section class="section-hero white-bg hidden-xs hidden-sm" id="section-category" loading="lazy"
        style="background:url('images/plastic.png')">
        <br><br><br>
        <div class="container-fluid">

            <div class="about-content-text text-center">
                <h3 style="line-height: 20px">Product Categories<br><a href="{{ route('categories_path') }}"
                        style="font-size: 12px; color: #3451a2">See All Categories</a></h3>
            </div>

            <div class="row">
                @foreach ($data['highlights'] as $h)
                    <!-- generate SEO URL link -->
                    <?php $seo_link = ProductsController::generateSeoUrlLink($h->name, $h->category_id); ?>

                    <!-- Category  -->
                    <div class="col-sm-3 col-xs-6">
                        <div class="hovereffect">
                            <img alt="{{ $h->name }}" src="{{ getenv('S3_URL') }}/categories/{{ $h->img }}"
                                class="img-responsive" style="border-radius: 15px;">
                            <a class="overlay" href="{{ route('product_list_path', $seo_link) }}">
                                <i class="fa fa-cubes fa-4x" style="color:#fddd29"></i><br><br>
                                <span class="categ">{{ $h->name }}</span>
                            </a>
                        </div>
                    </div>
                    <!-- End Category  -->
                @endforeach
            </div>
        </div>
        <br><br>
    </section>
    <!-- End Categories  -->

    @if (!empty($data['deals']) && $data['deals'] != null)
        <!-- Deals -->
        <section class="section-hero" id="section-deals">
            <br>
            <div class="about-content-text text-center hidden-xs">
                <h3 style="line-height: 20px">Daily Deals<br><a href="{{ route('deals_path') }}"
                        style="font-size: 12px; color: #3451a2">See All Deals</a></h3>
            </div>

            <div class="visible-xs">

                <div class="col-xs-6 about-content-text text-left">
                    <h3>Deals</h3>
                </div>

                <div class="col-xs-6 text-right margin-top-10">
                    <a class="btn btn-stroke thin dark" href="{{ route('deals_path') }}">View All</a>
                </div>

            </div>

            <div class="container">
                <div class="row">
                    <div id="dealsSlider" class="owl-carousel owl-theme">
                        @foreach ($data['deals'] as $product)
                            <div class="item">
                                <?php ProductsController::displayProductElement($product); ?>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <br>
        </section>
        <!-- End Deals -->
    @endif


    @if ($data['collections'])
        <!-- Collections  -->
        <section class="section-hero white-bg" id="section-collections" loading="lazy" style="background:url('images/plastic.png')">
            <br><br>
            <div class="container-fluid">

                <div class="about-content-text text-center">
                    <h3 style="line-height: 20px">Our Collections<br><a href="{{ route('collections_path') }}"
                            style="font-size: 12px; color: #3451a2">See All Collections</a></h3>
                </div>

                <div class="row">
                    @foreach ($data['collections'] as $c)
                        <div class="col-sm-4 col-xs-6" style="margin-bottom:30px">
                            <div class="hovereffect">
                                <img alt="{{ $c->name }}" src="{{ getenv('S3_URL') }}/tags/{{ $c->img }}"
                                    class="img-responsive" style="border-radius: 15px;">
                                <a class="overlay"
                                    href="{{ route('product_of_collection_path', [$c->url_tag_name, $c->tag_id]) }}">
                                    <i class="fa fa-cubes fa-4x" style="color:#fddd29"></i><br><br>
                                    <span class="categ">{{ $c->name }}</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <br>
        </section>
        <!-- End Collections  -->
    @endif


    @if (
        (!empty($data['best_sellers']) && $data['best_sellers'] != null) ||
            (!empty($data['featured']) && $data['featured'] != null))
        <!-- Best Sellers -->
        <section class="section-hero" id="section-best" style="padding-top: 100px">
            <br>

            <div class="about-content-text text-center">
                <h3 style="line-height: 20px">Best Sellers<br><a href="{{ route('best_sellers_path') }}"
                        style="font-size: 12px; color: #3451a2">See All Best Sellers</a></h3>
            </div>

            <div class="container">
                <div class="row">
                    <div id="productslider" class="owl-carousel owl-theme">
                        <?php
                        $best_sellers = []; // define an empty array
                        if (sizeof($data['best_sellers']) > 0) {
                            // if I have enough best sellers products => use them
                            $best_sellers = $data['best_sellers'];
                        }
                        
                        // if I don't have best sellers products
                        else {
                            // choose the first products from the featured products
                            for ($i = 0; $i < sizeof($data['featured']); $i++) {
                                $best_sellers[$i] = $data['featured'][$i];
                            }
                        }
                        ?>
                        @foreach ($best_sellers as $product)
                            <div class="item">
                                <?php ProductsController::displayProductElement($product); ?>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <br><br>
        </section>
        <!-- End Best Sellers -->

        <?php
        $img = getenv('S3_URL') . '/slides/' . $data['separators'][2]->image;
        ?>
        <!-- Parallax  -->
        <section class="section-hero-parallax parallax-section"
            style="background-image: url({{ $img }}); background-size: cover !important; background-repeat: no-repeat;">
            <div class="overly-shade">
                <div class="container">
                    <div class="hero-parallax-content ">
                        <h3 class="hero-section-title"> {{ $data['separators'][2]->title }} </h3>
                        <p>{{ $data['separators'][2]->subtitle }}</p>
                        <a class="btn btn-stroke thin lite" href="{{ $data['separators'][2]->action }}">Know More</a>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Parallax  -->
    @endif
    <?php /*
       @if (!empty($data['featured']) && $data['featured'] != null)
            <!-- Featured Products -->
            <section class="section-hero">
                <br><br><br>
                <div class="container">
    
                    <div class="about-content-text text-center">
                        <h3>Featured Products</h3>
                    </div>
    
                    <div class="section-content">
                        <div class="row has-equal-height-child">
                            @foreach ($data['featured'] as $product)
                                <div class="item col-sm-4 col-md-3 col-xs-6">
                                    <?php //ProductsController::displayProductElement($product);
                                    ?>
    ?>
    </div>
    @endforeach
    </div>
    </div>

    </div>
    <br><br>
    </section>
    <!-- End Featured Products -->
    @endif */ ?>

    <script type="text/javascript">
        $(".shop-online").click(function() {
            $('html,body').animate({
                    scrollTop: $("#section-best").offset().top
                },
                'slow');
        });
    </script>
@endsection
