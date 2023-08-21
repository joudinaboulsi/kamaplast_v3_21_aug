@extends('frontend.layouts.app')

@section('content')

<div class="container main-container headerOffset">

    <div class="row">
        <div class="breadcrumbDiv col-xs-12">
            <ul class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li>{{$user[0]->name}}</li>
            </ul>
        </div>
    </div> 

    <div class="row userInfo">
        <div class="col-xs-12">

            <!-- tabs -->
            <div class="tabbable tabs-left">
                <ul class="nav nav-tabs nav-stacked">
                    <li><a href="#info" data-toggle="tab">Personal Information</a></li>
                    <li><a href="#addresses" data-toggle="tab">My Addresses</a></li>
                    <li><a href="#history" data-toggle="tab">Order History</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" id="info">                
                        <div class="">
                            <h3>Personal Information</h3>
                            @include('frontend.users.user-information')                  
                        </div>
                    </div> 
                    <div class="tab-pane" id="addresses"> 
                        <div class="">
                            <h3>My Addresses</h3>
                            @include('frontend.users.user-addresses')       
                        </div>
                    </div>
                
                    <div class="tab-pane" id="history"> 
                        <div class="">
                            <h3>Order History</h3>
                            @include('frontend.users.user-orders')                
                        </div>
                    </div>
                </div>
            </div>
            <!-- /tabs -->

        </div>
    </div>
    <!--/row-->

    <div style="clear:both"></div>
</div>
<!-- /wrapper -->
<div class="gap"></div>

<script src="/js/jquery/jquery-2.1.3.min.js"></script>
<script src="/js/custom_functions.js"></script>
<script type="text/javascript">

$(document).ready(function(){

    $('.active').hide();
    var hash = window.location.hash;

    if (hash) {
        var selectedTab = $('.nav li a[href="' + hash + '"]');
        selectedTab.trigger('click', true);
    }

    // ================== Converting php data to a json =================
    var has_newsletters = <?=json_encode($user[0]->has_newsletters);?>

    if(has_newsletters==1)
        $('#has_newsletters').prop('checked',true);

     // ================== ladda loader for add user address =================
    loadLadda('submit_add_address');

    // ================== ladda loader for add user address =================
    loadLadda('submit_edit_address');

    // ==================== DELETE address  =====================
    ajaxDelete('.delete_user_address', 'delete-user-address', 'addr_rec_');
});


 // ajax call to load address details to edit address
    function getAddrtoEditByUser(address_id){

       $.ajax({
             url: "/get-address-details-for-user",
             method: "POST",
             data: {
                     'address_id' : address_id
                   },      
             headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
             },                      
             dataType: "json",
             success: function (data) { console.log(data);
                // change the data of the pop up modal
                  $('#address_id_edit').val(data[0].address_id);
                  $('#fullname_edit').val(data[0].name);
                  $('#company_edit').val(data[0].company);
                  $('#address_edit').val(data[0].address);
                  $('#apartment_edit').val(data[0].apartment);
                  $('#postal_code_edit').val(data[0].postal_code);
                  $('#city_edit').val(data[0].city);
                  $('#address_phone_edit').val(data[0].phone);
                  $("#country_edit").val(data[0].country_id);

                 },
             error: function(jqXHR, textStatus, errorThrown) {

                 console.log('Status:'+jqXHR.status);
                 console.log('Text status:'+textStatus);
                 console.log('Error Thrown:'+errorThrown);

             }
         });
     }

</script>

<!-- include footable plugin -->
<link href="/css/footable-0.1.css" rel="stylesheet" type="text/css"/>
<link href="/css/footable.sortable-0.1.css" rel="stylesheet" type="text/css"/>

<script src="/js/footable.js" type="text/javascript"></script>
<script src="/js/footable.sortable.js" type="text/javascript"></script>

<script type="text/javascript">
    $(function () {
        $('.footable').footable();
    });
</script>

@endsection