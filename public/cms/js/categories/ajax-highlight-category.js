// ajax function to highlight a category
function ajaxCategHighlight(category_id)
{
 $.ajax({
         url: "/highlight-category",
         method: "POST",
         data: {
                 'category_id' : category_id
               },      
         headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
         },                      
         dataType: "json",
         success: function (data) { console.log(data);
              if (data == 1)
                toastr.success('Category Successfully highlighted!','Changes are Saved!');
              else 
                toastr.error('Hightlight removed from category!','Changes are Saved!');
             },
         error: function(jqXHR, textStatus, errorThrown) {

             console.log('Status:'+jqXHR.status);
             console.log('Text status:'+textStatus);
             console.log('Error Thrown:'+errorThrown);

         }
     });
}