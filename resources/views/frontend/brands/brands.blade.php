@extends('frontend.layouts.app')

@section('content')

<!-- Page Header  -->
<div class="parallax-section parallax-fx about-3 parallaxOffset no-padding" style="background-image: url(images/about/1.jpg); ">
    <div class="w100 ">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="parallax-content clearfix  wow fadeInUp" data-wow-duration=".8s" data-wow-delay="0">
                        <h2 class="intro-heading ">Brands </h2>
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
        <div class="width100 section-block  text-center">
            <div class="row featuredImgLook2">

                <!-- Brand -->
                <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="inner" style="height: 414px;">
                        <a href="{{ route('brand_details_path') }}" class="img-link" style="height:100px">
                            <img alt="Amazon Keratin" class="img-responsive" src="images/brand/1.png">
                        </a>

                        <p>We are very pleased to announce the availability of many new emerging designers</p>

                        <a class="btn btn-link" href="{{ route('brand_details_path') }}"> See More <i class="fa fa-long-arrow-right"></i></a>
                    </div>
                </div>
                <!-- End Brand -->

                <!-- Brand -->
                <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="inner" style="height: 414px;">
                        <a href="{{ route('brand_details_path') }}" class="img-link" style="height:100px">
                            <img alt="Facepro" class="img-responsive" src="images/brand/2.png">
                        </a>

                        <p>We are very pleased to announce the availability of many new emerging designers</p>

                        <a class="btn btn-link" href="{{ route('brand_details_path') }}"> See More <i class="fa fa-long-arrow-right"></i></a>
                    </div>
                </div>
                <!-- End Brand -->

                <!-- Brand -->
                <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="inner" style="height: 414px;">
                        <a href="{{ route('brand_details_path') }}" class="img-link" style="height:100px">
                            <img alt="Vern" class="img-responsive" src="images/brand/3.png">
                        </a>

                        <p>We are very pleased to announce the availability of many new emerging designers</p>

                        <a class="btn btn-link" href="{{ route('brand_details_path') }}"> See More <i class="fa fa-long-arrow-right"></i></a>
                    </div>
                </div>
                <!-- End Brand -->

                <!-- Brand -->
                <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="inner" style="height: 414px;">
                        <a href="{{ route('brand_details_path') }}" class="img-link" style="height:100px">
                            <img alt="Davines" class="img-responsive" src="images/brand/4.png">
                        </a>

                        <p>We are very pleased to announce the availability of many new emerging designers</p>

                        <a class="btn btn-link" href="{{ route('brand_details_path') }}"> See More <i class="fa fa-long-arrow-right"></i></a>
                    </div>
                </div>
                <!-- End Brand -->

            </div>

        </div>
    </div>
</div>
<!-- End Page Content  -->

<div class="gap"></div>

@endsection