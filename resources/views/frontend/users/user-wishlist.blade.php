@extends('frontend.layouts.app')

@section('content')


<!-- Getting today's date -->
 <?php $now = date("Y-m-d"); ?>

{!! Form::open(array('route' => 'add_user_address_path', 'id' => 'add_form', 'class' => 'add-form', 'files' => true)) !!}
{!! Form::close() !!}


<div class="container main-container headerOffset">
    <div class="row">
        <div class="breadcrumbDiv col-lg-12">
            <ul class="breadcrumb">
                <li><a href="index.html">Home</a></li>
                <li><a href="{{ route('user_account_path',$user[0]->id) }}">{{$user[0]->name}}</a></li>
                <li class="active"> Wisthlist</li>
            </ul>
        </div>
    </div>
    <!--/.row-->

    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-7">
            <h1 class="section-title-inner"><span><i class="glyphicon glyphicon-heart"></i> Wisthlist </span></h1>

            <div class="row userInfo">
                <div class="col-lg-12">
                    <h2 class="block-title-2"> Update your wishlist if it has changed. </h2>
                </div>
                <div class="col-xs-12 col-sm-12">
                    <table>
                        <tbody>
                        @foreach($wishlist as $w)
                            <tr class="CartProduct" id="wishlist_rec_{{$w->wishlist_item_id}}">
                                <td style="width:10%" class="CartProductThumb">
                                    <div><a href="{{ route('product_path',$w->product_id) }}"><img src="{{getenv('S3_URL')}}/products/{{$w->img}}" alt="img"></a></div>
                                </td>
                                <td style="width:40%">
                                    <div class="CartDescription">
                                        <h4><a href="{{ route('product_path',$w->product_id) }}"> {{$w->name}} </a></h4>

                                        @if($w->sales_price != null && strtotime($w->sales_price_end_date) >= strtotime($now) )
                                            <div class="price"><span>${{$w->sales_price}}</span> <span class="old-price">${{$w->regular_price}}</span></div>
                                        @else
                                            <div class="price"><span>${{$w->regular_price}}</span></div>
                                        @endif

                                    </div>
                                </td>
                                <td style="width:15%">
                                    <a class="btn btn-primary">
                                        <span class="add2cart"><i class="glyphicon glyphicon-shopping-cart"> </i> Add to cart </span>
                                    </a>
                                </td>
                                <td style="width:40%" class="delete">
                                    <a id="{{$w->wishlist_item_id}}" class="delete_from_wishlist" title="Delete from Wishlist"> 
                                        <i class="glyphicon glyphicon-trash fa-2x"></i> 
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="col-lg-12 clearfix">
                    <ul class="pager">
                        <li class="previous pull-right"><a href="index.html"> <i class="fa fa-home"></i> Go to Shop </a>
                        </li>
                        <li class="next pull-left"><a href="{{ route('user_account_path',$user[0]->id) }}"> &larr; Back to My Account</a></li>
                    </ul>
                </div>

            </div>
            <!--/row end-->

        </div>
        <div class="col-lg-3 col-md-3 col-sm-5"></div>
    </div>
    <!--/.row-->
    <div style="clear:both"></div>
</div>
<!-- /main-container -->

<div class="gap"></div>

<script src="/js/jquery/jquery-2.1.3.min.js"></script>
<script src="/cms/js/custom_functions.js"></script>
<script type="text/javascript">

$(document).ready(function(){

    // ==================== DELETE from Wishlist  =====================
    ajaxDelete('.delete_from_wishlist', 'delete-from-wishlist', 'wishlist_rec_');
});

</script>

@endsection