
@extends('cms.layouts.app')

@section('content')

<!-- Header and General Information -->
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-4">
    <h2>Discounts</h2>
    <ol class="breadcrumb active">
      <li>
        <strong><a href="">Create Promotions and Discounts</a></strong>
      </li>
    </ol>
  </div>
  <div class="col-lg-8">
    <div class="title-action">
      <a href="{{ route('cms_add_promo_code_path') }}" class="btn btn-primary">Add Discount</a>
    </div>
  </div>
</div>

<!-- Loading datatable of the list of discounts -->
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>Promo Codes</h5>
          <div class="ibox-tools">
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <div class="table-responsive">
            <table id="client_table" class="footable table table-striped table-hover toggle-arrow-tiny default footable-loaded" >
              <thead>
                  <tr>
                      <th></th>
                      <th>Name</th>
                      <th>Used</th>
                      <th>Start</th>
                      <th>End</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
              	@foreach($promo_codes as $p)
                  <tr class="gradeX" id="promo_rec_{{$p->promo_code_id}}">
                    <td>
                      <div class="controls">
                        <div class="switch">
                          <div class="onoffswitch">
                            <input type="checkbox" onclick="ajaxPublish({{$p->promo_code_id}})" class="onoffswitch-checkbox" id="sales{{$p->promo_code_id}}" name="sales{{$p->promo_code_id}}" data-toggle="collapse" data-target="#demo" value="1" @if($p->hidden==0) checked @endif>
                            <label class="onoffswitch-label" for="sales{{$p->promo_code_id}}">
                              <span class="onoffswitch-inner"></span>
                              <span class="onoffswitch-switch"></span>
                            </label>
                          </div>
                        </div>
                        </div>
                    </td>
                    <td><a href="{{ route('cms_promo_code_details_path', $p->promo_code_id) }}">{{$p->name}}</a>@if(strtotime($p->end_date) < strtotime(date("Y/m/d"))) <span class="label label-danger b_radius" style="position:relative; left:5px;">Expired</span> @endif</td>
                    <td>{{$p->use_number}}</td>
                    <td>{{ date("d M Y", strtotime($p->start_date))}}</td>
                    <td>{{ date("d M Y", strtotime($p->end_date))}}</td>
                    <td>
                      <button type="button" id='{{$p->promo_code_id}}' class="edit_btn delete_promo_code" title="Delete Promo Code"><i class="fa fa-trash fa-lg"></i></button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
              <form>
                  <input name="_token" type="hidden" value="{{ csrf_token() }}">
              </form>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="/cms/js/custom_functions.js"></script>
<script type="text/javascript">
    //script for Data Table
    $(document).ready(function(){
        $('#client_table').DataTable({
            ordering: true,
            paging: true,
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


  // ==================== DELETE Promo Code   =====================
  ajaxDelete('.delete_promo_code', 'delete-promo-code', 'promo_rec_');
</script>

<!-- Publishing and Unpublishing the promo code in AJAX -->
<script src="/cms/js/promo-codes/ajax-publish-promo-code.js"></script>

@endsection