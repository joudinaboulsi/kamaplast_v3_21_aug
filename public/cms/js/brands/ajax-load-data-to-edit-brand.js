// ajax call to edit brand
function loadDataToEdit(id){

  // remove the old displayed image
  $('.display_brand_img').attr('src', '');


   $.ajax({
         url: "/get-brand-data-from-id",
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
              $('#edit_id').val(data[0].brand_id);
              $('#edit_name').val(data[0].name);
              $('#edit_description').val(data[0].description);

              if(data[0].img != null)
                $('.display_brand_img').attr('src', s3_url+'/brands/'+data[0].img);

             },
         error: function(jqXHR, textStatus, errorThrown) {

             console.log('Status:'+jqXHR.status);
             console.log('Text status:'+textStatus);
             console.log('Error Thrown:'+errorThrown);

         }
     });
 }