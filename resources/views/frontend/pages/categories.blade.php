<?php use App\Http\Controllers\Frontend\ProductsController; ?>
@extends('frontend.layouts.app')

@section('content')


<!-- Categories  -->
<section class="section-hero white-bg" id="section-collections">
    <div class="container">

        <div class="about-content-text text-center">
            <h3>Product Categories</h3>
        </div>

        <div class="row">
            @foreach($categories as $c)

            <!-- generate SEO URL link -->
            <?php $seo_link = ProductsController::generateSeoUrlLink($c->name, $c->category_id); ?>

            <!-- Category  -->
            <div class="block-explore col-sm-3 col-xs-6">
                <div class="inner">
                    <a class="promotion" href="{{ route('product_list_path', $seo_link) }}">
                        <span class="categ">{{$c->name}}</span>
                    </a>
                    <a href="{{ route('product_list_path', $seo_link) }}" class="img-block" style="border-radius: 15px;">
                        @if($c->img == '' || $c->img == null)
                            <img  loading="lazy" alt="{{$c->name}}" src="/images/placeholder-image.png" class="img-responsive" style="border-radius: 15px;">
                        @else
                            <img  loading="lazy" alt="{{$c->name}}" src="{{getenv('S3_URL')}}/categories/{{$c->img}}" class="img-responsive" style="border-radius: 15px;">
                        @endif
                        
                    </a>
                </div>
            </div>
            <!-- End Category  -->
            @endforeach
        </div>

    </div>
</section>
<!-- End Categories  -->


@endsection