 <?php use App\Http\Controllers\Frontend\ProductsController; ?>

 <table class="table_width">
     @foreach ($items as $i)
         <?php // generate seo url link
         $seo_link = ProductsController::generateSeoUrlLink($i->product_name, $i->product_id);
         ?>

         <tr>
             <td class="checkout_item">
                 <div style='margin-right: 20px;'>
                     <a href="{{ route('product_details_path', $seo_link) }}" style="display:inline-block;">
                         <?php
                         if ($i->product_img == null) {
                             $img = 'images/icons/box_icon.png';
                         } else {
                             $img = getenv('S3_URL') . '/products/' . $i->product_img;
                         }
                         ?>

                         <span class="checkout_qty pull-right">{{ $i->quantity }}</span>
                         <img src="{{ $img }}" alt="img"
                             style="width: 100%; height: auto; border:1px solid #c8c6c6; border-radius:5px;">
                     </a>
                 </div>
             </td>

             <td style="width:60%">
                 <h5 class="no-margin"><a class="grey_text"
                         href="{{ route('product_details_path', $seo_link) }}"><b>{{ $i->product_name }} @if ($i->sku != null)
                                 - {{ $i->sku }}
                             @endif
                     </a></b></h5>
                 <span class="size">{{ $i->attr_items }}</span>
             </td>

             <td class="right_align" style="width:20%;">
                 <div>
                     <span class="right_align" id="item_price_{{ $i->variant_id }}" style="font-size: 16px;"><b>$
                             {{ floatval($i->current_price) }}</b></span>
                     @if ($i->current_price != $i->regular_price)
                         <span class="small"> <s class='right_align'
                                 style="font-weight:normal; font-size:10px; display:block;">$
                                 {{ floatval($i->regular_price) }}</s> </span>
                     @endif

                 </div>
             </td>
         </tr>
     @endforeach
 </table>


 <div class="spliter"></div>

 <form id="apply_promo_form">

     <div class="input-group form-group user-input-wrp">
         <input type="text" class="form-control inputText" placeholder="Gift card or discount code" name="promo_code"
             autocomplete="off">
         <span class="input-group-btn">
             <button class="btn btn-dark checkout pull-right" type="submit" id="apply_promo">Apply</button>
         </span>
     </div>

     <div class="promo_error"></div>
     @if (session('promo_code'))
         <span class="badge badge-light promo_valid"> {{ session('promo_code') }} <button
                 class="btn btn-danger btn-xs clear_promo">x</button></span>
     @endif

 </form>

 <div class="spliter"></div>

 <table class="table_width" id="subtotals_table">
     <!-- <tr class="tr_30" id="subtotal_line">
 <td colspan="2" class="grey_text">Subtotal</td>
 <td class="right_align"><b>$ {{ $totals['subtotal'] }}</b></td>
 </tr> -->

     <!-- check if a promo is already applied -->
     @if (session('promo_type'))
         <tr class="tr_30" id="discount_line">
             <td colspan="2" class="green_discount_text">Discount
                 @if (session('promo_type') == 'percentage')
                     <small>({{ session('value') }}%)</small>
                 @endif
             </td>
             <td class="right_align green_discount_text"><b>- $ {{ $totals['discount_value'] }}</b></td>
         </tr>
     @endif

     @if ($totals['shipping_fees'] != 0)
         <tr class="tr_30">
             <td colspan="2" class="grey_text">Shipping Fees </td>
             <td class="right_align" style="color:#717171; font-size: 0.85714em">
                 <b>$ {{ $totals['shipping_fees'] }}</b>
             </td>
         </tr>
     @endif

     @if (config('global.VAT') != 0)
         <tr class="tr_30">
             <td colspan="2" class="grey_text">VAT <small>({{ config('global.VAT') }}%)</small></td>
             <td class="right_align" style="color:#717171; font-size: 0.85714em"><b>$ {{ $totals['vat'] }}</b></td>
         </tr>
     @endif
 </table>

 <table class="table_width">
     <tr>
         <td id="js_total_label" colspan="2" style="width:50%; font-size:16px;">Total<br><span
                 style="font-size: 12px; position: relative; bottom: 7px; color:#717171;">*all taxes included</span>
         </td>

         <!--  check if a promo is already applied -->
         @if (session('promo_type'))
             <td id="js_total_amount" class="right_align" style="font-size:13px;">
                 <div style="font-size:16px;"><b>$ {{ $totals['total'] }} </b></div>
                 <strike class='js_old_price'>$ {{ $totals['total_without_discount'] }}</strike>
             </td>
         @else
             <!-- no promo applied -->

             <td id="js_total_amount" class="right_align font_24">$ {{ $totals['total'] }} </td>
         @endif

     </tr>
     <tr>
         <td colspan="3">For shipping inside Lebanon, the payment method will be in cash on delivery; one of our team
             will contact you and advise the additional shipping fees.<br />
             For Shipping outside Lebanon, one of our team will contact you and advise the payment method and the
             additional shipping fees.</td>
     </tr>
 </table>

 <script type="text/javascript">
     var url_apply_promo = <?= json_encode(route('apply_promo_path')) ?>;
     var url_clear_promo = <?= json_encode(route('clear_promo_path')) ?>;

     var subtotal = <?= json_encode($totals['subtotal_float']) ?>;
     var total_without_discount = <?= json_encode($totals['total_without_discount']) ?>;
     var total_without_discount_float = <?= json_encode($totals['total_without_discount_float']) ?>;

     var removed_from_cart = <?= json_encode($removed_from_cart) ?>;
     var auth_user_id = <?= json_encode($auth_user_id) ?>;

     $('.checkout_qty').each(function(i, obj) {
         var height = $('.checkout_qty ').height();
     });
 </script>
