@extends('frontend.layouts.app')

@section('content')


<!-- Collections  -->
<section class="section-hero white-bg" id="section-collections">
    <div class="container">

        <div class="about-content-text text-center">
            <h3>Collections</h3>
        </div>

        <div class="row">
            @foreach($data['collections'] as $c)
             <div class="block-explore col-sm-4 col-xs-6">
                <div class="inner">
                    <a class="promotion" href="{{ route('product_of_collection_path',array($c->url_tag_name, $c->tag_id)) }}">
                        <span class="categ">{{$c->name}}</span>
                    </a>
                    <a href="{{ route('product_of_collection_path',array($c->url_tag_name, $c->tag_id)) }}" class="img-block">
                        <img alt="{{$c->name}}" src="{{getenv('S3_URL')}}/tags/{{$c->img}}" loading="lazy" class="img-responsive">
                    </a>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>
<!-- End Collections  -->


@endsection