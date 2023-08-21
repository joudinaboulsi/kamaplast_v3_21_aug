// ==================== UNLINK product LINK  =====================
$(document).on('click', '.delete_prod_link', function(e) { 
     
     // get the id of the selected tag for unlink
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
                        url: 'delete-prod-link',
                        method: "POST",
                        data: {
                                'linked_product_id' : id,
                                'current_product_id' : product_id 
                              },      
                        headers: {
                           'X-CSRF-Token': $('input[name=_token]').val()
                        },                      
                    dataType: "json",

                        success: function (data) { 
                                // remove the deleted record
                                $('#prod_link_' + id).remove();
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
