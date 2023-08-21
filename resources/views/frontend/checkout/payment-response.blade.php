 <?php use App\Http\Controllers\Frontend\ProductsController; ?>

@extends('frontend.layouts.app')

@section('title', 'Payment response')

@section('content')

<div class="container main-container">

    <div class="row">

        <div class="about-content-text text-center thanxContent">

            <h1> Thank you for your order 
            	@if(Auth::check())
            	<a href="{{ route('user_order_status_path',$order_id) }}">#{{$order_id}}</a>
            	@else
            		#{{$order_id}}
            	@endif
            </h1>
            <h4>we'll let you know when your items are on their way</h4>

        </div>

    </div>

    <div class="row">

        <div class="col-md-10 col-md-push-1">
            <div class="userInfo">
                <div class="cartContent w100">

                    <table class="cartTable cartTableBorder w100">
                        <tbody>

                        @foreach($items as $i)

                        <?php //W generate seo url link
                            $seo_link = ProductsController::generateSeoUrlLink($i->product_name, $i->product_id);
                        ?>

                        <tr class="CartProduct">
                            <td class="CartProductThumb">
                                <div>
                                    <a href="{{route('product_details_path', $seo_link)}}">
                                        <img alt="img" src="{{getenv('S3_URL')}}/products/{{$i->product_img}}">
                                    </a>
                                    <span class="small"> (x{{$i->quantity}}) </span>
                                </div>
                            </td>
                            <td>
                                <div class="CartDescription">
                                    <h4 class="no-margin no-padding"><a href="{{route('product_details_path', $seo_link)}}">{{$i->product_name}} @if($i->sku != NULL) - {{$i->sku}}  @endif </a></h4>
                                    <span class="size">{{$i->attr_items}}</span>

                                    <div class="price hidden">
                                    	 @if($i->current_price != $i->regular_price) 
                                    	 <span class="small"> <s class='right_align' style="font-weight:normal; font-size:13px; display:block;">${{$i->regular_price}}</s> </span>
							             @endif
							             $<span class="right_align bold" id="item_price_{{$i->variant_id}}" >{{$i->current_price}}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="price">${{$i->current_price * $i->quantity}}</td>
                        </tr>
                        @endforeach
                      
                        </tbody>
                    </table>

                </div>
            </div>   
        </div>
        <!--/col end-->

    </div>

    <!--/row-->

</div>


@endsection