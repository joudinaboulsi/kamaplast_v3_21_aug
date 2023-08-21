<?php use App\Http\Controllers\Frontend\ProductsController; ?>
@extends('frontend.layouts.app')

@section('content')

<!-- Categories  -->
<section class="section-hero white-bg" id="section-collections">
    <div class="container">

        <div class="about-content-text text-center">
            <h3>Best Sellers</h3>
        </div>

        <div class="container">
            <div class="row">
                <div id="productslider" class="owl-carousel owl-theme">
                    <?php  
                        $best_sellers =[]; // define an empty array
                        if(sizeof($data['best_sellers']) < 4) // if I don't have enough best sellers products
                        { 
                            // choose the first 4 products from the featured products
                            for($i=0; $i<4; $i++)
                                    $best_sellers[$i] = $data['featured'][$i]; 
                        }

                        else // if I have enough best sellers products => use them
                            $best_sellers = $data['best_sellers'];
                    ?>
                    @foreach($best_sellers as $product)
                        <div class="item">
                            <?php ProductsController::displayProductElement($product); ?>
                        </div>
                    @endforeach
                </div>
            </div>        
        </div>

    </div>
</section>
<!-- End Categories  -->


@endsection