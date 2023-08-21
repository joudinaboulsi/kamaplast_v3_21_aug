@extends('frontend.layouts.app')

@section('content')

<!-- Page Content  -->
<div class="blog-wrapper headerOffset">

    <div class="container">
        <div class="row">

            <div class="blog-left">
                <div class="bl-inner">
                    
                    <div class="item-blog-post">

                        <div class="about-content-text text-center">
                            <h3>Media</h3>
                            <p class="lead">
                                All our news and updates.
                            </p>
                        </div>

                    </div>

                    @foreach($media as $m)
                    <!-- Media -->
                    <div class="col-md-4 col-xs-12">
                        <div class="item-blog-post">
                            <div class="post-header clearfix">
                                <h2 class="wow  fadeIn" data-wow-duration="0.2s">
                                    {{$m->title}}
                                </h2>
                            </div>
                            <div class="post-main-view">
                                <div class="post-lead-image media-pic wow fadeInDown" data-wow-duration="0.2s">
                                    <img src="{{getenv('S3_URL').'/blog/'.$m->image}}" class="img-responsive" alt="{{$m->title}}">
                                </div>
                                <div class="post-description  wow fadeInDown" data-wow-duration="0.2s">
                                    {!! htmlspecialchars_decode($m->content) !!}
                                    <i>{{ $m->date }}</i>
                                    @if($m->link) <br><br> <a href="{{ $m->link }}" class="btn btn-stroke dark btn-xs">Read the article</a> @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Media -->
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page Content  -->

<div class="gap"></div>

@endsection