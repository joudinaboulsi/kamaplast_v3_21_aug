// ajax call to edit slide
function loadDataToEdit(id){

  // remove the old displayed image
  $('.display_slide_img').attr('src', '');

   $.ajax({
         url: "/get-slide-data-from-id",
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
              $('input[name*="e_id"]').val(data[0].slide_id);
              $('input[name*="e_title"]').val(data[0].title);
              $('input[name*="e_subtitle"]').val(data[0].subtitle);
              $('input[name*="e_btn_name"]').val(data[0].button_name);
              $('input[name*="e_action"]').val(data[0].action);

              if(data[0].image != null)
                $('.display_slide_img').attr('src', s3_url+'/slides/'+data[0].image);

             },
         error: function(jqXHR, textStatus, errorThrown) {

             console.log('Status:'+jqXHR.status);
             console.log('Text status:'+textStatus);
             console.log('Error Thrown:'+errorThrown);

         }
     });
 }