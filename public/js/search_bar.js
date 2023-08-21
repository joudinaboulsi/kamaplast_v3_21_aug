 $(document).ready(function(){

    $("#txt_search").keyup(function(){

        $("#searchResult").show(); // show the search result div (for firefox bug display)
        var search = $(this).val();

        if(search != "") // if the input search is not empty
        {
            $('.overlay_for_search').show(); // show the black overlay

            $.ajax({
                url: '/get-search',
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },  
                data: {
                        search:search
                      },
                dataType: 'json',
                success:function(response){

                    var len = response.length;
                    $("#searchResult").empty(); // clear the search on every ajax call

                    if(len == 0)
                    {
                        var element = "<li class='no_result'><b>No results has been found for </b><i>\""+search+"\"</i></li>";

                        $("#searchResult").append(element);
                    }

                    else
                    {
                        for( var i = 0; i<len; i++)
                        {
                            var product_id = response[i]['product_id'];
                            var name = response[i]['name'];
                            if(response[i]['img'] !== null)
                                var img = s3_url+"/products/"+response[i]['img'];
                            else
                                var img = '/images/product_icon.png';

                            // transform strings to lower case
                            var name_lower = name.toLowerCase();
                            var search_lower = search.toLowerCase();

                            var search_length = search.length;

                            // create the element
                            var element = "<li value='"+product_id+"'>\
                                                <a class='search_result_link' href='"+product_details_route.replace(':product_id', product_id)+"'>\
                                                    <img class='search_img' src='"+img+"'>\
                                                    <span id='prod_"+product_id+"'>"+name+"</span>\
                                                </a>\
                                            </li>";
                            
                            $("#searchResult").append(element);

                            // highlight in bold the searched sequence
                            $("#prod_"+product_id).html(name_lower.substring(0, name_lower.search(search_lower))+
                                                 "<b>"+ name_lower.substring(name_lower.search(search_lower), (name_lower.search(search_lower)+search_length)) + "</b>"
                                                      + name_lower.substring((name_lower.search(search_lower)+search_length) , name_lower.length));


                        }
                    }
                }
            });
        }

        else // if the search bar is empty
            closeSearch(); // exit the search module 
    });



// function that exit the search module
function closeSearch()
{
    $('.overlay_for_search').hide(); // remove the black overlay
    $("#searchResult").empty(); // clear the division
    $("#searchResult").hide(); // hide the search result div (firefox display purpose)
}


// close the search module when clicking outside it
$(window).click(function() {
    closeSearch(); 
});


// prevent the search module to close when clicking inside it 
$("#searchResult").click(function(event) {
    event.stopPropagation();
});


});
