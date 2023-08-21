// ==================== DELETE product tag  =====================

$(document).on('click', '.delete_tag', function(e) {  

    // get the id of the select tag for delete
      var id = $(this).attr('id');
      var type = $(this).attr('name');
      var text_msg = "";
      if(type=="1")
       text_msg = "Please confirm your delete action. If you delete this tag, ALL PRODUCTS linked to this tag will be UNLINKED";
      else text_msg = "Please confirm your delete action. If you delete this tag, ALL USERS linked to this tag will be UNLINKED";

     var confirmed = false;    
        // confirm your delete
        var $this = $(this);
            if (!confirmed) {
                e.preventDefault();
                swal({
                    title: "Delete This Tag?",
                    text: text_msg,
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
                        url: 'delete-tag',
                        method: "POST",
                        data: {
                                'id' : id,
                                'type' : type
                              },      
                        headers: {
                           'X-CSRF-Token': $('input[name=_token]').val()
                        },                      
                    dataType: "json",

                        success: function (data) { 
                                // remove the deleted record
                                $('#rec_' + id).remove();
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