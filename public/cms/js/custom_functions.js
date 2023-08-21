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
                               'X-CSRF-Token': $('input[name=_token]').val()
                            },                      
                        dataType: "json",

                            success: function (data) { 
                                    // remove the deleted record
                                    $('#' + record_to_remove + id).remove();
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
                               'X-CSRF-Token': $('input[name=_token]').val()
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
                   'X-CSRF-Token': $('input[name=_token]').val()
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
