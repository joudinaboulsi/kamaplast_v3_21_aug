// ajax call to edit variant
function loadVariantDataToEdit(variant_id){

   $.ajax({
         url: "/get-variant-from-id",
         method: "POST",
         data: {
                 'variant_id' : variant_id
               },      
         headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
         },                      
         dataType: "json",
         success: function (data) { console.log(data);
            // change the data of the pop up modal
              $('#edit_variant_product_id').val(data[0].product_id);
              $('#edit_variant_id').val(data[0].variant_id);
              $('#edit_variant_regular_price').val(data[0].regular_price);
              $('#edit_variant_sku').val(data[0].sku);
              $('#edit_variant_stock_quantity').val(data[0].stock_qty);
              $('#edit_variant_weight').val(data[0].weight);
              $('#edit_variant_length').val(data[0].length);
              $('#edit_variant_width').val(data[0].width);
              $('#edit_variant_height').val(data[0].height);
              $('#edit_variant_diameter').val(data[0].diameter);

              //If we did not enabled stock management
              if(stock_mgmt == 0)
              {
                $('#stock_qty_showed').hide();
                if(data[0].stock_status_id == 1)
                  $('#edit_variant_stock_status #stock_option1').prop("selected", true);
                else
                  $('#edit_variant_stock_status #stock_option2').prop("selected", true);
              }
              
              //If we enable stock management
              else
                $('#stock_status_showed').hide();


              data[1].forEach(function(entry) {
                  $('#attribute_'+entry.attribute_id+' #attribute_item_'+entry.attribute_item_id).prop("selected", true);
              });
             },
         error: function(jqXHR, textStatus, errorThrown) {

             console.log('Status:'+jqXHR.status);
             console.log('Text status:'+textStatus);
             console.log('Error Thrown:'+errorThrown);

         }
     });
 }


 // ajax call to edit variant
function loadVariantDataToDuplicate(variant_id){

   $.ajax({
         url: "/get-variant-from-id",
         method: "POST",
         data: {
                 'variant_id' : variant_id
               },      
         headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
         },                      
         dataType: "json",
         success: function (data) { console.log(data);
            // change the data of the pop up modal
              $('#variant_regular_price').val(data[0].regular_price);
              //$('#variant_sku').val(data[0].sku);
              $('#variant_sku').val('');
              $('#variant_stock_quantity').val(data[0].stock_qty);
              $('#variant_weight').val(data[0].weight);
              $('#variant_length').val(data[0].length);
              $('#variant_width').val(data[0].width);
              $('#variant_height').val(data[0].height);
              $('#variant_diameter').val(data[0].diameter);

              //If we did not enabled stock management
              if(stock_mgmt == 0)
              {
                $('#stock_qty_showed').hide();
                if(data[0].stock_status_id == 1)
                  $('#variant_stock_status #add_stock_option1').prop("selected", true);
                else
                  $('#variant_stock_status #add_stock_option2').prop("selected", true);
              }
              
              //If we enable stock management
              else
                $('#stock_status_showed').hide();

              var count = 0; //set attibute counter to zeo
              data[1].forEach(function(entry) { console.log(entry);
                  $('#add_attribute_'+count+' #add_attribute_item_'+entry.attribute_item_id).prop("selected", true);
                  count++;
              });
             },
         error: function(jqXHR, textStatus, errorThrown) {

             console.log('Status:'+jqXHR.status);
             console.log('Text status:'+textStatus);
             console.log('Error Thrown:'+errorThrown);

         }
     });
 }

// ajax call to edit variant promotion
function loadVariantPromoToEdit(variant_id){

  $('#edit_variant_promo_price').val('');
  $('#variant_promo_daterange').val('');

   $.ajax({
         url: "/get-promo-from-variant-id",
         method: "POST",
         data: {
                 'variant_id' : variant_id
               },      
         headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
         },                      
         dataType: "json",
         success: function (data) {
            // change the data of the pop up modal
              $('#variant_id_promo').val(data[0].variant_id);
              $('#edit_variant_promo_price').val(data[0].sales_price);

              if(data[0].sales_price_start_date == null && data[0].sales_price_end_date == null)
              {
                $('#variant_promo_daterange').val();

                //if there's no promotion, hide Stop Promo Button
                $('#stop_promo').hide();
              }

              else
              {
                //if there's a promotion, show Stop Promo Button
                $('#stop_promo').show();


                sales_start = new Date(data[0].sales_price_start_date);
                sales_end = new Date(data[0].sales_price_end_date);

                 console.log(sales_start);

                start_month = ("0" + (sales_start.getMonth() + 1)).slice(-2);
                start_day = ("0" + sales_start.getDate()).slice(-2);
                start_date = start_month + '/' + start_day + '/' + sales_start.getFullYear();

                end_month = ("0" + (sales_end.getMonth() + 1)).slice(-2);
                end_day = ("0" + sales_end.getDate()).slice(-2);
                end_date = end_month + '/' + end_day + '/' + sales_end.getFullYear();                    

                $('#variant_promo_daterange').val(start_date + ' - ' + end_date);
              }
             },
         error: function(jqXHR, textStatus, errorThrown) {

             console.log('Status:'+jqXHR.status);
             console.log('Text status:'+textStatus);
             console.log('Error Thrown:'+errorThrown);

         }
     });
 }


 // ajax call to publish/ unpublish variant
function ajaxPublish(id){

     $.ajax({
         url: "/publish-variant-from-id",
         method: "POST",
         data: {
                 'id' : id,
                 'status' : status
               },      
         headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
         },                      
         dataType: "json",
         success: function (data) { 
              if (data.publish == 1)
                toastr.error('Changes are saved!','Variant Successfully Unpublished!');
              else
                toastr.success('Changes are saved!','Variant Successfully Published!');

              if( (data.is_main == 1) && (data.publish == 1) )
              {
                alert('By unpublishing the main variant, the product will be automatically unpublished.');
                $('.js_published').empty();
                $('.js_published').append('<span class="text-danger"><b>UNPUBLISHED</b></span>');
              }
             },
         error: function(jqXHR, textStatus, errorThrown) {

             console.log('Status:'+jqXHR.status);
             console.log('Text status:'+textStatus);
             console.log('Error Thrown:'+errorThrown);

         }
     });
}


// ==================== DELETE All Variants  =====================

$(document).on('click', '.delete_all_variants', function(e) {       
      // get the id of the select image for delete
     var id = $(this).attr('id');

     var confirmed = false;    
        // confirm your delete
        var $this = $(this);
            if (!confirmed) {
                e.preventDefault();
                swal({
                    title: "Are you sure you want to delete ALL VARIANTS?!",
                    text: "Please confirm your delete action",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete all!",
                    closeOnConfirm: true,
                    html: false
                }, function() {
                    confirmed = true;

                    // confirmed
                    // ajax call to delete the image
                     $.ajax({
                        url: 'delete-all-product-variants',
                        method: "POST",
                        data: {
                                'id' : id
                              },      
                        headers: {
                           'X-CSRF-Token': $('input[name=_token]').val()
                        },                      
                    dataType: "json",

                        success: function (data) { 
                                // remove the deleted records
                                $('.variants_table >tbody tr:gt(0)').empty();
                                // click the close button to close the pop up
                                $('#close_popup').click();
                            /*    location.href = "product-"+product_id+"";*/
                                
                                },
                        error: function (request, status, error) {
                            console.log(error);
                          }

                      });
                    });
            }
});


// ajax call to control adding a variant when there is no product attribute
$(document).on('click', '.add_variant_control', function(e) {   

     var confirmed = false;    
        // confirm your delete
        var $this = $(this);
            if (!confirmed) {
                e.preventDefault();
                swal({
                    title: "You can't add a Variant for the moment",
                    text: "Please add product Attributes before",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "OK!",
                    closeOnConfirm: true,
                    html: false
                }, function() {
                    confirmed = false;
                    });
            }
});


// clear the add variant form
$(document).on('click', '#click_add_variant', function(e) {   

    $('#add_form .textinput').val(''); // empty all text input
    $(".attributes").val($(".attributes option:first").val()); // set the dropdown list to first value -NONE-

});
