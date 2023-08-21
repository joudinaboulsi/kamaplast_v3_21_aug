<?php use App\Http\Controllers\Frontend\ProductsController; ?>

@extends('frontend.layouts.app')

@section('content')

<div class="container main-container headerOffset">

    <div class="row">
        <div class="breadcrumbDiv col-xs-12">
            <ul class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="{{ route('user_account_path',$order_details[0]->user_id) }}">{{$order_details[0]->user_name}}</a></li>
                <li class="active"> Order #{{$order_details[0]->order_id}}</li>
            </ul>
        </div>
    </div> 

    <div class="row userInfo">

        <div class="statusContent">

            <div class="col-sm-12">
                <div class=" statusTop">
                    <p><strong>Order Number:</strong> #{{$order_details[0]->order_id}} </p>
                    <p><strong>Order Date:</strong> {{ date("d M Y - h:ia", strtotime($order_details[0]->created_at))}}</p>
                    <p><strong>Shipping Status:</strong> <span class="label label_color" style="color:white; background:{{$order_details[0]->shipping_status_color}}"> {{ $order_details[0]->shipping_status }} </span></p>
                    @if($order_details[0]->tracking_code != NULL)
                        <p><strong>Tracking code:</strong> #{{$order_details[0]->tracking_code}} </p>
                    @endif
                </div>
            </div>

            <div class="col-sm-6">
                <div class="order-box">
                    <div class="order-box-header">
                        Shipping Address
                    </div>

                    <div class="order-box-content">

                        <div class="address">
                            <p><strong>Shipping Name :</strong> 
                                {{$order_details[0]->shipping_name}}
                            </p>

                            <p><strong>Shipping Address :</strong>
                                {{$order_details[0]->shipping_address}}
                            </p>

                            <p><strong>Shipping Phone :</strong>
                                {{$order_details[0]->shipping_phone}}
                            </p>
                        </div>

                    </div>
                </div>

            </div>
            <div class="col-sm-6">
                <div class="order-box">
                    <div class="order-box-header">
                        Billing Address
                    </div>

                    <div class="order-box-content">
                        <div class="address">
                            <p><strong>Billing Name :</strong> 
                                {{$order_details[0]->billing_name}}
                            </p>

                            <p><strong>Billing Address :</strong>
                                {{$order_details[0]->billing_address}}
                            </p>

                            <p><strong>Billing Phone :</strong>
                                {{$order_details[0]->billing_phone}}
                            </p>
                        </div>
                    </div>

                </div>
            </div>


            <div class="col-sm-6">
                <div class="order-box">
                    <div class="order-box-header">
                        Aramex Shipping information
                    </div>

                    <div class="order-box-content">
                        <div class="address">
                            <p><strong>Tracking Number :</strong> 
                                {{$order_details[0]->aramex_tracking_number}}
                            </p>

                            <p><strong>Foreign Ref:</strong>
                                {{$order_details[0]->aramex_foreign_ref}}
                            </p>

                            <p><strong>Receipt Label <i class="fa fa-file-pdf-o" aria-hidden="true"></i> :</strong>
                                <a style="text-decoration:underline;" target="_blank" href="{{$order_details[0]->aramex_pdf_receipt}}"> Label PDF Receipt  </a>
                            </p>
                        </div>
                    </div>

                </div>
            </div>


            <div class="col-sm-6">
                <div class="order-box">
                    <div class="order-box-header">
                        Payment Method
                    </div>

                    <div class="order-box-content">
                        <div class="address">
                            <p>
                                <strong>Payment Method: </strong>{{ $order_details[0]->payment_method }} 
                                <span class="label label_color" style="color:white; background:{{$order_details[0]->payment_status_color}}"> {{ $order_details[0]->payment_status }} </span> 
                            </p>
                        </div>
                    </div>
                </div>
            </div>

         <!--    <div class="col-sm-6">
                <div class="order-box">
                    <div class="order-box-header">
                        Shipping Method
                    </div>

                    <div class="order-box-content">
                        <div class="address">
                            <p> <b>Post Air Mail</b> <a title="tracking number" href="#" target="_blank">#4502</a></p>

                            <p><strong>Ruth F. Burns </strong></p>

                            <p class="adr">4894 Burke Street North Billerica, MA 01862</p>
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="col-sm-12 clearfix">
                <div class="order-box">
                    <div class="order-box-header">
                        Order Items
                    </div>


                    <div class="order-box-content">
                        <div class="table-responsive">
                            <table class="order-details-cart">
                                <tbody>
                                    <tr>
                                        <th style="width:20%"> Product</th>
                                        <th style="width:40%"> Details</th>
                                        <th style="width:10%"> Quantity</th>
                                        <th style="width:15%; text-align:right;"> Total</th>
                                    </tr>

                                    @foreach($order_items as $item)

                                    <?php //W generate seo url link
                                        $seo_link = ProductsController::generateSeoUrlLink($item->product_name, $item->product_id);
                                    ?>

                                    <tr class="cartProduct">
                                        <td class="cartProductThumb" style="width:20%">
                                            <div><a href="{{ route('product_details_path', $seo_link) }}">
                                                <img alt="img" src="{{getenv('S3_URL')}}/products/thumbs/{{$item->product_img}}">
                                            </a></div>
                                        </td>
                                        <td style="width:40%">
                                            <div class="miniCartDescription">
                                                <h4><a href="{{ route('product_details_path', $seo_link) }}"> {{$item->product_name}} </a></h4>
                                                <div class="price">
                                                    <span>
                                                    @if($item->sales_price != NULL) 
                                                        ${{$item->sales_price}} <small style="font-weight:normal;"><strike>${{$item->regular_price}}</strike></small>
                                                    @else
                                                        ${{$item->regular_price}}
                                                    @endif
                                                    </span>
                                            </div>
                                            </div>
                                        </td>
                                        <td style="width:10%"><a> x {{$item->quantity}} </a></td>
                                        <td style="width:15%; text-align:right;"><span> <b>${{$item->total_price}} </b></span></td>
                                    </tr>

                                    @endforeach
                                
                                    <tr class="cartTotalTr blank">
                                        <td style="width:20%">
                                            <div></div>
                                        </td>
                                        <td style="width:40%"></td>
                                        <td style="width:20%"></td>
                                        <td style="width:15%"><span> </span></td>

                                    </tr>

                                    <tr class="cartTotalTr">
                                        <td style="width:20%">
                                            <div></div>
                                        </td>
                                        <td colspan="2" style="width:40%">SubTotal</td>
                                        <td style="width:15%"><span> ${{$order_details[0]->subtotal}} </span></td>

                                    </tr>
                                    <tr class="cartTotalTr">
                                        <td style="width:20%">
                                            <div></div>
                                        </td>
                                        <td colspan="2" style="width:40%">VAT (%{{$order_details[0]->vat}})</td>
                                        <?php 
                                            $vat = $order_details[0]->vat; 
                                            $vat = $order_details[0]->subtotal * ($vat/100);
                                            $vat = number_format($vat,"2",".","'");

                                        ?> 
                                        <td style="width:15%"><span>${{ $vat }}</span></td>

                                    </tr>
                                    <tr class="cartTotalTr">
                                        <td style="width:20%">
                                            <div></div>
                                        </td>
                                        <td colspan="2" style="width:40%">Shipping Fees</td>
                                        <td style="width:15%"><span> ${{$order_details[0]->shipping_fees}}</span></td>
                                    </tr>

                                    @if($order_details[0]->promo_code_id != NULL)
                                    <?php  
                                        if($order_details[0]->discount_percentage != NULL)
                                        {
                                            $discount_type = '('.$order_details[0]->discount_percentage.'%)';
                                            $discount_value = $order_details[0]->discount_percentage_value;
                                        }

                                        else
                                        {
                                            $discount_type = '';
                                            $discount_value = $order_details[0]->discount_value;
                                        }
                                    ?>
                                    <tr class="cartTotalTr">
                                        <td style="width:20%">
                                            <div></div>
                                        </td>
                                        <td colspan="2" style="width:40%; color:#28a745;">Discount {{$discount_type}}</td>
                                        <td style="width:15%; color:#28a745"><span>- ${{$discount_value}}</span></td>
                                    </tr>
                                    @endif

                                    <tr class="cartTotalTr">
                                        <td style="width:20%">
                                            <div></div>
                                        </td>
                                        <td style="width:40%"></td>
                                        <td style="width:20%; font-size:18px;"><b>Total</b></td>
                                        <td style="width:15%"><span class="price"> ${{$order_details[0]->total}} </span></td>

                                    </tr>


                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-xs-12 text-center">
            <a href="{{ route('user_account_path',$order_details[0]->user_id) }}" class="btn btn-stroke thin dark"> Back to My Account</a>
        </div>

    </div>
    <!--/row end-->

    <div style="clear:both"></div>
</div>
<!-- /main-container -->

<div class="gap"></div>


@endsection