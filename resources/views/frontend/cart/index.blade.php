<?php use App\Http\Controllers\Frontend\ProductsController; ?>

@extends('frontend.layouts.app')

@section('content')

    @if (Session::has('msg'))
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-xs modal-dialog" style="margin-top:123px">
                <!-- Modal content-->
                <div class="modal-content">

                    <div class="modal-header" style="background:#a94442">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times;</button>
                        <h2 class="text-center" style="position:relative; top:8px;">Error</h2>
                    </div>

                    <div class="modal-body">
                        <p>{!! Session::get('msg') !!}</p>
                    </div>

                </div>

            </div>
        </div>
    @endif

    <div class="container main-container headerOffset">

        <div class="row">
            <div class="about-content-text text-center"><br>
                <h3>Shopping Cart</h3>
            </div>
        </div>
        <!--/.row-->

        @if (!empty($items))
            <div class="row" style="margin-bottom: 60px">

                <div class="col-lg-9 col-md-9 col-sm-8">
                    <div class="userInfo">
                        <!-- <div class="cartContent w100">
                        <table class="cartTable table-responsive w100">
                            <tbody>

                            @foreach ($items as $i)
    <tr class="CartProduct" id="rec_{{ $i->cart_item_id }}">
                                <td class="CartProductThumb" style="width: 15%">
                                    <div>
                                        <a href="{{ route('product_details_path', $i->product_id) }}">
                                            <img src="{{ getenv('S3_URL') }}/products/{{ $i->product_img }}" alt="img">
                                        </a>
                                    </div>
                                </td>
                                <td style="width: 35%">
                                    <div class="CartDescription">
                                        <h4 class="no-padding">
                                            <a href="{{ route('product_details_path', $i->product_id) }}">{{ $i->product_name }} </a>
                                        </h4>
                                        <span class="size" style="color:#777">{{ $i->attr_items }}</span>

                                        <div class="price hidden">
                                            $<span id="item_price_{{ $i->variant_id }}" >{{ floatval($i->current_price) }}</span>
                                            @if ($i->current_price != $i->regular_price)
    <span class="small"> <s style="font-weight:normal; font-size:13px;">${{ floatval($i->regular_price) }}</s> </span>
    @endif
                                        </div>
                                    </div>
                                </td>
                                <td style="width: 25%">
                                    <span class="cart_btn" onclick="decrementQty({{ $i->variant_id }});"> - </span>
                                    <input id="qty_{{ $i->variant_id }}" class="qty_input" min='1' type="number" value="{{ $i->quantity }}" name="qty_{{ $i->variant_id }}" onfocusout="changeQty({{ $i->variant_id }});">
                                    <span class="cart_btn" onclick="incrementQty({{ $i->variant_id }});"> + </span>
                                </td>
                                <td class="price" style="width: 20%">$ <span class="subtotals" id="subtotal_{{ $i->variant_id }}">{{ $i->current_price * $i->quantity }}</span></td>
                                <td class="delete" style="width: 5%"><a class="@if (Auth::check()) delete_item @else delete_item_offline @endif" id="{{ $i->cart_item_id }}" title="Delete"> <i class="glyphicon glyphicon-remove" style="font-size: 10px;"></i></a></td>
                            </tr>
    @endforeach
                            </tbody>
                        </table>
                    </div> -->
                        <!--cartContent-->


                        <div class="cartContent w100">
                            <table class="cartTable table-responsive w100">
                                <tbody>

                                    @foreach ($items as $i)
                                        <?php // generate seo url link
                                        $seo_link = ProductsController::generateSeoUrlLink($i->product_name, $i->product_id);
                                        ?>

                                        <tr class="CartProduct" id="rec_{{ $i->cart_item_id }}">
                                            <td class="CartProductThumb">
                                                <div>
                                                    <a href="{{ route('product_details_path', $seo_link) }}">
                                                        <img src="{{ getenv('S3_URL') }}/products/{{ $i->product_img }}"
                                                            alt="img">
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="col-xs-10 no-padding">
                                                    <div class="CartDescription">
                                                        <h4 class="no-padding">
                                                            <a href="{{ route('product_details_path', $seo_link) }}">{{ $i->product_name }}
                                                            </a>
                                                        </h4>
                                                        <span class="size" style="color:#777">{{ $i->attr_items }}</span>

                                                        <div class="price hidden">
                                                            $<span
                                                                id="item_price_{{ $i->variant_id }}">{{ floatval($i->current_price) }}</span>
                                                            @if ($i->current_price != $i->regular_price)
                                                                <span class="small"> <s
                                                                        style="font-weight:normal; font-size:13px;">${{ floatval($i->regular_price) }}</s>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="delete col-xs-2 no-padding text-right"
                                                    style="vertical-align: top;">
                                                    <a class="@if (Auth::check()) delete_item @else delete_item_offline @endif"
                                                        id="{{ $i->cart_item_id }}" title="Delete">
                                                        <i class="glyphicon glyphicon-remove"></i>
                                                    </a>
                                                </div>
                                                <div class="col-xs-6 no-padding text-left">
                                                    <span class="cart_btn" onclick="decrementQty({{ $i->variant_id }});"> -
                                                    </span>
                                                    <input id="qty_{{ $i->variant_id }}" class="qty_input" min='1'
                                                        type="number" value="{{ $i->quantity }}"
                                                        name="qty_{{ $i->variant_id }}"
                                                        onfocusout="changeQty({{ $i->variant_id }});">
                                                    <span class="cart_btn" onclick="incrementQty({{ $i->variant_id }});"> +
                                                    </span>
                                                </div>
                                                <div class="col-xs-6 no-padding text-right">
                                                    <div class="price">$ <span class="subtotals"
                                                            id="subtotal_{{ $i->variant_id }}">{{ $i->current_price * $i->quantity }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <!--/row end-->
                </div>

                <div class="col-lg-3 col-md-3 col-sm-4 rightSidebar">
                    <div class="contentBox">
                        <div class="w100 costDetails">
                            <div class="table-block" id="order-detail-content">

                                <div class="w100 cartMiniTable">
                                    <table id="cart-summary" class="std table no-margin-bottom">
                                        <tbody>
                                            <!-- <tr>
                                            <td>Total products</td>
                                            <td class="price">$ <span id="total_price">{{ $totals['subtotal'] }}</span></td>
                                        </tr> -->

                                            @if ($totals['shipping_fees'] != 0)
                                                <tr style="">
                                                    <td>Shipping</td>
                                                    <td class="price">
                                                        <span class="success" id="shipping_price">
                                                            $ {{ $totals['shipping_fees'] }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endif

                                            <!-- <tr class="cart-total-price ">
                                            <td>Subtotal</td>
                                            <td class="price" id="total_no_tax">$ {{ $totals['subtotal_without_vat'] }}</td>
                                        </tr> -->

                                            @if (config('global.VAT') != 0)
                                                <tr>
                                                    <td>VAT <small>({{ config('global.VAT') }} %)</small></td>
                                                    <td class="price" id="vat">$ {{ $totals['vat'] }} </td>
                                                </tr>
                                            @endif

                                            <tr>
                                                <td> Total</td>
                                                <td class="price" id="total">$ {{ $totals['total'] }}</td>
                                            </tr>

                                            <tr>
                                                <td colspan="2">For shipping inside Lebanon, the payment method will be in cash on delivery; one of our team will contact you and advise the additional shipping fees.<br>
                                                For Shipping outside Lebanon, one of our team will contact you and advise the payment method and the additional shipping fees.</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="text-center">
                                    <a class="btn btn-stroke btn-dark checkout w100" title="checkout"
                                        href="{{ route('checkout_path') }}"> Proceed to checkout </a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- End popular -->

                </div>
                <!--/rightSidebar-->

            </div>
            <!--/row-->
        @else
            <!-- if cart is empty -->

            <div class="row" style="text-align:center">
                <img class="empty_cart_img" style="width:150px;" src="images/empty-cart.png"> <br />
                <h2>Your shopping cart is empty ! </h2>
                <h4>Click <a class="empty_cart_link" href="/">here</a> to continue shopping</h3>
            </div>
        @endif

        <div style="clear:both"></div>
    </div>
    <!-- /.main-container -->

    <script src="/js/custom_functions.js"></script>
    <script type="text/javascript">
        var removed_from_cart = <?= json_encode($removed_from_cart) ?>;
        var delivery_threshold = <?= json_encode(config('global.DELIVERY_THRESHOLD')) ?>;
        var delivery_price = <?= json_encode(config('global.DELIVERY_PRICE')) ?>;
        var vat = <?= json_encode(config('global.VAT')) ?>;

        $(document).ready(function() {

            // if the cleanCartFromExpiredItems returned expired products to be removed from the cart_items
            if (removed_from_cart != false && removed_from_cart !== null) {
                var products = '\n';

                // loop the returned array and concatenate the products in a string
                $.each(removed_from_cart, function(key, value) {
                    products = products + '\n- ' + value.product_name;
                })
                console.log(products);
                // display the removed products
                swal({
                    title: 'Products removed from cart',
                    text: 'The following products were removed from your cart because someone bought them before: ' +
                        products,
                    type: 'warning',
                });
            }

        });



        // call function when removing the cursor from the input qty after changing the value
        //$(".qty_input").focusout(editCartQuantity(42, this.val()));

        function changeQty(variant_id) {
            qty = $('#qty_' + variant_id).val(); // get the changed quantity
            editCartQuantity(variant_id, qty, qty); // call function to update in DB
        }



        function decrementQty(variant_id) {
            qty = $("#qty_" + variant_id).val(); // get the actual qty
            if (qty > 1) {
                $("#qty_" + variant_id).attr('value', qty - 1); // decrement the item qty by 1
                editCartQuantity(variant_id, qty - 1, qty); // call function to update in DB
            }
        }


        function incrementQty(variant_id) {
            qty = $("#qty_" + variant_id).val(); // get the actual qty

            $("#qty_" + variant_id).attr('value', parseInt(qty) + 1); // increment the item qty by 1
            editCartQuantity(variant_id, parseInt(qty) + 1, qty); // call function to update in DB
        }


        // function that edit directly the quantity of product in the cart
        function editCartQuantity(variant_id, qty, old_qty) {
            if (qty < 1)
                $('#qty_' + variant_id).val(1); //set value to 1 if the selected value is less than 1

            // ajax call to add the variant to the cart 
            $.ajax({
                url: "/add-edit-cart",
                method: "POST",
                data: {
                    'variant_id': variant_id,
                    'qty': qty,
                    'action': 'edit'
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(data) {

                    if (data == null) //no error
                    {
                        // calculate the new price 
                        price = $('#item_price_' + variant_id).html();
                        qty = $('#qty_' + variant_id).val();

                        subtotal = price * qty;
                        subtotal = parseFloat(subtotal.toFixed(2));
                        $('#subtotal_' + variant_id).html(subtotal);

                        // calculate and update the total price of the cart
                        getTotalPrice();
                    } else {
                        $('#qty_' + variant_id).attr('value',
                        old_qty); // if error or out of stock, put back the old quantity value
                        swal({
                            title: data.title,
                            text: data.msg,
                            type: "warning",
                        });

                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Status:' + jqXHR.status);
                    console.log('Text status:' + textStatus);
                    console.log('Error Thrown:' + errorThrown);
                }

            });

        }


        // ==================== DELETE item - ONLINE  =====================
        ajaxDeleteCartItems('.delete_item', 'delete-cart-item', 'rec_');

        // ==================== DELETE item - OFFLINE  =====================
        ajaxDeleteCartItems('.delete_item_offline', 'delete-cart-item-offline', 'rec_');
    </script>



@endsection
