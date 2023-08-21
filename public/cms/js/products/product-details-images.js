// ==================== Set img as primary image  =====================
$(document).on('click', '.set_primary_img', function(e) {     
  // get the id of the select image
 var img_id = $(this).attr('id');
      
   var confirmed = false;    
      // confirm your delete
      var $this = $(this);
          if (!confirmed) {
              e.preventDefault();
              swal({
                  title: "Are you sure?",
                  text: "Please confirm your action",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "Yes, Set as Primary!",
                  closeOnConfirm: true,
                  html: false
              }, function() {
                  confirmed = true;

                  // confirmed
                  // ajax call to delete the image
                   $.ajax({
                      url: 'img-set-primary',
                      method: "POST",
                      data: {
                              'img_id' : img_id,
                              'product_id' : product_id 
                            },      
                      headers: {
                         'X-CSRF-Token': $('input[name=_token]').val()
                      },                      
                  dataType: "json",

                      success: function (data) { 
                                                     
                          // loop every row
                          $("#prod_img_table tbody tr").each(function(){
                             
                             var image_id = $(this).attr('img_id'); // get the id of every img in every row
                       
                             // create the set primary button
                             var btn = "<a id='"+image_id+"' type='button' class='active set_primary_img' title='Set as primary image'>Set Primary</a>";
                             // create the delete button
                             var delete_btn = '<button type="button" id="'+image_id+'" class="edit_btn delete_product_img" title="Delete img"><i class="fa fa-trash fa-lg"></i></button>';
                             $(this).find('td:eq(1)').html(btn); // insert the "set primary" button
                             $(this).find('td:eq(2)').html(delete_btn); // insert the "delete" button

                          });

                          // remove the "set primary" button from the primary image and replace it by the label "is primary"
                          $('#rec_'+img_id+' td:nth-child(2)').empty().html('<span class="label label-primary b_radius">Is primary</span>'); 

                          $('#rec_'+img_id+' td:nth-child(3)').empty(); // remove the "delete" button from the primary image

                          var img_src = $('#prod_img_'+img_id).attr('src'); // get the source of the selected image
                          img_src = img_src.split('/'); // split the full source to get the image name in the DB
                          img_src = img_src.pop(); // get the last element of the array (image name)
                   
                          //create the edit button
                             var edit_btn = '<button onclick=\'loadEditPrimaryImage("'+img_id+'" , "'+img_src+'");\' type="button" data-toggle="modal" data-target="#edit_primary_img" id="'+img_id+'"" class="edit_btn edit_primary_img" title="Edit primary image"><i class="fa fa-edit fa-lg"></i></button>';

                          $('#rec_'+img_id+' td:nth-child(3)').html(edit_btn); // add the "edit" button from the primary image   


                         var img_src =  $('#rec_'+img_id+' td:nth-child(1) img').attr('src'); // get the source of the thumb image
                         img_src = img_src.replace('/thumbs',''); // remove the sequence "/thumbs" from the img path
                         $('#product_primary').attr('src', img_src); //change the source of the main image


                          },
                      error: function (request, status, error) {
                          console.log(error);
                      }

                    });
                  });
          }
});

// ajax call to load the image and id of the image to iamge
function loadEditPrimaryImage(primary_img_id, img)
{
  // populate the selected date in the form 
  $( "input[name*='edit_primary_img_id']" ).val(primary_img_id);
  $('#display_edit_primary_img').attr('src', s3_url+'/products/'+img);
}  