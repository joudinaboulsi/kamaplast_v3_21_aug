// ADD TO CART 
$(".add_to_cart").click(function() {

    // check if I have a quantity field
    if($('#order_qty').val() != null && $('#order_qty').val() != null)
        qty = $('#order_qty').val();
    else 
        qty = $(this).attr('qty');

    variant_id =  $(this).attr('variantid');
    
    // ajax call to add the variant to the cart 
    $.ajax({
         url: "/add-edit-cart",
         method: "POST",
         data: {
                 'variant_id' : variant_id,
                 'qty' : qty,
                 'action': 'add'
               },      
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },                      
         dataType: "json",
         success: function (data) {
 
            if(data == null) //no error
            {
                $('#after_add_to_cart').modal(); //open modal
                incrementCartCount(); // add +1 to the cart
            }
            else
            {
                swal({
                    title: data.title,
                    text: data.msg,
                    type: "warning",
                });

            }

         },
         error: function(jqXHR, textStatus, errorThrown) {
             console.log('Status:'+jqXHR.status);
             console.log('Text status:'+textStatus);
             console.log('Error Thrown:'+errorThrown);
        }

    });    

});