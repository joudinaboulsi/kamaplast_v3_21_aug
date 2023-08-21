@extends('cms.layouts.app')

@section('content')

{!! Form::open(array('route' => 'edit_order_path', 'id' => 'add_form', 'class' => 'add-form')) !!}

<style type="text/css">
    .chosen-container-multi {
    width: 100% !important;
}
</style>

<!-- GENERAL INFORMATION SECTION -->
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-md-10">
    <h2> Orders </h2>
    <ol class="breadcrumb ">
      <li>
        <strong><a href="{{ route('cms_orders') }}">Orders</a></strong>
      </li>
    </ol>
    <hr>
  </div>

  <div class="col-md-2 margin-top">
    <div style="text-align:right; margin-top:10px;">
      <input type="submit" name="edit_order" value="Save" class="ladda-button btn btn-primary btn-md save_btn" />
    </div>
  </div>

  <div class="col-md-12">
    <!-- GENERAL DETAILS SECTION -->
    <div class="col-md-4">
      <div class="form-group required">
        <label class="font-normal"><span><img src="/cms/svg/information.svg" width="20"></span> GENERAL DETAILS</label> 
      </div>
      <div class="form-group required">
        <label class="font-normal">Date:</label> {{ date("d M Y - h:ia", strtotime($order_details[0]->created_at))}} 
      </div>
      <div class="form-group required">
        <label class="font-normal">Customer Name:</label> 
          @if($order_details[0]->user_id != NULL)
            <a href='{{ route("cms_users_details", $order_details[0]->user_id) }}'>{{ $order_details[0]->customer }}</a>
          @else
            {{ $order_details[0]->customer }}
          @endif
      </div>
      <div class="form-group required">
        <label class="font-normal">Payment Method:</label> {{ $order_details[0]->payment_method }}
      </div>
      <div class="form-group required">
        <label class="font-normal">Payment Status:</label> <span class="label label_color b_radius" style="background:{{$order_details[0]->payment_status_color}}">{{ $order_details[0]->payment_status }}</span> 
      </div>

{{--
      <div class="form-group required">
        <label class="font-normal">Delivery date:</label> {{ $order_details[0]->delivery_date}}
      </div>

       <div class="form-group required">
        <label class="font-normal">Delivery time:</label> {{ $order_details[0]->delivery_time}}
      </div>

--}}

    </div>


    <!-- SHIPPING DETAILS SECTION -->
    <div class="col-md-4">
      <div class="form-group required">
        <label class="font-normal"><span><img src="/cms/svg/information.svg" width="20"></span> SHIPPING DETAILS</label> 
        <a data-toggle="modal" data-target="#edit_shipping" title="edit shipping details" style="position:relative; left:10px;">
          <i class="fa fa-edit fa-lg" style="color: #1bb394;"></i>
        </a>
      </div>
      <div class="form-group required">
        <label class="font-normal">Address:</label>
          {{ $order_details[0]->shipping_address }} - {{ $order_details[0]->shipping_city}} - {{ $order_details[0]->shipping_country }} 
      </div>

      <div class="form-group required">
        <label class="font-normal">Building:</label>
          {{ $order_details[0]->shipping_apartment }}
      </div>

      <div class="form-group required">
        <label class="font-normal">Floor:</label>
          {{ $order_details[0]->shipping_postal_code }}
      </div>

      <div class="form-group required">
        <label class="font-normal">Phone:</label> {{ $order_details[0]->shipping_phone }}
      </div>

      <div class="form-group required">
        <label class="font-normal">Shipping Status:</label> <span class="label label_color b_radius" style="background:{{$order_details[0]->shipping_status_color}}">{{ $order_details[0]->shipping_status }}</span> 
      </div> 

      <!-- <div class="form-group required">
        <label class="font-normal">Aramex Tracking Number:</label> {{$order_details[0]->aramex_tracking_number}}
      </div>

      <div class="form-group required">
        <label class="font-normal">Aramex Foreign Ref:</label> {{$order_details[0]->aramex_foreign_ref}}
      </div>

       <div class="form-group required">
        <label class="font-normal">Aramex PDF Label <i class="fa fa-file-pdf-o" aria-hidden="true"></i> :</label> 
        <a style="text-decoration:underline;" target="_blank" href="{{$order_details[0]->aramex_pdf_receipt}}"> Label PDF Receipt  </a>
      </div> -->

    </div>



    <!-- BILLING DETAILS SECTION -->
    <div class="col-md-4">
      <div class="form-group required">
        <label class="font-normal"><span><img src="/cms/svg/information.svg" width="20"></span> BILLING DETAILS</label> 
      <!--
        <a data-toggle="modal" data-target="#edit_shipping" title="edit shipping details" style="position:relative; left:10px;">
          <i class="fa fa-edit fa-lg" style="color: #1bb394;"></i>
        </a>
      -->
      </div>
      <div class="form-group required">
        <label class="font-normal">Address:</label>
          {{ $order_details[0]->billing_address }} - {{ $order_details[0]->billing_city}} - {{ $order_details[0]->billing_country }} 
      </div>

       <div class="form-group required">
        <label class="font-normal">Building:</label>
          {{ $order_details[0]->billing_apartment }}
      </div>

      <div class="form-group required">
        <label class="font-normal">Floor:</label>
          {{ $order_details[0]->billing_postal_code }}
      </div>


       <div class="form-group required">
        <label class="font-normal">Phone:</label> {{ $order_details[0]->billing_phone }}
      </div>

     </div>

  </div>
</div>

<div class="wrapper animated fadeInRight margin-top">
  <div class="row">
    <!-- ORDERS PAYMENT & SHIPPING SECTION -->
    <div class="col-md-4">
      <div class="ibox">
        <div class="ibox-title">
          <h5><span><img src="/cms/svg/information.svg" width="20"></span> PAYMENT & SHIPPING <small>Manage your order</small></h5>
          <div class="ibox-tools">
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">

          <input type="hidden" name="order_id" value="{{ $order_details[0]->order_id }}">

          <div class="form-group required">
            <label for="type" class="font-normal">Payment Status<br><small><i>Select the payment status of the order</i></small></label>
            <div class="controls input-group date col-md-12">
              <select class="form-control" id="payment_status" name="payment_status">
                @foreach($payment_status as $pay_stat)
                  <option value="{{$pay_stat->order_status_id}}" @if($order_details[0]->order_status_id == $pay_stat->order_status_id) selected @endif>{{ $pay_stat->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <hr>
          <div class="form-group required">
            <label for="ship_status" class="font-normal">Shipping Status<br><small><i>Select the shipping status of the order</i></small></label>
            <div class="controls input-group date col-md-12">
              <select class="form-control" id="ship_status" name="ship_status">
                @foreach($shipping_status as $ship_stat)
                  <option value="{{$ship_stat->delivery_status_id}}" @if($order_details[0]->delivery_status_id == $ship_stat->delivery_status_id) selected @endif>{{ $ship_stat->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group required" id="tracking_code_box" style="display: none">
            <label for="tracking_code" class="font-normal">Tracking Code <br><small><i>Add the delivery Tracking Code</i></small></label>
            <input type="text" name="tracking_code" id="tracking_code" class="form-control" value="{{ $order_details[0]->tracking_code }}">
          </div>

        </div>
      </div>
    </div>
    
    <!-- Order Details Indormation -->
    <div class="col-md-8">
      <div class="ibox">
        <div class="ibox-title">
          <h5><span><img src="/cms/svg/information.svg" width="20"></span> ORDER DETAILS <small>List of items ordered by the user</small></h5>
          <div class="ibox-tools">
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <div class="table-responsive">
            <table id="" class="footable table table-striped table-hover toggle-arrow-tiny default footable-loaded" >
              <thead>
                <tr>
                  <th>Product</th>
                  <th>Unit Price</th>
                  <th>Qty</th>
                  <th style="text-align:right">Total</th>
                </tr>
              </thead>
              <tbody>
                <?php $total=0; //defining a variable for the total amount ?>

                @foreach($order_items as $item)
                  <tr class="gradeX" id="{{$item->order_item_id}}">
                    <td><img src="{{getenv('S3_URL')}}/products/thumbs/{{$item->product_img}}" width="35" style="border-radius:10px; margin-right:5px; border:1px solid grey;"> {{$item->product_name}} @if($item->sku != NULL) - {{$item->sku}}  @endif<br/>
                        <small style="color:#9f9f9f">{{$item->attr_items}}</small> </td>
                    <td>
                      @if($item->sales_price != NULL) 
                          ${{$item->sales_price}} <small style="font-weight:normal;"><strike>${{$item->regular_price}}</strike></small>
                      @else
                          ${{$item->regular_price}}
                      @endif
                    </td>
                    <td>{{$item->quantity}}</td>
                    <td style="text-align:right">${{$item->total_price}}</td>
                  </tr>

                 <?php $total=$total+$item->total_price; //adding the total price of all items to the order total amount ?>

                @endforeach
              </tbody>
            </table>
          </div>

          <div class="table-responsive">
            <table id="" class="footable table table-hover toggle-arrow-tiny default footable-loaded" >
                <tr>
                    <td colspan="3" class="td_right_align totals_label_td">SubTotal</td>
                    <td class="td_right_align"><span> ${{$order_details[0]->subtotal}} </span></td>

                </tr>
                @if($order_details[0]->vat!=0)
                <tr>
                    <td colspan="3" class="td_right_align totals_label_td">VAT (%{{$order_details[0]->vat}})</td>
                    <?php 
                        $vat = $order_details[0]->vat; 
                        $vat = $order_details[0]->subtotal * ($vat/100);
                        $vat = number_format($vat,"2",".","'");

                    ?> 
                    <td class="td_right_align"><span>${{ $vat }}</span></td>
                </tr>
                @endif
                <tr>
                    <td colspan="3" class="td_right_align totals_label_td">Shipping Fees</td>
                    <td class="td_right_align"><span> ${{$order_details[0]->shipping_fees}}</span></td>
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
                <tr>
                    <td colspan="3" class="td_right_align totals_label_td" style="color:#28a745;">Discount {{$discount_type}}</td>
                    <td class="td_right_align" style="color:#28a745"><span>- ${{$discount_value}}</span></td>
                </tr>
                @endif

                <tr>
                    <td colspan="3" class="td_right_align totals_label_td" style="font-size:18px;"><b>Total</b></td>
                    <td class="td_right_align" style="font-size:17px;"><span class="price"> <b>${{$order_details[0]->total}}</b> </span></td>
                </tr>
            </table>
          </div>
         
        </div>
      </div>
      <br>
      <div class="ibox">
        <div class="ibox-title">
          <h5><span><i class="fa fa-tags fa-lg">&nbsp</i></span> NOTES <small>Order's notes</small></h5>
          <div class="ibox-tools">
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <div class="table-responsive">
             <div class="form-group col-md-12">
                {!! $order_details[0]->notes !!}
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="wrapper animated fadeInRight padding-bottom margin-top">
  <div class="row">
    <div style="text-align:right;margin-top: 15px; margin-right: 15px;">
      <input type="submit" value="Save" name="edit_order" class="ladda-button btn btn-primary save_btn" />
    </div>
  </div>
</div>

{!! Form::close() !!}


<!-- ================== MODALS ================== -->

<!-- MODAL UPDATE shippinng details -->
<div id="edit_shipping" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update Shipping Address</h4>
      </div>
      {!! Form::open(array('route' => 'edit_shipping_address_path', 'id' => 'add_form', 'class' => 'add-form')) !!}
      <div class="wrapper margin-top">
        <div class="row">
          <!-- ADDRESS SECTION -->
          <div class="col-md-12">
            <div class="profile-content">
              <div class="row">

                <input type="hidden" name="order_id" value="{{ $order_details[0]->order_id }}">

                <div class="form-group col-md-12">
                  <label for="shipping_address_edit" class="control-label requiredField"> ADDRESS<span class="red_star">*</span> <br><small><i>Select Shippinng Address</i></small></label>
                  <select class="form-control" id="shipping_address_edit" name="shipping_address_edit">
                    @foreach($addrList as $al)
                    <option value="{{$al->address_id}}" @if($order_details[0]->shipping_address == $al->address) selected @endif>{{$al->address}}</option>
                    @endforeach
                  </select>  
                </div>
               
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer" style="border-top: none;">
        <input type="submit" name="submit_edit_address" value="Save Changes" class="ladda-button btn btn-primary" data-style="expand-right"/>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>



<script type="text/javascript">
  $(document).ready(function(){

      // Open tracking code input if shipping status is fulfilled
      if($('#ship_status > option[value="2"]').is(":selected"))
      {
        $( "#tracking_code_box" ).slideDown( "slow" );
      }

      $( "#ship_status" ).click(function() {
        // Open tracking code input if shipping status is fulfilled
        if($('#ship_status > option[value="2"]').is(":selected"))
        {
          $( "#tracking_code_box" ).slideDown( "slow" );
        }
        else
          $( "#tracking_code_box" ).slideUp( "slow" );
          $('input[name="tracking_code"]').val('');
      });


  });
</script>

@endsection