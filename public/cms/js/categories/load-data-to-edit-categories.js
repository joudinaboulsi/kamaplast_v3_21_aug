// ajax call to edit category
function loadDataToEdit(id){

   $.ajax({
         url: "/get-category-data-from-id",
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
              $('#edit_id').val(data[0].category_id);
              $('#edit_name').val(data[0].name);
              $('#edit_description').val(data[0].description);
              $('#edit_parent').val(data[0].parent_id);

            if(data[0].img != null) // if the record has an image
            {  
              $('#display_edit_img').show(); // display the image division
              $('#display_edit_img').attr('src', s3_url+'/categories/thumbs/'+data[0].img); // change the source of the image
            }
                
            else // if the record doesn't have a image
              $('#display_edit_img').hide(); // hide the image division

            $('input[name*="edit_seo_title"]').val(data[0].seo_title);
            $('input[name*="edit_seo_description"]').val(data[0].seo_description);
            $('input[name*="edit_seo_keywords"]').val(data[0].seo_keywords);

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