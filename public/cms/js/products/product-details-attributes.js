// ajax call to edit product attribute
function loadMainAttrNameToEdit(id){

   $.ajax({
         url: "/get-attr-from-id",
         method: "POST",
         data: {
                 'id' : id
               },      
         headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
         },                      
         dataType: "json",
         success: function (data) { console.log(data);
            // change the data of the pop up modal
             $('input[name*="e_id"]').val(data[0].attribute_id);
              $('input[name*="e_attr_name"]').val(data[0].attribute);
             },
         error: function(jqXHR, textStatus, errorThrown) {

             console.log('Status:'+jqXHR.status);
             console.log('Text status:'+textStatus);
             console.log('Error Thrown:'+errorThrown);

         }
 });
}

// ajax call to edit product attribute
function loadMainAttrItemToEdit(attribute_item_id){
   $('#edit_attr_items').tagsinput('removeAll');

   $.ajax({
         url: "/get-main-attr-item-from-id",
         method: "POST",
         data: {
                 'attribute_item_id' : attribute_item_id
               },      
         headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
         },                      
         dataType: "json",
         success: function (data) {
            // change the data of the pop up modal
              $('input[name*="edit_id_attribute_item"]').val(data[0].attribute_item_id);
              $('input[name*="edit_attribute_item"]').val(data[0].name);

              $('#display_edit_attr_item_img').attr('src', s3_url+'/attribute_items/'+data[0].img); // load the right image if it exists

              $('#edit_color_input').val(data[0].color); // load the right color if it exists
             },
         error: function(jqXHR, textStatus, errorThrown) {

             console.log('Status:'+jqXHR.status);
             console.log('Text status:'+textStatus);
             console.log('Error Thrown:'+errorThrown);

         }
 });
}

// ajax call to edit product attribute
function loadAttrToEdit(id){

   $('#edit_attr_items').tagsinput('removeAll');

   $.ajax({
         url: "/get-attr-from-id",
         method: "POST",
         data: {
                 'id' : id
               },      
         headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
         },                      
         dataType: "json",
         success: function (data) { 
            // change the data of the pop up modal
              $('#edit_attribute_id').val(data[0].attribute_id);
              $('#edit_attribute_name').val(data[0].attribute);
              $('#edit_attr_items').tagsinput('add',data[0].attr_items);
             },
         error: function(jqXHR, textStatus, errorThrown) {

             console.log('Status:'+jqXHR.status);
             console.log('Text status:'+textStatus);
             console.log('Error Thrown:'+errorThrown);

         }
     });
}


// ajax call to delete attributes
$(document).on('click', '.delete_attribute', function(e) {
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
                        url: 'delete-attribute',
                        method: "POST",
                        data: {
                                'id' : id
                              },      
                        headers: {
                           'X-CSRF-Token': $('input[name=_token]').val()
                        },                      
                    dataType: "json",

                        success: function (data) { 

                            if(data == 0)
                            {
                                // remove the deleted record
                                $('#recattr_' + id).remove();
                                // click the close button to close the pop up
                                $('#close_popup').click();
                                location.href = "product-"+product_id+"";
                            }

                            else
                            {
                                console.log('Cannot Delete Attribute');
                                swal ( "Something went wrong!" ,  "Cannot delete attribute. Variants are linked to this attribute. Delete or Unlink the variants and try again." ,  "error" );
                            }
                                
                                },
                        error: function (request, status, error) {
                            console.log(error);
                            
                          }

                      });
                    });
            }
});


// ajax call to delete main attribute items
$(document).on('click', '.delete_main_attribute_item', function(e) {       
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
                        url: 'delete-main-attr-item',
                        method: "POST",
                        data: {
                                'id' : id
                              },      
                        headers: {
                           'X-CSRF-Token': $('input[name=_token]').val()
                        },                      
                    dataType: "json",

                        success: function (data) { 

                          if(data == 0)
                            {
                                // remove the deleted record
                                $('#rec_main_attr_item_' + id).remove();
                                // click the close button to close the pop up
                                $('#close_popup').click();
                                location.href = "product-"+product_id+"";
                                
                            }
                               
                            else
                            {
                                console.log('Cannot Delete Main Attribute Item');
                                swal ( "Something went wrong!" ,  "Cannot delete attribute. Variants are linked to this attribute. Delete or Unlink the variants and try again." ,  "error" );
                            }
                            
                          },

                        error: function (request, status, error) {
                            console.log(error);
                          }

                      });
                    });
            }
});
