// ==================== UNLINK product category  =====================
$(document).on('click', '.delete_cat', function(e) {       
 // get the id of the selected category for unlink
 var id = $(this).attr('id');

 var confirmed = false;    
    // confirm your delete
    var $this = $(this);
        if (!confirmed) {
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: "Please confirm your unlink action",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, unlink it!",
                closeOnConfirm: true,
                html: false
            }, function() {
                confirmed = true;

                // confirmed
                // ajax call to delete the image
                 $.ajax({
                    url: 'unlink-category',
                    method: "POST",
                    data: {
                            'category_id' : id,
                            'product_id' : product_id 
                          },      
                    headers: {
                       'X-CSRF-Token': $('input[name=_token]').val()
                    },                      
                dataType: "json",

                    success: function (data) { 
                            // remove the deleted record
                            $('#categrec_' + id).remove();
                            // click the close button to close the pop up
                            $('#close_popup').click();
                            //reload the page to be able to click on the unlinked category checkbox
                            location.href = "product-"+product_id+"";
                            },
                    error: function (request, status, error) {
                        console.log(error);
                    }

                  });
                });
        }
});