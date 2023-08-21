// ajax call to edit User publishing status
function ajaxPublish(id){
   $.ajax({
         url: "/publish-user-from-id",
         method: "POST",
         data: {
                 'id' : id
               },      
         headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
         },                      
         dataType: "json",
         success: function (data) { console.log(data);
              if (data == 1)
                toastr.error('User Successfully Unpublished!','Changes are Saved!');
              else 
                toastr.success('User Successfully Published!','Changes are Saved!');
             },
         error: function(jqXHR, textStatus, errorThrown) {

             console.log('Status:'+jqXHR.status);
             console.log('Text status:'+textStatus);
             console.log('Error Thrown:'+errorThrown);

         }
     });
}