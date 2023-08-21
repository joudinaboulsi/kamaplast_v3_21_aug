// ajax call to load address details to edit address
function getAddrtoEdit(address_id){

   $.ajax({
         url: "/get-address-details",
         method: "POST",
         data: {
                 'address_id' : address_id
               },      
         headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
         },                      
         dataType: "json",
         success: function (data) {
            // change the data of the pop up modal
              $('#address_id_edit').val(data[0].address_id);
              $('#address_fullname_edit').val(data[0].name);
              $('#company_edit').val(data[0].company);
              $('#address_edit').val(data[0].address);
              $('#apartment_edit').val(data[0].apartment);
              $('#country_edit').val(data[0].country_id);
              $('#postal_code_edit').val(data[0].postal_code);
              $('#city_edit').val(data[0].city);
              $('#address_phone_edit').val(data[0].phone);
             },
         error: function(jqXHR, textStatus, errorThrown) {

             console.log('Status:'+jqXHR.status);
             console.log('Text status:'+textStatus);
             console.log('Error Thrown:'+errorThrown);

         }
     });
 }