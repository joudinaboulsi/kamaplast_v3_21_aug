@extends('frontend.layouts.app')

@section('content')

<!-- Page Header  -->
<div class="parallax-section parallax-fx about-3 parallaxOffset no-padding" style="background-image: url(images/about/1.jpg); ">
    <div class="w100 ">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="parallax-content clearfix  wow fadeInUp" data-wow-duration=".8s" data-wow-delay="0">
                        <h2 class="intro-heading ">Brand Details </h2>
                        <p> A Quick Overview </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page Header  -->

<!-- Page Content  -->
<div class="blog-wrapper headerOffset">
    <div class="container">
        <div class="row">
            <div class="blog-left">
                <div class="bl-inner">

                    <!-- Brand Description -->
                    <div class="item-blog-post">

                        <div class="about-content-text text-center">
                            <h3>Amazon Keratin</h3>
                        </div>

                        <div class="post-main-view">

                            <div class="post-lead-image wow  fadeIn" data-wow-duration="0.2s" data-wow-delay=".6s">
                                <div id="carousel-id-2" class="carousel slide carousel-fade" data-ride="carousel">
                                    <!-- Indicators -->
                                    <ol class="carousel-indicators">
                                        <li data-target="#carousel-id-2" data-slide-to="0" class="active"></li>
                                        <li data-target="#carousel-id-2" data-slide-to="1"></li>
                                        <li data-target="#carousel-id-2" data-slide-to="2"></li>
                                    </ol>

                                    <!-- Wrapper for slides -->
                                    <div class="carousel-inner" role="listbox">
                                        <div class="item active">
                                            <img src="images/slider/slider1.jpg" alt="Slide Image" class="img-responsive">
                                        </div>
                                        <div class="item">
                                            <img src="images/slider/slider2.jpg" alt="Slide Image" class="img-responsive">
                                        </div>
                                        <div class="item">
                                            <img src="images/slider/slider3.jpg" alt="Slide Image" class="img-responsive">
                                        </div>
                                    </div>

                                    <!-- Controls -->
                                    <a class="left carousel-control" href="#carousel-id-2" role="button" data-slide="prev">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="right carousel-control" href="#carousel-id-2" role="button" data-slide="next">
                                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            </div>

                            <div class="post-description wow  fadeIn" data-wow-duration="0.2s" data-wow-delay=".1s">

                                <b> Amazon Keratin® is a globally acclaimed brand specializing in hair care therapies. Their highly developed Hair Straightening Systems create healthy, frizz-free, and beautiful looking hair.</b>

                                <b>The Amazon Keratin Hair Straightening System is an innovative hair treatment that combines keratin protein and natural ingredients, not only to straighten hair but also to restore its natural health, leaving it with a silky and shiny look.</b>

                                <hr>

                                <h3>Straightening Effect</h3>

                                <p> The special Amazon Keratin® formula is proven to turn unruly curls into shiny and sleek tresses, without losing the hair's natural body. By reducing unwanted volume and eliminating frizz, the Amazon Keratin® Hair Straightening System will leave your hair easier to style in no time. No more spending hours straightening your hair or using straightening products to achieve the desired results. Amazon Keratin® Hair Straightening System has different formulas to treat any type of hair, including damaged or chemically treated hair. </p>

                                <hr>

                                <h3>Restoring Effect</h3>

                                <p>The Amazon Keratin® Hair Straightening System will restore, rebuild and strengthen every single hair by warming the keratin protein into it - leaving your hair stronger, shinier and much softer to the touch. With the Amazon Keratin® Hair Straightening System, you will be able to control your hair easily. No matter how much humidity is in the atmosphere, your hairstyle will look perfect and hold much longer. </p>

                                <hr>

                                <h3>Long-Lasting Effect</h3>

                                <p>One single Amazon Keratin® Hair Straightening treatment will enhance the texture of your hair for a long period of time. By using Amazon Keratin® Maintenance Products, you can enjoy up to 6 months of beautiful, manageable and silky hair.</p>

                            </div>
                        </div>
                    </div>
                    <!-- End Brand Description -->

                    <!-- Categories -->
                    <div class="RecommendedBlog clearfix text-center">

                        <div class="about-content-text text-center">
                            <h3>Categories</h3>
                        </div>

                        <div class="row">

                            <!-- Category -->
                            <div class="block-explore col-sm-4 col-xs-12">
                                <div class="inner">
                                    <a class="promotion" href="{{ route('product_list_path', 10) }}">
                                        <span class="categ">Hair Tools</span>
                                    </a>
                                    <a href="{{ route('product_list_path', 10) }}" class="img-block">
                                        <img alt="Hair Tools" src="images/category/1.jpg" class="img-responsive">
                                    </a>
                                </div>
                            </div>
                            <!-- End Category -->

                            <!-- Category -->
                            <div class="block-explore col-sm-4 col-xs-12">
                                <div class="inner">
                                    <a class="promotion" href="{{ route('product_list_path', 10) }}">
                                        <span class="categ">Hair Accessories</span>
                                    </a>
                                    <a href="{{ route('product_list_path', 10) }}" class="img-block">
                                        <img alt="Hair Accessories" src="images/category/2.jpg" class="img-responsive">
                                    </a>
                                </div>
                            </div>
                            <!-- End Category -->

                            <!-- Category -->
                            <div class="block-explore col-sm-4 col-xs-12">
                                <div class="inner">
                                    <a class="promotion" href="{{ route('product_list_path', 10) }}">
                                        <span class="categ">Hair Extensions</span>
                                    </a>
                                    <a href="{{ route('product_list_path', 10) }}" class="img-block">
                                        <img alt="Hair Extensions" src="images/category/3.jpg" class="img-responsive">
                                    </a>
                                </div>
                            </div>
                            <!-- End Category -->

                        </div>

                    </div>
                    <!-- End Categories -->

                </div>
            </div>
        </div>
    </div>
</div>
<!-- EndPage Content  -->

<div class="gap"></div>

@endsection