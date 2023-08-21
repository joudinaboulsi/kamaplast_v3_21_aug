// ==================== UNLINK user Tag  =====================
$(document).on('click', '.delete_news_tag', function(e) {       
     // get the id of the selected tag for unlink
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
                        url: 'delete-news-tag',
                        method: "POST",
                        data: {
                                'tag_id' : id,
                                'news_id' : news_id 
                              },      
                        headers: {
                           'X-CSRF-Token': $('input[name=_token]').val()
                        },                      
                    dataType: "json",

                        success: function (data) { 
                                // remove the deleted record
                                $('#news_tag_' + id).remove();
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