// function that call getAllTotals function via AJAX and display the totals in the html tag
function getTotalPrice()
{
    $.ajax({
         url: "/get-cart-totals",
         method: "POST",
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },                      
         dataType: "json",
         success: function (data) {
          
            $('#total_price').html(data.subtotal); // fill the total price division
            // check if the shipping is free or not 
            if(data.shipping_fees == 0)
              $('#shipping_price').html('Free shipping!');
            else
              $('#shipping_price').html('$ '+data.shipping_fees);

            total_no_tax = data.subtotal_without_vat;
            $('#total_no_tax').html('$ '+total_no_tax); // fill the total without tax
               
            $('#vat').html('$ '+data.vat); // fill the VAT value

            $('#total').html('$ '+data.total);  // fill the total price

         },
         error: function(jqXHR, textStatus, errorThrown) {
             console.log('Status:'+jqXHR.status);
             console.log('Text status:'+textStatus);
             console.log('Error Thrown:'+errorThrown);
        }

    });    
}


function ajaxDelete(action_btn, url, record_to_remove)
{
    $(document).on('click', action_btn, function(e) {       
          // get the id of the select image for delete
         var id = $(this).attr('id');

         var confirmed = false;    
            // confirm your delete
            var $this = $(this);
                if (!confirmed) {
                    e.preventDefault();
                    swal({
                        title: "Are you sure?",
                        text: "Please confirm your delete action",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        closeOnConfirm: true,
                        html: false
                    }, function() {
                        confirmed = true;

                        // confirmed
                        // ajax call to delete the image
                         $.ajax({
                            url: url,
                            method: "POST",
                            data: {
                                    'id' : id
                                  },      
                            headers: {
                               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },                      
                        dataType: "json",

                            success: function (data) { 
                                    // remove the deleted record
                                    $('#' + record_to_remove + id).remove();
                                    // click the close button to close the pop up
                                    $('#close_popup').click();
                                    
                                    
                                    },
                            error: function(jqXHR, textStatus, errorThrown) {
                                   console.log('Status:'+jqXHR.status);
                                   console.log('Text status:'+textStatus);
                                   console.log('Error Thrown:'+errorThrown);
                              }

                          });
                        });
                }
    });
}

// DELETE FROM THE CART AND RECALCULATE THE TOTAL AMOUNT
function ajaxDeleteCartItems(action_btn, url, record_to_remove)
{
    $(document).on('click', action_btn, function(e) {       
          // get the id of the select image for delete
         var id = $(this).attr('id');

         var confirmed = false;    
            // confirm your delete
            var $this = $(this);
                if (!confirmed) {
                    e.preventDefault();
                    swal({
                        title: "Are you sure?",
                        text: "Please confirm your delete action",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        closeOnConfirm: true,
                        html: false
                    }, function() {
                        confirmed = true;

                        // confirmed
                        // ajax call to delete the image
                         $.ajax({
                            url: url,
                            method: "POST",
                            data: {
                                    'id' : id
                                  },      
                            headers: {
                               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },                      
                        dataType: "json",

                            success: function (data) { 
                                    // remove the deleted record
                                    $('#' + record_to_remove + id).remove();
                                    // click the close button to close the pop up
                                    $('#close_popup').click();
                                    
                                    getTotalPrice(); // recalculate the total amount of the cart

                                    decrementCartCount(); // decrement the cart count by one from the client side

                                    },
                            error: function (request, status, error) {
                                console.log(error);
                                
                            }

                          });
                        });
                }
    });
}





function ajaxDeleteRefresh(action_btn, url)
{
    $(document).on('click', action_btn, function(e) {       
          // get the id of the select image for delete
         var id = $(this).attr('id');

         var confirmed = false;    
            // confirm your delete
            var $this = $(this);
                if (!confirmed) {
                    e.preventDefault();
                    swal({
                        title: "Are you sure?",
                        text: "Please confirm your delete action",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        closeOnConfirm: true,
                        html: false
                    }, function() {
                        confirmed = true;

                        // confirmed
                        // ajax call to delete the image
                         $.ajax({
                            url: url,
                            method: "POST",
                            data: {
                                    'id' : id
                                  },      
                            headers: {
                               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },                      
                        dataType: "json",

                            success: function (data) { 
                                    // reload the page
                                    location.reload(); 
                                    // click the close button to close the pop up
                                    $('#close_popup').click();
                                    
                                    
                                    },
                            error: function (request, status, error) {
                                console.log(error);
                                
                            }

                          });
                        });
                }
    });
}



function loadLadda(id)
{
    $('#'+id).on('click',function(){

      var form = $(this).closest('form')[0];
      if(form.checkValidity() == true) {
        var l = Ladda.create( document.querySelector( '#'+id ) );
        l.start();
      form.submit();
      }

    });
}



function removeFile(action_btn, url)
{
// ==================== REMOVE files =====================
    $(document).on('click', action_btn, function(e) {    
    // get the id of the select image for delete

    var id = $(this).attr('id'); // get the clicked candidate_id
    var field = $(this).attr('field'); // get the name of the field to update

    var confrim = confirm('Are you sure that you want to remove this file?'); // confirm the delete
    
        if(confrim)  // confirmed
         {
            // ajax call to delete the image
             $.ajax({
                url: url,
                method: "POST",
                data: {
                        'id' : id,
                        'field' : field
                      },      
                headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },                      
            dataType: "json",

                success: function (data) { 
                        // remove the deleted record
                        $('#display_'+data.field).empty();
                        },
                error: function (request, status, error) {
                    console.log(error);
                }

              });                  
        }
    });
}



function getSelected() {
    // Iterate over all checkboxes in the table
       table.$('input[type="checkbox"]').each(function(){
          // If checkbox doesn't exist in DOM
          if(!$.contains(document, this)){
             // If checkbox is checked
             if(this.checked){
                // Create a hidden element 
                alert(this.value);
             }
          } 
       }); 
}
