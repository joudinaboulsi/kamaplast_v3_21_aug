// when changing an address populate the right data in the shipping form 
	$( "#stored_address").change(function() {
	  
		address_id = $(this).val();

		$.ajax({
             url: url_get_user_address_details,
             method: "POST",
             data: {
                     'address_id' : address_id
                   },      
             headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },                      
             dataType: "json",
             success: function (data) { 

             	  // if the user is filling a new address	
             	  if(address_id == 0)
             	  {
             	  	 $('.inputText').val(''); // empty the form
             	  	 $("#country").next(".custom_label").removeClass('floating-label-for-select');
             	  }   
         
             	  else	
             	  {	
             	      // change the data of the form below
	                  $("input[name^='fullname']").val(data[0].name);
	                  $("input[name^='company']").val(data[0].company);
	                  $("input[name^='address']").val(data[0].address);
	                  $("input[name^='apartment']").val(data[0].apartment);
	                  
	                  if(data[0].country_id != null && data[0].country_id != '')
	                  {
	                  	$("#country").val(data[0].country_id);
	                  	$("#country").next(".custom_label").addClass('floating-label-for-select');
	                  }
	                  
	                  $("input[name^='city']").val(data[0].city);	

	                  $("input[name^='address_phone']").val(data[0].phone);	
	                  $("input[name^='postal_code']").val(data[0].postal_code);	
      
	              }
                 },
             error: function(jqXHR, textStatus, errorThrown) {

                 console.log('Status:'+jqXHR.status);
                 console.log('Text status:'+textStatus);
                 console.log('Error Thrown:'+errorThrown);

             }
         });

	});



// function that change the design of the selected dropdown list
	$('#country').on('change', function(){

		if($(this).val() != '')
			$(this).next(".custom_label").addClass('floating-label-for-select');	
		else
			$(this).next(".custom_label").removeClass('floating-label-for-select');	

	});
