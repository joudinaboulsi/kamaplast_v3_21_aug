$(".attribute_selection" ).change(function() {
    
    var this_value = this.value;

    //Table containing the list of selected attribute items
    selected_attribute_items = [];
    clickable_attribute_items = [];

    //IF THE ATTRIBUTE ITEM SELECTED CAN BE CLICKABLE
    if($("#attribute_item_"+this_value).attr('dimmed') == 'false')
    {  
        //-------SAVING ALL DATA SELECTED ELEMENTS---------

        // Parsing the list of attributes
        attributes.forEach(function(entry) 
        {  
           // Getting the value of the selected attribute_item (from the parsed attribute)
           attr_item_val = $("input[name='attribute_"+entry.attribute_id+"']:checked").val();
           //Pushing the attribute items in a table if the attribute contains a selected value.
           if(attr_item_val != null)
            selected_attribute_items.push(attr_item_val);

        });

        //-------END SAVING ALL DATA SELECTED ELEMENTS---------


        //-------SAVING ALL DATA CLICKABLE ELEMENT ---------

        $(".attribute_selection").each(function( index ) {
            value = $( this ).val();
            //IF THE ATTRIBUTE ITEM SELECTED CAN BE CLICKABLE
            if($( this ).attr('dimmed') == 'false')
            {
                clickable_attribute_items.push(value);
            }
        });

        //-------END SAVING ALL DATA CLICKABLE ELEMENT ---------
    }

    //IF THE ATTRIBUTE ITEM SELECTED CANNOT BE CLICKABLE
    else
    {  
        selected_attribute_items.push(this.value);
    }
 
    //-------RESETING THE FORM ---------

    //Hide Stock Status
    $('#in_stock').empty();
    // Reset all the radio buttons to Checked = False
    $(".attribute_selection").attr('checked', false);
    // Reset all the radio buttons to Dimmed = True
    $(".attribute_selection").attr('dimmed', 'true');
    // RESET design of all radio buttons
    $(".checkmark").css('opacity', '0.2');
    $(".checkmark").css('border', '0px');
    $('#order_qty').prop('disabled', true);
    $('.add_to_cart').prop('disabled', true);

    //-------RESETING THE FORM ---------


    //-------SETTING NEW FORM ---------

    //If this is NOT the last level of attribute selected
   if(selected_attribute_items.length < attributes.length - 1 )
   {

       clickable_attribute_items.forEach(function(entry) 
       {   
           // Set the selected attribute item to true
           $("#attribute_item_"+entry).attr('checked', false);
           // Set the selected attribute item to not dimmed
           $("#attribute_item_"+entry).attr('dimmed', 'false');
           // Set design of SELECTED attribute items
           $("#checkmark_"+entry).css('opacity', '1');

       });

   }

   selected_attribute_items.forEach(function(entry) 
   {  
       // Set the selected attribute item to true
       $("#attribute_item_"+entry).attr('checked', true);
       // Set the selected attribute item to not dimmed
       $("#attribute_item_"+entry).attr('dimmed', 'false');
       // Set design of SELECTED attribute items
       $("#checkmark_"+entry).css('border', '3px solid #4bb777');
       $("#checkmark_"+entry).css('opacity', '1');
   });

   //------- END SETTING NEW FORM ---------

   //---------- GET ALL ATTRIBUTE ITEMS THAT CAN BE CLICKED AFTER ATTRIBUTE ITEM PRE-SELECTION ------------
   // Ajax call getting the attributes that we can select based on the user pre-selection

    $.ajax({
         url: "/get-variants-from-attributes",
         method: "POST",
         data: {
                 'nb_attribute_items' : selected_attribute_items.length,
                 'attribute_items' : selected_attribute_items.join(),
                 'product_id': product_id
               },      
         headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
         },                      
         dataType: "json",
         success: function (data) {
            
            attributes_we_can_click = data['attributes_we_can_click'];
            variants_prices = data['variants_prices'];
            variants = data['variant_list'];

            //FIll the add to cart button with the right data
            if(selected_attribute_items.length == attributes.length)
            {
                if((variants[0].enable_stock_mgmt == 0 && (variants[0].stock_status_id == 0 || variants[0].stock_status_id == null)) || (variants[0].enable_stock_mgmt == 1 && (variants[0].stock_qty == 0 || variants[0].stock_qty == null)))
                {
                    $('#in_stock').append("<i class=\"fa fa fa-times-circle color-out\"></i> Out of stock");
                }

                else
                {
                    
                    if(variants[0].enable_stock_mgmt == 1)
                        $('#in_stock').append("<i class=\"fa fa fa-check-circle-o color-in\"></i> In stock ("+variants[0].stock_qty+" items remaining)");
                    else
                        $('#in_stock').append("<i class=\"fa fa fa-check-circle-o color-in\"></i> In stock");

                    $('#order_qty').prop('disabled', false);
                    $('.add_to_cart').prop('disabled', false);
                    $('.add_to_cart').attr('variantid', variants[0].variant_id);
                } 
            }

            // Parse all the attribute items that can be clicked
            for(i=0; i < attributes_we_can_click.length; i++)
            {
               // Set all these attribute items to dimmed = false
               $("#attribute_item_"+attributes_we_can_click[i].attribute_item_id).attr('dimmed', 'false');
               //Design all radio buttons that can be selected               
               $("#checkmark_"+attributes_we_can_click[i].attribute_item_id).css('opacity', '1');
            }

            min_regular = parseFloat(variants_prices[0].min_regular_price);
            max_regular = parseFloat(variants_prices[0].max_regular_price);
            max_current = parseFloat(variants_prices[0].max_current_price);
            min_current = parseFloat(variants_prices[0].min_current_price);

            //If there is one unique current price (No Interval in Current Price)
            if(min_current == max_current)
            {
                // If there is one unique Regular price (No interval in Regular price)
                if(min_regular == max_regular)
                {
                    if(min_current == min_regular)
                    {
                        $( ".actual_price" ).html('$'+min_current);
                        $( ".old_price" ).html(''); //to strike
                    }
                    else
                    {
                        $( ".actual_price" ).html('$'+min_current);
                        $( ".old_price" ).html('$'+min_regular); //to strike
                    }
                }
                // If there is a range of regular price
                else
                {
                    $( ".actual_price" ).html('$'+min_regular+' - $'+max_regular); 
                    $( ".old_price" ).html('$'+min_current); //to strike
                }
            }
            // Ig there is a range of Current price
            else
            {
                // If there is one unique Regular price (No interval in Regular price)
                if(min_regular == max_regular)
                {
                    $( ".actual_price" ).html('$'+min_regular); 
                    $( ".old_price" ).html('$'+min_current+' - $'+max_current); //to strike
                }
                // If there is a range of regular price
                else
                {
                    if(min_current == min_regular && max_current == max_regular)
                    {
                        $( ".actual_price" ).html('$'+min_current+' - $'+max_current); //to strike
                        $( ".old_price" ).html(''); //to strike
                    }
                    else
                    {
                        $( ".actual_price" ).html('$'+min_current+' - $'+max_current);
                        $( ".old_price" ).html('$'+min_regular+' - $'+max_regular); //to strike
                    }
                }
            }

        },
         error: function(jqXHR, textStatus, errorThrown) {
             console.log('Status:'+jqXHR.status);
             console.log('Text status:'+textStatus);
             console.log('Error Thrown:'+errorThrown);
        }
    });

    //---------- END GET ALL ATTRIBUTE ITEMS THAT CAN BE CLICKED AFTER ATTRIBUTE ITEM PRE-SELECTION ------------

});



// CHANGE THE VARIANT IMAGE IN THE FRAME ON CLICK
$(".main_attribute" ).click(function() {

   var this_value = this.value;

   // get the url of the variant image
   var bg = $('#checkmark_'+this_value).css('background-image');
   bg = bg.replace('url(','').replace(')','').replace(/\"/gi, "");

   //change the path of the medium and large image
   large_img = bg.replace('attribute_items/thumbs', 'attribute_items/large');
   medium_img = bg.replace('attribute_items/thumbs', 'attribute_items');
   
   // Change the image in the sp-large division 
   $('.sp-large a').attr('href', large_img);
   $('.sp-large a img').attr('src', medium_img);
});