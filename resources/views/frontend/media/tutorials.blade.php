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
                            <h3>Tutorials</h3>
                            <p class="lead">
                                We teach you how to do it yourself.
                            </p>
                        </div>

                    </div>

                    @foreach($tutorials as $t)
                    <!-- Video -->
                    <div class="col-md-4 col-xs-12">
                        <div class="item-blog-post">
                            <div class="post-main-view">
                                <div class="post-lead-image  wow fadeInDown" data-wow-duration="0.2s">
                                    <iframe width="100%" height="200" src="{{$t->url}}" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Video -->
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page Content  -->

<div class="gap"></div>

@endsection