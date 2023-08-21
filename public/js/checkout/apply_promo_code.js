 $( document ).ready(function() {

	$('.delivery_time').datepicker({
		autoclose: true,
        format: 'yyyy-mm-dd'

	});

	
  
  // if the cleanCartFromExpiredItems returned expired products to be removed from the cart_items
  if(removed_from_cart != false && removed_from_cart !== null)
  {
    var products = '\n';

    // loop the returned array and concatenate the products in a string
    $.each( removed_from_cart, function( key, value ) {
        products = products+'\n- '+value.product_name;
        })
    
    // display the removed products
    swal({
        title: 'Products removed from cart',
        text: 'The following products were removed from your cart because someone bought them before: '+ products,
        type: 'warning',
    });
  }


    // APPLYING A PROMO CODE
	$('body').on('submit', '#apply_promo_form', function (e) {	 
		e.preventDefault();

		promo_code = $("input[name^='promo_code']").val();

		if(promo_code != '') // if we didn't enter any code
		{
			$.ajax({
	             url: url_apply_promo,
	             method: "POST",
	             data: {
	                     'promo_code' : promo_code,
	                     'user_id' : auth_user_id,
	                     'subtotal' : subtotal
	                   },       
	             headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	             },                      
	             dataType: "json",
	             success: function (data) {

	             	  $('.promo_error').empty(); // empty the span from the old error msg	
	             	  $('#discount_line').remove(); // remove the old discount 
	             	  $('.promo_valid').empty(); // remove the old active promo code
	             	  $("#js_total_amount").html('$ '+total_without_discount);

	             	  // if promo is valid
	             	  if(data.success == true)
	             	  {	             	  	
	             	  	$('<span class="badge badge-light promo_valid">'+promo_code+' <button class="btn btn-danger btn-xs clear_promo">x</button> </span>').insertAfter("#apply_promo_form");

	             	  	// percentage discount
	             	  	if(data.promo_type == 'percentage') 
	             	  	{	
	             	  		discount_value_float = (subtotal*data.value/100);
	             	  		discount_value = (subtotal*data.value/100).toFixed(2);
	             	  		discount= '<small>('+data.value+'%)</small>';
	             	  	}

	             	  	else if(data.promo_type == 'amount') // discount amount
	             	  	{	
	             	  		discount_value_float = data.value;
	             	  		discount_value = data.value;
	             	  		discount= '';
	             	  	}

	             	  	discount_line = '\
	             	  	<tr class="tr_30" id="discount_line">\
	             	  		<td colspan="2" class="green_discount_text"> Discount '+discount+' </td>\
	             	  		<td class="right_align bold green_discount_text"><b>- $  '+parseFloat(discount_value)+'</b></td>\
	             	  	</tr>';

	             	  	// add discount line
	             	    // $('#subtotals_table').append(discount_line);
	             	  	$( discount_line ).insertAfter( "#subtotal_line" );

	             	  	// Strike the old total and add the new total after discount
	             	  	$("#js_total_amount").wrapInner("<strike class='js_old_price'>");
	             	  	$("#js_total_amount").css('font-size', '13px');
	             	  	total = total_without_discount_float - discount_value_float;
	             	  	$("#js_total_amount").prepend('<div style="font-size:16px;" class="bold"><b>$ '+total.toFixed(2)+' </b></div>');
	             	  }

	             	  else // invalid promo
	             	  {
	             	  	$('.promo_error').html(data.msg); // display error promo message
	             	  }	

	                 },
	             error: function(jqXHR, textStatus, errorThrown) {

	                 console.log('Status:'+jqXHR.status);
	                 console.log('Text status:'+textStatus);
	                 console.log('Error Thrown:'+errorThrown);

	             }
	         });
		}

	});


	// CLEAR A PROMO CODE
	$('body').on('click', '.clear_promo', function () {
   	  
	  $("#promo_div").load(url_clear_promo); // reload the right div

	});
	


});
