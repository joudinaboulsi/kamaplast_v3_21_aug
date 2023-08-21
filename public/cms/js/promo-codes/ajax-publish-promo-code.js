// ajax call to edit Promo Code publishing status
function ajaxPublish(id){
   $.ajax({
         url: "/publish-promo-code-from-id",
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
                toastr.error('Promo Code Successfully Unpublished!','Changes are Saved!');
              else 
                toastr.success('Promo Code Successfully Published!','Changes are Saved!');
             },
         error: function(jqXHR, textStatus, errorThrown) {

             console.log('Status:'+jqXHR.status);
             console.log('Text status:'+textStatus);
             console.log('Error Thrown:'+errorThrown);

         }
     });
}