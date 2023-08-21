$(".sort_product" ).on("click", function() {
    
	order = $(this).data('sort');

    $.ajax({
             url: url_sort_by,
             method: "POST",
             data: {
                     'show_list' : show_list,
                     'sort_by': order
                   },      
             headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
             },                      
             dataType: "json",
             success: function (data) { 

                $("#dynamic_products").empty(); //empty the dynamic product div
                $("#dynamic_products").load(display_sorted_route); // load the new div content

                 },
             error: function(jqXHR, textStatus, errorThrown) {

                 console.log('Status:'+jqXHR.status);
                 console.log('Text status:'+textStatus);
                 console.log('Error Thrown:'+errorThrown);

             }
         });
});