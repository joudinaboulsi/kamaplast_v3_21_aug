@extends('frontend.layouts.app')

@section('content') 

<!-- Page Header  -->
<div class="parallax-section parallax-fx about-3 parallaxOffset no-padding" style="background-image: url(images/blog/2.jpg); ">
    <div class="w100 ">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="parallax-content clearfix  wow fadeInUp" data-wow-duration=".8s" data-wow-delay="0">
                        <h2 class="intro-heading ">Resources </h2>
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
            <div class="col-md-10 col-centered blog-left">
                <div class="bl-inner">

                    @foreach($news as $n)

                        <!-- Blog -->
                        <div class="item-blog-post">

                            <div class="post-header clearfix">
                                <h2 class="wow  fadeIn  " data-wow-duration="0.2s">
                                    <a href="{{ route('blog_details_path', $n->news_id) }}"> {{$n->title}} </a> <br> <span style="font-size: 14px; color: #3552a3;"><i>{{$n->date}}</i></span>
                                </h2>
                            </div>

                            <div class="post-main-view">

                                <div class="post-lead-image  wow fadeInDown  " data-wow-duration="0.2s">
                                    <a href="{{ route('blog_details_path', $n->news_id) }}">

                                        <img src="{{getenv('S3_URL').'/blog/'.$n->image}}" class="img-responsive" alt="Article Image">
                                    </a>
                                </div>

                                <div class="post-description  wow fadeInDown  " data-wow-duration="0.2s">
                                    {!! str_limit($n->content,$limit = 480, $end = '...')  !!}
                                    <br><br>
                                    
                                    <br><br>
                                    <!-- End Know More -->
                                    <a href="{{ route('blog_details_path', $n->news_id) }}" class="btn btn-more"> See More <i class="fa fa-long-arrow-right"></i> </a>
                                </div>

                            </div>

                        </div>
                    <!-- End Blog -->

                    @endforeach

                   

                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page Content  -->

<div class="gap"></div>

@endsection