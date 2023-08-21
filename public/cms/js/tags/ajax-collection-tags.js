// ajax function to display tags collection
function ajaxTagCollection(tag_id)
{
 $.ajax({
         url: "/update-collection-tag",
         method: "POST",
         data: {
                 'tag_id' : tag_id
               },      
         headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
         },                      
         dataType: "json",
         success: function (data) { console.log(data);
              if (data == 1)
                toastr.success('Tag added to collection!','Changes are Saved!');
              else 
                toastr.error('Tag removed from collection!','Changes are Saved!');
             },
         error: function(jqXHR, textStatus, errorThrown) {

             console.log('Status:'+jqXHR.status);
             console.log('Text status:'+textStatus);
             console.log('Error Thrown:'+errorThrown);

         }
     });
}