<?php use App\Http\Controllers\Frontend\ProductsController; ?>
@extends('frontend.layouts.app')

@section('content')

<div id="page-content">

    <div class="about-content-text text-center" style="position:relative; top:30px;">
        <h3>{{$data['tag_details'][0]->name}}</h3>
    </div>

    <div class="container main-container headerOffset">
        <div class="row transitionfx">
            <div class="col-lg-12 col-md-12 col-sm-12">
                @foreach($data['product_of_collection'] as $product)
                    <div class="item col-sm-4 col-md-4 col-lg-3 col-xs-6">
                        <?php ProductsController::displayProductElement($product); ?>
                    </div>
                @endforeach  
            </div>
        </div>
        <!--/.row-->
    </div>
</div>

@endsection