$(document).on('click', '.delete_slide', function(e) {  

// get the id of the selected brand for delete
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
                // ajax call to delete the brand
                 $.ajax({
                    url: 'delete-slide',
                    method: "POST",
                    data: {
                            'id' : id
                          },      
                    headers: {
                       'X-CSRF-Token': $('input[name=_token]').val()
                    },                      
                dataType: "json",

                    success: function (data) { 
                            // remove the deleted record
                            $('#slide_' + id).remove();
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