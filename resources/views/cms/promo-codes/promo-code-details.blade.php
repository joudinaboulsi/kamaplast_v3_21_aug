@extends('cms.layouts.app')

@section('content')


{!! Form::open(array('route' => 'edit_promo_code_path', 'id' => 'add_form', 'class' => 'add-form', 'files' => true)) !!}

<input type="hidden" name="promo_code_id" value="{{$promo_code_details[0]->promo_code_id}}">

<!-- Header and Promo code heneral Information -->
<div class="row wrapper border-bottom white-bg page-heading">
  
  <div class="row">
    <div class="col-lg-3">
      <h2>Discounts</h2>
      <ol class="breadcrumb ">
        <li>
          <strong><a href="{{ route('cms_promo_codes') }}">Discounts</a></strong>
        </li>
        <li>
          {{ $promo_code_details[0]->name }} 
          @if(strtotime($promo_code_details[0]->end_date) < strtotime(date("Y/m/d")))
          <span class="label label-danger b_radius" style="position:relative; left:5px;">Expired</span> 
          @endif
        </li>
      </ol>
      <br>
    </div>

    <!-- <div class="col-lg-9 margin-top">
      <div style="text-align:right; margin-top:10px;">
        <input type="submit" name="submit_edit" value="Save" class="ladda-button btn btn-primary save_btn" data-style="expand-right" id="submit_edit"/>
      </div>
    </div> -->

  </div>

  <div class="row">
        <div class="col-lg-4">
            <table class="table table-hover margin bottom">
                <tbody>
                <tr>
                    <td class="text-center">Promo code</td>
                    <td> <b> {{ $promo_code_details[0]->name }} </b> </td>
                </tr>
                <tr>
                    <td class="text-center">Discount:</td>
                    <td>
                      <b style="color:#ed5565">
                        @if($promo_code_details[0]->discount_percentage != NULL)
                         - {{$promo_code_details[0]->discount_percentage}} %
                        @elseif($promo_code_details[0]->discount_value != NULL)
                         - ${{$promo_code_details[0]->discount_value}}
                        @endif
                      </b>
                     </td>
                </tr>
                 <tr>
                    <td class="text-center">Active date</td>
                    <td><b>{{date("d M Y", strtotime($promo_code_details[0]->start_date))}} &nbsp - &nbsp  {{date("d M Y", strtotime($promo_code_details[0]->end_date))}}</b></td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="col-lg-7">
            <table class="table table-hover margin bottom">
                <tbody>
                 @if($promo_code_details[0]->min_purchase_amount != NULL || $promo_code_details[0]->min_qty_items != NULL) 
                 <tr>
                    <td class="text-center">Minimum requirement</td>
                    <td>
                      <ul>
                        @if($promo_code_details[0]->min_purchase_amount != NULL)
                         <li><b>Minimum purchase amount: ${{$promo_code_details[0]->min_purchase_amount}} </b></li> 
                        @endif
                        
                        @if($promo_code_details[0]->min_qty_items != NULL)
                          <li><b>Minimum quantity of items: (x{{$promo_code_details[0]->min_qty_items}}) </b></li> 
                        @endif 
                      </ul>

                    </td>
                </tr>
                @endif
                 
                 <tr>
                    <td class="text-center">Usage limits</td>
                    <td>
                      <ul>
                        @if($promo_code_details[0]->use_limit != NULL)

                          <?php  // define the string 'time' in singular or plural
                          if($promo_code_details[0]->use_limit > 1)
                            $times = 'times';
                          else
                            $times = 'time';
                          ?>

                         <li><b>This discount can be used only {{$promo_code_details[0]->use_limit}} {{$times}}</b></li> 
                        @endif
                        
                        <li><b>
                        @if($promo_code_details[0]->one_use_per_customer == 1)
                           User can use the promo only 1 time
                        @else
                          User can use the promo many times
                        @endif 
                        </b></li> 
                      </ul>

                    </td>
                </tr>
 
                </tbody>
            </table>
        </div>
    </div>

</div>
<!--
<div class="wrapper animated fadeInRight margin-top">
  <div class="row">
-->
    <!-- Usage limits Section 
    <div class="col-lg-5">
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
            <input id="checkbox_nbr_of_times" class='big_radio' type="checkbox" value="1" name="use_limit" @if($promo_code_details[0]->use_limit != null) checked @endif>
            <label class="pointer" for="checkbox_nbr_of_times">  Limit number of times that the discount can be used  </label>
        </div>


        <div class="form-group required" id="nbr_of_times_div" style="padding-bottom: 15px;display: none">
          <div class="input-group date col-md-12">
            <input class="input-md textinput textInput form-control" type="text" id="nbr_of_times" name="nbr_of_times" value="{{$promo_code_details[0]->use_limit}}" placeholder="Type Number of Times"/>
          </div>
        </div>

  
        <div id="checkbox_one_use_div" style="padding-top: 5px">
            <input id="checkbox_one_use" class='big_radio' type="checkbox" value="1" name="once_per_user" @if($promo_code_details[0]->one_use_per_customer ==1) checked @endif>
            <label class="pointer" for="checkbox_one_use">  Limit to one use per customer  </label>
        </div>

      </div> 
    </div>
  </div>
</div>-->
    <!-- ACTIVE DATES Section 
    <div class="col-lg-7">
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
            <input onkeydown="return false" class="form-control" autocomplete="off" type="text" name="discount_daterange" value="{{date('m/d/Y', strtotime($promo_code_details[0]->start_date))}} - {{date('m/d/y', strtotime($promo_code_details[0]->end_date))}}"  />
          </div>
        </div>
      </div> 
    </div>
  </div>
</div> -->
<!--  
  </div>
</div>

<div class="modal-footer padding-bottom margin-top" style="border-top: none;">
  <input type="submit" name="submit_edit" value="Save" class="ladda-button btn btn-primary save_btn" data-style="expand-right" id="submit_edit1"/>
</div>

-->

{!! Form::close() !!}

<script src="/cms/js/custom_functions.js"></script>
<script type="text/javascript">

/*

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
        
       $('#edit_code_name').val(code);

    }); 

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
      opens: 'left',
      format: 'MM/DD/YYYY',
      startDate: moment(),
      endDate: moment().add(5, 'days'),
      minDate: moment(),
    });



    // Open number of times of the promo input if it initially exists
    if($("#checkbox_nbr_of_times").is(":checked"))
    {
      $( "#nbr_of_times_div" ).slideDown( "slow" );
      $('input[name="use_limit"]').prop('required',true);
    }

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
});

 // ================== ladda loader for edit Name and Short Description =================
    loadLadda('submit_edit');
    loadLadda('submit_edit1');

*/
</script>

@endsection