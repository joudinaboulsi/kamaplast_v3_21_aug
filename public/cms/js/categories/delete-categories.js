// ajax call to delete category
$(document).on('click', '.delete_category', function(e) {
      // get the id of the selected category for delete
     var id = $(this).attr('id');

     var confirmed = false;    
        // confirm your delete
        var $this = $(this);
            if (!confirmed) {
                e.preventDefault();
                swal({
                    title: "Are you sure ?",
                    text: "By deleting this category, all the products linked to it will be unlinked. \n Please confirm you delete action.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: true,
                    html: false
                }, function() {
                    confirmed = true;

                    // confirmed
                    // ajax call to delete the category
                     $.ajax({
                        url: 'delete-category',
                        method: "POST",
                        data: {
                                'id' : id
                              },      
                        headers: {
                           'X-CSRF-Token': $('input[name=_token]').val()
                        },                      
                    dataType: "json",

                        success: function (data) { 
                                // reload the page
                                location.reload();                           
                                },
                        error: function (request, status, error) {
                            console.log(error);
                            swal({
                                title: "Cannot Delete Category!",
                                text: "Please make sure to delete all links between products and this category",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "Yes, delete it!",
                                closeOnConfirm: true,
                                html: false
                            });
                        }

                      });
                    });
            }
});