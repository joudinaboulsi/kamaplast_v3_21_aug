// ajax call to view address
function getAddrDetails(address_id){ 

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
              $('#view_address_id').val(data[0].address_id);
              $('#address_fullname_view').val(data[0].name);
              $('#company_view').val(data[0].company);
              $('#address_view').val(data[0].address);
              $('#apartment_view').val(data[0].apartment);
              $('#country_view').val(data[0].country_name);
              $('#postal_code_view').val(data[0].postal_code);
              $('#city_view').val(data[0].city);
              $('#address_phone_view').val(data[0].phone);
             },
         error: function(jqXHR, textStatus, errorThrown) {

             console.log('Status:'+jqXHR.status);
             console.log('Text status:'+textStatus);
             console.log('Error Thrown:'+errorThrown);

         }
     });
 }