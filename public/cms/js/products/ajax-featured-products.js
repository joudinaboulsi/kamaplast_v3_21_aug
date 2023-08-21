// ajax call to set the product as featured
function ajaxFeatured(id){ 
   $.ajax({
         url: "/feature-product-from-id",
         method: "POST",
         data: {
                 'id' : id
               },      
         headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
         },                      
         dataType: "json",
         success: function (data) {
              if (data == 1)
                toastr.success('Product Successfully Featured!','Changes are Saved!');
              else 
                toastr.error('Product not featured anymore!','Changes are Saved!');
             },
         error: function(jqXHR, textStatus, errorThrown) {

             console.log('Status:'+jqXHR.status);
             console.log('Text status:'+textStatus);
             console.log('Error Thrown:'+errorThrown);

         }
     });
}