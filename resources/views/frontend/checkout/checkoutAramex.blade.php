<?php use App\Http\Controllers\Frontend\CheckoutController; ?>

@extends('frontend.layouts.checkout_layout')

@section('title', 'Checkout')

@section('content')

<?php  
	if(Auth::check())	
		// data to be used in ajax
		$auth_user_id = Auth::user()->id;
	else
		$auth_user_id = 0;
?>

<div class="wrapper">

	<div class="container">

		<div class="row">
			
			<div class="col-md-7 padd60">

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

				{!! Form::open(array('route' => 'save_shipping_address_delivery_path', 'id' => 'save_form', 'class' => 'add-form')) !!}

					<div class="row">

						<div class="col-md-12">
							<h4>Contact Information</h4>
						</div>

						@if(Auth::check())
							<div class="col-md-12 mb-10">
							  	<label>Email: </label>
							  	<span>{{Auth::user()->email}}</span>
							</div>	
						@else
						 	<div class="col-md-12 form-group user-input-wrp">
							  	<input type="text" name="email" class="inputText form-control" value="{{session('email')}}" required>
							  	<label class="floating-label" for="email">Email</label>
							</div>
						@endif

					</div>

					<!-- SHIPPING SECTION -->
					<div class="row">

						<div class="col-md-12">
						 	<h4>Shipping Address</h4>	
						</div>

						@if(Auth::check()) 	
						  	<div class="col-md-12 form-group user-input-wrp">
								<select name="stored_address_id" class="inputText form-control" id="stored_address" aria-describedby="basic-addon3">
									<option value="" disabled selected>Stored Addresses</option>
									@foreach($addresses as $a)
										@if($a->is_billing == 0)
								  			<option value='{{$a->address_id}}' <?php if($a->address_id ==session('stored_address_id')) echo'selected'; ?> >{{$a->city}}, {{$a->address}}</option>
								  		@endif
									@endforeach
									<option value='0' <?php if(session('stored_address_id') == NULL) echo'selected'; ?> >New Address...</option>
								</select>
					  			<!-- <label class="floating-label floating-label-for-select" for="stored_address">Stored Addresses</label> -->
							</div>
						@endif

					</div>

					<div class="row">

					    <div class="col-md-6 form-group user-input-wrp">
						  	<input type="text" name="fullname" class="inputText form-control" value="{{session('fullname')}}" required>
						  	<label class="floating-label" for="fullname">Contact name</label>
						</div>

						<div class="col-md-6 form-group user-input-wrp">
						  	<input type="text" name="company" class="inputText form-control" value="{{session('company')}}" required>
						  	<label class="floating-label" for="company">Company</label>
						</div>
						
						<div class="col-md-12 form-group user-input-wrp">
							<input type="text" name="address" class="inputText form-control" value="{{session('address')}}" maxlength="100" required>
						  	<label class="floating-label" for="address">Address</label>
						</div>


						<div class="col-md-5 form-group user-input-wrp">
							<select name="city" id="city" value="{{session('city')}}" class="inputText form-control" required>
								<option value="" disabled selected>City</option>
								@foreach($aramex_cities as $c)
				                    <option value="{{$c}}" <?php if($c == session('city')) echo 'selected'; ?> >{{$c}}</option>
				                @endforeach
							</select>
						  	<!-- <label class="floating-label floating-label-for-select" for="country">Country</label> -->
						</div>

						<div class="col-md-3 form-group user-input-wrp">
							<input type="text" name="apartment" class="inputText form-control" value="{{session('apartment')}}" required>
						  	<label class="floating-label" for="apartment">Building</label>
						</div>

						<div class="col-md-4 form-group user-input-wrp">
							<input type="text" name="postal_code" value="{{session('postal_code')}}" class="inputText form-control" required>
						  	<label class="floating-label" for="postal_code">Floor</label>
						</div>

						<div class="col-md-12 form-group user-input-wrp">
							<select name="country" id="country" value="{{session('country')}}" class="inputText form-control" required>
								<option value="" disabled selected>Country</option>
								@foreach($countries as $c)
									@if($c->country_id == 127)
				                    	<option value="{{$c->country_id}}" selected>{{$c->title}}</option>
				                    @endif
				                @endforeach
							</select>
						  	<!-- <label class="floating-label floating-label-for-select" for="country">Country</label> -->
						</div>

						<div class="col-md-12 form-group user-input-wrp">
							<input type="text" name="address_phone" value="{{session('address_phone')}}" class="inputText form-control" required>
						  	<label class="floating-label" for="address_phone">Phone</label>
						</div>

					</div>

					<!-- BILLING SECTION -->
					<div class="row">

						<div class="col-md-12">
						 	<h4> <input id="billing_check" type="checkbox" name="billing_flag" value="1" @if(session('billing_flag')) checked @endif> &nbsp
						 		 <label class="billing_check_label" for="billing_check">Billing Address </label>
						 		 <small style="font-size:60%;">(use same as Shipping Address)</small>
						 	</h4>	
						</div>

					</div>

					<div class="row" id="billing_div" @if(!session('billing_flag')) style="display:none;" @endif>

					    <div class="col-md-6 form-group user-input-wrp">
						  	<input type="text" name="billing_fullname" class="inputText form-control" value="{{session('billing_fullname')}}">
						  	<label class="floating-label" for="billing_fullname">Contact name</label>
						</div>

						<div class="col-md-6 form-group user-input-wrp">
						  	<input type="text" name="billing_company" class="inputText form-control" value="{{session('billing_company')}}">
						  	<label class="floating-label" for="billing_company">Company</label>
						</div>
						
						<div class="col-md-12 form-group user-input-wrp">
							<input type="text" name="billing_address" class="inputText form-control" value="{{session('billing_address')}}" maxlength="100">
						  	<label class="floating-label" for="billing_address">Address</label>
						</div>


						<div class="col-md-5 form-group user-input-wrp">
							<input type="text" name="billing_city" value="{{session('billing_city')}}" class="inputText form-control">
						  	<label class="floating-label" for="billing_city">City</label>
						</div>

						<div class="col-md-3 form-group user-input-wrp">
							<input type="text" name="billing_apartment" class="inputText form-control" value="{{session('billing_apartment')}}">
						  	<label class="floating-label" for="billing_apartment">Building</label>
						</div>

						<div class="col-md-4 form-group user-input-wrp">
							<input type="text" name="billing_postal_code" value="{{session('billing_postal_code')}}" class="inputText form-control">
						  	<label class="floating-label" for="billing_postal_code">Floor</label>
						</div>

						<div class="col-md-12 form-group user-input-wrp">
							<select name="billing_country" id="billing_country" value="{{session('billing_country')}}" class="inputText form-control">
								<option value="" disabled selected>Country</option>
								@foreach($countries as $c)
				                    <option value="{{$c->country_id}}" <?php if($c->country_id == session('billing_country')) echo 'selected'; ?> >{{$c->title}}</option>
				                @endforeach
							</select>
						  	<!-- <label class="floating-label floating-label-for-select" for="country">Country</label> -->
						</div>

						<div class="col-md-12 form-group user-input-wrp">
							<input type="text" name="billing_address_phone" value="{{session('address_phone')}}" class="inputText form-control">
						  	<label class="floating-label" for="billing_address_phone">Phone</label>
						</div>

					</div>

					

					<!-- DELIVERY TIMING SECTION -->

					<div class="row">

						<div class="col-md-12">
						 	<h4>Notes</h4>	
						</div>
		{{--
						<div class="col-md-6 form-group user-input-wrp date" data-provide="datepicker">
							<input type="text" name="delivery_date" class="inputText form-control" placeholder="Date">
						  	<!-- <label class="floating-label" for="delivery_date">Date</label> -->
							<span class="input-group-addon hidden">
		                        <span class="glyphicon glyphicon-calendar"></span>
		                    </span>
						</div>

						<div class="col-md-6 form-group user-input-wrp">
							<select name="delivery_time" class="inputText form-control">
								<option value="" disabled selected>Time</option>
							  	<option @if(session('delivery_time') == 'From 9:00 a.m to 12:00 p.m') selected @endif value='From 9:00 a.m to 12:00 p.m'>From 9:00 a.m to 12:00 p.m</option>
							  	<option @if(session('delivery_time') == 'From 1:00 p.m to 6:00 p.m') selected @endif value='From 1:00 p.m to 6:00 p.m'>From 1:00 p.m to 6:00 p.m</option>
							</select>
						  	<!-- <label class="floating-label floating-label-for-select" for="delivery_time">Time</label> -->
						</div>
		--}}
						<div class="col-md-12">
							<div class="form-group user-input-wrp">
						      	<textarea name="notes" class="form-control" rows="5" placeholder="Notes">{{session('notes')}}</textarea>
						    </div>
						</div>	

					</div>

					<div class="row">

						<div class="col-md-12 hidden-xs">
						  	
						  	<a href="{{route('cart_path')}}" class="hidden-xs">< Return to cart</a>
						  	<button type="submit" class="btn btn-dark checkout pull-right">Save and proceed to payment</button>

						</div>

						<div class="col-xs-12 visible-xs text-center"><br>
						  	<button type="submit" class="btn btn-dark checkout mb-10">Save and proceed to payment</button><br>
						  	<a href="{{route('cart_path')}}">< Return to cart</a>
						</div>

					</div>
				  
				{!! Form::close() !!}

			</div>

		@if(!CheckoutController::isMobileDevice())
			<!-- Right column - Apply promo code + cart + total -->
			<div id="promo_div" class="col-md-5 right padd40">
			 	@include('frontend.checkout.includes.promo-total')
			</div>
		@endif


	</div>

</div>

<script type="text/javascript">
	var url_get_user_address_details =<?=json_encode(route('get_user_address_details_path'))?>;

	$(document).ready(function() {

		$('.date').datepicker({
			    format: 'yyyy-mm-dd',
			    startDate: '0'
			 });

  		// when checking/uncheking the billing details
	    $('#billing_check').change(function() {
	        if($(this).is(":checked"))
	        {	
	            $('#billing_div').slideDown('fast'); // show the billing division

	           	// GET ALL THE SHIPPING ADDRESS INFO
	            fullname = $("input[name^='fullname']").val();
	            company = $("input[name^='company']").val();
	            address = $("input[name^='address']").val();
	            apartment = $("input[name^='apartment']").val();
	            city = $("input[name^='city']").val();
	            postal_code = $("input[name^='postal_code']").val();
	            country_id = $("#country").val();
	            Phone = $("input[name^='address_phone']").val();


	            // SET ALL THE BILLING ADDRESS INFO 
	            $("input[name^='billing_fullname']").val(fullname);
	            $("input[name^='billing_company']").val(company);
	            $("input[name^='billing_address']").val(address);
	            $("input[name^='billing_apartment']").val(apartment);
	            $("input[name^='billing_city']").val(city);
	            $("input[name^='billing_postal_code']").val(postal_code);
	            $("#billing_country").val(country_id);
	            $("input[name^='billing_address_phone']").val(Phone);

	        }
	        else
	        	$('#billing_div').slideUp('fast'); 
	    });
	});
</script>

@endsection