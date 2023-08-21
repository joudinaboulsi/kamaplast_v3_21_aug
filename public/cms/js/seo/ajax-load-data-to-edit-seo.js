// ajax call to edit slide
function loadDataToEdit(id){

  // remove the old displayed image
  $('.display_seo_img').attr('src', '');

   $.ajax({
         url: "/get-page-seo-from-id",
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
              $('input[name*="seo_page_id"]').val(data[0].seo_page_id);
              $('input[name*="title"]').val(data[0].seo_title);
              $('input[name*="description"]').val(data[0].seo_description);
              $('input[name*="keywords"]').val(data[0].seo_keywords);

              if(data[0].og_image != null)
                $('.display_og_img').attr('src', s3_url+'/seo/'+data[0].og_image);

             },
         error: function(jqXHR, textStatus, errorThrown) {

             console.log('Status:'+jqXHR.status);
             console.log('Text status:'+textStatus);
             console.log('Error Thrown:'+errorThrown);

         }
     });
 }