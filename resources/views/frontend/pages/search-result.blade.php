<?php use App\Http\Controllers\Frontend\ProductsController; ?>
@extends('frontend.layouts.app')

@section('content')

<div id="page-content">

    <div class="about-content-text text-center" style="position:relative; top:30px;">
        <h3>Search Results</h3>
    </div>

    <div class="container main-container headerOffset">
        <div class="row transitionfx">
            <div class="col-lg-12 col-md-12 col-sm-12">
                
                @if($data['results'] == NULL)
                 
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Your search <b><i>{{$data['input']}}</i></b> did not match any documents.

                @else

                    @foreach($data['results'] as $product)
                        <div class="item col-sm-4 col-md-4 col-lg-3 col-xs-6">
                            <?php ProductsController::displayProductElement($product);?>
                        </div>
                    @endforeach  

                @endif

            </div>
        </div>
        <!--/.row-->
    </div>

</div>

@endsection