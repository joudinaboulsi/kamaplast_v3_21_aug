<?php use App\Http\Controllers\Frontend\CheckoutController; ?>

@extends('frontend.layouts.checkout_layout')

@section('title', 'Payment')

@section('content')

<?php  
	if(Auth::check())	
		// data to be used in ajax
		$auth_user_id = Auth::user()->id;
	else
		$auth_user_id = 0;
?>

@if(Session::has('msg'))
<p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{!!Session::get('msg')!!}</p>
 @endif



<div class="wrapper">

	<div class="container">

		<div class="row">

			<div class="col-sm-7 padd60">

				<a href="/"> <img style="margin-bottom:20px;" src="images/logo.png" alt="Kamaplast" width="200"> </a>

			@if(CheckoutController::isMobileDevice())
				<div id="promo_div" class="row">
				 	<div class="panel-group" id="accordion">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" aria-expanded="false" data-parent="#accordion" href="#collapse1"><i class="glyphicon-shopping-cart glyphicon"></i> &nbsp;Show order summary <i class="glyphicon glyphicon-chevron-down" style="font-size: 10px;"></i><i class="glyphicon glyphicon-chevron-up" style="font-size: 10px;"></i><div class="pull-right" style="color:#333">$ {{$totals['total']}}</div></a>
								</h4>
							</div>
							<div id="collapse1" class="panel-collapse collapse">
								<div class="panel-body">						
					 				@include('frontend.checkout.includes.promo-total')
								</div>
							</div>
						</div>
					</div>
				</div>
			@endif

				{!! Form::open(array('route' => 'pay_path', 'id' => 'pay_form', 'class' => 'add-form')) !!}
				 
					<div class="row">

						<div class="col-md-12">
							<h4>Contact Information</h4>
						</div>

						@if(Auth::check())
							<div class="col-md-12 user-input-wrp mb-10">
							  	<label>Email: </label>
							  	<span>{{Auth::user()->email}}</span>
							</div>	
						@else
						 	<div class="col-md-12 form-group user-input-wrp">
							  	<input type="text" name="email" class="inputText form-control" value="{{session('email')}}" required placeholder="Email">
							</div>
						@endif

					</div>

					<div class="row">

						<div class="col-sm-12">
							<h4>Payment method</h4>
							<h5 style="color:gray; margin-bottom:20px;">All transactions are secure and encrypted.</h5>
							
							<!-- <div class="panel panel-default">
							    <div class="panel-heading">
							    	<div class="radio">
									   	<label><input type="radio" name="payment_method" value="1"  @if(session('payment_method') == 1 || session('payment_method') == NULL) checked @endif>Credit Card</label>
									</div>
							    </div>
							    <div class="panel-body all_payments_details payment_details_1">Credit card credentials will be here</div>
							</div> -->

							<div class="panel panel-default">
							    <div class="panel-heading">
							    	<div class="radio">
									  	<label><input type="radio" name="payment_method" value="2" @if(session('payment_method') == 2 ) checked  @endif>Cash On Delivery</label>
									</div>
							    </div>
							    <div class="panel-body all_payments_details payment_details_2" style="display:none;">
							    	Payment to be made upon delivery of your order
							    </div>
							</div>

						</div>

					</div>

					<div class="row">

						<div class="col-md-12 hidden-xs">
						  	<a href="{{route('checkout_path')}}" class="hidden-xs">< Return to checkout </a>
						  	<button id="pay" type="submit" class="btn btn-dark complete_order pull-right ladda-button" data-style="expand-right">Complete Order</button>
						</div>

						<div class="col-xs-12 visible-xs text-center"><br>
						  	<button id="pay" type="submit" class="btn btn-dark complete_order mb-10 ladda-button" data-style="expand-right">Complete Order</button><br>
						  	<a href="{{route('checkout_path')}}">< Return to checkout </a>
						</div>

					</div>

				{!! Form::close() !!}		

			</div>
		@if(!CheckoutController::isMobileDevice())
			<!-- Right column - Apply promo code + cart + total -->
			<div id="promo_div" class="col-sm-5 padd40 right">
			 	@include('frontend.checkout.includes.promo-total')
			</div>
		@endif

		</div>

	</div>

</div>

@if($warning_popup['status'] == true)
<div class="modal fade" id="cart_change" tabindex="-1" role="dialog" style="margin-top:60px;" aria-labelledby="cart_change" aria-hidden="true">
    <div class="modal-dialog modal-xs" role="document">
        <div class="modal-content">
            <div class="modal-header popup_header">
                <button type="buttonrue" class="close" data-dismiss="modal" aria-hidden="true"> &times;</button>
                <h2 class="text-center">CART STATUS CHANGE</h2>
            </div>
            <div class="modal-body">

                <h3 class="reviewtitle text-danger">{{$warning_popup['title']}}</h3>
                <h5>{!! $warning_popup['text'] !!}</h5>
                <br/>
                <div class="row" style="margin-bottom:30px;">
                    <div class="col-lg-offset-1 col-lg-4">
                        <button type="submit" id="complete_order" class="btn btn-stroke btn-dark checkout btn-xs" data-dismiss="modal" aria-hidden="true">Continue & Pay anyway</button>
                    </div>

                    <div class="col-lg-offset-1 col-lg-4">
                        <button id="check_cart" class="btn btn-stroke dark btn-xs"> Check updated cart</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endif


<!-- Placed at the end of the document so the pages load faster -->
<script src="/bootstrap/js/bootstrap.min.js"></script>

<!-- Ladda Library -->
<script src="cms/js/plugins/ladda/spin.min.js"></script>
<script src="cms/js/plugins/ladda/ladda.min.js"></script>
<script src="cms/js/plugins/ladda/ladda.jquery.min.js"></script>

<script src="/js/jquery/jquery-2.1.3.min.js"></script>
<script type="text/javascript">

var warning_popup = <?= json_encode($warning_popup['status']) ?>;

$( document ).ready(function() {    
    // if cart changed, notify show modal 
    if(warning_popup == true)
    	$('#cart_change').modal();

    radio_checked_value =  $('input[type=radio][name=payment_method]:checked').val();

    $('.all_payments_details').slideUp('fast'); // hide all panels
    $('.payment_details_'+radio_checked_value).slideDown('fast'); // show the panel body of the selected payment method
 
 });

// close modal when clicking check cart button
$('#check_cart').on('click', function(){
	$('#cart_change').modal('hide');
});


// if we click on continue & pay anyway, activate the pay button and submit the form
$('#complete_order').on('click', function(){
	$('#pay').click();
})

	
// when changing payment method	
$('input[type=radio][name=payment_method]').change(function() {
    
   $('.all_payments_details').slideUp('fast'); // hide all panels

   id = $(this).val(); // get the value of the selected payment method
   $('.payment_details_'+id).slideDown('fast'); // show the panel body of the selected payment method

});


// block "complete order" button with ladda function when we click it
$('#pay').on('click',function(){
 var form = $(this).closest('form')[0];
  if(form.checkValidity() == true) {
    var l = Ladda.create( document.querySelector( '#pay' ) );
    l.start();
  form.submit();
  }
  });


</script>



@endsection