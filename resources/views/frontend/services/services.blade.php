@extends('frontend.layouts.app')

@section('content')

<!-- Page Header  -->
<div class="parallax-section parallax-fx about-3 parallaxOffset no-padding" style="background-image: url(images/about/1.jpg); ">
    <div class="w100 ">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="parallax-content clearfix  wow fadeInUp" data-wow-duration=".8s" data-wow-delay="0">
                        <h2 class="intro-heading ">Services </h2>
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

                <!-- Service  -->
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="inner">
                        <h3>Coaching</h3>
                        <p>We are very pleased to announce the availability of many new emerging designers</p>
                        <a class="btn btn-link" href="{{ route('service_details_path') }}"> See More <i class="fa fa-long-arrow-right"></i></a>
                    </div>
                </div>
                <!-- End Service  -->

                <!-- Service  -->
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="inner">
                        <h3>Education & Training</h3>
                        <p>We are very pleased to announce the availability of many new emerging designers</p>
                        <a class="btn btn-link" href="{{ route('service_details_path') }}"> See More <i class="fa fa-long-arrow-right"></i></a>
                    </div>
                </div>
                <!-- End Service  -->

                <!-- Service  -->
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="inner">
                        <h3>Salon Design</h3>
                        <p>We are very pleased to announce the availability of many new emerging designers</p>
                        <a class="btn btn-link" href="{{ route('service_details_path') }}"> See More <i class="fa fa-long-arrow-right"></i></a>
                    </div>
                </div>
                <!-- End Service  -->

            </div>
        </div>
    </div>
</div>
<!-- End Page Content  -->

<div class="gap"></div>

@endsection