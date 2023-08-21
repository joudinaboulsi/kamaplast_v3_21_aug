@extends('frontend.layouts.app')

@section('content')

<!-- Page Content  -->
<div class="blog-wrapper headerOffset">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-centered blog-left">
                <div class="bl-inner">

                    <!-- Article Details  -->
                    <div class="item-blog-post">

                        <div class="about-content-text text-center">
                            <h3 style="line-height: 20px">{{$news[0]->title}}<br> <span style="font-size: 12px; color: #3552a3">on {{$news[0]->date}}</span></h3>
                        </div>


                        <div class="post-main-view">
                            <div class="post-lead-image wow fadeIn" data-wow-duration="0.2s" data-wow-delay=".6s">
                                <a href=""> 
                                    <img src="{{getenv('S3_URL').'/blog/'.$news[0]->image}}" class="img-responsive" alt="Article Picture">
                                </a>
                            </div>

                            <div class="post-description wow fadeIn" data-wow-duration="0.2s" data-wow-delay=".1s">

                                {!! $news[0]->content !!}

                            </div>
                        </div>
                    </div>
                    <!-- End Article Details  -->



                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page Content  -->

<div class="gap"></div>

@endsection