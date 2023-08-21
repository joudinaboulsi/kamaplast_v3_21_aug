// load the product dynamic content based on the selected filter (BRANDS + TAGS + DISCOUNTS)
$('.click_load').change(function() {
    
    // get the name of the input to identify which attribute we are working on 
    name = $(this).attr("name");
    //get the value of the checked element
    value = $(this).attr('my_value');
    // define if the clicked input is checked or no (true or false)
    checked = this.checked;
//console.log(parseFloat(interval.max_price)); 
    $.ajax({
             url: url_click_filters,
             method: "POST",
             data: {
                     'name' : name,
                     'my_value': value, 
                     'checked' : checked
                   },      
             headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
             },                      
             dataType: "json",
             success: function (data) { 
                
                    $("#dynamic_products").empty(); //empty the dynamic product div
                    $("#dynamic_products").load(url_load_ajax_products); // load the new div content

                 },
             error: function(jqXHR, textStatus, errorThrown) {

                 console.log('Status:'+jqXHR.status);
                 console.log('Text status:'+textStatus);
                 console.log('Error Thrown:'+errorThrown);

             }
         });
});

// load the product dynamic content based on the selected filter (PRICE RANGE)
$("#submit_price_range" ).on("click", function() {

    // get the min and max value
    min_price = $("#min_price").val();
    max_price = $("#max_price").val();

    $.ajax({
             url: url_price_range,
             method: "POST",
             data: {
                     'min_price' : min_price,
                     'max_price': max_price
                   },      
             headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
             },                      
             dataType: "json",
             success: function (data) {
                    
                    // display the selected range 
                    $('#selected_range').html(min_price+' - '+max_price);

                    $("#dynamic_products").empty(); //empty the dynamic product div
                    $("#dynamic_products").load(url_load_ajax_products); // load the new div content 

                 },
             error: function(jqXHR, textStatus, errorThrown) {

                 console.log('Status:'+jqXHR.status);
                 console.log('Text status:'+textStatus);
                 console.log('Error Thrown:'+errorThrown);

             }
        });    
    });