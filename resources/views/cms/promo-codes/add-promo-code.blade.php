@extends('cms.layouts.app')

@section('content')

{!! Form::open(array('route' => 'add_promo', 'id' => 'add_form', 'class' => 'add-form', 'files' => true)) !!}

<!-- Product title, image and short description Section -->
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-3">
    <h2> Discounts</h2>
    <ol class="breadcrumb ">
      <li>
        <strong><a href="{{ route('cms_promo_codes') }}">Discounts</a></strong>
      </li>
      <li>
         Add Discount 
      </li>
    </ol>
    <hr>
  </div>
  <div class="col-lg-9 margin-top">
    <div style="text-align:right; margin-top:10px;">
         <input type="submit" name="submit_add" value="Save" class="ladda-button btn btn-primary save_btn" data-style="expand-right" id="submit_add"/>
    </div>
  </div>
</div>

<div class="wrapper animated fadeInRight margin-top">
  <div class="row">
    <!-- Discount code -->
    <div class="col-md-5">
      <div class="ibox">
        <div class="ibox-title">
          <h5><span><img src="/cms/svg/password.svg" width="24"></span>&nbsp Discount code <small>Name of the promotion code</small></h5>
          <div class="ibox-tools">
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content profile-content">
          <div id="signupbox" class="mainbox">

            <div class="form-group required"> 
              <label for="code_name" class="control-label"> Promo Code Name<span class="red_star">*</span> <br><small><i>Generate a Promo Discount</i></small></label>
              <div class="input-group date col-md-12">
                <input class="input-md textinput textInput form-control" maxlength="255" id="code_name" name="code_name" placeholder="Promo Code name" style="margin-right: 10px; width:75%" type="text" required/>
                <button id="generate_code" type='button' class='btn btn-success btn-sm active' title='Generate a code'>Generate</button>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
    <!-- Discount Type -->
    <div class="col-md-7">
      <div class="ibox">
        <div class="ibox-title">
          <h5><span><img src="/cms/svg/discount.svg" width="23"></span> &nbsp Options <small>Choose the discount option and the value related to it</small></h5>
          <div class="ibox-tools">
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content profile-content">
          <div id="signupbox" class="mainbox">
            <div class="row">

              <div class="form-group required col-md-6">
                <label for="type" class="font-normal">Type <br><small><i>Type of the Discount % or $</i></small></label>
                <div class="controls input-group date col-md-12">
                  <select class="form-control" id="type" name="type">
                    <option id="percentage" value="0">percentage</option>
                    <option id="amount" value="1">Fixed amount</option>
                  </select>
                </div>
              </div>
              <div class="form-group required col-md-6">
                  <label for="value" class="font-normal">Value<span class="red_star">*</span> <br><small><i>Value of the discount</i></small></label>
                  <div class="input-group date col-md-12">
                    <input class="input-md textinput textInput form-control" id="discount_value" name="discount_value" placeholder="Type the value" type="text" required />
                  </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
     
<div class="wrapper animated fadeInRight margin-top">
  <div class="row">
    <!-- Minimum requirement Section -->
    <div class="col-md-5">
      <div class="ibox">
        <div class="ibox-title">
          <h5><span><img src="/cms/svg/padlock.svg" width="23"></span> &nbsp Minimum requirement <small>Choose the requirements to get the promo</small></h5>
           <div class="ibox-tools">
              <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
              </a>
            </div>
        </div>
        <div class="ibox-content profile-content">
          <div id="signupbox" class="mainbox">
             <div id="none_box" style="padding-bottom: 0px;">
                <input id="none_id" class='big_radio' type="radio" value="0" name="requirement" checked>
                <label class="pointer" for="none_id">  None </label>
            </div>

            <div id="amount_radio_box" style="padding-top: 5px">
                <input id="amount_required" class='big_radio' type="radio" value="1" name="requirement" >
                <label class="pointer" for="amount_required">  Minimum purchase amount  </label>
            </div>

            <div class="form-group required" id="purchase_amount_div" style="display: none">
              <div class="input-group date col-md-12">
                <input class="input-md textinput textInput form-control" type="text" id="purchase_amount" name="purchase_amount" value="" placeholder="Edit Purchase Amount ($)"/>
              </div>
            </div> 


            <div id="qty_radio_box" style="padding-top: 5px">
                 <input id="qty_required" class='big_radio' type="radio" value="2" name="requirement" >
                <label class="pointer" for="qty_required">  Minimum quantity of items  </label>
            </div>

            <div class="form-group required" id="items_qty_div" style="display: none">
              <div class="input-group date col-md-12">
                <input class="input-md textinput textInput form-control" type="text" id="items_qty" name="items_qty" value="" placeholder="Edit Items Quantity"/>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Usage limits Section -->
    <div class="col-lg-7">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5><span><img src="/cms/svg/viral-marketing.svg" width="23"></span> &nbsp Usage limits <small>Manage usage limits of the discount</small></h5>
          <div class="ibox-tools">
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <div id="signupbox" class="mainbox"> 

            <div id="checkbox_nbr_of_times_div" style="padding-top: 5px">
                <input id="checkbox_nbr_of_times" class='big_radio' type="checkbox" value="1" name="use_limit">
                <label class="pointer" for="checkbox_nbr_of_times">  Limit number of times that the discount can be used  </label>
            </div>


            <div class="form-group required" id="nbr_of_times_div" style="padding-bottom: 15px;display: none">
              <div class="input-group date col-md-12">
                <input class="input-md textinput textInput form-control" type="text" id="nbr_of_times" name="nbr_of_times" value="" placeholder="Type Number of Times"/>
              </div>
            </div>

      
            <div id="checkbox_one_use_div" style="padding-top: 5px">
                <input id="checkbox_one_use" class='big_radio' type="checkbox" value="1" name="once_per_user">
                <label class="pointer" for="checkbox_one_use">  Limit to one use per customer  </label>
            </div>

          </div> 
        </div>
      </div>
    </div>
  </div>
</div>
   
<div class="wrapper animated fadeInRight margin-top">
  <div class="row">  
    <!-- ACTIVE DATES Section -->
    <div class="col-lg-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><span><img src="/cms/svg/calendar.svg" width="23"></span> &nbsp ACTIVE DATES <small>Manage the Start and End date of the Discount</small></h5>
          <div class="ibox-tools">
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <div id="signupbox" class="mainbox"> 

            <div class="form-group required">
              <label class="font-normal"> Schedule<span class="red_star">*</span> <br><small><i>Choose the Start and End date of the Discount</i></small></label>
              <div>
                <input onkeydown="return false" class="form-control" type="text" name="discount_daterange" value="" autocomplete="off" required />
              </div>
            </div>

          </div>
        </div>
      </div>
    </div> 
  </div>
</div>

<div class="modal-footer padding-bottom margin-top" style="border-top: none;margin-top: 15px; margin-right: 15px;">
  <input type="submit" name="submit_add" value="Save" class="ladda-button btn btn-primary save_btn" data-style="expand-right" id="submit_add"/>
</div>

{!! Form::close() !!}

<script src="/cms/js/custom_functions.js"></script>
<script type="text/javascript">

$(document).ready(function(){

    //script for Data Table
    $('.dataTables-example').DataTable({
        ordering: false,
        paging: false,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            {extend: 'copy'},
            {extend: 'csv'},
            {extend: 'excel', title: 'ExampleFile'},
            {extend: 'pdf', title: 'ExampleFile'},
            {extend: 'print',
             customize: function (win){
                    $(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');

                    $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
            }
            }
        ]
    });


      //script to generate automatically a Promo Code 
      $('#generate_code').click( function() {
          var code = '';
          var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
          var i = 0; 
          
         for (var i = 0; i < 12; i++)
            code += possible.charAt(Math.floor(Math.random() * possible.length));
          
         $('#code_name').val(code);

      }); 

      //script to activate multiselect input
      var config = {
                  '.chosen-select'           : {},
                  '.chosen-select-deselect'  : {allow_single_deselect:true},
                  '.chosen-select-no-single' : {disable_search_threshold:10},
                  '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
                  '.chosen-select-width'     : {width:"95%"}
                  }
              for (var selector in config) {
                  $(selector).chosen(config[selector]);
              }

      // call the plugin of the daterange input
      $('input[name="discount_daterange"]').daterangepicker({
        format: 'YYYY-MM-DD',
        startDate: moment(),
        endDate: moment().add(30, 'days'),
        minDate: moment(),
      });

      // Open Products section
      $( "#apply_to_product_box" ).click(function() {
        $( "#choose_product" ).slideDown( "slow" );
        $('select[id="products"]').prop('required',true);
      });

      // Close Products section
      $( "#entire_order_box" ).click(function() {
        $( "#choose_product" ).slideUp( "slow" );
        $('select[id="products"]').prop('required',false);
      });

      // Open Purchase Amount section and Close items quantity input
      $( "#amount_radio_box" ).click(function() { 
        $( "#purchase_amount_div" ).slideDown( "slow" );
        $('input[name="purchase_amount"]').prop('required',true);
        $('#amount_required').prop('checked', true);
        $( "#items_qty_div" ).slideUp( "slow" );
        $('input[name="items_qty"]').prop('required',false);
      });

      // Open items quantity input section and Close Purchase Amount
      $( "#qty_radio_box" ).click(function() {
        $( "#items_qty_div" ).slideDown( "slow" );
        $('input[name="items_qty"]').prop('required',true);
        $('#qty_required').prop('checked', true);
        $( "#purchase_amount_div" ).slideUp( "slow" );
        $('input[name="purchase_amount"]').prop('required',false);
      });

      // Close Purchase Amount and items quantity input
      $( "#none_box" ).click(function() {
        $( "#purchase_amount_div" ).slideUp( "slow" );
        $('input[name="purchase_amount"]').prop('required',false);
        $('#none_id').prop('checked', true);
        $( "#items_qty_div" ).slideUp( "slow" );
        $('input[name="items_qty"]').prop('required',false);
      });

      // Close/open number of times of the promo input
      $( "#checkbox_nbr_of_times_div" ).click(function() {
        if( $("#checkbox_nbr_of_times").is(":unchecked"))
        {
          $( "#nbr_of_times_div" ).slideUp( "slow" );
          $('input[name="use_limit"]').prop('required',false);
        }
        else
        {
         $( "#nbr_of_times_div" ).slideDown( "slow" );
         $('input[name="use_limit"]').prop('required',true);
        }
      });

      // ================== ladda loader for edit Name and Short Description =================
      loadLadda('submit_add');
  });
</script>

@endsection